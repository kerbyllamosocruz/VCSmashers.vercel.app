<?php
session_start();
require_once "config/config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Privacy Policy - Maysan Badminton Court</title>
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
          <img src="Assets/logo.png" alt="Maysan Badminton Court Logo" class="h-10" />
        </div>
        <div class="hidden md:flex items-center space-x-8">
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
          <button class="text-white">
            <i data-feather="menu"></i>
          </button>
        </div>
      </div>
    </div>
  </nav>

  <section class="bg-gradient-to-b from-primary to-accent text-white py-20">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h1 class="text-4xl md:text-5xl font-bold mb-6">Privacy Policy</h1>
    </div>
  </section>

  <section class="py-20 bg-[#DEDCFF]">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white p-8 rounded-lg shadow-lg">
        <div class="prose max-w-none">
          <h2 class="text-2xl font-bold text-primary mb-4">1. Information We Collect</h2>
          <p class="mb-4">We collect personal information that you provide to us, such as your name, email address, and payment information when you create an account or make a booking. We also collect data automatically as you use our services, such as your IP address and browsing behavior.</p>

          <h2 class="text-2xl font-bold text-primary mb-4">2. How We Use Your Information</h2>
          <p class="mb-4">We use your information to provide and improve our services, process your bookings, communicate with you, and ensure the security of our platform. We do not share your personal information with third parties except as necessary to provide our services or as required by law.</p>

          <h2 class="text-2xl font-bold text-primary mb-4">3. Data Security</h2>
          <p class="mb-4">We implement a variety of security measures to maintain the safety of your personal information. Your personal information is contained behind secured networks and is only accessible by a limited number of persons who have special access rights to such systems.</p>

          <h2 class="text-2xl font-bold text-primary mb-4">4. Your Rights</h2>
          <p class="mb-4">You have the right to access, correct, or delete your personal information at any time. You can do this by logging into your account or by contacting our support team.</p>

          <h2 class="text-2xl font-bold text-primary mb-4">5. Changes to This Policy</h2>
          <p class="mb-4">We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page. You are advised to review this Privacy Policy periodically for any changes.</p>
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
    feather.replace();
  </script>
</body>

</html>
