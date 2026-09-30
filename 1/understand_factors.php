<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Understanding Safety Factors - SafeWay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Google Font & AdminLTE CSS -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback"
    />
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
    <link rel="stylesheet" href="dist/css/adminlte.min.css" />
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css" />

    <style>
  :root {
    --primary: #004085;
    --accent: #007bff;
    --highlight: #17a2b8;
    --light-bg: #f4f7fb;
    --dark-bg: #1c1f22;
    --white: #ffffff;
    --card-bg: #ffffff;
    --dark-card: #2c2f34;
    --text-dark: #2d3436;
    --text-light: #ddd;
    --danger: #dc3545;
  }

  body {
    font-family: 'Source Sans Pro', sans-serif;
    background-color: var(--light-bg);
    color: var(--text-dark);
    transition: background 0.3s, color 0.3s;
    margin: 0;
  }

  .content-wrapper {
    background: var(--light-bg);
    padding: 2rem 1rem;
    transition: background 0.3s;
  }

  h2 {
    color: var(--primary);
    border-bottom: 3px solid var(--primary);
    padding-bottom: 12px;
    margin-bottom: 25px;
    font-weight: 700;
  }

  .feature-card {
    border: 1px solid #dbe6f4;
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 20px;
    background-color: var(--card-bg);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
    transition: 0.3s ease;
  }

  .feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
  }

  .feature-title {
    font-weight: 700;
    font-size: 1.3rem;
    color: var(--primary);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
  }

  .feature-icon {
    font-size: 1.5rem;
    margin-right: 10px;
    color: var(--highlight);
    transition: transform 0.3s;
  }

  .feature-card:hover .feature-icon {
    transform: scale(1.2);
  }

  .highlight {
    font-weight: bold;
    color: var(--danger);
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

  /* Dark Mode */
  body.dark-mode {
    background-color: var(--dark-bg);
    color: var(--text-light);
  }

  body.dark-mode .content-wrapper {
    background-color: var(--dark-bg);
  }

  body.dark-mode .feature-card {
    background: linear-gradient(135deg, var(--dark-card), #1a1c1f);
    color: var(--text-light);
    border: 1px solid #333;
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.05);
  }

  body.dark-mode .feature-title,
  body.dark-mode h2 {
    color: #80bdff;
  }

  body.dark-mode .feature-icon {
    color: #80bdff;
  }

  /* Button toggle */
  #darkModeToggle {
    transition: background 0.3s, color 0.3s;
  }

  @media (max-width: 768px) {
    .feature-title {
      font-size: 1.1rem;
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

            <!-- Dark/Light mode toggle button -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <button id="darkModeToggle" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-moon"></i> Dark Mode
                    </button>
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
  <a href="dashboard.php" class="nav-link ">
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
  <a href="understand_factors.php" class="nav-link active">
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
                <h2>Understanding Safety Factors</h2>
                <p class="mb-4">Learn about the key elements that influence how safe or risky a route may be.</p>

                <!-- Safety Factor Cards -->
                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-lightbulb feature-icon"></i>1. Lighting Conditions</div>
                    <p>Well-lit streets reduce the chances of crime and help in identifying suspicious activities. Avoid dark alleys or poorly lit areas.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-users feature-icon"></i>2. Crowd Density</div>
                    <p>Moderately crowded areas offer more safety. Too deserted or overly packed areas can both present risks.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-exclamation-triangle feature-icon"></i>3. Crime History</div>
                    <p>Areas with a history of thefts, harassment, or other crimes are marked with a <span class="highlight">red or orange</span> safety rating on the map.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-video feature-icon"></i>4. Nearby Safety Infrastructure</div>
                    <p>Presence of police stations, CCTV cameras, emergency booths, or security personnel increases safety.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-bus feature-icon"></i>5. Public Transport Quality</div>
                    <p>Trustworthy, well-regulated public transport options (like buses with verified routes) improve route safety.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-bullhorn feature-icon"></i>6. Real-Time Reports</div>
                    <p>Stay informed using recent community updates on suspicious activity or incidents submitted by users.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-moon feature-icon"></i>7. Time of Day</div>
                    <p>Night-time increases vulnerability. Prefer day-time travel or stay in well-patrolled zones after dark.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-road feature-icon"></i>8. Road Conditions</div>
                    <p>Uneven, broken, or isolated roads can cause accidents or slow emergency responses. Safer routes are those with maintained pavements, pedestrian lanes, and visibility.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-dog feature-icon"></i>9. Presence of Stray Animals</div>
                    <p>Unattended stray dogs or aggressive animals can pose a threat. Pay attention to user reports about such situations on routes.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-hands-helping feature-icon"></i>10. Community Engagement</div>
                    <p>Active community members who report, help others, and watch out for each other create a naturally safer environment.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-cloud-showers-heavy feature-icon"></i>11. Environmental Hazards</div>
                    <p>Flood-prone zones, construction areas, or regions with falling debris should be avoided. These natural or man-made hazards can amplify risks.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-hands feature-icon"></i>12. Availability of Help</div>
                    <p>Shops, cafes, and open commercial places around provide quick access to help during distress. Lonely stretches may lack immediate aid.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-volume-up feature-icon"></i>13. Sound Environment</div>
                    <p>Completely silent zones may feel unsafe. Balanced background activity (traffic, people) is more reassuring than eerie silence.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-signal feature-icon"></i>14. Mobile Connectivity</div>
                    <p>Ensure your phone has proper network access. Areas with weak signals can hinder emergency communication or tracking.</p>
                </div>

                <!-- New Unique Safety Features -->

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-user-shield feature-icon"></i>15. Presence of Security Personnel</div>
                    <p>Visible security guards or patrol officers actively monitoring the area significantly deter crime and provide immediate assistance.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-walking feature-icon"></i>16. Pedestrian-Friendly Infrastructure</div>
                    <p>Sidewalks, pedestrian crossings, traffic calming measures, and well-marked walkways improve safety for walkers by reducing accidents and confusion.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-clock feature-icon"></i>17. Average Wait Times at Crossings</div>
                    <p>Long waiting times at pedestrian signals can increase vulnerability. Areas with efficient traffic light timing offer safer, quicker street crossings.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-hand-holding-heart feature-icon"></i>18. Nearby Medical Facilities</div>
                    <p>Proximity to hospitals, clinics, or first aid centers ensures rapid medical attention if emergencies occur along the route.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-title"><i class="fas fa-eye-slash feature-icon"></i>19. Blind Spots and Visual Obstructions</div>
                    <p>Areas with obstructed views due to fences, tall bushes, or sharp corners can hide threats. Routes with clear visibility reduce surprise risks.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="plugins/jquery/jquery.min.js"></script>
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Dark/Light Mode Toggle
            const toggleBtn = document.getElementById('darkModeToggle');
            const body = document.body;

            if (toggleBtn) {
                // Load mode from localStorage
                if (localStorage.getItem('darkMode') === 'enabled') {
                    body.classList.add('dark-mode');
                    toggleBtn.innerHTML = '<i class="fas fa-sun"></i> Light Mode';
                }

                toggleBtn.addEventListener('click', () => {
                    body.classList.toggle('dark-mode');
                    if (body.classList.contains('dark-mode')) {
                        toggleBtn.innerHTML = '<i class="fas fa-sun"></i> Light Mode';
                        localStorage.setItem('darkMode', 'enabled');
                    } else {
                        toggleBtn.innerHTML = '<i class="fas fa-moon"></i> Dark Mode';
                        localStorage.setItem('darkMode', 'disabled');
                    }
                });
            }
        });
    </script>
</body>
</html>
