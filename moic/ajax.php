<?php
include('../dbconnection.php');


if(isset($_REQUEST["del_user"])){
	$u_id = $_REQUEST["u_id"];
	$query = "UPDATE hospital.users SET deleted=1 WHERE u_id='$u_id'";
	$res = mysqli_query($conn,$query);
	if($res){
		echo "Ok";	
	}else{
		echo "no";
	}
	
	exit();
}

if(isset($_REQUEST["declined"])){
	$request_id = $_REQUEST["request_id"];
	$query = "UPDATE hospital.requests SET state=2 WHERE request_id='$request_id'";
	$res = mysqli_query($conn,$query);
	if($res){
		echo "Ok";	
	}else{
		echo "no";
	}
	
	exit();
}

if(isset($_REQUEST["accepted"])){
	$request_id = $_REQUEST["request_id"];
	$query = "UPDATE hospital.requests SET state=1 WHERE request_id='$request_id'";
	$res = mysqli_query($conn,$query);
	if($res){
		echo "Ok";	
	}else{
		echo "no";
	}
	
	exit();
}

?>