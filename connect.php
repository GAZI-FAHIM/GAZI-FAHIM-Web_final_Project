


<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safeway";

$conn = new mysqli($servername, $username, $password, $dbname);

// ❌ REMOVE THIS LINE IF IT EXISTS
// echo "Connected successfully";

// ✅ OPTIONAL: You can use this for error check only
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
