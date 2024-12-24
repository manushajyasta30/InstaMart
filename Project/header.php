<?php if (isset($_SESSION['account_type']) && $_SESSION['account_type']=='buyer'): ?>

    <header>

        <div class="logo">

            <link rel="stylesheet" href="css/main.css">

            <img src="images/logo.jpg" alt="InstaMart Logo" class="logo-img">

            <span class="brand-name">InstaMart</span>

        </div>

        <nav>

            <ul class="nav-links">

                <li><a href="home.php#home">Home</a></li>

                <li><a href="home.php#categories">Categories</a></li>

                <li><a href="home.php#contactus">Contact Us</a></li>

                <?php if (isset($_SESSION['user_email'])): ?>

                    <li><a href="listOfItems.php">Shopping</a></li>

                    <li><a href="cart.php">Cart</a></li>

                    <li><a href="orders.php">Orders</a></li>

                    <li><a href="profile.php">User</a></li>

                    <li><a href="logout.php">Logout</a></li>

                <?php else: ?>

                    <li><a href="index.php">Login</a></li>

                <?php endif; ?>

            </ul>

        </nav>

    </header>

<?php else: ?>

    <header>

        <div class="logo">

            <link rel="stylesheet" href="css/main.css">

            <img src="images/logo.jpg" alt="InstaMart Logo" class="logo-img">

            <span class="brand-name">InstaMart</span>

        </div>

        <nav>   

            <ul class="nav-links">

                <li><a href="admin_home.php">Home</a></li>

                <li><a href="addStock.php">Add Product</a></li>

                <li><a href="updateStock.php">Update Product</a></li>

                <li><a href="shippingDetails.php">Shipping Details</a></li>

                <li><a href="profile.php">Profile</a></li>

                <li><a href="logout.php">Logout</a></li>

            </ul>

        </nav>

    </header>

<?php endif; ?>

