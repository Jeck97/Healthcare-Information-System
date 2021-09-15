<?php

    require "conn.php";

    $consultationID = array();
    if (isset($_POST['queue_id'])) {

        $queueID = $_POST['queue_id'];

        $sql = "SELECT consultation_id from consultation where queue_id='$queueID'";
        $result = $conn->query($sql);
        
        if ($result->num_rows > 0) {
            
            array_push($consultationID, "Found");
            if ($row = $result->fetch_assoc()) {
                
                array_push($consultationID, $row['consultation_id']);
            }

            header('Content-Type: application/json');
            echo json_encode($consultationID);

        } else {
            array_push($consultationID, "NotFound");
            header('Content-Type: application/json');
            echo json_encode($consultationID);
        }

    } else {
        array_push($consultationID, "NotFound");
        header('Content-Type: application/json');
        echo json_encode($consultationID);
    }

    
    $conn->close();

?>