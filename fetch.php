<?php
//fetch.php
$connect = mysqli_connect("localhost", "root", "1010");
$output = '';
if(isset($_POST["query"]))
{
 $search = mysqli_real_escape_string($connect, $_POST["query"]);
 $query = "
  SELECT * FROM hospital.drug_batches 
  WHERE serial_number  LIKE '%".$search."%'
  OR batch_number  LIKE '%".$search."%' 
   ";
}
else
{
 $query = "
  SELECT * FROM hospital.drug_batches ORDER BY batch_number  
 ";
}
$result = mysqli_query($connect, $query);
if(mysqli_num_rows($result) > 0)
{
 $output .= '
  <div class="table-responsive">
   <table class="table table bordered">
    <tr>
     <th>Serial Number</th>
     <th>Batch Number</th>
     <th>Arrival Date</th>
     <th>Expiry Date</th>
     <th>Arrival Amount</th>
     <th>Inventory Balance</th>
     <th>Dispensor Balance</th>
     <th>Other Departments Balance</th>
     <th>Total Balance</th>
     <th>Edit/Delete</th>
    </tr>
 ';
 while($row = mysqli_fetch_array($result))
 {if ($row["deleted"]==0){
  $output .= '
   <tr>
    <td>'.$row["serial_number"].'</td>
    <td>'.$row["batch_number"].'</td>
    <td>'.$row["arrival"].'</td>
    <td>'.$row["expire"].'</td>
    <td>'.$row["arrival_amount"].'</td>
    <td>'.$row["inventory_balance"].'</td>
    <td>'.$row["dispensory_balance"].'</td>
    <td>'.$row["other_departments_balance"].'</td>
    <td>'.$row["total_balance"].'</td>
    <td><a rel="facebox" title="Click to edit the product" href="editproduct.php?id=$row[batch_number] "><button class="btn btn-warning"><i class="icon-edit"></i> </button> </a>
      <a href="#" id=" echo $row[batch_number]; " class="delbutton" title="Click to Delete the product"><button class="btn btn-danger" onclick="deleteThis('.$row["batch_number"].');"></button></a></td>
   </tr>
  ';
  }
 }
 echo $output;
}
else
{
 echo 'Data Not Found';
}

?>
<html>
  <body>
    
  </body>
</html>


