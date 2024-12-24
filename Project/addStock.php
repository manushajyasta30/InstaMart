<?php
session_start(); // Start the session to check if the user is logged in
if (!isset($_SESSION['user_email']) && isset($_SESSION['account_type']) && $_SESSION['account_type']=='buyer') {
    header('Location: home.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Form</title>
    <link rel="stylesheet" href="css/header_style.css"/>
    <link rel="stylesheet" href="css/footer_style.css"/>
    <style>
        /* Reset body margin and padding */
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
    min-height: 100vh; /* Ensure body takes full height */
    display: flex;
    flex-direction: column; /* Stack elements vertically */
    justify-content: center; /* Center content vertically */
    align-items: center; /* Center content horizontally */

}

.container {
    margin-top: 100px;
    width: 500px; /* Increase width of form container */
    background: white;
    padding: 30px; /* Increase padding */
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    text-align: center; /* Center text inside the container */
    flex: 1; /* Allow this to take available space */
}

        h1 {
            margin-bottom: 25px; /* More space below the title */
            font-size: 24px; /* Increase font size of the title */
        }
        
        input[type="text"], textarea, input[type="file"] {
            width: 100%; /* Full width inputs */
            padding: 15px; /* Increase padding inside the input fields */
            margin: 15px 0; /* Space between inputs */
            border: 1px solid #ccc;
            border-radius: 8px; /* Rounded corners */
            box-sizing: border-box; /* Include padding in width calculation */
            font-size: 16px; /* Increase font size inside the inputs */
        }
        
        /* Prevent resizing of the textarea */
        textarea {
            resize: none; /* Disable resizing */
            height: 150px; /* Increase height of textarea */
        }
        
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 15px 20px; /* Increase button padding */
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%; /* Full width submit button */
            font-size: 18px; /* Increase font size of submit button */
        }
        
        input[type="submit"]:hover {
            background-color: #45a049;
        }

        label {
            text-align: left;
            display: block;
            margin-bottom: 8px; /* Increase space between label and input */
            font-weight: bold;
        }
 
        select {
    width: 100%; /* Full width select input */
    padding: 12px; /* Add padding for better spacing */
    margin: 15px 0; /* Space between the input and next element */
    border: 1px solid #ccc; /* Standard border */
    border-radius: 8px; /* Rounded corners */
    font-size: 16px; /* Increase font size */
    background-color: #fff; /* White background */
    color: #333; /* Text color */
    cursor: pointer; /* Show pointer cursor when hovering */
    appearance: none; /* Remove default arrow in some browsers */
 
}

select:focus {
    border-color: #4CAF50; /* Change border color on focus */
    outline: none; /* Remove default focus outline */
}

select option {
    padding: 12px; /* Space around options */
    background-color: #f4f4f4; /* Light background for options */
    font-size: 16px; /* Font size for the options */
}

select:hover {
    border-color: #45a049; /* Change border color on hover */
}

/* Add a custom arrow to the select dropdown */
select::-ms-expand {
    display: none; /* Remove default arrow in IE/Edge */
}


        /* Error message styling */
        .error {
        color: red;
        font-size: 0.9em;
        margin-top: 10px;
        margin-bottom: 10px;
        display: block;
    }

    </style>
</head>
<body>

<?php include('header.php') ?>
<?php
// Define variables and initialize with empty values
$price=$gender=$productName = $stock = $description =$categoryName= "";
$priceError=$productNameError=$stockError=$descriptionError=$imageError=$resultError=$categoryNameError=$genderError='';

