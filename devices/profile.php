<?php
require "../assets/vendors/autoload.php";
use Firebase\JWT\JWT;
$status = '0';
$message = "";
include("config.php");

$temp = "";
if (!isset($_SESSION['user'])) {
    header('location: logout.php');
}
$usr = $_SESSION['user'];
// to display error msg
if (!empty($_SESSION['import_status_message'])) {
    $message_stauts_class = $_SESSION['message_stauts_class'];
    $import_status_message = $_SESSION['import_status_message'];
    $_SESSION['message_stauts_class'] = '';
    $_SESSION['import_status_message'] = '';
}
if (count($_POST) > 0) {
    $uploadPath = 'user_images/';
    $statusMsg = '';
    $upload = 0;
    $pin = $_SESSION["pin"];
    //validate the pin
    $password = $_POST['newpin'];

    if( strlen($password ) > 4 ) {
        $error .= "Pin too long!";
    }
    if( strlen($password ) < 4 ) {
        $error .= "Pin too short!";
    }
    if( !preg_match("@[0-9]@", $password ) ) {
        $error .= "Pin must Numeric!";
    }
    $pin_flag = $_SESSION["pin_flag"];
    if (!empty($_FILES['file']['name'])) {
        $fileName = $_FILES['file']['name'];
        $fileType = $_FILES['file']['type'];
        $fileTemp = $_FILES['file']['tmp_name'];
        $filePath = $uploadPath . basename($fileName);
        // Allow certain file formats
        $allowTypes = array('image/png', 'image/jpg', 'image/jpeg', 'image/gif');
        if (in_array($fileType, $allowTypes)) {
            $rotation = $_POST['rotation'];
            if ($rotation == -90 || $rotation == 270) {
                $rotation = 90;
            } elseif ($rotation == -180 || $rotation == 180) {
                $rotation = 180;
            } elseif ($rotation == -270 || $rotation == 90) {
                $rotation = 270;
            }
            if (!empty($rotation)) {
                switch ($fileType) {
                    case 'image/png':
                        $source = imagecreatefrompng($fileTemp);
                        break;
                    case 'image/gif':
                        $source = imagecreatefromgif($fileTemp);
                        break;
                    default:
                        $source = imagecreatefromjpeg($fileTemp);
                }
                $imageRotate = imagerotate($source, $rotation, 0);
                switch ($fileType) {
                    case 'image/png':
                        $upload = imagepng($imageRotate, $filePath);
                        break;
                    case 'image/gif':
                        $upload = imagegif($imageRotate, $filePath);
                        break;
                    default:
                        $upload = imagejpeg($imageRotate, $filePath);
                }
            } elseif (move_uploaded_file($fileTemp, $filePath)) {
                $upload = 1;
            } else {
                $statusMsg = 'File upload failed, please try again.';
            }
        } else {
            $statusMsg = 'Sorry, only JPG/JPEG/PNG/GIF files are allowed to upload.';
        }
        if ($upload == 1) {
            if($error){

            }else{
                if ($pin_flag == "1") {
                    $_SESSION["pin"] = $_POST['pin'];
                    $sql = "update cam_users set pin='$password',password_pin = '$password',profile_pic='$fileName',firstname='$_POST[firstname]',lastname='$_POST[lastname]',mobile='$_POST[mobile]',email='$_POST[email]' where user_name='$usr'";
                } else {
                    $sql = "update cam_users set profile_pic='$fileName',firstname='$_POST[firstname]',lastname='$_POST[lastname]',mobile='$_POST[mobile]',email='$_POST[email]' where user_name='$usr'";
                }
                $result1 = mysqli_query($db, $sql);
                if ($result1) {
                    $_SESSION["fullname"] = $_POST['firstname'] . "&nbsp;" . $_POST['lastname'];
                    $message_stauts_class = 'alert-success';
                    $import_status_message = 'Success: Profile Updated Sucessfully.';
                } else {
                    $message_stauts_class = 'alert-danger';
                    $import_status_message = 'Error: Please Try Again.';
                }
            }
            $_SESSION["uu_img"] = $fileName;
        } else {
            echo '<h4>' . $statusMsg . '</h4>';
        }
    } else {
        if($error){

        }else{
            if ($pin_flag == "1") {
                $_SESSION["pin"] = $_POST['pin'];
                $sql = "update cam_users set pin='$password',password_pin = '$password',firstname='$_POST[firstname]',lastname='$_POST[lastname]',mobile='$_POST[mobile]',email='$_POST[email]' where user_name='$usr'";
            } else {
                $sql = "update cam_users set firstname='$_POST[firstname]',lastname='$_POST[lastname]',mobile='$_POST[mobile]',email='$_POST[email]' where user_name='$usr'";
            }
            $_SESSION["fullname"] = $_POST['firstname'] . "&nbsp;" . $_POST['lastname'];
            $result1 = mysqli_query($db, $sql);
            if ($result1) {
                $message_stauts_class = 'alert-success';
                $import_status_message = 'Success: Profile Updated Sucessfully.';
            } else {
                $message_stauts_class = 'alert-danger';
                $import_status_message = 'Error: Please Try Again.';
            }
        }

    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Device Type</title>
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
    <?php include('partials/sidebar.html') ?>
    <div class="main-wrapper mdc-drawer-app-content">
        <?php
        $title = "Edit Device Type";
        include('partials/navbar.html') ?>
        <div class="mdc-layout-grid">
            <form action="" method="" id="DeviceForm">
                <?php
                $query = sprintf("SELECT * FROM  cam_users where user_name = '$usr'  ; ");
                $qur = mysqli_query($db, $query);
                while ($rowc = mysqli_fetch_array($qur)) {
                ?>
                <div class="mdc-layout-grid">
                    <form action="" method="" id="device_settings">
                        <div class="mdc-layout-grid__inner form_bg">
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-8-desktop">
                                <div class="mdc-text-field mdc-text-field--outlined">
                                    <input class="mdc-text-field__input" name="username" value="<?php echo $rowc["user_name"]; ?>" >
                                    <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                        <div class="mdc-notched-outline__leading"></div>
                                        <div class="mdc-notched-outline__notch" style="">
                                            <label for="text-field-hero-input" class="mdc-floating-label" style="">Username</label>
                                        </div>
                                        <div class="mdc-notched-outline__trailing"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-8-desktop">
                                <div class="mdc-text-field mdc-text-field--outlined">
                                    <input class="mdc-text-field__input" name="firstname" value="<?php echo $rowc["firstname"]; ?>" >
                                    <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                        <div class="mdc-notched-outline__leading"></div>
                                        <div class="mdc-notched-outline__notch" style="">
                                            <label for="text-field-hero-input" class="mdc-floating-label" style="">Username</label>
                                        </div>
                                        <div class="mdc-notched-outline__trailing"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-8-desktop">
                                <div class="mdc-text-field mdc-text-field--outlined">
                                    <input class="mdc-text-field__input" name="lastname" value="<?php echo $rowc["lastname"]; ?>">
                                    <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                        <div class="mdc-notched-outline__leading"></div>
                                        <div class="mdc-notched-outline__notch" style="">
                                            <label for="text-field-hero-input" class="mdc-floating-label" style="">Username</label>
                                        </div>
                                        <div class="mdc-notched-outline__trailing"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-8-desktop">
                                <div class="mdc-text-field mdc-text-field--outlined">
                                    <input class="mdc-text-field__input" name="mobile" value="<?php echo $rowc["mobile"]; ?>" >
                                    <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                        <div class="mdc-notched-outline__leading"></div>
                                        <div class="mdc-notched-outline__notch" style="">
                                            <label for="text-field-hero-input" class="mdc-floating-label" style="">Username</label>
                                        </div>
                                        <div class="mdc-notched-outline__trailing"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-8-desktop">
                                <div class="mdc-text-field mdc-text-field--outlined">
                                    <input class="mdc-text-field__input" name="email" value="<?php echo $rowc["email"]; ?>" >
                                      <div class="mdc-notched-outline mdc-notched-outline--upgraded">
                                        <div class="mdc-notched-outline__leading"></div>
                                        <div class="mdc-notched-outline__notch" style="">
                                            <label for="text-field-hero-file" class="mdc-floating-label" style="">Username</label>
                                        </div>
                                        <div class="mdc-notched-outline__trailing"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6-desktop">
                                <button type="submit" id="submit_btn" class="mdc-button mdc-button--raised">Update</button>
                            </div>
                        </div>
                        <?php } ?>
                    </form>
                </div>
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



<script>
    function filePreview(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#imgPreview + img').remove();
                $('#imgPreview').after('<img src="' + e.target.result + '" class="pic-view" width="200" height="150" float="left"/>');
            };
            reader.readAsDataURL(input.files[0]);
            $('.img-preview').show();
        } else {
            $('#imgPreview + img').remove();
            $('.img-preview').hide();
        }
    }
    $("#file").change(function () {
        // Image preview
        filePreview(this);
    });
    $(function () {
        var rotation = 0;
        $("#rright").click(function () {
            rotation = (rotation - 90) % 360;
            $(".pic-view").css({'transform': 'rotate(' + rotation + 'deg)'});
            if (rotation != 0) {
                $(".pic-view").css({'width': '100px', 'height': '132px'});
            } else {
                $(".pic-view").css({'width': '24%', 'height': '132px'});
            }
            $('#rotation').val(rotation);
        });
        $("#rleft").click(function () {
            rotation = (rotation + 90) % 360;
            $(".pic-view").css({'transform': 'rotate(' + rotation + 'deg)'});
            if (rotation != 0) {
                $(".pic-view").css({'width': '100px', 'height': '132px'});
            } else {
                $(".pic-view").css({'width': '24%', 'height': '132px'});
            }
            $('#rotation').val(rotation);
        });
    });
</script>
</body>


