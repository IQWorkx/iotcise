<?php
include("../../config.php");
$heading = 'View Shipped Order';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>View Shipped Order</title>
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
    <link href="<?php echo $siteURL; ?>/assets/css/style-transparent.css" rel="stylesheet">
</head>
<body>
<script src="<?php echo $siteURL ?>/assets/js/preloader.js"></script>
<div class="body-wrapper">
    <?php include('../../partials/sidebar.html') ?>
    <div class="main-wrapper mdc-drawer-app-content">
        <?php
        $title = "View Shipped Order";
        include('../../partials/navbar.html') ?>
        <div class="mdc-layout-grid">
            <form action="" id="user_form" class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <?php
                            $id = $_GET['id'];
                            $sql = sprintf("SELECT * FROM sup_order where sup_order_id = '$id' and is_deleted != 1");
                            $qur = mysqli_query($sup_db, $sql);
                            $row = mysqli_fetch_array($qur);
                            $sup_order_id = $row['sup_order_id'];
                            $order_name = $row['order_name'];
                            $order_desc = $row['order_desc'];
                            $order_status_id = $row['order_status_id'];
                            $created_on = $row['created_on'];
                            $created_by = $row['created_by'];
                            $shipment_details = $row['shipment_details'];
                            $c_id = $row['c_id'];
                            ?>
                            <div class="">
                                <div class="card-header">
                                    <span class="main-content-title mg-b-0 mg-b-lg-1">Order ID - <?php echo $sup_order_id; ?></span>
                                </div>
                                <div class="pd-30 pd-sm-20">
                                    <div class="row row-xs">
                                        <div class="col-md-2">
                                            <label class="form-label mg-b-0">Supplier Name</label>
                                        </div>
                                        <div class="col-md-8 mg-t-10 mg-md-t-0">
                                            <input type="hidden" name="hidden_id" id="hidden_id" value="<?php echo $id; ?>">
                                            <?php
                                            $sql5 = sprintf("SELECT * FROM sup_account_users where sup_id = '$c_id'");
                                            $qur5 = mysqli_query($sup_db, $sql5);
                                            $row5 = mysqli_fetch_array($qur5);
                                            $c_name = $row5['user_name'];
                                            ?>
                                            <input type="text" class="form-control" name="s_name" id="s_name" value="<?php echo $c_name; ?>" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="pd-30 pd-sm-20">
                                    <div class="row row-xs">
                                        <div class="col-md-2">
                                            <label class="form-label mg-b-0">Order Name :</label>
                                        </div>
                                        <div class="col-md-8 mg-t-10 mg-md-t-0">
                                            <input type="text" class="form-control" name="o_name" id="o_name" value="<?php echo $order_name; ?>" disabled>
                                        </div>
                                    </div>
                                </div>

                                <div class="pd-30 pd-sm-20">
                                    <div class="row row-xs">
                                        <div class="col-md-2">
                                            <label class="form-label mg-b-0">Order Description :</label>
                                        </div>
                                        <div class="col-md-8 mg-t-10 mg-md-t-0">
                                            <input type="text" class="form-control" name="o_desc" id="o_desc" value="<?php echo $order_desc; ?>" disabled>

                                        </div>

                                    </div>
                                </div>

                                <div class="pd-30 pd-sm-20">
                                    <div class="row row-xs">
                                        <div class="col-md-2">
                                            <label class="form-label mg-b-0">Order Status :</label>
                                        </div>
                                        <div class="col-md-8 mg-t-10 mg-md-t-0">
                                            <?php
                                            $sql1 = sprintf("SELECT * FROM sup_order_status where sup_order_status_id = '$order_status_id' ");
                                            $qur1 = mysqli_query($sup_db, $sql1);
                                            $row1 = mysqli_fetch_array($qur1);
                                            $sup_order_status = $row1['sup_order_status'];
                                            ?>
                                            <input type="text" class="form-control" name="o_status" id="o_status" value="<?php echo $sup_order_status; ?>" disabled>

                                        </div>

                                    </div>
                                </div>

                                <div class="pd-30 pd-sm-20">
                                    <div class="row row-xs">
                                        <div class="col-md-2">
                                            <label class="form-label mg-b-0">Created By :</label>
                                        </div>
                                        <div class="col-md-8 mg-t-10 mg-md-t-0">
                                            <?php
                                            $sql2 = sprintf("SELECT * FROM cam_users where users_id = '$created_by' and is_deleted != 1");
                                            $qur2 = mysqli_query($db, $sql2);
                                            $row2 = mysqli_fetch_array($qur2);
                                            $full_name = $row2['firstname'] . ' ' . $row2['lastname'];
                                            ?>
                                            <input type="text" class="form-control" name="c_by" id="c_by" value="<?php echo $full_name; ?>" disabled>

                                        </div>

                                    </div>
                                </div>

                                <div class="pd-30 pd-sm-20">
                                    <div class="row row-xs">
                                        <div class="col-md-2">
                                            <label class="form-label mg-b-0">Created On :</label>
                                        </div>
                                        <div class="col-md-8 mg-t-10 mg-md-t-0">
                                            <input type="text" class="form-control" name="c_date" id="c_date" value="<?php echo $created_on; ?>" disabled>

                                        </div>

                                    </div>
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">

                            <div class="">
                                <div class="card-header">
                                    <span class="main-content-title mg-b-0 mg-b-lg-1">Shipment Details</span>
                                </div>
                                <div class="pd-30 pd-sm-20">
                                    <div class="row row-xs">
                                        <div class="col-md-1">
                                            <label class="form-label mg-b-0">Shipment Name :</label>
                                        </div>
                                        <div class="col-md-8 mg-t-10 mg-md-t-0">
                                            <input type="text" class="form-control" name="ship_name" id="ship_name" value="<?php echo $shipment_details; ?>" disabled>

                                        </div>

                                    </div>
                                </div>
                                <div class="pd-30 pd-sm-20">
                                    <?php
                                    $sql3 = sprintf("SELECT * FROM sup_invoice where sup_order_id = '$sup_order_id'");
                                    $qur3 = mysqli_query($sup_db, $sql3);
                                    while($row3 = mysqli_fetch_array($qur3)){
                                    $file_name = $row3['invoice_file'];
                                    $invoice_amount = $row3['invoice_amount'];
                                    ?>
                                    <?php if(!empty($file_name)){ ?>
                                    <div class="row row-xs">

                                        <div class="col-md-1">

                                            <label class="form-label mg-b-0">Invoice :</label>
                                        </div>
                                        <div class="col-md-4 mg-t-10 mg-md-t-0">
                                            <a href="../order_invoices/<?php echo $sup_order_id; ?>/<?php echo $file_name; ?>" target="_blank">
                                            <input type="text" class="form-control" name="att_voice" id="att_voice" value="<?php echo $file_name; ?>" disabled>

                                        </div>
                                        <div class="col-md-1"></div>
                                        <div class="col-md-1">
                                            <label class="form-label mg-b-0">Amount</label>
                                        </div>
                                        <div class="col-md-4 mg-t-10 mg-md-t-0">
                                            <input type="text" class="form-control" name="att_amount" id="att_amount" value="<?php echo $invoice_amount; ?>" disabled>

                                        </div>
                                        <div class="col-md-4" style="font-size: 20px!important;text-align: center;margin-left: -39px;margin-top: 4px;">
                                            <?php echo "(" . payment_currency . ")"; ?>
                                        </div>

                                    </div>
                                        <?php } } ?>
                                </div>
                                <div class="pd-30 pd-sm-20">
                                    <div class="row row-xs">
                                        <div class="col-md-1">
                                            <label class="form-label mg-b-0">Attachments :</label>
                                        </div>
                                        <div class="col-md-8 mg-t-10 mg-md-t-0">
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter Name" required>
                                        </div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
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