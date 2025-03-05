<?php
session_start();
error_reporting(0);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//$comp = intval($_GET['comp']); // get value
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
    if (isset($_POST['submitplb'])) {
        $sql = mysqli_query($con, "UPDATE `participation` SET `id-membre`='$id_membre',`id-membre-vainqueur`='$id_membre_vainqueur',`id-activite`='$id_activite',`id-siege`='$id_siege',`id-table`='$id_table',`id-challenge`='$id_challenge',`option`='$option',`ordre`='$ordre',`valide`='$valide',`commentaire`='$commentaire',`classement`='$classement',`points`='$points',`gain`='$gain',`ds`= CURRENT_TIMESTAMP,`ip-ins`='1',`ip-mod`='2',`ip-sup`='3' WHERE `participation`.`id-participation` = '$id'");
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
 
            $sql2 = mysqli_query($con, "INSERT INTO `participation` (`id-membre`, `id-membre-vainqueur`, `id-activite`, `id-siege`, `id-table`, `id-challenge`, `option`, `ordre`, `valide`, `commentaire`, `classement`, `points`, `gain`, `ds`, `ip-ins`, `ip-mod`, `ip-sup`, `bounty`) VALUES ( '$lois', '', '$activi', '', '', '', 'Reservation', '$ordre', 'Actif', NULL, '1', '0', '0', CURRENT_TIMESTAMP, '', '', '', '0')");

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
                                                        $('#example').DataTable({ order: [[0, 'asc']], pageLength: 8, language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' } });
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
  body{
        /* background-image: url('/assets/images/table.jpg') !important;
    } */
  .square-box {
        position: absolute;
        width: 87%;
        height: 58%;
        overflow: hidden;
        /* background: #6495ED; */
        background-size: cover;
        background-image: url('/panel/images/table-empire-10j.jpg');
        opacity: 1;
        left: 0;
        right: 0;
        top: -100px;
        bottom: 0;
        border-radius: 200px;
        border: 1px solid white;


    }

    .info1{
        position: absolute;
        width: 90%;
        height: 50%;
        overflow: hidden;
        background: #6495ED;
        opacity: 0.75;
        left: 0;
        right: 0;
        top: -100px;
        bottom: 0;
        margin: auto;
       
    }
    .info2{
        position: absolute;
        width: 90%;
        height: 50%;
        overflow: hidden;
        background: #6495ED;
        opacity: 0.75;
        left: 0;
        right: 0;
        top: -100px;
        bottom: 0;
        margin: auto;
       
    }

    .info1-content {
        position: absolute;
        top: 77%;
        left: 10%;
        color: blue;
        width: 100%%;
        height: 100%%;
        font-size: 2vw;

    }
    .info2-content {
        position: absolute;
        top: 82%;
        left: 10%;
        color: green;
        width: 100%%;
        height: 100%%;
        font-size: 2vw;

    }
    .info3-content {
        position: absolute;
        top: 87%;
        left: 10%;
        color: black;
        width: 100%%;
        height: 100%%;
        font-size: 2vw;

    }
    .info4-content {
        position: absolute;
        top: 92%;
        left: 10%;
        color: red;
        width: 100%%;
        height: 100%%;
        font-size: 2vw;

    }
    .info5-content {
        position: absolute;
        top: 97%;
        left: 10%;
        color: grey;
        width: 100%%;
        height: 100%%;
        font-size: 2vw;

    }
    .square-box2 {
        position: absolute;
        width: 50%;
        height: 20%;
        overflow: hidden;
        background: red;
        opacity: 0.25;
        left: 0;
        right: 0;
        top: -130px;
        bottom: 0;
        margin: auto;
        /* border-radius: 200px;
        border: 2px solid white; */


    }
    .titi {
        display: flex;
        align-items: center; 
        justify-content: center;
        text-align: center; 
    }

    .square-box:before {
        content: "";
        display: block;
        padding-top: 100%;
    }

    .square-content {
        position: absolute;
        top: 43%;
        left: 28%;
        color: white;
        width: 100%%;
        height: 100%%;
        font-size: 2.25vw;

    }

    .place-content {
        position: absolute;
        top: 45%;
        left: 20%;
        color: white;
        width: 100%%;
        height: 100%%;
        font-size: 2.5vw;

    }
    .place2-content {
        position: relative;
        top: 40px;
        left: 20px;
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
    color: white;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
} 

.players .player.player-2 {
    top: 14%;
    
    left:73%;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
}

.players .player.player-3 {
    top: 29%;
    left: 94%;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
}

.players .player.player-4 {
    top: 55%;
    left: 94%;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
}

.players .player.player-5 {
    top: 71%;
    left: 73%;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
}
.players .player.player-6 {
    top: 73.5%;
    left: 50%;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
}

.players .player.player-7 {
    top: 71%;
    left: 26%;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
}

.players .player.player-8 {
    top: 55%;
    left: 6%;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
}
.players .player.player-9 {
    top: 29%;
    left: 6%;
    -webkit-transform: translatex(-50%) translatey(-50%);
    transform: translatex(-50%) translatey(-50%);
}
.players .player.player-10 {
    top: 14%;
    left: 26%;
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
.p1p{
  
  
  
  display: flex;
  align-items: center; 
  justify-content: center;
  text-align: center; 
  color: #666;
  font-weight: bold;
  font-size: 17px;
 

}
.p1{
  
  
  
  display: flex;
  align-items: center; 
  justify-content: center;
  text-align: center; 
  color: #666;
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
 .p9{
   
   display: flex;
   align-items: center; 
   justify-content: center;
   text-align: center; 
   color: #fff;
   font-weight: bold;
   font-size: 17px;
   
  
 }
 .p10{
   
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
                                                                            <?php
                                                                            $reqnbt = mysqli_query($con, "SELECT * FROM `activite` WHERE `id-activite` = '30' ");
                                                                            $res = mysqli_fetch_array($reqnbt) ;
                                                                            $nbt = $res["nb-tables"];   
                                                                            
                                                                            if ($nbt == '2') 
                                                                            {?>
                                                                                <div id="bMenu">
                                                                                <a href="#" id="infos" class="btnnav" onmouseover="afficher2('infos')">Infos</a>
                                                                                <a href="#" id="inscrits" class="btnnav" onmouseover="afficher2('inscrits')">Inscrits</a>
                                                                                <a href="#" id="t1" class="btnnav" onmouseover="afficher2('t1')">Table 1</a>
                                                                                <a href="#" id="t2" class="btnnav" onmouseover="afficher2('t2')">Table 2</a>
                                                                                <!-- <a href="#" id="t3" class="btnnav" onmouseover="afficher2('t3')">Table 3</a> -->
                                                                                <!-- <a href="#" id="t4" class="btnnav" onmouseover="afficher2('t4')">Table 4</a> -->
                                                                                </div>
                                                                            <?php };

                                                                            if ($nbt == '3') 
                                                                            {?>
                                                                                <div id="bMenu">
                                                                                <a href="#" id="infos" class="btnnav" onmouseover="afficher3('infos')">Infos</a>
                                                                                <a href="#" id="inscrits" class="btnnav" onmouseover="afficher3('inscrits')">Inscrits</a>
                                                                                <a href="#" id="t1" class="btnnav" onmouseover="afficher3('t1')">Table 1</a>
                                                                                <a href="#" id="t2" class="btnnav" onmouseover="afficher3('t2')">Table 2</a>
                                                                                <a href="#" id="t3" class="btnnav" onmouseover="afficher3('t3')">Table 3</a>
                                                                                <!-- <a href="#" id="t4" class="btnnav" onmouseover="afficher3('t4')">Table 4</a> -->
                                                                                </div>
                                                                            <?php };

                                                                            if ($nbt == '4') 
                                                                            {?>
                                                                            <div id="bMenu">
                                                                                <a href="#" id="infos" class="btnnav" onmouseover="afficher4('infos')">Infos</a>
                                                                                <a href="#" id="inscrits" class="btnnav" onmouseover="afficher4('inscrits')">Inscrits</a>
                                                                                <a href="#" id="t1" class="btnnav" onmouseover="afficher4('t1')">Table 1</a>
                                                                                <a href="#" id="t2" class="btnnav" onmouseover="afficher4('t2')">Table 2</a>
                                                                                <a href="#" id="t3" class="btnnav" onmouseover="afficher4('t3')">Table 3</a>
                                                                                <a href="#" id="t4" class="btnnav" onmouseover="afficher4('t4')">Table 4</a>
                                                                            </div>
                                                                            <?php };
                                                                            
                                                                            ?>

                                                                            <div id="bSection">
                                                                                <div id="infosE">
                                                                                    <div class="wrap-content container" id="container">
                                                                                        <div class="container-fluid bbg-pink">
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
                                                                                                                            <tr><td rowspan="2" ><img src="images/<?php echo $row['photo'] ?>" width="85" height="85" style="align:center">
                                                                                                                                    <form id="image_upload_form" enctype="multipart/form-data" action="upload-photo-activite.php?editid=<?php echo $id ?>" method="post" class="change-pic">
                                                                                                                                        <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                                                                                                                                        <div>
                                                                                                                                            <input type="file"  class = "fa fa-camera" id="file" name="fileToUpload"  style="display:none;"/><input type="button" onClick="fileToUpload.click();" value="Modifier"/>
                                                                                                                                            <i class = "fa fa-camera"></i>
                                                                                                                                        </div> 
                                                                                                                                        <script type="text/javascript">
                                                                                                                                            document.getElementById("file").onchange = function() {
                                                                                                                                                document.getElementById("image_upload_form").submit(); };
                                                                                                                                        </script>
                                                                                                                                        <!-- <script src="https://webtinq.nl/ap/script.js"></script>
                                                                                                                                        <script type="text/javascript">play('example');</script> -->
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
                                                                                                                                                                <th><a href="creation-blindes.php?act=<?php echo $row['id-activite']; ?>&sou=/panel/voir-activite.php?uid=">Heure</a></th>
                                                                                                                                                                
                                                                                                                                                                
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
                                                                                    <div class="ccontainer-fluid ccontainer-fullw bg-dark-bricky " style="background-color:grey;opacity:1">                                                                                                                                                                        
                                                                                        <?php
                                                                                        $rowcountocc = "0";
                                                                                        $scru = "0";
                                                                                        while ((int)$rowcountocc < 1) 
                                                                                        {
                                                                                            (int)$scru ++;
                                                                                            // echo $scru."/////////////////////////////////";
                                                                                            $sqla = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = '$id' AND `id-table` = '$scru' ) LIMIT 1 ");
                                                                                            $rowcountocc = mysqli_num_rows($sqla);
                                                                                        };
                                                                                        (int)$tableaff = (int)$scru;
                                                                                        // $tableaff = 1;
                                                                                        $cnt=0;
                                                                                        
                                                                                        $sql = mysqli_query($con, "SELECT  `id-membre`,`position`,`id-participation`,`id-siege`,`option` FROM `participation` WHERE (`id-activite` = '$id' AND `id-table` = '$tableaff' )  ORDER BY `id-siege` ");
                                                                                        $nb_lignes=mysqli_num_rows($sql);
                                                                                        // echo $nb_lignes;
                                                                                        
                                                                                        while($row = mysqli_fetch_array($sql))
                                                                                            {  
                                                                                            $cnt = $cnt + 1; 
                                                                                            if ($row['id-siege'] <> $cnt ) $cnt=$cnt+1;
                                                                                            if ($row['id-siege'] <> $cnt ) $cnt=$cnt+1;
                                                                                            if ($row['id-siege'] <> $cnt ) $cnt=$cnt+1;
                                                                                            if ($row['id-siege'] <> $cnt ) $cnt=$cnt+1;
                                                                                            if ($row['id-siege'] <> $cnt ) $cnt=$cnt+1;
                                                                                            if ($row['id-siege'] <> $cnt ) $cnt=$cnt+1;
                                                                                            if ($row['id-siege'] <> $cnt ) $cnt=$cnt+1;
                                                                                            if ($row['id-siege'] <> $cnt ) $cnt=$cnt+1;
                                                                                            if ($row['id-siege'] <> $cnt ) $cnt=$cnt+1;
                                                                                            $idmembre[$cnt] = $row['id-membre']; 
                                                                                            $position[$cnt] = $row['position'];
                                                                                            $idparticipation[$cnt] = $row['id-participation']; 
                                                                                            $siege[$cnt] = $row['id-siege']; 
                                                                                            $option[$cnt] = $row['option'];   
                                                                                            $sql2 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  '$idmembre[$cnt]'");
                                                                                            while ($row2 = mysqli_fetch_array($sql2)) 
                                                                                                {
                                                                                                $pseudo[$cnt] = $row2['pseudo'];
                                                                                                } 
                                                                                            };
                                                                                        // //siege ->
                                                                                        // echo "-".$siege[1].".".$siege[2].".".$siege[3].".".$siege[4].".".$siege[5].".".$siege[6].".".$siege[7].".".$siege[8].".".$siege[9] .$siege[10]."-"  ;
                                                                                        // //idmembre ->
                                                                                        // echo "-".$idmembre[1].".".$idmembre[2].".".$idmembre[3].".".$idmembre[4].".".$idmembre[5].".".$idmembre[6].".".$idmembre[7].".".$idmembre[8].".".$idmembre[9].".".$idmembre[10]."-" ;    
                                                                                        // //pseudo ->
                                                                                        // echo "-".$pseudo[1].".".$pseudo[2].".".$pseudo[3].".".$pseudo[4].".".$pseudo[5].".".$pseudo[6].".".$pseudo[7].".".$pseudo[8].".".$pseudo[9] .$pseudo[10]."-"  ; 
                                                                                        // //option ->
                                                                                        // echo "-".$option[1].".".$option[2].".".$option[3].".".$option[4].".".$option[5].".".$option[6].".".$option[7].".".$option[8].".".$option[9] .$option[10]."-"  ;
                                                                                        // //idparticip ->
                                                                                        // echo "-".$idparticipation[1].".".$idparticipation[2].".".$idparticipation[3].".".$idparticipation[4].".".$idparticipation[5].".".$idparticipation[6].".".$idparticipation[7].".".$idparticipation[8].".".$idparticipation[9] .$idparticipation[10]."-"  ;                                                                                 
                                                                                        // $variable_comp = '<script type="text/javascript">document.write(comp);</script>'; 
                                                                                        // echo "{".$_SESSION["fin".$_SESSION["blinde"]]."}"."/".$_SESSION["blinde"];
                                                                                        // echo $_SESSION["blinde"]." --> ".$_SESSION["fin"."1"].$_SESSION["fin"."2"].$_SESSION["fin"."3"].$_SESSION["fin"."4"]."-----".$_SESSION["fin".$_SESSION["bl"]]."*****".$_SESSION["bl"]."t=".$tableaff.$scru.$rowcountocc."a";
                                                                                        
                                                                                        ?>
                                                                                        
                                                                       
                                                                       

<div id="main">
    <div class="players">
        <div class="player player-1 playing" id="player1" >
            <div class="avatar p1" style="background: blue ;font-size: 1.7vw">
            <form method="post">
                <?php if ($option[1] == 'Elimine' ) $nom ='X'; else $nom = $pseudo[1] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $idparticipation[1] ?>
                        class="btbn btn-pokerblue btn-block "
                        name="submitpl"><?php echo $nom ?>
                    </button>
                </form>
            </div>
        </div>
        <div class="player player-1p playing" id="player1p" >
            <div class='player player-1p playing' opacity:0.33>
                <div class='place-content' style='color:white' > <a href="modif-horloge.php?act=<?php echo $id;?>&min=-2&sou=http://poker31.org/panel/voir-activite.php?uid=">-2M</a></div>
            </div>
        </div>
        <div class="player player-1p playing" id="player1p" >
            <div class='player player-1p playing' opacity:0.33>
                <div class='place2-content'> <a href="modif-horloge.php?act=<?php echo $id;?>&min=2&sou=http://poker31.org/panel/voir-activite.php?uid=">+2M</a></div>
                <!-- <?php echo $_SESSION["blinde"]; ?> -->
            </div>
        </div>                                                                                        


        <div class="player player-2 playing"  id="player2">
            <div class="avatar p2" style="background: red ;font-size: 1.7vw">
                <form method="post">
                <?php if ($option[2] == 'Elimine' ) $nom ='X'; else $nom = $pseudo[2] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $idparticipation[2]  ?>
                        class="btbn btn-pokerred btn-block "
                        name="submitpl"><?php echo $nom ?>
                    </button>
                </form>
            </div>
        </div>
        <div class="player player-3 playing"  id="player3">
            <div class="avatar p3" style="background: black ;font-size:18px">
            <form method="post">
            <?php if ($option[3] == 'Elimine' ) $nom ='X'; else $nom = $pseudo[3] ; ?>
                <button 
                    type="submit"
                    id='submitpl'
                    value=<?php echo $idparticipation[3] ?>
                    class="btnn btn-primary-noir btn-block "
                    name="submitpl"><?php echo $nom ?>
                </button>
            </form>
            </div>
        </div>
        <div class="player player-4 playing"  id="player4">
            <div class="avatar p4" style="background: orange; font-size:18px">
            <form method="post">
            <?php if ($option[4] == 'Elimine' ) $nom ='X'; else $nom = $pseudo[4] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $idparticipation[4] ?>
                        class="btnn btn-primary-orange2 btn-block "
                        name="submitpl"><?php echo $nom ?>
                    </button>
            </div>
        </div>
        <div class="player player-5 playing"  id="player5">
            <div class="avatar p5" style="background: grey; font-size:18px">
            <form method="post">
            <?php if ($option[5] == 'Elimine' ) $nom ='X'; else $nom = $pseudo[5] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $idparticipation[5] ?>
                        class="btnn btn-primary-grey btn-block "
                        name="submitpl"><?php echo $nom ?>
                    </button>
            </div>
        </div>
        <div class="player player-6 playing"  id="player6">
            <div class="avatar p6" style="background: brown; font-size:18px">
             
            <form method="post">
            <?php if ($option[6] == 'Elimine' ) $nom ='X'; else $nom = $pseudo[6] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $idparticipation[6] ?>
                        class="btnn btn-primary-brown btn-block"
                        name="submitpl"><?php echo $nom ?>
                    </button>
            </div>
        </div>
        <div class="player player-7 playing"  id="player7">
            <div class="avatar p7" style="background: pink; font-size:18px">
            
            <form method="post">
            <?php if ($option[7] == 'Elimine' ) $nom ='X'; else $nom = $pseudo[7] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $idparticipation[7] ?>
                        class="btnn btn-primary-pink btn-block"
                        name="submitpl"><?php echo $nom ?>
                    </button>
            </div>
        </div>
        <div class="player player-8 playing"  id="player8">
            <div class="avatar p8" style="background: purple; font-size:18px">
             
            <form method="post">
            <?php if ($option[8] == 'Elimine' ) $nom ='X'; else $nom = $pseudo[8] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $idparticipation[8] ?>
                        class="btnn btn-primary-purple btn-block"
                        name="submitpl"><?php echo $nom ?>
                    </button>
            </div>
        </div>
        <div class="player player-9 playing"  id="player9">
            <div class="avatar p9" style="background: grey; font-size:18px">
            
            <form method="post">
            <?php if ($option[9] == 'Elimine' ) $nom ='X'; else $nom = $pseudo[9] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $idparticipation[9] ?>
                        class="btnn btn-primary-orange2 btn-block"
                        name="submitpl"><?php echo $nom ?>
                    </button>
            </div>
        </div>
        <div class="player player-10 playing"  id="player10">
            <div class="avatar p10" style="background: green; font-size:18px">
            
            <form method="post">
            <?php if ($option[10] == 'Elimine' ) $nom ='X'; else $nom = $pseudo[10] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $idparticipation[10] ?>
                        class="btnn btn-primary-noir btn-block"
                        name="submitpl"><?php echo $nom ?>
                    </button>
            </div>
        </div>
        
    </div> 
    
    <!-- <div class='square-box' opacity:0.5> -->
    <div class='square-box' opacity:0.85>
        <div class='square-content'> <div id="response"></div></div>
    </div>
    <!-- <div class='square-box' opacity:0.85>
         <div class='square-content'> <div id="comp"></div></div>
    </div> -->
    <!-- <?php $comp = '<script type="text/javascript">document.write(comp);</script> '; ?>
 
    <div class='square-box2' opacity:1>
        <div class='square-content'> <?php echo  '<script type="text/javascript">document.write(comp);</script> ;'?> </div>
    </div> -->
</div>


                                                                                           

                                                                                    </div>
                                                                                    
                                                                                    <div class='info1-content '> <?php echo "Dernier Eliminé : " ?>
                                                                                    <?php
                                                                                    $sql = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Elimine' )");
                                                                                    $rowcount = mysqli_num_rows($sql);
                                                                                    $sql = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule' )");
                                                                                    $rowcount2 = mysqli_num_rows($sql);
                                                                                    $sql = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule' ) OR (`id-activite` = $id AND `option` NOT LIKE 'Elimine' )");
                                                                                    $rowcount3 = mysqli_num_rows($sql);
                                                                                    $req = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-activite` = $id AND `option` LIKE 'Elimine' ORDER BY `ds` ASC"); 
                                                                                    $rowcounteli = mysqli_num_rows($req);               
                                                                                    while ($res = mysqli_fetch_array($req)) 
                                                                                    { 
                                                                                        $eli1=$res["id-membre"];
                                                                                        $res2=mysqli_query($con,"SELECT * FROM `membres` WHERE (`id-membre` = $eli1)");
                                                                                        $row2=mysqli_fetch_array($res2);$nom1=$row2["pseudo"];
                                                                                    };
                                                                                    echo $nom1; 
                                                                                    ?>
                                                                                    </div>

                                                                                    <div class='info2-content '> <?php echo "Pause et fin des recaves dans : .. minutes" ?></div>
                                                                                    <?php
                                                                                    
                                                                                    if ($rowcount2>5) {
                                                                                        $payes=2 ;
                                                                                        $r1=0.6;
                                                                                        $r2=0.4;
                                                                                    };
                                                                                    if ($rowcount2>8) {
                                                                                        $payes=3;
                                                                                        $r1=0.5;
                                                                                        $r2=0.3;
                                                                                        $r3=0.2;
                                                                                    };
                                                                                    if ($rowcount2>13) {
                                                                                        $payes=4;
                                                                                        $r1=0.4;
                                                                                        $r2=0.28;
                                                                                        $r3=0.19;
                                                                                        $r4=0.13;
                                                                                    };
                                                                                    if ($rowcount2>20) {
                                                                                        $payes=5;
                                                                                        $r1=0.35;
                                                                                        $r2=0.25;
                                                                                        $r3=0.18;
                                                                                        $r4=0.13;
                                                                                        $r5=0.09; 
                                                                                    };
                                                                                    ?>
                                                                                    <?php if ($rowcount2-$rowcounteli-$payes>0) 
                                                                                    { ?>
                                                                                        <div class='info3-content '> <?php echo "Premier des ".$payes." payés dans ".$rowcount2-$rowcounteli-$payes." Joueurs sur ".$rowcount2 ?></div>
                                                                                    <?php } else { ?>
                                                                                        <div class='info3-content '> <?php echo $payes." payés maintenant sur les ".$rowcount2." joueurs" ?></div>
                                                                                    <?php };
                                                                                    
                                                                                    $sql2 = mysqli_query($con, "SELECT * FROM `activite` WHERE (`id-activite` = $id  )");
                                                                                    $res2 = mysqli_fetch_array($sql2);
                                                                                    $buyin=$res2["buyin"];
                                                                                    $jetons=$res2["jetons"];
                                                                                    $pot=0;$nbr=0;$nba=0;
                                                                                    $req3 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule') ");                
                                                                                    while ($res3 = mysqli_fetch_array($req3)) 
                                                                                    {    
                                                                                     $pot=$pot+(((int)($res3["recave"])+(int)($res3["addon"])));
                                                                                     $nbr=$nbr+$res3["recave"];
                                                                                     $nba=$nba+$res3["addon"];
                                                                                    }; 
                                                                                    $tot=$pot+$rowcount2;$final=$tot*$buyin;
                                                                                    if ($payes==2) {
                                                                                        $p2=$final * $r2; $p2=$p2/10; $p2=round($p2,0); $p2=$p2*10;
                                                                                        $p1=$final-$p2;
                                                                                        ?> <div class='info4-content '> <?php echo "Pot total : ".$final."€ soit : "."P1=".$p1."€, P2=".$p2."€" ?></div><?php
                                                                                    };
                                                                                    if ($payes==3) { 
                                                                                        $p3=$final * $r3; $p3=$p3/10; $p3=round($p3,0); $p3=$p3*10;
                                                                                        $p2=$final * $r2; $p2=$p2/10; $p2=round($p2,0); $p2=$p2*10;
                                                                                        $p1=$final-$p3-$p2;
                                                                                        ?> <div class='info4-content '> <?php echo "Pot total : ".$final."€ soit : "."P1=".$p1."€, P2=".$p2."€, P3=".$p3."€" ?></div><?php
                                                                                    };
                                                                                    if ($payes==4) {
                                                                                        $p4=$final * $r4; $p4=$p4/10; $p4=round($p4,0); $p4=$p4*10; 
                                                                                        $p3=$final * $r3; $p3=$p3/10; $p3=round($p3,0); $p3=$p3*10;
                                                                                        $p2=$final * $r2; $p2=$p2/10; $p2=round($p2,0); $p2=$p2*10;
                                                                                        $p1=$final-$p4-$p3-$p2;
                                                                                        ?> <div class='info4-content '> <?php echo "Pot total : ".$final."€ soit : "."P1=".$p1."€, P2=".$p2."€, P3=".$p3."€, P4=".$p4."€" ?></div><?php
                                                                                    };
                                                                                    if ($payes==5) {
                                                                                        $p5=$final * $r5; $p5=$p5/10; $p5=round($p5,0); $p5=$p5*10;
                                                                                        $p4=$final * $r4; $p4=$p4/10; $p4=round($p4,0); $p4=$p4*10; 
                                                                                        $p3=$final * $r3; $p3=$p3/10; $p3=round($p3,0); $p3=$p3*10;
                                                                                        $p2=$final * $r2; $p2=$p2/10; $p2=round($p2,0); $p2=$p2*10;
                                                                                        $p1=$final-$p5-$p4-$p3-$p2;
                                                                                        ?><div class='info4-content '> <?php echo "Pot total : ".$final."€ soit : "."P1=".$p1."€, P2=".$p2."€, P3=".$p3."€, P4=".$p4."€, P5=".$p5."€" ?></div><?php
                                                                                    };
                                                                                    $enjeu=($rowcount2-$rowcounteli);
                                                                                    if ($enjeu == 0 ) $enjeu=1; else $enjeu= $rowcount2-$rowcounteli;
                                                                                    ?><div class='info5-content '> <?php echo "Stack Moyen : ".($jetons*($rowcount2+$nbr+$nba))/($enjeu)." sur ".($jetons*($rowcount2+$nbr+$nba)) ." = (".$rowcount2."B ) + "." (".$nbr."R ) + "." (".$nba."A )" ?>
                                                                                </div>
                                                                                    
                                                                                </div>
                                                                                <div id="t2E">
                                                                                    <div class="ccontainer-fluid ccontainer-fullw bbg-white ">                                                                                                                                                                        
                                                                                        <?php
                                                                                        $tableau=array();$tableau1=array();$tableau2=array();$tableau3=array();
                                                                                        $sql = mysqli_query($con, "SELECT  `id-membre`,`position`,`id-participation` FROM `participation` WHERE (`id-activite` = '$id' AND `id-table` = '2')  ORDER BY `id-siege` ");
                                                                                        $nb_lignes=mysqli_num_rows($sql);
                                                                                        // echo $nb_lignes;
                                                                                        
                                                                                        while($row = mysqli_fetch_array($sql))
                                                                                            {   $tableau[] = $row[0]; 
                                                                                                $tableau2[] = $row[1];
                                                                                                $tableau1[] = $row[2];    
                                                                                            $sql2 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  '$row[0]'");
                                                                                            while ($row2 = mysqli_fetch_array($sql2)) { $tableau3[] = $row2['pseudo']; } 
                                                                                            };
                                                                                            // idmembre ->
                                                                                        //     echo "-".$tableau[0].".".$tableau[1].".".$tableau[2].".".$tableau[3].".".$tableau[4].".".$tableau[5].".".$tableau[6].".".$tableau[7].".".$tableau[8]."-"   ;    
                                                                                        // // pseudo ->
                                                                                        // echo "/".$tableau3[0].".".$tableau3[1].".".$tableau3[2].".".$tableau3[3].".".$tableau3[4].".".$tableau3[5].".".$tableau3[6].".".$tableau3[7].".".$tableau3[8]."/" ; 
                                                                                        // // positions ->
                                                                                        // echo "(".$tableau2[0].".".$tableau2[1].".".$tableau2[2].".".$tableau2[3].".".$tableau2[4].".".$tableau2[5].".".$tableau2[6].".".$tableau2[7].".".$tableau2[8].")" ;
                                                                                        // // idparticip ->
                                                                                        // echo "{".$tableau1[0].".".$tableau1[1].".".$tableau1[2].".".$tableau1[3].".".$tableau1[4] .".".$tableau1[5].".".$tableau1[6].".".$tableau1[7].".".$tableau1[8]."}" ;                                                                                 
                                                                                        // // idsiege ->
                                                                                        // echo "[".$tableau4[0].".".$tableau4[1].".".$tableau4[2].".".$tableau4[3].".".$tableau4[4] .".".$tableau4[5].".".$tableau4[6].".".$tableau4[7].".".$tableau4[8]."]" ;                                                                                 
                                                                                        // // option ->
                                                                                        // echo "[".$tableau5[0].".".$tableau5[1].".".$tableau5[2].".".$tableau5[3].".".$tableau5[4] .".".$tableau5[5].".".$tableau5[6].".".$tableau5[7].".".$tableau5[8]."]" ;                                                                                 
                                                                                        
                                                                                        ?>
                                                                                        <!-- <?php include('horloge-ok-28nov-1600.php'); ?> -->
                                                                                        <!-- <?php include_once('horloge.php'); ?> -->
                                                                                        
                                                                       

                                                                       


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
            <?php if ($tableau5[7] == 'Elimine' ) $nom ='X'; else $nom = $tableau3[$tableau4[7]-1] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[$tableau4[7]-1] ?>
                        class="btnn btn-primary-purple btn-block"
                        name="submitpl"><?php echo $nom ?>
                    </button>
            </div>
        </div>
        <div class="player player-9 playing"  id="player9">
            <div class="avatar p9" style="background: grey; font-size:18px">
            
            <form method="post">
            <?php if ($tableau5[8] == 'Elimine' ) $nom ='X'; else $nom = $tableau3[$tableau4[8]-1] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[$tableau4[8]-1] ?>
                        class="btnn btn-primary-orange2 btn-block"
                        name="submitpl"><?php echo $nom ?>
                    </button>
            </div>
        </div>
        
        <div class="player player-10 playing"  id="player10">
            <div class="avatar p10" style="background: green; font-size:18px">
            
            <form method="post">
            <?php if ($tableau5[9] == 'Elimine' ) $nom ='X'; else $nom = $tableau3[$tableau4[9]-1] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[$tableau4[9]-1] ?>
                        class="btnn btn-primary-noir btn-block"
                        name="submitpl"><?php echo $nom ?>
                    </button>
            </div>
        </div>
        <div class="player player-9 playing"  id="player9">
            <div class="avatar p9" style="background: grey; font-size:18px">
            
            <form method="post">
            <?php if ($tableau5[6] == 'Elimine' ) $nom ='X'; else $nom = $tableau3[$tableau4[6]-1] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[$tableau4[6]-1] ?>
                        class="btnn btn-primary-orange2 btn-block"
                        name="submitpl"><?php echo $nom ?>
                    </button>
            </div>
        </div>
        <div class="player player-10 playing"  id="player10">
            <div class="avatar p10" style="background: green; font-size:18px">
            
            <form method="post">
            <?php if ($tableau5[6] == 'Elimine' ) $nom ='X'; else $nom = $tableau3[$tableau4[6]-1] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[$tableau4[6]-1] ?>
                        class="btnn btn-primary-noir btn-block"
                        name="submitpl"><?php echo $nom ?>
                    </button>
            </div>
        </div>
    </div>
        <!-- <div class='square-box' opacity:0.5> -->
        <div class='square-box' opacity:1>
        <div class='square-content'> <div id="response"></div></div>
    </div>
    <div class='square-box2' opacity:1>
        <div class='square-content'> <span></span></div>
    </div>
</div>
                                                                                    </div>   
                                                                                    <div class='info1-content '> <?php echo "Dernier Eliminé : " ?>
                                                                                    <?php
                                                                                    $sql = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Elimine' )");
                                                                                    $rowcount = mysqli_num_rows($sql);
                                                                                    $sql = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule' )");
                                                                                    $rowcount2 = mysqli_num_rows($sql);
                                                                                    $sql = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule' ) OR (`id-activite` = $id AND `option` NOT LIKE 'Elimine' )");
                                                                                    $rowcount3 = mysqli_num_rows($sql);
                                                                                    $req = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-activite` = $id AND `option` LIKE 'Elimine' ORDER BY `ds` ASC"); 
                                                                                    $rowcounteli = mysqli_num_rows($req);               
                                                                                    while ($res = mysqli_fetch_array($req)) 
                                                                                    { 
                                                                                        $eli1=$res["id-membre"];
                                                                                        $res2=mysqli_query($con,"SELECT * FROM `membres` WHERE (`id-membre` = $eli1)");
                                                                                        $row2=mysqli_fetch_array($res2);$nom1=$row2["pseudo"];
                                                                                    };
                                                                                    echo $nom1; 
                                                                                    ?>
                                                                                    </div>

                                                                                    <div class='info2-content '> <?php echo "Pause et fin des recaves dans : .. minutes" ?></div>
                                                                                    <?php
                                                                                    if ($rowcount2>5) $payes=2;if ($rowcount2>8) $payes=3;if ($rowcount2>13) $payes=4;if ($rowcount2>20) $payes=5;
                                                                                    ?>
                                                                                    <?php if ($rowcount2-$rowcounteli-$payes>0) 
                                                                                    { ?>
                                                                                        <div class='info3-content '> <?php echo "Premier des ".$payes." payés dans ".$rowcount2-$rowcounteli-$payes." Joueurs sur ".$rowcount2 ?></div>
                                                                                    <?php } else { ?>
                                                                                        <div class='info3-content '> <?php echo $payes." payés maintenant sur les ".$rowcount2." joueurs" ?></div>
                                                                                    <?php };
                                                                                    $sql2 = mysqli_query($con, "SELECT * FROM `activite` WHERE (`id-activite` = $id  )");
                                                                                    $res2 = mysqli_fetch_array($sql2);
                                                                                    $buyin=$res2["buyin"];
                                                                                    $pot=0;
                                                                                    $req3 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule') ");                
                                                                                    while ($res3 = mysqli_fetch_array($req3)) 
                                                                                    {    
                                                                                     $pot=$pot+(((int)($res3["recave"])+(int)($res3["addon"])));
                                                                                    }; 
                                                                                    $tot=$pot+$rowcount2;$final=$tot*$buyin;
                                                                                    ?>
                                                                                    <div class='info4-content '> <?php echo "Pot total : ".$final." €"; ?></div>
                                                                                </div>
                                                                                                                                                                  
                                                                                    </div> 
                                                                                <div id="t3E">
                                                                                <?php if ($nbt >= '3') { ?>    
                                                                                    <div class="ccontainer-fluid ccontainer-fullw bbg-white ">                                                                                                                                                                        
                                                                                        <?php
                                                                                        $tableau=array();$tableau1=array();$tableau2=array();$tableau3=array();
                                                                                        $sql = mysqli_query($con, "SELECT  `id-membre`,`position`,`id-participation` FROM `participation` WHERE (`id-activite` = '$id' AND `id-table` = '3')  ORDER BY `id-siege` ");
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
                        class="btnn btn-primary-purple btn-block"
                        name="submitpl"><?php echo $tableau3[7] ?>
                    </button>
            </div>
        </div>
        <div class="player player-9 playing"  id="player9">
            <div class="avatar p9" style="background: grey; font-size:18px">
            
            <form method="post">
            <?php if ($tableau5[6] == 'Elimine' ) $nom ='X'; else $nom = $tableau3[$tableau4[6]-1] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[$tableau4[6]-1] ?>
                        class="btnn btn-primary-orange2 btn-block"
                        name="submitpl"><?php echo $nom ?>
                    </button>
            </div>
        </div>
        <div class="player player-10 playing"  id="player10">
            <div class="avatar p10" style="background: green; font-size:18px">
            
            <form method="post">
            <?php if ($tableau5[6] == 'Elimine' ) $nom ='X'; else $nom = $tableau3[$tableau4[6]-1] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[$tableau4[6]-1] ?>
                        class="btnn btn-primary-noir btn-block"
                        name="submitpl"><?php echo $nom ?>
                    </button>
            </div>
        </div>
    </div>
    <div class='square-box' opacity:0.99>
    <div class='square-content'> <div id="response"></div></div>
    </div>
    <div class='square-box2' opacity:0.99>
        <div class='square-content'> </div>
    </div>
</div>


                                                                                    </div>
                                                                                    <div class='info1-content '> <?php echo "Dernier Eliminé : " ?>
                                                                                    <?php
                                                                                    $sql = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Elimine' )");
                                                                                    $rowcount = mysqli_num_rows($sql);
                                                                                    $sql = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule' )");
                                                                                    $rowcount2 = mysqli_num_rows($sql);
                                                                                    $sql = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule' ) OR (`id-activite` = $id AND `option` NOT LIKE 'Elimine' )");
                                                                                    $rowcount3 = mysqli_num_rows($sql);
                                                                                    $req = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-activite` = $id AND `option` LIKE 'Elimine' ORDER BY `ds` ASC"); 
                                                                                    $rowcounteli = mysqli_num_rows($req);               
                                                                                    while ($res = mysqli_fetch_array($req)) 
                                                                                    { 
                                                                                        $eli1=$res["id-membre"];
                                                                                        $res2=mysqli_query($con,"SELECT * FROM `membres` WHERE (`id-membre` = $eli1)");
                                                                                        $row2=mysqli_fetch_array($res2);$nom1=$row2["pseudo"];
                                                                                    };
                                                                                    echo $nom1; 
                                                                                    ?>
                                                                                    </div>

                                                                                    <div class='info2-content '> <?php echo "Pause et fin des recaves dans : .. minutes" ?></div>
                                                                                    <?php
                                                                                    if ($rowcount2>5) $payes=2;if ($rowcount2>8) $payes=3;if ($rowcount2>13) $payes=4;if ($rowcount2>20) $payes=5;
                                                                                    ?>
                                                                                    <?php if ($rowcount2-$rowcounteli-$payes>0) 
                                                                                    { ?>
                                                                                        <div class='info3-content '> <?php echo "Premier des ".$payes." payés dans ".$rowcount2-$rowcounteli-$payes." Joueurs sur ".$rowcount2 ?></div>
                                                                                    <?php } else { ?>
                                                                                        <div class='info3-content '> <?php echo $payes." payés maintenant sur les ".$rowcount2." joueurs" ?></div>
                                                                                    <?php };
                                                                                    $sql2 = mysqli_query($con, "SELECT * FROM `activite` WHERE (`id-activite` = $id  )");
                                                                                    $res2 = mysqli_fetch_array($sql2);
                                                                                    $buyin=$res2["buyin"];
                                                                                    $pot=0;
                                                                                    $req3 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule') ");                
                                                                                    while ($res3 = mysqli_fetch_array($req3)) 
                                                                                    {    
                                                                                     $pot=$pot+(((int)($res3["recave"])+(int)($res3["addon"])));
                                                                                    }; 
                                                                                    $tot=$pot+$rowcount2;$final=$tot*$buyin;
                                                                                    ?>
                                                                                    <div class='info4-content '> <?php echo "Pot total : ".$final." €"; ?></div>
                                                                                <?php } ?>    
                                                                                </div>
                                                                                    
                                                                                <div id="t4E"> 
                                                                                    <?php if ($nbt >= '4') { ?>
                                                                                    <div class="ccontainer-fluid ccontainer-fullw bbg-white ">                                                                                                                                                                        
                                                                                        <?php
                                                                                        $tableau=array();$tableau1=array();$tableau2=array();$tableau3=array();
                                                                                        $sql = mysqli_query($con, "SELECT  `id-membre`,`position`,`id-participation` FROM `participation` WHERE (`id-activite` = '$id' AND `id-table` = '4')  ORDER BY `id-siege` ");
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
                        class="btnn btn-primary-purple btn-block"
                        name="submitpl"><?php echo $tableau3[7] ?>
                    </button>
            </div>
        </div>
        <div class="player player-9 playing"  id="player9">
            <div class="avatar p9" style="background: grey; font-size:18px">
            
            <form method="post">
            <?php if ($tableau5[6] == 'Elimine' ) $nom ='X'; else $nom = $tableau3[$tableau4[6]-1] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[$tableau4[6]-1] ?>
                        class="btnn btn-primary-orange2 btn-block"
                        name="submitpl"><?php echo $nom ?>
                    </button>
            </div>
        </div>
        <div class="player player-10 playing"  id="player10">
            <div class="avatar p10" style="background: green; font-size:18px">
            
            <form method="post">
            <?php if ($tableau5[6] == 'Elimine' ) $nom ='X'; else $nom = $tableau3[$tableau4[6]-1] ; ?>
                    <button 
                        type="submit"
                        id='submitpl'
                        value=<?php echo $tableau1[$tableau4[6]-1] ?>
                        class="btnn btn-primary-noir btn-block"
                        name="submitpl"><?php echo $nom ?>
                    </button>
            </div>
        </div>
    </div>
    <div class='square-box' opacity:0.99>
        <div class='square-content'> <div id="response"></div></div>
    </div>
    <div class='square-box2' opacity:0.99>
        <div class='square-content'> </div>
    </div>
</div>
</div>
<div class='info1-content '> <?php echo "Dernier Eliminé : " ?>
                                                                                    <?php
                                                                                    $sql = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Elimine' )");
                                                                                    $rowcount = mysqli_num_rows($sql);
                                                                                    $sql = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule' )");
                                                                                    $rowcount2 = mysqli_num_rows($sql);
                                                                                    $sql = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule' ) OR (`id-activite` = $id AND `option` NOT LIKE 'Elimine' )");
                                                                                    $rowcount3 = mysqli_num_rows($sql);
                                                                                    $req = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-activite` = $id AND `option` LIKE 'Elimine' ORDER BY `ds` ASC"); 
                                                                                    $rowcounteli = mysqli_num_rows($req);               
                                                                                    while ($res = mysqli_fetch_array($req)) 
                                                                                    { 
                                                                                        $eli1=$res["id-membre"];
                                                                                        $res2=mysqli_query($con,"SELECT * FROM `membres` WHERE (`id-membre` = $eli1)");
                                                                                        $row2=mysqli_fetch_array($res2);$nom1=$row2["pseudo"];
                                                                                    };
                                                                                    echo $nom1; 
                                                                                    ?>
                                                                                    </div>

                                                                                    <div class='info2-content '> <?php echo "Pause et fin des recaves dans : .. minutes" ?></div>
                                                                                    <?php
                                                                                    if ($rowcount2>5) $payes=2;if ($rowcount2>8) $payes=3;if ($rowcount2>13) $payes=4;if ($rowcount2>20) $payes=5;
                                                                                    ?>
                                                                                    <?php if ($rowcount2-$rowcounteli-$payes>0) 
                                                                                    { ?>
                                                                                        <div class='info3-content '> <?php echo "Premier des ".$payes." payés dans ".$rowcount2-$rowcounteli-$payes." Joueurs sur ".$rowcount2 ?></div>
                                                                                    <?php } else { ?>
                                                                                        <div class='info3-content '> <?php echo $payes." payés maintenant sur les ".$rowcount2." joueurs" ?></div>
                                                                                    <?php };
                                                                                    $sql2 = mysqli_query($con, "SELECT * FROM `activite` WHERE (`id-activite` = $id  )");
                                                                                    $res2 = mysqli_fetch_array($sql2);
                                                                                    $buyin=$res2["buyin"];
                                                                                    $pot=0;
                                                                                    $req3 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule') ");                
                                                                                    while ($res3 = mysqli_fetch_array($req3)) 
                                                                                    {    
                                                                                     $pot=$pot+(((int)($res3["recave"])+(int)($res3["addon"])));
                                                                                    }; 
                                                                                    $tot=$pot+$rowcount2;$final=$tot*$buyin;
                                                                                    ?>
                                                                                    <div class='info4-content '> <?php echo "Pot total : ".$final." €"; ?></div>
                                                                                <?php } ?>
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
                                                                                                                                        <a href="sieges.php?&ac=<?php echo $id ?>">Inscrits</a>
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
                                                                                                                                                        <th>classt
                                                                                                                                                        </th>
                                                                                                                                                        <th>Pseudo
                                                                                                                                                        </th>
                                                                                                                                                        <th>Statut
                                                                                                                                                        </th>
                                                                                                                                                        <th>Table
                                                                                                                                                        </th>
                                                                                                                                                        <th>Siege
                                                                                                                                                        </th>
                                                                                                                                                        <th>Bounty
                                                                                                                                                        </th>
                                                                                                                                                        <th>Recaves
                                                                                                                                                        </th>
                                                                                                                                                        <th>Addon
                                                                                                                                                        </th>
                                                                                                                                                        <th>Infos
                                                                                                                                                        </th>
                                                                                                                                                    </tr>
                                                                                                                                                </thead>
                                                                                                                                                <tbody>
                                                                                                                                                    <?php $ret = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-activite` = '$id' ");
                                                                                                                                                    $cnt = 1;
                                                                                                                                                    while ($row = mysqli_fetch_array($ret)) { ?>
                                                                                                                                                        <?php
                                                                                                                                                        $id2 = $row['id-membre'];
                                                                                                                                                        $sql2 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` = '$id2' ORDER BY 'classement' ASC");
                                                                                                                                                        while ($row2 = mysqli_fetch_array($sql2)) { ?>
                                                                                                                                                            <tr>
                                                                                                                                                                <td>
                                                                                                                                                                    <?php echo $row['classement']; ?>
                                                                                                                                                                </td>
                                                                                                                                                                <td>
                                                                                                                                                                    <a href="voir-membre.php?id=<?php echo $row['id-membre']; ?>"  ><?php echo $row2['pseudo']; ?></a>
                                                                                                                                                                </td>
                                                                                                                                                                <td>
                                                                                                                                                                    <?php echo $row['option']; ?>
                                                                                                                                                                </td> 
                                                                                                                                                                <td>
                                                                                                                                                                    <?php echo $row['id-table']; ?>
                                                                                                                                                                </td>
                                                                                                                                                                <td>
                                                                                                                                                                    <?php echo $row['id-siege']; ?>
                                                                                                                                                                </td>
                                                                                                                                                                <td>
                                                                                                                                                                    <?php echo $row['bounty']; ?>
                                                                                                                                                                </td>
                                                                                                                                                                <td>
                                                                                                                                                                    <?php echo $row['recave']; ?>
                                                                                                                                                                </td>
                                                                                                                                                                <td>
                                                                                                                                                                    <?php echo $row['addon']; ?>
                                                                                                                                                                </td> 
                                                                                                                                                                <!-- <td>
                                                                                                                                                                    <?php echo $row['classement']; ?>
                                                                                                                                                                </td> -->
                                                                                                                                                            <?php } ?>
                                                                                                                                                            <!-- <td>
                                                                                                                                                                <a href="voir-membre.php?id=<?php echo $row['id-membre']; ?>"  ><i class="fa fa-pencil"></i></a>
                                                                                                                                                                <i class="fas fa-edit"></i></a>
                                                                                                                                                            </td> -->
                                                                                                                                                            <td>
                                                                                                                                                                <a href="voir-participation.php?id=<?php echo $row['id-participation']; ?>"  tooltip="Edition"><i class="fa fa-pencil"></i></a>
                                                                                                                                                                <i class="fas fa-edit"></i></a>
                                                                                                                                                            </td>
                                                                                                                                                            <!-- <td>
                                                                                                                                                                <a href="recaves.php?id=<?php echo $row['id-participation']; ?>&ac=<?php echo $row['id-activite']; ?>&source=<?php echo "https://poker31.org/panel/voir-activite.php?uid="; ?>"  tooltip="Edition"><i class="fa fa-pencil"></i></a>
                                                                                                                                                                <i class="fas fa-edit"></i></a>
                                                                                                                                                            </td> 
                                                                                                                                                            <td>
                                                                                                                                                                <a href="addon.php?id=<?php echo $row['id-participation']; ?>&ac=<?php echo $row['id-activite']; ?>&source=<?php echo "https://poker31.org/panel/voir-activite.php?uid="; ?>"  tooltip="Edition"><i class="fa fa-pencil"></i></a>
                                                                                                                                                                <i class="fas fa-edit"></i></a>
                                                                                                                                                            </td>    
                                                                                                                                                            <td>
                                                                                                                                                            <a href="elimination.php?id=<?php echo $row['id-participation']; ?>&ac=<?php echo $row['id-activite']; ?>&source=<?php echo "https://poker31.org/panel/voir-activite.php?uid="; ?>"  tooltip="Edition"><i class="fa fa-pencil"></i></a>
                                                                                                                                                                <i class="fas fa-edit"></i></a>
                                                                                                                                                                
                                                                                                                                                            </td>                    -->
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
                                                    
                                                    function afficher2(id) {
                                                        var leCalque = document.getElementById(id);
                                                        var leCalqueE = document.getElementById(id + "E");

                                                        document.getElementById("infosE").className = "rubrique bgImg";
                                                        document.getElementById("t2E").className = "rubrique bgImg";
                                                        document.getElementById("inscritsE").className = "rubrique bgImg";
                                                        document.getElementById("t1E").className = "rubrique bgImg";
                                                        // document.getElementById("t3E").className = "rubrique bgImg";
                                                        // document.getElementById("t4E").className = "rubrique bgImg";

                                                        document.getElementById("infos").className = "btnnav";
                                                        document.getElementById("t2").className = "btnnav";
                                                        document.getElementById("inscrits").className = "btnnav";
                                                        document.getElementById("t1").className = "btnnav";
                                                        // document.getElementById("t3").className = "btnnav";
                                                        // document.getElementById("t4").className = "btnnav";

                                                        leCalqueE.className += " montrer";
                                                        leCalque.className = "btnnavA";
                                                    }

                                                    function afficher3(id) {
                                                        var leCalque = document.getElementById(id);
                                                        var leCalqueE = document.getElementById(id + "E");

                                                        document.getElementById("infosE").className = "rubrique bgImg";
                                                        document.getElementById("t2E").className = "rubrique bgImg";
                                                        document.getElementById("inscritsE").className = "rubrique bgImg";
                                                        document.getElementById("t1E").className = "rubrique bgImg";
                                                        document.getElementById("t3E").className = "rubrique bgImg";
                                                        // document.getElementById("t4E").className = "rubrique bgImg";

                                                        document.getElementById("infos").className = "btnnav";
                                                        document.getElementById("t2").className = "btnnav";
                                                        document.getElementById("inscrits").className = "btnnav";
                                                        document.getElementById("t1").className = "btnnav";
                                                        document.getElementById("t3").className = "btnnav";
                                                        // document.getElementById("t4").className = "btnnav";

                                                        leCalqueE.className += " montrer";
                                                        leCalque.className = "btnnavA";
                                                    }

                                                    function afficher4(id) {
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
                                                <?php
                                                $onglet = 'inf';
                                                $onglet = $_GET['onglet'];
                                                if ($onglet == 'inf') {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                       afficher('infos');
                                                    </script>;
                                                    <?php 
                                                };
                                                if($onglet == 'ins') {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                        afficher('inscrits');
                                                    </script>;
                                                    <?php
                                                 };
                                                if ($onglet == '1') {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                        afficher('t1');
                                                    </script>;
                                                    <?php 
                                                };
                                                if ($onglet == '2') {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                        afficher('t2');
                                                    </script>;
                                                    <?php 
                                                };
                                                if ($onglet == '3') {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                        afficher('t3');
                                                    </script>;
                                                    <?php 
                                                };
                                                if ($onglet == '4') {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                        afficher('t4');
                                                    </script>;
                                                    <?php 
                                                };
                                                if ($onglet == '' AND $nbt == "2") {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                        afficher2('t1');
                                                    </script>;
                                                    <?php 
                                                };
                                                
                                                if ($onglet == '' AND $nbt == "3") {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                        afficher3('t1');
                                                    </script>;
                                                    <?php 
                                                };
                                                if ($onglet == '' AND $nbt == "4") {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                        afficher4('t1');
                                                    </script>;
                                                    <?php 
                                                };
                                                 ?>
                                            </body>
                                        </html>
<?php } ?>