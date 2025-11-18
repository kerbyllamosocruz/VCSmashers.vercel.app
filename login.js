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
  const passwordInput = document.getElementById("password");
  const eyeIcon = document.getElementById("eye-icon");
  const togglePasswordButton = document.getElementById("toggle-password");

  const MAX_LOGIN_ATTEMPTS = 5;
  const LOGIN_COOLDOWN_MS = 30_000;
  const STORAGE_KEYS = {
    attempts: "loginAttempts",
    cooldown: "loginCooldownUntil",
  };
  let cooldownTimer = null;

  const eyeIconMarkup = {
    default:
      '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" /><circle cx="12" cy="12" r="3" />',
    hidden:
      '<path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.77 21.77 0 0 1 5.06-6.94M9.88 9.88A3 3 0 0 0 12 15a3 3 0 0 0 3-3M1 1l22 22" />',
  };

  if (!loginModal) return;

  const getStoredAttempts = () => parseInt(localStorage.getItem(STORAGE_KEYS.attempts) || "0", 10);
  const setStoredAttempts = (value) => localStorage.setItem(STORAGE_KEYS.attempts, String(value));
  const clearStoredAttempts = () => localStorage.removeItem(STORAGE_KEYS.attempts);
  const getCooldownUntil = () => parseInt(localStorage.getItem(STORAGE_KEYS.cooldown) || "0", 10);
  const setCooldownUntil = (timestamp) => localStorage.setItem(STORAGE_KEYS.cooldown, String(timestamp));
  const clearCooldown = () => localStorage.removeItem(STORAGE_KEYS.cooldown);

  const remainingCooldown = () => Math.max(0, getCooldownUntil() - Date.now());
  const isLoginBlocked = () => remainingCooldown() > 0;

  const toggleFormDisabled = (disabled) => {
    if (!loginForm) return;
    const fields = loginForm.querySelectorAll("input, button");
    fields.forEach((field) => {
      if (field) field.disabled = disabled;
    });
  };

  const updateCooldownState = () => {
    const remainingMs = remainingCooldown();

    if (remainingMs > 0) {
      toggleFormDisabled(true);
      if (signInBtn) signInBtn.disabled = true;
      if (errorEl) {
        const seconds = Math.ceil(remainingMs / 1000);
        errorEl.textContent = `Too many login attempts. Try again in ${seconds}s.`;
      }

      if (cooldownTimer) clearTimeout(cooldownTimer);
      cooldownTimer = setTimeout(updateCooldownState, 1_000);
    } else {
      toggleFormDisabled(false);
      if (errorEl && errorEl.textContent && errorEl.textContent.includes("Too many login attempts")) {
        errorEl.textContent = "";
      }
      if (cooldownTimer) {
        clearTimeout(cooldownTimer);
        cooldownTimer = null;
      }
      clearCooldown();
      clearStoredAttempts();
    }
  };

  const recordFailedAttempt = () => {
    const currentAttempts = getStoredAttempts() + 1;
    const attempts = Math.min(currentAttempts, MAX_LOGIN_ATTEMPTS);
    setStoredAttempts(attempts);

    if (attempts >= MAX_LOGIN_ATTEMPTS) {
      setCooldownUntil(Date.now() + LOGIN_COOLDOWN_MS);
    }

    updateCooldownState();
  };

  const syncCooldownFromServer = (secondsRemaining) => {
    const seconds = Number(secondsRemaining);
    if (Number.isNaN(seconds) || seconds <= 0) return;
    setCooldownUntil(Date.now() + seconds * 1000);
    updateCooldownState();
  };

  const clearAttemptTracking = () => {
    clearStoredAttempts();
    clearCooldown();
    updateCooldownState();
  };

  const resetLoginForm = () => {
    if (loginForm) loginForm.reset();
    if (errorEl && !isLoginBlocked()) errorEl.textContent = "";
    if (signInBtn && !isLoginBlocked()) signInBtn.disabled = false;
    if (passwordInput) passwordInput.type = "password";
    if (eyeIcon) eyeIcon.innerHTML = eyeIconMarkup.default;
  };

  const hideLoginModal = () => {
    loginModal.classList.add("modal-hidden");
    resetLoginForm();
  };

  window.hideLoginModal = hideLoginModal;

  const resetRegisterFlow = () => {
    if (typeof window.resetRegisterFlow === "function") {
      window.resetRegisterFlow();
    }
  };

  const hideRegisterModal = () => {
    if (registerModal) {
      registerModal.classList.add("modal-hidden");
      resetRegisterFlow();
    }
  };

  openLoginBtns.forEach((btn) => {
    btn.addEventListener("click", () => loginModal.classList.remove("modal-hidden"));
  });

  if (openRegisterLink) {
    openRegisterLink.addEventListener("click", (e) => {
      e.preventDefault();
      hideLoginModal();
      registerModal && registerModal.classList.remove("modal-hidden");
    });
  }

  if (backToLoginLink) {
    backToLoginLink.addEventListener("click", (e) => {
      e.preventDefault();
      hideRegisterModal();
      loginModal.classList.remove("modal-hidden");
    });
  }

  // Close modals
  closeBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      hideLoginModal();
      hideRegisterModal();
    });
  });

  loginModal.addEventListener("click", (e) => {
    if (e.target === loginModal) hideLoginModal();
  });
  if (registerModal) {
    registerModal.addEventListener("click", (e) => {
      if (e.target === registerModal) hideRegisterModal();
    });
  }

  togglePasswordButton?.addEventListener("click", () => {
    const isPassword = passwordInput.type === "password";
    passwordInput.type = isPassword ? "text" : "password";
    eyeIcon.innerHTML = isPassword ? eyeIconMarkup.hidden : eyeIconMarkup.default;
  });

  updateCooldownState();

  if (loginForm) {
    loginForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      if (isLoginBlocked()) {
        if (errorEl) {
          const seconds = Math.ceil(remainingCooldown() / 1000);
          errorEl.textContent = `Too many login attempts. Try again in ${seconds}s.`;
        }
        return;
      }

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
            clearAttemptTracking();
            window.location.href = data.redirect ?? "landing.html";
          } else {
            errorEl.textContent = data.message || "Invalid credentials";
            if (typeof data.lockoutRemaining !== "undefined") {
              syncCooldownFromServer(data.lockoutRemaining);
              setStoredAttempts(MAX_LOGIN_ATTEMPTS);
            } else {
              recordFailedAttempt();
            }
          }
        } else {
          const text = await res.text();
          console.error("Unexpected login response:", res.status, text);
          errorEl.textContent = "Server error — check console (see network tab).";
          recordFailedAttempt();
        }
      } catch (err) {
        console.error("Login fetch error:", err);
        errorEl.textContent = "Network error. Try again.";
        recordFailedAttempt();
      } finally {
        if (signInBtn) signInBtn.disabled = isLoginBlocked();
      }
    });
  }
}

document.addEventListener("DOMContentLoaded", () => {
  initLoginModal();
});
