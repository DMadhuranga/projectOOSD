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

$error = false;
$emailErr = $passwordErr = $cpasswordErr ="";

function is_empty(...$paras){
  foreach ($paras as $para){
    if (($para == null) || ($para=='')){
      return true;
    }
  }
  return false;
}


if (isset($_REQUEST["submitb"])){
  if (($_REQUEST["password"])!=''){
    $password = $_REQUEST["password"];
    $cpassword = $_REQUEST["cpassword"];
    if(strlen($password)<8){
      $error = true;
      $passwordErr = "* Atleast 8 characters!";
    }
    elseif (is_empty($cpassword)){
      $error = true;
      $cpasswordErr = "* Please confirm the password";
    
    }elseif($cpassword!=$password){
      $error = true;
      $cpasswordErr = "* password miss match!";
    }
  }
  else{
    $password=$_SESSION['pass'];
    echo $_SESSION['pass'];
  }
  $email = $_REQUEST["email"];
  $first_name = $_REQUEST["first_name"];
  $last_name = $_REQUEST["last_name"];
  
  if ((is_empty($email)) || ($email == $_SESSION['em'])) {
    $email= $_SESSION['em'];
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
/*
  if (!is_empty($password)){
    if(strlen($password)<8){
      $error = true;
      $passwordErr = "* Atleast 8 characters!";
    }
    elseif (is_empty($cpassword)){
      $error = true;
      $cpasswordErr = "* Please confirm the password";
    
    }elseif($cpassword!=$password){
      $error = true;
      $cpasswordErr = "* password miss match!";
    }
  }
  */
  if ((is_empty($first_name))|| ($first_name == $_SESSION['fname'])){
    $first_name == $_SESSION['fname'];
  }
  if ((is_empty($last_name))|| ($last_name == $_SESSION['lname'])){
    $last_name == $_SESSION['lname'];
  }
  /*
  if (is_empty($password)){
    $password == $_SESSION['pass'];
  }
  */
  if (!$error){
    $id=$_SESSION['uid'];
    $query = "UPDATE hospital.users SET first_name='$first_name',last_name='$last_name',email='$email',password='$password' WHERE u_id= '$id'";
    $ins = mysqli_query($conn,$query);
    
    unset($_SESSION['fname']);
    unset($_SESSION['lname']);
    unset($_SESSION['em']);
    unset($_SESSION['pass']);
    unset($_SESSION['uid']);

    if ($ins){
      header('location:viewUser.php');
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

      <?php 
        if(( isset($_REQUEST['u_id']) || isset($_SESSION['uid'] )))
        {
          if (!isset($_REQUEST['u_id'])){
            $id = $_SESSION['uid'] ;
          } 
          else{
            $id = $_REQUEST['u_id'];}
          $query = "SELECT u_id,role_id,email,first_name,u_name,last_name,password FROM hospital.users WHERE u_id='$id' " or die ('error 1');
          $res = mysqli_query($conn,$query) or die('error completing query');
          if (mysqli_num_rows($res)==0){
            die ("no matches");
          }
          $row=mysqli_fetch_array($res,MYSQLI_ASSOC);
      
          $fname=$row['first_name'];
          $lname=$row['last_name'];
          $em=$row['email'];
          $pass=$row['password'];
          $uname=$row['u_name'];
          $_SESSION['fname']=$fname;
          $_SESSION['lname']=$lname;
          $_SESSION['em']=$em;
          $_SESSION['pass']=$pass;
          $_SESSION['uid']=$id;
        }
  
      ?>


        <div class="container">
          <h1 class="well">Edit User</h1>
          <h2> User : <?php echo $uname ?> </h2>
          <div class="col-lg-12 well">
            <div class="row">
              <form method ='POST' action="editUser.php">
                <div class="col-sm-12">
                  <div class="row">
                    <div class="col-sm-6 form-group">
                      <label>First Name</label>
                      <input name= "first_name" type="text" placeholder="Enter First Name Here.." class="form-control" value='<?php echo $fname;?>'>
                    </div>
                    <div class="col-sm-6 form-group">
                      <label>Last Name</label>
                      <input name="last_name"  type="text" placeholder="Enter Last Name Here.." class="form-control" value= '<?php echo $lname;?>'>
                    </div>
                  </div>
            
                <!--</div>-->
            <!--</div>-->
                  <div class="form-group">
                    <label>Email Address</label>
                    <input name="email"  type="text" placeholder="Enter Email Address Here.." class="form-control" value= '<?php echo $em ?>'>
                    <label><span style = "color:red"><?php echo $emailErr;?></span></label>
                  </div>  
            
                  <div class="form-group">
                    <label>New Password</label>
                    <input name= "password" type="password" placeholder="Enter New Password Here.." class="form-control" >
                    <label><span style = "color:red"><?php echo $passwordErr;?></span></label>
                  </div>
                  <div class="form-group">
                    <label>Confirm Password</label>
                    <input name="cpassword" type="password" placeholder="Re-enter Password Here.." class="form-control" >
                    <label><span style = "color:red"><?php echo $cpasswordErr;?></span></label>
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