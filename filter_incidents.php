<!-- filter_incidents.php -->
<?php
$mysqli = new mysqli("localhost", "root", "", "safeway");

// Fetch distinct filter options
$categories = $mysqli->query("SELECT DISTINCT category FROM incidents");
$areas = $mysqli->query("SELECT DISTINCT area FROM incidents");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Filter Incidents - SafeWay</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Google Font & AdminLTE CSS -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
/* ----------------------------
   Base Page Styling
----------------------------- */
body {
  background: linear-gradient(135deg, #e0f0ff 0%, #ffffff 100%);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #223344;
}

/* Full-page wrapper box with shadow and rounded edges */
.wrapper {
  min-height: 100vh;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  border-radius: 15px;
  overflow: hidden;
}

/* ----------------------------
   Navbar Styling
----------------------------- */
.main-header.navbar {
  background: #004085;
  color: white;
  font-weight: 600;
  box-shadow: 0 3px 8px rgba(0, 64, 133, 0.3);
  border-bottom: none;
  padding-left: 15px; /* Add spacing from sidebar */
  padding-right: 15px;
}

/* Nav links and icons inside navbar */
.main-header.navbar .nav-link,
.main-header.navbar .nav-icon {
  color: #cce0ff;
  transition: color 0.3s ease;
}

/* Hover state for nav links */
.main-header.navbar .nav-link:hover {
  color: #ffffff;
}

/* Small horizontal gap between nav items */
.main-header .navbar-nav .nav-item {
  margin-left: 10px;
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


/* ----------------------------
   Content Wrapper Styling
----------------------------- */
.content-wrapper {
  background: #fdfefe;
  padding: 40px 35px 60px;
  min-height: calc(100vh - 56px); /* Adjust for navbar height */
}

/* ----------------------------
   Headings
----------------------------- */
h2, h3 {
  border-bottom: 4px solid #0056b3;
  padding-bottom: 12px;
  margin-bottom: 30px;
  font-weight: 800;
  color: #003366;
  letter-spacing: 0.05em;
  text-transform: uppercase;
}

/* ----------------------------
   Filters Section (Form Inputs)
----------------------------- */
.filters {
  background: linear-gradient(90deg, #d9e7ff, #ffffff);
  padding: 25px 30px;
  border-radius: 15px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
  margin-bottom: 40px;
  display: flex;
  flex-wrap: wrap;
  gap: 1.8rem 2.5rem;
  align-items: center;
  justify-content: center;
}

/* Labels inside filters */
.filters label {
  font-weight: 700;
  color: #004085;
  font-size: 1.1rem;
  min-width: 90px;
  user-select: none;
}

/* Input + select dropdowns inside filters */
.filters select,
.filters input[type="date"] {
  border: 2px solid #0056b3;
  border-radius: 10px;
  padding: 10px 18px;
  font-size: 1.1rem;
  color: #003366;
  min-width: 180px;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.05);
}

/* Hover & Focus styles for inputs */
.filters select:hover,
.filters input[type="date"]:hover {
  border-color: #003366;
  box-shadow: 0 0 10px #0056b3aa;
}
.filters select:focus,
.filters input[type="date"]:focus {
  border-color: #00254d;
  outline: none;
  box-shadow: 0 0 12px #003366cc;
}

/* ----------------------------
   Map Styling
----------------------------- */
#map {
  height: 550px;
  border-radius: 20px;
  border: 4px solid #004085;
  box-shadow: 0 12px 28px rgba(0, 0, 0, 0.2);
  margin-bottom: 40px;
  transition: box-shadow 0.3s ease;
}

/* Map hover effect */
#map:hover {
  box-shadow: 0 18px 45px rgba(0, 0, 0, 0.35);
}

/* ----------------------------
   Results Container
----------------------------- */
.results {
  background: #ffffff;
  border-radius: 15px;
  padding: 30px 35px;
  box-shadow: 0 10px 28px rgba(0, 0, 0, 0.15);
  margin-bottom: 40px;
}

/* ----------------------------
   Incident List Styling
----------------------------- */
#incident-list {
  list-style: none;
  padding-left: 0;
  max-height: 320px;
  overflow-y: auto;
  font-size: 1.05rem;
}

/* Individual list items */
#incident-list li {
  border-bottom: 1px solid #cce0ff;
  padding: 14px 0;
  font-weight: 600;
  color: #223344;
  border-radius: 8px;
  transition: background-color 0.25s ease, color 0.25s ease;
}

