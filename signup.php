<?php
require_once("dbinfo.php");

// Check if form was submitted
if (isset($_POST['user-id'])) {
    // Get form data
    $user_id = trim($_POST['user-id']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $mobile = trim($_POST['mobile']);
    
    $errors = [];
    
    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address format";
    }
    
    // Validate password length (8-16 characters)
    if (strlen($password) < 8 || strlen($password) > 16) {
        $errors[] = "Password must be between 8 and 16 characters long";
    }
    
    // c) Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = "Oops! Your passwords don't match. Please try again.";
    }
    
    // Validate mobile number format (8 digits, starts with 8 or 9)
    if (!preg_match('/^[89]\d{7}$/', $mobile)) {
        $errors[] = "Mobile number must be exactly 8 digits and start with 8 or 9";
    }
    
    // Check if user ID already exists
    $result = $mysqli->query("SELECT COUNT(*) as count FROM users WHERE user_id = '$user_id'");
    $row = $result->fetch_assoc();
    if ($row['count'] > 0) {
        $errors[] = "User ID already exists";
    }
    
    // Check if email already exists
    $result = $mysqli->query("SELECT COUNT(*) as count FROM users WHERE email = '$email'");
    $row = $result->fetch_assoc();
    if ($row['count'] > 0) {
        $errors[] = "Email already registered";
    }
    
    // Check if mobile number already exists
    $result = $mysqli->query("SELECT COUNT(*) as count FROM users WHERE mobile = '$mobile'");
    $row = $result->fetch_assoc();
    if ($row['count'] > 0) {
        $errors[] = "Mobile number already registered";
    }
    
    // If no errors, create new user account
    if (empty($errors)) {
        // Insert user into database
        $result = $mysqli->query("INSERT INTO users (user_id, fullname, email, password, mobile) VALUES ('$user_id', '$fullname', '$email', '$password', '$mobile')");
        if ($result) {
            // Store user info in session and redirect
            $_SESSION['user_id'] = $user_id;
            $_SESSION['fullname'] = $fullname;
            $_SESSION['email'] = $email;
            $_SESSION['mobile'] = $mobile;
            $_SESSION['logged_in'] = true;
            
            // Redirect to reservations page
            header("Location: reservations.php");
            exit();
        } else {
            $errors[] = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Golden Scene</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/auth.css"> 
</head>
<body>
    <?php require_once("header.php"); ?>
    <main>
        <section class="form-section">
            <h1 class="page-title">Sign Up</h1>
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <p class="error"><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <form action="signup.php" method="post">
                <label for="user-id">User ID</label>
                <input type="text" id="user-id" name="user-id" required value="<?php echo isset($user_id) ? htmlspecialchars($user_id) : ''; ?>">
                
                <label for="fullname">User Name</label>
                <input type="text" id="fullname" name="fullname" required value="<?php echo isset($fullname) ? htmlspecialchars($fullname) : ''; ?>">
                
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email"  required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                
                <label for="password">Password</label>
                <small class="password-hint">Password must be at least 8 characters long and not more than 16 characters in length</small>
                <input type="password" id="password" placeholder="Enter your password" name="password" required>
                
                
                <label for="confirm-password">Re-Enter Password</label>
                <input type="password" id="confirm-password" placeholder="Re-enter your password" name="confirm-password" required>
                
                <label for="mobile">Mobile number</label>
                <input type="text" id="mobile" name="mobile" placeholder="e.g., 81234567" required value="<?php echo isset($mobile) ? htmlspecialchars($mobile) : ''; ?>">
                
                <button type="submit" class="btn btn-primary">Sign Up</button>
                <p>Already a member? <a href="login.php">Log In now to access exclusive content!</a></p>
            </form>
        </section>
    </main>
    <?php require_once("footer.php"); ?>
</body>
</html>