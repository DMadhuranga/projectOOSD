<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 7/6/2017
 * Time: 7:22 PM
 */


$conn = mysqli_connect('localhost', 'root', '1010');
if ($conn) {
    $notification=$_POST['nid'];
    $queryUP = "UPDATE moic.unseen_notifications SET status='0' WHERE notification_id='$notification'";
    echo $notification;
    if(mysqli_query($conn, $queryUP)) {
        echo "Success";
    }
    else{
        echo 'fail';
    }
}
else{
    echo "Error connn";
}