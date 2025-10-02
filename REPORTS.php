<?php
include("INCLUDES/db.php");
require_once('TCPDF-main/tcpdf.php'); 

$rows = [];
$columns = [];
$table = $_GET['table'] ?? "";
$from = $_GET['from'] ?? "";
$to   = $_GET['to'] ?? "";

/* ==============================
   QUICK STATS FOR ANALYTICS
   ============================== */
$stats = [
    "students" => 0,
    "payments" => 0,
    "bookings" => 0,
    "present"  => 0,
    "absent"   => 0,
    "pending"  => 0
];

// Total Students
$res = $conn->query("SELECT COUNT(*) AS c FROM users");
$stats['students'] = $res->fetch_assoc()['c'] ?? 0;

// Total Payments (sum)
$res = $conn->query("SELECT SUM(amount) AS total FROM payments");
$stats['payments'] = $res->fetch_assoc()['total'] ?? 0;

// Total Bookings
$res = $conn->query("SELECT COUNT(*) AS c FROM slotbookings");
$stats['bookings'] = $res->fetch_assoc()['c'] ?? 0;

// Attendance distribution
$res = $conn->query("SELECT attendance_status, COUNT(*) AS c FROM slotbookings GROUP BY attendance_status");
while ($row = $res->fetch_assoc()) {
    $stats[strtolower($row['attendance_status'])] = $row['c'];
}

// Bookings per slot (for chart)
$slotData = [];
$res = $conn->query("SELECT Slot, COUNT(*) AS c FROM slotbookings GROUP BY Slot");
while ($row = $res->fetch_assoc()) {
    $slotData[$row['Slot']] = $row['c'];
}

/* ==============================
   FETCH DATA FOR SELECTED TABLE
   ============================== */
if (!empty($table)) {
    $columns = [];
    $colResult = $conn->query("SHOW COLUMNS FROM $table");
    while ($col = $colResult->fetch_assoc()) {
        $columns[] = $col['Field'];
    }

    $query = "SELECT * FROM $table";
    if (!empty($from) && !empty($to)) {
        if ($table == "payments" || $table == "slotbookings") {
            $query .= " WHERE BookingDateChosen BETWEEN '$from' AND '$to'";
        } elseif ($table == "users") {
            $query .= " WHERE created_at BETWEEN '$from' AND '$to'";
        }
    }

    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }
}

/* ==============================
   CSV DOWNLOAD
   ============================== */
