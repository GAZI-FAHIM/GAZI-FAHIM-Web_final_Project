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

// Process form when submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $role = $_POST['role'];

    // Check if email already exists
    $checkEmailSql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($checkEmailSql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email already exists, return error response
        echo json_encode(["status" => "error", "message" => "Email already registered."]);
    } else {
        // Email not found, proceed with registration
        $insertSql = "INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("ssss", $fullName, $email, $password, $role);

        if ($insertStmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Welcome to SafeWay! Your account has been created."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Registration failed. Please try again later."]);
        }
        $insertStmt->close();
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signup</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(to right,rgb(212, 56, 8), #2575fc);
            color: white;
        }
        .register-box {
            margin: 200px auto;
            width: 400px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 35px;
            box-shadow: 20px 4px 20px rgba(0, 0, 0, 0.2);
            padding: 30px;
        }
        .btn-primary {
            background-color: #6a11cb;
            border: none;
        }
        .btn-primary:hover {
            background-color: #4c0ba6;
        }
        .alert {
            display: none;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
        }
        .alert-success {
            background-color: #28a745;
            color: white;
        }
        .alert-error {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
<div class="register-box">
    <div class="register-logo">
        <a href=""><b>SafeWay</b></a>
    </div>
    <div class="card">
        <div class="card-body register-card-body">
            <div id="alertBox" class="alert"></div>
            <p class="login-box-msg">Register a new membership</p>
            <form id="signupForm" method="POST">
                <div class="input-group mb-3">
                    <input type="text" id="fullName" name="fullName" class="form-control" placeholder="Full name" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <select id="role" name="role" class="form-control" required>
                        <option value="" disabled selected>Select your role</option>
                        <option value="admin">Admin</option>
                        <option value="Dwellers">New City Dwellers</option>
                        <option value="Students">Students</option>
                        <option value="Tourists">Tourists</option>
                        <option value="Workers">Night Shift Workers</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="agree" required>
                            <label for="agree">
                                I agree to the <a href="images/Terms.pdf" target="_blank">terms and conditions</a>
                            </label>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                    </div>
                </div>
            </form>
            <p class="mb-1">
                <a href="login.html">I already have a membership</a>
            </p>
        </div>
    </div>
</div>

<!-- jQuery (required for AJAX) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $("#signupForm").on("submit", function(e) {
        e.preventDefault();

        $.ajax({
            url: "signup.php",
            type: "POST",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response) {
                const alertBox = $("#alertBox");
                if (response.status === "success") {
                    alertBox.removeClass().addClass("alert alert-success").text(response.message).fadeIn();
                    $("#signupForm")[0].reset();
                } else {
                    alertBox.removeClass().addClass("alert alert-error").text(response.message).fadeIn();
                }
            },
            error: function() {
                alert("An unexpected error occurred. Please try again later.");
            }
        });
    });
</script>
</body>
</html>
