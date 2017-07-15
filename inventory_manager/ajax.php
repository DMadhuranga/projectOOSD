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
if(isset($_REQUEST["invDrugRequest"])){
	$str = $_REQUEST["drugs"];
	$str = substr($str,1);
	//$searchKey = "bshab12";
	$batches = explode("=", $str);
	$uid = $_REQUEST["uid"];
	$date = date("Y-m-d");
	$query = "INSERT into requests (date,sending_dept,receiving_dept,	u_id,state,description) values ('$date','2','0','$uid','0','Inventory Drug Request');";
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

if(isset($_REQUEST["drugToDispensary"])){
	$req_id = $_REQUEST["req_id"];
	$batches = $_REQUEST["batches"];
	$u_id = $_REQUEST["u_id"];
	$date = date("Y-m-d");
	$query = "SELECT state from hospital.requests where request_id='$req_id';";
	$res = mysqli_query($conn,$query);
	$ret = "";
	if($res){
		$noRow = mysqli_fetch_array($res,MYSQLI_ASSOC);
		if($noRow["state"]==1){
	$query = "INSERT into inventory.issues (department_id,date,u_id) values (1,'$date','$u_id');";
	$res = mysqli_query($conn,$query);
	if($res){
		$query = "SELECT LAST_INSERT_ID();";
		$res = mysqli_query($conn,$query);
		if($res){
			$row=mysqli_fetch_array($res,MYSQLI_ASSOC);
			$id = $row["LAST_INSERT_ID()"];
			$res = mysqli_query($conn,"START TRANSACTION;");
			if($res){
				$query = "INSERT into inventory.issue_details (issue_id,serial_number,batch_number,amount) values ";
				$drugs = explode("=",$batches);
				$data = "";
				foreach ($drugs as $drug){
					$drugDetails = explode("^",$drug);
					for($i=1;$i<sizeof($drugDetails);$i++){
						$data = $data."('".$id."','".$drugDetails[0]."','".explode("~",$drugDetails[$i])[0]."','".explode("~",$drugDetails[$i])[1]."'),";
					}
				}
				$query = $query.substr($data,0,-1).";";
				$res = mysqli_query($conn,$query);
				if($res){
					foreach ($drugs as $drug) {
						$drugDetails = explode("^",$drug);
						for($i=1;$i<sizeof($drugDetails);$i++) {
							$batc = explode("~",$drugDetails[$i])[0];
							$batcQuantity = (int)explode("~",$drugDetails[$i])[1];
							$query = "UPDATE hospital.drug_batches SET inventory_balance=inventory_balance-'$batcQuantity' where batch_number = '$batc' ;";
							$res = mysqli_query($conn,$query);
							if(!$res){
								break;
							}
						}
						if(!$res){
							break;
						}
					}
					if($res){
						$query = "UPDATE hospital.requests SET state=3,issue_id='$id' where request_id='$req_id';";
						$res = mysqli_query($conn,$query);
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
	}
}else{
	$ret = "someOne";
}
}
	echo $ret;
	exit();
}

if(isset($_REQUEST["nserial_number"])){
	$searchKey = $_REQUEST["nserial_number"];
	$query = "SELECT batch_number,expire,inventory_balance from drug_batches where ((serial_number='$searchKey' && deleted=0) && (expiry_status=0 && inventory_balance>0))";
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
    		$ret=$ret.$row["batch_number"]." ".$row["expire"]." ".$row["inventory_balance"];
    		$num++;
  		}
	}
	echo $ret;
	exit();
}

if(isset($_REQUEST["invDrugIssueToOthers"])){
	$str = $_REQUEST["drugs"];
	$str = substr($str,1);
	//$searchKey = "bshab12";
	$batches = explode("=", $str);
	$uid = $_REQUEST["uid"];
	$department_id = $_REQUEST["department_id"];
	$date = date("Y-m-d");
	$query = "INSERT into issues (date,	u_id,department_id) values ('$date','$uid','$department_id');";
	$ret = "";
	mysqli_select_db($conn,"inventory");
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
						$query = "update drug_batches set inventory_balance = 	inventory_balance-'$ro[2]', other_departments_balance = other_departments_balance+'$ro[2]' where batch_number= '$ro[1]'";
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
?>