<p id="walletAddr">Not connected</p>
<p id="walletBalance">—</p>
<div id="status">Status: waiting</div>
<button onclick="connectWalletConnect()">Connect Wallet</button>
<button id="payBtn" onclick="paySubscription()" disabled>Pay</button>
<script>
  window.USER_ID = "{{ $userId }}";
  window.AMOUNT = "{{ $amount }}";          // amount to pay (string)
  window.SAVED_WALLET = "{{ $savedWallet ?? '' }}"; // optional: pre-saved wallet from DB
  window.RECEIVER = "{{ env('RECEIVER_WALLET') }}";
  window.RETURN_URL = "{{ $returnUrl ?? '' }}";
</script>

@vite('resources/js/app.js')
