<?php

// Declare and define parameters to connect database
$serverName = "localhost";
$userName = "root";
$password = "";
$dbName = "healthcare_information_system";

// Connect to database
$conn = mysqli_connect($serverName, $userName, $password, $dbName);

// If error occur
if(mysqli_connect_errno()){
	echo "Failed to connect!";
	exit();
}
