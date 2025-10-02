<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header('Location: ADMIN DASHBOARD.php');
    exit();
}

include('INCLUDES/db.php');

/* =======================
   Fetch All Students
   ======================= */
$userSql = "
    SELECT u.id, u.name, u.email, u.contact
    FROM users u
    LEFT JOIN slotbookings b ON u.id = b.UserID
    GROUP BY u.id
    ORDER BY u.id
";
$result = $conn->query($userSql);

$allUser = [];   // FIX: store users
while ($row = $result->fetch_assoc()) {
    $allUser[] = $row;
}

/* =======================
   Fetch All Admins
   ======================= */
$adminSql = "SELECT id, name, email, contact FROM admin_user ORDER BY id";
$result = $conn->query($adminSql);

$allAdmin = [];
while ($row = $result->fetch_assoc()) {
    $allAdmin[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ACCOUNTS | EduAxis</title>
<link rel="icon" type="image/x-icon" href="IMGS/LOGO.ico">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<style>
body { 
    font-family: "DM Sans", sans-serif; 
    background: #f8f9fa; 
}
.container { 
    max-width: 1300px;
    margin: auto;
    padding: 20px; 
    text-align: center;
}
.logo img { 
    width: 160px; 
    border-radius: 8px; 
    margin-bottom: 20px; 
}
.section { margin-bottom: 50px; }
h2 { font-weight: 600; margin: 20px 0; color: #333; }
.table { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
.table th, .table td { vertical-align: middle; text-align: center; padding: 12px; }
.table-dark th { background-color: #212529 !important; color: #fff !important; }

/* Mobile view → stacked cards */
@media (max-width: 768px) {
    .table thead { display: none; }
    .table, .table tbody, .table tr, .table td { display: block; width: 100%; }
    .table tr { margin-bottom: 1rem; border: 1px solid #dee2e6; border-radius: 0.5rem; padding: 0.75rem; background: #fff; box-shadow: 0 1px 4px rgba(0,0,0,0.1); }
    .table td { text-align: left; padding: 0.5rem; border: none; border-bottom: 1px solid #f1f1f1; position: relative; }
    .table td:last-child { border-bottom: none; }
    .table td::before { content: attr(data-label); font-weight: bold; display: block; margin-bottom: 0.25rem; color: #555; }
}
</style>
</head>
<body>
<div class="container">
    <div class="logo">
        <img src="IMGS/LOGO.jpg" alt="Logo">
    </div>

    <!-- USERS TABLE -->
<div class="section">
    <h2><u>STUDENTS</u></h2>
    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Contact</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($allUser)): ?>
            <?php foreach ($allUser as $row): ?>
                <tr>
                    <td data-label="ID"><?= htmlspecialchars($row['id']) ?></td>
                    <td data-label="Name"><?= htmlspecialchars($row['name']) ?></td>
                    <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
                    <td data-label="Contact"><?= htmlspecialchars($row['contact']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="6">No users found</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

    <!-- ADMIN TABLE -->
    <div class="section">
        <h2><u>ADMIN USERS</u></h2>
        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($allAdmin)): ?>
                <?php foreach ($allAdmin as $row): ?>
                    <tr>
                        <td data-label="ID"><?= htmlspecialchars($row['id']) ?></td>
                        <td data-label="Name"><?= htmlspecialchars($row['name']) ?></td>
                        <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
                        <td data-label="Contact"><?= htmlspecialchars($row['contact']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="5">No admin users found</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

<?php $conn->close(); ?>


