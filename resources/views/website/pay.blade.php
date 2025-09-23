<script>
  window.USER_ID = "{{ $userId }}";
  window.AMOUNT = "{{ $amount }}";          // amount to pay (string)
  window.SAVED_WALLET = "{{ $savedWallet ?? '' }}"; // optional: pre-saved wallet from DB
  window.RECEIVER = "{{ env('RECEIVER_WALLET') }}";
  window.RETURN_URL = "{{ $returnUrl ?? '' }}";
</script>

@vite('resources/js/app.js')
