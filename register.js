  const registerModal = document.getElementById("registerModal");
  const registerForm = document.getElementById("registerForm");
  const otpForm = document.getElementById("otpForm");
  const registerAlert = document.getElementById("registerAlert");
  const otpAlert = document.getElementById("otpAlert");
  const backToLogin = document.getElementById("backToLogin");
  const closeRegisterModal = document.getElementById("closeRegisterModal");
  const registerFormContainer = document.getElementById("registerFormContainer");
  const otpFormContainer = document.getElementById("otpFormContainer");

  if (registerForm) {
    registerForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      registerAlert.classList.add("hidden");

      const formData = new FormData(registerForm);

      try {
        const res = await fetch("send_otp.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();

        registerAlert.textContent = data.message;
        registerAlert.classList.remove("hidden");

        if (data.status === "success") {
          registerAlert.className = "text-green-500 text-sm mb-4 text-center";
          registerFormContainer.classList.add("hidden");
          otpFormContainer.classList.remove("hidden");
        } else {
          registerAlert.className = "text-red-500 text-sm mb-4 text-center";
        }

      } catch (error) {
        console.error("Registration error:", error);
        registerAlert.textContent = "An unexpected error occurred.";
        registerAlert.className = "p-3 rounded-lg text-center text-white font-semibold bg-red-500";
        registerAlert.classList.remove("hidden");
      }
    });
  }

  if (otpForm) {
    otpForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      otpAlert.classList.add("hidden");

      const formData = new FormData(otpForm);

      try {
        const res = await fetch("register.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();

        otpAlert.textContent = data.message;
        otpAlert.classList.remove("hidden");

        if (data.status === "success") {
          otpAlert.className = "text-green-500 text-sm mb-4 text-center";
          setTimeout(() => {
            registerModal.classList.add("modal-hidden");
            const loginModalElement = document.getElementById("loginModal");
            if (loginModalElement) {
              loginModalElement.classList.remove("modal-hidden");
            }
          }, 1500);
        } else {
          otpAlert.className = "text-red-500 text-sm mb-4 text-center";
        }

      } catch (error) {
        console.error("OTP verification error:", error);
        otpAlert.textContent = "An unexpected error occurred.";
        otpAlert.className = "p-3 rounded-lg text-center text-white font-semibold bg-red-500";
        otpAlert.classList.remove("hidden");
      }
    });
  }

  if (closeRegisterModal) {
    closeRegisterModal.addEventListener("click", () => {
      registerModal.classList.add("modal-hidden");
    });
  }

  // Back-to-login is handled globally in login.js to avoid duplicate bindings
