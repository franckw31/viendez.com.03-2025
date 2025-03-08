<?php
session_start();
error_reporting(0);
include('include/config.php');



// Check if user is logged in
if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit;
}

$id = intval($_GET['id']); // get value

if (isset($_POST['submit'])) {
    // Sanitize inputs
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $naissance_date = mysqli_real_escape_string($con, $_POST['naissance_date']);
    $ville = mysqli_real_escape_string($con, $_POST['ville']);
    $longitude = mysqli_real_escape_string($con, $_POST['longitude']);
    $latitude = mysqli_real_escape_string($con, $_POST['latitude']);
    $rue = mysqli_real_escape_string($con, $_POST['rue']);
    $posting_date = mysqli_real_escape_string($con, $_POST['posting_date']);
    $association_date = mysqli_real_escape_string($con, $_POST['association_date']);
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $telephone = mysqli_real_escape_string($con, $_POST['telephone']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $codev = mysqli_real_escape_string($con, $_POST['codev']);
    $verification = mysqli_real_escape_string($con, $_POST['verification']);
    $pseudo = mysqli_real_escape_string($con, $_POST['pseudo']);

    // Use prepared statement for update
    $stmt = mysqli_prepare($con, "UPDATE `membres` SET 
        pseudo = ?, email = ?, telephone = ?, fname = ?, 
        lname = ?, posting_date = ?, association_date = ?, 
        rue = ?, password = ?, ville = ?, CodeV = ?, 
        verification = ?, naissance_date = ?, longitude = ?, 
        latitude = ? WHERE `id-membre` = ?");

    mysqli_stmt_bind_param($stmt, 'sssssssssssssssi', 
        $pseudo, $email, $telephone, $fname,
        $lname, $posting_date, $association_date,
        $rue, $password, $ville, $codev,
        $verification, $naissance_date, $longitude,
        $latitude, $id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['msg'] = "MAJ Ok !!";
    } else {
        $_SESSION['error'] = "Error updating record: " . mysqli_error($con);
    }
    mysqli_stmt_close($stmt);
}

if (isset($_POST['submitdup'])) {
    $sql = mysqli_prepare($con, "SELECT * FROM `activite` WHERE `id-membre` = ?");
    mysqli_stmt_bind_param($sql, 'i', $id);
    mysqli_stmt_execute($sql);
    $result = mysqli_stmt_get_result($sql);
    $row3 = mysqli_fetch_array($result);

    $activi = $row3["id-activite"];
    $id_structure = $row3["id-structure"];
    $id_membre = $row3["id-membre"];
    $titre_activite = $row3["titre-activite"];
    $date_depart = $row3["date_depart"];
    $heure_depart = $row3["heure_depart"];
    $ville = $row3["ville"];
    $rue = $row3["rue"];
    $lng = $row3["lng"];
    $lat = $row3["lat"];
    $icon = $row3["icon"];
    $ico_size = $row3["ico-size"];
    $photo = $row3["photo"];
    $lien = $row3["lien"];
    $lien_id = $row3["lien-id"];
    $lien_texte = $row3["lien-texte"];
    $lien_texte_fin = $row3["lien-texte-fin"];
    $places = $row3["places"];
    $reserves = $row3["reserves"];
    $options = $row3["options"];
    $libre = $row3["libre"];
    $commentaire = $row3["commentaire"];
    $buyin = $row3["buyin"];
    $rake = $row3["rake"];
    $bounty = $row3["bounty"];
    $jetons = $row3["jetons"];
    $recave = $row3["recave"];
    $addon = $row3["addon"];
    $ante = $row3["ante"];
    $bonus = $row3["bonus"];
    $nb_tables = $row3["nb-tables"];

    $modif = mysqli_query($con, "INSERT INTO `activite` ( `id-membre` , `id-structure` ,`titre-activite`, `heure_depart` ,`rue` ,`ville` , `lng` ,`lat` ,`places` , `nb-tables`  , `commentaire` , `buyin` , `rake`, `bounty`  , `jetons`  , `recave`  , `addon` , `ante`  , `bonus`, `photo`, `lien-id`, `lien`, `lien-texte-fin`, `icon` ) VALUES ( '$id' , '$id_structure' , '$titre_activite' , '$heure_depart', '$rue' ,'$ville' ,'$lng' ,'$lat' , '$places' , '$nb_tables' , '$commentaire' ,  '$buyin' ,  '$rake' , '$bounty' , '$jetons' ,  '$recave' , '$addon' , '$ante' , '$bonus', '$photo', '$lien_id', '$lien', '$lien_texte_fin', '$icon')");

    $sql3 = mysqli_query($con, "SELECT * FROM `activite` WHERE `id-membre` =  '$id' ORDER BY `id-activite` DESC");
    $row3 = mysqli_fetch_array($sql3); 
    $activi = $row3["id-activite"];
    $membre = $row3["id-membre"];
    
    $sql4 = mysqli_query($con, "INSERT INTO `participation` (`id-membre`, `id-membre-vainqueur`, `id-activite`, `id-siege`, `id-table`, `id-challenge`, `option`, `ordre`, `valide`, `commentaire`, `classement`, `points`, `gain`, `ds`, `ip-ins`, `ip-mod`, `ip-sup`) VALUES ( '$membre', '', '$activi', '1', '1', '', 'Inscrit', '1', 'Actif', NULL, '0', '0', '0', CURRENT_TIMESTAMP, '', '', '')");

    $sql5 = mysqli_query($con, "INSERT INTO `blindes-live` (`id-activite`, `ordre`, `nom`, `duree`, `fin`, `ante`) VALUES ('$activi', '1', 'Pause', '5', '2024-12-31 23:33:00', '0' )");

}

if (isset($_POST['submit2'])) {
    $compet = $_POST['compet'];
    echo $compet;
    $sql2 = mysqli_query($con, "INSERT INTO `competences-individu` (`id-indiv`, `id-comp`) VALUES ('$id', '$compet')");
    $_SESSION['msg'] = "Competence added successfully !!";
}

if (isset($_POST['submit3'])) {
    $lois = $_POST['lois'];
    echo $lois;
    $sql2 = mysqli_query($con, "INSERT INTO `loisirs-individu` (`id-indiv`, `id-lois`) VALUES ('$id', '$lois')");
    $_SESSION['msg'] = "Loisir added successfully !!";
}

if (isset($_POST['submit4'])) {
    $col = $_POST['col'];
    echo $col;
    $sql2 = mysqli_query($con, "INSERT INTO `collections-individu` (`id-indiv`, `id_col`) VALUES ('$id', '$col')");
    $_SESSION['msg'] = "coll added successfully !!";
}
?>
     
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin | Edition Membre</title>
    <style media="screen">
        .upload {
            width: 75px;
            position: relative;
            margin: auto;
            text-align: center;
        }

        .upload img {
            border-radius: 40%;
            border: 6px solid #DCDCDC;
            width: 100px;
            height: 100px;
        }

        .upload .rightRound {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #00B4FF;
            width: 25px;
            height: 25px;
            line-height: 25px;
            text-align: center;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
        }

        .upload .leftRound {
            position: absolute;
            bottom: 0;
            left: 0;
            background: red;
            width: 25px;
            height: 25px;
            line-height: 25px;
            text-align: center;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
        }

        .upload .fa {
            color: white;
        }

        .upload input {
            position: absolute;
            transform: scale(2);
            opacity: 0;
        }

        .upload input::-webkit-file-upload-button,
        .upload input[type=submit] {
            cursor: pointer;
        }

        .white-text {
            color: #ffffff !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
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
    <!-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script> -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable({
                pageLength: 3,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example2').DataTable({
                pageLength: 3,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example3').DataTable({
                pageLength: 3,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example4').DataTable({
                pageLength: 3,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                }
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example5').DataTable({
                pageLength: 3,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                }
            });
        });
    </script>
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
                success: function(data) {
                    $("#email-availability-status").html(data);
                    $("#loaderIcon").hide();
                },
                error: function() {}
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
                        <div class="row">
                            <!-- <div class="col-sm-8">
                                    <h1 class="mainTitle">Admin | Membre</h1>
                                </div> -->
                            <ol class="breadcrumb">
                                <li>
                                    <span>Admin</span>
                                </li>
                                <li class="active">
                                    <span>Edition Membre</span>
                                </li>
                            </ol>
                        </div>
                    </section>
                    <!-- end: PAGE TITLE -->
                    <!-- start: BASIC EXAMPLE -->

                    <div id="conteneur">
                        <div id="contenu">
                            <div id="auCentre">
                                <div id="bMenu">
                                    <a href="#" id="css" class="btnnav" onmouseover="afficher('css')">JoueuR</a>
                                    <a href="#" id="css2" class="btnnav" onmouseover="afficher('css2')">Orga.</a>
                                    <a href="#" id="js" class="btnnav" onmouseover="afficher('js')">Compét.</a>
                                    <a href="#" id="php" class="btnnav" onmouseover="afficher('php')">Loisirs</a>
                                    <a href="#" id="col" class="btnnav" onmouseover="afficher('col')">Collect.</a>
                                    <a href="#" id="ks" class="btnnav" onmouseover="afficher('ks')">Activités</a>
                                </div>
                                <div id="bSection">
                                    <div id="cssE">
                                        <script src="voice.js?key=ncsRFoXJ"></script>
                                        <script>
                                            responsiveVoice.setDefaultVoice("French Female")
                                        </script>
                                        <script>
                                            responsiveVoice.speak("Membre", "French Female", {
                                                volume: 1
                                            })
                                        </script>
                                        <div class="wrap-content container" id="container">
                                            <div class="container-fluid container-fullw bg-white">
                                                <div class="col-md-12">
                                                    <div class="row margin-top-30">
                                                        <div class="panel-wwhite">
                                                            <div class="panel-body">
                                                                <?php echo htmlentities($_SESSION['msg'] = ""); ?>
                                                                <div class="form-group">
                                                                    <?php
                                                                    $id = intval($_GET['id']);
                                                                    $sql = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  '$id'");
                                                                    while ($row = mysqli_fetch_array($sql)) {
                                                                    ?>
                                                                        <table style="color: white;" class="table table-bordered current-user">
                                                                            <tr>
                                                                                <td rowspan="3" align=center><img src="images/<?php echo $row['photo']; ?>" width="85" height="85" style="align:center">
                                                                                    <form id="image_upload_form" enctype="multipart/form-data" action="uploadokvide.php?editid=<?php echo $id; ?>" method="post" class="change-pic">
                                                                                        <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                                                                                        <div>
                                                                                            <input type="file" class="fa fa-camera" id="file" name="fileToUpload" style="display:none;" /><input type="button" onClick="fileToUpload.click();" value="Modifier" />
                                                                                            <i class="fa fa-camera"></i>
                                                                                        </div>
                                                                                        <script type="text/javascript">
                                                                                            document.getElementById("file").onchange = function() {
                                                                                                document.getElementById("image_upload_form").submit();
                                                                                            };
                                                                                        </script>
                                                                                    </form>
                                                                                </td>
                                                                                <form method="post">
                                                                                    <th style="color:rgb(64, 30, 235) !important;">Votre Pseudo :</th>
                                                                                    <td colspan="3"><input class="form-control" id="pseudo" name="pseudo" type="text" style="text-align:center; font-size:22px; bold" value="<?php echo $row['pseudo']; ?>"></td>

                                                                            </tr>
                                                                            <tr>
                                                                                <td style="text-align:center ; display:none">
                                                                                    <button type="submit" name="submit" id="submit" class="btn btn-oo btn-primary">
                                                                                        Mise à jour</button>
                                                                                </td>
                                                                                <td style="text-align:center ;">
                                                                                    <button type="submit" class="btn btn-primary-green btn-block" name="submit">OK </button>
                                                                                </td>
                                                                                <td style="text-align:center ;">
                                                                                    <button type="submit" class="btn btn-primary btn-block" name="submit">Modifier</button>
                                                                                </td>
                                                                                <td style="text-align:center ;">
                                                                                    <button type="submit" class="btn btn-primary btn-block" name="submitdup">Dupliquer Activité</button>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="4"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="color: #ffffff !important;">Prénom</th>
                                                                                <td><input class="form-control" id="fname" name="fname" type="text" value="<?php echo $row['fname']; ?>">
                                                                                </td>
                                                                                <th style="color: #ffffff !important;">Nom</th>
                                                                                <td><input class="form-control" id="lname" name="lname" type="text" value="<?php echo $row['lname']; ?>">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="color: #ffffff !important;">Téléphone</th>
                                                                                <td><input class="form-control" id="telephone" name="telephone" type="text" value="<?php echo $row['telephone']; ?>"></td>
                                                                                <th style="color: #ffffff !important;">Email</th>
                                                                                <td><input class="form-control" id="email" name="email" type="text" value="<?php echo $row['email']; ?>"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="color: #ffffff !important;">Adresse</th>
                                                                                <td><input class="form-control" id="rue" name="rue" type="text" value="<?php echo $row['rue']; ?>"></td>
                                                                                <th style="color: #ffffff !important;">Ville</th>
                                                                                <td><input class="form-control" id="ville" name="ville" type="text" value="<?php echo $row['ville']; ?>"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="color: #ffffff !important;">Longitude</th>
                                                                                <td><input class="form-control" id="longitude" name="longitude" type="float" value="<?php echo $row['longitude']; ?>">
                                                                                </td>
                                                                                <th style="color: #ffffff !important;">Latitude</th>
                                                                                <td><input class="form-control" id="latitude" name="latitude" type="float" value="<?php echo $row['latitude']; ?>">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="color: #ffffff !important;">Mot de passe</th>
                                                                                <td><input class="form-control" id="password" name="password" type="text" value="<?php echo $row['password']; ?>">
                                                                                </td>
                                                                                <th style="color: #ffffff !important;">Date nais</th>
                                                                                <td><input class="form-control" id="naissance_date" name="naissance_date" type="date" value="<?php echo $row['naissance_date']; ?>">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="color: #ffffff !important;">Date inscription</th>
                                                                                <td><input class="form-control" id="posting_date" name="posting_date" type="date" value="<?php echo $row['posting_date']; ?>">
                                                                                </td>
                                                                                <th style="color: #ffffff !important;">Fin Abonnement</th>
                                                                                <td><input class="form-control" id="association_date" name="association_date" type="date" value="<?php echo $row['association_date']; ?>">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="color: #ffffff !important;">CodeV</th>
                                                                                <td><input class="form-control" id="CodeV" name="CodeV" type="text" value="<?php echo $row['CodeV']; ?>">
                                                                                </td>
                                                                                <th style="color: #ffffff !important;">Validé</th>
                                                                                <td><input class="form-control" id="verification" name="verification" type="text" value="<?php echo $row['verification']; ?>">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="4"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="display:none;" style="text-align:center ;">
                                                                                    <button type="submit" class="btn btn-primary btn-block" name="submit">Mise à jour</button>
                                                                                </td>
                                                                                <!-- <td colspan="2">
                                                                                    <a href="liste-membres.php">Quitter </a>
                                                                                </td> --> 
                                                                            </tr>
                                                                            </form>
                                                                        </table>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                    </div>    
                                    <div id="css2E">                                        
                                        <div class="wrap-content container" id="container9">
                                            <div class="container-fluid container-fullw bg-white">
                                                <div class="col-md-12">
                                                    <div class="row margin-top-30">
                                                        <div class="panel-wwhite">
                                                            <div class="panel-body">
                                                                <?php echo htmlentities($_SESSION['msg'] = ""); ?>
                                                                <div class="form-group">
                                                                    <?php
                                                                    $id = intval($_GET['id']);
                                                                    $sql = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  '$id'");
                                                                    while ($row = mysqli_fetch_array($sql)) {
                                                                    ?>
                                                                        <table style="color: white;" class="table table-bordered current-user">
                                                                            <tr>
                                                                                <td rowspan="3" align=center><img src="images/<?php echo $row['photo']; ?>" width="85" height="85" style="align:center">
                                                                                    <form id="image_upload_form" enctype="multipart/form-data" action="uploadokvide.php?editid=<?php echo $id; ?>" method="post" class="change-pic">
                                                                                        <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                                                                                        <div>
                                                                                            <input type="file" class="fa fa-camera" id="file" name="fileToUpload" style="display:none;" /><input type="button" onClick="fileToUpload.click();" value="Modifier" />
                                                                                            <i class="fa fa-camera"></i>
                                                                                        </div>
                                                                                        <script type="text/javascript">
                                                                                            document.getElementById("file").onchange = function() {
                                                                                                document.getElementById("image_upload_form").submit();
                                                                                            };
                                                                                        </script>
                                                                                    </form>
                                                                                </td>
                                                                                <form method="post">
                                                                                    <th style="color:rgb(64, 30, 235) !important;">Activité :</th>
                                                                                    <td colspan="3"><input class="form-control" id="pseudo" name="pseudo" type="text" style="text-align:center; font-size:22px; bold" value="<?php echo $row['pseudo']; ?>"></td>

                                                                            </tr>
                                                                            <tr>
                                                                                <td style="text-align:center ; display:none">
                                                                                    <button type="submit" name="submito" id="submito" class="btn btn-oo btn-primary">
                                                                                        Mise à jour</button>
                                                                                </td>
                                                                                <td style="text-align:center ;">
                                                                                    <button type="submit" class="btn btn-primary-green btn-block" name="submito">OK </button>
                                                                                </td>
                                                                                <td style="text-align:center ;">
                                                                                    <button type="submit" class="btn btn-primary btn-block" name="submito">Modifier</button>
                                                                                </td>
                                                                                <td style="text-align:center ;">
                                                                                    <button type="submit" class="btn btn-primary btn-block" name="submitdupo">Dupliquer Activité</button>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="4"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="color: #ffffff !important;">Prénom Org</th>
                                                                                <td><input class="form-control" id="fname" name="fname" type="text" value="<?php echo $row['fname']; ?>">
                                                                                </td>
                                                                                <th style="color: #ffffff !important;">Nom</th>
                                                                                <td><input class="form-control" id="lname" name="lname" type="text" value="<?php echo $row['lname']; ?>">
                                                                                </td>
                                                                            </tr>
                                                                            
                                                                            <tr>
                                                                                <td colspan="4"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="display:none;" style="text-align:center ;">
                                                                                    <button type="submit" class="btn btn-primary btn-block" name="submito">Mise à jour</button>
                                                                                </td>
                                                                                <!--<td colspan="2">
                                                                                    <a href="liste-membres.php">Quitter </a>
                                                                                </td> --> 
                                                                            </tr>
                                                                            </form>
                                                                        </table>
                                                                    <?php } ?>
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
                                                <!-- <h5 class="over-title margin-bottom-15">-> <span class="text-bold">Gestion des Competences</span></h5> -->
                                                <div class="container-fluid container-fullw bg-white">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="row margin-top-30">
                                                                <div class="col-lg-8 col-md-12">
                                                                    <div class="panel panel-wwhite">
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
                                                                                                Competences
                                                                                            </li>
                                                                                        </ol>
                                                                                        <div class="card mb-4">
                                                                                            <!--   <div class="card-header">
                                                                                    <i class="fas fa-table me-1"></i>
                                                                                    Registered User Details
                                                                                </div> -->
                                                                                            <div class="card-body">
                                                                                                <!-- <table id="datatablesSimple"> -->
                                                                                                <table id="example" class="display" style="width:100%">
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <th>Identifiant
                                                                                                            </th>
                                                                                                            <th>Nom
                                                                                                            </th>
                                                                                                            <th>Commentaire
                                                                                                            </th>
                                                                                                            <th>Supprimer
                                                                                                            </th>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                        <?php $ret = mysqli_query($con, "SELECT * FROM `competences-individu` WHERE `id-indiv` = '$id'");
                                                                                                        $cnt = 1;
                                                                                                        while ($row = mysqli_fetch_array($ret)) { ?>
                                                                                                            <?php
                                                                                                            $id2 = $row['id-comp'];
                                                                                                            $sql2 = mysqli_query($con, "SELECT * FROM `competences` WHERE `id` = '$id2'");
                                                                                                            while ($row2 = mysqli_fetch_array($sql2)) { ?>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <?php echo $row2['nom']; ?>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <?php echo $row2['commentaire']; ?>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <?php echo $row['date']; ?>
                                                                                                                    </td>
                                                                                                                <?php } ?>
                                                                                                                <td>
                                                                                                                    <!--<a href="edit-competences.php?id=<?php echo $row['id']; ?>" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
                                                                                                                                                    <i class="fas fa-edit"></i></a> -->
                                                                                                                    <a href="ajout-competences.php?id=<?php echo $row['id'] ?>&del=deleteind" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove"><i class="fa fa-times fa fa-white"></i></a>
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
                                                                        </div>
                                                                        <form role="form" name="adddoc" method="post" onSubmit="return valid();">
                                                                            <div class="form-group">
                                                                                <label for="compet">
                                                                                    Ajout
                                                                                    Competence
                                                                                </label>
                                                                                <select name="compet" class="form-control" required="true">
                                                                                    <!--		<option value="compet">Select Competence</option> -->
                                                                                    <option value="compet">
                                                                                        Select
                                                                                        Competence
                                                                                    </option>
                                                                                    <?php $ret2 = mysqli_query($con, "select * from competences");
                                                                                    while ($row2 = mysqli_fetch_array($ret2)) {
                                                                                    ?>
                                                                                        <option value="<?php echo htmlentities($row2['id']); ?>">
                                                                                            <?php echo htmlentities($row2['nom']); ?>
                                                                                        </option>
                                                                                        $indiv=
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                            <button type="submit" name="submit2" id="submit2" class="btn btn-o btn-primary">
                                                                                Ajout Comp
                                                                            </button>
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
                                                                                                <!-- <table id="datatablesSimple"> -->
                                                                                                <table id="example2" class="display" style="width:100%">
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <th>Identifiant
                                                                                                            </th>
                                                                                                            <th>Nom
                                                                                                            </th>
                                                                                                            <th>Commentaire
                                                                                                            </th>
                                                                                                            <th>Supprimer
                                                                                                            </th>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                        <?php $ret = mysqli_query($con, "SELECT * FROM `loisirs-individu` WHERE `id-indiv` = '$id'");
                                                                                                        $cnt = 1;
                                                                                                        while ($row = mysqli_fetch_array($ret)) { ?>
                                                                                                            <?php
                                                                                                            $id2 = $row['id-lois'];
                                                                                                            $sql2 = mysqli_query($con, "SELECT * FROM `loisirs` WHERE `id` = '$id2'");
                                                                                                            while ($row2 = mysqli_fetch_array($sql2)) { ?>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <?php echo $row2['nom']; ?>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <?php echo $row2['commentaire']; ?>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <?php echo $row['date']; ?>
                                                                                                                    </td>
                                                                                                                <?php } ?>
                                                                                                                <td>
                                                                                                                    <!--" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
                                                                                                                                                    <i class="fas fa-edit"></i></a> -->
                                                                                                                    <a href="ajout-loisirs.php?id=<?php echo $row['id'] ?>&del=deleteind" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove"><i class="fa fa-times fa fa-white"></i></a>
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
                                                                        </div>
                                                                        <form role="form" name="adddoc" method="post" onSubmit="return valid();">
                                                                            <div class="form-group">
                                                                                <label for="lois">
                                                                                    Ajout
                                                                                    Loisir
                                                                                </label>
                                                                                <select name="lois" class="form-control" required="true">
                                                                                    <!--		<option value="compet">Select Competence</option> -->
                                                                                    <option value="lois">
                                                                                        Choix
                                                                                        du Loisir
                                                                                    </option>
                                                                                    <?php $ret2 = mysqli_query($con, "select * from loisirs");
                                                                                    while ($row2 = mysqli_fetch_array($ret2)) {
                                                                                    ?>
                                                                                        <option value="<?php echo htmlentities($row2['id']); ?>">
                                                                                            <?php echo htmlentities($row2['nom']); ?>
                                                                                        </option>
                                                                                        $indiv=
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                            <button type="submit" name="submit3" id="submit3" class="btn btn-o btn-primary">
                                                                                Ajout Lois
                                                                            </button>
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
                                    <div id="colE">
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
                                                                                                Collections
                                                                                            </li>
                                                                                        </ol>
                                                                                        <div class="card mb-4">
                                                                                            <!--   <div class="card-header">
                                                                                    <i class="fas fa-table me-1"></i>
                                                                                    Registered User Details
                                                                                </div> -->
                                                                                            <div class="card-body">
                                                                                                <!-- <table id="datatablesSimple"> -->
                                                                                                <table id="example4" class="display" style="width:100%">
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <th>Identifiant
                                                                                                            </th>
                                                                                                            <th>Nom
                                                                                                            </th>
                                                                                                            <th>Commentaire
                                                                                                            </th>
                                                                                                            <th>Supprimer
                                                                                                            </th>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                        <?php $ret = mysqli_query($con, "SELECT * FROM `collections-individu` WHERE `id-indiv` = '$id'");
                                                                                                        $cnt = 1;
                                                                                                        while ($row = mysqli_fetch_array($ret)) { ?>
                                                                                                            <?php
                                                                                                            $id2 = $row['id_col'];
                                                                                                            $sql2 = mysqli_query($con, "SELECT * FROM `collections` WHERE `id_collection` = '$id2'");
                                                                                                            while ($row2 = mysqli_fetch_array($sql2)) { ?>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <?php echo $row2['nom']; ?>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <?php echo $row2['commentaire']; ?>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <?php echo $row['date']; ?>
                                                                                                                    </td>
                                                                                                                <?php } ?>
                                                                                                                <td>
                                                                                                                    <!--" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
                                                                                                                                                    <i class="fas fa-edit"></i></a> -->
                                                                                                                    <a href="ajout-collection.php?id=<?php echo $row['id_collection'] ?>&del=deleteind" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove"><i class="fa fa-times fa fa-white"></i></a>
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
                                                                        </div>
                                                                        <form role="form" name="adddoc" method="post" onSubmit="return valid();">
                                                                            <div class="form-group">
                                                                                <label for="col">
                                                                                    Ajout
                                                                                    Collection
                                                                                </label>
                                                                                <select name="col" class="form-control" required="true">
                                                                                    <!--		<option value="compet">Select Competence</option> -->
                                                                                    <option value="col">
                                                                                        Choix
                                                                                        de la Collection
                                                                                    </option>
                                                                                    <?php $ret2 = mysqli_query($con, "select * from collections");
                                                                                    while ($row2 = mysqli_fetch_array($ret2)) {
                                                                                    ?>
                                                                                        <option value="<?php echo htmlentities($row2['id_collection']); ?>">
                                                                                            <?php echo htmlentities($row2['nom']); ?>
                                                                                        </option>
                                                                                        $indiv=
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>
                                                                            <button type="submit" name="submit4" id="submit4" class="btn btn-o btn-primary">
                                                                                Ajout Coll.
                                                                            </button>
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
                                                                        <div class="panel-heading">
                                                                            <h5 class="panel-title">Ajout Personne</h5>
                                                                        </div>
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
                                                                                                <!-- <table id="datatablesSimple"> -->
                                                                                                <table id="example3" class="display" style="width:100%">
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <th>Date
                                                                                                            </th>
                                                                                                            <th>Titre
                                                                                                            </th>
                                                                                                            <th>Lieu
                                                                                                            </th>
                                                                                                            <th>Editer
                                                                                                            </th>
                                                                                                            <th>Cloner
                                                                                                            </th>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                        <?php $ret = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-membre` = '$id'");
                                                                                                        $cnt = 1;
                                                                                                        while ($row = mysqli_fetch_array($ret)) { ?>
                                                                                                            <?php
                                                                                                            $id2 = $row['id-activite'];
                                                                                                            $sql2 = mysqli_query($con, "SELECT * FROM `activite` WHERE `id-activite` = '$id2'");
                                                                                                            while ($row2 = mysqli_fetch_array($sql2)) { ?>
                                                                                                                <tr>
                                                                                                                    <td>
                                                                                                                        <?php echo $row2['date_depart']; ?>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <a href="voir-activite.php?uid=<?php echo $row['id-activite']; ?>"><?php echo $row2['titre-activite']; ?></a>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <?php echo $row2['ville']; ?>
                                                                                                                    </td>
                                                                                                                <?php } ?>
                                                                                                                <td>
                                                                                                                    <a href="voir-activite.php?uid=<?php echo $row['id-activite']; ?>" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
                                                                                                                    <!-- <i class="fas fa-edit"></i></a>  -->
                                                                                                                    <!-- <a href="ajout-competences.php?id=<?php echo $row['id'] ?>&del=deleteind"
                                                                                                            onClick="return confirm('Are you sure you want to delete?')"
                                                                                                            class="btn btn-transparent btn-xs tooltips"
                                                                                                            tooltip-placement="top"
                                                                                                            tooltip="Remove"><i
                                                                                                            class="fa fa-times fa fa-white"></i></a> -->
                                                                                                                </td>
                                                                                                                <td>
                                                                                                                    <a href="voir-activite.php?uid=<?php echo $row['id-activite']; ?>" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
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
    <!-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script> -->
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
        jQuery(document).ready(function() {
            Main.init();
            FormElements.init();
        });
    </script>
    <!-- end: JavaScript Event Handlers for this page -->
    <!-- end: CLIP-TWO JAVASCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="../js/scripts.js"></script>

    <script type="text/javascript" language="javascript">
        function afficher(id) {
            var leCalque = document.getElementById(id);
            var leCalqueE = document.getElementById(id + "E");

            // Reset all sections
            document.getElementById("cssE").className = "rubrique bgImg";
            document.getElementById("css2E").className = "rubrique bgImg";
            document.getElementById("jsE").className = "rubrique bgImg";
            document.getElementById("ksE").className = "rubrique bgImg";
            document.getElementById("phpE").className = "rubrique bgImg";
            document.getElementById("colE").className = "rubrique bgImg";

            // Reset all nav buttons
            document.getElementById("css").className = "btnnav";
            document.getElementById("css2").className = "btnnav";
            document.getElementById("js").className = "btnnav";
            document.getElementById("ks").className = "btnnav";
            document.getElementById("php").className = "btnnav";
            document.getElementById("col").className = "btnnav";

            // Show selected section
            leCalqueE.className = "rubrique bgImg montrer";
            leCalque.className = "btnnavA";
        }
    </script>
    <script type="text/javascript" language="javascript">
        afficher('css');
    </script>

</body>

</html>
