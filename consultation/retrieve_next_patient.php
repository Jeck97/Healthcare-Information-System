<?php

    require "conn.php";

    // This php is used to retrieve the patient information for next patient
    // At here I only retrieve the dummy patient by using the patient id passed

    if (isset($_POST['patient_id'])) {

        $patientID = $_POST['patient_id'];

        $sql = "SELECT * from patient where patient_id='$patientID'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            
            if ($row = $result->fetch_assoc()) {
                $patient = array();
                array_push($patient, "Found");
                array_push($patient, $row['patient_first_name']);
                array_push($patient, $row['patient_last_name']);
                array_push($patient, $row['patient_dob']);
                array_push($patient, $row['patient_identification_number']);
                array_push($patient, $row['patient_email']);
                array_push($patient, $row['patient_phone_number']);
                array_push($patient, $row['patient_address_line_1']);
                array_push($patient, $row['patient_address_line_2']);
                array_push($patient, $row['patient_city']);
                array_push($patient, $row['patient_postcode']);
                array_push($patient, $row['patient_state']); 
                array_push($patient, $row['patient_id']);               

                header('Content-Type: application/json');
                echo json_encode($patient);
            }

        } else {
            $patient = array();
            array_push($patient, "NotFound");
            header('Content-Type: application/json');
            echo json_encode($patient);
        }

    } else {
        echo "Something went wrong! Please try again later";
    }

    $conn->close();

?>
