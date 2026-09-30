<?php
// Database connection
$servername = "localhost";
$username = "root";  // XAMPP default username
$password = "";      // XAMPP default password
$dbname = "safeway";  // your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email, password, and role from the form
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // SQL query to check if the user exists with the given email and role
    $sql = "SELECT * FROM users WHERE email = ? AND role = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $role);  // Bind parameters to prevent SQL injection
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user is found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $row['password'])) {
            // Start session and store session variables
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['full_name'];
            $_SESSION['role'] = $row['role'];

            // Redirect based on user role
            switch ($row['role']) {
                case 'admin':
                    header("Location: view_users.php");
                    break;
                case 'Dwellers':
                    header("Location: dashboard.php");
                    break;
                case 'Students':
                    header("Location: dashboard.php");
                    break;
                case 'Tourists':
                    header("Location: dashboard.php");
                    break;
                case 'Workers':
                    header("Location: dashboard.html");
                    break;   
                default:
                    echo "Role not recognized.";
                    break;
            }
            exit();
        } else {
            // Invalid password
            echo "Incorrect password!";
        }
    } else {
        // User not found
        echo "No user found with that email and role.";
    }


    // Close the prepared statement and database connection
    $stmt->close();
}
$conn->close();
?>
