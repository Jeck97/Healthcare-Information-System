<?php

require_once "conn.php";
session_start();

if ($_POST["login"]) {

    $email = $_POST["email"];
    $password = $_POST["password"];


    $sql = "SELECT * FROM user WHERE user_email='$email' AND user_password='$password'";
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {

        $row = mysqli_fetch_array($query);
        $_SESSION["id"] = $row["user_id"];

        echo "<script language='javascript'>
        alert('Login in success');
        window.location = 'navigation.php';
        </script>";
    } else {
        echo "<script language='javascript'>
        alert('Login in fail');
        window.location = 'login.php';
        </script>";
    }
}
