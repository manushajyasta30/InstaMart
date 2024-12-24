<?php
session_start(); // Start the session to check if the user is logged in
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InstaMart - Admin Home</title>
    <link rel="stylesheet" href="css/adminhome_styles.css">
    <link rel="stylesheet" href="css/header_style.css">
    <link rel="stylesheet" href="css/footer_style.css">
</head>
<body>

<!-- Header -->
<header>
    <div class="logo">
        <img src="images/logo.jpg" alt="InstaMart Logo" class="logo-img">
        <span>InstaMart</span>
    </div>
    <nav>   
        <ul>
        <li><a href="admin_home.php">Home</a></li>
        <?php if (isset($_SESSION['user_email'])): ?>
            <li><a href="addStock.php">Add Product</a></li>
            <li><a href="updateStock.php">Update Product</a></li>
            <li><a href="shippingDetails.php">Shipping Details</a></li>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php">Logout</a></li>
            <?php else: ?>
                    <li><a href="index.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<!-- Main Content Area -->
<div id="main-content" class="content">
    <!-- Welcome Message Section -->
    <section class="welcome-section">
        <div class="content">
            <h1>Welcome to InstaMart, Merchants!</h1>
            <p>We are excited to have you join our platform. Here, you can showcase your products and reach a wide audience.</p>
            <p>Get started by exploring the services we offer to help your business grow!</p>
            <a href="#services" class="cta-button">Explore Services</a>
        </div>
    </section>
</div>

<!-- Footer -->
<?php include_once('footer.php'); ?>
</body>
</html>
