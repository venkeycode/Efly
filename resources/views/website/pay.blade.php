<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Wallet Payment</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Ethers + WalletConnect -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.6.0/ethers.umd.min.js"></script>
  <script src="https://unpkg.com/@walletconnect/ethereum-provider/dist/umd/index.min.js"></script>
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-light">
  <div class="card p-4 shadow-sm text-center" style="max-width: 400px">
    <h3 class="mb-3">Connect Wallet</h3>

    <button class="btn btn-primary w-100 mb-3" onclick="connectWalletConnect()">Connect Trust Wallet</button>

    <div class="mb-2">
      <strong>Address:</strong>
      <p id="walletAddr" class="text-monospace small text-muted">Not connected</p>
    </div>

    <div class="mb-2">
      <strong>Balance:</strong>
      <p id="walletBalance">—</p>
    </div>

    <button id="payBtn" class="btn btn-success w-100" onclick="payWithToken()" disabled>
      Pay Subscription
    </button>
    <p id="status" class="mt-3 small text-muted"></p>
  </div>

  <script>
    // Config from backend
    window.USER_ID = "{{ $userId }}";
    window.AMOUNT = "1.0"; // subscription amount
    window.RECEIVER = "{{ env('RECEIVER_WALLET') }}";
    window.RETURN_URL = "https://earnnfly.com/New/user/index.php/home";
    window.USDT_CONTRACT = "0xc2132D05D31c914a87C6611C10748AEb04B58e8F"; // USDT Polygon

    let provider, signer, connectedAddress;
    const ERC20_ABI = [
      "function balanceOf(address) view returns (uint256)",
      "function decimals() view returns (uint8)",
      "function symbol() view returns (string)",
      "function transfer(address to, uint256 value) returns (bool)"
    ];

    function setStatus(msg, error=false) {
      const el = document.getElementById("status");
      el.innerText = "Status: " + msg;
      el.style.color = error ? "crimson" : "";
    }

    async function connectWalletConnect() {
      try {
        setStatus("Connecting...");
        const ethProvider = await window.WalletConnectProvider.EthereumProvider.init({
          projectId: "611536788e4297012ef34993004d5565", // your WC project id
          chains: [137], // Polygon
          showQrModal: true,
        });
        await ethProvider.enable();

        provider = new ethers.BrowserProvider(ethProvider);
        signer = await provider.getSigner();
        connectedAddress = await signer.getAddress();

        document.getElementById("walletAddr").innerText = connectedAddress;
        document.getElementById("payBtn").disabled = false;
        setStatus("Connected");

        refreshBalance();
      } catch (err) {
        console.error(err);
        setStatus("Connect failed: " + err.message, true);
      }
    }

    async function refreshBalance() {
      if (!provider || !connectedAddress) return;
      try {
        const balWei = await provider.getBalance(connectedAddress);
        const bal = ethers.formatEther(balWei);

        const token = new ethers.Contract(window.USDT_CONTRACT, ERC20_ABI, provider);
        const decimals = await token.decimals();
        const symbol = await token.symbol();
        const raw = await token.balanceOf(connectedAddress);
        const tokenBal = ethers.formatUnits(raw, decimals);

        document.getElementById("walletBalance").innerText =
          `${parseFloat(bal).toFixed(4)} MATIC | ${tokenBal} ${symbol}`;
      } catch (e) {
        console.error("refreshBalance failed:", e);
        document.getElementById("walletBalance").innerText = "Error";
      }
    }

    async function payWithToken() {
      try {
        if (!signer) { alert("Please connect wallet first."); return; }

        const token = new ethers.Contract(window.USDT_CONTRACT, ERC20_ABI, signer);
        const decimals = await token.decimals();
        const want = ethers.parseUnits(window.AMOUNT, decimals);

        const bal = await token.balanceOf(connectedAddress);
        if (bal < want) {
          alert("Insufficient USDT");
          return;
        }

        setStatus("Sending payment...");
        const tx = await token.transfer(window.RECEIVER, want);
        setStatus("Tx sent: " + tx.hash);
        await tx.wait(1);

        setStatus("Verifying...");
        const resp = await fetch("/api/payment/verify-token", {
          method: "POST",
          headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
          body: JSON.stringify({
            user_id: window.USER_ID,
            token_contract: window.USDT_CONTRACT,
            amount: window.AMOUNT,
            tx_hash: tx.hash,
            from_address: connectedAddress,
            receiver: window.RECEIVER
          })
        });
        const j = await resp.json();
        if (j.ok) {
          setStatus("Payment verified ✔");
          window.location.href = window.RETURN_URL;
        } else {
          setStatus("Verification failed", true);
          alert("Verify failed: " + (j.error || JSON.stringify(j)));
        }
      } catch (err) {
        console.error("payWithToken error:", err);
        setStatus("Payment error: " + err.message, true);
        alert("Payment error: " + err.message);
      }
    }
  </script>
</body>
</html>
{{-- <p id="walletAddr">Not connected</p>
<p id="walletBalance">—</p>
<div id="status">Status: waiting</div>
<button onclick="connectWalletConnect()">Connect Wallet</button>
<button id="payBtn" onclick="paySubscription()" disabled>Pay</button>
<script>
  window.USER_ID = "{{ $userId }}";
  window.AMOUNT = "{{ $amount }}";          // amount to pay (string)
  window.SAVED_WALLET = "{{ $savedWallet ?? '' }}"; // optional: pre-saved wallet from DB
  window.RECEIVER = "0xE77209d4f81121615953b6292DD0974f9883A1d9";
  window.RETURN_URL = "{{ $returnUrl ?? '' }}";
</script> --}}
{{-- <meta name="csrf-token" content="{{ csrf_token() }}">
<script>
  window.USER_ID = "{{ $userId }}";
  window.AMOUNT = "1.0"; // Example: 1 USDT
  window.RECEIVER = "0xYourReceiverWalletHere";
  // ✅ BSC USDT contract
  window.USDT_CONTRACT = "0x55d398326f99059fF775485246999027B3197955";
  window.RETURN_URL = "{{ $return ?? '' }}";
</script>
<button onclick="connectWalletConnect()">Connect Wallet</button>
<div>Address: <span id="walletAddr">-</span></div>
<div>Balance: <span id="walletBalance">-</span></div>
<div id="status"></div>
<button id="payBtn" onclick="payWithToken()" disabled>Pay Subscription</button>
@vite('resources/js/app.js') --}}
