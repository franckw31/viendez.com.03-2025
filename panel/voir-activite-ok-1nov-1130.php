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
        $options = $_POST['options'];
        $idmembresession = $_SESSION['id'];

        
        if (($idmembresession == $idmembre) OR ($idmembresession == 265)) {
          $msg = mysqli_query($con, "UPDATE `activite` SET `id-membre` = '$idmembre' , `titre-activite` = '$titre_activite' , `date_depart` = '$date_depart' , `heure_depart` = '$heure_depart' ,`ville` = '$ville' , `places` = '$places' , `options` = '$options' , `commentaire` = '$commentaire' , `buyin` = '$buyin' , `rake` = '$rake' , `bounty` = '$bounty' , `jetons` = '$jetons' , `recave` = '$recave' , `addon` = '$addon' , `ante` = '$ante' , `bonus` = '$bonus' WHERE `id-activite` = '$id'");
        };
        // $_SESSION['msg'] = "Activité ajoutée avec succés !!";
        // header('location:http://poker31.org/panel/liste-activites.php');
        // exit;
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
            $sql0 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = '$activi' AND `option` LIKE 'Reservation') OR (`id-activite` = '$activi' AND `option` LIKE 'Option') ");
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
    }
    ?>    
                                    <!DOCTYPE html>
                                        <html lang="fr">
                                            <head>
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
                                                                                <a href="#" id="css" class="btnnav" onmouseover="afficher('css')">Infos</a>
                                                                                <a href="#" id="js" class="btnnav" onmouseover="afficher('js')">Inscrits</a>
                                                                                <a href="#" id="php" class="btnnav" onmouseover="afficher('php')">Timer</a>
                                                                                <a href="#" id="ks" class="btnnav" onmouseover="afficher('ks')">Tables</a>
                                                                            </div>
                                                                            <div id="bSection">
                                                                                <div id="cssE">
                                                                                    <div class="wrap-content container" id="container">
                                                                                        <div class="container-fluid bg-white">
                                                                                            <div class="col-md-12">
                                                                                                <div class="row margin-top-30">
                                                                                                    <div class="panel-wwhite">
                                                                                                        <div class="panel-body">
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
                                                                                                                                                                        class="btn btn-primary btn-block"
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
                                                                                                                                                                        class="btn btn-primary btn-block"
                                                                                                                                                                        name="submit-ins">Se desinscrire</button>
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
                                                                                                                                                                <th>options</th>
                                                                                                                                                                <td><input
                                                                                                                                                                        class="form-control"
                                                                                                                                                                        id="options"
                                                                                                                                                                        name="options"
                                                                                                                                                                        type="text"
                                                                                                                                                                        value="<?php echo $row['options']; ?>">
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
                                                                                </div>                            
                                                                                <div id="jsE">
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
                                                                                                                                                        <!-- <th>Date
                                                                                                                                                        </th> -->
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
                                                                                                                                                                <!-- <td>
                                                                                                                                                                    <?php echo $row['ds']; ?>
                                                                                                                                                                </td>                         -->
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
                                                                                <div id="phpE">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                       <!-- <h5 class="over-title margin-bottom-15">-> <span class="text-bold">Gestion des Competences</span></h5> -->
                                                                            <div class="container-fluid container-fullw bg-white">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <div class="row margin-top-30">
                                                                                            <div class="col-lg-8 col-md-12">
                                                                                                <div class="panel panel-white">
                                                                                              <!--	<div class="panel-heading">
                                                                                                        <h5 class="panel-title">Ajout Personne</h5>
                                                                                                    </div> -->
                                                                                                    <div class="panel-body">
                                                                                                        <div id="layoutSidenav_content">
                                                                                                            <main>
                                                                                                                <div class="container-fluid px-4">
                                                                                                            <!--    <h1 class="mt-4">Gestion des Competences</h1> -->
                                                                                                                    <ol class="breadcrumb mb-4">
                                                                                                                        <li class="breadcrumb-item">
                                                                                                                            <a href="liste-membres.php">Membres</a>
                                                                                                                        </li>
                                                                                                                        <li class="breadcrumb-item active">
                                                                                                                            Loisirs
                                                                                                                        </li>
                                                                                                                    </ol>
                                                                                                                    <div class="card mb-4">
                                                                                                                 <!--   <div class="card-header">
                                                                                                                            <i class="fas fa-table me-1"></i>
                                                                                                                            Registered User Details
                                                                                                                        </div> -->
                                                                                                                        <div class="card-body">
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </main>
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
                                                                                <div id="ksE">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                       <!-- <h5 class="over-title margin-bottom-15">-> <span class="text-bold">Gestion des Competences</span></h5> -->
                                                                            <div class="container-fluid container-fullw bg-white">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <div class="row margin-top-30">
                                                                                            <div class="col-lg-8 col-md-12">
                                                                                                <div class="panel panel-white">
                                                                                            <!--	<div class="panel-heading">
                                                            <h5 class="panel-title">Ajout Personne</h5>
                                                            </div> -->
                                                                                                    <div class="panel-body">
                                                                                                        <div id="layoutSidenav_content">
                                                                                                            <main>
                                                                                                                <div class="container-fluid px-4">
                                                                                                            <!--    <h1 class="mt-4">Gestion des Competences</h1> -->
                                                                                                                    <ol class="breadcrumb mb-4">
                                                                                                                        <li class="breadcrumb-item">
                                                                                                                            <a href="liste-membres.php">Membres</a>
                                                                                                                        </li>
                                                                                                                        <li class="breadcrumb-item active">
                                                                                                                            Activités
                                                                                                                        </li>
                                                                                                                    </ol>
                                                                                                                    <div class="card mb-4">
                                                                                                                 <!--   <div class="card-header">
                                                                                    <i class="fas fa-table me-1"></i>
                                                                                    Registered User Details
                                                                                </div> -->
                                                                                                                        <div class="card-body">
                                                                                                                       
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </main>
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

                                                        document.getElementById("cssE").className = "rubrique bgImg";
                                                        document.getElementById("jsE").className = "rubrique bgImg";
                                                        document.getElementById("ksE").className = "rubrique bgImg";
                                                        document.getElementById("phpE").className = "rubrique bgImg";

                                                        document.getElementById("css").className = "btnnav";
                                                        document.getElementById("js").className = "btnnav";
                                                        document.getElementById("ks").className = "btnnav";
                                                        document.getElementById("php").className = "btnnav";

                                                        leCalqueE.className += " montrer";
                                                        leCalque.className = "btnnavA";
                                                    }
                                                </script>
                                                <script type="text/javascript" language="javascript">
                                                    afficher('css');
                                                </script>
                                            </body>
                                        </html>
<?php } ?>