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
$query = "select u_id,role_id,email,first_name,u_name,last_name from hospital.users where (deleted=0 AND role_id>0 )";
$res = mysqli_query($conn,$query);
if ($res){
  $users = array();
  while($row=mysqli_fetch_array($res,MYSQLI_ASSOC)){
    $tempUser = new User();
    $tempUser->setUName($row['u_name']);
    $tempUser->setRoleId($row['role_id']);
    $tempUser->setUId($row["u_id"]);
    $tempUser->setEmail($row['email']);
    $tempUser->setFirstName($row['first_name']);
    $tempUser->setLastName($row['last_name']);
    array_push($users,$tempUser);
  }

}
if(isset($_SESSION['otherUserEdited'])){
  echo '<script type="text/javascript">',
     'swal("Changes Saved!","uubub" ,"success");',
     '</script>';
  unset($_SESSION['otherUserEdited']);
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
        <table class="table table-striped">  
    <thead>  
      <tr>  
        <th>User Name</th>  
        <th>Role</th>  
        <th>First Name</th>  
        <th>Email</th> 
    <th>Edit/Remove</th> 
      </tr>  
    </thead>  
    <tbody>
    
    <?php
      foreach( $users as $user ) {?>
        <tr>  
          <td><?php echo $user->getUName(); ?></td>   
          <td><?php switch($user->getRoleId()){
            case 0:
                echo "Admin";
                break;
            case 1:
                echo "Dispenser";
                break;
            case 2:
                echo "Inventory Manager";
                break;
            case 3:
                echo "Doctor";
                break;
                
            }; ?></td>
          <td><?php echo $user->getFirstName(); ?></td>  
          <td><?php echo $user->getEmail(); ?></td> 
          <td><button type="button" class="btn btn-warning" onclick="eRedirect(<?php echo  $user->getUId(); ?>)">Edit</button>
          <button type="button" class="btn btn-danger" id="delete" onclick = "newF(<?php echo  $user->getUId(); ?>)">Delete</button></td> 
        </tr>
        
      <?php
      }
    /*$que = "select r_id,r_name from inventory.roles";
    $res = mysqli_query($conn,$que);
    if ($res){
    while($row = mysqli_fetch_array($res)){?>
      <option value="<?php echo $row['r_id']; ?>"> <?php echo $row['r_name']; ?></option>
    <?php } 
    }*/
  ?>
      <!--<tr>  
        <td>001</td>  
        <td>Rammohan </td>  
        <td>Reddy</td>  
        <td>A+</td>  
      </tr>  
      <tr>  
        <td>002</td>  
        <td>Smita</td>  
        <td>Pallod</td>  
        <td>A</td>  
      </tr>  
      <tr>  
        <td>003</td>  
        <td>Rabindranath</td>  
        <td>Sen</td>  
        <td>A+</td>  
      </tr>  -->
    </tbody>  
  </table> 
    
    
    <!-- Put Anything-->
    </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  function delUser(u_id){
    var dt = false;
    $.ajax({
    url : "ajax.php",
    type : "POST",
    async : false,
    data : {
      "del_user" : true,
      "u_id" : u_id
    },
    success: function (data){
        if(data=="Ok"){
           dt = true;
        }
    }
});
    return dt;
}
function Redirect(){
  location.reload();
}
function eRedirect(u_id){
  window.location.href=("editUser.php?u_id="+u_id);
}

</script>
<script type="text/javascript">
function newF(u_id){
  swal({
  title: "Are you sure?",
  text: "",
  type: "warning",
  showCancelButton: true,
  confirmButtonColor: "#DD6B55",
  confirmButtonText: "Yes, delete it!",
  cancelButtonText: "No, cancel!",
  closeOnConfirm: false,
  closeOnCancel: true
},
function(isConfirm){
  var dt = true;
  if(isConfirm){
    dt =  delUser(u_id);
  }
  if (dt && isConfirm) {
    swal("Deleted!", "User has deleted successfully", "success");
  } else {
    swal("Error!", "Server Error has occurred", "error");
  }
  setTimeout('Redirect()', 1500);
});
}
</script>
</body>
</html>


