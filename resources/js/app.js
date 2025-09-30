// resources/js/app.js
import { ethers } from "ethers";
import EthereumProvider from "@walletconnect/ethereum-provider";
window.ethers = ethers;
/*
  CONFIG - change via Vite env if needed
*/
const PROJECT_ID = import.meta.env.VITE_WC_PROJECT_ID || "611536788e4297012ef34993004d5565";
const DEFAULT_CHAIN = 56; // BSC (change if you want)
const RPC_MAP = {
  56: "https://bsc-dataseed.binance.org/",
  137: "https://polygon-rpc.com",
  1: "https://eth.llamarpc.com"
};
const BSC_USDT = "0x55d398326f99059fF775485246999027B3197955";

/* Minimal ERC20 ABI */
const ERC20_ABI = [
  "function balanceOf(address) view returns (uint256)",
  "function decimals() view returns (uint8)",
  "function symbol() view returns (string)",
  "function transfer(address to, uint256 value) returns (bool)"
];

/* STATE */
let wcProvider = null;       // raw WalletConnect provider
let provider = null;         // ethers BrowserProvider
let signer = null;           // ethers Signer
let connectedAddress = null; // string

/* DOM elements (initialized on DOMContentLoaded) */
let walletAddrEl, walletBalanceEl, statusEl, payBtn, reconnectBtn;

function initDom() {
  walletAddrEl = document.getElementById("walletAddr");
  walletBalanceEl = document.getElementById("walletBalance");
  statusEl = document.getElementById("status");
  payBtn = document.getElementById("payBtn");
  reconnectBtn = document.getElementById("reconnectBtn");
}
function setStatus(txt, isError = false) {
  if (!statusEl) return;
  statusEl.innerText = `Status: ${txt}`;
  statusEl.style.color = isError ? "crimson" : "";
}
function lower(a=''){ return (a||'').toLowerCase(); }

/* Init WalletConnect provider restricted to one chain (BSC) to avoid cross-chain session issues */
async function initWcProvider({ showQrModal = false } = {}) {
  return await EthereumProvider.init({
    projectId: PROJECT_ID,
    chains: [DEFAULT_CHAIN],
    showQrModal,
    rpcMap: { [DEFAULT_CHAIN]: RPC_MAP[DEFAULT_CHAIN] },
    optionalChains: []
  });
}

/* Try to request wallet_switchEthereumChain in several ways (best-effort) */
async function trySwitchToChain(ethProviderObj, targetChainId = DEFAULT_CHAIN) {
  const hex = "0x" + Number(targetChainId).toString(16);
  async function tryReq(obj) {
    if (!obj) return false;
    try {
      if (typeof obj.request === "function") {
        await obj.request({ method: "wallet_switchEthereumChain", params: [{ chainId: hex }] });
        return true;
      }
      if (typeof obj.send === "function") {
        await obj.send("wallet_switchEthereumChain", [{ chainId: hex }]);
        return true;
      }
      if (typeof obj.sendAsync === "function") {
        await new Promise((res, rej) => obj.sendAsync({ jsonrpc: "2.0", id: Date.now(), method: "wallet_switchEthereumChain", params: [{ chainId: hex }] }, (e, r) => (e ? rej(e) : res(r))));
        return true;
      }
    } catch (e) {
      return false;
    }
    return false;
  }
  const candidates = [ethProviderObj, wcProvider, (provider && provider.provider), provider];
  for (const c of candidates) {
    if (!c) continue;
    try {
      const ok = await tryReq(c);
      if (ok) return true;
    } catch (_) {}
  }
  return false;
}

/* Try ensure BSC — otherwise instruct user to switch manually */
async function ensureBscFlow() {
  const ok = await trySwitchToChain(wcProvider || (provider && provider.provider) || provider, DEFAULT_CHAIN);
  if (!ok) {
    setStatus("Switch wallet to BSC (BNB Smart Chain) and reconnect.", true);
    if (reconnectBtn) reconnectBtn.style.display = "inline-block";
    return false;
  }
  // small delay to let provider update
  await new Promise(r => setTimeout(r, 700));
  setStatus("Switched to BSC");
  return true;
}

