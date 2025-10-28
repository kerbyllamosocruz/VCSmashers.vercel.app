<?php
session_start();
require_once "config/config.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Court Schedule | Maysan Badminton Court</title>
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

<body class="bg-secondary/20 min-h-screen flex flex-col">

    <nav class="bg-primary shadow-lg w-full">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <img src="Assets/logo.png" alt="Logo" class="h-10" />
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="index.php"
                        class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Home</a>
                    <a href="schedule.php"
                        class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold underline">Schedule</a>
                    <a href="faqs.html"
                        class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">FAQs</a>
                    <a href="contact.html"
                        class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Contact Us</a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="profile_page.php"
                            class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Profile</a>
                    <?php else: ?>
                        <button id="loginBtn"
                            class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Login</button>
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

    <main class="flex-grow flex flex-col items-start justify-start py-10 px-4 w-full">

        <div class="w-full max-w-[1200px] mx-auto mb-6">
            <h2 class="text-2xl font-bold text-accent text-center">Court Schedule</h2>
        </div>

        <div id="calendar-container"
            class="w-full max-w-[1200px] min-h-[400px] bg-white rounded-2xl shadow-lg border-2 border-secondary/80 p-6 mx-auto">
        </div>

    </main>


    <div id="courtModal"
        class="fixed inset-0 hidden items-center justify-center z-50 bg-black/50 transition-opacity duration-300 opacity-0">
        <div id="courtModalBox"
            class="bg-white rounded-2xl shadow-xl w-full max-w-2xl border border-secondary/40 overflow-hidden transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center bg-primary text-white p-4">
                <h2 id="modal-date" class="font-bold text-lg">Selected Date</h2>
                <button id="closeModal" class="hover:text-secondary transition">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div id="court-container" class="p-4 max-h-[70vh] overflow-y-auto"></div>
        </div>
    </div>

    <div id="modal-container"></div>

    <footer class="bg-[#232067] text-white py-12 mt-10">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8 text-center md:text-left">
                <div>
                    <h3 class="text-xl font-bold mb-4">Maysan Badminton Court</h3>
                    <p class="text-secondary">Maysan Badminton Court is a project of Valenzuela Congressman Eric
                        Martinez</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="index.php" class="text-secondary hover:text-white transition">Home</a></li>
                        <li><a href="schedule.php"
                                class="text-secondary hover:text-white font-bold underline transition">Schedule</a></li>
                        <li><a href="faqs.html" class="text-secondary hover:text-white transition">FAQs</a></li>
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
                        <p class="text-secondary md:break-words [@media(min-width:1100px)]:break-normal">
                            support@maysanbadmintoncourt.site
                        </p>
                        <p class="text-secondary">0915-865-3350</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-white mt-8 pt-8 text-center text-secondary">
                <p>© 2025 Maysan Badminton Court. All rights reserved.</p>
            </div>
        </div>
        <?php include 'schedule-modal.php'; ?>
    </footer>

    <script>
        feather.replace();

        fetch("login-modal.php")
            .then(res => res.text())
            .then(html => {
                document.getElementById("modal-container").innerHTML = html;
                feather.replace();
                if (typeof initLoginModal === "function") initLoginModal();
            });
        // Expose login state to JS
        window.IS_LOGGED_IN = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    </script>
    <script src="schedule.js"></script>
    <script src="login.js"></script>
</body>

</html>