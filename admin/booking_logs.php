<?php
require_once "../config/config.php";

$sort_column = $_GET['sort'] ?? 'event_date';
$sort_order = $_GET['order'] ?? 'DESC';
$status_filter = $_GET['status'] ?? 'ALL';

$allowed_columns = ['booking_id', 'user_id', 'title', 'event_date', 'event_time', 'status'];

if (!in_array($sort_column, $allowed_columns)) {
    $sort_column = 'event_date';
}

$bookings = [];
$sql = "SELECT booking_id, user_id, title, event_date, event_time, status FROM bookings";

if ($status_filter != 'ALL') {
    $sql .= " WHERE status = ?";
}

$sql .= " ORDER BY $sort_column $sort_order";

$stmt = $conn->prepare($sql);

if ($status_filter != 'ALL') {
    $stmt->bind_param("s", $status_filter);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

$next_order = ($sort_order == 'ASC') ? 'DESC' : 'ASC';

?>

<h1 class="text-3xl font-bold text-primary mb-6">Booking Logs</h1>

<div class="mb-4">
    <a href="?page=bookings&status=ALL" class="px-4 py-2 rounded-md <?php echo ($status_filter == 'ALL') ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700'; ?>">All</a>
    <a href="?page=bookings&status=CONFIRMED" class="px-4 py-2 rounded-md <?php echo ($status_filter == 'CONFIRMED') ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700'; ?>">Confirmed</a>
    <a href="?page=bookings&status=COMPLETED" class="px-4 py-2 rounded-md <?php echo ($status_filter == 'COMPLETED') ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700'; ?>">Completed</a>
    <a href="?page=bookings&status=CANCELLED" class="px-4 py-2 rounded-md <?php echo ($status_filter == 'CANCELLED') ? 'bg-primary text-white' : 'bg-gray-200 text-gray-700'; ?>">Cancelled</a>
</div>

<div class="bg-white p-6 rounded-lg shadow-md">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm"><a href="?page=bookings&sort=booking_id&order=<?php echo $next_order; ?>&status=<?php echo $status_filter; ?>">Booking ID <?php if ($sort_column == 'booking_id') echo $sort_order == 'ASC' ? '&#9650;' : '&#9660;'; ?></a></th>
                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm"><a href="?page=bookings&sort=user_id&order=<?php echo $next_order; ?>&status=<?php echo $status_filter; ?>">User ID <?php if ($sort_column == 'user_id') echo $sort_order == 'ASC' ? '&#9650;' : '&#9660;'; ?></a></th>
                <th class="w-1/3 py-3 px-4 uppercase font-semibold text-sm"><a href="?page=bookings&sort=title&order=<?php echo $next_order; ?>&status=<?php echo $status_filter; ?>">Title <?php if ($sort_column == 'title') echo $sort_order == 'ASC' ? '&#9650;' : '&#9660;'; ?></a></th>
                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm"><a href="?page=bookings&sort=event_date&order=<?php echo $next_order; ?>&status=<?php echo $status_filter; ?>">Event Date <?php if ($sort_column == 'event_date') echo $sort_order == 'ASC' ? '&#9650;' : '&#9660;'; ?></a></th>
                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm"><a href="?page=bookings&sort=event_time&order=<?php echo $next_order; ?>&status=<?php echo $status_filter; ?>">Event Time <?php if ($sort_column == 'event_time') echo $sort_order == 'ASC' ? '&#9650;' : '&#9660;'; ?></a></th>
                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm"><a href="?page=bookings&sort=status&order=<?php echo $next_order; ?>&status=<?php echo $status_filter; ?>">Status <?php if ($sort_column == 'status') echo $sort_order == 'ASC' ? '&#9650;' : '&#9660;'; ?></a></th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            <?php foreach ($bookings as $booking): ?>
                <tr>
                    <td class="w-1/6 py-3 px-4"><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                    <td class="w-1/6 py-3 px-4"><?php echo htmlspecialchars($booking['user_id']); ?></td>
                    <td class="w-1/3 py-3 px-4"><?php echo htmlspecialchars($booking['title']); ?></td>
                    <td class="w-1/6 py-3 px-4"><?php echo htmlspecialchars($booking['event_date']); ?></td>
                    <td class="w-1/6 py-3 px-4"><?php echo htmlspecialchars($booking['event_time']); ?></td>
                    <td class="w-1/6 py-3 px-4"><?php echo htmlspecialchars($booking['status']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>