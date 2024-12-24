<?php
session_start();

// Check if the merchant is logged in
if (!isset($_SESSION['user_email'])) {
    die("You must be logged in as a merchant to view this page.");
}

// Get the logged-in merchant's email
$merchantEmail = $_SESSION['user_email'];

include('db.php');

try {
    // Fetch orders that are associated with products from this merchant
    $sql = "SELECT * FROM orders";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Prepare an array to store the relevant product details for this merchant
    $merchantOrders = [];
    foreach ($orders as $order) {
        // Split the product IDs from the order (if multiple products per order)
        $productIDs = explode(",", $order['productID']);
        
        $orderProducts = [];
        foreach ($productIDs as $productID) {
            // Fetch product details based on the product ID
            $productSql = "SELECT * FROM products WHERE productid = :productID AND merchantemail = :merchantEmail";
            $productStmt = $conn->prepare($productSql);
            $productStmt->bindParam(':productID', $productID);
            $productStmt->bindParam(':merchantEmail', $merchantEmail);
            $productStmt->execute();
            $product = $productStmt->fetchAll(PDO::FETCH_ASSOC)[0];

            // If the product belongs to the merchant, add it to the list
            if ($product) {
                $orderProducts[] = [
                    'product_name' => $product['productname'],
                    'shipping_status' => $order['shippingStatus'],
                    'product_description' => $product['description'],
                    'price' => $product['price'],
                    'image' => $product['productimage']
                ];
            }
        }

        if (!empty($orderProducts)) {
            $merchantOrders[] = [
                'order_id' => $order['id'],
                'products' => $orderProducts,
                'current_shipping_status' => $order['shippingStatus']
            ];
        }
    }

    // Handle the form submission to update shipping details
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        foreach ($merchantOrders as $order) {
            // Update the shipping status for the order
            $shippingStatus = $_POST['shipping_status_' . $order['order_id']];
            $updateSql = "UPDATE orders SET shippingStatus = :shippingStatus WHERE id = :orderID";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bindParam(':shippingStatus', $shippingStatus);
            $updateStmt->bindParam(':orderID', $order['order_id']);
            $updateStmt->execute();
        }

        echo "<p>Shipping details updated successfully!</p>";
    }

    $contactSql = "SELECT * FROM submissions";
    $contactStmt = $conn->prepare($contactSql);
    $contactStmt->execute();
    $submissions = $contactStmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Shipping Details</title>
    <link rel="stylesheet" href="css/header_style.css">
    <link rel="stylesheet" href="css/footer_style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            font-size: 22px;
            padding: 20px;
        }
        .order-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .container {
            margin-top: 50px;
            margin-bottom: 50px;
            padding: 20px;
        }
        .order-container {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.order-item {
    display: flex;
    margin-bottom: 20px;
    padding: 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    width: 80%;
    flex-wrap: wrap; /* Ensure the content wraps on smaller screens */
}

.product-image {
    flex: 1 1 200px; /* Allows image to take up 1/4th of the space */
    padding-right: 20px;
    max-width: 200px; /* Ensure image does not grow too large */
}

.product-details {
    flex: 2 1 400px; /* Allows details to take more space */
    padding-left: 20px;
}

.product-image img {
    max-width: 100%;
    height: auto;
    width: 200px;
    border-radius: 8px;
}

.shipping-update-form {
    margin-top: 10px;
}

textarea {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    font-size: 20px;
    resize: vertical;
}

        /* Footer Styling */
footer {
    background-color: #2a3d66;
    color: white;
    padding: 20px 0;
    text-align: center;
}

footer p {
    margin: 0;
    font-size: 1em;
}

footer a {
    color: white;
    text-decoration: none;
    margin-left: 10px;
}

footer a:hover {
    text-decoration: underline;
}

button {
    background-color: #4CAF50; /* Green background */
    color: white; /* White text */
    padding: 15px 30px; /* Larger padding for a bigger button */
    font-size: 18px; /* Increase font size */
    border: none; /* Remove border */
    border-radius: 8px; /* Rounded corners */
    cursor: pointer; /* Pointer cursor on hover */
    transition: background-color 0.3s ease; /* Smooth transition for hover effect */
    margin-top: 20px; /* Space above the button */
}

button:hover {
    background-color: #45a049; /* Darker green on hover */
}

    </style>
</head>
<body>

<?php include('header.php') ?>

<div class="container">

    <h1>Update Shipping Details</h1><br>

    <?php if (count($merchantOrders) > 0): ?>
        <form method="POST">
            <div class="order-container">
                <?php foreach ($merchantOrders as $order): ?>
                    <div class="order-item">
                        <div class="product-details">
                            <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['order_id']); ?></p>
                            <p><strong>Current Shipping Status:</strong> <?php echo htmlspecialchars($order['current_shipping_status']); ?></p>

                            <?php foreach ($order['products'] as $product): ?>
                                <div class="product-image">
                            <img src="data:image;base64,<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
                        </div>
                                <p><strong>Product Name:</strong> <?php echo htmlspecialchars($product['product_name']); ?></p>
                                <p><strong>Description:</strong> <?php echo htmlspecialchars($product['product_description']); ?></p>
                                <p><strong>Price:</strong> $<?php echo htmlspecialchars($product['price']); ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Text area for shipping status update (one per order) -->
                    <label for="shipping_status_<?php echo $order['order_id']; ?>">Update Shipping Status:</label>
                    <textarea name="shipping_status_<?php echo $order['order_id']; ?>" id="shipping_status_<?php echo $order['order_id']; ?>" rows="4" required><?php echo htmlspecialchars($order['current_shipping_status']); ?></textarea>
                    <button type="submit">Update Shipping Details</button><br>

                <?php endforeach; ?>
            </div>
        </form>
    <?php else: ?>
        <p>No products available for your merchant account.</p>
    <?php endif; ?>
</div>
    <!-- Contact Us Section -->
    <div class="contact-us">
        <h2>Contact Us Submissions</h2>
        <?php if (count($submissions) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Reason</th>
                        <th>Description</th>
                        <th>Submitted At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $submission): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($submission['name']); ?></td>
                            <td><?php echo htmlspecialchars($submission['email']); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($submission['reason'])); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($submission['description'])); ?></td>
                            <td><?php echo htmlspecialchars($submission['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No contact submissions found.</p>
        <?php endif; ?>
    </div>
</div>

<br>
<?php include('footer.php') ?>
</body>
</html>
