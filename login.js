function initLoginModal() {
  const loginModal = document.getElementById("loginModal");
  const registerModal = document.getElementById("registerModal");
  const openLoginBtns = [document.getElementById("loginBtn"), document.getElementById("loginBtn2")].filter(Boolean);
  const openRegisterLink = document.getElementById("openRegisterModal");
  const backToLoginLink = document.getElementById("backToLogin");
  const closeBtns = [document.getElementById("closeModal"), document.getElementById("closeRegisterModal")].filter(Boolean);

  // Open Login
  openLoginBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      loginModal.classList.remove("modal-hidden");
    });
  });

  // Switch to Register
  if (openRegisterLink) {
    openRegisterLink.addEventListener("click", (e) => {
      e.preventDefault();
      loginModal.classList.add("modal-hidden");
      registerModal.classList.remove("modal-hidden");
    });
  }

  // Switch back to Login
  if (backToLoginLink) {
    backToLoginLink.addEventListener("click", (e) => {
      e.preventDefault();
      registerModal.classList.add("modal-hidden");
      loginModal.classList.remove("modal-hidden");
    });
  }

  
  closeBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      loginModal.classList.add("modal-hidden");
      registerModal.classList.add("modal-hidden");
    });
  });

  
  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(loginForm);
      const res = await fetch("index.php", {
        method: "POST",
        body: formData,
        headers: { "X-Requested-With": "XMLHttpRequest" },
      });
      const data = await res.json();

      if (data.success) {
        window.location.href = data.redirect || "index.php";
      } else {
        document.getElementById("loginError").textContent = data.message;
      }
    });
  }

  
  const registerForm = document.getElementById("registerForm");
  if (registerForm) {
    // Register form submission handled inside register-modal.php inline script
  }
}

function initRegisterForm() {
  const registerModal = document.getElementById("registerModal");
  const loginModal = document.getElementById("loginModal");
  const registerForm = document.getElementById("registerForm");
  const alertBox = document.getElementById("registerAlert");

  if (!registerForm || !alertBox) return;

  // Avoid double-binding
  if (registerForm.__bound) return;
  registerForm.__bound = true;

  registerForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    alertBox.classList.add("hidden");

    const formData = new FormData(registerForm);
    try {
      const res = await fetch("register.php", { method: "POST", body: formData });
      const data = await res.json();

      alertBox.textContent = data.message || "Registration failed.";
      alertBox.classList.remove("hidden");

      if (data.status === "success") {
        alertBox.className = "p-3 rounded-lg text-center text-white font-semibold bg-green-500";
        setTimeout(() => {
          registerModal && registerModal.classList.add("modal-hidden");
          loginModal && loginModal.classList.remove("modal-hidden");
        }, 1500);
      } else {
        alertBox.className = "p-3 rounded-lg text-center text-white font-semibold bg-red-500";
      }
    } catch (err) {
      alertBox.textContent = "An unexpected error occurred.";
      alertBox.className = "p-3 rounded-lg text-center text-white font-semibold bg-red-500";
      alertBox.classList.remove("hidden");
    }
  });
}
