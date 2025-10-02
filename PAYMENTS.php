<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header('Location: ADMIN DASHBOARD.php');
    exit();
}

include('INCLUDES/db.php');

// Fetch all payments
$paymentSql = "
    SELECT 
        p.id, 
        p.UserID, 
        p.BookingID, 
        p.Slot, 
        p.BookingDateChosen, 
        u.name, 
        u.email, 
        p.amount, 
        p.payment_status, 
        p.created_at
    FROM payments p
    JOIN users u ON p.UserID = u.id
    ORDER BY p.created_at
";

$allPayments = [];
$result = $conn->query($paymentSql);
while ($row = $result->fetch_assoc()) {
    $allPayments[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USERS PAYMENTS | EduAxis</title>
    <link rel="icon" type="image/x-icon" href="IMGS/LOGO.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body { font-family: "DM Sans","sans-serif"; margin-top: 2px; }
        .container { display: flex; justify-content: center; padding: 20px; }
        .content { width: 95%; text-align: center; }
        .logo img { width: 150px; max-width: 80vw; border-radius: 8px; margin-bottom: 20px; }
        table th, table td { vertical-align: middle; text-align: center; }

        /* Mobile view → stacked cards */
        @media (max-width: 768px) {
            .table thead { display: none; }
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
            .table td:last-child { border-bottom: none; }
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

            <h2><u>USER'S PAYMENTS</u></h2>

            <div class="table-responsive mt-3">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>UserID</th>
                            <th>BookingID</th>
                            <th>Slot</th>
                            <th>Booking Date Chosen</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Amount</th>
                            <th>Payment Status</th>
                            <th>Created At</th>
                            <th>Receipt</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($allPayments)): ?>
                            <?php foreach ($allPayments as $row): ?>
                                <tr>
                                    <td data-label="ID"><?= htmlspecialchars($row['id']) ?></td>
                                    <td data-label="UserID"><?= htmlspecialchars($row['UserID']) ?></td>
                                    <td data-label="BookingID"><?= htmlspecialchars($row['BookingID']) ?></td>
                                    <td data-label="Slot"><?= htmlspecialchars($row['Slot']) ?></td>
                                    <td data-label="Booking Date Chosen"><?= htmlspecialchars($row['BookingDateChosen']) ?></td>
                                    <td data-label="Name"><?= htmlspecialchars($row['name']) ?></td>
                                    <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
                                    <td data-label="Amount">₹<?= htmlspecialchars($row['amount']) ?></td>
                                    <td data-label="Payment Status">
                                        <?php if ($row['payment_status'] === 'Paid'): ?>
                                            <span class="badge bg-success">Paid</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning text-dark">Not Paid</span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Created At"><?= htmlspecialchars($row['created_at']) ?></td>
                                    <td data-label="Receipt">
                                        <?php if ($row['payment_status'] === 'Paid'): ?>
                                            <a href="ADMIN_RECEIPT.php?id=<?= $row['id'] ?>" target="_blank" class="btn btn-sm btn-primary">Download</a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11">No payments found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>

