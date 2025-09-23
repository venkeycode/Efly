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
<meta name="csrf-token" content="{{ csrf_token() }}">
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
@vite('resources/js/app.js')
