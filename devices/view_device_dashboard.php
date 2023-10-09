<?php
require "../assets/vendors/autoload.php";
use Firebase\JWT\JWT;
$status = '0';
$message = "";
include("config.php");
//include("../sup_config.php");
$ctime = date("Y-m-d H:i:s");
$time = strtotime($ctime);
$time = $time - (2 * 60);
$cdate = date("Y-m-d H:i:s", $time);
$temp = "";
$device_id = $_GET['device_id'];
$temperature_data = '';
$humidity_data = '';
$pressure_data = '';
$iaq_data = '';
$voc_data = '';
$co2_data = '';
$datetime = '';
$is_online = '';
//Set the session duration for 10800 seconds - 3 hours
$duration = auto_logout_duration;
//Read the request time of the user
$time = $_SERVER['REQUEST_TIME'];
//Check the user's session exist or not
if (isset($_SESSION['LAST_ACTIVITY']) && ($time - $_SESSION['LAST_ACTIVITY']) > $duration) {
//Unset the session variables
    session_unset();
//Destroy the session
//    session_destroy();
	header('location:index.php');
    exit;
}
if (!empty($device_id)) {
    $cURLConnection = curl_init();

    curl_setopt($cURLConnection, CURLOPT_URL, 'http://13.214.116.35:3001/environment');
    curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

    $curl_response = curl_exec($cURLConnection);
    if ($curl_response === false) {
        $info = curl_getinfo($cURLConnection);
        curl_close($cURLConnection);
        die('error occured during curl exec. Additioanl info: ' . var_export($info));
    }
    curl_close($cURLConnection);

    $decoded = json_decode($curl_response);


    if (!empty($decoded->Temperature)) {
//			$device_id = $decoded->DeviceID;
        $temperature_data = $decoded->Temperature;
		$temperature_data = getFarenheit($temperature_data);
        $humidity_data = $decoded->Humidity;
        $pressure_data = $decoded->Pressure;
        $iaq_data = $decoded->IAQ;
        $voc_data = $decoded->VOC;
        $co2_data = $decoded->CO2;
		$createdAt = $decoded->Date_Time;
		$dateFrom = new DateTime($createdAt, new DateTimeZone('UTC'));
		$dateFrom->setTimezone(new DateTimeZone('America/Chicago'));
		$datetime = $dateFrom->format('Y-m-d H:i:s');
        $date1 = new DateTime($datetime);
        $date2 = new DateTime($cdate);
        if($date1>$date2){
            $is_online=1;
        }
        else {
            $is_online=0;
        }
    }

}
$temperature[] = $temperature_data;
$humidity[] = $humidity_data;
$pressure[] = $pressure_data;
$iaq[] = $iaq_data;
$voc[] = $voc_data;
$co2[] = $co2_data;

