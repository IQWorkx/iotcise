<?php include("./config.php");
	$chicagotime = date("Y-m-d H:i:s");
	$temp = "";
	//Set the session duration for 10800 seconds - 3 hours
	$duration = 10800;
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
	} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IOT Dash</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="../assets/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="../assets/vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="../assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="../assets/vendors/jvectormap/jquery-jvectormap.css">
    <!-- End plugin css for this page -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="<?php echo $iotURL?>/assets/css/demo/style.css">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="<?php echo $iotURL?>/assets/images/favicon.png"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
<script src="../assets/js/preloader.js"></script>
<div class="body-wrapper">
    <?php include('./partials/sidebar.html') ?>
    <div class="main-wrapper mdc-drawer-app-content">
        <?php
			$title = 'Welcome to IOT Dash';
            include('./partials/navbar.html') ?>
        <div class="page-wrapper mdc-toolbar-fixed-adjust">
            <main class="content-wrapper">
                <div class="mdc-layout-grid">
                    <div class="mdc-layout-grid__inner">
						<?php
							$sql = "SELECT * FROM `iot_devices` where is_deleted != 1";
							$result = mysqli_query($iot_db, $sql);
							while ($row = mysqli_fetch_array($result)) {
							$created_date = new DateTime(explode(' ',$row['created_on'])[0]);
							$current_date = new DateTime(date("Y-m-d"));
							$period = get_period_ago($current_date,$created_date);
							$edit_dev_loc = $iotURL .'devices/pages/devices/edit_device.php?device_id='.$row['device_id'];
							$view_dev_loc = $iotURL .'devices/view_device_dashboard.php?device_id='.$row['device_id'];
							$d_type_id=$row['type_id'];
							$d_type_sql = "SELECT dev_type_name FROM `iot_device_type` where type_id = '$d_type_id' and  is_deleted != 1";
							$d_type_res = mysqli_fetch_array(mysqli_query($iot_db, $d_type_sql));
						?>
                                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop mdc-layout-grid__cell--span-4-tablet">
                            <div class="mdc-card info-card info-card--success">
                                <div class="card-inner">
                                    <h5 class="card-title"><?php echo $row['device_name']?></h5>
                                    <h6 class="font-weight-light pb-2 mb-1"><b>Type : </b><?php echo $d_type_res['dev_type_name']?></h6>
                                    <p class="tx-12 text-muted"><?php echo 'Added ' . $period?></p>
                                    <div class="border-bottom"></div>
                                    <div class="d-flex justify-content-between install" style=" padding-top: 15px">
                                                <span style="width: 50%">
                                                    <a style="padding: 0% 5%;" href="<?php echo $edit_dev_loc?>"><i class="material-icons settings_gear">settings</i></a>
                                                    <a id="del_device" name="del_device" href="pages/devices/delete_device.php?device_id=<?php echo $row['device_id']?>"><i class="material-icons del_icon">delete</i></a>
                                                </span>
<!--                                        <span style="width: 35%"></span>-->
                                        <span style="width: 25%" id="view_link" class="text-primary">
                                            <a style="" href="<?php echo $view_dev_loc?>">
<!--                                                <i class="fa-solid fa-eye"></i>-->
                                                <i class="material-icons">visibility</i>
                                            </a>
                                        </span>
                                    </div>
                                    <div class="card-icon-wrapper">
                                        <i class="material-icons">settings_remote</i>
                                    </div>
                                </div>
                            </div>
                        </div>
                            <?php } ?>
                    </div>
                </div>
            </main>
            <?php include('./partials/footer.html')?>
        </div>
    </div>
</div>
<!-- plugins:js -->
<script src="../assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<script src="../assets/vendors/chartjs/Chart.min.js"></script>
<script src="../assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
<script src="../assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="../assets/js/material.js"></script>
<script src="../assets/js/misc.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="../assets/js/dashboard.js"></script>
<!-- End custom js for this page-->
</body>
</html>