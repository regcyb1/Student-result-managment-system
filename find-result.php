<?php
session_start();
//error_reporting(0);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require 'includes/PHPMailer/src/Exception.php';
require 'includes/PHPMailer/src/PHPMailer.php';
require 'includes/PHPMailer/src/SMTP.php';

include('includes/config.php');



if (isset($_POST['rollid'])) {

    $rollid = $_POST['rollid'];
    $button_value = $_POST['rollid'];
    $_SESSION['rollid'] = $rollid;

    $sql = 'SELECT * FROM `tblstudents` WHERE `RollId` = :rollid';
    $stmt = $dbh->prepare($sql);
    $stmt->execute(array(':rollid' => $rollid));
    $results = $stmt->fetchAll(PDO::FETCH_OBJ);
    if ($stmt->rowCount() == 0) {
        echo '<script>alert("No Student with Given Roll Number Found")</script>';
        echo "<script>window.location.href ='find-result.php'</script>";
    }

    $otp = str_pad(mt_rand(0, 999999), 6, "0", STR_PAD_LEFT);

    $expirationDate = date('Y-m-d H:i:s', strtotime('+1 hour'));


    $sql = "INSERT INTO otp_table(otp,roll_id,expiration_date) VALUES (:otp,:rollid,:expiration_date)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':otp', $otp, PDO::PARAM_STR);
    $query->bindParam(':rollid', $rollid, PDO::PARAM_STR);
    $query->bindParam(':expiration_date', $expirationDate, PDO::PARAM_STR);
    $query->execute();



    try {

        $phpmailer = new PHPMailer();
        $phpmailer->isSMTP();
        $phpmailer->Host = 'smtp.mailtrap.io';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 2525;
        $phpmailer->Username = 'ad9ca6854d03af';
        $phpmailer->Password = '0e0ff3efc78d96';
        //Recipients
        $phpmailer->setFrom('regmee6411@gmail.com', 'Sudeep');
        $phpmailer->addAddress($results[0]->StudentEmail, $results[0]->StudentName);     //Add a recipient


        //Content
        $phpmailer->isHTML(true);                                  //Set email format to HTML
        $phpmailer->Subject = 'OTP';
        $phpmailer->Body    = 'Dear Student,<br>Here is the OTP for viewing your result:<br><strong>OTP:</strong>' . $otp . '<br>Regards,<br>Sudeep';

        $phpmailer->send();

        echo '<script>alert("Please Check Your Email For OTP.")</script>';
        echo "<script>window.location.href ='otp.php'</script>";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}";
    }
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Result Management System</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/icheck/skins/flat/blue.css">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <script src="js/modernizr/modernizr.min.js"></script>
</head>

<body class="">
    <div class="main-wrapper">
    <style>
            body {
                background-image: url('images/back3.jpg');
                background-size: cover;
            }
        </style>

        <div class="login-bg-color bg-black-300">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel login-box">
                        <div class="panel-heading">
                            <div class="panel-title text-center">
                                <h4>Result Management System</h4>
                            </div>
                        </div>
                        <div class="panel-body p-20">



                            <form action="find-result.php" method="post">
                                <div class="form-group">
                                    <label for="rollid">Enter your Roll Id</label>
                                    <input type="text" class="form-control" id="rollid" placeholder="Enter Your Roll Id" autocomplete="off" name="rollid">


                                    <!-- <div class="form-group">
                                        <label for="default" class="col-sm-2 control-label">Class</label>
                                        <select name="class" class="form-control" id="default" required="required">
                                            <option value="">Select Class</option>
                                            <?php $sql = "SELECT * from tblclasses";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $result) {   ?>
                                                    <option value="<?php echo htmlentities($result->id); ?>"><?php echo htmlentities($result->ClassName); ?>&nbsp; Section-<?php echo htmlentities($result->Section); ?></option>
                                            <?php }
                                            }
                                            ?>
                                        </select>
                                    </div> -->



                                    <div class="form-group mt-20">
                                        <div class="">
                                            <button value="Submit" type="submit" class="btn btn-success btn-labeled pull-right">Send OTP<span class="btn-label btn-label-right"><i class="fa fa-check"></i></span></button>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <a href="index.php">Back to Home</a>
                                    </div>
                            </form>

                            <hr>

                        </div>
                    </div>
                    <!-- /.panel -->
                    <p class="text-muted text-center"><small>Result Management System</small></p>
                </div>
                <!-- /.col-md-6 col-md-offset-3 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /. -->

    </div>
    <!-- /.main-wrapper -->

    <!-- ========== COMMON JS FILES ========== -->
    <script src="js/jquery/jquery-2.2.4.min.js"></script>
    <script src="js/jquery-ui/jquery-ui.min.js"></script>
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/pace/pace.min.js"></script>
    <script src="js/lobipanel/lobipanel.min.js"></script>
    <script src="js/iscroll/iscroll.js"></script>

    <!-- ========== PAGE JS FILES ========== -->
    <script src="js/icheck/icheck.min.js"></script>

    <!-- ========== THEME JS ========== -->
    <script src="js/main.js"></script>
    <script>
        $(function() {
            $('input.flat-blue-style').iCheck({
                checkboxClass: 'icheckbox_flat-blue'
            });
        });
    </script>

    <!-- ========== ADD custom.js FILE BELOW WITH YOUR CHANGES ========== -->
</body>

</html>