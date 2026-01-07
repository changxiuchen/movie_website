<?php
session_start();
require_once 'dbinfo.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit();
}
// Calculate dashboard statistics
$total_users = $mysqli->query("SELECT COUNT(*) FROM users WHERE is_admin = 0")->fetch_row()[0];
$total_bookings = $mysqli->query("SELECT COUNT(*) FROM reservations")->fetch_row()[0];
$total_movies = $mysqli->query("SELECT COUNT(*) FROM movies")->fetch_row()[0];
$today = date('Y-m-d');
$today_bookings = $mysqli->query("SELECT COUNT(*) FROM reservations WHERE date = '$today'")->fetch_row()[0];
$this_week_start = date('Y-m-d', strtotime('monday this week'));
$this_week_end = date('Y-m-d', strtotime('sunday this week'));
$this_week_bookings = $mysqli->query("SELECT COUNT(*) FROM reservations WHERE date BETWEEN '$this_week_start' AND '$this_week_end'")->fetch_row()[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Golden Scene</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php require_once('header.php'); ?>
<main>
    <section class="admin-dashboard-section">
        <h1 class="page-title">Admin Dashboard</h1>
        <p>Manage your cinema system and view all user reservations.</p>
        <div class="admin-tabs">
            <button class="tab active">Dashboard</button>
            <a href="admin_reservations.php" class="tab">All Reservations</a>
            <a href="admin_movies.php" class="tab">Manage Movies</a>
        </div>
        <div class="admin-stats">
            <div class="stat-card"><h2><?php echo $total_users; ?></h2><p>Total Users</p></div>
            <div class="stat-card"><h2><?php echo $total_bookings; ?></h2><p>Total Bookings</p></div>
            <div class="stat-card"><h2><?php echo $total_movies; ?></h2><p>Total Movies</p></div>
            <div class="stat-card"><h2><?php echo $today_bookings; ?></h2><p>Today's Bookings</p></div>
            <div class="stat-card"><h2><?php echo $this_week_bookings; ?></h2><p>This Week's Bookings</p></div>
        </div>
        <div class="admin-actions">
            <div class="quick-actions">
                <h3>Quick Actions</h3>
                <a href="admin_reservations.php" class="btn btn-secondary">View All Reservations</a>
                <a href="admin_movies.php?action=add" class="btn btn-secondary">Add New Movie</a>
                <a href="admin_reservations.php?filter=today" class="btn btn-secondary">Today's Bookings</a>
                <a href="admin_reservations.php?filter=week" class="btn btn-secondary">This Week's Bookings</a>
            </div>
            <div class="movie-management">
                <h3>Movie Management</h3>
                <a href="admin_movies.php" class="btn btn-secondary">View All Movies</a>
                <a href="admin_movies.php?action=add" class="btn btn-secondary">Add New Movie</a>
                <a href="admin_movies.php?action=edit" class="btn btn-secondary">Edit Movies</a>
                <a href="admin_movies.php?action=delete" class="btn btn-secondary">Delete Movies</a>
            </div>
        </div>
    </section>
</main>
<?php require_once('footer.php'); ?>
</body>
</html> 