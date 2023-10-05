<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
{
    header('Location: ./config/403.php');
}
require "../../assets/vendors/autoload.php";
use Firebase\JWT\JWT;
$status = '0';
$message = "";
include("../config.php");
$user_id = $_SESSION["id"];
$chicagotime = date("Y-m-d H:i:s");
$temp = "";


if (($_POST['fSubmit'] == 1 ) && (!empty($_POST['edit_device_id']))){
    $dd_id = $_POST['edit_device_id'];
    $temperature_users = $_POST["temperature_users"];
    $humidity_users = $_POST["humidity_users"];
    $pressure_users = $_POST["pressure_users"];
    $iaq_users = $_POST["iaq_users"];
    $voc_users = $_POST["voc_users"];
    $co2_users = $_POST["co2_users"];

    $service_url = $rest_api_uri . "alert_config/create_alert_config.php";
    $curl = curl_init($service_url);
    $curl_post_data = array(
        'device_id' => $dd_id,
        'temperature_users' => $temperature_users,
        'humidity_users' => $humidity_users,
        'pressure_users' => $pressure_users,
        'iaq_users' => $iaq_users,
        'voc_users' => $voc_users,
        'co2_users' => $co2_users,
        'created_at' => $chicagotime,
    );
    $secretkey = "SupportPassHTSSgmmi";
    $payload = array(
        "author" => "Saargummi to HTS",
        "exp" => time()+1000
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
        die('error occured: ' . $decoded->errormessage);
        $errors[] = "Iot Device Not Updated.";
        $message_stauts_class = 'alert-danger';
        $import_status_message = 'Iot Device Not Updated.';
    }
    $errors[] = "Iot Device Updated Successfully.";
    $message_stauts_class = 'alert-success';
    $import_status_message = 'Device Type Created Successfully.';
    $_SESSION['import_status_message'] =  $import_status_message;
    $_SESSION['message_stauts_class'] = $message_stauts_class;
    exit;
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
////Set the time of the user's last activity
//$_SESSION['LAST_ACTIVITY'] = $time;
//$i = $_SESSION["role_id"];
//
//$assign_by = $_SESSION["id"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Configuration</title>
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
    <link rel="stylesheet" href="<?php echo $iotURL; ?>assets/css/pag_table.css"/>

    <link rel="stylesheet" href="<?php echo $iotURL; ?>assets/css/select2.min.css"/>

    <link rel="stylesheet" href="<?php echo $iotURL; ?>assets/css/common.css"/>

    <!-- INTERNAL Select2 css -->
    <link href="<?php echo $iotURL; ?>/assets/plugins/select2.min.css" rel="stylesheet" />

    <!-- STYLES CSS -->
    <link href="<?php echo $iotURL; ?>/assets/css/style.css" rel="stylesheet">

    <link href="<?php echo $iotURL; ?>/assets/css/style-dark.css" rel="stylesheet">
    <link href="<?php echo $iotURL; ?>/assets/css/style-transparent.css" rel="stylesheet">
</head>
<body>
<script src="<?php echo $iotURL ?>/assets/js/preloader.js"></script>
<div class="body-wrapper">
    <?php include('../partials/sidebar.html') ?>
    <div class="main-wrapper mdc-drawer-app-content">
        <?php
        $title = "Email Configuration";
        include('../partials/navbar.html') ?>
        <div class="mdc-layout-grid">
            <form action="" method="" id="device_settings">
                <?php
                $device_id = $_GET['device_id'];
                $sql = "select * from iot_devices where device_id = '$device_id' and is_deleted != 1";
                $res = mysqli_query($iot_db, $sql);
                $row = mysqli_fetch_array($res);
                $dev_id = $row['device_id'];
                ?>
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="">
                                <div class="card-header">
                                    <span class="main-content-title mg-b-0 mg-b-lg-1">Temperature</span>
                                </div>
                                <div class="pd-30 pd-sm-20">
                                    <div class="row row-xs">
                                        <div class="col-md-1">
                                            <label class="form-label mg-b-0">Users</label>
                                        </div>
                                        <div class="col-md-8 mg-t-10 mg-md-t-0">
                                            <input type="hidden" name="edit_device_id" id="edit_device_id" value="<?php echo $dev_id; ?>">

                                            <select name="temperature_users[]" id="temperature_users" class="form-control select2" multiple="multiple" data-placeholder="Select Users" >
                                                <option value="" > Select Users </option>
                                                <?php
                                                $sql1 = "SELECT * FROM `cam_users` WHERE `users_id` != '1' order BY `firstname` ";
                                                $result1 = $mysqli->query($sql1);
                                                while ($row1 = $result1->fetch_assoc()) {

                                                    echo "<option value='" . $row1['users_id'] . "' $selected>" . $row1['firstname'] . "&nbsp;" . $row1['lastname'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="">
                                <div class="card-header">
                                    <span class="main-content-title mg-b-0 mg-b-lg-1">Humidity</span>
                                </div>
                                <div class="pd-30 pd-sm-20">
                                    <div class="row row-xs">
                                        <div class="col-md-1">
                                            <label class="form-label mg-b-0">Users</label>
                                        </div>
                                        <div class="col-md-8 mg-t-10 mg-md-t-0">
                                            <select name="humidity_users[]" id="humidity_users" class="form-control select2" multiple="multiple" data-placeholder="Select Users" >
                                         x       <option value="" > Select Users </option>
                                                <?php
                                                $sql1 = "SELECT * FROM `cam_users` WHERE `users_id` != '1' order BY `firstname` ";
                                                $result1 = $mysqli->query($sql1);
                                                while ($row1 = $result1->fetch_assoc()) {

                                                    echo "<option value='" . $row1['users_id'] . "' $selected>" . $row1['firstname'] . "&nbsp;" . $row1['lastname'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="">
                                <div class="card-header">
                                    <span class="main-content-title mg-b-0 mg-b-lg-1">Pressure</span>
                                </div>
                                <div class="pd-30 pd-sm-20">
                                    <div class="row row-xs">
                                        <div class="col-md-1">
                                            <label class="form-label mg-b-0">Users</label>
                                        </div>
                                        <div class="col-md-8 mg-t-10 mg-md-t-0">
                                            <select name="pressure_users[]" id="pressure_users" class="form-control select2" multiple="multiple" data-placeholder="Select Users" >
                                                <option value="" > Select Users </option>
                                                <?php
                                                $sql1 = "SELECT * FROM `cam_users` WHERE `users_id` != '1' order BY `firstname` ";
                                                $result1 = $mysqli->query($sql1);
                                                while ($row1 = $result1->fetch_assoc()) {

                                                    echo "<option value='" . $row1['users_id'] . "' $selected>" . $row1['firstname'] . "&nbsp;" . $row1['lastname'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="">
                                <div class="card-header">
                                    <span class="main-content-title mg-b-0 mg-b-lg-1">IAQ</span>
                                </div>
                                <div class="pd-30 pd-sm-20">
                                    <div class="row row-xs">
                                        <div class="col-md-1">
                                            <label class="form-label mg-b-0">Users</label>
                                        </div>
                                        <div class="col-md-8 mg-t-10 mg-md-t-0">
                                            <select name="iaq_users[]" id="iaq_users" class="form-control select2" multiple="multiple" data-placeholder="Select Users" >
                                                <option value="" > Select Users </option>
                                                <?php
                                                $sql1 = "SELECT * FROM `cam_users` WHERE `users_id` != '1' order BY `firstname` ";
                                                $result1 = $mysqli->query($sql1);
                                                while ($row1 = $result1->fetch_assoc()) {

                                                    echo "<option value='" . $row1['users_id'] . "' $selected>" . $row1['firstname'] . "&nbsp;" . $row1['lastname'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="">
                                <div class="card-header">
                                    <span class="main-content-title mg-b-0 mg-b-lg-1">VOC</span>
                                </div>
                                <div class="pd-30 pd-sm-20">
                                    <div class="row row-xs">
                                        <div class="col-md-1">
                                            <label class="form-label mg-b-0">Users</label>
                                        </div>
                                        <div class="col-md-8 mg-t-10 mg-md-t-0">
                                            <select name="voc_users[]" id="voc_users" class="form-control select2" multiple="multiple" data-placeholder="Select Users" >
                                                <option value="" > Select Users </option>
                                                <?php
                                                $sql1 = "SELECT * FROM `cam_users` WHERE `users_id` != '1' order BY `firstname` ";
                                                $result1 = $mysqli->query($sql1);
                                                while ($row1 = $result1->fetch_assoc()) {

                                                    echo "<option value='" . $row1['users_id'] . "' $selected>" . $row1['firstname'] . "&nbsp;" . $row1['lastname'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="">
                                <div class="card-header">
                                    <span class="main-content-title mg-b-0 mg-b-lg-1">Co2</span>
                                </div>
                                <div class="pd-30 pd-sm-20">
                                    <div class="row row-xs">
                                        <div class="col-md-1">
                                            <label class="form-label mg-b-0">Users</label>
                                        </div>
                                        <div class="col-md-8 mg-t-10 mg-md-t-0">
                                            <select name="co2_users[]" id="co2_users" class="form-control select2" multiple="multiple" data-placeholder="Select Users" >
                                                <option value="" > Select Users </option>
                                                <?php
                                                $sql1 = "SELECT * FROM `cam_users` WHERE `users_id` != '1' order BY `firstname` ";
                                                $result1 = $mysqli->query($sql1);
                                                while ($row1 = $result1->fetch_assoc()) {

                                                    echo "<option value='" . $row1['users_id'] . "' $selected>" . $row1['firstname'] . "&nbsp;" . $row1['lastname'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                <div class="pd-30 pd-sm-20">
                    <div class="row row-xs">
                        <button type="submit" id="submit_btn" class="btn btn-primary pd-x-30 mg-r-5 mg-t-5 submit_btn">Submit</button>
                    </div>
                </div>
                </div>

            </form>



        </div>
    </div>
</div>

</body>




<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->


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


<script src="<?php echo $iotURL; ?>/assets/js/select2.js"></script>
<script src="<?php echo $iotURL; ?>/assets/js/select2.full.min.js"></script>
<script>
    $( "#submit_btn" ).click(function (e){
        e.preventDefault();
        $(':input[type="button"]').prop('disabled', true);
        var data = $("#device_settings").serialize();
        $.ajax({
            type: 'POST',
            url: 'email_notification.php',
            data: "fSubmit=1&" + data,
            success: function (data) {
                // window.location.href = window.location.href + "?aa=Line 1";
                window.location.replace("<?php echo $iotName; ?>devices/pages/devices/view_devices.php");
            }
        });
    });

</script>




</body>
</html>