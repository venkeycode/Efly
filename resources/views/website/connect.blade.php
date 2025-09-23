<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Connect Wallet</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

  <div class="container text-center">
    <div class="card shadow-sm border-0 p-4" style="max-width: 400px; margin:auto;">
      <h3 class="mb-4">Connect Wallet</h3>

      <!-- ✅ Large centered button -->
      <button onclick="connectWalletConnect()" class="btn btn-primary btn-lg w-100 mb-3">
        <i class="bi bi-wallet2 me-2"></i> Connect Trust Wallet
      </button>

      <!-- ✅ Wallet info -->
      <div class="mt-3">
        <p class="mb-1"><strong>Address:</strong></p>
        <p id="walletAddr" class="text-monospace small text-muted">Not connected</p>

        <p class="mb-1"><strong>Balance:</strong></p>
        <p id="walletBalance" class="fs-5 fw-bold text-success">—</p>
      </div>
    </div>
  </div>

  @vite('resources/js/app.js')

  <script>
    window.USER_ID = "{{ $userId }}";
  </script>

  <!-- Bootstrap Icons (optional, for wallet icon) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</body>
</html>
