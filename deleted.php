<?php
include('../dbconnection.php');

if(isset($_REQUEST["del_batch"])){
	$batch = $_REQUEST["batch_number"];
	$query = "UPDATE hospital.drug_batches SET deleted=1 WHERE batch_number='$batch'";
	$res = mysqli_query($conn,$query);
	if($res){
		echo "Ok";	
	}
	else{
		echo "no";
	}
	



		exit();
}
?>