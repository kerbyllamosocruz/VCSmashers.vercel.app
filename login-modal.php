<?php
?>

<div id="loginModal"
  class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 modal modal-hidden">
  <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
    <div class="p-6">
      <div class="flex justify-center items-center mb-4 relative">
        <h3 class="text-2xl font-bold text-primary text-center">Login</h3>
        <button id="closeModal" class="absolute right-0 text-gray-500 hover:text-gray-700">
          <i data-feather="x"></i>
        </button>
      </div>

      <p id="loginError" class="text-red-500 text-sm mb-4 text-center"></p>

      <form id="loginForm" method="POST" action="index.php" class="space-y-4">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
          <input type="email" id="email" name="email" required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" />
        </div>
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input type="password" id="password" name="password" required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" />
        </div>
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input id="remember-me" name="remember-me" type="checkbox"
              class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" />
            <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
          </div>
          <div class="text-sm">
            <a href="#" class="font-medium text-primary hover:text-opacity-80">Forgot password?</a>
          </div>
        </div>
        <div>
          <button id="signinBtn" type="submit"
            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
            Sign in
          </button>
        </div>
      </form>

      <div class="mt-4 text-center text-sm text-gray-600">
        Don’t have an account?
        <a href="#" id="openRegisterModal" class="font-medium text-primary hover:text-opacity-80">Register Here</a>
      </div>
    </div>
  </div>
</div>
<?php include 'register-modal.php'; ?>