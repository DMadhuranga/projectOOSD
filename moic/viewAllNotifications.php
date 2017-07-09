<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 7/7/2017
 * Time: 9:59 AM
 */

session_start();
include('../dbconnection.php'); //$conn
include('../User.php');
include('basicTemp.php');

if(!isset($_SESSION['logged']) || !isset($_SESSION['user'])){
    header('location:../login.php');
}
$pages = $_SESSION['pages'];
$user = unserialize($_SESSION['user']);
if ($user->getRoleId()!=0){
    header('location:../logout.php');
}
?>


<html>
    <head>

    </head>
    <body>
        <div class='container-fluid'>
            <div class='row'>
                <div class='col-md-2 col-md-2-height1'>
                    <div class = "row">
                        <ul class="nav nav-pills nav-stacked">
                            <?php
                                foreach( $pages as $tempPag ) {?>
                                <li><a href="<?php echo $tempPag[1]; ?>"><?php echo $tempPag[0]; ?></a></li>
                                <?php
                                    };
                                ?>
                        </ul>
                    </div>
                </div>
                <div class='col-md-10'>
                    <div class="row">
                <!-- Put Anything-->
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Notification</th>
                                    <th>View</th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                    $query="SELECT * FROM moic.unseen_notifications ORDER BY notification_id DESC";
                                    $res=mysqli_query($conn,$query);
                                    if ($res) {
                                        $notifications = array();
                                        while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
                                            array_push($notifications, array($row['notification_id'], $row['status']));
                                        }

                                        foreach ($notifications as $notification) {
                                            $notification_id=$notification[0];
                                            $queryNotification="SELECT * FROM hospital.notifications WHERE notification_id='$notification_id'";
                                            $result=mysqli_query($conn,$queryNotification);
                                            $rowNotification=mysqli_fetch_array($result,MYSQLI_ASSOC);
                                            $date=$rowNotification['date'];
                                            $heading=$rowNotification['message'];

                                            ?>
                                            <tr>
                                                <?php if ($notification[1] == 1) { ?>

                                                    <td><b><?php echo $date; ?></b></td>
                                                    <td><b><?php echo $heading; ?></b></td>
                                                    <td><a type="button" class="btn btn-warning" href="viewNotificationDetails.php?id=<?php echo $notification_id;?>
                                                    &title=<?php echo $heading;?> &msg=<?php echo $rowNotification['description'];?> &date=<?php echo $date ?> &type=<?php echo $rowNotification['type'];?> "
                                                           onclick="return update(<?php echo $notification_id;?>);">View</a></td>

                                                        <?php
                                                } else{?>
                                                    <td><?php echo $date; ?></td>
                                                    <td><?php echo $heading; ?></td>
                                                    <td><a type="button" class="btn btn-warning" href="viewNotificationDetails.php?id=<?php echo $notification_id;?>
                                                    &title=<?php echo $heading;?> &msg=<?php echo $rowNotification['description'];?> &date=<?php echo $date ?> &type=<?php echo $rowNotification['type'];?> " >View</a></td>
                                                <?php }?>
                                            </tr>
                                            <?php
                                        }
                                    }?>


                            </tbody>
                        </table>


                <!-- Put Anything-->
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>
<script type="text/javascript">
    function update(nid) {
        //alert(nid);
        $.ajax({
            url: "dbUpdate.php",
            type: "POST",
            data: { 'nid': nid },
            /*success: function(data)
             {
             alert(data);
             }*/
        });
        return true;
    }
</script>


