<?php
include("../dbconnection.php");

if(isset($_REQUEST["disDrugRequest"])){
	$str = $_REQUEST["drugs"];
	$str = substr($str,1);
	//$searchKey = "bshab12";
	$batches = explode("=", $str);
	$uid = $_REQUEST["uid"];
	$date = date("Y-m-d");
	$query = "INSERT into requests (date,sending_dept,receiving_dept,	u_id,state,description) values ('$date','1','0','$uid','0','Dispensary Drug Request');";
	mysqli_select_db($conn,"hospital");
	$res = mysqli_query($conn,$query);
	$ret = "";
	if($res){
		$query = "SELECT LAST_INSERT_ID();";
		$res = mysqli_query($conn,$query);
		if($res){
			$row=mysqli_fetch_array($res,MYSQLI_ASSOC);
			$query = "INSERT into request_details (request_id,serial_number,amount) values ";
			$id = $row["LAST_INSERT_ID()"];
			$data = "";
			for($i=0;$i<sizeof($batches);$i++) {
				$ro = explode("^", $batches[$i]);
				$data = $data."("."'".$id."'".","."'".$ro[0]."'".","."'".$ro[1]."'"."),";
			}
			$data = substr($data,0,-1);
			$query = $query.$data.";";
			$res = mysqli_query($conn,$query);
			if($res){
				$ret = "ok";
			}
		}
		
	}
	echo $ret;
	exit();
}
if(isset($_REQUEST["nserial_number"])){
	$searchKey = $_REQUEST["nserial_number"];
	$query = "SELECT batch_number,expire,dispensory_balance from drug_batches where ((serial_number='$searchKey' && deleted=0) && (expiry_status!=3 && dispensory_balance>0))";
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
    		$ret=$ret.$row["batch_number"]." ".$row["expire"]." ".$row["dispensory_balance"];
    		$num++;
  		}
	}
	echo $ret;
	exit();
}
if(isset($_REQUEST["disDrugIssue"])){
	$str = $_REQUEST["drugs"];
	$str = substr($str,1);
	//$searchKey = "bshab12";
	$batches = explode("=", $str);
	$uid = $_REQUEST["uid"];
	$pid = $_REQUEST["pid"];
	$date = date("Y-m-d");
	$query = "INSERT into issues (date,	u_id,reciever,patient_id) values ('$date','$uid','4','$pid');";
	$ret = "";
	mysqli_select_db($conn,"dispensary");
	$res = mysqli_query($conn,$query);
	if($res){
		$query = "SELECT LAST_INSERT_ID();";
		$res = mysqli_query($conn,$query);
		if($res){
			$row=mysqli_fetch_array($res,MYSQLI_ASSOC);
			$query = "INSERT into issue_details (issue_id,serial_number,batch_number,amount) values ";
			$id = $row["LAST_INSERT_ID()"];
			$data = "";
			for($i=0;$i<sizeof($batches);$i++) {
				$ro = explode("^", $batches[$i]);
				$data = $data."("."'".$id."'".","."'".$ro[0]."'".","."'".$ro[1]."'".","."'".$ro[2]."'"."),";
			}
			$data = substr($data,0,-1);
			$query = $query.$data.";";
			$res = mysqli_query($conn,"START TRANSACTION;");
			if($res){
				$res = mysqli_query($conn,$query);
				if($res){
					mysqli_select_db($conn,"hospital");
					for($i=0;$i<sizeof($batches);$i++) {
						$ro = explode("^", $batches[$i]);
						$query = "update drug_batches set dispensory_balance = 	dispensory_balance-'$ro[2]', total_balance = total_balance-'$ro[2]' where batch_number= '$ro[1]'";
						$res = mysqli_query($conn,$query);
						if(!$res){
							break;
						}
					}
					if($res){
						$res = mysqli_query($conn,"COMMIT;");
						if($res){
							$ret = "ok";
						}
					}
				}
			}
		}
		
	}
	echo $ret;
	exit();
}

if(isset($_REQUEST["respondToDelivery"])){
	$u_id = $_REQUEST["u_id"];
	$req_id = $_REQUEST["req_id"];
	$issue_id = $_REQUEST["issue_id"];
	$ret = "";
	$accept = $_REQUEST["accept"];
	$query = "SELECT batch_number,amount from inventory.issue_details WHERE issue_id='$issue_id';";
	$res = mysqli_query($conn,$query);
	if($res){
		$res1 = mysqli_query($conn,"START TRANSACTION;");
		if($res1){
			if($accept==1){
				while ($row = mysqli_fetch_array($res,MYSQLI_ASSOC)) {
					$batch_number = $row["batch_number"];
					$amount = $row["amount"];
					$res2 = mysqli_query($conn,"UPDATE hospital.drug_batches SET dispensory_balance=dispensory_balance+$amount WHERE batch_number='$batch_number';");
					if(!$res2){
						break;
					}
				}
				if($res2){
					$res3 = mysqli_query($conn,"UPDATE hospital.requests SET state=4 WHERE request_id = '$req_id'");
				}
			}else{
				while ($row = mysqli_fetch_array($res,MYSQLI_ASSOC)) {
					$batch_number = $row["batch_number"];
					$amount = $row["amount"];
					$res2 = mysqli_query($conn,"UPDATE hospital.drug_batches SET inventory_balance=inventory_balance+$amount WHERE batch_number='$batch_number';");
					if(!$res2){
						break;
					}
				}
				if($res2){
					$res3 = mysqli_query($conn,"UPDATE hospital.requests SET state=5 WHERE request_id = '$req_id'");
				}
			}
			if($res3){
				$res = mysqli_query($conn,"COMMIT;");
				if($res){
					$ret = "ok";
				}
			}

		}
	}
	echo $ret;
	exit();
}
?>