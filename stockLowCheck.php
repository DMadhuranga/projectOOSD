<?php
/**
 * Created by PhpStorm.
 * User: Rivindu
 * Date: 6/30/2017
 * Time: 8:19 AM
 */
/**
 * @param $inventory : 2 for main inventory, 1 for dispensary inventory
 * @param $serials: give array of serial numbers
 */

function checkInventory($inventory,$serials)
{

    $conn = mysqli_connect('localhost', 'root', '1010');

    if (!$conn) {
        die('server is not responding');
    }

    if ($inventory == 2) {
        foreach ($serials as $serial){
            $queryDrug = "SELECT inventory_stock_status,inventory_alert_level,drug_name FROM hospital.drugs WHERE serial_number='$serial'";
            $resDrug = mysqli_query($conn, $queryDrug);
            if (!$resDrug) {
                printf("Error1: %s\n", mysqli_error($conn));
                exit();
            }
            if ($resDrug) {
                $rowDrug = mysqli_fetch_array($resDrug, MYSQLI_ASSOC);
                $alert = $rowDrug['inventory_alert_level'];
                $name = $rowDrug['drug_name'];
                if (!$rowDrug['inventory_stock_status'] == 1) {

                    //get total and check
                    $queryBatch = "SELECT inventory_balance FROM hospital.drug_batches WHERE serial_number='$serial'";
                    $resBatch = mysqli_query($conn, $queryBatch);
                    if (!$resBatch) {
                        printf("Error2: %s\n", mysqli_error($conn));
                        exit();
                    }
                    $total = 0;
                    while ($rowBatch = mysqli_fetch_array($resBatch, MYSQLI_ASSOC)) {
                        $stock_balance = $rowBatch['inventory_balance'];
                        $total = $total + $stock_balance;
                    }
                    if ($alert >= $total) {
                        $queryUPstate = "UPDATE hospital.drugs SET inventory_stock_status='1' WHERE serial_number='$serial'";
                        $ins1 = mysqli_query($conn, $queryUPstate);
                        $date = date('Y-m-d H:i:s');
                        $queryUPnot = "INSERT INTO hospital.notifications (date,message,description,type) VALUES ('$date','Stock Low Warning','drug $name (serial number $serial) is running out of stock in main inventory. Remaining amount: $total','1') ";
                        $ins2 = mysqli_query($conn, $queryUPnot);
                        $notificationNum=mysqli_insert_id($conn);
                        $queryUPunseenNot="INSERT INTO inventory.unseen_notifications (notification_id) VALUES ('$notificationNum')";
                        $ins3=mysqli_query($conn,$queryUPunseenNot);
                        if ($ins1 && $ins2) {
                            //echo'yes';
                        } else {
                            echo "sql wrong</br>" . mysqli_error($conn) . "</br>";
                        }
                    } else {
                        //echo 'no inv warning';
                    }
                } else {
                    //echo 'inv warning already set';
                }
            } else {
                echo 'error query';
            }
        }
    } elseif ($inventory == 1) {
        foreach ($serials as $serial) {
            $queryDrug = "SELECT dispensary_stock_status,dispensary_alert_level,drug_name FROM hospital.drugs WHERE serial_number='$serial'";
            $resDrug = mysqli_query($conn, $queryDrug);
            if (!$resDrug) {
                printf("Error1: %s\n", mysqli_error($conn));
                exit();
            }
            if ($resDrug) {
                $rowDrug = mysqli_fetch_array($resDrug, MYSQLI_ASSOC);
                $alert = $rowDrug['dispensary_alert_level'];
                $name = $rowDrug['drug_name'];
                if (!$rowDrug['dispensary_stock_status'] == 1) {
                    //get total and check
                    $queryBatch = "SELECT dispensory_balance FROM hospital.drug_batches WHERE serial_number='$serial'";
                    $resBatch = mysqli_query($conn, $queryBatch);
                    $total = 0;
                    if (!$resBatch) {
                        printf("Error2: %s\n", mysqli_error($conn));
                        exit();
                    }
                    while ($rowBatch = mysqli_fetch_array($resBatch, MYSQLI_ASSOC)) {
                        $stock_balance = $rowBatch['dispensory_balance'];
                        $total = $total + $stock_balance;
                    }

                    if ($alert >= $total) {
                        $queryUPstate = "UPDATE hospital.drugs SET dispensary_stock_status='1' WHERE serial_number='$serial'";
                        $ins1 = mysqli_query($conn, $queryUPstate);
                        $date = date('Y-m-d H:i:s');
                        $queryUPnot = "INSERT INTO hospital.notifications (date,message,description,type) VALUES ('$date','Stock Low Warning','drug $name (serial number $serial) is running out of stock in dispensary. Remaining amount: $total','1') ";
                        $ins2 = mysqli_query($conn, $queryUPnot);
                        $notificationNum=mysqli_insert_id($conn);
                        $queryUPunseenNot="INSERT INTO dispensary.unseen_notifications (notification_id) VALUES ('$notificationNum')";
                        $ins3=mysqli_query($conn,$queryUPunseenNot);
                        if ($ins1 && $ins2) {
                            //echo 'yes'.$serial;
                        } else {
                            echo "sql wrong</br>" . mysqli_error($conn) . "</br>";
                        }

                    } else {
                        //echo 'no dis warning'.$serial;
                    }
                } else {
                    //echo 'dis warning already set'.$serial;
                }
            } else {
                echo 'error query';

            }
        }
    } else {
        echo 'error. Check inventoty id';
    }
}
?>