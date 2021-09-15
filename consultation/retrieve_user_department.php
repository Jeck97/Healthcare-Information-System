<?php

    require "conn.php";
    $userInfo = array();
    // May get from the session login later on
    if (isset($_POST['user_id'])) {

        $userID = $_POST['user_id'];
        $query = "SELECT user_first_name, user_last_name, department_name FROM `user` inner join department where user_id='$userID'";
        $result = $conn->query($query);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            array_push($userInfo, "Found");
            array_push($userInfo, $row['user_first_name']);
            array_push($userInfo, $row['user_last_name']);
            array_push($userInfo, $row['department_name']);
            header('Content-Type: application/json');
            echo json_encode($userInfo);
        } else {
            array_push($userInfo, "NotFound");
            header('Content-Type: application/json');
            echo json_encode($userInfo);
        }

    } else {
        array_push($userInfo, "Something went wrong! Please try again later");
        header('Content-Type: application/json');
        echo json_encode($userInfo);
    }

?>