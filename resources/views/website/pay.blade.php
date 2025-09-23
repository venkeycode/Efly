<!doctype html>
<html>
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
  <h3>Pay Page</h3>

  <button onclick="payWithToken()" id="payBtn" disabled>Pay Subscription</button>

  <!-- ✅ load ethers.js here -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ethers/6.7.0/ethers.umd.min.js"></script>

  <script>
    const TOKEN    = {!! json_encode($token ?? env('USDT_CONTRACT')) !!};
    const RECEIVER = {!! json_encode($receiver ?? env('RECEIVER_WALLET')) !!};
    const AMOUNT   = {!! json_encode($amount ?? '1.0') !!};
    const USER_ID  = {!! json_encode($userId ?? null) !!};

    async function payWithToken() {
      try {
        if (!window.signer) {
          alert("Please connect wallet first.");
          return;
        }

        const erc20abi = [
          "function transfer(address to, uint256 value) returns (bool)",
          "function decimals() view returns (uint8)"
        ];
        const contract = new ethers.Contract(TOKEN, erc20abi, signer);

        const decimals = await contract.decimals().catch(() => 6);
        const value = ethers.parseUnits(String(AMOUNT), decimals);

        const tx = await contract.connect(signer).transfer(RECEIVER, value);
        await tx.wait(1);

        // Verify on backend
        const res = await fetch("/api/payment/verify-token", {
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
            from_address: window.connectedAddress, // set on connect
            receiver: RECEIVER
          })
        });
        const j = await res.json();
        if (j.ok) {
          alert("Payment success ✔ Tx: " + tx.hash);
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
