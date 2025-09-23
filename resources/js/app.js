import { ethers } from "ethers";
import EthereumProvider from "@walletconnect/ethereum-provider";

// Config
const PROJECT_ID = import.meta.env.VITE_WC_PROJECT_ID || "611536788e4297012ef34993004d5565";
const CHAIN_IDS = [(Number(import.meta.env.VITE_CHAIN_ID) || 56)]; // ✅ BSC default
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

// --- Refresh balances (BNB + USDT) ---
// --- Refresh balances (native + token) ---
// --- Robust refreshBalance() to avoid BAD_DATA decode errors ---
export async function refreshBalance() {
  try {
    if (!provider || !connectedAddress) {
      if (walletBalanceEl) walletBalanceEl.innerText = "—";
      return;
    }

    // 1) native balance + network
    let nativeText = "—";
    let tokenText = "—";
    try {
      const balWei = await provider.getBalance(connectedAddress);
      nativeText = parseFloat(ethers.formatEther(balWei)).toFixed(6);
    } catch (err) {
      console.error("Native balance read failed:", err);
      nativeText = "Error";
    }

    let network;
    try {
      network = await provider.getNetwork();
    } catch (err) {
      console.warn("getNetwork failed:", err);
      network = { chainId: null, name: "unknown" };
    }
    const chainId = Number(network.chainId);
    // human native symbol per known chain
    const nativeSymbol = chainId === 56 ? "BNB" : chainId === 137 ? "MATIC" : chainId === 1 ? "ETH" : "NATIVE";

    // 2) choose token contract for chain (auto-detect)
    let tokenAddress = null;
    if (chainId === 137) {
      tokenAddress = "0xc2132D05D31c914a87C6611C10748AEb04B58e8F"; // Polygon USDT
    } else if (chainId === 56) {
      tokenAddress = "0x55d398326f99059fF775485246999027B3197955"; // BSC USDT
    } else if (chainId === 1) {
      tokenAddress = "0xdAC17F958D2ee523a2206206994597C13D831ec7"; // Ethereum USDT
    } else {
      // fallback to any provided global override
      tokenAddress = window.USDT_CONTRACT || null;
    }

    // 3) token: only attempt if we have an address AND the contract exists on chain
    if (tokenAddress) {
      try {
        // 3a) ensure contract exists on chain (avoid calling non-existent contract)
        const code = await provider.getCode(tokenAddress);
        if (!code || code === "0x" || code === "0x0") {
          console.warn("Token contract not found on this chain:", tokenAddress, "code:", code);
          tokenText = "Token not deployed on this chain";
        } else {
          // 3b) safe contract calls with per-call catch
          const token = new ethers.Contract(tokenAddress, ERC20_ABI, provider);

          // decimals
          let decimals = 18;
          try {
            const d = await token.decimals();
            // some tokens return BigInt-like, some number — normalize
            decimals = Number(d) || decimals;
          } catch (err) {
            console.warn("decimals() failed, defaulting to 18 (or use chain-specific overrides):", err);
            // override known USDT decimals per chain
            if (chainId === 137 || chainId === 1) decimals = 6; // polygon/eth USDT = 6
            if (chainId === 56) decimals = 18; // BSC USDT in your detector used 18
          }

          // symbol
          let symbol = "TOKEN";
          try {
            symbol = await token.symbol();
          } catch (_) { /* ignore */ }

          // balanceOf
          let rawBal = null;
          try {
            rawBal = await token.balanceOf(connectedAddress);
          } catch (err) {
            // sometimes provider returns empty data -> catch and handle
            console.error("balanceOf() call failed (likely wrong contract/chain):", err);
            rawBal = null;
          }

          if (rawBal === null) {
            tokenText = `0 ${symbol}`; // show 0 rather than throw
          } else {
            // format units safely
            let formatted = "0";
            try {
              formatted = ethers.formatUnits(rawBal, decimals);
            } catch (err) {
              console.warn("formatUnits failed:", err);
              // fallback: try dividing manually (careful with precision) — show raw as fallback
              formatted = rawBal.toString();
            }
            // show with safe decimal places
            const decimalsToShow = decimals === 6 ? 2 : 2;
            tokenText = parseFloat(formatted).toFixed(decimalsToShow) + " " + symbol;
          }
        }
      } catch (err) {
        console.error("Token read flow failed:", err);
        tokenText = "Error";
      }
    } else {
      tokenText = "No token configured";
    }

    // 4) update UI (use nativeSymbol)
    if (walletBalanceEl) {
      // choose readable nativeText (if numeric, format)
      let nativeDisplay = nativeText;
      if (!isNaN(Number(nativeText))) nativeDisplay = parseFloat(nativeText).toFixed(6) + " " + nativeSymbol;
      walletBalanceEl.innerText = `${nativeDisplay}  |  ${tokenText}`;
    }
  } catch (err) {
    console.error("refreshBalance failed outer:", err);
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
