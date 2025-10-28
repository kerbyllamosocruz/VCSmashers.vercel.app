<?php
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
$totalDays = date('t', $firstDayOfMonth);
$monthName = date('F', $firstDayOfMonth);
$startDayOfWeek = date('w', $firstDayOfMonth);
?>

<div class="flex justify-between items-center mb-6">
  <h2 class="text-2xl font-bold text-primary"><?= $monthName . " " . $year ?></h2>
  <div class="flex space-x-2">
    <button data-nav data-month="<?= $month - 1 <= 0 ? 12 : $month - 1 ?>"
      data-year="<?= $month - 1 <= 0 ? $year - 1 : $year ?>"
      class="p-2 rounded-full hover:bg-secondary transition">
      <i data-feather="chevron-left" class="text-primary"></i>
    </button>
    <button data-nav data-month="<?= $month + 1 > 12 ? 1 : $month + 1 ?>"
      data-year="<?= $month + 1 > 12 ? $year + 1 : $year ?>"
      class="p-2 rounded-full hover:bg-secondary transition">
      <i data-feather="chevron-right" class="text-primary"></i>
    </button>
  </div>
</div>

<div class="grid grid-cols-7 gap-2 mb-4 text-center font-semibold text-accent">
  <div>Sun</div>
  <div>Mon</div>
  <div>Tue</div>
  <div>Wed</div>
  <div>Thu</div>
  <div>Fri</div>
  <div>Sat</div>
</div>

<div id="calendar-days" class="grid grid-cols-7 text-center border border-gray-300">
  <?php
  for ($i = 0; $i < $startDayOfWeek; $i++)
    echo '<div class="py-3 border border-gray-300 bg-transparent"></div>';

  for ($day = 1; $day <= $totalDays; $day++) {
    $dateValue = sprintf("%04d-%02d-%02d", $year, $month, $day);
    $isToday = ($day == date('j') && $month == date('n') && $year == date('Y'));

    echo '<div data-date="' . $dateValue . '" class="calendar-day py-3 cursor-pointer border border-gray-300 ' .
      ($isToday ? 'today bg-primary text-white font-bold' : 'bg-secondary/30 hover:bg-primary hover:text-white') .
      '">' . $day . '</div>';
  }
  ?>
</div>