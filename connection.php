<?php
$host = "attendancedb.c9k8c68iecq2.us-east-2.rds.amazonaws.com";
    $username = "admin";
    $password = "Spartan$2025!";
    $dbname = "attendancedb";

    $con = mysqli_connect($host, $username, $password, $dbname);

    if (!$con = mysqli_connect($host, $username, $password, $dbname))
    {
        die("Connection failed!" . mysqli_connect_error());
    }
?>