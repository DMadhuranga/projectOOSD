<?php

function logUser($uid,$conn)
{
    $date = date('Y-m-d');
    $query = "SELECT user FROM hospital.loggers WHERE (login_date='$date') ";
    $res = mysqli_query($conn, $query);
    if (!$res) {
        printf("Error: %s\n", mysqli_error($conn));
        exit();
    } else {
        echo mysqli_num_rows($res);
        if (mysqli_num_rows($res) == 0) {
            //first user of the day
            $queryUp = "INSERT INTO hospital.loggers (login_date,user) VALUES ('$date','$uid')";
            $ins1 = mysqli_query($conn, $queryUp);
            //echo 'first user';
            include('expiryCheck.php');
            //checkDOE();

        } 
        else {
            //not the first user of the day
            $queryUser = "SELECT user FROM hospital.loggers WHERE (user='$uid' AND login_date='$date' ) ";
            $resUser = mysqli_query($conn, $queryUser);
            if (!$resUser) {
                printf("Error: %s\n", mysqli_error($conn));
                exit();
            } else {
                if (mysqli_num_rows($resUser) == 0) {
                    //not logged for the day
                    $queryUp2 = "INSERT INTO hospital.loggers (login_date,user) VALUES ('$date','$uid')";
                    $ins2 = mysqli_query($conn, $queryUp2);
                    //echo 'first time';

                } else {
                    //echo 'already logged once';

                }
            }
        }
        //$row=mysqli_fetch_array($res,MYSQLI_ASSOC);
    }
}
?>