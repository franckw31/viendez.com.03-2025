<?php
session_start();
error_reporting(0);
include ('include/config.php');
if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
} else {

    if (isset($_POST['submit'])) {
        $titreactivite = $_POST['titre-activite'];
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
        $structure = $_POST['id-structure'];
        $jetons = $_POST['jetons'];
        $addon = $_POST['addon'];
        $msg = mysqli_query($con, "INSERT INTO `activite` ( `titre-activite`, `id-membre`, `date_depart`, `heure_depart`, `ville`, `rue`, `lng`, `lat`, `places`, `reserves`, `options`, `libre`, `commentaire`, `id-structure`, `buyin`, `rake`, `bounty`, `jetons`, `recave`, `addon`, `ante`, `bonus`) VALUES ( '$titreactivite', '$idmembre', '$date_depart', '$heure_depart', '$ville', NULL, NULL, NULL, '$places', NULL, '0', NULL, '$commentaire', '$structure', '$buyin', '$rake', '$bounty', '$jetons', '$recave', '$addon', '$ante', '0')");
        //$msg=mysqli_query($con,"INSERT INTO `activite` (`id-activite`, `titre-activite`, `id-membre`, `date_depart`, `heure_depart`, `ville`, `rue`, `lng`, `lat`, `places`, `reserves`, `options`, `libre`, `commentaire`, `structure`, `buyin`, `rake`, `bounty`, `jetons`, `recave`, `addon`, `ante`, `bonus`) VALUES (NULL, '-', '', '2022-12-31', '', '?', NULL, NULL, NULL, '8', NULL, '0', NULL, 'Aucun', 'Structure', '25', '5', '0', '40000', '1', '0', '0', '')");
        //$sql=mysqli_query($con,"insert into competences(nom) values('$doctorspecilization')");
        $_SESSION['msg'] = "Activité ajoutée avec succés !!";
        // header('location:http://poker31.org/panel/liste-activites.php');
        // exit;
    }
    //Code Deletion
    if (isset($_GET['del'])) {
        $sid = $_GET['id'];
        mysqli_query($con, "delete from competences where id = '$sid'");
        $_SESSION['msg'] = "data deleted !!";
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Admin | Ajout Activité</title>
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
        <link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" href="assets/css/styles.css">
        <link rel="stylesheet" href="assets/css/plugins.css">
        <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
    </head>

    <body>
        <div id="app">
            <?php include ('include/sidebar.php'); ?>
            <div class="app-content">
                <?php include ('include/header.php'); ?>
                <!-- end: TOP NAVBAR -->
                <div class="main-content">
                    <div class="wrap-content container" id="container">
                        <!-- start: PAGE TITLE -->
                        <section id="page-title">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h1 class="mainTitle">Admin | AJOUTER PARTIE </h1>
                                </div>
                                <ol class="breadcrumb">
                                    <li>
                                        <span>Admin</span>
                                    </li>
                                    <li class="active">
                                        <span>Ajouter PARTIE</span>
                                    </li>
                                </ol>
                            </div>
                        </section>
                        <!-- end: PAGE TITLE -->
                        <!-- start: BASIC EXAMPLE -->
                        <div class="container-fluid container-fullw bg-white">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row margin-top-30">
                                        <div class="col-lg-6 col-md-12">
                                            <div class="panel panel-white">
                                                <div class="panel-heading">
                                                    <h5 class="panel-title">PARTIE</h5>
                                                </div>
                                                <div class="panel-body">
                                                    <p style="color:red;">
                                                        <?php echo htmlentities($_SESSION['msg']); ?>
                                                        <?php echo htmlentities($_SESSION['msg'] = ""); ?>
                                                    </p>
                                                    <form role="form" name="dcotorspcl" method="post">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1">
                                                                Intitulé
                                                            </label>
                                                            <input type="text" name="doctorspecilization"
                                                                class="form-control" placeholder="Entrer la Competence">
                                                        </div>
                                                        <div class="card-body">
                                                            <table class="table table-bordered">
                                                                <tr>
                                                                    <th>Titre</th>
                                                                    <td><input class="form-control" id="titre-activite"
                                                                            name="titre-activite" type="text"
                                                                            value="<?php echo $result['titre-activite']; ?>"
                                                                            required /></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Date</th>
                                                                    <td><input class="form-control" id="date_depart"
                                                                            name="date_depart" type="date"
                                                                            value="<?php echo $result['date_depart']; ?>"
                                                                            required /></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Heure</th>
                                                                    <td><input class="form-control" id="heure_depart"
                                                                            name="heure_depart" type="time"
                                                                            value="<?php echo $result['heure_depart']; ?>">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Lieu</th>
                                                                    <td><input class="form-control" id="ville" name="ville"
                                                                            type="text"
                                                                            value="<?php echo $result['ville']; ?>"
                                                                            required /></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Adresse</th>
                                                                    <td><input class="form-control" id="commentaire"
                                                                            name="commentaire" type="text"
                                                                            value="<?php echo $result['commentaire']; ?>">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Organisateur</th>
                                                                    <td><input class="form-control" id="id-membre"
                                                                            name="id-membre" type="text"
                                                                            value="<?php echo $result['id-membre']; ?>"
                                                                            required /></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Nb Joueurs Max</th>
                                                                    <td><input class="form-control" id="places"
                                                                            name="places" type="text"
                                                                            value="<?php echo $result['places']; ?>"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Buyin</th>
                                                                    <td><input class="form-control" id="buyin" name="buyin"
                                                                            type="text"
                                                                            value="<?php echo $result['buyin']; ?>"
                                                                            required /></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Rake</th>
                                                                    <td><input class="form-control" id="rake" name="rake"
                                                                            type="text"
                                                                            value="<?php echo $result['rake']; ?>"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Bounty</th>
                                                                    <td><input class="form-control" id="bounty"
                                                                            name="bounty" type="text"
                                                                            value="<?php echo $result['bounty']; ?>"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Nb Recave</th>
                                                                    <td><input class="form-control" id="recave"
                                                                            name="recave" type="text"
                                                                            value="<?php echo $result['recave']; ?>"
                                                                            required /></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Addon</th>
                                                                    <td><input class="form-control" id="addon" name="addon"
                                                                            type="text"
                                                                            value="<?php echo $result['addon']; ?>"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Ante</th>
                                                                    <td><input class="form-control" id="ante" name="ante"
                                                                            type="text"
                                                                            value="<?php echo $result['ante']; ?>"></td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Structure</th>
                                                                    <td><input class="form-control" id="id-structure"
                                                                            name="id-structure" type="text"
                                                                            value="<?php echo $result['id-structure']; ?>">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Stack</th>
                                                                    <td><input class="form-control" id="jetons"
                                                                            name="jetons" type="text"
                                                                            value="<?php echo $result['jetons']; ?>"></td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="4" style="text-align:center ;"><button
                                                                            type="submit" class="btn btn-primary btn-block"
                                                                            name="submit">Creation</button></td>
                                                                </tr>
                                                                <!--</tbody>-->
                                                            </table>
                                                        </div>
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
                <!-- end: BASIC EXAMPLE -->
                <!-- end: SELECT BOXES -->
            </div>
            <!-- start: FOOTER -->
            <?php include ('include/footer.php'); ?>
            <!-- end: FOOTER -->
            <!-- start: SETTINGS -->
            <?php include ('include/setting.php'); ?>
            <!-- end: SETTINGS -->
        </div>
        <!-- start: MAIN JAVASCRIPTS -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
        <script src="vendor/modernizr/modernizr.js"></script>
        <script src="vendor/jquery-cookie/jquery.cookie.js"></script>
        <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
        <script src="vendor/switchery/switchery.min.js"></script>
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
    </body>

    </html>
<?php } ?>