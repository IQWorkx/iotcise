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

$heading = 'Order Shipment';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Order Shipment</title>
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
    <link rel="stylesheet" href="<?php echo $siteURL; ?>assets/css/pag_table.css"/>

    <link rel="stylesheet" href="<?php echo $siteURL; ?>assets/css/select2.min.css"/>

    <link rel="stylesheet" href="<?php echo $siteURL; ?>assets/css/common.css"/>

    <!-- INTERNAL Select2 css -->
    <link href="<?php echo $siteURL; ?>/assets/plugins/select2.min.css" rel="stylesheet" />

    <!-- STYLES CSS -->
    <link href="<?php echo $siteURL; ?>/assets/css/style.css" rel="stylesheet">

    <link href="<?php echo $siteURL; ?>/assets/css/style-dark.css" rel="stylesheet">

    <style>
        .btn-success {
            background-color: #297eb4;
            border-color: #289986;
            color: #fff;
            padding: 1px 9px;
        }
        .material-icons {

            font-size: 18px;

            margin-top: 5px;
        }
    </style>

    <!--    <link href="--><?php //echo $siteURL; ?><!--/assets/css/font-awesome.css" rel="stylesheet">-->
    <!--    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">-->

</head>
<body>
<script src="<?php echo $siteURL ?>/assets/js/preloader.js"></script>
<div class="body-wrapper">
    <?php include('../../partials/sidebar.html') ?>
    <div class="main-wrapper mdc-drawer-app-content">
        <?php
        $title = "Order Shipment";
        include('../../partials/navbar.html') ?>
        <div class="mdc-layout-grid">
            <?php
            if (!empty($import_status_message)) {
                echo '<br/><div class="alert ' . $message_stauts_class . '">' . $import_status_message . '</div>';
            }
            if (!empty($_SESSION['import_status_message'])) {
                echo '<br/><div class="alert ' . $_SESSION['message_stauts_class'] . '">' . $_SESSION['import_status_message'] . '</div>';
            }
            ?>

            <form action="" id="up-supplier-module" method="post" class="form-horizontal" enctype="multipart/form-data">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="mdc-layout-grid__inner form_bg">
                            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-1-desktop">

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
                            <th>S.No</th>
                            <th>Action</th>
                            <th>Order Id</th>
                            <th>Order Name</th>
                            <th>Date</th>
                            </thead>
                            <tbody id="tbody">
                            <tr>
                                <?php
                                $index_left = 1;
                                $index_right = 2;
                                $c_query = "SELECT count(*) as tot_count FROM sup_shipment_details";
                                $c_qur = mysqli_query($sup_db, $c_query);
                                $c_rowc = mysqli_fetch_array($c_qur);
                                $tot_ships = $c_rowc['tot_count'];
                                $query = sprintf("SELECT * FROM sup_shipment_details where  created_by = '$user_id' group by sup_order_id LIMIT " . $start_index . ',' . $tab_num_rec);
                                $qur = mysqli_query($sup_db, $query);
                                while ($rowc = mysqli_fetch_array($qur)) {
                                $shipment_status = $rowc['shipment_status'];
                                if($shipment_status == 1){
                                    $ship = 'Shipped';
                                }else{
                                    $ship = 'Not-Shipped';
                                }
                                $created_by = $rowc['created_by'];
                                $q = sprintf("SELECT * FROM sup_account_users where sup_id = '$created_by'");
                                $qurr = mysqli_query($sup_db, $q);
                                $row2 = mysqli_fetch_array($qurr);
                                $fullname = $row2['u_firstname'] . ' ' . $row2['u_lastname'];
                                ?>
                                <td><?php echo ++$counter; ?></td>
                                <td>
                                    <a class="btn btn-success" href="view_shipped_data.php?id=<?php echo $rowc['sup_order_id']; ?>"><i class="material-icons">visibility</i></a>
                                </td>
                                <td><?php echo $rowc['sup_order_id']; ?></td>
                                <td><?php echo $rowc['ship_order_name']; ?></td>
                                <!--<td><?php /*echo $ship; */?></td>-->
                                <td><?php echo dateReadFormat($rowc['created_on']); ?></td>
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
<script>
    $(function(){
        var dtToday = new Date();

        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();

        var maxDate = year + '-' + month + '-' + day;

        $('#date_to').attr('max', maxDate);
        $('#date_from').attr('max', maxDate);
    });
</script>

<script>
    $("#num_tab_rec").change(function (e) {
        e.preventDefault();
        $(':input[type="button"]').prop('disabled', true);
        var data = "tab_rec_num="+ this.value;
        $.ajax({
            type: 'POST',
            data: data,
            url:'supplier_log.php',
            success: function (data) {
                $("body").html(data);
            }
        });
    });
    $( "[id^='tab_pg']" ).click(function (e){
        e.preventDefault();
        var tab_num = document.getElementById('tab_rec_num').value;
        var data = "tab_rec_num="+ tab_num +"&pg_num="+ this.text;
        $.ajax({
            type: 'POST',
            data: data,
            url:'supplier_log.php',
            success: function (data) {
                $("body").html(data);
            }
        });
    });

    $( "#next_pg" ).click(function (e){
        e.preventDefault();
        var tab_num = document.getElementById('tab_rec_num').value;
        var pg_num = document.getElementById('curr_pg').value;
        var nPage = 1;
        if(pg_num != null){
            nPage = (parseInt(pg_num) + 2);
        }
        var data = "tab_rec_num="+ tab_num +"&pg_num="+ nPage;
        $.ajax({
            type: 'POST',
            data: data,
            url:'supplier_log.php',
            success: function (data) {
                $("body").html(data);
            }
        });
    });


    $( "#prev_pg" ).click(function (e){
        e.preventDefault();
        var tab_num = document.getElementById('tab_rec_num').value;
        var pg_num = document.getElementById('curr_pg').value;
        // var nPage = 1;
        // if(pg_num != null){
        //     nPage = (parseInt(pg_num) - 1);
        // }
        var data = "tab_rec_num="+ tab_num +"&pg_num="+ pg_num;
        // var data = "tab_rec_num="+ tab_num +"&pg_num="+ this.text;
        $.ajax({
            type: 'POST',
            data: data,
            url:'supplier_log.php',
            success: function (data) {
                $("body").html(data);
            }
        });
    });

    $( "#num_tab_pg" ).change(function() {
        // e.preventDefault();
        var tab_num = document.getElementById('tab_rec_num').value;
        var pg_num =  this.value;
        var data = "tab_rec_num="+ tab_num +"&pg_num="+ pg_num;
        $.ajax({
            type: 'POST',
            data: data,
            url:'supplier_log.php',
            success: function (data) {
                $("body").html(data);
            }
        });
    });
</script>
<script>
    $("#checkAll").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    var $rows = $('#tbody tr');
    $('#ptab_search').keyup(function() {
        var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

        $rows.show().filter(function() {
            var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
            return !~text.indexOf(val);
        }).hide();
    });
</script>



</body>




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
</html>