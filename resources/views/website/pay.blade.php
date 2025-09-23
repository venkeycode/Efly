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
const PROJECT_ID = "611536788e4297012ef34993004d5565";
const CHAIN_IDS = [137]; // Polygon
let provider, signer;

async function restoreSession() {
  try {
    provider = await window.WalletConnectProvider.EthereumProvider.init({
      projectId: PROJECT_ID,
      chains: CHAIN_IDS,
      showQrModal: false, // don't show QR unless needed
    });
    await provider.enable();
    const ethersProvider = new ethers.BrowserProvider(provider);
    signer = await ethersProvider.getSigner();

    document.getElementById("payBtn").disabled = false;
    console.log("Restored session, wallet ready:", await signer.getAddress());
  } catch (e) {
    console.warn("No active session, require reconnect:", e);
  }
}

async function payWithToken() {
  if (!signer) {
    alert("Wallet session not active. Please reconnect.");
    return;
  }

  const token = "{{ $token }}";
  const receiver = "{{ $receiver }}";
  const amount = "{{ $amount }}";

  const abi = [
    "function transfer(address to, uint256 value) returns (bool)",
    "function decimals() view returns (uint8)"
  ];
  const contract = new ethers.Contract(token, abi, signer);
  const decimals = await contract.decimals().catch(()=>6);
  const value = ethers.parseUnits(amount, decimals);

  const tx = await contract.transfer(receiver, value);
  await tx.wait(1);

  alert("Payment done! Tx: " + tx.hash);
}

@if(!empty($savedWallet))
  restoreSession();
@endif
</script>
