<?php
require "conn.php";
$data = array();

if(isset($_POST['bill_id'])){
	$billId = $_POST['bill_id'];
	
	$search_payment_details_query = "select bill.bill_id,bill_total_price,patient_first_name,patient_last_name,patient_identification_number,patient_detail_address,patient_postcode,
	patient_area,patient_state,patient_country,patient_phone_number,`order`.order_quantity,order_drug.order_drug_quantity,drug.drug_id, drug.drug_name,drug.drug_price_per_unit,
	service.service_id, group_concat(service.service_name), service.service_price from payment inner join bill on payment.bill_id = bill.bill_id inner join consultation on 
	bill.consultation_id = consultation.consultation_id inner join queue on consultation.queue_id = queue.queue_id inner join patient on queue.patient_id = patient.patient_id left 
	join `order` on consultation.order_id = `order`.order_id left join order_drug on `order`.order_id = order_drug.order_id left join drug on order_drug.drug_id = drug.drug_id left 
	join service_consultation on service_consultation.consultation_id = consultation.consultation_id left join service on service.service_id = service_consultation.service_id where 
	bill.bill_id = '$billId' group by service_id;";
	
	$search_payment_details_result = mysqli_query($conn,$search_payment_details_query);
	
	if(mysqli_num_rows($search_payment_details_result) > 0){
		while($row = mysqli_fetch_array($search_payment_details_result)){
			$temp = array();
			
			if($row[17] != null){
				$service_name = explode(",",$row[17]);
			}
			
			$name = $row[2]." ".$row[3];
			$address = $row[5]." ".$row[6]." ".$row[7]." ".$row[8]." ".$row[9];
			
			$temp['bill_id'] = $row[0];
			$temp['bill_total_price'] = $row[1];
			$temp['name'] = $name;
			$temp['address'] = $address;
			$temp['phone_number'] = $row[10];
			$temp['drug_id'] = $row[13];
			$temp['drug_name'] = $row[14];
			$temp['order_quantity'] = $row[11];
			$temp['order_drug_quantity'] = $row[12];
			$temp['drug_price_per_unit'] = $row[15];
			$temp['service_id'] = $row[16];
			$temp['service_name'] = $service_name[0];
			$temp['service_price'] = $row[18];
			
			array_push($data,$temp);
		}
		echo json_encode($data);
	}else{
		echo json_encode(["message"=>"No result found"]);
	}
}else{
	echo json_encode(["message"=>"Something went wrong, please try again later"]);
}

mysqli_close($conn);
?>