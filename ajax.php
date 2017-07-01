<?php
include("dbconnection.php");

if($_REQUEST["nserial_number"]){
	$searchKey = $_REQUEST["nserial_number"];
	$query = "SELECT batch_number,expire,dispensory_balance from drug_batches where (serial_number='$searchKey' && deleted=0)";
	$query1 = "SELECT drug_name from drugs where (serial_number='$searchKey')";
	mysqli_select_db($conn,"hospital");
	$res = mysqli_query($conn,$query);
	$res1 = mysqli_query($conn,$query1);
	$ret = "";
	if($res && $res1){
		$row1=mysqli_fetch_array($res1,MYSQLI_ASSOC);
		$num = 0;
		while($row=mysqli_fetch_array($res,MYSQLI_ASSOC)){
			if($num>0){
				$ret=$ret."+";
			}
    		$ret=$ret.$row["batch_number"]." ".$row["expire"]." ".$row["dispensory_balance"]." ".$row1["drug_name"];
    		$num++;
  		}
	}
	echo $ret;
	exit();
}
?>