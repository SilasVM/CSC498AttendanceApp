<?php
$host = "attendancedb.c9k8c68iecq2.us-east-2.rds.amazonaws.com";
$username = "admin";
$password = "Spartan$2025!";
$dbname = "attendancedb";

// Attempt to connect to the database
$con = mysqli_connect($host, $username, $password, $dbname);

// Check the connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected successfully!";
}
?>