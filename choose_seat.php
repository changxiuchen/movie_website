<?php 
require_once("dbinfo.php");
require_once("header.php"); 

// Get booking details from URL parameters
$movie_id = isset($_GET['movie_id']) ? (int)$_GET['movie_id'] : 0;
$selected_date = isset($_GET['selected_date']) ? $_GET['selected_date'] : '';
$selected_theater = isset($_GET['selected_theater']) ? (int)$_GET['selected_theater'] : 0;
$selected_theater_name = isset($_GET['selected_theater_name']) ? $_GET['selected_theater_name'] : '';
$selected_time = isset($_GET['selected_time']) ? $_GET['selected_time'] : '';

// Get movie data from database
$movie = null;
if ($movie_id > 0) {
    $query = "SELECT * FROM movies WHERE movie_id = $movie_id;";
    $result = $mysqli->query($query);
    if ($result) {
        $movie = $result->fetch_assoc();
    }
}

// Redirect if required parameters are missing
if (!$movie || !$selected_date || !$selected_theater || !$selected_time) {
    header("Location: movies.php");
    exit();
}

// Format date for display
$display_date = date('D j M', strtotime($selected_date));

// Get list of seats that are already booked
$occupied_seats = [];
$time_for_query = $selected_time;

// Convert 12-hour time format to 24-hour for database
$time_parts = explode(' ', $selected_time);  // Split time and AM/PM
$time_only = $time_parts[0];  // Get just the time part (e.g., "2:30")

// Handle PM times (add 12 hours, except for 12:00 PM)
if (count($time_parts) > 1 && $time_parts[1] == 'PM' && $time_only != '12:00') {
    $hour = (int)explode(':', $time_only)[0] + 12;  // Add 12 to convert to 24-hour
    $time_only = $hour . ':' . explode(':', $time_only)[1] . ':00';
} 
// Handle 12:00 AM (convert to 00:00)
else if (count($time_parts) > 1 && $time_parts[1] == 'AM' && $time_only == '12:00') {
    $time_only = '00:00:00';
} 
// Handle AM times and 12:00 PM (just add seconds)
else {
    $time_only = $time_only . ':00';
}
// Query database for already booked seats
$query = "SELECT seats FROM reservations WHERE movie_id = $movie_id AND date = '$selected_date' AND time = '$time_only' AND theater_id = $selected_theater;";
$result = $mysqli->query($query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $seats = explode(',', $row['seats']);
        foreach ($seats as $seat) {
            $seat = trim($seat);
            if ($seat) $occupied_seats[] = $seat;
        }
    }
}
?>

<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/choose_seat.css">

