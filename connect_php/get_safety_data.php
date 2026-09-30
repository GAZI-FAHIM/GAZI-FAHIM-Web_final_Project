
<?php
header('Content-Type: application/json');

// Connect to your XAMPP MySQL DB
$host = "localhost";
$username = "root";
$password = "";
$database = "safeway"; // Replace with your actual DB name

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// Query your safety data
$sql = "SELECT location, latitude, longitude, safety_rating FROM safety_ratings";
$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $data[] = $row;
  }
}

$conn->close();

echo json_encode($data);
?>
