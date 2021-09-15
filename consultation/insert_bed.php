<?php

    require "conn.php";

    if (isset($_POST['patientID']) && isset($_POST['bedID'])) {

        $patientID = $_POST['patientID'];
        $bedID = $_POST['bedID'];

        // Insert into patient_bed table
        $insertPatientBed = "INSERT into patient_bed (patient_id, bed_id) "
            . "VALUES('$patientID', '$bedID')";

        $conn->query($insertPatientBed);

        // Update the bed to be unavailable
        $updateBedSQL = "UPDATE bed SET bed_status='Unavailable' where bed_id='$bedID'";
        $conn->query($updateBedSQL);

        echo "Bed information updated";

    } else {
        echo "Something went wrong! Please try again later";
    }

    $conn->close();
?>