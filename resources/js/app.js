// resources/js/app.js
import { ethers } from "ethers";
import EthereumProvider from "@walletconnect/ethereum-provider";

/**
 * Config
 */
const PROJECT_ID = import.meta.env.VITE_WC_PROJECT_ID || "611536788e4297012ef34993004d5565";
const CHAIN_IDS = [Number(import.meta.env.VITE_CHAIN_ID) || 56]; // default BSC mainnet
const RPC_MAP = {
  56: "https://bsc-dataseed.binance.org/",
  137: "https://polygon-rpc.com",
  1: "https://rpc.ankr.com/eth"
};

// Minimal ERC20 ABI
const ERC20_ABI = [
  "function balanceOf(address) view returns (uint256)",
  "function decimals() view returns (uint8)",
  "function symbol() view returns (string)",
  "function transfer(address to, uint256 value) returns (bool)"
];

/**
 * State
 */
let wcProvider = null;     // WalletConnect raw provider
let provider = null;       // ethers BrowserProvider
let signer = null;
let connectedAddress = null;

/**
 * DOM elements (populated on DOMContentLoaded)
 */
let walletAddrEl = null;
let walletBalanceEl = null;
let statusEl = null;
let payBtn = null;
let reconnectBtn = null;

/**
 * Helpers
 */
function setStatus(text, isError = false) {
  if (!statusEl) return;
  statusEl.innerText = `Status: ${text}`;
  statusEl.style.color = isError ? "crimson" : "";
}
function normalize(a='') { try { return (a||'').toLowerCase(); } catch { return String(a).toLowerCase(); } }
function getUsdtForChain(chainId) {
  if (chainId === 56) return "0x55d398326f99059fF775485246999027B3197955"; // BSC USDT (18)
  if (chainId === 137) return "0xc2132D05D31c914a87C6611C10748AEb04B58e8F"; // Polygon USDT (6)
  if (chainId === 1) return "0xdAC17F958D2ee523a2206206994597C13D831ec7"; // ETH USDT (6)
  return null;
}

/**
 * Initialize DOM refs once page loads
 */
function initDom() {
  walletAddrEl = document.getElementById("walletAddr");
  walletBalanceEl = document.getElementById("walletBalance");
  statusEl = document.getElementById("status");
  payBtn = document.getElementById("payBtn");
  reconnectBtn = document.getElementById("reconnectBtn");
}

/**
 * Create & init WalletConnect provider
 * showQrModal: boolean -> whether to open QR (true) or try silent restore (false)
 */
async function initWcProvider({ showQrModal = false } = {}) {
  // Use the CHAIN_IDS constant so the config is consistent
  const cfg = {
    projectId: PROJECT_ID,
    chains: CHAIN_IDS,
    showQrModal,
    rpcMap: RPC_MAP,
    optionalChains: [137, 1]
  };

  // init() returns object with enable()
  const ethProv = await EthereumProvider.init(cfg);
  return ethProv;
}

/**
 * Try to restore an existing WalletConnect session silently (no QR).
 * If success, sets provider/signer/connectedAddress and updates UI.
 */
export async function restoreSession() {
  setStatus("restoring session...");
  try {
    wcProvider = await initWcProvider({ showQrModal: false });
    // enable resolves only if session exists
    await wcProvider.enable();

    provider = new ethers.BrowserProvider(wcProvider);
    signer = await provider.getSigner();
    connectedAddress = await signer.getAddress();

    if (walletAddrEl) walletAddrEl.innerText = connectedAddress;
    setStatus("connected: " + connectedAddress);
    if (payBtn) payBtn.disabled = false;
    if (reconnectBtn) reconnectBtn.style.display = "none";

    // start balance refresh and listen for blocks
    await refreshBalance();
    try { provider.on("block", () => refreshBalance()); } catch(e){}

    // check saved wallet mismatch (if blade provided SAVED_WALLET)
    if (window.SAVED_WALLET) {
      const saved = normalize(window.SAVED_WALLET);
      if (normalize(connectedAddress) !== saved) {
        setStatus("Connected account differs from saved wallet", true);
        if (payBtn) payBtn.disabled = true;
      }
    }

    return connectedAddress;
  } catch (err) {
    console.warn("restoreSession failed:", err);
    setStatus("no active wallet session");
    if (reconnectBtn) reconnectBtn.style.display = "inline-block";
    if (payBtn) payBtn.disabled = true;
    return null;
  }
}

