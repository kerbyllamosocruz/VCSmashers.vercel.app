<?php
session_start();
require_once "config/config.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cookie Policy - Maysan Badminton Court</title>
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
      <h1 class="text-4xl md:text-5xl font-bold mb-6">Cookie Policy</h1>
    </div>
  </section>

  <section class="py-20 bg-[#DEDCFF]">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white p-8 rounded-lg shadow-lg">
        <div class="prose max-w-none">
          <h2 class="text-2xl font-bold text-primary mb-4">1. What Are Cookies</h2>
          <p class="mb-4">As is common practice with almost all professional websites, this site uses cookies, which are tiny files that are downloaded to your computer, to improve your experience. This page describes what information they gather, how we use it and why we sometimes need to store these cookies. We will also share how you can prevent these cookies from being stored however this may downgrade or 'break' certain elements of the sites functionality.</p>

          <h2 class="text-2xl font-bold text-primary mb-4">2. How We Use Cookies</h2>
          <p class="mb-4">We use cookies for a variety of reasons detailed below. Unfortunately in most cases there are no industry standard options for disabling cookies without completely disabling the functionality and features they add to this site. It is recommended that you leave on all cookies if you are not sure whether you need them or not in case they are used to provide a service that you use.</p>

          <h2 class="text-2xl font-bold text-primary mb-4">3. Disabling Cookies</h2>
          <p class="mb-4">You can prevent the setting of cookies by adjusting the settings on your browser (see your browser Help for how to do this). Be aware that disabling cookies will affect the functionality of this and many other websites that you visit. Disabling cookies will usually result in also disabling certain functionality and features of the this site. Therefore it is recommended that you do not disable cookies.</p>

          <h2 class="text-2xl font-bold text-primary mb-4">4. The Cookies We Set</h2>
          <ul class="list-disc list-inside mb-4">
            <li class="mb-2"><strong>Account related cookies:</strong> If you create an account with us then we will use cookies for the management of the signup process and general administration. These cookies will usually be deleted when you log out however in some cases they may remain afterwards to remember your site preferences when logged out.</li>
            <li class="mb-2"><strong>Login related cookies:</strong> We use cookies when you are logged in so that we can remember this fact. This prevents you from having to log in every single time you visit a new page. These cookies are typically removed or cleared when you log out to ensure that you can only access restricted features and areas when logged in.</li>
          </ul>

          <h2 class="text-2xl font-bold text-primary mb-4">5. More Information</h2>
          <p class="mb-4">Hopefully that has clarified things for you and as was previously mentioned if there is something that you aren't sure whether you need or not it's usually safer to leave cookies enabled in case it does interact with one of the features you use on our site.</p>
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
