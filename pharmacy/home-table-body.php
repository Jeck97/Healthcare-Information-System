<?php
include "DbController.php";

$db = new DbController();
$conn = $db->getConn();

updateStatus($conn);

$sql = getQuery();
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc())
        echo getRow($row, $conn);
} else echo getNoResult(5);

$conn->close();


function getQuery()
{
    $sqlRevisitQueueNo = "(SELECT `q2`.`queue_number` FROM `queue` `q2` WHERE `q2`.`old_consultation_id` = `c1`.`consultation_id` AND `q1`.`queue_number` IS NOT NULL ORDER BY `q2`.`queue_id` DESC LIMIT 1)";
    $sql = "SELECT *, $sqlRevisitQueueNo AS `revisit_queue_number` FROM `order` JOIN `consultation` `c1` USING(`order_id`) JOIN `user` USING(`user_id`) JOIN `department` USING(`department_id`) JOIN `queue` `q1` USING(`queue_id`) JOIN `patient` USING(`patient_id`)";
    if (isset($_POST["search"]) && $_POST["search"] !== "") {
        $sql .= " WHERE ($sqlRevisitQueueNo = '" . $_POST['search'] . "' OR (`queue_number` = '" . $_POST['search'] . "' AND $sqlRevisitQueueNo IS NULL)) AND DATE(`q1`.`queue_date`) = CURDATE()";
    }
    if (isset($_POST["status"]) && $_POST["status"] != "all") {
        $sql .=  empty($_POST["search"]) ? " WHERE" : " AND";
        $sql .= " `order_status` = '" . $_POST['status'] . "'";
    }
    $sql .= " ORDER BY `queue_id` DESC";
    return $sql;
}

function getRow($row, $conn)
{
    $queueNo = $row["revisit_queue_number"] ?? $row["queue_number"];
    $orderId = $row["order_id"];
    $orderStatus = $row["order_status"];
    $orderDesc = $row["order_description"];
    $orderQty = $row["order_quantity"];

    $normalRowId = "normal-row-$orderId";
    $hidableRowId = "hidable-row-$orderId";
    $drugTbody = getDrugsTable($orderId, $conn);

    return <<<HTML
    <tr id="{$normalRowId}" class="normal-row" onclick="showHideRow('$normalRowId', '$hidableRowId');">
        <td class="text-align-center">$queueNo</td>
        <td class="text-align-center">$orderId</td>
        <td>$orderStatus</td>
        <td>$orderDesc</td>
        <td class="text-align-center">$orderQty</td>
    </tr>

    <tr id="{$hidableRowId}" class="hidable-row hide">
        <td colspan="5">
            <div class="order-detail">
                <h3>ORDER DETAIL</h3>
                <hr>
                <div class="person-detail">
                    <div>                        
                        <h3>Patient Info</h3>
                        <p><i class="fas fa-user"></i>{$row["patient_first_name"]} {$row["patient_last_name"]}</p>
                        <p><i class="fas fa-id-card"></i>{$row["patient_identification_number"]}</p>
                        <p><i class="fas fa-phone-alt"></i>{$row["patient_phone_number"]}</p>
                        <p><i class="fas fa-envelope"></i>{$row["patient_email"]}</p>
                    </div>
                    <div>                        
                        <h3>Doctor Info</h3>
                        <p><i class="fas fa-user"></i>{$row["user_first_name"]} {$row["user_last_name"]}</p>
                        <p><i class="fas fa-building"></i>{$row["department_name"]}</p>
                        <p><i class="fas fa-door-closed"></i>Room {$row["room_number"]}</p>
                    </div>
                    <div>                        
                        <h3>Date Ordered</h3>
                        <p><i class="fas fa-calendar-day"></i>{$row["order_created_datetime"]}</p>
                    </div>
                </div>
                <div class="drug-detail">
                    <h3>Drugs Info</h3>
                    <table id="table-drug-detail">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th class="expanded">Drug Name</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>$drugTbody</tbody>
                    </table>
                </div>
                <div class="button-status $orderStatus">
                    <button type="button" onclick="Dialog.open($orderId, '$orderStatus', $queueNo);">Update Status</button>
                </div>
            </div>
        </td>
    </tr>
    HTML;
}

function getDrugsTable($orderId, $conn)
{
    $sql = "SELECT * FROM `order` NATURAL JOIN `order_drug` NATURAL JOIN `drug` WHERE `order_id` = '$orderId'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $i = 0;
        $html = "";
        while ($row = $result->fetch_assoc())
            $html .= getDrugRow($row, ++$i);
    } else $html = getNoResult(3);

    return $html;
}

function getDrugRow($row, $i)
{
    return <<<HTML
    <tr>
        <td>$i</td>
        <td class="expanded">{$row['drug_name']}</td>
        <td>{$row['order_drug_quantity']}</td>
    </tr>
    HTML;
}

function getNoResult($colspan)
{
    return <<<HTML
     <tr>
        <td class="no-result" colspan="$colspan">Result not found</td>
    </tr>
    HTML;
}

function updateStatus($conn)
{

    if (empty($_POST["order-id"]) || empty($_POST["order-status-to"])) return;

    $orderId = $_POST["order-id"];
    $statusTo = $_POST["order-status-to"];

    $sql = $statusTo == "Prepared"
        ? "UPDATE `order` SET `order_status`='$statusTo' WHERE `order_id`= $orderId"
        : "UPDATE `order` SET `order_status`='$statusTo', `order_quantity`=`order_quantity` - 1, `order_dispense_datetime` = now() WHERE `order_id`='$orderId'";

    if ($conn->query($sql) === false) {
        echo '<script>alert("Failed to update status.");</script>';
    }

    echo '
     <script>
     if (window.history.replaceState) 
        window.history.replaceState(null, null, window.location.href);
    </script>';
}
