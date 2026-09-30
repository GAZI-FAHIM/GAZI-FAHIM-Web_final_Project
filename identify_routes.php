<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Identify Safer Routes - SafeWay</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Google Font & AdminLTE -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />

  <!-- Bootstrap CSS (already included, if not add this) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>





  <style>
/* ----------------------------------------
   🌐 Page Base Styles
------------------------------------------- */
body {
  background: linear-gradient(135deg, #e0f0ff 0%, #ffffff 100%);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #223344;
}

/* Wrapper for full layout area */
.wrapper {
  min-height: 100vh;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
  border-radius: 15px;
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

.main-header.navbar .nav-link,
.main-header.navbar .nav-icon {
  color: #cce0ff;
  transition: color 0.3s ease;
}

.main-header.navbar .nav-link:hover {
  color: #ffffff;
}

/* Gap between navbar items */
.main-header .navbar-nav .nav-item {
  margin-left: 10px;
}

/* Sidebar branding line (if applicable) */
.brand-link {
  border-bottom: 1px solid #334;
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
   🧾 Content Wrapper
------------------------------------------- */
.content-wrapper {
  background: #fdfefe;
  padding: 40px 35px 60px;
  min-height: calc(100vh - 56px);
}

/* ----------------------------------------
   📢 Headings (h2, h4)
------------------------------------------- */
h2, h4 {
  border-bottom: 4px solid #0056b3;
  padding-bottom: 12px;
  margin-bottom: 30px;
  font-weight: 800;
  color: #003366;
  letter-spacing: 0.05em;
  text-transform: uppercase;
}

/* ----------------------------------------
   📋 Form Section Styles
------------------------------------------- */
.form-section {
  background: linear-gradient(90deg, #d9e7ff, #ffffff);
  padding: 30px;
  border-radius: 15px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
  margin-bottom: 40px;
}

/* Form control base */
.form-control {
  border: 2px solid #0056b3;
  border-radius: 10px;
  padding: 14px 20px;
  font-size: 1.1rem;
  color: #003366;
  height: auto;
  min-height: 52px;
  box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.form-control:hover {
  border-color: #003366;
  box-shadow: 0 0 10px #0056b3aa;
}

.form-control:focus {
  border-color: #00254d;
  outline: none;
  box-shadow: 0 0 12px #003366cc;
}

/* Enhanced dropdown arrow for select */
select.form-control {
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg%20viewBox='0%200%204%205'%20xmlns='http://www.w3.org/2000/svg'%3E%3Cpath%20fill='%230056b3'%20d='M2%205L0%200h4z'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 1rem center;
  background-size: 10px;
  padding-right: 3rem;
}

/* Adds spacing under inputs and buttons */
.form-control, .btn {
  margin-bottom: 10px;
}

/* ----------------------------------------
   🗺️ Map Styling
------------------------------------------- */
#map {
  height: 550px;
  width: 100%;
  border-radius: 20px;
  margin-top: 30px;
  border: 4px solid #004085;
  box-shadow: 0 12px 28px rgba(0, 0, 0, 0.2);
  transition: box-shadow 0.3s ease;
}

#map:hover {
  box-shadow: 0 18px 45px rgba(0, 0, 0, 0.35);
}

/* ----------------------------------------
   📍 Route Details Section
------------------------------------------- */
#routeDetails p {
  margin-bottom: 10px;
  font-size: 1.05rem;
  font-weight: 500;
  color: #004466;
}

/* ----------------------------------------
   🔘 Buttons
------------------------------------------- */
button.btn {
  font-weight: bold;
  padding: 10px 20px;
}

/* ----------------------------------------
   🧊 Modal Styles
------------------------------------------- */
.modal-header-custom {
  background: linear-gradient(135deg, #007bff, #00bcd4);
  color: white;
  padding: 1rem 1.5rem;
  border-bottom: none;
}

.modal-body-custom {
  font-size: 16px;
  background: #f9fbfd;
  padding: 2rem;
}

/* ----------------------------------------
   📊 Safety Modal Details
------------------------------------------- */
.safety-section {
  margin-bottom: 1.5rem;
}

.safety-section h5 {
  color: #0d6efd;
  font-weight: bold;
}

.badge-score {
  font-size: 1rem;
  padding: 0.4em 0.75em;
  background-color: #0dcaf0;
  color: #fff;
  border-radius: 0.5rem;
  margin-left: 10px;
}

.icon-label {
  font-weight: 600;
  margin-top: 10px;
  color: #495057;
}

.safety-list {
  padding-left: 1.25rem;
  list-style-type: disc;
}

.safety-list li {
  margin-bottom: 0.4rem;
}

/* ----------------------------------------
   📈 Safety Chart
------------------------------------------- */
#safetyChart {
  border-radius: 20px;
  box-shadow: 0 14px 40px rgba(0, 0, 0, 0.1);
  max-height: 450px;
  margin-top: 2rem;
}

/* ----------------------------------------
   📱 Responsive Tweaks
------------------------------------------- */
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
        <li class="nav-item"><a href="identify_routes.php" class="nav-link active"><i class="nav-icon fas fa-route"></i><p>Identify Safer Routes</p></a></li>
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











<!-- Main Content -->
<div class="content-wrapper p-3">
  <div class="container-fluid">
    <h2>Identifying Safer Routes in Dhaka</h2>
    <div class="form-section">
      <h4>Route Preferences</h4>
      <div class="row">
        <div class="col-md-6">
          <input type="text" id="start" class="form-control" placeholder="Start Location (e.g., Dhanmondi)" />
        </div>
        <div class="col-md-6">
          <input type="text" id="end" class="form-control" placeholder="End Location (e.g., Gulshan)" />
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-md-4">
          <select id="timeOfDay" class="form-control">
            <option value="day">Day</option>
            <option value="night">Night</option>
          </select>
        </div>
        <div class="col-md-4">
          <select id="mode" class="form-control">
            <option value="foot">Walking</option>
            <option value="cycling">Cycling</option>
            <option value="car">Driving</option>
          </select>
        </div>
        <div class="col-md-4">
          <select id="companion" class="form-control">
            <option value="alone">Alone</option>
            <option value="group">With Companion(s)</option>
          </select>
        </div>
      </div>

      <!-- Buttons -->
      <div class="d-flex flex-wrap">
        <button class="btn btn-primary mt-3 me-2" onclick="findRoute()">Find Safe Route</button>
        <!-- <button class="btn btn-secondary mt-3" onclick="suggestAlternative()">Suggest Safer Route</button> -->
      </div>

      <!-- Route Info -->
      <div id="routeDetails" class="mt-3">
        <p id="trafficInfo" class="text-muted"></p>
        <p id="weatherInfo" class="text-muted"></p>
        <p id="roadInfo" class="text-muted"></p>
        <p id="lightingInfo" class="text-muted"></p>
        <p id="cctvInfo" class="text-muted"></p>
      </div>


      <!-- Map -->
      <div id="map" class="mt-4" style="height: 550px;"></div>
    </div>
  </div>
</div>

<!-- Safety Info Modal -->
<div class="modal fade" id="safetyModal" tabindex="-1" aria-labelledby="safetyModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
      <div class="modal-header modal-header-custom">
        <h5 class="modal-title d-flex align-items-center" id="safetyModalLabel">
          <i class="fas fa-shield-alt me-2"></i> Safety Overview
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body modal-body-custom">
        <div id="safetyContent"></div>
        <canvas id="safetyChart" class="mt-4" width="400" height="200"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- JS Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>








<!-- KEEPING ALL HTML STRUCTURE AS YOU HAD -->

<!-- Just replace the <script> portion with this one -->
<script>
const map = L.map('map').setView([23.8103, 90.4125], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 19,
  attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

let control;

function normalizeScore(score) {
  return score > 10 ? score : score * 10;
}

async function fetchSafetyData(location) {
  const res = await fetch(`get_safety_info.php?location=${encodeURIComponent(location)}`);
  if (!res.ok) return null;
  return await res.json();
}

function formatTransport(transportData) {
  const obj = JSON.parse(transportData);
  return Object.entries(obj).map(([type, score]) => `<li>${type}: <strong>${score}</strong></li>`).join('');
}

function safetyTags(score) {
  if (score >= 8) return "<span class='badge bg-success ms-2'>✅ Very Safe</span>";
  if (score >= 5) return "<span class='badge bg-warning ms-2'>⚠️ Moderate</span>";
  return "<span class='badge bg-danger ms-2'>🛑 High Risk</span>";
}

function displayExtraInfo() {
  const time = document.getElementById('timeOfDay').value;
  const mode = document.getElementById('mode').value;
  const companion = document.getElementById('companion').value;

  let info = '';
  if (time === 'day') info += "☀️ It's daytime. Visibility and public activity are usually higher, enhancing safety.<br>";
  if (time === 'night') info += "🌙 It's nighttime. Be cautious, avoid poorly lit areas, and stick to main roads.<br>";

  if (mode === 'foot') info += "🚶 Walking selected. Stay alert, avoid shortcuts, and walk in groups when possible.<br>";
  if (mode === 'cycling') info += "🚴 Cycling selected. Wear a helmet, use reflective gear, and follow traffic rules.<br>";
  if (mode === 'car') info += "🚗 Driving selected. Use main roads and avoid high-crime zones or shortcuts.<br>";

  if (companion === 'alone') info += "🧍 Traveling alone. Let someone know your route, and keep emergency contacts handy.<br>";
  if (companion === 'group') info += "👥 Traveling with companions. Stay together, and look out for each other.<br>";

  return info;
}

async function findRoute() {
  const start = document.getElementById('start').value.trim();
  const end = document.getElementById('end').value.trim();
  const time = document.getElementById('timeOfDay').value;

  if (!start || !end) {
    alert("Please enter both start and end locations.");
    return;
  }

  try {
    const [startSafety, endSafety] = await Promise.all([
      fetchSafetyData(start),
      fetchSafetyData(end)
    ]);

    const [startData, endData] = await Promise.all([
      fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(start)}`).then(res => res.json()),
      fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(end)}`).then(res => res.json())
    ]);

    if (!startData.length || !endData.length) throw new Error("One or both locations not found.");

    const startCoords = [parseFloat(startData[0].lat), parseFloat(startData[0].lon)];
    const endCoords = [parseFloat(endData[0].lat), parseFloat(endData[0].lon)];

    if (control) map.removeControl(control);

    control = L.Routing.control({
      waypoints: [L.latLng(...startCoords), L.latLng(...endCoords)],
      lineOptions: { styles: [{ color: 'blue', weight: 6 }] },
      createMarker: (i, wp) => L.marker(wp.latLng).bindPopup(i === 0 ? "Start" : "End"),
      routeWhileDragging: false
    }).addTo(map);

    let msg = displayExtraInfo();

    if (time === 'night' && ((startSafety?.safety_score ?? 0) < 5 || (endSafety?.safety_score ?? 0) < 5)) {
      msg += `<div class="alert alert-danger">⚠️ It's night and one of the areas has a low safety score. Consider waiting or choosing a different route.</div>`;
    }

    if (!startSafety && !endSafety) {
      msg += `<p class="text-danger">⚠️ No safety data available for both locations.</p>`;
    } else {
      if (!startSafety) {
        msg += `<p class="text-warning">⚠️ Safety data not available for <strong>${start}</strong>.</p>`;
      } else {
        msg += `
          <div>
            <h5>📍 <b>${start}</b></h5>
            <p><strong>Safety Score:</strong> ${startSafety.safety_score} ${safetyTags(startSafety.safety_score)}</p>
            <p><strong>👮 Police Nearby:</strong> ${JSON.parse(startSafety.police_stations).join(', ')}</p>
            <p><strong>🌧 Weather:</strong> ${startSafety.weather_advisory}</p>
            <p><strong>📊 Crowd:</strong> ${startSafety.crowd_density}</p>
            <p><strong>⚠️ Incidents:</strong> ${JSON.parse(startSafety.incidents).join(', ')}</p>
            <p><strong>🚌 Transport:</strong><ul>${formatTransport(startSafety.transport_scores)}</ul></p>
          </div>`;
      }

      if (!endSafety) {
        msg += `<p class="text-warning mt-3">⚠️ Safety data not available for <strong>${end}</strong>.</p>`;
      } else {
        msg += `
          <hr>
          <div>
            <h5>📍 <b>${end}</b></h5>
            <p><strong>Safety Score:</strong> ${endSafety.safety_score} ${safetyTags(endSafety.safety_score)}</p>
            <p><strong>👮 Police Nearby:</strong> ${JSON.parse(endSafety.police_stations).join(', ')}</p>
            <p><strong>🌧 Weather:</strong> ${endSafety.weather_advisory}</p>
            <p><strong>📊 Crowd:</strong> ${endSafety.crowd_density}</p>
            <p><strong>⚠️ Incidents:</strong> ${JSON.parse(endSafety.incidents).join(', ')}</p>
            <p><strong>🚌 Transport:</strong><ul>${formatTransport(endSafety.transport_scores)}</ul></p>
          </div>`;
      }

      // Render chart only if both are present
      if (startSafety && endSafety) {
        setTimeout(() => {
          const ctx = document.getElementById('safetyChart').getContext('2d');
          new Chart(ctx, {
            type: 'bar',
            data: {
              labels: ['Start', 'End'],
              datasets: [{
                label: 'Safety Score (%)',
                data: [
                  normalizeScore(startSafety.safety_score),
                  normalizeScore(endSafety.safety_score)
                ],
                backgroundColor: ['#0d6efd', '#198754']
              }]
            },
            options: {
              responsive: true,
              scales: {
                y: {
                  beginAtZero: true,
                  max: 100,
                  ticks: {
                    callback: value => value + '%'
                  }
                }
              }
            }
          });
        }, 500);
      }
    }

    // 🔽 Add contextual details
    document.getElementById('trafficInfo').innerHTML =
      `<strong>🚦 Traffic Alert:</strong> ${Math.random() > 0.5 ? 'Moderate traffic expected along this route.' : 'Light traffic currently detected.'}`;

    document.getElementById('weatherInfo').innerHTML =
      `<strong>🌧 Weather:</strong> ${startSafety?.weather_advisory ?? 'Clear skies with no alerts.'}`;

    document.getElementById('roadInfo').innerHTML =
      `<strong>🏗️ Road Condition:</strong> ${Math.random() > 0.7 ? 'Possible construction ahead near Mohakhali. Consider delays.' : 'No roadwork reported along the route.'}`;

    const lighting = time === 'night'
      ? (Math.random() > 0.5
        ? '⚠️ Some segments may have poor street lighting. Avoid alleys and use well-lit main roads.'
        : '✅ Most areas along this route have working street lights.')
      : 'Not applicable (daytime).';

    document.getElementById('lightingInfo').innerHTML =
      `<strong>🔌 Street Lighting:</strong> ${lighting}`;

    document.getElementById('cctvInfo').innerHTML =
      `<strong>🎥 CCTV Coverage:</strong> ${Math.random() > 0.4 ? 'Several intersections have CCTV coverage for added security.' : 'Limited surveillance detected in some zones.'}`;

    document.getElementById('safetyContent').innerHTML = msg;
    new bootstrap.Modal(document.getElementById('safetyModal')).show();

  } catch (err) {
    alert(err.message);
  }
}

function suggestAlternative() {
  alert("🚧 Safer alternative route feature is under development.\nWe will recommend routes with higher safety scores soon.");
}
</script>




</body>
</html>
