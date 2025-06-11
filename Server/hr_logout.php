<?php
session_start(); // Start the session

// Destroy all session variables
session_unset();
session_destroy();

// Optionally: regenerate a new session ID for extra security
session_regenerate_id(true);

// Set headers to prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

// Redirect to login page
header("Location: ../client/index.php#loginsection");
exit();
?>
