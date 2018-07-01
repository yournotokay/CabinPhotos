<?php
require("/var/www/html/private/Secrets.php");

$link = mysqli_connect($hostname, $db_user, $db_password, $database)
        or die('Could not connect: ' . mysqli_error($link));
?>
<table class="special" style="width: 100%">
    <tr align="center" style="width: 100%">
<?php
$date = $_GET['time'];
$date = mktime(0,0,0,date('m',$date),date('d',$date)-$dayNum,date('Y',$date));
require("MakeTable.php");

mysqli_close($link);
?>
