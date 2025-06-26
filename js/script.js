document.addEventListener("DOMContentLoaded", () => {
  const verificationForm = document.getElementById("verification-form");
  const resultContainer = document.getElementById("result-container");    
  const resultSuccess = document.getElementById("result-success");
  const resultError = document.getElementById("result-error");
  const productName = document.getElementById("product-name");
  const productUid = document.getElementById("product-uid");
  const productDescription = document.getElementById("product-description");
  const uidInput = document.getElementById("uid");
  const verifyButton = verificationForm.querySelector("button");

  let debounceTimer;

  uidInput.addEventListener("input", () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
      resultContainer.classList.add("hidden");
      resultSuccess.classList.add("hidden");
      resultError.classList.add("hidden");
    }, 500);
  });

  verificationForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const uid = uidInput.value.trim();

    if (!uid) {
      showToast("Please enter a product UID", "warning");
      uidInput.focus();
      return;
    }

    // Disable UI during fetch
    uidInput.disabled = true;
    verifyButton.disabled = true;
    verifyButton.textContent = "Verifying...";

    try {
      const response = await fetch("api/check_product.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "uid=" + encodeURIComponent(uid),
      });

      if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

      const text = await response.text();
      let data;

      try {
        data = JSON.parse(text);
      } catch {
        throw new Error("Invalid JSON from server: " + text);
      }

      // Re-enable UI
      verifyButton.textContent = "Verify Product";
      verifyButton.disabled = false;
      uidInput.disabled = false;

      resultContainer.classList.remove("hidden");

      if (data.success && data.product) {
        const status = data.product.verification_status;

        if (status === "Already Used") {
          showToast("⚠️ This UID was already used.", "warning");
          return;
        } else if (status === "Session Verified") {
          showToast("ℹ️ This UID was already verified in this session.", "info");
          return;
        } else if (status === "First-time Verification") {
          showToast("✅ First-time product verification!", "success");
        }

        // Show success result
        resultSuccess.classList.remove("hidden");
        resultError.classList.add("hidden");

        productName.textContent = data.product.product_name || "N/A";
        productUid.textContent = data.product.uid || "N/A";
        productDescription.textContent = data.product.description || "No description available";

        resultContainer.focus();
      } else {
        showError(data.message || "Please enter a valid UID.");
      }

      resultContainer.scrollIntoView({ behavior: "smooth" });
    } catch (error) {
      console.error("Fetch error:", error.message);
      verifyButton.textContent = "Verify Product";
      verifyButton.disabled = false;
      uidInput.disabled = false;
      showError("An error occurred. Please try again later.");
    }
  });

  function showError(message) {
    resultContainer.classList.remove("hidden");
    resultSuccess.classList.add("hidden");
    resultError.classList.remove("hidden");
    const errorMessage = resultError.querySelector("p");
    if (errorMessage) errorMessage.textContent = message;
    showToast(message, "error");
    resultContainer.scrollIntoView({ behavior: "smooth" });
    resultContainer.focus();
  }

  function showToast(message, type = "info") {
    Toastify({
      text: message,
      duration: 4000,
      gravity: "top",
      position: "center",
      close: true,
      backgroundColor:
        type === "success"
          ? "#28a745"
          : type === "error"
          ? "#dc3545"
          : type === "warning"
          ? "#ffc107"
          : "#007bff", // default: info
    }).showToast();
  }
});
