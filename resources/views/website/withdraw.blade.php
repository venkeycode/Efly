{{-- resources/views/admin/withdraw-approve.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin — Approve Withdrawal</title>

  <!-- Bootstrap (optional but makes it look okay) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f6f8fa; padding:30px; }
    .card { max-width:900px; margin: 0 auto; padding:24px; border-radius:10px; }
    .addr { font-family: monospace; color:#0b74de; word-break:break-all; }
    .big { font-size:1.4rem; font-weight:600; }
    .status { margin-top:12px; color:#666; }
    .btn-process { background:#2d7a4b; color:#fff; padding:12px 18px; font-size:1rem; border-radius:8px; }
  </style>
</head>
<body>
  <div class="card shadow-sm bg-white">
    <div class="d-flex justify-content-between align-items-start mb-3">
      <div>
        <h3>Admin — Process Withdrawal</h3>
        <div class="small text-muted">Use this page to send funds from admin hotwallet to customer wallet (manual admin approval).</div>
      </div>
      <div>
        <button id="reconnectBtn" class="btn btn-outline-secondary" style="display:none;">Reconnect Wallet</button>
      </div>
    </div>

    @php
      // token contract you want to use for withdrawal (default BSC USDT)
      $usdtBsc = env('USDT_CONTRACT_BSC', '0x55d398326f99059fF775485246999027B3197955');
      $receiver = $withdraw->wallet_address;
    @endphp

    <div class="row">
      <div class="col-md-6">
        <h5>Request</h5>
        <p><strong>ID:</strong> {{ $withdraw->id }}</p>
        <p><strong>Customer:</strong> {{ $withdraw->customer->name }}</p>
        <p><strong>Customer Wallet:</strong> <span class="addr">{{ $withdraw->wallet_address }}</span></p>
        <p><strong>Amount:</strong> <span class="big">{{ $withdraw->amount }} </span></p>
      </div>

      <div class="col-md-6">
        <h5>Admin Wallet</h5>
        <p><strong>Connected:</strong></p>
        <div id="walletAddr" class="addr mb-2">Not connected</div>

        <p><strong>Balance:</strong></p>
        <div id="walletBalance" class="big">Loading...</div>

        <div class="mt-3">
          <button id="connectBtn" class="btn btn-primary me-2" onclick="(window.openConnectQr ? window.openConnectQr() : alert('Connect function not loaded'))">Connect Wallet</button>
          <button id="adminWithdrawBtn" class="btn btn-process" onclick="adminWithdraw()">Process Withdrawal</button>
        </div>

        <div id="status" class="status">Status: idle</div>
      </div>
    </div>
  </div>

  {{-- Expose values for JS --}}
  <script>
    window.WITHDRAW_ID       = {!! json_encode($withdraw->id) !!};
    window.WITHDRAW_RECEIVER = {!! json_encode($withdraw->wallet_address) !!};
    window.WITHDRAW_AMOUNT   = {!! json_encode((string)$withdraw->amount) !!}; // decimal string
    window.WITHDRAW_TOKEN    = {!! json_encode($usdtBsc) !!}; // token contract on the chain admin will use
    // Endpoint where server verifies & stores the completed withdraw record
    // Make sure this route exists and accepts POST JSON: /admin/withdraw/process
    window.WITHDRAW_ENDPOINT = {!! json_encode(route('withdraw.approve', [], false)) !!} || '/admin/withdraw/process';
    // After success, redirect url (admin list)
    window.WITHDRAW_RETURN = {!! json_encode(route('withdraw.approve', [], false)) !!} || '/admin/withdraws';
  </script>

  {{-- Load your bundled JS (expects functions: openConnectQr, fetchBalanceFromServer, payWithToken) --}}
  @vite('resources/js/app.js')

  <script>
    // adminWithdraw uses the same token-transfer flow but with admin as sender and customer as receiver.
    async function adminWithdraw() {
      try {
        // basic checks
        if (!window.openConnectQr && !window.connectWalletConnect) {
          return alert("WalletConnect script not loaded. Make sure resources/js/app.js is included via Vite.");
        }
        // require provider/session be present
        if (!window.__provider || !window.__signer) {
          // try restoring silently; if not, ask admin to connect
          if (window.restoreSession) {
            document.getElementById('status').innerText = "Status: trying to restore wallet session...";
            try { await window.restoreSession(); } catch(e){ console.warn(e); }
          }
        }

        if (!window.__provider || !window.__signer) {
          return alert("Please connect your admin wallet first (click Connect Wallet).");
        }

        const provider = window.__provider;
        const signer = window.__signer;

        const adminAddr = (await signer.getAddress()).toLowerCase();
        document.getElementById('walletAddr').innerText = adminAddr;

        // show please approve status before calling wallet
        document.getElementById('status').innerText = "Status: Please approve the transaction in your wallet app…";

        const tokenAddr = window.WITHDRAW_TOKEN;
        const receiver = (window.WITHDRAW_RECEIVER || '').toLowerCase();
        const amountStr = String(window.WITHDRAW_AMOUNT || "0");

        if (!tokenAddr || !receiver || !amountStr) {
          alert("Missing withdraw configuration (token/receiver/amount).");
          return;
        }

        // call the client-side payWithToken logic but adapted:
        // - reuse ERC20 ABI from app.js if available, otherwise redeclare minimal ABI
        const ERC20_ABI = [
          "function balanceOf(address) view returns (uint256)",
          "function decimals() view returns (uint8)",
          "function symbol() view returns (string)",
          "function transfer(address to, uint256 value) returns (bool)"
        ];

        const tokenContract = new ethers.Contract(tokenAddr, ERC20_ABI, provider);
        const tokenWithSigner = tokenContract.connect(signer);

        // read decimals (safe)
        let decimals = 18;
        try { decimals = Number(await tokenContract.decimals()); } catch(e) { console.warn("decimals read failed, defaulting", e); }

        // amount -> smallest unit
        const amountWei = ethers.parseUnits(amountStr, decimals);

        // check token balance of admin (optional)
        const adminBalRaw = await tokenContract.balanceOf(adminAddr);
        if (BigInt(adminBalRaw.toString()) < BigInt(amountWei.toString())) {
          alert("Admin token balance insufficient to fulfill withdrawal.");
          document.getElementById('status').innerText = "Status: insufficient token balance";
          return;
        }

        // check native balance for gas
        const nativeBal = await provider.getBalance(adminAddr);
        // estimate gas for transfer
        let gasLimit;
        try {
          gasLimit = await tokenWithSigner.estimateGas.transfer(receiver, amountWei);
          gasLimit = BigInt(gasLimit.toString());
        } catch (e) {
          console.warn("estimateGas failed, fallback to 150000", e);
          gasLimit = 150000n;
        }
        const feeData = await provider.getFeeData();
        let gasPrice = feeData.maxFeePerGas ?? feeData.gasPrice ?? ethers.parseUnits("5", "gwei");
        gasPrice = BigInt(gasPrice.toString());
        const estimatedFee = gasPrice * gasLimit;
        const buffer = BigInt(ethers.parseUnits("0.0005", "ether").toString());
        if (BigInt(nativeBal.toString()) < (estimatedFee + buffer)) {
          alert("Admin native (gas) balance insufficient. Please top up native token (BNB/ETH/MATIC depending on chain).");
          document.getElementById('status').innerText = "Status: insufficient native for gas";
          return;
        }

        // ask admin to confirm in wallet
        document.getElementById('status').innerText = "Status: sending transfer — confirm in wallet…";
        let tx;
        try {
          tx = await tokenWithSigner.transfer(receiver, amountWei);
        } catch (err) {
          console.error("transfer failed:", err);
          if ((err && err.code === 4001) || (err && /user rejected/i.test(err.message))) {
            document.getElementById('status').innerText = "Status: transaction rejected by user";
            alert("You rejected the transaction in your wallet.");
            return;
          }
          document.getElementById('status').innerText = "Status: transfer failed";
          alert("Transfer failed: " + (err.message || err));
          return;
        }

        document.getElementById('status').innerText = "Status: Transaction sent. Waiting 1 confirmation...";
        try { await tx.wait(1); } catch (e) { console.warn("wait error", e); }

        // POST to server to store withdrawal as processed
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrf = csrfMeta ? csrfMeta.getAttribute('content') : null;
        const payload = {
          withdraw_id: window.WITHDRAW_ID,
          tx_hash: tx.hash,
          admin_address: adminAddr,
          to_address: receiver,
          amount: amountStr,
          token_contract: tokenAddr
        };

        document.getElementById('status').innerText = "Status: Verifying and storing on server...";
        const resp = await fetch(window.WITHDRAW_ENDPOINT, {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            ...(csrf ? { "X-CSRF-TOKEN": csrf } : {})
          },
          body: JSON.stringify(payload)
        });
        const j = await resp.json();
        if (j.ok) {
          document.getElementById('status').innerText = "Status: Withdrawal completed ✔";
          alert("Withdrawal processed: " + tx.hash);
          // redirect to admin list if provided
          setTimeout(()=> {
            if (window.WITHDRAW_RETURN) location.href = window.WITHDRAW_RETURN;
          }, 900);
        } else {
          document.getElementById('status').innerText = "Status: Server verification failed";
          console.error("server verify error", j);
          alert("Server failed to record withdrawal: " + (j.error || JSON.stringify(j)));
        }

      } catch (err) {
        console.error("adminWithdraw error:", err);
        document.getElementById('status').innerText = "Status: error - " + (err.message || err);
        alert("Error processing withdrawal: " + (err.message || err));
      }
    }

    // wire small UI behaviour: refresh server-side balance when app.js signals refresh
    document.addEventListener('DOMContentLoaded', function () {
      // if your app.js exposes refresh fetch, call it
      if (window.fetchBalanceFromServer) {
        // refresh initial display using server-side balance fetch (for admin address if saved)
        try { window.fetchBalanceFromServer(); } catch(e){}
      }
    });
  </script>

</body>
</html>
