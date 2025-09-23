// resources/js/app.js
import { ethers } from "ethers";
import EthereumProvider from "@walletconnect/ethereum-provider";

/*
  CONFIG
*/
const PROJECT_ID = import.meta.env.VITE_WC_PROJECT_ID || "611536788e4297012ef34993004d5565";
const CHAIN_IDS = [56]; // default BSC
const RPC_MAP = {
  56: "https://bsc-dataseed.binance.org/",
  137: "https://polygon-rpc.com",
  1: "https://eth.llamarpc.com"
};
const BSC_USDT = "0x55d398326f99059fF775485246999027B3197955"; // BSC USDT (18 decimals)

/* minimal ERC20 ABI */
const ERC20_ABI = [
  "function balanceOf(address) view returns (uint256)",
  "function decimals() view returns (uint8)",
  "function symbol() view returns (string)",
  "function transfer(address to, uint256 value) returns (bool)"
];

/*
  STATE
*/
let wcProvider = null;        // WalletConnect provider (raw)
let provider = null;          // ethers BrowserProvider
let signer = null;            // ethers Signer
let connectedAddress = null;  // string

/*
  DOM references — these IDs should exist in your Blade
*/
let walletAddrEl = null;
let walletBalanceEl = null;
let statusEl = null;
let payBtn = null;
let reconnectBtn = null;

/* helpers */
function initDom() {
  walletAddrEl = document.getElementById("walletAddr");
  walletBalanceEl = document.getElementById("walletBalance");
  statusEl = document.getElementById("status");
  payBtn = document.getElementById("payBtn");
  reconnectBtn = document.getElementById("reconnectBtn");
}
function setStatus(txt, isError=false) {
  if (!statusEl) return;
  statusEl.innerText = `Status: ${txt}`;
  statusEl.style.color = isError ? "crimson" : "";
}
function normalize(a='') { try { return (a||'').toLowerCase(); } catch { return String(a).toLowerCase(); } }

/*
  Initialize WalletConnect provider
*/
// use only BSC (56) here so WalletConnect relay won't try to register polygon
async function initWcProvider({ showQrModal = false } = {}) {
  return await EthereumProvider.init({
    projectId: PROJECT_ID,
    chains: [56],               // IMPORTANT: only include the chains you actually need
    showQrModal,
    rpcMap: {
      56: "https://bsc-dataseed.binance.org/",
    },
    optionalChains: []         // leave empty to avoid unexpected chain registration
  });
}

/*
  try to switch chain via wallet_switchEthereumChain (no wallet_addEthereumChain)
  returns true if on target chain at end, false otherwise
*/
// robustly ask the provider to switch chain
async function trySwitchToChain(ethProvider, targetChainId = 56) {
  const hex = "0x" + Number(targetChainId).toString(16);

  async function tryRequest(obj) {
    if (!obj) return false;
    try {
      // EIP-1193 standard
      if (typeof obj.request === "function") {
        await obj.request({ method: "wallet_switchEthereumChain", params: [{ chainId: hex }] });
        return true;
      }

      // fallback: send(method, params) - some providers implement this
      if (typeof obj.send === "function") {
        try {
          await obj.send("wallet_switchEthereumChain", [{ chainId: hex }]);
          return true;
        } catch (e) {
          // try send with payload + callback
          try {
            await new Promise((resolve, reject) => {
              obj.send(
                { jsonrpc: "2.0", id: Date.now(), method: "wallet_switchEthereumChain", params: [{ chainId: hex }] },
                (err, result) => (err ? reject(err) : resolve(result))
              );
            });
            return true;
          } catch (e2) { /* fallthrough */ }
        }
      }

      if (typeof obj.sendAsync === "function") {
        await new Promise((resolve, reject) => {
          obj.sendAsync(
            { jsonrpc: "2.0", id: Date.now(), method: "wallet_switchEthereumChain", params: [{ chainId: hex }] },
            (err, result) => (err ? reject(err) : resolve(result))
          );
        });
        return true;
      }
    } catch (err) {
      return false;
    }
    return false;
  }

  // Try likely objects in order
  const candidates = [ethProvider, wcProvider, (provider && provider.provider), provider];
  for (const cand of candidates) {
    if (!cand) continue;
    try {
      const ok = await tryRequest(cand);
      if (ok) return true;
    } catch (e) {
      // ignore and try next
    }
  }
  return false;
}
/*
  Ensure BSC flow: returns true if on BSC at end
  - tries wallet_switchEthereumChain
  - if fails, shows instruction to user (manual switch + reconnect)
*/
async function ensureBscFlow() {
  const ok = await trySwitchToChain(wcProvider || (provider && provider.provider) || provider, 56);
  if (!ok) {
    setStatus("Please switch your wallet to BSC manually and reconnect.", true);
    if (reconnectBtn) reconnectBtn.style.display = "inline-block";
    return false;
  }
  // optional small wait to let provider update
  await new Promise(r => setTimeout(r, 700));
  setStatus("Switched to BSC");
  return true;
}

