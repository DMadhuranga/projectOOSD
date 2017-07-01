<?php
session_start();
include('dbconnection.php'); //$conn
include('User.php');
include('basicTemp.php');

if(!isset($_SESSION['logged']) || !isset($_SESSION['user'])){
    header('location:login.php');
}
$pages = $_SESSION['pages'];
$user = unserialize($_SESSION['user']);
function is_empty(...$paras){
  foreach ($paras as $para){
    if (($para == null) || ($para=='')){
      return true;
    }
  }
  return false;
}
$error = false;
$emailErr = $passwordErr = $cpasswordErr = '';
if(isset($_SESSION["justAddedUser"])){
  echo '<script type="text/javascript">',
     'swal("User Added!","uubub" ,"success");',
     '</script>';
  unset($_SESSION["justAddedUser"]);
}
if (isset($_REQUEST["submitb"])){
  $password = $_REQUEST["password"];
  $cpassword = $_REQUEST["cpassword"];
  $email = $_REQUEST["email"];
  $first_name = $_REQUEST["first_name"];
  $last_name = $_REQUEST["last_name"];

  if (is_empty($email) || ($email == $user->getEmail())){
    $email = $user->getEmail();
  }else{
    $query = "select u_name from hospital.users where email = '$email'";
    $ins = mysqli_query($conn,$query);
    if (!$ins){
      die("Server error");
    }
    if (mysqli_num_rows($ins)!=0){
      $error = true;
      $emailErr = "* Email already used";
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
  if (is_empty($first_name) || ($first_name == $user->getFirstName())){
    $first_name = $user->getFirstName();
    //$error = true;
  }
  if (is_empty($last_name) || ($last_name == $user->getLastName())){
    $last_name = $user->getLastName();
    //$error = true;
  }
  if (!$error){
    $query = "insert into hospital.users (u_name,first_name,last_name,email,password,role_id) values ('$name','$first_name','$last_name','$email','$password','$r_id')";
    $ins = mysqli_query($conn,$query);
    if ($ins){
      $_SESSION["justAddedUser"]=true;
      header('location:editProfile.php');
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
    <h1 class="well">User Details</h1>
  <div class="col-lg-12 well">
  <div class="row">
        <form method ='POST' action="editProfile.php">
          <div class="col-sm-12">
            <div class="row">
              <div class="col-sm-6 form-group">
                <label>First Name<span style = "color:red"><?php echo "*";?></label>
                <input name= "first_name" type="text" placeholder="Enter First Name Here.." class="form-control" value = "<?php echo $user->getFirstName(); ?>">
              </div>
              <div class="col-sm-6 form-group">
                <label>Last Name<span style = "color:red"><?php echo "*";?></label>
                <input name="last_name"  type="text" placeholder="Enter Last Name Here.." class="form-control">
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
            <input id = "p" name= "password" type="password" placeholder="Enter Password Here.." class="form-control" disabled="true">
            <label><span style = "color:red"><?php echo $passwordErr;?></span></label>
          </div>
          <div class="form-group">
            <label>Confirm Password<span style = "color:red"><?php echo "*";?></label>
            <input id ="cp" name="cpassword" type="password" placeholder="Re-enter Password Here.." class="form-control" disabled="true">
            <label><span style = "color:red"><?php echo $cpasswordErr;?></span></label>
          </div>
          
          <div class="row">
          <div class="col-md-3">
          <button  type="Submit" class="btn btn-lg btn-info" name="submitb" >Submit</button>
          </div>
          <div class="col-md-7">
          <button  onclick="mewFD('<?php echo $user->getPassword(); ?>');" type="button" class="btn btn-lg btn-info" name="changeb" id="ichange"  >Change Password</button>
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
<script type="text/javascript">
  function mewFD(pword){
    swal({
  title: "Please Enter The Password",
  text: "",
  type: "input",
  showCancelButton: true,
  closeOnConfirm: false,
  animation: "slide-from-top",
  inputPlaceholder: "Enter Password Here"
},
function(inputValue){
  if (inputValue === false) return false;
  
  if (inputValue != pword) {
    swal.showInputError("Please Enter Correct Password");
    return false
  }else{
    document.getElementById("p").disabled = false ;
    document.getElementById("cp").disabled = false ;
    //document.getElementById("changeb").style.visibility='hidden';
  }
  
  swal("Password Confirmed!", " " , "success");
});
  }
</script>
</body>
</html>


