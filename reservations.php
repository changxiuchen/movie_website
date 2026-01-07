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

// Get user information
$sql = "SELECT * FROM users WHERE user_id = '{$_SESSION["user_id"]}'";
$user_result = $mysqli->query($sql);
$user_info = $user_result->fetch_assoc();

// Query user's reservations with movie and theater information
$sql = "SELECT r.*, m.title, m.price_per_ticket, m.poster_url, t.name as theater_name, t.location 
        FROM reservations r 
        JOIN movies m ON r.movie_id = m.movie_id 
        JOIN theaters t ON r.theater_id = t.theater_id 
        WHERE r.user_id = '{$_SESSION["user_id"]}'
        ORDER BY r.date, r.time";

$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reservations - Golden Scene</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/reservations.css">
    
</head>
<body>
    <?php require_once("header.php"); ?>

    <main class="reservations-main">
        <div class="page-header">
            <div class="header-content">
                <h1 class="page-title">My Reservations</h1>
                <p class="page-subtitle">View and manage your movie bookings</p>
            </div>
        </div>

        <section class="reservations-section">
            
            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['booking_success'])): ?>
                <div class="success-message">
                    <p>✅ Booking successful! Your reservation has been confirmed.</p>
                </div>
                <?php unset($_SESSION['booking_success']); ?>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['booking_error'])): ?>
                <div class="error-message">
                    <p>❌ <?php echo htmlspecialchars($_SESSION['booking_error']); ?></p>
                </div>
                <?php unset($_SESSION['booking_error']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['cancel_success'])): ?>
                <div class="success-message">
                    <p>✅ Reservation cancelled successfully.</p>
                </div>
                <?php unset($_SESSION['cancel_success']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['cancel_error'])): ?>
                <div class="error-message">
                    <p>❌ <?php echo htmlspecialchars($_SESSION['cancel_error']); ?></p>
                </div>
                <?php unset($_SESSION['cancel_error']); ?>
            <?php endif; ?>
            
            <!-- User Information Section -->
            <div class="user-info">
                <div class="user-info-header">
                    <div class="user-info-left">
                        <div class="user-avatar">
                            <?php
                            $initial = isset($user_info['fullname']) && $user_info['fullname'] ? strtoupper($user_info['fullname'][0]) : '?';
                            echo '<span>' . htmlspecialchars($initial) . '</span>';
                            ?>
                        </div>
                        <div class="user-info-content">
                            <h2>Welcome, <?php echo htmlspecialchars($user_info['fullname'] ?? ''); ?>!</h2>
                            <div class="user-id">User ID: <?php echo htmlspecialchars($user_info['user_id'] ?? ''); ?></div>
                        </div>
                    </div>
                    <a href="update_account.php" class="edit-profile-btn">Edit Profile</a>
                </div>
                <hr class="user-divider">
                <div class="user-details">
                    <div class="detail-row">
                        <span class="detail-label">Email:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($user_info['email'] ?? ''); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Mobile:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($user_info['mobile'] ?? ''); ?></span>
                    </div>
                </div>
            </div>

            <h2>My Movie Reservations</h2>
            <?php if ($result && $result->num_rows > 0): ?>
                <div class="reservations-list">
                    <?php while ($row = $result->fetch_assoc()): 
                        // Calculate total price based on number of seats
                        $seats_array = explode(',', $row['seats']);
                        $num_seats = count($seats_array);
                        $total_price = $row['price_per_ticket'] * $num_seats;
                    ?>
                        <div class="reservation-card">
                            <div class="movie-poster">
                                <?php 
                                $poster = $row['poster_url'] ? 'images/' . $row['poster_url'] : 'images/placeholder.png';
                                ?>
                                <img src="<?php echo htmlspecialchars($poster); ?>" alt="<?php echo htmlspecialchars($row['title']); ?> Poster" class="poster-image">
                            </div>
                            <div class="reservation-content">
                                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                <div class="reservation-details">
                                    <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($row['date'])); ?></p>
                                    <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($row['time'])); ?></p>
                                    <p><strong>Theater:</strong> <?php echo htmlspecialchars($row['theater_name']); ?></p>
                                    <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                                    <p><strong>Seats:</strong> <?php echo htmlspecialchars($row['seats']); ?></p>
                                    <p><strong>Total Price:</strong> $<?php echo number_format($total_price, 2); ?></p>
                                    <form method="POST" action="reservation_process.php" onsubmit="return confirm('Are you sure you want to cancel this reservation?');" style="margin-top:10px;">
                                        <input type="hidden" name="cancel_reservation_id" value="<?php echo (int)$row['reservation_id']; ?>">
                                        <button type="submit" class="button cancel-button">Cancel</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p class="no-reservations">You don't have any reservations yet.</p>
                <a href="movies.php" class="button">Book a Movie</a>
            <?php endif; ?>
        </section>
    </main>

    <?php require_once("footer.php"); ?>
</body>
</html>
<?php
$mysqli->close();
?>