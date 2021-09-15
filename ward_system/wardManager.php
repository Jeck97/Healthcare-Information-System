<?php

require_once "conn.php";


if (isset($_POST["addWard"])) {
    $wardName = $_POST["wardName"];
    $wardLocation = $_POST["wardLocation"];
    $wardType = $_POST["wardType"];
    $departmentID = $_POST["departmentID"];

    $sql = "INSERT INTO `ward`(`ward_name`, `ward_location`, `ward_type`, `department_id`) VALUES ('$wardName','$wardLocation','$wardType','$departmentID')";
    $query = mysqli_query($conn, $sql);

    echo ("<script LANGUAGE='JavaScript'>
        						    window.alert('Successfully added a new ward.');
        						    window.location.href='index.php';
        						    </script>");
}

if (isset($_POST["editWard"])) {
    $wardID = $_POST["wardID"];
    $wardName = $_POST["wardName"];

    $sql = "UPDATE `ward` SET `ward_name`='$wardName' WHERE `ward_id`='$wardID'";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        echo ("<script LANGUAGE='JavaScript'>
        						    window.alert('Successfully edited the ward.');
        						    window.location.href='index.php';
        						    </script>");
    } else {
        echo ("<script LANGUAGE='JavaScript'>
        						    window.alert('Fail to edit the ward.');
        						    window.location.href='index.php';
        						    </script>");
    }
}


if (isset($_POST["addBed"])) {
    $wardID = $_POST["wardID"];
    $numberOfBed = $_POST["bedNumber"];
    $bedStatus = "Available";

    $sql = "SELECT COUNT(*) as total_bed FROM bed WHERE ward_id =$wardID";
    $query = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($query);

    for ($i = 1; $i <= $numberOfBed; $i++) {
        $sql = "INSERT INTO `bed`(`bed_number`, `bed_status`, `ward_id`) VALUES ('" . $row["total_bed"] + $i . "','$bedStatus','$wardID')";
        $result = mysqli_query($conn, $sql);
    }

    if ($result) {
        echo ("<script LANGUAGE='JavaScript'>
        						    window.alert('Successfully added the bed.');
        						    window.location.href='bedList.php';
        						    </script>");
    } else {
        echo ("<script LANGUAGE='JavaScript'>
        						    window.alert('Fail to add the bed.');
        						    window.location.href='bedList.php';
        						    </script>");
    }
}

if (isset($_POST["getWard"])) {

    $searchText = $_POST["query"];

    $sql = "SELECT * FROM ward WHERE ward_name LIKE '%$searchText%'";
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_array($query)) {
            echo '<a class="ward list-group-item list-group-item-action border-1" href="' . $row['ward_name'] . '" onclick="return false">' . $row['ward_name'] . '</a>';
        }
    } else {
        echo '<p class="list-group-item border-1">No Record</p>';
    }
}

if (isset($_POST["getBed"])) {

    $searchText = $_POST["query"];

    $sql = "SELECT * FROM bed JOIN ward ON bed.ward_id=ward.ward_id WHERE ward_name='$searchText'";
    $query = mysqli_query($conn, $sql);

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_array($query)) {
            echo '
                    <tr>
                        <td>' . $row["ward_name"] . '</td>
                        <td>' . $row["bed_number"] . '</td>
                        <td>' . $row["bed_status"] . '</td>
                        <td>' . $row["bed_status"] . '</td>';
            if ($row["bed_status"] == "Unavailable") {
                echo '<td>  <a href="wardManager.php?discharge&bedID=' . $row["bed_id"] . '"><button class="btn btn-primary text-uppercase">Discharge</button></a></td>';
            } else if ($row["bed_status"] == "Discharged") {
                echo '<td>  <a href="wardManager.php?makeAvailable&bedID=' . $row["bed_id"] . '"><button class="btn btn-primary text-uppercase">Make Available</button></a></td>';
            } else {
                echo '<td></td>';
            }
            echo '</tr>';
        }
    } else {
        echo '<p class="align-center">No Record</p>';
    }
}

if (isset($_GET["discharge"])) {
    $bedID = $_GET["bedID"];

    $sql = "UPDATE bed SET bed_status = 'Discharged' WHERE bed_id=$bedID";
    $query = mysqli_query($conn, $sql);

    echo ("<script LANGUAGE='JavaScript'>
        						    window.alert('Successfully discharge the patient.');
        						    window.location.href='bedList.php';
        						    </script>");
}

if (isset($_GET["makeAvailable"])) {
    $bedID = $_GET["bedID"];

    $sql = "UPDATE bed SET bed_status = 'Available' WHERE bed_id=$bedID";
    $query = mysqli_query($conn, $sql);

    echo ("<script LANGUAGE='JavaScript'>
        						    window.alert('Successfully make the bed available.');
        						    window.location.href='bedList.php';
        						    </script>");
}


if (isset($_GET["occupy"])) {
    $bedID = $_GET["bedID"];

    $sql = "UPDATE bed SET bed_status = 'Unavailable' WHERE bed_id=$bedID";
    $query = mysqli_query($conn, $sql);

    echo ("<script LANGUAGE='JavaScript'>
        						    window.alert('Successfully occupy the bed.');
        						    window.location.href='bedList.php';
        						    </script>");
}
