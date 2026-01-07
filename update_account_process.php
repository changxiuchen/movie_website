<?php
session_start();
require_once 'dbinfo.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


// Check if form was submitted
if (isset($_POST['email'])) {
    $user_id = $_SESSION['user_id'];
    $email = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);

    // --- 1. EXISTING VALIDATIONS ---

    // Check if all fields are filled
    if (empty($email) || empty($mobile)) {
        $_SESSION['update_error'] = "All fields are required.";
        header("Location: update_account.php");
        exit();
    }

    // Check if email format is correct
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['update_error'] = "Invalid email format.";
        header("Location: update_account.php");
        exit();
    }

    // Check if mobile number format is correct (8 digits starting with 8 or 9)
    if (!preg_match('/^[89]\d{7}$/', $mobile)) {
        $_SESSION['update_error'] = "Mobile number must be exactly 8 digits and start with 8 or 9";
        header("Location: update_account.php");
        exit();
    }

    // Check if email already exists for another user
    $result = $mysqli->query("SELECT user_id FROM users WHERE email ='$email' AND user_id !='$user_id'");
    if ($result->num_rows > 0) {
        $_SESSION['update_error'] = "This email address is already registered to another account.";
        header("Location: update_account.php");
        exit();
    }

    // Check if mobile number already exists for another user
    $result = $mysqli->query("SELECT user_id FROM users WHERE mobile = '$mobile' AND user_id !='$user_id'");
    if ($result->num_rows > 0) {
        $_SESSION['update_error'] = "This mobile number is already registered to another account.";
        header("Location: update_account.php");
        exit();
    }

    // --- 2. NEW IMAGE UPLOAD LOGIC ---
    
    $image_sql_part = ""; // Initialize as empty string

    // Check if a file was actually selected
    if (isset($_FILES['profile_pic']) && !empty($_FILES['profile_pic']['name'])) {
        $filename = $_FILES['profile_pic']['name'];
        $tempname = $_FILES['profile_pic']['tmp_name'];
        $folder = "images/" . $filename; // Destination path

        // Move the file from temp storage to your uploads folder
        if (move_uploaded_file($tempname, $folder)) {
            // If move is successful, prepare the SQL snippet
            $image_sql_part = ", profile_picture = '$filename'";
        } else {
            // Optional: Handle upload error
            $_SESSION['update_error'] = "Failed to upload image.";
            header("Location: update_account.php");
            exit();
        }
    }

    // --- 3. UPDATE DATABASE ---

    // We insert $image_sql_part into the query. 
    // If no image was uploaded, it adds nothing. 
    // If image uploaded, it adds ", profile_picture = 'filename.jpg'"
    $sql = "UPDATE users SET email = '$email', mobile = '$mobile' $image_sql_part WHERE user_id ='$user_id'";
    
    $result = $mysqli->query($sql);

    if ($result) {
        $_SESSION['update_success'] = "Account updated successfully!";
    } else {
        $_SESSION['update_error'] = "Error updating account: " . $mysqli->error;
    }
    
    header("Location: update_account.php");
    exit();
}
?>