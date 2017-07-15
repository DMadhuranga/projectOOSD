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
if(isset($_GET["id"])){
  $req_number = $_GET["id"];
}else{
  header('location:home.php');
}
//$req_number = 15;
$query = "select state,sending_dept,issue_id from hospital.requests where request_id='$req_number' ";
$res = mysqli_query($conn,$query);
if($res){
  $tempRow=mysqli_fetch_array($res,MYSQLI_ASSOC);
  if($tempRow["state"]!=3 || $tempRow["sending_dept"]!=1){
    header('location:home.php');
  }
  $issue_id = $tempRow['issue_id'];
}else{
  die("Server Error");
}
$drugs = array();
$query = "select t2.drug_name,t1.serial_number,t1.amount from hospital.request_details as t1 inner join hospital.drugs as t2 on t1.serial_number=t2.serial_number where t1.request_id='$req_number'";
$res = mysqli_query($conn,$query);
if($res){
  $query = "select serial_number,batch_number,amount from inventory.issue_details where issue_id='$issue_id'";
  $res2 = mysqli_query($conn,$query);
    if($res2){
      $issueArray = array();
      while($row2=mysqli_fetch_array($res2,MYSQLI_ASSOC)){
        $tempArray1 = array();
        $tempArray1["serial_number"] = $row2["serial_number"];
        $tempArray1["batch_number"] = $row2["batch_number"];
        $tempArray1["amount"] = $row2["amount"];
        array_push($issueArray,$tempArray1);
      }
      $issueArrayNew = array();
      foreach ($issueArray as $issue) {
        if(!isset($issueArrayNew[$issue["serial_number"]])){
          $issueArrayNew[$issue["serial_number"]] = 0;
        }
        $issueArrayNew[$issue["serial_number"]] = $issueArrayNew[$issue["serial_number"]]+$issue["amount"];
      }
      while($row=mysqli_fetch_array($res,MYSQLI_ASSOC)){
        $temp = array();
        $temp['drug_name']=$row['drug_name'];
        $temp['serial_number']=$row['serial_number'];
        $temp['amount']=$row['amount'];
        if(!isset($issueArrayNew[$row["serial_number"]])){
          $temp['issued_amount']=  0;
        }else{
          $temp['issued_amount']= $issueArrayNew[$row['serial_number']];
        }
        array_push($drugs,$temp);
      }
    }else{
      die("Server Error");
    }
}else{
  die("Server Error");
}
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
        
          <div class="col-md-9 ">
          <div class ="row">
          <h1 class="display-4 display-4-2">Issued Drugs</h1>
            <!--<label > Drug Cart</label>-->
          </div>
          </div>
          <div class="col-md-1 form-group">
          <input type="text" hidden="" id="u_idTag" value="<?php echo $user->getUId(); ?>" />
          <input type="text" hidden="" id="req_idTag" value="<?php echo $req_number; ?>" />
          <input type="text" hidden="" id="issue_idTag" value="<?php echo $issue_id; ?>" />
          <button id="sub" onclick="acceptIssue();"  type="button" class="btn btn-lg btn-info" name="addb" >Accept</button>
          </div>
          <div class="col-md-2 form-group">
          <button id="sub" onclick="rejectIssue();"  type="button" class="btn btn-lg btn-info" name="addb" >Reject</button>
          </div>
          
   </div>
  </div>
  
  <div class="col-lg-12 well">
  <table id="myTable" class="table table-striped">  
    <thead>  
      <tr>
        <th>#</th>   
        <th>Drug Name - Serail Number</th>     
        <th>Requested Quantity</th>
        <th>Issued Quantity</th> 
      </tr>
    </thead>  
    <tbody>
    <?php
    $num = 1;
    $data = '';
    foreach($drugs as $drug){
      $data=$data.'
      <tr>
      <th>'.$num.'</th>
      <th>'.$drug['drug_name']."  ~  ".$drug["serial_number"].'</th>
      <th>'.$drug["amount"].'</th>
      <th>'.$drug["issued_amount"].'</th>';
      $num++;
    }
    echo $data;
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
function acceptIssue() {
  swal({
  title: "Are you sure?",
  text: "",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "Yes, accept it!",
  cancelButtonText: "No, cancel!",
  closeOnConfirm: false,
  closeOnCancel: true
},
function(isConfirm){
  if (isConfirm) {
    var ok = respondToDelivery(1);
    if(ok){
      swal("Saved!", "", "success");
      setTimeout('Redirect()',1500);
    }else{
      swal("Server Error!", "", "error");
    }
  }
});
}
function rejectIssue(){
  swal({
  title: "Are you sure?",
  text: "",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "Yes, reject it!",
  cancelButtonText: "No, cancel!",
  closeOnConfirm: false,
  closeOnCancel: true
},
function(isConfirm){
  if (isConfirm) {
    var ok = respondToDelivery(0);
    if(ok){
      swal("Saved!", "", "success");
      setTimeout('Redirect()',1500);
    }else{
      swal("Server Error!", "", "error");
    }
  }
});
}
function respondToDelivery(respond){
  var ok = true;
  var issue_id = document.getElementById('issue_idTag').value;
  var u_id = document.getElementById('u_idTag').value;
  var req_id = document.getElementById('req_idTag').value;
  $.ajax({
    type : "POST",
    url : "ajax.php",
    data : {
      "respondToDelivery" : true,
      "u_id" : u_id,
      "issue_id" : issue_id,
      "req_id" : req_id,
      "accept" : respond
    },
    async : false,
    success : function(data){
      //alert(data);
      if(data!="ok"){
        ok = false;
      }
    }
  });
  return ok;
}
function Redirect(){
  window.location.href = "home.php";
}
</script>
</body>
</html>


