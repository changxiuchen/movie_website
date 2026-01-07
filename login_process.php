<?php
// Validate login form submission
if (!isset($_POST["user_id"]) || !isset($_POST["password"])) {
    echo "This page is not meant to be loaded directly";
    exit(0);
}

require_once("dbinfo.php");

// Get user input from the form
$user_id = $_POST["user_id"];
$password = $_POST["password"];

// Authenticate user against database
$result = $mysqli->query("SELECT * FROM users WHERE user_id = '$user_id' AND password = '$password'");

// If user found, start session and redirect
if ($result && $result->num_rows == 1) {
    session_start();
    $record = $result->fetch_assoc();
    // Store user info in session
    $_SESSION["user_id"] = $record["user_id"];
    $_SESSION["fullname"] = $record["fullname"];
    $_SESSION["is_admin"] = $record["is_admin"];
    
    $mysqli->close();
    // Redirect admin to admin dashboard, regular users to reservations page
    if ($record["is_admin"] == 1) {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: movies.php");
    }
    exit();
} else {
    // Login failed - show error message
    session_start();
    $_SESSION['error'] = "Invalid user ID or password. Please try again.";
    header("Location: login.php");
    exit();
}
?> 