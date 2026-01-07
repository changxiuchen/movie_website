<?php
session_start();
require_once 'dbinfo.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit();
}
// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $mysqli->query("DELETE FROM movies WHERE movie_id = $delete_id");
    header('Location: admin_movies.php');
    exit();
}
// Fetch all movies
$result = $mysqli->query("SELECT * FROM movies ORDER BY release_date DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Movies - Golden Scene</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php require_once('header.php'); ?>
<main>
    <section class="admin-dashboard-section">
        <h1 class="page-title">Manage Movies</h1>
        <p>View, edit, or delete movies in the system.</p>
        <div class="admin-tabs">
            <a href="admin_dashboard.php" class="tab">Dashboard</a>
            <a href="admin_reservations.php" class="tab">All Reservations</a>
            <button class="tab active">Manage Movies</button>
        </div>
        <a href="admin_add_movie.php" class="btn btn-secondary add-movie-btn">Add New Movie</a>
        <div class="movies-grid">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($movie = $result->fetch_assoc()): ?>
                    <div class="movie-card-admin">
                        <div class="admin-movie-poster-wrapper">
                            <img src="<?php echo isset($movie['poster_url']) && $movie['poster_url'] ? 'images/' . htmlspecialchars($movie['poster_url']) : 'images/placeholder.png'; ?>" alt="<?php echo htmlspecialchars($movie['title']); ?> Poster" class="admin-movie-poster">
                        </div>
                        <div class="admin-movie-card-content">
                            <h3 class="admin-movie-title-admin"><?php echo htmlspecialchars($movie['title']); ?></h3>
                            <div class="admin-movie-meta">
                                <div class="admin-movie-meta-row"><span class="admin-movie-meta-label">Duration:</span> <span class="admin-movie-meta-value"><?php echo htmlspecialchars($movie['running_time']); ?></span></div>
                                <div class="admin-movie-meta-row"><span class="admin-movie-meta-label">Genre:</span> <span class="admin-movie-meta-value"><?php echo htmlspecialchars($movie['genre']); ?></span></div>
                                <div class="admin-movie-meta-row"><span class="admin-movie-meta-label">Language:</span> <span class="admin-movie-meta-value"><?php echo htmlspecialchars($movie['language']); ?></span></div>
                                <div class="admin-movie-meta-row"><span class="admin-movie-meta-label">Release Date:</span> <span class="admin-movie-meta-value"><?php echo date('d M Y', strtotime($movie['release_date'])); ?></span></div>
                                <div class="admin-movie-meta-row"><span class="admin-movie-meta-label">Price:</span> <span class="admin-movie-meta-value">$<?php echo number_format($movie['price_per_ticket'], 2); ?></span></div>
                            </div>
                            <div class="admin-movie-desc-admin">
                                <?php echo (htmlspecialchars($movie['synopsis'])); ?>
                            </div>
                            <div class="admin-movie-actions">
                                <a href="admin_edit_movie.php?id=<?php echo $movie['movie_id']; ?>" class="edit-btn">Edit</a>
                                <a href="admin_movies.php?delete=<?php echo $movie['movie_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this movie?');">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="grid-column:1/-1;text-align:center;">No movies found.</p>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php require_once('footer.php'); ?>
</body>
</html> 