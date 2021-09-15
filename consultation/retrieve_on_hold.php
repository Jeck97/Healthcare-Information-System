<?php

    require "conn.php";

    $sql = "SELECT CONCAT(patient.patient_first_name, ' ', patient.patient_last_name) AS patient_name, " . 
        "consultation.consultation_reason, consultation.consultation_date_time, bed.bed_number, ward.ward_name, " .
        "patient_bed.patient_bed_id, patient_bed.bed_id from consultation inner join queue on consultation.queue_id " . 
        "= queue.queue_id inner join patient on queue.patient_id = patient.patient_id inner join patient_bed on " . 
        "patient.patient_id = patient_bed.patient_id inner join bed on patient_bed.bed_id = bed.bed_id inner join " . 
        "ward on bed.ward_id = ward.ward_id where consultation.on_hold = '1' AND patient_bed.patient_discharged_date IS NULL";

    $result = $conn->query($sql);

    $onhold_patient = array();
    if ($result->num_rows >= 1) {
        
        array_push($onhold_patient, "Found");
        while ($row = $result->fetch_assoc()) {
            
            $patient = array();
            array_push($patient, $row['patient_name']);
            array_push($patient, $row['consultation_reason']);
            array_push($patient, $row['consultation_date_time']);
            array_push($patient, $row['bed_number']);
            array_push($patient, $row['ward_name']);
            array_push($patient, $row['patient_bed_id']);
            array_push($patient, $row['bed_id']);
            array_push($onhold_patient, $patient);
        }

        header('Content-Type: application/json');
        echo json_encode($onhold_patient);

    } else {
        array_push($onhold_patient, "NotFound");
        header('Content-Type: application/json');
        echo json_encode($onhold_patient);
    }

    $conn->close();

?>
