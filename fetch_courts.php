<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "config/config.php";

$date = $_GET['date'] ?? date('Y-m-d');

$courts = [
  1 => "Court 1",
  2 => "Court 2",
  3 => "Court 3"
];

$times = [
  "08:00:00" => "8:00 AM - 9:00 AM",
  "09:00:00" => "9:00 AM - 10:00 AM",
  "10:00:00" => "10:00 AM - 11:00 AM",
  "11:00:00" => "11:00 AM - 12:00 PM",
  "12:00:00" => "12:00 PM - 1:00 PM",
  "13:00:00" => "1:00 PM - 2:00 PM",
  "14:00:00" => "2:00 PM - 3:00 PM",
  "15:00:00" => "3:00 PM - 4:00 PM",
  "16:00:00" => "4:00 PM - 5:00 PM"
];

$stmt = $conn->prepare("SELECT * FROM bookings WHERE event_date = ? AND status IN ('PENDING','CONFIRMED','COMPLETED')");
if (!$stmt) {
    die("SQL Error: " . $conn->error);
}
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

$bookedSlots = [];
while ($row = $result->fetch_assoc()) {
  $bookedSlots[$row['court_number']][$row['event_time']] = [
    'status' => $row['status'],
    'user_id' => $row['user_id'],
    'booking_id' => $row['id'] ?? $row['booking_id'] ?? null
  ];
}
?>

<?php foreach ($courts as $courtNum => $courtName): ?>
  <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-secondary/40 mb-6">
    <div class="bg-primary text-white p-4">
      <h3 class="font-bold text-lg"><?= $courtName ?></h3>
    </div>
    <div class="p-4 space-y-3">
      <?php foreach ($times as $time => $label):
        $slotData = $bookedSlots[$courtNum][$time] ?? null;
        $slotStatus = $slotData['status'] ?? null;
        $slotUserId = $slotData['user_id'] ?? null;
        $bookingId = $slotData['booking_id'] ?? null;
        
        // Check if current user owns this booking
        $currentUserId = $_SESSION['user_id'] ?? null;
        $isOwnBooking = ($currentUserId && $slotUserId && $currentUserId == $slotUserId);
        
        // Debug - remove this after testing
        if ($slotStatus) {
          error_log("Debug - Current User: $currentUserId, Slot User: $slotUserId, Own Booking: " . ($isOwnBooking ? 'YES' : 'NO'));
        }
        
        // Check if this time slot has already passed for today
        $isPastTime = false;
        $today = date('Y-m-d');
        
        if ($date === $today) {
          $currentHour = (int)date('H');
          $slotHour = (int)substr($time, 0, 2);
          
          // Only block slots from previous hours, allow current hour and future hours
          if ($currentHour > $slotHour) {
            $isPastTime = true;
          }
          // Allow booking within current hour and future hours
        }
      ?>
        <div class="flex justify-between items-center p-2 border-b border-secondary/50">
          <span class="text-accent"><?= $label ?></span>
          <?php if ($isOwnBooking): ?>
            <?php 
            // Check if booking has actually ended (not just database status)
            $today = date('Y-m-d');
            $current_time = date('H:i:s');
            $booking_end_time = date('H:i:s', strtotime($time) + 3600); // Add 1 hour to start time
            
            $is_truly_completed = false;
            if ($date < $today) {
                // Past date - definitely completed
                $is_truly_completed = true;
            } elseif ($date === $today && $current_time >= $booking_end_time) {
                // Today and past end time - completed
                $is_truly_completed = true;
            }
            
            if ($is_truly_completed): ?>
              <button
                class="bg-green-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-green-700 transition view-receipt-btn"
                data-booking-id="<?= $bookingId ?>"
                title="Booking ID: <?= $bookingId ?>">
                Completed
              </button>
            <?php else: ?>
              <button
                class="bg-green-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-green-700 transition view-receipt-btn"
                data-booking-id="<?= $bookingId ?>"
                title="Booking ID: <?= $bookingId ?>">
                View Ticket
              </button>
            <?php endif; ?>
          <?php elseif ($slotStatus === 'CONFIRMED' || $slotStatus === 'COMPLETED'): ?>
            <span class="text-red-500 text-sm font-medium">Booked</span>
          <?php elseif ($slotStatus === 'PENDING'): ?>
            <span class="text-yellow-500 text-sm font-medium">Pending</span>
          <?php elseif ($isPastTime): ?>
            <button
              class="bg-gray-400 text-gray-600 px-3 py-1 rounded-lg text-sm cursor-not-allowed"
              disabled>
              Unavailable
            </button>
          <?php else: ?>
            <button
              class="bg-primary text-white px-3 py-1 rounded-lg text-sm hover:bg-accent transition openBookingModal"
              data-date="<?= $date ?>"
              data-time="<?= $time ?>"
              data-court="<?= $courtNum ?>">
              Book
            </button>
          <?php endif; ?>

        </div>
      <?php endforeach; ?>
    </div>
  </div>
<?php endforeach; ?>

<!-- Receipt Modal -->
<div id="receiptModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
  <div class="bg-white rounded-lg w-full max-w-md mx-auto max-h-[90vh] overflow-y-auto">
    <div class="sticky top-0 bg-white border-b border-gray-200 p-4 flex justify-between items-center">
      <h3 class="text-lg font-bold text-primary">Receipt</h3>
      <button id="closeReceiptModal" class="text-gray-500 hover:text-gray-700">
        <i data-feather="x"></i>
      </button>
    </div>
    
    <div id="receiptContent" class="p-6">
      <div class="text-center text-gray-500">
        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary mx-auto mb-2"></div>
        Loading receipt...
      </div>
    </div>
    
    <div class="sticky bottom-0 bg-gray-50 border-t border-gray-200 p-4 flex justify-end">
      <button id="downloadReceiptBtn" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-accent transition">
        Download Receipt
      </button>
    </div>
  </div>
</div>