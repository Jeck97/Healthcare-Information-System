<?php
include "DbController.php";

$db = new DbController();
$conn = $db->getConn();

$sql = getQuery();
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc())
        echo getRow($row);
} else echo getNoResult();

$conn->close();


function getQuery()
{
    $sql = "SELECT * FROM `drug`";
    if (!empty($_POST["search"])) {
        $search = $_POST['search'];
        $sql .= " WHERE `drug_id` = '$search' OR `drug_name` LIKE '%$search%'";
    }
    $sql .= " ORDER BY `drug_id`";

    return $sql;
}

function getRow($row)
{
    $drugId = $row["drug_id"];
    $drugName = $row["drug_name"];
    $drugDesc = $row["drug_description"];
    $drugPrice = $row["drug_price_per_unit"];

    return <<<HTML
    <tr>
        <td style="text-align: center;">$drugId</td>
        <td>{$drugName}</td>
        <td>{$drugDesc}</td>
        <td style="text-align: end;">$drugPrice</td>
        <td style="text-align: center;">
            <button type="button" onclick="Dialog.onEdit($drugId, '$drugName', '$drugDesc', $drugPrice);">
                <i class="fas fa-edit"></i>
            </button>
        </td>
    </tr>
    HTML;
}

function getNoResult()
{
    return <<<HTML
    <tr>
        <td class="no-result" colspan="5">Result not found</td>
    </tr>
    HTML;
}