$d1 = new DateTime($datetime);
$d2 = new DateTime($cdate);
$isOnline = false;
if($d1>$d2){
    $isOnline = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="300">
    <title>IOT Devices Home</title>
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
    <link rel="stylesheet" href="<?php echo $iotURL; ?>/assets/css/vDeviceDashboard.css">
<!--    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.0.1/chart.js"></script>-->
    <script src="./../assets/js/canvas.min.js"></script>
    <script src="./../assets/js/canvasChart.min.js"></script>
</head>
<body>
<script src="../assets/js/preloader.js"></script>
<div class="body-wrapper">
	<?php include('./partials/sidebar.html') ?>
    <div class="main-wrapper mdc-drawer-app-content">
		<?php
			$title = "IOT Device";
			include('./partials/navbar.html') ?>
        <div class="page-wrapper mdc-toolbar-fixed-adjust">
            <main class="content-wrapper">
				<?php $sql = "SELECT * FROM `iot_devices` where device_id='$device_id' and is_deleted != 1";
					$result = mysqli_query($iot_db, $sql);
					while ($row = mysqli_fetch_array($result)) {
					$created_date = new DateTime(explode(' ', $row['created_on'])[0]);
					$current_date = new DateTime(date("Y-m-d"));
					$period = get_period_ago($current_date, $created_date);
					$edit_dev_loc = $iotURL . 'devices/pages/devices/edit_device.php?device_id=' . $row['device_id'];
					$d_type_id = $row['type_id'];
					$d_type_sql = "SELECT dev_type_name FROM `iot_device_type` where type_id = '$d_type_id' and  is_deleted != 1";
					$d_type_res = mysqli_fetch_array(mysqli_query($iot_db, $d_type_sql));
				?>
                <div class="mdc-layout-grid">
                    <div class="mdc-layout-grid__inner">
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-3-desktop mdc-layout-grid__cell--span-4-tablet">
                            <div class="mdc-card bg-c-lite-green text-center text-white" style="position: relative;">
                                <div class="row">
                                <div class="bio-row" style="width: 75%!important;text-align: center;margin-bottom: 30px;">
                                    <img src="<?php $iotURL ?>../assets/images/iot_sensor_icon.png"
                                         class="img-radius" alt="Device-Profile-Image">
                                </div>
                                </div>
                                <div class="row">
                                    <div class="bio-row">
                                        <p><span>Name </span>: <?php echo $row['device_name'] ?></p>
                                    </div>
                                    <div class="bio-row">
                                        <p><span>Type </span>: <?php echo $d_type_res['dev_type_name'] ?></p>
                                    </div>
                                    <div class="bio-row">
                                        <p><span>Location </span>: <?php echo $row['device_location'] ?></p>
                                    </div>
                                    <div class="bio-row">
                                        <p><span>Description </span>: <?php echo $row['device_description'] ?></p>
                                    </div>
                                    <div class="bio-row">
                                        <hr style="border-top: 1px solid rgb(255 255 255 / 40%) !important;width: 100%;"/>
                                        <p><?php echo 'Device Added ' . $period ?></p>
                                        <span style="width: 50%;margin: auto;">
                                    <p><span>Edit Device :</span>
                                        <a style="padding: 0% 5%;color: inherit" href="<?php echo $edit_dev_loc ?>">
                                            <i class="material-icons text-white" style="font-size: small">edit</i></a>
                                                </span>
                                    </div>

                                </div>
                                <?php if($isOnline == false){?>
                                <div class="row" id="off_disp">
                                    <p>This Device is Offline<br>
                                        Current Time : <?php echo dateReadFormat($cdate) ?></p>
                                </div>
								<?php }else{?>
                                <div class="row" id="on_disp">
                                    <p>This Device is Online</p>
                                </div>
								<?php }?>
                                <div class="time_det"><p><?php echo 'Time : ' . dateReadFormat($datetime) ?></p></div>
                            </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-9-desktop mdc-layout-grid__cell--span-4-tablet">
                            <div class="mdc-layout-grid__inner">
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop mdc-layout-grid__cell--span-4-tablet shadow-1">
                                <div class="mdc-card info-card info-card--success">
                                    <div class="card-inner">
                                        <div id="myChartDivTit">
                                        <h5 class="card-title">Temperature</h5>
                                        </div>
                                        <div id="myChartDiv">
                                            <canvas id="myChart"></canvas>
                                        </div>
                                        <a href="../devices/pages/logs/temperature_dashboard.php" class="mdc-button mdc-button--raised icon-button mdc-ripple-upgraded" style="z-index: 1;float:right;--mdc-ripple-fg-size: 21px; --mdc-ripple-fg-scale: 2.900556583115782; --mdc-ripple-fg-translate-start: 5.09375px, 11.25px; --mdc-ripple-fg-translate-end: 7.5px, 7.5px;" target="_blank">
                                            <i class="material-icons mdc-button__icon">trending_up</i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop mdc-layout-grid__cell--span-4-tablet shadow-1">
                                <div class="mdc-card info-card info-card--danger">
                                    <div class="card-inner">
                                        <h5 class="card-title">Humidity</h5>
                                        <div id="myChartDiv">
                                            <canvas id="myChart1"></canvas>
                                        </div>
                                        <a  href="../devices/pages/logs/humidity_dashboard.php" class="mdc-button mdc-button--raised icon-button mdc-ripple-upgraded" style="z-index: 1;float:right;--mdc-ripple-fg-size: 21px; --mdc-ripple-fg-scale: 2.900556583115782; --mdc-ripple-fg-translate-start: 5.09375px, 11.25px; --mdc-ripple-fg-translate-end: 7.5px, 7.5px;" target="_blank">
                                            <i class="material-icons mdc-button__icon">trending_up</i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop mdc-layout-grid__cell--span-4-tablet shadow-1">
                                <div class="mdc-card info-card info-card--primary">
                                    <div class="card-inner">
                                        <h5 class="card-title">Pressure</h5>
                                        <div id="myChartDiv">
                                            <canvas id="myChart2"></canvas>
                                        </div>
                                        <a  href="../devices/pages/logs/pressure_dashboard.php" class="mdc-button mdc-button--raised icon-button mdc-ripple-upgraded" style="z-index: 1;float:right;--mdc-ripple-fg-size: 21px; --mdc-ripple-fg-scale: 2.900556583115782; --mdc-ripple-fg-translate-start: 5.09375px, 11.25px; --mdc-ripple-fg-translate-end: 7.5px, 7.5px;" target="_blank">
                                            <i class="material-icons mdc-button__icon">trending_up</i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop mdc-layout-grid__cell--span-4-tablet shadow-1">
                                <div class="mdc-card info-card info-card--success">
                                    <div class="card-inner">
                                        <h5 class="card-title">IAQ</h5>
                                        <div id="myChartDiv">
                                            <canvas id="myChart3"></canvas>
                                        </div>
                                        <a  href="../devices/pages/logs/iaq_dashboard.php" class="mdc-button mdc-button--raised icon-button mdc-ripple-upgraded" style="z-index: 1;float:right;--mdc-ripple-fg-size: 21px; --mdc-ripple-fg-scale: 2.900556583115782; --mdc-ripple-fg-translate-start: 5.09375px, 11.25px; --mdc-ripple-fg-translate-end: 7.5px, 7.5px;" target="_blank">
                                            <i class="material-icons mdc-button__icon">trending_up</i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop mdc-layout-grid__cell--span-4-tablet shadow-1">
                                <div class="mdc-card info-card info-card--danger">
                                    <div class="card-inner">
                                        <h5 class="card-title">VOC</h5>
                                        <div id="myChartDiv">
                                            <canvas id="myChart4"></canvas>
                                        </div>
                                        <a  href="../devices/pages/logs/voc_dashboard.php" class="mdc-button mdc-button--raised icon-button mdc-ripple-upgraded" style="z-index: 1;float:right;--mdc-ripple-fg-size: 21px; --mdc-ripple-fg-scale: 2.900556583115782; --mdc-ripple-fg-translate-start: 5.09375px, 11.25px; --mdc-ripple-fg-translate-end: 7.5px, 7.5px;" target="_blank">
                                            <i class="material-icons mdc-button__icon">trending_up</i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop mdc-layout-grid__cell--span-4-tablet shadow-1">
                                <div class="mdc-card info-card info-card--primary">
                                    <div class="card-inner">
                                        <h5 class="card-title">CO2</h5>
                                        <div id="myChartDiv">
                                            <canvas id="myChart5"></canvas>
                                        </div>
                                        <a  href="../devices/pages/logs/co2_dashboard.php" class="mdc-button mdc-button--raised icon-button mdc-ripple-upgraded" style="z-index: 1;float:right;--mdc-ripple-fg-size: 21px; --mdc-ripple-fg-scale: 2.900556583115782; --mdc-ripple-fg-translate-start: 5.09375px, 11.25px; --mdc-ripple-fg-translate-end: 7.5px, 7.5px;" target="_blank">
                                            <i class="material-icons mdc-button__icon">trending_up</i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </main>
			<?php include('./partials/footer.html')?>
        </div>
    </div>
</div>

<script>
    window.onload = function () {
        history.replaceState("", "", "<?php echo $iotURL; ?>devices/view_device_dashboard.php?device_id=CC:50:E3:BF:B2:5C");
    }
</script>





<script>
    Chart.types.Doughnut.extend({
        name: "DoughnutTextInside",
        showTooltip: function () {
            this.chart.ctx.save();
            Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
            this.chart.ctx.restore();
        },
        draw: function () {
            Chart.types.Doughnut.prototype.draw.apply(this, arguments);

            var width = this.chart.width,
                height = this.chart.height;

            var fontSize = 1.2;
            this.chart.ctx.font = fontSize + "em Verdana";
            this.chart.ctx.textBaseline = "middle";

            var text = <?php echo json_encode($temperature); ?>,
                textX = Math.round((width - this.chart.ctx.measureText(text).width) / 2),
                textY = height / 2;

            this.chart.ctx.fillText(text, textX, textY);
        }
    });

    var data = [{
        value: <?php echo json_encode($temperature); ?>,
        color: "#F7464A"

    }];
    var DoughnutTextInsideChart = new Chart(document.getElementById("myChart").getContext("2d")).DoughnutTextInside(data, { aspectRatio: 2,responsive: true});
</script>


<!----------------->


<script>
    Chart.types.Doughnut.extend({
        name: "DoughnutTextInside",
        showTooltip: function () {
            this.chart.ctx.save();
            Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
            this.chart.ctx.restore();
        },
        draw: function () {
            Chart.types.Doughnut.prototype.draw.apply(this, arguments);

            var width = this.chart.width,
                height = this.chart.height;

            // var fontSize = (height / 200).toFixed(2);
            var fontSize = 1.2;
            this.chart.ctx.font = fontSize + "em Verdana";
            this.chart.ctx.textBaseline = "middle";

            var text = <?php echo json_encode($humidity); ?>,
                textX = Math.round((width - this.chart.ctx.measureText(text).width) / 2),
                textY = height / 2;

            this.chart.ctx.fillText(text, textX, textY);
        }
    });

    var data = [{
        value: <?php echo json_encode($humidity); ?>,
        color: "#1ca2bd"

    }];

    var DoughnutTextInsideChart = new Chart(document.getElementById("myChart1").getContext("2d")).DoughnutTextInside(data, {
        responsive: true
    });
</script>

<!----------------->


<script>
    Chart.types.Doughnut.extend({
        name: "DoughnutTextInside",
        showTooltip: function () {
            this.chart.ctx.save();
            Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
            this.chart.ctx.restore();
        },
        draw: function () {
            Chart.types.Doughnut.prototype.draw.apply(this, arguments);

            var width = this.chart.width,
                height = this.chart.height;

            // var fontSize = (height / 200).toFixed(2);
            var fontSize = 1.2;
            this.chart.ctx.font = fontSize + "em Verdana";
            this.chart.ctx.textBaseline = "middle";

            var text = <?php echo json_encode($pressure); ?>,
                textX = Math.round((width - this.chart.ctx.measureText(text).width) / 2),
                textY = height / 2;

            this.chart.ctx.fillText(text, textX, textY);
        }
    });

    var data = [{
        value: <?php echo json_encode($pressure); ?>,
        color: "#7a2c2c"

    }];

    var DoughnutTextInsideChart = new Chart(document.getElementById("myChart2").getContext("2d")).DoughnutTextInside(data, {
        responsive: true
    });
</script>

<!----------------->

<script>
    Chart.types.Doughnut.extend({
        name: "DoughnutTextInside",
        showTooltip: function () {
            this.chart.ctx.save();
            Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
            this.chart.ctx.restore();
        },
        draw: function () {
            Chart.types.Doughnut.prototype.draw.apply(this, arguments);

            var width = this.chart.width,
                height = this
                    .chart.height;

            // var fontSize = (height / 200).toFixed(2);
            var fontSize = 1.2;
            this.chart.ctx.font = fontSize + "em Verdana";
            this.chart.ctx.textBaseline = "middle";

            var text = <?php echo json_encode($iaq); ?>,
                textX = Math.round((width - this.chart.ctx.measureText(text).width) / 2),
                textY = height / 2;

            this.chart.ctx.fillText(text, textX, textY);
        }
    });

    var data = [{
        value: <?php echo json_encode($iaq); ?>,
        color: "#ff8400"

    }];

    var DoughnutTextInsideChart = new Chart(document.getElementById("myChart3").getContext("2d")).DoughnutTextInside(data, {
        responsive: true
    });
</script>


<!----------------->


<script>
    Chart.types.Doughnut.extend({
        name: "DoughnutTextInside",
        showTooltip: function () {
            this.chart.ctx.save();
            Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
            this.chart.ctx.restore();
        },
        draw: function () {
            Chart.types.Doughnut.prototype.draw.apply(this, arguments);

            var width = this.chart.width,
                height = this.chart.height;

            // var fontSize = (height / 200).toFixed(2);
            var fontSize = 1.2;
            this.chart.ctx.font = fontSize + "em Verdana";
            this.chart.ctx.textBaseline = "middle";

            var text = <?php echo json_encode($voc); ?>,
                textX = Math.round((width - this.chart.ctx.measureText(text).width) / 2),
                textY = height / 2;

            this.chart.ctx.fillText(text, textX, textY);
        }
    });

    var data = [{
        value: <?php echo json_encode($voc); ?>,
        color: "#ff4000"

    }];

    var DoughnutTextInsideChart = new Chart(document.getElementById("myChart4").getContext("2d")).DoughnutTextInside(data, {
        responsive: true
    });
</script>

<!----------------->


<script>
    Chart.types.Doughnut.extend({
        name: "DoughnutTextInside",
        showTooltip: function () {
            this.chart.ctx.save();
            Chart.types.Doughnut.prototype.showTooltip.apply(this, arguments);
            this.chart.ctx.restore();
        },
        draw: function () {
            Chart.types.Doughnut.prototype.draw.apply(this, arguments);

            var width = this.chart.width,
                height = this.chart.height;

            // var fontSize = (height / 200).toFixed(2);
            var fontSize = 1.2;
            this.chart.ctx.font = fontSize + "em Verdana";
            this.chart.ctx.textBaseline = "middle";

            var text = <?php echo json_encode($co2); ?>,
                textX = Math.round((width - this.chart.ctx.measureText(text).width) / 2),
                textY = height / 2;

            this.chart.ctx.fillText(text, textX, textY);
        }
    });

    var data = [{
        value: <?php echo json_encode($co2); ?>,
        color: "#1f7330"

    }];

    var DoughnutTextInsideChart = new Chart(document.getElementById("myChart5").getContext("2d")).DoughnutTextInside(data, {
        responsive: true
    });
</script>
<!-- plugins:js -->
<script src="<?php echo $iotURL?>/assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<!--<script src="--><?php //echo $iotURL?><!--/assets/vendors/chartjs/Chart.min.js"></script>-->
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