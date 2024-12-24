<?php
session_start();

 // Check if user is logged in (i.e., the session contains a user)
 if (!isset($_SESSION['user_email'])) {
    header('Location: home.php');
    exit();
}

// Database connection
include('db.php');

try {
    // Fetch cart items for the logged-in user
    $stmt = $conn->prepare("SELECT c.productid, c.count, p.productname, p.price, p.productimage
                            FROM cart c 
                            JOIN products p ON c.productid = p.productid
                            WHERE c.buyeremail = :buyeremail");
    $stmt->bindParam(':buyeremail', $_SESSION['user_email']);
    $stmt->execute();
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

$deliveryCost = rand (10*10, 20*10) / 10; // Flat delivery cost (can be dynamic based on location)
$packingCost = rand (5*10, 10*10) / 10; // Flat packing cost (can be dynamic based on items or location)

$totalPrice = 0;
foreach ($cartItems as $item) {
    $totalPrice += $item['price'] * $item['count'];
}
if($totalPrice!=0){
    $totalAmount = $totalPrice + $deliveryCost + $packingCost;
}else{
    $totalAmount=0;
    $deliveryCost=0;
    $packingCost=0;
}
$_SESSION['totalAmount']=$totalAmount;



} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="css/cart_style.css">
    <link rel="stylesheet" href="css/header_style.css">
    <link rel="stylesheet" href="css/footer_style.css">
</head>
<body>

<?php include('header.php'); ?>

<main>
    <h1>Your Shopping Cart</h1>
    <section class="cart-items">
        <?php if ($cartItems && count($cartItems) > 0): ?>
            <?php foreach ($cartItems as $item): ?>
                <div class="cart-item">
                    <!-- Product Image -->
                    <img src="data:image;base64,<?php echo $item['productimage']; ?>" alt="<?php echo $item['productname']; ?>" class="cart-item-image">
                    
                    <!-- Product Details -->
                    <div class="cart-item-details">
                        <h3><?php echo $item['productname']; ?></h3>
                        <p>Price: $<?php echo number_format($item['price'], 2); ?></p>
                        <p>Quantity: <?php echo $item['count']; ?></p>
                    </div>
                    
                    <!-- Remove Button -->
                    <form action="remove_from_cart.php" method="POST" class="remove-item-form">
                        <input type="hidden" name="productid" value="<?php echo $item['productid']; ?>">
                        <button type="submit" class="remove-item-btn">Remove</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Your cart is empty. <a href="listOfItems.php">Continue Shopping</a></p>
        <?php endif; ?>
    </section>

    <section class="cart-summary">
        <h2>Order Summary</h2>
        <div class="summary-item">
            <span>Price:</span>
            <span>$<?php echo number_format($totalPrice, 2); ?></span>
        </div>
        <div class="summary-item">
            <span>Delivery:</span>
            <span>$<?php echo number_format($deliveryCost, 2); ?></span>
        </div>
        <div class="summary-item">
            <span>Packing:</span>
            <span>$<?php echo number_format($packingCost, 2); ?></span>
        </div>
        <div class="summary-item total">
            <span>Total Amount:</span>
            <span>$<?php echo number_format($totalAmount, 2); ?></span>
        </div>

        <br><a href='shopping_cart.php' class="checkout-btn"> Checkout</a>
    </section>
</main>

<?php include('footer.php')?>
</body>
</html>
