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

/* BSC chain params for wallet_addEthereumChain fallback */
const BSC_PARAMS = {
  chainId: "0x38", // 56
  chainName: "BNB Smart Chain",
  nativeCurrency: { name: "BNB", symbol: "BNB", decimals: 18 },
  rpcUrls: ["https://bsc-dataseed.binance.org/"],
  blockExplorerUrls: ["https://bscscan.com"]
};

/* Minimal ERC-20 ABI */
const ERC20_ABI = [
  "function balanceOf(address) view returns (uint256)",
  "function decimals() view returns (uint8)",
  "function symbol() view returns (string)",
  "function transfer(address to, uint256 value) returns (bool)"
];

/**
 * State
 */
let wcProvider = null;
let provider = null;
let signer = null;
let connectedAddress = null;

/**
 * DOM refs (populated in DOMContentLoaded)
 */
let walletAddrEl = null;
let walletBalanceEl = null;
let statusEl = null;
let payBtn = null;
let reconnectBtn = null;

function initDom() {
  walletAddrEl = document.getElementById("walletAddr");
  walletBalanceEl = document.getElementById("walletBalance");
  statusEl = document.getElementById("status");
  payBtn = document.getElementById("payBtn");
  reconnectBtn = document.getElementById("reconnectBtn");
}

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
 * Initialize WalletConnect provider object via ESM package (Vite bundles it).
 */
async function initWcProvider({ showQrModal = false } = {}) {
  const cfg = {
    projectId: PROJECT_ID,
    chains: CHAIN_IDS,
    showQrModal,
    rpcMap: RPC_MAP,
    optionalChains: [137, 1]
  };
  return await EthereumProvider.init(cfg);
}

/**
 * Ensure wallet (provider) is on target chain (try switch, then add if necessary).
 */
async function ensureChain(targetChainId = 56) {
  if (!provider) return;
  try {
    const net = await provider.getNetwork();
    const current = Number(net?.chainId || 0);
    if (current === targetChainId) return; // already on correct chain

    // Try switch
    await provider.send("wallet_switchEthereumChain", [{ chainId: "0x" + targetChainId.toString(16) }]);
    // success -> now on target chain
    return;
  } catch (err) {
    // If switch failed because chain not added, try adding (example BSC)
    try {
      if (targetChainId === 56) {
        await provider.send("wallet_addEthereumChain", [BSC_PARAMS]);
        return;
      }
    } catch (addErr) {
      console.warn("ensureChain add failed:", addErr);
      throw addErr;
    }
    throw err;
  }
}
async function ensureBsc() { return ensureChain(56); }

/**
 * Restore existing WalletConnect session silently (no QR).
 */
export async function restoreSession() {
  setStatus("restoring session...");
  try {
    wcProvider = await initWcProvider({ showQrModal: false });
    await wcProvider.enable(); // resolves only if session exists

    provider = new ethers.BrowserProvider(wcProvider);
    signer = await provider.getSigner();
    connectedAddress = await signer.getAddress();

    // Attempt to switch to BSC automatically (user will be prompted in wallet if needed)
    try { await ensureBsc(); } catch(e){ console.warn("could not ensure BSC:", e); }

    if (walletAddrEl) walletAddrEl.innerText = connectedAddress;
    setStatus("connected: " + connectedAddress);
    if (payBtn) payBtn.disabled = false;
    if (reconnectBtn) reconnectBtn.style.display = "none";

    await refreshBalance();
    try { provider.on("block", () => refreshBalance()); } catch(e){}

    // Validate saved wallet match (if provided)
    if (window.SAVED_WALLET) {
      const saved = normalize(window.SAVED_WALLET);
      if (normalize(connectedAddress) !== saved) {
        setStatus("Connected address differs from saved wallet", true);
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
 * Open WalletConnect QR modal for fresh connect
 */
export async function openConnectQr() {
  setStatus("opening WalletConnect QR...");
  try {
    wcProvider = await initWcProvider({ showQrModal: true });
    await wcProvider.enable();

    provider = new ethers.BrowserProvider(wcProvider);
    signer = await provider.getSigner();
    connectedAddress = await signer.getAddress();

    // Attempt chain switch to BSC
    try { await ensureBsc(); } catch(e){ console.warn("could not ensure BSC after connect:", e); }

    if (walletAddrEl) walletAddrEl.innerText = connectedAddress;
    setStatus("connected: " + connectedAddress);
    if (payBtn) payBtn.disabled = false;
    if (reconnectBtn) reconnectBtn.style.display = "none";

    // Optionally save to backend if USER_ID available
    try {
      if (window.USER_ID) {
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        await fetch("/customer/wallet/save", {
          method: "POST",
          headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrf, "Accept": "application/json" },
          body: JSON.stringify({ wallet_address: connectedAddress, user_id: window.USER_ID })
        });
      }
    } catch (e) { console.warn("save wallet to server failed:", e); }

    await refreshBalance();
    try { provider.on("block", () => refreshBalance()); } catch(e){}

    return connectedAddress;
  } catch (err) {
    console.error("openConnectQr failed:", err);
    setStatus("connect failed: " + (err.message || err), true);
    throw err;
  }
}

/**
 * Public connect wrapper used by Blade: connectWalletConnect()
 */
export async function connectWalletConnect() {
  return await openConnectQr();
}

/**
 * Robust refreshBalance: native + USDT token
 */
export async function refreshBalance() {
  try {
    if (!provider || !connectedAddress) {
      if (walletBalanceEl) walletBalanceEl.innerText = "—";
      return;
    }

    // native
    let nativeDisplay = "—";
    try {
      const balWei = await provider.getBalance(connectedAddress);
      const network = await provider.getNetwork();
      const nativeSymbol = network.chainId === 56 ? "BNB" : network.chainId === 137 ? "MATIC" : network.chainId === 1 ? "ETH" : `NATIVE(${network.chainId})`;
      nativeDisplay = parseFloat(ethers.formatEther(balWei)).toFixed(6) + " " + nativeSymbol;
    } catch (err) {
      console.warn("native read failed:", err);
      nativeDisplay = "Error";
    }

    // token
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

          // decimals (safe)
          let decimals = 18;
          try { decimals = Number(await token.decimals()) || decimals; } catch (e) {
            if (chainId === 137 || chainId === 1) decimals = 6;
            if (chainId === 56) decimals = 18;
            console.warn("decimals fallback used:", decimals, e);
          }

          // symbol (safe)
          let symbol = "USDT";
          try { const s = await token.symbol(); if (s) symbol = s; } catch (e) {}

          // balanceOf (safe)
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
 * Pay with token (USDT) — user signs the token transfer
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
      alert("USDT token not configured for this chain. Please switch network to BSC.");
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

    // send transfer
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
 * DOM ready wiring
 */
document.addEventListener("DOMContentLoaded", async () => {
  initDom();

  // expose functions
  window.connectWalletConnect = connectWalletConnect;
  window.refreshBalance = refreshBalance;
  window.payWithToken = payWithToken;
  window.restoreSession = restoreSession;
  window.openConnectQr = openConnectQr;

  // If saved wallet present, try silent restore
  if (window.SAVED_WALLET) {
    try { await restoreSession(); } catch (e) { console.warn("silent restore failed", e); }
  } else {
    setStatus("no saved wallet");
  }
});
