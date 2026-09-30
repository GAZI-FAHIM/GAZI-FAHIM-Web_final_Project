<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Check Before Going Out - SafeWay</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Fonts & AdminLTE CSS -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" />
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
  <link rel="stylesheet" href="dist/css/adminlte.min.css" />
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

  <style>
  /* Body and font */
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

  /* Header */
  header.text-center {
    background-color: #004085;
    color: white;
    padding: 1rem;
    font-size: 1.7rem;
    font-weight: 700;
    border-radius: 15px;
    margin-bottom: 30px;
    letter-spacing: 0.05em;
  }

  /* Form Controls */
  input.form-control {
    border-radius: 15px;
    padding: 14px 20px;
    border: 1px solid #ced4da;
    font-size: 1.1rem;
    box-shadow: none;
    transition: border-color 0.3s ease;
  }

  input.form-control:focus {
    border-color: #004085;
    box-shadow: 0 0 8px rgba(0, 64, 133, 0.4);
  }

  /* Button */
  .btn-primary {
    background-color: #004085;
    border-color: #004085;
    font-weight: 700;
    border-radius: 15px;
    padding: 12px 28px;
    font-size: 1.1rem;
    transition: background-color 0.3s ease;
  }

  .btn-primary:hover {
    background-color: #0056b3;
    border-color: #0056b3;
  }

  /* Result */
  #result {
    font-size: 1.4rem;
    font-weight: 700;
    color: #003366;
    margin-top: 30px;
  }

  /* Map styling */
  #map {
    height: 550px;
    width: 100%;
    border-radius: 15px;
    border: 3px solid #004085;
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
    transition: box-shadow 0.3s ease;
    margin-top: 30px;
  }

  #map:hover {
    box-shadow: 0 18px 45px rgba(0, 0, 0, 0.25);
  }

  /* Feature Cards */
  .feature-card {
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 25px;
    background: #e6f0ff;
    box-shadow: 0 8px 24px rgba(0, 64, 133, 0.15);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .feature-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 32px rgba(0, 64, 133, 0.25);
  }

  .feature-title {
    font-weight: 700;
    font-size: 1.4rem;
    color: #003366;
    margin-bottom: 15px;
  }

  /* Responsive */
  @media (max-width: 768px) {
    #map {
      height: 300px;
    }

    header.text-center {
      font-size: 1.3rem;
      padding: 0.75rem;
    }

    .feature-card {
      padding: 18px;
    }

    input.form-control {
      font-size: 1rem;
      padding: 12px 14px;
    }

    .btn-primary {
      font-size: 1rem;
      padding: 10px 22px;
    }
  }

  
.live-clock {
  font-size: 1.4rem;
  font-weight: 600;
  margin-top: 10px;
  padding: 8px 16px;
}


.safety-check-header {
  background: linear-gradient(90deg, #6d4accff, #090781ff);
  padding: 20px;
  border-radius: 16px;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.1);
  animation: fadeSlideIn 0.6s ease-in-out;
  color: #2c3e50;
}

.safety-check-title {
  font-size: 1.8rem;
  font-weight: 700;
  color: #ffffffff;
  text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.6);
}



@keyframes fadeSlideIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
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
        <img src="dist/img/AdminLTELogo.png" alt="Logo" class="brand-image img-circle elevation-3" />
        <span class="brand-text font-weight-light">SafeWay</span>
      </a>
      <div class="sidebar">
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
            <!-- Dashboard -->
            <li class="nav-item">
              <a href="dashboard.php" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
              </a>
            </li>

            <!-- Location & Map Features -->
            <li class="nav-item">
              <a href="location_search.php" class="nav-link">
                <i class="nav-icon fas fa-search-location"></i>
                <p>Basic Location Search</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="map_explore.php" class="nav-link">
                <i class="nav-icon fas fa-map-marked-alt"></i>
                <p>Map Exploration</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="safety_ratings.php" class="nav-link">
                <i class="nav-icon fas fa-eye"></i>
                <p>Visual Safety Ratings</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="check_safety.php" class="nav-link active">
                <i class="nav-icon fas fa-shield-alt"></i>
                <p>Check Before Going Out</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="identify_routes.php" class="nav-link">
                <i class="nav-icon fas fa-route"></i>
                <p>Identify Safer Routes</p>
              </a>
            </li>

            <!-- Crime & Incident Insights -->
            <li class="nav-item">
              <a href="filter_incidents.php" class="nav-link">
                <i class="nav-icon fas fa-filter"></i>
                <p>Filter Incidents</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="crime_hotspot.php" class="nav-link">
                <i class="nav-icon fas fa-exclamation-triangle"></i>
                <p>Crime Hotspot Tracker</p>
              </a>
            </li>

            <!-- Resources & Community Support -->
            <li class="nav-item">
              <a href="community_resources.php" class="nav-link">
                <i class="nav-icon fas fa-hands-helping"></i>
                <p>Community Resources</p>
              </a>
            </li>

            <!-- Educational Tools -->
            <li class="nav-item">
              <a href="understand_factors.php" class="nav-link">
                <i class="nav-icon fas fa-lightbulb"></i>
                <p>Understanding Safety Factors</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="legend_info.php" class="nav-link">
                <i class="nav-icon fas fa-map"></i>
                <p>Using the Legend</p>
              </a>
            </li>

            <!-- Communication -->
            <li class="nav-item">
              <a href="send_notifications.php" class="nav-link">
                <i class="nav-icon fas fa-bell"></i>
                <p>Send Notifications</p>
              </a>
            </li>
            <li class="nav-item"><a href="all_notifications.php" class="nav-link "><i class="nav-icon fas fa-bell"></i><p>All Notifications</p></a></li>

            <li class="nav-item">
              <a href="emergency_calls.php" class="nav-link">
                <i class="nav-icon fas fa-phone-alt"></i>
                <p>Emergency Calls</p>
              </a>
            </li>

            <!-- Logout -->
            <li class="nav-item">
              <a href="login.html" class="nav-link">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>Logout</p>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </aside>





