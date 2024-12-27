<?php
$servername = "localhost";
$username = "root";  // MySQL username
$password = "";      // MySQL password (default is empty)
$dbname = "myStore";    // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Database Connection failed: " . $conn->connect_error);
} 
?>

