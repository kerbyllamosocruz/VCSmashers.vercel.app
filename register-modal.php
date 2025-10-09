<?php
// This file only contains the modal HTML
?>
<!-- Registration Modal -->
<div id="registerModal"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 modal modal-hidden">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 p-10">
    <div class="flex justify-center items-center mb-4 relative">
      <h2 class="text-3xl font-bold text-primary text-center">Create Account</h2>
      <button id="closeRegisterModal" class="absolute right-0 text-gray-500 hover:text-gray-700">
        <i data-feather="x"></i>
      </button>
    </div>

    <form action="register.php" method="POST" class="space-y-5">
      <!-- Full Name -->
      <div>
        <label for="name" class="block text-sm font-semibold text-gray-700">Full Name</label>
        <input type="text" name="name" id="name" placeholder="John Doe"
          class="mt-1 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none"
          required />
      </div>

      <!-- Email -->
      <div>
        <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
        <input type="email" name="email" id="email" placeholder="example@email.com"
          class="mt-1 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none"
          required />
      </div>

      <!-- Phone -->
      <div>
        <label for="phone" class="block text-sm font-semibold text-gray-700">Phone</label>
        <input type="tel" name="phone" id="phone" placeholder="09123456789" pattern="[0-9]{11}" maxlength="11"
          class="mt-1 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none"
          required />
      </div>

      <!-- Password -->
      <div>
        <label for="pass" class="block text-sm font-semibold text-gray-700">Password</label>
        <input type="password" name="pass" id="pass" placeholder="********"
          class="mt-1 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:outline-none"
          required minlength="6" />
      </div>

      <!-- Submit Button -->
      <button type="submit"
        class="w-full bg-primary text-white font-bold py-3 px-4 rounded-lg hover:bg-accent transition">
        Register
      </button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-6">
      Already have an account?
      <a href="#" id="openLoginFromRegister" class="text-primary font-semibold hover:underline">Login here</a>
    </p>
  </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", () => {
  const loginModal = document.getElementById("loginModal");
  const registerModal = document.getElementById("registerModal");
  const openRegisterModal = document.getElementById("openRegisterModal");
  const openLoginFromRegister = document.getElementById("openLoginFromRegister");

  if (openRegisterModal) {
    openRegisterModal.addEventListener("click", (e) => {
      e.preventDefault();
      loginModal?.classList.add("modal-hidden");
      registerModal?.classList.remove("modal-hidden");
    });
  }

  if (openLoginFromRegister) {
    openLoginFromRegister.addEventListener("click", (e) => {
      e.preventDefault();
      registerModal?.classList.add("modal-hidden");
      loginModal?.classList.remove("modal-hidden");
    });
  }
  const closeRegisterModal = document.getElementById("closeRegisterModal");
if (closeRegisterModal) {
  closeRegisterModal.addEventListener("click", () => {
    registerModal.classList.add("modal-hidden");
  });
}
});

</script>