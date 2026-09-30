<?php
header('Content-Type: application/json');  // Ensure it's treated as JSON
include 'connect.php';

$location = $_GET['location'];

$stmt = $conn->prepare("SELECT * FROM safety_data WHERE area = ?");
$stmt->bind_param("s", $location);
$stmt->execute();

$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode($data);
?>
