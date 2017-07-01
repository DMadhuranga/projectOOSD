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
  height: 1200px;
    text-align: left;
  background-color: #f8f8f8;
  <!--background-color: #f8f8f8;-->
}
.navbar-default-nopaddingup{
  padding-bottom: 0px;
  margin-bottom: 0px;
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
      <form class="navbar-form navbar-left">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#">Requests</a></li>
    <li><a href="#">Notifications</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $user->getUName(); ?> <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="../editProfile.php">Edit Profile</a></li>
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
</body>
</html>
