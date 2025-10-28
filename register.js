  const registerModal = document.getElementById("registerModal");
  const registerForm = document.getElementById("registerForm");
  const alertBox = document.getElementById("registerAlert");
  const backToLogin = document.getElementById("backToLogin");
  const closeRegisterModal = document.getElementById("closeRegisterModal");

  if (registerForm) { // Add a check to ensure form exists
    registerForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      alertBox.classList.add("hidden");

      const formData = new FormData(registerForm);

      try {
        const res = await fetch("register.php", {
          method: "POST",
          body: formData
        });
        const data = await res.json();

        alertBox.textContent = data.message;
        alertBox.classList.remove("hidden");

        if (data.status === "success") {
          alertBox.className = "text-green-500 text-sm mb-4 text-center";
          setTimeout(() => {
            registerModal.classList.add("modal-hidden");
            const loginModalElement = document.getElementById("loginModal");
            if (loginModalElement) {
              loginModalElement.classList.remove("modal-hidden");
            }
          }, 1500);
        } else {
          alertBox.className = "text-red-500 text-sm mb-4 text-center";
        }

      } catch (error) {
        console.error("Registration error:", error);
        alertBox.textContent = "An unexpected error occurred.";
        alertBox.className = "p-3 rounded-lg text-center text-white font-semibold bg-red-500";
        alertBox.classList.remove("hidden");
      }
    });
  }


  if (closeRegisterModal) {
    closeRegisterModal.addEventListener("click", () => {
      registerModal.classList.add("modal-hidden");
    });
  }

  // Back-to-login is handled globally in login.js to avoid duplicate bindings
