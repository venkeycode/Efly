// resources/js/app.js
// Replace existing file with this content
import { ethers } from "ethers";
import EthereumProvider from "@walletconnect/ethereum-provider";

/*
  CONFIG - change env via Vite if needed
*/
const PROJECT_ID = import.meta.env.VITE_WC_PROJECT_ID || "611536788e4297012ef34993004d5565";
const DEFAULT_CHAIN = 56; // BSC
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

/* State */
let wcProvider = null;       // raw WalletConnect provider
let provider = null;         // ethers BrowserProvider
let signer = null;           // ethers Signer
let connectedAddress = null; // string

/* DOM elems - initialized on DOMContentLoaded */
let walletAddrEl, walletBalanceEl, statusEl, payBtn, reconnectBtn;

/* Helpers */
function setStatus(txt, isError = false) {
  if (!statusEl) return;
  statusEl.innerText = `Status: ${txt}`;
  statusEl.style.color = isError ? "crimson" : "";
}
function initDom() {
  walletAddrEl = document.getElementById("walletAddr");
  walletBalanceEl = document.getElementById("walletBalance");
  statusEl = document.getElementById("status");
  payBtn = document.getElementById("payBtn");
  reconnectBtn = document.getElementById("reconnectBtn");
}
function lower(a=''){ return (a||'').toLowerCase(); }

/* Init WalletConnect provider restricted to BSC to avoid sessions pinned to wrong chain */
async function initWcProvider({ showQrModal = false } = {}) {
  return await EthereumProvider.init({
    projectId: PROJECT_ID,
    chains: [DEFAULT_CHAIN],
    showQrModal,
    rpcMap: { [DEFAULT_CHAIN]: RPC_MAP[DEFAULT_CHAIN] },
    optionalChains: []
  });
}

/* Try to request wallet_switchEthereumChain in several ways */
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
        // legacy providers
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

/* Ensure we are on BSC: try to switch programmatically; if fails, instruct user */
async function ensureBscFlow() {
  const ok = await trySwitchToChain(wcProvider || (provider && provider.provider) || provider, DEFAULT_CHAIN);
  if (!ok) {
    setStatus("Switch wallet to BSC (BNB Smart Chain) and reconnect.", true);
    if (reconnectBtn) reconnectBtn.style.display = "inline-block";
    return false;
  }
  // wait a moment for provider to update
  await new Promise(r => setTimeout(r, 700));
  setStatus("Switched to BSC");
  return true;
}

