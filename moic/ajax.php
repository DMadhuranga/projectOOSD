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

?>