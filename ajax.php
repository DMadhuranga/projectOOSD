<?php
include('../dbconnection.php');


if(isset($_REQUEST["del_batch"])){
	$u_id = $_REQUEST["b_id"];
	$query = "UPDATE hospital.drug_batches SET deleted=1 WHERE batch_number='$b_id'";
	$res = mysqli_query($conn,$query);
	if($res){
		echo "Ok";	
	}else{
		echo "no";
	}
	
	exit();
}

?>