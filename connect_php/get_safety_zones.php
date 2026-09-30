<?php
header('Content-Type: application/json'); 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safeway";

// Corrected variable names
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$sql = "SELECT * FROM safety_zones";
$result = $conn->query($sql);

$safetyData = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $safetyData[] = [
            "name" => $row["name"],
            "coords" => [(float)$row["latitude"], (float)$row["longitude"]],
            "level" => $row["level"],
            "patrolling" => $row["patrolling"],
            "streetLighting" => $row["streetLighting"],
            "incidentReports" => $row["incidentReports"],
            "womenHelpline" => $row["womenHelpline"],
            "transportAccess" => $row["transportAccess"],
            "description" => $row["description"],
            "color" => $row["color"]
        ];
    }
}

echo json_encode($safetyData);
$conn->close();
?>
