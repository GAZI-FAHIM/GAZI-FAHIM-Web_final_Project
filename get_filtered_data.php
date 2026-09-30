<?php
$mysqli = new mysqli("localhost", "root", "", "safeway");

$category = $_GET['category'] ?? '';
$area = $_GET['area'] ?? '';
$date = $_GET['date'] ?? '';

$query = "SELECT * FROM incidents WHERE 1=1";

if ($category) $query .= " AND category = '" . $mysqli->real_escape_string($category) . "'";
if ($area) $query .= " AND area = '" . $mysqli->real_escape_string($area) . "'";
if ($date) $query .= " AND date = '" . $mysqli->real_escape_string($date) . "'";

$result = $mysqli->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
  $data[] = $row;
}
header('Content-Type: application/json');
echo json_encode($data);
