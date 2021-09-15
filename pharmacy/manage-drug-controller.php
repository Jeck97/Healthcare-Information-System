<?php
include "DbController.php";

if (isset($_POST["submit"])) {

    $db = new DbController();
    $conn = $db->getConn();

    $drugName = $_POST["drug-name"];
    $drugPrice = $_POST["drug-price"];
    $drugDescription = $_POST["drug-description"];

    if (isset($_POST["drug-id"])) {
        // Update drug

        $drugId = $_POST["drug-id"];
        $sql = "UPDATE `drug` SET `drug_name`='$drugName',`drug_description`='$drugDescription',`drug_price_per_unit`='$drugPrice' WHERE `drug_id`='$drugId'";

        if ($conn->query($sql) === true) {
            echo "<script>alert('Drug $drugName updated successful.');</script>";
        } else {
            echo "<script>alert('Failed to update drug.');</script>";
        }
    } else {
        // Insert Drug

        $sql = "INSERT INTO `drug`(`drug_id`, `drug_name`, `drug_description`, `drug_price_per_unit`) VALUES (NULL,'$drugName','$drugDescription','$drugPrice')";

        if ($conn->query($sql) === true) {
            echo "<script>alert('Drug $drugName added successful.');</script>";
        } else {
            echo "<script>alert('Failed to create drug.');</script>";
        }
    }

    $conn->close();
}

echo "<script>window.location.assign('manage-drug-page.php')</script>;";
