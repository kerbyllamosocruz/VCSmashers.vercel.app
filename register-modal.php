<div id="registerModal"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 modal modal-hidden">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
    <div class="p-6">
      <div class="flex justify-center items-center mb-4 relative">
        <h3 class="text-2xl font-bold text-primary text-center">Register</h3>
        <button id="closeRegisterModal" class="absolute right-0 text-gray-500 hover:text-gray-700">
          <i data-feather="x"></i>
        </button>
      </div>

      <div id="registerAlert" class="hidden p-3 rounded-lg text-center text-white font-semibold"></div>

      <form id="registerForm" method="POST" action="register.php" class="space-y-4">
        <div>
          <label for="regName" class="block text-sm font-medium text-gray-700">Full Name</label>
          <input type="text" id="regName" name="name" required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" />
        </div>

        <div>
          <label for="regEmail" class="block text-sm font-medium text-gray-700">Email Address</label>
          <input type="email" id="regEmail" name="email" required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" />
        </div>

        <div>
          <label for="regPhone" class="block text-sm font-medium text-gray-700">Phone (optional)</label>
          <input type="tel" id="regPhone" name="phone"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" />
        </div>

        <div>
          <label for="regPassword" class="block text-sm font-medium text-gray-700">Password</label>
          <input type="password" id="regPassword" name="pass" required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none" />
        </div>

        <div>
          <button type="submit"
            class="w-full py-2 px-4 bg-primary text-white font-bold rounded-md hover:bg-opacity-90 transition">
            Register
          </button>
        </div>
      </form>

      <div class="mt-4 text-center text-sm text-gray-600">
        Already have an account?
        <a href="#" id="backToLogin" class="font-medium text-primary hover:text-opacity-80">Back to Login</a>
      </div>
    </div>
  </div>
</div>


<script>
document.addEventListener("DOMContentLoaded", () => {
  const registerModal = document.getElementById("registerModal");
  const loginModal = document.getElementById("loginModal");
  const registerForm = document.getElementById("registerForm");
  const alertBox = document.getElementById("registerAlert");
  const backToLogin = document.getElementById("backToLogin");
  const closeRegisterModal = document.getElementById("closeRegisterModal");

  registerForm.addEventListener("submit", async (e) => {
    e.preventDefault(); // stop form from reloading page
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
        alertBox.className = "p-3 rounded-lg text-center text-white font-semibold bg-green-500";
        setTimeout(() => {
          registerModal.classList.add("modal-hidden");
          loginModal?.classList.remove("modal-hidden");
        }, 1500);
      } else {
        alertBox.className = "p-3 rounded-lg text-center text-white font-semibold bg-red-500";
      }

    } catch (error) {
      alertBox.textContent = "An unexpected error occurred.";
      alertBox.className = "p-3 rounded-lg text-center text-white font-semibold bg-red-500";
      alertBox.classList.remove("hidden");
    }
  });

  if (closeRegisterModal) {
    closeRegisterModal.addEventListener("click", () => {
      registerModal.classList.add("modal-hidden");
    });
  }

  // Back-to-login is handled globally in login.js to avoid duplicate bindings
});
</script>
