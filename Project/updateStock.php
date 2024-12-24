<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit;
}

include('db.php');

$categoryNameError=$productNameError=$newDescriptionError=$newStockError=$resultError='';

// Handling the product update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {

    $category = $_POST['categoryname'];
    $productname = $_POST['productname'];
    $new_stock = $_POST['stock'];
    $new_price=$_POST['price'];
    $new_description = $_POST['description'];
    $merchantname = $_SESSION['name']; // Assuming merchant name is stored in session
    $merchantemail = $_SESSION['user_email']; // Assuming merchant email is stored in session
    $updateMade = false;

    if(!empty($new_description)){
        $update_query = "UPDATE products 
        SET description = :description 
        WHERE categoryname = :categoryname 
            AND productname = :productname 
            AND merchantname = :merchantname 
            AND merchantemail = :merchantemail";

// Prepare the statement
$stmt = $conn->prepare($update_query);

// Bind the parameters dynamically
$stmt->bindParam(':description', $new_description);
$stmt->bindParam(':categoryname', $category);
$stmt->bindParam(':productname', $productname);
$stmt->bindParam(':merchantname', $merchantname);
$stmt->bindParam(':merchantemail', $merchantemail);
        // Execute the query
        if ($stmt->execute()) {
            $affected_rows = $stmt->rowCount();  // Get the number of affected rows
            if ($affected_rows > 0) {
                $updateMade = true;
            }
        }

    }
    if(!empty($new_stock)){
        $update_query = "UPDATE products 
        SET stock = :stock
        WHERE categoryname = :categoryname 
            AND productname = :productname 
            AND merchantname = :merchantname 
            AND merchantemail = :merchantemail";

// Prepare the statement
$stmt = $conn->prepare($update_query);

// Bind the parameters dynamically
$stmt->bindParam(':stock', $new_stock);
$stmt->bindParam(':categoryname', $category);
$stmt->bindParam(':productname', $productname);
$stmt->bindParam(':merchantname', $merchantname);
$stmt->bindParam(':merchantemail', $merchantemail);
        // Execute the query
        if ($stmt->execute()) {
            $affected_rows = $stmt->rowCount();  // Get the number of affected rows
            if ($affected_rows > 0) {
                $updateMade = true;
            }
        }

}
    if(!empty($new_price)){
        $update_query = "UPDATE products 
        SET price = :price
        WHERE categoryname = :categoryname 
            AND productname = :productname 
            AND merchantname = :merchantname 
            AND merchantemail = :merchantemail";

// Prepare the statement
$stmt = $conn->prepare($update_query);

// Bind the parameters dynamically
$stmt->bindParam(':price', $new_price);
$stmt->bindParam(':categoryname', $category);
$stmt->bindParam(':productname', $productname);
$stmt->bindParam(':merchantname', $merchantname);
$stmt->bindParam(':merchantemail', $merchantemail);
        // Execute the query
        if ($stmt->execute()) {
            $affected_rows = $stmt->rowCount();  // Get the number of affected rows
            if ($affected_rows > 0) {
                $updateMade = true;
            }
        }

}

if ($updateMade) {
    $resultError = "Product details successfully updated!";
    echo "<script>if (confirm('Product details successfully updated! Do you want to continue?')) { window.location.href = 'updateStock.php'; }</script>";
} else {
    $resultError = "No matching item found or no changes made. Please check your input and try again.";
    echo "<script>if (confirm('No matching item found or no changes made. Please check your input and try again.')) { window.location.href = 'updateStock.php'; }</script>";
}

 }
 
 // Fetching all products from the database
 $query = "SELECT * FROM products WHERE merchantname = ? AND merchantemail = ?";
 $stmt = $conn->prepare($query);
 $stmt->execute([$_SESSION['name'], $_SESSION['user_email']]);
 $products = $stmt->fetchAll();
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="css/header_style.css">
    <link rel="stylesheet" href="css/footer_style.css">
    <style>
        /* General Body Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f7fc;
    color: #333;
    margin: 0;
    padding: 0;
}


/* Container for Main Content and Table */
.container {
    margin-top: 20px;
    max-width: 1100px;
    margin: 0 auto;
    padding: 30px;
}

