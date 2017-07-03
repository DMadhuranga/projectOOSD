<?php
session_start();
include('dbconnection.php'); //$conn
include('User.php');
include('basicTemp.php');

if(!isset($_SESSION['logged']) || !isset($_SESSION['user'])){
  header('location:../login.php');
}
$pages = $_SESSION['pages'];
$user = unserialize($_SESSION['user']);
$user_name = $user-> getUname();
$previous_f_name;
$previous_l_name;
$previous_pass;
$previous_email;
$u_id;
$bool = false;
$query = "select first_name,last_name,email,password from hospital.users where u_name = '$user_name'";
$ins = mysqli_query($conn,$query);
if ($is_run = mysqli_query($conn,$query)){
  $is_run = mysqli_fetch_array($is_run,MYSQLI_ASSOC);
  $previous_f_name = $is_run['first_name'];
  $previous_l_name = $is_run['last_name'];
  $previous_email = $is_run['email'];
  $previous_pass = $is_run['password'];
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

$nameErr = $emailErr = $passwordErr = $cpasswordErr= $cnpasswordErr = $first_nameErr = $last_nameErr = $r_idErr= $genderErr='';

if (isset($_POST["change_pass"])){
  echo "passed";
  
  if (isset($_REQUEST["submitb"])){
      $password = $_REQUEST["password"];
      $cpassword = $_REQUEST["cpassword"];
      $cnpassword = $_REQUEST["cnpassword"];
      $email = $_REQUEST["email"];
      $first_name = $_REQUEST["first_name"];
      $last_name = $_REQUEST["last_name"];

      if (is_empty($email)){
        $error = true;
        $emailErr = "* Please enter an email";
      }elseif($previous_email !== $email){
        $query = "select u_name from hospital.users where email = '$email'";
        $ins = mysqli_query($conn,$query);
        if (mysqli_num_rows($ins)!=0){
          $error = true;
          $emailErr = "* User email already used";
        }
      }
      if (is_empty($first_name)){
        $error = true;
        $first_nameErr = "* Required";
      }
      if (is_empty($last_name)){
        $error = true;
        $last_nameErr = "* Required";
      }
      
      if (is_empty($password) ){
        $error = true;
        $passwordErr = "* Please enter a password";
      }elseif(strlen($cpassword)<8 ){
        $error = true;
        $cpasswordErr = "* Atleast 8 characters!";
      }elseif( $password != $previous_pass ){
        $error = true;
        $passwordErr = "*please enter currrent password correctly";
      }
      if (is_empty($cpassword)){
        $error = true;
        $cnpasswordErr = "* Please confirm the password";
      }elseif( $cnpassword!=$cpassword){
        $error = true;
        $cnpasswordErr = "* password miss match!";
        $cpasswordErr = "*password miss match";
      }

      
  
  
//  $conn = mysqli_connect('localhost','root','1010','hospital');
      if (!$error){
        
          $sql = "UPDATE hospital.users
            SET first_name='$first_name',last_name = '$last_name',email = '$email',password = '$password'
            WHERE u_name='$user_name'";
        
  
        $ins = mysqli_query($conn,$sql);

        if ($ins){
          $user ->setFirstName($first_name);
          $user ->setLastName($last_name);
          $user ->setEmail($email);
          header('location:profile_edited.php');
        }else{
          echo "sql wrong</br>".mysqli_error($conn)."</br>";
        }
      }
    }
  }
else
    {
      if(isset($_REQUEST["change_pass"])){
      $email = $_REQUEST["email"];
      $first_name = $_REQUEST["first_name"];
      $last_name = $_REQUEST["last_name"];


      if (is_empty($email)){
        $error = true;
        $emailErr = "* Please enter an email";
      }elseif($previous_email != $email){
        $query = "select u_name from hospital.users where email = '$email'";
        $ins = mysqli_query($conn,$query);
        if (mysqli_num_rows($ins)!=0){
          $error = true;
          $emailErr = "* User email already used";
        }
      }
      if (is_empty($first_name)){
        $error = true;
        $first_nameErr = "* Required";
      }
      if (is_empty($last_name)){
        $error = true;
        $last_nameErr = "* Required";
      }
      if (!$error){
        
          $sql = "UPDATE hospital.users
            SET first_name='$first_name',last_name = '$last_name',email = '$email'
            WHERE u_name='$user_name'";
        
  
        $ins = mysqli_query($conn,$sql);

        if ($ins){
          $user ->setFirstName($first_name);
          $user ->setLastName($last_name);
          $user ->setEmail($email);
          echo "<script type = text/javascript>alert();</script>";
          header('location:profile_edited.php');
        }else{
          echo "sql wrong</br>".mysqli_error($conn)."</br>";
        }

        }
        

    }
   }



?>


<html>
<head>
<script>
  var enabled = "none";
  function change_enable(){
    var enabled = "true";
    }

  }
