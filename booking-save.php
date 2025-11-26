<?php
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "config/config.php";

if (empty($_SESSION['user_id']) && !empty($_SESSION['payment_verified'])) {
    if (!empty($_POST['user_id'])) {
        $_SESSION['user_id'] = (int) $_POST['user_id'];
    } elseif (!empty($_SESSION['pending_booking']['user_id'])) {
        $_SESSION['user_id'] = (int) $_SESSION['pending_booking']['user_id'];
    }
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You must be logged in.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_SESSION['payment_verified'])) {
        echo json_encode(['status' => 'error', 'message' => 'Payment not verified.']);
        exit;
    }
    $user_id = (int) $_SESSION['user_id'];
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $activity_name = $_POST['activity_name'] ?? '';
    $court_number = (int) ($_POST['court_number'] ?? 1);
    $num_of_participants = (int) ($_POST['num_of_participants'] ?? 1);
    $event_date = $_POST['event_date'] ?? date('Y-m-d');
    $event_time = $_POST['event_time'] ?? date('H:i:s');
    $event_end_time = $_POST['event_end_time'] ?? null;

    $fee_per_head = match ($activity_name) {
        'Pickleball' => 100,
        'Badminton' => 80,
        default => 0,
    };
    $total_fee = $fee_per_head * $num_of_participants;

    $status = 'CONFIRMED';
    $slots = [];
    if (!empty($event_end_time) && strtotime($event_end_time) > strtotime($event_time)) {
        $t = strtotime($event_time);
        $end = strtotime($event_end_time);
        while ($t < $end) {
            $slots[] = date('H:i:s', $t);
            $t += 3600;
        }
    } else {
        $slots[] = $event_time;
    }

    foreach ($slots as $s) {
        $chk = $conn->prepare("SELECT COUNT(*) AS c FROM bookings WHERE event_date = ? AND court_number = ? AND event_time = ? AND status IN ('PENDING','CONFIRMED')");
        $chk->bind_param("sis", $event_date, $court_number, $s);
        $chk->execute();
        $res = $chk->get_result()->fetch_assoc();
        $chk->close();
        if (!empty($res['c']) && (int) $res['c'] > 0) {
            echo json_encode(['status' => 'error', 'message' => 'One or more selected time slots are already booked.']);
            $conn->close();
            exit;
        }
    }

    $conn->begin_transaction();
    $insert = $conn->prepare("INSERT INTO bookings (user_id, title, description, activity_name, court_number, num_of_participants, fee_per_head, total_fee, event_date, event_time, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if (!$insert) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare booking insert.']);
        $conn->close();
        exit;
    }
    $event_time_var = $event_time;
    $insert->bind_param("isssiiddsss", $user_id, $title, $description, $activity_name, $court_number, $num_of_participants, $fee_per_head, $total_fee, $event_date, $event_time_var, $status);

    $ok = true;
    foreach ($slots as $s) {
        $event_time_var = $s;
        if (!$insert->execute()) {
            $ok = false;
            break;
        }
    }

    if ($ok) {
        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Booking successful!']);
    } else {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Booking failed.']);
    }

    $insert->close();
    $conn->close();
}