/* Styling for the Product Table */
.product-table-container {
    margin-top: 10px;
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-bottom: 30px;
}

.product-table-container h2 {
    margin-top: 100px;
    font-size: 1.8em;
    margin-bottom: 15px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

table th {
    background-color: #2a3d66;
    color: white;
}

table td {
    background-color: #f9f9f9;
}

table tr:hover {
    background-color: #f1f1f1;
}

/* Update Form Container */
.update-form-container {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.update-form-container h2 {
    font-size: 1.8em;
    margin-bottom: 20px;
}

form {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

form label {
    font-weight: bold;
}

form input[type="text"],
form input[type="number"],
form textarea {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1em;
    width: 100%;
}

form textarea {
    resize: vertical;
    min-height: 100px;
}

form input[type="submit"] {
    background-color: #2a3d66;
    color: white;
    padding: 12px;
    border: none;
    border-radius: 5px;
    font-size: 1.1em;
    cursor: pointer;
}

form input[type="submit"]:hover {
    background-color: #3c5a8b;
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

/* Responsive Design */
@media (max-width: 768px) {
    header h1 {
        font-size: 2em;
    }

    header nav ul li {
        display: block;
        margin: 10px 0;
    }

    .container {
        padding: 15px;
    }

    .product-table-container {
        padding: 15px;
    }

    form input[type="text"],
    form input[type="number"],
    form textarea,
    form input[type="submit"] {
        font-size: 0.9em;
    }
}

    </style>
</head>
<body>

<!--- Header -->
<?php include('header.php'); ?>

<!-- Main Container for the content -->
<div class="container">

    <!-- Displaying the Product Table -->
    <div class="product-table-container">
        <h2>Product List</h2>
        <table>
    <thead>
        <tr>
            <th>Product ID</th>
            <th>Category</th>
            <th>Product Name</th>
            <th>Gender</th>
            <th>Stock</th>
            <th>Price</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?php echo htmlspecialchars($product['productid']); ?></td>
                <td><?php echo htmlspecialchars($product['categoryname']); ?></td>
                <td><?php echo htmlspecialchars($product['productname']); ?></td>
                <td><?php echo htmlspecialchars($product['gender']); ?></td>
                <td><?php echo htmlspecialchars($product['stock']); ?></td>
                <td><?php echo htmlspecialchars($product['price']); ?></td>
                <td><?php echo htmlspecialchars($product['description']); ?></td>
                <td>
                    <form action="deleteProduct.php" method="POST" style="display:inline;">
                        <input type="hidden" name="productid" value="<?php echo htmlspecialchars($product['productid']); ?>">
                        <input type="hidden" name="categoryname" value="<?php echo htmlspecialchars($product['categoryname']); ?>">
                        <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    </div>

    <!-- Search and Update Form -->
    <div class="update-form-container">
        <h2>Search and Update Product</h2>
        <form action="updateStock.php" method="POST">
            <label for="categoryname">Category Name:</label>
            <input type="text" name="categoryname" required><br>

            <label for="productname">Product Name:</label>
            <input type="text" name="productname" required><br>

            <label for="stock">New Stock:</label>
            <input type="number" name="stock"><br>

            <label for="price">New Price:</label>
            <input type="number" name="price"><br>

            <label for="description">New Description:</label>
            <textarea name="description"></textarea><br>

            <input type="submit" name="update" value="Update Product"><br>
            <span class="error"><?php echo $resultError; ?></span><br>
        </form>
    </div>

</div>

<!-- Footer -->
<?php include_once('footer.php'); ?>

</body>
</html>