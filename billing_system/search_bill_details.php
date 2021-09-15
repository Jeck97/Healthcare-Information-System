<?php
require "conn.php";
$data = array();

if(isset($_POST['bill_id'])){
	$bill_id = $_POST['bill_id'];
	
	$search_bill_details_query = "Select bill.bill_id, `order`.order_quantity, order_drug.order_drug_quantity, drug.drug_id, drug.drug_name, drug.drug_price_per_unit, 
	service.service_id, group_concat(service.service_name), service.service_price from bill inner join consultation on bill.consultation_id =consultation.consultation_id left join 
	`order` on consultation.order_id = `order`.order_id left join order_drug on `order`.order_id = order_drug.order_id left join drug on order_drug.drug_id = drug.drug_id left join 
	service_consultation on service_consultation.consultation_id = consultation.consultation_id left join service on service.service_id = service_consultation.service_id where 
	bill.bill_id = '$bill_id' group by service_id;";
	
	$search_bill_details_result = mysqli_query($conn, $search_bill_details_query);
	
	if(mysqli_num_rows($search_bill_details_result) > 0){
		while($row = mysqli_fetch_array($search_bill_details_result)){
			$temp = array();
			
			if($row[7] != null){
				$service_name = explode(",",$row[7]);
			}
			
			$temp['bill_id'] = $row[0];
			$temp['drug_id'] = $row[3];
			$temp['drug_name'] = $row[4];
			$temp['order_quantity'] = $row[1];
			$temp['order_drug_quantity'] = $row[2];
			$temp['drug_price_per_unit'] = $row[5];
			$temp['service_id'] = $row[6];
			$temp['service_name'] = $service_name[0];
			$temp['service_price'] = $row[8];
			
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