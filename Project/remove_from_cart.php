<?php
session_start();

// Database connection
include('db.php');

try {
    if (!isset($_SESSION['user_email'])) {
        // Redirect to login page if not logged in
        header('Location: home.php');
        exit();
    }
    
    // Check if the product ID is provided
    if (isset($_POST['productid'])) {
        $productid = $_POST['productid'];
        $buyeremail = $_SESSION['user_email'];

        // Remove the product from the cart
        $stmt = $conn->prepare("DELETE FROM cart WHERE productid = :productid AND buyeremail = :buyeremail");
        $stmt->bindParam(':productid', $productid);
        $stmt->bindParam(':buyeremail', $buyeremail);
        $stmt->execute();
    }

    // Redirect to the cart page after removal
    header('Location: cart.php');
    exit();

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}