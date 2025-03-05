<?php
header('Content-Type: application/json; charset=utf-8');
include('panel/include/config.php');
$actu = strtotime(date("Y-m-d"));
echo $actu;

?>
