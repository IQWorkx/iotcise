<?php require "../assets/vendors/autoload.php";
use Firebase\JWT\JWT;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
include("config.php");
$chicagotime = date("Y-m-d H:i:s");
$users_id = 1;
//select number of device
$sqlv = "select * from iot_devices where is_deleted != 1";
$resultv = mysqli_query($iot_db, $sqlv);
while($rowv = mysqli_fetch_array($resultv)){
    $device_id = $rowv['device_id'];
    $device_name = $rowv['device_name'];
    $d_type_id = $rowv['type_id'];
    $device_location = $rowv['device_location'];
    $device_description = $rowv['device_description'];
    $d_type_sql = "SELECT dev_type_name FROM `iot_device_type` where type_id = '$d_type_id' and  is_deleted != 1";
    $d_type_res = mysqli_fetch_array(mysqli_query($iot_db, $d_type_sql));
    $dev_type_name = $d_type_res['dev_type_name'];

    //select all coming live data from device
    $sqlv2 = "SELECT * FROM `live_data` where device_id = '$device_id' order by `dev_id` desc limit 1";
    $resultv2 = mysqli_query($iot_db, $sqlv2);
    $rowv2 = mysqli_fetch_array($resultv2);
    $dev_id = $rowv2['dev_id'];
    $d_id = $rowv2['device_id'];
    $temperature = $rowv2['temperature'];
    $humidity = $rowv2['humidity'];
    $pressure = $rowv2['pressure'];
    $iaq = $rowv2['iaq'];
    $voc = $rowv2['voc'];
    $co2 = $rowv2['co2'];
    $tmp_high_email = $rowv2['tmp_high_email'];
    $tmp_low_email = $rowv2['tmp_low_email'];
    $hum_high_email	 = $rowv2['hum_high_email'];
    $hum_low_email = $rowv2['hum_low_email'];
    $pre_high_email = $rowv2['pre_high_email'];
    $pre_low_email = $rowv2['pre_low_email'];
    $iaq_high_email = $rowv2['iaq_high_email'];
    $iaq_low_email = $rowv2['iaq_low_email'];
    $voc_high_email = $rowv2['voc_high_email'];
    $voc_low_email = $rowv2['voc_low_email'];
    $co2_high_email = $rowv2['co2_high_email'];
    $co2_low_email	 = $rowv2['co2_low_email'];

    //based on one device we select device status
    //if the status is temperature
    $sqlv1 = "select * from device_parameter_config where device_id = '$d_id' and p_id = '1'";
    $resultv1 = mysqli_query($iot_db, $sqlv1);
    $rowv1 = mysqli_fetch_array($resultv1);
    $temp_upper_tolerance = $rowv1['upper_tolerance'];
    $temp_lower_tolerance = $rowv1['lower_tolerance'];
    if($rowv1 == true){
        if($tmp_high_email != 1)
        {
            if($temperature > $temp_upper_tolerance){
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->SMTPAuth = true;
                $mail->Username = EMAIL_USER;
                $mail->Password = EMAIL_PASSWORD;
                $mail->setFrom('admin@plantnavigator.com', 'admin@plantnavigator.com');
                $subject = "Temperature High";
                $message1 = "The Temperature Cross the Upper Tolerance";
                $message2 = "Upper Tolerance Value : " . $temp_upper_tolerance;
                $message3 = "Actual Value  : " . $temperature;
                $message4 = "Device Name : " . $device_name;
                $message5 = "Type : " . $dev_type_name;
                $message6 = "Location : " . $device_location;
                $message7 = "Description : " . $device_description;
                $signature = " - Plantnavigator Admin";
                $structure = '<html><body>';
                $structure .= "<br/><br/><span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > Hello,</span><br/><br/>";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message1 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message2 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message3 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message4 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message5 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message6 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message7 . "</span><br/><br/> ";
                $structure .= "<br/><br/>";
                $structure .= $signature;
                $structure .= "</body></html>";
                if(!empty($users_id)) {
                    $query0003 = sprintf("SELECT * FROM cam_users where users_id = '$users_id'");
                    $qur0003 = mysqli_query($db, $query0003);
                    $rowc0003 = mysqli_fetch_array($qur0003);
                    $email = $rowc0003["email"];
                    $lasname = $rowc0003["lastname"];
                    $firstname = $rowc0003["firstname"];
                    $mail->addAddress($email, $firstname);
                }
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $structure;
                if(!$mail->Send()){
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }else{
                    //echo "success";
                }
                $sqlvv = "update `live_data` set tmp_high_email = '1' where `dev_id` = '$dev_id'";
                $resultvv = mysqli_query($iot_db, $sqlvv);
            }
        }
        if($tmp_low_email != 1)
        {
            if($temperature < $temp_lower_tolerance){
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->SMTPAuth = true;
                $mail->Username = EMAIL_USER;
                $mail->Password = EMAIL_PASSWORD;
                $mail->setFrom('admin@plantnavigator.com', 'admin@plantnavigator.com');
                $subject = "Temperature Low";
                $message1 = "The Temperature has reached below than lower tolerance";
                $message2 = "Lower Tolerance Value : " . $temp_lower_tolerance;
                $message3 = "Actual Value  : " . $temperature;
                $message4 = "Device Name : " . $device_name;
                $message5 = "Type : " . $dev_type_name;
                $message6 = "Location : " . $device_location;
                $message7 = "Description : " . $device_description;
                $signature = " - Plantnavigator Admin";
                $structure = '<html><body>';
                $structure .= "<br/><br/><span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > Hello,</span><br/><br/>";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message1 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message2 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message3 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message4 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message5 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message6 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message7 . "</span><br/><br/> ";
                $structure .= "<br/><br/>";
                $structure .= $signature;
                $structure .= "</body></html>";
                if(!empty($users_id)) {
                    $query0003 = sprintf("SELECT * FROM cam_users where users_id = '$users_id'");
                    $qur0003 = mysqli_query($db, $query0003);
                    $rowc0003 = mysqli_fetch_array($qur0003);
                    $email = $rowc0003["email"];
                    $lasname = $rowc0003["lastname"];
                    $firstname = $rowc0003["firstname"];
                    $mail->addAddress($email, $firstname);
                }
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $structure;
                if(!$mail->Send()){
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }else{
                    //echo "success";
                }
                $sqlvv = "update `live_data` set tmp_low_email = '1' where `dev_id` = '$dev_id'";
                $resultvv = mysqli_query($iot_db, $sqlvv);
            }
        }
    }
    //if the status is Humidity
    $sqlv3 = "select * from device_parameter_config where device_id = '$d_id' and p_id = '2'";
    $resultv3 = mysqli_query($iot_db, $sqlv3);
    $rowv3 = mysqli_fetch_array($resultv3);
    $hum_upper_tolerance = $rowv3['upper_tolerance'];
    $hum_lower_tolerance = $rowv3['lower_tolerance'];
    if($rowv3 == true){
        if($hum_high_email != 1)
        {
            if($humidity > $hum_upper_tolerance){
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->SMTPAuth = true;
                $mail->Username = EMAIL_USER;
                $mail->Password = EMAIL_PASSWORD;
                $mail->setFrom('admin@plantnavigator.com', 'admin@plantnavigator.com');
                $subject = "Humidity High";
                $message1 = "The Humidity Cross the Upper Tolerance";
                $message2 = "Upper Tolerance Value : " . $hum_upper_tolerance;
                $message3 = "Actual Value  : " . $humidity;
                $message4 = "Device Name : " . $device_name;
                $message5 = "Type : " . $dev_type_name;
                $message6 = "Location : " . $device_location;
                $message7 = "Description : " . $device_description;
                $signature = " - Plantnavigator Admin";
                $structure = '<html><body>';
                $structure .= "<br/><br/><span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > Hello,</span><br/><br/>";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message1 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message2 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message3 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message4 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message5 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message6 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message7 . "</span><br/><br/> ";
                $structure .= "<br/><br/>";
                $structure .= $signature;
                $structure .= "</body></html>";
                if(!empty($users_id)) {
                    $query0003 = sprintf("SELECT * FROM cam_users where users_id = '$users_id'");
                    $qur0003 = mysqli_query($db, $query0003);
                    $rowc0003 = mysqli_fetch_array($qur0003);
                    $email = $rowc0003["email"];
                    $lasname = $rowc0003["lastname"];
                    $firstname = $rowc0003["firstname"];
                    $mail->addAddress($email, $firstname);
                }
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $structure;
                if(!$mail->Send()){
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }else{
                    //echo "success";
                }
                $sqlvv = "update `live_data` set hum_high_email = '1' where `dev_id` = '$dev_id'";
                $resultvv = mysqli_query($iot_db, $sqlvv);
            }
        }
        if($hum_low_email != 1)
        {
            if($humidity < $hum_lower_tolerance){
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->SMTPAuth = true;
                $mail->Username = EMAIL_USER;
                $mail->Password = EMAIL_PASSWORD;
                $mail->setFrom('admin@plantnavigator.com', 'admin@plantnavigator.com');
                $subject = "Humidity Low";
                $message1 = "The Humidity has reached below than lower tolerance";
                $message2 = "Lower Tolerance Value : " . $hum_lower_tolerance;
                $message3 = "Actual Value  : " . $humidity;
                $message4 = "Device Name : " . $device_name;
                $message5 = "Type : " . $dev_type_name;
                $message6 = "Location : " . $device_location;
                $message7 = "Description : " . $device_description;
                $signature = " - Plantnavigator Admin";
                $structure = '<html><body>';
                $structure .= "<br/><br/><span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > Hello,</span><br/><br/>";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message1 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message2 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message3 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message4 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message5 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message6 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message7 . "</span><br/><br/> ";
                $structure .= "<br/><br/>";
                $structure .= $signature;
                $structure .= "</body></html>";
                if(!empty($users_id)) {
                    $query0003 = sprintf("SELECT * FROM cam_users where users_id = '$users_id'");
                    $qur0003 = mysqli_query($db, $query0003);
                    $rowc0003 = mysqli_fetch_array($qur0003);
                    $email = $rowc0003["email"];
                    $lasname = $rowc0003["lastname"];
                    $firstname = $rowc0003["firstname"];
                    $mail->addAddress($email, $firstname);
                }
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $structure;
                if(!$mail->Send()){
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }else{
                    //echo "success";
                }
                $sqlvv = "update `live_data` set hum_low_email = '1' where `dev_id` = '$dev_id'";
                $resultvv = mysqli_query($iot_db, $sqlvv);
            }
        }
    }
    //if the status is Pressure
    $sqlv4 = "select * from device_parameter_config where device_id = '$d_id' and p_id = '3'";
    $resultv4 = mysqli_query($iot_db, $sqlv4);
    $rowv4 = mysqli_fetch_array($resultv4);
    $pre_upper_tolerance = $rowv4['upper_tolerance'];
    $pre_lower_tolerance = $rowv4['lower_tolerance'];
    if($rowv4 == true){
        if($pre_high_email != 1)
        {
            if($pressure > $pre_upper_tolerance){
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->SMTPAuth = true;
                $mail->Username = EMAIL_USER;
                $mail->Password = EMAIL_PASSWORD;
                $mail->setFrom('admin@plantnavigator.com', 'admin@plantnavigator.com');
                $subject = "Pressure High";
                $message1 = "The Pressure Cross the Upper Tolerance";
                $message2 = "Upper Tolerance Value : " . $pre_upper_tolerance;
                $message3 = "Actual Value  : " . $pressure;
                $message4 = "Device Name : " . $device_name;
                $message5 = "Type : " . $dev_type_name;
                $message6 = "Location : " . $device_location;
                $message7 = "Description : " . $device_description;
                $signature = " - Plantnavigator Admin";
                $structure = '<html><body>';
                $structure .= "<br/><br/><span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > Hello,</span><br/><br/>";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message1 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message2 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message3 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message4 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message5 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message6 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message7 . "</span><br/><br/> ";
                $structure .= "<br/><br/>";
                $structure .= $signature;
                $structure .= "</body></html>";
                if(!empty($users_id)) {
                    $query0003 = sprintf("SELECT * FROM cam_users where users_id = '$users_id'");
                    $qur0003 = mysqli_query($db, $query0003);
                    $rowc0003 = mysqli_fetch_array($qur0003);
                    $email = $rowc0003["email"];
                    $lasname = $rowc0003["lastname"];
                    $firstname = $rowc0003["firstname"];
                    $mail->addAddress($email, $firstname);
                }
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $structure;
                if(!$mail->Send()){
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }else{
                    //echo "success";
                }
                $sqlvv = "update `live_data` set pre_high_email = '1' where `dev_id` = '$dev_id'";
                $resultvv = mysqli_query($iot_db, $sqlvv);
            }
        }
        if($pre_low_email != 1)
        {
            if($pressure < $pre_lower_tolerance){
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->SMTPAuth = true;
                $mail->Username = EMAIL_USER;
                $mail->Password = EMAIL_PASSWORD;
                $mail->setFrom('admin@plantnavigator.com', 'admin@plantnavigator.com');
                $subject = "Pressure Low";
                $message1 = "The Pressure has reached below than lower tolerance";
                $message2 = "Lower Tolerance Value : " . $pre_lower_tolerance;
                $message3 = "Actual Value  : " . $pressure;
                $message4 = "Device Name : " . $device_name;
                $message5 = "Type : " . $dev_type_name;
                $message6 = "Location : " . $device_location;
                $message7 = "Description : " . $device_description;
                $signature = " - Plantnavigator Admin";
                $structure = '<html><body>';
                $structure .= "<br/><br/><span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > Hello,</span><br/><br/>";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message1 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message2 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message3 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message4 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message5 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message6 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message7 . "</span><br/><br/> ";
                $structure .= "<br/><br/>";
                $structure .= $signature;
                $structure .= "</body></html>";
                if(!empty($users_id)) {
                    $query0003 = sprintf("SELECT * FROM cam_users where users_id = '$users_id'");
                    $qur0003 = mysqli_query($db, $query0003);
                    $rowc0003 = mysqli_fetch_array($qur0003);
                    $email = $rowc0003["email"];
                    $lasname = $rowc0003["lastname"];
                    $firstname = $rowc0003["firstname"];
                    $mail->addAddress($email, $firstname);
                }
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $structure;
                if(!$mail->Send()){
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }else{
                    //echo "success";
                }
                $sqlvv = "update `live_data` set pre_low_email = '1' where `dev_id` = '$dev_id'";
                $resultvv = mysqli_query($iot_db, $sqlvv);
            }
        }
    }
    //if the status is IAQ
    $sqlv5 = "select * from device_parameter_config where device_id = '$d_id' and p_id = '4'";
    $resultv5 = mysqli_query($iot_db, $sqlv5);
    $rowv5 = mysqli_fetch_array($resultv5);
    $iaq_upper_tolerance = $rowv5['upper_tolerance'];
    $iaq_lower_tolerance = $rowv5['lower_tolerance'];
    if($rowv5 == true){
        if($iaq_high_email != 1)
        {
            if($iaq > $iaq_upper_tolerance){
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->SMTPAuth = true;
                $mail->Username = EMAIL_USER;
                $mail->Password = EMAIL_PASSWORD;
                $mail->setFrom('admin@plantnavigator.com', 'admin@plantnavigator.com');
                $subject = "IAQ High";
                $message1 = "The IAQ Cross the Upper Tolerance";
                $message2 = "Upper Tolerance Value : " . $iaq_upper_tolerance;
                $message3 = "Actual Value  : " . $iaq;
                $message4 = "Device Name : " . $device_name;
                $message5 = "Type : " . $dev_type_name;
                $message6 = "Location : " . $device_location;
                $message7 = "Description : " . $device_description;
                $signature = " - Plantnavigator Admin";
                $structure = '<html><body>';
                $structure .= "<br/><br/><span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > Hello,</span><br/><br/>";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message1 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message2 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message3 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message4 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message5 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message6 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message7 . "</span><br/><br/> ";
                $structure .= "<br/><br/>";
                $structure .= $signature;
                $structure .= "</body></html>";
                if(!empty($users_id)) {
                    $query0003 = sprintf("SELECT * FROM cam_users where users_id = '$users_id'");
                    $qur0003 = mysqli_query($db, $query0003);
                    $rowc0003 = mysqli_fetch_array($qur0003);
                    $email = $rowc0003["email"];
                    $lasname = $rowc0003["lastname"];
                    $firstname = $rowc0003["firstname"];
                    $mail->addAddress($email, $firstname);
                }
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $structure;
                if(!$mail->Send()){
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }else{
                    //echo "success";
                }
                $sqlvv = "update `live_data` set iaq_high_email = '1' where `dev_id` = '$dev_id'";
                $resultvv = mysqli_query($iot_db, $sqlvv);
            }
        }
        if($iaq_low_email != 1)
        {
            if($iaq < $iaq_lower_tolerance){
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->SMTPAuth = true;
                $mail->Username = EMAIL_USER;
                $mail->Password = EMAIL_PASSWORD;
                $mail->setFrom('admin@plantnavigator.com', 'admin@plantnavigator.com');
                $subject = "IAQ Low";
                $message1 = "The IAQ has reached below than lower tolerance";
                $message2 = "Lower Tolerance Value : " . $iaq_lower_tolerance;
                $message3 = "Actual Value  : " . $iaq;
                $message4 = "Device Name : " . $device_name;
                $message5 = "Type : " . $dev_type_name;
                $message6 = "Location : " . $device_location;
                $message7 = "Description : " . $device_description;
                $signature = " - Plantnavigator Admin";
                $structure = '<html><body>';
                $structure .= "<br/><br/><span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > Hello,</span><br/><br/>";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message1 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message2 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message3 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message4 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message5 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message6 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message7 . "</span><br/><br/> ";
                $structure .= "<br/><br/>";
                $structure .= $signature;
                $structure .= "</body></html>";
                if(!empty($users_id)) {
                    $query0003 = sprintf("SELECT * FROM cam_users where users_id = '$users_id'");
                    $qur0003 = mysqli_query($db, $query0003);
                    $rowc0003 = mysqli_fetch_array($qur0003);
                    $email = $rowc0003["email"];
                    $lasname = $rowc0003["lastname"];
                    $firstname = $rowc0003["firstname"];
                    $mail->addAddress($email, $firstname);
                }
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $structure;
                if(!$mail->Send()){
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }else{
                    //echo "success";
                }
                $sqlvv = "update `live_data` set iaq_low_email = '1' where `dev_id` = '$dev_id'";
                $resultvv = mysqli_query($iot_db, $sqlvv);
            }
        }
    }
    //if the status is VOC
    $sqlv6 = "select * from device_parameter_config where device_id = '$d_id' and p_id = '5'";
    $resultv6 = mysqli_query($iot_db, $sqlv6);
    $rowv6 = mysqli_fetch_array($resultv6);
    $voc_upper_tolerance = $rowv6['upper_tolerance'];
    $voc_lower_tolerance = $rowv6['lower_tolerance'];
    if($rowv6 == true){
        if($voc_high_email != 1)
        {
            if($voc > $voc_upper_tolerance){
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->SMTPAuth = true;
                $mail->Username = EMAIL_USER;
                $mail->Password = EMAIL_PASSWORD;
                $mail->setFrom('admin@plantnavigator.com', 'admin@plantnavigator.com');
                $subject = "VOC High";
                $message1 = "The VOC Cross the Upper Tolerance";
                $message2 = "Upper Tolerance Value : " . $voc_upper_tolerance;
                $message3 = "Actual Value  : " . $voc;
                $message4 = "Device Name : " . $device_name;
                $message5 = "Type : " . $dev_type_name;
                $message6 = "Location : " . $device_location;
                $message7 = "Description : " . $device_description;
                $signature = " - Plantnavigator Admin";
                $structure = '<html><body>';
                $structure .= "<br/><br/><span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > Hello,</span><br/><br/>";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message1 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message2 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message3 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message4 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message5 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message6 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message7 . "</span><br/><br/> ";
                $structure .= "<br/><br/>";
                $structure .= $signature;
                $structure .= "</body></html>";
                if(!empty($users_id)) {
                    $query0003 = sprintf("SELECT * FROM cam_users where users_id = '$users_id'");
                    $qur0003 = mysqli_query($db, $query0003);
                    $rowc0003 = mysqli_fetch_array($qur0003);
                    $email = $rowc0003["email"];
                    $lasname = $rowc0003["lastname"];
                    $firstname = $rowc0003["firstname"];
                    $mail->addAddress($email, $firstname);
                }
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $structure;
                if(!$mail->Send()){
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }else{
                    //echo "success";
                }
                $sqlvv = "update `live_data` set voc_high_email = '1' where `dev_id` = '$dev_id'";
                $resultvv = mysqli_query($iot_db, $sqlvv);
            }
        }
        if($voc_low_email != 1)
        {
            if($voc < $voc_lower_tolerance){
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->SMTPAuth = true;
                $mail->Username = EMAIL_USER;
                $mail->Password = EMAIL_PASSWORD;
                $mail->setFrom('admin@plantnavigator.com', 'admin@plantnavigator.com');
                $subject = "VOC Low";
                $message1 = "The VOC has reached below than lower tolerance";
                $message2 = "Lower Tolerance Value : " . $voc_lower_tolerance;
                $message3 = "Actual Value  : " . $voc;
                $message4 = "Device Name : " . $device_name;
                $message5 = "Type : " . $dev_type_name;
                $message6 = "Location : " . $device_location;
                $message7 = "Description : " . $device_description;
                $signature = " - Plantnavigator Admin";
                $structure = '<html><body>';
                $structure .= "<br/><br/><span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > Hello,</span><br/><br/>";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message1 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message2 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message3 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message4 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message5 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message6 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message7 . "</span><br/><br/> ";
                $structure .= "<br/><br/>";
                $structure .= $signature;
                $structure .= "</body></html>";
                if(!empty($users_id)) {
                    $query0003 = sprintf("SELECT * FROM cam_users where users_id = '$users_id'");
                    $qur0003 = mysqli_query($db, $query0003);
                    $rowc0003 = mysqli_fetch_array($qur0003);
                    $email = $rowc0003["email"];
                    $lasname = $rowc0003["lastname"];
                    $firstname = $rowc0003["firstname"];
                    $mail->addAddress($email, $firstname);
                }
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $structure;
                if(!$mail->Send()){
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }else{
                    //echo "success";
                }
                $sqlvv = "update `live_data` set voc_low_email = '1' where `dev_id` = '$dev_id'";
                $resultvv = mysqli_query($iot_db, $sqlvv);
            }
        }
    }
    //if the status is CO2
    $sqlv7 = "select * from device_parameter_config where device_id = '$d_id' and p_id = '6'";
    $resultv7 = mysqli_query($iot_db, $sqlv7);
    $rowv7 = mysqli_fetch_array($resultv7);
    $co2_upper_tolerance = $rowv7['upper_tolerance'];
    $co2_lower_tolerance = $rowv7['lower_tolerance'];
    if($rowv7 == true){
        if($co2_high_email != 1)
        {
            if($co2 > $co2_upper_tolerance){
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->SMTPAuth = true;
                $mail->Username = EMAIL_USER;
                $mail->Password = EMAIL_PASSWORD;
                $mail->setFrom('admin@plantnavigator.com', 'admin@plantnavigator.com');
                $subject = "CO2 High";
                $message1 = "The CO2 Cross the Upper Tolerance";
                $message2 = "Upper Tolerance Value : " . $co2_upper_tolerance;
                $message3 = "Actual Value  : " . $co2;
                $message4 = "Device Name : " . $device_name;
                $message5 = "Type : " . $dev_type_name;
                $message6 = "Location : " . $device_location;
                $message7 = "Description : " . $device_description;
                $signature = " - Plantnavigator Admin";
                $structure = '<html><body>';
                $structure .= "<br/><br/><span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > Hello,</span><br/><br/>";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message1 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message2 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message3 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message4 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message5 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message6 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message7 . "</span><br/><br/> ";
                $structure .= "<br/><br/>";
                $structure .= $signature;
                $structure .= "</body></html>";
                if(!empty($users_id)) {
                    $query0003 = sprintf("SELECT * FROM cam_users where users_id = '$users_id'");
                    $qur0003 = mysqli_query($db, $query0003);
                    $rowc0003 = mysqli_fetch_array($qur0003);
                    $email = $rowc0003["email"];
                    $lasname = $rowc0003["lastname"];
                    $firstname = $rowc0003["firstname"];
                    $mail->addAddress($email, $firstname);
                }
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $structure;
                if(!$mail->Send()){
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }else{
                    //echo "success";
                }
                $sqlvv = "update `live_data` set co2_high_email = '1' where `dev_id` = '$dev_id'";
                $resultvv = mysqli_query($iot_db, $sqlvv);
            }
        }
        if($co2_low_email != 1)
        {
            if($co2 < $co2_lower_tolerance){
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->SMTPAuth = true;
                $mail->Username = EMAIL_USER;
                $mail->Password = EMAIL_PASSWORD;
                $mail->setFrom('admin@plantnavigator.com', 'admin@plantnavigator.com');
                $subject = "CO2 Low";
                $message1 = "The CO2 has reached below than lower tolerance";
                $message2 = "Lower Tolerance Value : " . $co2_lower_tolerance;
                $message3 = "Actual Value  : " . $co2;
                $message4 = "Device Name : " . $device_name;
                $message5 = "Type : " . $dev_type_name;
                $message6 = "Location : " . $device_location;
                $message7 = "Description : " . $device_description;
                $signature = " - Plantnavigator Admin";
                $structure = '<html><body>';
                $structure .= "<br/><br/><span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > Hello,</span><br/><br/>";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message1 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message2 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message3 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message4 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message5 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message6 . "</span><br/><br/> ";
                $structure .= "<span style='font-family: 'Source Sans Pro', sans-serif;color:#757575;font-weight:600;' > " . $message7 . "</span><br/><br/> ";
                $structure .= "<br/><br/>";
                $structure .= $signature;
                $structure .= "</body></html>";
                if(!empty($users_id)) {
                    $query0003 = sprintf("SELECT * FROM cam_users where users_id = '$users_id'");
                    $qur0003 = mysqli_query($db, $query0003);
                    $rowc0003 = mysqli_fetch_array($qur0003);
                    $email = $rowc0003["email"];
                    $lasname = $rowc0003["lastname"];
                    $firstname = $rowc0003["firstname"];
                    $mail->addAddress($email, $firstname);
                }
                $mail->isHTML(true);
                $mail->Subject = $subject;
                $mail->Body = $structure;
                if(!$mail->Send()){
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }else{
                    //echo "success";
                }
                $sqlvv = "update `live_data` set co2_low_email = '1' where `dev_id` = '$dev_id'";
                $resultvv = mysqli_query($iot_db, $sqlvv);
            }
        }
    }
}

