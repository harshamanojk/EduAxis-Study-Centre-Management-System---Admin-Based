<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header('Location: ADMIN DASHBOARD.php');
    exit();
}

include('INCLUDES/db.php');

// Fetch booked slots
$bookingSql = "
    SELECT 
        b.BookingID, 
        b.UserID,
        u.name, 
        u.email, 
        b.Slot, 
        b.BookingDateChosen, 
        b.Status, 
        b.attendance_status,
        b.created_at
    FROM slotbookings b
    JOIN users u ON b.UserID = u.id
    ORDER BY b.BookingDateChosen DESC
";

// Fetch waitlist slots
$waitlistSql = "
    SELECT 
        w.id AS BookingID, 
        w.UserID,
        u.name, 
        u.email, 
        w.Slot, 
        w.BookingDateChosen, 
        'Waiting' AS Status, 
        'N/A' AS attendance_status,
        w.request_date AS created_at
    FROM waitlist w
    JOIN users u ON w.UserID = u.id
    LEFT JOIN slotbookings b 
        ON b.UserID = w.UserID 
        AND b.Slot = w.Slot 
        AND b.BookingDateChosen = w.BookingDateChosen
    WHERE b.BookingID IS NULL
      AND w.id = (
          SELECT MIN(id) 
          FROM waitlist 
          WHERE UserID = w.UserID 
            AND Slot = w.Slot 
            AND BookingDateChosen = w.BookingDateChosen
      )
    ORDER BY w.request_date ASC
";

$allSlots = [];

// Booked slots
$result = $conn->query($bookingSql);
while ($row = $result->fetch_assoc()) {
    $allSlots[] = $row;
}

// Waitlist slots
$result = $conn->query($waitlistSql);
while ($row = $result->fetch_assoc()) {
    $allSlots[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>USERS SLOTS | EduAxis</title>
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

        <h2><u>USER'S SLOTS</u></h2>

        <table class="table table-bordered table-striped mt-3">
            <thead class="table-dark">
                <tr>
                    <th>BookingID</th>
                    <th>UserID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Slot</th>
                    <th>Booking Date Chosen</th>
                    <th>Status</th>
                    <th>Attendance</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
            <?php if (!empty($allSlots)): ?>
                <?php foreach ($allSlots as $row): ?>
                    <tr>
                        <td data-label="BookingID"><?= htmlspecialchars($row['BookingID']) ?></td>
                        <td data-label="UserID"><?= htmlspecialchars($row['UserID']) ?></td>
                        <td data-label="Name"><?= htmlspecialchars($row['name']) ?></td>
                        <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
                        <td data-label="Slot"><?= htmlspecialchars($row['Slot']) ?></td>
                        <td data-label="Booking Date Chosen"><?= htmlspecialchars($row['BookingDateChosen']) ?></td>
                        <td data-label="Status"><?= htmlspecialchars($row['Status']) ?></td>
                        <td data-label="Attendance">
                            <?php 
                                $att = htmlspecialchars($row['attendance_status']);
                                if ($att === "Present") {
                                    echo "<span style='color: green; font-weight: bold;'>$att</span>";
                                } elseif ($att === "Absent") {
                                    echo "<span style='color: red; font-weight: bold;'>$att</span>";
                                } elseif ($att === "Pending") {
                                    echo "<span style='color: orange; font-weight: bold;'>$att</span>";
                                } else {
                                    echo $att;
                                }
                            ?>
                        </td>
                        <td data-label="Created At"><?= htmlspecialchars($row['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No slots found</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

<?php $conn->close(); ?>
