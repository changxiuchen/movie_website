<?php
session_start();
require_once 'dbinfo.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit();
}
// Check if movie ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: admin_movies.php');
    exit();
}
$movie_id = intval($_GET['id']);
$error = '';

// Handle form submission
if (isset($_POST['title'])) {
    // Get form data
    $title = trim($_POST['title']);
    $running_time = trim($_POST['running_time']);
    $genre = trim($_POST['genre']);
    $language = trim($_POST['language']);
    $release_date = trim($_POST['release_date']);
    $price_per_ticket = trim($_POST['price_per_ticket']);
    $poster_url = trim($_POST['poster_url']);     
    $synopsis = trim($_POST['synopsis']);
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    // Check if all required fields are filled
    if ($title && $running_time && $genre && $language && $release_date && $price_per_ticket && $synopsis && $status) {
        // Update movie in database
        $query = "UPDATE movies SET title='$title', running_time='$running_time', genre='$genre', language='$language', release_date='$release_date', price_per_ticket=$price_per_ticket, poster_url='$poster_url', synopsis='$synopsis', status='$status' WHERE movie_id=$movie_id";
        $mysqli->query($query);
        header('Location: admin_movies.php');
        exit();
    } else {
        $error = 'Please fill in all required fields.';
    }
}

// Get movie data to display in form
$query = "SELECT * FROM movies WHERE movie_id = $movie_id";
$result = $mysqli->query($query);
$movie = $result->fetch_assoc();

if (!$movie) {
    header('Location: admin_movies.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie - Golden Scene</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php require_once('header.php'); ?>
<main>
    <section class="admin-dashboard-section">
        <h1 class="page-title">Edit Movie</h1>
        <div class="admin-tabs">
            <a href="admin_dashboard.php" class="tab">Dashboard</a>
            <a href="admin_reservations.php" class="tab">All Reservations</a>
            <a href="admin_movies.php" class="tab">Manage Movies</a>
        </div>
        <div class="admin-form-container">
            <h2>Edit Movie Details</h2>
            <?php if ($error): ?><div class="error-msg"><?php echo $error; ?></div><?php endif; ?>
            <form method="post" class="admin-form">
                <label for="title">Movie Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($movie['title']); ?>" required>
                <label for="running_time">Duration (e.g. 2h 30m)</label>
                <input type="text" id="running_time" name="running_time" value="<?php echo htmlspecialchars($movie['running_time']); ?>" required>
                <label for="genre">Genre</label>
                <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($movie['genre']); ?>" required>
                <label for="language">Language</label>
                <input type="text" id="language" name="language" value="<?php echo htmlspecialchars($movie['language']); ?>" required>
                <label for="release_date">Release Date</label>
                <input type="date" id="release_date" name="release_date" value="<?php echo htmlspecialchars($movie['release_date']); ?>" required>
                <label for="price_per_ticket">Price per Ticket</label>
                <input type="number" step="0.01" id="price_per_ticket" name="price_per_ticket" value="<?php echo htmlspecialchars($movie['price_per_ticket']); ?>" required>
                <label for="poster_url">Poster Image Filename</label>
                <input type="text" id="poster_url" name="poster_url" value="<?php echo str_replace('images/', '', htmlspecialchars($movie['poster_url'])); ?>">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="now_showing" <?php if($movie['status']==='now_showing') echo 'selected'; ?>>Now Showing</option>
                    <option value="coming_soon" <?php if($movie['status']==='coming_soon') echo 'selected'; ?>>Coming Soon</option>
                    <option value="advance_sale" <?php if($movie['status']==='advance_sale') echo 'selected'; ?>>Advance Sale</option>
                </select>
                <label for="synopsis">Description</label>
                <textarea id="synopsis" name="synopsis" required><?php echo htmlspecialchars($movie['synopsis']); ?></textarea>
                <div class="form-actions">
                    <button type="submit" class="btn btn-secondary">Save Changes</button>
                    <a href="admin_movies.php" class="btn btn-danger cancel-btn">Cancel</a>
                </div>
            </form>
        </div>
    </section>
</main>
<?php require_once('footer.php'); ?>
</body>
</html> 