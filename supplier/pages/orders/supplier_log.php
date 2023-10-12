<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
{
    header('Location: ./config/403.php');
}
require "../../../assets/vendors/autoload.php";
use Firebase\JWT\JWT;
$status = '0';
$message = "";
include("../../config.php");
$user_id = $_SESSION["id"];
$chicagotime = date("Y-m-d H:i:s");
$temp = "";

$timestamp = date('H:i:s');
$message = date("Y-m-d H:i:s");
$chicagotime = date("d-m-Y");
$role = $_SESSION['role_id'];
$user_id = $_SESSION["id"];
$heading = 'Supplier Logs';
$_SESSION['supplier'] = "";
$_SESSION['button'] = "";
$_SESSION['timezone'] = "";
if (count($_POST) > 0) {
    $_SESSION['supplier'] = $_POST['supplier'];
    $_SESSION['date_from'] = $_POST['date_from'];
    $_SESSION['date_to'] = $_POST['date_to'];
    $_SESSION['timezone'] = $_POST['timezone'];
    $supplier = $_POST['supplier'];
    $dateto = $_POST['date_to'];
    $datefrom = $_POST['date_from'];
    $timezone = $_POST['timezone'];
}
if(empty($dateto)){
    $curdate = date('Y-m-d');
    $dateto = $curdate;
}

if(empty($datefrom)){
    $yesdate = date('Y-m-d',strtotime("-1 days"));
    $datefrom = $yesdate;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Supplier Log</title>
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
        $title = "Supplier Log";
        include('../../partials/navbar.html') ?>
        <div class="mdc-layout-grid">
            <form action="" id="user_form" class="form-horizontal" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card">
                            <div class="">
                                <div class="card-header">
                                    <span class="main-content-title mg-b-0 mg-b-lg-1"></span>
                                </div>
                                <div class="pd-30 pd-sm-20">
                                    <div class="row row-xs">
                                        <div class="col-md-1">
                                            <label class="form-label mg-b-0">Supplier</label>
                                        </div>
                                        <div class="col-md-4 mg-t-10 mg-md-t-0">
                                            <select name="supplier" id="supplier" class="form-control select2" data-placeholder="Select Supplier">
                                                <option value="" selected> Select Supplier </option>
                                                <?php
                                                $st_dashboard = $_POST['supplier'];
                                                $sql1 = "SELECT * FROM `sup_account_users` order by sup_id asc";
                                                $result1 = mysqli_query($sup_db,$sql1);
                                                while ($row1 = $result1->fetch_assoc()) {
                                                    if($st_dashboard == $row1['sup_id'])
                                                    {
                                                        $entry = 'selected';
                                                    }
                                                    else
                                                    {
                                                        $entry = '';

                                                    }
                                                    echo "<option value='" . $row1['sup_id'] . "'  $entry>" . $row1['user_name'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="pd-30 pd-sm-20">
                                    <div class="row row-xs">
                                        <div class="col-md-1">
                                            <label class="form-label mg-b-0">Date From</label>
                                        </div>
                                        <div class="col-md-4 mg-t-10 mg-md-t-0">
                                            <div class="input-group">

                                                <input type="date" class="form-control fc-datepicker" name="date_from" id="date_from" value="<?php echo $datefrom; ?>" placeholder="MM-DD-YYYY" type="text">
                                            </div>
                                        </div>
                                        <div class="col-md-1 "></div>
                                        <div class="col-md-1">
                                            <label class="form-label mg-b-0">Date To</label>
                                        </div>
                                        <div class="col-md-4 mg-t-10 mg-md-t-0">
                                            <div class="input-group">

                                                <input type="date" class="form-control fc-datepicker" name="date_to" id="date_to" value="<?php echo $dateto; ?>" placeholder="MM-DD-YYYY" type="text">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pt-0">
                                    <button type="submit" class="btn btn-primary pd-x-30 mg-r-5 mg-t-5 submit_btn">Submit</button>
                                    <button type="button" class="btn btn-primary mg-t-5" onclick="window.location.reload();">Reset</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
                            <th> Order No </th>
                            <th> Order Name </th>
                            <th> Order Desc </th>
                            <th> Invoice Amount </th>
                            <th> Ordered On </th>
                            <th> Order Status </th>
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
                                ?>
                                <td><label class="ckbox"><input type="checkbox" id="delete_check[]"
                                                                name="delete_check[]"
                                                                value="<?php echo $rowc["device_id"]; ?>"><span></span></label>
                                </td>
                                <td class="">
                                    <a href="edit_device.php?device_id=<?php echo $rowc["device_id"]; ?>"
                                       class="edit-btn">EDIT DEVICE
                                        <!--                                                <i class="fa fa-pencil-alt"></i>-->
                                        <!--                                                <i class="material-icons mdc-button__icon" style="font-size: large">edit</i>-->
                                    </a> &emsp;
                                    <a href="../../notification/edit_email_notification.php?device_id=<?php echo $rowc["device_id"]; ?>"
                                       class="edit-btn">EDIT EMAIL
                                    </a>
                                </td>
                                <td><?php echo $rowc["device_name"]; ?></td>
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