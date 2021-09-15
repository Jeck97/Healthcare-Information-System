<?php
require "conn.php";
date_default_timezone_set("Asia/Kuala_Lumpur");

if(isset($_POST['billId'])){
	$billId = $_POST['billId'];
	$date = date('Y-m-d H:i:s');
	
	$insert_payment_query = "Insert into payment (bill_id, payment_date_time) values ('$billId','$date');";
	$insert_payment_result = mysqli_query($conn, $insert_payment_query);
	
	if($insert_payment_result){
		echo "Success";
	}else{
		echo "Something went wrong, please try again later";
	}
}

mysqli_close($conn);
?>