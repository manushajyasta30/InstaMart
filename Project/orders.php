<?php
session_start();

 // Check if user is logged in
 if (!isset($_SESSION['user_email'])) {
    // Redirect to login page if not logged in
    header('Location: home.php');
    exit();
}

include('db.php');

try {

    $sql = "SELECT * FROM orders WHERE buyerEmail = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $_SESSION['user_email']);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch product details for each order
    $orderDetails = [];
    foreach ($orders as $order) {
        $productIDs = explode(",", $order['productID']);  // Split the product IDs

        // Get details for each product in the order
        $products = [];
        foreach ($productIDs as $productID) {
            $productSql = "SELECT * FROM products WHERE productid = :productID";
            $productStmt = $conn->prepare($productSql);
            $productStmt->bindParam(':productID', $productID);
            $productStmt->execute();
            $products = $productStmt->fetchAll(PDO::FETCH_ASSOC);
            //$products[] = $product;
        }

        // Combine order data with product details
        $orderDetails[] = [
            'order' => $order,
            'products' => $products
        ];
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/header_style.css">
    <link rel="stylesheet" href="css/footer_style.css">
    <title>Your Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .orders-container {
            width: 80%;
            margin-top: 100px;
        }
        .order-item {
            border: 1px solid #ddd;
            padding: 20px;
            margin: 10px 0;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
        }
        .order-item h4 {
            margin-top: 0;
        }
        .product-item {
            display: flex;
            margin-top: 10px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .product-image {
            flex: 1;
            padding-right: 20px;
        }
        .product-details {
            flex: 3;
        }
        .product-image img {
            max-width: 100%;
            width: 190px;
            height: 200px;
            border-radius: 8px;
        }
        .product-item p {
            margin: 5px 0;
        }
        .order-details {
            margin-top: 20px;
        }
    </style>
</head>
<body>


    <?php include('header.php') ?>

    <div class="orders-container">
        <h1>Your Orders</h1>
        
        <?php if (count($orderDetails) > 0): ?>
            <?php foreach ($orderDetails as $orderDetail): ?>
                <?php $order = $orderDetail['order']; ?>
                
                <div class="order-item">
                    <h4>Order ID: <?php echo htmlspecialchars($order['id']); ?></h4>
                    <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['orderDate']); ?></p>
                    <p><strong>Total Amount:</strong> $<?php echo htmlspecialchars($order['totalAmount']); ?></p>
                    <p><strong>Shipping Status:</strong> <?php echo htmlspecialchars($order['shippingStatus']); ?></p>
                    
                    <div class="order-details">
                        <h5>Products in this Order</h5>
                        <?php foreach ($orderDetail['products'] as $product): ?>
                            <div class="product-item">
                                <!-- Product Image (left side) -->
                                <div class="product-image">
                                    <img src="data:image;base64,<?php echo htmlspecialchars($product['productimage']); ?>" alt="Product Image">
                                </div>
                                
                                <!-- Product Details (right side) -->
                                <div class="product-details">
                                    <p><strong>Product Name:</strong> <?php echo htmlspecialchars($product['productname']); ?></p>
                                    <p><strong>Description:</strong> <?php echo htmlspecialchars($product['description']); ?></p>
                                    <p><strong>Price:</strong> $<?php echo htmlspecialchars($product['price']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="shipping-details">
                        <h5>Shipping Information</h5>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
                        <p><strong>City:</strong> <?php echo htmlspecialchars($order['city']); ?></p>
                        <p><strong>State:</strong> <?php echo htmlspecialchars($order['state']); ?></p>
                        <p><strong>Pin Code:</strong> <?php echo htmlspecialchars($order['pin']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No orders placed yet.</p>
        <?php endif; ?>

    </div>

    <?php include('footer.php') ?>
</body>
</html>