<main class="choose-seat-main">
    <div class="page-header">
        <div class="header-content">
            <h1 class="page-title">Select Your Seats</h1>
            <p class="page-subtitle">Choose the perfect spot for your movie experience</p>
        </div>
    </div>

    <section class="booking-section">
        <div class="booking-details">
            <div class="movie-poster-container">
                <div class="movie-poster">
                    <img src="images/<?php echo htmlspecialchars($movie['poster_url']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?> Poster">
                    <div class="poster-overlay"></div>
                </div>
            </div>
            <div class="booking-details-content">
                <div class="movie-info">
                    <h1 class="movie-title"><?php echo htmlspecialchars($movie['title']); ?></h1>
                    <div class="movie-meta">
                        <span class="genre-tag"><?php echo htmlspecialchars($movie['genre']); ?></span>
                        <span class="duration-tag"><?php echo htmlspecialchars($movie['running_time']); ?></span>
                    </div>
                </div>
                
                <div class="booking-info">
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="info-icon">🎬</div>
                            <div class="info-content">
                                <label>Theater</label>
                                <span><?php echo htmlspecialchars($selected_theater_name); ?></span>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">🗓️</div>
                            <div class="info-content">
                                <label>Date & Time</label>
                                <span><?php echo strtoupper($display_date) . ', ' . $selected_time; ?></span>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">🌐</div>
                            <div class="info-content">
                                <label>Language</label>
                                <span><?php echo htmlspecialchars($movie['language']); ?></span>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">💲</div>
                            <div class="info-content">
                                <label>Price per Ticket</label>
                                <span class="price">$<?php echo number_format($movie['price_per_ticket'], 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <form action="reservation_process.php" method="POST" id="seat-form">
            <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
            <input type="hidden" name="selected_date" value="<?php echo $selected_date; ?>">
            <input type="hidden" name="selected_theater" value="<?php echo $selected_theater; ?>">
            <input type="hidden" name="selected_theater_name" value="<?php echo htmlspecialchars($selected_theater_name); ?>">
            <input type="hidden" name="selected_time" value="<?php echo $selected_time; ?>">
            <input type="hidden" name="selected_seats" id="selected-seats-input" value="">
            
            <div class="seating-chart">
                <div class="chart-header">
                    <h2>Choose Your Seat</h2>
                    <p class="chart-subtitle">Click on available seats to select them</p>
                </div>
                <div class="screen">Screen</div>
                <div class="seat-legend">
                    <span class="legend-item"><span class="legend-seat available"></span>Available</span>
                    <span class="legend-item"><span class="legend-seat selected"></span>Selected</span>
                    <span class="legend-item"><span class="legend-seat occupied"></span>Occupied</span>
                </div>
                <div class="seats">
                    <?php
                    // Generate seat rows (A, B, C)
                    $rows = ['G','F','E','D','C', 'B', 'A'];
                    $numSeats = 13  ;
                    foreach ($rows as $row):
                    ?>
                    <div class="row">
                        <span class="row-label"><?php echo $row; ?></span>
                        <?php for ($i = 1; $i <= $numSeats; $i++): 
                            $seat_id = $row . $i;
                            $is_occupied = in_array($seat_id, $occupied_seats);
                        ?>
                        <button type="button" class="seat<?php if ($is_occupied) echo ' occupied-seat'; ?>" data-seat="<?php echo $seat_id; ?>" aria-label="Row <?php echo $row; ?>, Seat <?php echo $i; ?>" tabindex="0" <?php if ($is_occupied) echo 'disabled aria-disabled="true"'; ?>>
                            <?php echo $i; ?>
                            <span class="seat-tooltip">Row <?php echo $row; ?>, Seat <?php echo $i; ?></span>
                        </button>
                        <?php endfor; ?>
                        <span class="row-label"><?php echo $row; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="booking-summary">
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
                        <span class="summary-value"><?php echo $display_date; ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Theater:</span>
                        <span class="summary-value"><?php echo htmlspecialchars($selected_theater_name); ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Time:</span>
                        <span class="summary-value"><?php echo $selected_time; ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Selected Seats:</span>
                        <span class="summary-value" id="selected-seats-display">None</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Ticket(s):</span>
                        <span class="summary-value" id="ticket-count">0</span>
                    </div>
                    <div class="summary-item total-item">
                        <span class="summary-label">Total:</span>
                        <span class="summary-value total-price">$<span id="total-price">0.00</span></span>
                    </div>
                </div>
                <button type="submit" id="confirm-btn" disabled>Confirm Booking</button>
            </div>
        </form>
    </section>
</main>

<script src="jq/jquery.js"></script>
<script>
$(document).ready(function() {
    // Initialize variables for seat selection and pricing
    var selectedSeats = []; // Array to store selected seat IDs
    var pricePerTicket = <?php echo $movie['price_per_ticket']; ?>; // Get price from PHP
    var occupiedSeats = <?php echo json_encode($occupied_seats); ?>; // Get occupied seats from PHP

    // Mark occupied seats as disabled and styled
    $('.seat').each(function() {
        var seatId = $(this).data('seat'); // Get seat ID from data attribute
        if (occupiedSeats.indexOf(seatId) !== -1) { // Check if seat is occupied
            $(this).addClass('occupied-seat').prop('disabled', true).attr('aria-disabled', 'true'); // Disable and style occupied seat
        }
    });

    // Handle seat selection with toggle functionality and animation
    $('.seat').on('click', function(e) {
        var seatId = $(this).data('seat'); // Get clicked seat ID
        if ($(this).hasClass('occupied-seat')) return; // Ignore if seat is occupied
        if ($(this).hasClass('selected-seat')) { // If seat is already selected
            $(this).removeClass('selected-seat'); // Remove selection styling
            selectedSeats = selectedSeats.filter(function(seat) { return seat !== seatId; }); // Remove from selected array
        } else { // If seat is not selected
            $(this).addClass('selected-seat'); // Add selection styling
            selectedSeats.push(seatId); // Add to selected array
            // Create pop animation effect
            $(this).css({transform: 'scale(1.18)'}).animate({transform: 'scale(1)'}, 200, function() {
                $(this).css({transform: ''}); // Reset transform after animation
            });
        }
        updateBookingSummary(); // Update summary display
    });

    // Show/hide seat tooltips on hover and focus
    $('.seat').on('mouseenter focus', function() {
        $(this).find('.seat-tooltip').fadeIn(120); // Show tooltip with fade effect
    }).on('mouseleave blur', function() {
        $(this).find('.seat-tooltip').fadeOut(120); // Hide tooltip with fade effect
    });

    // Function to update booking summary with selected seats and pricing
    function updateBookingSummary() {
        var $selectedSeatsDisplay = $('#selected-seats-display'); // Get seats display element
        var $ticketCount = $('#ticket-count'); // Get ticket count element
        var $totalPrice = $('#total-price'); // Get total price element
        var $confirmBtn = $('#confirm-btn'); // Get confirm button
        var $selectedSeatsInput = $('#selected-seats-input'); // Get hidden input for form
        if (selectedSeats.length > 0) { // If seats are selected
            $selectedSeatsDisplay.text(selectedSeats.join(', ')); // Display selected seats
            $ticketCount.text(selectedSeats.length); // Show ticket count
            $totalPrice.text((selectedSeats.length * pricePerTicket).toFixed(2)); // Calculate and show total price
            $confirmBtn.prop('disabled', false); // Enable confirm button
            $selectedSeatsInput.val(selectedSeats.join(',')); // Set hidden input value
        } else { // If no seats selected
            $selectedSeatsDisplay.text('None'); // Show "None" for seats
            $ticketCount.text('0'); // Show 0 tickets
            $totalPrice.text('0.00'); // Show $0.00 total
            $confirmBtn.prop('disabled', true); // Disable confirm button
            $selectedSeatsInput.val(''); // Clear hidden input
        }
    }

    // Show loading message when form is submitted
    $('#seat-form').submit(function() {
        $('#confirm-btn').text('Processing...'); // Change button text to show processing
    });

    
});
</script>

<?php require_once("footer.php"); ?>