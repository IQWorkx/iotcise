<?php include("../../config.php");
if (!isset($_SESSION['user'])) {
    header('location: ../logout.php');
}
$temp = "";
$timestamp = date('H:i:s');
$message = date("Y-m-d H:i:s");
$chicagotime = date("Y-m-d H:i:s");
$role = $_SESSION['role_id'];
$user_id = $_SESSION["id"];
$heading = 'Active Orders';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Active Orders</title>
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
        $title = "Active Orders";
        include('../../partials/navbar.html') ?>
        <div class="mdc-layout-grid">

                    <?php
                    if (!empty($import_status_message)) {
                        echo '<br/><div style="width: 1520px!important;font-size: 18px!important;" class="alert ' . $message_stauts_class . '">' . $import_status_message . '</div>';
                    }
                    ?>
                    <?php
                    if (!empty($_SESSION['import_status_message'])) {
                        echo '<br/><div style="width: 1520px!important;font-size: 18px!important;" class="alert ' . $_SESSION['message_stauts_class'] . '">' . $_SESSION['import_status_message'] . '</div>';
                        $_SESSION['message_stauts_class'] = '';
                        $_SESSION['import_status_message'] = '';
                    }
                    ?>
                    <div class="row">
                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <input type="text" id="search" placeholder="Type to search">
                                    <div class="table-responsive">
                                        <table class="table" id="table">
                                            <tbody>
                                            <?php
                                            $query = sprintf("SELECT * FROM  sup_order  where order_active = 1 and c_id = '$user_id' and is_deleted != 1 order by created_on DESC");
                                            $qur = mysqli_query($sup_db, $query);
                                            while ($rowc = mysqli_fetch_array($qur)) {
                                                $order_id = $rowc['order_id'];
                                                $order_status_id = $rowc['order_status_id'];
                                                $ship_det = $rowc['shipment_details'];
                                                $qurtemp = mysqli_query($sup_db, "SELECT * FROM  sup_order_status where sup_order_status_id  = '$order_status_id' ");
                                                while ($rowctemp = mysqli_fetch_array($qurtemp)) {
                                                    $order_status = $rowctemp["sup_order_status"];
                                                }
                                                ?>
                                                <input hidden id="edit_order_id" name="edit_order_id"
                                                       value="<?php echo $order_id; ?>">
                                                <input hidden id="e_order_status" name="e_order_status"
                                                       value="<?php echo $order_status_id; ?>">

                                                <tr>
                                                    <td><table class="tbl_border" border="1" width="100%">
                                                            <tr>
                                                                <td>
                                                                    <div class="top_row rr">
                                                                        <div class="cdiv">

                                                                            <?php if($order_status_id < 4){ ?>
                                                                                <div class="alert alert-error">
                                                                                    <div class="icon__wrapper">
                                                                                        <span class="mdi mdi-alert-outline"></span>
                                                                                    </div>

                                                                                    <p>Action Pending from your end.Click here to Update.</p>
                                                                                    <a class="mdi mdi-open-in-new open" href="order_edit.php?id=<?php echo $order_id ?>">
                                                                                    </a></div>
                                                                            <?php }elseif($order_status_id == 4){ ?>
                                                                                <div class="alert alert-error">
                                                                                    <div class="icon__wrapper">
                                                                                        <span class="mdi mdi-alert-outline"></span>
                                                                                    </div>

                                                                                    <p>Action Pending from your end.Click here to Update.</p>
                                                                                    <a class="mdi mdi-open-in-new open" href="edit_shipped_file.php?id=<?php echo $order_id ?>">
                                                                                    </a></div>
                                                                            <?php }else{ ?>
                                                                                <div class="alert alert-primary">
                                                                                    <div class="icon__wrapper">
                                                                                        <span class="mdi mdi-alert-outline"></span>
                                                                                    </div>
                                                                                    <p>Click here to view the order details. </p>
                                                                                    <a class="mdi mdi-eye-outline open" href="view_order_data.php?id=<?php echo $rowc['sup_order_id'] ?>">
                                                                                    </a></div>
                                                                            <?php } ?>

                                                                        </div>
                                                                    </div>
                                                                    <div class="top_row rr">
                                                                        <div class="cdiv"> <p class="font-weight-bold">Order Number : <span class="text-primary font-weight-bold"><?php echo $rowc['sup_order_id']; ?></span></p>
                                                                        </div>
                                                                        <div class="cdiv"><p class="font-weight-bold">Order Name : <span class="text-primary font-weight-bold"><?php echo $rowc['order_name']; ?></span></p>
                                                                        </div>
                                                                        <div class="cdiv"><p class="font-weight-bold">Order Date : <span class="text-primary font-weight-bold"><?php echo dateReadFormat($rowc['created_on']); ?></span></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="top_row rr">
                                                                        <div class="rdiv"><p class="font-weight-bold">Order Description : <span class="text-primary font-weight-bold"><?php echo $rowc['order_desc']; ?></span></p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="top_row">

                                                                    </div>
                                                                    <div class="top_row">
                                                                        <div class="rdiv">
                                                                            <div class="row d-flex justify-content-center">
                                                                                <div class="col-12">
                                                                                    <ul id="progressbar" class="text-center">
                                                                                        <li class="active step0"></li>
                                                                                        <li class="<?php if($order_status_id >= 2 ) echo 'active '?> step0"></li>
                                                                                        <li class="<?php if($order_status_id >= 3 ) echo 'active '?> step0"></li>
                                                                                        <li class="<?php if($order_status_id >= 4 ) echo 'active '?> step0"></li>
                                                                                        <li class="<?php if($order_status_id >= 5 ) echo 'active '?>step0"></li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                            <div class="row justify-content-between top">
                                                                                <div class="row d-flex icon-content">
                                                                                    <img class="icon" src="<?php echo $siteURL?>assets/images/icon/9nnc9Et.png">
                                                                                    <div class="d-flex flex-column">
                                                                                        <p class="font-weight-bold">Order<br>Placed</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row d-flex icon-content">
                                                                                    <img class="icon" src="<?php echo $siteURL?>assets/images/icon/9nnc9Et.png">
                                                                                    <div class="d-flex flex-column">
                                                                                        <p class="font-weight-bold">Order<br>Acknowledged</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row d-flex icon-content">
                                                                                    <img class="icon" src="<?php echo $siteURL?>assets/images/icon/u1AzR7w.png">
                                                                                    <div class="d-flex flex-column">
                                                                                        <p class="font-weight-bold">Order<br>Processed</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row d-flex icon-content">
                                                                                    <img class="icon" src="<?php echo $siteURL?>assets/images/icon/TkPm63y.png">
                                                                                    <div class="d-flex flex-column">
                                                                                        <p class="font-weight-bold">Order<br>Shipped</p>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="row d-flex icon-content">
                                                                                    <img class="icon" src="<?php echo $siteURL?>assets/images/icon/HdsziHP.png">
                                                                                    <div class="d-flex flex-column">
                                                                                        <p class="font-weight-bold">Order<br>Received</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- content-wrapper ends -->

                </div>
                <!-- main-panel ends -->
            </div>

        </div>
    </div>
</div>
<script>
    var $rows = $('#table tr');
    $('#search').keyup(function() {
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

        $rows.show().filter(function() {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });

    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->


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
