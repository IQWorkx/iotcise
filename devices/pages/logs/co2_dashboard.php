<?php
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
			$title = "Manage Device Type";
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