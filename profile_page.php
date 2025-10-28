<?php
session_start();

$userName = $_SESSION["name"] ?? "Guest";
$userEmail = $_SESSION["email"] ?? "Unknown";
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>User Profile</title>
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
            <a href="profile_page.php" class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold underline transition">Profile</a>
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

  <header class="bg-gradient-to-b from-primary to-accent text-white py-8">
    <h2 class="text-3xl font-bold text-center">Profile</h2>
  </header>

  <div class="container mx-auto p-4 grid grid-cols-1 lg:grid-cols-3 gap-8">
    <section class="lg:col-span-1 flex flex-col gap-y-8">
      <div class="bg-white p-6 rounded-lg shadow-lg text-center">
        <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($userName); ?></h3>
        <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($userEmail); ?></p>
        <button class="bg-primary text-white px-6 py-3 rounded-lg font-bold hover:bg-opacity-80 transition w-full">Edit Profile</button>
      </div>

      <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-xl font-bold mb-4 text-left">Quick Links</h3>
        <ul class="space-y-2 text-left">
          <li><a href="#" class="text-gray-700 hover:text-primary">Account Settings</a></li>
          <form action="logout.php" method="POST" style="margin:0;">
            <button type="submit" class="text-red-600 hover:text-red-800 font-bold">Log out</button>
          </form>
        </ul>
      </div>
    </section>

    <!-- Right Section: Booking History -->
    <section class="lg:col-span-2">
      <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-primary mb-4">Booking History</h2>

        <!-- Tabs -->
        <div class="border-b border-gray-200">
          <nav id="booking-tabs" class="-mb-px flex space-x-8" aria-label="Tabs">
            <button
              class="tab-btn border-primary text-primary whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
              data-tab="upcoming">Upcoming</button>
            <button
              class="tab-btn border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
              data-tab="past">Past</button>
          </nav>
        </div>

        <!-- Upcoming Bookings -->
        <div id="upcoming-bookings" class="booking-tab-content mt-6 space-y-4 overflow-auto h-[400px] pr-2">

          <!-- Booking 1 -->
          <div class="border p-4 rounded-lg flex flex-col md:flex-row justify-between items-start gap-4">
            <div class="flex-grow">
              <p class="font-semibold text-lg text-accent">Pickleball Session</p>
              <p class="text-sm text-gray-500">October 2, 2025 - 10:00 AM</p>
              <p class="text-sm text-green-600 font-medium mt-1 inline-flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"></path>
                </svg>
                Confirmed
              </p>
            </div>
            <div class="flex-shrink-0 flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
              <button
                class="w-full sm:w-auto text-center bg-secondary text-primary font-semibold text-sm py-2 px-4 rounded-lg hover:bg-opacity-80">
                View Ticket
              </button>
              <button
                class="w-full sm:w-auto text-center bg-red-100 text-red-700 font-semibold text-sm py-2 px-4 rounded-lg hover:bg-red-200">
                Cancel
              </button>
            </div>
          </div>

          <!-- Booking 2 -->
          <div class="border p-4 rounded-lg flex flex-col md:flex-row justify-between items-start gap-4">
            <div class="flex-grow">
              <p class="font-semibold text-lg text-accent">Badminton Practice</p>
              <p class="text-sm text-gray-500">October 3, 2025 - 9:00 AM</p>
              <p class="text-sm text-red-600 font-medium mt-1 inline-flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                Cancelled
              </p>
            </div>
            <div class="flex-shrink-0 flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
              <button
                class="w-full sm:w-auto text-center bg-secondary text-primary font-semibold text-sm py-2 px-4 rounded-lg hover:bg-opacity-80">
                View Ticket
              </button>
            </div>
          </div>

        </div>

        <!-- Past Bookings -->
        <div id="past-bookings" class="booking-tab-content mt-6 space-y-4 hidden overflow-auto h-[400px] pr-2">
          <div class="border p-4 rounded-lg flex flex-col md:flex-row justify-between items-start gap-4 bg-gray-50">
            <div class="flex-grow">
              <p class="font-semibold text-lg text-gray-600">Badminton Duos</p>
              <p class="text-sm text-gray-500">September 15, 2025 - 6:00 PM</p>
              <p class="text-sm text-gray-600 font-medium mt-1 inline-flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                  <path fill-rule="evenodd"
                    d="M4 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z"
                    clip-rule="evenodd"></path>
                </svg>
                Completed
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

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
              <a href="index.php" class="text-secondary hover:text-white font-bold transition">Home</a>
            </li>
            <li><a href="schedule.php" class="text-secondary hover:text-white transition">Schedule</a></li>
            <li><a href="faqs.php" class="text-secondary hover:text-white transition font-bold">FAQs</a></li>
            <li><a href="contact.php" class="text-secondary hover:text-white transition">Contact</a></li>
          </ul>
        </div>
        <div>
          <h4 class="text-lg font-semibold mb-4">Legal</h4>
          <ul class="space-y-2">
            <li><a href="#" class="text-secondary hover:text-white transition">Terms of Service</a></li>
            <li><a href="#" class="text-secondary hover:text-white transition">Privacy Policy</a></li>
            <li><a href="#" class="text-secondary hover:text-white transition">Cookie Policy</a></li>
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
    const menu = document.getElementById('mobile-menu');
    const navLinks = document.getElementById('nav-links');
    menu.addEventListener('click', () => {
      navLinks.classList.toggle('active');
      menu.classList.toggle('open');
    });
  </script>

  <script src="profile_page.js"></script>
</body>
</html>
