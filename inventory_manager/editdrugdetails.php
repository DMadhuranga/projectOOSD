<?php
session_start();
include('../dbconnection.php'); //$conn
include('../User.php');
include('../Drug.php');
include('basicTemp.php');
if(!isset($_SESSION['logged']) || !isset($_SESSION['user'])){
  header('location:../login.php');
}
$pages = $_SESSION['pages'];
//$s_num = $_REQUEST['serial_number'];
//echo $s_num;
$user = unserialize($_SESSION['user']);
if ($user->getRoleId()!=2){
  header('location:../logout.php');
}


$error = false;
$nameErr = $serialErr = $dis_levelErr = $inv_levelErr = '';

function is_empty(...$paras){
  foreach ($paras as $para){
    if (($para == null) || ($para=='')){
      return true;
    }
  }
  return false;
}

        if(( isset($_REQUEST['serial_number']) || isset($_SESSION['snum'] )))
        {
          if (!isset($_REQUEST['serial_number'])){
            $s_num = $_SESSION['snum'] ;
          } 
          else{
            $s_num = $_REQUEST['serial_number'];}
          $query = "select * from hospital.drugs where serial_number='$s_num' " or die ('error 1');
          $res = mysqli_query($conn,$query) or die('error completing query');
          
          $row=mysqli_fetch_array($res,MYSQLI_ASSOC);
      
          $name=$row['drug_name'];
          $type=$row['type'];
          $desc=$row['description'];
          $invalrt=$row['inventory_alert_level'];
          $dispalrt=$row['dispensary_alert_level'];
          $_SESSION['name']=$name;
          $_SESSION['type']=$type;
          $_SESSION['desc']=$desc;
          $_SESSION['invalrt']=$invalrt;
          $_SESSION['dispalrt']=$dispalrt;
          $_SESSION['snum']=$s_num;
        }


if (isset($_REQUEST["submitb"])){
  //echo $error;
  $drugname = $_REQUEST["name"];
  //$serial = $_REQUEST["serial"];
  $type = $_REQUEST["type"];
  $description = $_REQUEST["desc"];
  $inv_level = $_REQUEST["invalrt"];
  $dis_level = $_REQUEST["dispalrt"];
  if(is_empty($inv_level)){
    $error = true;
    $inv_levelErr = "* Please enter a warning level";
  }elseif(!is_numeric($inv_level)){
    $error = true;
    $inv_levelErr = "* Incorrect input";
  }else{
    $inv_level = (int)$inv_level;
    if($inv_level<=0){
      $error = true;
      $inv_levelErr = "* Incorrect input";
    }
  }
  if(is_empty($dis_level)){
    $error = true;
    $dis_levelErr = "* Please enter a warning level";
  }elseif(!is_numeric($dis_level)){
    $error = true;
    $dis_levelErr = "* Incorrect input";
  }else{
    $dis_level = (int)$dis_level;
    if($dis_level<=0){
      $error = true;
      $dis_levelErr = "* Incorrect input";
    }
  }
  if (is_empty($drugname)){
    $error = true;
    $nameErr = "* Please enter an drug name";
  }elseif($name != $drugname){
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
  
  
  
  if (!$error){
    $query = "UPDATE hospital.drugs SET drug_name='$drugname',type='$type',description='$description',inventory_alert_level='$inv_level', dispensary_alert_level= $dis_level WHERE serial_number= '$s_num'";
    $ins = mysqli_query($conn,$query);
    if ($ins){
      echo '<script = type="text/javascript">swal("Done!", "Drug Details Changed!", "success");</script>';
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

     <!-- <?php 
        
        
          $query = "select * from hospital.drugs where serial_number='$s_num' " or die ('error 1');
          $res = mysqli_query($conn,$query) or die('error completing query');
          $res = mysqli_query($conn,$query);
          $row=mysqli_fetch_array($res,MYSQLI_ASSOC);
      
          $name=$row['drug_name'];
          $type=$row['type'];
          $desc=$row['description'];
          $invalrt=$row['inventory_alert_level'];
          $dispalrt=$row['dispensary_alert_level'];




      ?>-->





        <div class="container">
          <h1 class="well">Edit Drug Details</h1>
          <h2> Serial Number : <?php echo $s_num ?> </h2>
          <div class="col-lg-12 well">
            <div class="row">
              <form method ='POST' action="editdrugdetails.php">
                <div class="col-sm-12">
                  <div class="row">
                    <div class="col-sm-12 form-group">
                      <label>Drug Name</label>
                        <input name="name"  type="text" placeholder="Enter Drug name Here.." class="form-control" value= '<?php echo $name ?>'>
                      <label><span style = "color:red"><?php echo $nameErr;?></span></label>
                    </div>  
                    <div class="col-sm-6 form-group">
                
                      <div class="form-group">
                        <label for="sel1">Drug Type:</label>
                        <select name= "type" class="form-control" id="sel1" >
                          <option selected="selected"><?php echo($type )?></option>

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
            
                
                  
            
                  <div class="form-group">
                    <label>Description</label>
                    <input name= "desc" type="text" placeholder="Enter Drug Description Here.." class="form-control" value='<?php echo $desc ?>' >
                    
                  </div>
                  <div class = "row">
                    <div class="col-sm-6 form-group">
                      <label>Inventory Alert Level</label>
                      <input name="invalrt" type="text" placeholder="Enter Inventory Alert Level Here.." class="form-control" value='<?php echo $invalrt;?>'>
                      <label><span style = "color:red"><?php echo $inv_levelErr;?></span></label>
                    </div>
                    <div class="col-sm-6 form-group">
                      <label>Dispensary Alert Level</label>
                      <input name="dispalrt" type="text" placeholder="Enter Dispensart Alert Level Here.." class="form-control" value = '<?php echo $dispalrt; ?>'>
                      <label><span style = "color:red"><?php echo $dis_levelErr;?></span></label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-10">
                      <button  type="Submit" class="btn btn-lg btn-info" name="submitb" >Submit</button>
                    </div>
                  </div>
                </div>
              </form> 
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
</script>
</body>
</html>