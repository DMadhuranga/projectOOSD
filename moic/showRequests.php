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
    <h1 class="well">Drug Requests</h1>
    <table class="table table-striped">  
    <thead>  
      <tr>  
        <th>Date</th>  
        <th>Sending Department</th>  
        <th>Show Details</th> 
      </tr>  
    </thead>  
    <tbody>
    
    <?php
    $query = "select request_id,date,sending_dept from hospital.requests where (state=0 AND receiving_dept=0) ";
    $res = mysqli_query($conn,$query);
    if ($res){
      while($row=mysqli_fetch_array($res,MYSQLI_ASSOC)){?>
        <tr>  
          <td><?php echo $row['date']; ?></td>          
          <td><?php switch($row['sending_dept']){
            case 1:
                echo "Dispensary";
                break;
            case 2:
                echo "Inventory";
                break;              
            }; ?></td> 
          <td><button type="button" class="btn btn-warning" onclick="eRedirect(<?php echo  $row['request_id']; ?>)">Show Details</button>
          </td> 
        </tr>
      <?php  
      }

    }?>
      
      
    </tbody>  
    </table>   
    <!-- Put Anything-->
    </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function Redirect(){
  location.reload();
}
function eRedirect(request_id){
  window.location.href=("requestDetails.php?request_id="+request_id);
}
</script>
</body>
</html>


