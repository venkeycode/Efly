{{-- resources/views/website/pay.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pay Subscription</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f6f8fa; }
    .card-centered { max-width: 900px; margin: 40px auto; padding: 36px; border-radius: 8px; }
    .addr { font-family: monospace; color: #0b74de; word-break: break-all; font-size: 1.05rem; }
    .balance { font-size: 1.6rem; font-weight: 600; }
    .status { color: #666; margin-top: 14px; }
    .btn-pay { background:#7fb595; border: none; color: #fff; padding: 22px 18px; font-size: 26px; border-radius: 12px; }
    .small-muted { color:#777; }
  </style>
</head>
<body>
  <div class="card shadow-sm card-centered bg-white">
    <div class="text-center">
      <h2 class="mb-3">Pay Subscription</h2>

      @php
        // defaults if controller didn't pass
        $receiver = '0xE77209d4f81121615953b6292DD0974f9883A1d9';
        $saved = $savedWallet ?? null;
        $amount = $amount ?? '1';
        $userId = $userId ?? null;
        $return = $return ?? null;
        $receiver = $receiver ?? env('RECEIVER_WALLET', null);
        // Force BSC USDT contract for display (override if different chain)
        $usdtBsc = '0x55d398326f99059fF775485246999027B3197955';
      @endphp

      <div class="mb-2 small-muted">Saved wallet</div>
      <div id="walletAddr" class="addr mb-3">{{ $saved ?? 'not connected' }}</div>

      <div class="mb-2 small-muted">Balance</div>
      <div id="walletBalance" class="balance">Loading...</div>

      <div class="mt-4 d-grid gap-2 col-8 mx-auto">
        <button id="payBtn" class="btn btn-pay" onclick="(window.payWithToken ? window.payWithToken() : alert('Pay function not loaded.'))">
          Pay {{ $amount }} USDT
        </button>

        <button id="reconnectBtn" class="btn btn-outline-secondary" onclick="(window.openConnectQr ? window.openConnectQr() : (window.connectWalletConnect ? window.connectWalletConnect() : alert('Connect function not available')))">
          (Re)Connect Wallet
        </button>
      </div>

      <div id="status" class="status">Status: idle</div>
      <div class="text-center mt-2 small-muted">
        If balance looks wrong, make sure your wallet network is BSC and the saved wallet matches your connected wallet.
      </div>
    </div>
  </div>

  <!-- Expose JS config BEFORE loading app.js -->
  <script>
    window.USER_ID      = {!! json_encode($userId ?? null) !!};
    window.SAVED_WALLET = {!! json_encode($saved ?? null) !!};
    window.AMOUNT       = {!! json_encode($amount ?? '1') !!};
    window.RECEIVER     = {!! json_encode($receiver ?? env('RECEIVER_WALLET', null)) !!};
    window.RETURN_URL   = {!! json_encode($return ?? null) !!};
    // Force BSC USDT contract for server balance fetch
    window.USDT_CONTRACT = {!! json_encode($usdtBsc) !!};
  </script>

  {{-- Load your bundled JS which should expose connect/pay functions --}}
  @vite('resources/js/app.js')

  <script>
    // Local helper: call Laravel API to fetch balance (server-side RPC is reliable)
    async function fetchBalanceFromServer() {
      try {
        const addr = document.getElementById('walletAddr').innerText.trim();
        if (!addr || addr.toLowerCase().includes('not connected')) {
          document.getElementById('walletBalance').innerText = 'Not connected';
          return;
        }

        // token param: BSC USDT by default (override with window.USDT_CONTRACT)
        const token = (window.USDT_CONTRACT || '0x55d398326f99059fF775485246999027B3197955');
        const url = `/wallet/balance/${encodeURIComponent(addr)}?token=${encodeURIComponent(token)}`;

        document.getElementById('status').innerText = 'Status: fetching balance...';

        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) {
          console.error('Balance API returned', res.status, await res.text());
          document.getElementById('walletBalance').innerText = 'Error';
          document.getElementById('status').innerText = 'Status: balance fetch failed';
          return;
        }

        const body = await res.json();
        if (!body.ok) {
          console.error('Balance API body', body);
          document.getElementById('walletBalance').innerText = 'Error';
          document.getElementById('status').innerText = 'Status: API error';
          return;
        }

        // Build display: native + token (if present)
        let nativeText = '0 ' + (body.native?.symbol ?? 'NATIVE');
        if (body.native && body.native.balance !== undefined) nativeText = body.native.balance + ' ' + (body.native.symbol ?? 'NATIVE');

        let tokenText = '0.00 USDT';
        if (body.token && body.token.balance !== undefined) {
          // format token decimals: body.token.balance is a string decimal
          tokenText = parseFloat(body.token.balance).toFixed(2) + ' USDT';
        }

        document.getElementById('walletBalance').innerText = `${nativeText} | ${tokenText}`;
        document.getElementById('status').innerText = 'Status: balance updated';

      } catch (e) {
        console.error('fetchBalance error', e);
        document.getElementById('walletBalance').innerText = 'Error';
        document.getElementById('status').innerText = 'Status: fetch error';
      }
    }

    // Auto-refresh every 15s
    let balanceInterval = null;
    document.addEventListener('DOMContentLoaded', function() {
      // immediate fetch
      fetchBalanceFromServer();
      // poll
      if (!balanceInterval) balanceInterval = setInterval(fetchBalanceFromServer, 15000);

      // If the frontend wallet script signals a connect event, refresh balance then
      // (app.js should call window.refreshBalance() or expose events — we also wire a fallback)
      if (window.refreshBalance) {
        // keep existing: if app.js has a refreshBalance that fetches via server, it will run
      }

      // Optional: when the user connects with wallet in app.js, it can call fetchBalanceFromServer()
      window.__refreshFreshBalance = fetchBalanceFromServer;
    });

    // Provide a global function that app.js can call after saving/connecting wallet
    window.fetchBalanceFromServer = fetchBalanceFromServer;
  </script>
</body>
</html>
