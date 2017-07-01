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
$drugs = array();
$query = "select drug_name,serial_number,type from hospital.drugs where deleted=0";
$res = mysqli_query($conn,$query);
if($res){
  while($row=mysqli_fetch_array($res,MYSQLI_ASSOC)){
    $temp = array();
    $temp['drug_name']=$row['drug_name'];
    $temp['serial_number']=$row['serial_number'];
    $temp['type']=$row['type'];
    array_push($drugs,$temp);
  }
}else{
  die("Server Error");
}
?>


<html>
<head>
<link href="../assests/library/select2-4.0.3/dist/css/select2.min.css" rel="stylesheet"/>
<style type="text/css">
.display-4-2{
  margin-left: 15px;
}
</style>  
<link rel="stylesheet" href="../assests/library/jquery-ui-1.12.1.custom/jquery-ui.structure.css" />
<link rel="stylesheet" href="../assests/library/jquery-ui-1.12.1.custom/jquery-ui.theme.css" />
<script src="../assests/library/jquery-ui-1.12.1.custom/jquery-ui.css"></script>
<script src="../assests/library/jquery-ui-1.12.1.custom/jquery-ui.js"></script>
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
        <div class="container">
    <!--<h1 class="well">Add Drugs</h1>-->
    <div class="col-lg-12 well">
  <div class="row">
        
          <div class="col-md-10">
          <div class ="row">
          <h1 class="display-4 display-4-2">Drug Arrival</h1>
            <!--<label > Drug Cart</label>-->
          </div>
          </div>
          <div class="col-md-2">
          <div class="row">
          <button id="sub" onclick="submitForm();"  type="button" class="btn btn-lg btn-info" name="addb" disabled="">Add Arrival</button>
          </div>
          </div>
          
   </div>
  </div>
  <div class="col-lg-12 well">
  <div class="row">
        <form method ='POST'>
          <div class="col-sm-12">
            <div class="row">
              <div class="col-sm-6 form-group">
                <label>Drug Name - Serial Number</label>
                <select class="form-control" id="serialN" >
                <object>Select</object>
                <option value="" selected = "" disabled=""></option>
    <?php
    foreach($drugs as $drug){?>
      <option value="<?php echo $drug["drug_name"]." - ".$drug['serial_number']; ?>"> <?php echo $drug['drug_name']."  -  ".$drug['serial_number']; ?></option>
    <?php }
    ?>
    </select>
              </div>
              
              <div class="col-sm-6 form-group">
                <label>Quantity</label>
                <input id="quantity" name="last_name"  type="text" placeholder="Enter Quantity" class="form-control">
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6 form-group">
                <label>Arrival Date</label>
                <input id="arrivalD" name= "u_name" type="text"  class="form-control">
              </div>
              <div class="col-sm-6 form-group">
                <div class="form-group">
                  <label for="sel1">Expire Date</label>
                  <input id="expireD" name= "u_name" type="text"  class="form-control">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6 form-group">
                <label>Batch Number</label>
                <input id="batchN" name= "u_name" type="text"  class="form-control">
              </div>
              <div class="col-sm-4 form-group">
                <div class="form-group">
                </div>
              </div>
              <div class="col-sm-2 form-group">
                <div class="form-group">
                  <label for="sel1"></label>
                  <button id="addb"  type="button" class="btn btn-lg btn-info" name="addb" onclick="addThisDrug();" >Add To Arrival</button>
                </div>
              </div>
            </div>      
          </div>
        </form> 
        </div>
  </div>
  <div class="col-lg-12 well">
  <table id="myTable" class="table table-striped">  
    <thead>  
      <tr>
        <th>#</th>   
        <th>Drug Name - Serail Number</th>    
        <th>Batch Number</th>  
        <th>Quantity</th> 
        <th>Arrival Date</th>
        <th>Expire Date</th>
        <th>Remove</th> 
      </tr>  
    </thead>  
    <tbody>
      
    </tbody>  
  </table> 
  </div>
  </div>
    
    
    <!-- Put Anything-->
    </div>
    </div>
  </div>
</div>
<script src="../assests/library/select2-4.0.3/dist/js/select2.full.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  $(".js-example-basic-single").select2();
});
</script>
<script>

        $(document).ready(function() { 
            $("#serialN").select2({
                    placeholder: "Select a drug",
                    allowClear: true
             });
        $( function() {
        $( "#expireD" ).datepicker();
        } );
        $( function() {
        $( "#arrivalD" ).datepicker();
        } );

       });
