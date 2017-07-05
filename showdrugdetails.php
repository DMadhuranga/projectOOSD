<?php
session_start();
include('dbconnection.php'); //$conn
include('User.php');
include('basicTemp.php');
include("Drug.php");
if(!isset($_SESSION['logged']) || !isset($_SESSION['user'])){
  header('location:login.php');
}
$pages = $_SESSION['pages'];
$user = unserialize($_SESSION['user']);

$query = "select drug_name,serial_number,type,description from hospital.drugs where deleted=0";
$res = mysqli_query($conn,$query);
if ($res){
  $drugs = array();
  while($row=mysqli_fetch_array($res,MYSQLI_ASSOC)){

    $tempDrug = new Drug();
    $tempDrug->setDName($row['drug_name']);
    $tempDrug->setSerialNumber($row['serial_number']);
    $tempDrug->setType($row["type"]);
  //  $tempDrug->setEmail($row['email']);
    $tempDrug->setDescription($row['description']);
   // $tempDrug->setLastName($row['last_name']);
    array_push($drugs,$tempDrug);
  }

}
//echo count($drugs);
?>


<html>
<head>
<link type="text/css" href="../assests/library/dataTAble/css/jquery.dataTables.min.css" />
<script type="text/javascript" src="../assests/library/dataTAble/js/jquery.dataTables.min.js"></script>

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
    
    <table id="myTable" class="table table-striped">  
    <thead>  
      <tr>  
        <th>Drug Name</th>  
        <th>Serial Number</th>  
        <th>Type</th>  
        <th>Description</th> 
    <th>Show Details</th> 
      </tr>  
    </thead>  
    <tbody>
    
    <?php
      foreach( $drugs as $drug ) {?>
        <tr class = "table-active">  
          <td><?php echo $drug->getDName();?></td>   
          <td><?php echo $drug->getSerialNumber(); ?></td>  
          <td><?php echo $drug->getType(); ?></td>       
          <td><?php echo $drug->getDescription(); ?></td> 
          <td><button type="button" class="btn btn-warning" onclick="eRedirect(<?php echo  $drug->getSerialNumber();?>)">Show Details</button>

        </tr> 
        
      <?php
      }

  ?>

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
function eRedirect(serial_number){
  window.location.href=("drugdetails.php?serial_number="+serial_number);
  //document.write(serial_number);
}
</script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#myTable').DataTable();
});
</script>
</body>
</html>


