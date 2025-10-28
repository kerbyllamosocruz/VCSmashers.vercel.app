function initLoginModal() {
  const loginModal = document.getElementById("loginModal");
  const registerModal = document.getElementById("registerModal");
  const openLoginBtns = [document.getElementById("loginBtn"), document.getElementById("loginBtn2")].filter(Boolean);
  const openRegisterLink = document.getElementById("openRegisterModal");
  const backToLoginLink = document.getElementById("backToLogin");
  const closeBtns = [document.getElementById("closeModal"), document.getElementById("closeRegisterModal")].filter(Boolean);
  const loginForm = document.getElementById("loginForm");
  const errorEl = document.getElementById("loginError");
  const signInBtn = document.getElementById("signinBtn");

  if (!loginModal) return;

  // Open Login Modal
  openLoginBtns.forEach((btn) => {
    btn.addEventListener("click", () => loginModal.classList.remove("modal-hidden"));
  });

  // Switch to Register
  if (openRegisterLink) {
    openRegisterLink.addEventListener("click", (e) => {
      e.preventDefault();
      loginModal.classList.add("modal-hidden");
      registerModal && registerModal.classList.remove("modal-hidden");
    });
  }

  // Switch back to Login
  if (backToLoginLink) {
    backToLoginLink.addEventListener("click", (e) => {
      e.preventDefault();
      registerModal && registerModal.classList.add("modal-hidden");
      loginModal.classList.remove("modal-hidden");
    });
  }

  // Close modals
  closeBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      loginModal.classList.add("modal-hidden");
      registerModal && registerModal.classList.add("modal-hidden");
    });
  });

  // Close modal by clicking outside
  loginModal.addEventListener("click", (e) => {
    if (e.target === loginModal) loginModal.classList.add("modal-hidden");
  });
  if (registerModal) {
    registerModal.addEventListener("click", (e) => {
      if (e.target === registerModal) registerModal.classList.add("modal-hidden");
    });
  }

  // Login form submission
  if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      if (errorEl) errorEl.textContent = "";

      if (signInBtn) signInBtn.disabled = true;

      try {
        const formData = new FormData(loginForm);
        const res = await fetch("index.php", {
          method: "POST",
          headers: { "X-Requested-With": "XMLHttpRequest" },
          body: formData,
        });

        const contentType = res.headers.get("content-type") || "";

        if (res.ok && contentType.includes("application/json")) {
          const data = await res.json();
          if (data.success) {
            window.location.href = data.redirect ?? "landing.html";
          } else {
            errorEl.textContent = data.message || "Invalid credentials";
          }
        } else {
          const text = await res.text();
          console.error("Unexpected login response:", res.status, text);
          errorEl.textContent = "Server error — check console (see network tab).";
        }
      } catch (err) {
        console.error("Login fetch error:", err);
        errorEl.textContent = "Network error. Try again.";
      } finally {
        if (signInBtn) signInBtn.disabled = false;
      }
    });
  }
}

function initRegisterForm() {
  const registerModal = document.getElementById("registerModal");
  const loginModal = document.getElementById("loginModal");
  const registerForm = document.getElementById("registerForm");
  const errorEl = document.getElementById("registerError");
  const submitBtn = registerForm?.querySelector('button[type="submit"]');

  if (!registerForm || !errorEl) return;

  if (registerForm.__bound) return;
  registerForm.__bound = true;

  registerForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    errorEl.textContent = "";
    
    if (submitBtn) submitBtn.disabled = true;

    try {
      const formData = new FormData(registerForm);
      const res = await fetch("register.php", {
        method: "POST",
        headers: { "X-Requested-With": "XMLHttpRequest" },
        body: formData
      });

      const contentType = res.headers.get("content-type") || "";
      
      if (res.ok && contentType.includes("application/json")) {
        const data = await res.json();
        if (data.status === "success") {
          errorEl.textContent = data.message || "Registration successful!";
          errorEl.className = "text-green-500 text-sm mb-4 text-center";
          setTimeout(() => {
            registerModal?.classList.add("modal-hidden");
            loginModal?.classList.remove("modal-hidden");
          }, 1500);
        } else {
          errorEl.textContent = data.message || "Registration failed";
          errorEl.className = "text-red-500 text-sm mb-4 text-center";
        }
      } else {
        const text = await res.text();
        console.error("Unexpected register response:", res.status, text);
        errorEl.textContent = "Server error — check console";
        errorEl.className = "text-red-500 text-sm mb-4 text-center";
      }
    } catch (err) {
      console.error("Register fetch error:", err);
      errorEl.textContent = "Network error. Try again.";
      errorEl.className = "text-red-500 text-sm mb-4 text-center";
    } finally {
      if (submitBtn) submitBtn.disabled = false;
    }
  });
}

// Initialize both forms when the DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  initLoginModal();
  initRegisterForm();
});
