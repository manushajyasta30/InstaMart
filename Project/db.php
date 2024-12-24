<?php
// Database connection settings
$servername = "localhost";  // Use localhost if MySQL is running on the same machine
$port = "3307";  // The port MySQL is running on
$dbusername = "root";  // Replace with your MySQL username
$dbpassword = "";  // Replace with your MySQL password
$dbname = "project";  // Replace with your actual database name

try {
    // Create a PDO connection to check and create the database
    $dsn = "mysql:host=$servername;port=$port";
    $conn = new PDO($dsn, $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Step 1: Check if the database exists
    $stmt = $conn->query("SHOW DATABASES LIKE '$dbname'");
    if ($stmt->rowCount() == 0) {
        // Database does not exist, create it
        $conn->exec("CREATE DATABASE `$dbname`");
    } 

    // Step 2: Select the database to use
    $conn->exec("USE `$dbname`");
}catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>