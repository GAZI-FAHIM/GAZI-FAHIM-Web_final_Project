<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Basic Location Search - SafeWay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>

    <style>
/* 🌐 Base Styles */
/* 🌐 Base Styles */
body {
  background: linear-gradient(135deg, #e0f0ff 0%, #ffffff 100%);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #223344;
}

/* ⬛ Navbar Styles */
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

/* 🧭 Sidebar Styles */
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

/* 📄 Content Wrapper */
.content-wrapper {
  background: #fdfefe;
  padding: 40px 35px 60px;
  min-height: calc(100vh - 56px);
}

/* 📢 Headings (h2 and card titles) */
h2,
h3.card-title {
  border-bottom: 4px solid #0056b3;
  padding-bottom: 12px;
  margin-bottom: 30px;
  font-weight: 800;
  color: #003366;
  letter-spacing: 0.05em;
  text-transform: uppercase;
}

/* 📦 Card Styles */
.card.card-info {
  border-radius: 15px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
  border: none;
}

/* Card header with custom style */
/* Card header with custom style */
.card.card-info .card-header {
  background-color: #005db9ff; /* Deep but slightly lighter than h2 text */
  border-radius: 15px 15px 0 0;
  color: white;
}


/* Custom header class for enhanced styling */
.card-header.custom-header {
  background-color: #004085; /* same dark blue theme */
  color: #ffffff;            /* white text for contrast */
  font-weight: 700;
  font-size: 1.5rem;
  display: flex;
  align-items: center;
  gap: 10px;                 /* spacing between emoji and text */
  border-radius: 15px 15px 0 0;
  border-bottom: none;
  text-decoration: none;     /* remove underline */
  padding: 15px 25px;
  box-shadow: 0 4px 8px rgba(0, 64, 133, 0.3);
}

/* Card title inside the custom header */
.card-header.custom-header h3.card-title {
  margin: 0;                /* remove default margin */
  text-decoration: none;    /* ensure no underline */
  font-size: 1.4rem;
  font-weight: 700;
  color: #ffffff;
  display: flex;
  align-items: center;
  gap: 10px;
  letter-spacing: 0.5px;
}


.custom-header {
  background: linear-gradient(135deg,rgb(1, 30, 73),rgb(143, 179, 214));
  border-radius: 15px 15px 0 0;
  padding: 20px 25px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
}

/* 🔍 Input Group (search box) */
.search-box input.form-control {
  border-radius: 10px 0 0 10px;
  border: 2px solid #0056b3;
  padding: 12px 15px;
  font-size: 1rem;
  box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.06);
  transition: 0.3s;
}

.search-box input.form-control:focus {
  border-color: #00254d;
  box-shadow: 0 0 10px #0056b3aa;
}

.search-box .btn.btn-info {
  background-color: #004085;
  border-color: #004085;
  border-radius: 0 10px 10px 0;
  font-weight: bold;
  transition: 0.3s;
}

.search-box .btn.btn-info:hover {
  background-color: #00254d;
}

/* 🗺️ Map Styling */
#map {
  height: 450px;
  border-radius: 15px;
  border: 3px solid #004085;
  box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
  transition: box-shadow 0.3s ease;
}

#map:hover {
  box-shadow: 0 18px 45px rgba(0, 0, 0, 0.25);
}

/* 🧾 Info Cards */
.info-card .info-box {
  background: #ffffff;
  border-radius: 14px;
  padding: 18px;
  box-shadow: 0 5px 12px rgba(0, 0, 0, 0.08);
  transition: transform 0.2s;
}

.info-card .info-box:hover {
  transform: translateY(-3px);
}

.info-card h5 {
  font-weight: 700;
  color: #004085;
  margin-bottom: 10px;
  font-size: 1rem;
}

/* Highlighted text styling */
.highlight {
  color: #17a2b8;
  font-weight: bold;
}

/* List styles */
ul {
  padding-left: 1.2rem;
}

ul li {
  margin-bottom: 6px;
  color: #333;
}

