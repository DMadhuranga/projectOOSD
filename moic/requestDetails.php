<?php
session_start();
$request_id = $_REQUEST['request_id'];
include('../dbconnection.php'); //$conn
include('../User.php');
include('basicTemp.php');

if(!isset($_SESSION['logged']) || !isset($_SESSION['user'])){
  header('location:../login.php');
}
$pages = $_SESSION['pages'];
$user = unserialize($_SESSION['user']);
if ($user->getRoleId()!=0){
  header('location:../logout.php');
}
?>


<html>
<head>

</head>
<body>
<div class='container-fluid'>
  <div class='row'>
    <div class='col-xs-12 col-xs-12-height1 col-md-2 col-md-2-height1' id="sideB">
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
    <div class='col-xs-12 col-md-10'  id="mb">
    <div class="row">
    <!-- Put Anything-->
    <h1 class="well">Request Details</h1>
    <?php
    	$query1 = "select * from hospital.request_details where request_id = '$request_id'";
    	$res = mysqli_query($conn, $query1);
    	$query3 = "select * from hospital.requests where request_id = '$request_id'";
    	$boo =  mysqli_query($conn, $query3);
    ?>
    <h3 class="well">
    	<?php 
    		$xxx = mysqli_fetch_array($boo,MYSQLI_ASSOC);
    		switch($xxx['sending_dept']){
            	case 1:
                	echo "Dispensary Drug Request";
                	break;
            	case 2:
                	echo "Inventory Drug Request";
                	break;              
            }; 
        ?>	
    </h3>
    <br>
    <table class="table table-striped">  
    <thead>  
      <tr>  
        <th>Serial Number</th>  
        <th>Drug Name</th>  
        <th>Amount</th> 
      </tr>  
    </thead> 
    <tbody>
    	<?php
    		if ($res) {
    			while ($row=mysqli_fetch_array($res,MYSQLI_ASSOC)) {?>
    				<tr>
    					<td><?php echo $row['serial_number'];?></td>
    					<?php
    						$snum = $row['serial_number'];
    						$query2 = "select drug_name from hospital.drugs where serial_number = '$snum'";
    						$check =  mysqli_query($conn, $query2);
    						$nm = mysqli_fetch_array($check,MYSQLI_ASSOC);
    						$drug_name = $nm['drug_name'];
    					?>
    					<td><?php echo $drug_name;?></td>
    					<td><?php echo $row['amount'];?></td>
    				</tr>
    			<?php
    			}
    		}

    	?>
    </tbody> 
    </table>
    <button type="button" class="btn btn-success" id="delete" onclick = "newF2(<?php echo  $request_id; ?>)">Accept Request</button>
    <button type="button" class="btn btn-danger" id="delete" onclick = "newF(<?php echo  $request_id; ?>)">Decline Request</button>
    
    
    <!-- Put Anything-->
    </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function decline(request_id){
    var dt = false;
    $.ajax({
    url : "ajax.php",
    type : "POST",
    async : false,
    data : {
      "declined" : true,
      "request_id" : request_id
    },
    success: function (data){
        if(data=="Ok"){
           dt = true;
        }
    }
});
    return dt;
}

function newF(request_id){
  swal({
  title: "Are you sure?",
  text: "",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "Yes, decline it!",
  cancelButtonText: "No, cancel!",
  closeOnConfirm: false,
  closeOnCancel: true
},
function(isConfirm){
  var dt = true;
  if(isConfirm){
    dt =  decline(request_id);
  }
  if (dt && isConfirm) {
    swal("Declined!", "Request is declined successfully", "success");
  } else {
    swal("Error!", "Server Error has occurred", "error");
  }
  setTimeout('Redirect()', 1500);
});
}


function newF2(request_id){
  swal({
  title: "Are you sure?",
  text: "",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3BBA76",
  confirmButtonText: "Yes, Accept it!",
  cancelButtonText: "No, cancel!",
  closeOnConfirm: false,
  closeOnCancel: true
},
function(isConfirm){
  var dt = true;
  if(isConfirm){
    dt =  accept(request_id);
  }
  if (dt && isConfirm) {
    swal("Accepted!", "Request is accepted successfully", "success");
  } else {
    swal("Error!", "Server Error has occurred", "error");
  }
  setTimeout('Redirect()', 1500);
});
}

function accept(request_id){
    var dt = false;
    $.ajax({
    url : "ajax.php",
    type : "POST",
    async : false,
    data : {
      "accepted" : true,
      "request_id" : request_id
    },
    success: function (data){
        if(data=="Ok"){
           dt = true;
        }
    }
});
    return dt;
}
</script>
</body>
</html>


