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
          <h1 class="display-4 display-4-2">Drug Request</h1>
            <!--<label > Drug Cart</label>-->
          </div>
          </div>
          <div class="col-md-2">
          <div class="row">
          <button id="sub" onclick="submitForm();"  type="button" class="btn btn-lg btn-info" name="addb" disabled="">Send Request</button>
          <input id="uid" value="<?php echo $user->getUId(); ?>" name="" type="hidden">
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
      <option value="<?php echo $drug["drug_name"]." ~ ".$drug['serial_number']; ?>"> <?php echo $drug['drug_name']."  -  ".$drug['serial_number']; ?></option>
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
                <!--<label>Batch Number</label>
                <input id="batchN" name= "u_name" type="text"  class="form-control">-->
              </div>
              <div class="col-sm-4 form-group">
                <div class="form-group">
                </div>
              </div>
              <div class="col-sm-2 form-group">
                <div class="form-group">
                  <label for="sel1"></label>
                  <button id="addb"  type="button" class="btn btn-lg btn-info" name="addb" onclick="addThisDrug();" >Add To Request</button>
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
        <th>Quantity</th> 
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
  if(states==null){
    swal("Select a drug!", "", "error");
  }else if(quantity==""){
    swal("Enter a quantity!", "", "error");
  }else if(isNaN(quantity)){
    swal("Quantity Error!", "", "error");
  }else if(parseInt(quantity)<=0){
    swal("Invalid Quantity!", "", "error");
  }else if(parseFloat(quantity)>parseInt(quantity)){
    swal("Invalid Quantity!", "", "error");
  }else if(alreadyExists(states)){
      swal("Drug already added!", "", "error");
  }else{
      document.getElementById("sub").disabled = false;
      myAddARow(states,quantity);
      document.getElementById('quantity').value = "";
  }
  
}
</script>
<script type="text/javascript">
  function myAddARow(name,qu) {
    var table = document.getElementById("myTable");
    var row = table.insertRow(table.rows.length);
    var cell0 = row.insertCell(0);
    var cell1 = row.insertCell(1);
    var cell2 = row.insertCell(2);
    var cell3 = row.insertCell(3);
    cell0.innerHTML = table.rows.length-1;
    cell1.innerHTML = name;
    cell2.innerHTML = qu;
    cell3.innerHTML = "<button class='btn btn-warning' onclick='removeThis(this);'>Remove</button>";
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
function alreadyExists(snum){
  var table = document.getElementById("myTable");
  for(var i=1;i<table.rows.length;i++){
    if(table.rows[i].cells[1].innerHTML==snum){
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
    var drugs = "";
    var uid = $("#uid").val();
    var table = document.getElementById("myTable");
    for(var i=1;i<table.rows.length;i++){
      var snum = table.rows[i].cells[1].innerHTML.split(" ~ ")[1];
      drugs = drugs+"="+snum+"^"+table.rows[i].cells[2].innerHTML;
    }
    $.ajax({
      type: "POST",
      url: "ajax.php",
      data:{
         "invDrugRequest" : true,
         "drugs" : drugs,
         "uid" : uid
      },
      async: false,
      success: function(msg){
        //alert(msg);
        if(msg!="ok"){
          ok = false;
        }
      }
    });
    if (ok) {
        swal("Successfully Sent!", "", "success");
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
</script>
</body>
</html>


