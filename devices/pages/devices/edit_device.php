<?php require "../../../assets/vendors/autoload.php";
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
    $edit_dev_name = $_POST["edit_dev_name"];
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

    $edit_temperature = $_POST['edit_temperature'];
    if($edit_temperature == '1'){
        $temp = '1';
    }else{
        $temp = '0';
    }

    $edit_humidity = $_POST['edit_humidity'];
    if($edit_humidity == '1'){
        $hum = '1';
    }else{
        $hum = '0';
    }

    $edit_pressure = $_POST['edit_pressure'];
    if($edit_pressure == '1'){
        $pres = '1';
    }else{
        $pres = '0';
    }

    $edit_voc = $_POST['edit_voc'];
    if($edit_voc == '1'){
        $voc1 = '1';
    }else{
        $voc1 = '0';
    }
    $edit_iaq = $_POST['edit_iaq'];
    if($edit_iaq == '1'){
        $iaq1 = '1';
    }else{
        $iaq1 = '0';
    }
    $edit_co2 = $_POST['edit_co2'];
    if($edit_co2 == '1'){
        $co = '1';
    }else{
        $co = '0';
    }

    //email edit enable

    $edit_email_temperature = $_POST['edit_email_temperature'];
    if($edit_email_temperature == '1'){
        $temp_email = '1';
    }else{
        $temp_email = '0';
    }

    $edit_email_humidity = $_POST['edit_email_humidity'];
    if($edit_email_humidity == '1'){
        $hum_email = '1';
    }else{
        $hum_email = '0';
    }

    $edit_email_pressure = $_POST['edit_email_pressure'];
    if($edit_email_pressure == '1'){
        $pres_email = '1';
    }else{
        $pres_email = '0';
    }

    $edit_email_voc = $_POST['edit_email_voc'];
    if($edit_email_voc == '1'){
        $voc1_email = '1';
    }else{
        $voc1_email = '0';
    }
    $edit_email_iaq = $_POST['edit_email_iaq'];
    if($edit_email_iaq == '1'){
        $iaq1_email = '1';
    }else{
        $iaq1_email = '0';
    }
    $edit_email_co2 = $_POST['edit_email_co2'];
    if($edit_email_co2 == '1'){
        $co_email = '1';
    }else{
        $co_email = '0';
    }

    //sms edit enable

    $edit_sms_temperature = $_POST['edit_sms_temperature'];
    if($edit_sms_temperature == '1'){
        $temp_sms = '1';
    }else{
        $temp_sms = '0';
    }

    $edit_sms_humidity = $_POST['edit_sms_humidity'];
    if($edit_sms_humidity == '1'){
        $hum_sms = '1';
    }else{
        $hum_sms = '0';
    }

    $edit_sms_pressure = $_POST['edit_sms_pressure'];
    if($edit_sms_pressure == '1'){
        $pres_sms = '1';
    }else{
        $pres_sms = '0';
    }

    $edit_sms_voc = $_POST['edit_sms_voc'];
    if($edit_sms_voc == '1'){
        $voc1_sms = '1';
    }else{
        $voc1_sms = '0';
    }
    $edit_sms_iaq = $_POST['edit_sms_iaq'];
    if($edit_sms_iaq == '1'){
        $iaq1_sms = '1';
    }else{
        $iaq1_sms = '0';
    }
    $edit_sms_co2 = $_POST['edit_sms_co2'];
    if($edit_sms_co2 == '1'){
        $co_sms = '1';
    }else{
        $co_sms = '0';
    }



    //REST API URL
    $service_url = $rest_api_uri . "devices/edit_iot_device.php";
    $curl = curl_init($service_url);
    $curl_post_data = array(
        'device_id' => $dd_id,
        'device_name' => $edit_dev_name,
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

        'temperature_enabled' => $temp,
        'humidity_enabled' => $hum,
        'pressure_enabled' => $pres,
        'iaq_enabled' => $iaq1,
        'voc_enabled' => $voc1,
        'co2_enabled' => $co,

        'temperature_email_enabled' => $temp_email,
        'humidity_email_enabled' => $hum_email,
        'pressure_email_enabled' => $pres_email,
        'iaq_email_enabled' => $iaq1_email,
        'voc_email_enabled' => $voc1_email,
        'co2_email_enabled' => $co_email,

        'temperature_sms_enabled' => $temp_sms,
        'humidity_sms_enabled' => $hum_sms,
        'pressure_sms_enabled' => $pres_sms,
        'iaq_sms_enabled' => $iaq1_sms,
        'voc_sms_enabled' => $voc1_sms,
        'co2_sms_enabled' => $co_sms,

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
                $temperature_enabled = $rowv1["is_enabled"];
                $temperature_email_enabled = $rowv1["is_email_enabled"];
                $temperature_sms_enabled = $rowv1["is_sms_enabled"];
                $temperature_upp_tolerance = $rowv1["upper_tolerance"];
                $temperature_low_tolerance = $rowv1["lower_tolerance"];

                $sqlv2 = "select * from device_parameter_config where device_id = '$dev_id' and p_id = '2' and is_deleted != 1";
                $resv2 = mysqli_query($iot_db, $sqlv2);
                $rowv2 = mysqli_fetch_array($resv2);
                $humidity_enabled = $rowv2["is_enabled"];
                $humidity_email_enabled = $rowv2["is_email_enabled"];
                $humidity_sms_enabled = $rowv2["is_sms_enabled"];
                $humidity_upp_tolerance = $rowv2["upper_tolerance"];
                $humidity_low_tolerance = $rowv2["lower_tolerance"];

                $sqlv3 = "select * from device_parameter_config where device_id = '$dev_id' and p_id = '3' and is_deleted != 1";
                $resv3 = mysqli_query($iot_db, $sqlv3);
                $rowv3 = mysqli_fetch_array($resv3);
                $pressure_enabled = $rowv3["is_enabled"];
                $pressure_email_enabled = $rowv3["is_email_enabled"];
                $pressure_sms_enabled = $rowv3["is_sms_enabled"];
                $pressure_upp_tolerance = $rowv3["upper_tolerance"];
                $pressure_low_tolerance = $rowv3["lower_tolerance"];

                $sqlv4 = "select * from device_parameter_config where device_id = '$dev_id' and p_id = '4' and is_deleted != 1";
                $resv4 = mysqli_query($iot_db, $sqlv4);
                $rowv4 = mysqli_fetch_array($resv4);
                $iaq_enabled = $rowv4["is_enabled"];
                $iaq_email_enabled = $rowv4["is_email_enabled"];
                $iaq_sms_enabled = $rowv4["is_sms_enabled"];
                $iaq_upp_tolerance = $rowv4["upper_tolerance"];
                $iaq_low_tolerance = $rowv4["lower_tolerance"];

                $sqlv5 = "select * from device_parameter_config where device_id = '$dev_id' and p_id = '5' and is_deleted != 1";
                $resv5 = mysqli_query($iot_db, $sqlv5);
                $rowv5 = mysqli_fetch_array($resv5);
                $voc_enabled = $rowv5["is_enabled"];
                $voc_email_enabled = $rowv5["is_email_enabled"];
                $voc_sms_enabled = $rowv5["is_sms_enabled"];
                $voc_upp_tolerance = $rowv5["upper_tolerance"];
                $voc_low_tolerance = $rowv5["lower_tolerance"];

                $sqlv6 = "select * from device_parameter_config where device_id = '$dev_id' and p_id = '6' and is_deleted != 1";
                $resv6 = mysqli_query($iot_db, $sqlv6);
                $rowv6 = mysqli_fetch_array($resv6);
                $co2_enabled = $rowv6["is_enabled"];
                $co2_email_enabled = $rowv6["is_email_enabled"];
                $co2_sms_enabled = $rowv6["is_sms_enabled"];
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
                            <div class="w100 template-demo" style="pointer-events: none;">
                                <div class="w100 mdc-select demo-width-class" data-mdc-auto-init="MDCSelect">
                                    <input type="hidden" name="edit_type_id" id="edit_type_id">
                                    <i class="mdc-select__dropdown-icon"></i>
                                    <div class="mdc-select__selected-text"></div>
                                    <div class="mdc-select__menu mdc-menu-surface demo-width-class">
                                        <ul class="mdc-list">
                                            <?php
                                            $st_dashboard1 = $dev_type;
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
                                                <li class="mdc-list-item" data-value="<?php echo $row1['type_id'];?>">
                                                    <?php echo $row1['dev_type_name']; ?>
                                                    <input type="hidden" value="  <?php echo $_SESSION['device_type_namee'] = "<option value='" . $row1['type_id'] . "' $entry>" . $row1['dev_type_name'];"</option>";?>">
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <span class="mdc-floating-label"><?php echo $display = $_SESSION['device_type_namee']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w100 mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">
                        <div class="w100 mdc-layout-grid__cell--span-4 mdc-layout-grid__cell--span-6-desktop stretch-card">
                            <div class="w100 template-demo" style="pointer-events: none;">
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
                                                    <input type="hidden" value="  <?php echo $_SESSION['c_namee'] = "<option value='" . $row1['c_id'] . "' $entry>" . $row1['c_name'];"</option>";?>">
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <span class="mdc-floating-label"><?php echo $display = $_SESSION['c_namee']; ?></span>
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
                                       id="edit_temperature"
                                       name="edit_temperature"
                                       class="mdc-checkbox__native-control"
                                    <?php if($temperature_enabled == 1){echo 'checked';} ?>
                                       value="1"
                                />
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
                                       id="edit_email_temperature"
                                       name="edit_email_temperature"
                                       class="mdc-checkbox__native-control"
                                    <?php if($temperature_email_enabled == 1){echo 'checked';} ?>
                                       value="1"
                                />
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
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="edit_sms_temperature"
                                       name="edit_sms_temperature"
                                       class="mdc-checkbox__native-control"
                                    <?php if($temperature_sms_enabled == 1){echo 'checked';} ?>
                                       value="1"
                                />
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
                                       id="edit_humidity"
                                       name="edit_humidity"
                                       class="mdc-checkbox__native-control"
                                    <?php if($humidity_enabled == 1){echo 'checked';} ?>
                                       value="1"/>
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
                                       id="edit_email_humidity"
                                       name="edit_email_humidity"
                                       class="mdc-checkbox__native-control"
                                    <?php if($humidity_email_enabled == 1){echo 'checked';} ?>
                                       value="1"/>
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
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="edit_sms_humidity"
                                       name="edit_sms_humidity"
                                       class="mdc-checkbox__native-control"
                                    <?php if($humidity_sms_enabled == 1){echo 'checked';} ?>
                                       value="1"/>
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
                                       id="edit_pressure"
                                       name="edit_pressure"
                                       class="mdc-checkbox__native-control"
                                    <?php if($pressure_enabled == 1){echo 'checked';} ?>
                                       value="1"/>
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
                                       id="edit_email_pressure"
                                       name="edit_email_pressure"
                                       class="mdc-checkbox__native-control"
                                    <?php if($pressure_email_enabled == 1){echo 'checked';} ?>
                                       value="1"/>
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
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="edit_sms_pressure"
                                       name="edit_sms_pressure"
                                       class="mdc-checkbox__native-control"
                                    <?php if($pressure_sms_enabled == 1){echo 'checked';} ?>
                                       value="1"/>
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
                                       id="edit_iaq"
                                       name="edit_iaq"
                                       class="mdc-checkbox__native-control"
                                    <?php if($iaq_enabled == 1){echo 'checked';} ?>
                                       value="1"/>
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
                                       id="edit_email_iaq"
                                       name="edit_email_iaq"
                                       class="mdc-checkbox__native-control"
                                    <?php if($iaq_email_enabled == 1){echo 'checked';} ?>
                                       value="1"/>
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
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="edit_sms_iaq"
                                       name="edit_sms_iaq"
                                       class="mdc-checkbox__native-control"
                                    <?php if($iaq_sms_enabled == 1){echo 'checked';} ?>
                                       value="1"/>
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
                                       id="edit_voc"
                                       name="edit_voc"
                                       class="mdc-checkbox__native-control"
                                    <?php if($voc_enabled == 1){echo 'checked';} ?>
                                       value="1"/>

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
                                       id="edit_email_voc"
                                       name="edit_email_voc"
                                       class="mdc-checkbox__native-control"
                                    <?php if($voc_email_enabled == 1){echo 'checked';} ?>
                                       value="1"/>
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
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="edit_sms_voc"
                                       name="edit_sms_voc"
                                       class="mdc-checkbox__native-control"
                                    <?php if($voc_sms_enabled == 1){echo 'checked';} ?>
                                       value="1"/>
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
                                       id="edit_co2"
                                       name="edit_co2"
                                       class="mdc-checkbox__native-control"
                                    <?php if($co2_enabled == '1'){ echo 'checked'; } ?>
                                       value="1"/>
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
                                       id="edit_email_co2"
                                       name="edit_email_co2"
                                       class="mdc-checkbox__native-control"
                                    <?php if($co2_email_enabled == '1'){ echo 'checked'; } ?>
                                       value="1"/>
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
                        <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                                <input type="checkbox"
                                       id="edit_sms_co2"
                                       name="edit_sms_co2"
                                       class="mdc-checkbox__native-control"
                                    <?php if($co2_sms_enabled == '1'){ echo 'checked'; } ?>
                                       value="1"/>
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
