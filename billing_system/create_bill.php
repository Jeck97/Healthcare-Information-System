<?php
require "conn.php";

if(isset($_POST['billId']) && isset($_POST['grandTotal'])){
	$billId = $_POST['billId'];
	$grandTotal = $_POST['grandTotal'];
	
	$create_bill_query = "Update bill set bill_total_price = '$grandTotal' where bill_id = '$billId';";
	$create_bill_result = mysqli_query($conn, $create_bill_query);
	
	if($create_bill_result){
		echo "Success";
	}else{
		echo "Something went wrong, please try again later";
	}
}else{
	echo "Something went wrong, please try again later";
}

mysqli_close($conn);
?>