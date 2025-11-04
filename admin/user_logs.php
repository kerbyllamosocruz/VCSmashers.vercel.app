<?php
require_once "../config/config.php";

$sort_column = $_GET['sort'] ?? 'user_id';
$sort_order = $_GET['order'] ?? 'DESC';
$allowed_columns = ['user_id', 'role_id', 'name', 'email', 'phone'];

if (!in_array($sort_column, $allowed_columns)) {
    $sort_column = 'user_id';
}

$users = [];
$sql = "SELECT user_id, role_id, name, email, phone FROM users ORDER BY $sort_column $sort_order";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$next_order = ($sort_order == 'ASC') ? 'DESC' : 'ASC';

?>

<h1 class="text-3xl font-bold text-primary mb-6">User Logs</h1>
<div class="bg-white p-6 rounded-lg shadow-md">
    <table class="min-w-full bg-white">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm"><a href="?page=users&sort=user_id&order=<?php echo $next_order; ?>">User ID <?php if ($sort_column == 'user_id') echo $sort_order == 'ASC' ? '&#9650;' : '&#9660;'; ?></a></th>
                <th class="w-1/6 py-3 px-4 uppercase font-semibold text-sm"><a href="?page=users&sort=role_id&order=<?php echo $next_order; ?>">Role ID <?php if ($sort_column == 'role_id') echo $sort_order == 'ASC' ? '&#9650;' : '&#9660;'; ?></a></th>
                <th class="w-1/3 py-3 px-4 uppercase font-semibold text-sm"><a href="?page=users&sort=name&order=<?php echo $next_order; ?>">Name <?php if ($sort_column == 'name') echo $sort_order == 'ASC' ? '&#9650;' : '&#9660;'; ?></a></th>
                <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm"><a href="?page=users&sort=email&order=<?php echo $next_order; ?>">Email <?php if ($sort_column == 'email') echo $sort_order == 'ASC' ? '&#9650;' : '&#9660;'; ?></a></th>
                <th class="w-1/4 py-3 px-4 uppercase font-semibold text-sm"><a href="?page=users&sort=phone&order=<?php echo $next_order; ?>">Phone <?php if ($sort_column == 'phone') echo $sort_order == 'ASC' ? '&#9650;' : '&#9660;'; ?></a></th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            <?php foreach ($users as $user): ?>
                <tr>
                    <td class="w-1/6 py-3 px-4"><?php echo htmlspecialchars($user['user_id']); ?></td>
                    <td class="w-1/6 py-3 px-4"><?php echo htmlspecialchars($user['role_id']); ?></td>
                    <td class="w-1/3 py-3 px-4"><?php echo htmlspecialchars($user['name']); ?></td>
                    <td class="w-1/4 py-3 px-4"><?php echo htmlspecialchars($user['email']); ?></td>
                    <td class="w-1/4 py-3 px-4"><?php echo htmlspecialchars($user['phone']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>