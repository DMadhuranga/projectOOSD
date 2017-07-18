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
        <h1 class="well">View Drugs</h1>
       <div class="col-lg-12 well">
          <div class="row">
          </div>
        </div>
      </div>
     <div class="form-group">
    <div class="input-group">
     <span class="input-group-addon">Search</span>
     <input type="text" name="search_text" id="search_text" placeholder="Search drugs " class="form-control" />
    </div>
   </div>
   <br />
   <div id="result"></div>




    
    
    <!-- Put Anything-->
    </div>
    </div>
  </div>
</div>
<script>
$(document).ready(function(){

 load_data();

 function load_data(query)
 {
  $.ajax({
   url:"fetch.php",
   method:"POST",
   data:{query:query},
   success:function(data)
   {
    $('#result').html(data);
   }
  });
 }
 $('#search_text').keyup(function(){
  var search = $(this).val();
  if(search != '')
  {
   load_data(search);
  }
  else
  {
   load_data();
  }
 });
});
</script>

<script type="text/javascript">
      function deleteThis(batch_number){
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
  dt =  deleteBatch(batch_number);
  location.reload();
  if (dt) {
    swal("Deleted!", "Drug batch has deleted successfully", "success");

  } else {
    swal("Error!", "Server Error has occurred", "error");
  }
  setTimeout('Redirect()', 1500);
});
        
}
function deleteBatch(batch_number){
  var dt = false;
    $.ajax({
    url : "deleted.php",
    type : "POST",
    async : false,
    data : {
      "del_batch" : true,
      "batch_number" : batch_number
    },
    success: function (data){
        if(data=="Ok"){
           dt = true;
        }
    }
});
    return dt;
}
    </script>
</body>
</html>

