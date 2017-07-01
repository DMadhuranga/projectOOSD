<?php
include("../dbconnection.php");

if(isset($_REQUEST["nbatch_number"])){
	$searchKey = $_REQUEST["nbatch_number"];
	//$searchKey = "bshab12";
	$query = "SELECT serial_number from drug_batches where (batch_number='$searchKey' && deleted=0)";
	mysqli_select_db($conn,"hospital");
	$res = mysqli_query($conn,$query);
	$ret = "";
	if($res){
		$ret = "ok";
		if(mysqli_num_rows($res)!=0){
			$ret = "1";
		}
		
	}
	echo $ret;
	exit();
}
if(isset($_REQUEST["addArrival"])){
	$str = $_REQUEST["batches"];
	//$searchKey = "bshab12";
	$batches = explode("=", $str);
	$data = "";

	for($i=0;$i<sizeof($batches)-1;$i++) {
		$row = explode("^", $batches[$i]);
		$data = $data."("."'".$row[1]."'".","."'".$row[0]."'".","."'".substr($row[3],6)."-".substr($row[3],0,2)."-".substr($row[3],3,2)."'".","."'".substr($row[4],6)."-".substr($row[4],0,2)."-".substr($row[4],3,2)."'".","."'".$row[2]."'".","."'".$row[2]."'".","."'".$row[2]."'"."),";
	}
	$data = substr($data,0,-1);
	$query = "INSERT into drug_batches (batch_number,serial_number,arrival,expire,arrival_amount,inventory_balance,total_balance) values ";
	$query = $query.$data.";";
	mysqli_select_db($conn,"hospital");
	$res = mysqli_query($conn,$query);
	$ret = "";
	if($res){
		$ret = "ok";
	}
	echo $ret;
	exit();
}


?>