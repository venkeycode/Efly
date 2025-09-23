@if(empty($savedWallet))
  <a href="{{ url('/customer/wallet?user_id='.$userId) }}" class="btn btn-primary">
    Connect Wallet
  </a>
@else
  <p><strong>Wallet:</strong> {{ $savedWallet }}</p>
  <button onclick="payWithToken()" id="payBtn" class="btn btn-success" disabled>
    Pay {{ $amount }} USDT
  </button>
@endif

<script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.umd.min.js"></script>
<script src="https://unpkg.com/@walletconnect/ethereum-provider/dist/umd/index.min.js"></script>
<script>
const PROJECT_ID = "611536788e4297012ef34993004d5565"; // your WalletConnect Project ID
const CHAIN_IDS = [56]; // ✅ BSC mainnet
const USDT_CONTRACT = "0x55d398326f99059fF775485246999027B3197955"; // ✅ BSC USDT
const RECEIVER = "{{ $receiver }}"; // put your receiver wallet
const AMOUNT = "{{ $amount }}"; // e.g. "1.0"

let provider, signer;

async function restoreSession() {
  try {
    provider = await window.WalletConnectProvider.EthereumProvider.init({
      projectId: PROJECT_ID,
      chains: CHAIN_IDS,
      showQrModal: false,
    });
    await provider.enable();

    const ethersProvider = new ethers.BrowserProvider(provider);
    signer = await ethersProvider.getSigner();

    document.getElementById("payBtn").disabled = false;
    console.log("Wallet restored:", await signer.getAddress());
  } catch (e) {
    console.warn("No active session. User must reconnect:", e);
  }
}

async function payWithToken() {
  if (!signer) {
    alert("Wallet session not active. Please reconnect.");
    return;
  }

  try {
    const abi = [
      "function transfer(address to, uint256 value) returns (bool)",
      "function decimals() view returns (uint8)"
    ];

    const contract = new ethers.Contract(USDT_CONTRACT, abi, signer);
    const decimals = await contract.decimals().catch(()=>18);

    const value = ethers.parseUnits(AMOUNT, decimals);

    console.log(`Sending ${AMOUNT} USDT to ${RECEIVER}...`);

    const tx = await contract.transfer(RECEIVER, value);
    setStatus("Tx sent: " + tx.hash);

    await tx.wait(1);
    alert("✅ Payment success. Tx: " + tx.hash);

    // Optionally verify with Laravel
    await fetch("/api/payment/verify-token", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        user_id: "{{ $userId }}",
        token_contract: USDT_CONTRACT,
        amount: AMOUNT,
        tx_hash: tx.hash,
        from_address: await signer.getAddress(),
        receiver: RECEIVER
      })
    });

  } catch (err) {
    console.error("Payment failed:", err);
    alert("❌ Payment error: " + (err.message || err));
  }
}

@if(!empty($savedWallet))
  restoreSession();
@endif
</script>
