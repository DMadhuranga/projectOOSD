<?php
/**
 * Created by PhpStorm.
 * User: Rivindu
 * Date: 7/3/2017
 * Time: 6:08 AM
 */

//$conn = mysqli_connect('localhost', 'root', '1010');
include('dbconnection.php');
if (!$conn) {
    die('server is not responding');
}
else {
    $query = "SELECT batch_number,serial_number,expire,inventory_balance,dispensory_balance FROM hospital.drug_batches 
                WHERE (deleted=0 AND total_balance>0 AND expire = DATE_ADD(CURDATE(), INTERVAL 90 DAY))";
    $res = mysqli_query($conn, $query);
    checksql($res, 0);

    if ($res) {
        if (mysqli_num_rows($res) != 0) {
            while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
                $serial = $row['serial_number'];
                //echo $serial . " : " . $row['expire'].'90day'.'<br>';
                $batch = $row['batch_number'];
                $date = date('Y-m-d H:i:s');
                //echo $serial . " : " . $row['expire'].$batch.$invBal.$disBal.$date;
                $queryDrug = "SELECT drug_name FROM hospital.drugs WHERE serial_number='$serial'";
                $resDrug = mysqli_query($conn, $queryDrug);
                checksql($resDrug, 1);
                $drugname = mysqli_fetch_array($resDrug, MYSQLI_ASSOC);
                $name = $drugname['drug_name'];

                $queryUpdate = "UPDATE hospital.drug_batches SET expiry_status='1' WHERE batch_number='$batch'";
                $ins1 = mysqli_query($conn, $queryUpdate);
                checksql($ins1, 2);

                $queryNotify = "INSERT INTO hospital.notifications (date,message,description) VALUES ('$date','Drug Expiry Warning','drug $name (serial number $serial) batch no. $batch will expire in 90 days') ";
                $ins2 = mysqli_query($conn, $queryNotify);
                checksql($ins2, 3);
                $invBal = $row['inventory_balance'];
                $disBal = $row['dispensory_balance'];

                $notificationNum = mysqli_insert_id($conn);
                if ($invBal > 0) {
                    $queryUPunseenNotI = "INSERT INTO inventory.unseen_notifications (notification_id) VALUES ('$notificationNum')";
                    $ins3 = mysqli_query($conn, $queryUPunseenNotI);
                    checksql($ins3, 5);
                }
                if ($disBal > 0) {
                    $queryUPunseenNotD = "INSERT INTO dispensary.unseen_notifications (notification_id) VALUES ('$notificationNum')";
                    $ins4 = mysqli_query($conn, $queryUPunseenNotD);
                    checksql($ins4, 6);
                }
            }
        }
    } else {
        die('Server error1');
    }
    $query2 = "SELECT batch_number,serial_number,expire,inventory_balance,dispensory_balance FROM hospital.drug_batches 
                WHERE (deleted=0 AND total_balance>0 AND expire = DATE_ADD(CURDATE(), INTERVAL 45 DAY))";
    $res2 = mysqli_query($conn, $query2);
    checksql($res2, 0);

    if ($res2) {
        if (mysqli_num_rows($res2) != 0){
            while ($row = mysqli_fetch_array($res2, MYSQLI_ASSOC)) {
                $serial = $row['serial_number'];
               // echo $serial . " : " . $row['expire'].'45day'.'<br>';
                $batch = $row['batch_number'];
                $date = date('Y-m-d H:i:s');
                //echo $serial . " : " . $row['expire'].$batch.$invBal.$disBal.$date;
                $queryDrug = "SELECT drug_name FROM hospital.drugs WHERE serial_number='$serial'";
                $resDrug = mysqli_query($conn, $queryDrug);
                checksql($resDrug, 1);
                $drugname = mysqli_fetch_array($resDrug, MYSQLI_ASSOC);
                $name = $drugname['drug_name'];

                $queryUpdate = "UPDATE hospital.drug_batches SET expiry_status='2' WHERE batch_number='$batch'";
                $ins1 = mysqli_query($conn, $queryUpdate);
                checksql($ins1, 2);

                $queryNotify = "INSERT INTO hospital.notifications (date,message,description,type) 
              VALUES ('$date','Drug Expiry Warning','drug $name (serial number $serial) batch no. $batch will expire in 45 days','0') ";
                $ins2 = mysqli_query($conn, $queryNotify);
                checksql($ins2, 3);
                $invBal = $row['inventory_balance'];
                $disBal = $row['dispensory_balance'];

                $notificationNum = mysqli_insert_id($conn);
                if ($invBal > 0) {
                    $queryUPunseenNotI = "INSERT INTO inventory.unseen_notifications (notification_id) VALUES ('$notificationNum')";
                    $ins3 = mysqli_query($conn, $queryUPunseenNotI);
                    checksql($ins3, 5);
                }
                if ($disBal > 0) {
                    $queryUPunseenNotD = "INSERT INTO dispensary.unseen_notifications (notification_id) VALUES ('$notificationNum')";
                    $ins4 = mysqli_query($conn, $queryUPunseenNotD);
                    checksql($ins4, 6);
                }
            }
        }
    } else {
        die('Server error2');
    }

    $query3 = "SELECT batch_number,serial_number,expire,inventory_balance,dispensory_balance FROM hospital.drug_batches 
                WHERE (deleted=0 AND total_balance>0 AND expire = DATE_ADD(CURDATE(), INTERVAL 0 DAY))";
    $res3 = mysqli_query($conn, $query3);
    checksql($res3, 0);

    if ($res3) {
        if ((mysqli_num_rows($res3) == 0) AND (mysqli_num_rows($res2)==0) AND (mysqli_num_rows($res)==0)) {
            //echo 'no expiry warnings';
        } else {
            while ($row = mysqli_fetch_array($res3, MYSQLI_ASSOC)) {
                $serial = $row['serial_number'];
                // echo $serial . " : " . $row['expire'].'45day'.'<br>';
                $batch = $row['batch_number'];
                $date = date('Y-m-d H:i:s');
                //echo $serial . " : " . $row['expire'].$batch.$invBal.$disBal.$date;
                $queryDrug = "SELECT drug_name FROM hospital.drugs WHERE serial_number='$serial'";
                $resDrug = mysqli_query($conn, $queryDrug);
                checksql($resDrug,1);
                $drugname = mysqli_fetch_array($resDrug, MYSQLI_ASSOC);
                $name = $drugname['drug_name'];

                $queryUpdate = "UPDATE hospital.drug_batches SET expiry_status='3' WHERE batch_number='$batch'";
                $ins1 = mysqli_query($conn, $queryUpdate);
                checksql($ins1, 2);

                $queryNotify = "INSERT INTO hospital.notifications (date,message,description,type) 
              VALUES ('$date','Drug Expiry Warning','drug $name (serial number $serial) batch no. $batch will expire in 0 days','0') ";
                $ins2 = mysqli_query($conn, $queryNotify);
                checksql($ins2, 3);
                $invBal = $row['inventory_balance'];
                $disBal = $row['dispensory_balance'];

                $notificationNum = mysqli_insert_id($conn);
                if ($invBal > 0) {
                    $queryUPunseenNotI = "INSERT INTO inventory.unseen_notifications (notification_id) VALUES ('$notificationNum')";
                    $ins3 = mysqli_query($conn, $queryUPunseenNotI);
                    checksql($ins3, 5);
                }
                if ($disBal > 0) {
                    $queryUPunseenNotD = "INSERT INTO dispensary.unseen_notifications (notification_id) VALUES ('$notificationNum')";
                    $ins4 = mysqli_query($conn, $queryUPunseenNotD);
                    checksql($ins4, 6);
                }
            }
        }
    } else {
        die('Server error3');
    }
}
function checksql($res,$num){
    if (!$res) {
        $conn = mysqli_connect('localhost', 'root', '1010');
        printf("Error $num: %s\n", mysqli_error($conn));
        exit();
    }
}
