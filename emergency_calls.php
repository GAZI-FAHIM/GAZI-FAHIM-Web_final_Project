<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";  // XAMPP default username
$password = "";      // XAMPP default password
$dbname = "safeway";

// Create connection with error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

// Create users table if it doesn't exist
$createUsersTableQuery = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

try {
    $conn->query($createUsersTableQuery);
} catch (Exception $e) {
    die("Error creating users table: " . $e->getMessage());
}

// Create scheduled_calls table if it doesn't exist
$createScheduledCallsTableQuery = "CREATE TABLE IF NOT EXISTS scheduled_calls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    scheduled_time DATETIME NOT NULL,
    status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

try {
    $conn->query($createScheduledCallsTableQuery);
} catch (Exception $e) {
    die("Error creating scheduled_calls table: " . $e->getMessage());
}

// Create call_logs table if it doesn't exist
$createCallLogsTableQuery = "CREATE TABLE IF NOT EXISTS call_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    scheduled_time DATETIME NOT NULL,
    status ENUM('completed', 'failed') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

try {
    $conn->query($createCallLogsTableQuery);
} catch (Exception $e) {
    die("Error creating call_logs table: " . $e->getMessage());
}

// Debug session information
echo "<!-- Debug Info: ";
print_r($_SESSION);
echo " -->";

// Verify user exists before proceeding
if (isset($_SESSION['user_id'])) {
    $checkUserQuery = "SELECT id FROM users WHERE id = ?";
    $stmt = $conn->prepare($checkUserQuery);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        $_SESSION['error'] = "User not found in database. Please log in again.";
        header("Location: login.html");
        exit();
    }
    $stmt->close();
}

// Create trustednumbers table if it doesn't exist
$createTableQuery = "CREATE TABLE IF NOT EXISTS trustednumbers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(50) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    special_note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

try {
    $conn->query($createTableQuery);
} catch (Exception $e) {
    die("Error creating table: " . $e->getMessage());
}

// Create emergency_numbers table if it doesn't exist
$createEmergencyTableQuery = "CREATE TABLE IF NOT EXISTS emergency_numbers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

try {
    $conn->query($createEmergencyTableQuery);
} catch (Exception $e) {
    die("Error creating emergency_numbers table: " . $e->getMessage());
}

// Create message_history table if it doesn't exist
$createMessageHistoryTableQuery = "CREATE TABLE IF NOT EXISTS message_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message_text TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

try {
    $conn->query($createMessageHistoryTableQuery);
} catch (Exception $e) {
    die("Error creating message_history table: " . $e->getMessage());
}

// Create medical_info table if it doesn't exist
$createMedicalInfoTableQuery = "CREATE TABLE IF NOT EXISTS medical_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    blood_type VARCHAR(10),
    allergies TEXT,
    medical_conditions TEXT,
    medications TEXT,
    emergency_contact_name VARCHAR(100),
    emergency_contact_phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

try {
    $conn->query($createMedicalInfoTableQuery);
} catch (Exception $e) {
    die("Error creating medical_info table: " . $e->getMessage());
}

// Get user's role
$userRole = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Get trusted numbers for the current user
$trustedNumbers = [];
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $getNumbersQuery = "SELECT * FROM trustednumbers WHERE user_id = ?";
    $stmt = $conn->prepare($getNumbersQuery);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $trustedNumbers[] = $row;
    }
}

// Get emergency numbers
$emergencyNumbers = [];
$getEmergencyQuery = "SELECT * FROM emergency_numbers ORDER BY title";
$result = $conn->query($getEmergencyQuery);
while ($row = $result->fetch_assoc()) {
    $emergencyNumbers[] = $row;
}

// Get user's medical info if exists
$medicalInfo = null;
if (isset($_SESSION['user_id']) && $userRole !== 'Admin') {
    $getMedicalInfoQuery = "SELECT * FROM medical_info WHERE user_id = ?";
    $stmt = $conn->prepare($getMedicalInfoQuery);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $medicalInfo = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_trusted_number'])) {
    if (isset($_SESSION['user_id'])) {
        // Debug POST data
        echo "<!-- POST Data: ";
        print_r($_POST);
        echo " -->";
        
        // Validate and sanitize input
        $title = trim($_POST['title']);
        $phoneNumber = trim($_POST['phone_number']);
        $specialNote = trim($_POST['special_note']);
        
        // Basic validation
        if (empty($title) || empty($phoneNumber)) {
            $_SESSION['error'] = "Title and phone number are required fields.";
        } else {
            try {
                // Prepare the insert statement
                $insertQuery = "INSERT INTO trustednumbers (user_id, title, phone_number, special_note) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                
                if ($stmt) {
                    $stmt->bind_param("isss", $_SESSION['user_id'], $title, $phoneNumber, $specialNote);
                    
                    if ($stmt->execute()) {
                        $_SESSION['success'] = "Trusted number added successfully!";
                        // Debug successful insertion
                        echo "<!-- Insert successful. Last insert ID: " . $conn->insert_id . " -->";
                    } else {
                        $_SESSION['error'] = "Error adding trusted number: " . $stmt->error;
                        // Debug statement error
                        echo "<!-- Statement error: " . $stmt->error . " -->";
                    }
                    
                    $stmt->close();
                } else {
                    $_SESSION['error'] = "Error preparing statement: " . $conn->error;
                    // Debug connection error
                    echo "<!-- Connection error: " . $conn->error . " -->";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Database error: " . $e->getMessage();
                // Debug exception
                echo "<!-- Exception: " . $e->getMessage() . " -->";
            }
        }
        
        // Redirect back to the same page
        header("Location: emergency_calls.php");
        exit();
    } else {
        $_SESSION['error'] = "User not logged in. Please log in to add trusted numbers.";
    }
}

// Handle emergency number form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_emergency_number'])) {
    if ($userRole === 'Admin') {
        $title = trim($_POST['title']);
        $phoneNumber = trim($_POST['phone_number']);
        $description = trim($_POST['description']);
        
        if (empty($title) || empty($phoneNumber)) {
            $_SESSION['error'] = "Title and phone number are required fields.";
        } else {
            try {
                $insertQuery = "INSERT INTO emergency_numbers (title, phone_number, description) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                
                if ($stmt) {
                    $stmt->bind_param("sss", $title, $phoneNumber, $description);
                    
                    if ($stmt->execute()) {
                        $_SESSION['success'] = "Emergency number added successfully!";
                    } else {
                        $_SESSION['error'] = "Error adding emergency number: " . $stmt->error;
                    }
                    
                    $stmt->close();
                } else {
                    $_SESSION['error'] = "Error preparing statement: " . $conn->error;
                }
            } catch (Exception $e) {
                $_SESSION['error'] = "Database error: " . $e->getMessage();
            }
        }
        
        header("Location: emergency_calls.php");
        exit();
    } else {
        $_SESSION['error'] = "Unauthorized access. Admin privileges required.";
    }
}

