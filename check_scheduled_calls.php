<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "safeway";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Get current time
    $currentTime = date('Y-m-d H:i:s');
    
    // Get pending calls that are due
    $getDueCallsQuery = "SELECT * FROM scheduled_calls 
                        WHERE status = 'pending' 
                        AND scheduled_time <= ?";
    $stmt = $conn->prepare($getDueCallsQuery);
    $stmt->bind_param("s", $currentTime);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $dueCalls = [];
    while ($row = $result->fetch_assoc()) {
        $dueCalls[] = $row;
    }
    $stmt->close();
    
    foreach ($dueCalls as $call) {
        try {
            // Make the call using the phone number
            $phoneNumber = $call['phone_number'];
            
            // Log the call attempt
            $logQuery = "INSERT INTO call_logs (user_id, phone_number, scheduled_time, status) 
                        VALUES (?, ?, ?, 'completed')";
            $logStmt = $conn->prepare($logQuery);
            $logStmt->bind_param("iss", $call['user_id'], $call['phone_number'], $call['scheduled_time']);
            $logStmt->execute();
            $logStmt->close();
            
            // Update status to completed
            $updateQuery = "UPDATE scheduled_calls SET status = 'completed' WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("i", $call['id']);
            $updateStmt->execute();
            $updateStmt->close();
            
            // Here you would implement the actual call functionality
            // For now, we'll just log it
            error_log("Call completed successfully for ID: " . $call['id']);
            
        } catch (Exception $e) {
            error_log("Error processing call ID " . $call['id'] . ": " . $e->getMessage());
            
            // Update status to failed
            $updateQuery = "UPDATE scheduled_calls SET status = 'failed' WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("i", $call['id']);
            $updateStmt->execute();
            $updateStmt->close();
            
            // Log the failed attempt
            $logQuery = "INSERT INTO call_logs (user_id, phone_number, scheduled_time, status) 
                        VALUES (?, ?, ?, 'failed')";
            $logStmt = $conn->prepare($logQuery);
            $logStmt->bind_param("iss", $call['user_id'], $call['phone_number'], $call['scheduled_time']);
            $logStmt->execute();
            $logStmt->close();
        }
    }
    
    $conn->close();
    
} catch (Exception $e) {
    error_log("Error in check_scheduled_calls.php: " . $e->getMessage());
}
?> 

