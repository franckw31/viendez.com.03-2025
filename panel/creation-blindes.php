<?php
session_start();
error_reporting(0);
$_SESSION["cptblinde"] = '1';
include ('include/config.php');
$activite = intval($_GET['act']); // get value
$source = intval($_GET['sou']); // get value
$zero = intval($_GET['zero']); // get value
$req1 = mysqli_query($con, "SELECT  * FROM `activite` WHERE `id-activite` = '$activite' ");
$req1b = mysqli_query($con, "DELETE FROM `blindes-live` WHERE `id-activite` = '$activite' ");
$nb_lignes = mysqli_num_rows($req1);
echo " nbr activ = (" . $nb_lignes . ")";
date_default_timezone_set('Etc/GMT+2');
while ($res1 = mysqli_fetch_array($req1)) {
    $structure = $res1['id-structure'];
    $departh = $res1['heure_depart'];
    $departd = $res1['date_depart'];
    if ($zero == 1) {
        date_default_timezone_set('Arctic/Longyearbyen');
        $departh = date("Y-m-d H:i:s", time());
        $departd = date("Y-m-d", time());
    }
    ;
    $heure = $departh;
    $req2 = mysqli_query($con, "SELECT * FROM `structure` WHERE `id-structure` = $structure ORDER BY 'id-blinde'");
    while ($res2 = mysqli_fetch_array($req2)) {
        $blinde = $res2['id-blinde'];
        $req3 = mysqli_query($con, "SELECT * FROM `blindes` WHERE `id-blinde` =  '$blinde'");
        while ($res3 = mysqli_fetch_array($req3)) {
            echo "blinde = {" . $blinde . "}";
            echo "h = {" . $heure . "}";
            $minutes = $res2['duree'] / 60;
            echo "m = {" . $minutes . "}";
            $heure = strtotime($heure);
            $heure = strtotime("+" . $minutes . " minute", $heure);
            echo date('Y-m-d H:i:s', $heure);
            $heure = date("Y-m-d H:i:s", $heure);
            echo $activite . " / ";
            echo $res2['ordre'] . " / ";
            $ordre = $res2['ordre'];
            echo $res3['nom'] . " / ";
            $nom = $res3['nom'];
            $sb = $res3['val-sb'];
            $bb = $res3['val-bb'];
            echo $res2['duree'] . " / ";
            $duree = $res2['duree'] / 60;
            echo $heure . " / ";
            $fin = $heure;
            echo $res3['ante'] . " ---------- ";
            $ante = $res3['ante'];
            $modif = mysqli_query($con, "INSERT INTO `blindes-live` (`id-activite`, `ordre`, `nom`, `sb`, `bb`, `duree`, `fin`, `ante`) VALUES ('$activite', '$ordre', '$nom', '$sb', '$bb', '$duree', '$heure', '$ante' )");
        }
        ;
    }
    ;
}
;
?>
<script language="JavaScript" type="text/javascript">
    window.location.replace("/panel/voir-activite.php?uid=<?php echo $activite ?>");
</script>';
<!-- <script language="JavaScript" type="text/javascript"> window.location.replace("addon.php?id=<?php echo $id ?>&ac=<?php echo $id_activite ?>&source=<?php echo "https://poker31.org/panel/voir-activite.php?uid=" ?>");  -->
</script>