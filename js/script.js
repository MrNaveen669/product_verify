document.addEventListener('DOMContentLoaded', () => {
  // Select elements
  const verificationForm = document.getElementById('verification-form');
  const resultContainer = document.getElementById('result-container');
  const resultSuccess = document.getElementById('result-success');
  const resultError = document.getElementById('result-error');
  const productName = document.getElementById('product-name');
  const productUid = document.getElementById('product-uid');
  const productDescription = document.getElementById('product-description');
  const uidInput = document.getElementById('uid');
  const verifyButton = verificationForm.querySelector('button');

  // Debounce timer to hide results when user edits input
  let debounceTimer;

  uidInput.addEventListener('input', () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
      // Hide results if user changes input
      resultContainer.classList.add('hidden');
      resultSuccess.classList.add('hidden');
      resultError.classList.add('hidden');
    }, 500);
  });

  verificationForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const uid = uidInput.value.trim();

    if (!uid) {
      showError('Please enter a product UID');
      uidInput.focus();
      return;
    }

    // Disable input and button during verification
    uidInput.disabled = true;
    verifyButton.disabled = true;
    verifyButton.textContent = 'Verifying...';

    try {
      const response = await fetch('api/check_product.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'uid=' + encodeURIComponent(uid),
      });

      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }

      const data = await response.json();

      // Reset button and input state
      verifyButton.textContent = 'Verify Product';
      verifyButton.disabled = false;
      uidInput.disabled = false;

      // Show result container
      resultContainer.classList.remove('hidden');

      if (data && data.success && data.product) {
        // Show success result
        resultSuccess.classList.remove('hidden');
        resultError.classList.add('hidden');

        // Populate product details
        productName.textContent = data.product.product_name || 'N/A';
        productUid.textContent = data.product.uid || 'N/A';
        productDescription.textContent = data.product.description || 'No description available';

        // Focus result container for accessibility
        resultContainer.focus();
      } else {
        // Show error result
        resultSuccess.classList.add('hidden');
        resultError.classList.remove('hidden');
        resultError.querySelector('p').textContent = data.message || 'Product not found.';
        resultContainer.focus();
      }

      // Scroll to results
      resultContainer.scrollIntoView({ behavior: 'smooth' });
    } catch (error) {
      console.error('Fetch error:', error);

      verifyButton.textContent = 'Verify Product';
      verifyButton.disabled = false;
      uidInput.disabled = false;

      showError('An error occurred. Please try again later.');
    }
  });

  // Helper function to show error
  function showError(message) {
    resultContainer.classList.remove('hidden');
    resultSuccess.classList.add('hidden');
    resultError.classList.remove('hidden');
    resultError.querySelector('p').textContent = message;
    resultContainer.scrollIntoView({ behavior: 'smooth' });
    resultContainer.focus();
  }
});
