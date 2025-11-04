<?php
session_start();
require_once "config/config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us - Maysan Badminton Court</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Gotu&family=Montserrat:wght@400;500;600;700&display=swap"
    rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="contact.css" />
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
            class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold underline transition">Contact Us</a>
          
          <?php if (isset($_SESSION['user_id'])):
            ?>
            <a href="profile_page.php" class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Profile</a>
          <?php else:
            ?>
            <button id="loginBtn" class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Login</button>
          <?php endif; ?>

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
        <a href="contact.php" class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-bold underline transition">Contact Us</a>
        <?php if (isset($_SESSION['user_id'])):
          ?>
          <a href="profile_page.php" class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-bold">Profile</a>
        <?php else:
          ?>
          <button id="loginBtnMobile" class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-bold">Login</button>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <section class="bg-gradient-to-b from-primary to-accent text-white py-20">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <h1 class="text-4xl md:text-5xl font-bold mb-6">Contact Us</h1>
      <p class="text-[18px] mb-8 font-gotu">We'd love to hear from you. Send us a message and we'll get back to you as soon as possible.</p>
    </div>
  </section>

  <section class="py-20 bg-[#DEDCFF]">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid md:grid-cols-2 gap-12">
        <div class="bg-white p-8 rounded-lg shadow-lg">
          <h2 class="text-2xl font-bold mb-6">Send us a message</h2>
          <form id="contactForm">
            <div class="mb-4">
              <label for="name" class="block text-gray-700 font-bold mb-2">Name</label>
              <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
              <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
              <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
              <label for="message" class="block text-gray-700 font-bold mb-2">Message</label>
              <textarea id="message" name="message" rows="5" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required></textarea>
            </div>
            <button type="submit" class="bg-primary text-white px-6 py-3 rounded-lg font-bold hover:bg-accent transition">Send Message</button>
          </form>
          <div id="form-status" class="mt-4"></div>
        </div>
        <div class="bg-white p-8 rounded-lg shadow-lg">
          <h2 class="text-2xl font-bold mb-6">Contact Information</h2>
          <div class="flex items-center mb-4">
            <i data-feather="map-pin" class="w-6 h-6 text-primary mr-4"></i>
            <p class="text-gray-700">Maysan, Valenzuela City, Philippines</p>
          </div>
          <div class="flex items-center mb-4">
            <i data-feather="phone" class="w-6 h-6 text-primary mr-4"></i>
            <p class="text-gray-700">0915-865-3350</p>
          </div>
          <div class="flex items-center mb-4">
            <i data-feather="mail" class="w-6 h-6 text-primary mr-4"></i>
            <p class="text-gray-700">support@maysanbadmintoncourt.site</p>
          </div>
          <h3 class="text-xl font-bold mt-8 mb-4">Follow Us</h3>
          <div class="flex space-x-4">
            <a href="https://www.facebook.com/people/Valenzuela-City-Smashers/100091987359934/" class="text-primary hover:text-accent transition">
              <i data-feather="facebook" class="w-8 h-8"></i>
            </a>
            <a href="#" class="text-primary hover:text-accent transition">
                <i class="fa-brands fa-whatsapp fa-2x"></i>
            </a>
          </div>
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
            <li><a href="contact.php" class="text-secondary hover:text-white font-bold underline transition">Contact</a></li>
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
  <script src="contact.js"></script>
</body>

</html>