<!-- Main Content -->
<div class="content-wrapper p-3">
  <div class="container-fluid">
    <!-- Header -->
<header class="safety-check-header text-center py-3 mb-4">
  <h3 class="safety-check-title">
    🔎 <span>Check Safety Before Going Out</span>
  </h3>
  <div id="liveClock" class="live-clock mt-2"></div>
</header>




    <!-- Location Input -->
    <div class="form-group mt-3">
      <label for="locationInput">Enter Location:</label>
      <input type="text" id="locationInput" class="form-control" placeholder="e.g., Dhanmondi, Dhaka">
    </div>
    <button class="btn btn-primary mt-2" onclick="checkSafety()">Check Safety</button>

    <!-- Safety Score Result -->
    <div id="result" class="mt-4"></div>

    <!-- Map -->
    <div id="map" class="mt-4" style="height: 550px;"></div>

    <!-- Features (initially hidden) -->
    <div id="features" class="mt-4" style="display: none;">
      <div class="feature-card">
        <div class="feature-title">1. Time-Sensitive Safety Advisory</div>
        <div id="timeAdvisory">Checking...</div>
      </div>

      <div class="feature-card">
        <div class="feature-title">2. Nearby Police Stations</div>
        <ul id="policeList"></ul>
      </div>

      <div class="feature-card">
        <div class="feature-title">3. Emergency Contacts</div>
        <p>
          <a href="tel:999" class="btn btn-danger btn-sm">Call Police (999)</a>
          <a href="tel:199" class="btn btn-warning btn-sm">Fire Service (199)</a>
          <a href="tel:16263" class="btn btn-info btn-sm">Health Help (16263)</a>
        </p>
      </div>

      <div class="feature-card">
        <div class="feature-title">4. Weather-Based Advisory</div>
        <div id="weatherAdvisory">...</div>
      </div>

      <div class="feature-card">
        <div class="feature-title">5. Crowd Density Estimator</div>
        <div id="crowdDensity">...</div>
      </div>

      <div class="feature-card">
        <div class="feature-title">6. Last Reported Incidents</div>
        <ul id="incidentList"></ul>
      </div>

      <div class="feature-card">
        <div class="feature-title">7. Public Transport Safety Score</div>
        <div id="transportScores"></div>
      </div>

      <div class="feature-card">
        <div class="feature-title">8. Estimated Lighting Conditions</div>
        <div id="lightingEstimator">Checking...</div>
      </div>

      <div class="feature-card">
        <div class="feature-title">9. Clothing Tip</div>
        <div id="clothingTip">Checking...</div>
      </div>

      <div class="feature-card">
        <div class="feature-title">10. Estimated Noise Level</div>
        <div id="noiseLevel">Checking...</div>
      </div>

      <div class="feature-card">
        <div class="feature-title">11. Mood Forecast 🌤️</div>
        <div id="moodForecast">Analyzing...</div>
      </div>
    </div>
  </div>
</div>

<!-- JS Dependencies -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

