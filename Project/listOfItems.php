<?php

session_start();

 if (!isset($_SESSION['user_email'])) {
    // Redirect to login page if not logged in
    header('Location: home.php');
    exit();
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Collection</title>

    <link rel="stylesheet" href="css/listOfItems_styles.css">

    <link rel="stylesheet" href="css/footer_style.css">

    <style>

        header {

            display: flex;

            justify-content: space-between;

            align-items: center;

            padding: 15px;

            background-color: #007bff;

            color: #fff;

        }



        header .logo {

            display: flex;

            align-items: center;

        }



        header .logo img {

            height: 40px; /* Adjust height as needed */

            margin-right: 10px;

        }



        header .logo span {

            font-size: 24px;

            font-weight: bold;

            color: #fff;

        }



        nav ul {

            list-style: none;

            display: flex;

        }



        nav ul li {

            margin: 0 10px;

        }



        nav ul li a {

            color: #fff;

            text-decoration: none;

        }

        /* Checkout Button CSS */

        .checkout {

            display: inline-block;

            padding: 10px 20px;

            background-color: #28a745; /* Green background */

            color: white;

            text-align: center;

            font-size: 16px;

            text-decoration: none;

            border-radius: 5px;

            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

            transition: background-color 0.3s ease;

        }



        .checkout:hover {

            background-color: #218838; /* Darker green on hover */

        }



        .checkout:active {

            background-color: #1e7e34; /* Even darker green on click */

        }



        .checkout-container {

            text-align: center; /* Centers the button horizontally */

            margin-top: 20px; /* Adds some space above the button */

        }



        .no-item {

    text-align: center;          /* Centers the text */

    font-size: 1.2rem;           /* Slightly increase the font size */

    line-height: 1.5;            /* Make the line spacing more readable */

    color: #333;                 /* Dark grey color for the text */

    margin: 20px auto;           /* Adds margin around the paragraph, auto centers it */

    max-width: 800px;            /* Restricts the width for better readability */

    padding: 10px;               /* Add some padding for spacing */

    font-family: 'Arial', sans-serif;  /* Change font to something more modern */

    background-color: #f4f4f4;   /* Soft background color */

    border-radius: 10px;         /* Rounded corners for the paragraph */

    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Slight shadow for the paragraph */

    transition: all 0.3s ease;   /* Smooth transition effect for hover */

}



.no-item:hover {

    background-color: #e9ecef;   /* Slightly darker background color on hover */

    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2); /* Stronger shadow on hover */

    color: #007bff;               /* Change text color on hover */

}

    </style>

    <script src="scripts.js" defer></script>

</head>

<body>



<?php include('header.php')?>



<main>

    <section class="shirt-list">

        <?php

        include('db.php');



        $category = isset($_GET['category']) ? $_GET['category'] : ''; // Retrieve category from URL



        try {



            if ($category) {

                // If category is set, query by category

                $stmt = $conn->prepare("SELECT p.*, c.count FROM products p 

                    LEFT JOIN cart c ON p.productid = c.productid AND c.buyeremail = :user_email

                    WHERE p.categoryname = :category");

                $stmt->bindParam(':category', $category);

            } else {

                // If no category is selected, show all products

                $stmt = $conn->prepare("SELECT p.*, c.count FROM products p 

                    LEFT JOIN cart c ON p.productid = c.productid AND c.buyeremail = :user_email");

            }



$stmt->bindParam(':user_email', $_SESSION['user_email']);

$stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);



            // Check if results were found

            if ($result && count($result) > 0) {

                echo "<div class='product-grid'>";

                // Loop through the result and display each shirt

                foreach ($result as $shirt) {

                    $quantityInCart = $shirt['count'] ?? 0;  // Default to 0 if not in cart

                    echo "<div class='product-card'>

                        <img src='data:image;base64,{$shirt['productimage']}' alt='" . htmlspecialchars($shirt['productname']) . "'>

                        <h3>" . htmlspecialchars($shirt['productname']) . "</h3>

                        <p>Price: $" . number_format($shirt['price'], 2) . "</p>

                        <p>Quantity: " . htmlspecialchars($shirt['stock']) . "</p>

                        <form class='add-to-cart-form' method='POST'>

                            <div class='quantity-controls'>

                                <button type='button' class='decrease-quantity' data-productid='" . htmlspecialchars($shirt['productid']) . "'>-</button>

                                <input type='text' name='quantity' value='" . ($quantityInCart > 0 ? $quantityInCart : 1) . "' id='quantity-" . htmlspecialchars($shirt['productid']) . "' readonly>

                                <button type='button' class='increase-quantity' data-productid='" . htmlspecialchars($shirt['productid']) . "'>+</button>

                            </div>

                            <input type='hidden' name='productid' value='" . htmlspecialchars($shirt['productid']) . "'>

                            <button type='submit' class='add-to-cart' data-productid='" . htmlspecialchars($shirt['productid']) . "'>Add to Cart</button>

                        </form>

                    </div>";

                }

                echo "</div>";

                //<!-- Checkout Button -->

                echo "<div class='checkout-container'>        

                        <a href='cart.php' class='checkout'>Checkout</a>

                    </div>";

            } else {

                // If no results are found, display a message

                echo "<p class='no-item'>No items found in the collection.</p>";

            }



        } catch (PDOException $e) {

            echo 'Connection failed: ' . $e->getMessage();

            exit;

        }

        ?>

    </section>

</main>



<script>

    document.addEventListener("DOMContentLoaded", function () {

        const increaseButtons = document.querySelectorAll(".increase-quantity");

        const decreaseButtons = document.querySelectorAll(".decrease-quantity");

        const addToCartButtons = document.querySelectorAll(".add-to-cart");



        // Disable quantity changes and button once 'Add to Cart' is clicked

        addToCartButtons.forEach(button => {

            button.addEventListener("click", function (e) {

                e.preventDefault(); // Prevent form submission

                const productId = this.getAttribute("data-productid");

                const quantityInput = document.getElementById(`quantity-${productId}`);



                // Disable quantity input and the 'Add to Cart' button

                quantityInput.setAttribute('readonly', true);

                this.textContent = "Added";

                // this.disabled = true;



                // Send the data asynchronously to the server

                const formData = new FormData();

                formData.append('productId', productId);

                formData.append('count', quantityInput.value);



                // Make AJAX request to add product to the cart

                fetch('process_cart.php', {

                    method: 'POST',

                    body: formData

                })

                .then(response => response.json())

                .then(data => {

                    if (data.success) {

                        console.log('Product added to cart');

                    }

                })

                .catch(error => {

                    console.error('Error:', error);

                });

            });

        });



        // Handle increase and decrease quantity actions

        increaseButtons.forEach(button => {

            button.addEventListener("click", function () {

                const productId = this.getAttribute("data-productid");

                const quantityInput = document.getElementById(`quantity-${productId}`);

                let currentQuantity = parseInt(quantityInput.value);

                currentQuantity++;

                quantityInput.value = currentQuantity;

            });

        });



        decreaseButtons.forEach(button => {

            button.addEventListener("click", function () {

                const productId = this.getAttribute("data-productid");

                const quantityInput = document.getElementById(`quantity-${productId}`);

                let currentQuantity = parseInt(quantityInput.value);

                if (currentQuantity > 0) {

                    currentQuantity--;

                    quantityInput.value = currentQuantity;

                }

            });

        });

    });

</script>



<?php include('footer.php') ?>



</body>

</html>

