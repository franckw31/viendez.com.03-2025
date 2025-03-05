<?php
session_start();
error_reporting(0);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

include_once('include/config.php');
$ret = mysqli_query($con, "SELECT * FROM `activite` WHERE 1 ");
while ($row = mysqli_fetch_array($ret)) 
    { 
    $pointeur = $row["id-activite"];
    $pointeur_ordre = 0;
    $ret2 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = '$pointeur' AND `option` LIKE 'Reservation') OR (`id-activite` = '$pointeur' AND `option` LIKE 'Option')
        OR (`id-activite` = '$pointeur' AND `option` LIKE 'Inscrit') OR (`id-activite` = '$pointeur' AND `option` LIKE 'Confirme') OR (`id-activite` = '$pointeur' AND `option` LIKE 'Elimine') ORDER BY `ordre` ASC" ) ;
    while ($row2 = mysqli_fetch_array($ret2)) 
        { 
        $id = $row2['id-participation'];
        $pointeur_ordre = $pointeur_ordre + 1;
        $modif = mysqli_query($con, "UPDATE `participation` SET `ordre` = '$pointeur_ordre' WHERE `id-participation` = '$id'");
        } ;
    };
// echo "Reorg Ok";
require 'vendor/autoload.php';
if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
    exit;
} else {
    $id = intval($_GET['uid']); // get value
    if (isset($_POST['submit'])) {
        $titre_activite = $_POST['titre-activite'];
        $date_depart = $_POST['date_depart'];
        $heure_depart = $_POST['heure_depart'];
        $ville = $_POST['ville'];
        $places = $_POST['places'];
        $rake = $_POST['rake'];
        $buyin = $_POST['buyin'];
        $bounty = $_POST['bounty'];
        $recave = $_POST['recave'];
        $addon = $_POST['addon'];
        $ante = $_POST['ante'];
        $idmembre = $_POST['id-membre'];
        $commentaire = $_POST['commentaire'];
        // $structure = $_POST['structure'];
        $jetons = $_POST['jetons'];
        $bonus = $_POST['bonus'];
        $addon = $_POST['addon'];
        $nb_tables = $_POST['nb-tables'];
        $idmembresession = $_SESSION['id'];

        
        if (($idmembresession == $idmembre) OR ($idmembresession == 265)) {
            $msg = mysqli_query($con, "UPDATE `activite` SET `id-membre` = '$idmembre' , `titre-activite` = '$titre_activite' , `date_depart` = '$date_depart' , `heure_depart` = '$heure_depart' ,`ville` = '$ville' , `places` = '$places' , `nb-tables` = '$nb_tables' , `commentaire` = '$commentaire' , `buyin` = '$buyin' , `rake` = '$rake' , `bounty` = '$bounty' , `jetons` = '$jetons' , `recave` = '$recave' , `addon` = '$addon' , `ante` = '$ante' , `bonus` = '$bonus' WHERE `id-activite` = '$id'");
        };
        // $_SESSION['msg'] = "Activité ajoutée avec succés !!";
        // header('location:http://poker31.org/panel/liste-activites.php');
        // exit;
    }
    if (isset($_POST['submitpl'])) {
        $particip=$_POST['submitpl'];
        // echo $particip;
        header('location:voir-participation.php?id='.$particip);

        
        // $sql2 = mysqli_query($con, "INSERT INTO `competences-individu` (`id-indiv`, `id-comp`) VALUES ('$id', '$compet')");
        // $_SESSION['msg'] = "Doctor Specialization added successfully !!";
    }
    if (isset($_POST['submit2'])) {
        $compet = $_POST['compet'];
        echo $compet;
        $sql2 = mysqli_query($con, "INSERT INTO `competences-individu` (`id-indiv`, `id-comp`) VALUES ('$id', '$compet')");
        // $_SESSION['msg'] = "Doctor Specialization added successfully !!";
    }
    //if (isset($_POST['submit3'])) {
        //$lois = $_POST['lois'];
        // echo $lois;
        //$sql2 = mysqli_query($con, "INSERT INTO `loisirs-individu` (`id-indiv`, `id-lois`) VALUES ('$id', '$lois')");
        // $_SESSION['msg'] = "Doctor Specialization added successfully !!";
    //}
    if ((isset($_POST['submit-ins'])) OR (isset($_POST['submit3']))){
        $lois = $_POST['lois'];
        $activi = $_POST['activi'];
        // $lois = "30";$activi = "31";

        $sql0 = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-membre` = '$lois' AND `id-activite` = '$activi' ");
        // Return the number of rows in result set
        $rowcount = mysqli_num_rows($sql0);

        if ($rowcount == '0') {
            $ordre = '0';
            $sql0 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = '$activi' AND `option` LIKE 'Reservation') OR (`id-activite` = '$activi' AND `option` LIKE 'Option') OR (`id-activite` = '$activi' AND `option` LIKE 'Inscrit') ");
            $ordre = mysqli_num_rows($sql0);
            $intordre = (int) $ordre;
            $intordre = $intordre + 1;
            $ordre = (string) $intordre;
 
            $sql2 = mysqli_query($con, "INSERT INTO `participation` (`id-membre`, `id-membre-vainqueur`, `id-activite`, `id-siege`, `id-table`, `id-challenge`, `option`, `ordre`, `valide`, `commentaire`, `classement`, `points`, `gain`, `ds`, `ip-ins`, `ip-mod`, `ip-sup`) VALUES ( '$lois', '', '$activi', '', '', '', 'Reservation', '$ordre', 'Actif', NULL, '0', '0', '0', CURRENT_TIMESTAMP, '', '', '')");

            // recherche email
            $sql3 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  '$lois'");
            while ($result = mysqli_fetch_array($sql3)) {
                $email = $result['email'];
                $num_membre = $result['id-membre'];
                $num_activite = $activi;
                $reset = $result['CodeV'];
            }
            ;
            // debut mail

            $mail = new PHPMailer(true);
            try {
                //Server settings
                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->SMTPDebug = 0; //Enable verbose debug output
                $mail->isSMTP(); //Send using SMTP
                $mail->Host = 'smtp.ionos.fr'; //Set the SMTP server to send through
                $mail->SMTPAuth = true; //Enable SMTP authentication
                $mail->Username = 'admin@poker31.org'; //SMTP username
                $mail->Password = 'Kookies7*p'; //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
                $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('admin@poker31.org', 'Admin@Poker31.Org');
                //   $mail->addAddress('wenger.franck@gmail.com', 'Franck.W');     //Add a recipient
                $mail->addAddress($email, 'Privé'); //Add a recipient
                //   $mail->addAddress('ellen@example.com');               //Name is optional
                $mail->addReplyTo('admin@poker31.org', 'Administrateur');
                //   $mail->addCC('cc@example.com');
                //   $mail->addBCC('bcc@example.com');

                //Attachments
                //   $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                //   $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
                //Content
                $mail->isHTML(true); //Set email format to HTML
                $mail->Subject = 'AR Inscription www.poker31.org';
                $mail->Body = '<p>Votre inscription est prise en compte</p><p>Votre ordre d inscription est : ' . $ordre . '</p><p> Reset mot de passe : <a href="http://poker31.org/reg/change-Password.php?Reset=' . $reset . '">"http://poker31.org/reg/change-Password.php?Reset=' . $reset . '"</a></p>' . '<p> Lien activité : <b><a href="http://poker31.org/panel/voir-activite.php?uid=' . $num_activite . '">"http://poker31.org/panel/voir-activite.php?uid=' . $num_activite . '"</a></p>';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                $mail->send();
                // echo 'Message has been sent';
            } catch (Exception $e) 
            {
                // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        ;};

        // echo '<script language="JavaScript" type="text/javascript"> window.location.replace("/panel/liste-activite.php"); </script>';

        // header('location:/panel/liste-activites.php');
        // exit;

        // $_SESSION['msg'] = "bingo !!";
    };
    if (isset($_POST['submit-desins'])) {
        $lois = $_SESSION['id'];
        $activi = $id;




        if ($option = 'Annule') { echo "coucouc";
            $id_table='';$id_siege='';
        } else echo "jkljmlkjmlkjmlkjmkljmlkjmlk";




        $sql0 = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-membre` = '$lois' AND `id-activite` = '$activi' ");
        // Return the number of rows in result set
        $rowcount = mysqli_num_rows($sql0);
        if ($rowcount == '1') { 
            $sql10 = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-membre` = '$lois' AND `id-activite` = '$activi' ");
            $part = mysqli_fetch_array($sql10);
            $id_part = $part['id-participation'];
            $sql2 = mysqli_query($con, "UPDATE `participation` SET `id-membre`='$lois',`position`='0',`id-table`='$id_table',`id-siege`='$id_siege',`id-activite`='$activi',`option`='Annule',`ds`= CURRENT_TIMESTAMP WHERE `participation`.`id-participation` = '$id_part'");
        
            // recherche email
            $sql3 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  '$lois'");
            while ($result = mysqli_fetch_array($sql3)) {
                $email = $result['email'];
                $num_membre = $result['id-membre'];
                $num_activite = $activi;
                $reset = $result['CodeV'];
            }
            ;
            // debut mail

            $mail = new PHPMailer(true);
            try {
                //Server settings
                // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
                $mail->SMTPDebug = 0; //Enable verbose debug output
                $mail->isSMTP(); //Send using SMTP
                $mail->Host = 'smtp.ionos.fr'; //Set the SMTP server to send through
                $mail->SMTPAuth = true; //Enable SMTP authentication
                $mail->Username = 'admin@poker31.org'; //SMTP username
                $mail->Password = 'Kookies7*p'; //SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
                $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                //Recipients
                $mail->setFrom('admin@poker31.org', 'Admin@Poker31.Org');
                //   $mail->addAddress('wenger.franck@gmail.com', 'Franck.W');     //Add a recipient
                $mail->addAddress($email, 'Privé'); //Add a recipient
                //   $mail->addAddress('ellen@example.com');               //Name is optional
                $mail->addReplyTo('admin@poker31.org', 'Administrateur');
                //   $mail->addCC('cc@example.com');
                //   $mail->addBCC('bcc@example.com');

                //Attachments
                //   $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
                //   $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
                //Content
                $mail->isHTML(true); //Set email format to HTML
                $mail->Subject = 'AR Inscription www.poker31.org';
                $mail->Body = '<p>Votre des-inscription est prise en compte</p><p> Reset mot de passe : <a href="http://poker31.org/reg/change-Password.php?Reset=' . $reset . '">"http://poker31.org/reg/change-Password.php?Reset=' . $reset . '"</a></p>' . '<p> Lien activité : <b><a href="http://poker31.org/panel/voir-activite.php?uid=' . $num_activite . '">"http://poker31.org/panel/voir-activite.php?uid=' . $num_activite . '"</a></p>';
                $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                $mail->send();
                // echo 'Message has been sent';
            } catch (Exception $e) 
            {
                // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        ;};

        // echo '<script language="JavaScript" type="text/javascript"> window.location.replace("/panel/liste-activite.php"); </script>';

        // header('location:/panel/liste-activites.php');
        // exit;

        // $_SESSION['msg'] = "bingo !!";
    };
    ?>    
                                    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
                                        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
                                            <head>
                                                <meta http-equiv="Content-Type" content="text/html; charset=UTF-16" />
                                           
                                                <title>Admin | Edition Membre</title>
                                                <link
                                                    href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic"
                                                    rel="stylesheet" type="text/css" />
                                                <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
                                                <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
                                                <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
                                                <link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
                                                <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
                                                <link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
                                                <link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
                                                <link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
                                                <link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
                                                <!-- <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" /> -->
                                                <link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
                                                <link rel="stylesheet" href="assets/css/styles.css">
                                                <link rel="stylesheet" href="assets/css/plugins.css">
                                                <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
                                                <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
                                                <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
                                                <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
                                                <script src="https://cdnjs.cloudflare.com/ajax/libs/luxon/2.3.1/luxon.min.js"></script>
                                                <script type="text/javascript">$(document).ready(function () {
                                                        $('#example').DataTable({ pageLength: 8, language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' } });
                                                    });</script>
                                                <script type="text/javascript">$(document).ready(function () {
                                                        $('#example2').DataTable({ font-size: 16px;pageLength: 8, language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' } });
                                                    });</script>
                                                <script type="text/javascript">$(document).ready(function () {
                                                        $('#example3').DataTable({ font-size: 28px;pageLength: 8, language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' } });
                                                    });</script>
                                                <link rel="stylesheet" href="css/mes-styles.css">
                                                <link rel="stylesheet" href="css/les-styles.css">


                                                <style>
  
  .square-box {
        position: absolute;
        width: 90%;
        height: 50%;
        overflow: hidden;
        background: #6495ED;
        opacity: 0.5;
        left: 0;
        right: 0;
        top: -100px;
        bottom: 0;
        margin: auto;
        border-radius: 200px;
        border: 5px solid black;


    }

    .square-box2 {
        position: absolute;
        width: 60%;
        height: 25%;
        overflow: hidden;
        background: black;
        opacity: 0.5;
        left: 0;
        right: 0;
        top: -110px;
        bottom: 0;
        margin: auto;
        border-radius: 200px;
        border: 5px solid black;


    }


    .square-box:before {
        content: "";
        display: block;
        padding-top: 100%;
    }

    .square-content {
        position: absolute;
        top: 38%;
        left: 35%;
        color: white;
        width: 100%%;
        height: 100%%;
        font-size: 4vw;

    }

    .place-content {
        position: absolute;
        top: 38%;
        left: 30%;
        color: white;
        width: 100%%;
        height: 100%%;
        font-size: 2.5vw;

    }

    .square-content div {
        display: table;
        width: 100%;
        height: 100%;
    }

    .square-content span {
        display: table-cell;
        text-align: center;
        vertical-align: middle;
        color: white
    }

.players {
    position: relative;
    top: -10px;
    width: 100%;
    height: 100%;
    z-index: 100;
    
}

.players .player {
    position: absolute;
}
.players .player.player-1 {
    top: 11%;
  
    left:50%;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
}

.players .player.player-1p {
    top: 20%;
  
    left:49%;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
}

.players .player.player-2 {
    top: 17.5%;
    
    left:80%;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
}

.players .player.player-3 {
    top: 40%;
    left: 95%;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
}

.players .player.player-4 {
    top: 67%;
    left: 80%;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
}

.players .player.player-5 {
    top: 74%;
    left: 50%;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
}
.players .player.player-6 {
    top: 67%;
    left: 20%;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
}

.players .player.player-7 {
    top: 40%;
    left: 5%;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
}

.players .player.player-8 {
    top: 17.5%;
    left: 20%;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
}
.players .player .avatar {
    width: 14vw;
    height: 8vw;
    bbackground-color: lightcoral;
    
    border-radius: 100%;
    position: relative;
    top: 5px;
    z-index: 1;
}
#main{
    position: absolute;
        width: 85%;
        height: 100%;
        overflow: none;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        margin: auto;
      

}

.p1{
  
  
  
  display: flex;
  align-items: center; 
  justify-content: center;
  text-align: center; 
  color: #FFF;
  font-weight: bold;
  font-size: 17px;
 

}
.p2{
  
  display: flex;
  align-items: center; 
  justify-content: center;
  text-align: center; 
  color: #fff;
  font-weight: bold;
  font-size: 17px;
  opacity: 0.95;
 
}
.p3{
   
  display: flex;
  align-items: center; 
  justify-content: center;
  text-align: center; 
  color: #fff;
  font-weight: bold;
  font-size: 2.5vw;
  opacity: 0.95;
 
}
.p4{
   
   display: flex;
   align-items: center; 
   justify-content: center;
   text-align: center; 
   color: #fff;
   font-weight: bold;
   font-size: 2.5vw;
   opacity: 0.9;
  
 }
 .p5{
   
   display: flex;
   align-items: center; 
   justify-content: center;
   text-align: center; 
   color: #fff;
   font-weight: bold;
   font-size: 17px;
   
  
 }
 .p6{
   
   display: flex;
   align-items: center; 
   justify-content: center;
   text-align: center; 
   color: #fff;
   font-weight: bold;
   font-size: 2.5vw;
   opacity: 0.90;
  
 }
 .p7{
   
   display: flex;
   align-items: center; 
   justify-content: center;
   text-align: center; 
   color: #fff;
   font-weight: bold;
   font-size: 2.5vw;
   opacity: 0.95;
  
 }
 .p8{
   
   display: flex;
   align-items: center; 
   justify-content: center;
   text-align: center; 
   color: #fff;
   font-weight: bold;
   font-size: 17px;
   
  
 }
</style>


                                                <script type="text/javascript">
                                                    function valid() {
                                                        if (document.adddoc.npass.value != document.adddoc.cfpass.value) {
                                                            alert("Password and Confirm Password Field do not match  !!");
                                                            document.adddoc.cfpass.focus();
                                                            return false;
                                                        }
                                                        return true;
                                                    }
                                                </script>
                                                <script>
                                                    function checkemailAvailability() {
                                                        $("#loaderIcon").show();
                                                        jQuery.ajax({
                                                            url: "check_availability.php",
                                                            data: 'emailid=' + $("#docemail").val(),
                                                            type: "POST",
                                                            success: function (data) {
                                                                $("#email-availability-status").html(data);
                                                                $("#loaderIcon").hide();
                                                            },
                                                            error: function () { }
                                                        });
                                                    }
                                                </script>
                                            </head>
                                            <body>
                                                <div id="app">
                                                    <?php include('include/sidebar.php'); ?>
                                                    <div class="app-content">
                                                        <?php include('include/header.php'); ?>
                                                    <!-- end: TOP NAVBAR -->
                                                    <!-- <div class="calque">
                                                        Sections et onglets Css
                                                    </div> -->
                                                        <div class="main-content">
                                                            <div class="wrap-content container" id="container">
                                                                <!-- start: PAGE TITLE -->
                                                                <section id="page-title">
                                                                    <!-- <div class="row"> -->
                                                                        <!-- <div class="col-sm-8">
                                                                            <h1 class="mainTitle">Admin | Membre</h1>
                                                                        </div> -->
                                                                        <!-- <ol class="breadcrumb">
                                                                            <li>
                                                                                <span>Admin</span>
                                                                            </li>
                                                                            <li class="active">
                                                                                <span>Edition Membre</span>
                                                                            </li>
                                                                        </ol> -->
                                                                    <!-- </div> -->
                                                                </section>
                                                                <!-- end: PAGE TITLE -->
                                                                <!-- start: BASIC EXAMPLE -->
                                                                <div id="conteneur">
                                                                    <div id="contenu">
                                                                        <div id="auCentre">
                                                                            <div id="bMenu">
                                                                                <a href="#" id="infos" class="btnnav" onmouseover="afficher('infos')">Infos</a>
                                                                                <a href="#" id="inscrits" class="btnnav" onmouseover="afficher('inscrits')">Inscrits</a>
                                                                                <a href="#" id="t1" class="btnnav" onmouseover="afficher('t1')">Table 1</a>
                                                                                <a href="#" id="t2" class="btnnav" onmouseover="afficher('t2')">Table 2</a>
                                                                                <a href="#" id="t3" class="btnnav" onmouseover="afficher('t3')">Table 3</a>
                                                                                <a href="#" id="t4" class="btnnav" onmouseover="afficher('t4')">Table 4</a>
                                                                            </div>
                                                                            <div id="bSection">
                                                                                <div id="infosE">
                                                                                    <div class="wrap-content container" id="container">
                                                                                        <div class="container-fluid bbg-white">
                                                                                            <div class="col-md-12">
                                                                                                <div class="row margin-top-30">
                                                                                                    <div class="panel-wwhite">
                                                                                                        <div class="ppanel-body">
                                                                                                            <!-- <?php echo htmlentities($_SESSION['msg'] = ""); ?> -->
                                                                                                            <div class="form-group">
                                                                                                                <?php
                                                                                                                $id = intval($_GET['uid']);
                                                                                                                $sql = mysqli_query($con, "SELECT * FROM `activite` WHERE `id-activite` =  '$id'");
                                                                                                                while ($row = mysqli_fetch_array($sql)) { $id_org = $row["id-membre"];
                                                                                                                    $sql2 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  '$id_org'");
                                                                                                                    while ($row2 = mysqli_fetch_array($sql2)) { $nom_org = $row2['pseudo']; } ?>
                                                                                                                        <!-- <form method="post"> -->
                                                                                                                        <table class="table table-bordered">            
                                                                                                                            <tr><td rowspan="2" ><img src="images/<?php echo $row['photo']; ?>" width="85" height="85" style="align:center">
                                                                                                                                    <form id="image_upload_form" enctype="multipart/form-data" action="upload-photo-activite.php?editid=<?php echo $id; ?>" method="post" class="change-pic">
                                                                                                                                        <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                                                                                                                                        <div>
                                                                                                                                            <input type="file"  class = "fa fa-camera" id="file" name="fileToUpload"  style="display:none;"/><input type="button" onClick="fileToUpload.click();" value="Modifier"/>
                                                                                                                                            <i class = "fa fa-camera"></i>
                                                                                                                                        </div> 
                                                                                                                                        <script type="text/javascript">
                                                                                                                                            document.getElementById("file").onchange = function() {
                                                                                                                                                document.getElementById("image_upload_form").submit(); };
                                                                                                                                        </script>
                                                                                                                                    </form>
                                                                                                                                </td> 
                                                                                                                                <form method="post">                                   
                                                                                                                                <td colspan="3"><input
                                                                                                                                                                            class="form-control"
                                                                                                                                                                            id="titre-activite"
                                                                                                                                                                            name="titre-activite"
                                                                                                                                                                            type="text"
                                                                                                                                                                            style="text-align:center; font-size:22px; bold"
                                                                                                                                                                            value="<?php echo $row['titre-activite']; ?>">
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <tr><td style="text-align:center ; display:none" >
                                                                                                                                                                    <button
                                                                                                                                                                        type="submit"
                                                                                                                                                                        name="submit"
                                                                                                                                                                        id="submit" 
                                                                                                                                                                        class="btn btn-oo btn-primary">
                                                                                                                                                                        Mise à jour</button>
                                                                                                                                </td>
                                                                                                                                <td style="text-align:center ;">
                                                                                                                                                                    <button type="submit"
                                                                                                                                                                        class="btn btn-primaryg btn-block"
                                                                                                                                                                        name="submit-ins">S'inscrire </button>
                                                                                                                                </td>
                                                                                                                                <td style="text-align:center ;">
                                                                                                                                                                    <button type="submit"
                                                                                                                                                                        class="btn btn-primary btn-block"
                                                                                                                                                                        name="submit">Modifier</button>
                                                                                                                                </td>
                                                                                                                                <td style="text-align:center ;">
                                                                                                                                                                    <button 
                                                                                                                                                                        type="submit"
                                                                                                                                                                        class="btn btn-primary-rouge btn-block"
                                                                                                                                                                        name="submit-desins">Se désinscrire</button>
                                                                                                                                </td>
                                                                                                                            </tr>                                
                                                                                                                            </tr>                                <!-- <tr>
                                                                                                                                                                <td colspan="4"></td>
                                                                                                                                                            </tr> -->
                                                                                                                            <tr>
                                                                                                                                                                <th>Date</th>
                                                                                                                                                                <td><input
                                                                                                                                                                        class="form-control"
                                                                                                                                                                        id="date_depart"
                                                                                                                                                                        name="date_depart"
                                                                                                                                                                        type="date"
                                                                                                                                                                        value="<?php echo $row['date_depart']; ?>">
                                                                                                                                                                </td>
                                                                                                                                                                <th>Heure</th>
                                                                                                                                                                <td><input
                                                                                                                                                                        class="form-control"
                                                                                                                                                                        id="heure_depart"
                                                                                                                                                                        name="heure_depart"
                                                                                                                                                                        type="time"
                                                                                                                                                                        value="<?php echo $row['heure_depart']; ?>">
                                                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <tr>
                                                                                                                                                                <th><a href="voir-membre.php?id=<?php echo $row['id-membre']; ?>">Orga: <?php echo " ".$nom_org." "; ?></a></th>
                                                                                                                                                                <td colspan="0"><input
                                                                                                                                                                        
                                                                                                                                                                        id="id-membre"
                                                                                                                                                                        name="id-membre"
                                                                                                                                                                        type="text"
                                                                                                                                                                        value="<?php echo $row['id-membre']; ?>">
                                                                                                                                                                </td>
                                                                                                                                                                <th>ville</th>
                                                                                                                                                                <td><input
                                                                                                                                                                        class="form-control"
                                                                                                                                                                        id="ville"
                                                                                                                                                                        name="ville"
                                                                                                                                                                        type="text"
                                                                                                                                                                        value="<?php echo $row['ville']; ?>">
                                                                                                                                                                        <!-- <script type="text/javascript">
                                                                                                                                                                            document.getElementById("ville").onchange = function() {
                                                                                                                                                                                document.getElementById("submit").submit(); };
                                                                                                                                                                        </script> -->
                                                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <tr>
                                                                                                                                                                <th>lng</th>
                                                                                                                                                                <td><input
                                                                                                                                                                        class="form-control"
                                                                                                                                                                        id="lng" name="lng"
                                                                                                                                                                        type="text"
                                                                                                                                                                        value="<?php echo $row['lng']; ?>">
                                                                                                                                                                </td>
                                                                                                                                                                <th>lat</th>
                                                                                                                                                                <td><input
                                                                                                                                                                        class="form-control"
                                                                                                                                                                        id="lat"
                                                                                                                                                                        name="lat"
                                                                                                                                                                        type="text"
                                                                                                                                                                        value="<?php echo $row['lat']; ?>">
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <tr>
                                                                                                                                                                <th>places</th>
                                                                                                                                                                <td><input
                                                                                                                                                                        class="form-control"
                                                                                                                                                                        id="places"
                                                                                                                                                                        name="places"
                                                                                                                                                                        type="text"
                                                                                                                                                                        value="<?php echo $row['places']; ?>">
                                                                                                                                                                </td>
                                                                                                                                                                <script type="text/javascript">
                                                                                                                                                                    document.getElementById("places").onchange = function() {
                                                                                                                                                                        document.getElementById("submit").submit(); };
                                                                                                                                                                </script>
                                                                                                                                                                <th>nb tables</th>
                                                                                                                                                                <td><input
                                                                                                                                                                        class="form-control"
                                                                                                                                                                        id="nb-tables"
                                                                                                                                                                        name="nb-tables"
                                                                                                                                                                        type="text"
                                                                                                                                                                        value="<?php echo $row['nb-tables']; ?>">
                                                                                                                                                                </td>
                                                                                                                                                                
                                                                                                                                                                <!-- <td>
                                                                                                                                                                <label for="commentaire"></label>
                                                                                                                                                                    <select name="commentaire" id="commentaire">
                                                                                                                                                                        <option value=<?php echo $row['commentaire']; ?> selected><?php echo $row['commentaire']; ?></option>
	                                                                                                                                                                    <?php if ($row['commentaire'] <> '1') echo "<option value='1'>1</option>";?> 
                                                                                                                                                                        <?php if ($row['commentaire'] <> '2') echo "<option value='2'>2</option>";?> 
                                                                                                                                                                        <?php if ($row['commentaire'] <> '3') echo "<option value='3'>3</option>";?> 
                                                                                                                                                                    </select>
                                                                                                                                                                </td> -->
                                                                                                                            </tr>
                                                                                                                            <tr>
                                                                                                                                                                <th>buyin</th>
                                                                                                                                                                <td><input
                                                                                                                                                                        class="form-control"
                                                                                                                                                                        id="pasbuyinsword"
                                                                                                                                                                        name="buyin"
                                                                                                                                                                        type="text"
                                                                                                                                                                        value="<?php echo $row['buyin']; ?>">
                                                                                                                                                                </td>
                                                                                                                                                                <th>rake</th>
                                                                                                                                                                <td><input
                                                                                                                                                                        class="form-control"
                                                                                                                                                                        id="rake"
                                                                                                                                                                        name="rake"
                                                                                                                                                                        type="text"
                                                                                                                                                                        value="<?php echo $row['rake']; ?>">
                                                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <tr>
                                                                                                                                                                <th>recave</th>
                                                                                                                                                                <td><input
                                                                                                                                                                        class="form-control"
                                                                                                                                                                        id="recave"
                                                                                                                                                                        name="recave"
                                                                                                                                                                        type="text"
                                                                                                                                                                        value="<?php echo $row['recave']; ?>">
                                                                                                                                                                </td>
                                                                                                                                                                <th>addon</th>
                                                                                                                                                                <td><input
                                                                                                                                                                        class="form-control"
                                                                                                                                                                        id="addon"
                                                                                                                                                                        name="addon"
                                                                                                                                                                        type="text"
                                                                                                                                                                        value="<?php echo $row['addon']; ?>">
                                                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                            <tr>
                                                                                                                                                                <th>bounty</th>
                                                                                                                                                                <td><input
                                                                                                                                                                        class="form-control"
                                                                                                                                                                        id="bounty"
                                                                                                                                                                        name="bounty"
                                                                                                                                                                        type="text"
                                                                                                                                                                        value="<?php echo $row['bounty']; ?>">
                                                                                                                                                                </td>
                                                                                                                                                                <th>ante</th>
                                                                                                                                                                <td><input
                                                                                                                                                                        class="form-control"
                                                                                                                                                                        id="ante"
                                                                                                                                                                        name="ante"
                                                                                                                                                                        type="text"
                                                                                                                                                                        value="<?php echo $row['ante']; ?>">
                                                                                                                                                            </td>
                                                                                                                            </tr>                       
                                                                                                                            <tr>
                                                                                                                                                                <th>jetons</th>
                                                                                                                                                                <td><input
                                                                                                                                                                        class="form-control"
                                                                                                                                                                        id="jetons"
                                                                                                                                                                        name="jetons"
                                                                                                                                                                        type="text"
                                                                                                                                                                        value="<?php echo $row['jetons']; ?>">
                                                                                                                                                                </td>
                                                                                                                                                                <th>bonus</th>
                                                                                                                                                                <td><input
                                                                                                                                                                        class="form-control"
                                                                                                                                                                        id="bonus"
                                                                                                                                                                        name="bonus"
                                                                                                                                                                        type="text"
                                                                                                                                                                        value="<?php echo $row['bonus']; ?>">
                                                                                                                                                                </td>
                                                                                                                            </tr>                        
                                                                                                                            <tr>                            
                                                                                                                                                                <td style="display:none" ; colspan="2" >
                                                                                                                                                                    <select name="lois" value = "lois" class="form-control" required="false" >
                                                                                                                                                                        <option  
                                                                                                                                                                            value="<?php echo htmlentities($_SESSION['id']); ?>"> <?php echo htmlentities($_SESSION['id']); ?>
                                                                                                                                                                        </option>
                                                                                                                                                                    </select>
                                                                                                                                                                </td>
                                                                                                                                                                <td style="display:none" ; colspan="2" >
                                                                                                                                                                    <select name="activi" value = "activi" class="form-control" required="false">
                                                                                                                                                                        <option
                                                                                                                                                                            value="<?php echo htmlentities($row['id-activite']); ?>"> <?php echo htmlentities($row['id-activite']); ?>
                                                                                                                                                                        </option>
                                                                                                                                                                    </select>
                                                                                                                                                                </td>
                                                                                                                                                                <td style="display:none;"
                                                                                                                                                                    style="text-align:center ;">
                                                                                                                                                                    <button type="submit"
                                                                                                                                                                    class="btn btn-primary btn-block"
                                                                                                                                                                    name="submit">Mise à
                                                                                                                                                                    jour</button>
                                                                                                                                                                </td> 
                                                                                                                            </tr> 
                                                                                                                         </tr>
                                                                                                                    </table>
                                                                                                                                </form>
                                                                                                                <?php
                                                                                                                }
                                                                                                                ?>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div id="t1E">
                                                                                    <div class="ccontainer-fluid ccontainer-fullw bbg-white ">                                                                                                                                                                        
                                                                                        <?php
                                                                                        // $tableau=array();
                                                                                        $sql = mysqli_query($con, "SELECT  `id-membre`,`position`,`id-participation` FROM `participation` WHERE (`id-activite` = '30' AND `id-table` = '1')  ORDER BY `id-siege` ");
                                                                                        $nb_lignes=mysqli_num_rows($sql);
                                                                                        // echo $nb_lignes;
                                                                                        
                                                                                        while($row = mysqli_fetch_array($sql))
                                                                                            {   $tableau[] = $row[0]; 
                                                                                                $tableau2[] = $row[1];
                                                                                                $tableau1[] = $row[2];    
                                                                                            $sql2 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  '$row[0]'");
                                                                                            while ($row2 = mysqli_fetch_array($sql2)) { $tableau3[] = $row2['pseudo']; } 
                                                                                            };
                                                                                        // // idmembre ->
                                                                                        //     echo "-".$tableau[0].".".$tableau[1].".".$tableau[2].".".$tableau[3].".".$tableau[4].".".$tableau[5].".".$tableau[6].".".$tableau[7].".".$tableau[8]."-"   ;    
                                                                                        // // pseudo ->
                                                                                        // echo "/".$tableau3[0].".".$tableau3[1].".".$tableau3[2].".".$tableau3[3].".".$tableau3[4].".".$tableau3[5].".".$tableau3[6].".".$tableau3[7].".".$tableau3[8]."/" ; 
                                                                                        // // positions ->
                                                                                        // echo "(".$tableau2[0].".".$tableau2[1].".".$tableau2[2].".".$tableau2[3].".".$tableau2[4].".".$tableau2[5].".".$tableau2[6].".".$tableau2[7].".".$tableau2[8].")" ;
                                                                                        // // idparticip ->
                                                                                        // echo "{".$tableau1[0].".".$tableau1[1].".".$tableau1[2].".".$tableau1[3].".".$tableau1[4] .".".$tableau1[5].".".$tableau1[6].".".$tableau1[7].".".$tableau1[8]."}" ;                                                                                 
                                                                                        
                                                                                        ?>
                                                                       
                                                                       


<div id="main">
    <div class="players">
        <div class="player player-1 playing" id="player1" >
            <div class="avatar p1" style="background: blue ;font-size: 1.7vw">
            <form method="post">
            
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[0] ?>
                        class="btbn btn-pokerblue btn-block "
                        name="submitpl"><?php echo $tableau3[0] ?>
                    </button>
                </form>
            </div>
        </div>

        <div class="player player-1p playing" id="player1p" >
            

            <div class='player player-1p playing' opacity:0.5>
                <div class='place-content'> <span>1</span></div>
            </div>

            
        </div>


        <div class="player player-2 playing"  id="player2">
            <div class="avatar p2" style="background: red ;font-size: 1.7vw">
                <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[1] ?>
                        class="btbn btn-pokerred btn-block "
                        name="submitpl"><?php echo $tableau3[1] ?>
                    </button>
                </form>
            </div>
        </div>
        <div class="player player-3 playing"  id="player3">
            <div class="avatar p3" style="background: black ;font-size:18px">
            <form method="post">
                <button 
                    type="submit"
                    id='submitpl'
                    value=<?php echo $tableau1[2] ?>
                    class="btnn btn-primary-noir btn-block "
                    name="submitpl"><?php echo $tableau3[2] ?>
                </button>
            </form>
            </div>
        </div>
        <div class="player player-4 playing"  id="player4">
            <div class="avatar p4" style="background: orange; font-size:18px">
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[3] ?>
                        class="btnn btn-primary-orange2 btn-block "
                        name="submitpl"><?php echo $tableau3[3] ?>
                    </button>
            </div>
        </div>
        <div class="player player-5 playing"  id="player5">
            <div class="avatar p5" style="background: grey; font-size:18px">
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[4] ?>
                        class="btnn btn-primary-grey btn-block "
                        name="submitpl"><?php echo $tableau3[4] ?>
                    </button>
            </div>
        </div>
        <div class="player player-6 playing"  id="player6">
            <div class="avatar p6" style="background: brown; font-size:18px">
             
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[5] ?>
                        class="btnn btn-primary-brown btn-block"
                        name="submitpl"><?php echo $tableau3[5] ?>
                    </button>
            </div>
        </div>
        <div class="player player-7 playing"  id="player7">
            <div class="avatar p7" style="background: pink; font-size:18px">
            
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[6] ?>
                        class="btnn btn-primary-pink btn-block"
                        name="submitpl"><?php echo $tableau3[6] ?>
                    </button>
            </div>
        </div>
        <div class="player player-8 playing"  id="player8">
            <div class="avatar p8" style="background: purple; font-size:18px">
             
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[7] ?>
                        class="btnn btn-primary-blue btn-block"
                        name="submitpl"><?php echo $tableau3[7] ?>
                    </button>
            </div>
        </div>
    </div>
    <div class='square-box' opacity:0.5>
        <div class='square-content'> <span>TABLE N°1</span></div>
    </div>
    <div class='square-box2' opacity:0.5>
        <div class='square-content'> <span></span></div>
    </div>
</div>


                                                                                    </div>
                                                                                    </div>
                                                                                <div id="t2E">
                                                                                    <div class="ccontainer-fluid ccontainer-fullw bbg-white ">                                                                                                                                                                        
                                                                                        <?php
                                                                                        $tableau=array();$tableau1=array();$tableau2=array();$tableau3=array();
                                                                                        $sql = mysqli_query($con, "SELECT  `id-membre`,`position`,`id-participation` FROM `participation` WHERE (`id-activite` = '30' AND `id-table` = '2')  ORDER BY `position` ");
                                                                                        $nb_lignes=mysqli_num_rows($sql);
                                                                                        // echo $nb_lignes;
                                                                                        
                                                                                        while($row = mysqli_fetch_array($sql))
                                                                                            {   $tableau[] = $row[0]; 
                                                                                                $tableau2[] = $row[1];
                                                                                                $tableau1[] = $row[2];    
                                                                                            $sql2 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  '$row[0]'");
                                                                                            while ($row2 = mysqli_fetch_array($sql2)) { $tableau3[] = $row2['pseudo']; } 
                                                                                            };
                                                                                        // echo "-".$tableau[0].".".$tableau[1].".".$tableau[2].".".$tableau[3].".".$tableau[4].".".$tableau[5].".".$tableau[6].".".$tableau[7].".".$tableau[8]."-"   ;    
                                                                                        // echo "/".$tableau3[0].".".$tableau3[1].".".$tableau3[2].".".$tableau3[3].".".$tableau3[4].".".$tableau3[5].".".$tableau3[6].".".$tableau3[7].".".$tableau3[8]."/" ; 
                                                                                        // echo "(".$tableau2[0].".".$tableau2[1].".".$tableau2[2].".".$tableau2[3].".".$tableau2[4].".".$tableau2[5].".".$tableau2[6].".".$tableau2[7].".".$tableau2[8].")" ;
                                                                                        // echo "{".$tableau1[0].".".$tableau1[1].".".$tableau1[2].".".$tableau1[3].".".$tableau1[4] .".".$tableau1[5].".".$tableau1[6].".".$tableau1[7].".".$tableau1[8]."}" ;                                                                                 
                                                                                        
                                                                                        ?>
                                                                       
                                                                       


<div id="main">
    <div class="players">
        <div class="player player-1 playing" id="player1" >
            <div class="avatar p1" style="background: blue; font-size:18px">
            <form method="post">
            
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[0] ?>
                        class="bbtn btn-pokerblue btn-block "
                        name="submitpl"><?php echo $tableau3[0] ?>
                    </button>
                </form>
            </div>
        </div>
        <div class="player player-2 playing"  id="player2">
            <div class="avatar p2" style="background: red; font-size:18px">
                <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[1] ?>
                        class="bbtn btn-primary btn-block "
                        name="submitpl"><?php echo $tableau3[1] ?>
                    </button>
                </form>
            </div>
        </div>
        <div class="player player-3 playing"  id="player3">
            <div class="avatar p3" style="background: black; font-size:18px">
            <form method="post">
                <button 
                    type="submit"
                    id='submitpl'
                    value=<?php echo $tableau1[2] ?>
                    class="bbtn btn-primary btn-block "
                    name="submitpl"><?php echo $tableau3[2] ?>
                </button>
            </form>
            </div>
        </div>
        <div class="player player-4 playing"  id="player4">
            <div class="avatar p4" style="background: orange; font-size:18px">
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[3] ?>
                        class="btnn btn-primary btn-block "
                        name="submitpl"><?php echo $tableau3[3] ?>
                    </button>
            </div>
        </div>
        <div class="player player-5 playing"  id="player5">
            <div class="avatar p5" style="background: grey; font-size:18px">
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[4] ?>
                        class="btnn btn-primary btn-block"
                        name="submitpl"><?php echo $tableau3[4] ?>
                    </button>
            </div>
        </div>
        <div class="player player-6 playing"  id="player6">
            <div class="avatar p6" style="background: brown; font-size:18px">
             
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[5] ?>
                        class="btnn btn-primary btn-block "
                        name="submitpl"><?php echo $tableau3[5] ?>
                    </button>
            </div>
        </div>
        <div class="player player-7 playing"  id="player7">
            <div class="avatar p7" style="background: pink; font-size:18px">
            
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[6] ?>
                        class="btnn btn-primary btn-block "
                        name="submitpl"><?php echo $tableau3[6] ?>
                    </button>
            </div>
        </div>
        <div class="player player-8 playing"  id="player8">
            <div class="avatar p8" style="background: purple; font-size:18px">
             
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[7] ?>
                        class="btnn btn-primary btn-block"
                        name="submitpl"><?php echo $tableau3[7] ?>
                    </button>
            </div>
        </div>
    </div>
    <div class='square-box' opacity:0.5>
        <div class='square-content'> <span>TABLE N°2</span></div>
    </div>
    <div class='square-box2' opacity:0.5>
        <div class='square-content'> <span></span></div>
    </div>


</div>


                                                                                    </div>                                                                                     
                                                                                    </div> 
                                                                                <div id="t3E">
                                                                                    <div class="ccontainer-fluid ccontainer-fullw bbg-white ">                                                                                                                                                                        
                                                                                        <?php
                                                                                        $tableau=array();$tableau1=array();$tableau2=array();$tableau3=array();
                                                                                        $sql = mysqli_query($con, "SELECT  `id-membre`,`position`,`id-participation` FROM `participation` WHERE (`id-activite` = '30' AND `position` > 16)  ORDER BY `position` ");
                                                                                        $nb_lignes=mysqli_num_rows($sql);
                                                                                        // echo $nb_lignes;
                                                                                        
                                                                                        while($row = mysqli_fetch_array($sql))
                                                                                            {   $tableau[] = $row[0]; 
                                                                                                $tableau2[] = $row[1];
                                                                                                $tableau1[] = $row[2];    
                                                                                            $sql2 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  '$row[0]'");
                                                                                            while ($row2 = mysqli_fetch_array($sql2)) { $tableau3[] = $row2['pseudo']; } 
                                                                                            };
                                                                                        // echo "-".$tableau[0].".".$tableau[1].".".$tableau[2].".".$tableau[3].".".$tableau[4].".".$tableau[5].".".$tableau[6].".".$tableau[7].".".$tableau[8]."-"   ;    
                                                                                        // echo "/".$tableau3[0].".".$tableau3[1].".".$tableau3[2].".".$tableau3[3].".".$tableau3[4].".".$tableau3[5].".".$tableau3[6].".".$tableau3[7].".".$tableau3[8]."/" ; 
                                                                                        // echo "(".$tableau2[0].".".$tableau2[1].".".$tableau2[2].".".$tableau2[3].".".$tableau2[4].".".$tableau2[5].".".$tableau2[6].".".$tableau2[7].".".$tableau2[8].")" ;
                                                                                        // echo "{".$tableau1[0].".".$tableau1[1].".".$tableau1[2].".".$tableau1[3].".".$tableau1[4] .".".$tableau1[5].".".$tableau1[6].".".$tableau1[7].".".$tableau1[8]."}" ;                                                                                 
                                                                                        
                                                                                        ?>
                                                                       
                                                                       


<div id="main">
    <div class="players">
        <div class="player player-1 playing" id="player1" >
            <div class="avatar p1" style="background: blue;">
            <form method="post">
            
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[0] ?>
                        class="btn btn-primary btn-block font-size:13px "
                        name="submitpl"><?php echo $tableau3[0] ?>
                    </button>
                </form>
            </div>
        </div>
        <div class="player player-2 playing"  id="player2">
            <div class="avatar p2" style="background: red;">
                <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[1] ?>
                        class="btn btn-primary btn-block font-size:12px"
                        name="submitpl"><?php echo $tableau3[1] ?>
                    </button>
                </form>
            </div>
        </div>
        <div class="player player-3 playing"  id="player3">
            <div class="avatar p3" style="background: black">
            <form method="post">
                <button 
                    type="submit"
                    id='submitpl'
                    value=<?php echo $tableau1[2] ?>
                    class="btn btn-primary btn-block font-size:13px"
                    name="submitpl"><?php echo $tableau3[2] ?>
                </button>
            </form>
            </div>
        </div>
        <div class="player player-4 playing"  id="player4">
            <div class="avatar p4" style="background: orange;">
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[3] ?>
                        class="btn btn-primary btn-block font-size:13px"
                        name="submitpl"><?php echo $tableau3[3] ?>
                    </button>
            </div>
        </div>
        <div class="player player-5 playing"  id="player5">
            <div class="avatar p5" style="background: grey;">
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[4] ?>
                        class="btn btn-primary btn-block font-size:13px"
                        name="submitpl"><?php echo $tableau3[4] ?>
                    </button>
            </div>
        </div>
        <div class="player player-6 playing"  id="player6">
            <div class="avatar p6" style="background: brown;">
             
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[5] ?>
                        class="btn btn-primary btn-block font-size:13px"
                        name="submitpl"><?php echo $tableau3[5] ?>
                    </button>
            </div>
        </div>
        <div class="player player-7 playing"  id="player7">
            <div class="avatar p7" style="background: pink;">
            
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[6] ?>
                        class="btn btn-primary btn-block font-size:13px"
                        name="submitpl"><?php echo $tableau3[6] ?>
                    </button>
            </div>
        </div>
        <div class="player player-8 playing"  id="player8">
            <div class="avatar p8" style="background: purple;">
             
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[7] ?>
                        class="btn btn-primary btn-block"
                        name="submitpl"><?php echo $tableau3[7] ?>
                    </button>
            </div>
        </div>
    </div>
    <div class='square-box' opacity:0.5>
        <div class='square-content'> <span>TABLE N°3</span></div>
    </div>
</div>


                                                                                    </div>
                                                                                    </div>
                                                                                <div id="t4E">
                                                                                    <div class="ccontainer-fluid ccontainer-fullw bbg-white ">                                                                                                                                                                        
                                                                                        <?php
                                                                                        $tableau=array();$tableau1=array();$tableau2=array();$tableau3=array();
                                                                                        $sql = mysqli_query($con, "SELECT  `id-membre`,`position`,`id-participation` FROM `participation` WHERE (`id-activite` = '30' AND `position` > 24)  ORDER BY `position` ");
                                                                                        $nb_lignes=mysqli_num_rows($sql);
                                                                                        // echo $nb_lignes;
                                                                                        
                                                                                        while($row = mysqli_fetch_array($sql))
                                                                                            {   $tableau[] = $row[0]; 
                                                                                                $tableau2[] = $row[1];
                                                                                                $tableau1[] = $row[2];    
                                                                                            $sql2 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  '$row[0]'");
                                                                                            while ($row2 = mysqli_fetch_array($sql2)) { $tableau3[] = $row2['pseudo']; } 
                                                                                            };
                                                                                        // echo "-".$tableau[0].".".$tableau[1].".".$tableau[2].".".$tableau[3].".".$tableau[4].".".$tableau[5].".".$tableau[6].".".$tableau[7].".".$tableau[8]."-"   ;    
                                                                                        // echo "/".$tableau3[0].".".$tableau3[1].".".$tableau3[2].".".$tableau3[3].".".$tableau3[4].".".$tableau3[5].".".$tableau3[6].".".$tableau3[7].".".$tableau3[8]."/" ; 
                                                                                        // echo "(".$tableau2[0].".".$tableau2[1].".".$tableau2[2].".".$tableau2[3].".".$tableau2[4].".".$tableau2[5].".".$tableau2[6].".".$tableau2[7].".".$tableau2[8].")" ;
                                                                                        // echo "{".$tableau1[0].".".$tableau1[1].".".$tableau1[2].".".$tableau1[3].".".$tableau1[4] .".".$tableau1[5].".".$tableau1[6].".".$tableau1[7].".".$tableau1[8]."}" ;                                                                                 
                                                                                        
                                                                                        ?>
                                                                       
                                                                       


<div id="main">
    <div class="players">
        <div class="player player-1 playing" id="player1" >
            <div class="avatar p1" style="background: blue;">
            <form method="post">
            
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[0] ?>
                        class="btn btn-primary btn-block font-size:13px "
                        name="submitpl"><?php echo $tableau3[0] ?>
                    </button>
                </form>
            </div>
        </div>
        <div class="player player-2 playing"  id="player2">
            <div class="avatar p2" style="background: red;">
                <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[1] ?>
                        class="btn btn-primary btn-block font-size:12px"
                        name="submitpl"><?php echo $tableau3[1] ?>
                    </button>
                </form>
            </div>
        </div>
        <div class="player player-3 playing"  id="player3">
            <div class="avatar p3" style="background: black">
            <form method="post">
                <button 
                    type="submit"
                    id='submitpl'
                    value=<?php echo $tableau1[2] ?>
                    class="btn btn-primary btn-block font-size:13px"
                    name="submitpl"><?php echo $tableau3[2] ?>
                </button>
            </form>
            </div>
        </div>
        <div class="player player-4 playing"  id="player4">
            <div class="avatar p4" style="background: orange;">
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[3] ?>
                        class="btn btn-primary btn-block font-size:3nv"
                        name="submitpl"><?php echo $tableau3[3] ?>
                    </button>
            </div>
        </div>
        <div class="player player-5 playing"  id="player5">
            <div class="avatar p5" style="background: grey;">
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[4] ?>
                        class="btn btn-primary btn-block font-size:13px"
                        name="submitpl"><?php echo $tableau3[4] ?>
                    </button>
            </div>
        </div>
        <div class="player player-6 playing"  id="player6">
            <div class="avatar p6" style="background: brown;">
             
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[5] ?>
                        class="btn btn-primary btn-block font-size:13px"
                        name="submitpl"><?php echo $tableau3[5] ?>
                    </button>
            </div>
        </div>
        <div class="player player-7 playing"  id="player7">
            <div class="avatar p7" style="background: pink;">
            
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[6] ?>
                        class="btn btn-primary btn-block font-size:13px"
                        name="submitpl"><?php echo $tableau3[6] ?>
                    </button>
            </div>
        </div>
        <div class="player player-8 playing"  id="player8">
            <div class="avatar p8" style="background: purple;">
             
            <form method="post">
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[7] ?>
                        class="btn btn-primary btn-block"
                        name="submitpl"><?php echo $tableau3[7] ?>
                    </button>
            </div>
        </div>
    </div>
    <div class='square-box' opacity:0.5>
        <div class='square-content'> <span>TABLE N°4</span></div>
    </div>
</div>


                                                                                    </div>
                                                                                    </div>    
                                                                                <div id="inscritsE">
                                                                                <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <div class="container-fluid container-fullw bg-white">
                                                                                                <div class="row">
                                                                                                    <div class="col-md-12">
                                                                                                        <div class="row margin-top-30">
                                                                                                            <div class="col-lg-8 col-md-12">
                                                                                                                <div class="panel panel-wwhite">
                                                                                                                    <div class="panel-body">
                                                                                                                        <div id="layoutSidenav_content">
                                                                                                                            <main>
                                                                                                                                <div class="container-fluid px-4">
                                                                                                                                    <ol class="breadcrumb mb-4">
                                                                                                                                        <li class="breadcrumb-item">
                                                                                                                                            <a href="liste-membres.php">Membres</a>
                                                                                                                                        </li>
                                                                                                                                        <li class="breadcrumb-item active">
                                                                                                                                            Inscrits
                                                                                                                                        </li>
                                                                                                                                    </ol>
                                                                                                                                    <div class="card mb-4">
                                                                                                                                        <div class="card-body">
                                                                                                                                            <table
                                                                                                                                                id="example"
                                                                                                                                                class="display"
                                                                                                                                                style="width:100% ;font-size:14px;">
                                                                                                                                                <thead>
                                                                                                                                                    <tr>
                                                                                                                                                        <th>Ordre
                                                                                                                                                        </th>
                                                                                                                                                        <th>Pseudo
                                                                                                                                                        </th>
                                                                                                                                                        <th>Statut
                                                                                                                                                        </th>
                                                                                                                                                        <th>Position
                                                                                                                                                        </th>
                                                                                                                                                        <!-- <th>Modifier
                                                                                                                                                        </th> -->
                                                                                                                                                        <th>Participation
                                                                                                                                                        </th>
                                                                                                                                                        
                                                                                                                                                    </tr>
                                                                                                                                                </thead>
                                                                                                                                                <tbody>
                                                                                                                                                    <?php $ret = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-activite` = '$id' ");
                                                                                                                                                    $cnt = 1;
                                                                                                                                                    while ($row = mysqli_fetch_array($ret)) { ?>
                                                                                                                                                        <?php
                                                                                                                                                        $id2 = $row['id-membre'];
                                                                                                                                                        $sql2 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` = '$id2' ORDER BY 'ordre' DESC");
                                                                                                                                                        while ($row2 = mysqli_fetch_array($sql2)) { ?>
                                                                                                                                                            <tr>
                                                                                                                                                                <td>
                                                                                                                                                                    <?php echo $row['ordre']; ?>
                                                                                                                                                                </td>
                                                                                                                                                                <td>
                                                                                                                                                                    <a href="voir-membre.php?id=<?php echo $row['id-membre']; ?>"  ><?php echo $row2['pseudo']; ?></a>
                                                                                                                                                                </td>
                                                                                                                                                                <td>
                                                                                                                                                                    <?php echo $row['option']; ?>
                                                                                                                                                                </td> 
                                                                                                                                                                <td>
                                                                                                                                                                    <?php echo $row['position']; ?>
                                                                                                                                                                </td> 
                                                                                                                                                            <?php } ?>
                                                                                                                                                            <!-- <td>
                                                                                                                                                                <a href="voir-membre.php?id=<?php echo $row['id-membre']; ?>"  ><i class="fa fa-pencil"></i></a>
                                                                                                                                                                <i class="fas fa-edit"></i></a>
                                                                                                                                                            </td> -->
                                                                                                                                                            <td>
                                                                                                                                                                <a href="voir-participation.php?id=<?php echo $row['id-participation']; ?>"  tooltip="Edition"><i class="fa fa-pencil"></i></a>
                                                                                                                                                                <i class="fas fa-edit"></i></a>
                                                                                                                                                            </td>                    
                                                                                                                                                        </tr>
                                                                                                                                                        <?php $cnt = $cnt + 1;
                                                                                                                                                    } ?>
                                                                                                                                                </tbody>
                                                                                                                                            </table>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                </div>
                                                                                                                            </main>
                                                                                                                        </div>
                                                                                                                    <!-- </div> -->
                                                                                                                    <form method="post"> 
                                                                                                                        <table>      
                                                                                                                            <tr>                            
                                                                                                                                <td colspan="3" >
                                                                                                                                                                <select
                                                                                                                                                                    name="lois"
                                                                                                                                                                    class="form-control"
                                                                                                                                                                    required="true">
                                                                                                                                                                    <option value="lois">- Participant à Ajouter manuellement -</option>
                                                                                                                                                                    <?php $ret2 = mysqli_query($con, "select * from membres ORDER BY `pseudo` ASC");
                                                                                                                                                                    while ($row2 = mysqli_fetch_array($ret2)) {
                                                                                                                                                                        ?>
                                                                                                                                                                        <option
                                                                                                                                                                            value="<?php echo htmlentities($row2['id-membre']); ?>">
                                                                                                                                                                            <?php echo htmlentities($row2['pseudo']); ?>
                                                                                                                                                                        </option>       
                                                                                                                                                                    <?php } ?>
                                                                                                                                                                </select>
                                                                                                                                </td>                                                                                    
                                                                                                                                                            
                                                                                                                                <td style="display:none" ; colspan="2" >
                                                                                                                                                                    <select name="activi" value = "activi" class="form-control" required="false">
                                                                                                                                                                        <option value="<?php echo htmlentities($id); ?>"> <?php echo htmlentities($id); ?></option>
                                                                                                                                                                    </select>
                                                                                                                                </td>
                                                                                                                                <td>
                                                                                                                                                                <button
                                                                                                                                                                    type="submit"
                                                                                                                                                                    name="submit3"
                                                                                                                                                                    id="submit3"
                                                                                                                                                                    class="btn btn-o btn-primary">
                                                                                                                                                                    Ajout
                                                                                                                                                                </button>
                                                                                                                                </td>
                                                                                                                            </tr>
                                                                                                                        </table>
                                                                                                                    </form>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <!-- end: BASIC EXAMPLE -->
                                                                    <!-- end: SELECT BOXES -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                                                    <!-- start: FOOTER -->
                                                    <?php include('include/footer.php'); ?>
                                                                                    <!-- end: FOOTER -->
                                                                                    <!-- start: SETTINGS -->
                                                    <?php include('include/setting.php'); ?>
                                                                                    <!-- end: SETTINGS -->
                                                </div>
                                                                                <!-- start: MAIN JAVASCRIPTS -->
                                                <script src="vendor/jquery/jquery.min.js"></script>
                                                <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
                                                <script src="vendor/modernizr/modernizr.js"></script>
                                                <script src="vendor/jquery-cookie/jquery.cookie.js"></script>
                                                <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
                                                <script src="vendor/switchery/switchery.min.js"></script>
                                                <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
                                                <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
                                                                                <!-- end: MAIN JAVASCRIPTS -->
                                                                                <!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
                                                <script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
                                                <script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
                                                <script src="vendor/autosize/autosize.min.js"></script>
                                                <script src="vendor/selectFx/classie.js"></script>
                                                <script src="vendor/selectFx/selectFx.js"></script>
                                                <script src="vendor/select2/select2.min.js"></script>
                                                <script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
                                                <script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
                                                                                <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
                                                                                <!-- start: CLIP-TWO JAVASCRIPTS -->
                                                <script src="assets/js/main.js"></script>
                                                                                <!-- start: JavaScript Event Handlers for this page -->
                                                <script src="assets/js/form-elements.js"></script>
                                                <script>
                                                    jQuery(document).ready(function () {
                                                        Main.init();
                                                        FormElements.init();
                                                    });
                                                </script>
                                                                                <!-- end: JavaScript Event Handlers for this page -->
                                                                                <!-- end: CLIP-TWO JAVASCRIPTS -->
                                                <script
                                                    src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"
                                                    crossorigin="anonymous">
                                                </script>
                                                <script src="../js/scripts.js"></script>
                                                                                <!-- <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
            <script src="../js/datatables-simple-demo.js"></script> -->
                                                <script type="text/javascript" language="javascript">
                                                    function afficher(id) {
                                                        var leCalque = document.getElementById(id);
                                                        var leCalqueE = document.getElementById(id + "E");

                                                        document.getElementById("infosE").className = "rubrique bgImg";
                                                        document.getElementById("t2E").className = "rubrique bgImg";
                                                        document.getElementById("inscritsE").className = "rubrique bgImg";
                                                        document.getElementById("t1E").className = "rubrique bgImg";
                                                        document.getElementById("t3E").className = "rubrique bgImg";
                                                        document.getElementById("t4E").className = "rubrique bgImg";

                                                        document.getElementById("infos").className = "btnnav";
                                                        document.getElementById("t2").className = "btnnav";
                                                        document.getElementById("inscrits").className = "btnnav";
                                                        document.getElementById("t1").className = "btnnav";
                                                        document.getElementById("t3").className = "btnnav";
                                                        document.getElementById("t4").className = "btnnav";


                                                        leCalqueE.className += " montrer";
                                                        leCalque.className = "btnnavA";
                                                    }
                                                </script>
                                                <script type="text/javascript" language="javascript">
                                                    afficher('infos');
                                                </script>
                                            </body>
                                        </html>
<?php } ?>