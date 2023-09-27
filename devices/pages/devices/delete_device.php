<?php require "../../../assets/vendors/autoload.php";
use Firebase\JWT\JWT;
$status = '0';
$message = "";
include("../../config.php");
//include("../sup_config.php");
$chicagotime = date("Y-m-d H:i:s");
$temp = "";
$delete_check = $_POST['delete_check'];
if(empty($delete_check)){
    $delete_check[0]=$_GET['device_id'];
}
if (!empty($delete_check)){
    $cnt = count($delete_check);
    for ($i = 0; $i < $cnt;$i++) {
        $service_url = $rest_api_uri . "devices/delete_iot_device.php";
        $curl = curl_init($service_url);
        $curl_post_data = array(
            'delete_check' => $delete_check[$i],
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
        }else{
            header('Location: ../../home.php');
        }

    }
}