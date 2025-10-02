<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header('Location: ADMIN DASHBOARD.php');
    exit();
}

include('INCLUDES/db.php');

// ✅ Handle attendance update
if (isset($_POST['updateAttendance'])) {
    $bookingId = intval($_POST['bookingId']);
    $status = $_POST['attendance_status'];

    $stmt = $conn->prepare("UPDATE slotbookings SET attendance_status=? WHERE BookingID=?");
    $stmt->bind_param("si", $status, $bookingId);
    $stmt->execute();
    $stmt->close();

    $message = "✅ Attendance updated successfully.";
}

// ✅ Fetch all bookings
$sql = "
    SELECT b.BookingID, u.name, u.email, u.contact, 
           b.Slot, b.BookingDateChosen, s.start_time, s.end_time, 
           b.attendance_status
    FROM slotbookings b
    JOIN users u ON b.UserID = u.id
    JOIN slots s ON b.Slot = s.Slot
    ORDER BY b.BookingDateChosen DESC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ADMIN ATTENDANCE | EduAxis</title>
  <link rel="icon" type="image/x-icon" href="IMGS/LOGO.ico">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <img src="IMGS/LOGO.jpg" alt="Logo" style="width:150px;display:block;margin:0 auto 20px;border-radius:8px;">
  <h2 class="text-center mb-4">Manage Attendance</h2>

  <?php if (!empty($message)): ?>
    <div class="alert alert-info"><?= $message ?></div>
  <?php endif; ?>

  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-dark">
        <tr>
          <th>Booking ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Slot</th>
          <th>Date</th>
          <th>Timings</th>
          <th>Attendance</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <?php
            $slotStart = strtotime($row['BookingDateChosen'] . ' ' . $row['start_time']);
            $slotEnd   = strtotime($row['BookingDateChosen'] . ' ' . $row['end_time']);
            $now       = time();
            $isActive  = ($now >= $slotStart && $now <= $slotEnd);
          ?>
          <tr>
            <td><?= $row['BookingID'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['Slot']) ?></td>
            <td><?= htmlspecialchars($row['BookingDateChosen']) ?></td>
            <td><?= date("h:i A", strtotime($row['start_time'])) . " - " . date("h:i A", strtotime($row['end_time'])) ?></td>
            <td>
              <?php if ($row['attendance_status'] == "Present"): ?>
                <span class="badge bg-success">Present</span>
              <?php elseif ($row['attendance_status'] == "Absent"): ?>
                <span class="badge bg-danger">Absent</span>
              <?php else: ?>
                <span class="badge bg-warning text-dark">Pending</span>
              <?php endif; ?>
            </td>
            <td>
              <form method="POST" class="d-flex gap-2">
                <input type="hidden" name="bookingId" value="<?= $row['BookingID'] ?>">
                <select name="attendance_status" class="form-select form-select-sm"
                        <?= ($isActive && $row['attendance_status']=="Pending") ? "" : "disabled" ?>>
                  <option value="Pending" <?= $row['attendance_status']=="Pending"?"selected":"" ?>>Pending</option>
                  <option value="Present" <?= $row['attendance_status']=="Present"?"selected":"" ?>>Present</option>
                  <option value="Absent" <?= $row['attendance_status']=="Absent"?"selected":"" ?>>Absent</option>
                </select>
                <button type="submit" name="updateAttendance" class="btn btn-primary btn-sm"
                        <?= ($isActive && $row['attendance_status']=="Pending") ? "" : "disabled" ?>>
                  Save
                </button>
              </form>
              <?php if ($row['attendance_status'] != "Pending"): ?>
                <small class="text-muted">✅ Attendance already marked</small>
              <?php elseif (!$isActive): ?>
                <small class="text-muted">⏳ Slot not active</small>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
<?php $conn->close(); ?>
