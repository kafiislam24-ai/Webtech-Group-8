<?php
// Database configuration credentials
$host     = "localhost";
$username = "root";
$password = "";
$database = "emrs_db";

// Establish procedural connection to MySQL
$conn = mysqli_connect($host, $username, $password, $database);

// Verify connection integrity
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Set character set to utf8mb4 for standard character and symbol support
mysqli_set_charset($conn, "utf8mb4");
?>