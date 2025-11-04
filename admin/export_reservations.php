<?php
require_once __DIR__ . "/../config/config.php";

// Simple endpoint to export reservations as CSV (for Excel) or JSON for client-side PDF generation
$format = isset($_GET['format']) ? strtolower($_GET['format']) : 'csv';
$from = isset($_GET['from']) && $_GET['from'] !== '' ? $_GET['from'] : null;
$to = isset($_GET['to']) && $_GET['to'] !== '' ? $_GET['to'] : null;
$status = isset($_GET['status']) && $_GET['status'] !== '' ? $_GET['status'] : null;

$where = [];
if ($from) {
    $fromEsc = $conn->real_escape_string($from);
    $where[] = "event_date >= '" . $fromEsc . "'";
}
if ($to) {
    $toEsc = $conn->real_escape_string($to);
    $where[] = "event_date <= '" . $toEsc . "'";
}
if ($status) {
    $statusEsc = $conn->real_escape_string($status);
    $where[] = "status = '" . $statusEsc . "'";
}

$sql = "SELECT booking_id, user_id, title, activity_name, court_number, num_of_participants, total_fee, event_date, event_time, event_end_time, status FROM bookings";
if (!empty($where)) $sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= ' ORDER BY event_date, event_time';

$res = $conn->query($sql);
$rows = [];
if ($res) {
    while ($r = $res->fetch_assoc()) {
        $rows[] = $r;
    }
}

if ($format === 'json') {
    header('Content-Type: application/json');
    echo json_encode($rows);
    exit;
}

// Default: CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=reservation_report_' . date('Ymd_His') . '.csv');
$out = fopen('php://output', 'w');
fputcsv($out, ['Booking ID', 'User ID', 'Title', 'Activity', 'Court', 'Participants', 'Total Fee', 'Event Date', 'Start', 'End', 'Status']);
foreach ($rows as $r) {
    fputcsv($out, [$r['booking_id'], $r['user_id'], $r['title'], $r['activity_name'], $r['court_number'], $r['num_of_participants'], $r['total_fee'], $r['event_date'], $r['event_time'], $r['event_end_time'], $r['status']]);
}
fclose($out);
exit;
