<?php
header('Content-Type: application/json');
$mysqli = new mysqli("localhost", "root", "", "safeway");

if ($mysqli->connect_error) {
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}

$category = $_GET['category'] ?? '';
$area = $_GET['area'] ?? '';
$date = $_GET['date'] ?? '';
$days = intval($_GET['days'] ?? 30);

$where = [];
$params = [];
$types = "";

// Filters
if ($category) {
    $where[] = "category = ?";
    $params[] = $category;
    $types .= "s";
}
if ($area) {
    $where[] = "area LIKE ?";
    $params[] = "%$area%";
    $types .= "s";
}
if ($date) {
    $where[] = "incident_date = ?";
    $params[] = $date;
    $types .= "s";
}
$where[] = "incident_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)";
$params[] = $days;
$types .= "i";

$where_sql = implode(' AND ', $where);

// Query
$sql = "SELECT id, category, area, latitude, longitude, incident_count, incident_date, description
        FROM safety_incidents
        WHERE $where_sql";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

$data = [];
while ($row = $res->fetch_assoc()) {
    $row['incident_count'] = (int)$row['incident_count'];
    $data[] = $row;
}

echo json_encode(["data" => $data]);
?>
