<?php
session_start();
include('../dbconnection.php'); //$conn
include('../User.php');
include('basicTemp.php');
include('../Drug.php');
if(!isset($_SESSION['logged']) || !isset($_SESSION['user'])){
  header('location:../login.php');
}
$pages = $_SESSION['pages'];
$user = unserialize($_SESSION['user']);
if ($user->getRoleId()!=2){
  header('location:../logout.php');
}
$s_num = $_REQUEST['serial_number'];

$query1 = "select * from hospital.drugs where serial_number = '$s_num'";
$is_run = mysqli_query($conn,$query1);
if ($is_run = mysqli_query($conn,$query1)){
  $is_run = mysqli_fetch_array($is_run);
  $drug_name = $is_run['drug_name'];
  $type = $is_run['type'];
}
$query2 = "select * from hospital.drug_batches where ((expiry_status!=3) AND (deleted=0 AND serial_number = '$s_num' ))";
//echo $s_num;
$res = mysqli_query($conn,$query2);
if ($res){
  $batches = array();
  while($row=mysqli_fetch_array($res,MYSQLI_ASSOC)){
    $batch = new drugbatch();
    $batch->setBatch_number($row['batch_number']);
    $batch->setArrival($row["arrival"]);
    $batch->setExpire($row['expire']);
    $batch->setArrival_amount($row['arrival_amount']);
    $batch->setInventory_balance($row['inventory_balance']);
    $batch->setDispensary_balance($row['dispensory_balance']);
    $batch->setTotal_balance($row['total_balance']);
    $batch->setOther_department_balance($row['other_departments_balance']);
    $num = $row['serial_number'];
    array_push($batches,$batch);
  }

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
    <h1 class="well"><?php echo $drug_name ?></h1>
    <h3 class="well">Serial Number : <?php echo $s_num?></h3>
    <table id="myTable" class="table table-striped">  
    <thead>  
      <tr>  
        <th>Batch Number</th>  
        <th>Arrival Date</th>  
        <th>Expire Date</th>  
        <th>Arrival Amount</th>
        <th>Inventory Amount</th> 
      <th>Dispensary Balance</th> 
      <th>Other Department Balance</th>
      <th>Total Balance</th>
      <th>Send Return Order</th>
      </tr>  
    </thead>  
    <tbody>
    
    <?php
      foreach( $batches as $batch ) {?>
        <tr class = "table-active">  
          <td><?php echo $batch->getBatch_number();?></td>   
          <td><?php echo $batch->getArrival(); ?></td>  
          <td><?php echo $batch->getExpire(); ?></td>       
          <td><?php echo $batch->getArrival_amount(); ?></td> 
          <td><?php echo $batch->getInventory_balance(); ?></td> 
          <td><?php echo $batch->getDispensary_balance(); ?></td> 
          <td><?php echo $batch->getOther_department_balance(); ?></td> 
          <td><?php echo $batch->getTotal_balance(); ?></td>
          <td>
          <button type="button" id = "1" class="btn btn-warning" >Send Return Order </button></td> 
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
</script>
</body>
</html>


