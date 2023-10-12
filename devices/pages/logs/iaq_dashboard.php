<?php require "../../../assets/vendors/autoload.php";
use Firebase\JWT\JWT;
$status = '0';
$message = "";
include("../../config.php");
//include("../sup_config.php");
$chicagotime = date("Y-m-d H:i:s");
$timestamp = date('H:i:s');
$message = date("Y-m-d H:i:s");
$temp = "";
if (empty($dateto)) {
    $curdate = date('Y-m-d');
    $dateto = $curdate;
}

if (empty($datefrom)) {
    $yesdate = date('Y-m-d', strtotime("-1 days"));
    $datefrom = $yesdate;
}
$_SESSION['date_from'] = "";
$_SESSION['date_to'] = "";
if (count($_POST) > 0) {
    $_SESSION['date_from'] = $_POST['date_from'];
    $_SESSION['date_to'] = $_POST['date_to'];
    $_SESSION['timezone'] = $_POST['timezone'];

    $dateto = $_POST['date_to'];
    $datefrom = $_POST['date_from'];
    $timezone = $_POST['timezone'];
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
    <style>
        .chartWrapper {
            position: relative;
            background-color: #ffffff;
        }

        .chartWrapper > canvas {
            position: absolute;
            left: 0;
            top: 0;
            pointer-events: none;
        }

        .height100{
            height: 100%;
        }
        .chartAreaWrapper {
            width: 98%;
            overflow-x: scroll;
        }
        li {
            list-style-type: none;
        }

        .label {
            margin-left: 15px;
            font-family: 'Source Sans Pro', sans-serif;
            color: #666666;
            font-size: 14px;
        }

        #legend{
            width: 100%;
        }
        .legendValue {
            float: left;
            height: 25px;
            padding: 0px 20px 20px 0px;
        }

        .clear {
            /*clear: both*/
        }
    </style>
    <link rel="shortcut icon" href="<?php echo $iotURL ?>/assets/images/favicon.png"/>
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
                            <div class="mdc-toolbar-fixed-adjust">
                                <div class="mdc-layout-grid">
                                    <div class="mdc-layout-grid__inner">
                                        <input type="hidden" name="device_id" value="<?php echo $_GET['id'];?>">
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
                                                         name="date_to" id="date_to" style="float:left;padding: 0px;height: 40px;"
                                                         value="<?php echo $dateto; ?>" placeholder="Enter Device Name"
                                                         required></span>

                                        </div>
                                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-1-desktop mdc-layout-grid__cell--span-2-tablet">
                                            <button type="submit" name="submit_btn" id="submit_btn"
                                                    class="mdc-button mdc-button--raised">Submit
                                            </button>
                                        </div>
                                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop mdc-layout-grid__cell--span-2-tablet">
                        </form>
                        <form action="export_iaq.php" method="post" id="export_excel">
                            <input type="hidden" value="<?php echo  $_GET['id']; ?>" name="device_id" id="device_id">
                            <input type="hidden" value="<?php echo $dateto; ?>" name="dateto" id="dateto">
                            <input type="hidden" value="<?php echo $datefrom; ?>" name="datefrom" id="datefrom">
                            <button type="submit" name="export" id="export"
                                    class="mdc-button mdc-button--raised">
                                Export
                            </button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
        <div class="mdc-toolbar-fixed-adjust">
            <main class="content-wrapper height100">
                <div class="mdc-layout-grid height100">
                    <div class="chartWrapper mdc-layout-grid__cell mdc-layout-grid__cell--span-4 mdc-layout-grid__cell--span-8-tablet">
                        <div class="mdc-card chartAreaWrapper height100">
                            <div class="d-flex d-lg-block d-xl-flex justify-content-between">
                                <div id="legend"></div>
                            </div>
                            <div class="chart-container mt-4">
                                <canvas id="mycanvas" height="500" ></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('input:radio').change(function () {
            var abc = $(this).val()
            //alert(abc)
            if (abc == "button1")
            {
                $('#date_from').prop('disabled', false);
                $('#date_to').prop('disabled', false);
                $('#timezone').prop('disabled', true);
            }
        });
    });
</script>
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
    var data = $("#device_settings").serialize();
    $.ajax({
        type: "POST",
        url: "../../../devices/schedular/livedata.php?p_id=4&&device_id=<?php echo $_GET['id']; ?>",
        data: data,
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
            var dPointWidth = datetime.length * 20;

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
                        //borderDash: [5, 5],
                        pointHoverBackgroundColor: "rgba(206,38,15, 1)",
                        pointHoverBorderColor: "rgba(206,38,15, 1)",
                        data: iaq_low
                    },

                ]
            };

            var ctx = document.getElementById("mycanvas");
            var rectangleSet = false;
            var LineGraph = new Chart(ctx, {
                type: 'line',
                data: chartdata,
                // maintainAspectRatio: false,
                responsive: true,
                options: {
                    legend: {
                        display: false,
                        position: 'top',
                    },
                    legendCallback: function(chart) {
                        var text = [];
                        text.push('<ul class="' + chart.id + '-legend">');
                        for (var i = 0; i < chart.data.datasets.length; i++) {
                            text.push('<li><div class="legendValue"><span style="background-color:' + chart.data.datasets[i].backgroundColor + '">&nbsp;&nbsp;&nbsp;&nbsp;</span>');

                            if (chart.data.datasets[i].label) {
                                text.push('<span class="label">' + chart.data.datasets[i].label + '</span>');
                            }

                            text.push('</div></li><div class="clear"></div>');
                        }

                        text.push('</ul>');

                        return text.join('');
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                fontSize: 12,
                                // display: false,
                                autoSkip: false,
                                maxRotation: 90,
                                minRotation: 90
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                fontSize: 12,
                                stepSize:2,
                                beginAtZero: false
                            }
                        }]
                    }, maintainAspectRatio: false,

                }
            });
            LineGraph.canvas.parentNode.style.height = '480px';
            LineGraph.canvas.parentNode.style.width = dPointWidth + 'px';
            $('#legend').prepend(LineGraph.generateLegend());
        },
        error: function (data) {

        }
    });

</script>
<!-- End custom js for this page-->
</body>
</html>