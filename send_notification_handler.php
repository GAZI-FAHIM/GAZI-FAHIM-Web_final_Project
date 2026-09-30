<?php
session_start(); // Start session to access user_id

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("<script>alert('You must be logged in to send notifications.'); window.location.href='login.html';</script>");
}

// Database configuration
$servername = "localhost";
$username = "root"; // XAMPP default
$password = "";
$dbname = "safeway";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Sanitize & validate input
$title     = htmlspecialchars(trim($_POST['title']));
$message   = htmlspecialchars(trim($_POST['message']));
$location  = htmlspecialchars(trim($_POST['location']));
$category  = htmlspecialchars(trim($_POST['category']));
$urgency   = htmlspecialchars(trim($_POST['urgency']));
$link      = isset($_POST['link']) ? htmlspecialchars(trim($_POST['link'])) : null;

// Timestamp
$timestamp = date("Y-m-d H:i:s");

// Get user ID from session
$user_id = $_SESSION['user_id'];

// SQL to insert the notification
$sql = "INSERT INTO notifications (user_id, title, message, location, category, urgency, link, timestamp)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("isssssss", $user_id, $title, $message, $location, $category, $urgency, $link, $timestamp);

if ($stmt->execute()) {
    echo "<script>alert('Notification sent successfully!'); window.location.href='send_notifications.php';</script>";

    // 🚀 You can trigger API notifications here like sendPushNotification($title, $message, $location);
} else {
    echo "<script>alert('Error sending notification: " . $stmt->error . "'); history.back();</script>";
}

$stmt->close();
$conn->close();
?>
