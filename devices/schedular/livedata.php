<?php
header('Content-Type: application/json');
include("../config.php");

$timestamp = date('H:i:s');
$message = date("Y-m-d H:i:s");
$chicagotime = date("d-m-Y");

$p_id = $_GET['p_id'];
if(empty($dateto)){
    $curdate = date('Y-m-d');
    $dateto = $curdate;
}

if(empty($datefrom)){
    $yesdate = date('Y-m-d',strtotime("-1 days"));
    $datefrom = $yesdate;
}

//NEW VCODE
if($p_id == 1){
	$query = ("SELECT live_data.dev_id, live_data.temperature,DATE_FORMAT(live_data.datetime, '%d-%M-%Y %H:%i') as dTime,device_parameter_config.upper_tolerance,device_parameter_config.lower_tolerance FROM live_data INNER JOIN iot_devices ON live_data.device_id =iot_devices.device_id INNER JOIN device_parameter_config on iot_devices.device_id = device_parameter_config.device_id where device_parameter_config.p_id = ".$p_id);
}else if($p_id == 2){
	$query = ("SELECT live_data.dev_id, live_data.humidity,DATE_FORMAT(live_data.datetime, '%d-%M-%Y %H:%i') as dTime,device_parameter_config.upper_tolerance,device_parameter_config.lower_tolerance FROM live_data INNER JOIN iot_devices ON live_data.device_id =iot_devices.device_id INNER JOIN device_parameter_config on iot_devices.device_id = device_parameter_config.device_id where device_parameter_config.p_id = ".$p_id);
	
}else if($p_id == 3){
    $query = ("SELECT live_data.dev_id, live_data.pressure,DATE_FORMAT(live_data.datetime, '%d-%M-%Y %H:%i') as dTime,device_parameter_config.upper_tolerance,device_parameter_config.lower_tolerance FROM live_data INNER JOIN iot_devices ON live_data.device_id =iot_devices.device_id INNER JOIN device_parameter_config on iot_devices.device_id = device_parameter_config.device_id where device_parameter_config.p_id = ".$p_id);

}
else if($p_id == 4){
    $query = ("SELECT live_data.dev_id, live_data.iaq,DATE_FORMAT(live_data.datetime, '%d-%M-%Y %H:%i') as dTime,device_parameter_config.upper_tolerance,device_parameter_config.lower_tolerance FROM live_data INNER JOIN iot_devices ON live_data.device_id =iot_devices.device_id INNER JOIN device_parameter_config on iot_devices.device_id = device_parameter_config.device_id where device_parameter_config.p_id = ".$p_id);

}else if($p_id == 5){
    $query = ("SELECT live_data.dev_id, live_data.voc,DATE_FORMAT(live_data.datetime, '%d-%M-%Y %H:%i') as dTime,device_parameter_config.upper_tolerance,device_parameter_config.lower_tolerance FROM live_data INNER JOIN iot_devices ON live_data.device_id =iot_devices.device_id INNER JOIN device_parameter_config on iot_devices.device_id = device_parameter_config.device_id where device_parameter_config.p_id = ".$p_id);

}
else if($p_id == 6){
    $query = ("SELECT live_data.dev_id, live_data.co2,DATE_FORMAT(live_data.datetime, '%d-%M-%Y %H:%i') as dTime,device_parameter_config.upper_tolerance,device_parameter_config.lower_tolerance FROM live_data INNER JOIN iot_devices ON live_data.device_id =iot_devices.device_id INNER JOIN device_parameter_config on iot_devices.device_id = device_parameter_config.device_id where device_parameter_config.p_id = ".$p_id);

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

//close connection

//now print the data
print json_encode($data);
