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
                    <a href="index.php">
                        <img src="Assets/logo.png" alt="Maysan Badminton Court Logo" class="h-10" />
                    </a>
                </div>
                <div class="hidden md:flex items-center space-x-8" id="nav-links">
                    <a href="index.php"
                        class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Home</a>
                    <a href="schedule.php"
                        class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold underline">Schedule</a>
                    <a href="faqs.php"
                        class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">FAQs</a>
                    <a href="contact.php"
                        class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Contact Us</a>
                    <?php if (isset($_SESSION['user_id'])):
                        ?>
                        <a href="profile_page.php"
                            class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Profile</a>
                    <?php else:
                        ?>
                        <button id="loginBtn"
                            class="text-white hover:text-secondary px-3 py-2 rounded-md text-base font-bold">Login</button>
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
                <a href="schedule.php" class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-bold underline">Schedule</a>
                <a href="faqs.php" class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-bold">FAQs</a>
                <a href="contact.php" class="text-white hover:text-secondary block px-3 py-2 rounded-md text-base font-bold">Contact Us</a>
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

    <?php include 'modals.php'; ?>

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
        <?php include 'ticket-modal.php'; ?>
    </footer>

    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        feather.replace();
        // Expose login state to JS
        window.IS_LOGGED_IN = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
    </script>
    <script src="schedule.js"></script>
    <script>
        // Make showTicketModal globally accessible
        window.showTicketModal = function(bookingId) {
            const modal = document.getElementById('ticketModal');
            if (!modal) {
                console.error('Ticket modal not found');
                return;
            }
            const content = document.getElementById('ticketModalContent');

            // Show loading state
            content.innerHTML = `
                <div class="flex items-center justify-center p-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                </div>
            `;

            modal.classList.remove('hidden');
            requestAnimationFrame(() => {
                modal.classList.add('flex');
                modal.classList.remove('opacity-0');
                modal.querySelector('.modal-content').classList.remove('scale-95');
                modal.querySelector('.modal-content').classList.add('scale-100');
            });

            // Get access token from URL if present
            const urlParams = new URLSearchParams(window.location.search);
            const accessToken = urlParams.get('access_token');

            // Fetch ticket content with access token if available
            const url = new URL('fetch_receipt.php', window.location.href);
            url.searchParams.set('booking_id', bookingId);
            if (accessToken) {
                url.searchParams.set('access_token', accessToken);
            }

            // Add debug parameter
            url.searchParams.set('debug', '1');

            console.log('Fetching receipt with URL:', url.toString());

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    console.log('Receipt response:', data); // Add debug logging
                    if (data.success && data.receipt) {
                        const r = data.receipt;
                        content.innerHTML = `
                            <div class="relative mb-6">
                                <div class="text-center mb-6">
                                    <h1 class="text-2xl font-bold text-primary">Maysan Badminton Court</h1>
                                    <p class="text-sm text-gray-500">BOOKING CONFIRMATION</p>
                                </div>

                                <div class="mb-4">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-gray-600">Transaction ID:</span>
                                        <span class="font-mono">${r.transaction_id}</span>
                                    </div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-gray-600">Booking Date:</span>
                                        <span class="font-mono">${r.date_now}</span>
                                    </div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-gray-600">Reserved By:</span>
                                        <span>${r.customer_name}</span>
                                    </div>
                                </div>

                                <div class="border-t border-dashed border-gray-300 my-4"></div>

                                <div class="mb-4">
                                    <h2 class="text-lg font-semibold mb-2">Reservation Details</h2>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-gray-600">Activity / Title:</span>
                                        <span>${r.title || r.activity_name}</span>
                                    </div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-gray-600">Court:</span>
                                        <span>Court ${r.court_number}</span>
                                    </div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-gray-600">Date:</span>
                                        <span>${r.event_date}</span>
                                    </div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-gray-600">Time:</span>
                                        <span>${r.event_time}${r.event_end_time ? ' - ' + r.event_end_time : ''}</span>
                                    </div>
                                </div>

                                <div class="border-t border-dashed border-gray-300 my-4"></div>

                                <div class="mb-6">
                                    <div class="flex justify-between items-center text-xl font-bold">
                                        <span>Total Paid:</span>
                                        <span class="text-green-600">PHP ${r.total_fee}</span>
                                    </div>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-gray-600">Payment Method:</span>
                                        <span>GCASH Payment</span>
                                    </div>
                                </div>

                                <div class="relative text-center">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-6xl font-black text-green-500 opacity-20 transform -rotate-12 select-none">CONFIRMED</span>
                                    </div>
                                    <p class="text-gray-500 italic">Thank you for your reservation!</p>
                                </div>

                                <div class="mt-8 flex justify-end space-x-4">
                                    <a href="view_ticket.php?booking_id=${r.booking_id}" 
                                       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                                       target="_blank">
                                        View Full Receipt
                                    </a>
                                    <button onclick="closeTicketModal()"
                                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">
                                        Close
                                    </button>
                                </div>
                            </div>
                        `;
                    } else {
                        content.innerHTML = `
                            <div class="p-8 text-center">
                                <p class="text-red-500">Could not load booking details.</p>
                                <button onclick="closeTicketModal()"
                                        class="mt-4 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">
                                    Close
                                </button>
                            </div>
                        `;
                    }
                })
                .catch(err => {
                    content.innerHTML = `
                        <div class="p-8 text-center">
                            <p class="text-red-500">An error occurred while loading the booking details.</p>
                            <button onclick="closeTicketModal()"
                                    class="mt-4 px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">
                                Close
                            </button>
                        </div>
                    `;
                });
        }

        window.closeTicketModal = function() {
            const modal = document.getElementById('ticketModal');
            modal.classList.add('opacity-0');
            modal.querySelector('.modal-content').classList.remove('scale-100');
            modal.querySelector('.modal-content').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }

        // Close on background click
        document.getElementById('ticketModal').addEventListener('click', (e) => {
            if (e.target.id === 'ticketModal') {
                closeTicketModal();
            }
        });

        // Show ticket modal if URL has booking_id and show_ticket parameters
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('booking_id') && urlParams.has('show_ticket')) {
            showTicketModal(urlParams.get('booking_id'));
        }
    </script>
</body>

</html>