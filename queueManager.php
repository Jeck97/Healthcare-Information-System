<?php


require_once "conn.php";

if (isset($_POST["getQueueNumber"])) {

    $message = "";
    $sql = "SELECT * FROM `queue` JOIN `consultation` ON `consultation`.queue_id = `queue`.queue_id JOIN `user` ON `user`.user_id=`consultation`.user_id WHERE DATE(queue_date) = CURDATE() and queue_status>0 AND (queue_type=1 OR queue_type=0) ORDER BY queue_status DESC LIMIT 5";
    $query = mysqli_query($conn, $sql);
    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_array($query)) {
            $message .= "<div class='container mt-3 p-5 bg-white text-center rounded shadow'>
            <div> <span style='font-size:7em'><strong>" . $row["queue_number"] . "</strong></span> <i class='fas fa-arrow-right mx-5'style='font-size:7em'></i> <span style='font-size:7em'><strong>ROOM " . $row["room_number"] . "</strong></span></div>
            </div>";
        }
    }
    echo $message;
}

if (isset($_POST["getQueueNumberPharmacy"])) {

    $message = "";
    $sql = "SELECT * FROM `queue` WHERE DATE(queue_date) = CURDATE() and queue_status>0 AND queue_type=-1 ORDER BY queue_status DESC LIMIT 5 ";
    $query = mysqli_query($conn, $sql);
    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_array($query)) {
            $message .= "<div class='container mt-3 p-5 bg-white text-center rounded shadow'>
            <div> <span style='font-size:7em'><strong>" . $row["queue_number"] . "</strong></span> <i class='fas fa-arrow-right mx-5'style='font-size:7em'></i> <span style='font-size:7em'><strong>COUNTER</strong></span></div>
            </div>";
        }
    }

    echo $message;
}

if (isset($_POST["nextQueue"])) {

    $meaage = "";
    $update = false;
    $sql = "SELECT MAX(queue_status) as total_queue FROM `queue` WHERE DATE(queue_date) = CURDATE()";
    $query = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($query);
    $queueStatus = $row["total_queue"] + 1;

    $sql = "SELECT * FROM `queue` WHERE DATE(queue_date) = CURDATE() and queue_status=0";
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_array($query)) {
            if (isset($_POST["consultation"])) {
                if ($row["queue_type"] == '1') {
                    // change when combine
                    if ($_POST["doctorID"] == 2) {
                        $sql = "Update `queue` SET queue_status=$queueStatus WHERE queue_id = " . $row["queue_id"] . "";
                        $update = mysqli_query($conn, $sql);
                        $sql = "UPDATE `consultation` SET `user_id`=$doctorID WHERE queue_id = " . $row["queue_id"] . "";
                        $update = mysqli_query($conn, $update);
                        break;
                    }
                } else if ($row["queue_type"] == '0') {
                    $sql = "Update `queue` SET queue_status=$queueStatus WHERE queue_id = " . $row["queue_id"] . "";
                    $update = mysqli_query($conn, $sql);
                    $sql = "UPDATE `consultation` SET `user_id`=$doctorID WHERE queue_id = " . $row["queue_id"] . "";
                    $update = mysqli_query($conn, $update);
                    break;
                }
            }
        }
    }

    $response = array();
    array_push($response, $row['patient_id']);
    array_push($response, $row['queue_id']);

    header('Content-Type: application/json');
    echo json_encode($response);
}

if (isset($_POST["nextPharmacy"])) {
    $queueNumber = $_POST["queueNumber"];

    $sql = "SELECT MAX(queue_status) as total_queue FROM `queue` WHERE DATE(queue_date) = CURDATE()";
    $query = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($query);
    $queueStatus = $row["total_queue"] + 1;

    $sql = "UPDATE `queue` SET queue_status=$queueStatus WHERE queue_number = $queueNumber AND DATE(queue_date) = CURDATE()";
    $update = mysqli_query($conn, $sql);
}
