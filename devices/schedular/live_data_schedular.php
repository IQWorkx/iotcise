<?php
require "./vendor/autoload.php";

use Firebase\JWT\JWT;

$status = '0';
$message = "";
include("config.php");
$chicagotime = date("Y-m-d H:i:s");
$temp = "";


// TODO GET API
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


if (!empty($decoded ->Temperature)) {
    $device_id =  $decoded ->DeviceID;
    $temperature = $decoded ->Temperature;
    $humidity = $decoded ->Humidity;
    $pressure = $decoded ->Pressure;
    $iaq = $decoded ->IAQ;
    $voc = $decoded ->VOC;
    $co2 = $decoded ->CO2;
    $datetime = $decoded ->Date_Time;
}

//TODO POST api
$service_url = $rest_api_uri . "devices/live_device.php";
$curl = curl_init($service_url);
$curl_post_data = array(
    'device_id' => $device_id,
    'temperature' => $temperature,
    'humidity' => $humidity,
    'pressure' => $pressure,
    'iaq' => $iaq,
    'voc' => $voc,
    'co2' => $co2,
    'datetime' => $datetime
);


$secretkey = "SupportPassHTSSgmmi";
$payload = array(
    "author" => "Saargummi to HTS",
    "exp" => time()+1000
);
try{
    $jwt = JWT::encode($payload, $secretkey , 'HS256');
}catch (UnexpectedValueException $e) {
    echo $e->getMessage();
}
$headers = array(
    "Accept: application/json",
    "access-token: " . $jwt . '"',
);


curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
$curl_response = curl_exec($curl);
if ($curl_response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('error occured during curl exec. Additioanl info: ' . var_export($info));
}
curl_close($curl);
$decoded = json_decode($curl_response);
if (isset($decoded->status) && $decoded->status == 'ERROR') {
    die('error occured: ' . $decoded->errormessage);
}


?>