<?php
session_start();
$_SESSION["bl"]=1;
error_reporting(0);
include_once('include/config.php');

$res=mysqli_query($con,"SELECT * FROM `blindes-live` WHERE (`id-activite` = $id AND `ordre` = 1)");
$row=mysqli_fetch_array($res);
$nom=$row["nom"];
$ante1=$row["ante"];
$_SESSION["fin"."1"]=$row["fin"];

// $_SESSION["nom"]=$nom;
// $_SESSION["stop"]='0';
// $_SESSION["blinde"]="1";
// $fin=$row["fin"];
// $ordre=$row["ordre"];
// $_SESSION["fin"]=$fin;          

$res=mysqli_query($con,"SELECT * FROM `blindes-live` WHERE (`id-activite` = $id AND `ordre` = 2)");
$row=mysqli_fetch_array($res);
$nom2=$row["nom"];
$ante2=$row["ante"];
$_SESSION["fin"."2"]=$row["fin"];

// $nom2=$row["nom"];
// $_SESSION["fin2"]=$fin2;
// $_SESSION["nom2"]=$nom2;
// $_SESSION["stop2"]='0';
// $fin2=$row["fin"];        
// $ordre2=$row["ordre"];

$res=mysqli_query($con,"SELECT * FROM `blindes-live` WHERE (`id-activite` = $id AND `ordre` = 3)");
$row=mysqli_fetch_array($res);
$nom3=$row["nom"];
$ante3=$row["ante"];
$_SESSION["fin"."3"]=$row["fin"];

// $nom3=$row["nom"];
// $ordre3=$row["ordre"];
// $_SESSION["fin3"]=$fin3;
// $_SESSION["nom3"]=$nom3;
// $_SESSION["stop3"]='0';
// $fin3=$row["fin"];               

$res=mysqli_query($con,"SELECT * FROM `blindes-live` WHERE (`id-activite` = $id AND `ordre` = 4)");
$row=mysqli_fetch_array($res);
$nom4=$row["nom"];
$ante4=$row["ante"];
$_SESSION["fin"."4"]=$row["fin"];

// $fin4=$row["fin"];               
// $nom4=$row["nom"];
// $ordre4=$row["ordre"];
// $_SESSION["fin4"]=$fin4;
// $_SESSION["nom4"]=$nom4;
// $_SESSION["stop4"]='0';

?>

<form>
  <input type="hidden" id="nom" value="<?php echo $nom; ?>">
  <input type="hidden" id="ante1" value="<?php echo $ante1; ?>">
  <input type="hidden" id="nom2" value="<?php echo $nom2; ?>">
  <input type="hidden" id="ante2" value="<?php echo $ante2; ?>">
  <input type="hidden" id="nom3" value="<?php echo $nom3; ?>">
  <input type="hidden" id="ante3" value="<?php echo $ante3; ?>">
  <input type="hidden" id="nom4" value="<?php echo $nom4; ?>">
  <input type="hidden" id="ante4" value="<?php echo $ante4; ?>">
</form>

<?php

if ($_SESSION["stop"] == '0') { ?>

    <div id="response"></div>
    <script type="text/javascript">
        let nIntervId;

        function compteur() { if (!nIntervId) { nIntervId = setInterval(decompte, 1000);}}
        function decompte() { var xmlhttp=new XMLHttpRequest(); xmlhttp.open("GET","response.php",false); xmlhttp.send(null);                     
            if (xmlhttp.responseText == 0) {stopcompteur();compteur2()} else {document.getElementById("response").innerHTML=document.getElementById("nom").value+" + "+document.getElementById("ante1").value+" : "+xmlhttp.responseText;}}
        function stopcompteur() { clearInterval(nIntervId); nIntervId = null; }

        function compteur2() { if (!nIntervId) { nIntervId = setInterval(decompte2, 1000); }}
        function decompte2() { var xmlhttp=new XMLHttpRequest(); xmlhttp.open("GET","response.php",false); xmlhttp.send(null);                     
            if (xmlhttp.responseText == 0) {stopcompteur2();compteur3()} else {document.getElementById("response").innerHTML=document.getElementById("nom2").value+" + "+document.getElementById("ante2").value+" : "+xmlhttp.responseText;}}
        function stopcompteur2() { clearInterval(nIntervId); nIntervId = null; }

        function compteur3() { if (!nIntervId) { nIntervId = setInterval(decompte3, 1000); }}
        function decompte3() { var xmlhttp=new XMLHttpRequest(); xmlhttp.open("GET","response.php",false); xmlhttp.send(null);                     
            if (xmlhttp.responseText == 0) {stopcompteur3();;compteur4()} else {document.getElementById("response").innerHTML=document.getElementById("nom3").value+" + "+document.getElementById("ante3").value+" : "+xmlhttp.responseText;}}
        function stopcompteur3() { clearInterval(nIntervId); nIntervId = null; }

        function compteur4() { if (!nIntervId) { nIntervId = setInterval(decompte4, 1000); }}
        function decompte4() { var xmlhttp=new XMLHttpRequest(); xmlhttp.open("GET","response.php",false); xmlhttp.send(null);                     
            if (xmlhttp.responseText == 0) {stopcompteur4();} else {document.getElementById("response").innerHTML=document.getElementById("nom4").value+" + "+document.getElementById("ante4").value+" : "+xmlhttp.responseText;}}
        function stopcompteur3() { clearInterval(nIntervId); nIntervId = null; }

        compteur();
    </script>

    <?php ; }

?> 