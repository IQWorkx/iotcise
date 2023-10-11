<?php
header('Content-Type: application/json');
include("../config.php");
$chicagotime = date("Y-m-d H:i:s");
$curdate = date('Y-m-d');
$dateto = $curdate;
$datefrom = $curdate;
$button = "";
$temp = "";

$_SESSION['date_from'] = "";
$_SESSION['date_to'] = "";
$_SESSION['button'] = "";
$_SESSION['timezone'] = "";
$_SESSION['device_id'] = "";

if (count($_POST) > 0) {
    $_SESSION['device_id'] = $_POST['device_id'];
    $_SESSION['date_from'] = $_POST['date_from'];
    $_SESSION['date_to'] = $_POST['date_to'];
    $_SESSION['button'] = $_POST['button'];
    $_SESSION['timezone'] = $_POST['timezone'];
    $device_id = $_SESSION['device_id'];
    $dateto = $_POST['date_to'];
    $datefrom = $_POST['date_from'];
    $button = $_POST['button'];
    $timezone = $_POST['timezone'];
}
$p_id = $_GET['p_id'];
//NEW VCODE
if($p_id == 1){
	$query = ("SELECT live_data.dev_id, live_data.temperature,DATE_FORMAT(live_data.create_date, '%d-%M-%Y %H:%i') as dTime,device_parameter_config.upper_tolerance,device_parameter_config.lower_tolerance FROM live_data INNER JOIN device_parameter_config on device_parameter_config.device_id = live_data.device_id where live_data.device_id = '$device_id' and DATE_FORMAT(live_data.create_date, '%Y-%m-%d') BETWEEN '$datefrom' and '$dateto' and device_parameter_config.p_id = '1' ORDER BY `dev_id` DESC");
}else if($p_id == 2){
	$query = ("SELECT live_data.dev_id, live_data.humidity,DATE_FORMAT(live_data.create_date, '%d-%M-%Y %H:%i') as dTime,device_parameter_config.upper_tolerance,device_parameter_config.lower_tolerance FROM live_data INNER JOIN device_parameter_config on device_parameter_config.device_id = live_data.device_id where live_data.device_id = '$device_id' and DATE_FORMAT(live_data.create_date, '%Y-%m-%d') BETWEEN '$datefrom' and '$dateto' and device_parameter_config.p_id = '2' ORDER BY `dev_id` DESC");
}else if($p_id == 3){
    $query = ("SELECT live_data.dev_id, live_data.pressure,DATE_FORMAT(live_data.create_date, '%d-%M-%Y %H:%i') as dTime,device_parameter_config.upper_tolerance,device_parameter_config.lower_tolerance FROM live_data INNER JOIN device_parameter_config on device_parameter_config.device_id = live_data.device_id where live_data.device_id = '$device_id' and DATE_FORMAT(live_data.create_date, '%Y-%m-%d') BETWEEN '$datefrom' and '$dateto' and device_parameter_config.p_id = '3' ORDER BY `dev_id` DESC");
}
else if($p_id == 4){
    $query = ("SELECT live_data.dev_id, live_data.iaq,DATE_FORMAT(live_data.create_date, '%d-%M-%Y %H:%i') as dTime,device_parameter_config.upper_tolerance,device_parameter_config.lower_tolerance FROM live_data INNER JOIN device_parameter_config on device_parameter_config.device_id = live_data.device_id where live_data.device_id = '$device_id' and DATE_FORMAT(live_data.create_date, '%Y-%m-%d') BETWEEN '$datefrom' and '$dateto' and device_parameter_config.p_id = '4' ORDER BY `dev_id` DESC");
}else if($p_id == 5){
    $query = ("SELECT live_data.dev_id, live_data.voc,DATE_FORMAT(live_data.create_date, '%d-%M-%Y %H:%i') as dTime,device_parameter_config.upper_tolerance,device_parameter_config.lower_tolerance FROM live_data INNER JOIN device_parameter_config on device_parameter_config.device_id = live_data.device_id where live_data.device_id = '$device_id' and DATE_FORMAT(live_data.create_date, '%Y-%m-%d') BETWEEN '$datefrom' and '$dateto' and device_parameter_config.p_id = '5' ORDER BY `dev_id` DESC");
}
else if($p_id == 6){
    $query = ("SELECT live_data.dev_id, live_data.co2,DATE_FORMAT(live_data.create_date, '%d-%M-%Y %H:%i') as dTime,device_parameter_config.upper_tolerance,device_parameter_config.lower_tolerance FROM live_data INNER JOIN device_parameter_config on device_parameter_config.device_id = live_data.device_id where live_data.device_id = '$device_id' and DATE_FORMAT(live_data.create_date, '%Y-%m-%d') BETWEEN '$datefrom' and '$dateto' and device_parameter_config.p_id = '6' ORDER BY `dev_id` DESC");
}

//execute query
$result = mysqli_query($iot_db, $query);

//loop through the returned data
$data = array();
foreach ($result as $row) {
    $data[] = $row;
}

//free memory associated with result
$result->close();

//now print the data
print json_encode($data);
