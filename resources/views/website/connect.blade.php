<!doctype html>
<html>
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
  <h2>Connect Wallet</h2>
  <button onclick="connectWalletConnect()">Connect Trust Wallet</button>
  <div>Address: <span id="walletAddr">Not connected</span></div>
  <div>Balance: <span id="walletBalance">—</span></div>

  @vite('resources/js/app.js')

  <script>
    window.USER_ID = "{{ $userId }}";
  </script>
</body>
</html>
