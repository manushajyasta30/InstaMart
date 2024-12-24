<?php

session_start(); // Start the session to check if the user is logged in

?>



<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registration Form</title>

    <link rel="stylesheet" type="text/css" href="css/style.css">

</head>

<body>



<?php



include('db.php');

// Initialize variables and error messages

$name = $email = $password = $confirmPassword = $address = $city = $state = $pin = $phone = $dob = $accountType = "";

$nameErr = $emailErr = $passwordErr = $confirmPasswordErr = $addressErr = $cityErr = $stateErr = $pinErr = $phoneErr = $dobErr = $accountTypeErr = "";

$resultError = "";

$image="";



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate Name

    if (empty($_POST["name"])) {

        $nameErr = "Name is required";

    } else {

        $name = htmlspecialchars($_POST["name"]);

    }



    // Validate Email

    if (empty($_POST["email"])) {

        $emailErr = "Email is required";

    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {

        $emailErr = "Invalid email format";

    } else {

        $email = htmlspecialchars($_POST["email"]);

    }



    // Validate Password

    if (empty($_POST["password"])) {

        $passwordErr = "Password is required";

    } elseif (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/", $_POST["password"])) {

        $passwordErr = "Password must be at least 8 characters, include an uppercase, lowercase, and number.";

    } else {

        $password = $_POST["password"];

    }



    // Validate Confirm Password

    if (empty($_POST["confirmPassword"])) {

        $confirmPasswordErr = "Please confirm your password";

    } elseif ($_POST["confirmPassword"] !== $password) {

        $confirmPasswordErr = "Passwords do not match";

    }



    // Validate Address

    if (empty($_POST["address"])) {

        $addressErr = "Address is required";

    } else {

        $address = htmlspecialchars($_POST["address"]);

    }



    // Validate Date of birth

    if (empty($_POST["dob"])) {

        $dobErr = "Date of Birth is required";

    } else {

        $dob = htmlspecialchars($_POST["dob"]);

    }

    

    // Validate City

    if (empty($_POST["city"])) {

        $cityErr = "City is required";

    } else {

        $city = htmlspecialchars($_POST["city"]);

    }



    // Validate State

    if (empty($_POST["state"])) {

        $stateErr = "State is required";

    } else {

        $state = htmlspecialchars($_POST["state"]);

    }



    // Validate Pin Code

    if (empty($_POST["pin"])) {

        $pinErr = "Pin code is required";

    } elseif (!preg_match("/^[0-9]{5}$/", $_POST["pin"])) {

        $pinErr = "Pin must be a 5-digit number";

    } else {

        $pin = htmlspecialchars($_POST["pin"]);

    }



    // Validate Phone Number

    if (empty($_POST["phone"])) {

        $phoneErr = "Phone number is required";

    } elseif (!preg_match("/^[0-9]{10}$/", $_POST["phone"])) {

        $phoneErr = "Phone must be a 10-digit number";

    } else {

        $phone = htmlspecialchars($_POST["phone"]);

    }



    // Validate account type

    if (empty($_POST["account_type"])) {

        $accountTypeErr = "Account type is required";

    } else {

        $accountType = $_POST["account_type"];

    }



    // If no errors, process form and insert data into the database

    if (empty($nameErr) && empty($emailErr) && empty($passwordErr) && empty($confirmPasswordErr) && empty($addressErr) && empty($cityErr) && empty($stateErr) && empty($pinErr) && empty($phoneErr) && empty($accountTypeErr)) {

        try {

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);



            $stmt = $conn->prepare("INSERT INTO users (name, email, password, account_type, address, city, state, pin, phone, dob, image) 

                        VALUES (:name, :email, :password, :account_type, :address, :city, :state, :pin, :phone, :dob, :image)");



            $stmt->bindParam(':name', $name);

            $stmt->bindParam(':email', $email);

            $stmt->bindParam(':password', $hashedPassword);

            $stmt->bindParam(':account_type', $accountType);

            $stmt->bindParam(':address', $address);

            $stmt->bindParam(':city', $city);

            $stmt->bindParam(':state', $state);

            $stmt->bindParam(':pin', $pin);

            $stmt->bindParam(':phone', $phone);

            $stmt->bindParam(':dob', $dob);

            $stmt->bindParam(':image', $image);



            // Execute the query

            $stmt->execute();

            $resultError = "Registration successful!";

        } catch(PDOException $e) {

            $resultError = "Error: " . $e->getMessage();

        }

    }

}

?>



<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

    <fieldset>

        <legend>Registration</legend>

        

        <label for="name">Name:</label>

        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>"><br>

        <span class="error"><?php echo $nameErr; ?></span><br>



        <label for="email">Email:</label>

        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>"><br>

        <span class="error"><?php echo $emailErr; ?></span><br>



        <label for="phone">Phone Number:</label>

        <input type="number" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>"><br>

        <span class="error"><?php echo $phoneErr; ?></span><br>



        <label for="dob">Date of Birth:</label>

        <input type="date" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>"><br>

        <span class="error"><?php echo $dobErr; ?></span><br>



        <label for="password">Password:</label>

        <input type="password" id="password" name="password"><br>

        <span class="error"><?php echo $passwordErr; ?></span><br>



        <label for="confirmPassword">Confirm Password:</label>

        <input type="password" id="confirmPassword" name="confirmPassword"><br>

        <span class="error"><?php echo $confirmPasswordErr; ?></span><br>



        <label for="account_type">Account Type:</label>

        <input type="radio" name="account_type" value="buyer" <?php echo ($accountType == 'buyer') ? 'checked' : ''; ?>> Buyer

        <input type="radio" name="account_type" value="merchant" <?php echo ($accountType == 'merchant') ? 'checked' : ''; ?>> Merchant

        <span class="error"><?php echo $accountTypeErr; ?></span><br>



        <label for="address">Address:</label>

        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>"><br>

        <span class="error"><?php echo $addressErr; ?></span><br>



        <label for="city">City:</label>

        <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($city); ?>"><br>

        <span class="error"><?php echo $cityErr; ?></span><br>



        <label for="state">State:</label>

        <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($state); ?>"><br>

        <span class="error"><?php echo $stateErr; ?></span><br>



        <label for="pin">Pin Code:</label>

        <input type="number" id="pin" name="pin" value="<?php echo htmlspecialchars($pin); ?>"><br>

        <span class="error"><?php echo $pinErr; ?></span><br>



        <input type="submit" value="Register"><br><br>

        <span class="error"><?php echo $resultError; ?></span><br><br>

        <a href="index.php">Login?</a><br>



    </fieldset>



</form>



</body>

</html>