</script>
</head>
<body>
<div class='container-fluid'>
  <div class='row'>
    <div class='col-md-2 col-md-2-height1'>
    <div class = "row">

    </div>
    </div>
    <div class='col-md-10'>
    <div class="row">
    <!-- Put Anything-->
 <h1 class="well">Edit Profile</h1>
    <div class="col-lg-12 well">
  <div class="row">
        <form method ='POST' action="editprofile.php">
          <div class="col-sm-12">
            <div class="row">
              <div class="col-sm-6 form-group">
                <label>First Name<span style = "color:red"><?php echo "*";?></label>
                <input name= "first_name" type="text" value="<?php echo htmlspecialchars($previous_f_name);?>" class="form-control">
                <label><span style = "color:red"><?php echo $first_nameErr;?></span></label>
              </div>
              <div class="col-sm-6 form-group">
                <label>Last Name<span style = "color:red"><?php echo "*";?></label>
                <input name= "last_name" type="text" value="<?php echo htmlspecialchars($previous_l_name);?>" class="form-control">
                <label><span style = "color:red"><?php echo $last_nameErr;?></span></label>
              </div>
            </div>
          <div class="form-group">
            <label>Email Address<span style = "color:red"><?php echo "*";?></label>
            <!--<label><span style = "color:red"><?php echo $emailErr;?></span></label>-->
            <input name="email"  type="text" value="<?php echo htmlspecialchars($previous_email);?>" class="form-control">
            <label><span style = "color:red"><?php echo $emailErr;?></span></label>
          </div>  
          <button name = "change_pass" type="button"  onclick= 'document.getElementById("p3").disabled = false ;document.getElementById("p2").disabled = false ;document.getElementById("p1").disabled = false; '> Change Password</button>

         
          <div>
          <label></label>
          <label></label>
          <label></label>
          </div>
          

          <div class="form-group">
            <label>Current Password<span style = "color:red"><?php echo "*";?></label>
            <input id = "p1" name= "password" type="password" placeholder="Enter Current Password here..." class="form-control" disabled="true" >
            <label><span style = "color:red"><?php echo $passwordErr;?></span></label> 
          </div>

          <div class="form-group">
            <label>New Password<span style = "color:red"><?php echo "*";?></label>
            <input id = "p2" name="cpassword" type="password" placeholder="Enter New Password here..." class="form-control"  disabled="true">
            <label><span style = "color:red"><?php echo $cpasswordErr;?></span></label>
          </div>
           <div class="form-group">
            <label>Confirm New Password<span style = "color:red"><?php echo "*";?></label>
            <input id = "p3" name= "cnpassword" type="password" placeholder="Re-enter New Password here..." class="form-control" disabled="true">
            <label><span style = "color:red"><?php echo $cnpasswordErr;?></span></label>
          </div>
          
          <div class="row">
          <div class="col-md-10">
          <button  type="Submit" class="btn btn-lg btn-info" name="submitb" >Submit</button>
          </div>
          <div class="col-md-2">
          </div>
          </div>       
          </div>

        </form> 
        </div>
  </div>      
    
    
    <!-- Put Anything-->
    </div>
    </div>
  </div>
</div>
</body>
</html>


