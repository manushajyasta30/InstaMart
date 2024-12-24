<?php
session_start();

 // Check if user is logged in
 if (!isset($_SESSION['user_email'])) {
    // Redirect to login page if not logged in
    header('Location: home.php');
    exit();
}
//Database Connection
include('db.php');

// Simulate payment process
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the form values
        $address = $_POST['address'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $pin = $_POST['pin'];
        $card_number = $_POST['card_number'];
        $card_name = $_POST['card_name'];
        $expiry_date = $_POST['expiry_date'];
        $cvv = $_POST['cvv'];
    
        
        // Get the current date for orderDate
        $orderDate = date('Y-m-d');
    
        try {
       
            $stmt = $conn->prepare("SELECT productid,count  FROM cart WHERE buyeremail = :buyeremail");
            $stmt->bindParam(':buyeremail', $_SESSION['user_email']);
            $stmt->execute();
            // Initialize arrays to store productId's and counts
            $productIds = [];
            $productCounts = [];
            $productDetails = [];


// Loop through all the records and store the values in arrays
foreach ($stmt->fetchALL(PDO::FETCH_ASSOC) as $row) {
    $productIds[] = $row['productid']; 
    $productCounts[] = $row['count'];  

    // Fetch product details to update stock later
    $productDetails[$row['productid']] = $row['count'];

}

// Convert the arrays into a single line string using implode()
$productIdsString = implode(", ", $productIds);
$productCountsString = implode(", ", $productCounts);
        

            // Insert order details into the orders table
            $sql = "INSERT INTO orders (productID, totalCounts, buyerEmail,orderDate, totalAmount, cardNumber, expiryDate, CVV, cardHolderName, address, city, state, pin, shippingStatus) 
                    VALUES (:productID, :totalCounts, :buyerEmail, :orderDate, :totalAmount, :cardNumber, :expiryDate, :CVV, :cardHolderName, :address, :city, :state, :pin, 'Pending')";
    
            $stmt = $conn->prepare($sql);
            
            // Bind the parameters to the SQL query
            $stmt->bindParam(':productID', $productIdsString);
            $stmt->bindParam(':totalCounts',$productCountsString);
            $stmt->bindParam(':buyerEmail',$_SESSION['user_email']);
            $stmt->bindParam(':orderDate', $orderDate);
            $stmt->bindParam(':totalAmount', $_SESSION['totalAmount']);
            $stmt->bindParam(':cardNumber', $card_number);
            $stmt->bindParam(':expiryDate', $expiry_date);
            $stmt->bindParam(':CVV', $cvv);
            $stmt->bindParam(':cardHolderName', $card_name);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':city', $city);
            $stmt->bindParam(':state', $state);
            $stmt->bindParam(':pin', $pin);
    
            // Execute the query
            if($stmt->execute()){
            // Payment Successful, now proceed with cart cleanup and stock update
            // Delete cart items for the user
            $deleteCartStmt = $conn->prepare("DELETE FROM cart WHERE buyeremail = :buyeremail");
            $deleteCartStmt->bindParam(':buyeremail', $_SESSION['user_email']);
            $deleteCartStmt->execute();

            // Update the stock in the products table based on the ordered counts
            foreach ($productDetails as $productId => $count) {
                $updateStockStmt = $conn->prepare("UPDATE products SET stock = stock - :count WHERE productid = :productid");
                $updateStockStmt->bindParam(':count', $count);
                $updateStockStmt->bindParam(':productid', $productId);
                $updateStockStmt->execute();
            }

            $message = "Payment Successful! Thank you for your purchase.";

        } else {
            $message = "Payment failed. Please try again.";
        }
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    
} else {
    // Redirect to cart page if accessed without POST
    header("Location: shopping_cart.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        .message {
            font-size: 1.5em;
            color: #28a745;
            text-align: center;
            margin-top: 20px;
        }
        .message.error {
            color: #dc3545;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 1.2em;
            color: #007bff;
        }
    </style>
</head>
<body>

    <div class="message <?php echo isset($payment_success) && $payment_success ? '' : 'error'; ?>">
        <?php echo isset($message) ? $message : ''; ?>
    </div>

    <a href="listOfItems.php">Go to Shopping</a>

</body>
</html>
