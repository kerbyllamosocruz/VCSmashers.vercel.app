 const registerModal = document.getElementById("registerModal");
 const registerForm = document.getElementById("registerForm");
 const otpForm = document.getElementById("otpForm");
 const registerAlert = document.getElementById("registerAlert");
 const otpAlert = document.getElementById("otpAlert");
 const backToLogin = document.getElementById("backToLogin");
 const closeRegisterModal = document.getElementById("closeRegisterModal");
 const registerFormContainer = document.getElementById("registerFormContainer");
 const otpFormContainer = document.getElementById("otpFormContainer");

 const registerAlertDefaultClass = registerAlert ? registerAlert.className : "hidden mb-4 text-center text-sm";
 const otpAlertDefaultClass = otpAlert ? otpAlert.className : "hidden mb-4 text-center text-sm";

 const resetAlert = (alertEl, defaultClass) => {
   if (!alertEl) return;
   alertEl.textContent = "";
   alertEl.className = defaultClass;
   if (!alertEl.classList.contains("hidden")) {
     alertEl.classList.add("hidden");
   }
 };

 const resetRegisterFlow = () => {
   if (registerForm) registerForm.reset();
   if (otpForm) otpForm.reset();

   resetAlert(registerAlert, registerAlertDefaultClass);
   resetAlert(otpAlert, otpAlertDefaultClass);

   if (registerFormContainer) registerFormContainer.classList.remove("hidden");
   if (otpFormContainer) otpFormContainer.classList.add("hidden");
 };

 window.resetRegisterFlow = resetRegisterFlow;
 resetRegisterFlow();
 window.addEventListener("pageshow", (event) => {
   if (event.persisted) {
     resetRegisterFlow();
   }
 });

 // Password visibility toggle for registration
 const regPasswordInput = document.getElementById("regPassword");
 const regEyeIcon = document.getElementById("eye-icon-reg");
 const toggleRegPasswordButton = document.getElementById("toggle-reg-password");
 toggleRegPasswordButton?.addEventListener("click", () => {
   const isPassword = regPasswordInput.type === "password";
   regPasswordInput.type = isPassword ? "text" : "password";
   regEyeIcon.innerHTML = isPassword
     ? '<path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a21.77 21.77 0 0 1 5.06-6.94M9.88 9.88A3 3 0 0 0 12 15a3 3 0 0 0 3-3M1 1l22 22" />'
     : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" /><circle cx="12" cy="12" r="3" />';
 });

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
            resetRegisterFlow();
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
      resetRegisterFlow();
    });
  }

  // Back-to-login is handled globally in login.js to avoid duplicate bindings
