<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['uid'])) {
    header('Location: ADMIN DASHBOARD.php');
    exit;
}

$error = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = trim($_POST['txtemail']);
    $password = trim($_POST['txtpassword']);

    if ($email == '' || $password == '') {
        $error = "Please fill all the fields";
    } else {
        // Database connection
        $servername = "localhost";
        $username = "root";
        $dbpassword = "hmk12345#Tia"; // Change this to your database password //
        $dbname = "eduaxis";

        $conn = new mysqli($servername, $username, $dbpassword, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Use prepared statement to avoid SQL injection
        $stmt = $conn->prepare("SELECT id, name, email, password FROM admin_user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $error = "Email or password is wrong";
        } else {
            $data = $result->fetch_assoc();
            // Verify bcrypt password
            if (password_verify($password, $data['password'])) {
                $_SESSION['uid'] = $data['id'];
                $_SESSION['fullname'] = $data['name'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['admin'] = true; // ✅ mark as admin

                header('Location: ADMIN DASHBOARD.php');
                exit;
            } else {
                $error = "Email or password is wrong";
            }
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ADMIN LOGIN | EduAxis</title>
<!-- Bootstrap and custom CSS links -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href="CSS/access.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<link rel="icon" type="image/x-icon" href="IMGS/LOGO.ico">
</head>
<body>
<div class="container">
    <div class="form-container">
        <div class="img">
            <img src="IMGS/LOGO.jpg" alt="Logo">
        </div>
        <h2 style="text-align:center"><u><b>EDUAXIS ADMIN</b></u></h2>
        <p style="font-size: 18px; text-align: center;"><u>LOGIN</u></p>
        <form method="post" action="LOGIN_FORM.php">
            <div class="form-group">
                <input type="email" class="form-control" name="txtemail" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="txtpassword" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <span class="error" style="color:red;"><?php echo $error; ?></span>
        </form>
        <br>
    </div>
</div>

<footer class="text-center py-4 bg-dark text-light">
    <p>&copy; 2025 EduAxis. All rights reserved.</p>
</footer>
</body>
</html>
