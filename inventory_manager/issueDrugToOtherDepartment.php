<?php
session_start();
include('../dbconnection.php'); //$conn
include('../User.php');
include('basicTemp.php');

if(!isset($_SESSION['logged']) || !isset($_SESSION['user'])){
  header('location:login.php');
}
$pages = $_SESSION['pages'];
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
<link rel="stylesheet" href="../assests/library/sweetAlert2/sweetalert2.min.css">
<script src="../assests/library/sweetAlert2/sweetalert2.min.js"></script>
<!-- Include a polyfill for ES6 Promises (optional) for IE11 and Android browser -->
<script src="../assests/library/sweetAlert2/core.js"></script>
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
    <form>

    </form>
    <table id = "tb1">

    </table>
    <form>
    
    </form>
    <div class="container">
    <!--<h1 class="well">Add Drugs</h1>-->
    <div class="col-lg-12 well">
  <div class="row">
        
          <div class="col-md-10">
          <div class ="row">
          <h1 class="display-4 display-4-2">Drug Issue</h1>
            <!--<label > Drug Cart</label>-->
          </div>
          </div>
          <div class="col-md-2">
          <div class="row">
          <button id="sub" onclick="submitForm();"  type="button" class="btn btn-lg btn-info" name="addb" disabled="">Issue Drugs</button>
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
              <div class="col-sm-5 form-group">
                <label>Drug Name</label>
                <select class="form-control" id="states" >
                <object>Select</object>
                <option value="" selected = "" disabled=""></option>
    <?php
    foreach($drugs as $drug){?>
      <option value="<?php echo $drug['drug_name']." ~ ".$drug["serial_number"]; ?>"> <?php echo $drug['drug_name']."  -  ".$drug['serial_number']; ?></option>
    <?php }
    ?>
    </select>
              </div>
              <div class="col-sm-4 form-group">
                <label>Batch Number</label>
                <select class="form-control" id="selectBN" value='' placeholder="Select a batch">
                <option value="" selected = "" disabled="">Select</option>
    
    </select>
              </div>
              <div class="col-sm-3 form-group">
                <label>Quantity</label>
                <input id="quantity" name="last_name"  type="text" placeholder="Enter Quantity" class="form-control">
              </div>
            </div>
          <div class="row">
          <div class="col-md-10">
          </div>
          <div class="col-md-2">
          <div class="row">
          <button  type="button" class="btn btn-lg btn-info" name="addb" onclick="addThisDrug();" >Add To Issue</button>
          </div>
          </div>
          </div>
          <!--<button  type="Submit" class="btn btn-lg btn-info" name="submitb" >Submit</button>  -->       
          </div>
        </form> 
        </div>
  </div>
  <div class="col-lg-12 well">
  <table id="myTable" class="table table-striped">  
    <thead>  
      <tr>  
        <th>#</th>
        <th>Drug Name - Serial Number</th>  
        <th>Batch Number</th>  
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
<div id="modal_content">
  <select id="chosen" hidden="">
    <option value=""></option>
    <option value="1">example 1</option>
    <option value="2">example 2</option>
    <option value="3">example 3</option>
    <option value="4">example 4</option>
    <option value="5">example 5</option>
    <option value="6">example 6</option>
    <option value="7">example 7</option>
  </select>
</div>
<script src="../assests/library/select2-4.0.3/dist/js/select2.full.min.js"></script>
<script>
        $(document).ready(function() { 
            $("#states").select2({
                    placeholder: "Select a drug",
                    allowClear: true
             });
            $('select').on('select2:select', function (evt) {
          var value1 = $('select').val();
          var ok = true;
          value1 = value1.split(" ~ ")[1];
          var newOptions = {};
          $.ajax({
                type: "POST",
                url: "ajax.php",
                data:{
                   "nserial_number" : value1
                },
                async: false,
                success: function(msg){
                    
                    if(msg!=""){
                    var res = msg.split("+");
                    for (i = 0; i < res.length; i++) {
                var resr = res[i].split(" ");
              newOptions[resr[0]+" "+resr[1]+" "+resr[2]] = resr[0]+" "+resr[2];
            }
            }else{
              ok = false;
            }

                //pin number should return
                }
            });
          var $el = $("#selectBN");
        $el.empty(); // remove old options
        if(ok){
        $.each(newOptions, function(key,value) {
            $el.append($("<option></option>").attr("value", value).text(key));
        });
        }
      });

        });
