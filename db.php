<?php
$servername = "localhost";  // Your server
$username = "root";         // Your MySQL username
$password = "";             // Your MySQL password
$database = "attendease";   // Your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
