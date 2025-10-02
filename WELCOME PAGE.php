<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ADMIN PORTAL| EduAxis</title>
<link rel="icon" type="image/x-icon" href="IMGS/LOGO.ico">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<style>
body {
    height: 100%;
    margin: 0;
    background-color: white;
    font-family: Arial, sans-serif;
}
.img {
    text-align: center;
    margin-top: 5px;
}
.img img {
    width: 150px;
    max-width: 80vw;
    height: auto;
    border-radius: 8px;
}
.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    padding: 15px;
    box-sizing: border-box;
}
.form-container {
    width: 100%;
    max-width: 400px;
    padding: 30px 20px;
    box-shadow: 0 0 15px rgba(0,0,0,0.2);
    border-radius: 8px;
    background-color: lightblue;
}
.btn-primary {
    display: block;
    margin: 15px auto;
    width: 100%;
    max-width: 200px;
    font-size: 16px;
    padding: 12px;
}
@media (max-width: 480px) {
    .form-container {
        padding: 20px 15px;
    }
    .btn-primary {
        max-width: 100%;
    }
}
</style>
</head>
<body>
<div class="container">
    <div class="form-container">
        <div class="img">
            <img src="IMGS/LOGO.jpg" alt="Logo">
        </div>
        <h2 style="text-align:center"><u><b>EDUAXIS ADMIN</b></u></h2>
        <a href="LOGIN_FORM.php" target="_blank" class="btn btn-primary">LOGIN</a>
        <a href="REGISTER_FORM.php" target="_blank" class="btn btn-primary">REGISTER</a>
        <br>
    </div>
</div>
<footer class="text-center py-4 bg-dark text-light">
    <p>&copy; 2025 EduAxis. All rights reserved.</p>
</footer>
</body>
</html>
