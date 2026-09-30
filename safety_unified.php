<?php
/***********************
 * safety_unified.php
 * One page to rule them all: map + heatmap + search/filter + stats + table.
 * Also serves JSON feed via ?feed=1
 ***********************/
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer-when-downgrade');
header('X-UA-Compatible: IE=edge');

// ===== DB CONNECT =====
$HOST = 'localhost';
$USER = 'root';
$PASS = '';
$DB   = 'safeway';

$conn = new mysqli($HOST, $USER, $PASS, $DB);
if ($conn->connect_error) {
  http_response_code(500);
  die('DB connection failed');
}

// ===== JSON FEED (same file) =====
if (isset($_GET['feed'])) {
  header('Content-Type: application/json; charset=utf-8');

  // Filters (optional)
  $level = isset($_GET['level']) ? $_GET['level'] : null;        // High / Moderate / Low
  $q     = isset($_GET['q']) ? trim($_GET['q']) : null;          // search text

  $params = [];
  $where  = [];

  if ($level && in_array($level, ['High','Moderate','Low'])) {
    $where[] = 'safety_rating = ?';
    $params[] = $level;
  }
  if ($q) {
    $where[] = '(location LIKE ? OR area_type LIKE ? OR notes LIKE ?)';
    $like = '%'.$q.'%';
    $params[] = $like; $params[] = $like; $params[] = $like;
  }

  $sql = "SELECT id, location, latitude, longitude, area_type, reported_incidents,
                 safety_rating, notes, patrolling, street_lighting, women_helpline,
                 transport_access, description, last_updated
          FROM safety_locations";
  if ($where) $sql .= ' WHERE '.implode(' AND ', $where);

  $stmt = $conn->prepare($sql);

  if ($params) {
    // dynamic bind
    $types = str_repeat('s', count($params));
    $stmt->bind_param($types, ...$params);
  }

  $stmt->execute();
  $res = $stmt->get_result();

  $points = [];
  while ($row = $res->fetch_assoc()) {
    $points[] = $row;
  }

  // Stats
  $totalRes = $conn->query("SELECT COUNT(*) c FROM safety_locations")->fetch_assoc()['c'] ?? 0;
  $categoriesRes = $conn->query("SELECT COUNT(DISTINCT area_type) c FROM safety_locations")->fetch_assoc()['c'] ?? 0;
  $locationsRes = $conn->query("SELECT COUNT(DISTINCT location) c FROM safety_locations")->fetch_assoc()['c'] ?? 0;

  echo json_encode([
    'points' => $points,
    'stats'  => [
      'total'      => (int)$totalRes,
      'categories' => (int)$categoriesRes,
      'distinct'   => (int)$locationsRes
    ]
  ]);
  exit;
}

// ===== PAGE QUERIES (for counters + table) =====
$totalRes      = $conn->query("SELECT COUNT(*) c FROM safety_locations");
$categoriesRes = $conn->query("SELECT COUNT(DISTINCT area_type) c FROM safety_locations");
$locationsRes  = $conn->query("SELECT COUNT(DISTINCT location) c FROM safety_locations");

