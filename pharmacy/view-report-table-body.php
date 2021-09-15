<?php
include "DbController.php";

$db = new DbController();
$conn = $db->getConn();
$sql = getQuery();
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $total = 0;
    while ($row = $result->fetch_assoc())
        echo getRow($row, $total);
    echo getTotalRow($total);
} else echo getNoResult();

$conn->close();


function getQuery()
{
    $dateBetween = "";
    if (!empty($_POST["date-from"])) {
        $from = $_POST["date-from"];
        $to = $_POST["date-to"];
        $dateBetween = "AND order_dispense_datetime BETWEEN '$from' AND '$to 23:59:59' ";
    }
    return "SELECT order_dispense_datetime, order_created_datetime, order_id, 
            CONCAT(patient_first_name, ' ', patient_last_name) AS patient_name, 
            CONCAT(user_first_name, ' ', user_last_name) AS user_name, 
            SUM(order_drug_quantity * drug_price_per_unit) AS order_drug_total_fee 
            FROM `consultation` JOIN `order` USING(`order_id`) JOIN `user` USING(`user_id`) 
            JOIN `queue` USING(`queue_id`) JOIN `patient` USING(`patient_id`) 
            JOIN `order_drug` USING(`order_id`) JOIN `drug` USING(`drug_id`)
            WHERE order_dispense_datetime IS NOT NULL $dateBetween
            GROUP BY  order_dispense_datetime, order_created_datetime, order_id, 
            patient_first_name, patient_last_name, user_first_name, user_last_name 
            ORDER BY order_dispense_datetime DESC";
}

function getRow($row, &$total)
{
    $dateDispense = $row["order_dispense_datetime"];
    $dateCreated = $row["order_created_datetime"];
    $orderId = $row["order_id"];
    $patientName = $row["patient_name"];
    $doctorName = $row["user_name"];
    $drugTotalFee = $row["order_drug_total_fee"];
    $total += $drugTotalFee;

    return <<<HTML
    <tr>
        <td>$dateDispense</td>
        <td>$dateCreated</td>
        <td style="text-align: center;">$orderId</td>
        <td>$patientName</td>
        <td>$doctorName</td>
        <td style="text-align: right;">$drugTotalFee</td>
    </tr>
    HTML;
}

function getTotalRow($total)
{
    $formatTotal = number_format($total, 2, '.', '');
    return <<<HTML
    <tr><td colspan="6"></td></tr>
    <tr><td colspan="6"></td></tr>
    <tr>
        <td class="total">TOTAL DRUG FEE</td>
        <td class="total" style="text-align: end;" colspan="5">RM $formatTotal</td>
    </tr>
    HTML;
}

function getNoResult()
{
    return <<<HTML
     <tr>
        <td class="no-result" colspan="6">Result not found</td>
    </tr>
    HTML;
}