// Handle emergency message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send_emergency_message'])) {
    if (isset($_SESSION['user_id'])) {
        $messageType = $_POST['message_type'];
        $userId = $_SESSION['user_id'];
        $sendMethod = $_POST['send_method'] ?? '';
        
        // Get user's current location
        if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
            $lat = $_POST['latitude'];
            $lon = $_POST['longitude'];
            $locationUrl = "https://www.google.com/maps?q={$lat},{$lon}";
        } else {
            $locationUrl = "Location not available";
        }
        
        // Create message based on type
        $messageText = "";
        if ($messageType === 'help') {
            $messageText = "I need help! My current location: {$locationUrl}";
        } else if ($messageType === 'safe') {
            $messageText = "I am safe. My current location: {$locationUrl}";
        }
        
        // Store message in history
        try {
            $insertMessageQuery = "INSERT INTO message_history (user_id, message_text) VALUES (?, ?)";
            $stmt = $conn->prepare($insertMessageQuery);
            $stmt->bind_param("is", $userId, $messageText);
            $stmt->execute();
            $messageId = $conn->insert_id;
            $stmt->close();
            
            // Get trusted numbers for the user
            $getNumbersQuery = "SELECT phone_number FROM trustednumbers WHERE user_id = ?";
            $stmt = $conn->prepare($getNumbersQuery);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $sentCount = 0;
            while ($row = $result->fetch_assoc()) {
                $phoneNumber = $row['phone_number'];
                
                if ($sendMethod === 'whatsapp') {
                    // WhatsApp sharing link
                    $whatsappLink = "https://wa.me/?text=" . urlencode($messageText);
                    // You can redirect to WhatsApp or open in new tab
                    header("Location: " . $whatsappLink);
                    exit();
                } else if ($sendMethod === 'sms') {
                    // SMS sharing link
                    $smsLink = "sms:{$phoneNumber}?body=" . urlencode($messageText);
                    // You can redirect to SMS or open in new tab
                    header("Location: " . $smsLink);
                    exit();
                }
                
                $sentCount++;
            }
            
            $_SESSION['success'] = "Message sent to {$sentCount} trusted contacts via {$sendMethod}!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error sending message: " . $e->getMessage();
        }
        
        header("Location: emergency_calls.php");
        exit();
    }
}

// Handle panic button message sending
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_type']) && $_POST['message_type'] === 'panic') {
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        
        // Get user's current location
        if (isset($_POST['latitude']) && isset($_POST['longitude'])) {
            $lat = $_POST['latitude'];
            $lon = $_POST['longitude'];
            $locationUrl = "https://www.google.com/maps?q={$lat},{$lon}";
        } else {
            $locationUrl = "Location not available";
        }
        
        // Create panic message
        $messageText = "EMERGENCY! I need immediate help! My current location: {$locationUrl}";
        
        // Store message in history
        try {
            $insertMessageQuery = "INSERT INTO message_history (user_id, message_text) VALUES (?, ?)";
            $stmt = $conn->prepare($insertMessageQuery);
            $stmt->bind_param("is", $userId, $messageText);
            $stmt->execute();
            $messageId = $conn->insert_id;
            $stmt->close();
            
            // Get mother contacts from trusted numbers
            $getMotherContactsQuery = "SELECT phone_number FROM trustednumbers 
                                     WHERE user_id = ? AND LOWER(title) IN ('mother', 'mothers')";
            $stmt = $conn->prepare($getMotherContactsQuery);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            $sentCount = 0;
            $motherNumbers = [];
            while ($row = $result->fetch_assoc()) {
                $motherNumbers[] = $row['phone_number'];
                $sentCount++;
            }
            
            if ($sentCount > 0) {
                // Send via WhatsApp
                $whatsappLink = "https://wa.me/?text=" . urlencode($messageText);
                header("Location: " . $whatsappLink);
                exit();
            } else {
                $_SESSION['error'] = "No mother contacts found in your trusted numbers.";
            }
            
        } catch (Exception $e) {
            $_SESSION['error'] = "Error sending panic message: " . $e->getMessage();
        }
        
        header("Location: emergency_calls.php");
        exit();
    }
}

// Handle medical info form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_medical_info'])) {
    if (isset($_SESSION['user_id']) && $userRole !== 'Admin') {
        $bloodType = trim($_POST['blood_type']);
        $allergies = trim($_POST['allergies']);
        $medicalConditions = trim($_POST['medical_conditions']);
        $medications = trim($_POST['medications']);
        $emergencyContactName = trim($_POST['emergency_contact_name']);
        $emergencyContactPhone = trim($_POST['emergency_contact_phone']);
        
        try {
            if ($medicalInfo) {
                // Update existing medical info
                $updateQuery = "UPDATE medical_info SET 
                    blood_type = ?, 
                    allergies = ?, 
                    medical_conditions = ?, 
                    medications = ?, 
                    emergency_contact_name = ?, 
                    emergency_contact_phone = ? 
                    WHERE user_id = ?";
                $stmt = $conn->prepare($updateQuery);
                $stmt->bind_param("ssssssi", 
                    $bloodType, 
                    $allergies, 
                    $medicalConditions, 
                    $medications, 
                    $emergencyContactName, 
                    $emergencyContactPhone, 
                    $_SESSION['user_id']
                );
            } else {
                // Insert new medical info
                $insertQuery = "INSERT INTO medical_info (
                    user_id, blood_type, allergies, medical_conditions, 
                    medications, emergency_contact_name, emergency_contact_phone
                ) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("issssss", 
                    $_SESSION['user_id'], 
                    $bloodType, 
                    $allergies, 
                    $medicalConditions, 
                    $medications, 
                    $emergencyContactName, 
                    $emergencyContactPhone
                );
            }
            
            if ($stmt->execute()) {
                $_SESSION['success'] = "Medical information saved successfully!";
            } else {
                $_SESSION['error'] = "Error saving medical information: " . $stmt->error;
            }
            
            $stmt->close();
        } catch (Exception $e) {
            $_SESSION['error'] = "Error saving medical information: " . $e->getMessage();
        }
        
        header("Location: emergency_calls.php");
        exit();
    }
}

