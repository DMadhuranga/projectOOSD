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
    checkDrugs(1, 90);
    checkDrugs(2, 45);
    checkDrugs(3, 0);
}
function checksql($res,$num){
    if (!$res) {
        $conn = mysqli_connect('localhost', 'root', '1010');
        printf("Error $num: %s\n", mysqli_error($conn));
        exit();
    }
}

function checkDrugs($status,$days)
{
    $conn = mysqli_connect('localhost', 'root', '1010');
    $query = "SELECT batch_number,serial_number,expire,inventory_balance,dispensory_balance FROM hospital.drug_batches 
                WHERE (deleted=0 AND expiry_status<$status AND total_balance>0 AND expire <= DATE_ADD(CURDATE(), INTERVAL $days DAY))";
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

                $queryUpdate = "UPDATE hospital.drug_batches SET expiry_status=$status WHERE batch_number='$batch'";
                $ins1 = mysqli_query($conn, $queryUpdate);
                checksql($ins1, 2);

                $queryNotify = "INSERT INTO hospital.notifications (date,message,description,type) VALUES ('$date','Drug Expiry Warning','drug $name (serial number $serial) batch no. $batch will expire in $days days','0') ";
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
        die('Server error' . $status);
    }
}
