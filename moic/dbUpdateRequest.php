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
    $status=$_POST['state'];
    $comment=$_POST['comment'];
    $query="SELECT sending_dept FROM hospital.requests WHERE request_id='$request'";
    $res=mysqli_query($conn,$query);
    if (!$res){
        die("Server error");
    }
    $row=mysqli_fetch_array($res,MYSQLI_ASSOC);
    $dept=$row['sending_dept'];

    $queryUP = "UPDATE hospital.requests SET state='$status' WHERE request_id='$request'";
    //echo "Request".$request;
    //echo "dept : ".$dept;
    //echo "status : ".$status;
    $date=date("Y-m-d");
    /**notificaation**/
    if ($dept=='2') {
        if($status=='1'){
            $queryNotify="INSERT INTO hospital.notifications (date,message,description,type) VALUES ('$date' ,'Inventory Drug Request','Your request was accepted by MOIC!($request) $comment','2')";
            //$queryNotify="INSERT INTO hospital.notifications (date,message,description,type) VALUES (date('Y-m-d H:i:s'),'Inventory Drug Request','Your request was accepted by MOIC! $request','2')";
            $notificationNum = mysqli_insert_id($conn);
            $queryNotify2="INSERT INTO inventory.unseen_notifications (notification_id) VALUES ('$notificationNum')";
        }
        else{
            $queryNotify="INSERT INTO hospital.notifications (date,message,description,type) VALUES ('$date','Inventory Drug Request','Your request was rejected by MOIC!($request) $comment','2')";
            //$queryNotify="INSERT INTO hospital.notifications (date,message,description,type) VALUES (date('Y-m-d H:i:s'),'Inventory Drug Request','Your request was rejected by MOIC! $request','2')";
            $notificationNum = mysqli_insert_id($conn);
            $queryNotify2="INSERT INTO inventory.unseen_notifications (notification_id) VALUES ('$notificationNum')";
        }
    }
    else{
        if($status=='2'){
            $queryNotify="INSERT INTO hospital.notifications (date,message,description,type) VALUES ('$date','Dispensary Drug Request','Your request was rejected by MOIC!($request) $comment','2')";
            //$queryNotify="INSERT INTO hospital.notifications (date,message,description,type) VALUES (date('Y-m-d H:i:s'),'Dispensary Drug Request','Your request was rejected by MOIC! $request','2')";
            $notificationNum = mysqli_insert_id($conn);
            $queryNotify2="INSERT INTO dispensary.unseen_notifications (notification_id) VALUES ('$notificationNum')";
        }
    }

    /**notificaation**/
    if(mysqli_query($conn, $queryUP) AND mysqli_query($conn,$queryNotify)){
        $notificationNum = mysqli_insert_id($conn);
        if ($dept=='2') {
            $queryNotify2="INSERT INTO inventory.unseen_notifications (notification_id) VALUES ('$notificationNum')";
        }
        else{
            $queryNotify2="INSERT INTO dispensary.unseen_notifications (notification_id) VALUES ('$notificationNum')";
        }
        if(mysqli_query($conn, $queryNotify2)){
            echo "Success";
        }
        else{
            echo "Error quer2";
        }
    }
    else{
        echo 'Error query';
    }
}
else{
    echo "Error connn";
}


