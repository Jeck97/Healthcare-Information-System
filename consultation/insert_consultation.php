<?php

    require "conn.php";

    if (isset($_POST['consultationID']) 
        && isset($_POST['revisitStatus']) 
        && isset($_POST['onhold'])) {

            $consultationID = $_POST['consultationID'];
            $revisitStatus = $_POST['revisitStatus'];
            $onhold = $_POST['onhold'];

            if (isset($_POST['orderID'])) {
                $orderID = $_POST['orderID'];

                $insertWithOrder = "UPDATE consultation SET revisit_status='$revisitStatus', 
                    order_id='$orderID', on_hold='$onhold' WHERE consultation_id='$consultationID'";

                if ($conn->query($insertWithOrder) === TRUE) {
                    echo "Success";
                } else {
                    echo "Something went wrong! Please try again later";
                }

            } else {

                echo "Checking " . $revisitStatus;
                echo "Checking " . $onhold;
                echo "Checking " . $consultationID;

                $insertWithoutOrder = "UPDATE consultation SET revisit_status='$revisitStatus', 
                    on_hold='$onhold' WHERE consultation_id='$consultationID'";

                if ($conn->query($insertWithoutOrder) === TRUE) {
                    echo "Success";
                } else {
                    echo "Something went wrong! Please try again later";
                } 
            }

    } else {
        echo "Something went wrong! Please try again later";
    }

    $conn->close();

?>