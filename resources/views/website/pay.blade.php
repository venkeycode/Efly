<button onclick="payWithToken()" id="payBtn">Pay Subscription</button>

<script>
async function payWithToken() {
  const token = "{{ $token }}";
  const receiver = "{{ $receiver }}";
  const amount = "{{ $amount }}";

  const contract = new ethers.Contract(token, [
    "function transfer(address to, uint256 value) returns (bool)"
  ], signer);

  const decimals = 6; // USDT Polygon
  const value = ethers.parseUnits(amount, decimals);

  const tx = await contract.connect(signer).transfer(receiver, value);
  await tx.wait(1);

  // send tx hash to backend for verification
  const resp = await fetch("/wallet/verify-token", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
      user_id: "{{ $userId }}",
      token_contract: token,
      amount: amount,
      tx_hash: tx.hash,
      from_address: connected,
      receiver: receiver
    })
  });
  const j = await resp.json();
  console.log("Verify:", j);
}
</script>