</script>
<script type="text/javascript">
function addThisDrug(){
  var quantity = $("#quantity").val();
  var states = $("#serialN").val();
  var batch = $("#batchN").val();
  var arrival = $("#arrivalD").val();
  var expire = $("#expireD").val();

  if(states==null){
    swal("Select a drug!", "", "error");
  }else if(quantity==""){
    swal("Enter a quantity!", "", "error");
  }else if(batch==""){
    swal("Enter batch number!", "", "error");
  }
  else if(arrival==""){
    swal("Enter arrival date!", "", "error");
  }else if(expire==""){
    swal("Enter expire date!", "", "error");
  }else if (!dateFormat(arrival)){
    swal("Arrival date format error!", "", "error");
  }else if(!dateFormat(expire)){
    swal("Expire date format error!", "", "error");
  }else if(!(parseInt(quantity)>0)){
    swal("Quantity Error!", "", "error");
  }else if(alreadyExists(batch)){
      swal("Batch number already added!", "", "error");
  }else{
    var dub = false;
    var ok = true;
    $.ajax({
      type: "POST",
      url: "ajax.php",
      data:{
         "nbatch_number" : batch
      },
      async: false,
      success: function(msg){
        //alert(msg);
        if(msg=="1"){
          dub = true;
        }else if(msg!="ok"){
          ok = false;
        }
      }
    });
    if(!ok){
      swal("Server Error!", "", "error");
    }else if(dub){
      swal("Batch number already exists!", "", "error");
    }else{
      document.getElementById("sub").disabled = false;
      myAddARow(states,batch,quantity,arrival,expire);
    }
  }
  
}
</script>
<script type="text/javascript">
  function myAddARow(name,bnum,qu,arrD,expD) {
    var table = document.getElementById("myTable");
    var row = table.insertRow(table.rows.length);
    var cell0 = row.insertCell(0);
    var cell1 = row.insertCell(1);
    var cell2 = row.insertCell(2);
    var cell3 = row.insertCell(3);
    var cell4 = row.insertCell(4);
    var cell5 = row.insertCell(5);
    var cell6 = row.insertCell(6);
    cell0.innerHTML = table.rows.length-1;
    cell1.innerHTML = name;
    cell2.innerHTML = bnum;
    cell3.innerHTML = qu;
    cell4.innerHTML = arrD;
    cell5.innerHTML = expD;
    cell6.innerHTML = "<button class='btn btn-warning' onclick='removeThis(this);'>Remove</button>";
  }
</script>
<script type="text/javascript">
function removeThis(e){
  var currentRow = e.parentElement.parentElement.rowIndex;
  document.getElementById("myTable").deleteRow(currentRow);
  if(document.getElementById("myTable").rows.length==1){
    document.getElementById("sub").disabled = true;
  }
  var as = document.getElementById('myTable');
  for(var i=1;i<as.rows.length;i++) {
    as.rows[i].cells[0].innerHTML = i;
  }
}
function alreadyExists(bnum){
  var table = document.getElementById("myTable");
  for(var i=1;i<table.rows.length;i++){
    if(table.rows[i].cells[2].innerHTML==bnum){
      return true;
    }
  }
  return false;
}
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
    var ok = true;
    var batches = "";
    var table = document.getElementById("myTable");
    for(var i=1;i<table.rows.length;i++){
      var snum = table.rows[i].cells[1].innerHTML.split(" - ")[1];
      batches = batches+snum+"^"+table.rows[i].cells[2].innerHTML+"^"+table.rows[i].cells[3].innerHTML+"^"+table.rows[i].cells[4].innerHTML+"^"+table.rows[i].cells[5].innerHTML+"=";
    }
    $.ajax({
      type: "POST",
      url: "ajax.php",
      data:{
         "addArrival" : true,
         "batches" : batches
      },
      async: false,
      success: function(msg){
        if(msg!="ok"){
          ok = false;
        }
      }
    });
    if (ok) {
        swal("Successfully Added!", "", "success");
        setTimeout('Redirect()',1500);
    }else if(!ok){
      swal("Server Error!", "", "error");
    }
  }
    
  });
}
function Redirect(){
  location.reload();
}
function dateFormat(date){
  if((date.length!=10)){
    return false;
  }else{
    ar = date.split("/");
    if(ar.length!=3){
      return false;
    }else{
      for(i=0;i<ar.length;i++){
        if((parseInt(ar[i])=="NaN")){
          return false;
        }
      }
  var dd = parseInt(ar[1]);  
  var mm  = parseInt(ar[0]);  
  var yy = parseInt(ar[2]);  
  // Create list of days of a month [assume there is no leap year by default]  
  var ListofDays = [31,28,31,30,31,30,31,31,30,31,30,31]; 
  if(mm>12){
    return false;
  }
  if(mm<1){
    return false;
  }
  if(dd<1){
    return false;
  }
  if(yy<2010){
    return false;
  }
  if(yy>2050){
    return false;
  }
  
  if (mm==1 || mm>2)  
  {  
  if (dd>ListofDays[mm-1])  
  {
  return false;  
  }  
  }
  if (mm==2)  
  {  
  var lyear = false;  
  if ( (!(yy % 4) && yy % 100) || !(yy % 400))   
  {  
  lyear = true;  
  }  
  if ((lyear==false) && (dd>=29))  
  {  
  return false;  
  }  
  if ((lyear==true) && (dd>29))  
  {   
  return false;  
  }  
  }
      
    }
  }
  return true;
}


</script>
</body>
</html>


