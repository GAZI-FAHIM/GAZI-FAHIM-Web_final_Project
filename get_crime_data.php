<?php
// get_crime_data.php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'safeway';
$user = 'root';
$pass = '';

// Connect to MySQL database
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

// Get days parameter safely
$days = isset($_GET['days']) ? (int)$_GET['days'] : 30;
if ($days <= 0) $days = 30;

// Calculate cutoff date
$cutoff_date = date('Y-m-d', strtotime("-$days days"));

// Prepare and execute query
$sql = "SELECT area, latitude AS lat, longitude AS lon, incident_count AS count, incident_date AS date
        FROM crime_incidents
        WHERE incident_date >= ?
        ORDER BY incident_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $cutoff_date);
$stmt->execute();

$result = $stmt->get_result();
$crimeData = [];

while ($row = $result->fetch_assoc()) {
    // Add default type field so frontend JS doesn't break
    $row['type'] = 'Unknown';
    $crimeData[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode(['data' => $crimeData]);
?>
