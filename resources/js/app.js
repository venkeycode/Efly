import { ethers } from "ethers";
import EthereumProvider from "@walletconnect/ethereum-provider";

async function connectWalletConnect() {
  const ethProvider = await EthereumProvider.init({
    projectId: "611536788e4297012ef34993004d5565",  // your WalletConnect Project ID
    chains: [137, 80001],  // Polygon mainnet + testnet (remove others if not needed)
    showQrModal: true,
  });

  await ethProvider.enable();

  const provider = new ethers.BrowserProvider(ethProvider);
  const signer = await provider.getSigner();
  const address = await signer.getAddress();

  console.log("Connected:", address);

  // ✅ Update dashboard UI
  document.getElementById("walletAddr").innerText = address;

  // ✅ Start balance auto-refresh
  refreshBalance(provider, address);
  provider.on("block", () => refreshBalance(provider, address));

  // ✅ Save wallet to backend
  const csrf = document.querySelector('meta[name="csrf-token"]').content;
  await fetch("/customer/wallet/save", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": csrf,
      "Accept": "application/json",
    },
    body: JSON.stringify({ wallet_address: address,user_id: window.USER_ID
 }),
  });
}

async function refreshBalance(provider, address) {
  try {
    const balanceWei = await provider.getBalance(address);
    const balance = ethers.formatEther(balanceWei);
    document.getElementById("walletBalance").innerText = parseFloat(balance).toFixed(4) + " MATIC";
  } catch (err) {
    console.error("Balance fetch failed:", err);
    document.getElementById("walletBalance").innerText = "Error";
  }
}

// ✅ Expose for Blade button
window.connectWalletConnect = connectWalletConnect;
