<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: login.html");
  exit();
}

$conn = new mysqli("localhost", "root", "", "safeway");
if ($conn->connect_error) {
  die("DB connection failed");
}

$sql = "SELECT title, message, location, category, urgency, link, timestamp FROM notifications ORDER BY timestamp DESC";
$result = $conn->query($sql);

$notifications = [];
$categoryCount = [];
$urgencyCount = [];
$timeCount = [];
$locationCount = [];

while ($row = $result->fetch_assoc()) {
  $notifications[] = $row;

  $category = $row['category'];
  $categoryCount[$category] = ($categoryCount[$category] ?? 0) + 1;

  $urgency = $row['urgency'];
  $urgencyCount[$urgency] = ($urgencyCount[$urgency] ?? 0) + 1;

  $timeKey = date("Y-m-d", strtotime($row['timestamp']));
  $timeCount[$timeKey] = ($timeCount[$timeKey] ?? 0) + 1;

  $location = $row['location'];
  $locationCount[$location] = ($locationCount[$location] ?? 0) + 1;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>All Notifications - SafeWay</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Google Font & AdminLTE CSS -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback" />
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.min.css" />
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>

/* ----------------------------------------
   🌐 Page Base Styles (Matches Route Page)
------------------------------------------- */
body {
  background: linear-gradient(135deg, #e6f0ff 0%, #ffffff 100%);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #223344;
}

.wrapper {
  min-height: 100vh;
  background: transparent;
  overflow: hidden;
}

/* ----------------------------------------
   🧭 Navbar Styles
------------------------------------------- */
.main-header.navbar {
  background: #004085;
  color: white;
  font-weight: 600;
  box-shadow: 0 3px 8px rgba(0, 64, 133, 0.3);
  border-bottom: none;
  padding-left: 15px; /* Slight gap from sidebar */
  padding-right: 15px;
}

.main-header.navbar .nav-link {
  color: #d9eaff;
}

.main-header.navbar .nav-link:hover {
  color: #ffffff;
}

/* ----------------------------------------
   🖤 Sidebar (Match from Identify Routes)
------------------------------------------- */
.main-sidebar {
  background-color:rgb(28, 31, 34); /* Dark navy blue */
  color: #ffffff;
}

.main-sidebar .brand-link {
  background-color:rgb(34, 38, 43);
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

/* ----------------------------------------
   📦 Content Wrapper
------------------------------------------- */
.content-wrapper {
  margin-left: 250px; /* same as sidebar width */
  padding: 40px;
  background: linear-gradient(135deg, #f3faff 0%, #ffffff 100%);
  border-top-left-radius: 20px;
  min-height: 100vh;
}

/* ----------------------------------------
   📢 Heading
------------------------------------------- */
h1 {
  font-weight: 800;
  color: #003366;
  letter-spacing: 0.05em;
  text-transform: uppercase;
  border-bottom: 4px solid #0056b3;
  padding-bottom: 12px;
  margin-bottom: 30px;
  font-size: 2rem;
}

/* ----------------------------------------
   🧠 Chart Cards
------------------------------------------- */
.chart-container {
  background: #ffffff;
  border: 1px solid #dbe8ff;
  padding: 25px 30px;
  border-radius: 15px;
  box-shadow: 0 6px 16px rgba(0, 64, 128, 0.1);
  margin-bottom: 40px;
  height: 320px;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* ----------------------------------------
   🧾 Table Styling
------------------------------------------- */
.table-responsive {
  background: #ffffff;
  border-radius: 15px;
  box-shadow: 0 8px 24px rgba(0, 64, 128, 0.08);
  padding: 20px;
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0 10px;
  font-size: 1rem;
  color: #223344;
}

thead tr {
  background: #003366;
  color: white;
  border-radius: 15px;
}

thead th {
  padding: 14px 18px;
  text-align: left;
  font-weight: 700;
  text-transform: uppercase;
}

tbody tr {
  background: #f4f8ff;
  transition: background-color 0.3s ease;
  border-radius: 15px;
}

tbody tr:hover {
  background: #dceeff;
  color: #001f3f;
  cursor: pointer;
}

tbody td {
  padding: 14px 18px;
  vertical-align: middle;
  font-weight: 600;
}

tbody td a {
  color: #004085;
  font-weight: 700;
  text-decoration: none;
}

tbody td a:hover {
  text-decoration: underline;
}

/* ----------------------------------------
   🏷️ Badges
------------------------------------------- */
.badge {
  padding: 6px 14px;
  border-radius: 12px;
  font-weight: 700;
  font-size: 0.85rem;
  color: white;
  text-transform: capitalize;
}

.badge-danger    { background: #d63031; }
.badge-warning   { background: #fdcb6e; color: #333; }
.badge-info      { background: #00bcd4; }
.badge-secondary { background: #95a5a6; }

/* ----------------------------------------
   📱 Responsive
------------------------------------------- */
@media (max-width: 991px) {
  .content-wrapper {
    margin-left: 0;
    padding: 25px 20px;
  }

  .chart-container {
    height: 280px;
  }
}

@media (max-width: 480px) {
  h1 {
    font-size: 1.5rem;
  }

  thead th,
  tbody td {
    padding: 10px 12px;
    font-size: 0.9rem;
  }

  .chart-container {
    height: 240px;
  }
}



</style>










</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm px-3">
      <ul class="navbar-nav">
        <li class="nav-item me-3">
          <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="#" class="nav-link font-weight-bold text-primary">Home</a>
        </li>
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
        <li class="nav-item"><a href="send_notifications.php" class="nav-link"><i class="nav-icon fas fa-bell"></i><p>Send Notifications</p></a></li>
        <li class="nav-item"><a href="all_notifications.php" class="nav-link active"><i class="nav-icon fas fa-bell"></i><p>All Notifications</p></a></li>
        <li class="nav-item"><a href="emergency_calls.php" class="nav-link"><i class="nav-icon fas fa-phone-alt"></i><p>Emergency Calls</p></a></li>
        <li class="nav-item"><a href="login.html" class="nav-link"><i class="nav-icon fas fa-sign-out-alt"></i><p>Logout</p></a></li>
      </ul>
    </nav>
  </div>
</aside>

    <!-- Main Content -->
    <div class="content-wrapper">
      <h1>All Notifications</h1>
      <p>Browse all safety notifications submitted by all users.</p>

      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-6 col-md-12">
            <div class="chart-container">
              <canvas id="categoryChart" aria-label="Notification Categories Chart" role="img"></canvas>
            </div>
          </div>
          <div class="col-lg-6 col-md-12">
            <div class="chart-container">
              <canvas id="urgencyChart" aria-label="Urgency Levels Chart" role="img"></canvas>
            </div>
          </div>
          <div class="col-lg-6 col-md-12">
            <div class="chart-container">
              <canvas id="timeChart" aria-label="Notifications Over Time Chart" role="img"></canvas>
            </div>
          </div>
          <div class="col-lg-6 col-md-12">
            <div class="chart-container">
              <canvas id="locationChart" aria-label="Top Locations Reported Chart" role="img"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="table-responsive mt-4" tabindex="0" aria-label="Notifications Table">
        <table>
          <thead>
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
            if (empty($notifications)) {
              echo "<tr><td colspan='7'>No notifications available.</td></tr>";
            } else {
              foreach ($notifications as $row) {
                $urgencyClass = match($row['urgency']) {
                  'critical' => 'danger',
                  'high' => 'warning',
                  'medium' => 'info',
                  default => 'secondary',
                };
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                echo "<td>" . htmlspecialchars($row['message']) . "</td>";
                echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                echo "<td>" . ucfirst(htmlspecialchars($row['category'])) . "</td>";
                echo "<td><span class='badge badge-$urgencyClass'>" . ucfirst(htmlspecialchars($row['urgency'])) . "</span></td>";
                echo "<td>" . ($row['link'] ? "<a href='" . htmlspecialchars($row['link']) . "' target='_blank'>Link</a>" : "-") . "</td>";
                echo "<td>" . date("M d, Y h:i A", strtotime($row['timestamp'])) . "</td>";
                echo "</tr>";
              }
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>










  <script>
    const categoryData = <?php echo json_encode($categoryCount); ?>;
    const urgencyData = <?php echo json_encode($urgencyCount); ?>;
    const timeData = <?php echo json_encode($timeCount); ?>;
    const locationData = <?php echo json_encode($locationCount); ?>;

    const colors = ['#6c5ce7', '#00cec9', '#fdcb6e', '#d63031', '#636e72'];

    new Chart(document.getElementById('categoryChart'), {
      type: 'bar',
      data: {
        labels: Object.keys(categoryData),
        datasets: [{
          label: 'Category Count',
          data: Object.values(categoryData),
          backgroundColor: colors,
          borderRadius: 6,
          borderSkipped: false
        }]
      },
      options: {
        responsive: true,
        plugins: { 
          title: { display: true, text: 'Notification Categories', color: '#004085', font: { size: 18, weight: '700' } },
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: '#e3e9f3',
              borderColor: '#ccc'
            },
            ticks: {
              color: '#223344',
              font: {weight: '600'}
            }
          },
          x: {
            ticks: {
              color: '#223344',
              font: {weight: '600'}
            },
            grid: {
              display: false
            }
          }
        }
      }
    });

    new Chart(document.getElementById('urgencyChart'), {
      type: 'doughnut',
      data: {
        labels: Object.keys(urgencyData),
        datasets: [{
          label: 'Urgency Levels',
          data: Object.values(urgencyData),
          backgroundColor: colors,
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        plugins: {
          title: { display: true, text: 'Urgency Breakdown', color: '#004085', font: { size: 18, weight: '700' } },
          legend: {
            labels: {
              color: '#223344',
              font: {weight: '600'}
            }
          }
        }
      }
    });

    new Chart(document.getElementById('timeChart'), {
      type: 'line',
      data: {
        labels: Object.keys(timeData),
        datasets: [{
          label: 'Notifications Over Time',
          data: Object.values(timeData),
          borderColor: '#004085',
          backgroundColor: 'rgba(0, 64, 133, 0.3)',
          fill: true,
          tension: 0.3,
          borderWidth: 3,
          pointRadius: 5,
          pointBackgroundColor: '#004085'
        }]
      },
      options: {
        responsive: true,
        plugins: {
          title: { display: true, text: 'Notifications Over Time', color: '#004085', font: { size: 18, weight: '700' } },
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: '#e3e9f3',
              borderColor: '#ccc'
            },
            ticks: {
              color: '#223344',
              font: {weight: '600'}
            }
          },
          x: {
            ticks: {
              color: '#223344',
              font: {weight: '600'}
            },
            grid: {
              display: false
            }
          }
        }
      }
    });

    new Chart(document.getElementById('locationChart'), {
      type: 'bar',
      data: {
        labels: Object.keys(locationData),
        datasets: [{
          label: 'Location Count',
          data: Object.values(locationData),
          backgroundColor: colors,
          borderRadius: 6,
          borderSkipped: false
        }]
      },
      options: {
        responsive: true,
        plugins: {
          title: { display: true, text: 'Top Locations Reported', color: '#004085', font: { size: 18, weight: '700' } },
          legend: { display: false }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: '#e3e9f3',
              borderColor: '#ccc'
            },
            ticks: {
              color: '#223344',
              font: {weight: '600'}
            }
          },
          x: {
            ticks: {
              color: '#223344',
              font: {weight: '600'}
            },
            grid: {
              display: false
            }
          }
        }
      }
    });
  </script>
</body>
</html>