include('db.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate product name
    if (!empty($_POST['category_name'])) {
        $categoryName = htmlspecialchars($_POST['category_name']);
    } else {
        $categoryNameError= "Category Name is required.";
    }

    // Validate product name
    if (!empty($_POST['product_name'])) {
        $productName = htmlspecialchars($_POST['product_name']);
    } else {
        $productNameError= "Product Name is required.";
    }

    // Validate stock
    if (!empty($_POST['stock'])) {
        $stock = htmlspecialchars($_POST['stock']);
    } else {
        $stockError= "Stock is required.";
    }

        // Validate price
        if (!empty($_POST['price'])) {
            $price = htmlspecialchars($_POST['price']);
        } else {
            $priceError= "Price is required.";
        }
    
        // Validate gender
        if (!empty($_POST['gender'])) {
            $gender = htmlspecialchars($_POST['gender']);
        } else {
            $genderError = "Gender is required.";
        }

    // Validate description
    if (!empty($_POST['description'])) {
        $description = htmlspecialchars($_POST['description']);
    } else {
        $descriptionError= "Description is required.";
    }
    
        // Image validation
        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
            // Check if the file is an image
            $imageSize = getimagesize($_FILES['product_image']['tmp_name']);
            if ($imageSize === false) {
                $imageError = "Please Select A Valid Image.";
            } else {
                // Get the MIME type of the uploaded image
                $allowedTypes = ['image/jpeg', 'image/png'];
                $imageMimeType = mime_content_type($_FILES['product_image']['tmp_name']);
                
                // Check if the MIME type is allowed
                if (!in_array($imageMimeType, $allowedTypes)) {
                    $imageError = "Only JPG, PNG images are allowed.";
                }
            }
        } else {
            $imageError = "Please select an image file.";
        }


    if (empty($productNameError) && empty($descriptionError) && empty($stockError) && empty($imageError) ) {
 
try {
            $stmt = $conn->prepare("INSERT INTO products (categoryname,productname,merchantname, merchantemail,stock,price,description,productimage,gender) 
            VALUES (:categoryname,:productname, :merchantname, :merchantemail, :stock, :price,:description, :productimage, :gender)");
        

        // Declare variables 
        $image = $_FILES['product_image']['tmp_name'];
        $image = base64_encode(file_get_contents(addslashes($image)));
        $stmt->bindParam(':categoryname', $categoryName);
        $stmt->bindParam(':productname', $productName);
        $stmt->bindParam(":merchantname",$_SESSION['name']);
        $stmt->bindParam(":merchantemail",$_SESSION['user_email']);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':productimage', $image);
        $stmt->bindParam(':gender',$gender);

        // Execute the query
        if($stmt->execute()){
            $resultError="Product added Successfully";
            echo "<script>if (confirm('Product added Successfully! Do you want to continue?')) { window.location.href = 'addStock.php'; }</script>";

        }else{
            $resultError="Error Executing :". $stmt->errorInfo()[2];
        }
 
} catch (PDOException $e) {
    $resultError= 'Connection failed: ' . $e->getMessage();
}
    }
}
?>

<div class="container">
    <h1>Add Product</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <label for="category_name">Category Name:</label>
        <input type="text" name="category_name" id="category_name" value="<?= $categoryName ?>" required><br>
        <span class="error"><?php echo $categoryNameError; ?></span><br>

        <label for="product_name">Product Name</label>
        <input type="text" name="product_name" id="product_name" value="<?= $productName ?>" required><br>
        <span class="error"><?php echo $productNameError; ?></span><br>

        <label for="gender">Gender:</label>
<select name="gender" id="gender" required>
    <option value="">Select Gender</option>
    <option value="Men" <?php echo ($gender == 'Men') ? 'selected' : ''; ?>>Men</option>
    <option value="Women" <?php echo ($gender == 'Women') ? 'selected' : ''; ?>>Women</option>
    <option value="Kids" <?php echo ($gender == 'Kids') ? 'selected' : ''; ?>>Kids</option>
</select><br>
<span class="error"><?php echo $genderError; ?></span><br>
        
        <label for="stock">Stock</label>
        <input type="text" name="stock" id="stock" value="<?= $stock ?>" required><br>
        <span class="error"><?php echo $stockError; ?></span><br>

        <label for="price">Price</label>
        <input type="text" name="price" id="price" value="<?= $price ?>" required><br>
        <span class="error"><?php echo $priceError; ?></span><br>


        <label for="description">Description</label>
        <textarea name="description" id="description" required></textarea><br>
        <span class="error"><?php echo $descriptionError; ?></span><br>


        <label for="product_image">Product Image</label>
        <input type="file" name="product_image" id="product_image"><br>
        <span class="error"><?php echo $imageError; ?></span><br>


        <input type="submit" value="Submit"><br>
        <span class="error"><?php echo $resultError; ?></span><br>

    </form>
</div>

<!-- Footer -->
<?php include_once('footer.php'); ?>

</body>
</html>
