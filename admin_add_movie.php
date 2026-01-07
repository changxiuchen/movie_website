<?php
session_start();
require_once 'dbinfo.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

// Process movie addition form
if (isset($_POST['title'])) {
    // Extract form data
    $title = trim($_POST['title']);
    $running_time = trim($_POST['running_time']);
    $genre = trim($_POST['genre']);
    $language = trim($_POST['language']);
    $release_date = trim($_POST['release_date']);
    $price_per_ticket = trim($_POST['price_per_ticket']);
    $poster_url = trim($_POST['poster_url']);
    $banner_url = trim($_POST['banner_url']);
    $synopsis = trim($_POST['synopsis']);
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    
    // Validate all required fields are provided
    if ($title && $running_time && $genre && $language && $release_date && $price_per_ticket && $synopsis && $banner_url && $status) {
        // Insert new movie into database
        $sql = "INSERT INTO movies (title, running_time, genre, language, release_date, price_per_ticket, poster_url, banner_url, synopsis, status) VALUES (
            \"$title\", '$running_time', '$genre', '$language', '$release_date', '$price_per_ticket', '$poster_url', '$banner_url', '$synopsis', '$status')";
        if ($mysqli->query($sql)) {
            $success = 'Movie added successfully!';
            // Clear form data after successful submission
            $_POST = array();
        } else {
            $error = 'Error adding movie. Please try again.';
        }
    } else {
        $error = 'Please fill in all required fields.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movie - Golden Scene</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>
<?php require_once('header.php'); ?>
<main>
    <section class="admin-dashboard-section">
        <h1 class="page-title">Add New Movie</h1>
        <div class="admin-tabs">
            <a href="admin_dashboard.php" class="tab">Dashboard</a>
            <a href="admin_reservations.php" class="tab">All Reservations</a>
            <a href="admin_movies.php" class="tab">Manage Movies</a>
        </div>
        <div class="admin-form-container">
            <h2>Add Movie Details</h2>
            <?php if ($error): ?><div class="error-msg"><?php echo $error; ?></div><?php endif; ?>
            <?php if ($success): ?><div class="success-msg"><?php echo $success; ?></div><?php endif; ?>
            <form method="post" class="admin-form">
                <label for="title">Movie Title *</label>
                <input type="text" id="title" name="title" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" required>
                
                <label for="running_time">Duration (e.g. 2h 30m) *</label>
                <input type="text" id="running_time" name="running_time" value="<?php echo isset($_POST['running_time']) ? htmlspecialchars($_POST['running_time']) : ''; ?>" required>
                
                <label for="genre">Genre *</label>
                <input type="text" id="genre" name="genre" value="<?php echo isset($_POST['genre']) ? htmlspecialchars($_POST['genre']) : ''; ?>" placeholder="e.g. Action, Adventure, Thriller" required>
                
                <label for="language">Language *</label>
                <input type="text" id="language" name="language" value="<?php echo isset($_POST['language']) ? htmlspecialchars($_POST['language']) : ''; ?>" placeholder="e.g. English, Japanese" required>
                
                <label for="release_date">Release Date *</label>
                <input type="date" id="release_date" name="release_date" value="<?php echo isset($_POST['release_date']) ? htmlspecialchars($_POST['release_date']) : ''; ?>" required>
                
                <label for="price_per_ticket">Price per Ticket *</label>
                <input type="number" step="0.01" id="price_per_ticket" name="price_per_ticket" value="<?php echo isset($_POST['price_per_ticket']) ? htmlspecialchars($_POST['price_per_ticket']) : ''; ?>" required>
                
                <label for="banner_url">Banner Image Filename *</label>
                <input type="text" id="banner_url" name="banner_url" value="<?php echo isset($_POST['banner_url']) ? htmlspecialchars($_POST['banner_url']) : ''; ?>" placeholder="e.g. movie_banner.jpg" required>
                
                <label for="poster_url">Poster Image Filename</label>
                <input type="text" id="poster_url" name="poster_url" value="<?php echo isset($_POST['poster_url']) ? htmlspecialchars($_POST['poster_url']) : ''; ?>" placeholder="e.g. movie_poster.jpg">
                
                <label for="status">Status *</label>
                <select id="status" name="status" required>
                    <option value="">-- Select Status --</option>
                    <option value="now_showing" <?php if(isset($_POST['status']) && $_POST['status']==='now_showing') echo 'selected'; ?>>Now Showing</option>
                    <option value="coming_soon" <?php if(isset($_POST['status']) && $_POST['status']==='coming_soon') echo 'selected'; ?>>Coming Soon</option>
                    <option value="advance_sale" <?php if(isset($_POST['status']) && $_POST['status']==='advance_sale') echo 'selected'; ?>>Advance Sale</option>
                </select>
                
                <label for="synopsis">Description *</label>
                <textarea id="synopsis" name="synopsis" required><?php echo isset($_POST['synopsis']) ? htmlspecialchars($_POST['synopsis']) : ''; ?></textarea>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-secondary">Add Movie</button>
                    <a href="admin_movies.php" class="btn btn-danger cancel-btn">Cancel</a>
                </div>
            </form>
        </div>
    </section>
</main>
<?php require_once('footer.php'); ?>
</body>
</html> 