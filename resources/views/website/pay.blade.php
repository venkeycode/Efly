{{-- resources/views/website/pay.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pay Subscription</title>

  <!-- Bootstrap (optional) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- === UMD libs === -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.umd.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/@walletconnect/ethereum-provider@2.9.0/dist/umd/index.min.js"></script>

  <style>
    body { background:#f6f8fa; }
    .card { max-width:620px; margin:36px auto; }
    .addr { font-family: monospace; color:#0b74de; word-break: break-all; }
  </style>
</head>
<body>
  <div class="card shadow">
    <div class="card-body p-4 text-center">
      <h4 class="mb-3">Pay Subscription</h4>

      {{-- Blade-injected server variables (safe) --}}
      @php
        // fallback env values if controller didn't pass them
        $receiver = $receiver ?? env('RECEIVER_WALLET', null);
      @endphp

      @if(empty($savedWallet))
        <p class="text-danger">No wallet linked to your account.</p>
        <a href="{{ url('/customer/wallet?user_id=' . ($userId ?? '') ) }}" class="btn btn-primary btn-lg w-75">Connect Wallet</a>
      @else
        <div class="mb-3">
          <div class="small text-muted">Saved wallet</div>
          <div class="addr">{{ $savedWallet }}</div>
        </div>

        <div class="mb-3">
          <div class="small text-muted">Balance</div>
          <div id="walletBalance" class="fs-5">Loading...</div>
        </div>

        <div class="d-grid gap-2 col-8 mx-auto">
          <button id="payBtn" class="btn btn-success btn-lg" onclick="payWithToken()" disabled>
            Pay {{ $amount ?? '1' }} USDT
          </button>
          <button id="reconnectBtn" class="btn btn-outline-secondary" onclick="openConnectQr()" style="display:none;">
            Reconnect Wallet (open QR)
          </button>
        </div>

        <p id="status" class="mt-3 small text-muted">Status: idle</p>
      @endif
    </div>
  </div>

<script>
/* === Config & server-provided values (safe injection) === */
const USER_ID      = {!! json_encode($userId ?? null) !!};
const SAVED_WALLET = {!! json_encode($savedWallet ?? null) !!};
const AMOUNT       = {!! json_encode($amount ?? '1') !!};
const RETURN_URL   = {!! json_encode($return ?? null) !!};
const RECEIVER     = {!! json_encode($receiver ?? env('RECEIVER_WALLET', null)) !!};

/* WalletConnect Project ID (replace with yours if different) */
const PROJECT_ID = "611536788e4297012ef34993004d5565";

/* Default chain: BSC mainnet (56) */
const DEFAULT_CHAIN = 56;

/* ERC20 ABI */
const ERC20_ABI = [
  "function balanceOf(address) view returns (uint256)",
  "function decimals() view returns (uint8)",
  "function symbol() view returns (string)",
  "function transfer(address to, uint256 value) returns (bool)"
];

/* UI elements */
const walletBalanceEl = document.getElementById("walletBalance");
const payBtn = document.getElementById("payBtn");
const statusEl = document.getElementById("status");
const reconnectBtn = document.getElementById("reconnectBtn");

function setStatus(txt, isError=false){
  if (!statusEl) return;
  statusEl.innerText = "Status: " + txt;
  statusEl.style.color = isError ? "crimson" : "";
}

/* === Normalize WalletConnect UMD export === */
const WCEthProvider =
  window.EthereumProvider ||
  window.WalletConnectEthereumProvider ||
  (window.WalletConnectProvider && (window.WalletConnectProvider.default || window.WalletConnectProvider)) ||
  null;

// expose for debugging
window.WCEthProvider = WCEthProvider;

if (!WCEthProvider) {
  console.error("WalletConnect EthereumProvider UMD not found. WalletConnect features will fail.");
  setStatus("WalletConnect lib not loaded — check CDN/CSP/adblock.", true);
}

/* state */
let wcProvider = null;
let provider = null;   // ethers BrowserProvider
let signer = null;
let connectedAddress = null;

/* Helper: USDT contract per chain */
function getUsdtForChain(chainId){
  if (chainId === 56) return "0x55d398326f99059fF775485246999027B3197955"; // BSC USDT (18)
  if (chainId === 137) return "0xc2132D05D31c914a87C6611C10748AEb04B58e8F"; // Polygon USDT (6)
  if (chainId === 1) return "0xdAC17F958D2ee523a2206206994597C13D831ec7"; // ETH USDT (6)
  return null;
}

/* Try silently restoring an active WalletConnect session (no QR) */
async function restoreSession() {
  if (!WCEthProvider) return;
  setStatus("restoring wallet session...");
  try {
    wcProvider = await WCEthProvider.init({
      projectId: PROJECT_ID,
      chains: [DEFAULT_CHAIN],
      showQrModal: false,
      // RPC map so provider doesn't hit relay rpc.walletconnect.org
      rpcMap: {
        56: "https://bsc-dataseed.binance.org/",
        137: "https://polygon-rpc.com",
        1: "https://rpc.ankr.com/eth"
      },
      optionalChains: [137,1]
    });

    // enable only resolves if session exists & is valid
    await wcProvider.enable();

    provider = new ethers.BrowserProvider(wcProvider);
    signer = await provider.getSigner();
    connectedAddress = await signer.getAddress();

    // If saved wallet in DB differs from connected wallet, warn and disable pay
    if (SAVED_WALLET && connectedAddress && connectedAddress.toLowerCase() !== SAVED_WALLET.toLowerCase()) {
      setStatus("Connected account differs from saved wallet. Reconnect with correct wallet.", true);
      if (reconnectBtn) reconnectBtn.style.display = "inline-block";
      if (payBtn) payBtn.disabled = true;
      return;
    }

    setStatus("connected: " + connectedAddress);
    if (payBtn) payBtn.disabled = false;
    if (reconnectBtn) reconnectBtn.style.display = "none";

    // start balance refresh
    await refreshBalance();
    try { provider.on("block", () => refreshBalance()); } catch(e){}

  } catch (err) {
    console.warn("restoreSession failed:", err);
    setStatus("no active wallet session");
    if (reconnectBtn) reconnectBtn.style.display = "inline-block";
    if (payBtn) payBtn.disabled = true;
  }
}

/* Open WalletConnect QR for user to scan (Trust Wallet) */
async function openConnectQr() {
  if (!WCEthProvider) { alert("WalletConnect library not loaded"); return; }
  try {
    setStatus("opening QR...");
    wcProvider = await WCEthProvider.init({
      projectId: PROJECT_ID,
      chains: [DEFAULT_CHAIN],
      showQrModal: true,
      rpcMap: {
        56: "https://bsc-dataseed.binance.org/",
        137: "https://polygon-rpc.com",
        1: "https://rpc.ankr.com/eth"
      },
      optionalChains: [137,1]
    });
    await wcProvider.enable();

    provider = new ethers.BrowserProvider(wcProvider);
    signer = await provider.getSigner();
    connectedAddress = await signer.getAddress();

    // Save the connected wallet to backend (if user ID is present)
    if (USER_ID) {
      try {
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        await fetch("/customer/wallet/save", {
          method: "POST",
          headers: {"Content-Type":"application/json","X-CSRF-TOKEN":csrf},
          body: JSON.stringify({ user_id: USER_ID, wallet_address: connectedAddress })
        });
      } catch(e){ console.warn("save wallet failed:", e); }
    }

    setStatus("connected: " + connectedAddress);
    if (payBtn) payBtn.disabled = false;
    if (reconnectBtn) reconnectBtn.style.display = "none";
    await refreshBalance();
    try { provider.on("block", () => refreshBalance()); } catch(e){}
  } catch (err) {
    console.error("openConnectQr failed:", err);
    setStatus("connect failed: " + (err.message || err), true);
  }
}

/* Refresh balances (native + token) - robust */
async function refreshBalance(){
  if (!provider || !connectedAddress) {
    if (walletBalanceEl) walletBalanceEl.innerText = "—";
    return;
  }
  setStatus("refreshing balance...");

  // native
  let nativeDisplay = "—";
  try {
    const balWei = await provider.getBalance(connectedAddress);
    const network = await provider.getNetwork();
    const nativeSymbol = network.chainId === 56 ? "BNB" : network.chainId === 137 ? "MATIC" : network.chainId === 1 ? "ETH" : "NATIVE";
    nativeDisplay = parseFloat(ethers.formatEther(balWei)).toFixed(6) + " " + nativeSymbol;
  } catch (e) {
    console.warn("native balance failed:", e);
    nativeDisplay = "Error";
  }

  // token
  let tokenDisplay = "—";
  try {
    const network = await provider.getNetwork();
    const tokenAddr = getUsdtForChain(Number(network.chainId)) || window.USDT_CONTRACT || null;
    if (!tokenAddr) {
      tokenDisplay = "No token configured";
    } else {
      const code = await provider.getCode(tokenAddr).catch(()=> "0x");
      if (!code || code === "0x" || code === "0x0") {
        tokenDisplay = "Token not on chain";
      } else {
        const token = new ethers.Contract(tokenAddr, ERC20_ABI, provider);

        // safe decimals
        let decimals = 18;
        try { decimals = Number(await token.decimals()) || decimals; } catch(e){
          if (network.chainId === 137 || network.chainId === 1) decimals = 6;
          if (network.chainId === 56) decimals = 18;
        }

        let symbol = "USDT";
        try { symbol = await token.symbol(); } catch(e){}

        let raw = null;
        try { raw = await token.balanceOf(connectedAddress); } catch(e){ raw = null; console.warn("balanceOf failed:", e); }

        if (raw === null) tokenDisplay = "0 " + symbol;
        else {
          const formatted = ethers.formatUnits(raw, decimals);
          tokenDisplay = parseFloat(formatted).toFixed(decimals === 6 ? 2 : 2) + " " + symbol;
        }
      }
    }
  } catch (e) {
    console.error("token balance failed:", e);
    tokenDisplay = "Error";
  }

  if (walletBalanceEl) walletBalanceEl.innerText = `${nativeDisplay}  |  ${tokenDisplay}`;
  setStatus("balance updated");
}

/* Pay: send USDT transfer (user signs), then call backend verify */
async function payWithToken(){
  if (!SAVED_WALLET) { alert("No wallet saved for this user. Please connect first."); return; }
  if (!signer) { alert("Wallet session not active. Please reconnect (QR)."); return; }
  if (!RECEIVER) { alert("Receiver wallet not configured on server."); return; }

  setStatus("Preparing payment...");

  try {
    const net = await provider.getNetwork();
    const chainId = Number(net.chainId);
    const tokenAddr = getUsdtForChain(chainId) || window.USDT_CONTRACT;
    if (!tokenAddr) {
      alert("USDT token not configured for this chain. Please switch network.");
      setStatus("Token not configured", true);
      return;
    }

    const code = await provider.getCode(tokenAddr).catch(()=> "0x");
    if (!code || code === "0x" || code === "0x0") {
      alert("USDT token contract not found on this chain. Switch to BSC (or correct network).");
      setStatus("Token missing on chain", true);
      return;
    }

    const token = new ethers.Contract(tokenAddr, ERC20_ABI, provider);
    const tokenWithSigner = token.connect(signer);

    // decimals
    let decimals = 18;
    try { decimals = Number(await token.decimals()); } catch(e){
      if (chainId === 137 || chainId === 1) decimals = 6;
      if (chainId === 56) decimals = 18;
    }

    const want = ethers.parseUnits(String(AMOUNT), decimals);

    // token balance
    const bal = await token.balanceOf(connectedAddress);
    if (bal < want) {
      alert(`Insufficient token balance. Have ${ethers.formatUnits(bal, decimals)}, need ${AMOUNT}`);
      setStatus("Insufficient token", true);
      return;
    }

    // gas estimate + native balance check
    let gasLimit;
    try { gasLimit = await tokenWithSigner.estimateGas.transfer(RECEIVER, want); } catch(e) { gasLimit = 150000n; }
    const feeData = await provider.getFeeData();
    const gasPriceRaw = feeData.maxFeePerGas ?? feeData.gasPrice ?? ethers.parseUnits("5", "gwei");
    const gasPrice = BigInt(gasPriceRaw.toString());
    const estimatedFee = gasPrice * BigInt(gasLimit);
    const nativeBal = await provider.getBalance(connectedAddress);
    if (nativeBal < estimatedFee) {
      alert("Insufficient native token (BNB) to pay gas. Please top up BNB.");
      setStatus("Insufficient native for gas", true);
      return;
    }

    // send transfer
    setStatus("Please confirm transfer in your wallet...");
    const tx = await tokenWithSigner.transfer(RECEIVER, want);
    setStatus("Tx sent: " + tx.hash + " — waiting 1 confirmation...");
    await tx.wait(1);

    // call Laravel verify API
    setStatus("Verifying transaction with server...");
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrf = csrfMeta ? csrfMeta.getAttribute('content') : null;
    const verifyResp = await fetch("/api/payment/verify-token", {
      method: "POST",
      headers: { "Content-Type":"application/json", ...(csrf?{"X-CSRF-TOKEN":csrf}:{}) },
      body: JSON.stringify({
        user_id: USER_ID,
        token_contract: tokenAddr,
        amount: AMOUNT,
        tx_hash: tx.hash,
        from_address: connectedAddress,
        receiver: RECEIVER
      })
    });
    const verifyJson = await verifyResp.json();
    if (verifyJson.ok) {
      setStatus("Payment verified ✔");
      alert("Payment successful: " + tx.hash);
      if (RETURN_URL) {
        const u = new URL(RETURN_URL);
        u.searchParams.set("user_id", USER_ID);
        u.searchParams.set("tx", tx.hash);
        window.location.href = u.toString();
      }
    } else {
      setStatus("Verification failed", true);
      alert("Verify failed: " + (verifyJson.error || JSON.stringify(verifyJson)));
    }

  } catch (err) {
    console.error("payWithToken error:", err);
    setStatus("payment error: " + (err.message || err), true);
    alert("Payment failed: " + (err.message || err));
  }
}

/* initialize on page load if saved wallet exists */
(async function init(){
  if (!SAVED_WALLET) {
    setStatus("no saved wallet");
    return;
  }
  setStatus("initializing...");
  await restoreSession();
})();
</script>

</body>
</html>
