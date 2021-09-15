<?php

    require "conn.php";

    $sql = "SELECT * from drug";
    $result = $conn->query($sql);

    $drug = array();
    if ($result->num_rows >= 1) {
        
        array_push($drug, "Found");
        while ($row = $result->fetch_assoc()) {
            
            $drugRow = array();

            array_push($drugRow, $row['drug_id']);
            array_push($drugRow, $row['drug_name']);
            array_push($drugRow, $row['drug_description']);
            array_push($drugRow, $row['drug_price_per_unit']);

            array_push($drug, $drugRow);
        }

        header('Content-Type: application/json');
        echo json_encode($drug);

    } else {
        array_push($drug, "NotFound");
        header('Content-Type: application/json');
        echo json_encode($drug);
    }

    $conn->close();

?>