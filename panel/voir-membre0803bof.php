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

if (isset($_POST['submitj'])) {
    // Add debug logging
    error_log("Form submitted with submitj");
    
    // Basic sanitization
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $naissance_date = mysqli_real_escape_string($con, $_POST['naissance_date']);
    $ville = mysqli_real_escape_string($con, $_POST['ville']); 
    $rue = mysqli_real_escape_string($con, $_POST['rue']);
    $posting_date = mysqli_real_escape_string($con, $_POST['posting_date']);
    $association_date = mysqli_real_escape_string($con, $_POST['association_date']);
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $telephone = mysqli_real_escape_string($con, $_POST['telephone']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $codev = mysqli_real_escape_string($con, $_POST['CodeV']); // Fixed field name
    $verification = mysqli_real_escape_string($con, $_POST['verification']);
    $pseudo = mysqli_real_escape_string($con, $_POST['pseudo']);

    // Numeric validation
    $longitude = filter_var($_POST['longitude'], FILTER_VALIDATE_FLOAT) ?: 0.0;
    $latitude = filter_var($_POST['latitude'], FILTER_VALIDATE_FLOAT) ?: 0.0;
    
    // Log sanitized values
    error_log("Sanitized values: " . print_r([
        'pseudo' => $pseudo,
        'email' => $email,
        'codev' => $codev
    ], true));

    try {
        $stmt = mysqli_prepare($con, "UPDATE `membres` SET 
            pseudo = ?, email = ?, telephone = ?, fname = ?, 
            lname = ?, posting_date = ?, association_date = ?, 
            rue = ?, password = ?, ville = ?, CodeV = ?,
            verification = ?, naissance_date = ?, longitude = ?,
            latitude = ? WHERE `id-membre` = ?");

        if ($stmt === false) {
            throw new Exception('Erreur de préparation: ' . mysqli_error($con));
        }

        // Fixed parameter binding - all strings except coordinates and id
        mysqli_stmt_bind_param($stmt, 'ssssssssssssddsi',
            $pseudo, $email, $telephone, $fname,
            $lname, $posting_date, $association_date,
            $rue, $password, $ville, $codev,
            $verification, $naissance_date, $longitude,
            $latitude, $id);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Erreur lors de la mise à jour: " . mysqli_stmt_error($stmt));
        }

        $_SESSION['msg'] = "Mise à jour effectuée avec succès";
        error_log("Update successful for member ID: " . $id);
        
    } catch (Exception $e) {
        error_log("Error updating member: " . $e->getMessage());
        $_SESSION['error'] = $e->getMessage();
    } finally {
        if (isset($stmt)) {
            mysqli_stmt_close($stmt);
        }
    }
}
if (isset($_POST['submitdup'])) {
    mysqli_begin_transaction($con);
    try {
        // Utilisation de requête préparée pour la première sélection
        $stmt = mysqli_prepare($con, "SELECT * FROM `activite` WHERE `id-membre` = ?");
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row3 = mysqli_fetch_array($result);
        
        if (!$row3) {
            throw new Exception("Aucune activité trouvée");
        }

        // Préparation de l'insertion avec les champs validés
        $stmt = mysqli_prepare($con, "INSERT INTO `activite` (
            `id-membre`, `id-structure`, `titre-activite`, `heure_depart`, 
            `rue`, `ville`, `lng`, `lat`, `places`, `nb-tables`, 
            `commentaire`, `buyin`, `rake`, `bounty`, `jetons`, 
            `recave`, `addon`, `ante`, `bonus`, `photo`, 
            `lien-id`, `lien`, `lien-texte-fin`, `icon`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        mysqli_stmt_bind_param($stmt, 'iissssddiiidddiiiiddssss',
            $id, $row3['id-structure'], $row3['titre-activite'], $row3['heure_depart'],
            $row3['rue'], $row3['ville'], $row3['lng'], $row3['lat'],
            $row3['places'], $row3['nb-tables'], $row3['commentaire'],
            $row3['buyin'], $row3['rake'], $row3['bounty'], $row3['jetons'],
            $row3['recave'], $row3['addon'], $row3['ante'], $row3['bonus'],
            $row3['photo'], $row3['lien-id'], $row3['lien'],
            $row3['lien-texte-fin'], $row3['icon']
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Erreur lors de la duplication de l'activité");
        }

        $nouvel_id_activite = mysqli_insert_id($con);

        // Insertion participation
        $stmt = mysqli_prepare($con, "INSERT INTO `participation` 
            (`id-membre`, `id-activite`, `id-siege`, `id-table`, `option`, `ordre`, `valide`) 
            VALUES (?, ?, 1, 1, 'Inscrit', 1, 'Actif')");
            
        mysqli_stmt_bind_param($stmt, 'ii', $id, $nouvel_id_activite);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Erreur lors de l'ajout de la participation");
        }

        // Insertion blindes
        $stmt = mysqli_prepare($con, "INSERT INTO `blindes-live` 
            (`id-activite`, `ordre`, `nom`, `duree`, `fin`, `ante`) 
            VALUES (?, 1, 'Pause', 5, '2024-12-31 23:33:00', 0)");
            
        mysqli_stmt_bind_param($stmt, 'i', $nouvel_id_activite);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Erreur lors de l'ajout des blindes");
        }

        mysqli_commit($con);
        $_SESSION['msg'] = "Duplication réussie";

    } catch (Exception $e) {
        mysqli_rollback($con);
        $_SESSION['error'] = "Erreur : " . $e->getMessage();
    }
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
                                                                                    <button type="submit" class="btn btn-primary-green btn-block" name="submitj">OK </button>
                                                                                </td>
                                                                                <td style="text-align:center ;">
                                                                                    <button type="submit" class="btn btn-primary btn-block" name="submitj">Modifier</button>
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
   
                                                                            <!-- <tr>
                                                                                <td style="display:none;" style="text-align:center ;">
                                                                                    <button type="submit" class="btn btn-primary btn-block" name="submit">Mise à jour</button>   <!-- Add a hidden debug field -->
                                                                                </td>                                                                                 <input type="hidden" name="debug" value="1">
                                                                                <td colspan="2">
                                                                                    <a href="liste-membres.php">Quitter </a>
                                                                                </td>  
                                                                            </tr> -->   <div class="alert alert-success">
                                                                            </form>p echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                                                                        </table>
                                                                    <?php } ?>hp endif; ?>
                                                                </div>
                                                            </div> <?php if(isset($_SESSION['error'])): ?>
                                                        </div>        <div class="alert alert-danger">
                                                    </div>              <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                                </div>                  </div>
                                            </div>                  <?php endif; ?>
                                        </div>                    </td>
                                    </div>                      </tr>
                                    <div id="css2E">                                        
                                        <div class="wrap-content container" id="container9">                          
                                            <div class="container-fluid container-fullw bg-white">                                
                                                <div class="col-md-12">                                      
                                                    <div class="row margin-top-30">
                                                        <div class="panel-wwhite">"2">
                                                            <div class="panel-body">-membres.php">Quitter </a>
                                                                <?php echo htmlentities($_SESSION['msg'] = ""); ?>           
                                                                <div class="form-group">
                                                                    <?php>
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
                                                                                    <th style="color:rgb(64, 30, 235) !important;">Libellé par défaut </th>LECT * FROM `membres` WHERE `id-membre` =  '$id'");
                                                                                    <td colspan="3"><input class="form-control" id="def_nomact" name="def_nomact" type="text" style="text-align:center; font-size:22px; bold" value="<?php echo $row['def_nomact']; ?>"></td>tch_array($sql)) {

                                                                            </tr> class="table table-bordered current-user">
                                                                            <tr>
                                                                                <td style="text-align:center ; display:none">
                                                                                    <button type="submit" name="submit" id="submit" class="btn btn-oo btn-primary">                                                                                    <form id="image_upload_form" enctype="multipart/form-data" action="uploadokvide.php?editid=<?php echo $id; ?>" method="post" class="change-pic">
                                                                                        Mise à jour</button>       <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                                                                                </td>        <div>
                                                                                <td style="text-align:center ;">amera" id="file" name="fileToUpload" style="display:none;" /><input type="button" onClick="fileToUpload.click();" value="Modifier" />
                                                                                    <button type="submit" class="btn btn-primary-green btn-block" name="submito">OK </button>
                                                                                </td>
                                                                                <td style="text-align:center ;">   <script type="text/javascript">
                                                                                    <button type="submit" class="btn btn-primary btn-block" name="submito">Modifier</button>yId("file").onchange = function() {
                                                                                </td>
                                                                                <td style="text-align:center ;">       };
                                                                                    <button type="submit" class="btn btn-primary btn-block" name="submitdup">Dupliquer Activité</button>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="4"></td>
                                                                            </tr>td colspan="3"><input class="form-control" id="def_nomact" name="def_nomact" type="text" style="text-align:center; font-size:22px; bold" value="<?php echo $row['def_nomact']; ?>"></td>
                                                                            <tr>
                                                                                <th style="color: #ffffff !important;">Prénom Org</th>>
                                                                                <td><input class="form-control" id="fname" name="fname" type="text" value="<?php echo $row['fname']; ?>">
                                                                                </td>td style="text-align:center ; display:none">
                                                                                <th style="color: #ffffff !important;">Nom</th>    <button type="submit" name="submit" id="submit" class="btn btn-oo btn-primary">
                                                                                <td><input class="form-control" id="lname" name="lname" type="text" value="<?php echo $row['lname']; ?>">
                                                                                </td>
                                                                            </tr>tyle="text-align:center ;">
                                                                            <tr>y-green btn-block" name="submito">OK </button>
                                                                                <th style="color: #ffffff !important;">Téléphone</th>
                                                                                <td><input class="form-control" id="telephone" name="telephone" type="text" value="<?php echo $row['telephone']; ?>"></td>tyle="text-align:center ;">
                                                                                <th style="color: #ffffff !important;">Email</th>   <button type="submit" class="btn btn-primary btn-block" name="submito">Modifier</button>
                                                                                <td><input class="form-control" id="email" name="email" type="text" value="<?php echo $row['email']; ?>"></td></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="color: #ffffff !important;">Adresse</th>
                                                                                <td><input class="form-control" id="rue" name="rue" type="text" value="<?php echo $row['rue']; ?>"></td>
                                                                                <th style="color: #ffffff !important;">Ville</th>
                                                                                <td><input class="form-control" id="ville" name="ville" type="text" value="<?php echo $row['ville']; ?>"></td><td colspan="4"></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="color: #ffffff !important;">Longitude</th></th>
                                                                                <td><input class="form-control" id="longitude" name="longitude" type="float" value="<?php echo $row['longitude']; ?>">
                                                                                </td>/td>
                                                                                <th style="color: #ffffff !important;">Latitude</th><th style="color: #ffffff !important;">Nom</th>
                                                                                <td><input class="form-control" id="latitude" name="latitude" type="float" value="<?php echo $row['latitude']; ?>">e" type="text" value="<?php echo $row['lname']; ?>">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="color: #ffffff !important;">Mot de passe</th>
                                                                                <td><input class="form-control" id="password" name="password" type="text" value="<?php echo $row['password']; ?>">input class="form-control" id="telephone" name="telephone" type="text" value="<?php echo $row['telephone']; ?>"></td>
                                                                                </td>th style="color: #ffffff !important;">Email</th>
                                                                                <th style="color: #ffffff !important;">Date nais</th><td><input class="form-control" id="email" name="email" type="text" value="<?php echo $row['email']; ?>"></td>
                                                                                <td><input class="form-control" id="naissance_date" name="naissance_date" type="date" value="<?php echo $row['naissance_date']; ?>">
                                                                                </td>
                                                                            </tr>tyle="color: #ffffff !important;">Adresse</th>
                                                                            <tr>ype="text" value="<?php echo $row['rue']; ?>"></td>
                                                                                <th style="color: #ffffff !important;">Date inscription</th>
                                                                                <td><input class="form-control" id="posting_date" name="posting_date" type="timestamp" value="<?php echo $row['posting_date']; ?>">input class="form-control" id="ville" name="ville" type="text" value="<?php echo $row['ville']; ?>"></td>
                                                                                </td>
                                                                                <th style="color: #ffffff !important;">Fin Abonnement</th>
                                                                                <td><input class="form-control" id="association_date" name="association_date" type="timestamp" value="<?php echo $row['association_date']; ?>">
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th style="color: #ffffff !important;">CodeV</th>
                                                                                <td><input class="form-control" id="CodeV" name="CodeV" type="text" value="<?php echo $row['CodeV']; ?>">
                                                                                </td>
                                                                                <th style="color: #ffffff !important;">Validé</th>
                                                                                <td><input class="form-control" id="verification" name="verification" type="text" value="<?php echo $row['verification']; ?>">se</th>
                                                                                </td>d']; ?>">
                                                                            </tr>
                                                                            <tr>th>
                                                                                <td colspan="4"></td>; ?>">
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="display:none;" style="text-align:center ;">
                                                                                    <button type="submit" class="btn btn-primary btn-block" name="submit">Mise à jour</button>ffff !important;">Date inscription</th>
                                                                                </td>td><input class="form-control" id="posting_date" name="posting_date" type="timestamp" value="<?php echo $row['posting_date']; ?>">
                                                                                <!--<td colspan="2"></td>
                                                                                    <a href="liste-membres.php">Quitter </a>/th>
                                                                                </td> -->  value="<?php echo $row['association_date']; ?>">
                                                                            </tr>
                                                                            </form>
                                                                        </table>
                                                                    <?php } ?>"color: #ffffff !important;">CodeV</th>
                                                                </div>td><input class="form-control" id="CodeV" name="CodeV" type="text" value="<?php echo $row['CodeV']; ?>">
                                                            </div>d>
                                                        </div><th style="color: #ffffff !important;">Validé</th>
                                                    </div>  <td><input class="form-control" id="verification" name="verification" type="text" value="<?php echo $row['verification']; ?>">
                                                </div>          </td>
                                            </div>          </tr>
                                       </div>              <tr>
                                   </div>                      <td colspan="4">
                                   <div id="jsE">                              <!-- Add a hidden debug field -->
                                        <div class="row">                                  <input type="hidden" name="debug" value="1">
                                            <div class="col-md-12">                                       
                                                <!-- <h5 class="over-title margin-bottom-15">-> <span class="text-bold">Gestion des Competences</span></h5> -->                                           <!-- Add form submission status display -->
                                                <div class="container-fluid container-fullw bg-white">                                   <?php if(isset($_SESSION['msg'])): ?>
                                                    <div class="row">                               <div class="alert alert-success">
                                                        <div class="col-md-12">                         <?php echo $_SESSION['msg']; unset($_SESSION['msg']); ?>
                                                            <div class="row margin-top-30">
                                                                <div class="col-lg-8 col-md-12">
                                                                    <div class="panel panel-wwhite">               
                                                                        <!--	<div class="panel-heading">     <?php if(isset($_SESSION['error'])): ?>
                                                                  <h5 class="panel-title">Ajout Personne</h5>v class="alert alert-danger">
                                                            </div> -->p echo $_SESSION['error']; unset($_SESSION['error']); ?>
                                                                        <div class="panel-body">
                                                                            <div id="layoutSidenav_content">
                                                                                <main>
                                                                                    <div class="container-fluid px-4">      </tr>
                                                                                        <!--    <h1 class="mt-4">Gestion des Competences</h1> -->
                                                                                        <ol class="breadcrumb mb-4">yle="text-align:center ;">
                                                                                            <li class="breadcrumb-item">utton type="submit" class="btn btn-primary btn-block" name="submit">Mise à jour</button>
                                                                                                <a href="liste-membres.php">Membres</a>
                                                                                            </li>
                                                                                            <li class="breadcrumb-item active">ter </a>
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
                                                                                                            </th>"text-bold">Gestion des Competences</span></h5> -->
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
                                                                                                            <?php>
                                                                                                            $id2 = $row['id-comp'];
                                                                                                            $sql2 = mysqli_query($con, "SELECT * FROM `competences` WHERE `id` = '$id2'");x-4">
                                                                                                            while ($row2 = mysqli_fetch_array($sql2)) { ?>
                                                                                                                <tr>4">
                                                                                                                    <td>
                                                                                                                        <?php echo $row2['nom']; ?>
                                                                                                                    </td>
                                                                                                                    <td>em active">
                                                                                                                        <?php echo $row2['commentaire']; ?>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <?php echo $row['date']; ?>
                                                                                                                    </td>
                                                                                                                <?php } ?>
                                                                                                                <td>
                                                                                                                    <!--<a href="edit-competences.php?id=<?php echo $row['id']; ?>" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
                                                                                                                                                    <i class="fas fa-edit"></i></a> -->
                                                                                                                    <a href="ajout-competences.php?id=<?php echo $row['id'] ?>&del=deleteind" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove"><i class="fa fa-times fa fa-white"></i></a>Simple"> -->
                                                                                                                </td>class="display" style="width:100%">
                                                                                                                </tr>
                                                                                                            <?php $cnt = $cnt + 1;
                                                                                                            } ?>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>Commentaire
                                                                                    </div></th>
                                                                                </main>    <th>Supprimer
                                                                            </div>          </th>
                                                                        </div>          </tr>
                                                                        <form role="form" name="adddoc" method="post" onSubmit="return valid();">          </thead>
                                                                            <div class="form-group">             <tbody>
                                                                                <label for="compet">                      <?php $ret = mysqli_query($con, "SELECT * FROM `competences-individu` WHERE `id-indiv` = '$id'");
                                                                                    Ajout                          $cnt = 1;
                                                                                    Competence ?>
                                                                                </label>        <?php
                                                                                <select name="compet" class="form-control" required="true">        $id2 = $row['id-comp'];
                                                                                    <!--		<option value="compet">Select Competence</option> -->                   $sql2 = mysqli_query($con, "SELECT * FROM `competences` WHERE `id` = '$id2'");
                                                                                    <option value="compet">              while ($row2 = mysqli_fetch_array($sql2)) { ?>
                                                                                        Select                        <tr>
                                                                                        Competence
                                                                                    </option>; ?>
                                                                                    <?php $ret2 = mysqli_query($con, "select * from competences");         </td>
                                                                                    while ($row2 = mysqli_fetch_array($ret2)) {                      <td>
                                                                                    ?>                      <?php echo $row2['commentaire']; ?>
                                                                                        <option value="<?php echo htmlentities($row2['id']); ?>">                       </td>
                                                                                            <?php echo htmlentities($row2['nom']); ?>
                                                                                        </option>cho $row['date']; ?>
                                                                                        $indiv=                              </td>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>                   <!--<a href="edit-competences.php?id=<?php echo $row['id']; ?>" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
                                                                            <button type="submit" name="submit2" id="submit2" class="btn btn-o btn-primary">                                                     <i class="fas fa-edit"></i></a> -->
                                                                                Ajout Comp                      <a href="ajout-competences.php?id=<?php echo $row['id'] ?>&del=deleteind" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove"><i class="fa fa-times fa fa-white"></i></a>
                                                                            </button>                       </td>
                                                                        </form>                              </tr>
                                                                    </div>
                                                                </div>                  } ?>
                                                            </div>               </tbody>
                                                        </div>                 </table>
                                                    </div>                  </div>
                                                </div>                  </div>
                                            </div>                  </div>
                                        </div>                  </main>
                                    </div>                  </div>
                                    <div id="phpE">                  </div>
                                        <div class="row">                      <form role="form" name="adddoc" method="post" onSubmit="return valid();">
                                            <div class="col-md-12">                              <div class="form-group">
                                                <!-- <h5 class="over-title margin-bottom-15">-> <span class="text-bold">Gestion des Competences</span></h5> -->                                      <label for="compet">
                                                <div class="container-fluid container-fullw bg-white">                                 Ajout
                                                    <div class="row">                           Competence
                                                        <div class="col-md-12">             </label>
                                                            <div class="row margin-top-30">
                                                                <div class="col-lg-8 col-md-12">e="compet">Select Competence</option> -->
                                                                    <div class="panel panel-white">               <option value="compet">
                                                                        <!--	<div class="panel-heading">         Select
                                                                  <h5 class="panel-title">Ajout Personne</h5>petence
                                                            </div> -->
                                                                        <div class="panel-body">ysqli_query($con, "select * from competences");
                                                                            <div id="layoutSidenav_content">i_fetch_array($ret2)) {
                                                                                <main>
                                                                                    <div class="container-fluid px-4">                  <option value="<?php echo htmlentities($row2['id']); ?>">
                                                                                        <!--    <h1 class="mt-4">Gestion des Competences</h1> -->p echo htmlentities($row2['nom']); ?>
                                                                                        <ol class="breadcrumb mb-4">
                                                                                            <li class="breadcrumb-item">  $indiv=
                                                                                                <a href="liste-membres.php">Membres</a>
                                                                                            </li>
                                                                                            <li class="breadcrumb-item active">
                                                                                                Loisirsmit2" class="btn btn-o btn-primary">
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
                                                                                                            </th>"text-bold">Gestion des Competences</span></h5> -->
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
                                                                                                            <?php>
                                                                                                            $id2 = $row['id-lois'];
                                                                                                            $sql2 = mysqli_query($con, "SELECT * FROM `loisirs` WHERE `id` = '$id2'");x-4">
                                                                                                            while ($row2 = mysqli_fetch_array($sql2)) { ?>
                                                                                                                <tr>4">
                                                                                                                    <td>
                                                                                                                        <?php echo $row2['nom']; ?>
                                                                                                                    </td>
                                                                                                                    <td>em active">
                                                                                                                        <?php echo $row2['commentaire']; ?>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <?php echo $row['date']; ?>
                                                                                                                    </td>
                                                                                                                <?php } ?>
                                                                                                                <td>
                                                                                                                    <!--" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
                                                                                                                                                    <i class="fas fa-edit"></i></a> -->
                                                                                                                    <a href="ajout-loisirs.php?id=<?php echo $row['id'] ?>&del=deleteind" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove"><i class="fa fa-times fa fa-white"></i></a>Simple"> -->
                                                                                                                </td> class="display" style="width:100%">
                                                                                                                </tr>
                                                                                                            <?php $cnt = $cnt + 1;
                                                                                                            } ?>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>Commentaire
                                                                                    </div></th>
                                                                                </main>    <th>Supprimer
                                                                            </div>          </th>
                                                                        </div>          </tr>
                                                                        <form role="form" name="adddoc" method="post" onSubmit="return valid();">          </thead>
                                                                            <div class="form-group">             <tbody>
                                                                                <label for="lois">                      <?php $ret = mysqli_query($con, "SELECT * FROM `loisirs-individu` WHERE `id-indiv` = '$id'");
                                                                                    Ajout                          $cnt = 1;
                                                                                    Loisir ?>
                                                                                </label>        <?php
                                                                                <select name="lois" class="form-control" required="true">          $id2 = $row['id-lois'];
                                                                                    <!--		<option value="compet">Select Competence</option> -->                   $sql2 = mysqli_query($con, "SELECT * FROM `loisirs` WHERE `id` = '$id2'");
                                                                                    <option value="lois">                  while ($row2 = mysqli_fetch_array($sql2)) { ?>
                                                                                        Choix                        <tr>
                                                                                        du Loisir
                                                                                    </option>; ?>
                                                                                    <?php $ret2 = mysqli_query($con, "select * from loisirs");           </td>
                                                                                    while ($row2 = mysqli_fetch_array($ret2)) {                       <td>
                                                                                    ?>                       <?php echo $row2['commentaire']; ?>
                                                                                        <option value="<?php echo htmlentities($row2['id']); ?>">                       </td>
                                                                                            <?php echo htmlentities($row2['nom']); ?>
                                                                                        </option>cho $row['date']; ?>
                                                                                        $indiv=                              </td>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>                   <!--" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
                                                                            <button type="submit" name="submit3" id="submit3" class="btn btn-o btn-primary">                                                     <i class="fas fa-edit"></i></a> -->
                                                                                Ajout Lois                      <a href="ajout-loisirs.php?id=<?php echo $row['id'] ?>&del=deleteind" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove"><i class="fa fa-times fa fa-white"></i></a>
                                                                            </button>                       </td>
                                                                        </form>                              </tr>
                                                                    </div>
                                                                </div>                  } ?>
                                                            </div>               </tbody>
                                                        </div>                 </table>
                                                    </div>                  </div>
                                                </div>                  </div>
                                            </div>                  </div>
                                        </div>                  </main>
                                    </div>                  </div>
                                    <div id="colE">                  </div>
                                        <div class="row">                      <form role="form" name="adddoc" method="post" onSubmit="return valid();">
                                            <div class="col-md-12">                              <div class="form-group">
                                                <!-- <h5 class="over-title margin-bottom-15">-> <span class="text-bold">Gestion des Competences</span></h5> -->                                      <label for="lois">
                                                <div class="container-fluid container-fullw bg-white">                                 Ajout
                                                    <div class="row">                           Loisir
                                                        <div class="col-md-12">             </label>
                                                            <div class="row margin-top-30">
                                                                <div class="col-lg-8 col-md-12">e="compet">Select Competence</option> -->
                                                                    <div class="panel panel-white">               <option value="lois">
                                                                        <!--	<div class="panel-heading">         Choix
                                                                  <h5 class="panel-title">Ajout Personne</h5>Loisir
                                                            </div> -->
                                                                        <div class="panel-body">ysqli_query($con, "select * from loisirs");
                                                                            <div id="layoutSidenav_content">i_fetch_array($ret2)) {
                                                                                <main>
                                                                                    <div class="container-fluid px-4">                  <option value="<?php echo htmlentities($row2['id']); ?>">
                                                                                        <!--    <h1 class="mt-4">Gestion des Competences</h1> -->p echo htmlentities($row2['nom']); ?>
                                                                                        <ol class="breadcrumb mb-4">
                                                                                            <li class="breadcrumb-item">  $indiv=
                                                                                                <a href="liste-membres.php">Membres</a>
                                                                                            </li>
                                                                                            <li class="breadcrumb-item active">
                                                                                                Collectionsmit3" class="btn btn-o btn-primary">
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
                                                                                                            </th>"text-bold">Gestion des Competences</span></h5> -->
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
                                                                                                            <?php>
                                                                                                            $id2 = $row['id_col'];
                                                                                                            $sql2 = mysqli_query($con, "SELECT * FROM `collections` WHERE `id_collection` = '$id2'");x-4">
                                                                                                            while ($row2 = mysqli_fetch_array($sql2)) { ?>
                                                                                                                <tr>4">
                                                                                                                    <td>
                                                                                                                        <?php echo $row2['nom']; ?>
                                                                                                                    </td>
                                                                                                                    <td>em active">
                                                                                                                        <?php echo $row2['commentaire']; ?>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <?php echo $row['date']; ?>
                                                                                                                    </td>
                                                                                                                <?php } ?>
                                                                                                                <td>
                                                                                                                    <!--" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
                                                                                                                                                    <i class="fas fa-edit"></i></a> -->
                                                                                                                    <a href="ajout-collection.php?id=<?php echo $row['id_collection'] ?>&del=deleteind" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove"><i class="fa fa-times fa fa-white"></i></a>Simple"> -->
                                                                                                                </td> class="display" style="width:100%">
                                                                                                                </tr>
                                                                                                            <?php $cnt = $cnt + 1;
                                                                                                            } ?>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>
                                                                                        </div>Commentaire
                                                                                    </div></th>
                                                                                </main>    <th>Supprimer
                                                                            </div>          </th>
                                                                        </div>          </tr>
                                                                        <form role="form" name="adddoc" method="post" onSubmit="return valid();">          </thead>
                                                                            <div class="form-group">             <tbody>
                                                                                <label for="col">                      <?php $ret = mysqli_query($con, "SELECT * FROM `collections-individu` WHERE `id-indiv` = '$id'");
                                                                                    Ajout                          $cnt = 1;
                                                                                    Collection ?>
                                                                                </label>        <?php
                                                                                <select name="col" class="form-control" required="true">           $id2 = $row['id_col'];
                                                                                    <!--		<option value="compet">Select Competence</option> -->                   $sql2 = mysqli_query($con, "SELECT * FROM `collections` WHERE `id_collection` = '$id2'");
                                                                                    <option value="col">              while ($row2 = mysqli_fetch_array($sql2)) { ?>
                                                                                        Choix                        <tr>
                                                                                        de la Collection
                                                                                    </option>; ?>
                                                                                    <?php $ret2 = mysqli_query($con, "select * from collections");            </td>
                                                                                    while ($row2 = mysqli_fetch_array($ret2)) {                       <td>
                                                                                    ?>                <?php echo $row2['commentaire']; ?>
                                                                                        <option value="<?php echo htmlentities($row2['id_collection']); ?>">                       </td>
                                                                                            <?php echo htmlentities($row2['nom']); ?>
                                                                                        </option>cho $row['date']; ?>
                                                                                        $indiv=                              </td>
                                                                                    <?php } ?>
                                                                                </select>
                                                                            </div>                   <!--" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
                                                                            <button type="submit" name="submit4" id="submit4" class="btn btn-o btn-primary">                                                     <i class="fas fa-edit"></i></a> -->
                                                                                Ajout Coll.                      <a href="ajout-collection.php?id=<?php echo $row['id_collection'] ?>&del=deleteind" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove"><i class="fa fa-times fa fa-white"></i></a>
                                                                            </button>                       </td>
                                                                        </form>                              </tr>
                                                                    </div>
                                                                </div>                 } ?>
                                                            </div>               </tbody>
                                                        </div>                 </table>
                                                    </div>                  </div>
                                                </div>                  </div>
                                            </div>                  </div>
                                        </div>                  </main>
                                    </div>                  </div>
                                    <div id="ksE">                  </div>
                                        <div class="row">                      <form role="form" name="adddoc" method="post" onSubmit="return valid();">
                                            <div class="col-md-12">                              <div class="form-group">
                                                <!-- <h5 class="over-title margin-bottom-15">-> <span class="text-bold">Gestion des Competences</span></h5> -->                                      <label for="col">
                                                <div class="container-fluid container-fullw bg-white">                                  Ajout
                                                    <div class="row">                           Collection
                                                        <div class="col-md-12">             </label>
                                                            <div class="row margin-top-30">
                                                                <div class="col-lg-8 col-md-12">e="compet">Select Competence</option> -->
                                                                    <div class="panel panel-white">               <option value="col">
                                                                        <div class="panel-heading">         Choix
                                                                            <h5 class="panel-title">Ajout Personne</h5>la Collection
                                                                        </div>
                                                                        <div class="panel-body">ysqli_query($con, "select * from collections");
                                                                            <div id="layoutSidenav_content">mysqli_fetch_array($ret2)) {
                                                                                <main>
                                                                                    <div class="container-fluid px-4">          <option value="<?php echo htmlentities($row2['id_collection']); ?>">
                                                                                        <!--    <h1 class="mt-4">Gestion des Competences</h1> -->p echo htmlentities($row2['nom']); ?>
                                                                                        <ol class="breadcrumb mb-4">
                                                                                            <li class="breadcrumb-item">  $indiv=
                                                                                                <a href="liste-membres.php">Membres</a>
                                                                                            </li>
                                                                                            <li class="breadcrumb-item active">
                                                                                                Activitésmit4" class="btn btn-o btn-primary">
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
                                                                                                            </th>"text-bold">Gestion des Competences</span></h5> -->
                                                                                                            <th>Lieu
                                                                                                            </th>
                                                                                                            <th>Editer
                                                                                                            </th>
                                                                                                            <th>Cloner
                                                                                                            </th>
                                                                                                        </tr>
                                                                                                    </thead>e</h5>
                                                                                                    <tbody>
                                                                                                        <?php $ret = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-membre` = '$id'");
                                                                                                        $cnt = 1;
                                                                                                        while ($row = mysqli_fetch_array($ret)) { ?>
                                                                                                            <?phpluid px-4">
                                                                                                            $id2 = $row['id-activite'];
                                                                                                            $sql2 = mysqli_query($con, "SELECT * FROM `activite` WHERE `id-activite` = '$id2'");4">
                                                                                                            while ($row2 = mysqli_fetch_array($sql2)) { ?>
                                                                                                                <tr>mbres.php">Membres</a>
                                                                                                                    <td>
                                                                                                                        <?php echo $row2['date_depart']; ?>
                                                                                                                    </td>
                                                                                                                    <td>
                                                                                                                        <a href="voir-activite.php?uid=<?php echo $row['id-activite']; ?>"><?php echo $row2['titre-activite']; ?></a>
                                                                                                                    </td>
                                                                                                                    <td>r">
                                                                                                                        <?php echo $row2['ville']; ?>
                                                                                                                    </td>
                                                                                                                <?php } ?>
                                                                                                                <td>
                                                                                                                    <a href="voir-activite.php?uid=<?php echo $row['id-activite']; ?>" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
                                                                                                                    <!-- <i class="fas fa-edit"></i></a>  -->s="display" style="width:100%">
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
                                                                                                                </tr>r
                                                                                                            <?php $cnt = $cnt + 1;
                                                                                                            } ?>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </div>$con, "SELECT * FROM `participation` WHERE `id-membre` = '$id'");
                                                                                        </div>;
                                                                                    </div>e ($row = mysqli_fetch_array($ret)) { ?>
                                                                                </main>    <?php
                                                                            </div>          $id2 = $row['id-activite'];
                                                                        </div>              $sql2 = mysqli_query($con, "SELECT * FROM `activite` WHERE `id-activite` = '$id2'");
                                                                    </div>                  while ($row2 = mysqli_fetch_array($sql2)) { ?>
                                                                </div>                         <tr>
                                                            </div>                                  <td>
                                                        </div>                                          <?php echo $row2['date_depart']; ?>
                                                    </div>                                          </td>
                                                </div>                                              <td>
                                            </div>                                                      <a href="voir-activite.php?uid=<?php echo $row['id-activite']; ?>"><?php echo $row2['titre-activite']; ?></a>
                                        </div>                                                      </td>
                                    </div>                                                          <td>
                                </div>                                                                  <?php echo $row2['ville']; ?>
                            </div>                                                                  </td>
                        </div>                                                                  <?php } ?>
                        <!-- end: BASIC EXAMPLE -->                                                                      <td>
                        <!-- end: SELECT BOXES -->                                                                              <a href="voir-activite.php?uid=<?php echo $row['id-activite']; ?>" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
                    </div>                                                                                  <!-- <i class="fas fa-edit"></i></a>  -->
                </div>                                                                                      <!-- <a href="ajout-competences.php?id=<?php echo $row['id'] ?>&del=deleteind"
            </div>                                                         onClick="return confirm('Are you sure you want to delete?')"
        </div>                                                          class="btn btn-transparent btn-xs tooltips"
        <!-- start: FOOTER -->                                                                                  tooltip-placement="top"
        <?php include('include/footer.php'); ?>                                                                                      tooltip="Remove"><i
        <!-- end: FOOTER -->                                                                                          class="fa fa-times fa fa-white"></i></a> -->
        <!-- start: SETTINGS -->                                                                                                  </td>
        <?php include('include/setting.php'); ?>                                                                                  <td>
        <!-- end: SETTINGS -->                                                                     <a href="voir-activite.php?uid=<?php echo $row['id-activite']; ?>" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
    </div>                                                                                    </td>
    <!-- start: MAIN JAVASCRIPTS -->                                                                                </tr>
    <script src="vendor/jquery/jquery.min.js"></script>                                                            <?php $cnt = $cnt + 1;
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>                                                                              } ?>
    <script src="vendor/modernizr/modernizr.js"></script>                                                                                          </tbody>
    <script src="vendor/jquery-cookie/jquery.cookie.js"></script>                                                            </table>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>                                     </div>
    <script src="vendor/switchery/switchery.min.js"></script>                        </div>
    <!-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script> -->                           </div>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>               </main>
    <!-- end: MAIN JAVASCRIPTS -->/div>
    <!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->           </div>
    <script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
    <script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="vendor/autosize/autosize.min.js"></script>                          </div>
    <script src="vendor/selectFx/classie.js"></script>iv>
    <script src="vendor/selectFx/selectFx.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
    <!-- start: CLIP-TWO JAVASCRIPTS -->
    <script src="assets/js/main.js"></script>
    <!-- start: JavaScript Event Handlers for this page -->
    <script src="assets/js/form-elements.js"></script>
    <script> BOXES -->
        jQuery(document).ready(function() {
            Main.init();
            FormElements.init();
        });v>
    </script>
    <!-- end: JavaScript Event Handlers for this page -->nclude/footer.php'); ?>
    <!-- end: CLIP-TWO JAVASCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">- start: SETTINGS -->
    </script> include('include/setting.php'); ?>
    <script src="../js/scripts.js"></script>

    <script type="text/javascript" language="javascript">
        function afficher(id) {rc="vendor/jquery/jquery.min.js"></script>
            var leCalque = document.getElementById(id);ap.min.js"></script>
            var leCalqueE = document.getElementById(id + "E");    <script src="vendor/modernizr/modernizr.js"></script>
/script>
            // Reset all sections-scrollbar/perfect-scrollbar.min.js"></script>
            document.getElementById("cssE").className = "rubrique bgImg";cript>
            document.getElementById("css2E").className = "rubrique bgImg";></script> -->
            document.getElementById("jsE").className = "rubrique bgImg";    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
            document.getElementById("ksE").className = "rubrique bgImg";>
            document.getElementById("phpE").className = "rubrique bgImg";
            document.getElementById("colE").className = "rubrique bgImg";
in.js"></script>
            // Reset all nav buttons
            document.getElementById("css").className = "btnnav";
            document.getElementById("css2").className = "btnnav";
            document.getElementById("js").className = "btnnav";    <script src="vendor/select2/select2.min.js"></script>
            document.getElementById("ks").className = "btnnav";tepicker/bootstrap-datepicker.min.js"></script>
            document.getElementById("php").className = "btnnav";r.min.js"></script>
            document.getElementById("col").className = "btnnav";

            // Show selected section
            leCalqueE.className = "rubrique bgImg montrer";
            leCalque.className = "btnnavA";
        }    <script>
    </script>ion() {
    <script type="text/javascript" language="javascript">
        afficher('css');
    </script>);

</body>
AVASCRIPTS -->
</html>rc="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
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
