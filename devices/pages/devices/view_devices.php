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

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>IOT Devices</title>
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
    <link rel="stylesheet" href="<?php echo $iotURL; ?>assets/css/common.css"/>
    <!-- End layout styles -->
    <link rel="shortcut icon" href="<?php echo $iotURL ?>/assets/images/favicon.png"/>

</head>
<body>
<script src="<?php echo $iotURL ?>/assets/js/preloader.js"></script>
<div class="body-wrapper">
	<?php include('./../../partials/sidebar.html') ?>
    <div class="main-wrapper mdc-drawer-app-content">
		<?php
			$title = "View Devices";
			include('./../../partials/navbar.html') ?>
        <div class="page-wrapper mdc-toolbar-fixed-adjust">
            <main class="content-wrapper">
                <div class="mdc-layout-grid">
                    <form action="" id="up-iot-device" method="post" class="form-horizontal"
                          enctype="multipart/form-data">
                        <!--                        <div class="col-md-offset-1 col-md-12">-->
                        <?php
                        
                        if (!empty($_SESSION['import_status_message']) && ($_SESSION['message_stauts_class'] == 'alert-success')) {
                            echo '<div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-10">
                        <p class="mdc-typography mdc-theme--success">'.$_SESSION['import_status_message'].'</p>
                      </div>';
                        }else if(!empty($import_status_message) && ($import_status_message == 'alert-danger')){
                            echo '<div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-10">
                        <p class="mdc-typography mdc-theme--secondary">' . $_SESSION['import_status_message'] . '</p>
                      </div>';
                        }else{
							echo '<div class='.$_SESSION['message_stauts_class'].'>' . $_SESSION['import_status_message'] . '</div>';
                        }
							unset($_SESSION['message_stauts_class']);
							unset($_SESSION['import_status_message']);
                        ?>
                        <div class="panel">
                            <div class="panel-heading">
                                <div class="mdc-layout-grid__inner form_bg">
                                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-1-desktop">
                                        <button onclick="deleteDevices('delete_device.php')" type="button"
                                                class="pull-left mdc-button mdc-button--raised icon-button filled-button--secondary mdc-ripple-upgraded"
                                                style="--mdc-ripple-fg-size: 21px; --mdc-ripple-fg-scale: 2.900556583115782; --mdc-ripple-fg-translate-start: 3.58203125px, 7.8125px; --mdc-ripple-fg-translate-end: 7.5px, 7.5px;">
                                            <i class="material-icons mdc-button__icon">delete</i>
                                        </button>
                                    </div>
                                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-5-desktop">
                                        <input type="text" class="ptab_search" id="ptab_search"
                                               placeholder="Type to search">
                                    </div>
                                    <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-6-desktop">
                                            <span class="form-horizontal pull-right">
                                                <div class="form-group">
                                                    <label>Show : </label>
                                                    <?php
														$tab_num_rec = (empty($_POST['tab_rec_num']) ? 10 : $_POST['tab_rec_num']);
														$pg = (empty($_POST['pg_num']) ? 0 : ($_POST['pg_num'] - 1));
														$start_index = $pg * $tab_num_rec;
													?>
                                                    <input type="hidden" id='tab_rec_num'
                                                           value="<?php echo $tab_num_rec ?>">
                                                    <input type="hidden" id='curr_pg' value="<?php echo $pg ?>">
                                                    <select id="num_tab_rec" class="ptab_search">
                                                        <option value="10" <?php echo ($tab_num_rec == 10) ? 'selected' : '' ?>>10</option>
                                                        <option value="25" <?php echo ($tab_num_rec == 25) ? 'selected' : '' ?>>25</option>
                                                        <option value="50" <?php echo ($tab_num_rec == 50) ? 'selected' : '' ?>>50</option>
                                                    </select>
                                                </div>
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body table-responsive">
                                <table class="table">
                                    <thead>
                                    <th>
                                        <label class="ckbox"> <input type="checkbox" id="checkAll"><span></span></label>
                                    </th>
                                    <th>Action</th>
                                    <th>Device Name</th>
                                    <th>Device Type</th>
                                    <th>Device Location</th>
                                    <th>Active</th>

                                    </thead>
                                    <tbody id="tbody">
                                    <tr>
										<?php
											$index_left = 1;
											$index_right = 2;
											$c_query = "SELECT count(*) as tot_count FROM  iot_devices where is_deleted != 1";
											$c_qur = mysqli_query($iot_db, $c_query);
											$c_rowc = mysqli_fetch_array($c_qur);
											$tot_devices = $c_rowc['tot_count'];
											$query = "SELECT * FROM  iot_devices where is_deleted != 1  LIMIT " . $start_index . ',' . $tab_num_rec;
											$qur = mysqli_query($iot_db, $query);

                                        while ($rowc = mysqli_fetch_array($qur)) {
                                        $t_id = $rowc["type_id"];
                                        $query1 ="SELECT `dev_type_name` FROM `iot_device_type` WHERE `type_id` = '$t_id'";
                                        $res = mysqli_query($iot_db,$query1);
                                        $rown = mysqli_fetch_array($res);
                                        $dev_type_name = $rown['dev_type_name'];

                                        ?>

                                        <td><label class="ckbox"><input type="checkbox" id="delete_check[]"
                                                                        name="delete_check[]"
                                                                        value="<?php echo $rowc["device_id"]; ?>"><span></span></label>
                                        </td>
                                        <td class="">
                                            <a href="edit_device.php?device_id=<?php echo $rowc["device_id"]; ?>"
                                               class="mdc-button mdc-button--raised">EDIT DEVICE</a> &emsp;
                                            <a href="../../notification/edit_email_notification.php?device_id=<?php echo $rowc["device_id"]; ?>"
                                               class="mdc-button mdc-button--raised">EDIT EMAIL</a>
                                        </td>
                                        <td><?php echo $rowc["device_name"]; ?></td>

                                        <td><?php echo $dev_type_name; ?></td>
                                        <td><?php echo $rowc["device_location"]; ?></td>

                                        <td>
											<?php
												if ($rowc["is_active"] == 1) {
													echo 'Yes';
												} else {
													echo 'No';
												}
											?>
                                        </td>
                                    </tr>
									<?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="panel-footer">
                                <div class="mdc-layout-grid__inner form_bg">
									<?php
										$remainder = $tot_devices % $tab_num_rec;
										$quotient = ($tot_devices - $remainder) / $tab_num_rec;
										$tot_pg = (($remainder == 0) ? $quotient : ($quotient + 1));
										$curr_page = ($pg + 1);
									?>
                                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">Page <b><?php echo $curr_page ?></b> of
                                        <b><?php echo $tot_pg ?></b></div>
                                    <!--                                        <div class="col-sm-4 col-xs-6" style="text-align: center">Page - -->
									<?php //echo $curr_page; ?><!--</div>-->
                                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4-desktop">Go To Page -
                                        <input id="num_tab_pg" class="ptab_goto_num" type="number" min="1"
                                               value='<?php echo $curr_page ?>'/>
                                    </div>
                                    <!--                                        <div class="col-sm-4 col-xs-6" style="text-align: center">Go To Page --->
                                    <!--                                            <select id="num_tab_pg" class="ptab_search">-->
                                    <!--												--><?php
										//													for ($y = 1; $y <= $tot_pg; $y++) {
										//														if($y == $curr_page){
										//															echo "<option value='$y' selected>$y</option>";
										////															echo "<li" . " class='active'" . "><a class='tab_pg' id='tab_pg_$x' val='$x' >$x</a></li>";
										//														}else{
										//															echo "<option value='$y'>$y</option>";
										//														}
										//													}
										//												?>
                                    <!--                                            </select>-->
                                    <!--                                        </div>-->
                                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                                    </div>
                                    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-2-desktop">
                                        <ul class="pagination hidden-xs pull-right">
											<?php
												
												$xx = (($curr_page - 2) > 0) ? ($curr_page - 2) : 1;
												$zz = (($curr_page + 2) < $tot_pg) ? ($curr_page + 2) : $tot_pg;
												if ($curr_page > 1) {
													$pPg = $xx - 1;
													echo "<li><a <a id='prev_pg' val='$pPg'>«</a></li>";
												}
												for ($x = $xx; $x <= $zz; $x++) {
													if ($x == $curr_page) {
														echo "<li" . " class='active'" . "><a class='tab_pg' id='tab_pg_$x' val='$x' >$x</a></li>";
													} else {
														echo "<li><a class='tab_pg'  id='tab_pg_$x' val='$x' >$x</a></li>";
													}
												}
												if ($curr_page < $tot_pg) {
													$nPg = $zz + 1;
													echo "<li><a id='next_pg' val='$nPg'>»</a></li>";
												}
											?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--                        </div>-->
                    </form>
                </div>
            </main>
			<?php include('./../../partials/footer.html') ?>
        </div>
    </div>
</div>
<!-- plugins:js -->
<script src="<?php echo $iotURL ?>/assets/vendors/js/vendor.bundle.base.js"></script>
<!-- endinject -->
<!-- Plugin js for this page-->
<script src="<?php echo $iotURL ?>/assets/vendors/chartjs/Chart.min.js"></script>
<script src="<?php echo $iotURL ?>/assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
<script src="<?php echo $iotURL ?>/assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- End plugin js for this page-->
<!-- inject:js -->
<script src="<?php echo $iotURL ?>/assets/js/material.js"></script>
<script src="<?php echo $iotURL ?>/assets/js/misc.js"></script>
<!-- endinject -->
<script>
    function deleteDevices(url) {
        $(':input[type="button"]').prop('disabled', true);
        var data = $("#up-iot-device").serialize();
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function (data) {
                // window.location.href = window.location.href + "?aa=Line 1";
                $(':input[type="button"]').prop('disabled', false);
                location.reload();
            }
        });
    }

    $("#num_tab_rec").change(function (e) {
        e.preventDefault();
        $(':input[type="button"]').prop('disabled', true);
        var data = "tab_rec_num=" + this.value;
        $.ajax({
            type: 'POST',
            data: data,
            url: 'view_devices.php',
            success: function (data) {
                $("body").html(data);
            }
        });
    });

    $("[id^='tab_pg']").click(function (e) {
        e.preventDefault();
        var tab_num = document.getElementById('tab_rec_num').value;
        var data = "tab_rec_num=" + tab_num + "&pg_num=" + this.text;
        $.ajax({
            type: 'POST',
            data: data,
            url: 'view_devices.php',
            success: function (data) {
                $("body").html(data);
            }
        });
    });

    $("#next_pg").click(function (e) {
        e.preventDefault();
        var tab_num = document.getElementById('tab_rec_num').value;
        var pg_num = document.getElementById('curr_pg').value;
        var nPage = 1;
        if (pg_num != null) {
            nPage = (parseInt(pg_num) + 2);
        }
        var data = "tab_rec_num=" + tab_num + "&pg_num=" + nPage;
        $.ajax({
            type: 'POST',
            data: data,
            url: 'view_devices.php',
            success: function (data) {
                $("body").html(data);
            }
        });
    });

    $("#prev_pg").click(function (e) {
        e.preventDefault();
        var tab_num = document.getElementById('tab_rec_num').value;
        var pg_num = document.getElementById('curr_pg').value;
        // var nPage = 1;
        // if(pg_num != null){
        //     nPage = (parseInt(pg_num) - 1);
        // }
        var data = "tab_rec_num=" + tab_num + "&pg_num=" + pg_num;
        // var data = "tab_rec_num="+ tab_num +"&pg_num="+ this.text;
        $.ajax({
            type: 'POST',
            data: data,
            url: 'view_devices.php',
            success: function (data) {
                $("body").html(data);
            }
        });
    });

    $("#num_tab_pg").change(function () {
        // e.preventDefault();
        var tab_num = document.getElementById('tab_rec_num').value;
        var pg_num = this.value;
        var data = "tab_rec_num=" + tab_num + "&pg_num=" + pg_num;
        $.ajax({
            type: 'POST',
            data: data,
            url: 'view_devices.php',
            success: function (data) {
                $("body").html(data);
            }
        });
    });

    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    var $rows = $('#tbody tr');
    $('#ptab_search').keyup(function () {
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

        $rows.show().filter(function () {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });
</script>
</body>
</html>