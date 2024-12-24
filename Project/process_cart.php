<?php
session_start();

// Get the productId and count values from the POST request
$productId = isset($_POST['productId']) ? $_POST['productId'] : null;
$count = isset($_POST['count']) ? $_POST['count'] : 0;

if ($productId === null || $count <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request data']);
    exit;
}
//Database Connection
include('db.php');

try {
    // Check if the user is logged in (session should contain user details)
    if (!isset($_SESSION['user_email'])) {
        echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
        exit;
    }

    // Check if the product already exists in the cart for the current user
    $stmt = $conn->prepare("SELECT * FROM cart WHERE productid = :productId AND buyeremail = :buyeremail");
    $stmt->bindParam(':productId', $productId);
    $stmt->bindParam(':buyeremail', $_SESSION['user_email']);
    $stmt->execute();
    $existingProduct = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];

    if ($existingProduct) {
        // If the product exists, update the count
        $stmt = $conn->prepare("UPDATE cart SET count = :count WHERE productid = :productId AND buyeremail = :buyeremail");
        $stmt->bindParam(':count', $count);
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':buyeremail', $_SESSION['user_email']);
        $stmt->execute();
        $message = 'Cart updated successfully!';
    } else {
        // If the product does not exist, insert a new record
        $stmt = $conn->prepare("INSERT INTO cart (productid, count, buyername, buyeremail) VALUES (:productId, :count, :buyername, :buyeremail)");
        $stmt->bindParam(':productId', $productId);
        $stmt->bindParam(':count', $count);
        $stmt->bindParam(':buyername', $_SESSION['name']);
        $stmt->bindParam(':buyeremail', $_SESSION['user_email']);
        $stmt->execute();
        $message = 'Product added to cart successfully!';
    }

    // Return a success response
    echo json_encode(['status' => 'success', 'message' => $message, 'productId' => $productId, 'count' => $count]);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}
?>
