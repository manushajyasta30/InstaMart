<?php

session_start(); // Start the session to check if the user is logged in

?>

<?php



// Include the database connection file

include('db.php');



// Check if form is submitted

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST['email'];

    $name = $_POST['name'];

    $reason = $_POST['reason'];

    $description = $_POST['description'];



    try {

        if(!(empty($email) && empty($name) && empty($reason) && empty($description))){

            $sql = "INSERT INTO submissions (email, name, reason, description) VALUES (:email, :name, :reason, :description)";

        

            $stmt = $conn->prepare($sql);

                  

             

            // Bind parameters

            $stmt->bindParam(':email', $email);

            $stmt->bindParam(':name', $name);

            $stmt->bindParam(':reason', $reason);

            $stmt->bindParam(':description', $description);

    

            // Execute the query

         $stmt->execute();

    

        }

        } catch (PDOException $e) {

        echo "Error: " . $e->getMessage();

    }



}

?>





<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>InstaMart - Home</title>

    <link rel="stylesheet" href="css/home_style.css">

</head>

<body>



    <!-- Header -->

    <header>

        <div class="logo">

            <img src="images/logo.jpg" alt="Logo"/>

            <span>InstaMart</span>

        </div>

        <nav>

            <ul>

                <li><a href="#home">Home</a></li>

                <li><a href="#categories">Categories</a></li>

                <li><a href="#contactus">Contact Us</a></li>

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



    <!-- Hero Section -->

    <section class="hero" id="home">

        <div class="hero-content">

            <h1>Welcome to InstaMart</h1>

            <p style="color:blue">Your one-stop shop for the best deals!</p>

            <a href="#featured-products" class="cta-button">Shop Now</a>

        </div>

    </section>

    



    <!-- Slider Section (Deals) -->

    <section class="slider" id="deals">

        <div class="slides">

            <img src="images/logo.jpg" alt="Slide 1" class="active">

            <!-- <img src="images/BD_salee.jpg" alt="Slide 2"> -->

            <img src="images/mens.jpg" alt="Slide 2">

            <img src="images/gif.gif" alt="Slide 3">

            <img src="images/sale1.webp" alt="Slide 4">

            <img src="images/brand.webp" alt="Slide 5">

            <img src="images/free_resized.png" alt="Slide 6">

            <img src="images/kids-baby-sale.avif" alt="Slide 7">

        </div>

        <!-- Navigation buttons -->

        <button class="prev" onclick="changeSlide(-1)">&#10094;</button>

        <button class="next" onclick="changeSlide(1)">&#10095;</button>

    </section>



    <script src="script.js"></script>



    <!-- Featured Products -->

    <section id="featured-products">

        <h2>Featured Products</h2>

        <div class="product-grid">

            <img src="images/featureproduct1.jpg" class="product-card" width="300px" height="150px" alt="Slide 2">

            <!-- <div class="product-card">Product 1</div> -->

            <img src="images/featureproduct2.jpg" class="product-card" width="300px" height="150px" alt="Slide 2">

            <!-- <div class="product-card">Product 2</div> -->

            <img src="images/featureproduct3.jpg" class="product-card" width="300px" height="150px" alt="Slide 2">

            <!-- <div class="product-card">Product 3</div> -->

            <img src="images/featureproduct4.jpg" class="product-card" width="300px" height="150px" alt="Slide 2">

            <!-- <div class="product-card">Product 4</div> -->

        </div>

    </section>



    <!-- Categories Overview -->

    <section id="categories">

        <h2>Shop by Categories</h2>

        <div class="category-grid">

        <div class="category-card" onclick="fetchCategory('Electronics')">Electronics</div>

        <div class="category-card" onclick="fetchCategory('Fashion')">Fashion</div>

        <div class="category-card" onclick="fetchCategory('Home and Garden')">Home & Garden</div>

        <div class="category-card" onclick="fetchCategory('Beauty')">Beauty</div>

        </div>

    </section>

    <script>

    function fetchCategory(category) {

    window.location.href = `listOfItems.php?category=${encodeURIComponent(category)}`;

    }

</script>



<section class="contactus" id="contactus">

    <h2>Contact Us</h2>

    <form action="" method="post">



        <label for="email">Email: </label>

        <input type="email" name="email" placeholder="Enter your email" required><br>

        

        <label for="name">Name: </label>

        <input type="text" name="name" placeholder="Enter your name" required><br>

        

        <label for="reason">Reason: </label>

        <input type="text" name="reason" placeholder="Enter your reason" required><br>

        

        <label>Description: </label><br>

        <textarea id="description" name="description" rows="10" cols="70"></textarea><br>

        

        <input type="submit" value="Submit" onclick="return confirm('Are you sure you want to submit this query ?');"/>

    </form>

</section>

    <!-- Footer -->

    <footer>

        <div class="footer-container">

            <div class="footer-section about-us">

                <h3>About Us</h3>

                <p>We are a leading e-commerce company offering the latest fashion trends. Our goal is to provide high-quality products with exceptional customer service.</p>

            </div>



            <div class="footer-section social-media">

                <h3>Follow Us</h3>

                <ul>

                    <li><a href="#" target="_blank">Facebook</a></li>

                    <li><a href="#" target="_blank">Twitter</a></li>

                    <li><a href="#" target="_blank">Instagram</a></li>

                     <li><a href="#" target="_blank">YouTube</a></li>

                </ul>

            </div>



            <div class="map-container">

                <h3>Location</h3>

                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2926.0525749493666!2d-73.98513038453956!3d40.7580!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c2588e4bb8b7c3%3A0xa9b457e51cf8b92e!2sTimes%20Square%2C%20New%20York%2C%20NY%2010019%2C%20USA!5e0!3m2!1sen!2sus!4v1631794698819!5m2!1sen!2sus"

                        width="40%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>

            </div>



            <div class="footer-section contact-info">

                <h3>Contact Info</h3>

                <ul>

                    <li>Email: <a href="mailto:info@ecommerce.com">info@ecommerce.com</a></li>

                    <li>Phone: <a href="tel:+1234567890">+1234567890</a></li>

                    <li>Address: 123 E-commerce St, City, Country</li>

                </ul>

            </div>

        </div>



        <div class="footer-bottom">

            <p>&copy; 2024 E-commerce Company. All rights reserved.</p><br>

            <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a>

        </div>

    </footer>



</body>

</html>

