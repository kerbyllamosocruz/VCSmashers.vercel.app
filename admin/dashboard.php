<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

$page = $_GET['page'] ?? 'dashboard';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="../script.js"></script>
</head>
<body class="bg-gray-100 font-montserrat">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-primary text-white flex flex-col">
            <div class="px-8 py-6 border-b border-secondary">
                <h2 class="text-2xl font-bold">Admin Panel</h2>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="dashboard.php?page=dashboard" class="flex items-center px-4 py-2 rounded-md hover:bg-accent">Dashboard</a>
                <a href="dashboard.php?page=bookings" class="flex items-center px-4 py-2 rounded-md hover:bg-accent">Booking Logs</a>
                <a href="dashboard.php?page=users" class="flex items-center px-4 py-2 rounded-md hover:bg-accent">User Logs</a>
                <a href="../profile_page.php" class="flex items-center px-4 py-2 rounded-md hover:bg-accent">Go Back</a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-10">
            <?php
            switch ($page) {
                case 'bookings':
                    include 'booking_logs.php';
                    break;
                case 'users':
                    include 'user_logs.php';
                    break;
                default:
                    include 'dashboard_home.php';
                    break;
            }
            ?>
        </div>
    </div>
</body>
</html>