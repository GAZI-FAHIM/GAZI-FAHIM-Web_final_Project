<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Send Safety Notifications - SafeWay</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Google Font & AdminLTE CSS -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.min.css" />
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css" />

  <style>
  /* ------------------------------------------------------
     ✅ GENERAL LAYOUT & COLOR THEME STYLES
  ---------------------------------------------------------*/
  body {
    font-family: 'Source Sans Pro', sans-serif;
    background-color: #f9fafe;
  }

  .content-wrapper {
    background: #fdfefe;
    padding: 40px 35px 60px;
    min-height: calc(100vh - 56px);
  }

  /* ------------------------------------------------------
     ✅ NAVBAR STYLING
  ---------------------------------------------------------*/
  .main-header.navbar {
    background: #004085;
    color: white;
    font-weight: 600;
    box-shadow: 0 3px 8px rgba(0, 64, 133, 0.3);
  }

  .main-header.navbar .nav-link,
  .main-header.navbar .nav-icon {
    color: #cce0ff;
    transition: color 0.3s ease;
  }

  .main-header.navbar .nav-link:hover {
    color: #ffffff;
  }

  /* ------------------------------------------------------
     ✅ SIDEBAR STYLING
  ---------------------------------------------------------*/
  .main-sidebar {
    background-color: rgb(28, 31, 34);
    color: #ffffff;
  }

  .main-sidebar .brand-link {
    background-color: rgb(34, 38, 43);
    color: #ffffff;
    font-weight: bold;
    border-bottom: 1px solid #003366;
  }

  .main-sidebar .nav-link {
    color: #cfd9ff;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .main-sidebar .nav-link .nav-icon {
    color: #a0b8ff;
  }

  .main-sidebar .nav-link.active,
  .main-sidebar .nav-link:hover {
    background-color: #e6f0ff;
    color: #001f3f;
    font-weight: bold;
    border-radius: 8px;
  }

  .main-sidebar .nav-link.active .nav-icon,
  .main-sidebar .nav-link:hover .nav-icon {
    color: #001f3f;
  }

  /* ------------------------------------------------------
     ✅ SECTION HEADING STYLE
  ---------------------------------------------------------*/
  h2, h4, h5 {
    border-bottom: 4px solid #0056b3;
    padding-bottom: 12px;
    margin-bottom: 30px;
    font-weight: 800;
    color: #003366;
    letter-spacing: 0.05em;
    text-transform: uppercase;
  }

  /* ------------------------------------------------------
     ✅ NOTIFICATION FORM
  ---------------------------------------------------------*/
  .notification-form {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
  }

  .form-section-title {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 10px;
  }

  /* ------------------------------------------------------
     ✅ INFO PANEL STYLES
  ---------------------------------------------------------*/
  .info-panel {
    background-color: #fff;
    border-radius: 12px;
    padding: 20px 25px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    color: #333;
    margin-top: 20px;
  }

  .info-panel h5 {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: #dc3545;
    user-select: none;
  }

  .info-panel ul {
    list-style: none;
    padding-left: 0;
    margin: 0;
  }

  .info-panel ul li {
    font-size: 1rem;
    margin-bottom: 15px;
    line-height: 1.4;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
    transition: background-color 0.3s ease;
    cursor: default;
  }

  .info-panel ul li:hover {
    background-color: #f9f9f9;
  }

  .info-panel ul li strong {
    color: #b02a37;
  }

  .info-panel ul li em {
    color: #666;
    font-style: normal;
    font-weight: 600;
  }

  .info-panel ul li small {
    color: #999;
    font-size: 0.85rem;
    user-select: none;
  }

  /* ------------------------------------------------------
     ✅ EXAMPLE ALERT SNIPPET STYLES
  ---------------------------------------------------------*/
  .example-alert {
    background-color: #fff8f8;
    border-left: 5px solid #dc3545;
    padding: 12px 15px;
    margin-bottom: 15px;
    border-radius: 8px;
    font-size: 1rem;
    display: flex;
    align-items: center;
    color: #5a1f24;
    box-shadow: 0 1px 6px rgba(220, 53, 69, 0.15);
    transition: box-shadow 0.3s ease;
  }

  .example-alert:hover {
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
    background-color: #fff2f2;
  }

  .example-alert i {
    margin-right: 12px;
    font-size: 1.3rem;
    flex-shrink: 0;
    user-select: none;
  }

  .example-alert strong {
    margin-right: 6px;
    color: #b02a37;
    user-select: text;
  }

  /* ------------------------------------------------------
     ✅ BADGE STYLING FOR URGENCY
  ---------------------------------------------------------*/
  .badge {
    font-size: 0.8rem;
    padding: 3px 10px;
    border-radius: 15px;
    font-weight: 600;
    text-transform: capitalize;
    vertical-align: middle;
    margin: 0 6px;
    color: #fff;
    user-select: none;
  }

  .badge-danger {
    background-color: #dc3545;
  }

  .badge-warning {
    background-color: #ffc107;
    color: #212529;
  }

  .badge-info {
    background-color: #17a2b8;
  }

  .badge-secondary {
    background-color: #6c757d;
  }

  /* ------------------------------------------------------
     ✅ TABLE STYLES FOR NOTIFICATION HISTORY
  ---------------------------------------------------------*/
  table {
    background-color: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
  }

  thead.thead-dark th {
    background-color: #343a40;
    color: #fff;
    font-weight: 600;
  }

  .table td,
  .table th {
    vertical-align: middle;
    padding: 12px;
  }

  .table tbody tr:hover {
    background-color: #f6f6f6;
    transition: background 0.3s ease;
  }

  .table-responsive {
    border-radius: 10px;
    overflow: auto;
    margin-top: 1.5rem;
    max-height: 400px;
  }

  /* ------------------------------------------------------
     ✅ RESPONSIVE TWEAKS FOR SMALL SCREENS
  ---------------------------------------------------------*/
  @media (max-width: 576px) {
    .info-panel ul li,
    .example-alert {
      font-size: 0.9rem;
    }

    .example-alert i {
      font-size: 1.1rem;
      margin-right: 8px;
    }
  }
