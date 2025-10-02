<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header('Location: ADMIN DASHBOARD.php');
    exit();
}

include('INCLUDES/db.php');

// Fetch all suggestions
$suggestionsSql = "SELECT id, fullname, contact_number, email_id,subject,submitted_at FROM contact_form ORDER BY id";
$result = $conn->query($suggestionsSql);

$allSuggestions = [];
while ($row = $result->fetch_assoc()) {
    $allSuggestions[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SUGGESTIONS | EduAxis</title>
<link rel="icon" type="image/x-icon" href="IMGS/LOGO.ico">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<style>
body { 
    font-family: "DM Sans","sans-serif"; 
    margin-top: 2px; 
}
.container { 
    display: flex; 
    justify-content: center; 
    padding: 20px; 
}
.content { 
    width: 95%; 
    text-align: center; 
}
.logo img { 
    width: 150px; 
    max-width: 80vw; 
    border-radius: 8px; 
    margin-bottom: 20px; 
}
table th, table td { 
    vertical-align: middle; 
    text-align: center; 
}

/* Mobile view → stacked cards */
@media (max-width: 768px) {
    .table thead {
        display: none;
    }
    .table, .table tbody, .table tr, .table td {
        display: block;
        width: 100%;
    }
    .table tr {
        margin-bottom: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 0.5rem;
        padding: 0.5rem;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .table td {
        text-align: left;
        padding: 0.5rem;
        border: none;
        border-bottom: 1px solid #f1f1f1;
        position: relative;
    }
    .table td:last-child {
        border-bottom: none;
    }
    .table td::before {
        content: attr(data-label) " ";
        font-weight: bold;
        display: block;
        margin-bottom: 0.2rem;
        color: #333;
    }
}
</style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="logo">
            <img src="IMGS/LOGO.jpg" alt="Logo">
        </div>

        <h2><u>CUSTOMER'S SUGGESTIONS</u></h2>

        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Suggestions</th>
                    <th>Submitted Time</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($allSuggestions)): ?>
                <?php foreach ($allSuggestions as $row): ?>
                    <tr>
                        <td data-label="ID"><?= htmlspecialchars($row['id']) ?></td>
                        <td data-label="Name"><?= htmlspecialchars($row['name']) ?></td>
                        <td data-label="Contact"><?= htmlspecialchars($row['contact']) ?></td>
                        <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
                        <td data-label="Suggestions"><?= htmlspecialchars($row['subject']) ?></td>
                        <td data-label="Submitted Time"><?= htmlspecialchars($row['submitted_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No Suggestions found</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

<?php $conn->close(); ?>