<?php
if(isset($_SESSION['user'])){
  $user = unserialize($_SESSION['user']);
}
?>
<html>
<head>
<title>home page</title>
<link rel="stylesheet" href="../assests/library/bootstrap-3.3.7-dist/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="../assests/library/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css">
<link rel="stylesheet" type="text/css" href="../assests/library/sweetalert-master/dist/sweetalert.css">
<style type='text/css'>
.col-md-2-height1 {
	      text-align: left;
	background-color: #f8f8f8;
	<!--background-color: #f8f8f8;-->
}
.col-xs-12-height1{
  background-color: #f8f8f8;
}
.navbar-default-nopaddingup{
	padding-bottom: 0px;
	margin-bottom: 0px;
}
.sidebar{
  background-color: #f8f8f8;
}
</style>

</head>
<body>
<header>
<nav class="navbar navbar-default navbar-default-nopaddingup">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand1" href="#">
	  <img alt="Bitz" src="../images/logo.png" style="width:50px;height:50px;">
        
      </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <!--<li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>-->
        <li><a href="home.php">Home</a></li>
      </ul>
      <!--<form class="navbar-form navbar-left">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>-->
      <ul class="nav navbar-nav navbar-right">
        <!--<li><a href="#">Requests</a></li>
		    <li><a href="#">Notifications</a></li>-->
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $user->getUName(); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="editprofile.php">Edit Profile</a></li>
            <li role="separator" class="divider"></li>
            <li><a href="../logout.php">Log Out</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
</header>
<script src="../assests/jquery.min.js"></script>
<script src="../assests/library/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
<script src="../assests/library/sweetalert-master/dist/sweetalert.min.js"></script>
<script type="text/javascript">
function htmlbodyHeightUpdate(){
    var height3 = $( window ).height();
    var height2 = $('#mb').height();
    var height1 = $('#sideB').height();
    var width1 = $( window ).width();
    if(width1>1000){
      if(height2>height3){
      $('#sideB').height(height2+50);
      }else{
      $('#sideB').height(height3+50);
      } 
    }else{
      var needH = $('#nop').val();
      needH = parseInt(needH);
      $('#sideB').height(40*needH + 30);
    }
  }
  $(document).ready(function () {
  htmlbodyHeightUpdate();
    $( window ).resize(function() {

      htmlbodyHeightUpdate();
    });
  });
</script>
</body>
</html>