</style>









</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="#" class="nav-link">Home</a></li>
    </ul>
  </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="dashboard.php" class="brand-link">
    <img src="dist/img/AdminLTELogo.png" alt="Logo" class="brand-image img-circle elevation-3">
    <span class="brand-text font-weight-light">SafeWay</span>
  </a>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
        <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
        <li class="nav-item"><a href="location_search.php" class="nav-link"><i class="nav-icon fas fa-shield-alt"></i><p>Safety Control Center</p></a></li>
        <li class="nav-item"><a href="safety_unified.php" class="nav-link"><i class="nav-icon fas fa-map-marked-alt"></i><p>Unified Safety Explorer</p></a></li>
        <li class="nav-item"><a href="identify_routes.php" class="nav-link"><i class="nav-icon fas fa-route"></i><p>Identify Safer Routes</p></a></li>
        <li class="nav-item"><a href="filter_incidents.php" class="nav-link"><i class="nav-icon fas fa-exclamation-triangle"></i><p>Incidents & Hotspots</p></a></li>
        <li class="nav-item"><a href="community_resources.php" class="nav-link"><i class="nav-icon fas fa-hands-helping"></i><p>Community Resources</p></a></li>
        <li class="nav-item"><a href="legend_info.php" class="nav-link "><i class="nav-icon fas fa-map"></i><p>Using the Legend</p></a></li>
        <li class="nav-item"><a href="send_notifications.php" class="nav-link active"><i class="nav-icon fas fa-bell"></i><p>Send Notifications</p></a></li>
        <li class="nav-item"><a href="all_notifications.php" class="nav-link"><i class="nav-icon fas fa-bell"></i><p>All Notifications</p></a></li>
        <li class="nav-item"><a href="emergency_calls.php" class="nav-link"><i class="nav-icon fas fa-phone-alt"></i><p>Emergency Calls</p></a></li>
        <li class="nav-item"><a href="login.html" class="nav-link"><i class="nav-icon fas fa-sign-out-alt"></i><p>Logout</p></a></li>
      </ul>
    </nav>
  </div>
