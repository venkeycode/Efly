<!doctype html>
<html>
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.umd.min.js"></script>
</head>
<body>
  <h3>Pay Subscription</h3>

  @if(empty($savedWallet))
      <p>No wallet connected.</p>
      <a href="{{ url('/customer/wallet?user_id='.$userId) }}" class="btn btn-primary">
        Connect Wallet
      </a>
  @else
      <p><strong>Wallet:</strong> {{ $savedWallet }}</p>
      <button onclick="payWithToken()" id="payBtn" class="btn btn-success">
        Pay {{ $amount }} USDT
      </button>
  @endif

  <script>
    const TOKEN    = {!! json_encode($token) !!};
    const RECEIVER = {!! json_encode($receiver) !!};
    const AMOUNT   = {!! json_encode($amount) !!};
    const USER_ID  = {!! json_encode($userId) !!};
    const SAVED_WALLET = {!! json_encode($savedWallet) !!};

    async function payWithToken() {
      try {
        if (!SAVED_WALLET) {
          alert("No wallet connected. Please connect first.");
          return;
        }

        // Assume user is already connected in Trust Wallet app session
        // You still need provider/signer from WalletConnect (opened earlier)
        if (!window.signer) {
          alert("Wallet session not active. Please reconnect.");
          return;
        }

        const abi = [
          "function transfer(address to, uint256 value) returns (bool)",
          "function decimals() view returns (uint8)"
        ];
        const contract = new ethers.Contract(TOKEN, abi, signer);

        const decimals = await contract.decimals().catch(() => 6);
        const value = ethers.parseUnits(String(AMOUNT), decimals);

        const tx = await contract.connect(signer).transfer(RECEIVER, value);
        await tx.wait(1);

        // ✅ Verify in backend
        const res = await fetch("/wallet/verify-token", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            user_id: USER_ID,
            token_contract: TOKEN,
            amount: AMOUNT,
            tx_hash: tx.hash,
            from_address: SAVED_WALLET,
            receiver: RECEIVER
          })
        });
        const j = await res.json();
        if (j.ok) {
          alert("Payment success ✔ Tx: " + tx.hash);
          window.location.href = "{{ $return ?? url('/') }}";
        } else {
          alert("Verify failed: " + (j.error || JSON.stringify(j)));
        }
      } catch (e) {
        console.error("payWithToken error:", e);
        alert("Payment failed: " + (e.message || e));
      }
    }
  </script>
</body>
</html>
