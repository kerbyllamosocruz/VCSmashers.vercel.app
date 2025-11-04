<?php
session_start();
require_once "config/config.php";

$userName = $_SESSION["name"] ?? "Guest";
$userEmail = $_SESSION["email"] ?? "Unknown";
$userPhone = $_SESSION["phone"] ?? "";
$status = $_GET['status'] ?? '';
$message = $_GET['message'] ?? '';

// First, update status of past bookings to COMPLETED
$current_date = date('Y-m-d');
$current_time = date('H:i:s');
$update_sql = "UPDATE bookings 
               SET status = 'COMPLETED' 
               WHERE (event_date < ?) 
               OR (event_date = ? AND event_time < ?) 
               AND status = 'CONFIRMED'";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param('sss', $current_date, $current_date, $current_time);
$update_stmt->execute();

$bookings = [];
// Only show bookings for the current user and include booking_id
$user_id = $_SESSION['user_id'] ?? 0;
$sql = "SELECT booking_id, title, event_date, event_time, status FROM bookings WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

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
    <style>
    #receiptModal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        overflow-y: auto;
    }
    #receiptModal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .receipt-container::before,
    .receipt-container::after {
        content: '';
        display: block;
        width: 100%;
        height: 15px;
        background-image: linear-gradient(to right, #a0aec0 33%, rgba(255,255,255,0) 0%);
        background-position: bottom;
        background-size: 6px 2px;
        background-repeat: repeat-x;
        position: absolute;
        left: 0;
    }
    .receipt-container::before { top: -10px; }
    .receipt-container::after { bottom: -10px; }
    </style>
</head>

<body>  <nav class="bg-primary shadow-lg">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between h-20">
        <div class="flex items-center">
          <a href="index.php">
            <img src="Assets/logo.png" alt="Maysan Badminton Court Logo" class="h-10" />
          </a>
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

  <header class="bg-gray-100 py-8">
    <h2 class="text-3xl font-bold text-center text-primary">Profile</h2>
  </header>

  <div class="container mx-auto p-4 grid grid-cols-1 lg:grid-cols-3 gap-8">
    <section class="lg:col-span-1 flex flex-col gap-y-8">
      <div class="bg-white p-6 rounded-lg shadow-lg text-center">
        <div class="w-24 h-24 mx-auto mb-3 rounded-full bg-gray-200 flex items-center justify-center text-primary font-bold text-2xl overflow-hidden">
          <?php if (!empty($_SESSION['profile_pic'])): ?>
            <img src="<?php echo htmlspecialchars($_SESSION['profile_pic']); ?>" alt="Profile" class="w-full h-full object-cover">
          <?php else: ?>
            <?php echo strtoupper(substr($userName, 0, 1)); ?>
          <?php endif; ?>
        </div>
        <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($userName); ?></h3>
        <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($userEmail); ?></p>
        <a href="account_settings.php" class="bg-primary text-white inline-block px-6 py-3 rounded-lg font-bold hover:bg-opacity-80 transition w-full text-center">Account Settings</a>
      </div>

      <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-xl font-bold mb-4 text-left">Quick Links</h3>
        <ul class="space-y-2 text-left">
          <li><a href="account_settings.php#profile" class="text-gray-700 hover:text-primary">Profile</a></li>
          <li><a href="account_settings.php#security" class="text-gray-700 hover:text-primary">Security</a></li>
          <li><a href="account_settings.php#preferences" class="text-gray-700 hover:text-primary">Preferences</a></li>
          <li><a href="account_settings.php#payments" class="text-gray-700 hover:text-primary">Payments</a></li>
          <li><a href="account_settings.php#privacy" class="text-gray-700 hover:text-primary">Privacy</a></li>
          <li><a href="#booking-history" class="text-gray-700 hover:text-primary">Booking History</a></li>
          <li>
            <form action="logout.php" method="POST" style="margin:0;">
              <button type="submit" class="text-red-600 hover:text-red-800 font-bold">Log out</button>
            </form>
          </li>
        </ul>
      </div>
    </section>

    <!-- Right Section: Booking History only -->
    <section class="lg:col-span-2 space-y-8">

      <div id="booking-history" class="bg-white p-6 rounded-lg shadow-lg">
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
            <?php foreach ($bookings as $booking): ?>
              <?php if (in_array($booking['status'], ['CONFIRMED', 'CANCELLED'])): ?>
                <div class="border p-4 rounded-lg flex flex-col md:flex-row justify-between items-start gap-4">
                  <div class="flex-grow">
                    <p class="font-semibold text-lg text-accent"><?php echo htmlspecialchars($booking['title']); ?></p>
                    <p class="text-sm text-gray-500"><?php echo date("F j, Y", strtotime($booking['event_date'])) . " - " . date("g:i A", strtotime($booking['event_time']))?></p>
                    <?php if ($booking['status'] === 'CONFIRMED'): ?>
                      <p class="text-sm text-green-600 font-medium mt-1 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                        </svg>
                        Confirmed
                      </p>  
                    <?php elseif ($booking['status'] === 'CANCELLED'): ?>
                      <p class="text-sm text-red-600 font-medium mt-1 inline-flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round"
                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Cancelled
                      </p>
                    <?php endif; ?>
                  </div>
                  <div class="flex-shrink-0 flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                    <button onclick="viewReceipt(<?php echo (int)$booking['booking_id']; ?>)"
                      class="w-full sm:w-auto text-center bg-secondary text-primary font-semibold text-sm py-2 px-4 rounded-lg hover:bg-opacity-80">
                      View Receipt
                    </button>
                    <?php if ($booking['status'] === 'CONFIRMED'): ?>
                      <button
                        class="w-full sm:w-auto text-center bg-red-100 text-red-700 font-semibold text-sm py-2 px-4 rounded-lg hover:bg-red-200 cancel-booking-btn"
                        data-booking-id="<?php echo htmlspecialchars($booking['booking_id']); ?>">
                        Cancel
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
              <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <!-- Past Bookings -->
        <div id="past-bookings" class="booking-tab-content mt-6 space-y-4 hidden overflow-auto h-[400px] pr-2">
          <?php foreach ($bookings as $booking): ?>
            <?php if ($booking['status'] === 'COMPLETED'): ?>
              <div class="border p-4 rounded-lg flex flex-col md:flex-row justify-between items-start gap-4 bg-gray-50">
                <div class="flex-grow">
                  <p class="font-semibold text-lg text-gray-600"><?php echo htmlspecialchars($booking['title']); ?></p>
                  <p class="text-sm text-gray-500"><?php echo date("F j, Y", strtotime($booking['event_date'])) . " - " . date("g:i A", strtotime($booking['event_time']))?></p>
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
                <div class="flex-shrink-0">
                  <button onclick="viewReceipt(<?php echo (int)$booking['booking_id']; ?>)"
                    class="w-full sm:w-auto text-center bg-secondary text-primary font-semibold text-sm py-2 px-4 rounded-lg hover:bg-opacity-80">
                    View Receipt
                  </button>
                </div>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
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
    const menu = document.getElementById('mobile-menu');
    const navLinks = document.getElementById('nav-links');
    menu.addEventListener('click', () => {
      navLinks.classList.toggle('active');
      menu.classList.toggle('open');
    });
  </script>

  <!-- Receipt Modal -->
  <div id="receiptModal">
    <div class="bg-gray-100 p-8 max-w-xl w-full mx-4">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-800">Booking Receipt</h2>
        <button onclick="closeReceiptModal()" class="text-gray-600 hover:text-gray-800">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div id="receipt-container" class="receipt-container relative mb-6">
        <div id="receipt" class="bg-white text-gray-900 p-8 rounded-lg shadow-lg w-full">
          <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-primary">Maysan Badminton Court</h1>
            <p class="text-sm text-gray-500">OFFICIAL RECEIPT</p>
          </div>

          <div id="receiptContent">
            <div class="h-4 bg-gray-200 rounded mb-2"></div>
            <div class="h-4 bg-gray-200 rounded mb-2 w-3/4"></div>
            <div class="h-4 bg-gray-200 rounded mb-2 w-1/2"></div>
          </div>
        </div>
      </div>

      <div class="flex justify-end gap-4">
        <button onclick="closeReceiptModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">Close</button>
        <button onclick="downloadReceipt()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
          Download Receipt (PNG)
        </button>
      </div>
    </div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script>
    let currentReceiptData = null;

    function viewReceipt(bookingId) {
      const modal = document.getElementById('receiptModal');
      const content = document.getElementById('receiptContent');
      
      // Show loading state (skeleton placeholders without animation)
      content.innerHTML = `
        <div>
          <div class="h-4 bg-gray-200 rounded mb-2"></div>
          <div class="h-4 bg-gray-200 rounded mb-2 w-3/4"></div>
          <div class="h-4 bg-gray-200 rounded mb-2 w-1/2"></div>
        </div>
      `;
      
      modal.classList.add('active');

      // Fetch receipt data
      fetch(`fetch_receipt.php?booking_id=${bookingId}`)
        .then(response => response.json())
        .then(data => {
          if (data.error) {
            content.innerHTML = `<p class="text-red-600">${data.error}</p>`;
            return;
          }

          currentReceiptData = data.receipt;
          
          // Render receipt content
          content.innerHTML = `
            <div class="mb-4">
              <div class="flex justify-between mb-1">
                <span class="text-gray-600">Transaction ID:</span>
                <span class="font-mono font-bold">${data.receipt.transaction_id}</span>
              </div>
              <div class="flex justify-between mb-1">
                <span class="text-gray-600">Date Paid:</span>
                <span class="font-mono font-bold">${data.receipt.date_now}</span>
              </div>
              <div class="flex justify-between mb-1">
                <span class="text-gray-600">Paid By:</span>
                <span class="font-bold">${data.receipt.customer_name}</span>
              </div>
            </div>

            <div class="border-t border-dashed border-gray-300 my-4"></div>

            <div class="mb-4">
              <h2 class="text-lg font-semibold mb-2">Reservation Details</h2>
              <div class="flex justify-between mb-1">
                <span class="text-gray-600">Activity / Title:</span>
                <span class="font-bold">${data.receipt.title}</span>
              </div>
              <div class="flex justify-between mb-1">
                <span class="text-gray-600">Court:</span>
                <span class="font-bold">Court ${data.receipt.court_number}</span>
              </div>
              <div class="flex justify-between mb-1">
                <span class="text-gray-600">Date:</span>
                <span class="font-bold">${data.receipt.event_date}</span>
              </div>
              <div class="flex justify-between mb-1">
                <span class="text-gray-600">Time:</span>
                <span class="font-bold">${data.receipt.event_time}</span>
              </div>
            </div>

            <div class="border-t border-dashed border-gray-300 my-4"></div>

            <div class="mb-6">
              <div class="flex justify-between items-center text-xl font-bold">
                <span>Total Paid:</span>
                <span class="text-green-600">PHP ${data.receipt.total_fee}</span>
              </div>
              <div class="flex justify-between items-center mt-2">
                <span class="text-gray-600">Payment Method:</span>
                <span class="font-bold">Online Payment</span>
              </div>
            </div>

            <div class="relative text-center">
              <div class="absolute inset-0 flex items-center justify-center">
                <span class="text-6xl font-black text-green-500 opacity-20 transform -rotate-12 select-none">
                  ${data.receipt.status}
                </span>
              </div>
              <p class="text-gray-500 italic">Thank you for your reservation!</p>
            </div>
          `;
        })
        .catch(error => {
          content.innerHTML = `<p class="text-red-600">Error loading receipt. Please try again.</p>`;
          console.error('Error:', error);
        });
    }

    function closeReceiptModal() {
      document.getElementById('receiptModal').classList.remove('active');
    }

    function downloadReceipt() {
      if (!currentReceiptData) return;

      const receiptElement = document.getElementById('receipt');
      const downloadBtn = document.querySelector('#receiptModal button:last-child');
      const originalText = downloadBtn.innerHTML;
      
      downloadBtn.innerHTML = 'Generating...';
      downloadBtn.disabled = true;

      html2canvas(receiptElement, { 
        scale: 2,
        useCORS: true,
        backgroundColor: '#ffffff'
      }).then(canvas => {
        const dataUrl = canvas.toDataURL('image/png');
        const link = document.createElement('a');
        link.href = dataUrl;
        link.download = `receipt-booking-${currentReceiptData.booking_id}.png`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        downloadBtn.innerHTML = originalText;
        downloadBtn.disabled = false;
      }).catch(err => {
        console.error('Error generating receipt:', err);
        downloadBtn.innerHTML = 'Error - Try Again';
        downloadBtn.disabled = false;
        setTimeout(() => downloadBtn.innerHTML = originalText, 2000);
      });
    }

    // Close modal when clicking outside
    document.getElementById('receiptModal').addEventListener('click', (e) => {
      if (e.target.id === 'receiptModal') {
        closeReceiptModal();
      }
    });
  </script>

  <script src="profile_page.js"></script>
</body>
</html>