</aside>





<!-- Main Content -->
<div class="content-wrapper p-3">
  <div class="container-fluid">
    <h2>Send Safety Notifications</h2>
    <p class="mb-4">Alert nearby users about safety hazards, disruptions, or real-time updates with precision and clarity.</p>

    <?php if (isset($_GET['status'])): ?>
      <div class="alert alert-<?php echo $_GET['status'] === 'success' ? 'success' : 'danger'; ?>">
        <?php echo $_GET['status'] === 'success' ? '✅ Notification sent successfully!' : '❌ Failed to send notification. Try again.'; ?>
      </div>
    <?php endif; ?>

    <div class="notification-form">
      <form action="send_notification_handler.php" method="POST">
        <div class="form-group">
          <label for="title">Notification Title</label>
          <input type="text" class="form-control" id="title" name="title" required />
        </div>

        <div class="form-group">
          <label for="message">Message Content</label>
          <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
        </div>

        <div class="form-group">
          <label for="location">Target Location</label>
          <input type="text" class="form-control" id="location" name="location" required />
        </div>

        <div class="form-group">
          <label for="category">Alert Category</label>
          <select class="form-control" id="category" name="category" required>
            <option value="crime">🚨 Crime or Suspicious Activity</option>
            <option value="weather">🌧 Weather or Environmental</option>
            <option value="traffic">🚗 Traffic or Road Hazard</option>
            <option value="general">ℹ️ General Safety Update</option>
            <option value="emergency">❗ Emergency Alert</option>
            <option value="fire">🔥 Fire Alert</option>
            <option value="medical">🏥 Medical Emergency</option>
            <option value="public_event">🎉 Public Event</option>
            <option value="police_activity">👮 Police Activity</option>
            <option value="protest">✊ Protest or Demonstration</option>
            <option value="power_outage">💡 Power Outage</option>
            <option value="road_closure">🚧 Road Closure</option>
            <option value="natural_disaster">🌪 Natural Disaster</option>
            <option value="hazardous_material">☣️ Hazardous Material</option>
            <option value="lost_person">🔍 Lost Person Alert</option>
            <option value="other">🔔 Other Alert Category</option>
          </select>
        </div>

        <div class="form-group">
          <label for="urgency">Urgency Level</label>
          <select class="form-control" id="urgency" name="urgency">
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
            <option value="critical">Critical</option>
          </select>
        </div>

        <div class="form-group">
          <label for="link">Optional Link</label>
          <input type="url" class="form-control" id="link" name="link" placeholder="https://example.com" />
        </div>

        <button type="submit" class="btn btn-danger btn-block">
          <i class="fas fa-paper-plane"></i> Send Notification
        </button>
      </form>
    </div>

    <?php
    $conn = new mysqli("localhost", "root", "", "safeway");
    if ($conn->connect_error) {
      echo "<div class='alert alert-danger'>❌ Database error.</div>";
    } else {
      $sql_live = "SELECT title, message, category, urgency, timestamp FROM notifications ORDER BY timestamp DESC LIMIT 5";
      $result_live = $conn->query($sql_live);
    }
    ?>

    <!-- Live Summary -->
    <div class="info-panel mt-5">
      <h5>📢 Latest Safety Notifications</h5>
      <?php if ($result_live && $result_live->num_rows > 0): ?>
        <ul>
          <?php while ($row = $result_live->fetch_assoc()): ?>
            <li>
              <strong><?php echo htmlspecialchars($row['title']); ?></strong> — 
              <em><?php echo ucfirst(htmlspecialchars($row['category'])); ?></em> | 
              <span class="badge badge-<?php 
                echo $row['urgency'] === 'critical' ? 'danger' : 
                     ($row['urgency'] === 'high' ? 'warning' : 
                     ($row['urgency'] === 'medium' ? 'info' : 'success')); 
              ?>">
                <?php echo ucfirst(htmlspecialchars($row['urgency'])); ?>
              </span> | 
              <small><?php echo date("M d, Y h:i A", strtotime($row['timestamp'])); ?></small>
            </li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p>No recent notifications found.</p>
      <?php endif; ?>
    </div>

    <!-- Alert Snippets -->
    <div class="mt-4">
      <h5>🔔 Recent Alert Snippets</h5>
      <?php
      $conn->close();
      $conn = new mysqli("localhost", "root", "", "safeway");
      $result_snippets = $conn->query($sql_live);
      if ($result_snippets && $result_snippets->num_rows > 0):
        while ($row = $result_snippets->fetch_assoc()):
          $icon_class = "fas fa-info-circle text-secondary";
          switch ($row['category']) {
            case 'crime': $icon_class = "fas fa-shield-alt text-danger"; break;
            case 'weather': $icon_class = "fas fa-bolt text-warning"; break;
            case 'traffic': $icon_class = "fas fa-road text-danger"; break;
            case 'emergency': $icon_class = "fas fa-exclamation-triangle text-danger"; break;
            case 'fire': $icon_class = "fas fa-fire text-danger"; break;
            case 'medical': $icon_class = "fas fa-hospital-symbol text-info"; break;
            case 'general': $icon_class = "fas fa-info-circle text-info"; break;
            default: $icon_class = "fas fa-bell text-secondary"; break;
          }
      ?>
        <div class="example-alert">
          <i class="<?php echo $icon_class; ?>"></i>
          <strong><?php echo htmlspecialchars($row['title']); ?>:</strong>
          <?php echo nl2br(htmlspecialchars($row['message'])); ?>
        </div>
      <?php endwhile;
      else: ?>
        <p>No alert snippets found.</p>
      <?php endif;
      $conn->close();
      ?>
    </div>

    <!-- My Notifications Table -->
    <div class="mt-5">
      <h4>📋 My Sent Notifications</h4>
      <div class="table-responsive mt-3">
        <table class="table table-bordered table-hover table-striped">
          <thead class="thead-dark">
            <tr>
              <th>Title</th>
              <th>Message</th>
              <th>Location</th>
              <th>Category</th>
              <th>Urgency</th>
              <th>Link</th>
              <th>Sent At</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $conn = new mysqli("localhost", "root", "", "safeway");
            if ($conn->connect_error) {
              echo "<tr><td colspan='7'>❌ Database error.</td></tr>";
            } else {
              $user_id = $_SESSION['user_id'];
              $sql = "SELECT title, message, location, category, urgency, link, timestamp 
                      FROM notifications 
                      WHERE user_id = ? 
                      ORDER BY timestamp DESC";
              $stmt = $conn->prepare($sql);
              $stmt->bind_param("i", $user_id);
              $stmt->execute();
              $result = $stmt->get_result();
              if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                  echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                  echo "<td>" . ucfirst(htmlspecialchars($row['category'])) . "</td>";
                  echo "<td><span class='badge badge-" . (
                    $row['urgency'] === 'critical' ? 'danger' :
                    ($row['urgency'] === 'high' ? 'warning' :
                    ($row['urgency'] === 'medium' ? 'info' : 'success'))
                  ) . "'>" . ucfirst(htmlspecialchars($row['urgency'])) . "</span></td>";
                  echo "<td>" . ($row['link'] ? "<a href='" . htmlspecialchars($row['link']) . "' target='_blank'>Link</a>" : "-") . "</td>";
                  echo "<td>" . date("M d, Y h:i A", strtotime($row['timestamp'])) . "</td>";
                  echo "</tr>";
                }
              } else {
                echo "<tr><td colspan='7'>No notifications found.</td></tr>";
              }
              $stmt->close();
              $conn->close();
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>

<!-- Scripts -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