/* Restore session silently (no QR) if available */
export async function restoreSession() {
  setStatus("restoring session...");
  try {
    wcProvider = await initWcProvider({ showQrModal: false });
    await wcProvider.enable(); // resolves only if session exists

    provider = new ethers.BrowserProvider(wcProvider);
    signer = await provider.getSigner();
    connectedAddress = await signer.getAddress();

    // expose for debugging in console
    window.__wcProvider = wcProvider;
    window.__provider = provider;
    window.__signer = signer;

    if (walletAddrEl) walletAddrEl.innerText = connectedAddress;
    setStatus("connected: " + connectedAddress);
    if (payBtn) payBtn.disabled = false;
    if (reconnectBtn) reconnectBtn.style.display = "none";

    await ensureBscFlow();
    await fetchBalanceFromServer(connectedAddress);
    return connectedAddress;
  } catch (err) {
    console.warn("restoreSession failed:", err);
    setStatus("no active wallet session");
    if (reconnectBtn) reconnectBtn.style.display = "inline-block";
    if (payBtn) payBtn.disabled = true;
    return null;
  }
}

/* Open connect QR and connect fresh */
export async function openConnectQr() {
  setStatus("opening WalletConnect QR...");
  try {
    wcProvider = await initWcProvider({ showQrModal: true });
    await wcProvider.enable();

    provider = new ethers.BrowserProvider(wcProvider);
    signer = await provider.getSigner();
    connectedAddress = await signer.getAddress();

    window.__wcProvider = wcProvider;
    window.__provider = provider;
    window.__signer = signer;

    if (walletAddrEl) walletAddrEl.innerText = connectedAddress;
    setStatus("connected: " + connectedAddress);
    if (payBtn) payBtn.disabled = false;
    if (reconnectBtn) reconnectBtn.style.display = "none";

    // ensure on BSC (best-effort)
    await ensureBscFlow();

    // auto-save wallet to backend (if configured)
    try {
      if (window.USER_ID) {
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrf = csrfMeta ? csrfMeta.getAttribute('content') : null;
        await fetch("/customer/wallet/save", {
          method: "POST",
          headers: {
            "Content-Type":"application/json",
            ...(csrf?{"X-CSRF-TOKEN":csrf}:{})
          },
          body: JSON.stringify({ wallet_address: connectedAddress, user_id: window.USER_ID })
        });
      }
    } catch (e) { console.warn("save wallet failed:", e); }

    await fetchBalanceFromServer(connectedAddress);
    return connectedAddress;
  } catch (err) {
    console.error("openConnectQr failed:", err);
    setStatus("connect failed: " + (err.message || err), true);
    throw err;
  }
}

