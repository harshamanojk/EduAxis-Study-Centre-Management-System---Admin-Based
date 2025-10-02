🏫 EduAxis Study Centre Management System (Admin Portal)
This is the Admin Panel of the Study Centre Management System, built with HTML + PHP + MySQL.
Admins can manage students, track attendance, handle payments, generate reports, and oversee operations.
________________________________________
📂 Project Structure
When unzipped correctly, the project should look like this:
ADMIN STUDY CENTRE/
│── WELCOME PAGE.php
│── REGISTER_FORM.php
│── LOGIN_FORM.php
│── ADMIN DASHBOARD.php
│── SLOTS.php
│── PAYMENT.php
│── WAITLIST.php
│── USERS.php
│── CUSTOMER SUGGESTIONS.php
│── REPORTS.php
│── ADMIN_ATTENDANCE.php
│── MOVEMENTS.php
│── QR_SCAN.php
│── UPDATE_PROFILE.php
│── DELETE_ACCOUNT.php
│── LOGOUT.php
│
├── CSS/
├── IMGS/
├── INCLUDES/
└── TCPDF-main/


🔓 Unzip Required Folders
When you download this project, you’ll see the following folders are provided as ZIP files:
•	CSS.zip
•	IMGS.zip
•	INCLUDES.zip
•	TCPDF-main.zip
👉 Before running the project, please extract/unzip these files into the main project folder.
⚠️ If you don’t unzip them, the project will not load styles, database connection, or PDF features.
________________________________________
⚙️ Requirements
•	XAMPP (Apache)
•	PHP 7.4+
•	MySQL 5.7+ 
•	Any modern web browser
________________________________________
🗄 Database Setup
1.	Open phpMyAdmin (http://localhost/phpmyadmin).
2.	Create a database: eduaxis
3.	Import EduAxis.sql
4.	Done ✅
________________________________________
🔑 Database Config
Check INCLUDES/db.php and set credentials:
•	host = "localhost"
•	user = "root"
•	password = "" (set your own MySQL password if any)
•	db = "EduAxis"
________________________________________

🚀 Run the Project
1.	Copy the folder to htdocs (XAMPP).
2.	Start Apache.
3.	Visit in browser:
http://localhost/ADMIN%20STUDY%20CENTRE/WELCOME%20PAGE.php
________________________________________
📌 Key Features
•	Admin dashboard
•	Manage student accounts
•	Slot & attendance management
•	Payment tracking
•	Generate receipts & reports (PDF)
•	QR code scanning
•	View customer suggestions
________________________________________
⚠️ Common Issues
•	Database not found → Import EduAxis.sql
•	Login not working → Check admin users exist in DB
•	Connection error → Update db.php
•	Blank page → Ensure PHP is running properly
•	PDF not working → Ensure TCPDF-main is extracted
