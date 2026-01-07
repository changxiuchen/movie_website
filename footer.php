<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-section">
                <div class="footer-brand">
                    <h3>Golden Scene Cinema</h3>
                    <p>Your premier destination for the best movie experience.</p>
                </div>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <?php if (isset($_SESSION["user_id"]) && isset($_SESSION["is_admin"]) && $_SESSION["is_admin"] == 1): ?>
                        <li><a href="admin_dashboard.php">Admin Dashboard</a></li>
                        <li><a href="admin_reservations.php">All Reservations</a></li>
                        <li><a href="admin_movies.php">Manage Movies</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php elseif (isset($_SESSION["user_id"])): ?>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="movies.php">Movies</a></li>
                        <li><a href="reservations.php">My Reservations</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="movies.php">Movies</a></li>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="signup.php">Sign Up</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact Us</h3>
                <ul>
                    <li><span class="icon">📞</span> +65 88996677</li>
                    <li><span class="icon">✉️</span> meow@gmail.com</li>
                    <li><span class="icon">📍</span> 369 Movie Street, Singapore</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Golden Scene Cinema. All rights reserved.</p>
        </div>
    </div>
</footer>
</body>
</html>