<?php
session_start();
include('dbconnection.php');
include('User.php');
function is_empty(...$paras){
    foreach ($paras as $para){
        if (($para == null) || ($para=='')){
            return true;
        }
    }
    return false;
}
$loginErr = '';
$nameErr = 'Username';
$passwordErr = "Password";
$pErr = false;
$nErr = false;
if (isset($_SESSION['logged']) && $_SESSION['logged']){
    header('location:home.php');
}elseif (isset($_REQUEST["Submit"])){
    $error = false;
    $name = $_REQUEST["Username"];
    $password = $_REQUEST["Password"];
    if (is_empty($name)){
        $error = true;
        $nameErr = "*";
    }
    if (is_empty($password)){
        $error = true;
        $passwordErr = "*";
    }
    /*echo $nameErr."</br>";
    echo $passwordErr."</br>";*/
    if (!$error){
        $query = "select password,u_id,role_id,email,first_name,last_name from hospital.users where u_name ='$name' ";
        $res = mysqli_query($conn,$query);

        if ($res){
            $res = mysqli_fetch_array($res,MYSQLI_ASSOC);
            $userId=$res['u_id'];//new
            include('log.php');//new

            if($res['password']==$password){
                $nuser = new User();
                $nuser->setUName($name);
                $nuser->setRoleId($res['role_id']);
                $nuser->setEmail($res['email']);
                $nuser->setUId($res['u_id']);
                $nuser->setFirstName($res['first_name']);
                $nuser->setpassword($res['password']);
                $nuser->setLastName($res['last_name']);
                $_SESSION['logged'] = true;
                $_SESSION['user'] = serialize($nuser);
                $role_idNew = $res['role_id'];
                mysqli_select_db($conn,"hospital");
                $query = "SELECT pg.page_url,pg.page_name
                from pages pg
                inner join role2page r2p
                on pg.page_id = r2p.page_id
                where r2p.role_id='$role_idNew';";
                $res = mysqli_query($conn,$query);
                $pages = array();
                if ($res){
                    while($row=mysqli_fetch_array($res,MYSQLI_ASSOC)){
                        $tempPage = array();
                        array_push($tempPage,$row['page_name']);
                        array_push($tempPage,$row['page_url']);
                        array_push($pages,$tempPage);
                    }

                }
                $_SESSION['pages'] = $pages;
                logUser($userId,$conn);//new
                header('location:home.php');
            }elseif ($res['u_id']==''){
                $nErr = true;
                $nameErr = "* Invalid user name";
            }else{
                $pErr = true;
                $passwordErr='* Password missmatch ';
            }
        }else{
            echo "sql wrong</br>".mysqli_error($conn)."</br>";
        }
    }
}
?>

<html>
<head>
    <title>home page</title>
    <link rel="stylesheet" href="assests/library/bootstrap-3.3.7-dist/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="assests/library/bootstrap-3.3.7-dist/css/bootstrap-theme.min.css">
    <style type='text/css'>
        .wrapper {
            margin-top: 80px;
            margin-bottom: 20px;
        }

        .form-signin {
            max-width: 420px;
            padding: 30px 38px 66px;
            margin: 0 auto;
            background-color: #eee;
            border: 3px dotted rgba(0,0,0,0.1);
        }

        .form-signin-heading {
            text-align:center;
            margin-bottom: 30px;
        }

        .form-control {
            position: relative;
            font-size: 16px;
            height: auto;
            padding: 10px;
        }

        input[type="text"] {
            margin-bottom: 0px;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        input[type="password"] {
            margin-bottom: 20px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }

        .colorgraph {
            height: 7px;
            border-top: 0;
            background: #c4e17f;
            border-radius: 5px;
            background-image: -webkit-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
            background-image: -moz-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
            background-image: -o-linear-gradient(left, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
            background-image: linear-gradient(to right, #c4e17f, #c4e17f 12.5%, #f7fdca 12.5%, #f7fdca 25%, #fecf71 25%, #fecf71 37.5%, #f0776c 37.5%, #f0776c 50%, #db9dbe 50%, #db9dbe 62.5%, #c49cde 62.5%, #c49cde 75%, #669ae1 75%, #669ae1 87.5%, #62c2e4 87.5%, #62c2e4);
        }

        Username:-webkit-input-placeholder {
            color:    #999;
        }
        Username:-moz-placeholder {
            color:    #999;
        }
        Username:-ms-input-placeholder {
            color:    #999;
        }
    </style>

</head>
<body>
<div class = "container">
    <div class="wrapper">
        <form action="login.php" method='POST' name="Login_Form" class="form-signin">
            <h3 class="form-signin-heading">Welcome Back! Please Sign In</h3>
            <hr class="colorgraph"><br>
            <input type="text" class="form-control" name="Username" placeholder="Username" required="" autofocus="" />
            <!--<label><span style = "color:red"><?php echo $nameErr;?></span></label>-->
            <?php
            if($nErr){
                ?><p style='color:red; text-align:center; font-size:15px'>    Invalid username</p><?php
            }else{
                ?><p></p><?php
            }?>

            <input type="password" class="form-control" name="Password" placeholder="Password" required=""/>
            <!--<label><span style = "color:red"><?php echo $passwordErr;?></span></label>-->
            <?php
            if($pErr){
                ?><p style='color:red; text-align:center; font-size:15px'>    Incorrect Password</p><?php
            }else{
                ?><p></p><?php
            }?>

            <button class="btn btn-lg btn-primary btn-block"  name="Submit" value="Login" type="Submit">Login</button>
        </form>
    </div>
</div>

<script src="assests/jquery.min.js"></script>
<script src="assests/library/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
</body>
</html>
