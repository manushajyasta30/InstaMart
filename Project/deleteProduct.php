<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit;
}
include('db.php');

// Check if form is submitted and required parameters are available
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $productid = $_POST['productid'];
    $categoryname = $_POST['categoryname'];
    $merchantname = $_SESSION['name'];
    $merchantemail = $_SESSION['user_email'];

    // Check if the product belongs to the logged-in merchant
    $query = "SELECT * FROM products WHERE productid = :productid AND categoryname = :categoryname AND merchantname = :merchantname AND merchantemail = :merchantemail";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':productid', $productid);
    $stmt->bindParam(':categoryname', $categoryname);
    $stmt->bindParam(':merchantname', $merchantname);
    $stmt->bindParam(':merchantemail', $merchantemail);
    $stmt->execute();

    $product = $stmt->fetchALL(PDO::FETCH_ASSOC);

    if ($product) {
        // Product exists and belongs to the merchant, so proceed with deletion
        $delete_query = "DELETE FROM products WHERE productid = :productid AND categoryname = :categoryname AND merchantname = :merchantname AND merchantemail = :merchantemail";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bindParam(':productid', $productid);
        $delete_stmt->bindParam(':categoryname', $categoryname);
        $delete_stmt->bindParam(':merchantname', $merchantname);
        $delete_stmt->bindParam(':merchantemail', $merchantemail);
        if ($delete_stmt->execute()) {
            // Redirect back to the product management page
            header("Location: updateStock.php");
            exit;
        } else {
            echo "Error deleting the product.";
        }
    } else {
        echo "You don't have permission to delete this product.";
    }
}
?>
