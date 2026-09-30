<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safeway";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>📊 SafeWay Insights</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- Styles -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">



  <style>
    body { font-family: 'Source Sans Pro', sans-serif; background: #f8f9fa; }
    .insight-section { margin-bottom: 30px; }
    .table-responsive { overflow-x: auto; }
    .dataTables_wrapper .dt-buttons {
      margin-bottom: 10px;
    }
    .card h4 i { margin-right: 10px; }
    .badge { font-size: 0.95rem; padding: 0.4em 0.6em; }
    .data-table th, .data-table td { vertical-align: middle; }
    .card-header {
      border-bottom: 2px solid rgba(0,0,0,0.1);
      box-shadow: inset 0 -1px 0 rgba(0,0,0,0.1);
    }
    .insight-section .card-body {
      background-color: #ffffff;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="dashboard.php" class="nav-link">Home</a></li>
    </ul>
  </nav>

  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="dashboard.php" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="SafeWay Logo" class="brand-image img-circle elevation-3" />
      <span class="brand-text font-weight-light">SafeWay</span>
    </a>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column">
          <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="nav-icon fas fa-chart-bar"></i><p>Dashboard</p></a></li>
          <li class="nav-item"><a href="detailed_Insights.php" class="nav-link active"><i class="nav-icon fas fa-table"></i><p>Detailed Insights</p></a></li>
        </ul>
      </nav>
    </div>
  </aside>

  <!-- Content -->
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <h2 class="mb-1">🔍 SafeWay Insights</h2>
        <p class="text-muted">Live analysis pulled from 8 key SafeWay datasets</p>
      </div>
    </section>

    <section class="content">
      <div class="container-fluid">












      <!-- Crime Hotspots (Updated) -->
<div class="card insight-section">
  <div class="card-header bg-danger text-white">
    <h4><i class="fas fa-map-marked-alt"></i> Crime Hotspots (Top 4)</h4>
  </div>
  <div class="card-body">
    <div class="row">
      <?php
      $result = $conn->query("SELECT area, thana, incident_count, crime_index FROM crime_stats ORDER BY incident_count DESC LIMIT 4");
      while ($row = $result->fetch_assoc()):
      ?>
      <div class="col-md-3">
        <div class="info-box bg-danger">
          <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text"><?= $row['area'] ?> (<?= $row['thana'] ?>)</span>
            <span class="info-box-number"><?= $row['incident_count'] ?> Incidents</span>
            <span class="progress-description">Crime Index: <?= number_format($row['crime_index'], 2) ?></span>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
</div>

<!-- Safety Distribution (Updated) -->
<div class="card insight-section">
  <div class="card-header bg-success text-white"><h4><i class="fas fa-chart-pie"></i> Safety Distribution</h4></div>
  <div class="card-body row">
    <?php
    $result = $conn->query("SELECT category, label, value, weightage_percent FROM safety_distribution");
    while ($row = $result->fetch_assoc()):
    $color = ($row['label'] === 'Safe') ? 'success' : (($row['label'] === 'Moderate') ? 'warning' : 'danger');
    ?>
    <div class="col-md-4">
      <h5><?= $row['label'] ?> (<?= $row['category'] ?>)</h5>
      <div class="progress mb-1">
        <div class="progress-bar bg-<?= $color ?>" style="width: <?= $row['value'] ?>%">
          <?= $row['value'] ?>%
        </div>
      </div>
      <small>Weightage: <?= $row['weightage_percent'] ?>%</small>
    </div>
    <?php endwhile; ?>
  </div>
</div>






<!-- Safety Checks Table -->
<div class="card insight-section">
  <div class="card-header bg-info text-white"><h4><i class="fas fa-check-circle"></i> Safety Check Usage</h4></div>
  <div class="card-body table-responsive">
    <table id="safetyChecksTable" class="table table-bordered table-hover">
      <thead class="thead-dark"><tr><th>Day</th><th>Time</th><th>Type</th><th>Location</th><th>Status</th></tr></thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT check_day, check_time, check_type, location, status FROM safety_checks ORDER BY check_day");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
          <td><?= $row['check_day'] ?></td>
          <td><?= $row['check_time'] ?></td>
          <td><?= $row['check_type'] ?></td>
          <td><?= $row['location'] ?></td>
          <td><span class="badge badge-<?= ($row['status'] == 'Safe') ? 'success' : 'danger' ?>"><?= $row['status'] ?></span></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Feature Usage Table -->
<div class="card insight-section">
  <div class="card-header bg-primary text-white"><h4><i class="fas fa-cogs"></i> Feature Usage</h4></div>
  <div class="card-body table-responsive">
    <table id="featureUsageTable" class="table table-bordered table-hover">
      <thead class="thead-dark"><tr><th>Feature</th><th>Usage Count</th><th>User Type</th><th>Last Used</th></tr></thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT feature, usage_count, user_type, last_used_at FROM feature_usage ORDER BY usage_count DESC");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
          <td><?= $row['feature'] ?></td>
          <td><?= $row['usage_count'] ?></td>
          <td><?= $row['user_type'] ?></td>
          <td><?= $row['last_used_at'] ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Weekly Crime Type Trends Table -->
<div class="card insight-section">
  <div class="card-header bg-warning text-dark"><h4><i class="fas fa-chart-line"></i> Weekly Crime Type Trends</h4></div>
  <div class="card-body table-responsive">
    <table id="crimeTrendsTable" class="table table-striped table-sm table-hover">
      <thead class="thead-dark"><tr><th>Week</th><th>Area</th><th>Theft</th><th>Harassment</th><th>Assault</th><th>Robbery</th><th>Vandalism</th><th>Kidnapping</th><th>Last Update</th></tr></thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT * FROM crime_type_trends ORDER BY id");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
          <td><?= $row['week'] ?></td>
          <td><?= $row['area'] ?></td>
          <td><?= $row['theft'] ?></td>
          <td><?= $row['harassment'] ?></td>
          <td><?= $row['assault'] ?></td>
          <td><?= $row['robbery'] ?></td>
          <td><?= $row['vandalism'] ?></td>
          <td><?= $row['kidnapping'] ?></td>
          <td><?= $row['updated_at'] ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Emergency Response Table -->
<div class="card insight-section">
  <div class="card-header bg-secondary text-white"><h4><i class="fas fa-ambulance"></i> Emergency Response</h4></div>
  <div class="card-body table-responsive">
    <table id="emergencyResponseTable" class="table table-striped table-hover">
      <thead class="thead-dark"><tr><th>Area</th><th>Service Type</th><th>Avg. Response Time (min)</th><th>Rating</th><th>Last Reported</th></tr></thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT * FROM emergency_response ORDER BY average_response_time_min");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
          <td><?= $row['area'] ?></td>
          <td><?= $row['service_type'] ?></td>
          <td><?= number_format($row['average_response_time_min'], 2) ?></td>
          <td><?= $row['response_rating'] ?>/5</td>
          <td><?= $row['last_reported'] ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Notification Engagement Table -->
<div class="card insight-section">
  <div class="card-header bg-teal text-white"><h4><i class="fas fa-bell"></i> Notification Engagement</h4></div>
  <div class="card-body table-responsive">
    <table id="notificationTable" class="table table-sm table-bordered table-hover">
      <thead class="thead-dark"><tr><th>Type</th><th>Location</th><th>Viewed</th><th>Clicked</th><th>Dismissed</th><th>Sent At</th></tr></thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT type, location, SUM(viewed) as viewed, SUM(clicked) as clicked, SUM(dismissed) as dismissed, MAX(sent_at) as sent_at FROM notification_engagement GROUP BY type, location");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
          <td><?= $row['type'] ?></td>
          <td><?= $row['location'] ?></td>
          <td><?= $row['viewed'] ?></td>
          <td><?= $row['clicked'] ?></td>
          <td><?= $row['dismissed'] ?></td>
          <td><?= $row['sent_at'] ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Feedback Bubble Table -->
<div class="card insight-section">
  <div class="card-header bg-purple text-white"><h4><i class="fas fa-comments"></i> User Feedback & Traffic</h4></div>
  <div class="card-body table-responsive">
    <table id="feedbackTable" class="table table-striped table-hover">
      <thead class="thead-dark"><tr><th>Label</th><th>Positive</th><th>Negative</th><th>Safety Score</th><th>Traffic</th><th>Submitted At</th></tr></thead>
      <tbody>
        <?php
        $result = $conn->query("SELECT label, feedback_positive, feedback_negative, safety_score, traffic_density, submitted_at FROM feedback_bubble ORDER BY submitted_at DESC LIMIT 10");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
          <td><?= $row['label'] ?></td>
          <td><?= $row['feedback_positive'] ?>%</td>
          <td><?= $row['feedback_negative'] ?>%</td>
          <td><?= $row['safety_score'] ?>%</td>
          <td><?= $row['traffic_density'] ?>%</td>
          <td><?= $row['submitted_at'] ?></td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Add to bottom of body -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<!-- jQuery (must be before DataTables) -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>


<script>
$(document).ready(function () {
  const tables = [
    '#safetyChecksTable',
    '#featureUsageTable',
    '#crimeTrendsTable',
    '#emergencyResponseTable',
    '#notificationTable',
    '#feedbackTable'
  ];

  tables.forEach(selector => {
    if ($(selector).length) {
      $(selector).DataTable({
        pageLength: 10,
        lengthChange: false,
        dom: 'Bfrtip',
        buttons: ['copyHtml5', 'csvHtml5', 'excelHtml5', 'pdfHtml5'],
        responsive: true
      });
    }
  });
});
</script>

</body>
</html>