// Handle scheduled call form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['schedule_call'])) {
    if (isset($_SESSION['user_id'])) {
        $phoneNumber = trim($_POST['phone_number']);
        $scheduledTime = trim($_POST['scheduled_time']);
        
        // Debug information
        error_log("Attempting to schedule call - Phone: $phoneNumber, Time: $scheduledTime");
        
        if (empty($phoneNumber) || empty($scheduledTime)) {
            $_SESSION['error'] = "Phone number and scheduled time are required.";
        } else {
            try {
                // Remove any spaces or special characters from the phone number
                $phoneNumber = preg_replace('/[^0-9+]/', '', $phoneNumber);
                
                // Very lenient validation - just check if it's not empty
                if (empty($phoneNumber)) {
                    throw new Exception("Phone number is required");
                }
                
                // Validate scheduled time is in the future
                $scheduledDateTime = new DateTime($scheduledTime);
                $currentDateTime = new DateTime();
                if ($scheduledDateTime <= $currentDateTime) {
                    throw new Exception("Scheduled time must be in the future");
                }
                
                // Check if the scheduled time is within 24 hours
                $maxScheduledTime = (new DateTime())->modify('+24 hours');
                if ($scheduledDateTime > $maxScheduledTime) {
                    throw new Exception("Scheduled time cannot be more than 24 hours in the future");
                }
                
                // Check for existing pending calls
                $checkPendingQuery = "SELECT COUNT(*) as count FROM scheduled_calls 
                                    WHERE user_id = ? AND status = 'pending'";
                $checkStmt = $conn->prepare($checkPendingQuery);
                $checkStmt->bind_param("i", $_SESSION['user_id']);
                $checkStmt->execute();
                $result = $checkStmt->get_result();
                $row = $result->fetch_assoc();
                
                if ($row['count'] >= 3) {
                    throw new Exception("You can only have 3 pending scheduled calls at a time");
                }
                
                $insertQuery = "INSERT INTO scheduled_calls (user_id, phone_number, scheduled_time) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("iss", $_SESSION['user_id'], $phoneNumber, $scheduledTime);
                
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Call scheduled successfully for " . date('Y-m-d H:i', strtotime($scheduledTime));
                    error_log("Call scheduled successfully - ID: " . $conn->insert_id);
                } else {
                    throw new Exception("Error scheduling call: " . $stmt->error);
                }
                
                $stmt->close();
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                error_log("Error scheduling call: " . $e->getMessage());
            }
        }
        
        header("Location: emergency_calls.php");
        exit();
    }
}

// Get user's scheduled calls
$scheduledCalls = [];
if (isset($_SESSION['user_id'])) {
    $getScheduledCallsQuery = "SELECT * FROM scheduled_calls WHERE user_id = ? AND status = 'pending' ORDER BY scheduled_time ASC";
    $stmt = $conn->prepare($getScheduledCallsQuery);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $scheduledCalls[] = $row;
    }
    $stmt->close();
}

// Display success/error messages if they exist
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 300px;">
            ' . $_SESSION['success'] . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>';
    unset($_SESSION['success']);
}

if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; max-width: 300px;">
            ' . $_SESSION['error'] . '
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
          </div>';
    unset($_SESSION['error']);
}

// Include the check for scheduled calls
require_once 'check_scheduled_calls.php';
?>














