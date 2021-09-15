<?php

    require "conn.php";

    if (isset($_POST['patientBedID']) && isset($_POST['bedID'])) {

        // Update patient_bed table
        $dateCurrent = date("Y-m-d");
        $patientBedID = $_POST['patientBedID'];
        $dischargeSQL = "UPDATE patient_bed SET patient_discharged_date='$dateCurrent' where patient_bed_id='$patientBedID'";
        $conn->query($dischargeSQL);

        // Update bed availability
        $bedID = $_POST['bedID'];
        $updateBed = "UPDATE bed SET bed_status='Discharged' where bed_id='$bedID'";
        $conn->query($updateBed);

        echo "Bed discharged";

    } else {
        echo "Something went wrong! Please try again later";
    }


?>