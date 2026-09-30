<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Using the Map Legend - SafeWay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Google Font & AdminLTE CSS -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">

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
    margin-bottom: 15px;
    display: flex;
    align-items: center;
  }

  .feature-title i {
    font-size: 1.4rem;
    margin-right: 10px;
    color: var(--accent);
  }

  .icon-legend {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    font-size: 1rem;
    color: #333;
  }

  .legend-color {
    display: inline-block;
    width: 26px;
    height: 26px;
    border-radius: 6px;
    margin-right: 12px;
    border: 2px solid #fff;
    box-shadow: 0 0 4px rgba(0, 0, 0, 0.1);
  }

  .legend-description {
    font-size: 0.95rem;
    color: #555;
    margin-top: 10px;
  }

  ul {
    padding-left: 20px;
  }

  p.mb-4 {
    font-size: 1.05rem;
    color: #555;
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
  /* Optional: Mobile responsive tweaks */
  @media (max-width: 768px) {
    .feature-title {
      font-size: 1.1rem;
    }

    .legend-color {
      width: 22px;
      height: 22px;
    }

    .feature-card {
      padding: 16px;
    }
  }






.section-nav {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.section-btn {
    background: var(--primary);
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background 0.3s ease;
    cursor: pointer;
}

.section-btn.active {
    background: var(--accent);
}

.section-btn:hover {
    background: var(--accent);
    color: #fff;
}

.feature-card {
    display: none; /* Hide all by default */
}

.feature-card.active {
    display: block; /* Show active section */
}

@media (max-width: 768px) {
    .section-nav {
        flex-direction: column;
    }
    .section-btn {
        width: 100%;
        justify-content: center;
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
        <li class="nav-item"><a href="legend_info.php" class="nav-link active"><i class="nav-icon fas fa-map"></i><p>Using the Legend</p></a></li>
        <li class="nav-item"><a href="send_notifications.php" class="nav-link"><i class="nav-icon fas fa-bell"></i><p>Send Notifications</p></a></li>
        <li class="nav-item"><a href="all_notifications.php" class="nav-link"><i class="nav-icon fas fa-bell"></i><p>All Notifications</p></a></li>
        <li class="nav-item"><a href="emergency_calls.php" class="nav-link"><i class="nav-icon fas fa-phone-alt"></i><p>Emergency Calls</p></a></li>
        <li class="nav-item"><a href="login.html" class="nav-link"><i class="nav-icon fas fa-sign-out-alt"></i><p>Logout</p></a></li>
      </ul>
    </nav>
  </div>
</aside>



<!-- Content Wrapper -->
<div class="content-wrapper">
    <!-- Page Header -->
    <section class="content-header">
        <div class="container-fluid">
            <h2>Using the Map Legend</h2>
            <p class="mb-4">
                Understand the symbols, safety factors, and tools to make your journey more secure and informed.
            </p>

            <!-- Horizontal Section Navigation -->
            <div class="section-nav mb-4">
                <a data-target="safety-factors" class="section-btn active">
                    <i class="fas fa-shield-alt"></i> Safety Factors
                </a>
                <a data-target="understanding-symbols" class="section-btn">
                    <i class="fas fa-map"></i> Understanding Symbols
                </a>
                <a data-target="safety-news" class="section-btn">
                    <i class="fas fa-newspaper"></i> Safety News
                </a>
                <a data-target="educational-videos" class="section-btn">
                    <i class="fas fa-video"></i> Educational Videos
                </a>
                <a data-target="resources-guides" class="section-btn">
                    <i class="fas fa-book"></i> Resources & Guides
                </a>
                <a data-target="interactive-learning" class="section-btn">
                    <i class="fas fa-gamepad"></i> Interactive Learning
                </a>
            </div>
        </div>
    </section>

<!-- Main Content Sections -->
<div id="safety-factors" class="feature-card active">
    <div class="feature-title"><i class="fas fa-shield-alt"></i> Safety Factors</div>
    <p>View and understand the factors influencing the safety rating of an area, including crime rate, lighting, police presence, CCTV coverage, and emergency services.</p>
    <ul>
        <li><strong>Articles / Research:</strong>
            <ul>
                <li><a href="https://www.unwomen.org/en/digital-library/publications/2011/12/safe-cities-free-of-violence-against-women" target="_blank">UN Women: Safe Cities Guide</a></li>
                <li><a href="https://www.worldbank.org/en/topic/urbandevelopment/brief/safe-cities" target="_blank">World Bank: Urban Safety Index</a></li>
                <li><a href="https://nij.ojp.gov/topics/crime-prevention" target="_blank">National Institute of Justice – Crime Prevention</a></li>
            </ul>
        </li>
        <li><strong>Local Data:</strong>
            <ul>
                <li><a href="https://www.dmp.gov.bd" target="_blank">Dhaka Metropolitan Police Crime Reports</a></li>
                <li><a href="https://data.gov.bd/" target="_blank">Bangladesh Police Open Data</a></li>
            </ul>
        </li>
        <li><strong>Tools / Apps:</strong>
            <ul>
                <li><a href="https://www.spotcrime.com/" target="_blank">SpotCrime</a></li>
                <li><a href="https://nextdoor.com/" target="_blank">Nextdoor</a></li>
                <li><a href="https://citizen.com/" target="_blank">Citizen</a></li>
            </ul>
        </li>
        <li><strong>Videos / Guides:</strong>
            <ul>
                <li>YouTube: “Factors Affecting Urban Safety”</li>
                <li>Online guide: “How to Assess Neighborhood Safety” (PDF)</li>
            </ul>
        </li>
    </ul>
</div>

<div id="understanding-symbols" class="feature-card">
    <div class="feature-title"><i class="fas fa-map"></i> Understanding Symbols</div>
    <p>Icons help you quickly identify threats, facilities, and safety alerts on the map.</p>
    <ul>
        <li><strong>Icon/Map Guides:</strong>
            <ul>
                <li><a href="https://wiki.openstreetmap.org/wiki/Map_Features" target="_blank">OpenStreetMap Symbol Guide</a></li>
                <li><a href="https://docs.mapbox.com/mapbox-gl-js/style-spec/layers/#symbol-layer" target="_blank">Mapbox Symbol Guide</a></li>
            </ul>
        </li>
        <li><strong>Common Safety Symbols:</strong>
            <ul>
                <li>Red shield: High-crime area</li>
                <li>CCTV icon: Surveillance cameras present</li>
                <li>Streetlight icon: Well-lit streets</li>
                <li>Police icon: Police station nearby</li>
            </ul>
        </li>
        <li><strong>Interactive Learning:</strong>
            <ul>
                <li><a href="https://www.sporcle.com/" target="_blank">Safety Symbol Quizzes</a></li>
                <li>YouTube: “Map Symbols Explained for Safety Apps”</li>
            </ul>
        </li>
    </ul>
</div>

<div id="safety-news" class="feature-card">
    <div class="feature-title"><i class="fas fa-newspaper"></i> Safety News</div>
    <p>Stay informed with live updates, local incidents, and global safety alerts.</p>
    <ul>
        <li><strong>Local News:</strong>
            <ul>
                <li><a href="https://www.thedailystar.net/city/news" target="_blank">The Daily Star: Crime Section</a></li>
                <li><a href="https://bangladeshchronicle.net/" target="_blank">Bangladesh Chronicle</a></li>
                <li><a href="https://www.dhakatribune.com/bangladesh/crime" target="_blank">Dhaka Tribune: Crime Updates</a></li>
            </ul>
        </li>
        <li><strong>Global Alerts:</strong>
            <ul>
                <li><a href="https://www.interpol.int/en/Crimes" target="_blank">Interpol Crime Reports</a></li>
                <li><a href="https://www.unodc.org/unodc/en/frontpage.html" target="_blank">UNODC Crime News</a></li>
            </ul>
        </li>
        <li><strong>Notifications:</strong>
            <ul>
                <li>Google Alerts: “Dhaka Crime”</li>
                <li><a href="https://play.google.com/store/apps/details?id=com.bdp.police" target="_blank">Bangladesh Police App</a></li>
            </ul>
        </li>
    </ul>
</div>

<div id="educational-videos" class="feature-card">
    <div class="feature-title"><i class="fas fa-video"></i> Educational Videos</div>
    <p>Learn important safety skills through video tutorials and demonstrations.</p>
    <ul>
        <li><strong>YouTube Channels:</strong>
            <ul>
                <li><a href="https://www.youtube.com/results?search_query=self+defense+for+women" target="_blank">Self Defense Tutorials</a></li>
                <li><a href="https://www.youtube.com/user/redcross" target="_blank">Red Cross Emergency Training</a></li>
                <li>Street Safety Tips (YouTube search)</li>
            </ul>
        </li>
        <li><strong>Courses:</strong>
            <ul>
                <li>Coursera: “Personal & Community Safety”</li>
                <li>Udemy: “Urban Safety and Awareness”</li>
                <li>Khan Academy / TED-Ed videos on safety and preparedness</li>
            </ul>
        </li>
        <li><strong>Local Programs:</strong>
            <ul>
                <li>Police-led safety workshops in Dhaka</li>
                <li>University safety training programs</li>
            </ul>
        </li>
    </ul>
</div>

<div id="resources-guides" class="feature-card">
    <div class="feature-title"><i class="fas fa-book"></i> Resources & Guides</div>
    <p>Access printable and quick-reference safety guides, checklists, and manuals.</p>
    <ul>
        <li><strong>PDF Guides:</strong>
            <ul>
                <li><a href="https://www.unwomen.org/en/digital-library/publications/2017/06/safe-city" target="_blank">UN Women: Safety Planning Guide</a></li>
                <li><a href="https://www.fema.gov/emergency-managers/practitioners" target="_blank">FEMA Emergency Preparedness Guides</a></li>
                <li><a href="https://www.brac.net/program/rights-safety/" target="_blank">BRAC Safety Manuals</a></li>
            </ul>
        </li>
        <li><strong>Apps / Tools:</strong>
            <ul>
                <li><a href="https://www.redcross.org/get-help/how-to-prepare-for-emergencies/mobile-apps.html" target="_blank">Red Cross First Aid App</a></li>
                <li>Emergency contact lists and GPS trackers</li>
            </ul>
        </li>
        <li><strong>Checklists:</strong>
            <ul>
                <li>Daily safety checklist</li>
                <li>Home safety checklist</li>
                <li>Travel safety checklist</li>
            </ul>
        </li>
    </ul>
</div>

<div id="interactive-learning" class="feature-card">
    <div class="feature-title"><i class="fas fa-gamepad"></i> Interactive Learning</div>
    <p>Test and improve your safety knowledge through games, quizzes, and simulations.</p>
    <ul>
        <li><strong>Quizzes & Games:</strong>
            <ul>
                <li><a href="https://www.cdc.gov/healthyschools/health-safety-quiz.html" target="_blank">CDC Safety Quiz</a></li>
                <li><a href="https://www.who.int/roadsafety/game" target="_blank">WHO Road Safety Game</a></li>
            </ul>
        </li>
        <li><strong>Simulations:</strong>
            <ul>
                <li>Virtual neighborhood safety assessments</li>
                <li>Interactive “Escape & Safety Strategy” games</li>
            </ul>
        </li>
        <li><strong>Apps / Platforms:</strong>
            <ul>
                <li>Kahoot! Safety Quizzes</li>
                <li>Mobile safety gamification apps for students</li>
                <li>VR simulations for personal safety (if available locally)</li>
            </ul>
        </li>
    </ul>
</div>

<!-- Tab Switching Script -->
<script>
    document.querySelectorAll('.section-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active state from all buttons
            document.querySelectorAll('.section-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Hide all feature cards
            document.querySelectorAll('.feature-card').forEach(card => card.classList.remove('active'));

            // Show the selected one
            const target = this.getAttribute('data-target');
            document.getElementById(target).classList.add('active');
        });
    });
</script>

<!-- Scripts -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>
