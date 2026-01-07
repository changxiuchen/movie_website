<?php
session_start();
require_once 'dbinfo.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit();
}
// Fetch all reservations with user and movie info
$sql = "SELECT r.reservation_id, u.user_id, m.title, m.genre, r.date, r.time, r.seats, m.price_per_ticket FROM reservations r JOIN users u ON r.user_id = u.user_id JOIN movies m ON r.movie_id = m.movie_id ORDER BY r.date DESC, r.time DESC";
$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All User Reservations - Golden Scene</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php require_once('header.php'); ?>
<main>
    <section class="admin-reservations-section">
        <h1 class="page-title">All User Reservations</h1>
        <p>View and manage all bookings made by users across the system.</p>
        <div class="admin-tabs">
            <a href="admin_dashboard.php" class="tab">Dashboard</a>
            <button class="tab active">All Reservations</button>
            <a href="admin_movies.php" class="tab">Manage Movies</a>
        </div>
        <div class="admin-card">
            <h2 class="admin-card-title">Reservations List</h2>
            <div class="reservations-list">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Reservation ID</th>
                            <th>User ID</th>
                            <th>Movie Title</th>
                            <th>Date & Time</th>
                            <th>Seats</th>
                            <th>Total Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['reservation_id']; ?></td>
                                <td><?php echo $row['user_id']; ?></td>
                                <td><strong><?php echo $row['title']; ?></strong><br><span class="admin-table-meta"> <?php echo $row['genre']; ?></span></td>
                                <td><?php echo date('F j, Y', strtotime($row['date'])) . '<br>' . date('g:i A', strtotime($row['time'])); ?></td>
                                <td><?php echo $row['seats']; ?></td>
                                <td>
                                    $<?php 
                                        $seats_array = explode(',', $row['seats']);
                                        $num_seats = count($seats_array);
                                        $total_price = $row['price_per_ticket'] * $num_seats;
                                        echo number_format($total_price, 2);
                                    ?>
                                </td>
                                <td>
                                    <form method="post" action="admin_reservations.php" onsubmit="return confirm('Are you sure you want to cancel this booking?');" style="margin:0;">
                                        <input type="hidden" name="cancel_id" value="<?php echo $row['reservation_id']; ?>">
                                        <button type="submit" class="btn btn-danger cancel-btn">Cancel</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" style="text-align:center;">No reservations found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</main>
<?php require_once('footer.php'); ?>
<?php
// Handle cancel action
if (isset($_POST['cancel_id'])) {
    $cancel_id = $_POST['cancel_id'];
    $mysqli->query("DELETE FROM reservations WHERE reservation_id = $cancel_id");
    echo "<script>window.location.href='admin_reservations.php';</script>";
    exit();
}
?>
</body>
</html> 