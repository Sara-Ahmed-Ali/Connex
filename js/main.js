let firstDiv = document.querySelector(".navbar0")


window.addEventListener("scroll",function(){
let x= this.window.scrollY

if(x > 100){
    firstDiv.style.display="none"
} else{
    firstDiv.style.display="block"
}
})



  window.onload = function () {
    const paymentBtn = document.getElementById("go-to-payment");
    if (paymentBtn) {
      paymentBtn.addEventListener("click", function () {
        localStorage.setItem("redirectAfterLogin", "subscribe.php");
        window.location.href = "expertLOGIN.php";
      });
    }
  
    const loginForm = document.getElementById("user-loginPage");
    if (loginForm) {
      loginForm.addEventListener("submit", function (e) {
        e.preventDefault();
  
        const redirectPage = localStorage.getItem("redirectAfterLogin");
  
        if (redirectPage) {
          localStorage.removeItem("redirectAfterLogin");
          window.location.href = redirectPage;
        } 
      });
    }
  };

const form = document.getElementById("registrationForm");

const nameInput = document.getElementById("user_name");
const idInput = document.getElementById("regist-id");
const phoneInput = document.getElementById("regist-phone");
const addressInput = document.getElementById("regist-address");
const passwordInput = document.getElementById("user_password");
const emailInput = document.getElementById("regist-email");

// Live filtering for National ID: only digits, max 14
idInput.addEventListener("input", function () {
  this.value = this.value.replace(/\D/g, '').slice(0, 14);
});

// Hide all errors
function hideErrors() {
  document.querySelectorAll(".error-message").forEach(span => {
    span.textContent = "";
    span.style.display = "none";
  });
}

// Show error under specific input
function showError(inputId, message) {
  const errorSpan = document.getElementById(inputId + "_error");
  if (errorSpan) {
    errorSpan.textContent = message;
    errorSpan.style.display = "block";
  }
}

form.addEventListener("submit", function (e) {
  let isValid = true;
  hideErrors();

  if (nameInput.value.trim() === "") {
    showError("user_name", "Please enter your name.");
    isValid = false;
  }

  const idVal = idInput.value.trim();
  if (idVal === "") {
    showError("regist-id", "Please enter your national ID.");
    isValid = false;
  } else if (idVal.length !== 14) {
    showError("regist-id", "National ID must be exactly 14 digits.");
    isValid = false;
  }

  if (phoneInput.value.trim() === "") {
    showError("regist-phone", "Please enter your phone number.");
    isValid = false;
  }

  if (addressInput.value.trim() === "") {
    showError("regist-address", "Please enter your address.");
    isValid = false;
  }

  if (passwordInput.value.trim() === "") {
    showError("user_password", "Please enter your password.");
    isValid = false;
  }

  const emailVal = emailInput.value.trim();
  if (emailVal === "") {
    showError("regist-email", "Please enter your email.");
    isValid = false;
  } else if (!emailVal.includes("@")) {
    showError("regist-email", "Please enter a valid email address.");
    isValid = false;
  }

  if (!isValid) {
    e.preventDefault(); // Prevent form submission if invalid
  }
});