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
    $queryUP = "UPDATE hospital.requests SET state='1' WHERE request_id='$request'";
    echo $request;
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


