<?php

class DbController
{
    private $conn;

    function __construct()
    {
        $hostname = "localhost";
        $username = "root";
        $password = "";
        $database = "healthcare_information_system";

        $this->conn = new mysqli($hostname, $username, $password, $database);
        if ($this->conn->connect_error) die("Connection failed: " . $this->conn->connect_error);
    }

    function getConn()
    {
        return $this->conn;
    }
}
