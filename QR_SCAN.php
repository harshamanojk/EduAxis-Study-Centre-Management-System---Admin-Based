<!DOCTYPE html>
<html>
<head>
    <title>QR SCANNER | EduAxis</title>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <link rel="icon" type="image/x-icon" href="IMGS/LOGO.ico">
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }
        #reader {
            width: 400px;
            margin: auto;
        }
        #result {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }
        button {
            margin-top: 10px;
            padding: 8px 16px;
            font-size: 16px;
            cursor: pointer;
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
    </style>
</head>
<body>
<div class="form-container">
    <div class="img">
        <img src="IMGS/LOGO.jpg" alt="Logo">
</div>
    <h2>📸 QR Code Scanner</h2>
    <p>Show your QR code to the camera</p>
    <div id="reader"></div>
    <div id="result"></div>

    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Stop scanner after first scan
            html5QrcodeScanner.clear();

            // Show what we scanned
            document.getElementById("result").innerHTML = "Scanned: " + decodedText;

            // Send token to SCAN.php
            fetch("SCAN.php?qr_token=" + encodeURIComponent(decodedText))
                .then(response => response.text())
                .then(data => {
                    document.getElementById("result").innerHTML = data;
                })
                .catch(err => {
                    document.getElementById("result").innerHTML = "❌ Error: " + err;
                });
        }

        function onScanFailure(error) {
            // Ignore scanning errors
        }

        // Initialize scanner with rear camera
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", 
            { 
                fps: 10, 
                qrbox: 250,
                facingMode: "environment" // rear camera
            }
        );
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
</body>
</html>