/* Restore session silently (no QR) */
export async function restoreSession() {
  setStatus("restoring session...");
  try {
    wcProvider = await initWcProvider({ showQrModal: false });
    await wcProvider.enable(); // resolves only if session exists

    provider = new ethers.BrowserProvider(wcProvider);
    signer = await provider.getSigner();
    connectedAddress = await signer.getAddress();

    // expose for debugging
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

/* Open QR modal and connect fresh */
export async function openConnectQr() {
  setStatus("opening WalletConnect QR...");
  try {
    wcProvider = await initWcProvider({ showQrModal: true });
    await wcProvider.enable();

    provider = new ethers.BrowserProvider(wcProvider);
    signer = await provider.getSigner();
    connectedAddress = await signer.getAddress();

    // expose for debugging
    window.__wcProvider = wcProvider;
    window.__provider = provider;
    window.__signer = signer;

    if (walletAddrEl) walletAddrEl.innerText = connectedAddress;
    setStatus("connected: " + connectedAddress);
    if (payBtn) payBtn.disabled = false;
    if (reconnectBtn) reconnectBtn.style.display = "none";

    // ensure on BSC
    await ensureBscFlow();

    // save wallet to backend if USER_ID present
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
    try { provider.on("block", () => fetchBalanceFromServer(connectedAddress)); } catch(e){}
    return connectedAddress;
  } catch (err) {
    console.error("openConnectQr failed:", err);
    setStatus("connect failed: " + (err.message || err), true);
    throw err;
  }
}

/* Fetch balances from Laravel server (server chooses RPC per token) */
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

/* Ensure native token (BNB) available for gas before sending transfer */
async function ensureNativeForGas(tokenContractAddress, receiver, amountUnits /* BigInt or ethers.BigInt */) {
  if (!provider || !signer || !connectedAddress) throw new Error("Wallet not connected");
  try {
    const token = new ethers.Contract(tokenContractAddress, ERC20_ABI, provider);

    // estimate gas (fallback to 150k)
    let gasLimit;
    try {
      gasLimit = await token.connect(signer).estimateGas.transfer(receiver, amountUnits);
      gasLimit = BigInt(gasLimit.toString());
    } catch (e) {
      console.warn("estimateGas failed, fallback to 150000", e);
      gasLimit = 150000n;
    }

    // feeData
    const feeData = await provider.getFeeData();
    let gasPriceWei;
    if (feeData.maxFeePerGas) {
      gasPriceWei = BigInt(feeData.maxFeePerGas.toString());
    } else if (feeData.gasPrice) {
      gasPriceWei = BigInt(feeData.gasPrice.toString());
    } else {
      gasPriceWei = BigInt(ethers.parseUnits("5", "gwei").toString());
    }

    const estimatedFeeWei = gasLimit * gasPriceWei;
    const bufferWei = BigInt(ethers.parseUnits("0.0005", "ether").toString());
    const totalNeededWei = estimatedFeeWei + bufferWei;

    const balWei = await provider.getBalance(connectedAddress);
    const balBig = BigInt(balWei.toString());
    const shortWei = totalNeededWei > balBig ? (totalNeededWei - balBig) : 0n;

    return {
      ok: balBig >= totalNeededWei,
      requiredWei: totalNeededWei,
      requiredBNB: ethers.formatEther(totalNeededWei),
      balanceWei: balBig,
      balanceBNB: ethers.formatEther(balBig),
      shortWei,
      shortBNB: shortWei === 0n ? "0" : ethers.formatEther(shortWei),
      gasLimit: gasLimit.toString(),
      estimatedFeeWei: estimatedFeeWei.toString(),
      gasPriceWei: gasPriceWei.toString()
    };
  } catch (err) {
    console.error("ensureNativeForGas error:", err);
    throw err;
  }
}

/* Pay with token (USDT) flow */
export async function payWithToken() {
  try {
    if (!signer) { alert("Please connect your wallet first."); return; }
    const connected = (await signer.getAddress()).toLowerCase();
    const saved = (window.SAVED_WALLET || "").toLowerCase();
    if (saved && connected !== saved) {
      if (!confirm("Connected wallet differs from saved wallet. Continue?")) return;
    }

    // Determine token & receiver & amount
    const tokenAddr = window.USDT_CONTRACT || BSC_USDT;
    const receiver = window.RECEIVER;
    const amountStr = String(window.AMOUNT || "1");
    if (!tokenAddr || !receiver || !amountStr) { alert("Missing token/receiver/amount"); return; }

    setStatus("Checking server balance...");
    const server = await fetchBalanceFromServer(connected);
    if (!server) { alert("Could not get server balance."); return; }

    const serverTokenRaw = server.token?.balance_wei ?? "0"; // decimal string
    const serverTokenDecimals = Number(server.token?.decimals ?? 18);
    const requiredWei = ethers.parseUnits(amountStr, serverTokenDecimals);
    const serverBig = BigInt(serverTokenRaw || "0");

    if (serverBig < requiredWei) {
      alert(`Insufficient ${ (server.token?.symbol ?? 'USDT') }. Server shows ${server.token?.balance ?? '0'}. Need ${amountStr}`);
      setStatus("insufficient token", true);
      return;
    }

    // ensure token contract exists on current chain
    const code = await provider.getCode(tokenAddr).catch(()=> "0x");
    if (!code || code === "0x" || code === "0x0") {
      alert("Token contract not found on current network. Switch wallet to BSC and reconnect.");
      setStatus("token not on chain", true);
      return;
    }

    // check native (BNB) for gas
    setStatus("Checking gas requirement...");
    const gasCheck = await ensureNativeForGas(tokenAddr, receiver, requiredWei);
    if (!gasCheck.ok) {
      alert(
        `Insufficient native token (BNB) for gas.\nYou have: ${gasCheck.balanceBNB} BNB\nRequired (approx): ${gasCheck.requiredBNB} BNB\nShort by: ${gasCheck.shortBNB} BNB\nPlease top up and try again.`
      );
      setStatus("insufficient gas", true);
      return;
    }

    // Confirm and send
    if (!confirm(`Send ${amountStr} USDT? Estimated gas ≈ ${ethers.formatEther(gasCheck.estimatedFeeWei)} BNB (with buffer).`)) {
      setStatus("payment cancelled");
      return;
    }

    setStatus("Sending token transfer — confirm in wallet...");
    const tokenContract = new ethers.Contract(tokenAddr, ERC20_ABI, signer);
    const tx = await tokenContract.transfer(receiver, requiredWei);
    setStatus("Tx sent: " + tx.hash + " — waiting 1 confirmation...");
    await tx.wait(1);

    // verify with server
    setStatus("Verifying server...");
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrf = csrfMeta ? csrfMeta.getAttribute('content') : null;
    const resp = await fetch("/api/payment/verify-token", {
      method: "POST",
      headers: { "Content-Type": "application/json", ...(csrf?{"X-CSRF-TOKEN":csrf}:{}) },
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

/* Wire up on DOM ready */
document.addEventListener("DOMContentLoaded", async () => {
  initDom();

  // expose functions
  window.connectWalletConnect = openConnectQr;
  window.openConnectQr = openConnectQr;
  window.restoreSession = restoreSession;
  window.refreshBalance = () => fetchBalanceFromServer();
  window.payWithToken = payWithToken;

  // try to silently restore if a saved wallet exists (doesn't guarantee provider session)
  if (window.SAVED_WALLET) {
    try { await restoreSession(); } catch (e) { console.warn("silent restore failed", e); }
  } else {
    setStatus("no saved wallet");
  }

  // reconnect button fallback
  if (reconnectBtn) {
    reconnectBtn.onclick = () => {
      if (window.openConnectQr) window.openConnectQr();
      else location.reload();
    };
  }

  // poll server-side balance so UI updates if something changed
  setInterval(() => { fetchBalanceFromServer(); }, 15000);
});
