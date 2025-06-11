<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'utab_leave_system';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
else {
    echo"";
}
?>
