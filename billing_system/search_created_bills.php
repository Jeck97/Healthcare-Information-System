<?php
require "conn.php";
date_default_timezone_set("Asia/Kuala_Lumpur");
$data = array();

if(isset($_POST['searchType'])){
	$searchType = $_POST['searchType'];
	
	if($searchType == "Today" || $searchType == "Yesterday"){
		if($searchType == "Today")
			$date = date('Y-m-d');
		else
			$date = date('Y-m-d',strtotime("-1 days"));
			
		$search_bill_query = "Select bill.bill_id,patient_first_name,patient_last_name,patient_identification_number,patient_gender, patient_detail_address,patient_postcode,
		patient_area,patient_state,patient_country,patient_phone_number, bill.bill_date_time from bill natural join consultation inner join queue on consultation.queue_id = 
		queue.queue_id inner join patient on queue.patient_id = patient.patient_id left join payment on bill.bill_id = payment.bill_id where DATE(bill_date_time)='$date' and 
		bill.bill_total_price is not null and payment_id is null;";
	}else if($searchType == 'Last 7 Days' || $searchType == 'Last 30 Days'){
		$currentDate = date('Y-m-d');
		if($searchType == "Last 7 Days")
			$date = date('Y-m-d', strtotime("-7 days"));
		else if($searchType == "Last 30 Days")
			$date = date('Y-m-d', strtotime("-30 days"));
		
		$search_bill_query = "Select bill.bill_id,patient_first_name,patient_last_name,patient_identification_number,patient_gender,patient_detail_address,patient_postcode,
		patient_area,patient_state,patient_country,patient_phone_number, bill.bill_date_time from bill natural join consultation inner join queue on consultation.queue_id = 
		queue.queue_id inner join patient on queue.patient_id = patient.patient_id left join payment on bill.bill_id = payment.bill_id where DATE(bill_date_time) between 
		'$date' and '$currentDate' and bill.bill_total_price is not null and payment_id is null;";
	}else{
		$search_bill_query = "Select bill.bill_id,patient_first_name,patient_last_name,patient_identification_number,patient_gender,patient_detail_address,patient_postcode,
		patient_area,patient_state,patient_country,patient_phone_number, bill.bill_date_time from bill natural join consultation inner join queue on consultation.queue_id = 
		queue.queue_id inner join patient on queue.patient_id = patient.patient_id left join payment on bill.bill_id = payment.bill_id where patient_identification_number = 
		'$searchType' and bill.bill_total_price is not null and payment_id is null;";
	}
	$search_bill_result = mysqli_query($conn, $search_bill_query);
	if(mysqli_num_rows($search_bill_result) > 0){
		while($row = mysqli_fetch_array($search_bill_result)){
			$temp = array();
			
			$address = $row[5]." ".$row[6]." ".$row[7]." ".$row[8]." ".$row[9];
			$name = $row[1]." ".$row[2];
			
			$temp['bill_id'] = $row[0];
			$temp['name'] = $name;
			$temp['identification_number'] = $row[3];
			$temp['gender'] = $row[4];
			$temp['address'] = $address;
			$temp['phone_number'] = $row[10];
			$temp['date'] = $row[11];

			array_push($data, $temp);
		}
		echo json_encode($data);
	}else{
		echo json_encode(["message"=>"No result found"]);
	}
}else{
	echo json_encode(["message"=>"Something went wrong, please try again later"]);
}

?>