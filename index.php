<?php
session_start();
require_once "config/config.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST["email"] ?? "");
  $password = trim($_POST["password"] ?? "");

  $response = ["success" => false, "message" => "An error occurred."];

  if (empty($email) || empty($password)) {
    $response["message"] = "Please fill in all fields.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response["message"] = "Invalid email format.";
  } else {
    $stmt = $conn->prepare("SELECT user_id, pass, name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $stmt->bind_result($userId, $hashedPassword, $name);
      $stmt->fetch();

      if (password_verify($password, $hashedPassword)) {
        $_SESSION["user_id"] = $userId;
        $_SESSION["email"] = $email;
        $_SESSION["name"] = $name;

        $response["success"] = true;
        // **MODIFIED: Redirect back to index.php to reflect the login state.**
        $response["redirect"] = "index.php"; 
      } else {
        $response["message"] = "Invalid password.";
      }
    } else {
      $response["message"] = "No account found with that email.";
    }

    $stmt->close();
  }

  if (
    !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
  ) {
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
  } else {
    if ($response['success']) {
      header("Location: " . ($response['redirect'] ?? 'index.php'));
      exit;
    } else {
      $error = $response['message'];
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Maysan Badminton Court</title>
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
            class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold underline transition">Home</a>
          <a href="schedule.html"
            class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Schedule</a>
          <a href="faqs.html" class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">FAQs</a>
          <a href="contact.html"
            class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Contact Us</a>
          
          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="profile_page.php" class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Profile</a>
          <?php else: ?>
            <button id="loginBtn" class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Login</button>
          <?php endif; ?>

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
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
      <div class="md:flex items-center justify-between">
        <div class="md:w-1/2 mb-10 md:mb-0">
          <h1 class="text-4xl md:text-5xl font-bold mb-6">Book Your Badminton Court With Ease</h1>
          <p class="text-[18px] mb-8 font-gotu">
            Our intuitive scheduling system makes reserving courts quick and hassle-free. Never miss a game again!
          </p>
          <div class="flex space-x-4">
            <button class="bg-secondary text-primary px-6 py-3 rounded-lg font-bold hover:bg-white transition">View
              Schedule</button>
            
            <?php if (isset($_SESSION['user_id'])): ?>
              <a href="profile_page.php" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-bold hover:bg-white hover:text-primary transition">Profile</a>
            <?php else: ?>
              <button id="loginBtn2" class="bg-transparent border-2 border-white text-white px-6 py-3 rounded-lg font-bold hover:bg-white hover:text-primary transition">Login</button>
            <?php endif; ?>

          </div>
        </div>
        <div class="md:w-1/2">
          <img src="Assets/court.png" alt="Badminton Court" class="rounded-lg shadow-2xl" />
        </div>
      </div>
    </div>
  </section>

  <section class="py-20 bg-[#DEDCFF]">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl font-bold text-center mb-12">How It Works</h2>
      <div class="grid md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
          <div class="text-primary text-4xl font-bold mb-4">1</div>
          <i data-feather="user" class="w-12 h-12 mx-auto text-primary mb-4"></i>
          <h3 class="text-xl font-bold mb-3">Create Account</h3>
          <p class="text-gray-700">Sign up in seconds to get started with your booking journey.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
          <div class="text-primary text-4xl font-bold mb-4">2</div>
          <i data-feather="calendar" class="w-12 h-12 mx-auto text-primary mb-4"></i>
          <h3 class="text-xl font-bold mb-3">Pick Date & Time</h3>
          <p class="text-gray-700">Select your preferred date and available time slot.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
          <div class="text-primary text-4xl font-bold mb-4">3</div>
          <i data-feather="credit-card" class="w-12 h-12 mx-auto text-primary mb-4"></i>
          <h3 class="text-xl font-bold mb-3">Secure Payment</h3>
          <p class="text-gray-700">Complete your booking with our safe payment process.</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
          <div class="text-primary text-4xl font-bold mb-4">4</div>
          <i data-feather="check-circle" class="w-12 h-12 mx-auto text-primary mb-4"></i>
          <h3 class="text-xl font-bold mb-3">Play & Enjoy</h3>
          <p class="text-gray-700">Arrive at your scheduled time and enjoy your game!</p>
        </div>
      </div>
    </div>
  </section>

  <section class="py-20 bg-white">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
      <h2 class="text-3xl font-bold text-center mb-12">Advantages of Playing on Our Courts</h2>
      <div class="grid md:grid-cols-3 gap-8">
        <div class="bg-secondary p-6 rounded-lg shadow-lg">
          <div class="text-primary mb-4">
            <i data-feather="calendar" class="w-12 h-12"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">Real-Time Availability</h3>
          <p class="text-gray-700">See up-to-the-minute court availability and book instantly from any device.</p>
        </div>
        <div class="bg-secondary p-6 rounded-lg shadow-lg">
          <div class="text-primary mb-4">
            <i data-feather="clock" class="w-12 h-12"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">24/7 Access</h3>
          <p class="text-gray-700">Book courts anytime, anywhere - no need to call during business hours.</p>
        </div>
        <div class="bg-secondary p-6 rounded-lg shadow-lg">
          <div class="text-primary mb-4">
            <i data-feather="user" class="w-12 h-12"></i>
          </div>
          <h3 class="text-xl font-bold mb-3">Player Profiles</h3>
          <p class="text-gray-700">Manage your bookings, payment methods, and preferences all in one place.</p>
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
              <a href="index.html" class="text-secondary hover:text-white font-bold underline transition">Home</a>
            </li>
            <li><a href="schedule.html" class="text-secondary hover:text-white transition">Schedule</a></li>
            <li><a href="faqs.html" class="text-secondary hover:text-white transition font-bold">FAQs</a></li>
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
            <p class="text-secondary">support@maysanbadmintoncourt.site</p>
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
    let phpError = <?php echo json_encode($error); ?>;

    fetch("login-modal.php")
      .then(res => res.text())
      .then(html => {
        document.getElementById("modal-container").innerHTML = html;

        feather.replace();
        initLoginModal();

        if (phpError) {
          const errorEl = document.querySelector("#loginModal p.text-red-500");
          if (errorEl) errorEl.textContent = phpError;

          document.getElementById("loginModal").classList.remove("modal-hidden");
        }
      });

    feather.replace();
  </script>
  <script src="login.js"></script>
</body>

</html>
