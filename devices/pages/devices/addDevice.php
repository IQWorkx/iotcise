<?php require "../../../assets/vendors/autoload.php";
use Firebase\JWT\JWT;
$status = '0';
$message = "";
include("../../config.php");
//include("../sup_config.php");
$chicagotime = date("Y-m-d H:i:s");
$temp = "";
$user_id = $_SESSION["id"];

$device_id = $_GET['device_id'];

if (($_POST['fSubmit'] == 1) && (!empty($_POST['add_dev_id']))) {
    $c_id = $_POST["select_customer"];
    $device_id = $_POST["add_dev_id"];
    $device_name = $_POST["add_dev_name"];
    $device_desc = $_POST["add_dev_desc"];
    $type_id = $_POST["select_dev_type"];
    $device_loc = $_POST["add_dev_location"];
    $temperature_upp_tolerance = $_POST["temp_upper_tolerance"];
    $temperature_low_tolerance = $_POST["temp_lower_tolerance"];

    $humidity_upp_tolerance = $_POST["hum_upper_tolerance"];
    $humidity_low_tolerance = $_POST["hum_lower_tolerance"];

    $pressure_upp_tolerance = $_POST["pressure_upper_tolerance"];
    $pressure_low_tolerance = $_POST["pressure_lower_tolerance"];

    $iaq_upp_tolerance = $_POST["iaq_upper_tolerance"];
    $iaq_low_tolerance = $_POST["iaq_lower_tolerance"];

    $voc_upp_tolerance = $_POST["voc_upper_tolerance"];
    $voc_low_tolerance = $_POST["voc_lower_tolerance"];

    $co2_upp_tolerance = $_POST["co2_upper_tolerance"];
    $co2_low_tolerance = $_POST["co2_lower_tolerance"];

    $temperature_enabled = $_POST['temperature_enabled'];
    if($temperature_enabled == '1'){
        $temp = '1';
    }else{
        $temp = '0';
    }
    $humidity_enabled = $_POST['humidity_enabled'];
    if($humidity_enabled == '1'){
        $hum = '1';
    }else{
        $hum = '0';
    }
    $pressure_enabled = $_POST['pressure_enabled'];
    if($pressure_enabled == '1'){
        $pres = '1';
    }else{
        $pres = '0';
    }
    $voc_enabled = $_POST['voc_enabled'];
    if($voc_enabled == '1'){
        $voc1 = '1';
    }else{
        $voc1 = '0';
    }
    $iaq_enabled = $_POST['iaq_enabled'];
    if($iaq == '1'){
        $iaq1 = '1';
    }else{
        $iaq1 = '0';
    }
    $co2_enabled = $_POST['co2_enabled'];
    if($co2_enabled == '1'){
        $co = '1';
    }else{
        $co = '0';
    }

    $is_active = 1;
    $service_url = $rest_api_uri . "devices/iot_device.php";
    $curl = curl_init($service_url);
    $curl_post_data = array(
        'c_id' => $c_id,
        'device_id' => $device_id,
        'device_name' => $device_name,
        'device_description' => $device_desc,
        'type_id' => $type_id,
        'device_location' => $device_loc,
        'temperature_upp_tolerance' => $temperature_upp_tolerance,
        'temperature_low_tolerance' => $temperature_low_tolerance,
        'humidity_upp_tolerance' => $humidity_upp_tolerance,
        'humidity_low_tolerance' => $humidity_low_tolerance,
        'pressure_upp_tolerance' => $pressure_upp_tolerance,
        'pressure_low_tolerance' => $pressure_low_tolerance,
        'iaq_upp_tolerance' => $iaq_upp_tolerance,
        'iaq_low_tolerance' => $iaq_low_tolerance,
        'voc_upp_tolerance' => $voc_upp_tolerance,
        'voc_low_tolerance' => $voc_low_tolerance,
        'co2_upp_tolerance' => $co2_upp_tolerance,
        'co2_low_tolerance' => $co2_low_tolerance,
        'temperature_enabled' => $temp,
        'humidity_enabled' => $hum,
        'pressure_enabled' => $pres,
        'iaq_enabled' => $iaq1,
        'voc_enabled' => $voc1,
        'co2_enabled' => $co,
        'is_active' => $is_active,
        'created_by' => $user_id,
        'created_on' => $chicagotime
    );
    $secretkey = "SupportPassHTSSgmmi";
    $payload = array(
        "author" => "Saargummi to HTS",
        "exp" => time() + 1000
    );
    try {
        $jwt = JWT::encode($payload, $secretkey, 'HS256');
    } catch (UnexpectedValueException $e) {
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
        $_SESSION["alert_danger_class"] = 'Device Not Added Please Try Again!..';
        $_SESSION["error_1"] = 'Device Not Added Please Try Again!..';
        $message_stauts_class = $_SESSION["alert_danger_class"];
        $import_status_message = $_SESSION["error_1"];
        header("Location:addDevice.php");
        exit();
    } else {
        $_SESSION["alert_success_class"] = 'Device created Successfully';
        $_SESSION["error_2"] = 'Device created Successfully';
        $message_stauts_class = $_SESSION["alert_success_class"];
        $import_status_message = $_SESSION["error_2"];
        header("Location:view_devices.php");
        exit();
    }
}

