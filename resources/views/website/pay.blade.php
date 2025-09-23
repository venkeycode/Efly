<!doctype html><html><head>
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Pay</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head><body class="d-flex align-items-center justify-content-center vh-100 bg-light">
<div class="card p-4" style="max-width:480px; width:100%">
  <h4 class="mb-3 text-center">Pay {{ $amount }} MATIC</h4>

  @if(empty($savedWallet))
    <div class="text-center mb-3">
      <p class="text-danger">No wallet connected for this user.</p>
      <a href="{{ url('/wallet/connect') . '?user_id=' . $userId . '&return=' . urlencode(request()->fullUrl()) }}" class="btn btn-outline-primary btn-lg w-100">Connect Wallet</a>
    </div>
  @else
    <div class="mb-2">
      <strong>Saved wallet:</strong>
      <div class="text-monospace small text-muted">{{ $savedWallet }}</div>
    </div>

    <div class="d-grid gap-2">
      <button id="connectBtn" class="btn btn-outline-secondary">(Re)Connect Wallet</button>
      <button id="payBtn" class="btn btn-success btn-lg" disabled>Pay {{ $amount }} MATIC</button>
    </div>

    <div class="mt-3 text-center small" id="status">Status: waiting</div>
  @endif
</div>

<script type="module">
import { ethers } from "https://unpkg.com/ethers@6.11.1/dist/ethers.min.js";
import EthereumProvider from "https://unpkg.com/@walletconnect/ethereum-provider@2.7.2/dist/ethereum-provider.min.js";

const userId = "{{ $userId }}";
const amount = "{{ $amount }}";
const savedWallet = "{{ $savedWallet ?? '' }}".toLowerCase();
const receiver = "{{ env('RECEIVER_WALLET') }}";
const statusEl = document.getElementById('status');
let provider, signer, addr;

function setStatus(t, err=false){ statusEl.innerText = 'Status: ' + t; statusEl.style.color = err? 'crimson' : ''; }

async function connect() {
  try {
    setStatus('connecting...');
    const ethProvider = await EthereumProvider.init({ projectId:'611536788e4297012ef34993004d5565', chains:[137], showQrModal:true });
    await ethProvider.enable();
    provider = new ethers.BrowserProvider(ethProvider);
    signer = await provider.getSigner();
    addr = (await signer.getAddress()).toLowerCase();
    setStatus('connected ' + addr);

    if (addr !== savedWallet) {
      setStatus('Connected wallet does not match saved wallet. Please connect the saved address.', true);
      document.getElementById('payBtn').disabled = true;
      return;
    }
    document.getElementById('payBtn').disabled = false;
  } catch (e) {
    console.error(e);
    setStatus('connect failed: ' + (e.message||e), true);
  }
}

async function pay() {
  try {
    setStatus('sending tx via wallet...');
    const tx = await signer.sendTransaction({ to: receiver, value: ethers.parseEther(amount) });
    setStatus('tx sent: ' + tx.hash + '. waiting 1 confirmation...');
    await tx.wait(1);

    setStatus('verifying...');
    const res = await fetch('/api/payment/verify', {
      method:'POST',
      headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},
      body: JSON.stringify({ user_id: userId, amount: amount, tx_hash: tx.hash, from_address: addr })
    });
    const j = await res.json();
    if (j.ok) {
      setStatus('Payment verified ✔');
      // redirect back to CI if 'return' param was provided in query; otherwise show success
      const urlParams = new URLSearchParams(window.location.search);
      const ret = urlParams.get('return');
      if (ret) {
        const u = new URL(ret);
        u.searchParams.set('user_id', userId);
        u.searchParams.set('tx', tx.hash);
        window.location.href = u.toString();
      } else {
        alert('Payment verified: ' + tx.hash);
      }
    } else {
      setStatus('verification failed: ' + (j.error||'unknown'), true);
      alert('Verify failed: ' + JSON.stringify(j));
    }
  } catch (e) {
    console.error(e);
    setStatus('Payment error: ' + (e.message||e), true);
  }
}

document.getElementById('connectBtn')?.addEventListener('click', connect);
document.getElementById('payBtn')?.addEventListener('click', pay);
</script>
</body></html>
