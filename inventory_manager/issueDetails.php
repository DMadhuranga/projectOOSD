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
if ($user->getRoleId()!=2){
  header('location:../logout.php');
}
if(isset($_GET["issue_id"])){
  $printHtml = "";
  $issue_id = $_GET["issue_id"];
  $query1 = "select date,department_id,u_id from inventory.issues where issue_id='$issue_id'";
  $res1 = mysqli_query($conn,$query1);
  if(!$res1){
    die("Server Error");
  }
  $row1 = mysqli_fetch_array($res1,MYSQLI_ASSOC);
  $date = $row1["date"];
  $department_id = $row1["department_id"];
  $u_id = $row1["u_id"];
  $query3 = "select u_name from hospital.users where u_id='$u_id'";
  $res3 = mysqli_query($conn,$query3);
  if(!$res3){
    die("Server Error");
  }
  $row3 = mysqli_fetch_array($res3,MYSQLI_ASSOC);
  $u_name = $row3["u_name"];
  $query = "select serial_number,batch_number,amount from inventory.issue_details where issue_id='$issue_id'";
  $res = mysqli_query($conn,$query);
  if(!$res){
    die("Server Error");
  }
  while($row = mysqli_fetch_array($res,MYSQLI_ASSOC)){
    $serial_number = $row["serial_number"];
    $query4 = "select drug_name from hospital.drugs where serial_number='$serial_number'";
    $res4 = mysqli_query($conn,$query4);
    if(!$res4){
      die("Server Error");
    }
    $row4 = mysqli_fetch_array($res4,MYSQLI_ASSOC);
    $printHtml = $printHtml."<tr> <td>".$row4["drug_name"]."</td><td>".$row["serial_number"]."</td><td>".$row["batch_number"]."</td><td>".$row["amount"]."</td></tr>";
  }
}else{
  header('location:pastIssues.php');
}
$departments = array();
$departments["0"] = "Dispensary";
$departments["1"] = "Ward";
$departments["2"] = "Department2";
$departments["3"] = "Department3";
$departments["4"] = "Department4";
$departments["5"] = "Department5";

?>


<html>
<head>

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
        
          <div class="col-md-3">
          <div class ="row">
          <h1 class="display-4 display-4-2">Issued drugs</h1>
            <!--<label > Drug Cart</label>-->
          </div>
          </div>
          <div class="col-md-9">
           <p></p>
          </div>
          
   </div>
   <div class="row">
   <div class="col-md-2">
   <label>Date </label>
   </div>
   <div class="col-md-3">
       <label><?php echo ":".$date ?></label>
     </div>
   </div>
   <div class="row">
   <div class="col-md-2">
     <label>Department </label>
     </div>
     <div class="col-md-3">
       <label><?php echo ":".$departments[$department_id] ?></label>
     </div>
   </div>
   <div class="row">
   <div class="col-md-2">
     <label>Issued By </label>
     </div>
     <div class="col-md-3">
       <label><?php echo ":".$u_name ?></label>
     </div>
   </div>
  </div>
  
  
  <table id="myTable" class="table table-striped">  
    <thead>  
      <tr>  
        <!--<th>#</th>-->
        <th>Drug Name</th>
        <th>Serial Number</th>   
        <th>Batch Number</th> 
        <th>Quantity</th>
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
</body>
</html>