//Set the session duration for 10800 seconds - 3 hours
$duration = auto_logout_duration;
//Read the request time of the user
$time = $_SERVER['REQUEST_TIME'];
//Check the user's session exist or not
if (isset($_SESSION['LAST_ACTIVITY']) && ($time - $_SESSION['LAST_ACTIVITY']) > $duration) {
    //Unset the session variables
    session_unset();
    //Destroy the session
    session_destroy();
    header('location:index.php');
    exit;
}
//Set the time of the user's last activity
$_SESSION['LAST_ACTIVITY'] = $time;
$i = $_SESSION["role_id"];

$assign_by = $_SESSION["id"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IOT Add Device</title>
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
        $title = "Add an IOT Device";
        include('./../../partials/navbar.html') ?>
        <div class="mdc-layout-grid">
            <form action="" method="" id="addDeviceForm">

                <div class="mdc-layout-grid__inner form_bg">
                    <!--     Device type and Customer           -->
                    <div style="margin-top: 10px;" class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input class="mdc-text-field__input" id="add_dev_id" name="add_dev_id" required>
                            <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                <div class="mdc-notched-outline__leading"></div>
                                <div class="mdc-notched-outline__notch" style="">
                                    <label for="text-field-hero-input" class="mdc-floating-label" style="">Device ID</label>
                                </div>
                                <div class="mdc-notched-outline__trailing"></div>
                            </div>
                        </div>
                    </div>
                    <div class="w100 mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">
                        <div class="w100 mdc-layout-grid__cell--span-4 mdc-layout-grid__cell--span-6-desktop stretch-card">
                            <div class="w100 template-demo">
                                <div class="w100 mdc-select demo-width-class" data-mdc-auto-init="MDCSelect">
                                    <input type="hidden" name="select_dev_type">
                                    <i class="mdc-select__dropdown-icon"></i>
                                    <div class="mdc-select__selected-text"></div>
                                    <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                                        <ul class="mdc-list">
                                            <?php
                                            $st_dashboard1 = $_POST['dev_type'];
                                            $sql1 = "SELECT * FROM `iot_device_type` where is_deleted != 1";
                                            $result1 = mysqli_query($iot_db,$sql1);
                                            while ($row1 = $result1->fetch_assoc()) {
                                                if($st_dashboard1 == $row1['type_id'])
                                                {
                                                    $entry = 'selected';
                                                }
                                                else
                                                {
                                                    $entry = '';

                                                }
                                                ?>
                                                <li class="mdc-list-item" data-value="<?php  echo $row1['type_id']; ?>">
                                                    <?php  echo $row1['dev_type_name']; ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <span class="mdc-floating-label">Select Device Type *</span>
                                    <div class="mdc-line-ripple"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w100 mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">
                        <div class="w100 mdc-layout-grid__cell--span-4 mdc-layout-grid__cell--span-6-desktop stretch-card">
                            <div class="w100 template-demo">
                                <div class="w100 mdc-select demo-width-class" data-mdc-auto-init="MDCSelect">
                                    <input type="hidden" name="select_customer">
                                    <i class="mdc-select__dropdown-icon"></i>
                                    <div class="mdc-select__selected-text"></div>
                                    <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                                        <ul class="mdc-list">
                                            <?php
                                            $st_dashboard = $_POST['customer'];
                                            $sql1 = "SELECT * FROM `cus_account` where is_deleted != 1";
                                            $result1 = mysqli_query($db,$sql1);
                                            while ($row1 = $result1->fetch_assoc()) {
                                                if($st_dashboard == $row1['c_id'])
                                                {
                                                    $entry = 'selected';
                                                }
                                                else
                                                {
                                                    $entry = '';
                                                }
                                                ?>
                                                <li class="mdc-list-item" data-value="<?php  echo $row1['c_id']; ?>">
                                                    <?php  echo $row1['c_name']; ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <span class="mdc-floating-label">Select Customer</span>
                                    <div class="mdc-line-ripple"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input class="mdc-text-field__input" id="add_dev_name"  name="add_dev_name" required>
                            <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                <div class="mdc-notched-outline__leading"></div>
                                <div class="mdc-notched-outline__notch" style="">
                                    <label for="text-field-hero-input" class="mdc-floating-label" style="">Device Name</label>
                                </div>
                                <div class="mdc-notched-outline__trailing"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input class="mdc-text-field__input" id="add_dev_location" name="add_dev_location">
                            <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                <div class="mdc-notched-outline__leading"></div>
                                <div class="mdc-notched-outline__notch" style="">
                                    <label for="text-field-hero-input" class="mdc-floating-label" style="">Device Location</label>
                                </div>
                                <div class="mdc-notched-outline__trailing"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input class="mdc-text-field__input" id="add_dev_desc"  name="add_dev_desc">
                            <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                <div class="mdc-notched-outline__leading"></div>
                                <div class="mdc-notched-outline__notch" style="">
                                    <label for="text-field-hero-input" class="mdc-floating-label" style="">Device Description</label>
                                </div>
                                <div class="mdc-notched-outline__trailing"></div>
                            </div>
                        </div>
                    </div>

                    <!--       Temperature Tolerance         -->
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="temperature_enabled"
                                       name="temperature_enabled"
                                       value="1"
                                       class="mdc-checkbox__native-control"
                                       checked/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">Enable</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="check_temp_email_alert"
                                       name="check_temp_email_alert"
                                       class="mdc-checkbox__native-control"
                                       checked/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">eMail Alert</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field fm-item-disabled">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="check_temp_sms_alert"
                                       name="check_temp_sms_alert"
                                       class="mdc-checkbox__native-control"
                                       disabled/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">SMS Alert</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input class="mdc-text-field__input" id="temp_upper_tolerance" name="temp_upper_tolerance">
                            <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                <div class="mdc-notched-outline__leading"></div>
                                <div class="mdc-notched-outline__notch" style="">
                                    <label for="text-field-hero-input" class="mdc-floating-label" style="">Temperature Upper Tolerance</label>
                                </div>
                                <div class="mdc-notched-outline__trailing"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input class="mdc-text-field__input" id="temp_lower_tolerance" name="temp_lower_tolerance">
                            <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                <div class="mdc-notched-outline__leading"></div>
                                <div class="mdc-notched-outline__notch" style="">
                                    <label for="text-field-hero-input" class="mdc-floating-label" style="">Temperature Lower Tolerance</label>
                                </div>
                                <div class="mdc-notched-outline__trailing"></div>
                            </div>
                        </div>
                    </div>
                    <!--       Humidity Tolerance         -->
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="humidity_enabled"
                                       name="humidity_enabled"
                                       value="1"
                                       class="mdc-checkbox__native-control"
                                       checked/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">Enable</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="check_hum_email_alert"
                                       name="check_hum_email_alert"
                                       class="mdc-checkbox__native-control"
                                       checked/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">eMail Alert</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field fm-item-disabled">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="check_hum_sms_alert"
                                       name="check_hum_sms_alert"
                                       class="mdc-checkbox__native-control"
                                       disabled/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">SMS Alert</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input class="mdc-text-field__input" id="hum_upper_tolerance" name="hum_upper_tolerance">
                            <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                <div class="mdc-notched-outline__leading"></div>
                                <div class="mdc-notched-outline__notch" style="">
                                    <label for="text-field-hero-input" class="mdc-floating-label" style="">Humidity Upper Tolerance</label>
                                </div>
                                <div class="mdc-notched-outline__trailing"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input class="mdc-text-field__input" id="hum_lower_tolerance" name="hum_lower_tolerance">
                            <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                <div class="mdc-notched-outline__leading"></div>
                                <div class="mdc-notched-outline__notch" style="">
                                    <label for="text-field-hero-input" class="mdc-floating-label" style="">Humidity Lower Tolerance</label>
                                </div>
                                <div class="mdc-notched-outline__trailing"></div>
                            </div>
                        </div>
                    </div>
                    <!--       Pressure Tolerance         -->
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="pressure_enabled"
                                       name="pressure_enabled"
                                       value="1"
                                       class="mdc-checkbox__native-control"
                                       checked/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">Enable</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="pressure_email_tolerance_alert"
                                       name="pressure_email_tolerance_alert"
                                       class="mdc-checkbox__native-control"
                                       checked/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">eMail Alert</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field fm-item-disabled">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="pressure_sms_tolerance_alert"
                                       name="pressure_sms_tolerance_alert"
                                       class="mdc-checkbox__native-control"
                                       disabled/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">SMS Alert</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input class="mdc-text-field__input" id="pressure_upper_tolerance" name="pressure_upper_tolerance">
                            <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                <div class="mdc-notched-outline__leading"></div>
                                <div class="mdc-notched-outline__notch" style="">
                                    <label for="text-field-hero-input" class="mdc-floating-label" style="">Pressure Upper Tolerance</label>
                                </div>
                                <div class="mdc-notched-outline__trailing"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input class="mdc-text-field__input" id="pressure_lower_tolerance" name="pressure_lower_tolerance">
                            <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                <div class="mdc-notched-outline__leading"></div>
                                <div class="mdc-notched-outline__notch" style="">
                                    <label for="text-field-hero-input" class="mdc-floating-label" style="">Pressure Lower Tolerance</label>
                                </div>
                                <div class="mdc-notched-outline__trailing"></div>
                            </div>
                        </div>
                    </div>
                    <!--       IAQ Tolerance         -->
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="iaq_enabled"
                                       name="iaq_enabled"
                                       value="1"
                                       class="mdc-checkbox__native-control"
                                       checked/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">Enable</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="iaq_email_tolerance_alert"
                                       name="iaq_email_tolerance_alert"
                                       class="mdc-checkbox__native-control"
                                       checked/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">eMail Alert</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field fm-item-disabled">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="iaq_sms_tolerance_alert"
                                       name="iaq_sms_tolerance_alert"
                                       class="mdc-checkbox__native-control"
                                       disabled/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">SMS Alert</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input class="mdc-text-field__input" id="iaq_upper_tolerance" name="iaq_upper_tolerance">
                            <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                <div class="mdc-notched-outline__leading"></div>
                                <div class="mdc-notched-outline__notch" style="">
                                    <label for="text-field-hero-input" class="mdc-floating-label" style="">IAQ Upper Tolerance</label>
                                </div>
                                <div class="mdc-notched-outline__trailing"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input class="mdc-text-field__input" id="iaq_lower_tolerance" name="iaq_lower_tolerance">
                            <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                <div class="mdc-notched-outline__leading"></div>
                                <div class="mdc-notched-outline__notch" style="">
                                    <label for="text-field-hero-input" class="mdc-floating-label" style="">IAQ Lower Tolerance</label>
                                </div>
                                <div class="mdc-notched-outline__trailing"></div>
                            </div>
                        </div>
                    </div>
                    <!--       VOC Tolerance         -->
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="voc_enabled"
                                       name="voc_enabled"
                                       value="1"
                                       class="mdc-checkbox__native-control"
                                       checked/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">Enable</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="voc_email_tolerance_alert"
                                       name="voc_email_tolerance_alert"
                                       class="mdc-checkbox__native-control"
                                       checked/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">eMail Alert</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field fm-item-disabled">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="voc_sms_tolerance_alert"
                                       name="voc_sms_tolerance_alert"
                                       class="mdc-checkbox__native-control"
                                       disabled/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">SMS Alert</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input class="mdc-text-field__input" id="voc_upper_tolerance" name="voc_upper_tolerance">
                            <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                <div class="mdc-notched-outline__leading"></div>
                                <div class="mdc-notched-outline__notch" style="">
                                    <label for="text-field-hero-input" class="mdc-floating-label" style="">VOC Upper Tolerance</label>
                                </div>
                                <div class="mdc-notched-outline__trailing"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input class="mdc-text-field__input" id="voc_lower_tolerance" name="voc_lower_tolerance">
                            <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                <div class="mdc-notched-outline__leading"></div>
                                <div class="mdc-notched-outline__notch" style="">
                                    <label for="text-field-hero-input" class="mdc-floating-label" style="">VOC Lower Tolerance</label>
                                </div>
                                <div class="mdc-notched-outline__trailing"></div>
                            </div>
                        </div>
                    </div>
                    <!--       CO2 Tolerance         -->
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="co2_enabled"
                                       name="co2_enabled"
                                       value="1"
                                       class="mdc-checkbox__native-control"
                                       checked/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">Enable</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="co2_email_tolerance_alert"
                                       name="co2_email_tolerance_alert"
                                       class="mdc-checkbox__native-control"
                                       checked/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">eMail Alert</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                        <div class="mdc-form-field fm-item-disabled">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="co2_sms_tolerance_alert"
                                       name="co2_sms_tolerance_alert"
                                       class="mdc-checkbox__native-control"
                                       disabled/>
                                <div class="mdc-checkbox__background">
                                    <svg class="mdc-checkbox__checkmark"
                                         viewBox="0 0 24 24">
                                        <path class="mdc-checkbox__checkmark-path"
                                              fill="none"
                                              d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                    </svg>
                                    <div class="mdc-checkbox__mixedmark"></div>
                                </div>
                            </div>
                            <label for="basic-disabled-checkbox" id="basic-disabled-checkbox-label">SMS Alert</label>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input class="mdc-text-field__input" id="co2_upper_tolerance" name="co2_upper_tolerance">
                            <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                <div class="mdc-notched-outline__leading"></div>
                                <div class="mdc-notched-outline__notch" style="">
                                    <label for="text-field-hero-input" class="mdc-floating-label" style="">CO2 Upper Tolerance</label>
                                </div>
                                <div class="mdc-notched-outline__trailing"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input class="mdc-text-field__input" id="co2_lower_tolerance" name="co2_lower_tolerance">
                            <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                <div class="mdc-notched-outline__leading"></div>
                                <div class="mdc-notched-outline__notch" style="">
                                    <label for="text-field-hero-input" class="mdc-floating-label" style="">CO2 Lower Tolerance</label>
                                </div>
                                <div class="mdc-notched-outline__trailing"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit                -->
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12-desktop">
                        <hr style="width: 100%"/>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12-desktop">
                        <button type="submit"
                                onclick="deviceDB('<?php echo $device_id; ?>')"
                                name="submit_btn" id="submit_btn" class="mdc-button mdc-button--raised">Save & Proceed </button>
                       <!-- <button class="mdc-button mdc-button--raised" id="submit_btn">
                            Submit
                        </button>-->
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
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
<!-- endinject -->
<script>
    $( "#submit_btn" ).click(function (e){
        e.preventDefault();
        $(':input[type="button"]').prop('disabled', true);
        var data = $("#addDeviceForm").serialize();
        $.ajax({
            type: 'POST',
            url: 'addDevice.php',
            data: "fSubmit=1&" + data,
            success: function (data) {
                // window.location.href = window.location.href + "?aa=Line 1";
                $(':input[type="button"]').prop('disabled', false);
                var st_val =  data.split("}",2);
                var st = JSON.parse(st_val[0]+'}')['status'];
                var message_text = JSON.parse(st_val[0]+'}')['message'];
                if(st == 'error'){
                    document.getElementById('dp_fail_msg').innerText = message_text;
                    document.getElementById('aFail').style.display = 'block';
                    document.getElementById('aSucc').style.display = 'none';
                    window.scrollTo(0, 0);
                }else if(st == 'success'){
                    document.getElementById('dp_suc_msg').innerText = message_text;
                    document.getElementById('aSucc').style.display = 'block';
                    document.getElementById('addDevice').style.display = 'none';
                    document.getElementById('aFail').style.display = 'none';
                    window.scrollTo(0, 0);
                }
            }
        });
    });
</script>
<script>
    function deviceDB(device_id) {
        window.open("<?php echo $iotURL . "devices/notification/email_notification.php?device_id=" ; ?>" + device_id , "_self")
    }

</script>
</body>
</html>