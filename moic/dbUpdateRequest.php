<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 7/6/2017
 * Time: 7:22 PM
 */


$conn = mysqli_connect('localhost', 'root', '1010');
if ($conn) {
    $request=$_POST['rid'];
	$state=$_POST['state'];
	if($state=='1'){
		$status='1';
	}
	else{
	$status='2';
	$query="SELECT sending_dept FROM hospital.requests WHERE request_id='$request'";
	$res=mysqli_query($conn,$query);
	$row=mysqli_fetch_array($res,MYSQLI_ASSOC);
	$dept=$row['sending_dept'];
	}
    $queryUP = "UPDATE hospital.requests SET state='$status' WHERE request_id='$request'";
    echo $request;
    if ($dept=='2') {
        if($status=='1'){
            $queryNotify="INSERT INTO hospital.notifications (date,message,description,type) VALUES ('date('Y-m-d');','Inventory Drug Request','Your request was accepted by MOIC! $request','2')";
            $notificationNum = mysqli_insert_id($conn);
            $queryNotify2="INSERT INTO inventory.unseen_notifications (notification_id) VALUES ('$notificationNum')";
        }
        else{
            $queryNotify="INSERT INTO hospital.notifications (date,message,description,type) VALUES ('date('Y-m-d');','Inventory Drug Request','Your request was rejected by MOIC! $request','2')";
            $notificationNum = mysqli_insert_id($conn);
            $queryNotify2="INSERT INTO inventory.unseen_notifications (notification_id) VALUES ('$notificationNum')";
        }
    }
    else{
        if($status=='2'){
            $queryNotify="INSERT INTO hospital.notifications (date,message,description,type) VALUES ('date('Y-m-d');','Dispensary Drug Request','Your request was rejected by MOIC! $request','2')";
            $notificationNum = mysqli_insert_id($conn);
            $queryNotify2="INSERT INTO dispensary.unseen_notifications (notification_id) VALUES ('$notificationNum')";
        }
    }

    if(mysqli_query($conn, $queryUP) AND mysqli_query($conn,$queryNotify) AND mysqli_query($conn,$queryNotify2)) {
        echo "Success";
    }
    else{
        echo 'fail';
    }
}
else{
    echo "Error connn";
}


