<?php
session_start();
include 'connect.php';

$email = $_SESSION['email'] ?? null;
$role = $_SESSION['role'] ?? null;

$metrics = [
    "community_resources" => "SELECT COUNT(*) as count FROM community_resources",
    "crime_incidents" => "SELECT COUNT(*) as count FROM crime_incidents",
    "incidents" => "SELECT COUNT(*) as count FROM incidents",
    "locations" => "SELECT COUNT(*) as count FROM locations",
    "notifications" => "SELECT COUNT(*) as count FROM notifications",
    "safety_data" => "SELECT COUNT(*) as count FROM safety_data",
    "safety_ratings" => "SELECT COUNT(*) as count FROM safety_ratings",
    "safety_zones" => "SELECT COUNT(*) as count FROM safety_zones",
];

$results = [];

foreach ($metrics as $key => $query) {
    $res = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($res);
    $results[$key] = $row['count'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Dashboard - SafeWay</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Fonts & Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <!-- Bootstrap & Theme -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.min.css" />
  <!-- Charts -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Custom CSS -->
  <link rel="stylesheet" href="dashboard.css" />

  <script src="dashboard.js" defer></script>


</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- 🌐 Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm px-3">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="dashboard.php" class="nav-link">Home</a>
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
        <li class="nav-item"><a href="dashboard.php" class="nav-link active"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
        <li class="nav-item"><a href="location_search.php" class="nav-link"><i class="nav-icon fas fa-shield-alt"></i><p>Safety Control Center</p></a></li>
        <li class="nav-item"><a href="safety_unified.php" class="nav-link"><i class="nav-icon fas fa-map-marked-alt"></i><p>Unified Safety Explorer</p></a></li>
        <li class="nav-item"><a href="identify_routes.php" class="nav-link"><i class="nav-icon fas fa-route"></i><p>Identify Safer Routes</p></a></li>
        <li class="nav-item"><a href="filter_incidents.php" class="nav-link"><i class="nav-icon fas fa-exclamation-triangle"></i><p>Incidents & Hotspots</p></a></li>
        <li class="nav-item"><a href="community_resources.php" class="nav-link"><i class="nav-icon fas fa-hands-helping"></i><p>Community Resources</p></a></li>
        <li class="nav-item"><a href="legend_info.php" class="nav-link "><i class="nav-icon fas fa-map"></i><p>Using the Legend</p></a></li>
        <li class="nav-item"><a href="send_notifications.php" class="nav-link"><i class="nav-icon fas fa-bell"></i><p>Send Notifications</p></a></li>
        <li class="nav-item"><a href="all_notifications.php" class="nav-link"><i class="nav-icon fas fa-bell"></i><p>All Notifications</p></a></li>
        <li class="nav-item"><a href="emergency_calls.php" class="nav-link"><i class="nav-icon fas fa-phone-alt"></i><p>Emergency Calls</p></a></li>
        <li class="nav-item"><a href="login.html" class="nav-link"><i class="nav-icon fas fa-sign-out-alt"></i><p>Logout</p></a></li>
      </ul>
    </nav>
  </div>
</aside>





      <!-- Content -->
      <div class="content-wrapper">
<section class="content-header welcome-banner">
  <div class="container-fluid text-center">
    <h1 class="welcome-title">
      <span class="wave">👋</span> Welcome to <span class="safeway-text">SafeWay</span>
    </h1>
    <p class="welcome-subtext">
      You're safe here — explore smart tools designed to protect and empower you.
    </p>
    <div id="liveClock" class="live-clock mt-3"></div>
  </div>
</section>






        

<div class="row">
  <?php
  // Display settings: [Title, Icon, Background, Description]
  $tableDisplaySettings = [
      "community_resources" => ["Community Resources", "fas fa-hands-helping", "bg-primary", "Support services like shelters, clinics, or NGOs"],
      "crime_incidents"     => ["Crime Incidents", "fas fa-exclamation-triangle", "bg-danger", "Reported crimes with location and date"],
      "incidents"           => ["Incident Logs", "fas fa-book", "bg-warning", "Detailed incident reports submitted"],
      "locations"           => ["Monitored Areas", "fas fa-map-marker-alt", "bg-success", "Areas tracked for safety analytics"],
      "notifications"       => ["Alerts Sent", "fas fa-bell", "bg-info", "Urgent safety alerts sent to users"],
      "safety_data"         => ["Safety Records", "fas fa-database", "bg-secondary", "Combined safety data with advisories"],
      "safety_ratings"      => ["Safety Ratings", "fas fa-shield-alt", "bg-teal", "Safety scores based on incidents"],
      "safety_zones"        => ["Safety Zones", "fas fa-draw-polygon", "bg-purple", "Defined safe/unsafe zones across the map"]
  ];

  foreach ($results as $tableName => $rowCount) {
      list($title, $iconClass, $bgColorClass, $description) = $tableDisplaySettings[$tableName];
  ?>
    <div class="col-md-3 mb-4">
      <div class="card <?= $bgColorClass ?> text-white h-100 shadow">
        <div class="card-body">
          <div class="d-flex align-items-center mb-2">
            <i class="<?= $iconClass ?> fa-2x me-3"></i>
            <div>
              <h5 class="mb-0"><?= $rowCount ?></h5>
              <small class="fw-bold"><?= $title ?></small>
            </div>
          </div>
          <p class="mb-0 small"><?= $description ?></p>
        </div>
      </div>
    </div>
  <?php } ?>
</div>



        <section class="content">
  <div class="container-fluid">
    <!-- Info Boxes -->
    <div class="row">

      <!-- Basic Location Search -->
      <div class="col-md-4">
        <div class="info-box" onclick="window.location='location_search.php'">
          <span class="info-box-icon bg-info">
            <i class="fas fa-search-location"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text">Basic Location Search</span>
            <span class="info-box-number">Check safety scores of areas</span>
          </div>
        </div>
      </div>

      <!-- Map Exploration -->
      <div class="col-md-4">
        <div class="info-box" onclick="window.location='map_explore.php'">
          <span class="info-box-icon bg-success">
            <i class="fas fa-map-marked-alt"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text">Map Exploration</span>
            <span class="info-box-number">Explore safety zones visually</span>
          </div>
        </div>
      </div>

      <!-- Visual Safety Ratings -->
      <div class="col-md-4">
        <div class="info-box" onclick="window.location='safety_ratings.php'">
          <span class="info-box-icon bg-warning">
            <i class="fas fa-eye"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text">Visual Safety Ratings</span>
            <span class="info-box-number">Charts of crime rates and areas</span>
          </div>
        </div>
      </div>

      <!-- Check Before Going Out -->
      <div class="col-md-4">
        <div class="info-box" onclick="window.location='check_safety.php'">
          <span class="info-box-icon bg-danger">
            <i class="fas fa-shield-alt"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text">Check Before Going Out</span>
            <span class="info-box-number">Analysis of your route’s safety</span>
          </div>
        </div>
      </div>

      <!-- Identify Safer Routes -->
      <div class="col-md-4">
        <div class="info-box" onclick="window.location='identify_routes.php'">
          <span class="info-box-icon bg-primary">
            <i class="fas fa-route"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text">Identify Safer Routes</span>
            <span class="info-box-number">Suggests optimal paths based on crime data</span>
          </div>
        </div>
      </div>

      <!-- Filter Incidents -->
      <div class="col-md-4">
        <div class="info-box" onclick="window.location='filter_incidents.php'">
          <span class="info-box-icon bg-orange">
            <i class="fas fa-filter"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text">Filter Incidents</span>
            <span class="info-box-number">Search by time, type, or location</span>
          </div>
        </div>
      </div>

      <!-- Crime Hotspot Tracker -->
      <div class="col-md-4">
        <div class="info-box" onclick="window.location='crime_hotspot.php'">
          <span class="info-box-icon bg-danger">
            <i class="fas fa-exclamation-triangle"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text">Crime Hotspot Tracker</span>
            <span class="info-box-number">Monitor frequently dangerous areas</span>
          </div>
        </div>
      </div>

      <!-- Community Resources -->
      <div class="col-md-4">
        <div class="info-box" onclick="window.location='community_resources.php'">
          <span class="info-box-icon bg-success">
            <i class="fas fa-hands-helping"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text">Community Resources</span>
            <span class="info-box-number">Access support centers near you</span>
          </div>
        </div>
      </div>

      <!-- Understanding Safety Factors -->
      <div class="col-md-4">
        <div class="info-box" onclick="window.location='understand_factors.php'">
          <span class="info-box-icon bg-secondary">
            <i class="fas fa-lightbulb"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text">Understanding Safety Factors</span>
            <span class="info-box-number">Make informed decisions about safety</span>
          </div>
        </div>
      </div>

      <!-- Using the Legend -->
      <div class="col-md-4">
        <div class="info-box" onclick="window.location='legend_info.php'">
          <span class="info-box-icon bg-teal">
            <i class="fas fa-map"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text">Using the Legend</span>
            <span class="info-box-number">Learn to interpret map safety ratings</span>
          </div>
        </div>
      </div>

      <!-- Send Notifications -->
      <div class="col-md-4">
        <div class="info-box" onclick="window.location='send_notifications.php'">
          <span class="info-box-icon bg-purple">
            <i class="fas fa-bell"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text">Send Notifications</span>
            <span class="info-box-number">Alert users about nearby danger</span>
          </div>
        </div>
      </div>

      <!-- Emergency Calls -->
      <div class="col-md-4">
        <div class="info-box" onclick="window.location='emergency_calls.php'">
          <span class="info-box-icon bg-dark">
            <i class="fas fa-phone-alt"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text">Emergency Calls</span>
            <span class="info-box-number">Connect to help when you need it most</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>



<!-- Charts Section -->
<div class="dashboard-section">
  <div class="card">
    <div class="card-header bg-gradient-primary text-white">
      <h3 class="card-title">
        <i class="fas fa-chart-bar"></i> Safety Insights Dashboard
      </h3>
    </div>
    <div class="card-body">
      <p class="mb-4">
        Real-time safety analytics and trends in Dhaka based on data collection. Click on each chart to interact and explore.
      </p>
      <div class="container-fluid">
        <div class="row g-4">
          
          <!-- Chart Box Template -->
          <div class="col-md-6 col-xl-6">
            <div class="chart-container">
              <div class="chart-title">Reported Crime Incidents by Area (Last 30 Days)</div>
              <div class="chart-inner">
                <canvas id="crimeChart"></canvas>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-6">
            <div class="chart-container">
              <div class="chart-title">Safety Zone Distribution</div>
              <div class="chart-inner">
                <canvas id="areaSafetyPie"></canvas>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-6">
            <div class="chart-container">
              <div class="chart-title">Weekly Usage of Safety Check Tool</div>
              <div class="chart-inner">
                <canvas id="routeCheckLine"></canvas>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-6">
            <div class="chart-container">
              <div class="chart-title">Feature Popularity Radar</div>
              <div class="chart-inner">
                <canvas id="featureRadar"></canvas>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-6">
            <div class="chart-container">
              <div class="chart-title">Weekly Crime Trends by Type</div>
              <div class="chart-inner">
                <canvas id="crimeTypeTrend"></canvas>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-6">
            <div class="chart-container">
              <div class="chart-title">Avg. Emergency Response Time (by Area)</div>
              <div class="chart-inner">
                <canvas id="responseBar"></canvas>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-6">
            <div class="chart-container">
              <div class="chart-title">User Response to Push Notifications</div>
              <div class="chart-inner">
                <canvas id="notifEngage"></canvas>
              </div>
            </div>
          </div>

          <div class="col-md-6 col-xl-6">
            <div class="chart-container">
              <div class="chart-title">Feedback, Safety, and Foot Traffic Analysis</div>
              <div class="chart-inner">
                <canvas id="bubbleFeedback"></canvas>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>





<!-- Detailed Insights Banner -->
<div class="card mt-4 bg-gradient-primary text-white" style="cursor: pointer;" onclick="window.location.href='detailed_Insights.php'">
  <div class="card-body text-center">
    <h4><i class="fas fa-search-plus"></i> Explore Full Safety Insights & Trends</h4>
    <p class="mb-0">Click to dive deeper into location-based analytics, response times, trends, and more.</p>
  </div>
</div>








<!-- Feature Summary -->
<div class="dashboard-section">
  <div class="card shadow-lg border-0">
    <div class="card-header bg-gradient-info text-white d-flex align-items-center">
      <h3 class="card-title mb-0">
        <i class="fas fa-layer-group me-2"></i> Explore Our Powerful Features
      </h3>
    </div>
    <div class="card-body bg-white">
      <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
        <!-- Feature Boxes -->
        <?php
        $features = [
          ["Basic Location Search", "fa-map-marker-alt", "primary", "Discover safety scores instantly for any location in Dhaka using real-time AI analysis. The system evaluates crime statistics, foot traffic, and environmental factors to give you an accurate safety score for any area. Simply enter a location, and get a snapshot of its safety levels."],
          ["Map Exploration", "fa-map", "success", "Visually explore crime zones, lighting, CCTV coverage, and safe paths on an interactive map. Users can toggle between different layers of data to identify high-risk areas and safer routes. The map is regularly updated to reflect real-time changes in safety conditions."],
          ["Visual Safety Ratings", "fa-chart-pie", "danger", "Dive into colorful charts and analytics comparing safety scores across neighborhoods. These visual tools give users easy access to key metrics like crime rates, lighting conditions, and police presence, allowing you to make informed decisions about your safety."],
          ["Check Before Going Out", "fa-shield-alt", "warning", "Let AI scan your route and suggest safety levels before you step outside. The system checks real-time data on crime trends, traffic, and environmental factors, helping you decide if your planned route is safe or if you should consider alternatives."],
          ["Identify Safer Routes", "fa-route", "info", "Avoid unsafe areas with intelligent alternate path suggestions based on live data. The app uses historical data and real-time reports to suggest safer routes that avoid high-risk zones, helping you navigate through the city more securely."],
          ["Understanding Safety Factors", "fa-search-location", "secondary", "Learn what influences safety scores—lighting, crowd density, recent incidents, and more. This feature helps you understand how various environmental and social factors impact the safety of a given area. You can make better decisions by knowing what makes a place safe or risky."],
          ["Using the Map Legend", "fa-info-circle", "dark", "Quickly understand symbols and color codes to decode the safety map effectively. The map legend provides a guide to help you interpret safety data, making it easier to read the map and assess the risks in various areas at a glance."],
          ["Notifications & Alerts", "fa-bell", "pink", "Stay updated with timely alerts about risky zones and changing conditions near you. The app sends notifications when there are significant changes in the safety of areas you're nearby or routes you're planning to take. You'll always be informed when something important happens in your surroundings."],
          ["Emergency Calls", "fa-phone-alt", "indigo", "Tap to quickly reach emergency services or share your location with trusted contacts. This feature gives you instant access to emergency phone numbers and allows you to send your live location to friends, family, or emergency responders if needed. Always stay connected when help is just a tap away."]
        ];

        foreach ($features as [$title, $icon, $color, $desc]) {
          echo "
            <div class='col'>
              <div class='feature-box p-4 bg-light rounded border-start border-4 border-$color shadow-sm h-100 transition hover-shadow'>
                <h5 class='fw-bold'>
                  <i class='fas $icon text-$color me-2'></i> $title
                </h5>
                <p class='text-muted small mb-0'>$desc</p>
              </div>
            </div>
          ";
        }
        ?>
      </div>
    </div>
  </div>
</div>






<!-- Safety Tips -->
<div class="card mt-5 shadow-lg border-0">
  <div class="card-header bg-gradient-dark text-white d-flex align-items-center">
    <h3 class="card-title mb-0">
      <i class="fas fa-lightbulb me-2 text-warning"></i> Smart Safety Tips Just for You
    </h3>
  </div>
  <div class="card-body bg-light">
    <ul class="list-unstyled row row-cols-1 row-cols-md-2 g-3">
      <?php
      $tips = [
        ["fa-moon text-danger", "Avoid poorly lit or isolated routes after dark — safety begins with visibility."],
        ["fa-user-friends text-primary", "Inform a friend or family member about your travel route and ETA."],
        ["fa-shield-alt text-warning", "Use the <strong>\"Check Before Going Out\"</strong> feature to analyze your path."],
        ["fa-video text-info", "Prefer routes with CCTV coverage and steady pedestrian movement."],
        ["fa-location-arrow text-secondary", "Enable real-time location sharing with your trusted contacts."],
        ["fa-battery-full text-success", "Keep your phone charged — carry a power bank just in case!"],
        ["fa-phone text-pink", "Set up emergency contacts for one-tap calling when it counts."],
        ["fa-bell text-warning", "Pay attention to safety alerts and push notifications from the app."],
        ["fa-exclamation-triangle text-danger", "If your gut says “no,” trust it — reroute instantly for your peace of mind."],
        ["fa-headphones-alt text-secondary", "Avoid using headphones in both ears when you're in a new or quiet area."],
        ["fa-history text-info", "Use the SafeWay route history to reflect on and avoid risky paths."]
      ];

      foreach ($tips as [$icon, $text]) {
        echo "
          <li class='d-flex align-items-start'>
            <i class='fas $icon me-3 mt-1 fs-5'></i>
            <span class='small'>$text</span>
          </li>
        ";
      }
      ?>
    </ul>
  </div>
</div>



          </div>
        </section>
      </div>
    </div>

    <!-- Scripts -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



<script>
  function updateClock() {
    const now = new Date();
    const timeString = now.toLocaleTimeString([], {
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit'
    });
    document.getElementById('liveClock').textContent = `🕒 ${timeString}`;
  }

  setInterval(updateClock, 1000);
  updateClock(); // Initial call
</script>




  </body>
</html>
