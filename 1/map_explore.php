<?php
include 'connect.php';

// Total Resources
$totalSql = "SELECT * FROM safety_ratings";
$totalRes = $conn->query($totalSql);

// Unique Categories (area types)
$categoriesSql = "SELECT DISTINCT area_type FROM safety_ratings";
$categoriesRes = $conn->query($categoriesSql);

// Distinct Areas (locations)
$locationsSql = "SELECT DISTINCT location FROM safety_ratings";
$locationsRes = $conn->query($locationsSql);
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Map Exploration - SafeWay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Fonts & Icons -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700"
    />
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />

    <!-- AdminLTE & Plugins -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css" />
    <link
      rel="stylesheet"
      href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css"
    />

    <!-- Leaflet & Plugins -->
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    />
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css"
    />
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css"
    />
    <link
      rel="stylesheet"
      href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css"
    />

    <style>
/* 🌐 Base Styles */
body {
  background: linear-gradient(135deg, #e0f0ff 0%, #ffffff 100%);
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  color: #223344;
}

/* ⬛ Navbar */
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

/* 🧭 Sidebar */
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

/* 📢 Headings */
h2, h5 {
  border-bottom: 4px solid #0056b3;
  padding-bottom: 12px;
  margin-bottom: 30px;
  font-weight: 800;
  color: #003366;
  letter-spacing: 0.05em;
  text-transform: uppercase;
}

/* 🗺️ Map Styling */
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

/* 🔍 Inputs */
#search-input,
#filter-select {
  width: 300px;
  padding: 14px 20px;
  font-size: 1rem;
  border-radius: 10px;
  border: 2px solid #0056b3;
  margin: 10px 5px;
  color: #003366;
  box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

#search-input:hover,
#filter-select:hover {
  border-color: #003366;
  box-shadow: 0 0 10px #0056b3aa;
}

#search-input:focus,
#filter-select:focus {
  border-color: #00254d;
  outline: none;
  box-shadow: 0 0 12px #003366cc;
}

#search-button {
  padding: 12px 22px;
  background-color: #004085;
  color: white;
  border: none;
  border-radius: 10px;
  font-weight: bold;
  transition: background-color 0.3s;
}

#search-button:hover {
  background-color: #00254d;
}

/* 🌙 Dark Mode Button */
#dark-mode-toggle {
  position: fixed;
  top: 80px;
  right: 30px;
  z-index: 1000;
  background: #ffffff;
  color: #223344;
  padding: 10px 18px;
  border: none;
  border-radius: 10px;
  font-weight: 600;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
}

.dark-mode {
  background-color: #1f1f1f;
  color: #f0f0f0;
}

.dark-mode #map {
  filter: invert(90%) hue-rotate(180deg);
}

