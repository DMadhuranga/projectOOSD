<?php
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

$days = 'true';
$months = 'false';
$month = 'false';
$year = 'false';
$date=date_create(date("Y-m-d"));
if(isset($_GET["range"])){
  $range = $_GET["range"];
  if($range=="30"){
    $month = 'true';
    date_sub($date,date_interval_create_from_date_string("30 days"));
  }elseif($range=="90"){
    $months = 'true';
    date_sub($date,date_interval_create_from_date_string("90 days"));
  }elseif($range=="year"){
    $year = 'true';
    date_sub($date,date_interval_create_from_date_string("365 days"));
  }else{
    $range = "7";
    date_sub($date,date_interval_create_from_date_string("7 days"));
  }
}else{
  date_sub($date,date_interval_create_from_date_string("7 days"));
}

//$date = getdate();

$newDate = date_format($date,"Y-m-d");

$query2 = "select u_id,u_name from hospital.users where role_id=1";
$res2 = mysqli_query($conn,$query2);
if(!$res2){
  die("Server error");
}
$users = array();
while($row1 = mysqli_fetch_array($res2,MYSQLI_ASSOC)){
  $users[$row1["u_id"]]=$row1["u_name"];
}
$query = "select issue_id,patient_id,date,u_id from dispensary.issues where (date>='$newDate' && reciever=4)";
$res = mysqli_query($conn,$query);
if(!$res){
  die("Server error");
}
//echo $newDate;
$printHtml = "";
while($row = mysqli_fetch_array($res,MYSQLI_ASSOC)){
  $issue_id = $row["issue_id"];
  $query3 = "select serial_number from dispensary.issue_details where issue_id='$issue_id'";
  $res3 = mysqli_query($conn,$query3);
  if(!$res3){
    die("Server Error");
  }
  if(mysqli_num_rows($res3)>0){
  $printHtml = $printHtml."<tr> <td>".$row["date"]."</td><td>".$row["patient_id"]."</td><td>".$users[$row["u_id"]]."</td><td><button class='btn btn-info' value=".$row["issue_id"]." onclick='viewDetails(this);'>Details</button></td></tr>";
  }
}

?>


<html>
<head>
<script type="text/javascript" src="assests/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="../assests/library/myTable/jquery.dataTables.min.css">
<script type="text/javascript" src="../assests/library/myTable/jquery.dataTables.min.js"></script>
</head>
<body>
<div class='container-fluid'>
  <div class='row'>
    <div class='col-md-2 col-md-2-height1' id="sideB">
    <div class = "row">
    <ul class="nav nav-pills nav-stacked">
    <input type="hidden" value="<?php echo sizeof($pages);?>" id="nop">
      <?php
      foreach( $pages as $tempPag ) {?>
        <li><a href="<?php echo $tempPag[1]; ?>"><?php echo $tempPag[0]; ?></a></li>
      <?php      
      }; 
?>
    </ul>
    </div>
    </div>
    <div class='col-md-10' id="mb">
    <div class="row">
    <!-- Put Anything-->
    <div class="container">
    <!--<h1 class="well">Add Drugs</h1>-->
    <div class="col-lg-12 well">
  <div class="row">
        
          <div class="col-md-10">
          <div class ="row">
          <h1 class="display-4 display-4-2">Drug Issues</h1>
            <!--<label > Drug Cart</label>-->
          </div>
          </div>
          <div class="col-md-2">
          
          </div>
          
   </div>
  </div>
  
  <div class="col-lg-12 well">
  <div class="row">
  <div class="col-sm-4 form-group">
                <label>Issued In</label>
                <select class="form-control" id="selectBN" value="" placeholder="Select" onchange="loadThisRange(this);">
                <!--<option value="" selected = "" disabled="">Select</option>-->
                <?php
                if($range=="7"){
                  ?><option value="7" selected="">Last 7 days</option> <?php
                }else{
                  ?><option value="7" >Last 7 days</option> <?php
                }
                if($range=="30"){
                  ?><option value="30" selected="">Last month</option> <?php
                }else{
                  ?><option value="30" >Last month</option> <?php
                }
                if($range=="90"){
                  ?><option value="90" selected="">Last 3 months</option> <?php
                }else{
                  ?><option value="90" >Last 3 months</option> <?php
                }
                if($range=="year"){
                  ?><option value="year" selected="">Last Year</option> <?php
                }else{
                  ?><option value="year" >Last Year</option> <?php
                }
                
                ?>
    </select>
    </div></div>
  <table id="myTable" class="table table-striped">  
    <thead>  
      <tr>  
        <!--<th>#</th>-->
        <th>Date<img alt="sort" src="../images/sort2.png" style="width:25px;height:25px;" ></th>
        <th>Patient Id<img alt="sort" src="../images/sort2.png" style="width:25px;height:25px;" ></th>   
        <th>Issued By<img alt="sort" src="../images/sort2.png" style="width:25px;height:25px;" ></th> 
        <th>Details</th>
      </tr>  
    </thead>  
    <tbody>
    <?php
    echo $printHtml;

    ?>
    </tbody>  
  </table> 
  </div>
  </div>
    
    
    <!-- Put Anything-->
    </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  function loadThisRange(me) {
    //alert(me.value);
    window.location.href = "pastIssues.php?range="+me.value;
  }
  function viewDetails(me){
    window.location.href = "issueDetails.php?issue_id="+me.value;
  }
</script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#myTable').DataTable();
});
</script>
</body>
</html>


