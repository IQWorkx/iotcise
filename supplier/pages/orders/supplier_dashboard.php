<?php include("../../config.php");

?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Supplier Dashboard</title>
        <!-- plugins:css -->
        <link rel="stylesheet" href="<?php echo $siteURL ?>/assets/vendors/mdi/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="<?php echo $siteURL ?>/assets/vendors/css/vendor.bundle.base.css">
        <!-- endinject -->
        <!-- Plugin css for this page -->
        <link rel="stylesheet" href="<?php echo $siteURL ?>/assets/vendors/flag-icon-css/css/flag-icon.min.css">
        <link rel="stylesheet" href="<?php echo $siteURL ?>/assets/vendors/jvectormap/jquery-jvectormap.css">
        <!-- End plugin css for this page -->
        <!-- Layout styles -->
        <link rel="stylesheet" href="<?php echo $siteURL ?>/assets/css/demo/style.css">


        <link rel="stylesheet" href="<?php echo $siteURL; ?>assets/css/select2.min.css"/>

        <link rel="stylesheet" href="<?php echo $siteURL; ?>assets/css/common.css"/>

        <!-- INTERNAL Select2 css -->
        <link href="<?php echo $siteURL; ?>/assets/plugins/select2.min.css" rel="stylesheet" />

        <!-- STYLES CSS -->
        <link href="<?php echo $siteURL; ?>/assets/css/style.css" rel="stylesheet">
        <!---->
        <!--    <link href="--><?php //echo $siteURL; ?><!--/assets/css/style-dark.css" rel="stylesheet">-->
        <!--    <link href="--><?php //echo $siteURL; ?><!--/assets/css/style-transparent.css" rel="stylesheet">-->
        <link href="<?php echo $siteURL; ?>/assets/css/order_track.css">
    </head>
<body>
    <script src="<?php echo $siteURL ?>/assets/js/preloader.js"></script>
<div class="body-wrapper">
<?php include('../../partials/sidebar.html') ?>
    <div class="main-wrapper mdc-drawer-app-content">
<?php
$title = "Supplier Dashboard";
include('../../partials/navbar.html') ?>
        <div class="mdc-layout-grid">
        </div>
    </div>
</div>


        <!-- plugins:js -->
        <script src="<?php echo $siteURL ?>/assets/vendors/js/vendor.bundle.base.js"></script>
        <!-- endinject -->
        <!-- Plugin js for this page-->
        <script src="<?php echo $siteURL ?>/assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
        <script src="<?php echo $siteURL ?>/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
        <!-- End plugin js for this page-->
        <!-- inject:js -->
        <script src="<?php echo $siteURL ?>/assets/js/material.js"></script>
        <script src="<?php echo $siteURL ?>/assets/js/misc.js"></script>
        <!-- endinject -->


        <script src="<?php echo $siteURL; ?>/assets/js/select2.js"></script>
        <script src="<?php echo $siteURL; ?>/assets/js/select2.full.min.js"></script>

        <!-- date time picker -->
        <script src="<?php echo $siteURL ?>/assets/js/datetimepicker.min.js"></script>
        <script src="<?php echo $siteURL ?>/assets/js/bootstrap-datepicker.js"></script>
        <script src="<?php echo $siteURL ?>/assets/js/datepicker.js"></script>
        <script src="<?php echo $siteURL ?>/assets/js/dtpicker.js"></script>
        <script src="<?php echo $siteURL ?>/assets/js/picker.min.js"></script>
</body>