/*
  Restore session silently (no QR) if available
*/
// Restore session silently (no QR) if available
export async function restoreSession() {
  setStatus("restoring session...");
  try {
    wcProvider = await initWcProvider({ showQrModal:false });
    await wcProvider.enable(); // resolves only if session exists

    provider = new ethers.BrowserProvider(wcProvider);
    signer = await provider.getSigner();
    connectedAddress = await signer.getAddress();

    // expose for console/debugging
    window.__wcProvider = wcProvider;
    window.__provider = provider;
    window.__signer = signer;

    if (walletAddrEl) walletAddrEl.innerText = connectedAddress;
    setStatus("connected: " + connectedAddress);
    if (payBtn) payBtn.disabled = false;
    if (reconnectBtn) reconnectBtn.style.display = "none";

    await ensureBscFlow();
    await fetchBalanceFromServer(connectedAddress);

    try { provider.on("block", () => fetchBalanceFromServer(connectedAddress)); } catch(e){}

    return connectedAddress;
  } catch (err) {
    console.warn("restoreSession failed:", err);
    setStatus("no active wallet session");
    if (reconnectBtn) reconnectBtn.style.display = "inline-block";
    if (payBtn) payBtn.disabled = true;
    return null;
  }
}

/*
  Open connect QR and connect (fresh)
*/
// Open connect QR and connect (fresh)
export async function openConnectQr() {
  setStatus("opening WalletConnect QR...");
  try {
    wcProvider = await initWcProvider({ showQrModal:true });
    await wcProvider.enable();

    provider = new ethers.BrowserProvider(wcProvider);
    signer = await provider.getSigner();
    connectedAddress = await signer.getAddress();

    // expose for console/debugging
    window.__wcProvider = wcProvider;
    window.__provider = provider;
    window.__signer = signer;

    if (walletAddrEl) walletAddrEl.innerText = connectedAddress;
    setStatus("connected: " + connectedAddress);
    if (payBtn) payBtn.disabled = false;
    if (reconnectBtn) reconnectBtn.style.display = "none";

    // attempt BSC switch
    await ensureBscFlow();

    // auto-save to backend if USER_ID present
    try {
      if (window.USER_ID) {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrf = csrfMeta ? csrfMeta.getAttribute('content') : null;
        await fetch("/customer/wallet/save", {
          method: "POST",
          headers: {
            "Content-Type":"application/json",
            ...(csrf?{"X-CSRF-TOKEN":csrf}:{}),
            "Accept":"application/json"
          },
          body: JSON.stringify({ wallet_address: connectedAddress, user_id: window.USER_ID })
        });
      }
    } catch (e) { console.warn("save wallet failed:", e); }

    await fetchBalanceFromServer(connectedAddress);
    try { provider.on("block", () => fetchBalanceFromServer(connectedAddress)); } catch(e){}

    return connectedAddress;
  } catch (err) {
    console.error("openConnectQr failed:", err);
    setStatus("connect failed: " + (err.message || err), true);
    throw err;
  }
}

/*
  Connect wrapper for Blade button (calls openConnectQr)
*/
export async function connectWalletConnect() {
  return openConnectQr();
}

/*
  Fetch balance from your Laravel server (safe/trusted)
  - uses the server endpoint: /wallet/balance/{address}?token={token}
*/
export async function fetchBalanceFromServer(addressArg = null) {
  try {
    const addr = addressArg || connectedAddress || (walletAddrEl ? walletAddrEl.innerText.trim() : null);
    if (!addr) {
      if (walletBalanceEl) walletBalanceEl.innerText = "Not connected";
      return;
    }

    setStatus("fetching balance...");
    const token = window.USDT_CONTRACT || BSC_USDT;
    const url = `/wallet/balance/${encodeURIComponent(addr)}?token=${encodeURIComponent(token)}`;

    const res = await fetch(url, { headers: { Accept: "application/json" } });
    if (!res.ok) {
      console.error("Balance API error:", res.status, await res.text());
      if (walletBalanceEl) walletBalanceEl.innerText = "Error";
      setStatus("balance fetch failed", true);
      return;
    }
    const body = await res.json();
    if (!body.ok) {
      console.error("Balance API returned error", body);
      if (walletBalanceEl) walletBalanceEl.innerText = "Error";
      setStatus("balance API error", true);
      return;
    }

    const native = body.native?.balance ?? "0";
    const nativeSym = body.native?.symbol ?? "NATIVE";
    let tokenText = "0.00 USDT";
    if (body.token && body.token.balance !== undefined) {
      tokenText = parseFloat(body.token.balance).toFixed(body.token.decimals === 6 ? 2 : 2) + " USDT";
    }

    if (walletBalanceEl) walletBalanceEl.innerText = `${native} ${nativeSym} | ${tokenText}`;
    setStatus("balance updated");
    return body;
  } catch (err) {
    console.error("fetchBalanceFromServer error:", err);
    if (walletBalanceEl) walletBalanceEl.innerText = "Error";
    setStatus("fetch error", true);
    return null;
  }
}

