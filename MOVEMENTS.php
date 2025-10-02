<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header('Location: ADMIN DASHBOARD.php');
    exit();
}

include 'INCLUDES/db.php';

/* =======================
   Fetch Movements
   ======================= */
$sql = "
    SELECT u.name, m.action, m.timestamp 
    FROM movements m 
    JOIN users u ON m.id = u.id 
    ORDER BY m.timestamp DESC 
    LIMIT 50
";
$result = $conn->query($sql);

$allMovements = [];
while ($row = $result->fetch_assoc()) {
    $allMovements[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MOVEMENTS REPORT| EduAxis</title>
<link rel="icon" type="image/x-icon" href="IMGS/LOGO.ico">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<style>
body { 
    font-family: "DM Sans", sans-serif; 
    background: #f8f9fa; 
}
.container { 
    max-width: 1100px;
    margin: auto;
    padding: 20px; 
    text-align: center;
}
.logo img { 
    width: 160px; 
    border-radius: 8px; 
    margin-bottom: 20px; 
}
h2 { font-weight: 600; margin: 20px 0; color: #333; }
.table { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.08); }
.table th, .table td { vertical-align: middle; text-align: center; padding: 12px; }
.table-dark th { background-color: #212529 !important; color: #fff !important; }

/* Mobile responsive cards */
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

    <!-- MOVEMENTS TABLE -->
    <h2><u>STUDENT MOVEMENTS</u></h2>
    <table class="table table-bordered table-striped mt-3">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Action</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($allMovements)): ?>
            <?php foreach ($allMovements as $row): ?>
                <tr>
                    <td data-label="Name"><?= htmlspecialchars($row['name']) ?></td>
                    <td data-label="Action"><?= htmlspecialchars($row['action']) ?></td>
                    <td data-label="Time"><?= htmlspecialchars($row['timestamp']) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3">No movements found</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>

<?php $conn->close(); ?>
