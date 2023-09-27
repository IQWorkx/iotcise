<?php  session_start();
    require "../../../assets/vendors/autoload.php";
use Firebase\JWT\JWT;
$status = '0';
$message = "";
include("../../config.php");
//include("../sup_config.php");
$chicagotime = date("Y-m-d H:i:s");
$temp = "";
$modified_by = $_SESSION["id"];
if (($_POST['fSubmit'] == 1) && (!empty($_POST['edit_device_id']))) {
    $edit_cust_id = $_POST['edit_cust_id'];
    $dd_id = $_POST['edit_device_id'];
    $edit_dev_id = $_POST["edit_dev_id"];
    $edit_dev_desc = $_POST["edit_dev_desc"];
    $edit_dev_loc = $_POST["edit_dev_loc"];
    $edit_temperature_upp_tolerance = $_POST["edit_temperature_upp_tolerance"];
    $edit_temperature_low_tolerance = $_POST["edit_temperature_low_tolerance"];
    $edit_humidity_upp_tolerance = $_POST["edit_humidity_upp_tolerance"];
    $edit_humidity_low_tolerance = $_POST["edit_humidity_low_tolerance"];
    $edit_pressure_upp_tolerance = $_POST["edit_pressure_upp_tolerance"];
    $edit_pressure_low_tolerance = $_POST["edit_pressure_low_tolerance"];
    $edit_iaq_upp_tolerance = $_POST["edit_iaq_upp_tolerance"];
    $edit_iaq_low_tolerance = $_POST["edit_iaq_low_tolerance"];
    $edit_voc_upp_tolerance = $_POST["edit_voc_upp_tolerance"];
    $edit_voc_low_tolerance = $_POST["edit_voc_low_tolerance"];
    $edit_co2_upp_tolerance = $_POST["edit_co2_upp_tolerance"];
    $edit_co2_low_tolerance = $_POST["edit_co2_low_tolerance"];
    $service_url = $rest_api_uri . "devices/edit_iot_device.php";
    $curl = curl_init($service_url);
    $curl_post_data = array(
        'device_id' => $dd_id,
        'device_description' => $edit_dev_desc,
        'device_location' => $edit_dev_loc,
        'temperature_upp_tolerance' => $edit_temperature_upp_tolerance,
        'temperature_low_tolerance' => $edit_temperature_low_tolerance,
        'humidity_upp_tolerance' => $edit_humidity_upp_tolerance,
        'humidity_low_tolerance' => $edit_humidity_low_tolerance,
        'pressure_upp_tolerance' => $edit_pressure_upp_tolerance,
        'pressure_low_tolerance' => $edit_pressure_low_tolerance,
        'iaq_upp_tolerance' => $edit_iaq_upp_tolerance,
        'iaq_low_tolerance' => $edit_iaq_low_tolerance,
        'voc_upp_tolerance' => $edit_voc_upp_tolerance,
        'voc_low_tolerance' => $edit_voc_low_tolerance,
        'co2_upp_tolerance' => $edit_co2_upp_tolerance,
        'co2_low_tolerance' => $edit_co2_low_tolerance,
        'modified_by' => $modified_by,
        'modified_on' => $chicagotime,
        'updated_at' => $chicagotime
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
    $import_status_message = 'Iot Device Updated Successfully.';
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
    <title>Edit IOT Device</title>
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
        $title = "Edit an IOT Device";
        include('./../../partials/navbar.html') ?>
        <div class="mdc-layout-grid">
            <form action="" method="" id="EditDeviceForm">
                <?php
                $device_id = $_GET['device_id'];
                $sql = "select * from iot_devices where device_id = '$device_id' and is_deleted != 1";
                $res = mysqli_query($iot_db, $sql);
                $row = mysqli_fetch_array($res);
                $customer = $row['c_id'];
                $dev_id = $row['device_id'];
                $dev_name = $row['device_name'];
                $dev_desc = $row['device_description'];
                $dev_type = $row['type_id'];
                $dev_loc = $row['device_location'];
                $is_active = $row['is_active'];
                $sqlv1 = "select * from device_parameter_config where device_id = '$dev_id' and p_id = '1' and is_deleted != 1";
                $resv1 = mysqli_query($iot_db, $sqlv1);
                $rowv1 = mysqli_fetch_array($resv1);
                $temperature_upp_tolerance = $rowv1["upper_tolerance"];
                $temperature_low_tolerance = $rowv1["lower_tolerance"];
                $sqlv2 = "select * from device_parameter_config where device_id = '$dev_id' and p_id = '2' and is_deleted != 1";
                $resv2 = mysqli_query($iot_db, $sqlv2);
                $rowv2 = mysqli_fetch_array($resv2);
                $humidity_upp_tolerance = $rowv2["upper_tolerance"];
                $humidity_low_tolerance = $rowv2["lower_tolerance"];
                $sqlv3 = "select * from device_parameter_config where device_id = '$dev_id' and p_id = '3' and is_deleted != 1";
                $resv3 = mysqli_query($iot_db, $sqlv3);
                $rowv3 = mysqli_fetch_array($resv3);
                $pressure_upp_tolerance = $rowv3["upper_tolerance"];
                $pressure_low_tolerance = $rowv3["lower_tolerance"];
                $sqlv4 = "select * from device_parameter_config where device_id = '$dev_id' and p_id = '4' and is_deleted != 1";
                $resv4 = mysqli_query($iot_db, $sqlv4);
                $rowv4 = mysqli_fetch_array($resv4);
                $iaq_upp_tolerance = $rowv4["upper_tolerance"];
                $iaq_low_tolerance = $rowv4["lower_tolerance"];
                $sqlv5 = "select * from device_parameter_config where device_id = '$dev_id' and p_id = '5' and is_deleted != 1";
                $resv5 = mysqli_query($iot_db, $sqlv5);
                $rowv5 = mysqli_fetch_array($resv5);
                $voc_upp_tolerance = $rowv5["upper_tolerance"];
                $voc_low_tolerance = $rowv5["lower_tolerance"];
                $sqlv6 = "select * from device_parameter_config where device_id = '$dev_id' and p_id = '6' and is_deleted != 1";
                $resv6 = mysqli_query($iot_db, $sqlv6);
                $rowv6 = mysqli_fetch_array($resv6);
                $co2_upp_tolerance = $rowv6["upper_tolerance"];
                $co2_low_tolerance = $rowv6["lower_tolerance"];
                ?>
                <div class="mdc-layout-grid__inner form_bg">
                    <!--     Device type and Customer           -->
                    <div style="margin-top: 10px;" class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input type="hidden" name="edit_device_id" id="edit_device_id" value="<?php echo $dev_id; ?>">
                            <input class="mdc-text-field__input" value="<?php echo $dev_id; ?>" id="edit_dev_id" name="edit_dev_id">
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
                                    <input type="hidden" name="edit_type_id" id="edit_type_id">
                                    <i class="mdc-select__dropdown-icon"></i>
                                    <div class="mdc-select__selected-text"></div>
                                    <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                                        <ul class="mdc-list">
                                            <?php
                                            $st_dashboard1 = $_POST['dev_type'];
                                            $sql1 = "SELECT * FROM `iot_device_type` where is_deleted != 1";
                                            $result1 = mysqli_query($iot_db,$sql1);
                                            while ($row1 = $result1->fetch_assoc()) {
                                                if($dev_type == $row1['type_id'])
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
                                    <input type="hidden" name="edit_cust_id" id="edit_cust_id">
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
                                    <span class="mdc-floating-label">Select Customer *</span>
                                    <div class="mdc-line-ripple"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                        <div class="mdc-text-field mdc-text-field--outlined">
                            <input class="mdc-text-field__input" id="edit_dev_name"  name="edit_dev_name" value="<?php echo $dev_name; ?>">
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
                            <input class="mdc-text-field__input" id="edit_dev_loc" name="edit_dev_loc" value="<?php echo $dev_loc; ?>">
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
                            <input class="mdc-text-field__input" id="edit_dev_desc"  name="edit_dev_desc" value="<?php echo $dev_desc; ?>">
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
                                       id="check_temp_enabled"
                                       name="check_temp_enabled"
                                       class="mdc-checkbox__native-control"
                                       checked />
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
                                       checked />
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
                            <input class="mdc-text-field__input" id="edit_temperature_upp_tolerance" name="edit_temperature_upp_tolerance"  value="<?php echo $temperature_upp_tolerance; ?>">
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
                            <input class="mdc-text-field__input" id="edit_temperature_low_tolerance" name="edit_temperature_low_tolerance" value="<?php echo $temperature_low_tolerance; ?>">
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
                                       id="check_humidity_enabled"
                                       name="check_humidity_enabled"
                                       class="mdc-checkbox__native-control"
                                       checked />
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
                                       checked />
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
                            <input class="mdc-text-field__input" id="edit_humidity_upp_tolerance" name="edit_humidity_upp_tolerance" value="<?php echo $humidity_upp_tolerance; ?>">
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
                            <input class="mdc-text-field__input" id="edit_humidity_low_tolerance" name="edit_humidity_low_tolerance" value="<?php echo $humidity_low_tolerance; ?>">
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
                                       id="checkbox_pressure_enabled"
                                       name="checkbox_pressure_enabled"
                                       class="mdc-checkbox__native-control"
                                       checked />
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
                                       checked />
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
                            <input class="mdc-text-field__input" id="edit_pressure_upp_tolerance" name="edit_pressure_upp_tolerance" value="<?php echo $pressure_upp_tolerance; ?>">
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
                            <input class="mdc-text-field__input" id="edit_pressure_low_tolerance" name="edit_pressure_low_tolerance" value="<?php echo $pressure_low_tolerance; ?>">
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
                                       id="check_IAQ_enabled"
                                       name="check_IAQ_enabled"
                                       class="mdc-checkbox__native-control"
                                       checked />
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
                                       checked />
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
                            <input class="mdc-text-field__input" id="edit_iaq_upp_tolerance" name="edit_iaq_upp_tolerance" value="<?php echo $iaq_upp_tolerance; ?>">
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
                            <input class="mdc-text-field__input" id="edit_iaq_low_tolerance" name="edit_iaq_low_tolerance" value="<?php echo $iaq_low_tolerance; ?>">
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
                                       id="check_voc_enabled"
                                       name="check_voc_enabled"
                                       class="mdc-checkbox__native-control"
                                       checked />
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
                                       checked />
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
                            <input class="mdc-text-field__input" id="edit_voc_upp_tolerance" name="edit_voc_upp_tolerance" value="<?php echo $voc_upp_tolerance; ?>">
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
                            <input class="mdc-text-field__input" id="edit_voc_low_tolerance" name="edit_voc_low_tolerance" value="<?php echo $voc_low_tolerance; ?>">
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
                                       id="check_co2_enabled"
                                       name="check_co2_enabled"
                                       class="mdc-checkbox__native-control"
                                       checked />
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
                                       checked />
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
                            <input class="mdc-text-field__input" id="edit_co2_upp_tolerance" name="edit_co2_upp_tolerance"  value="<?php echo $co2_upp_tolerance; ?>">
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
                            <input class="mdc-text-field__input" id="edit_co2_low_tolerance" name="edit_co2_low_tolerance" value="<?php echo $co2_low_tolerance; ?>">
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
                        <button class="mdc-button mdc-button--raised" id="submit_btn">
                            Update
                        </button>
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
        var data = $("#EditDeviceForm").serialize();
        $.ajax({
            type: 'POST',
            url: 'edit_device.php',
            data: "fSubmit=1&" + data,
            success: function (data) {
                // window.location.href = window.location.href + "?aa=Line 1";
                window.location.replace("view_devices.php");
            }
        });
    });
</script>
</body>
</html>
