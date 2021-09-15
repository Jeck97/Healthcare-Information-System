<?php

    require "conn.php";

    $sql = "SELECT * from service where service_id > 1";
    $result = $conn->query($sql);

    $services = array();
    if ($result->num_rows >= 1) {
        
        array_push($services, "Found");
        while ($row = $result->fetch_assoc()) {
            
            $serviceRow = array();

            array_push($serviceRow, $row['service_id']);
            array_push($serviceRow, $row['service_name']);
            array_push($serviceRow, $row['service_price']);

            array_push($services, $serviceRow);
        }

        header('Content-Type: application/json');
        echo json_encode($services);

    } else {
        array_push($services, "NotFound");
        header('Content-Type: application/json');
        echo json_encode($services);
    }

    $conn->close();

?>