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
if(isset($_GET["id"])){
  $req_number = $_GET["id"];
}else{
  header('location:home.php');
}
$query = "select state,sending_dept from hospital.requests where request_id='$req_number' ";
$res = mysqli_query($conn,$query);
if($res){
  $tempRow=mysqli_fetch_array($res,MYSQLI_ASSOC);
  if($tempRow["state"]!=1 || $tempRow["sending_dept"]!=1){
    header('location:home.php');
  }
}else{
  die("Server Error");
}
$drugs = array();
$query = "select t2.drug_name,t1.serial_number,t1.amount from hospital.request_details as t1 inner join hospital.drugs as t2 on t1.serial_number=t2.serial_number where t1.request_id='$req_number'";
$res = mysqli_query($conn,$query);
if($res){
  while($row=mysqli_fetch_array($res,MYSQLI_ASSOC)){
    $snumber = $row['serial_number'];
    $query = "select batch_number,inventory_balance from hospital.drug_batches where ((serial_number='$snumber' && inventory_balance>0) && (deleted=0 && expiry_status!=3))";
    $res1 = mysqli_query($conn,$query);
    if($res1){
      $newAr = array();
      $temp = array();
      while($row1=mysqli_fetch_array($res1,MYSQLI_ASSOC)){
        $newAr1 = array();
        $newAr1['batch_number'] = $row1['batch_number'];
        $newAr1['inventory_balance'] = $row1['inventory_balance'];
        array_push($newAr,$newAr1);
      }
      $temp['drug_name']=$row['drug_name'];
      $temp['serial_number']=$row['serial_number'];
      $temp['amount']=$row['amount'];
      $temp['batches']= $newAr;
      array_push($drugs,$temp);
    }else{
      die("Server Error");
    }
  }
}else{
  die("Server Error");
}
?>


<html>
<head>
<style type="text/css">
.display-4-2{
  margin-left: 15px;
}
</style>
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
        
          <div class="col-md-10 ">
          <div class ="row">
          <h1 class="display-4 display-4-2">Drug Issue</h1>
            <!--<label > Drug Cart</label>-->
          </div>
          </div>
          <div class="col-md-2 form-group">
          <input type="text" hidden="" id="u_idTag" value="<?php echo $user->getUId(); ?>" />
          <input type="text" hidden="" id="req_idTag" value="<?php echo $req_number; ?>" />
          <button id="sub" onclick="submitForm();"  type="button" class="btn btn-lg btn-info" name="addb" >Issue</button>
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
        <th>Batch Number - Remaining Quantity</th>
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
      <th value="'.$drug["drug_name"]." ~ ".$drug['serial_number'].'">'.$drug['drug_name']."  ~  ".$drug["serial_number"].'</th>
      <th>'.$drug["amount"].'</th>
      <th><select class="form-control" onchange="checkForChange(this);" id="'.$drug['serial_number'].'"><option value="" selected = "" disabled=""></option>';
          $batches = $drug['batches'];
          foreach ($batches as $key) {
            $data = $data.
              '<option>'.$key['batch_number']." ~ ".$key['inventory_balance'].'</option>';
          }
      $data = $data.'</select><select onchange="checkForChange(this);" class="form-control" id="'.$drug['serial_number']."^".'"><option value="" selected = "" disabled=""></option>';
      $batches = $drug['batches'];
          foreach ($batches as $key) {
            $data = $data.
              '<option>'.$key['batch_number']." ~ ".$key['inventory_balance'].'</option>';
          }
      $data = $data.'</select>
      </th>
      <th><input type="text" class="form-control" name="'.$drug["amount"].'" onchange="checkQuantity(this);" disabled="" id="'.$drug['serial_number']."`".'"></input>
      <input type="text" class="form-control" onchange="checkQuantity(this);" disabled="" id="'.$drug['serial_number']."~".'"></input></th>
      </tr>'; 
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
function checkForChange(me){
  if(me.id.indexOf("^")==-1){
    var qu = document.getElementById(me.id+"`");
    qu.value = "";
    var Nex = document.getElementById(me.id+"^");
    if(me.value==Nex.value){
      swal("Cannot use the same batch again","","error");
      me.value = "";
      qu.disabled = true;
    }else{
      qu.disabled = false;
    }
  }else{
    var qu = document.getElementById(me.id.slice(0,-1)+"~");
    qu.value = "";
    var Pre = document.getElementById(me.id.slice(0,-1));
    if(me.value==Pre.value){
      swal("Cannot use the same batch again","","error");
      me.value = "";
      qu.disabled = true;
    }else{
      qu.disabled = false;
    }
  }
}
function checkQuantityValidity(str1,str2){
  if(isNaN(str1) || isNaN(str2)){
    return false;
  }
  if(parseInt(str2)<parseFloat(str2)){
    return false;
  }
  if(parseInt(str1)<parseInt(str2)){
    return false;
  }
  if(parseInt(str2)<0){
    return false;
  }
  return true;

}
function checkQuantityOverAll(total,myval,otherval){
  if(otherval==""){
    otherval=0;
  }else{
    otherval = parseInt(otherval);
  }
  if(total<myval+otherval){
    return false;
  }
  return true;
}
function checkQuantity(me){
  if(me.id.indexOf("~")==-1){
    var batch = document.getElementById(me.id.slice(0,-1));
    var nextInp = document.getElementById(me.id.slice(0,-1)+"~");
    //alert(me.name+" "+me.value+" "+nextInp.value);
    if(!checkQuantityValidity(batch.value.split(" ~ ")[1],me.value)){
      me.value = "";
      swal("Invalid input!", "", "error");
    }else if(!checkQuantityOverAll(parseInt(me.name),parseInt(me.value),nextInp.value)){
      me.value = "";
      swal("Invalid input!", "Quantity higher than requested", "error");
    }
  }else{
    var batch = document.getElementById(me.id.slice(0,-1)+"^");
    var preInp = document.getElementById(me.id.slice(0,-1)+"`");
    //alert(preInp.name+" "+me.value+" "+preInp.value);
    if(!checkQuantityValidity(batch.value.split(" ~ ")[1],me.value)){
      me.value = "";
      swal("Invalid input!", "", "error");
    }else if(!checkQuantityOverAll(parseInt(preInp.name),parseInt(me.value),preInp.value)){
      me.value = "";
      swal("Invalid input!", "Quantity higher than requested", "error");
    }
  }
}

