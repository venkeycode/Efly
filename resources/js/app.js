// resources/js/app.js
import { ethers } from "ethers";
import EthereumProvider from "@walletconnect/ethereum-provider";

// Config
const PROJECT_ID = import.meta.env.VITE_WC_PROJECT_ID || "611536788e4297012ef34993004d5565";
const CHAIN_IDS = [(Number(import.meta.env.VITE_CHAIN_ID) || 137)]; // Polygon default

// ERC-20 ABI minimal
const ERC20_ABI = [
  "function balanceOf(address) view returns (uint256)",
  "function decimals() view returns (uint8)",
  "function symbol() view returns (string)",
  "function transfer(address to, uint256 value) returns (bool)"
];

// DOM elements
const walletAddrEl = document.getElementById("walletAddr");
const walletBalanceEl = document.getElementById("walletBalance");
const statusEl = document.getElementById("status");
const payBtn = document.getElementById("payBtn");

// State
let provider = null;
let signer = null;
let connectedAddress = null;

// Helpers
function setStatus(text, isError = false) {
  if (!statusEl) return;
  statusEl.innerText = `Status: ${text}`;
  statusEl.style.color = isError ? "crimson" : "";
}
function normalizeAddress(a = "") {
  try { return (a || "").toLowerCase(); } catch { return ("" + a).toLowerCase(); }
}

// --- Connect ---
export async function connectWalletConnect() {
  try {
    setStatus("connecting...");
    const ethProvider = await EthereumProvider.init({
      projectId: PROJECT_ID,
      chains: CHAIN_IDS,
      showQrModal: true,
    });
    await ethProvider.enable();

    provider = new ethers.BrowserProvider(ethProvider);
    signer = await provider.getSigner();
    connectedAddress = await signer.getAddress();

    if (walletAddrEl) walletAddrEl.innerText = connectedAddress;
    setStatus("connected: " + connectedAddress);
    if (payBtn) payBtn.disabled = false;

    await refreshBalance();
    provider.on("block", () => refreshBalance());

    // Save wallet
    try {
      const csrfMeta = document.querySelector('meta[name="csrf-token"]');
      const csrf = csrfMeta ? csrfMeta.getAttribute("content") : null;
      if (csrf && window.USER_ID) {
        const saveResp = await fetch("/customer/wallet/save", {
          method: "POST",
          headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrf, "Accept": "application/json" },
          body: JSON.stringify({ wallet_address: connectedAddress, user_id: window.USER_ID })
        });
        const saveJson = await saveResp.json();
        if (!saveJson.ok) setStatus("connected (save failed)", true);
        else setStatus("connected & saved");
      }
    } catch (e) {
      console.warn("wallet save error:", e);
    }

    // Check against saved wallet
    if (window.SAVED_WALLET) {
      const saved = normalizeAddress(window.SAVED_WALLET);
      if (normalizeAddress(connectedAddress) !== saved) {
        setStatus("Connected address differs from saved wallet", true);
        if (payBtn) payBtn.disabled = true;
      }
    }

    return connectedAddress;
  } catch (err) {
    console.error("connectWalletConnect failed:", err);
    setStatus("connect failed: " + (err.message || err), true);
    throw err;
  }
}

// --- Refresh balances (native + token) ---
export async function refreshBalance() {
  try {
    if (!provider || !connectedAddress) {
      if (walletBalanceEl) walletBalanceEl.innerText = "—";
      return;
    }
    const balWei = await provider.getBalance(connectedAddress);
    const bal = ethers.formatEther(balWei);

    // Also fetch token (USDT) balance if contract configured
    const tokenAddress = window.USDT_CONTRACT;
    let tokenBal = "";
    if (tokenAddress) {
      const { token, decimals, symbol } = await getTokenInfo(tokenAddress, provider);
      const raw = await token.balanceOf(connectedAddress);
      tokenBal = ethers.formatUnits(raw, decimals) + " " + symbol;
    }

    if (walletBalanceEl) walletBalanceEl.innerText = `${parseFloat(bal).toFixed(4)} MATIC | ${tokenBal}`;
  } catch (err) {
    console.error("refreshBalance failed:", err);
    if (walletBalanceEl) walletBalanceEl.innerText = "Error";
  }
}

// --- ERC20 helpers ---
async function getTokenInfo(tokenAddress, provider) {
  const token = new ethers.Contract(tokenAddress, ERC20_ABI, provider);
  const decimals = await token.decimals().catch(()=>18);
  const symbol = await token.symbol().catch(()=>"TOKEN");
  return { token, decimals, symbol };
}

// --- Pay with token (USDT) ---
export async function payWithToken() {
  try {
    if (!signer || !connectedAddress) {
      alert("Please connect your wallet first.");
      return;
    }
    const tokenAddress = window.USDT_CONTRACT;
    const receiver = window.RECEIVER;
    const amountStr = window.AMOUNT; // decimal like "1.0"

    if (!tokenAddress || !receiver || !amountStr) {
      alert("Token address, receiver, or amount missing.");
      return;
    }

    setStatus("Checking balances...");
    const { token, decimals, symbol } = await getTokenInfo(tokenAddress, signer.provider);
    const want = ethers.parseUnits(String(amountStr), decimals);
    const bal = await token.balanceOf(connectedAddress);
    if (bal < want) {
      alert(`Insufficient ${symbol}. Have ${ethers.formatUnits(bal, decimals)}, need ${amountStr}`);
      setStatus("Insufficient token balance", true);
      return;
    }

    // send tx
    setStatus("Sending token transfer...");
    const tx = await token.connect(signer).transfer(receiver, want);
    setStatus("Tx sent: " + tx.hash + " (waiting 1 conf...)");
    await tx.wait(1);

    // verify with server
    setStatus("Verifying server...");
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrf = csrfMeta ? csrfMeta.getAttribute("content") : null;
    const resp = await fetch("/api/payment/verify-token", {
      method: "POST",
      headers: { "Content-Type": "application/json", ...(csrf ? { "X-CSRF-TOKEN": csrf } : {}) },
      body: JSON.stringify({
        user_id: window.USER_ID,
        token_contract: tokenAddress,
        amount: amountStr,
        tx_hash: tx.hash,
        from_address: connectedAddress,
        receiver: receiver
      })
    });
    const j = await resp.json();
    if (j.ok) {
      setStatus("Payment verified ✔");
      if (window.RETURN_URL) {
        const u = new URL(window.RETURN_URL);
        u.searchParams.set("user_id", window.USER_ID);
        u.searchParams.set("tx", tx.hash);
        window.location.href = u.toString();
      } else {
        alert("Payment success: " + tx.hash);
      }
    } else {
      setStatus("Verification failed", true);
      alert("Verify failed: " + (j.error || JSON.stringify(j)));
    }
  } catch (err) {
    console.error("payWithToken error:", err);
    setStatus("payment error: " + (err.message||err), true);
    alert("Payment error: " + (err.message||err));
  }
}

// Expose to Blade
window.connectWalletConnect = connectWalletConnect;
window.refreshBalance = refreshBalance;
window.payWithToken = payWithToken;