/* Hover style for list items */
#incident-list li:hover {
  background-color: #d0e7ff;
  color: #00254d;
}

/* Scrollbar Styling for incident list */
#incident-list::-webkit-scrollbar {
  width: 8px;
}
#incident-list::-webkit-scrollbar-track {
  background: #f1f5fc;
  border-radius: 8px;
}
#incident-list::-webkit-scrollbar-thumb {
  background: #004085;
  border-radius: 8px;
}

/* ----------------------------
   Chart Canvas Styling
----------------------------- */
#incidentChart {
  border-radius: 20px;
  box-shadow: 0 14px 40px rgba(0, 0, 0, 0.1);
  max-height: 450px;
}

/* ----------------------------
   Responsive Design Tweaks
----------------------------- */
@media (max-width: 991px) {
  .filters {
    flex-direction: column;
    gap: 1.5rem;
  }
  .filters select,
  .filters input[type="date"] {
    width: 100%;
    min-width: auto;
  }
}

@media (max-width: 480px) {
  h2, h3 {
    font-size: 1.5rem;
  }
  .filters label {
    font-size: 1rem;
    min-width: 80px;
  }
  .filters select,
  .filters input[type="date"] {
    font-size: 1rem;
    padding: 8px 15px;
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
        <li class="nav-item"><a href="filter_incidents.php" class="nav-link active"><i class="nav-icon fas fa-exclamation-triangle"></i><p>Incidents & Hotspots</p></a></li>
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







    <!-- Main Content -->

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid pt-3">

      <header class="text-center py-2">
        <h3>🚨 SafeWay - Incident Filter & Crime Hotspot Tracker</h3>
        <div id="liveClock" class="live-clock mt-2"></div>
      </header>

      <!-- Controls -->
      <div id="controls" class="mb-3 text-center">
        <input type="text" id="areaSearch" placeholder="Search by Area">
        <select id="category"><option value="">All Categories</option></select>
        <select id="timeRange">
          <option value="7">Last 7 Days</option>
          <option value="30" selected>Last 30 Days</option>
          <option value="90">Last 90 Days</option>
        </select>
        <input type="date" id="dateFilter">
        <button onclick="updateView()">🔄 Refresh</button>
        <button onclick="getUserLocation()">📍 My Safety</button>
        <button onclick="toggleHeatmap()">🔥 Toggle Heatmap</button>
        <button onclick="toggleDarkMode()">🌙 Dark Mode</button>
      </div>

      <!-- Alerts -->
      <div id="alertBox" class="text-center my-2"></div>
      <div id="safetyStatus" class="text-center my-2"></div>
      <div id="crimeSummary" class="text-center my-2"></div>

      <!-- Map -->
      <div id="map"></div>

      <!-- Incident List -->
      <div id="incidentListContainer" class="mt-3">
        <h3>📄 Incident List</h3>
        <ul id="incidentList"></ul>
      </div>

      <!-- Chart -->
      <div id="crimeChartContainer" class="mt-3">
        <h3>📊 Incidents by Area</h3>
        <canvas id="crimeChart" height="150"></canvas>
      </div>
    </div>
  </section>
</div>

<!-- JS -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
<script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.js"></script>

<script>
const map = L.map('map').setView([23.8103, 90.4125], 12);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
let markersCluster = L.markerClusterGroup();
map.addLayer(markersCluster);

let heatLayer, crimeChart, globalData = [];
let alertThreshold = 7;

// Clock
function updateClock() {
  const now = new Date();
  document.getElementById('liveClock').textContent = '🕒 ' + now.toLocaleTimeString();
}
setInterval(updateClock, 1000); updateClock();

// Fetch incidents
async function fetchData() {
  const category = document.getElementById('category').value;
  const area = document.getElementById('areaSearch').value;
  const date = document.getElementById('dateFilter').value;
  const days = document.getElementById('timeRange').value;

  const res = await fetch(`get_incidents.php?category=${encodeURIComponent(category)}&area=${encodeURIComponent(area)}&date=${encodeURIComponent(date)}&days=${days}`);
  const json = await res.json();
  return json.data || [];
}

// Render markers
function renderMarkers(data) {
  markersCluster.clearLayers();
  data.forEach(d => {
    L.marker([d.latitude, d.longitude])
      .bindPopup(`<b>${d.category}</b><br>${d.area}<br>${d.incident_count} incident(s)<br>${d.incident_date}<br>${d.description}`)
      .addTo(markersCluster);
  });
}

// Render heatmap
function renderHeatmap(data) {
  if (heatLayer) map.removeLayer(heatLayer);
  heatLayer = L.heatLayer(
    data.map(d => [d.latitude, d.longitude, d.incident_count]),
    { radius: 25, blur: 20 }
  ).addTo(map);
}

// Render incident list
function renderList(data) {
  const list = document.getElementById('incidentList');
  list.innerHTML = '';
  if (!data.length) {
    list.innerHTML = '<li>No incidents found.</li>';
    return;
  }
  data.forEach(d => {
    list.innerHTML += `<li><b>${d.category}</b> - ${d.area} (${d.incident_date})<br><em>${d.description}</em></li>`;
  });
}

// Render chart
function renderChart(data) {
  const areaCounts = {};
  data.forEach(d => {
    areaCounts[d.area] = (areaCounts[d.area] || 0) + d.incident_count;
  });

  const ctx = document.getElementById('crimeChart').getContext('2d');
  if (crimeChart) {
    crimeChart.data.labels = Object.keys(areaCounts);
    crimeChart.data.datasets[0].data = Object.values(areaCounts);
    crimeChart.update();
  } else {
    crimeChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: Object.keys(areaCounts),
        datasets: [{ label: 'Incidents', data: Object.values(areaCounts), backgroundColor: '#dc3545' }]
      },
      options: { scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
    });
  }
  checkAlerts(areaCounts);
}