<script>
  const map = L.map('map').setView([23.8103, 90.4125], 13);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18 }).addTo(map);
  let currentMarker;

  function estimateLighting() {
    const hour = new Date().getHours();
    const zoom = map.getZoom();
    let lighting = "Unknown";

    if (hour >= 6 && hour <= 17) {
      lighting = "Bright daylight — visibility is good.";
    } else if (hour > 17 && hour < 20) {
      lighting = "Dusk — street lights may vary.";
    } else {
      lighting = zoom >= 15
        ? "Urban night — streets likely lit."
        : "Dark zone — lighting might be poor.";
    }

    document.getElementById("lightingEstimator").textContent = lighting;
  }

  map.on('zoomend', estimateLighting);

  function clothingTipFromWeather(weatherText) {
    const tipBox = document.getElementById("clothingTip");
    const text = weatherText.toLowerCase();

    if (text.includes("rain")) tipBox.textContent = "Carry an umbrella or raincoat.";
    else if (text.includes("hot") || text.includes("sunny")) tipBox.textContent = "Wear light and breathable clothes.";
    else if (text.includes("cold") || text.includes("chill")) tipBox.textContent = "Dress warmly in layers.";
    else tipBox.textContent = "Dress comfortably for general conditions.";
  }

  function estimateNoiseLevel(crowd) {
    const hour = new Date().getHours();
    const isPeak = (hour >= 7 && hour <= 9) || (hour >= 17 && hour <= 20);
    let noise = "Quiet";

    if (crowd.toLowerCase().includes("high")) {
      noise = isPeak ? "Very loud — rush hour crowd." : "Loud — heavy presence.";
    } else if (crowd.toLowerCase().includes("moderate")) {
      noise = "Moderate — some foot traffic.";
    }

    document.getElementById("noiseLevel").textContent = noise;
  }

  function moodForecast(score) {
    let mood = "Neutral vibes 🌫️";
    if (score > 80) mood = "Positive vibes 🌞 — feels like a good time to go out!";
    else if (score > 50) mood = "Mixed vibes 🌤️ — stay alert, stay safe!";
    else mood = "Caution vibes 🌧️ — better wait or be highly cautious.";

    document.getElementById("moodForecast").textContent = mood;
  }

  function checkSafety() {
    const location = document.getElementById('locationInput').value.trim();
    if (!location) return alert("Please enter a location.");

    fetch(`get_check_safety.php?location=${encodeURIComponent(location)}`)
      .then(response => response.json())
      .then(data => {
        if (data.error) return alert(data.error);

        const {
          lat, lon, safety_score, police_stations,
          weather_advisory, crowd_density, incidents, transport_scores
        } = data;

        // Update map
        if (currentMarker) map.removeLayer(currentMarker);
        currentMarker = L.marker([lat, lon]).addTo(map).bindPopup(location).openPopup();
        map.setView([lat, lon], 15);

        // Safety Score
        const safetyColor = safety_score > 70 ? 'green' : (safety_score > 40 ? 'orange' : 'red');
        document.getElementById('result').innerHTML = `
          <div class="safety-score p-3" style="background-color:${safetyColor}; color:white; border-radius:10px;">
            Safety Score: ${safety_score}/100
          </div>
        `;

        // Police Stations
        const policeList = document.getElementById('policeList');
        policeList.innerHTML = "";
        police_stations.forEach(station => {
          const li = document.createElement("li");
          li.textContent = station;
          policeList.appendChild(li);
        });

        // Weather
        document.getElementById('weatherAdvisory').textContent = weather_advisory;

        // Crowd Density
        document.getElementById('crowdDensity').innerHTML =
          `Estimated: <strong>${crowd_density}</strong> crowd in your area right now.`;

        // Incidents
        const incidentList = document.getElementById('incidentList');
        incidentList.innerHTML = "";
        incidents.forEach(item => {
          const li = document.createElement("li");
          li.textContent = item;
          incidentList.appendChild(li);
        });

        // Transport Scores
        const transportDiv = document.getElementById('transportScores');
        transportDiv.innerHTML = "";
        for (const [type, score] of Object.entries(transport_scores)) {
          transportDiv.innerHTML += `<div>${type}: <span>${score}</span></div>`;
        }

        // Advisory
        const hour = new Date().getHours();
        const timeMessage = (hour >= 18 || hour <= 5)
          ? "It's dark outside. Be extra cautious and avoid poorly lit areas."
          : "It's daytime. Still, remain alert and aware of your surroundings.";
        document.getElementById('timeAdvisory').textContent = timeMessage;

        // Extra features
        clothingTipFromWeather(weather_advisory);
        estimateNoiseLevel(crowd_density);
        moodForecast(safety_score);
        estimateLighting();

        // Show features
        document.getElementById("features").style.display = "block";
      })
      .catch(err => {
        alert("Failed to fetch safety data. Please try again later.");
        console.error(err);
      });
  }

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
