<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Wallet Payment</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- ✅ Load ethers.js & WalletConnect provider (UMD builds) -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@walletconnect/ethereum-provider@2.9.0/dist/umd/index.min.js"></script>
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

<div class="container text-center">
  <div class="card shadow p-4" style="max-width:400px; margin:auto;">
    <h3 class="mb-3">Pay with Wallet</h3>

    <button onclick="connectWalletConnect()" class="btn btn-primary w-100 mb-3">🔗 Connect Wallet</button>
    <button onclick="payWithToken()" id="payBtn" class="btn btn-success w-100 mb-3" disabled>💸 Pay Subscription</button>

    <p><strong>Address:</strong> <span id="walletAddr">Not connected</span></p>
    <p><strong>Balance:</strong> <span id="walletBalance">—</span></p>
    <p id="status" class="fw-bold text-muted">Status: idle</p>
  </div>
</div>

<script>
  // Config
  const PROJECT_ID = "611536788e4297012ef34993004d5565"; // WalletConnect Cloud
  const CHAIN_IDS = [137]; // Polygon
  const USDT_CONTRACT = "0xc2132D05D31c914a87C6611C10748AEb04B58e8F"; // Polygon USDT
  const RECEIVER = "{{ env('RECEIVER_WALLET') }}"; // Laravel .env receiver
  const AMOUNT = "{{ request('amount', 1) }}"; // from query
  const USER_ID = "{{ request('user_id') }}";

  // State
  let provider, signer, connectedAddress;

  function setStatus(msg, error=false){
    document.getElementById("status").innerText = "Status: " + msg;
    document.getElementById("status").style.color = error ? "crimson" : "black";
  }

  async function connectWalletConnect(){
    try {
      setStatus("connecting...");
      const ethProvider = await window.EthereumProvider.init({
        projectId: PROJECT_ID,
        chains: CHAIN_IDS,
        showQrModal: true,
      });
      await ethProvider.enable();

      provider = new ethers.BrowserProvider(ethProvider);
      signer = await provider.getSigner();
      connectedAddress = await signer.getAddress();

      document.getElementById("walletAddr").innerText = connectedAddress;
      document.getElementById("payBtn").disabled = false;
      setStatus("connected: " + connectedAddress);

      refreshBalance();
      provider.on("block", refreshBalance);
    } catch(err){
      console.error("Connect failed:", err);
      setStatus("connect failed: " + err.message, true);
    }
  }

  async function refreshBalance(){
    try {
      const balWei = await provider.getBalance(connectedAddress);
      const balMatic = ethers.formatEther(balWei);

      // ERC20 (USDT)
      const erc20 = new ethers.Contract(USDT_CONTRACT, [
        "function balanceOf(address) view returns (uint256)",
        "function decimals() view returns (uint8)",
        "function symbol() view returns (string)"
      ], provider);

      const [raw, decimals, symbol] = await Promise.all([
        erc20.balanceOf(connectedAddress),
        erc20.decimals(),
        erc20.symbol()
      ]);

      const balToken = ethers.formatUnits(raw, decimals);
      document.getElementById("walletBalance").innerText =
        parseFloat(balMatic).toFixed(4) + " MATIC | " + balToken + " " + symbol;
    } catch(e){
      console.error("refreshBalance error:", e);
      document.getElementById("walletBalance").innerText = "Error";
    }
  }

  async function payWithToken(){
    try {
      setStatus("sending payment...");
      const erc20 = new ethers.Contract(USDT_CONTRACT, [
        "function transfer(address to, uint256 value) returns (bool)",
        "function decimals() view returns (uint8)"
      ], signer);

      const decimals = await erc20.decimals();
      const value = ethers.parseUnits(AMOUNT, decimals);

      const tx = await erc20.transfer(RECEIVER, value);
      setStatus("Tx sent: " + tx.hash);

      await tx.wait(1);
      setStatus("Payment confirmed ✔");

      // Call Laravel verify API
      await fetch("/api/payment/verify-token", {
        method: "POST",
        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({
          user_id: USER_ID,
          token_contract: USDT_CONTRACT,
          amount: AMOUNT,
          tx_hash: tx.hash,
          from_address: connectedAddress,
          receiver: RECEIVER
        })
      });

    } catch(err){
      console.error("payWithToken error:", err);
      setStatus("payment error: " + err.message, true);
    }
  }
</script>
<!-- ethers.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.umd.min.js"></script>

<!-- WalletConnect Ethereum Provider -->
<script src="https://cdn.jsdelivr.net/npm/@walletconnect/ethereum-provider@2.9.0/dist/umd/index.min.js"></script>

<script>
  // normalize WalletConnect provider export
  const WCEthProvider = window.EthereumProvider
    || window.WalletConnectEthereumProvider
    || (window.WalletConnectProvider ? window.WalletConnectProvider.default : null);

  if (!WCEthProvider) {
    console.error("WalletConnect provider not found in globals.");
  }

  async function connectWalletConnect() {
    try {
      const ethProvider = await WCEthProvider.init({
        projectId: "611536788e4297012ef34993004d5565", // your WalletConnect Cloud ID
        chains: [137], // Polygon
        showQrModal: true,
      });

      await ethProvider.enable();

      const provider = new ethers.BrowserProvider(ethProvider);
      const signer = await provider.getSigner();
      const address = await signer.getAddress();

      document.getElementById("walletAddr").innerText = address;
      document.getElementById("status").innerText = "Connected ✔ " + address;
    } catch (err) {
      console.error("Connect failed:", err);
      document.getElementById("status").innerText = "Error: " + err.message;
    }
  }
</script>
</body>
</html>
