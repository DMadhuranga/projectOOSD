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
                                    <th><a href='viewAllNotifications.php?sort=date' style="text-decoration: none; color: black">Date<img alt="sort" src="../images/sort2.png"style="width:25px;height:25px;" ></th>
                                    <th>Notification</th>
                                    <th><a href='viewAllNotifications.php?sort=view' style="text-decoration: none; color: black">View<img alt="sort" src="../images/sort2.png"style="width:25px;height:25px;" ></th>

                                </tr>
                            </thead>
                            <tbody>

                                <?php
                                    if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
                                    $results_per_page = 10;
                                    $start_from = ($page-1) * $results_per_page;
                                    $query_total="SELECT * FROM moic.unseen_notifications";
                                    $res_total=mysqli_query($conn,$query_total);
                                    $count=mysqli_num_rows($res_total);
                                    $total_pages=ceil($count/$results_per_page);

                                    if(isset($_GET['sort']))
                                    {
                                        switch(strtolower(trim($_GET['sort'])))
                                        {
                                            case 'date':
                                                $sort='notification_id';
                                                break;
                                            case 'view':
                                                $sort='status';
                                                break;
                                        }


                                    }
                                    else{
                                        $sort='notification_id';
                                    }

                                    $query="SELECT * FROM moic.unseen_notifications ORDER BY $sort DESC LIMIT $start_from,".$results_per_page;
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
                                                    <td><a type="button" class="btn btn-success" href="viewNotificationDetails.php?id=<?php echo $notification_id;?>
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
                        <ul class="pagination">
                            <?php if ( $page<=1){?>
                                <li class="disabled"><a href="">Previous</a> </li>
                            <?php }else {?>
                                <li><a href="viewAllNotifications.php?page=<?php echo $page-1;?>">Previous</a></li><?php }?>

                            <?php  for ($i=1; $i<=$total_pages; $i++) {
                                if($i==$page){?>
                                    <li class="active"><a href="viewAllNotifications.php?page=<?php echo $i;?>"><?php echo $i?></a></li>
                                <?php }
                                else{?>
                                    <li><a href="viewAllNotifications.php?page=<?php echo $i;?>"><?php echo $i?></a></li>
                                <?php }}?>
                            <?php if ( $page>=$total_pages){?>
                                <li class="disabled"><a href="">Next</a> </li>
                            <?php }else {?>
                                <li><a href="viewAllNotifications.php?page=<?php echo $page+1;?>">Next</a></li><?php }?>
                        </ul>

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


