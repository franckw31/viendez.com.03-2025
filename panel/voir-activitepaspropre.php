<?php
session_start();
error_reporting(0);
include('include/config.php');
if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
    exit;
} else {
    $id = intval($_GET['id']); // get value
    if (isset($_POST['submit2'])) {
        $compet = $_POST['compet'];
        echo $compet;
        $sql2 = mysqli_query($con, "INSERT INTO `competences-individu` (`id-indiv`, `id-comp`) VALUES ('$id', '$compet')");
        //$sql=mysqli_query($con,"insert into competences(nom) values('$doctorspecilization')");
        $_SESSION['msg'] = "Doctor Specialization added successfully !!";
    }
    if (isset($_POST['submit3'])) {
        $lois = $_POST['lois'];
        echo $lois;
        $sql2 = mysqli_query($con, "INSERT INTO `loisirs-individu` (`id-indiv`, `id-lois`) VALUES ('$id', '$lois')");
        //$sql=mysqli_query($con,"insert into competences(nom) values('$doctorspecilization')");
        $_SESSION['msg'] = "Doctor Specialization added successfully !!";
    }
    if (isset($_POST['submit-ins'])) {
        $lois = $_POST['lois'];
        $activi = $_POST['activi'];
        // $id = 88;
        $sql2 = mysqli_query($con, "INSERT INTO `participation` (`id-membre`, `id-activite`) VALUES ('$lois', '$activi')");
        header('location:/panel/liste-activites.php');
        exit;
        //$sql=mysqli_query($con,"insert into competences(nom) values('$doctorspecilization')");
        $_SESSION['msg'] = "bingo !!";
    }
    ?>
                                            <!DOCTYPE html>
                                            <html lang="en">

                                            <head>
                                                <title>Admin | Edition Activite</title>
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
                                                <script type="text/javascript">$(document).ready(function () {
                                                        $('#example').DataTable({ pageLength: 3, language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' } });
                                                    });</script>
                                                <script type="text/javascript">$(document).ready(function () {
                                                        $('#example2').DataTable({ pageLength: 3, language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' } });
                                                    });</script>
                                                <script type="text/javascript">$(document).ready(function () {
                                                        $('#example3').DataTable({ pageLength: 3, language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' } });
                                                    });</script>
                                                <link rel="stylesheet" href="css/mes-styles.css">
                                                <link rel="stylesheet" href="css/les-styles.css">
                                            </head>

                                            <body>
                                                <div id="app">
                                                    <?php include('include/sidebar.php'); ?>
                                                    <div class="app-content">
                                                        <?php include('include/header.php'); ?>
                                                        <!-- end: TOP NAVBAR -->
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
                                                                                <span>Edition Activité</span>
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
                                                                                <a href="#" id="css" class="btnnav" onmouseover="afficher('css')">Titre</a>
                                                                                <a href="#" id="js" class="btnnav" onmouseover="afficher('js')">Membres</a>
                                                                                <a href="#" id="php" class="btnnav" onmouseover="afficher('php')">Timer</a>
                                                                                <a href="#" id="ks" class="btnnav" onmouseover="afficher('ks')">Activités</a>
                                                                            </div>
                                                                            <div id="bSection">
                                                                                <div id="cssE">
                                                                                    <!-- <div class="row"> -->
                                                                                        <div class="col-md-12">
                                                                                            <div class="container-fluid container-fullw bg-white">
                                                                                                <div class="row">
                                                                                                    <div class="col-md-12">
                                                                                                        <!-- <div class="row margin-top-30"> -->
                                                                                                            <div class="col-lg-6 col-md-12">
                                                                                                                <div class="panel panel-white">
                                                                                                                    <div class="panel-body">
                                                                                                                        <?php echo htmlentities($_SESSION['msg'] = ""); ?>
                                                                                                                        <div class="form-group">
                                                                                                                            <?php
                                                                                                                            $id = intval($_GET['uid']);
                                                                                                                            $sql = mysqli_query($con, "SELECT * FROM `activite` WHERE `id-activite` =  '$id'");
                                                                                                                            while ($result = mysqli_fetch_array($sql)) {
                                                                                                                                ?>
                                                                                                                                                                        <table class="table table-bordered">
                                                                                                                                                                            <tr>
                                                                                                                                                                                <td colspan="4" align="center"
                                                                                                                                                                                    text="bold">
                                                                                                                                                                                    <?php echo $result['titre-activite']; ?>
                                                                                                                                                                                </td>
                                                                                                                                                                            </tr>
                                                                                                                                                                            <tr>
                                                                                                                                                                                <th>Date</th>
                                                                                                                                                                                <td>
                                                                                                                                                                                    <?php echo $result['date_depart']; ?>
                                                                                                                                                                                </td>

                                                                                                                                                                                <th>Heure</th>
                                                                                                                                                                                <td>
                                                                                                                                                                                    <?php echo $result['heure_depart']; ?>
                                                                                                                                                                                </td>
                                                                                                                                                                            </tr>
                                                                                                                                                                            <tr>
                                                                                                                                                                                <th>Organisateur</th>
                                                                                                                                                                                <td>
                                                                                                                                                                                    <?php echo $result['id-membre']; ?>
                                                                                                                                                                                </td>
                                                                                                                                                                                <th>Ville</th>
                                                                                                                                                                                <td>
                                                                                                                                                                                    <?php echo $result['ville']; ?>
                                                                                                                                                                                </td>
                                                                                                                                                                            </tr>
                                                                                                                                                                            <tr>
                                                                                                                                                                                <th>Longitude</th>
                                                                                                                                                                                <td>
                                                                                                                                                                                    <?php echo $result['lng']; ?>
                                                                                                                                                                                </td>
                                                                                                                                                                                <th>Latitude</th>
                                                                                                                                                                                <td>
                                                                                                                                                                                    <?php echo $result['lat']; ?>
                                                                                                                                                                                </td>
                                                                                                                                                                            </tr>
                                                                                                                                                                            <tr>
                                                                                                                                                                                <th>Nb Joueurs Max</th>
                                                                                                                                                                                <td>
                                                                                                                                                                                    <?php echo $result['places']; ?>
                                                                                                                                                                                </td>
                                                                                                                                                                                <th>Places Dispos</th>
                                                                                                                                                                                <td>
                                                                                                                                                                                    <?php echo $result['places']; ?>
                                                                                                                                                                                </td>
                                                                                                                                                                            </tr>
                                                                                                                                                                            <tr>
                                                                                                                                                                                <th>Buyin</th>
                                                                                                                                                                                <td>
                                                                                                                                                                                    <?php echo $result['buyin']; ?>
                                                                                                                                                                                </td>
                                                                                                                                                                                <th>Rake</th>
                                                                                                                                                                                <td>
                                                                                                                                                                                    <?php echo $result['rake']; ?>
                                                                                                                                                                                </td>

                                                                                                                                                                            </tr>
                                                                                                                                                                            <tr>
                                                                                                                                                                                <th>Nb Recave</th>
                                                                                                                                                                                <td>
                                                                                                                                                                                    <?php echo $result['recave']; ?>
                                                                                                                                                                                </td>
                                                                                                                                                                                <th>Addon</th>
                                                                                                                                                                                <td>
                                                                                                                                                                                    <?php echo $result['addon']; ?>
                                                                                                                                                                                </td>
                                                                                                                                                                            </tr>

                                                                                                                                                                            <tr>
                                                                                                                                                                                <th>Bounty</th>
                                                                                                                                                                                <td>
                                                                                                                                                                                    <?php echo $result['bounty']; ?>
                                                                                                                                                                                </td>
                                                                                                                                                                                <th>Ante</th>
                                                                                                                                                                                <td>
                                                                                                                                                                                    <?php echo $result['ante']; ?>
                                                                                                                                                                                </td>
                                                                                                                                                                            </tr>
                                                                                                                                                                            <tr>
                                                                                                                                                                                <th>Structure</th>
                                                                                                                                                                                <td colspan="4">
                                                                                                                                                                                    <?php echo $result['structure']; ?>
                                                                                                                                                                                </td>
                                                                                                                                                                            </tr>
                                                                                                                                                                            <tr>
                                                                                                                                                                                <th>Stack</th>
                                                                                                                                                                                <td>
                                                                                                                                                                                    <?php echo $result['jetons']; ?>
                                                                                                                                                                                </td>
                                                                                                                                                                                <th>Bonus Heure</th>
                                                                                                                                                                                <td><input class="form-control"
                                                                                                                                                                                        id="jetons"
                                                                                                                                                                                        name="jetons"
                                                                                                                                                                                        type="text"
                                                                                                                                                                                        value="<?php echo $result['bonus']; ?>">
                                                                                                                                                                                </td>
                                                                                                                                                                            </tr>
                                                                                                                                                                            <tr>
                                                                                                                                                                                <td colspan="4"></td>
                                                                                                                                                                            </tr>
                                                                                                                                                                            <tr>
                                                                                                                                                                                <td colspan="3" align="center">
                                                                                                                                                                                    <a
                                                                                                                                                                                        href="edit-individu.php?id=<?php echo $row['id']; ?>">Modifier
                                                                                                                                                                                        les Infos</a>
                                                                                                                                                                                </td>
                                                                                                                                                                                <td colspan="3" align="center">
                                                                                                                                                                                    <a href="liste-activites.php"">Quitter </a>
                                                                                                                </td>
                                                                                                                <tr>
                                                                                                                <td colspan="4" align="center">
                                                                                                                                                                        <form role="form" name="adddoc" method="post" onSubmit="return valid();">
                                                                                                                                                                            <tr>
                                                                                                                                                                                <td colspan="2">
                                                                                                                                                                                <select name="lois" value = "lois" class="form-control" required="false">
                                                                                                                                                                                    <option
                                                                                                                                                                                        value="<?php echo htmlentities($_SESSION['id']); ?>"> <?php echo htmlentities($_SESSION['id']); ?>
                                                                                                                                                                                    </option>
                                                                                                                                                                                </select>
                                                                                                                                            </td>
                                                                                                                                            <td>
                                                                                                                                                                                <select name="activi" value = "activi" class="form-control" required="true">
                                                                                                                                                                                    <option
                                                                                                                                                                                        value="<?php echo htmlentities($result['id-activite']); ?>"> <?php echo htmlentities($result['id-activite']); ?>
                                                                                                                                                                                    </option>
                                                                                                                                                                                </select>
                                                                                                                                            </td>
                                                                                                                                            <td align="center">
                                                                                                                                                                                <button
                                                                                                                                                                                    type="submit"
                                                                                                                                                                                    name="submit-ins"
                                                                                                                                                                                    id="submit-ins"
                                                                                                                                                                                    class="btn btn-o btn-primary">
                                                                                                                                                                                    Valider Inscription IcI
                                                                                                                                                                                </button>
                                                                                                                                            </td>
                                                                                                                                            </tr>                                
                                                                                                                                                                        </form>
                                                                                                                                            
                                                                                                                                                                        </td>
                                                                                                                                                        </tr>
                                                                                                                                            </tr>
                                                                                                                                        </table>
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
                                                                                                <div class=" col-lg-12 col-md-12">
                                                                                                    <div class="panel panel-white">
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
                                                                                                                                            Competences
                                                                                                                                        </li>
                                                                                                                                    </ol>
                                                                                                                                    <div class="card mb-4">
                                                                                                                                    <!--   <div class="card-header">
                                                                                                                                <i class="fas fa-table me-1"></i>
                                                                                                                                Registered User Details
                                                                                                                                </div> -->
                                                                                                                                                                    <div
                                                                                                                                                                        class="card-body">
                                                                                                                                                                        <!-- <table id="datatablesSimple"> -->
                                                                                                                                                                        <table
                                                                                                                                                                            id="example"
                                                                                                                                                                            class="display"
                                                                                                                                                                            style="width:100%">
                                                                                                                                                                            <thead>
                                                                                                                                                                                <tr>
                                                                                                                                                                                    <th>Ordre
                                                                                                                                                                                    </th>
                                                                                                                                                                                    <th>Pseudo
                                                                                                                                                                                    </th>
                                                                                                                                                                                    <th>Statut
                                                                                                                                                                                    </th>
                                                                                                                                                                                    <th>Action
                                                                                                                                                                                    </th>
                                                                                                                                                                                </tr>
                                                                                                                                                                            </thead>
                                                                                                                                                                            <tfoot>
                                                                                                                                                                                <tr>
                                                                                                                                                                                    <th>Ordre
                                                                                                                                                                                    </th>
                                                                                                                                                                                    <th>Pseudo
                                                                                                                                                                                    </th>
                                                                                                                                                                                    <th>Statut
                                                                                                                                                                                    </th>
                                                                                                                                                                                    <th>Action
                                                                                                                                                                                    </th>
                                                                                                                                                                                </tr>
                                                                                                                                                                            </tfoot>
                                                                                                                                                                            <tbody>
                                                                                                                                                                                <?php $ret = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-activite` = '$id'");
                                                                                                                                                                                $cnt = 1;
                                                                                                                                                                                while ($row = mysqli_fetch_array($ret)) { ?>
                                                                                                                                                                                                                            <?php
                                                                                                                                                                                                                            $id2 = $row['id-membre'];
                                                                                                                                                                                                                            $sql2 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` = '$id2'");
                                                                                                                                                                                                                            while ($row2 = mysqli_fetch_array($sql2)) { ?>
                                                                                                                                                                                                                                                                        <tr>
                                                                                                                                                                                                                                                                            <td>
                                                                                                                                                                                                                                                                                <?php echo $row['ordre']; ?>
                                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                                                            <td>
                                                                                                                                                                                                                                                                                <?php echo $row2['pseudo']; ?>
                                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                                                            <td>
                                                                                                                                                                                                                                                                                <?php echo $row['option']; ?>
                                                                                                                                                                                                                                                                            </td>
                                                                                                                                    
                                                                                                                                                                                                                                <?php } ?>
                                                                                                                                                                                                                                <td>
                                                                                                                                                                                                                                    <a href="voir-membre.php?id=<?php echo $row['id-membre']; ?>"  tooltip="Edition"><i class="fa fa-pencil"></i></a>
                                                                                                                                                                            <i class="fas fa-edit"></i></a>
                                                                                                                                                                                                                                    <!-- <a href="ajout-competences.php?id=<?php echo $row['id'] ?>&del=deleteind"
                                                                                                                                                                                                                onClick="return confirm('Are you sure you want to delete?')"
                                                                                                                                                                                                                class="btn btn-transparent btn-xs tooltips"
                                                                                                                                                                                                                tooltip-placement="top"
                                                                                                                                                                                                                tooltip="Remove"><i
                                                                                                                                                                                                                    class="fa fa-times fa fa-white"></i></a> -->
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
                                                                                                                                                <form role="form"
                                                                                                                                                    name="adddoc"
                                                                                                                                                    method="post"
                                                                                                                                                    onSubmit="return valid();">
                                                                                                                                                    <div
                                                                                                                                                        class="form-group">
                                                                                                                                                        <label
                                                                                                                                                            for="compet">
                                                                                                                                                            Ajout
                                                                                                                                                            Competence
                                                                                                                                                        </label>
                                                                                                                                                        <select
                                                                                                                                                            name="compet"
                                                                                                                                                            class="form-control"
                                                                                                                                                            required="true">
                                                                                                                                                            <!--		<option value="compet">Select Competence</option> -->
                                                                                                                                                            <option
                                                                                                                                                                value="compet">
                                                                                                                                                                Select
                                                                                                                                                                Competence
                                                                                                                                                            </option>
                                                                                                                                                            <?php $ret2 = mysqli_query($con, "select * from competences");
                                                                                                                                                            while ($row2 = mysqli_fetch_array($ret2)) {
                                                                                                                                                                ?>
                                                                                                                                                                                                        <option
                                                                                                                                                                                                            value="<?php echo htmlentities($row2['id']); ?>">
                                                                                                                                                                                                            <?php echo htmlentities($row2['nom']); ?>
                                                                                                                                                                                                        </option>
                                                                                                                                                                                                        $indiv=
                                                                                                                                                            <?php } ?>
                                                                                                                                                        </select>
                                                                                                                                                    </div>
                                                                                                                                                    <button
                                                                                                                                                        type="submit"
                                                                                                                                                        name="submit2"
                                                                                                                                                        id="submit2"
                                                                                                                                                        class="btn btn-o btn-primary">
                                                                                                                                                        Ajout Comp
                                                                                                                                                    </button>
                                                                                                                                                </form>
                                                                                                                                            </div>
                                                                                                                                        </div>

                                                                                                                                    <!-- </div> -->
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
                                                                                                                        <div
                                                                                                                            class="container-fluid container-fullw bg-white">
                                                                                                                            <div class="row">
                                                                                                                                <div class="col-md-12">
                                                                                                                                    <!-- <div class="row margin-top-30"> -->
                                                                                                                                        <div
                                                                                                                                            class="col-lg-8 col-md-12">
                                                                                                                                            <div
                                                                                                                                                class="panel panel-white">
                                                                                                                                                <!--	<div class="panel-heading">
                                                                                                                <h5 class="panel-title">Ajout Personne</h5>
                                                                                                            </div> -->
                                                                                                                                                <div
                                                                                                                                                    class="panel-body">
                                                                                                                                                    <div
                                                                                                                                                        id="layoutSidenav_content">
                                                                                                                                                        <main>
                                                                                                                                                            <div
                                                                                                                                                                class="container-fluid px-4">
                                                                                                                                                                <!--    <h1 class="mt-4">Gestion des Competences</h1> -->
                                                                                                                                                                <ol
                                                                                                                                                                    class="breadcrumb mb-4">
                                                                                                                                                                    <li
                                                                                                                                                                        class="breadcrumb-item">
                                                                                                                                                                        <a
                                                                                                                                                                            href="liste-membres.php">Membres</a>
                                                                                                                                                                    </li>
                                                                                                                                                                    <li
                                                                                                                                                                        class="breadcrumb-item active">
                                                                                                                                                                        Activités
                                                                                                                                                                    </li>
                                                                                                                                                                </ol>
                                                                                                                                                                <div
                                                                                                                                                                    class="card mb-4">
                                                                                                                                                                    <!--   <div class="card-header">
                                                                                                                                <i class="fas fa-table me-1"></i>
                                                                                                                                Registered User Details
                                                                                                                                </div> -->
                                                                                                                                                                    <div
                                                                                                                                                                        class="card-body">
                                                                                                                                                                        <!-- <table id="datatablesSimple"> -->
                                                                                                                                                                        <table
                                                                                                                                                                            id="example3"
                                                                                                                                                                            class="display"
                                                                                                                                                                            style="width:100%">
                                                                                                                                                                            <thead>
                                                                                                                                                                                <tr>
                                                                                                                                                                                    <th>Date
                                                                                                                                                                                    </th>
                                                                                                                                                                                    <th>Titre
                                                                                                                                                                                    </th>
                                                                                                                                                                                    <th>Lieux
                                                                                                                                                                                    </th>
                                                                                                                                                                                    <th>Action
                                                                                                                                                                                    </th>
                                                                                                                                                                                </tr>
                                                                                                                                                                            </thead>
                                                                                                                                                                            <tfoot>
                                                                                                                                                                                <tr>
                                                                                                                                                                                    <th>Date
                                                                                                                                                                                    </th>
                                                                                                                                                                                    <th>Titre
                                                                                                                                                                                    </th>
                                                                                                                                                                                    <th>Lieux
                                                                                                                                                                                    </th>
                                                                                                                                                                                    <th>Action
                                                                                                                                                                                    </th>
                                                                                                                                                                                </tr>
                                                                                                                                                                            </tfoot>
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
                                                                                                                                                                                                                                                                                <?php echo $row2['titre-activite']; ?>
                                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                                                            <td>
                                                                                                                                                                                                                                                                                <?php echo $row2['ville']; ?>
                                                                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                                <?php } ?>
                                                                                                                                                                                                                                <td>
                                                                                                                                                                                                                                    <a href="voir-activite.php?uid=<?php echo $row['id-activite']; ?>"
                                                                                                                                                                                                                                        class="btn btn-transparent btn-xs"
                                                                                                                                                                                                                                        tooltip-placement="top"
                                                                                                                                                                                                                                        tooltip="Edit"><i
                                                                                                                                                                                                                                            class="fa fa-pencil"></i></a>
                                                                                                                                                                                                                                    <!-- <i class="fas fa-edit"></i></a>  -->
                                                                                                                                                                                                                                    <!-- <a href="ajout-competences.php?id=<?php echo $row['id'] ?>&del=deleteind"
                                                                                                                                                        onClick="return confirm('Are you sure you want to delete?')"
                                                                                                                                                        class="btn btn-transparent btn-xs tooltips"
                                                                                                                                                        tooltip-placement="top"
                                                                                                                                                        tooltip="Remove"><i
                                                                                                                                                            class="fa fa-times fa fa-white"></i></a> -->
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
                                                                                                                                    <!-- </div> -->
                                                                                                                                </div>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div id="phpE">
                                                                                                            <!-- <script src="../js/datatables-simple-demo.js"></script> -->
                                                                                                            <div class="row">
                                                                                                                <div class="col-md-12">
                                                                                                                    <!-- <h5 class="over-title margin-bottom-15">-> <span class="text-bold">Gestion des Loisirs</span></h5> -->
                                                                                                                    <div
                                                                                                                        class="container-fluid container-fullw bg-white">
                                                                                                                        <div class="row">
                                                                                                                            <div class="col-md-12">
                                                                                                                                <!-- <div class="row margin-top-30"> -->
                                                                                                                                    <div class="col-lg-8 col-md-12">
                                                                                                                                        <div
                                                                                                                                            class="panel panel-white">
                                                                                                                                            <!--	<div class="panel-heading">
                                                                                    <h5 class="panel-title">Ajout Personne</h5>
                                                                                </div> -->
                                                                                                                                            <div class="panel-body">
                                                                                                                                                <div
                                                                                                                                                    id="layoutSidenav_content">
                                                                                                                                                    <main>
                                                                                                                                                        <div
                                                                                                                                                            class="container-fluid px-4">
                                                                                                                                                            <!--    <h1 class="mt-4">Gestion des Competences</h1> -->
                                                                                                                                                            <ol
                                                                                                                                                                class="breadcrumb mb-4">
                                                                                                                                                                <li
                                                                                                                                                                    class="breadcrumb-item">
                                                                                                                                                                    <a
                                                                                                                                                                        href="liste-membres.php">Membres</a>
                                                                                                                                                                </li>
                                                                                                                                                                <li
                                                                                                                                                                    class="breadcrumb-item active">
                                                                                                                                                                    Loisirs
                                                                                                                                                                </li>
                                                                                                                                                            </ol>
                                                                                                                                                            <div
                                                                                                                                                                class="card mb-4">
                                                                                                                                                                <!--   <div class="card-header">
                                                                                                    <i class="fas fa-table me-1"></i>
                                                                                                    Registered User Details
                                                                                                    </div> -->
                                                                                                                                                                <div
                                                                                                                                                                    class="card-body">
                                                                                                                                                                    <table
                                                                                                                                                                        id="example2"
                                                                                                                                                                        class="display"
                                                                                                                                                                        style="width:100%">
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
                                                                                                                                                                        <tfoot>
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
                                                                                                                                                                        </tfoot>
                                                                                                                                                                        <tbody>
                                                                                                                                                                            <?php $ret3 = mysqli_query($con, "SELECT * FROM `loisirs-individu` WHERE `id-indiv` = '$id'");
                                                                                                                                                                            $cnt2 = 1;
                                                                                                                                                                            while ($row3 = mysqli_fetch_array($ret3)) { ?>
                                                                                                                                                                                                                        <?php
                                                                                                                                                                                                                        $id4 = $row3['id-lois'];
                                                                                                                                                                                                                        $sql4 = mysqli_query($con, "SELECT * FROM `loisirs` WHERE `id` = '$id4'");
                                                                                                                                                                                                                        while ($row4 = mysqli_fetch_array($sql4)) { ?>
                                                                                                                                                                                                                                                                    <tr>
                                                                                                                                                                                                                                                                        <td>
                                                                                                                                                                                                                                                                            <?php echo $row4['nom']; ?>
                                                                                                                                                                                                                                                                        </td>
                                                                                                                                                                                                                                                                        <td>
                                                                                                                                                                                                                                                                            <?php echo $row4['commentaire']; ?>
                                                                                                                                                                                                                                                                        </td>
                                                                                                                                                                                                                                                                        <td>
                                                                                                                                                                                                                                                                            <?php echo $row3['date']; ?>
                                                                                                                                                                                                                                                                        </td>
                                                                                                                                                                                                                            <?php } ?>
                                                                                                                                                                                                                            <td>
                                                                                                                                                                                                                                <!--<a href="edit-competences.php?id=<?php echo $row['id']; ?>" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
                                                                                                                    <i class="fas fa-edit"></i></a> -->
                                                                                                                                                                                                                                <a href="ajout-loisirs.php?id=<?php echo $row3['id'] ?>&del=deleteind"
                                                                                                                                                                                                                                    onClick="return confirm('Are you sure you want to delete?')"
                                                                                                                                                                                                                                    class="btn btn-transparent btn-xs tooltips"
                                                                                                                                                                                                                                    tooltip-placement="top"
                                                                                                                                                                                                                                    tooltip="Remove"><i
                                                                                                                                                                                                                                        class="fa fa-times fa fa-white"></i></a>
                                                                                                                                                                                                                            </td>
                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                        <?php $cnt2 = $cnt2 + 1;
                                                                                                                                                                            } ?>
                                                                                                                                                                        </tbody>
                                                                                                                                                                    </table>
                                                                                                                                                                </div>
                                                                                                                                                            </div>
                                                                                                                                                        </div>
                                                                                                                                                    </main>
                                                                                                                                                </div>
                                                                                                                                            </div>
                                                                                                                                            <form role="form"
                                                                                                                                                name="adddoc"
                                                                                                                                                method="post"
                                                                                                                                                onSubmit="return valid();">
                                                                                                                                                <div
                                                                                                                                                    class="form-group">
                                                                                                                                                    <label
                                                                                                                                                        for="lois">
                                                                                                                                                        Ajout Loisir
                                                                                                                                                    </label>
                                                                                                                                                    <select
                                                                                                                                                        name="lois"
                                                                                                                                                        class="form-control"
                                                                                                                                                        required="true">
                                                                                                                                                        <!--		<option value="compet">Select Loisir</option> -->
                                                                                                                                                        <option
                                                                                                                                                            value="lois">
                                                                                                                                                            Select
                                                                                                                                                            Loisir
                                                                                                                                                        </option>
                                                                                                                                                        <?php $ret2 = mysqli_query($con, "select * from loisirs");
                                                                                                                                                        while ($row2 = mysqli_fetch_array($ret2)) {
                                                                                                                                                            ?>
                                                                                                                                                                                                    <option
                                                                                                                                                                                                        value="<?php echo htmlentities($row2['id']); ?>">
                                                                                                                                                                                                        <?php echo htmlentities($row2['nom']); ?>
                                                                                                                                                                                                    </option>
                                                                                                                                                                                                    $indiv=
                                                                                                                                                        <?php } ?>
                                                                                                                                                    </select>
                                                                                                                                                </div>
                                                                                                                                                <button
                                                                                                                                                    type="submit"
                                                                                                                                                    name="submit3"
                                                                                                                                                    id="submit3"
                                                                                                                                                    class="btn btn-o btn-primary">
                                                                                                                                                    Ajout lois
                                                                                                                                                </button>
                                                                                                                                            </form>
                                                                                                                                        </div>
                                                                                                                                    </div>
                                                                                                                                <!-- </div> -->
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <!-- </div> -->
                                                                                                <!-- </div> -->
                                                                                                <!-- </div> -->
                                                                                                <!-- end: BASIC EXAMPLE -->
                                                                                                <!-- end: SELECT BOXES -->
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
                                                                                <script
                                                                                    src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
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
                                                                                    crossorigin="anonymous"></script>
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