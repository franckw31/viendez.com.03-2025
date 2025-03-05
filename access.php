<?php
session_start();
$num_membre = $_GET['membre']; // get values
$source = $_GET['source']; // get value

$num_activite = $_GET['activite']; // get value
$code = $_GET['code']; // get value
// $source="/panel/voir-activite.php?uid=";
echo "debut";
include('config.php');
define('DB_SERVER','db5011397709.hosting-data.io');
define('DB_USER','dbu5472475');
define('DB_PASS' ,'Kookies7*');
define('DB_NAME', 'dbs9616600');
$con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
$sql = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` = '$num_membre' ");
$result = mysqli_fetch_array($sql) ;
$email = $result['email'];
$mdp = $result['password'];
echo "milieu 0";
$codev = $result['CodeV'];
$pseudo = $result['pseudo'];
echo "milieu 1".$codev;

$msg = "";
$Error_Pass = "";
if ($_GET['code']>1) {
    $coderecu = $_GET['code']; // get value
    if ($coderecu == $codev) {
        $_SESSION['login']=$result['pseudo'];
		$_SESSION['id']=$result['id-membre'];
        echo $_SESSION['id'].$_SESSION['login'];
        // echo '<script language="JavaScript" type="text/javascript"> window.location.replace("/panel/voir-activite.php?uid=30"); </script>';
        // ?><script type="text/javascript">window.location.replace("<?php echo $source.$num_activite; ?>");</script> ; <?php
        echo "milieu 2".$source.$num_acitivte;
    };
};
echo "fin";
?>