<?php
// Start the session
session_start();

// Destroy all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect the user to the homepage (or any other page you prefer)
header("Location: index.php");
exit();
?>
