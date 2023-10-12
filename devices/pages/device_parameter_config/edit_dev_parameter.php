<?php
require "../../../assets/vendors/autoload.php";
use Firebase\JWT\JWT;
$status = '0';
$message = "";
include("../../config.php");

$chicagotime = date("Y-m-d H:i:s");
$temp = "";
if (($_POST['fSubmit'] == 1) && (!empty($_POST['edit_p_name']))) {

    $p_id = $_POST['edit_p_id'];
    $edit_p_name  = $_POST['edit_p_name'];

    $service_url = $rest_api_uri . "iot_parameter_config/edit_parameter.php";
    $curl = curl_init($service_url);
    $curl_post_data = array(
        'p_id' => $p_id,
        'edit_p_name' => $edit_p_name,
    );
    $secretkey = "SupportPassHTSSgmmi";
    $payload = array(
        "author" => "Saargummi to HTS",
        "exp" => time()+1000
    );
    try{
        $jwt = JWT::encode($payload, $secretkey , 'HS256');
    }catch (UnexpectedValueException $e) {
        echo $e->getMessage();
    }
    $headers = array(
        "Accept: application/json",
        "access-token: " . $jwt . '"',
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
    $curl_response = curl_exec($curl);
    if ($curl_response === false) {
        $info = curl_getinfo($curl);
        curl_close($curl);
        die('error occured during curl exec. Additioanl info: ' . var_export($info));
    }
    curl_close($curl);
    $decoded = json_decode($curl_response);
    if (isset($decoded->status) && $decoded->status == 'ERROR') {
        die('error occured: ' . $decoded->errormessage);
        $errors[] = "Iot Device Not Updated.";
        $message_stauts_class = 'alert-danger';
        $import_status_message = 'Iot Device Not Updated.';
    }
    $errors[] = "Iot Device Updated Successfully.";
    $message_stauts_class = 'alert-success';
    $import_status_message = 'Device Parameter Updated Successfully.';
    $_SESSION['import_status_message'] =  $import_status_message;
    $_SESSION['message_stauts_class'] = $message_stauts_class;
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Parameter</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="<?php echo $iotURL ?>/assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="<?php echo $iotURL ?>/assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="<?php echo $iotURL ?>/assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?php echo $iotURL ?>/assets/vendors/jvectormap/jquery-jvectormap.css">
    <!-- End plugin css for this page -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="<?php echo $iotURL ?>/assets/css/demo/style.css">
    <link rel="stylesheet" href="<?php echo $iotURL ?>/assets/css/customForm.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="<?php echo $iotURL ?>/assets/images/favicon.png"/>
</head>
<body>
<script src="<?php echo $iotURL ?>/assets/js/preloader.js"></script>
<div class="body-wrapper">
    <?php include('./../../partials/sidebar.html') ?>
    <div class="main-wrapper mdc-drawer-app-content">
        <?php
        $title = "Edit Parameter";
        include('./../../partials/navbar.html') ?>
        <div class="mdc-layout-grid">
            <form action="" method="" id="EditDeviceForm">
                <?php
                $p_id = $_GET['p_id'];

                $sql = "select * from iot_parameter where p_id = '$p_id' and is_deleted != 1";
                $res = mysqli_query($iot_db, $sql);
                $row = mysqli_fetch_array($res);
                $p_id  = $row['p_id'];
                $p_name  = $row['p_name'];
                ?>
                <div class="mdc-layout-grid">
                    <form action="" method="" id="device_settings">

                        <div class="mdc-layout-grid__inner form_bg">

                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                                <div class="mdc-text-field mdc-text-field--outlined">
                                    <input type="hidden" name="edit_p_id" id="edit_p_id" value="<?php echo $p_id; ?>">

                                    <input class="mdc-text-field__input" name="edit_p_name" id="edit_p_name" value="<?php echo $p_name; ?>">
                                    <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                        <div class="mdc-notched-outline__leading"></div>
                                        <div class="mdc-notched-outline__notch" style="">
                                            <label for="text-field-hero-input" class="mdc-floating-label" style=""></label>
                                        </div>
                                        <div class="mdc-notched-outline__trailing"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- Submit                -->
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                                <button type="submit" id="submit_btn" class="mdc-button mdc-button--raised">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
        </div>
    </div>
</div>
<script>
    var select = document.getElementById('cmbitems');
    var input = document.getElementById('txtprice');
    select.onchange = function() {
        input.value = select.value;
    }
</script>
<!-- plugins:js -->
<script src="<?php echo $iotURL ?>/assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<script src="<?php echo $iotURL ?>/assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
<script src="<?php echo $iotURL ?>/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="<?php echo $iotURL ?>/assets/js/material.js"></script>
<script src="<?php echo $iotURL ?>/assets/js/misc.js"></script>
<script>
    $( "#submit_btn" ).click(function (e){
        e.preventDefault();
        $(':input[type="button"]').prop('disabled', true);
        var data = $("#EditDeviceForm").serialize();
        $.ajax({
            type: 'POST',
            url: 'edit_dev_parameter.php',
            data: "fSubmit=1&" + data,
            success: function (data) {
                // window.location.href = window.location.href + "?aa=Line 1";
                window.location.replace("view_dev_parameter.php");
            }
        });
    });
</script>
</body>


