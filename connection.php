<?php


$myServer = "localhost";
$myUser = "root";
$myPass = "";
$myDB = "Assignment8";


$conn = new mysqli($myServer, $myUser, $myPass,$myDB);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully";
?>