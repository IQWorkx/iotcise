<?php
require "../../../assets/vendors/autoload.php";

use Firebase\JWT\JWT;

$status = '0';
$message = "";
include("../../config.php");
//include("../sup_config.php");
$chicagotime = date("Y-m-d H:i:s");
$temp = "";
$device_id = $_GET['device_id'];
$temperature_data = '';
$humidity_data = '';
$pressure_data = '';
$iaq_data = '';
$voc_data = '';
$co2_data = '';
$datetime = '';

$timestamp = date('H:i:s');
$message = date("Y-m-d H:i:s");
$chicagotime = date("d-m-Y");

if (empty($dateto)) {
    $curdate = date('Y-m-d');
    $dateto = $curdate;
}

if (empty($datefrom)) {
    $yesdate = date('Y-m-d', strtotime("-1 days"));
    $datefrom = $yesdate;
}


$tab_line = $_SESSION['tab_station'];
$is_tab_login = $_SESSION['is_tab_user'];
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
    if ($_SESSION['is_tab_user'] || $_SESSION['is_cell_login']) {
        header($redirect_tab_logout_path);
    } else {
        header($redirect_logout_path);
    }

//	header('location: ../logout.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IAQ Trend</title>
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
    <!-- End layout styles -->
    <link rel="shortcut icon" href="<?php echo $iotURL ?>/assets/images/favicon.png"/>
    <!--	-->
    <!--    <script type="text/javascript" src="-->
    <?php //echo $iotURL?><!--/assets/js/linegraph_js/jquery.min.js"></script>-->
    <script type="text/javascript" src="<?php echo $iotURL ?>/assets/js/linegraph_js/Chart.min.js"></script>
</head>
<body>
<script src="<?php echo $iotURL ?>/assets/js/preloader.js"></script>
<div class="body-wrapper">
    <?php include('./../../partials/sidebar.html') ?>
    <div class="main-wrapper mdc-drawer-app-content">
        <?php
        $title = "IAQ Trend";
        include('./../../partials/navbar.html') ?>
        <div class="mdc-toolbar-fixed-adjust">
            <!--            <main class="content-wrapper">-->
            <div class="mdc-layout-grid">
                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4 mdc-layout-grid__cell--span-8-tablet">
                    <div class="mdc-card">
                        <form action="" method="post" id="device_settings" enctype="multipart/form-data">
                            <?php
                            $date_from = $_GET['date_from'];
                            $date_to = $_GET['date_to'];
                            ?>
                            <div class="mdc-toolbar-fixed-adjust">
                                <div class="mdc-layout-grid">
                                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop mdc-layout-grid__cell--span-4-tablet">
                                        <span style="padding: 10px 20px 0px 0px;">Date From</span>
                                        <span><input type="date" class="form-control mdc-text-field__input"
                                                     name="date_from" id="date_from" style="float:left;padding: 0px;height: 40px;"
                                                     value="<?php echo $datefrom; ?>" placeholder="Enter Device Name"
                                                     required></span>
                                    </div>
                                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop mdc-layout-grid__cell--span-4-tablet">
                                        <span style="padding: 10px 20px 0px 0px;">Date To</span>
                                        <span><input type="date" class="form-control mdc-text-field__input"
                                                     name="date_from" id="date_from" style="float:left;padding: 0px;height: 40px;"
                                                     value="<?php echo $dateto; ?>" placeholder="Enter Device Name"
                                                     required></span>

                                    </div>
                                </div>
                            </div>

                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12-desktop">
                                <button type="submit" name="submit_btn" id="submit_btn"
                                        class="mdc-button mdc-button--raised">Submit
                                </button>
                                <!-- <button class="mdc-button mdc-button--raised" id="submit_btn">
									 Submit
								 </button>-->
                            </div>


                        </form>
                    </div>
                </div>
            </div>
            <!--            </main>-->
        </div>
        <div class="mdc-toolbar-fixed-adjust">
            <main class="content-wrapper">
                <div class="mdc-layout-grid">
                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4 mdc-layout-grid__cell--span-8-tablet">
                        <div class="mdc-card">
                            <div class="d-flex d-lg-block d-xl-flex justify-content-between">
                                <div>
                                    <h4 class="card-title">IAQ Graph</h4>
                                    <!--                                    <h6 class="card-sub-title">Customers 58.39k</h6>-->
                                </div>
                                <div id="sales-legend" class="d-flex flex-wrap"></div>
                            </div>
                            <div class="chart-container mt-4">
                                <canvas id="mycanvas" height="260"></canvas>
                            </div>
                        </div>
                    </div>

                </div>
            </main>
            <?php include('./../../partials/footer.html') ?>
        </div>
    </div>
</div>
<!-- plugins:js -->
<script src="<?php echo $iotURL ?>assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<script src="<?php echo $iotURL ?>assets/vendors/chartjs/Chart.min.js"></script>
<script src="<?php echo $iotURL ?>assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
<script src="<?php echo $iotURL ?>assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="<?php echo $iotURL ?>assets/js/material.js"></script>
<script src="<?php echo $iotURL ?>assets/js/misc.js"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script>
    $.ajax({
        url: "../../../devices/schedular/livedata.php?p_id=4",
        type: "GET",
        async: false,
        success : function(data){
            console.log(data);

            var dev_id = [];
            var datetime = [];
            var iaq_follower = [];
            var iaq_upp = [];
            var iaq_low = [];

            for(var i in data)
            {
                dev_id.push("" + data[i].dev_id);
                iaq_follower.push(data[i].iaq);
                iaq_upp.push(data[i].upper_tolerance);
                iaq_low.push(data[i].lower_tolerance);
                datetime.push(data[i].dTime);
            }

            var chartdata = {
                labels: datetime,
                datasets: [

                    {
                        label: "iaq",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(29, 202, 255, 0.75)",
                        borderColor: "rgba(29, 202, 255, 1)",
                        pointHoverBackgroundColor: "rgba(29, 202, 255, 1)",
                        pointHoverBorderColor: "rgba(29, 202, 255, 1)",
                        data: iaq_follower
                    },
                    {
                        label: "upper_tolerance",
                        fill: false,
                        lineTension: 0.1,
                        backgroundColor: "rgba(143, 0, 12, 0.75)",
                        borderColor: "rgba(143, 0, 12, 1)",
                        pointHoverBackgroundColor: "rgba(143, 0, 12, 1)",
                        pointHoverBorderColor: "rgba(143, 0, 12, 1)",
                        data: iaq_upp
                    },
                    {
                        label: "lower_tolerance",
                        fill: false,
                        backgroundColor: "rgba(206,38,15,0.75)",
                        borderColor: "rgba(206,38,15, 1)",
                        borderDash: [5, 5],
                        pointHoverBackgroundColor: "rgba(206,38,15, 1)",
                        pointHoverBorderColor: "rgba(206,38,15, 1)",
                        data: iaq_low
                    },

                ]
            };

            var ctx = $("#mycanvas");

            var LineGraph = new Chart(ctx, {
                type: 'line',
                data: chartdata
            });
        },
        error : function(data) {

        }
    });
</script>
<!-- End custom js for this page-->
</body>
</html>