// Table data (use the unified table directly)
$tableRes = $conn->query("SELECT id, location, latitude, longitude, area_type, reported_incidents, safety_rating, notes FROM safety_locations ORDER BY location ASC");
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>SafeWay — Unified Map & Ratings</title>


      <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Styles (AdminLTE & DataTables) -->
  <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />


    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">






  <!-- Custom CSS -->
  <style>
  /* Base body */
  body {
    background: linear-gradient(135deg, #e0f0ff 0%, #ffffff 100%);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #223344;
  }

  /* Navbar */
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

  /* Sidebar */
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

  /* Content Wrapper */
  .content-wrapper {
    background: #fdfefe;
    padding: 40px 35px 60px;
    min-height: calc(100vh - 56px);
  }

  /* Headings */
  h3, h5 {
    border-bottom: 4px solid #0056b3;
    padding-bottom: 12px;
    margin-bottom: 30px;
    font-weight: 800;
    color: #003366;
    letter-spacing: 0.05em;
    text-transform: uppercase;
  }

  /* Card */
  .card {
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
    border: none;
  }

  .card .card-body {
    padding: 2rem;
  }

  /* Stats Cards */
  .stats-card {
    text-align: center;
    padding: 20px;
    border-radius: 15px;
    color: white;
    font-weight: bold;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    transition: transform 0.2s ease;
  }
  .stats-card:hover {
    transform: translateY(-5px);
  }

  .bg-info {
    background-color:rgb(31, 191, 219) !important; /* Stronger royal blue */
  color: #FFFFFF !important;
  text-shadow: 0 1px 3px rgba(0,0,0,0.4);
}

.bg-success {
  background-color: #28A745 !important; /* Bootstrap’s classic green */
  color: #FFFFFF !important;
  text-shadow: 0 1px 3px rgba(0,0,0,0.35);
}

.bg-danger {
  background-color: #DC3545 !important; /* Bootstrap’s rich red */
  color: #FFFFFF !important;
  text-shadow: 0 1px 3px rgba(0,0,0,0.4);
}


  /* Map Styling */
  #map {
    height: 550px;
    width: 100%;
    border-radius: 15px;
    border: 3px solid #004085;
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
    transition: box-shadow 0.3s ease;
  }

  #map:hover {
    box-shadow: 0 18px 45px rgba(0, 0, 0, 0.25);
  }

  /* Table */
  table.dataTable thead th {
    background-color: #004085 !important;
    color: white !important;
    font-weight: 700;
  }

  table.dataTable.no-footer {
    border-radius: 15px;
    border: 3px solid #004085;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
  }

  /* Table Container */
  .table-container h5 {
    margin-top: 40px;
    margin-bottom: 20px;
    color: #003366;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .stats-card {
      margin-bottom: 20px;
    }
  }






    #map { height: 560px; border-radius: 10px; }
    .legend {
      background: white; padding: 8px 10px; line-height: 1.2; border-radius: 8px;
      box-shadow: 0 2px 8px rgba(0,0,0,.15); font-size: 13px;
    }
    .legend i { width: 12px; height: 12px; display: inline-block; margin-right: 6px; border-radius: 3px; }
    .stats-card small { opacity: .9; }
    .leaflet-bar button { background: #fff; border: none; width: 34px; height: 34px; line-height: 34px; cursor: pointer; }
    .leaflet-bar button:focus { outline: none; }
    .dark-mode { background: #0d1117; color: #e6edf3; }
    .dark-mode .card { background: #161b22; color: #e6edf3; }





</style>





</head>




<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
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
        <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
        <li class="nav-item"><a href="location_search.php" class="nav-link"><i class="nav-icon fas fa-shield-alt"></i><p>Safety Control Center</p></a></li>
        <li class="nav-item"><a href="safety_unified.php" class="nav-link active"><i class="nav-icon fas fa-map-marked-alt"></i><p>Unified Safety Explorer</p></a></li>
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
  <div class="content-wrapper p-3">
    <div class="container-fluid">

      <!-- Header -->
      <header class="text-center py-2 mb-3">
        <h3>🔎 Unified Map Exploration & Visual Safety Ratings</h3>
        <div id="liveClock" class="text-muted"></div>
      </header>

      <!-- Stats -->
      <div class="row text-white mb-3">
        <div class="col-md-4">
          <div class="stats-card bg-info p-3 rounded shadow text-center">
            <h3 class="count" data-count="<?= (int)($totalRes->fetch_assoc()['c'] ?? 0) ?>">0</h3>
            <small>Total Resources</small>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stats-card bg-success p-3 rounded shadow text-center">
            <h3 class="count" data-count="<?= (int)($categoriesRes->fetch_assoc()['c'] ?? 0) ?>">0</h3>
            <small>Unique Area Type</small>
          </div>
        </div>
        <div class="col-md-4">
          <div class="stats-card bg-danger p-3 rounded shadow text-center">
            <h3 class="count" data-count="<?= (int)($locationsRes->fetch_assoc()['c'] ?? 0) ?>">0</h3>
            <small>Distinct Location</small>
          </div>
        </div>
      </div>

      <!-- Search / Filter / Dark Mode -->
      <div class="d-flex flex-wrap gap-2 align-items-end mb-3">
        <div class="me-2">
          <label class="form-label mb-1">Search</label>
          <input type="text" id="search-input" class="form-control" placeholder="e.g., Gulshan 2, Mirpur..." />
        </div>
        <div class="me-2">
          <label class="form-label mb-1">Filter by Safety</label>
          <select id="filter-select" class="form-select">
            <option value="all">All</option>
            <option value="High">High</option>
            <option value="Moderate">Moderate</option>
            <option value="Low">Low</option>
          </select>
        </div>
        <div class="me-2">
          <button id="apply-filter" class="btn btn-primary">Apply</button>
          <button id="reset-filter" class="btn btn-secondary">Reset</button>
        </div>
        <div class="ms-auto">
          <button id="dark-mode-toggle" class="btn btn-outline-dark">Toggle Dark Mode</button>
        </div>
      </div>

      <!-- Map -->
      <div id="map" class="mb-4"></div>

      <!-- Safety Ratings Table -->
      <div class="card">
        <div class="card-body">
          <h5 class="mb-2">Detailed Safety Ratings Table</h5>
          <table id="ratingsTable" class="display nowrap table table-bordered table-hover" style="width:100%">
            <thead class="thead-dark">
              <tr>
                <th>Location</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Area Type</th>
                <th>Reported Incidents</th>
                <th>Safety Rating</th>
                <th>Notes</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($tableRes && $tableRes->num_rows > 0): ?>
                <?php while($row = $tableRes->fetch_assoc()): ?>
                  <tr>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    <td><?= htmlspecialchars($row['latitude']) ?></td>
                    <td><?= htmlspecialchars($row['longitude']) ?></td>
                    <td><?= htmlspecialchars($row['area_type']) ?></td>
                    <td><?= htmlspecialchars($row['reported_incidents']) ?></td>
                    <td><?= htmlspecialchars($row['safety_rating']) ?></td>
                    <td><?= htmlspecialchars($row['notes']) ?></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="7">No data found</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Scripts -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

<!-- Leaflet & Plugins -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
  // Live clock
  function updateClock() {
    const now = new Date();
    document.getElementById('liveClock').textContent = `🕒 ${now.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit', second:'2-digit'})}`;
  }
  setInterval(updateClock, 1000); updateClock();

  // Counters
  $('.count').each(function () {
    const $this = $(this);
    const countTo = +$this.attr('data-count') || 0;
    $({ n: 0 }).animate({ n: countTo }, {
      duration: 900,
      easing: 'swing',
      step: function() { $this.text(Math.floor(this.n)); },
      complete: function() { $this.text(countTo); }
    });
  });

  // Map
  const map = L.map('map').setView([23.8103, 90.4125], 12);
  const light = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {maxZoom: 19, attribution: '&copy; OpenStreetMap contributors'}).addTo(map);
  const dark  = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', { attribution: '&copy; OpenStreetMap & CartoDB' });

  function getColor(level) {
    const r = (level || '').toLowerCase();
    if (r === 'high') return 'green';
    if (r === 'moderate') return 'orange';
    return 'red';
  }

  // Controls: locate, fullscreen, heat toggle
  const toolbar = L.control({ position: 'topleft' });
  let heatLayer = null;
  let cluster = L.markerClusterGroup();
  toolbar.onAdd = function() {
    const wrap = L.DomUtil.create('div', 'leaflet-bar');
    const btnLocate = L.DomUtil.create('button', '', wrap);
    btnLocate.innerHTML = '📍';
    btnLocate.title = 'Locate me';
    btnLocate.onclick = () => {
      navigator.geolocation.getCurrentPosition(pos => {
        const latlng = [pos.coords.latitude, pos.coords.longitude];
        map.setView(latlng, 15);
        L.marker(latlng).addTo(map).bindPopup("You're here").openPopup();
      });
    };

    const btnFull = L.DomUtil.create('button', '', wrap);
    btnFull.innerHTML = '⛶';
    btnFull.title = 'Fullscreen';
    btnFull.onclick = () => {
      if (!document.fullscreenElement) document.documentElement.requestFullscreen();
      else document.exitFullscreen();
    };

    const btnHeat = L.DomUtil.create('button', '', wrap);
    btnHeat.innerHTML = '🔥';
    btnHeat.title = 'Toggle Heatmap';
    btnHeat.onclick = () => {
      if (!heatLayer) return;
      if (map.hasLayer(heatLayer)) map.removeLayer(heatLayer);
      else heatLayer.addTo(map);
    };
    return wrap;
  };
  toolbar.addTo(map);
  cluster.addTo(map);

  // Legend
  const legend = L.control({ position: 'bottomright' });
  legend.onAdd = function () {
    const div = L.DomUtil.create('div', 'legend');
    div.innerHTML = '<strong>Safety Levels</strong><br>';
    div.innerHTML += '<i style="background:green"></i> High<br>';
    div.innerHTML += '<i style="background:orange"></i> Moderate<br>';
    div.innerHTML += '<i style="background:red"></i> Low<br>';
    return div;
  };
  legend.addTo(map);

  // Dark mode toggle
  document.getElementById('dark-mode-toggle').addEventListener('click', () => {
    if (map.hasLayer(light)) { map.removeLayer(light); dark.addTo(map); document.body.classList.add('dark-mode'); }
    else { map.removeLayer(dark); light.addTo(map); document.body.classList.remove('dark-mode'); }
  });

  // Fetch and render points
  let allPoints = [];
  function buildPopup(p) {
    return `
      <table class="table table-sm mb-0">
        <tr><th colspan="2">${p.location}</th></tr>
        <tr><td><b>Safety Level</b></td><td>${p.safety_rating}</td></tr>
        <tr><td><b>Area Type</b></td><td>${p.area_type ?? '-'}</td></tr>
        <tr><td><b>Incidents</b></td><td>${p.reported_incidents ?? 0}</td></tr>
        <tr><td><b>Patrolling</b></td><td>${p.patrolling ?? '-'}</td></tr>
        <tr><td><b>Street Lighting</b></td><td>${p.street_lighting ?? '-'}</td></tr>
        <tr><td><b>Women Helpline</b></td><td>${p.women_helpline ?? '109'}</td></tr>
        <tr><td><b>Transport Access</b></td><td>${p.transport_access ?? '-'}</td></tr>
        <tr><td><b>Notes</b></td><td>${p.description || p.notes || '-'}</td></tr>
      </table>
    `;
  }

  function render(points) {
    cluster.clearLayers();
    const heatData = [];

    points.forEach(p => {
      const lat = parseFloat(p.latitude), lon = parseFloat(p.longitude);
      if (isNaN(lat) || isNaN(lon)) return;

      // heat intensity: map High=0.6, Moderate=0.4, Low=0.2 (tweakable)
      const lvl = (p.safety_rating || '').toLowerCase();
      const intensity = lvl === 'high' ? 0.6 : (lvl === 'moderate' ? 0.4 : 0.2);
      heatData.push([lat, lon, intensity]);

      const marker = L.circleMarker([lat, lon], {
        radius: 8,
        color: getColor(p.safety_rating),
        fillColor: getColor(p.safety_rating),
        fillOpacity: 0.85,
        weight: 1
      }).bindPopup(buildPopup(p))
        .bindTooltip(`${p.location} — ${p.safety_rating}`, {direction:'top', offset:[0,-8]});
      cluster.addLayer(marker);
    });

    if (heatLayer) map.removeLayer(heatLayer);
    heatLayer = L.heatLayer(heatData, { radius: 26, blur: 18 });
  }

  async function loadFeed(params = {}) {
    const url = new URL(window.location.href);
    url.searchParams.set('feed', '1');
    if (params.level && params.level !== 'all') url.searchParams.set('level', params.level);
    else url.searchParams.delete('level');
    if (params.q && params.q.trim()) url.searchParams.set('q', params.q.trim());
    else url.searchParams.delete('q');

    const res = await fetch(url.toString(), { cache: 'no-store' });
    const data = await res.json();
    allPoints = data.points || [];
    render(allPoints);
  }

  // Initial load
  loadFeed();

  // Apply / Reset filters
  document.getElementById('apply-filter').addEventListener('click', () => {
    const q = document.getElementById('search-input').value;
    const level = document.getElementById('filter-select').value;
    loadFeed({ q, level });
  });
  document.getElementById('reset-filter').addEventListener('click', () => {
    document.getElementById('search-input').value = '';
    document.getElementById('filter-select').value = 'all';
    loadFeed();
  });

  // DataTable
  $(document).ready(function () {
    $('#ratingsTable').DataTable({
      responsive: true,
      dom: 'Bfrtip',
      buttons: ['csvHtml5', 'excelHtml5', 'pdfHtml5'],
      pageLength: 10
    });
  });
</script>
</body>
</html>
<?php $conn->close(); ?>