</script>
<script type="text/javascript">
function addThisDrug(){
  var quantity = $("#quantity").val();
  var batchN = $("#selectBN").val();
  var states = $("#states").val();
  
  if(states==null){
    swal("Select a drug!", "", "error");
  }else if(batchN==null){
    swal("Not Available!", "Selected drug is not available", "error");
  }else if(alreadyExists(batchN.split(" ")[0])){
      swal("Batch already added!", "", "error");
  }else if(quantity==""){
    swal("Enter a quantity!", "", "error");
  }else if(isNaN(quantity)){
    swal("Invalid Quantity!", "", "error");
  }else if(parseInt(quantity)<=0){
    swal("Invalid Quantity!", "", "error");
  }else if(parseFloat(quantity)>parseInt(quantity)){
    swal("Invalid Quantity!", "", "error");
  }else{
    var availableQuantity = batchN.split(" ")[1];
    if(availableQuantity<parseInt(quantity)){
      swal("Quantity not available!", "Available Quantity is less", "error");
    }else{
      myAddARow(states,batchN.split(" ")[0],quantity);
      document.getElementById("sub").disabled = false;
      document.getElementById('quantity').value = "";
      document.getElementById('selectBN').value = "";
    }
  }
}
</script>
<script type="text/javascript">
function myAddARow(name,bnum,qu) {
    var table = document.getElementById("myTable");
    var row = table.insertRow(table.rows.length);
    var cell0 = row.insertCell(0);
    var cell1 = row.insertCell(1);
    var cell2 = row.insertCell(2);
    var cell3 = row.insertCell(3);
    var cell4 = row.insertCell(4);
    cell0.innerHTML = table.rows.length-1;
    cell1.innerHTML = name;
    cell2.innerHTML = bnum;
    cell3.innerHTML = qu;
    cell4.innerHTML = "<button class='btn btn-warning' onclick='removeThis(this);'>Remove</button>";
}
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
function Redirect(){
  location.reload();
}
function submitForm(){
  var s = $('#modal_content').clone();
  //s.find('.chosen').addClass('swal');
  swal({
    title: 'Select Department',
  input: 'select',
  inputOptions: {
    '4': 'Serbia',
    '5': 'Ukraine',
    '6': 'Croatia'
  },
  inputPlaceholder: 'Select Department',

    showCancelButton: true, 
    closeOnConfirm: false,
    animation: "slide-from-top",
    inputValidator: function (value) {
    return new Promise(function (resolve, reject) {
      if (value === "") {
        reject('You need to select');
      } else {
        resolve();
      }
    });
    }

  }).then(
  function(inputValue){
    if (inputValue === false) return false;
    
    if (inputValue === "") {
      return false;
    }
    
    //swal("Nice!", "You wrote: ", "success");
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
  }).then(
  function(isConfirm){
    if(isConfirm){
    var ok = true;
    var drugs = "";
    var uid = $("#uid").val();
    var table = document.getElementById("myTable");
    for(var i=1;i<table.rows.length;i++){
      var snum = table.rows[i].cells[1].innerHTML.split(" ~ ")[1];
      drugs = drugs+"="+snum+"^"+table.rows[i].cells[2].innerHTML+"^"+table.rows[i].cells[3].innerHTML;
    }
    //alert(inputValue);
    $.ajax({
      type: "POST",
      url: "ajax.php",
      data:{
         "invDrugIssueToOthers" : true,
         "drugs" : drugs,
         "uid" : uid,
         "department_id" : inputValue
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
  });
}
</script>
</body>
</html>


