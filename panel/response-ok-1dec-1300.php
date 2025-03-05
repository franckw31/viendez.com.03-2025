<?php
session_start();

$departsecondes=strtotime(date("Y-m-d H:i:s"));
$arriveesecondes1=strtotime($_SESSION["fin".$_SESSION["bl"]]);
$ecartsecondes1=$arriveesecondes1-$departsecondes;

if ($ecartsecondes1 > 0)
{ echo gmdate("i:s",$ecartsecondes1);}
else

{ 
    echo "0";
    $_SESSION["bl"]=$_SESSION["bl"]+1;    
}
?> 