/**
 * Open WalletConnect QR for fresh connect
 */
export async function openConnectQr() {
  setStatus("opening WalletConnect QR...");
  try {
    wcProvider = await initWcProvider({ showQrModal: true });
    await wcProvider.enable();

    provider = new ethers.BrowserProvider(wcProvider);
    signer = await provider.getSigner();
    connectedAddress = await signer.getAddress();

    if (walletAddrEl) walletAddrEl.innerText = connectedAddress;
    setStatus("connected: " + connectedAddress);
    if (payBtn) payBtn.disabled = false;
    if (reconnectBtn) reconnectBtn.style.display = "none";

    // optionally save connected wallet to server (if user_id present)
    try {
      if (window.USER_ID) {
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        await fetch("/customer/wallet/save", {
          method: "POST",
          headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrf, "Accept": "application/json" },
          body: JSON.stringify({ wallet_address: connectedAddress, user_id: window.USER_ID })
        });
      }
    } catch (e) {
      console.warn("Failed to save wallet to server:", e);
    }

    await refreshBalance();
    try { provider.on("block", () => refreshBalance()); } catch(e){}
    return connectedAddress;
  } catch (err) {
    console.error("openConnectQr error:", err);
    setStatus("connect failed: " + (err.message || err), true);
    throw err;
  }
}

/**
 * Connect flow (explicit) - calls QR and returns address
 */
export async function connectWalletConnect() {
  return await openConnectQr();
}

/**
 * Robust refreshBalance: reads native balance and token (USDT) safely
 */
export async function refreshBalance() {
  try {
    if (!provider || !connectedAddress) {
      if (walletBalanceEl) walletBalanceEl.innerText = "—";
      return;
    }

    // Native balance
    let nativeDisplay = "—";
    try {
      const balWei = await provider.getBalance(connectedAddress);
      const network = await provider.getNetwork();
      const nativeSymbol = network.chainId === 56 ? "BNB" : network.chainId === 137 ? "MATIC" : network.chainId === 1 ? "ETH" : "NATIVE";
      nativeDisplay = parseFloat(ethers.formatEther(balWei)).toFixed(6) + " " + nativeSymbol;
    } catch (err) {
      console.warn("native read failed:", err);
      nativeDisplay = "Error";
    }

    // Token (auto-detect USDT per chain)
    let tokenDisplay = "—";
    try {
      const network = await provider.getNetwork();
      const chainId = Number(network.chainId || 0);
      const tokenAddr = getUsdtForChain(chainId) || window.USDT_CONTRACT || null;

      if (!tokenAddr) {
        tokenDisplay = "No token configured";
      } else {
        const code = await provider.getCode(tokenAddr).catch(()=> "0x");
        if (!code || code === "0x" || code === "0x0") {
          tokenDisplay = "Token not on chain";
        } else {
          const token = new ethers.Contract(tokenAddr, ERC20_ABI, provider);

          // decimals safe
          let decimals = 18;
          try { decimals = Number(await token.decimals()) || decimals; } catch (e) {
            if (chainId === 137 || chainId === 1) decimals = 6;
            if (chainId === 56) decimals = 18;
            console.warn("decimals fallback used:", decimals, e);
          }

          let symbol = "TOKEN";
          try { symbol = await token.symbol(); } catch (e){}

          let raw = null;
          try { raw = await token.balanceOf(connectedAddress); } catch (e) { raw = null; console.warn("balanceOf failed:", e); }

          if (raw === null) tokenDisplay = `0 ${symbol}`;
          else {
            const formatted = ethers.formatUnits(raw, decimals);
            tokenDisplay = parseFloat(formatted).toFixed(decimals === 6 ? 2 : 2) + " " + symbol;
          }
        }
      }
    } catch (err) {
      console.error("token read failed:", err);
      tokenDisplay = "Error";
    }

    if (walletBalanceEl) walletBalanceEl.innerText = `${nativeDisplay}  |  ${tokenDisplay}`;
    setStatus("balance updated");
  } catch (err) {
    console.error("refreshBalance outer failed:", err);
    if (walletBalanceEl) walletBalanceEl.innerText = "Error";
  }
}

