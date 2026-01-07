<?php 
// ========================================
// INCLUDES AND DEPENDENCIES
// ========================================
require_once("dbinfo.php");
require_once("header.php"); 

// ========================================
// CONFIGURATION AND SETTINGS
// ========================================
// Pagination settings
$movies_per_page = 8;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_status = isset($_GET['status']) ? $_GET['status'] : 'now_showing';

?>
<!-- ========================================
     CSS STYLESHEETS
     ======================================== -->
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/movies.css">

<!-- ========================================
     MAIN CONTENT
     ======================================== -->
<main>
    <section class="movie-section">
        <h1 class="page-title">Movies</h1>
        <?php
        // Handle search and filtering functionality
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
        // Define movie status categories for filtering
        $statuses = [
            'now_showing' => 'Now Showing',
            'coming_soon' => 'Coming Soon',
            'advance_sale' => 'Advance Sale'
        ];
        
        // Query movies from database with search functionality
        $all_movies = [];
        $query = "SELECT * FROM movies";
        if ($searchTerm) {
            $query .= " WHERE LOWER(title) LIKE '%" . strtolower($searchTerm) . "%'";
        }
        $query .= " ORDER BY release_date DESC";
        
        $result = $mysqli->query($query);
        if ($result && $result->num_rows > 0) {
            while ($movie = $result->fetch_assoc()) {
                $all_movies[$movie['status']][] = $movie;
            }
        }
        
        // ========================================
        // PAGINATION CALCULATIONS
        // ========================================
        // Get current status movies for pagination
        $current_movies = isset($all_movies[$current_status]) ? $all_movies[$current_status] : [];
        $total_movies = count($current_movies);
        $total_pages = ceil($total_movies / $movies_per_page);
        
        // Ensure current page is within valid range
        if ($current_page < 1) $current_page = 1;
        if ($current_page > $total_pages && $total_pages > 0) $current_page = $total_pages;
        
        // Get movies for current page
        $start_index = ($current_page - 1) * $movies_per_page;
        $current_page_movies = array_slice($current_movies, $start_index, $movies_per_page);
        ?>
        
        <!-- ========================================
             NAVIGATION TABS
             ======================================== -->
        <div class="tabs">
            <?php foreach ($statuses as $key => $label): ?>
                <a href="?status=<?php echo $key; ?>&search=<?php echo urlencode($searchTerm); ?>" 
                   class="tab<?php echo $key === $current_status ? ' active' : ''; ?>"><?php echo $label; ?></a>
            <?php endforeach; ?>
        </div>
        
        <!-- ========================================
             SEARCH FORM
             ======================================== -->
        <form method="get" action="movies.php" class="search-form">
            <input type="hidden" name="status" value="<?php echo htmlspecialchars($current_status); ?>">
            <input type="text" id="search" name="search" placeholder="Search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            
            <button type="button" class="reset-btn" onclick="resetSearch()">Reset</button>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
        
        <!-- ========================================
             MOVIE GRID DISPLAY
             ======================================== -->
        <div class="movie-grid">
            <?php if (!empty($current_page_movies)): ?>
                <?php foreach ($current_page_movies as $movie):
                    $poster = $movie['poster_url'] ? 'images/' . $movie['poster_url'] : 'images/placeholder.png';
                ?>
                <div class="movie-card">
                    <div class="movie-poster-container">
                        <a href="movie_booking.php?movie_id=<?php echo $movie['movie_id']; ?>">
                            <img src="<?php echo $poster; ?>" alt="<?php echo $movie['title']; ?> Poster" class="movie-poster fit-cover">
                        </a>
                    </div>
                    <div class="movie-info">
                        <h3><?php echo $movie['title']; ?></h3>
                        <a href="movie_booking.php?movie_id=<?php echo $movie['movie_id']; ?>" class="btn btn-primary book-now">More Details</a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="grid-column:1/-1;text-align:center;">No movies found.</p>
            <?php endif; ?>
        </div>
        
        <!-- ========================================
             PAGINATION CONTROLS
             ======================================== -->
        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php if ($current_page > 1): ?>
                <a href="?status=<?php echo $current_status; ?>&page=<?php echo $current_page - 1; ?>&search=<?php echo urlencode($searchTerm); ?>" class="page-link">&laquo; Previous</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php if ($i == $current_page): ?>
                    <span class="page-link active"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?status=<?php echo $current_status; ?>&page=<?php echo $i; ?>&search=<?php echo urlencode($searchTerm); ?>" class="page-link"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($current_page < $total_pages): ?>
                <a href="?status=<?php echo $current_status; ?>&page=<?php echo $current_page + 1; ?>&search=<?php echo urlencode($searchTerm); ?>" class="page-link">Next &raquo;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </section>
</main>

<!-- ========================================
     FOOTER INCLUDE
     ======================================== -->
<?php require_once("footer.php"); ?>

<!-- ========================================
     JAVASCRIPT FUNCTIONS
     ======================================== -->
<script>
function resetSearch() {
    // Redirect to movies.php without any search parameters
    window.location.href = 'movies.php';
}
</script>
