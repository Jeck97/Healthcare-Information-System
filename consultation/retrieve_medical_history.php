<?php

    require "conn.php";

    if (isset($_POST['patient_id'])) {

        $patientID = $_POST['patient_id'];

        $sql = "SELECT * from medical_history where patient_id='$patientID' order by medical_history_id desc";
        $result = $conn->query($sql);

        $medicalHistory = array();

        if ($result->num_rows >= 1) {
        
            array_push($medicalHistory, "Found");

            while ($row = $result->fetch_assoc()) {
                $medicalRecord = array();
                array_push($medicalRecord, $row['medical_history_id']);
                array_push($medicalRecord, $row['medical_history_description']);
                array_push($medicalRecord, $row['medical_history_date']);
                array_push($medicalHistory, $medicalRecord);
            }

            header('Content-Type: application/json');
            echo json_encode($medicalHistory);

        } else {
            array_push($medicalHistory, "NotFound");
            header('Content-Type: application/json');
            echo json_encode($medicalHistory);
        }

    } else {
        echo "Something went wrong! Please try again later";
    }

    $conn->close();

?>