/* 🪟 Responsive adjustments */
@media (max-width: 768px) {
  .search-box input.form-control,
  .search-box .btn.btn-info {
    width: 100%;
    border-radius: 10px !important;
    margin-top: 10px;
  }

  .info-card {
    margin-bottom: 20px;
  }
}


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
        <li class="nav-item"><a href="location_search.php" class="nav-link active"><i class="nav-icon fas fa-shield-alt"></i><p>Safety Control Center</p></a></li>
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


    <!-- Main Content -->
    <div class="content-wrapper p-3">
      <div class="container-fluid">
        <!-- Header -->
        <header class="text-center py-3 mb-4">
          <h3 class="mb-1">🔎 Safety Check • Locate • Navigate • Insights</h3>
          <div id="liveClock" class="mt-1 small text-muted"></div>
        </header>

        <!-- Search + Actions -->
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">📍 Search for a Location in Dhaka</h3>
          </div>
          <div class="card-body">
            <form id="location-form">
              <div class="input-group">
                <input type="text" id="locationInput" class="form-control" placeholder="e.g., Dhanmondi, Dhaka">
                <div class="input-group-append">
                  <button type="submit" class="btn btn-info">Search</button>
                </div>
              </div>
            </form>

            <div class="mt-3 d-flex flex-wrap gap-2">
              <button id="copySummaryBtn" class="btn btn-outline-secondary btn-sm">Copy Summary</button>
              <div id="timeAdvisoryChip" class="badge badge-secondary p-2 ml-2">Time Advisory</div>
              <div id="lightingChip" class="badge badge-secondary p-2 ml-2">Lighting</div>
            </div>

            <div id="map" class="mt-3" style="height: 520px;"></div>
          </div>
        </div>

        <!-- Quick KPIs -->
        <div class="row mt-3">
          <div class="col-md-4">
            <div class="info-box bg-success">
              <span class="info-box-icon"><i class="fas fa-shield-alt"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Safety Score</span>
                <span id="safetyScoreText" class="info-box-number">—</span>
                <div class="progress"><div id="safetyScoreBar" class="progress-bar" style="width: 0%"></div></div>
                <span id="crimeTrendText" class="progress-description">Crime Trend: —</span>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="info-box bg-info">
              <span class="info-box-icon"><i class="fas fa-cloud-sun-rain"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Weather Advisory</span>
                <span id="weatherAdvisory" class="info-box-number">—</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span id="clothingTip" class="progress-description">Clothing Tip: —</span>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="info-box bg-warning">
              <span class="info-box-icon"><i class="fas fa-users"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Crowd & Noise</span>
                <span id="crowdDensity" class="info-box-number">—</span>
                <div class="progress"><div class="progress-bar" style="width: 100%"></div></div>
                <span id="noiseLevel" class="progress-description">Noise: —</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Details -->
        <div class="row">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header"><h3 class="card-title">🏥 Nearby Hospitals</h3></div>
              <div class="card-body"><ul id="hospitalList" class="mb-0"></ul></div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card">
              <div class="card-header"><h3 class="card-title">👮 Nearby Police Stations</h3></div>
              <div class="card-body"><ul id="policeList" class="mb-0"></ul></div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card">
              <div class="card-header"><h3 class="card-title">📈 Crime Trends (Last 30 Days)</h3></div>
              <div class="card-body"><p id="crimeTrendPara" class="mb-0">—</p></div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card">
              <div class="card-header"><h3 class="card-title">📞 Emergency Contacts</h3></div>
              <div class="card-body">
                <ul class="mb-3">
                  <li>Police: <strong>999</strong></li>
                  <li>Women’s Helpline: <strong>109</strong></li>
                  <li>Fire Service: <strong>199</strong></li>
                  <li>Health Help: <strong>16263</strong></li>
                </ul>
                <a href="tel:999" class="btn btn-danger btn-sm mr-2">Call Police (999)</a>
                <a href="tel:199" class="btn btn-warning btn-sm mr-2">Fire Service (199)</a>
                <a href="tel:16263" class="btn btn-info btn-sm">Health Help (16263)</a>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card">
              <div class="card-header"><h3 class="card-title">🛡️ Safety Tips</h3></div>
              <div class="card-body">
                <ul id="safetyTips" class="mb-0">
                  <li>Avoid dark alleys after 8 PM.</li>
                  <li>Use main roads or ride-share services.</li>
                  <li>Stay in well-lit areas if walking alone.</li>
                </ul>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card">
              <div class="card-header"><h3 class="card-title">🚉 Public Transport Safety</h3></div>
              <div class="card-body" id="transportScores">—</div>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <div class="card-header"><h3 class="card-title">🚨 Last Reported Incidents</h3></div>
              <div class="card-body"><ul id="incidentList" class="mb-0"></ul></div>
            </div>
          </div>

          <div class="col-md-12">
            <div class="card">
              <div class="card-header"><h3 class="card-title">🌤️ Mood Forecast</h3></div>
              <div class="card-body"><div id="moodForecast" class="mb-0">Analyzing…</div></div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

  <script>
    // ==== Map setup ====
    const map = L.map('map').setView([23.8103, 90.4125], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
    let currentMarker, radiusCircle;

    function putMarker(lat, lon, label) {
      if (currentMarker) map.removeLayer(currentMarker);
      if (radiusCircle) map.removeLayer(radiusCircle);

      currentMarker = L.marker([lat, lon]).addTo(map).bindPopup(label || 'Selected Location').openPopup();

      // 400m highlight radius
      radiusCircle = L.circle([lat, lon], { radius: 400, fillOpacity: 0.08, weight: 1 }).addTo(map);
      map.setView([lat, lon], 15);
    }

    // ==== Live clock ====
    function updateClock() {
      const now = new Date();
      const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });
      document.getElementById('liveClock').textContent = `🕒 ${timeString}`;
    }
    setInterval(updateClock, 1000); updateClock();

    // ==== Lighting estimator ====
    function estimateLightingChip() {
      const hour = new Date().getHours();
      const zoom = map.getZoom();
      let txt = "Lighting: Unknown";

      if (hour >= 6 && hour <= 17) {
        txt = "Lighting: Daylight";
      } else if (hour > 17 && hour < 20) {
        txt = "Lighting: Dusk (varying lights)";
      } else {
        txt = zoom >= 15 ? "Lighting: Urban night (likely lit)" : "Lighting: Night (possibly dark)";
      }
      document.getElementById('lightingChip').textContent = txt;
    }
    map.on('zoomend', estimateLightingChip);
    estimateLightingChip();

    // ==== Clothing tip based on weather text ====
    function clothingTipFromWeather(weatherText) {
      const tipBox = document.getElementById("clothingTip");
      const text = (weatherText || "").toLowerCase();

      if (text.includes("rain")) tipBox.textContent = "Clothing Tip: Carry an umbrella or raincoat.";
      else if (text.includes("hot") || text.includes("sunny")) tipBox.textContent = "Clothing Tip: Wear light and breathable clothes.";
      else if (text.includes("cold") || text.includes("chill")) tipBox.textContent = "Clothing Tip: Dress warmly in layers.";
      else tipBox.textContent = "Clothing Tip: Dress comfortably for general conditions.";
    }

    // ==== Noise estimator based on crowd ====
    function estimateNoiseLevel(crowd) {
      const hour = new Date().getHours();
      const isPeak = (hour >= 7 && hour <= 9) || (hour >= 17 && hour <= 20);
      let noise = "Quiet";

      const ctext = (crowd || "").toLowerCase();
      if (ctext.includes("high")) {
        noise = isPeak ? "Very loud — rush hour crowd." : "Loud — heavy presence.";
      } else if (ctext.includes("moderate")) {
        noise = "Moderate — some foot traffic.";
      }
      document.getElementById("noiseLevel").textContent = "Noise: " + noise;
    }

    // ==== Mood forecast ====
    function moodForecast(score) {
      let mood = "Neutral vibes 🌫️";
      if (score > 80) mood = "Positive vibes 🌞 — feels like a good time to go out!";
      else if (score > 50) mood = "Mixed vibes 🌤️ — stay alert, stay safe!";
      else mood = "Caution vibes 🌧️ — better wait or be highly cautious.";
      document.getElementById("moodForecast").textContent = mood;
    }

    // ==== Time advisory chip ====
    function updateTimeAdvisory() {
      const hour = new Date().getHours();
      const timeMessage = (hour >= 18 || hour <= 5)
        ? "Time: Dark hours — extra caution."
        : "Time: Daylight — stay alert.";
      document.getElementById('timeAdvisoryChip').textContent = timeMessage;
    }
    setInterval(updateTimeAdvisory, 60000); updateTimeAdvisory();

    // ==== Populate UI helpers ====
    function setSafetyScore(score, crimeTrend) {
      const safeText = document.getElementById("safetyScoreText");
      const bar = document.getElementById("safetyScoreBar");
      const trendLine = document.getElementById("crimeTrendText");

      const s = Number(score) || 0;
      safeText.textContent = `${s}/100`;
      bar.style.width = `${Math.max(0, Math.min(100, s))}%`;

      // Color cue
      const parent = bar.parentElement.parentElement.parentElement; // .info-box
      if (s > 70) parent.className = "info-box bg-success";
      else if (s > 40) parent.className = "info-box bg-warning";
      else parent.className = "info-box bg-danger";

      trendLine.textContent = "Crime Trend: " + (crimeTrend || "—");
    }

    function renderCSVList(el, csv) {
      const list = (csv || "").split(",").map(s => s.trim()).filter(Boolean);
      el.innerHTML = "";
      if (!list.length) {
        el.innerHTML = "<li>—</li>";
        return;
      }
      list.forEach(x => {
        const li = document.createElement("li");
        li.textContent = x;
        el.appendChild(li);
      });
    }

    function renderTransportScores(div, scoresObj) {
      div.innerHTML = "";
      const entries = Object.entries(scoresObj || {});
      if (!entries.length) {
        div.textContent = "—";
        return;
      }
      entries.forEach(([mode, val]) => {
        const pill = document.createElement("span");
        pill.className = "badge badge-pill mr-2";
        // Color by score
        if (val > 70) pill.classList.add("badge-success");
        else if (val > 40) pill.classList.add("badge-warning");
        else pill.classList.add("badge-danger");
        pill.textContent = `${mode}: ${val}`;
        div.appendChild(pill);
      });
    }

    // Copy a neat summary
    function copySummaryToClipboard(summary) {
      navigator.clipboard.writeText(summary).then(() => {
        $('#copySummaryBtn').text('Copied!').prop('disabled', true);
        setTimeout(() => {
          $('#copySummaryBtn').text('Copy Summary').prop('disabled', false);
        }, 1500);
      });
    }

    document.getElementById("copySummaryBtn").addEventListener("click", () => {
      const area = window.__lastAreaName || "(Unknown)";
      const score = (document.getElementById("safetyScoreText").textContent || "—").trim();
      const weather = (document.getElementById("weatherAdvisory").textContent || "—").trim();
      const crowd = (document.getElementById("crowdDensity").textContent || "—").trim();
      const time = (document.getElementById("timeAdvisoryChip").textContent || "—").trim();
      const lighting = (document.getElementById("lightingChip").textContent || "—").trim();
      const mood = (document.getElementById("moodForecast").textContent || "—").trim();
      const summary = `Area: ${area}\nSafety Score: ${score}\nWeather: ${weather}\nCrowd: ${crowd}\n${time}\n${lighting}\nMood: ${mood}`;
      copySummaryToClipboard(summary);
    });

    // ==== Geocode + Fetch unified data ====
    async function geocodeDhaka(query) {
      const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query + ", Dhaka")}`;
      const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
      if (!res.ok) throw new Error("Geocoding failed");
      const data = await res.json();
      return data && data.length ? data[0] : null;
    }

    async function fetchSafety(location) {
      const res = await fetch(`connect_php/get_location_safety.php?location=${encodeURIComponent(location)}`);
      const data = await res.json();
      return data;
    }

    async function doSearch(location) {
      try {
        // Geocode to center the map
        const place = await geocodeDhaka(location);
        if (!place) {
          alert("Location not found. Please try a different search.");
          return;
        }
        const lat = parseFloat(place.lat);
        const lon = parseFloat(place.lon);
        putMarker(lat, lon, place.display_name);

        // Fetch consolidated safety data from backend
        const data = await fetchSafety(location);
        if (data.error) {
          console.warn(data.error);
          // Still show map position; set defaults
          setSafetyScore(0, "N/A");
          document.getElementById("weatherAdvisory").textContent = "N/A";
          clothingTipFromWeather("N/A");
          document.getElementById("crowdDensity").textContent = "—";
          document.getElementById("crimeTrendPara").textContent = "—";
          renderCSVList(document.getElementById("hospitalList"), "");
          renderCSVList(document.getElementById("policeList"), "");
          renderCSVList(document.getElementById("incidentList"), "");
          renderTransportScores(document.getElementById("transportScores"), {});
          estimateNoiseLevel("");
          moodForecast(0);
          updateTimeAdvisory();
          estimateLightingChip();
          return;
        }

        window.__lastAreaName = data.area_name || location;

        // If DB has its own lat/lon, prefer those to be precise for the area record
        if (typeof data.lat === "number" && typeof data.lon === "number") {
          putMarker(data.lat, data.lon, data.area_name || location);
        }

        // Safety score + trend
        setSafetyScore(data.safety_score, data.crime_trend);
        document.getElementById("crimeTrendPara").textContent = data.crime_trend || "—";

        // Hospitals & police
        renderCSVList(document.getElementById("hospitalList"), data.hospitals);
        renderCSVList(document.getElementById("policeList"), data.police_stations);

        // Weather
        document.getElementById("weatherAdvisory").textContent = data.weather_advisory || "—";
        clothingTipFromWeather(data.weather_advisory || "");

        // Crowd
        document.getElementById("crowdDensity").textContent = data.crowd_density ? `Estimated: ${data.crowd_density}` : "—";
        estimateNoiseLevel(data.crowd_density || "");

        // Incidents
        renderCSVList(document.getElementById("incidentList"), data.incidents);

        // Transport
        renderTransportScores(document.getElementById("transportScores"), data.transport_scores || {});

        // Misc
        moodForecast(Number(data.safety_score) || 0);
        updateTimeAdvisory();
        estimateLightingChip();

      } catch (err) {
        console.error(err);
        alert("Error fetching location/safety data.");
      }
    }

    // Form submit
    document.getElementById("location-form").addEventListener("submit", function(e) {
      e.preventDefault();
      const q = document.getElementById("locationInput").value.trim();
      if (!q) return;
      doSearch(q);
    });
  </script>
</body>

</html>
