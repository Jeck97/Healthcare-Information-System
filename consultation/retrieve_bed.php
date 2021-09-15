<?php

    require "conn.php";

    $sql = "SELECT bed.bed_id, bed.bed_number, ward.ward_name from bed 
        inner join ward on bed.ward_id = ward.ward_id where bed_status='Available'";
    $result = $conn->query($sql);

    $bed = array();
    if ($result->num_rows >= 1) {
        
        array_push($bed, "Found");
        while ($row = $result->fetch_assoc()) {
            
            $bedRow = array();

            array_push($bedRow, $row['bed_id']);
            array_push($bedRow, $row['bed_number']);
            array_push($bedRow, $row['ward_name']);

            array_push($bed, $bedRow);
        }

        header('Content-Type: application/json');
        echo json_encode($bed);

    } else {
        array_push($bed, "NotFound");
        header('Content-Type: application/json');
        echo json_encode($bed);
    }

    $conn->close();

?>