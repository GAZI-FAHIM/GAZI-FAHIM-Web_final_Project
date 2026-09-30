<?php
// Connect to database
$mysqli = new mysqli("localhost", "root", "", "safeway");

// Fetch filters from GET query params
$category_filter = $_GET['category'] ?? '';
$search_query = $_GET['search'] ?? '';
$area_filter = $_GET['area'] ?? '';

// Build query
$whereClauses = [];
$params = [];
$types = '';

if ($category_filter) {
    $whereClauses[] = "category = ?";
    $params[] = $category_filter;
    $types .= 's';
}
if ($area_filter) {
    $whereClauses[] = "location LIKE ?";
    $params[] = "%$area_filter%";
    $types .= 's';
}
if ($search_query) {
    $whereClauses[] = "(name LIKE ? OR purpose LIKE ? OR description LIKE ?)";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
    $params[] = "%$search_query%";
    $types .= 'sss';
}

$whereSQL = $whereClauses ? "WHERE " . implode(" AND ", $whereClauses) : "";

$stmt = $mysqli->prepare("SELECT * FROM community_resources $whereSQL ORDER BY category, name");
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$categoriesRes = $mysqli->query("SELECT DISTINCT category FROM community_resources ORDER BY category");
$locationsRes = $mysqli->query("SELECT DISTINCT location FROM community_resources ORDER BY location");

// Map markers
$mapMarkers = [];
while ($row = $result->fetch_assoc()) {
    if (!empty($row['latitude']) && !empty($row['longitude'])) {
        $mapMarkers[] = [
            'name' => $row['name'],
            'category' => $row['category'],
            'lat' => (float)$row['latitude'],
            'lng' => (float)$row['longitude'],
            'location' => $row['location'],
            'purpose' => $row['purpose']
        ];
    }
}
$result->data_seek(0);

$badgeColors = ['primary', 'success', 'info', 'warning', 'danger', 'dark', 'secondary'];
$categoryIcons = [
    'Medical' => 'fas fa-briefcase-medical',
    'Shelter' => 'fas fa-home',
    'Legal Aid' => 'fas fa-balance-scale',
    'Food' => 'fas fa-utensils',
    'Police' => 'fas fa-shield-alt',
    'Education' => 'fas fa-book-open'
];
$categoryColorMap = [];
$colorIndex = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Community Resources - SafeWay</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS & Fonts -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap">

    <style>
  :root {
    --primary: #004085;
    --accent: #007bff;
    --light-bg: #f4f7fb;
    --dark-bg: #1c1f22;
    --white: #ffffff;
    --dark-text: #2d3436;
  }

  body {
    font-family: 'Inter', sans-serif;
    background: var(--light-bg);
    color: var(--dark-text);
    margin: 0;
  }

  /* Navbar */
  .main-header.navbar {
    background: var(--primary);
    color: white;
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.15);
  }

  .main-header .nav-link {
    color: #cfe7ff;
    font-weight: 500;
  }

  .main-header .nav-link:hover {
    color: white;
  }

  /* Sidebar */
  .main-sidebar {
    background-color: var(--dark-bg);
    color: var(--white);
  }

  .main-sidebar .brand-link {
    background-color: #22262b;
    color: white;
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

  /* Page Container */
  .content-wrapper {
    background-color: var(--light-bg);
    padding: 2rem 1rem;
  }

  .card.bg-white {
    border-radius: 14px;
    border: 1px solid #e1eaf1;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.06);
    padding: 1.5rem;
  }

  h2.text-primary {
    color: #003366 !important;
    font-weight: bold;
  }

  p.text-muted {
    font-size: 1rem;
  }

  /* Stats Cards */
  .stats-card {
    padding: 20px;
    border-radius: 16px;
    text-align: center;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07);
    transition: 0.3s ease-in-out;
  }

  .stats-card:hover {
    transform: scale(1.05);
  }

  .stats-card h3 {
    font-size: 2rem;
    font-weight: bold;
  }

  .stats-card small {
    font-size: 0.9rem;
    color: #f8f9fa;
  }

  /* Filters */
  .filter-form label {
    font-weight: 600;
    color: #003366;
  }

  .filter-form .form-control {
    border-radius: 10px;
    border: 1px solid #ccddee;
    transition: 0.2s ease;
  }

  .filter-form .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 6px rgba(0, 123, 255, 0.25);
    outline: none;
  }

  .filter-form .btn-primary {
    background-color: var(--primary);
    border-color: var(--primary);
    font-weight: 600;
  }

  .filter-form .btn-outline-secondary {
    border-radius: 8px;
  }

  /* Map */
  #map {
    height: 550px;
    border-radius: 12px;
    border: 3px solid #004085;
    margin-bottom: 25px;
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.1);
  }

  /* Table */
  .table-responsive {
    background: #ffffff;
    padding: 1rem;
    border-radius: 12px;
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
  }

  .table thead {
    background-color: #003366;
    color: white;
    font-weight: bold;
  }

  .table-striped tbody tr:hover {
    background-color: #e3f2fd;
  }

  .badge-category {
    padding: 5px 10px;
    font-size: 0.85rem;
    border-radius: 12px;
  }

  .purpose-highlight {
    font-weight: 600;
    font-size: 0.95rem;
    color: #007bff;
    cursor: help;
    text-decoration: underline dotted;
  }

  .table td a.btn-outline-success {
    font-weight: 500;
    border-radius: 8px;
  }

  @media (max-width: 768px) {
    .filter-form .form-row {
      flex-direction: column;
    }
    .form-group {
      margin-bottom: 1rem;
    }
  }
  .main-header .nav-link {
    color: white !important;
  }
  .main-header .nav-link:hover {
    color: #ffdd57 !important;
  }
