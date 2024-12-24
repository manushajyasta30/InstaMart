<?php
session_start(); // Start the session to check if the user is logged in
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <title>Login Form</title>
</head>
<body>

<?php
include('db.php');
// Define variables and set them to empty values
$email = $password = $accountType = "";
$emailErr = $passwordErr = $accountTypeErr = "";
$resultError = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    } else {
        $email = htmlspecialchars($_POST["email"]);
    }

    // Validate password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = sanitize_input($_POST["password"]);
    }

    // Validate account type
    if (empty($_POST["account_type"])) {
        $accountTypeErr = "Account type is required";
    } else {
        $accountType = $_POST["account_type"];
    }

    // If no errors, check credentials in the database
    if (empty($emailErr) && empty($passwordErr) && empty($accountTypeErr)) {

        try {

            // Prepare the SQL query to check for matching email and account type
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email AND account_type = :account_type");
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':account_type', $accountType);

            // Execute the query
            $stmt->execute();

            // Check if the user exists and verify the password
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);  // Corrected fetch method
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_email'] = $email; // Store user email in session
                    $_SESSION['name']=$user['name'];
                    $_SESSION['account_type']=$user['account_type'];
                    $_SESSION['address']=$user['address'];
                    $_SESSION['city']=$user['city'];
                    $_SESSION['state']=$user['state'];
                    $_SESSION['pin']=$user['pin'];
                    $_SESSION['phone']=$user['phone'];
                    $_SESSION['dob']=$user['dob'];
                    $_SESSION['id']=$user['id'];

                    if($user['account_type']=='merchant'){
                        header("Location: admin_home.php");
                        exit();    
                    }
                    // Login successful, redirect to the home page
                    header("Location: home.php");
                    exit();
                } else {
                    $resultError = "Invalid password.";
                }
            } else {
                $resultError = "No user found with that email and account type.";
            }

        } catch(PDOException $e) {
            $resultError = "Error: " . $e->getMessage();
        }
    }
}

// Sanitize input function
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <fieldset>
        <legend>Login</legend>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>"><br>
        <span class="error"><?php echo $emailErr; ?></span><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br>
        <span class="error"><?php echo $passwordErr; ?></span><br>

        <label for="account_type">Account Type:</label>
        <input type="radio" id="buyer" name="account_type" value="buyer" <?php if ($accountType == "buyer") echo "checked"; ?>>
        <label for="buyer">Buyer</label><br>
        <input type="radio" id="merchant" name="account_type" value="merchant" <?php if ($accountType == "merchant") echo "checked"; ?>>
        <label for="merchant">Merchant</label><br>
        <span class="error"><?php echo $accountTypeErr; ?></span><br>

        <input type="submit" value="Submit">

        <p>New user? <a href="registration.php">Register Here!</a></p>

        <span class="error"><?php echo $resultError; ?></span><br>
    </fieldset>
</form>

</body>
</html>
