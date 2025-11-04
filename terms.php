<?php
session_start();
require_once "config/config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Terms of Service - Maysan Badminton Court</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Gotu&family=Montserrat:wght@400;500;600;700&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/feather-icons"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
  <link rel="icon" type="image/x-icon" href="Assets/logo.png" />
  <script src="script.js"></script>
</head>

<body>
  <nav class="bg-primary shadow-lg">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-20">
        <div class="flex items-center">
          <a href="index.php">
            <img src="Assets/logo.png" alt="Maysan Badminton Court Logo" class="h-10" />
          </a>
        </div>
        <div class="hidden md:flex items-center space-x-8" id="nav-links">
          <a href="index.php"
            class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Home</a>
          <a href="schedule.php"
            class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Schedule</a>
          <a href="faqs.php" class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">FAQs</a>
          <a href="contact.php"
            class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Contact Us</a>
          
          <?php if (isset($_SESSION['user_id'])):
            ?>
            <a href="profile_page.php" class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Profile</a>
          <?php else:
            ?>
            <button id="loginBtn" class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Login</button>
          <?php endif;
          ?>

        </div>
        <div class="md:hidden flex items-center">
          <button class="text-white" id="mobile-menu-button">
            <i data-feather="menu"></i>
          </button>
        </div>
      </div>
    </div>
    <!-- Mobile menu, show/hide based on menu state. -->
    <div class="md:hidden hidden" id="mobile-menu">
      <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 text-center">
        <a href="index.php" class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-bold">Home</a>
        <a href="schedule.php" class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-bold">Schedule</a>
        <a href="faqs.php" class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-bold">FAQs</a>
        <a href="contact.php" class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-bold">Contact Us</a>
        <?php if (isset($_SESSION['user_id'])):
          ?>
          <a href="profile_page.php" class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-bold">Profile</a>
        <?php else:
          ?>
          <button id="loginBtnMobile" class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-bold">Login</button>
        <?php endif;
        ?>
      </div>
    </div>
  </nav>

  <section class="bg-gradient-to-b from-primary to-accent text-white py-20">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h1 class="text-4xl md:text-5xl font-bold mb-6">Terms of Service</h1>
    </div>
  </section>

  <section class="py-20 bg-[#DEDCFF]">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white p-8 rounded-lg shadow-lg">
        <div class="prose max-w-none">
          <h2 class="text-2xl font-bold text-primary mb-4">1. Introduction</h2>
          <p class="mb-4">Welcome to Maysan Badminton Court. These Terms of Service govern your use of our website and services. By accessing or using our services, you agree to be bound by these terms.</p>

          <h2 class="text-2xl font-bold text-primary mb-4">2. Booking and Payment</h2>
          <p class="mb-4">All bookings must be made through our online system. Payment is required at the time of booking to secure your court time. We accept various forms of payment as indicated on our payment page.</p>

          <h2 class="text-2xl font-bold text-primary mb-4">3. Cancellations and Refunds</h2>
          <p class="mb-4">Cancellations made at least 24 hours before the scheduled booking will receive a full refund. No refunds will be provided for cancellations made within 24 hours of the booking time. To cancel a booking, please log in to your account or contact our support team.</p>

          <h2 class="text-2xl font-bold text-primary mb-4">4. Court Usage</h2>
          <p class="mb-4">Proper attire, including non-marking shoes, is required on the courts. Please be respectful of other players and staff. Any damage to the court or equipment will be the responsibility of the person who booked the court.</p>

          <h2 class="text-2xl font-bold text-primary mb-4">5. Limitation of Liability</h2>
          <p class="mb-4">Maysan Badminton Court is not liable for any injuries or loss of property sustained while using our facilities. All players participate at their own risk.</p>

          <h2 class="text-2xl font-bold text-primary mb-4">6. Changes to Terms</h2>
          <p class="mb-4">We reserve the right to modify these terms at any time. Any changes will be effective immediately upon posting on our website. Your continued use of our services after changes are posted constitutes your acceptance of the new terms.</p>
        </div>
      </div>
    </div>
  </section>

  <div id="modal-container"></div>
  <footer class="bg-[#232067] text-white py-12">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid md:grid-cols-4 gap-8 text-center md:text-left">
        <div>
          <h3 class="text-xl font-bold mb-4">Maysan Badminton Court</h3>
          <p class="text-secondary">Maysan Badminton Court is a project of Valenzuela Congressman Eric Martinez</p>
        </div>
        <div>
          <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
          <ul class="space-y-2">
            <li>
              <a href="index.php" class="text-secondary hover:text-white transition">Home</a>
            </li>
            <li><a href="schedule.php" class="text-secondary hover:text-white transition">Schedule</a></li>
            <li><a href="faqs.php" class="text-secondary hover:text-white transition">FAQs</a></li>
            <li><a href="contact.php" class="text-secondary hover:text-white transition">Contact</a></li>
          </ul>
        </div>
        <div>
          <h4 class="text-lg font-semibold mb-4">Legal</h4>
          <ul class="space-y-2">
            <li><a href="terms.php" class="text-secondary hover:text-white transition">Terms of Service</a></li>
            <li><a href="privacy.php" class="text-secondary hover:text-white transition">Privacy Policy</a></li>
            <li><a href="cookies.php" class="text-secondary hover:text-white transition">Cookie Policy</a></li>
          </ul>
        </div>
        <div>
          <h4 class="text-lg font-semibold mb-4">Connect</h4>
          <div class="flex justify-center md:justify-start space-x-4">
            <a href="https://www.facebook.com/people/Valenzuela-City-Smashers/100091987359934/"
              class="text-secondary hover:text-white transition">
              <i data-feather="facebook"></i>
            </a>
            <a href="#" class="text-secondary hover:text-white transition">
              <i class="fa-brands fa-whatsapp fa-xl"></i>
            </a>
          </div>
          <div class="mt-4">
            <p class="text-secondary md:break-words [@media(min-width:1100px)]:break-normal">support@maysanbadmintoncourt.site</p>
            <p class="text-secondary">0915-865-3350</p>
          </div>
        </div>
      </div>

      <div class="border-t border-white mt-8 pt-8 text-center text-secondary">
        <p>© 2025 Maysan Badminton Court. All rights reserved.</p>
      </div>
    </div>
  </footer>
  <script>
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuButton.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden');
    });

    feather.replace();
  </script>
</body>

</html>
