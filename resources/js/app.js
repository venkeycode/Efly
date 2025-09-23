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
async function payWithToken() {
  try {
    // basic checks
    if (!signer) { alert("Please connect wallet first"); return; }
    const connected = (await signer.getAddress()).toLowerCase();
    const saved = (window.SAVED_WALLET || "").toLowerCase();
    if (saved && connected !== saved) {
      if (!confirm("Connected wallet differs from saved wallet. Continue with connected wallet?")) return;
    }

    // token settings (BSC USDT)
    const tokenAddr = window.USDT_CONTRACT || "0x55d398326f99059fF775485246999027B3197955";
    const receiver = window.RECEIVER;
    const wantStr = String(window.AMOUNT || "1");

    // 1) ask server for balance (trusted)
    const balResp = await fetch(`/wallet/balance/${encodeURIComponent(connected)}?token=${encodeURIComponent(tokenAddr)}`);
    if (!balResp.ok) {
      alert("Balance API error");
      console.error(await balResp.text());
      return;
    }
    const balJson = await balResp.json();
    if (!balJson.ok) {
      alert("Balance API returned error: " + (balJson.error||"unknown"));
      console.error(balJson);
      return;
    }

    // server token balance (string decimal)
    const serverTokenBalanceStr = balJson.token?.balance ?? "0";
    const serverTokenDecimals = Number(balJson.token?.decimals ?? 18);

    // compare using bignumber (ethers)
    const wantWei = ethers.parseUnits(wantStr, serverTokenDecimals); // BigInt
    const serverRawWei = balJson.token?.balance_wei ?? "0";
    // convert serverRawWei (string decimal of smallest unit) to BigInt
    const serverRawBig = BigInt(serverRawWei || "0");

    if (serverRawBig < wantWei) {
      alert(`Insufficient ${balJson.token ? "token" : "funds"}. Server shows ${serverTokenBalanceStr}, need ${wantStr}`);
      return;
    }

    // 2) ensure token contract is present on chain (quick provider check)
    const code = await provider.getCode(tokenAddr).catch(()=> "0x");
    if (!code || code === "0x" || code === "0x0") {
      alert("Token contract not found on current network. Switch your wallet to BSC and reconnect.");
      return;
    }

    // 3) check native balance for gas
    const nativeBal = await provider.getBalance(connected);
    // estimate gas for token transfer
    const tokenContract = new ethers.Contract(tokenAddr, ERC20_ABI, provider);
    let gasLimit;
    try {
      gasLimit = await tokenContract.connect(signer).estimateGas.transfer(receiver, wantWei);
    } catch (e) {
      // fallback
      gasLimit = 150000n;
    }
    const feeData = await provider.getFeeData();
    const gasPrice = BigInt((feeData.maxFeePerGas ?? feeData.gasPrice ?? ethers.parseUnits("5", "gwei")).toString());
    const estFee = gasLimit * gasPrice;
    if (nativeBal < estFee) {
      alert("Insufficient native token (BNB) to pay gas. Please top up BNB.");
      return;
    }

    // 4) Send transaction
    setStatus("Sending token transfer — confirm in wallet");
    const tx = await tokenContract.connect(signer).transfer(receiver, wantWei);
    setStatus("Sent tx: " + tx.hash + " — waiting 1 confirmation");
    await tx.wait(1);

    // 5) Verify with backend (your existing verify endpoint)
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrf = csrfMeta ? csrfMeta.getAttribute("content") : null;
    const verifyResp = await fetch("/api/payment/verify-token", {
      method: "POST",
      headers: { "Content-Type":"application/json", ...(csrf?{"X-CSRF-TOKEN":csrf}:{}) },
      body: JSON.stringify({
        user_id: window.USER_ID,
        token_contract: tokenAddr,
        amount: wantStr,
        tx_hash: tx.hash,
        from_address: connected,
        receiver: receiver
      })
    });
    const verifyJson = await verifyResp.json();
    if (verifyJson.ok) {
      setStatus("Payment verified ✔");
      alert("Payment successful: " + tx.hash);
      if (window.RETURN_URL) {
        const u = new URL(window.RETURN_URL);
        u.searchParams.set("user_id", window.USER_ID);
        u.searchParams.set("tx", tx.hash);
        window.location.href = u.toString();
      }
    } else {
      setStatus("Verification failed", true);
      alert("Verification failed: " + (verifyJson.error || JSON.stringify(verifyJson)));
    }

  } catch (err) {
    console.error("payWithToken error:", err);
    alert("Payment error: " + (err.message || err));
    setStatus("payment error: " + (err.message || err), true);
  }
}

// --- Expose for Blade ---
window.connectWalletConnect = connectWalletConnect;
window.refreshBalance = refreshBalance;
window.payWithToken = payWithToken;
