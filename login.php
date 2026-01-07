<?php
session_start();

// Check if user is already logged in and redirect
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: reservations.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Golden Scene</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <?php require_once("header.php"); ?>
    <main>
        <section class="form-section">
            <h1 class="page-title">Login</h1>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message">
                    <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php endif; ?>
            <form action="login_process.php" method="post">
                <label for="user_id">User ID</label>
                <input type="text" id="user_id" name="user_id" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <button type="submit" class="btn btn-primary">Login</button>
                <p>Not our member yet? <a href="signup.php">Sign Up Now!</a></p>
            </form>
        </section>
    </main>
    <?php require_once("footer.php"); ?>
</body>
</html>