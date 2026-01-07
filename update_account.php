<!DOCTYPE html>
<?php
session_start();
require_once 'dbinfo.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
// Fetch user info including the profile picture
$result = $mysqli->query("SELECT email, mobile, profile_picture FROM users WHERE user_id = '$user_id'");
$user = $result->fetch_assoc();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Account - Golden Scene</title>
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/auth.css"> 
</head>
<body>
    <?php require_once("header.php"); ?>
    
    <main>
        <section class="form-section">
            <h1 class="page-title">Update Account</h1>
            
            <?php if (isset($_SESSION['update_error'])): ?>
                <div class="error-message">
                    <?php 
                    echo $_SESSION['update_error'];
                    unset($_SESSION['update_error']);
                    ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['update_success'])): ?>
                <div class="success-message">
                    <?php 
                    echo $_SESSION['update_success'];
                    unset($_SESSION['update_success']);
                    ?>
                </div>
            <?php endif; ?>

            <form action="update_account_process.php" method="post" enctype="multipart/form-data">
                
                <?php if (!empty($user['profile_picture'])): ?>
                    <img src="images/<?php echo $user['profile_picture']; ?>" class="profile-preview" alt="Current Profile Picture">
                <?php endif; ?>

                <div class="file-input-group">
                    <label for="profile_pic">Profile Picture</label>
                    <br>
                    <input type="file" id="profile_pic" name="profile_pic">
                </div>

                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                
                <label for="mobile">Mobile number</label>
                <input type="text" id="mobile" name="mobile"  value="<?php echo htmlspecialchars($user['mobile']); ?>" required>
                
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="reservations.php">Back to Profile</a>
            </form>
        </section>
    </main>
    <?php require_once("footer.php"); ?>
</body>
</html> 