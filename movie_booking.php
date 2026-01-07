<?php 
require_once("dbinfo.php");
require_once("header.php"); 

// Handle quick booking from homepage
if (isset($_GET['movie_id'], $_GET['selected_date'], $_GET['selected_theater'])) {
    $movie_id = (int)$_GET['movie_id'];
    $date = urlencode($_GET['selected_date']);
    $theatre = (int)$_GET['selected_theater'];
    header("Location: choose_seat.php?movie_id=$movie_id&date=$date&theatre=$theatre");
    exit();
}

// Get movie ID from URL parameter
$movie_id = (int)($_GET['movie_id'] ?? 0);

// Fetch movie details from database
$movie = null;
if ($movie_id > 0) {
    $result = $mysqli->query("SELECT * FROM movies WHERE movie_id = $movie_id");
    $movie = $result->fetch_assoc();
}

// Redirect if movie not found
if (!$movie) {
    header("Location: movies.php");
    exit();
}
?>
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/booking.css">

<main class="movie-booking-main">
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">Book Your Movie</h1>
            <p class="page-subtitle">Select your preferred date, time, and theater</p>
        </div>
    </div>

    <section class="booking-section">
        <div class="movie-info-container">
            <div class="movie-poster-container">
                <div class="movie-poster">
                    <img src="images/<?php echo htmlspecialchars($movie['poster_url']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?> Poster">
                    <div class="poster-overlay"></div>
                </div>
            </div>
            
            <div class="movie-details">
                <div class="movie-info">
                    <h2 class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></h2>
                    <div class="movie-meta">
                        <span class="genre-tag"><?php echo htmlspecialchars($movie['genre']); ?></span>
                        <span class="duration-tag"><?php echo htmlspecialchars($movie['running_time']); ?></span>
                    </div>
                </div>
                
                <div class="movie-details-info">
                    <div class="details-grid">
                        <div class="detail-item">
                            <div class="detail-icon">📅</div>
                            <div class="detail-content">
                                <label>Release Date</label>
                                <span><?php echo date('d M Y', strtotime($movie['release_date'])); ?></span>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">🌐</div>
                            <div class="detail-content">
                                <label>Language</label>
                                <span><?php echo htmlspecialchars($movie['language']); ?></span>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <div class="detail-icon">💲</div>
                            <div class="detail-content">
                                <label>Price per Ticket</label>
                                <span class="price">$<?php echo number_format($movie['price_per_ticket'], 2); ?></span>
                            </div>
                        </div>
                        
                        <div class="detail-item synopsis-item">
                            <div class="detail-icon">📖</div>
                            <div class="detail-content">
                                <label>Synopsis</label>
                                <span><?php echo htmlspecialchars($movie['synopsis']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <form action="choose_seat.php" method="GET" id="booking-form">
            <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
            
            <div class="date-selector">
                <div class="selector-header">
                    <h2>Select Date</h2>
                    <p class="selector-subtitle">Choose your preferred viewing date</p>
                </div>
                <div class="calendar">
                    <?php
                    // Generate next 7 days
                    for ($i = 0; $i < 7; $i++) {
                        $date = date('Y-m-d', strtotime("+$i days"));
                        $dayName = date('D', strtotime($date));
                        $dayNum = date('j', strtotime($date));
                        $month = date('M', strtotime($date));
                    ?>
                    <button type="button" class="date-btn" data-date="<?php echo $date; ?>">
                        <span class="day-name"><?php echo strtoupper($dayName); ?></span>
                        <span class="day-number"><?php echo $dayNum; ?></span>
                        <span class="month"><?php echo strtoupper($month); ?></span>
                    </button>
                    <?php } ?>
                </div>
            </div>
            
            <div class="time-cinema">
                <div class="selector-header">
                    <h2>Select Time & Cinema</h2>
                    <p class="selector-subtitle">Choose your preferred theater and showtime</p>
                </div>
                <div class="theater-times">
                    <?php
                    // Get theaters from database
                    $theaters_result = $mysqli->query("SELECT * FROM theaters ORDER BY name");
                    $times = ['10:00 AM', '12:00 PM', '3:10 PM', '6:30 PM', '9:50 PM'];
                    
                    while ($theater = $theaters_result->fetch_assoc()):
                    ?>
                    <div class="theater-group">
                        <div class="theater-name"><?php echo htmlspecialchars($theater['name']); ?></div>
                        <div class="times">
                            <?php foreach ($times as $time): ?>
                            <button type="button" class="time-btn" 
                                    data-theater="<?php echo $theater['theater_id']; ?>"
                                    data-theater-name="<?php echo htmlspecialchars($theater['name']); ?>"
                                    data-time="<?php echo $time; ?>">
                                <?php echo $time; ?>
                            </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            
            <div class="booking-summary" style="display: none;">
                <div class="summary-header">
                    <h3>Booking Summary</h3>
                    <div class="summary-icon">📋</div>
                </div>
                <div class="summary-content">
                    <div class="summary-item">
                        <span class="summary-label">Movie:</span>
                        <span class="summary-value"><?php echo htmlspecialchars($movie['title']); ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Date:</span>
                        <span class="summary-value" id="summary-date"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Theater:</span>
                        <span class="summary-value" id="summary-theater"></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Time:</span>
                        <span class="summary-value" id="summary-time"></span>
                    </div>
                    <div class="summary-item total-item">
                        <span class="summary-label">Price:</span>
                        <span class="summary-value total-price">$<span id="summary-price"><?php echo $movie['price_per_ticket']; ?></span></span>
                    </div>
                </div>
                <button type="submit" class="proceed-btn">Choose Seats</button>
            </div>
        </form>
    </section>
</main>

<script src="jq/jquery.js"></script>
<script>
$(document).ready(function() {
    // Initialize variables to store user selections
    var selectedDate = '';
    var selectedTheater = '';
    var selectedTheaterName = '';
    var selectedTime = '';

    // Handle date button clicks - remove previous selection and highlight current
    $('.date-btn').on('click', function() {
        $('.date-btn').removeClass('selected'); // Remove selection from all date buttons
        $(this).addClass('selected'); // Add selection to clicked button
        selectedDate = $(this).data('date'); // Store selected date
        updateSummary(); // Update booking summary
    });

    // Handle time/theater button clicks - remove previous selection and highlight current
    $('.time-btn').on('click', function() {
        $('.time-btn').removeClass('selected'); // Remove selection from all time buttons
        $(this).addClass('selected'); // Add selection to clicked button
        selectedTheater = $(this).data('theater'); // Store theater ID
        selectedTheaterName = $(this).data('theater-name'); // Store theater name
        selectedTime = $(this).data('time'); // Store selected time
        updateSummary(); // Update booking summary
    });

    // Function to update booking summary display
    function updateSummary() {
        var $summaryDiv = $('.booking-summary'); // Get summary container
        var $summaryDate = $('#summary-date'); // Get date display element
        var $summaryTheater = $('#summary-theater'); // Get theater display element
        var $summaryTime = $('#summary-time'); // Get time display element
        
        // Check if all selections are made
        if (selectedDate && selectedTheater && selectedTime) {
            $summaryDate.text(selectedDate); // Display selected date
            $summaryTheater.text(selectedTheaterName); // Display selected theater
            $summaryTime.text(selectedTime); // Display selected time
            $summaryDiv.show(); // Show summary section
            
            // Create hidden form inputs for form submission
            var $dateInput = $('input[name="selected_date"]'); // Check if date input exists
            var $theaterInput = $('input[name="selected_theater"]'); // Check if theater input exists
            var $theaterNameInput = $('input[name="selected_theater_name"]'); // Check if theater name input exists
            var $timeInput = $('input[name="selected_time"]'); // Check if time input exists
            
            // Create date input if it doesn't exist
            if ($dateInput.length === 0) {
                $dateInput = $('<input>').attr({
                    type: 'hidden',
                    name: 'selected_date'
                });
                $('#booking-form').append($dateInput); // Add to form
            }
            // Create theater input if it doesn't exist
            if ($theaterInput.length === 0) {
                $theaterInput = $('<input>').attr({
                    type: 'hidden',
                    name: 'selected_theater'
                });
                $('#booking-form').append($theaterInput); // Add to form
            }
            // Create theater name input if it doesn't exist
            if ($theaterNameInput.length === 0) {
                $theaterNameInput = $('<input>').attr({
                    type: 'hidden',
                    name: 'selected_theater_name'
                });
                $('#booking-form').append($theaterNameInput); // Add to form
            }
            // Create time input if it doesn't exist
            if ($timeInput.length === 0) {
                $timeInput = $('<input>').attr({
                    type: 'hidden',
                    name: 'selected_time'
                });
                $('#booking-form').append($timeInput); // Add to form
            }
            
            // Set values for form submission
            $dateInput.val(selectedDate); // Set date value
            $theaterInput.val(selectedTheater); // Set theater ID value
            $theaterNameInput.val(selectedTheaterName); // Set theater name value
            $timeInput.val(selectedTime); // Set time value
        } else {
            $summaryDiv.hide(); // Hide summary if not all selections made
        }
    }
});
</script>

<?php require_once("footer.php"); ?>