<?php
include("../../config.php");
$devc_check = $_POST['edit_is_active'];
$check = $_POST['is_active'];
$devc_is_checked = $_POST['isChecked'];
if ($check != "" or $devc_check != "") {

    if($devc_is_checked =='true') {
        $sql1 = "UPDATE `iot_devices` set  is_active = 1  WHERE `device_id`='$check' or `device_id`='$devc_check'";
    }else{
        $sql1 = "UPDATE `iot_devices` set  is_active = 0  WHERE `device_id`='$check' or `device_id`='$devc_check'";
    }
    if (mysqli_query($iot_db, $sql1)) {
        $_SESSION['message_stauts_class'] = 'alert-success';
        $_SESSION['import_status_message'] = 'Required Sucessfully';
    } else {
        $_SESSION['message_stauts_class'] = 'alert-danger';
        $_SESSION['import_status_message'] = 'please retry.';
    }
}

header("Location:view_devices.php");
?>