/* 📊 Stats Cards */
.stats-card {
  text-align: center;
  padding: 18px;
  border-radius: 14px;
  font-weight: bold;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.bg-info {
  background-color: #17a2b8 !important;
  color: white;
}

.bg-success {
  background-color: #28a745 !important;
  color: white;
}

.bg-danger {
  background-color: #dc3545 !important;
  color: white;
}

/* 📋 Table Styling */
.table-container {
  background: #ffffff;
  padding: 25px;
  border-radius: 20px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
  margin-top: 40px;
}

table.table {
  border-radius: 12px;
  overflow: hidden;
  font-size: 0.95rem;
}

thead.thead-dark th {
  background-color: #004085;
  color: white;
}

/* 🔃 Mobile */
@media (max-width: 768px) {
  #search-input,
  #filter-select,
  #search-button {
    width: 90%;
    margin: 5px auto;
  }

  #dark-mode-toggle {
    right: 10px;
    top: 70px;
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
        <img src="dist/img/AdminLTELogo.png" alt="SafeWay Logo" class="brand-image img-circle elevation-3" />
        <span class="brand-text font-weight-light">SafeWay</span>
      </a>
      <div class="sidebar">
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column">
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
              <a href="map_explore.php" class="nav-link active">
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
              <a href="check_safety.php" class="nav-link">
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

      <div class="content-wrapper">
        <section class="content-header">
          <div class="container-fluid">
            <h2>Map Exploration</h2>
            <p class="text-muted">
              Explore safety data on the map. Click on markers for more details.
            </p>
          </div>
        </section>


        <section class="content">
          <div class="container-fluid">
            <!-- Stats Cards -->
            <div class="row text-white mb-4">
              <div class="col-md-4">
                <div class="stats-card bg-info p-3 rounded shadow text-center">
                  <h3 class="count" data-count="<?= $totalRes->num_rows ?>">
                    0
                  </h3>
                  <small>Total Resources</small>
                </div>
              </div>
              <div class="col-md-4">
                <div
                  class="stats-card bg-success p-3 rounded shadow text-center"
                >
                  <h3
                    class="count"
                    data-count="<?= $categoriesRes->num_rows ?>"
                  >
                    0
                  </h3>
                  <small>Unique Area Type</small>
                </div>
              </div>
              <div class="col-md-4">
                <div
                  class="stats-card bg-danger p-3 rounded shadow text-center"
                >
                  <h3 class="count" data-count="<?= $locationsRes->num_rows ?>">
                    0
                  </h3>
                  <small>Distinct Location</small>
                </div>
              </div>
            </div>


            

      <!-- Search & Filter -->
      <div id="search-container">
        <input
          type="text"
          id="search-input"
          placeholder="Search a place (e.g. Gulshan 2)"
        />
        <button id="search-button">Search</button>
      </div>

      <div id="filter-container">
        <label for="filter-select">Filter by Safety Level:</label>
        <select id="filter-select">
          <option value="all">All</option>
          <option value="High">High</option>
          <option value="Moderate">Moderate</option>
          <option value="Low">Low</option>
        </select>
      </div>

      <!-- ✅ Dark Mode Toggle -->
      <button id="dark-mode-toggle">Toggle Dark Mode</button>





            <div id="map"></div>
            <!-- ✅ All JS features like heatmap, fullscreen, etc. attach here -->

          <!-- Safety Ratings Table -->
          <div class="table-container mt-4">
            <h5 class="mt-4 mb-2">Detailed Safety Ratings Table</h5>
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
              <?php
              $sql = "SELECT * FROM safety_ratings";
              $result = $conn->query($sql);
              if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                  echo "<tr>
                          <td>{$row['location']}</td>
                          <td>{$row['latitude']}</td>
                          <td>{$row['longitude']}</td>
                          <td>{$row['area_type']}</td>
                          <td>{$row['reported_incidents']}</td>
                          <td>{$row['safety_rating']}</td>
                          <td>{$row['notes']}</td>
                        </tr>";
                }
              } else {
                echo "<tr><td colspan='7'>No data found</td></tr>";
              }
              ?>
              </tbody>
            </table>
          </div>

          </div>
        </section>
      </div>
    </div>

    <!-- Scripts -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <!-- Leaflet JS & Plugins -->
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>

    <!-- DataTables -->
    <link
      rel="stylesheet"
      href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css"
    />
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <script>
      $(document).ready(function () {
        $("#ratingsTable").DataTable({
          responsive: true,
          dom: "Bfrtip",
          buttons: ["csvHtml5", "excelHtml5", "pdfHtml5"],
          pageLength: 10,
        });

        // Counter Animation
        $(".count").each(function () {
          let $this = $(this);
          let countTo = $this.attr("data-count");
          $({ countNum: $this.text() }).animate(
            {
              countNum: countTo,
            },
            {
              duration: 1000,
              easing: "swing",
              step: function () {
                $this.text(Math.floor(this.countNum));
              },
              complete: function () {
                $this.text(this.countNum);
              },
            }
          );
        });
      });

      const map = L.map("map").setView([23.8103, 90.4125], 12);

      let light = L.tileLayer(
        "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
        {
          maxZoom: 19,
          attribution: "&copy; OpenStreetMap contributors",
        }
      ).addTo(map);

      let dark = L.tileLayer(
        "https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png",
        {
          attribution: "&copy; OpenStreetMap contributors & CartoDB",
        }
      );

      const markers = [];
      let heat;

      fetch("connect_php/get_safety_zones.php")
        .then((response) => response.json())
        .then((safetyData) => {
          console.log("Fetched safety data:", safetyData);

          heat = L.heatLayer(
            safetyData.map((p) => [...p.coords, 0.5]),
            { radius: 25 }
          );

          safetyData.forEach((location) => {
            const popupContent = `
                <table class="popup-table">
                    <tr><th colspan="2">${location.name}</th></tr>
                    <tr><td><strong>Safety Level:</strong></td><td>${location.level}</td></tr>
                    <tr><td><strong>Police Patrolling:</strong></td><td>${location.patrolling}</td></tr>
                    <tr><td><strong>Street Lighting:</strong></td><td>${location.streetLighting}</td></tr>
                    <tr><td><strong>Incident Reports:</strong></td><td>${location.incidentReports}</td></tr>
                    <tr><td><strong>Women Help Support:</strong></td><td>${location.womenHelpline}</td></tr>
                    <tr><td><strong>Public Transport Access:</strong></td><td>${location.transportAccess}</td></tr>
                    <tr><td><strong>General Notes:</strong></td><td>${location.description}</td></tr>
                </table>
            `;

            const marker = L.circleMarker(location.coords, {
              radius: 8,
              color: location.color,
              fillColor: location.color,
              fillOpacity: 0.8,
            })
              .addTo(map)
              .bindPopup(popupContent);

            markers.push({
              name: location.name.toLowerCase(),
              marker: marker,
              popup: popupContent,
              location: location,
            });
          });

          document
            .getElementById("search-button")
            .addEventListener("click", () => {
              const query = document
                .getElementById("search-input")
                .value.toLowerCase()
                .trim();
              const results = markers.filter((item) =>
                item.name.includes(query)
              );

              if (results.length > 0) {
                const result = results[0];
                map.setView(result.marker.getLatLng(), 15);
                result.marker.openPopup();
              } else {
                alert(
                  "Place not found. Try typing part of the name like 'Gulshan', 'Mirpur', etc."
                );
              }
            });
        })
        .catch((error) => console.error("Error loading safety data:", error));

      // Dark Mode Toggle
      const darkModeBtn = document.getElementById("dark-mode-toggle");
      darkModeBtn.addEventListener("click", () => {
        if (map.hasLayer(light)) {
          map.removeLayer(light);
          dark.addTo(map);
          document.body.classList.add("dark-mode");
        } else {
          map.removeLayer(dark);
          light.addTo(map);
          document.body.classList.remove("dark-mode");
        }
      });

      // Geolocation Button
      L.control.locate = function () {
        let control = L.control({ position: "topright" });
        control.onAdd = function () {
          let container = L.DomUtil.create("button", "leaflet-bar");
          container.innerHTML = '<i class="fas fa-location-arrow"></i>';
          container.style.background = "white";
          container.onclick = function () {
            navigator.geolocation.getCurrentPosition(function (position) {
              let latlng = [
                position.coords.latitude,
                position.coords.longitude,
              ];
              map.setView(latlng, 15);
              L.marker(latlng)
                .addTo(map)
                .bindPopup("You are here!")
                .openPopup();
            });
          };
          return container;
        };
        return control;
      };
      L.control.locate().addTo(map);

      // Heatmap Toggle
      const heatToggle = L.control({ position: "topright" });
      heatToggle.onAdd = function () {
        const div = L.DomUtil.create("div", "leaflet-bar");
        const btn = document.createElement("button");
        btn.textContent = "Toggle Heatmap";
        btn.style.padding = "5px";
        btn.onclick = () => {
          if (map.hasLayer(heat)) {
            map.removeLayer(heat);
          } else {
            heat.addTo(map);
          }
        };
        div.appendChild(btn);
        return div;
      };
      heatToggle.addTo(map);

      // Legend
      const legend = L.control({ position: "bottomright" });
      legend.onAdd = function () {
        const div = L.DomUtil.create("div", "legend");
        div.innerHTML += '<i style="background:green"></i> High<br>';
        div.innerHTML += '<i style="background:yellow"></i> Moderate<br>';
        div.innerHTML += '<i style="background:red"></i> Low<br>';
        return div;
      };
      legend.addTo(map);

      // Real-Time Clock
      const clockControl = L.control({ position: "bottomleft" });
      clockControl.onAdd = function () {
        const div = L.DomUtil.create("div", "clock");
        const updateClock = () => {
          const now = new Date();
          div.innerHTML = now.toLocaleTimeString();
        };
        setInterval(updateClock, 1000);
        updateClock();
        return div;
      };
      clockControl.addTo(map);

      // Fullscreen Toggle
      const fullscreenControl = L.control({ position: "topleft" });
      fullscreenControl.onAdd = function () {
        const btn = L.DomUtil.create("button", "leaflet-bar");
        btn.innerHTML = '<i class="fas fa-expand"></i>';
        btn.onclick = () => {
          if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
          } else {
            document.exitFullscreen();
          }
        };
        return btn;
      };
      fullscreenControl.addTo(map);
    </script>
  </body>
</html>
