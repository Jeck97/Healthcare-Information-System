<?php

$conn = new mysqli('localhost', 'root', '', 'healthcare_information_system');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
