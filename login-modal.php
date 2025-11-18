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

      <form id="loginForm" method="POST" action="index.php" class="space-y-4">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
          <input type="email" id="email" name="email" required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" />
        </div>
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <div class="relative">
            <input type="password" id="password" name="password" required
              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" />
            <button
              type="button"
              id="toggle-password"
              class="absolute inset-y-0 right-3 flex items-center text-zinc-500 transition-colors hover:text-zinc-900"
              aria-label="Toggle password visibility"
            >
              <svg
                id="eye-icon"
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              >
                <path
                  d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"
                />
                <circle cx="12" cy="12" r="3" />
              </svg>
            </button>
          </div>
        </div>

        <p id="loginError" class="text-red-500 text-sm mb-4 text-center"></p>

        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input id="remember-me" name="remember-me" type="checkbox"
              class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded" />
            <label for="remember-me" class="ml-2 block text-sm text-gray-700">Remember me</label>
          </div>
          <div class="text-sm">
            <a href="#" id="forgotPasswordLink" class="font-medium text-primary hover:text-opacity-80">Forgot password?</a>
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

<!-- Forgot Password Modal -->
<div id="forgotPasswordModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 modal modal-hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <div class="flex justify-center items-center mb-4 relative">
                <h3 class="text-2xl font-bold text-primary text-center">Forgot Password</h3>
                <button id="closeForgotPasswordModal" class="absolute right-0 text-gray-500 hover:text-gray-700">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="forgotPasswordForm" class="space-y-4">
                <div>
                    <label for="forgot_email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" id="forgot_email" name="email" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" />
                </div>
                <p id="forgotPasswordError" class="text-red-500 text-sm mb-4 text-center"></p>
                <div>
                    <button id="sendOtpBtn" type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Send OTP
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Verify OTP Modal -->
<div id="verifyOtpModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 modal modal-hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <div class="flex justify-center items-center mb-4 relative">
                <h3 class="text-2xl font-bold text-primary text-center">Verify OTP</h3>
                <button id="closeVerifyOtpModal" class="absolute right-0 text-gray-500 hover:text-gray-700">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="verifyOtpForm" class="space-y-4">
                <div>
                    <label for="otp" class="block text-sm font-medium text-gray-700">OTP</label>
                    <input type="text" id="otp" name="otp" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" />
                </div>
                <p id="verifyOtpError" class="text-red-500 text-sm mb-4 text-center"></p>
                <div>
                    <button id="verifyOtpBtn" type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Verify OTP
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div id="resetPasswordModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 modal modal-hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="p-6">
            <div class="flex justify-center items-center mb-4 relative">
                <h3 class="text-2xl font-bold text-primary text-center">Reset Password</h3>
                <button id="closeResetPasswordModal" class="absolute right-0 text-gray-500 hover:text-gray-700">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="resetPasswordForm" class="space-y-4">
                <div>
                    <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                    <input type="password" id="new_password" name="new_password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" />
                </div>
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" />
                </div>
                <p id="resetPasswordError" class="text-red-500 text-sm mb-4 text-center"></p>
                <div>
                    <button id="resetPasswordBtn" type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'register-modal.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const loginModal = document.getElementById('loginModal');
    const forgotPasswordModal = document.getElementById('forgotPasswordModal');
    const verifyOtpModal = document.getElementById('verifyOtpModal');
    const resetPasswordModal = document.getElementById('resetPasswordModal');

    const forgotPasswordLink = document.getElementById('forgotPasswordLink');
    const closeForgotPasswordModal = document.getElementById('closeForgotPasswordModal');
    const closeVerifyOtpModal = document.getElementById('closeVerifyOtpModal');
    const closeResetPasswordModal = document.getElementById('closeResetPasswordModal');

    const forgotPasswordForm = document.getElementById('forgotPasswordForm');
    const verifyOtpForm = document.getElementById('verifyOtpForm');
    const resetPasswordForm = document.getElementById('resetPasswordForm');

    const sendOtpBtn = document.getElementById('sendOtpBtn');
    const verifyOtpBtn = document.getElementById('verifyOtpBtn');
    const resetPasswordBtn = document.getElementById('resetPasswordBtn');

    const forgotPasswordError = document.getElementById('forgotPasswordError');
    const verifyOtpError = document.getElementById('verifyOtpError');
    const resetPasswordError = document.getElementById('resetPasswordError');

    forgotPasswordLink.addEventListener('click', (e) => {
        e.preventDefault();
        loginModal.classList.add('modal-hidden');
        forgotPasswordModal.classList.remove('modal-hidden');
    });

    closeForgotPasswordModal.addEventListener('click', () => {
        forgotPasswordModal.classList.add('modal-hidden');
    });

    closeVerifyOtpModal.addEventListener('click', () => {
        verifyOtpModal.classList.add('modal-hidden');
    });

    closeResetPasswordModal.addEventListener('click', () => {
        resetPasswordModal.classList.add('modal-hidden');
    });

    forgotPasswordForm.addEventListener('submit', (e) => {
        e.preventDefault();
        sendOtpBtn.disabled = true;
        sendOtpBtn.textContent = 'Sending...';
        forgotPasswordError.textContent = '';

        const formData = new FormData(forgotPasswordForm);

        fetch('send_forgot_password_otp.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                forgotPasswordModal.classList.add('modal-hidden');
                verifyOtpModal.classList.remove('modal-hidden');
            } else {
                forgotPasswordError.textContent = data.message;
            }
        })
        .catch(error => {
            forgotPasswordError.textContent = 'An error occurred. Please try again.';
        })
        .finally(() => {
            sendOtpBtn.disabled = false;
            sendOtpBtn.textContent = 'Send OTP';
        });
    });

    verifyOtpForm.addEventListener('submit', (e) => {
        e.preventDefault();
        verifyOtpBtn.disabled = true;
        verifyOtpBtn.textContent = 'Verifying...';
        verifyOtpError.textContent = '';

        const formData = new FormData(verifyOtpForm);

        fetch('verify_forgot_password_otp.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                verifyOtpModal.classList.add('modal-hidden');
                resetPasswordModal.classList.remove('modal-hidden');
            } else {
                verifyOtpError.textContent = data.message;
            }
        })
        .catch(error => {
            verifyOtpError.textContent = 'An error occurred. Please try again.';
        })
        .finally(() => {
            verifyOtpBtn.disabled = false;
            verifyOtpBtn.textContent = 'Verify OTP';
        });
    });

    resetPasswordForm.addEventListener('submit', (e) => {
        e.preventDefault();
        resetPasswordBtn.disabled = true;
        resetPasswordBtn.textContent = 'Resetting...';
        resetPasswordError.textContent = '';

        const formData = new FormData(resetPasswordForm);

        fetch('update_password.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resetPasswordModal.classList.add('modal-hidden');
                loginModal.classList.remove('modal-hidden');
                // Optionally show a success message in the login modal
            } else {
                resetPasswordError.textContent = data.message;
            }
        })
        .catch(error => {
            resetPasswordError.textContent = 'An error occurred. Please try again.';
        })
        .finally(() => {
            resetPasswordBtn.disabled = false;
            resetPasswordBtn.textContent = 'Reset Password';
        });
    });
});
</script>