// Check alerts
function checkAlerts(areaCounts) {
  const alertBox = document.getElementById('alertBox');
  const hotspots = Object.entries(areaCounts).filter(([_, c]) => c >= alertThreshold);

  if (hotspots.length) {
    alertBox.style.display = 'block';
    alertBox.style.background = '#ffe6e6';
    alertBox.style.color = '#a00';
    alertBox.innerHTML = '⚠️ Hotspots: ' + hotspots.map(([a, c]) => `${a} (${c})`).join(', ');
  } else {
    alertBox.style.display = 'none';
  }

  document.getElementById('crimeSummary').textContent =
    '📊 Total Incidents: ' + Object.values(areaCounts).reduce((a, b) => a + b, 0);
}

// Update view
async function updateView() {
  globalData = await fetchData();
  renderMarkers(globalData);
  renderHeatmap(globalData);
  renderList(globalData);
  renderChart(globalData);
  populateCategoryDropdown(globalData);
}

// Populate category dropdown
function populateCategoryDropdown(data) {
  const categorySelect = document.getElementById('category');
  const categories = [...new Set(data.map(d => d.category))].sort();
  categorySelect.innerHTML = '<option value="">All Categories</option>';
  categories.forEach(cat => {
    categorySelect.innerHTML += `<option value="${cat}">${cat}</option>`;
  });
}

// Get user location safety status
function getUserLocation() {
  if (!navigator.geolocation) return alert('Location not supported.');
  navigator.geolocation.getCurrentPosition(pos => {
    const { latitude, longitude } = pos.coords;
    const nearby = globalData.filter(d =>
      L.latLng(latitude, longitude).distanceTo([d.latitude, d.longitude]) / 1000 <= 0.5
    );
    const total = nearby.reduce((a, b) => a + b.incident_count, 0);
    const status = document.getElementById('safetyStatus');
    status.style.display = 'block';
    if (total >= alertThreshold) {
      status.textContent = `🚨 High Risk! ${total} incidents nearby.`;
      status.style.background = '#ffdddd';
      status.style.color = '#a00';
    } else if (total > 0) {
      status.textContent = `⚠️ Medium Risk: ${total} incidents nearby.`;
      status.style.background = '#fff3cd';
      status.style.color = '#856404';
    } else {
      status.textContent = `✅ Low-risk area.`;
      status.style.background = '#d4edda';
      status.style.color = '#155724';
    }
  });
}

function toggleHeatmap() {
  if (heatLayer && map.hasLayer(heatLayer)) map.removeLayer(heatLayer);
  else renderHeatmap(globalData);
}
function toggleDarkMode() {
  document.body.classList.toggle('dark-mode');
}

// Event listeners
document.getElementById('areaSearch').addEventListener('input', updateView);
document.getElementById('category').addEventListener('change', updateView);
document.getElementById('timeRange').addEventListener('change', updateView);
document.getElementById('dateFilter').addEventListener('change', updateView);

// Initial load
updateView();
</script>
</body>
</html>
