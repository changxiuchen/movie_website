<?php
if (!isset($_SESSION)) {
    session_start();
}
require_once("dbinfo.php");

// Check if user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

// Process booking form submission from seat selection
if (isset($_POST['movie_id'])) {
    // Extract booking details from form
    $movie_id = (int)$_POST['movie_id'];
    $selected_date = $_POST['selected_date'];
    $selected_theater = (int)$_POST['selected_theater'];
    $selected_time = $_POST['selected_time'];
    $selected_seats = $_POST['selected_seats'];
    $user_id = $_SESSION["user_id"];
    
    // Check if all required fields are filled
    if ($movie_id && $selected_date && $selected_theater && $selected_time && $selected_seats) {
        // Convert 12-hour time format to 24-hour for database storage
        $time_parts = explode(' ', $selected_time);
        $time_only = $time_parts[0];
        if (count($time_parts) > 1 && $time_parts[1] == 'PM' && $time_only != '12:00') {
            $hour = (int)explode(':', $time_only)[0] + 12;
            $time_only = $hour . ':' . explode(':', $time_only)[1] . ':00';
        } else if (count($time_parts) > 1 && $time_parts[1] == 'AM' && $time_only == '12:00') {
            $time_only = '00:00:00';
        } else {
            $time_only = $time_only . ':00';
        }
        
        // Insert new reservation into database
        $sql = "INSERT INTO reservations (user_id, movie_id, date, time, theater_id, seats) 
                VALUES ('$user_id', $movie_id, '$selected_date', '$time_only', $selected_theater, '$selected_seats')";
        
        if ($mysqli->query($sql)) {
            // Booking successful
            $_SESSION['booking_success'] = true;
            header("Location: reservations.php");
            exit();
        } else {
            // Booking failed
            $_SESSION['booking_error'] = "Failed to create booking. Please try again.";
            header("Location: reservations.php");
            exit();
        }
    } else {
        // Missing required fields
        $_SESSION['booking_error'] = "Missing required booking information.";
        header("Location: reservations.php");
        exit();
    }
}

// Handle cancellation request
if (isset($_POST['cancel_reservation_id'])) {
    $cancel_id = (int)$_POST['cancel_reservation_id'];
    $user_id = $_SESSION["user_id"];
    // Make sure the reservation belongs to the user
    $sql = "SELECT * FROM reservations WHERE reservation_id = $cancel_id AND user_id = '$user_id'";
    $result = $mysqli->query($sql);
    if ($result && $result->num_rows > 0) {
        // Delete the reservation
        $sql = "DELETE FROM reservations WHERE reservation_id = $cancel_id";
        if ($mysqli->query($sql)) {
            $_SESSION['cancel_success'] = true;
        } else {
            $_SESSION['cancel_error'] = "Failed to cancel reservation. Please try again.";
        }
    } else {
        $_SESSION['cancel_error'] = "Reservation not found or does not belong to you.";
    }
    header("Location: reservations.php");
    exit();
}

// If no valid POST data, redirect to reservations page
header("Location: reservations.php");
exit();
?>
