<?php
session_start();
// Check if user is logged in (you can add login verification here)
if (!isset($_SESSION['user_email'])) {
    header("Location: index.php");
    exit;
}
//Database Connection
include('db.php');
$name = $email = $address = $city = $state = $pin = $phone = $dob = $accountType = "";

$stmt = $conn->prepare("SELECT image FROM users WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();

// Fetch the data
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user_data) {
    // If the image is found, convert it to base64 and set the source
    $image_data = $user_data['image'];
    $image_src = "data:image/jpeg;base64," . base64_encode($image_data);
} else {
    $image_src = "images/logo.jpg"; // Default image
}


// Update profile data if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $pin = $_POST['pin'];
    $image= !empty($image_data)?$image_data:'';

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        if (getimagesize($_FILES['profile_image']['tmp_name']) == false) {
            echo "<br />Please Select An Image.";
        } else {
            // Declare variables
            $image = $_FILES['profile_image']['tmp_name'];
            $image = base64_encode(file_get_contents(addslashes($image)));
           }
    }
               // Prepare SQL query to update profile
               $stmt = $conn->prepare("UPDATE `users` 
               SET `name` = :name, 
                   `address` = :address, 
                   `city` = :city, 
                   `state` = :state, 
                   `pin` = :pin, 
                   `phone` = :phone, 
                   `image` = :image 
               WHERE `id` = :id");
       
               // Bind the parameters to the query
               $stmt->bindParam(':name', $name);
               $stmt->bindParam(':address', $address);
               $stmt->bindParam(':city', $city);
               $stmt->bindParam(':state', $state);
               $stmt->bindParam(':pin', $pin);
               $stmt->bindParam(':phone', $phone);
               $stmt->bindParam(':image', $image, PDO::PARAM_LOB); // Bind the image as a BLOB (Binary data)
               $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);  // Bind the ID for the WHERE clause
       
               // Execute the update query
               $stmt->execute();
       
               // Fetch the image and update $image_src
               $stmt = $conn->prepare("SELECT * FROM users WHERE id = :id");
               $stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
               $stmt->execute();
       
               // Fetch the data
               $user_data = $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
               $_SESSION['name']=$user_data['name'];
               $_SESSION['address']=$user_data['address'];
               $_SESSION['city']=$user_data['city'];
               $_SESSION['state']=$user_data['state'];
               $_SESSION['pin']=$user_data['pin'];
               $_SESSION['phone']=$user_data['phone'];

       
               if ($user_data) {
                   // If the image is found, convert it to base64 and set the source
                   $image_data = $user_data['image'];
               } else {
                   $image_src = "images/default_profile.jpg"; // Default image
               }   

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/header_style.css">
    <link rel="stylesheet" href="css/footer_style.css">

    <style>
        /* Basic reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin-top: 150px;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            font-size: 22px;
            color: #333;
        }

        .container {
            width: 60%;
            margin-top: 150px;
            margin-bottom: 30px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 30px;
        }

        .profile-header img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-right: 20px;
            border: 3px solid #ff5a00;
        }

        .profile-header h1 {
            font-size: 28px;
            color: #333;
        }

        .form-container {
            display: flex;
            flex-direction: column;
        }

        .form-container label {
            margin: 10px 0 5px;
            font-weight: bold;
        }

        .form-container input,
        .form-container textarea,
        .form-container select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .form-container input[type="file"] {
            padding: 0;
            font-size: 16px;
        }

        .cta-button {
            background-color: #ff5a00;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            text-align: center;
            display: inline-block;
            width: 100%;
        }

        .cta-button:hover {
            background-color: #e44d00;
        }
    </style>
</head>
<body>
<!--- Header -->
<?php include('header.php'); ?>

<!-- Profile Edit Form -->
<div class="container">
    <div class="profile-header">
        <img src="data:image;base64,<?php echo $image_data ?>" alt="Profile Image">
        <div>
            <h1>Edit Profile</h1>
            <p>Update your personal information below.</p>
        </div>
    </div>

    <!-- Profile Edit Form -->
    <form class="form-container" action="profile.php" method="POST" enctype="multipart/form-data">

        <!-- Profile Image -->
        <label for="profile_image">Profile Image</label>
        <input type="file" id="profile_image" name="profile_image">

        <!-- Full Name -->
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['name']); ?>" required>

        <!-- Email -->
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user_email']); ?>" readonly>

        <!-- Phone Number -->
        <label for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($_SESSION['phone']); ?>" required>

        <!-- Account Type -->
        <label for="account_type">Account Type</label>
        <select id="account_type" name="account_type" disabled>
            <option value="buyer" <?php echo $_SESSION['account_type'] == 'buyer' ? 'selected' : ''; ?>>Buyer</option>
            <option value="merchant" <?php echo $_SESSION['account_type'] == 'merchant' ? 'selected' : ''; ?>>Merchant</option>
        </select>

        <!-- Address -->
        <label for="address">Address</label>
        <textarea id="address" name="address" required><?php echo htmlspecialchars($_SESSION['address']); ?></textarea>

        <!-- City -->
        <label for="city">City</label>
        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($_SESSION['city']); ?>" required>

        <!-- State -->
        <label for="state">State</label>
        <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($_SESSION['state']); ?>" required>

        <!-- Pin Code -->
        <label for="pin">Pin Code</label>
        <input type="text" id="pin" name="pin" value="<?php echo htmlspecialchars($_SESSION['pin']); ?>" required>

        <!-- Date of Birth -->
        <label for="dob">Date of Birth</label>
        <input type="date" id="dob" name="dob" value="<?php echo $_SESSION['dob']; ?>" readonly>

        <!-- Save Button -->
        <button type="submit" class="cta-button">Save Changes</button>
    </form>
</div>

<!--- Footer -->
<?php include('footer.php'); ?>

</body>
</html>
