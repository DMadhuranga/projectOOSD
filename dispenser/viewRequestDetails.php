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
if ($user->getRoleId()!=1){
    header('location:../logout.php');
}

?>


<html xmlns="http://www.w3.org/1999/html">
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
                    <!--<h1 class="well">Add Drugs</h1>-->
                    <div class="col-lg-12 well">
                        <div class="row">

                            <div class="col-md-10" style="text-align: center">
                                <div class ="row">
                                    <?php
                                    $request_id=$_GET['id'];
                                    $sender=$_GET['name'];
                                    $query="SELECT * FROM hospital.requests WHERE request_id='$request_id'";
                                    $res=mysqli_query($conn,$query);
                                    $row=mysqli_fetch_array($res,MYSQLI_ASSOC);
                                    $date=$row['date'];
                                    $state=$row['state'];
                                    $description=$row['description'];





                                    ?>
                                    <h3 class="display-4 display-4-2" ><?php echo $description;?></h3>
                                    <!--<label > Drug Cart</label>-->
                                </div>
                            </div>
                            <!--<div class="col-md-2">
                                <div class="row">
                                    <button id="sub" onclick="submitForm();"  type="button" class="btn btn-lg btn-info" name="addb" disabled="">Add Arrival</button>
                                </div>
                            </div>-->

                        </div>
                    </div>
                        <div class="col-lg-12 well">

                            <div class="col-lg-12" style="font-size: medium; text-align: left; line-height: 200%">


                                <b><?php echo 'Date : '?></b>
                                <?php echo $date.'<br>';?>
                                <b><?php echo 'Sender : '?></b>
                                <?php echo $sender.'<br>';?>
                                <b><?php echo 'Status : '?></b>
                                <?php if ($state==4){
                                    echo "Accepted";
                                }
                                elseif ($state==1){
                                    echo "Dugs Issued";
                                }

                                ?>

                            </div>

                            <div class="col-lg-10" style="margin-top:20px" >
                                <table class="table table-bordered">

                                    <thead>
                                    <tr>

                                        <th colspan="2">Drug</th>
                                        <th colspan="2">Amount</th>


                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $queryDrug="SELECT serial_number,amount FROM hospital.request_details WHERE request_id='$request_id'";
                                    $resDrug=mysqli_query($conn,$queryDrug);
                                    $serials=array();
                                    while ($rowDrug=mysqli_fetch_array($resDrug,MYSQLI_ASSOC)){
                                        array_push($serials,array($rowDrug['serial_number'],$rowDrug['amount']));
                                    }
                                    foreach ($serials as $serial_num){
                                        $queryName="SELECT drug_name FROM hospital.drugs WHERE serial_number='$serial_num[0]'";
                                        $resName=mysqli_query($conn,$queryName);
                                        $rowName=mysqli_fetch_array($resName,MYSQLI_ASSOC); ?>
                                        <tr>

                                            <td colspan="2"><?php echo $rowName['drug_name'];?></td>
                                            <td colspan="2"><?php echo $serial_num[1]?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody></table>
                            </div>

                <!-- Put Anything-->
            </div>

                    <div class="col-sm-10 form-group">
                        <div class="form-group" >
                            <div class="col-md-4"></div>


                                <button id="addb"  type="button" class="btn btn-lg btn-info" onclick="location.href='viewAllRequests.php'" ><span class="glyphicon glyphicon-arrow-left"></span> Back</button>
                                <div class="col-md-1">
                                    <button id="addb"  type="button" class="btn btn-lg btn-success" onclick="location.href='home.php'" ><span class="glyphicon glyphicon-home"></span> Home</button>
                                </div>
                            <div class="col-md-3"></div>

                    </div>
        </div>
    </div>
</div>
</body>
</html>


<?php function expiry(){?>
    <b><?php echo 'Date :';?></b>
    <?php echo $_REQUEST['date'].'<br>';?>
    <?php $message=$_REQUEST['msg'];
    $pieces=explode("drug ",$message);
    $pieces=explode(" (serial number",$pieces[1]);
    $drug_name=$pieces[0];
    $pieces=explode(") ",$pieces[1]);
    $serial=$pieces[0];
    $pieces=explode("batch no. ",$pieces[1]);
    $pieces=explode(" will expire",$pieces[1]);
    $batch=$pieces[0];
    $pieces=explode(" in ",$pieces[1]);
    $days=$pieces[1];?>
    <b><?php echo "Drug Name : "?></b>
    <?php echo $drug_name.'<br>';?>
    <b><?php echo "Serial Number : "?></b>
    <?php echo $serial.'<br>';?>
    <b><?php echo "Batch Number : "?></b>
    <?php echo $batch.'<br>';?>
    <b><?php echo "Days to Expire : "?></b>
    <?php echo $days.'<br>';
    $conn = mysqli_connect('localhost', 'root', '1010');
    $query="SELECT inventory_balance FROM hospital.drug_batches WHERE batch_number='$batch'";
    $res=mysqli_query($conn,$query);
    $row=mysqli_fetch_array($res,MYSQLI_ASSOC);
    $balance=$row['inventory_balance']
    ?>
    <b><?php echo "Balance : "?></b>
    <?php echo $balance.'<br>';

}?>
<?php function stockLow(){?>
    <b><?php echo 'Date :';?></b>
    <?php echo $_REQUEST['date'].'<br>';?>
    <?php $message=$_REQUEST['msg'];
    $pieces=explode("drug ",$message);
    $pieces=explode(" (serial number",$pieces[1]);
    $drug_name=$pieces[0];
    $pieces=explode(") ",$pieces[1]);
    $serial=$pieces[0];
    $pieces=explode("amount: ",$pieces[1]);
    $balance=$pieces[1];?>
    <b><?php echo "Drug Name : "?></b>
    <?php echo $drug_name.'<br>';?>
    <b><?php echo "Serial Number : "?></b>
    <?php echo $serial.'<br>';?>
    <b><?php echo "Balance : "?></b>
    <?php echo $balance.'<br>';

}
/**
 * other notifications
 */