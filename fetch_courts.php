<?php
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

$stmt = $conn->prepare("SELECT court_number, event_time, status FROM bookings WHERE event_date = ? AND status IN ('PENDING','CONFIRMED')");
$stmt->bind_param("s", $date);
$stmt->execute();
$result = $stmt->get_result();

$bookedSlots = [];
while ($row = $result->fetch_assoc()) {
  $bookedSlots[$row['court_number']][$row['event_time']] = $row['status'];
}
?>

<?php foreach ($courts as $courtNum => $courtName): ?>
  <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-secondary/40 mb-6">
    <div class="bg-primary text-white p-4">
      <h3 class="font-bold text-lg"><?= $courtName ?></h3>
    </div>
    <div class="p-4 space-y-3">
      <?php foreach ($times as $time => $label):
        $slotStatus = $bookedSlots[$courtNum][$time] ?? null;
      ?>
        <div class="flex justify-between items-center p-2 border-b border-secondary/50">
          <span class="text-accent"><?= $label ?></span>
          <?php if ($slotStatus === 'CONFIRMED'): ?>
            <span class="text-red-500 text-sm font-medium">Booked</span>
          <?php elseif ($slotStatus === 'PENDING'): ?>
            <span class="text-yellow-500 text-sm font-medium">Pending</span>
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