<?php
session_start();
error_reporting(0);
include('include/config.php');
    {
    $id_participation = intval($_GET['id']); // get value
    $id_activite = intval($_GET['ac']);
    $source= $_GET['source'];
    $req = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-participation` = '$id_participation' ");            
    while ($res = mysqli_fetch_array($req)) 
        { 
        $modif = mysqli_query($con, "UPDATE `participation` SET `option` = 'Elimine', `id-siege` = '0',`id-table` = '0' WHERE `id-participation` = '$id_participation'");
        };
    }; 
?>
<script type="text/javascript">window.location.replace("<?php echo $source.$id_activite; ?>");</script> ; 