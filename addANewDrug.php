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
function is_empty(...$paras){
  foreach ($paras as $para){
    if (($para == null) || ($para=='')){
      return true;
    }
  }
  return false;
}
$error = false;
$nameErr = $serialErr ='';
if(isset($_SESSION["justAddedDrug"])){
  echo '<script type="text/javascript">',
     'swal("Drug Added!", "success");',
     '</script>';
  unset($_SESSION["justAddedDrug"]);
}
if (isset($_REQUEST["submitb"])){
  $drugname = $_REQUEST["drugname"];
  $serial = $_REQUEST["serial"];
  $type = $_REQUEST["type"];
  $description = $_REQUEST["description"];
  if (is_empty($drugname)){
    $error = true;
    $nameErr = "* Please enter an drug name";
  }else{
    $query = "select drug_name from hospital.drugs where drug_name = '$drugname'";
    $ins = mysqli_query($conn,$query);
    if (!$ins){
      die("Server error");
    }
    if (mysqli_num_rows($ins)!=0){
      $error = true;
      $nameErr = "* Drug already exits";
    }
  }
  if (is_empty($serial)){
    $error = true;
    $serialErr = "* Please enter a serial number";
  }else{
    $query = "select serial_number from hospital.drugs where serial_number = '$serial'";
    $ins = mysqli_query($conn,$query);
    if (!$ins){
      die("Server error");
    }
    if (mysqli_num_rows($ins)!=0){
      $error = true;
      $serialErr = "* Drug already used";
    }
  }
  
  
  if (!$error){
    $query = "insert into hospital.drugs (drug_name,serial_number,type,description) values ('$drugname','$serial','$type','$description')";
    $ins = mysqli_query($conn,$query);
    if ($ins){
      $_SESSION["justAddedDrug"] = true;
      header('location:addANewDrug.php');
    }else{
      echo "sql wrong</br>".mysqli_error($conn)."</br>";
    }
  }
}
?>


<html>
<head>

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
    <h1 class="well">Drug Details</h1>
  <div class="col-lg-12 well">
  <div class="row">
        <form method ='POST' action="addANewDrug.php">
          <div class="col-sm-12">
            <!--<div class="row">
              <div class="col-sm-6 form-group">
                <label>First Name</label>
                <input name= "first_name" type="text" placeholder="Enter First Name Here.." class="form-control">
                <label><span style = "color:red"><?php echo $first_nameErr;?></span></label>
              </div>
              <div class="col-sm-6 form-group">
                <label>Last Name</label>
                <input name="last_name"  type="text" placeholder="Enter Last Name Here.." class="form-control">
                <label><span style = "color:red"><?php echo $last_nameErr;?></span></label>
              </div>
            </div>-->
            <div class="row">
              <div class="col-sm-6 form-group">
                <label>Serial Number <span style = "color:red"><?php echo "*";?></span>   </label>
                <input name= "serial" type="text" placeholder="Enter Serial Number Here.." class="form-control">
                <label><span style = "color:red"><?php echo $serialErr;?></span></label>
              </div>
              <div class="col-sm-6 form-group">
                <!--<label>Last Name</label>
                <input type="text" placeholder="Enter Last Name Here.." class="form-control">-->
                <div class="form-group">
                  <label for="sel1">Drug Type:</label>
                  <select name= "type" class="form-control" id="sel1">
                  <option value="Tablet">Tablet</option>
                  <option value="Capsule">Capsule</option>
                  <option value="Inhaler">Inhaler</option>
                  <option value="Vial">Vial</option>
                  <option value="Bottle">Bottle</option>
                  <option value="Ampules">Ampules</option>
                  <option value="Suppociteries">Suppociteries</option>
                  <option value="Tube">Tube</option>
                  <option value="Pessaries">Pessaries</option>
                  <option value="Spray">Spray</option>
                  <option value="Other">Other</option>
                  </select>
                </div>
              </div>
            </div>
            <!--
            <div class="form-group">
              <label>Address</label>
              <textarea placeholder="Enter Address Here.." rows="3" class="form-control"></textarea>
            </div>  
            <div class="row">
              <div class="col-sm-4 form-group">
                <label>City</label>
                <input type="text" placeholder="Enter City Name Here.." class="form-control">
              </div>  
              <div class="col-sm-4 form-group">
                <label>State</label>
                <input type="text" placeholder="Enter State Name Here.." class="form-control">
              </div>  
              <div class="col-sm-4 form-group">
                <label>Zip</label>
                <input type="text" placeholder="Enter Zip Code Here.." class="form-control">
              </div>    
            </div>
            <div class="row">
              <div class="col-sm-6 form-group">
                <label>Title</label>
                <input type="text" placeholder="Enter Designation Here.." class="form-control">
              </div>    
              <div class="col-sm-6 form-group">
                <label>Company</label>
                <input type="text" placeholder="Enter Company Name Here.." class="form-control">
              </div>  
            </div>            
          <div class="form-group">
            <label>Phone Number</label>
            <input type="text" placeholder="Enter Phone Number Here.." class="form-control">
          </div>  
            -->
          <div class="form-group">
            <label>Drug Name<span style = "color:red"><?php echo "*";?></span></label>
            <input name="drugname"  type="text" placeholder="Enter Drug Name Here.." class="form-control">
            <label><span style = "color:red"><?php echo $nameErr;?></span></label>
          </div>  
          <div class="form-group">
            <label>Description</label>
            <input name= "description" type="text" placeholder="Enter Description.." class="form-control">
            
          </div>
          <!--
          <div class="form-group">
            <label>Confirm Password</label>
            <input name="cpassword" type="text" placeholder="Re-enter Password Here.." class="form-control">
            <label><span style = "color:red"></span></label>
          </div>-->
          <div class="row">
          <div class="col-md-10">
          <button  type="Submit" class="btn btn-lg btn-info" name="submitb" >Submit</button>
          </div>
          <div class="col-md-2">
          <label id="lbl1"><span style = "color:red; text-align:right" ><?php echo "* Required";?></span></label>
          </div>
          </div>
          </div>
        </form> 
        </div>
  </div>
  </div>
    
    
    <!-- Put Anything-->
    </div>
    </div>
  </div>
</div>
</body>
</html>


