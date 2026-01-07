<?php
// Database connection configuration
$hostname = "localhost";
$dbUser = "root";
$dbPassword = "";
$db = "movies_db";

// Establish database connection
$mysqli = new mysqli($hostname, $dbUser, $dbPassword, $db);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
?>