if (isset($_GET['download']) && $_GET['download'] === 'csv' && !empty($rows)) {
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename={$table}_report.csv");

    $output = fopen("php://output", "w");
    fputcsv($output, $columns);
    foreach ($rows as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

/* ==============================
   PDF DOWNLOAD (TCPDF)
   ============================== */
if (isset($_GET['download']) && $_GET['download'] === 'pdf' && !empty($rows)) {
    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetMargins(10, 15, 10);
    $pdf->SetFont('helvetica', '', 10);
    $pdf->AddPage();

    $html = "<h2 style='text-align:center;'>Report: {$table}</h2>";
    $html .= "<table border='1' cellpadding='4'><thead><tr style='background-color:#f2f2f2;'>";
    foreach ($columns as $col) {
        $html .= "<th><b>" . htmlspecialchars($col) . "</b></th>";
    }
    $html .= "</tr></thead><tbody>";
    foreach ($rows as $row) {
        $html .= "<tr>";
        foreach ($row as $val) {
            $html .= "<td>" . htmlspecialchars($val) . "</td>";
        }
        $html .= "</tr>";
    }
    $html .= "</tbody></table>";
    $html .= "<br><p>Report generated on: <b>" . date('Y-m-d H:i:s') . "</b></p>";

    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->Output("{$table}_report.pdf", 'D');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>REPORTS | EduAxis</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="icon" type="image/x-icon" href="IMGS/LOGO.ico">
</head>
<body class="container mt-5">
    <img src="IMGS/LOGO.jpg" alt="LOGO" style="display:block; margin:5px auto; width:150px; max-width:80vw; height:auto; border-radius:8px">
    <h2 class="text-center mb-4">Reports & Analytics</h2>

<!-- Quick Stats -->
<div class="row text-center mb-4">
  <div class="col-md-2">
    <div class="card bg-primary text-white">
      <div class="card-body">
        <h4><?= $stats['students'] ?></h4>
        <p>Students</p>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card bg-success text-white">
      <div class="card-body">
        <h4>₹<?= number_format($stats['payments'], 2) ?></h4>
        <p>Total Payments</p>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card bg-info text-white">
      <div class="card-body">
        <h4><?= $stats['bookings'] ?></h4>
        <p>Total Bookings</p>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card bg-success text-white">
      <div class="card-body">
        <h4><?= $stats['present'] ?></h4>
        <p>Present</p>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card bg-danger text-white">
      <div class="card-body">
        <h4><?= $stats['absent'] ?></h4>
        <p>Absent</p>
      </div>
    </div>
  </div>
  <div class="col-md-2">
    <div class="card bg-warning text-dark">
      <div class="card-body">
        <h4><?= $stats['pending'] ?></h4>
        <p>Pending</p>
      </div>
    </div>
  </div>
</div>

    <!-- Charts -->
    <div class="row mb-5">
      <div class="col-md-6">
        <canvas id="slotChart"></canvas>
      </div>
      <div class="col-md-6">
        <canvas id="attChart"></canvas>
      </div>
    </div>

    <!-- Dropdown + Date Range -->
    <form method="get" class="mb-4 text-center">
        <label for="table" class="form-label fw-bold">Choose a table:</label>
        <select name="table" id="table" class="form-select w-50 d-inline-block" onchange="this.form.submit()">
            <option value="">-- Select Table --</option>
            <option value="users" <?= $table=='users'?'selected':'' ?>>Users</option>
            <option value="payments" <?= $table=='payments'?'selected':'' ?>>Payments</option>
            <option value="slotbookings" <?= $table=='slotbookings'?'selected':'' ?>>Slot Bookings</option>
            <option value="slots" <?= $table=='slots'?'selected':'' ?>>Slots</option>
            <option value="waitlist" <?= $table=='waitlist'?'selected':'' ?>>Waitlist</option>
            <option value="contact_form" <?= $table=='contact_form'?'selected':'' ?>>Contact Form</option>
        </select>

        <!-- Date Filter -->
        <div class="mt-3">
            <label class="fw-bold">From:</label>
            <input type="date" name="from" value="<?= htmlspecialchars($from) ?>">
            <label class="fw-bold">To:</label>
            <input type="date" name="to" value="<?= htmlspecialchars($to) ?>">
            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
        </div>
    </form>

    <?php if (!empty($rows)) : ?>
        <!-- Download Buttons -->
        <div class="mb-3 text-center">
            <a href="REPORTS.php?table=<?= $table ?>&from=<?= $from ?>&to=<?= $to ?>&download=csv" class="btn btn-success me-2">Download CSV</a>
            <a href="REPORTS.php?table=<?= $table ?>&from=<?= $from ?>&to=<?= $to ?>&download=pdf" class="btn btn-danger">Download PDF</a>
        </div>

        <!-- Table Preview -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <?php foreach ($columns as $col): ?>
                            <th><?= htmlspecialchars($col) ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <?php foreach ($row as $val): ?>
                                <td><?= htmlspecialchars($val) ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php elseif (!empty($table)) : ?>
        <p class="text-center text-danger">No data available in <strong><?= htmlspecialchars($table) ?></strong>.</p>
    <?php endif; ?>
</body>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Slot bookings chart
  const slotCtx = document.getElementById('slotChart');
  new Chart(slotCtx, {
    type: 'bar',
    data: {
      labels: <?= json_encode(array_keys($slotData)) ?>,
      datasets: [{
        label: 'Bookings per Slot',
        data: <?= json_encode(array_values($slotData)) ?>,
        backgroundColor: 'rgba(54, 162, 235, 0.7)'
      }]
    }
  });

  // Attendance chart
  const attCtx = document.getElementById('attChart');
  new Chart(attCtx, {
    type: 'pie',
    data: {
      labels: ['Present', 'Absent', 'Pending'],
      datasets: [{
        data: [
          <?= $stats['present'] ?>,
          <?= $stats['absent'] ?>,
          <?= $stats['pending'] ?>
        ],
        backgroundColor: ['#28a745','#dc3545','#ffc107']
      }]
    }
  });
</script>
</html>
