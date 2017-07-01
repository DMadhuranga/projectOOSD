<?php
session_start();
include('dbconnection.php'); //$conn
include('User.php');
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
<link href="assests/library/select2-4.0.3/dist/css/select2.min.css" rel="stylesheet"/>
<style type="text/css">
.display-4-2{
	margin-left: 15px;
}
</style>
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
          <button  type="button" class="btn btn-lg btn-info" name="addb" >Issue Drugs</button>
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
                <label>Drug Name</label>
                <select class="form-control" id="states" >
                <object>Select</object>
                <option value="" selected = "" disabled=""></option>
	  <?php
		foreach($drugs as $drug){?>
			<option value="<?php echo $drug["serial_number"]; ?>"> <?php echo $drug['drug_name']."  -  ".$drug['serial_number']; ?></option>
		<?php }
	  ?>
	  </select>
              </div>
              <div class="col-sm-3 form-group">
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
        <th>Drug Name</th>  
        <th>Serial Number</th>  
        <th>Batch Number</th>  
        <th>Quantity</th> 
    <th>Remove</th> 
      </tr>  
    </thead>  
    <tbody>
    
    <!--<?php
      foreach( $users as $user ) {?>
        <tr>  
          <td><?php echo $user->getUName(); ?></td>   
          <td><?php switch($user->getRoleId()){
            case 0:
                echo "Admin";
                break;
            case 1:
                echo "Dispenser";
                break;
            case 2:
                echo "Inventory Manager";
                break;
                
            }; ?></td>
          <td><?php echo $user->getFirstName(); ?></td>  
          <td><?php echo $user->getEmail(); ?></td> 
          <td><button type="button" class="btn btn-warning" onclick="eRedirect(<?php echo  $user->getUId(); ?>)">Edit</button>
          <button type="button" class="btn btn-danger" id="delete" onclick = "newF(<?php echo  $user->getUId(); ?>)">Delete</button></td> 
        </tr>
        
      <?php
      }
    
  ?>-->
      
    </tbody>  
  </table> 
  </div>
  </div>
	  <!-- Put Anything-->
    </div>
	  </div>
	</div>
</div>
<script src="assests/library/select2-4.0.3/dist/js/select2.full.min.js"></script>
<script>
        $(document).ready(function() { 
            $("#states").select2({
                    placeholder: "Select a drug",
                    allowClear: true
             });
            $('select').on('select2:select', function (evt) {
  				var value1 = $('select').val();
  				var ok = true;
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
							newOptions[resr[0]+" "+resr[1]+" "+resr[2]] = resr[0]+" "+resr[3];
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
	if(batchN!=null && quantity!=""){
		var tb = batchN.split(" ");
		myAddARow(tb[1],states,tb[0],quantity);
		//alert(states);
	}else if(states==null){
		swal("Select a drug!", "", "error");
	}else if(batchN==null){
		swal("Not Available!", "Selected drug is not available", "error");
	}else if(quantity==""){
		swal("Enter a quantity!", "", "error");
	}
	
}
</script>
<script type="text/javascript">
	function myAddARow(name,snum,bnum,qu) {
    var table = document.getElementById("myTable");
    var row = table.insertRow(table.rows.length);
    var cell1 = row.insertCell(0);
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
    var cell4 = row.insertCell(3);
    var cell5 = row.insertCell(4);
    cell1.innerHTML = name;
    cell2.innerHTML = snum;
    cell3.innerHTML = bnum;
    cell4.innerHTML = qu;
    var btn = document.createElement('button');
	btn.type = "button";
	//btn.class = "btn btn-danger";
	btn.value = "Remove";
	//btn.onclick = "alert();";
	cell5.appendChild(btn);
	btn.addEventListener("click", function(){
   		alert();

   
});
	}
</script>
<script type="text/javascript">
function removeThis(){
	alert();
}
</script>
</body>
</html>


