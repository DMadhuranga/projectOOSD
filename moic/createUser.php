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
function is_empty(...$paras){
  foreach ($paras as $para){
    if (($para == null) || ($para=='')){
      return true;
    }
  }
  return false;
}
$error = false;
$nameErr = $emailErr = $passwordErr = $cpasswordErr = $first_nameErr = $last_nameErr = $r_idErr= $genderErr='';
if(isset($_SESSION["justAddedUser"])){
  echo '<script type="text/javascript">',
     'swal("User Added!","uubub" ,"success");',
     '</script>';
  unset($_SESSION["justAddedUser"]);
}
if (isset($_REQUEST["submitb"])){
  $name = $_REQUEST["u_name"];
  $password = $_REQUEST["password"];
  $cpassword = $_REQUEST["cpassword"];
  $email = $_REQUEST["email"];
  $first_name = $_REQUEST["first_name"];
  $last_name = $_REQUEST["last_name"];
  $r_id = $_REQUEST["r_id"];
  if (is_empty($name)){
    $error = true;
    $nameErr = "* Please enter an user name";
  }else{
    $query = "select u_name from hospital.users where u_name = '$name'";
    $ins = mysqli_query($conn,$query);
    if (!$ins){
      die("Server error");
    }
    if (mysqli_num_rows($ins)!=0){
      $error = true;
      $nameErr = "* User name already exits";
    }
  }
  if (is_empty($email)){
    $error = true;
    $emailErr = "* Please enter an email";
  }else{
    $query = "select u_name from hospital.users where email = '$email'";
    $ins = mysqli_query($conn,$query);
    if (!$ins){
      die("Server error");
    }
    if (mysqli_num_rows($ins)!=0){
      $error = true;
      $emailErr = "* User email already used";
    }
  }
  if (is_empty($password)){
    $error = true;
    $passwordErr = "* Please enter a password";
  }elseif(strlen($password)<8){
    $error = true;
    $passwordErr = "* Atleast 8 characters!";
  }
  if (is_empty($cpassword)){
    $error = true;
    $cpasswordErr = "* Please confirm the password";
  }elseif($cpassword!=$password){
    $error = true;
    $cpasswordErr = "* password miss match!";
  }
  if (is_empty($first_name)){
    $error = true;
    $first_nameErr = "* Required";
  }
  if (is_empty($last_name)){
    $error = true;
    $last_nameErr = "* Required";
  }
  if (is_empty($r_id)){
    $error = true;
    $r_idErr = "* Required";
  }else{
    try{
      $r_id = intval($r_id);
    }catch(Exception $e){
      $error = true;
      $r_idErr = "* Required";
    } 
  }
  if (!$error){
    $query = "insert into hospital.users (u_name,first_name,last_name,email,password,role_id) values ('$name','$first_name','$last_name','$email','$password','$r_id')";
    $ins = mysqli_query($conn,$query);
    if ($ins){
      $_SESSION["justAddedUser"]=true;
      header('location:createUser.php');
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
    <!-- Put Anything-->
    <div class="container">
    <h1 class="well">User Details</h1>
  <div class="col-lg-12 well">
  <div class="row">
        <form method ='POST' action="createUser.php">
          <div class="col-sm-12">
            <div class="row">
              <div class="col-sm-6 form-group">
                <label>First Name<span style = "color:red"><?php echo "*";?></label>
                <input name= "first_name" type="text" placeholder="Enter First Name Here.." class="form-control">
                <label><span style = "color:red"><?php echo $first_nameErr;?></span></label>
              </div>
              <div class="col-sm-6 form-group">
                <label>Last Name<span style = "color:red"><?php echo "*";?></label>
                <input name="last_name"  type="text" placeholder="Enter Last Name Here.." class="form-control">
                <label><span style = "color:red"><?php echo $last_nameErr;?></span></label>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6 form-group">
                <label>User Name<span style = "color:red"><?php echo "*";?></label>
                <input name= "u_name" type="text" placeholder="Enter User Name Here.." class="form-control">
                <label><span style = "color:red"><?php echo $nameErr;?></span></label>
              </div>
              <div class="col-sm-6 form-group">
                <!--<label>Last Name</label>
                <input type="text" placeholder="Enter Last Name Here.." class="form-control">-->
                <div class="form-group">
                  <label for="sel1">Role:</label>
                  <select name= "r_id" class="form-control" id="sel1">
                  <option value="0">Admin</option>
                  <option value="1">Dispenser</option>
                  <option value="2">Inventory Manager</option>
                  <option value="3">Doctor</option>
                  </select>
                </div>
              </div>
            </div>
          <div class="form-group">
            <label>Email Address<span style = "color:red"><?php echo "*";?></label>
            <!--<label><span style = "color:red"><?php echo $emailErr;?></span></label>-->
            <input name="email"  type="text" placeholder="Enter Email Address Here.." class="form-control">
            <label><span style = "color:red"><?php echo $emailErr;?></span></label>
          </div>  
          <div class="form-group">
            <label>Password<span style = "color:red"><?php echo "*";?></label>
            <input name= "password" type="password" placeholder="Enter Password Here.." class="form-control">
            <label><span style = "color:red"><?php echo $passwordErr;?></span></label>
          </div>
          <div class="form-group">
            <label>Confirm Password<span style = "color:red"><?php echo "*";?></label>
            <input name="cpassword" type="password" placeholder="Re-enter Password Here.." class="form-control">
            <label><span style = "color:red"><?php echo $cpasswordErr;?></span></label>
          </div>
          <div class="row">
          <div class="col-md-10">
          <button  type="Submit" class="btn btn-lg btn-info" name="submitb" >Submit</button>
          </div>
          <div class="col-md-2">
          <label id="lbl1"><span style = "color:red; text-align:right" ><?php echo "* Required";?></span></label>
          </div>
          </div>
          <!--<button  type="Submit" class="btn btn-lg btn-info" name="submitb" >Submit</button>  -->       
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