</script>
<script type="text/javascript">
function submitForm(){
  swal({
    title: "Are you sure?",
    text: "",
    type: "warning",
    showCancelButton: true,
    confirmButtonColor: "#DD6B55",
    confirmButtonText: "Yes, submit it!",
    cancelButtonText: "No, cancel!",
    closeOnConfirm: false,
    closeOnCancel: true
  },
  function(isConfirm){
    if(isConfirm){
    var ok = 1;
    var batches = "";
    var request_id = document.getElementById("req_idTag").value;
    var u_id = document.getElementById("u_idTag").value;
    var table = document.getElementById("myTable");
    for(var i=1;i<table.rows.length;i++){
      var snum = table.rows[i].cells[1].innerHTML.split(" ~ ")[1].slice(1);
      var firstSelect = document.getElementById(snum);
      var secondSelect = document.getElementById(snum+"^");
      var firstQuantity = document.getElementById(snum+"`");
      var secondQuantity = document.getElementById(snum+"~");
      if(firstSelect!=null){
        firstSelect =firstSelect.value;
      }else{
        firstSelect = "";
      }
      if(secondSelect!=null){
        secondSelect =secondSelect.value;
      }else{
        secondSelect = "";
      }
      if(firstQuantity!=null){
        firstQuantity =firstQuantity.value;
      }else{
        firstQuantity = "";
      }
      if(secondQuantity!=null){
        secondQuantity =secondQuantity.value;
      }else{
        secondQuantity = "";
      }
      var tempData = "";
      if((firstSelect!="") && (firstQuantity!="")){
        tempData=tempData+"^"+firstSelect.split(" ~ ")[0]+"~"+firstQuantity;
      }
      if((secondSelect!="") && (secondQuantity!="")){
        tempData=tempData+"^"+secondSelect.split(" ~ ")[0]+"~"+secondQuantity;
      }
      if(tempData != ""){
        batches = batches+snum+tempData+"=";
      }
    }
    $.ajax({
      type: "POST",
      url: "ajax.php",
      data:{
         "drugToDispensary" : true,
         "batches" : batches,
         "u_id" : u_id,
         "req_id" : request_id
      },
      async: false,
      success: function(msg){
        if(msg!="ok"){
          ok = 0;
        }
        if(msg=="someOne"){
          ok = 2;
        }
      }
    });
    if (ok==1) {
        swal("Successfully Issued!", "", "success");
        setTimeout('Redirect()',1500);
    }else if(ok==0){
      swal("Server Error!", "", "error");
    }else if(ok==2){
      swal("Someone Already Issued!", "", "error");
      setTimeout('Redirect()',1500);
    }
  }
    
  });
}
function Redirect(){
  window.location.href = "home.php";
}
</script>
</body>
</html>


