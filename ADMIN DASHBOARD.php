<?php
    session_start();
    if (!isset($_SESSION['uid'])) {
        header('Location: LOGIN_FORM.php');
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ADMIN DASHBOARD | EduAxis</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="CSS/dashboard.css">
    <link rel="icon" type="image/x-icon" href="IMGS/LOGO.ico">
</head>
<body>
    <!-- User Info -->
    <div class="user-info text-center">
        <div class="nav-item dropdown">
            <?php if (!empty($thisuser['img'])): ?>
                <img src="<?= htmlspecialchars($thisuser['img']) ?>" alt="User Image" width="40" height="40">
            <?php else: ?>
                <img src="IMGS/user.png" alt="Default User Image" width="40" height="40">
            <?php endif; ?>
            <div class="welcome-msg">
                <span>Welcome,</span><br>
                <strong><?= htmlspecialchars($_SESSION['fullname'] ?? $thisuser['name']) ?></strong>
            </div>
            <div class="dropdown">
                <div class="dropdown-content">
                    <a href="UPDATE_PROFILE.php" target="_blank">Update Details</a>
                    <a href="LOGOUT.php">Log Out</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Logo -->
    <img src="IMGS/LOGO.jpg" alt="LOGO" style="display:block; margin:5px auto; width:150px; max-width:80vw; height:auto; border-radius:8px">

    <!-- Dashboard Heading -->
    <div class="dashboard-container text-center">
        <h1>WELCOME,</h1>
        <h1><strong><?= htmlspecialchars($_SESSION['fullname'] ?? $thisuser['name']) ?></strong></h1>
    </div>

    <!-- Dashboard Cards -->
    <div class="cards-wrapper text-center">

        <div class="card1 card">
            <a href="SLOTS.php" target="_blank" class="btn-link">SLOTS</a> 
        </div>

        <div class="card2 card">
            <a href="PAYMENTS.php" target="_blank" class="btn-link">PAYMENTS</a> 
        </div>

        <div class="card3 card"> 
            <a href="WAITLIST.php" target="_blank" class="btn-link">WAITLIST</a> 
        </div> 

        <div class="card4 card"> 
            <a href="USERS.php" target="_blank" class="btn-link">ACCOUNTS(USER & ADMINS)</a> 
        </div> 

        <div class="card5 card"> 
            <a href="CUSTOMER SUGGESTIONS.php" target="_blank" class="btn-link">CUSTOMER SUGGESTIONS</a> 
        </div>

        <div class="card6 card">
            <a href="REPORTS.php" target="_blank" class="btn-link">REPORTS</a>
        </div>

        <div class="card7 card">
            <a href="ADMIN_ATTENDANCE.php" target="_blank" class="btn-link">MANUAL ATTENDANCE</a>
        </div>

        <div class="card8 card">
            <a href="MOVEMENTS.php" target="_blank" class="btn-link"> STUDENT TRACKER </a>
            </div>

        <div class="card9 card">
            <a href="QR_SCAN.php" target="_blank" class="btn-link"> SCANNER </a>
    </div>

    <!-- Footer -->
    <footer class="text-center py-4 bg-dark text-light mt-4">
        <p>&copy; 2025 EduAxis. All rights reserved.</p>
    </footer>
</body>
</html>
