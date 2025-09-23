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
// Replace your existing paySubscription() with this function
export async function paySubscription() {
  try {
    if (!signer || !connectedAddress) {
      alert("Please connect your wallet first.");
      return;
    }

    // Amount (string) set by Blade: window.AMOUNT (native token e.g. "1")
    const amountStr = window.AMOUNT;
    if (!amountStr) {
      alert("Payment amount not defined.");
      return;
    }

    const receiver = window.RECEIVER || import.meta.env.VITE_RECEIVER_WALLET || "";
    if (!receiver) {
      alert("Receiver wallet not configured.");
      return;
    }

    setStatus("Preparing transaction...");

    // Convert amounts to wei (BigInt)
    const valueWei = ethers.parseEther(String(amountStr)); // BigInt

    // 1) balance
    const balanceWei = await provider.getBalance(connectedAddress); // BigInt

    // 2) estimate gas limit for simple transfer
    let gasLimit;
    try {
      gasLimit = await provider.estimateGas({ from: connectedAddress, to: receiver, value: valueWei });
    } catch (e) {
      // fallback to safe basic transfer gas
      gasLimit = 21000n;
    }

    // 3) get reasonable gas price / EIP-1559 fields
    const feeData = await provider.getFeeData();
    let gasPriceWei;
    if (feeData.maxFeePerGas) {
      // use maxFeePerGas if available (EIP-1559)
      gasPriceWei = feeData.maxFeePerGas;
    } else if (feeData.gasPrice) {
      gasPriceWei = feeData.gasPrice;
    } else {
      // conservative fallback 30 gwei
      gasPriceWei = ethers.parseUnits("30", "gwei");
    }

    // Estimated fee = gasLimit * gasPrice
    const estimatedFeeWei = gasLimit * gasPriceWei; // BigInt

    // Small safety buffer to avoid borderline failures (e.g., price spikes)
    const bufferWei = ethers.parseUnits("0.0005", "ether"); // ~0.0005 native token

    const totalNeededWei = valueWei + estimatedFeeWei + bufferWei;

    // If balance covers total, proceed normally
    if (balanceWei >= totalNeededWei) {
      setStatus("Sending transaction (wallet)...");
      const tx = await signer.sendTransaction({ to: receiver, value: valueWei });
      setStatus("Tx sent: " + tx.hash + " — waiting 1 confirmation...");
      await tx.wait(1);
      setStatus("Verifying transaction with server...");
      // verify server call (same as before)
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
          amount: amountStr,
          tx_hash: tx.hash,
          from_address: connectedAddress
        })
      });
      const verifyJson = await verifyResp.json();
      if (verifyJson.ok) {
        setStatus("Payment verified ✔");
        if (window.RETURN_URL && window.RETURN_URL.length) {
          const u = new URL(window.RETURN_URL);
          u.searchParams.set("user_id", window.USER_ID);
          u.searchParams.set("tx", tx.hash);
          window.location.href = u.toString();
        } else {
          alert("Payment successful: " + tx.hash);
        }
      } else {
        setStatus("Verification failed: " + (verifyJson.error || "unknown"), true);
        alert("Verification failed: " + (verifyJson.error || JSON.stringify(verifyJson)));
      }
      return;
    }

    // Not enough funds to send full amount + gas. Compute shortfall and suggest options.
    const shortfallWei = totalNeededWei - balanceWei; // BigInt (how much MATIC missing)
    const shortfall = ethers.formatEther(shortfallWei);

    // Option A: inform user to top up
    const needMsg = `Insufficient funds.\nYou have ${ethers.formatEther(balanceWei)} available.\n` +
                    `To send ${amountStr} you'd need approx ${ethers.formatEther(totalNeededWei)} (amount + fee + buffer).\n` +
                    `You are short ~${shortfall} native token.`;

    // Offer to send a reduced amount that fits their balance, if that makes sense.
    // Compute maximum sendable value = balance - estimatedFee - buffer
    const maxSendableWei = balanceWei - estimatedFeeWei - bufferWei;
    if (maxSendableWei <= 0n) {
      // Can't even cover fee
      alert(needMsg + "\n\nPlease top up native token to cover the network fee.");
      setStatus("Insufficient funds for fee", true);
      return;
    }

    // If maxSendable is less than 1e-9, treat as zero
    const maxSendable = ethers.formatEther(maxSendableWei);

    const tryReduced = confirm(needMsg + `\n\nYou can send up to ${maxSendable} (after reserving gas). Send reduced amount instead?`);

    if (!tryReduced) {
      setStatus("Payment cancelled by user", true);
      return;
    }

    // Proceed with reduced amount (rounded down to 9 decimal places to be safe)
    // Use BigInt math: use maxSendableWei directly as value
    const reducedValueWei = maxSendableWei;
    const reducedValue = ethers.formatEther(reducedValueWei);

    setStatus(`Sending reduced amount ${reducedValue} (wallet)...`);
    const tx2 = await signer.sendTransaction({
      to: receiver,
      value: reducedValueWei
    });

    setStatus("Tx sent: " + tx2.hash + " — waiting 1 confirmation...");
    await tx2.wait(1);

    setStatus("Verifying reduced tx with server...");
    const csrfMeta2 = document.querySelector('meta[name="csrf-token"]');
    const csrf2 = csrfMeta2 ? csrfMeta2.getAttribute('content') : null;
    const verifyResp2 = await fetch("/api/payment/verify", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        ...(csrf2 ? { "X-CSRF-TOKEN": csrf2 } : {})
      },
      body: JSON.stringify({
        user_id: window.USER_ID,
        amount: ethers.formatEther(reducedValueWei), // note: server expects amount as decimal string — adapt if needed
        tx_hash: tx2.hash,
        from_address: connectedAddress
      })
    });
    const verifyJson2 = await verifyResp2.json();
    if (verifyJson2.ok) {
      setStatus("Payment (reduced) verified ✔");
      if (window.RETURN_URL && window.RETURN_URL.length) {
        const u2 = new URL(window.RETURN_URL);
        u2.searchParams.set("user_id", window.USER_ID);
        u2.searchParams.set("tx", tx2.hash);
        window.location.href = u2.toString();
      } else {
        alert("Payment successful: " + tx2.hash);
      }
    } else {
      setStatus("Verification failed: " + (verifyJson2.error || "unknown"), true);
      alert("Verification failed: " + (verifyJson2.error || JSON.stringify(verifyJson2)));
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
