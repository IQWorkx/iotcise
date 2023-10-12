<?php
	require "../../../assets/vendors/autoload.php";
	use Firebase\JWT\JWT;
	$status = '0';
	$message = "";
	include("../../config.php");
	//include("../sup_config.php");
	$chicagotime = date("Y-m-d H:i:s");
	$temp = "";
	$user_id = $_SESSION["id"];
	if (($_POST['fSubmit'] == 1 ) && (!empty($_POST['dev_id']))){
		$c_id = $_POST["customer"];
		$device_id = $_POST["dev_id"];
		$device_name = $_POST["dev_name"];
		$device_desc = $_POST["dev_desc"];
		$type_id = $_POST["dev_type"];
		$device_loc = $_POST["dev_loc"];
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
			'is_active' => $is_active,
			'created_by' => $user_id,
			'created_on' => $chicagotime
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
			$_SESSION['mType'] = mTypeError;
			$_SESSION['dispMessage'] = $decoded->message;
			echo json_encode(array("status" => "error" , "message" => $decoded->message));
			exit;
		}else{
			$_SESSION['mType'] = mTypeSucess;
			$_SESSION['dispMessage'] = 'Device created Successfully';
			echo json_encode(array("status" => "success" , "message" => 'Device created Successfully'));
			exit;
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
	<link rel="stylesheet" href="<?php echo $iotURL?>/assets/vendors/mdi/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="<?php echo $iotURL?>/assets/vendors/css/vendor.bundle.base.css">
	<!-- endinject -->
	<!-- Plugin css for this page -->
	<link rel="stylesheet" href="<?php echo $iotURL?>/assets/vendors/flag-icon-css/css/flag-icon.min.css">
	<link rel="stylesheet" href="<?php echo $iotURL?>/assets/vendors/jvectormap/jquery-jvectormap.css">
	<!-- End plugin css for this page -->
	<!-- Layout styles -->
	<link rel="stylesheet" href="<?php echo $iotURL?>/assets/css/demo/style.css">
	<!-- End layout styles -->
	<link rel="shortcut icon" href="<?php echo $iotURL?>/assets/images/favicon.png"/>
</head>
<body>
<script src="<?php echo $iotURL?>/assets/js/preloader.js"></script>
<div class="body-wrapper">
	<?php include('./../../partials/sidebar.html') ?>
	<div class="main-wrapper mdc-drawer-app-content">
		<?php
			$title = "Manage Device Parameter Configuration";
			include('./../../partials/navbar.html') ?>
		<div class="page-wrapper mdc-toolbar-fixed-adjust">
			<main class="content-wrapper">
				<div class="mdc-layout-grid">
					<div class="mdc-layout-grid__inner">
					</div>
				</div>
			</main>
			<?php include('./../../partials/footer.html')?>
		</div>
	</div>
</div>
<!-- plugins:js -->
<script src="<?php echo $iotURL?>/assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<script src="<?php echo $iotURL?>/assets/vendors/chartjs/Chart.min.js"></script>
<script src="<?php echo $iotURL?>/assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
<script src="<?php echo $iotURL?>/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="<?php echo $iotURL?>/assets/js/material.js"></script>
<script src="<?php echo $iotURL?>/assets/js/misc.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="<?php echo $iotURL?>/assets/js/dashboard.js"></script>
<!-- End custom js for this page-->
</body>
</html>