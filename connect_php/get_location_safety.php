
<?php
// api/get_location_safety.php
header('Content-Type: application/json');

// Basic DB connection (adjust credentials)
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'safeway';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    echo json_encode(['error' => 'Database connection failed.']);
    exit;
}

$location = isset($_GET['location']) ? trim($_GET['location']) : '';
if ($location === '') {
    echo json_encode(['error' => 'location parameter is required']);
    exit;
}

// Try exact match first, then LIKE
$stmt = $mysqli->prepare("SELECT id, area_name, lat, lon, safety_score, hospitals, police_stations, crime_trend, weather_advisory, crowd_density, incidents, transport_scores 
                          FROM location_safety 
                          WHERE area_name = ? 
                          LIMIT 1");
$stmt->bind_param('s', $location);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

if (!$row) {
    // Fallback to LIKE search
    $like = "%".$location."%";
    $stmt2 = $mysqli->prepare("SELECT id, area_name, lat, lon, safety_score, hospitals, police_stations, crime_trend, weather_advisory, crowd_density, incidents, transport_scores 
                               FROM location_safety 
                               WHERE area_name LIKE ? 
                               ORDER BY safety_score DESC NULLS LAST
                               LIMIT 1");
    $stmt2->bind_param('s', $like);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $row = $res2->fetch_assoc();
}

// If still not found, return a soft hint
if (!$row) {
    echo json_encode([
        'error' => 'No data found for this location in database.',
        'hint'  => 'Add a row in location_safety or handle “not found” on UI.'
    ]);
    exit;
}

// Build JSON
// Normalize types & defaults
$lat = is_null($row['lat']) ? null : floatval($row['lat']);
$lon = is_null($row['lon']) ? null : floatval($row['lon']);
$safety_score = is_null($row['safety_score']) ? 0 : intval($row['safety_score']);
$hospitals = $row['hospitals'] ?: '';
$police_stations = $row['police_stations'] ?: '';
$crime_trend = $row['crime_trend'] ?: 'N/A';
$weather_advisory = $row['weather_advisory'] ?: 'N/A';
$crowd_density = $row['crowd_density'] ?: 'Unknown';
$incidents = $row['incidents'] ?: '';
$transport_scores_raw = $row['transport_scores'] ?: '{}';

// Decode transport scores JSON safely
$transport_scores = json_decode($transport_scores_raw, true);
if (!is_array($transport_scores)) $transport_scores = [];

echo json_encode([
    'id' => intval($row['id']),
    'area_name' => $row['area_name'],
    'lat' => $lat,
    'lon' => $lon,
    'safety_score' => $safety_score,
    'hospitals' => $hospitals,                 // CSV string
    'police_stations' => $police_stations,     // CSV string
    'crime_trend' => $crime_trend,
    'weather_advisory' => $weather_advisory,
    'crowd_density' => $crowd_density,
    'incidents' => $incidents,                 // CSV string
    'transport_scores' => $transport_scores    // JSON object
], JSON_UNESCAPED_UNICODE);