/*
  Payment flow: uses server balance as gate, then uses signer to send token.transfer()
*/
export async function payWithToken() {
  try {
    if (!signer) { alert("Please connect your wallet first."); return; }
    const connected = (await signer.getAddress()).toLowerCase();
    const saved = (window.SAVED_WALLET || "").toLowerCase();
    if (saved && connected !== saved) {
      if (!confirm("Connected wallet differs from saved wallet. Continue with connected wallet?")) return;
    }

    const tokenAddr = window.USDT_CONTRACT || BSC_USDT;
    const receiver = window.RECEIVER;
    const amountStr = String(window.AMOUNT || "1");

    if (!receiver || !amountStr) { alert("Receiver or amount missing."); return; }

    // 1) server balance check for trusted info
    setStatus("checking server balance...");
    const server = await fetchBalanceFromServer(connected);
    if (!server) { alert("Could not get server balance."); return; }
    const serverTokenRaw = server.token?.balance_wei ?? "0"; // string decimal
    const serverTokenDecimals = Number(server.token?.decimals ?? 18);
    const requiredWei = ethers.parseUnits(amountStr, serverTokenDecimals);
    const serverBig = BigInt(serverTokenRaw || "0");

    if (serverBig < requiredWei) {
      alert(`Insufficient USDT. Server shows ${server.token?.balance ?? "0"}. Need ${amountStr}`);
      setStatus("insufficient token", true);
      return;
    }

    // 2) ensure token contract exists on current chain
    const code = await provider.getCode(tokenAddr).catch(()=> "0x");
    if (!code || code === "0x" || code === "0x0") {
      alert("Token contract not found on current network. Switch your wallet to BSC and reconnect.");
      setStatus("token not on chain", true);
      return;
    }

    // 3) ensure native gas available
    const nativeBal = await provider.getBalance(connected);
    // estimate gas for transfer
    const tokenContract = new ethers.Contract(tokenAddr, ERC20_ABI, provider);
    let gasLimit;
    try {
      gasLimit = await tokenContract.connect(signer).estimateGas.transfer(receiver, requiredWei);
    } catch (e) {
      gasLimit = 150000n;
    }
    const feeData = await provider.getFeeData();
    const gasPriceRaw = feeData.maxFeePerGas ?? feeData.gasPrice ?? ethers.parseUnits("5", "gwei");
    const gasPrice = BigInt(gasPriceRaw.toString());
    const estimatedFee = gasPrice * BigInt(gasLimit);
    if (nativeBal < estimatedFee) {
      alert("Insufficient native token (BNB) for gas. Please top up.");
      setStatus("insufficient gas", true);
      return;
    }

    // 4) send transfer
    setStatus("Sending transfer — confirm in wallet");
    const tokenWithSigner = tokenContract.connect(signer);
    const tx = await tokenWithSigner.transfer(receiver, requiredWei);
    setStatus("Tx sent: " + tx.hash + " — waiting 1 confirmation");
    await tx.wait(1);

    // 5) server verify
    setStatus("Verifying with server...");
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrf = csrfMeta ? csrfMeta.getAttribute('content') : null;
    const resp = await fetch("/api/payment/verify-token", {
      method: "POST",
      headers: { "Content-Type":"application/json", ...(csrf?{"X-CSRF-TOKEN":csrf}:{}) },
      body: JSON.stringify({
        user_id: window.USER_ID,
        token_contract: tokenAddr,
        amount: amountStr,
        tx_hash: tx.hash,
        from_address: connected,
        receiver: receiver
      })
    });
    const j = await resp.json();
    if (j.ok) {
      setStatus("Payment verified ✔");
      alert("Payment success: " + tx.hash);
      if (window.RETURN_URL) {
        const u = new URL(window.RETURN_URL);
        u.searchParams.set("user_id", window.USER_ID);
        u.searchParams.set("tx", tx.hash);
        window.location.href = u.toString();
      }
    } else {
      setStatus("Verification failed", true);
      alert("Verification failed: " + (j.error || JSON.stringify(j)));
    }

  } catch (err) {
    console.error("payWithToken error:", err);
    setStatus("payment error: " + (err.message || err), true);
    alert("Payment error: " + (err.message || err));
  }
}

/*
  Expose and wire up on DOM ready
*/
document.addEventListener("DOMContentLoaded", async () => {
  initDom();

  // expose functions for Blade to call
  window.connectWalletConnect = connectWalletConnect;
  window.openConnectQr = openConnectQr;
  window.restoreSession = restoreSession;
  window.refreshBalance = () => fetchBalanceFromServer();
  window.payWithToken = payWithToken;

  // try silent restore if saved wallet present
  if (window.SAVED_WALLET) {
    try { await restoreSession(); } catch(e) { console.warn("silent restore failed", e); }
  } else {
    setStatus("no saved wallet");
  }

  // wire reconnect button if present
  if (reconnectBtn) {
    reconnectBtn.onclick = () => {
      if (window.openConnectQr) window.openConnectQr();
      else if (window.connectWalletConnect) window.connectWalletConnect();
      else location.reload();
    };
  }

  // auto-refresh balance every 15s
  setInterval(() => { fetchBalanceFromServer(); }, 15000);
});
