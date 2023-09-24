<?php
$message = "";
include("config.php");
$chicagotime = date("Y-m-d H:i:s");
if (count($_POST) > 0) {
$is_error = 0;
$result = "SELECT * FROM iot_users WHERE cust_name='" . $_POST["user"] . "' and cust_password = '" . (md5($_POST["pass"])) . "'";
$q = mysqli_query($iot_db,$result);
$row = mysqli_fetch_array($q);
if (is_array($row)) {
$_SESSION["id"] = $row['cust_id'];
$_SESSION["user"] = $row['cust_name'];
$_SESSION["name"] = $row['cust_name'];
$_SESSION["email"] = $row['cust_email'];
$_SESSION["uu_img"] = $row['cust_profile_pic'];
$_SESSION["role_id"] = $row['role'];
$logid = $row['cust_id'];
$_SESSION["fullname"] = $row['cust_fistname'] . "&nbsp;" . $row['cust_lastname'];
$_SESSION["pin"] = $row['pin'];
$_SESSION["pin_flag"] = $row['pin_flag'];
$pin = $row['pin'];
$pin_flag = $row['pin_flag'];
// mysqli_query($sup_db, "INSERT INTO `sup_session_log`(`u_id`,`created_at`) VALUES ('$logid','$chicagotime')");
} else {
$result = mysqli_query($iot_db, "SELECT * FROM iot_users WHERE status = '0' AND cust_name='" . $_POST["user"] . "' and cust_password = '" . (md5($_POST["pass"])) . "'");
$row = mysqli_fetch_array($result);
if(is_array($row)) {
$message_stauts_class = $_SESSION["alert_danger_class"];
$import_status_message = $_SESSION["error_6"];
$is_error = 1;
} else {
$message_stauts_class = $_SESSION["alert_danger_class"];
$import_status_message = $_SESSION["error_1"];
$is_error = 1;
}
}
if ($is_error == 0) {
header("Location:home.php");
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Design by foolishdeveloper.com -->
    <title>IOT</title>
    <!--Stylesheet-->
    <style media="screen">
        *,
        *:before,
        *:after{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        body{
            background-color: #03194d;
            /*background-color: #554daf;*/
        }
        .background{
            width: 430px;
            height: 520px;
            position: absolute;
            transform: translate(-50%,-50%);
            left: 50%;
            top: 50%;
        }
        .background .shape{
            height: 200px;
            width: 200px;
            position: absolute;
            border-radius: 50%;
        }
        .shape:first-child{
            background: linear-gradient(
                    #1845ad,
                    #23a2f6
            );
            left: -80px;
            top: -80px;
        }
        .shape:last-child{
            background: linear-gradient(
                    to right,
                    #ff512f,
                    #f09819
            );
            right: -30px;
            bottom: -80px;
        }
        form{
            height: 520px;
            width: 400px;
            background-color: rgba(255,255,255,0.13);
            position: absolute;
            transform: translate(-50%,-50%);
            top: 50%;
            left: 50%;
            border-radius: 10px;
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255,255,255,0.1);
            box-shadow: 0 0 40px rgba(8,7,16,0.6);
            padding: 30px 35px;
        }
        form *{
            font-family: 'Poppins',sans-serif;
            color: #ffffff;
            letter-spacing: 0.5px;
            outline: none;
            border: none;
        }
        form h3{
            font-size: 28px;
            font-weight: 500;
            line-height: 32px;
            text-align: center;
        }

        form h4{
            font-size: 16px;
            font-weight: 500;
            line-height: 87px;
            text-align: center;

        }

        label{
            display: block;
            margin-top: 25px;
            font-size: 16px;
            font-weight: 500;
        }
        input{
            display: block;
            height: 40px;
            width: 100%;
            background-color: rgba(255,255,255,0.07);
            border-radius: 3px;
            padding: 0 10px;
            margin-top: 8px;
            font-size: 14px;
            font-weight: 300;
        }
        ::placeholder{
            color: #e5e5e5;
        }
        button{
            margin-top: 20px;
            width: 100%;
            background-color: #ffffff;
            color: #080710;
            padding: 15px 0;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
        }
        .social{
            margin-top: 20px;
            display: flex;
            font-size: small;
            /*text-align: center;*/
            /*align-items: center;*/
        }
        .social div{
            /*background: red;*/
            /*width: 150px;*/
            border-radius: 3px;
            padding: 5px 10px 10px 0px;
            /*background-color: rgba(255,255,255,0.27);*/
            color: #eaf0fb;
            /*text-align: center;*/
            /*min-height: 50px;*/
        }
        .social div:hover{
            background-color: rgba(255,255,255,0.47);
        }
        .social .fb{
            margin-left: 25px;
        }
        .social i{
            margin-right: 4px;
        }
        .logo{
            text-align: center;
            margin-bottom: 20px;
        }
        .input-icon {
            position: absolute!important;
            top: 78px!important;
            color: #23a2f6!important;
            margin-left: 302px!important;
            margin-top: 209px;
        }

    </style>
    <script type="text/javascript">
        function disablebackbutton(){
            window.history.forward();
        }
        disablebackbutton();
    </script>

</head>
<body>
<div class="background">
    <div class="shape"></div>
    <div class="shape"></div>
</div>
<form method="post">
    <div class="logo">
        <img class="user" src="./assets/images/site_logo.png"  width="120px">
    </div>
    <?php
    if (!empty($import_status_message)) {
        echo '<div class="alert ' . $message_stauts_class . '">' . $import_status_message . '</div>';
    }
    ?>
    <h3>Login Here</h3>
    <label for="username">Username</label>
    <input type="text" placeholder="Email or Phone" name="user" id="user" required="required">

    <label for="password">Password</label>
    <input type="password" placeholder="Password" id="pass"  name="pass">
    <span class="input-icon" onclick="myFunction()" style="cursor: pointer;float: right;"><i class="fa fa-eye" aria-hidden="true"></i></span>

    <button type="submit" id="signin">Log In</button>

    <h4><a href="forgotpass.php">Forgot Password?</a></h4>

</form>
<script>

    function myFunction() {
        var x = document.getElementById("pass");
        if (x.type === "password") {
            x.type = "text";

        } else {
            x.type = "password";
        }
    }

</script>
</body>
</html>
