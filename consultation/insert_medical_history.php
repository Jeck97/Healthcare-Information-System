<?php

    require "conn.php";

    if (isset($_POST['patientMedical']) && isset($_POST['consultationMedical'])) {

        $patientMedical = $_POST['patientMedical'];
        $consultationMedical = $_POST['consultationMedical'];

        $insertWithOrder = "INSERT into medical_history (medical_history_description, patient_id) "
                    . "VALUES('$consultationMedical', '$patientMedical')";

        $conn->query($insertWithOrder);

        echo "Medical record inserted successfully";

    } else {
        echo "Something went wrong! Please try again later";
    }

    $conn->close();

?>