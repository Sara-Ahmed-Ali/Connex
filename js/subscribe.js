document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("myForm");

  const cardholderInput = document.getElementById("name");
  const cardNumberInput = document.getElementById("card-number");
  const expiryInput = document.getElementById("expiry");
  const cvvInput = document.getElementById("cvv");

  // وظائف التحقق
  function validateCardholder() {
    clearError(cardholderInput);
    if (cardholderInput.value.trim() === "") {
      showError(cardholderInput, "Cardholder name is required.");
      return false;
    }
    return true;
  }

  function validateCardNumber() {
    clearError(cardNumberInput);
    const value = cardNumberInput.value.replace(/\s/g, "");
    if (value === "") {
      showError(cardNumberInput, "Card number is required.");
      return false;
    } else if (!/^\d{16}$/.test(value)) {
      showError(cardNumberInput, "Card number must be 16 digits.");
      return false;
    }
    return true;
  }

  function validateExpiry() {
    clearError(expiryInput);
    const value = expiryInput.value.trim();
    const expiryRegex = /^(0[1-9]|1[0-2])\/(\d{2})$/;
    const match = value.match(expiryRegex);

    if (value === "") {
      showError(expiryInput, "Expiry date is required.");
      return false;
    } else if (!match) {
      showError(expiryInput, "Enter expiry date in MM/YY format.");
      return false;
    } else {
      const inputMonth = parseInt(match[1], 10);
      const inputYear = parseInt(match[2], 10);
      const now = new Date();
      const currentMonth = now.getMonth() + 1;
      const currentYear = now.getFullYear() % 100;

      if (inputYear < currentYear || (inputYear === currentYear && inputMonth < currentMonth)) {
        showError(expiryInput, "Expiry date must be in the future.");
        return false;
      }
    }
    return true;
  }

  function validateCVV() {
    clearError(cvvInput);
    const value = cvvInput.value.trim();
    if (value === "") {
      showError(cvvInput, "CVV is required.");
      return false;
    } else if (!/^\d{3,4}$/.test(value)) {
      showError(cvvInput, "CVV must be 3 or 4 digits.");
      return false;
    }
    return true;
  }

  // ربط الـ events بالتحديث المباشر
  cardholderInput.addEventListener("input", validateCardholder);
  cardNumberInput.addEventListener("input", validateCardNumber);
  expiryInput.addEventListener("input", validateExpiry);
  cvvInput.addEventListener("input", validateCVV);

  // منع الإرسال لو فيه أخطاء
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    let isValid = true;

    if (!validateCardholder()) isValid = false;
    if (!validateCardNumber()) isValid = false;
    if (!validateExpiry()) isValid = false;
    if (!validateCVV()) isValid = false;

    if (isValid) form.submit();
  });

  // مساعدات
  function showError(input, message) {
    input.classList.add("error");
    const errorDiv = document.createElement("div");
    errorDiv.className = "error-message";
    errorDiv.textContent = message;
    input.parentElement.appendChild(errorDiv);
  }

  function clearError(input) {
    input.classList.remove("error");
    const oldMessage = input.parentElement.querySelector('.error-message');
    if (oldMessage) oldMessage.remove();
  }
});
