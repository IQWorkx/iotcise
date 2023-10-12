<?php include("../../config.php");
$device_id = $_POST['device_id'];
$datefrom = $_POST['datefrom'];
$dateto = $_POST['dateto'];
//retrieve the data from iot devices table
$sqlv = "select * from iot_devices where device_id = '$device_id'";
$resultv = mysqli_query($iot_db,$sqlv);
$rowv = mysqli_fetch_array($resultv);
$device_name = $rowv['device_name'];
//retrieve the data from device parameter config table
$sqlvv = "select * from device_parameter_config where device_id = '$device_id' and p_id = '3'";
$resultvv = mysqli_query($iot_db,$sqlvv);
$rowvv = mysqli_fetch_array($resultvv);
$upper_tolerance = $rowvv['upper_tolerance'];
$lower_tolerance = $rowvv['lower_tolerance'];

$exp = mysqli_query($iot_db,"SELECT pressure,DATE_FORMAT(create_date, '%d-%M-%Y %H:%i') as create_date FROM `live_data` WHERE `device_id` = '$device_id' and create_date >= '$datefrom' and create_date <= '$dateto' order by create_date desc");
$header = "Pressure Value" . "\t" . "Date" . "\t";
$p1 = "Device Id : " .$device_id;
$p2 = "Device Name : " .$device_name;
$p3 = "Upper Tolerance Value : " .$upper_tolerance;
$p4 = "Lower Tolerance Value : " .$lower_tolerance;
$p5 = "Device Data From : " .$datefrom. ' To : ' .$dateto;
while ($row = mysqli_fetch_row($exp)) {
    $line = '';
    $j = 1;
    foreach ($row as $value) {
        if ((!isset($value) ) || ( $value == "" )) {
            $value = "\t";
        } else {
            $value = str_replace('"', '""', $value);
            $value = '"' . $value . '"' . "\t";
        }
        $line .= $value;
        $j++;
    }
    $result .= trim($line) ."\n";
}

$result = str_replace("\r", "", $result);
if ($result == "") {
    $result = "\nNo Record(s) Found!\n";
}
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=".$device_name."_pressure_data.xls");
header("Pragma: no-cache");
header("Expires: 0");
print "\n\n" . $p1 . "\n" . $p2 . "\n" . $p3 . "\n" . $p4 . "\n" . $p5 . "\n\n" . $header . "\n" . $result;
?>
<?php
