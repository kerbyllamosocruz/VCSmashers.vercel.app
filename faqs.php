<?php
session_start();
require_once "config/config.php";

$faqs = [];
$sql = "SELECT question, answer FROM faqs";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $faqs[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Maysan Badminton Court - FAQs</title>
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
          <a href="schedule.html"
            class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Schedule</a>
          <a href="faqs.php" class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold underline transition">FAQs</a>
          <a href="contact.html"
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

  <section class="py-20 bg-gray-100">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl font-bold text-center mb-12">Frequently Asked Questions</h2>
      <div class="space-y-4">

        <?php foreach ($faqs as $faq): ?>
        <div class="bg-white p-6 rounded-lg shadow-lg">
          <button class="w-full text-left flex justify-between items-center accordion-button">
            <h3 class="text-xl font-bold"><?php echo htmlspecialchars($faq['question']); ?></h3>
            <i data-feather="chevron-down" class="transition-transform duration-300"></i>
          </button>
          <div class="mt-4 hidden accordion-content">
            <p class="text-gray-700"><?php echo htmlspecialchars($faq['answer']); ?></p>
          </div>
        </div>
        <?php endforeach; ?>

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
              <a href="index.php" class="text-secondary hover:text-white font-bold transition">Home</a>
            </li>
            <li><a href="schedule.html" class="text-secondary hover:text-white transition">Schedule</a></li>
            <li><a href="faqs.php" class="text-secondary hover:text-white transition font-bold underline">FAQs</a></li>
            <li><a href="contact.html" class="text-secondary hover:text-white transition">Contact</a></li>
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
  <script src="./faqs.js"></script>
</body>

</html>