<?php
// send_notifications.php
// Run this script daily (e.g., cron or Task Scheduler) to send email reminders 1 day before bookings
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/mail.php';
require_once __DIR__ . '/PHPMailer/src/Exception.php';
require_once __DIR__ . '/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Get current date and time
$now = new DateTime();
$targetDate = $now->format('Y-m-d');

// Find bookings that are exactly 1 day away (between now and 24 hours from now)
$tomorrow = (clone $now)->add(new DateInterval('P1D'));
$tomorrowDate = $tomorrow->format('Y-m-d');

// Query for bookings scheduled for tomorrow (1 day away)
$sql = "SELECT b.booking_id, b.user_id, b.title, b.event_date, b.event_time, u.email, u.name FROM bookings b LEFT JOIN users u ON b.user_id = u.user_id WHERE b.event_date = ? AND b.status = 'CONFIRMED'";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $tomorrowDate);
$stmt->execute();
$result = $stmt->get_result();

$sent = 0;
$errors = [];
while ($row = $result->fetch_assoc()) {
    if (empty($row['email'])) continue;
    
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;
        
        $mail->setFrom('noreply@redsoiltradinghardware.store', 'Maysan Badminton Court');
        $mail->addAddress($row['email'], $row['name'] ?: 'Player');
        
        $mail->isHTML(true);
        $name = $row['name'] ?: 'Player';
        $mail->Subject = "Reminder: Booking tomorrow - {$row['title']}";
        $mail->Body = "Hello " . htmlspecialchars($name) . ",<br><br>This is a friendly reminder about your booking scheduled for <b>{$row['event_date']}</b> at <b>{$row['event_time']}</b>.<br><br>See you!<br>Maysan Badminton Court";
        
        if ($mail->send()) {
            $sent++;
        } else {
            $errors[] = "Failed to send to {$row['email']}: {$mail->ErrorInfo}";
        }
    } catch (Exception $e) {
        $errors[] = "Error sending to {$row['email']}: {$e->getMessage()}";
    }
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode(['date' => $targetDate, 'sent' => $sent, 'errors' => $errors]);

?>
