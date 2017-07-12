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
if ($user->getRoleId()!=1){
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
                        <th><a href='viewAllRequests.php?sort=date' style="text-decoration: none; color: black">Date<img alt="sort" src="../images/sort2.png"style="width:25px;height:25px;" ></th>
                        <th><a href='viewAllRequests.php?sort=request' style="text-decoration: none; color: black"">Request<img alt="sort" src="../images/sort2.png"style="width:25px;height:25px;" ></th>
                        <th><a href='viewAllRequests.php?sort=sender' style="text-decoration: none; color: black"">Sender<img alt="sort" src="../images/sort2.png"style="width:25px;height:25px;" ></th>
                        <th><a href='viewAllRequests.php?sort=view' style="text-decoration: none; color: black"">View<img alt="sort" src="../images/sort2.png"style="width:25px;height:25px;" ></th>

                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };
                    $results_per_page = 10;
                    $start_from = ($page-1) * $results_per_page;
                    $query_total="SELECT * FROM hospital.requests WHERE (sending_dept='1' AND receiving_dept='0' AND state='3') OR (sending_dept='2' AND receiving_dept='1' AND state='0')";
                    $res_total=mysqli_query($conn,$query_total);
                    $count=mysqli_num_rows($res_total);
                    $total_pages=ceil($count/$results_per_page);

                    if(isset($_GET['sort']))
                    {
                        switch(strtolower(trim($_GET['sort'])))
                        {
                            case 'date':
                                $sort='date';
                                break;
                            case 'request':
                                $sort='sending_dept';
                                break;
                            case 'sender':
                                $sort='u_id';
                                break;
                            case 'view':
                                $sort="FIELD(state,3,0,4,1)";
                                break;
                        }


                    }
                    else{
                        $sort="FIELD(state,3,0,4,1)";
                    }

                    $query="SELECT * FROM hospital.requests WHERE (sending_dept='1' AND receiving_dept='0' AND (state='3' OR state='4')) OR (sending_dept='2' AND receiving_dept='1' AND (state='0' OR state='1')) ORDER BY $sort ASC LIMIT $start_from,".$results_per_page;
                    $res=mysqli_query($conn,$query);
                    if ($res) {
                        $requests = array();
                        while ($row = mysqli_fetch_array($res, MYSQLI_ASSOC)) {
                            array_push($requests, array($row['request_id'], $row['state']));
                        }

                        foreach ($requests as $request) {
                            $request_id=$request[0];
                            $queryRequest="SELECT * FROM hospital.requests WHERE request_id='$request_id'";
                            $result=mysqli_query($conn,$queryRequest);
                            $rowRequest=mysqli_fetch_array($result,MYSQLI_ASSOC);
                            $date=$rowRequest['date'];
                            $sender_id=$rowRequest['u_id'];
                            $state=$rowRequest['state'];
                            $description=$rowRequest['description'];
                            $queryUser="SELECT u_name FROM hospital.users WHERE u_id=$sender_id";
                            $resultUser=mysqli_query($conn,$queryUser);
                            $row=mysqli_fetch_array($resultUser,MYSQLI_ASSOC);
                            $sender=$row['u_name'];

                            ?>
                            <tr>
                                <td><?php echo $date; ?></td>
                                <td><?php echo $description; ?></td>
                                <td><?php echo $sender; ?></td>
                                <?php if ($request[1] ==0 OR $request[1]==3) { ?>
                                    <td><a type="button" class="btn btn-primary" href="#?id=<?php echo $request_id;?>"> Take Action </a></td>
                                    <?php
                                } else{?>
                                    <td><a type="button" class="btn btn-success" href="viewRequestDetails.php?id=<?php echo $request_id;?> & name=<?php echo $sender?>">View Request</a></td>
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
                        <li><a href="viewAllRequests.php?page=<?php echo $page-1;?>">Previous</a></li><?php }?>

                    <?php  for ($i=1; $i<=$total_pages; $i++) {
                        if($i==$page){?>
                            <li class="active"><a href="viewAllRequests.php?page=<?php echo $i;?>"><?php echo $i?></a></li>
                        <?php }
                        else{?>
                            <li><a href="viewAllRequests.php?page=<?php echo $i;?>"><?php echo $i?></a></li>
                        <?php }}?>
                    <?php if ( $page>=$total_pages){?>
                        <li class="disabled"><a href="">Next</a> </li>
                    <?php }else {?>
                        <li><a href="viewAllRequests.php?page=<?php echo $page+1;?>">Next</a></li><?php }?>
                </ul>

                <!-- Put Anything-->
            </div>
        </div>
    </div>

</div>
</body>
</html>

