<?php

    require "conn.php";

    if (isset($_POST['consultationID']) && isset($_POST['services'])) {

        $consultationID = $_POST['consultationID'];
        $services = $_POST['services'];

        $split_services = explode(",", $services);
			
		// For each category in array
		foreach ($split_services as $services){
			// Insert interested category into database
			$insert_service_consultation = "Insert into service_consultation (service_id, consultation_id) values ('$services', '$consultationID');";
			$insert_service_consultation_result = mysqli_query($conn, $insert_service_consultation);
		}

        echo "Service consultation inserted successfully";

    } else {
        echo "Something went wrong! Please try again later";
    }

    mysqli_close($conn);

?>