// resources/js/app.js
import { ethers } from "ethers";
import EthereumProvider from "@walletconnect/ethereum-provider";

// Config (optionally exposed via Vite env or set in Blade)
const PROJECT_ID = import.meta.env.VITE_WC_PROJECT_ID || "611536788e4297012ef34993004d5565";
const CHAIN_IDS = [(Number(import.meta.env.VITE_CHAIN_ID) || 137)]; // default polygon

// DOM elements (Blade should contain these ids)
const walletAddrEl = document.getElementById("walletAddr");
const walletBalanceEl = document.getElementById("walletBalance");
const statusEl = document.getElementById("status"); // optional status element
const payBtn = document.getElementById("payBtn"); // optional pay button on page

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
  try { return (a || "").toLowerCase(); } catch (e) { return ("" + a).toLowerCase(); }
}

// --- Connect (WalletConnect v2 + MetaMask) ---
export async function connectWalletConnect() {
  try {
    setStatus("connecting...");
    // initialize WalletConnect provider
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

    // enable pay button only if present
    if (payBtn) payBtn.disabled = false;

    // refresh balance now and on new blocks
    await refreshBalance();
    try { provider.on("block", () => refreshBalance()); } catch (e) { /* ignore */ }

    // Optionally save the wallet to your backend (keeps existing flow)
    try {
      const csrfMeta = document.querySelector('meta[name="csrf-token"]');
      const csrf = csrfMeta ? csrfMeta.getAttribute('content') : null;
      if (csrf && window.USER_ID) {
        const saveResp = await fetch("/customer/wallet/save", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrf,
            "Accept": "application/json"
          },
          body: JSON.stringify({
            wallet_address: connectedAddress,
            user_id: window.USER_ID
          })
        });
        const saveJson = await saveResp.json();
        if (!saveJson.ok) {
          console.warn("save wallet returned not ok:", saveJson);
          // don't fail the connect flow — just inform
          setStatus("connected (save failed)", true);
        } else {
          setStatus("connected & saved");
        }
      }
    } catch (e) {
      console.warn("error saving wallet to server:", e);
    }

    // If server provided a saved wallet and it doesn't match, warn & disable pay
    if (window.SAVED_WALLET) {
      const saved = normalizeAddress(window.SAVED_WALLET);
      if (normalizeAddress(connectedAddress) !== saved) {
        setStatus("Connected address differs from saved wallet. Use the saved wallet to pay.", true);
        if (payBtn) payBtn.disabled = true;
      } else {
        if (payBtn) payBtn.disabled = false;
      }
    }

    return connectedAddress;
  } catch (err) {
    console.error("connectWalletConnect failed:", err);
    setStatus("connect failed: " + (err.message || err), true);
    throw err;
  }
}

// --- Refresh native balance ---
export async function refreshBalance() {
  try {
    if (!provider || !connectedAddress) {
      if (walletBalanceEl) walletBalanceEl.innerText = "—";
      return;
    }
    const balWei = await provider.getBalance(connectedAddress);
    const bal = ethers.formatEther(balWei);
    if (walletBalanceEl) walletBalanceEl.innerText = parseFloat(bal).toFixed(4) + " MATIC";
  } catch (err) {
    console.error("refreshBalance failed:", err);
    if (walletBalanceEl) walletBalanceEl.innerText = "Error";
  }
}

// --- Payment: user signs tx in their wallet; server verifies ---
export async function paySubscription() {
  try {
    if (!signer || !connectedAddress) {
      alert("Please connect your wallet first.");
      return;
    }

    // If server provided a saved wallet, ensure the connected address matches
    if (window.SAVED_WALLET && normalizeAddress(window.SAVED_WALLET) !== normalizeAddress(connectedAddress)) {
      alert("Connected wallet does not match saved wallet. Please connect the saved wallet to pay.");
      return;
    }

    // amount & receiver should be set from Blade or env
    const amount = window.AMOUNT || prompt("Enter amount to pay (in native token)", "0.5");
    if (!amount) return;

    const receiver = window.RECEIVER || import.meta.env.VITE_RECEIVER_WALLET || "";
    if (!receiver) {
      alert("Receiver wallet is not configured on the page.");
      return;
    }

    setStatus("sending transaction from wallet...");
    const tx = await signer.sendTransaction({
      to: receiver,
      value: ethers.parseEther(amount)
    });

    setStatus("tx sent: " + tx.hash + " — waiting for 1 confirmation...");
    // wait for 1 confirmation (reduces false positives from pending tx)
    await tx.wait(1);

    setStatus("verifying transaction with server...");
    // call Laravel /api/payment/verify or your verify endpoint
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrf = csrfMeta ? csrfMeta.getAttribute('content') : null;
    const verifyResp = await fetch("/api/payment/verify", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        ...(csrf ? { "X-CSRF-TOKEN": csrf } : {})
      },
      body: JSON.stringify({
        user_id: window.USER_ID,
        amount: amount,
        tx_hash: tx.hash,
        from_address: connectedAddress
      })
    });

    const verifyJson = await verifyResp.json();
    if (verifyJson.ok) {
      setStatus("payment verified ✔");
      // Optional: redirect back to CI if a return URL is provided
      if (window.RETURN_URL && window.RETURN_URL.length) {
        const u = new URL(window.RETURN_URL);
        u.searchParams.set("user_id", window.USER_ID);
        u.searchParams.set("tx", tx.hash);
        window.location.href = u.toString();
      } else {
        alert("Payment success: " + tx.hash);
      }
    } else {
      console.error("verify failed:", verifyJson);
      setStatus("verification failed: " + (verifyJson.error || "unknown"), true);
      alert("Verification failed: " + (verifyJson.error || JSON.stringify(verifyJson)));
    }
  } catch (err) {
    console.error("paySubscription error:", err);
    setStatus("payment error: " + (err.message || err), true);
    alert("Payment error: " + (err.message || err));
  }
}

// Expose to Blade inline handlers
window.connectWalletConnect = connectWalletConnect;
window.paySubscription = paySubscription;
window.refreshBalance = refreshBalance;
