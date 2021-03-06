<?php
if(isset($_SESSION['user'])){
  $user = unserialize($_SESSION['user']);
}
?>
<html>
<head>
<title>home page</title>
<link rel="stylesheet" href="../assests/library/bootstrap-3.3.7-dist/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="../assests/library/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="../assests/library/sweetalert-master/dist/sweetalert.css">
<style type='text/css'>
.col-md-2-height1 {
  height: 1200px;
    text-align: left;
  background-color: #f8f8f8;
  <!--background-color: #f8f8f8;-->
}
.col-xs-12-height1{
  background-color: #f8f8f8;
}
.navbar-default-nopaddingup{
  padding-bottom: 0px;
  margin-bottom: 0px;
}
</style>

</head>
<body>
<header>
<nav class="navbar navbar-default navbar-default-nopaddingup">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand1" href="#">
    <img alt="Bitz" src="../images/logo.png" style="width:50px;height:50px;">
        
      </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <!--<li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>-->
        <li><a href="home.php">Home</a></li>
      </ul>
      <form class="navbar-form navbar-left">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
          <li class=""dropdown">
          <?php
          $query="SELECT * FROM hospital.requests WHERE (sending_dept='1' AND state='1' AND receiving_dept='0')  OR (sending_dept='2' AND state='1' AND receiving_dept='1') ORDER BY request_id";//" DESC LIMIT 5";
          $result=mysqli_query($conn,$query);
          $count=mysqli_num_rows($result);
          ?>
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-pill label-danger count"><?php if ($count!=0){ echo $count;}?></span><img alt="Request icon" src="../images/request.png"style="width:25px;height:25px;"> Requests</a>
          <ul class="dropdown-menu">
              <?php requests(); ?>
              <li><a href="viewAllRequests.php">View all..</a></li>
          </ul>
          </li>
          <li class=""dropdown">
          <?php
          $query="SELECT * FROM inventory.unseen_notifications WHERE status='1' ORDER BY id";//" DESC LIMIT 5";
          $result=mysqli_query($conn,$query);
          $count=mysqli_num_rows($result);
          ?>
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-pill label-danger count"><?php if ($count!=0){ echo $count;}?></span><img alt="Bell" src="../images/bell2.png"style="width:20px;height:20px;">Notifications</a>
          <ul class="dropdown-menu">
              <?php notifications(); ?>
              <li><a href="viewAllNotifications.php">View all..</a></li>
          </ul>
          </li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $user->getUName(); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="editprofile.php">Edit Profile</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="../logout.php">Log Out</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
</header>
<script src="../assests/jquery.min.js"></script>
<script src="../assests/library/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
<script src="../assests/library/sweetalert-master/dist/sweetalert.min.js"></script>
<script type="text/javascript">
function htmlbodyHeightUpdate(){
    var height3 = $( window ).height();
    var height2 = $('#mb').height();
    var height1 = $('#sideB').height();
    var width1 = $( window ).width();
    if(width1>1000){
      if(height2>height3){
      $('#sideB').height(height2+50);
      }else{
      $('#sideB').height(height3+50);
      } 
    }else{
      var needH = $('#nop').val();
      needH = parseInt(needH);
      $('#sideB').height(40*needH + 30);
    }
  }
  $(document).ready(function () {
  htmlbodyHeightUpdate();
    $( window ).resize(function() {

      htmlbodyHeightUpdate();
    });
  });
</script>
</body>
</html>
<?php
function notifications(){
    $conn = mysqli_connect('localhost', 'root', '1010');
    $query="SELECT * FROM inventory.unseen_notifications WHERE status='1' ORDER BY id DESC LIMIT 5";
    $result=mysqli_query($conn,$query);

    if(mysqli_num_rows($result)>0){
        while($row=mysqli_fetch_array($result)){
            $id=$row['notification_id'];
            $queryNotification="SELECT * FROM hospital.notifications WHERE notification_id='$id'";
            $resNotification=mysqli_query($conn,$queryNotification);
            if(mysqli_num_rows($resNotification)!=0){
                $rowNotification=mysqli_fetch_array($resNotification);
                $title=$rowNotification['message'];
                $description = substr($rowNotification['description'], 0, 15).'....';
                $message=$rowNotification['description'];
                $type=$rowNotification['type'];

                echo'<li><a href="viewNotificationDetails.php?id='. $row['id'] .'&title='.$title.'&msg='.$message.'&date='.$rowNotification['date'].'&type='.$type.' " onclick="return update('.$id.');">
                    <srong><b>'.$rowNotification['message'].'</b></srong><br />
                    <small><em>'.$description.'</em></small>
                </a></li>';
            }
        }
    }
    else{
        echo'<li><a href ="#" class="text-bold text-italic">No New Notifications</a></li>';
    }
}

function requests(){
    /**
     * moic receiving 0 state 0
     * IM sending 1 receiving 0 state 1
     * Dis sending 2 receiving 1 state 0
     */
    $conn = mysqli_connect('localhost', 'root', '1010');
    $query="SELECT * FROM hospital.requests WHERE (sending_dept='1' AND state='1' AND receiving_dept='0') OR (sending_dept='2' AND state='1' AND receiving_dept='1') ORDER BY request_id DESC LIMIT 5";
    $result=mysqli_query($conn,$query);

    if(mysqli_num_rows($result)>0){
        while($row=mysqli_fetch_array($result)){
            $u_id=$row['u_id'];
            $queryName="SELECT u_name FROM hospital.users WHERE u_id='$u_id'";
            $resName=mysqli_query($conn,$queryName);
            $rowName=mysqli_fetch_array($resName,MYSQLI_ASSOC);
            $sender=$rowName['u_name'];
            $message=$row['description'];
            if ($message=="Dispensary Drug Request") {
                echo '<li><a href="issueDrugToDispensary.php?id=' . $row['request_id'] . ' ">
                    <small><em>' . $row['date'] . '</em></small><br />
                    <srong><b>' . $message . '</b></srong><br />
                    
                </a></li>';
            }
            else{
                echo '<li><a href="acceptReturnedDrugs.php?id=' . $row['request_id'] . ' &name='.$sender.'">
                    <small><em>' . $row['date'] . '</em></small><br />
                    <srong><b>' . $message . '</b></srong><br />
                    
                </a></li>';
            }
        }
    }

    else{
        echo'<li><a href ="#" class="text-bold text-italic">No New Requests</a></li>';
    }
}
?>


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
