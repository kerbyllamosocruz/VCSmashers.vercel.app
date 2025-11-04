<?php
require_once "../config/config.php";

// Total Bookings
$total_bookings_result = $conn->query("SELECT COUNT(*) as count FROM bookings");
$total_bookings = $total_bookings_result->fetch_assoc()['count'];

// Total Users
$total_users_result = $conn->query("SELECT COUNT(*) as count FROM users");
$total_users = $total_users_result->fetch_assoc()['count'];

// Total Revenue
$total_revenue_result = $conn->query("SELECT SUM(total_fee) as total FROM bookings WHERE status = 'COMPLETED'");
$total_revenue = $total_revenue_result->fetch_assoc()['total'];

?>
<h1 class="text-3xl font-bold text-primary mb-6">Dashboard</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold text-accent">Total Bookings</h3>
        <p class="text-4xl font-bold mt-2"><?php echo $total_bookings; ?></p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold text-accent">Total Users</h3>
        <p class="text-4xl font-bold mt-2"><?php echo $total_users; ?></p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-xl font-bold text-accent">Revenue</h3>
        <p class="text-4xl font-bold mt-2">&#8369; <?php echo number_format($total_revenue, 2); ?></p>
    </div>
</div>