// resources/js/app.js
import { ethers } from "ethers";
import EthereumProvider from "@walletconnect/ethereum-provider";

// --- Config ---
const PROJECT_ID =
  import.meta.env.VITE_WC_PROJECT_ID ||
  "611536788e4297012ef34993004d5565";

// Default to BSC
const CHAIN_IDS = [56];

// RPC map for multiple chains
const RPC_MAP = {
  56: "https://bsc-dataseed.binance.org/",
  137: "https://polygon-rpc.com",
  1: "https://eth.llamarpc.com",
};

// ERC20 ABI (minimal)
const ERC20_ABI = [
  "function balanceOf(address) view returns (uint256)",
  "function decimals() view returns (uint8)",
  "function symbol() view returns (string)",
  "function transfer(address to, uint256 value) returns (bool)",
];

// --- DOM elements ---
const walletAddrEl = document.getElementById("walletAddr");
const walletBalanceEl = document.getElementById("walletBalance");
const statusEl = document.getElementById("status");
const payBtn = document.getElementById("payBtn");

// --- State ---
let provider = null;
let signer = null;
let connectedAddress = null;

// --- Helpers ---
function setStatus(text, isError = false) {
  if (!statusEl) return;
  statusEl.innerText = `Status: ${text}`;
  statusEl.style.color = isError ? "crimson" : "";
}
function normalizeAddress(a = "") {
  try {
    return (a || "").toLowerCase();
  } catch {
    return ("" + a).toLowerCase();
  }
}

// --- Ensure chain (BSC) ---
async function ensureChain(targetChainId = 56) {
  if (!provider) return;
  try {
    const net = await provider.getNetwork();
    const current = Number(net?.chainId || 0);
    if (current === targetChainId) return;

    const hex = "0x" + targetChainId.toString(16);
    try {
      await provider.send("wallet_switchEthereumChain", [{ chainId: hex }]);
      return;
    } catch (err) {
      console.warn("wallet_switchEthereumChain failed:", err);
      setStatus(
        "Please switch your wallet manually to BNB Smart Chain (BSC) and reconnect.",
        true
      );
      throw err;
    }
  } catch (err) {
    console.warn("ensureChain error:", err);
    throw err;
  }
}
async function ensureBsc() {
  return ensureChain(56);
}

// --- Init WalletConnect provider ---
async function initWcProvider({ showQrModal = false } = {}) {
  const cfg = {
    projectId: PROJECT_ID,
    chains: CHAIN_IDS,
    showQrModal,
    rpcMap: RPC_MAP,
    optionalChains: [137, 1],
  };
  return await EthereumProvider.init(cfg);
}

// --- Connect ---
export async function connectWalletConnect() {
  try {
    setStatus("connecting...");
    const wcProvider = await initWcProvider({ showQrModal: true });
    await wcProvider.enable();

    provider = new ethers.BrowserProvider(wcProvider);
    signer = await provider.getSigner();
    connectedAddress = await signer.getAddress();

    if (walletAddrEl) walletAddrEl.innerText = connectedAddress;
    setStatus("connected: " + connectedAddress);
    if (payBtn) payBtn.disabled = false;

    try {
      await ensureBsc();
    } catch (e) {
      console.warn("Could not auto-switch to BSC:", e);
    }

    await refreshBalance();
    provider.on("block", () => refreshBalance());

    return connectedAddress;
  } catch (err) {
    console.error("connectWalletConnect failed:", err);
    setStatus("connect failed: " + (err.message || err), true);
    throw err;
  }
}

// --- Refresh balances (native + token) ---
async function refreshBalance() {
  try {
    const address = connectedAddress; // already connected via wallet
    if (!address) return;

    // Call Laravel API instead of ethers.js
    const res = await fetch(`/wallet/balance/${address}?token=0x55d398326f99059fF775485246999027B3197955`);
    const data = await res.json();

    if (data.ok) {
      const native = data.native.balance + " " + data.native.symbol;
      const token = data.token ? (data.token.balance + " USDT") : "—";
      document.getElementById("walletBalance").innerText = `${native} | ${token}`;
    } else {
      document.getElementById("walletBalance").innerText = "Error";
      console.error("API error:", data);
    }
  } catch (e) {
    document.getElementById("walletBalance").innerText = "Error fetching";
    console.error(e);
  }
}

// --- Pay with token (USDT) ---
export async function payWithToken() {
  try {
    if (!signer || !connectedAddress) {
      alert("Please connect your wallet first.");
      return;
    }

    const network = await provider.getNetwork();
    const chainId = Number(network.chainId);

    let tokenAddress;
    if (chainId === 56)
      tokenAddress = "0x55d398326f99059fF775485246999027B3197955";
    else if (chainId === 137)
      tokenAddress = "0xc2132D05D31c914a87C6611C10748AEb04B58e8F";
    else if (chainId === 1)
      tokenAddress = "0xdAC17F958D2ee523a2206206994597C13D831ec7";
    else tokenAddress = window.USDT_CONTRACT;

    if (!tokenAddress) {
      alert("USDT token not supported for this chain.");
      return;
    }

    const receiver = window.RECEIVER;
    const amountStr = window.AMOUNT;

    if (!receiver || !amountStr) {
      alert("Receiver or amount missing.");
      return;
    }

    const token = new ethers.Contract(tokenAddress, ERC20_ABI, signer);

    let decimals = 18;
    try {
      decimals = Number(await token.decimals());
    } catch {
      if (chainId === 56) decimals = 18;
      if (chainId === 137 || chainId === 1) decimals = 6;
    }

    let symbol = "USDT";
    try {
      symbol = await token.symbol();
    } catch {}

    const want = ethers.parseUnits(String(amountStr), decimals);
    const bal = await token.balanceOf(connectedAddress);
    if (bal < want) {
      alert(
        `Insufficient ${symbol}. Have ${ethers.formatUnits(
          bal,
          decimals
        )}, need ${amountStr}`
      );
      return;
    }

    setStatus(`Sending ${amountStr} ${symbol}...`);
    const tx = await token.transfer(receiver, want);
    setStatus("Tx sent: " + tx.hash + " (waiting 1 conf...)");
    await tx.wait(1);

    setStatus("Verifying server...");
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrf = csrfMeta ? csrfMeta.getAttribute("content") : null;
    const resp = await fetch("/api/payment/verify-token", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        ...(csrf ? { "X-CSRF-TOKEN": csrf } : {}),
      },
      body: JSON.stringify({
        user_id: window.USER_ID,
        token_contract: tokenAddress,
        amount: amountStr,
        tx_hash: tx.hash,
        from_address: connectedAddress,
        receiver: receiver,
      }),
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
    setStatus("payment error: " + (err.message || err), true);
    alert("Payment error: " + (err.message || err));
  }
}

// --- Expose for Blade ---
window.connectWalletConnect = connectWalletConnect;
window.refreshBalance = refreshBalance;
window.payWithToken = payWithToken;