/**
 * payWithToken: detect token by chain, check balances, check gas, send transfer and call server verify.
 */
export async function payWithToken() {
  try {
    if (!signer || !connectedAddress) {
      alert("Please connect your wallet first.");
      return;
    }

    const net = await provider.getNetwork();
    const chainId = Number(net.chainId);
    const tokenAddress = getUsdtForChain(chainId) || window.USDT_CONTRACT;
    if (!tokenAddress) {
      alert("Token not configured for this chain. Switch network or contact admin.");
      return;
    }

    const receiver = window.RECEIVER;
    const amountStr = window.AMOUNT;
    if (!receiver || !amountStr) {
      alert("Receiver or amount missing.");
      return;
    }

    setStatus("Checking balances...");

    const token = new ethers.Contract(tokenAddress, ERC20_ABI, provider);
    const tokenWithSigner = token.connect(signer);

    // decimals fallback
    let decimals = 18;
    try { decimals = Number(await token.decimals()); } catch (e) {
      if (chainId === 137 || chainId === 1) decimals = 6;
      if (chainId === 56) decimals = 18;
    }

    const want = ethers.parseUnits(String(amountStr), decimals);

    // balance check
    const bal = await token.balanceOf(connectedAddress);
    if (bal < want) {
      alert(`Insufficient token balance. Have ${ethers.formatUnits(bal, decimals)}, need ${amountStr}`);
      setStatus("Insufficient token", true);
      return;
    }

    // gas / native balance check
    let gasLimit;
    try { gasLimit = await tokenWithSigner.estimateGas.transfer(receiver, want); } catch (e) { gasLimit = 150000n; }
    const feeData = await provider.getFeeData();
    const gasPriceRaw = feeData.maxFeePerGas ?? feeData.gasPrice ?? ethers.parseUnits("5", "gwei");
    const gasPrice = BigInt(gasPriceRaw.toString());
    const estimatedFee = gasPrice * BigInt(gasLimit);
    const nativeBal = await provider.getBalance(connectedAddress);
    if (nativeBal < estimatedFee) {
      alert("Insufficient native token (BNB/ETH/MATIC) for gas. Please top up gas token.");
      setStatus("Insufficient native for gas", true);
      return;
    }

    // send tx
    setStatus(`Sending ${amountStr} ... confirm in wallet`);
    const tx = await tokenWithSigner.transfer(receiver, want);
    setStatus("Tx sent: " + tx.hash + " (waiting 1 conf...)");
    await tx.wait(1);

    // verify with backend
    setStatus("Verifying transaction with server...");
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrf = csrfMeta ? csrfMeta.getAttribute("content") : null;
    const resp = await fetch("/api/payment/verify-token", {
      method: "POST",
      headers: { "Content-Type":"application/json", ...(csrf?{"X-CSRF-TOKEN":csrf}:{}) },
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
      alert("Payment success: " + tx.hash);
      if (window.RETURN_URL) {
        const u = new URL(window.RETURN_URL);
        u.searchParams.set("user_id", window.USER_ID);
        u.searchParams.set("tx", tx.hash);
        window.location.href = u.toString();
      }
    } else {
      setStatus("Verification failed", true);
      alert("Verify failed: " + (j.error || JSON.stringify(j)));
    }

  } catch (err) {
    console.error("payWithToken error:", err);
    setStatus("payment error: " + (err.message || err), true);
    alert("Payment error: " + (err.message || err));
  }
}

/**
 * DOM ready: wire up and try restoring session if saved wallet exists
 */
document.addEventListener("DOMContentLoaded", async () => {
  initDom();

  // Expose debug functions on window
  window.connectWalletConnect = connectWalletConnect;
  window.refreshBalance = refreshBalance;
  window.payWithToken = payWithToken;
  window.restoreSession = restoreSession;
  window.openConnectQr = openConnectQr;

  // If there is a saved wallet in DB, try to silently restore session
  if (window.SAVED_WALLET) {
    try {
      await restoreSession();
    } catch(e){
      console.warn("silent restore failed", e);
    }
  } else {
    setStatus("no saved wallet");
  }
});
