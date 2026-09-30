<?php
header('Content-Type: application/json');

$host = 'localhost';
$db = 'safeway';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed.']));
}

$location = $_GET['location'] ?? '';
if (empty($location)) {
    echo json_encode(['error' => 'Location not provided.']);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM safety_data WHERE LOWER(area) = LOWER(?) LIMIT 1");
$stmt->bind_param("s", $location);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'area' => $row['area'],
        'lat' => $row['lat'],
        'lon' => $row['lon'],
        'safety_score' => $row['safety_score'],
        'police_stations' => json_decode($row['police_stations']),
        'weather_advisory' => $row['weather_advisory'],
        'crowd_density' => $row['crowd_density'],
        'incidents' => json_decode($row['incidents']),
        'transport_scores' => json_decode($row['transport_scores'], true)
    ]);
} else {
    echo json_encode(['error' => 'No data found for this area.']);
}

$conn->close();
?>