/* Fetch balance via Laravel server endpoint (most reliable across chains) */
export async function fetchBalanceFromServer(addressArg = null) {
  try {
    const addr = addressArg || connectedAddress || (walletAddrEl ? walletAddrEl.innerText.trim() : null);
    if (!addr) {
      if (walletBalanceEl) walletBalanceEl.innerText = "Not connected";
      return null;
    }
    setStatus("fetching balance...");
    const token = window.USDT_CONTRACT || BSC_USDT;
    const url = `/wallet/balance/${encodeURIComponent(addr)}?token=${encodeURIComponent(token)}`;
    const res = await fetch(url, { headers: { Accept: "application/json" } });
    if (!res.ok) {
      console.error("Balance API error:", res.status, await res.text());
      if (walletBalanceEl) walletBalanceEl.innerText = "Error";
      setStatus("balance fetch failed", true);
      return null;
    }
    const body = await res.json();
    if (!body.ok) {
      console.error("Balance API returned error", body);
      if (walletBalanceEl) walletBalanceEl.innerText = "Error";
      setStatus("balance API error", true);
      return null;
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

/* payWithToken: robust client-side flow (checks server, checks gas, sends ERC20 transfer) */
/* Pay with token (USDT) flow */
export async function payWithToken() {
  try {
    if (!provider || !signer) {
      alert("Please connect your wallet first.");
      return;
    }

    const connected = (await signer.getAddress()).toLowerCase();
    const saved = (window.SAVED_WALLET || "").toLowerCase();
    if (saved && saved !== "" && connected !== saved) {
      if (!confirm("Connected wallet differs from saved wallet. Continue with connected wallet?")) return;
    }

    const tokenAddr = (window.USDT_CONTRACT || "").trim();
    const receiver = (window.RECEIVER || "").trim();
    const amountStr = String(window.AMOUNT || "0").trim();

    if (!tokenAddr) { alert("Token contract not configured (USDT)."); return; }
    if (!receiver)  { alert("Receiver wallet not configured."); return; }
    if (!amountStr || Number(amountStr) <= 0) { alert("Payment amount invalid."); return; }

    if (payBtn) {
      payBtn.disabled = true;
      payBtn.classList.add('disabled');
    }

    setStatus("Preparing payment...");

    const code = await provider.getCode(tokenAddr).catch(() => "0x");
    if (!code || code === "0x" || code === "0x0") {
      alert("Token contract not found on current network. Switch network and reconnect.");
      setStatus("Token not on chain", true);
      if (payBtn) { payBtn.disabled = false; payBtn.classList.remove('disabled'); }
      return;
    }

    const tokenContract = new ethers.Contract(tokenAddr, ERC20_ABI, provider);
    const tokenWithSigner = tokenContract.connect(signer);

    let decimals = 18;
    try { decimals = Number(await tokenContract.decimals()); } catch (e) {}
    let symbol = "TOKEN";
    try { symbol = await tokenContract.symbol(); } catch (e) {}

    const want = ethers.parseUnits(amountStr, decimals);

    setStatus("Checking token balance...");
    let rawBal;
    try {
      rawBal = await tokenContract.balanceOf(connected);
    } catch (e) {
      console.error("balanceOf failed:", e);
      alert("Could not read token balance. Try reconnecting.");
      setStatus("Balance read failed", true);
      if (payBtn) { payBtn.disabled = false; payBtn.classList.remove('disabled'); }
      return;
    }
    if (BigInt(rawBal.toString()) < BigInt(want.toString())) {
      alert(`Insufficient ${symbol}. Have ${ethers.formatUnits(rawBal, decimals)}, need ${amountStr}`);
      setStatus("Insufficient token balance", true);
      if (payBtn) { payBtn.disabled = false; payBtn.classList.remove('disabled'); }
      return;
    }

    setStatus("Checking native balance for gas...");
    const nativeBal = await provider.getBalance(connected);
    let gasLimit;
    try {
      gasLimit = await tokenWithSigner.estimateGas.transfer(receiver, want);
      gasLimit = BigInt(gasLimit.toString());
    } catch (e) {
      console.warn("estimateGas failed, fallback to 150000", e);
      gasLimit = 150000n;
    }
    const feeData = await provider.getFeeData();
    let gasPrice = feeData.maxFeePerGas ?? feeData.gasPrice ?? ethers.parseUnits("5", "gwei");
    gasPrice = BigInt(gasPrice.toString());
    const estimatedFee = gasPrice * gasLimit;
    const buffer = BigInt(ethers.parseUnits("0.0005", "ether").toString());
    if (BigInt(nativeBal.toString()) < (estimatedFee + buffer)) {
      const haveBNB = parseFloat(ethers.formatEther(nativeBal || 0n)).toFixed(6);
      const needBNB = parseFloat(ethers.formatEther(estimatedFee + buffer)).toFixed(6);
      alert(`Insufficient native token (for gas).\nYou have: ${haveBNB}\nRequired (approx): ${needBNB}\nPlease top up and try again.`);
      setStatus("Insufficient native for gas", true);
      if (payBtn) { payBtn.disabled = false; payBtn.classList.remove('disabled'); }
      return;
    }

    // 👇 Show "please approve" before wallet prompt
    setStatus("Please approve the transaction in your wallet app…");

    let tx;
    try {
      tx = await tokenWithSigner.transfer(receiver, want);
    } catch (sendErr) {
      console.error("transfer() failed:", sendErr);
      const msg = (sendErr && sendErr.message) ? sendErr.message.toLowerCase() : "";
      if (msg.includes("user rejected") || msg.includes("rejected") || (sendErr.code === 4001)) {
        setStatus("Transaction rejected by user", true);
        alert("You rejected the transaction in your wallet.");
      } else {
        setStatus("Transaction send failed", true);
        alert("Transaction failed to send: " + (sendErr.message || sendErr));
      }
      if (payBtn) { payBtn.disabled = false; payBtn.classList.remove('disabled'); }
      return;
    }

    setStatus("Transaction submitted — waiting for confirmation...");
    try {
      await tx.wait(1);
    } catch (waitErr) {
      console.warn("tx.wait failed:", waitErr);
      setStatus("Transaction submitted (no confirmation yet). Verifying...", true);
    }

    setStatus("Verifying with server...");
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrf = csrfMeta ? csrfMeta.getAttribute("content") : null;
    const verifyResp = await fetch("/api/payment/verify", {
      method: "POST",
      headers: {
        "Content-Type":"application/json",
        ...(csrf ? { "X-CSRF-TOKEN": csrf } : {})
      },
      body: JSON.stringify({
        user_id: window.USER_ID,
        token_contract: tokenAddr,
        amount: amountStr,
        tx_hash: tx.hash,
        from_address: connected,
        receiver: receiver
      })
    });
    const verifyJson = await verifyResp.json();
    if (verifyJson.ok) {
      setStatus("Payment verified ✔");

      // 🔑 Redirect to CI return URL after success
      if (window.RETURN_URL) {
        const u = new URL(window.RETURN_URL);
        u.searchParams.set("user_id", window.USER_ID);
        u.searchParams.set("plan_id", window.PLAN_ID);
        u.searchParams.set("tx", tx.hash);
        window.location.href = u.toString();
      } else {
        alert("Payment successful: " + tx.hash);
      }
    } else {
      setStatus("Server verification failed", true);
      console.error("verify response:", verifyJson);
      alert("Verify failed: " + (verifyJson.error || JSON.stringify(verifyJson)));
    }

    if (payBtn) { payBtn.disabled = false; payBtn.classList.remove('disabled'); }
  } catch (err) {
    console.error("payWithToken outer error:", err);
    setStatus("payment error: " + (err.message || err), true);
    alert("Payment error: " + (err.message || err));
    if (payBtn) { payBtn.disabled = false; payBtn.classList.remove('disabled'); }
  }
}
/* Wire-up on DOM ready */
document.addEventListener("DOMContentLoaded", async () => {
  initDom();

  // expose functions for Blade to call
  window.connectWalletConnect = openConnectQr;
  window.openConnectQr = openConnectQr;
  window.restoreSession = restoreSession;
  window.refreshBalance = () => fetchBalanceFromServer();
  window.payWithToken = payWithToken;

  // try silent restore if the page already has a saved wallet
  if (window.SAVED_WALLET) {
    try { await restoreSession(); } catch (e) { console.warn("silent restore failed", e); }
  } else {
    setStatus("no saved wallet");
  }

  // reconnect button behavior
  if (reconnectBtn) {
    reconnectBtn.onclick = () => {
      if (window.openConnectQr) window.openConnectQr();
      else location.reload();
    };
  }

  // poll server-side balance every 15s
  setInterval(() => { fetchBalanceFromServer(); }, 15000);
});
