<?php
session_start();
//error_reporting(0);
include('includes/config.php');

if (isset($_POST['Submit'])) {
    $db = new mysqli("localhost", "root", '', "srms");
    $button_value = $_POST['Submit'];
    $roll_id = $_SESSION['rollid'];
    $time = date('Y-m-d H:i:s');




    
    $otp = $db->real_escape_string($_POST['otp']);

    
    $query = "SELECT * FROM `otp_table` WHERE `otp`='$otp' AND `expiration_date` >= NOW() AND `roll_id` = '$roll_id'";



    
    $result = $db->query($query);
    $row = mysqli_fetch_array($result);
    
    if ($row['otp']) {


        
        if (strtotime($row['expiration_date']) > time()) {
            
            echo '<script>alert("OTP Verified Sucessfully")</script>';
            echo "<script>window.location.href ='result.php'</script>";
            
            
            exit;
        } else {
            
            echo '<script>alert("The OTP has expired")</script>';
            echo "<script>window.location.href ='otp.php'</script>";
        }
    } else {
        
        echo '<script>alert("Invalid OTP!! Try Again!")</script>';
        echo "<script>window.location.href ='otp.php'</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Please Enter the OTP </title>
    <link rel="stylesheet" href="css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css" media="screen">
    <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen">
    <link rel="stylesheet" href="css/icheck/skins/flat/blue.css">
    <link rel="stylesheet" href="css/main.css" media="screen">
    <script src="js/modernizr/modernizr.min.js"></script>
</head>

<body class="">
    <div class="main-wrapper">

        <div class="login-bg-color bg-black-300">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel login-box">
                        <div class="panel-heading">
                            <div class="panel-title text-center">
                                <h4>Please enter the OTP</h4>
                            </div>
                        </div>
                        <div class="panel-body p-20">



                            <form action="otp.php" method="POST">
                                <div class="form-group">
                                    <label for="otp">Please enter the OTP</label>
                                    <input type="text" class="form-control" id="otp" name="otp" placeholder="XXXXXX" autocomplete="off" name="otp">
                                </div>

                                <div class="form-group mt-20">
                                    <div class="">
                                        <button name="Submit" value="Submit" type="submit" class="btn btn-success btn-labeled pull-right">Submit<span class="btn-label btn-label-right"><i class="fa fa-check"></i></span></button>
                                        <div class="clearfix"></div>
                                    </div>

                            </form>
                            <div class="col-sm-6">
                                <a href="index.php">Back to Home</a>
                            </div>