<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Emergency & Trusted Contacts - SafeWay</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Google Font & AdminLTE CSS -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  
  
  <style>
  /* ------------------------------------------------------
     ✅ GENERAL LAYOUT & COLOR THEME STYLES
  ---------------------------------------------------------*/
  body {
    font-family: 'Source Sans Pro', sans-serif;
    background-color: #f9fafe;
  }

  .content-wrapper {
    background: #fdfefe;
    padding: 40px 35px 60px;
    min-height: calc(100vh - 56px);
  }

  /* ------------------------------------------------------
     ✅ NAVBAR STYLING
  ---------------------------------------------------------*/
  .main-header.navbar {
    background: #004085;
    color: white;
    font-weight: 600;
    box-shadow: 0 3px 8px rgba(0, 64, 133, 0.3);
  }

  .main-header.navbar .nav-link,
  .main-header.navbar .nav-icon {
    color: #cce0ff;
    transition: color 0.3s ease;
  }

  .main-header.navbar .nav-link:hover {
    color: #ffffff;
  }

  /* ------------------------------------------------------
     ✅ SIDEBAR STYLING
  ---------------------------------------------------------*/
  .main-sidebar {
    background-color: rgb(28, 31, 34);
    color: #ffffff;
  }

  .main-sidebar .brand-link {
    background-color: rgb(34, 38, 43);
    color: #ffffff;
    font-weight: bold;
    border-bottom: 1px solid #003366;
  }

  .main-sidebar .nav-link {
    color: #cfd9ff;
    font-weight: 500;
    transition: all 0.3s ease;
  }

  .main-sidebar .nav-link .nav-icon {
    color: #a0b8ff;
  }

  .main-sidebar .nav-link.active,
  .main-sidebar .nav-link:hover {
    background-color: #e6f0ff;
    color: #001f3f;
    font-weight: bold;
    border-radius: 8px;
  }

  .main-sidebar .nav-link.active .nav-icon,
  .main-sidebar .nav-link:hover .nav-icon {
    color: #001f3f;
  }

  /* ------------------------------------------------------
     ✅ BUTTON STYLES
  ---------------------------------------------------------*/
  .btn-panic {
    background-color: #dc3545;
    color: #fff;
    font-size: 1.2rem;
    padding: 15px;
    border: none;
    border-radius: 10px;
    width: 100%;
    margin-bottom: 15px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .btn-panic:hover {
    background-color: #c82333;
  }

  /* ------------------------------------------------------
     ✅ CALL BUTTON STYLING
  ---------------------------------------------------------*/
  .call-button {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 15px;
    transition: background-color 0.2s ease;
    cursor: pointer;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  }

  .call-button:hover {
    background-color: #e2e6ea;
  }

  .call-icon {
    font-size: 1.8rem;
    margin-right: 10px;
    color: #007bff;
  }

  /* ------------------------------------------------------
     ✅ QR CODE BOX STYLE
  ---------------------------------------------------------*/
  .qr-box {
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 10px;
    text-align: center;
    padding: 15px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.1);
  }

  /* ------------------------------------------------------
     ✅ VOICE NOTE TEXT STYLE
  ---------------------------------------------------------*/
  .voice-note {
    font-style: italic;
    color: #555;
    margin-top: 8px;
  }

  /* ------------------------------------------------------
     ✅ SECTION TITLE STYLE
  ---------------------------------------------------------*/
  .section-title {
    font-size: 1.2rem;
    font-weight: bold;
    margin-top: 30px;
    margin-bottom: 15px;
    color: #003366;
    letter-spacing: 0.05em;
    text-transform: uppercase;
  }

  /* ------------------------------------------------------
     ✅ INFO BOX STYLE
  ---------------------------------------------------------*/
  .info-box {
    background-color: #e9ecef;
    padding: 15px;
    border-radius: 10px;
    margin-bottom: 15px;
    box-shadow: inset 0 0 5px rgba(0,0,0,0.05);
    color: #333;
  }

  /* ------------------------------------------------------
     ✅ FORM CONTROL SPACING
  ---------------------------------------------------------*/
  .form-control {
    margin-bottom: 10px;
  }

  /* ------------------------------------------------------
     ✅ HEADER STYLE
  ---------------------------------------------------------*/
  header {
    background-color: var(--primary);
    color: white;
    padding: 1rem;
    text-align: center;
    font-size: 1.7rem;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 3px 6px rgba(0,64,133,0.4);
  }

  /* ------------------------------------------------------
     ✅ SHARE BUTTONS CONTAINER & BUTTONS
  ---------------------------------------------------------*/
  .share-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 15px;
  }

  .share-button {
    padding: 8px 15px;
    border-radius: 5px;
    text-decoration: none;
    color: white;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .share-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
  }

  .share-button i {
    margin-right: 5px;
  }

  /* ------------------------------------------------------
     ✅ VOICE COMMAND CONTAINER & BUTTON
  ---------------------------------------------------------*/
  .voice-command-container {
    padding: 20px;
    text-align: center;
  }

  #voiceCommandBtn {
    width: 200px;
    height: 200px;
    border-radius: 50%;
    font-size: 1.2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.5);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }

  #voiceCommandBtn:hover {
    background-color: #0056b3;
    box-shadow: 0 6px 15px rgba(0, 86, 179, 0.7);
  }

  #voiceCommandBtn i {
    font-size: 2rem;
    margin-bottom: 10px;
  }

  #voiceStatus {
    min-height: 50px;
    margin-top: 15px;
    color: #444;
    font-weight: 600;
  }

  /* ------------------------------------------------------
     ✅ RESPONSIVE TWEAKS FOR SMALL SCREENS
  ---------------------------------------------------------*/
  @media (max-width: 576px) {
    .call-button {
      flex-direction: column;
      align-items: flex-start;
      gap: 10px;
    }

    .voice-command-container {
      padding: 10px;
    }

    #voiceCommandBtn {
      width: 150px;
      height: 150px;
      font-size: 1rem;
    }

    #voiceCommandBtn i {
      font-size: 1.5rem;
      margin-bottom: 6px;
    }
  }

  .emergency-header {
  margin: 40px 0 30px;
  padding: 20px;
  background: linear-gradient(90deg, #6d4accff, #460879ff);
  border-radius: 16px;
  box-shadow: 0 6px 20px rgba(255, 65, 108, 0.2);
  color: #fff;
  animation: fadeIn 1s ease-out;
}

.emergency-title {
  font-size: 2.2rem;
  font-weight: 800;
  margin-bottom: 10px;
  color: #fff;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
}

.emergency-subtext {
  font-size: 1.1rem;
  font-weight: 400;
  margin: 0;
  color: #fff;
  opacity: 0.95;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

</style>











</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a></li>
      <li class="nav-item d-none d-sm-inline-block"><a href="#" class="nav-link">Home</a></li>
    </ul>
  </nav>

  

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="dashboard.php" class="brand-link">
    <img src="dist/img/AdminLTELogo.png" alt="Logo" class="brand-image img-circle elevation-3">
    <span class="brand-text font-weight-light">SafeWay</span>
  </a>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
        <li class="nav-item"><a href="dashboard.php" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
        <li class="nav-item"><a href="location_search.php" class="nav-link"><i class="nav-icon fas fa-shield-alt"></i><p>Safety Control Center</p></a></li>
        <li class="nav-item"><a href="safety_unified.php" class="nav-link"><i class="nav-icon fas fa-map-marked-alt"></i><p>Unified Safety Explorer</p></a></li>
        <li class="nav-item"><a href="identify_routes.php" class="nav-link"><i class="nav-icon fas fa-route"></i><p>Identify Safer Routes</p></a></li>
        <li class="nav-item"><a href="filter_incidents.php" class="nav-link"><i class="nav-icon fas fa-exclamation-triangle"></i><p>Incidents & Hotspots</p></a></li>
        <li class="nav-item"><a href="community_resources.php" class="nav-link"><i class="nav-icon fas fa-hands-helping"></i><p>Community Resources</p></a></li>
        <li class="nav-item"><a href="legend_info.php" class="nav-link "><i class="nav-icon fas fa-map"></i><p>Using the Legend</p></a></li>
        <li class="nav-item"><a href="send_notifications.php" class="nav-link"><i class="nav-icon fas fa-bell"></i><p>Send Notifications</p></a></li>
        <li class="nav-item"><a href="all_notifications.php" class="nav-link"><i class="nav-icon fas fa-bell"></i><p>All Notifications</p></a></li>
        <li class="nav-item"><a href="emergency_calls.php" class="nav-link active"><i class="nav-icon fas fa-phone-alt"></i><p>Emergency Calls</p></a></li>
        <li class="nav-item"><a href="login.html" class="nav-link"><i class="nav-icon fas fa-sign-out-alt"></i><p>Logout</p></a></li>
      </ul>
    </nav>
  </div>
</aside>





















  <!-- Main Content -->
  <div class="content-wrapper p-3">
    <div class="container-fluid">
<div class="emergency-header text-center">
  <h2 class="emergency-title">
    🚨 Emergency & Trusted Calls
  </h2>
  <p class="emergency-subtext">
    Reach help instantly — whether it's authorities or someone you trust.
  </p>
</div>

      <!-- Emergency Services -->
      <div class="section-title">Emergency Services</div>
      <?php foreach ($emergencyNumbers as $number): ?>
      <div class="call-button bg-danger text-white" onclick="window.location.href='tel:<?php echo htmlspecialchars($number['phone_number']); ?>'">
          <div>
              <i class="fas fa-shield-alt call-icon"></i> 
              <?php echo htmlspecialchars($number['title']); ?>
              <br>
              <small class="text-light">
                  <i class="fas fa-phone-alt"></i> <?php echo htmlspecialchars($number['phone_number']); ?>
                  <?php if (!empty($number['description'])): ?>
                      <br><i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($number['description']); ?>
                  <?php endif; ?>
              </small>
          </div>
          <i class="fas fa-phone"></i>
      </div>
      <?php endforeach; ?>

      <?php if ($userRole === 'Admin'): ?>
      <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addEmergencyNumberModal">
          <i class="fas fa-plus"></i> Add Emergency Number
      </button>
      <?php endif; ?>

      <!-- Add Emergency Number Modal -->
      <div class="modal fade" id="addEmergencyNumberModal" tabindex="-1" role="dialog" aria-labelledby="addEmergencyNumberModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="addEmergencyNumberModalLabel">Add Emergency Number</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <form method="POST" action="">
                      <div class="modal-body">
                          <div class="form-group">
                              <label for="emergency_title">Title (e.g., Police, Fire Service)</label>
                              <input type="text" class="form-control" id="emergency_title" name="title" required>
                          </div>
                          <div class="form-group">
                              <label for="emergency_phone">Phone Number</label>
                              <input type="tel" class="form-control" id="emergency_phone" name="phone_number" required>
                          </div>
                          <div class="form-group">
                              <label for="emergency_description">Description (Optional)</label>
                              <textarea class="form-control" id="emergency_description" name="description" rows="2"></textarea>
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" name="add_emergency_number" class="btn btn-primary">Add Emergency Number</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>

      <!-- Trusted Contacts -->
      <div class="section-title">Trusted Contacts</div>
      
      <?php if ($userRole !== 'Admin'): ?>
      <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addTrustedNumberModal">
        <i class="fas fa-plus"></i> Add Trusted Number
      </button>
      <?php endif; ?>

      <?php if (empty($trustedNumbers)): ?>
        <div class="alert alert-info">
          No trusted contacts added yet. Add your trusted contacts to get started.
        </div>
      <?php else: ?>
        <?php foreach ($trustedNumbers as $contact): ?>
          <div class="call-button" onclick="window.location.href='tel:<?php echo htmlspecialchars($contact['phone_number']); ?>'">
            <div>
              <i class="fas fa-user-shield call-icon"></i> 
              <?php echo htmlspecialchars($contact['title']); ?>
              <?php if (!empty($contact['special_note'])): ?>
                <small class="text-muted">(<?php echo htmlspecialchars($contact['special_note']); ?>)</small>
              <?php endif; ?>
            </div>
            <i class="fas fa-phone text-success"></i>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

      <!-- Add Trusted Number Modal -->
      <div class="modal fade" id="addTrustedNumberModal" tabindex="-1" role="dialog" aria-labelledby="addTrustedNumberModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="addTrustedNumberModalLabel">Add Trusted Number</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="">
              <div class="modal-body">
                <div class="form-group">
                  <label for="title">Title (e.g., Mother, Father, Brother)</label>
                  <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="form-group">
                  <label for="phone_number">Phone Number</label>
                  <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                </div>
                <div class="form-group">
                  <label for="special_note">Special Note (Optional)</label>
                  <textarea class="form-control" id="special_note" name="special_note" rows="2"></textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="add_trusted_number" class="btn btn-primary">Add Contact</button>
              </div>
            </form>
          </div>
        </div>
      </div>

    <!-- Live Location Sharing -->
    <div class="card p-4">
      <div class="section-title">📍 Live Location Sharing</div>
      <p>Share your real-time location with emergency contacts to help them find you quickly.</p>
      <button class="btn btn-primary" onclick="shareLocation()">Share My Location</button>
      
      <!-- Sharing Options (Initially Hidden) -->
      <div id="sharingOptions" class="mt-3" style="display: none;">
        <div class="alert alert-info">
          <p id="locationMessage" class="mb-2"></p>
          <div class="share-buttons">
            <a id="whatsappShare" class="share-button" style="background-color: #25D366;" target="_blank">
              <i class="fab fa-whatsapp"></i> Share on WhatsApp
            </a>
            <a id="messengerShare" class="share-button" style="background-color: #0084FF;" target="_blank">
              <i class="fab fa-facebook-messenger"></i> Share on Messenger
            </a>
            <a id="smsShare" class="share-button" style="background-color: #6c757d;" target="_blank">
              <i class="fas fa-sms"></i> Share via SMS
            </a>
            <button class="share-button" style="background-color: #17a2b8;" onclick="copyLocationLink()">
              <i class="fas fa-copy"></i> Copy Link
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Emergency Message Templates -->
    <div class="card p-4">
      <div class="section-title">✉️ Emergency Message Templates</div>
      <p>Quickly send pre-written messages during emergencies.</p>
      
      <!-- Message Type Selection -->
      <div class="mb-3">
        <button type="button" class="btn btn-danger mb-2" onclick="prepareEmergencyMessage('help')">
          <i class="fas fa-exclamation-triangle"></i> Send: I need help
        </button>
        <button type="button" class="btn btn-success" onclick="prepareEmergencyMessage('safe')">
          <i class="fas fa-check-circle"></i> Send: I am safe
        </button>
      </div>

      <!-- Sending Options Modal -->
      <div class="modal fade" id="sendingOptionsModal" tabindex="-1" role="dialog" aria-labelledby="sendingOptionsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="sendingOptionsModalLabel">Choose Sending Method</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form method="POST" action="" id="emergencyMessageForm">
                <input type="hidden" name="latitude" id="messageLatitude">
                <input type="hidden" name="longitude" id="messageLongitude">
                <input type="hidden" name="message_type" id="messageType">
                <input type="hidden" name="send_emergency_message" value="1">
                
                <div class="form-group">
                  <label>Select sending method:</label>
                  <div class="btn-group-vertical w-100">
                    <button type="submit" name="send_method" value="whatsapp" class="btn btn-success mb-2">
                      <i class="fab fa-whatsapp"></i> Send via WhatsApp
                    </button>
                    <button type="submit" name="send_method" value="sms" class="btn btn-primary">
                      <i class="fas fa-sms"></i> Send via SMS
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Panic Button -->
    <div class="card p-4 text-center">
      <div class="section-title">🚨 Panic Button</div>
      <button class="btn-panic" onclick="activatePanicButton()">
        <i class="fas fa-exclamation-triangle"></i> Panic Button
      </button>
    </div>

    <!-- Medical ID Display -->
    <div class="card p-4">
      <div class="section-title">🩺 Medical ID</div>
      
      <?php if ($userRole !== 'Admin'): ?>
        <?php if ($medicalInfo): ?>
          <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#editMedicalInfoModal">
            <i class="fas fa-edit"></i> Edit Medical Info
          </button>
        <?php else: ?>
          <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#addMedicalInfoModal">
            <i class="fas fa-plus"></i> Add Medical Info
          </button>
        <?php endif; ?>
      <?php endif; ?>

      <?php if ($medicalInfo): ?>
        <div class="medical-info-display">
          <p><strong>Blood Type:</strong> <?php echo htmlspecialchars($medicalInfo['blood_type'] ?? 'Not specified'); ?></p>
          <p><strong>Allergies:</strong> <?php echo htmlspecialchars($medicalInfo['allergies'] ?? 'None'); ?></p>
          <p><strong>Medical Conditions:</strong> <?php echo htmlspecialchars($medicalInfo['medical_conditions'] ?? 'None'); ?></p>
          <p><strong>Current Medications:</strong> <?php echo htmlspecialchars($medicalInfo['medications'] ?? 'None'); ?></p>
          <p><strong>Emergency Contact:</strong> 
            <?php echo htmlspecialchars($medicalInfo['emergency_contact_name'] ?? 'Not specified'); ?> 
            (<?php echo htmlspecialchars($medicalInfo['emergency_contact_phone'] ?? 'Not specified'); ?>)
          </p>
        </div>
      <?php else: ?>
        <p class="text-muted">No medical information available.</p>
      <?php endif; ?>
    </div>

    <!-- Add Medical Info Modal -->
    <div class="modal fade" id="addMedicalInfoModal" tabindex="-1" role="dialog" aria-labelledby="addMedicalInfoModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addMedicalInfoModalLabel">Add Medical Information</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="POST" action="">
            <div class="modal-body">
              <div class="form-group">
                <label for="blood_type">Blood Type</label>
                <select class="form-control" id="blood_type" name="blood_type" required>
                  <option value="">Select Blood Type</option>
                  <option value="A+">A+</option>
                  <option value="A-">A-</option>
                  <option value="B+">B+</option>
                  <option value="B-">B-</option>
                  <option value="AB+">AB+</option>
                  <option value="AB-">AB-</option>
                  <option value="O+">O+</option>
                  <option value="O-">O-</option>
                </select>
              </div>
              <div class="form-group">
                <label for="allergies">Allergies</label>
                <textarea class="form-control" id="allergies" name="allergies" rows="2" placeholder="List any allergies"></textarea>
              </div>
              <div class="form-group">
                <label for="medical_conditions">Medical Conditions</label>
                <textarea class="form-control" id="medical_conditions" name="medical_conditions" rows="2" placeholder="List any medical conditions"></textarea>
              </div>
              <div class="form-group">
                <label for="medications">Current Medications</label>
                <textarea class="form-control" id="medications" name="medications" rows="2" placeholder="List current medications"></textarea>
              </div>
              <div class="form-group">
                <label for="emergency_contact_name">Emergency Contact Name</label>
                <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" required>
              </div>
              <div class="form-group">
                <label for="emergency_contact_phone">Emergency Contact Phone</label>
                <input type="tel" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" name="save_medical_info" class="btn btn-primary">Save Information</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit Medical Info Modal -->
    <div class="modal fade" id="editMedicalInfoModal" tabindex="-1" role="dialog" aria-labelledby="editMedicalInfoModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editMedicalInfoModalLabel">Edit Medical Information</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="POST" action="">
            <div class="modal-body">
              <div class="form-group">
                <label for="edit_blood_type">Blood Type</label>
                <select class="form-control" id="edit_blood_type" name="blood_type" required>
                  <option value="">Select Blood Type</option>
                  <option value="A+" <?php echo ($medicalInfo['blood_type'] ?? '') === 'A+' ? 'selected' : ''; ?>>A+</option>
                  <option value="A-" <?php echo ($medicalInfo['blood_type'] ?? '') === 'A-' ? 'selected' : ''; ?>>A-</option>
                  <option value="B+" <?php echo ($medicalInfo['blood_type'] ?? '') === 'B+' ? 'selected' : ''; ?>>B+</option>
                  <option value="B-" <?php echo ($medicalInfo['blood_type'] ?? '') === 'B-' ? 'selected' : ''; ?>>B-</option>
                  <option value="AB+" <?php echo ($medicalInfo['blood_type'] ?? '') === 'AB+' ? 'selected' : ''; ?>>AB+</option>
                  <option value="AB-" <?php echo ($medicalInfo['blood_type'] ?? '') === 'AB-' ? 'selected' : ''; ?>>AB-</option>
                  <option value="O+" <?php echo ($medicalInfo['blood_type'] ?? '') === 'O+' ? 'selected' : ''; ?>>O+</option>
                  <option value="O-" <?php echo ($medicalInfo['blood_type'] ?? '') === 'O-' ? 'selected' : ''; ?>>O-</option>
                </select>
              </div>
              <div class="form-group">
                <label for="edit_allergies">Allergies</label>
                <textarea class="form-control" id="edit_allergies" name="allergies" rows="2"><?php echo htmlspecialchars($medicalInfo['allergies'] ?? ''); ?></textarea>
              </div>
              <div class="form-group">
                <label for="edit_medical_conditions">Medical Conditions</label>
                <textarea class="form-control" id="edit_medical_conditions" name="medical_conditions" rows="2"><?php echo htmlspecialchars($medicalInfo['medical_conditions'] ?? ''); ?></textarea>
              </div>
              <div class="form-group">
                <label for="edit_medications">Current Medications</label>
                <textarea class="form-control" id="edit_medications" name="medications" rows="2"><?php echo htmlspecialchars($medicalInfo['medications'] ?? ''); ?></textarea>
              </div>
              <div class="form-group">
                <label for="edit_emergency_contact_name">Emergency Contact Name</label>
                <input type="text" class="form-control" id="edit_emergency_contact_name" name="emergency_contact_name" value="<?php echo htmlspecialchars($medicalInfo['emergency_contact_name'] ?? ''); ?>" required>
              </div>
              <div class="form-group">
                <label for="edit_emergency_contact_phone">Emergency Contact Phone</label>
                <input type="tel" class="form-control" id="edit_emergency_contact_phone" name="emergency_contact_phone" value="<?php echo htmlspecialchars($medicalInfo['emergency_contact_phone'] ?? ''); ?>" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" name="save_medical_info" class="btn btn-primary">Update Information</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Voice Command Activation -->
    <div class="card p-4">
      <div class="section-title">🎤 Emergency Voice Detection</div>
      <p class="voice-note">Click the button and say any emergency word like "help", "emergency", or "danger" to call emergency services immediately</p>
      
      <div class="voice-command-container text-center">
        <button id="voiceCommandBtn" class="btn btn-primary btn-lg">
          <i class="fas fa-microphone"></i> Click to Speak
        </button>
        <div id="voiceStatus" class="mt-2"></div>
      </div>
    </div>

    <!-- Scheduled Calls Section -->
    <div class="card p-4">
        <div class="section-title">⏰  Emergency Call</div>
        <p class="text-muted">A call to be made automatically at a specific time (up to 24 hours in advance)</p>
        
        <form method="POST" action="" id="scheduleCallForm">
            <div class="form-group">
                <label for="phone_number">Phone Number to Call</label>
                <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                       required 
                       placeholder="e.g., +8801600347734">
                <small class="form-text text-muted">Enter the phone number you want to call</small>
            </div>
            <div class="form-group">
                <label for="scheduled_time"> Time</label>
                <input type="datetime-local" class="form-control" id="scheduled_time" name="scheduled_time" 
                       min="<?php echo date('Y-m-d\TH:i'); ?>" 
                       max="<?php echo date('Y-m-d\TH:i', strtotime('+24 hours')); ?>" required>
                <small class="form-text text-muted">Select a time within the next 24 hours</small>
            </div>
            <button type="submit" name="schedule_call" class="btn btn-primary"> Call</button>
        </form>

        <?php if (!empty($scheduledCalls)): ?>
            <div class="mt-4">
                <h5>Your  Calls</h5>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Phone Number</th>
                                <th>Call Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($scheduledCalls as $call): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($call['phone_number']); ?></td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($call['scheduled_time'])); ?></td>
                                    <td>
                                        <span class="badge badge-<?php 
                                            echo $call['status'] === 'pending' ? 'warning' : 
                                                ($call['status'] === 'completed' ? 'success' : 'danger'); 
                                        ?>">
                                            <?php echo htmlspecialchars($call['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($call['status'] === 'pending'): ?>
                                            <form method="POST" action="" style="display: inline;">
                                                <input type="hidden" name="cancel_call_id" value="<?php echo $call['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Are you sure you want to cancel this scheduled call?')">
                                                    Cancel
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Satellite Communication Fallback -->
    <div class="card p-4">
      <div class="section-title">🛰️ Satellite Communication (Fallback)</div>
      <p>If there's no cellular signal, SafeWay will attempt to use available satellite APIs or fallback mechanisms for sending alerts. (Available in premium devices)</p>
      <button class="btn btn-dark" disabled>Activate Satellite SOS (Coming Soon)</button>
    </div>

    <!-- QR Code for Emergency Page -->
    <div class="card p-4 text-center">
      <div class="section-title">🔗 Quick Access QR (Backup)</div>
      <p>Scan this to access Emergency Panel from another device</p>
      <div class="qr-box">
        <img src="https://api.qrserver.com/v1/create-qr-code/?data=https://yourdomain.com/emergency_calls.php&size=150x150" alt="QR Code">
      </div>
    </div>
  </div>

  <!-- JS -->
  <script src="plugins/jquery/jquery.min.js"></script>
  <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script>
    // Share location using Geolocation API
    function shareLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          const lat = position.coords.latitude;
          const lon = position.coords.longitude;
          const locationUrl = `https://www.google.com/maps?q=${lat},${lon}`;
          const message = `I need help! My current location: ${locationUrl}`;
          
          // Update the location message
          document.getElementById('locationMessage').textContent = message;
          
          // Set up sharing links
          const whatsappLink = `https://wa.me/?text=${encodeURIComponent(message)}`;
          const messengerLink = `https://www.facebook.com/dialog/send?app_id=YOUR_APP_ID&link=${encodeURIComponent(locationUrl)}&redirect_uri=${encodeURIComponent(window.location.href)}`;
          const smsLink = `sms:?body=${encodeURIComponent(message)}`;
          
          document.getElementById('whatsappShare').href = whatsappLink;
          document.getElementById('messengerShare').href = messengerLink;
          document.getElementById('smsShare').href = smsLink;
          
          // Show sharing options
          document.getElementById('sharingOptions').style.display = 'block';
          
          // Scroll to sharing options
          document.getElementById('sharingOptions').scrollIntoView({ behavior: 'smooth' });
        }, function(error) {
          alert("Unable to access location. Please enable GPS and try again.");
          console.error("Geolocation error:", error);
        });
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    }

    function copyLocationLink() {
      const message = document.getElementById('locationMessage').textContent;
      navigator.clipboard.writeText(message).then(function() {
        alert("Location link copied to clipboard!");
      }).catch(function(err) {
        console.error('Could not copy text: ', err);
        alert("Failed to copy location link. Please try again.");
      });
    }

    // Send predefined emergency message
    function prepareEmergencyMessage(type) {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          document.getElementById('messageLatitude').value = position.coords.latitude;
          document.getElementById('messageLongitude').value = position.coords.longitude;
          document.getElementById('messageType').value = type;
          
          // Show the sending options modal
          $('#sendingOptionsModal').modal('show');
        }, function(error) {
          alert("Unable to get location. Please enable GPS and try again.");
          console.error("Geolocation error:", error);
        });
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    }

    // Panic button logic
    function activatePanicButton() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
          const lat = position.coords.latitude;
          const lon = position.coords.longitude;
          const locationUrl = `https://www.google.com/maps?q=${lat},${lon}`;
          const message = `EMERGENCY! I need immediate help! My current location: ${locationUrl}`;
          
          // Submit the form with panic message
          const form = document.createElement('form');
          form.method = 'POST';
          form.action = '';
          
          const latitudeInput = document.createElement('input');
          latitudeInput.type = 'hidden';
          latitudeInput.name = 'latitude';
          latitudeInput.value = lat;
          
          const longitudeInput = document.createElement('input');
          longitudeInput.type = 'hidden';
          longitudeInput.name = 'longitude';
          longitudeInput.value = lon;
          
          const messageTypeInput = document.createElement('input');
          messageTypeInput.type = 'hidden';
          messageTypeInput.name = 'message_type';
          messageTypeInput.value = 'panic';
          
          const sendMethodInput = document.createElement('input');
          sendMethodInput.type = 'hidden';
          sendMethodInput.name = 'send_method';
          sendMethodInput.value = 'whatsapp'; // Default to WhatsApp for panic
          
          form.appendChild(latitudeInput);
          form.appendChild(longitudeInput);
          form.appendChild(messageTypeInput);
          form.appendChild(sendMethodInput);
          
          document.body.appendChild(form);
          form.submit();
        }, function(error) {
          alert("Unable to get location. Please enable GPS and try again.");
          console.error("Geolocation error:", error);
        });
      } else {
        alert("Geolocation is not supported by this browser.");
      }
    }

    // Voice command functionality
    const helpPatterns = [
        /help/i,
        /emergency/i,
        /danger/i,
        /dangerous/i,
        /unsafe/i,
        /scared/i,
        /afraid/i,
        /frightened/i,
        /threat/i,
        /attack/i,
        /assault/i,
        /robbery/i,
        /theft/i,
        /accident/i,
        /injury/i,
        /hurt/i,
        /pain/i,
        /bleeding/i,
        /unconscious/i,
        /faint/i,
        /collapse/i,
        /fall/i,
        /fire/i,
        /smoke/i,
        /flood/i,
        /earthquake/i,
        /storm/i,
        /hurricane/i,
        /tornado/i,
        /tsunami/i,
        /disaster/i,
        /crisis/i,
        /urgent/i,
        /immediate/i,
        /quick/i,
        /fast/i,
        /now/i,
        /911/i,
        /999/i,
        /112/i,
        /police/i,
        /ambulance/i,
        /fire/i,
        /rescue/i,
        /save/i,
        /sos/i,
        /distress/i,
        /trouble/i,
        /problem/i,
        /issue/i,
        /worry/i,
        /concern/i,
        /anxiety/i,
        /panic/i,
        /fear/i,
        /terror/i,
        /horror/i,
        /shock/i,
        /trauma/i,
        /crisis/i,
        /critical/i,
        /serious/i,
        /severe/i,
        /extreme/i,
        /intense/i,
        /violent/i,
        /aggressive/i,
        /hostile/i,
        /threatening/i,
        /intimidating/i,
        /scary/i,
        /frightening/i,
        /terrifying/i,
        /alarming/i,
        /disturbing/i,
        /unsettling/i,
        /uncomfortable/i,
        /vulnerable/i,
        /exposed/i,
        /at risk/i,
        /in danger/i,
        /in trouble/i,
        /in distress/i,
        /in pain/i,
        /in shock/i,
        /in crisis/i,
        /in emergency/i,
        /in need/i,
        /in fear/i,
        /in panic/i,
        /in terror/i,
        /in horror/i,
        /in trauma/i,
        /in critical/i,
        /in serious/i,
        /in severe/i,
        /in extreme/i,
        /in intense/i,
        /in violent/i,
        /in aggressive/i,
        /in hostile/i,
        /in threatening/i,
        /in intimidating/i,
        /in scary/i,
        /in frightening/i,
        /in terrifying/i,
        /in alarming/i,
        /in disturbing/i,
        /in unsettling/i,
        /in uncomfortable/i,
        /in vulnerable/i,
        /in exposed/i,
        /in at risk/i
    ];

    // Check if browser supports speech recognition
    if ('webkitSpeechRecognition' in window) {
      const recognition = new webkitSpeechRecognition();
      recognition.continuous = false;
      recognition.interimResults = false;
      recognition.lang = 'en-US';
      
      const voiceCommandBtn = document.getElementById('voiceCommandBtn');
      const voiceStatus = document.getElementById('voiceStatus');
      
      function checkForHelpWords(text) {
        for (const pattern of helpPatterns) {
          if (pattern.test(text)) {
            return true;
          }
        }
        return false;
      }
      
      function startVoiceRecognition() {
        voiceStatus.innerHTML = '<div class="alert alert-info">Listening for emergency words...</div>';
        voiceCommandBtn.classList.remove('btn-primary');
        voiceCommandBtn.classList.add('btn-danger');
        recognition.start();
      }
      
      recognition.onresult = function(event) {
        const command = event.results[0][0].transcript.toLowerCase();
        voiceStatus.innerHTML = `<div class="alert alert-info">Heard: ${command}</div>`;
        
        // Check for help words
        if (checkForHelpWords(command)) {
          voiceStatus.innerHTML = '<div class="alert alert-danger">Emergency detected! Calling 999...</div>';
          window.location.href = 'tel:999';
        } else {
          voiceStatus.innerHTML = '<div class="alert alert-warning">No emergency detected. Please try again if you need help.</div>';
        }
      };
      
      recognition.onerror = function(event) {
        voiceStatus.innerHTML = '<div class="alert alert-danger">Error: ' + event.error + '</div>';
        voiceCommandBtn.classList.remove('btn-danger');
        voiceCommandBtn.classList.add('btn-primary');
      };
      
      recognition.onend = function() {
        // Reset button state
        voiceCommandBtn.classList.remove('btn-danger');
        voiceCommandBtn.classList.add('btn-primary');
      };
      
      // Single click to start listening
      voiceCommandBtn.addEventListener('click', startVoiceRecognition);
      
    } else {
      voiceStatus.innerHTML = '<div class="alert alert-warning">Voice detection is not supported in this browser</div>';
      voiceCommandBtn.disabled = true;
    }

    // Update form validation
    document.getElementById('scheduleCallForm').addEventListener('submit', function(e) {
        const scheduledTime = new Date(document.getElementById('scheduled_time').value);
        const currentTime = new Date();
        const maxTime = new Date();
        maxTime.setHours(maxTime.getHours() + 24);
        
        if (scheduledTime <= currentTime) {
            e.preventDefault();
            alert('Please select a future time for scheduling the call.');
            return;
        }
        
        if (scheduledTime > maxTime) {
            e.preventDefault();
            alert('Scheduled time cannot be more than 24 hours in the future.');
            return;
        }
    });

    // Function to check for scheduled calls
    function checkScheduledCalls() {
        fetch('check_scheduled_calls.php')
            .then(response => response.text())
            .then(data => {
                console.log('Checked scheduled calls:', data);
            })
            .catch(error => {
                console.error('Error checking scheduled calls:', error);
            });
    }

    // Check for scheduled calls every minute
    setInterval(checkScheduledCalls, 60000);

    // Check immediately when the page loads
    checkScheduledCalls();
  </script>
</body>
</html>
