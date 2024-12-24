<?php
session_start();

 // Check if user is logged in
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
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/header_style.css">
    <link rel="stylesheet" href="css/footer_style.css">
    <style>
        /* General Reset and Body Styling */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fb;
            color: #333;
            line-height: 1.6;
            padding-top: 60px; /* Space for the header */
        }

        /* Main Content */
        .checkout-form {
            margin: 80px auto;
            padding: 20px;
            max-width: 800px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        .address-section, .payment-section {
            margin-bottom: 20px;
        }

        .address-field, .payment-field {
            margin-bottom: 15px;
        }

        label {
            font-size: 14px;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"], input[type="month"], input[type="number"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Success Message */
        .message {
            font-size: 1.5em;
            color: #28a745;
            text-align: center;
            margin-top: 20px;
        }

        .message.error {
            color: #dc3545;
        }

    </style>
</head>
<body>


<?php include('header.php') ?>
    <section class="checkout-form">
    <h2>Complete Your Order</h2>
    <form action="payment.php" method="POST">
        <!-- Delivery Address Section -->
        <div class="address-section">
            <h3>Enter Delivery Address</h3>
            <div class="address-field">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($_SESSION['address']); ?>" required>
            </div>
            <div class="address-field">
                <label for="city">City:</label>
                <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($_SESSION['city']); ?>" required>
            </div>
            <div class="address-field">
                <label for="state">State:</label>
                <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($_SESSION['state']); ?>" required>
            </div>
            <div class="address-field">
                <label for="pin">Pin Code:</label>
                <input type="text" id="pin" name="pin" value="<?php echo htmlspecialchars($_SESSION['pin']); ?>" required>
            </div>
        </div>

        <!-- Payment Section -->
        <div class="payment-section">
            <h3>Enter Payment Details</h3>
            <div class="payment-field">
                <label for="card_number">Card Number:</label>
                <input type="text" id="card_number" name="card_number" placeholder="Enter your card number" required><br>
            </div>
            <div class="payment-field">
                <label for="card_name">Cardholder's Name:</label>
                <input type="text" id="card_name" name="card_name" placeholder="Enter cardholder's name" required><br>
            </div>
            <div class="payment-field">
                <label for="expiry_date">Expiry Date:</label>
                <input type="month" id="expiry_date" name="expiry_date" required><br>
            </div>
            <div class="payment-field">
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" placeholder="Enter CVV" required><br>
            </div>
        </div>

        <button type="submit" class="checkout-btn">Proceed to Checkout</button>
    </form>
</section>
    <a href="listOfItems.php">Continue Shopping</a><br><br>

    <?php include('footer.php') ?>

</body>
</html>