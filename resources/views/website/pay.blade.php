{{-- resources/views/website/pay.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pay Subscription</title>

  <!-- Optional Bootstrap for nicer look -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { background:#f6f8fa; }
    .card { max-width:720px; margin:36px auto; }
    .addr { font-family: monospace; color:#0b74de; word-break: break-all; }
  </style>
</head>
<body>
  <div class="card shadow">
    <div class="card-body p-4 text-center">
      <h4 class="mb-3">Pay Subscription</h4>

      @php
        // fallback server-side values if controller didn't provide
        $receiver = $receiver ?? env('RECEIVER_WALLET', null);
      @endphp

      @if(empty($savedWallet))
        <p class="text-danger">No wallet linked to your account.</p>
        <a href="{{ url('/customer/wallet?user_id=' . ($userId ?? '') ) }}" class="btn btn-primary btn-lg w-75">Connect Wallet</a>
      @else
        <div class="mb-3">
          <div class="small text-muted">Saved wallet</div>
          <div id="walletAddr" class="addr">{{ $savedWallet }}</div>
        </div>

        <div class="mb-3">
          <div class="small text-muted">Balance</div>
          <div id="walletBalance" class="fs-5">Loading...</div>
        </div>

        <div class="d-grid gap-2 col-8 mx-auto">
          <button id="payBtn" class="btn btn-success btn-lg" onclick="payWithToken()" disabled>
            Pay {{ $amount ?? '1' }} USDT
          </button>
          <button id="reconnectBtn" class="btn btn-outline-secondary" onclick="openConnectQr()" style="display:none;">
            Reconnect Wallet (open QR)
          </button>
        </div>

        <p id="status" class="mt-3 small text-muted">Status: idle</p>
      @endif
    </div>
  </div>

  <!-- Inject JS config for app.js BEFORE loading Vite bundle -->
<script>
  window.USER_ID      = {!! json_encode($userId ?? null) !!};
  window.SAVED_WALLET = {!! json_encode($savedWallet ?? null) !!};
  window.AMOUNT       = {!! json_encode($amount ?? '1') !!};
  window.RECEIVER     = {!! json_encode($receiver ?? env('RECEIVER_WALLET', null)) !!};
  window.RETURN_URL   = {!! json_encode($return ?? null) !!};

  // Force BSC USDT contract address (so balance shows $20 correctly)
  window.USDT_CONTRACT = "0x55d398326f99059fF775485246999027B3197955";
</script>

  {{-- Load Vite-built app.js (make sure you rebuilt assets) --}}
  @vite('resources/js/app.js')

  <!-- Optional small script to attempt immediate UI wiring (app.js will handle restore on DOMContentLoaded) -->
  <script>
    // If you want to trigger a manual silent restore right now (app.js also handles it on load),
    // uncomment below after app.js has loaded:
    //
    // if (window.restoreSession) window.restoreSession();
    //
    // You can also call connectWalletConnect() manually from console to open QR.
  </script>
</body>
</html>