</style>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark" style="background-color: #003366;">
    <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a></li>
            <li class="nav-item d-none d-sm-inline-block"><a href="dashboard.php" class="nav-link">Home</a></li>
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
        <li class="nav-item"><a href="filter_incidents.php" class="nav-link"><i class="nav-icon fas fa-exclamation-triangle"></i><p>Incidents & Hotspots</p></a></li>
        <li class="nav-item"><a href="community_resources.php" class="nav-link active"><i class="nav-icon fas fa-hands-helping"></i><p>Community Resources</p></a></li>
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
            <div class="card bg-white">
                <div class="card-body">
                    <h2 class="mb-3 text-primary"><i class="fas fa-map-marked-alt"></i> Community Resources & Help Points</h2>
                    <p class="text-muted">Explore trusted community and NGO resources for safety and well-being in your area.</p>

                    <!-- Stats -->
                    <div class="row text-white">
                        <div class="col-md-4">
                            <div class="stats-card bg-info">
                                <h3 class="count" data-count="<?= $result->num_rows ?>">0</h3>
                                <small>Total Resources</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-card bg-success">
                                <h3 class="count" data-count="<?= $categoriesRes->num_rows ?>">0</h3>
                                <small>Unique Categories</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="stats-card bg-danger">
                                <h3 class="count" data-count="<?= $locationsRes->num_rows ?>">0</h3>
                                <small>Distinct Areas</small>
                            </div>
                        </div>
                    </div>

                    <!-- Filters -->
                    <form method="GET" class="mb-4 filter-form">
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-control">
                                    <option value="">All</option>
                                    <?php $catRes = $mysqli->query("SELECT DISTINCT category FROM community_resources ORDER BY category");
                                    while($cat = $catRes->fetch_assoc()): ?>
                                        <option value="<?=htmlspecialchars($cat['category'])?>" <?=($category_filter == $cat['category']) ? 'selected' : ''?>><?=htmlspecialchars($cat['category'])?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="area">Area</label>
                                <select name="area" id="area" class="form-control">
                                    <option value="">All</option>
                                    <?php $locRes = $mysqli->query("SELECT DISTINCT location FROM community_resources ORDER BY location");
                                    while($loc = $locRes->fetch_assoc()): ?>
                                        <option value="<?=htmlspecialchars($loc['location'])?>" <?=($area_filter == $loc['location']) ? 'selected' : ''?>><?=htmlspecialchars($loc['location'])?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="search">Search</label>
                                <input type="search" name="search" id="search" class="form-control" placeholder="Search..." value="<?=htmlspecialchars($search_query)?>">
                            </div>
                            <div class="form-group col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-filter"></i> Filter</button>
                            </div>
                        </div>
                        <a href="community_resources.php" class="btn btn-sm btn-outline-secondary"><i class="fas fa-redo"></i> Reset</a>
                    </form>

                    <!-- Map -->
                    <div id="map" class="mb-4"></div>

                    <!-- Table -->
                    <?php if($result->num_rows === 0): ?>
                        <div class="alert alert-warning">No resources found matching your criteria.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table id="resourceTable" class="table table-striped table-bordered">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Location</th>
                                        <th>Purpose</th>
                                        <th>Hours</th>
                                        <th>Contact</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($res = $result->fetch_assoc()):
                                        $category = htmlspecialchars($res['category']);
                                        if (!isset($categoryColorMap[$category])) {
                                            $categoryColorMap[$category] = $badgeColors[$colorIndex % count($badgeColors)];
                                            $colorIndex++;
                                        }
                                        $badgeColor = $categoryColorMap[$category];
                                        $icon = $categoryIcons[$category] ?? 'fas fa-tag';
                                    ?>
                                        <tr>
                                            <td><?=htmlspecialchars($res['name'])?></td>
                                            <td><span class="badge badge-<?= $badgeColor ?>"><i class="<?= $icon ?>"></i> <?= $category ?></span></td>
                                            <td><?=htmlspecialchars($res['location'])?></td>
                                            <td><span class="purpose-highlight" data-toggle="tooltip" title="<?=htmlspecialchars($res['description'])?>"><?=htmlspecialchars($res['purpose'])?></span></td>
                                            <td><?=htmlspecialchars($res['hours'])?></td>
                                            <td><a href="tel:<?=htmlspecialchars($res['contact'])?>" class="btn btn-sm btn-outline-success"><i class="fas fa-phone"></i> Call</a></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>

<script>


    // Map setup
    const markersData = <?= json_encode($mapMarkers) ?>;
    const map = L.map('map').setView([23.8103, 90.4125], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);
    markersData.forEach(m => {
        L.marker([m.lat, m.lng]).addTo(map).bindPopup(`
            <strong>${m.name}</strong><br/>
            <span class="badge badge-info">${m.category}</span><br/>
            📍 ${m.location}<br/>
            <b>Purpose:</b> ${m.purpose}
        `);
    });

    // Tooltip
    $('[data-toggle="tooltip"]').tooltip();

    // Counter Animation
    document.querySelectorAll(".count").forEach(counter => {
        let end = +counter.dataset.count;
        let step = Math.ceil(end / 50);
        let count = 0;
        let update = () => {
            count += step;
            counter.innerText = count >= end ? end : count;
            if (count < end) requestAnimationFrame(update);
        };
        update();
    });

    // DataTable
    $('#resourceTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['excelHtml5', 'csvHtml5', 'pdfHtml5']
    });
</script>
</body>
</html>
