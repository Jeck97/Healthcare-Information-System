<?php

    require "conn.php";

    if (isset($_POST['orderQuantity']) 
        && isset($_POST['orderDescription']) 
        && isset($_POST['drugID']) 
        && isset($_POST['drugQuantity'])) {

            $orderQuantity = $_POST['orderQuantity'];
            $orderDescription = $_POST['orderDescription'];
            $drugID = json_decode($_POST['drugID']);
            $drugQuantity = json_decode($_POST['drugQuantity']);

            // Insert order record to the mysql
            $insertOrderSQL = "INSERT INTO `order` (order_status, order_description, order_quantity) "
                . "VALUES('Ordered', '$orderDescription', '$orderQuantity')";

            if ($conn->query($insertOrderSQL) === TRUE) {
                $orderID = $conn->insert_id;
                
                // Insert order_drug by using the latest order id
                for ($counter = 0; $counter < sizeof($drugID); $counter++) {
                    $insertOrderDrugSQL = "INSERT into `order_drug` (order_drug_quantity, order_id, drug_id) "
                        . "VALUES('$drugQuantity[$counter]', '$orderID', '$drugID[$counter]')";
                    $conn->query($insertOrderDrugSQL);
                }

                echo $orderID;

            } else {
                echo "Something went wrong!";
            }  

    } else {
        echo "Something went wrong!";
    }

    $conn->close();
    

?>