<?php
// Initialize session if not already started
if (!isset($_SESSION)) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Golden Scene Cinema - Book your movie tickets online">
    <title>Golden Scene Cinema</title>
    
    <!-- Global CSS -->
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/header.css">
        
   
    <link rel="stylesheet" href="css/auth.css">
    <link rel="stylesheet" href="css/booking.css">
    <link rel="stylesheet" href="css/footer.css">
    
    

</head>
<body>
<header>
    <div class="container">
        <div class="header-content">
            
            <nav class="main-nav">
            <a href="index.php" class="logo-link">
                <img src="images/logo.png" alt="Golden Scene Logo" class="logo">
            </a>
                <ul>
                    <?php if (!isset(
                        
                        $_SESSION["is_admin"]) || $_SESSION["is_admin"] != 1): ?>
                        <li><a href="index.php"><span class="icon">🏠</span> Home</a></li>
                        <li><a href="movies.php"><span class="icon">🎬</span> Movies</a></li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION["user_id"]) && isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1): ?>
                        <li><a href="admin_dashboard.php"><span class="icon">📊</span> Admin Dashboard</a></li>
                        <li><a href="admin_reservations.php"><span class="icon">📋</span> All Reservations</a></li>
                        <li><a href="admin_movies.php"><span class="icon">⚙️</span> Manage Movies</a></li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION["user_id"]) && (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] != 1)): ?>
                        <li><a href="reservations.php"><span class="icon">🎫</span> My Reservations</a></li>
                        <li><a href="update_account.php"><span class="icon">👤</span> Update Account</a></li>
                    <?php endif; ?>
                    <?php if (isset($_SESSION["user_id"])): ?>
                        <li><a href="logout.php"><span class="icon">🚪</span> Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php"><span class="icon">🔑</span> Login</a></li>
                    <?php endif; ?>
                </ul>
                <button class="burger-menu" aria-label="Toggle navigation">
                <span class="icon">☰</span>
            </nav>
            
            </button>
        </div>
    </div>
</header>

<script src="jq/jquery.js"></script>
<script>
$(document).ready(function() {
    $('.burger-menu').click(function() {
        $('.main-nav ul').slideToggle(200);
        $(this).toggleClass('active');
    });
    
});
</script>
