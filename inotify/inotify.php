<?php
require("/var/www/html/private/Secrets.php");

$link = mysqli_connect($hostname, $db_user, $db_password, $database)
    or die('Could not connect: ' . mysqli_error($link));

$filename = $_SERVER['argv'][1];

if(!preg_match('/(deck|front|deck_pan)([0-9]{4})([0-9]{2})([0-9]{2})s?([0-9]{2})([0-9]{2})/',$filename,$group)){
    echo "Bad File format.\n";
    die;
}
$year = $group[2];
$month = $group[3];
$day = $group[4];
$hour = $group[5];
$min = $group[6];

if($min >= 30){
  $hour = sprintf('%02d', $hour + 1);
}


$html = `curl -s -c /tmp/cookies.txt https://my.radiothermostat.com/rtcoa/login.html`;
if(preg_match('/name="_csrf" content="([^"]*)"/',$html,$csrf_arr)) {$csrf = $csrf_arr[1];}
$json = `curl -s -b /tmp/cookies.txt -c /tmp/cookies2.txt -X POST https://my.radiothermostat.com/rtcoa/login -d "username=$t_user&password=$t_password&_csrf=$csrf&g-recaptcha-response="; curl -b /tmp/cookies2.txt https://my.radiothermostat.com/rtcoa/rest/thermostats/4eee92dfe4b045ea7717d3b7 --silent --connect-timeout 2 --max-time 3 --retry 5 --retry-delay 0`;
#$json = `curl -s --connect-timeout 10 http://cozycabin.dyndns.org:1031/tstat/temp`;

if(preg_match('/\"temp\":(-?[0-9]+\.[0-9]+)/',$json,$temp_arr)) {$temp1 = $temp_arr[1];}

//Go get temperature if available
$html = `curl -kLs --connect-timeout 10 https://www.bigtreestech.com/home/`;

if(preg_match('/Temperature: (-?\d+\.?\d* )/',$html,$temp_arr)) {$temp2 = $temp_arr[1];}

if(isset($temp1)){
    $temp1var = ",tempIn";
    $temp1=",'$temp1'";
}else {
    $temp1var = "";
    $temp1 = "";
}

if(isset($temp2)){
    $temp2var = ",tempOut";
    $temp2=",'$temp2'";
}else {
    $temp2var = "";
    $temp2 = "";
}

$query = "insert into images (creation_time,creation_date,file_name$temp1var$temp2var) values ('$hour:00:00','$year-$month-$day','$filename'$temp1$temp2)";

mysqli_query($link, $query) or die('Query failed: ' . mysqli_error($link));

mysqli_close($link);
?>
