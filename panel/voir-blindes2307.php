<?php
session_start();
error_reporting(0);
include('include/config.php');
$id = intval($_GET['uid']);
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
    if (isset($_POST['moins'])) {
        $id = $_GET['uid'];
        ?> <script type="text/javascript">window.location.replace("/panel/modif-horloge.php?act=<?php echo $id ?>&min=-5&sou=http://viendez.com/panel/voir-blindes.php?uid=");</script> ; <?php

    }
    if (isset($_POST['plus'])) {
        $id = $_GET['uid'];
        ?> <script type="text/javascript">window.location.replace("/panel/modif-horloge.php?act=<?php echo $id ?>&min=+5&sou=http://viendez.com/panel/voir-blindes.php?uid=");</script> ; <?php

    }
    if (isset($_POST['moins1'])) {
        $id = $_GET['uid'];
        ?> <script type="text/javascript">window.location.replace("/panel/modif-horloge.php?act=<?php echo $id ?>&min=-1&sou=http://viendez.com/panel/voir-blindes.php?uid=");</script> ; <?php

    }
    if (isset($_POST['plus1'])) {
        $id = $_GET['uid'];
        ?> <script type="text/javascript">window.location.replace("/panel/modif-horloge.php?act=<?php echo $id ?>&min=+1&sou=http://viendez.com/panel/voir-blindes.php?uid=");</script> ; <?php

    }
    if (isset($_POST['pauseresume'])) {
        $id = $_GET['uid'];
        if ($_SESSION["en_pause".$id] =="0") {
        ?> <script type="text/javascript">window.location.replace("/panel/en-pause.php?act=<?php echo $id ?>&sou=http://viendez.com/panel/voir-blindes.php?uid=");</script> ; <?php
        }
        if ($_SESSION["en_pause".$id] =="1") {
            ?> <script type="text/javascript">window.location.replace("/panel/de-pause.php?act=<?php echo $id ?>&sou=http://viendez.com/panel/voir-blindes.php?uid=");</script> ; <?php
            }

    }
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

                                                <script>responsiveVoice.setDefaultVoice("French Female")</script>
                                                <!-- <script>responsiveVoice.speak("menu activite")</script> -->
                                                
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
                                                <script>
                                                    // var audio = new Audio("plus1_071016_Alex.WAV");
                                                    // var audio = new Audio("http://glpjt.s3.amazonaws.com/so/av/a12.mp3");                 
                                                    var audio = new Audio("https://s3.amazonaws.com/audio-experiments/examples/elon_mono.wav");
                                                    function playAudio() {
                                                        audio.play();
                                                    }   
                                                    function pauseAudio() {
                                                        audio.pause();
                                                    }
                                                    function cancelAudio() {
                                                        audio.pause();
                                                        audio.currentTime = 0;
                                                    }
                                                    //  playAudio();
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
                                                                            $id = intval($_GET['uid']);
                                                                            $reqnbt = mysqli_query($con, "SELECT * FROM `activite` WHERE `id-activite` = '$id' ");
                                                                            $res = mysqli_fetch_array($reqnbt) ;
                                                                            $nbt = $res["nb-tables"];   
                                                                            
                                                                            if ($nbt == '1') 
                                                                            {?>
                                                                                <div id="bMenu">
                                                                                <a href="#" id="Timer" class="btnnav" onmouseover="afficher1('Timer')">Timer</a>
                                                                                <a href="#" id="Blindes" class="btnnav" onmouseover="afficher1('Blindes')">Blindes</a>
                                                                                <a href="#" id="Structure" class="btnnav" onmouseover="afficher1('Structure')">Structure</a>
                                                                                <!-- <a href="#" id="t2" class="btnnav" onmouseover="afficher2('t2')">Table 2</a> -->
                                                                                <!-- <a href="#" id="t3" class="btnnav" onmouseover="afficher2('t3')">Table 3</a> -->
                                                                                <!-- <a href="#" id="t4" class="btnnav" onmouseover="afficher2('t4')">Table 4</a> -->
                                                                                </div>
                                                                            <?php };

                                                                            if ($nbt == '2') 
                                                                            {?>
                                                                                <div id="bMenu">
                                                                                <a href="#" id="Timer" class="btnnav" onmouseover="afficher2('Timer')">Timer</a>
                                                                                <a href="#" id="Blindes" class="btnnav" onmouseover="afficher2('Blindes')">Blindes</a>
                                                                                <a href="#" id="Structure" class="btnnav" onmouseover="afficher2('Structure')">Structure</a>
                                                                                <a href="#" id="Outils" class="btnnav" onmouseover="afficher2('Outils')">Outils</a>
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
                                                                            <div id="TimerE">
                                        <div class="rrow">
                                            <div class="ccol-md-12">
                                                <div class="ccontainer-fluid ccontainer-fullw bbg-white">
                                                    <div class="rrow">
                                                        <div class="col-md-12">
                                                            <div class="rrow margin-top-0">
                                                                <div class="col-lg-8 col-md-12">
                                                                    <div class="ppanel panel-wwhite">
                                                                        <div class="ppanel-body">
                                                                            <div id="llayoutSidenav_content">
                                                                                <main>
                                                                                    <div class="ccontainer-fluid px-4">
                                                                                        <ol class="breadcrumb mb-4">
                                                                                            <li class="breadcrumb-item">
                                                                                            <a href="/panel/voir-activite.php?uid=<?php echo $id; ?>">Retour Activité</a>
                                                                                            </li>
                                                                                            <li class="breadcrumb-item active">
                                                                                                <a href="/panel/voir-blindes.php?&uid=<?php echo $id ?>">Blindes</a>
                                                                                            </li>
                                                                                        </ol>
                                                                                        <div class="card mb-4">
                                                                                            <div class="card-body">
                                                                                                
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </main>
                                                                            </div>
                                                                                                                                                    
                                                                            <?php $id = intval($_GET['uid']);$_SESSION["act"]=$id; ?>
                                                                            <?php include_once('horloge-heure.php'); ?>  
                                                                                <div style="color:red ; font-size: 200px ; text-align: center;" id="response"></div>
                                                                            <div style="color:green ; text-align: center" >  
                                                                            <form method="post">                                   
                                                                                
                                                                                <table class="table table-bordered">
                                                                                    <tr>    
                                                                                        <td colspan="3" style="text-align:center ;">
                                                                                            <button type="submit"
                                                                                                                                                                        id="moins" 
                                                                                                                                                                        class="btn btn-primaryg btn-block"
                                                                                                                                                                        name="moins"><<< -5 Minutes</button>
                                                                                        </td>
                                                                                        <td colspan="3" style="text-align:center ;">
                                                                                                                                                                    <button type="submit"
                                                                                                                                                                        class="btn btn-primary btn-block"
                                                                                                                                                                        name="pauseresume">Pause / Resume</button>
                                                                                        </td>
                                                                                        <td colspan="3" style="text-align:center ;">
                                                                                                                                                                    <button 
                                                                                                                                                                        type="submit"
                                                                                                                                                                        class="btn btn-primary-rouge btn-block"
                                                                                                                                                                        name="plus">+5 Minutes >>></button>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>    
                                                                                        <td colspan="3" style="text-align:center ;">
                                                                                            <button type="submit"
                                                                                                                                                                        id="moins1" 
                                                                                                                                                                        class="btn btn-primaryg btn-block"
                                                                                                                                                                        name="moins1"><<< -1 Minute</button>
                                                                                        </td>
                                                                                        <td colspan="3" style="text-align:center ;">
                                                                                                                                                                    <button type="submit"
                                                                                                                                                                        class="btn btn-primary btn-block"
                                                                                                                                                                        name="pauseresume">Reset blinde</button>
                                                                                        </td>
                                                                                        <td colspan="3" style="text-align:center ;">
                                                                                                                                                                    <button 
                                                                                                                                                                        type="submit"
                                                                                                                                                                        class="btn btn-primary-rouge btn-block"
                                                                                                                                                                        name="plus1">+1 Minute >>></button>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <!-- <tr>    
                                                                                        <td colspan="4" style="text-align:center ;">
                                                                                            <button type="submit"
                                                                                                                                                                        id="moinsb" 
                                                                                                                                                                        class="btn btn-primaryg btn-block"
                                                                                                                                                                        name="moinsb"><<< Blinde Precedente</button>
                                                                                        </td>
                                                                                        <td colspan="4" style="text-align:center ;">
                                                                                                                                                                    <button 
                                                                                                                                                                        type="submit"
                                                                                                                                                                        class="btn btn-primary-rouge btn-block"
                                                                                                                                                                        name="plusb">Blinde suivante >>></button>
                                                                                        </td>
                                                                                    </tr> -->
                                                                                </table>  
                                                                            </form>                                   
                                                                                   
                                                                                <!-- <a href="modif-horloge.php?act=<?php echo $id;?>&min=-2&sou=http://poker31.org/panel/voir-blindes.php?uid=">-2 Minutes
                                                                                <a href="modif-horloge.php?act=<?php echo $id;?>&min=2&sou=http://poker31.org/panel/voir-blindes.php?uid="> / +2 Minutes
                                                                                <a href="en-pause.php?act=<?php echo $id;?>&min=2&sou=http://poker31.org/panel/voir-blindes.php?uid="> / Pause
                                                                                <a href="de-pause.php?act=<?php echo $id;?>&min=2&sou=http://poker31.org/panel/voir-blindes.php?uid="> / Reprise</a> -->
                                                                            </div>
                                                                            <?php include_once('horloge-sb.php'); ?>  
                                                                                <div style="color:orange ; font-size: 90px  ; text-align: center" id="response-sb"></div>
                                                                            <?php include_once('horloge-ante.php'); ?>  
                                                                                <div style="color:blue ; font-size: 50px ; text-align: center" id="response-ante"></div>
                                                                            <?php include_once('horloge-pause.php'); ?> 
                                                                                <div style="color:red ; font-size: 30px ; text-align: center" id="car-pause"></div>
                                                                            <?php include_once('horloge-estim.php'); ?> 
                                                                                <div style="color:grey ; font-size: 30px ; text-align: center" id="response-estim">
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
                                                                                <div id="StructureE">
                                                                                </div>
                                                                                <div id="OutilsE">
                                                                                </div>
                                                                                <div id="t3E">
                                                                                </div>
                                                                                <div id="BlindesE">
                                                                                <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <div class="ccontainer-fluid ccontainer-fullw bbg-white">
                                                                                                <div class="row">
                                                                                                    <div class="col-md-12">
                                                                                                        <div class="row margin-top-30">
                                                                                                            <div class="col-lg-8 col-md-12">
                                                                                                                <div class="panel panel-wwhite">
                                                                                                                    <div class="panel-body">
                                                                                                                        <div id="layoutSidenav_content">
                                                                                                                        <main>
                                                                                    <div class="container-fluid ppx-4">
                                                                                        <ol class="breadcrumb mb-4">
                                                                                            <li class="breadcrumb-item">
                                                                                            <a href="voir-activite.php?uid=<?php echo $id; ?>">Retour Activité</a>
                                                                                            </li>
                                                                                            <li class="breadcrumb-item aactive">
                                                                                                <a href="sieges.php?&ac=<?php echo $id ?>">Blindes</a>
                                                                                            </li>
                                                                                        </ol>
                                                                                        <div class="card mmb-4">
                                                                                            <div class="card-body">
                                                                                                <table
                                                                                                    id="example"
                                                                                                    class="cell-border compact stripe hover"
                                                                                                    style="width:95% ;font-size:12px;color:black">
                                                                                                    <thead>
                                                                                                        <tr>
                                                                                                            <!-- <th>id</th> -->
                                                                                                            <th>Ordre</th>
                                                                                                            <!-- <th>activite</th> -->
                                                                                                            <th>Blindes</th>
                                                                                                            <th>Duree</th>
                                                                                                            <th>Fin</th>
                                                                                                            <th>Ante</th>
                                                                                                            <th>Modifier</th>
                                                                                                        </tr>
                                                                                                    </thead>
                                                                                                    <tbody>
                                                                                                        <?php $ret = mysqli_query($con, "SELECT * FROM `blindes-live` WHERE (`id-activite` = $id ) ");
                                                                                                        $cnt = 1;
                                                                                                        while ($row = mysqli_fetch_array($ret)) { ?>
                                                                                                            <?php
                                                                                                            $id2 = $row['id-activite'];
                                                                                                            $sql2 = mysqli_query($con, "SELECT * FROM `activite` WHERE `id-activite` = '$id2' ");
                                                                                                            while ($row2 = mysqli_fetch_array($sql2)) { ?>
                                                                                                                <tr>
                                                                                                                    <!-- <td><?php echo $row['id']; ?></td> -->
                                                                                                                    <td><?php echo $row['ordre']; ?></td>
                                                                                                                    <!-- <td><a href="voir-activite.php?id=<?php echo $row['id-activite']; ?>"  ><?php echo $row2['titre']; ?></a></td> -->
                                                                                                                    <td><?php echo $row['nom']; ?></td> 
                                                                                                                    <td><?php echo $row['duree']; ?></td>
                                                                                                                    <td><?php echo $row['fin']; ?></td>
                                                                                                                    <td><?php echo $row['ante']; ?></td>
                                                                                                                <?php } ?>
                                                                                                                <td>
                                                                                                                    <a href="modif-blinde-live.php?id=<?php echo $row['id']; ?>"  tooltip="Edition"><i class="fa fa-pencil"></i></a>
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
                                                    
                                                    function afficher1(id) {
                                                        var leCalque = document.getElementById(id);
                                                        var leCalqueE = document.getElementById(id + "E");

                                                        document.getElementById("TimerE").className = "rubrique bgImg";
                                                        // document.getElementById("t2E").className = "rubrique bgImg";
                                                        document.getElementById("BlindesE").className = "rubrique bgImg";
                                                        document.getElementById("StructureE").className = "rubrique bgImg";
                                                        // document.getElementById("t3E").className = "rubrique bgImg";
                                                        // document.getElementById("t4E").className = "rubrique bgImg";

                                                        document.getElementById("Timer").className = "btnnav";
                                                        // document.getElementById("t2").className = "btnnav";
                                                        document.getElementById("Blindes").className = "btnnav";
                                                        document.getElementById("Structure").className = "btnnav";
                                                        // document.getElementById("t3").className = "btnnav";
                                                        // document.getElementById("t4").className = "btnnav";

                                                        leCalqueE.className += " montrer";
                                                        leCalque.className = "btnnavA";
                                                    }

                                                    function afficher2(id) {
                                                        var leCalque = document.getElementById(id);
                                                        var leCalqueE = document.getElementById(id + "E");

                                                        document.getElementById("TimerE").className = "rubrique bgImg";
                                                        document.getElementById("OutilsE").className = "rubrique bgImg";
                                                        document.getElementById("BlindesE").className = "rubrique bgImg";
                                                        document.getElementById("StructureE").className = "rubrique bgImg";
                                                        // document.getElementById("t3E").className = "rubrique bgImg";
                                                        // document.getElementById("t4E").className = "rubrique bgImg";

                                                        document.getElementById("Timer").className = "btnnav";
                                                        document.getElementById("Outils").className = "btnnav";
                                                        document.getElementById("Blindes").className = "btnnav";
                                                        document.getElementById("Structure").className = "btnnav";
                                                        // document.getElementById("t3").className = "btnnav";
                                                        // document.getElementById("t4").className = "btnnav";

                                                        leCalqueE.className += " montrer";
                                                        leCalque.className = "btnnavA";
                                                    }

                                                </script>
                                                <?php
                                                // $onglet = 'inf';
                                                $onglet = $_GET['onglet'];
                                                if ($onglet == 'inf') {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                       afficher('Timer');
                                                    </script>
                                                    <?php 
                                                };
                                                if($onglet == 'ins') {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                        afficher('Blindes');
                                                    </script>
                                                    <?php
                                                 };
                                                if ($onglet == '1') {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                        afficher('Timer');
                                                    </script>
                                                    <?php 
                                                };
                                                if ($onglet == '2') {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                        afficher('Blindes');
                                                    </script>
                                                    <?php 
                                                };
                                                if ($onglet == '3') {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                        afficher('Structure');
                                                    </script>
                                                    <?php 
                                                };
                                                if ($onglet == '4') {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                        afficher('Outils');
                                                    </script>
                                                    <?php 
                                                };
                                                if ($onglet == '' AND $nbt == "1") {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                        afficher1('Timer');
                                                    </script>
                                                    <?php 
                                                };

                                                if ($onglet == '' AND $nbt == "2") {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                        afficher2('Timer');
                                                    </script>
                                                    <?php 
                                                };
                                                
                                                if ($onglet == '' AND $nbt == "3") {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                        afficher3('Timer');
                                                    </script>
                                                    <?php 
                                                };
                                                if ($onglet == '' AND $nbt == "4") {
                                                    ?>
                                                    <script type="text/javascript" language="javascript">
                                                        afficher4('Timer');
                                                    </script>
                                                    <?php 
                                                };
                                                ?><script type="text/javascript" language="javascript">
                                                        afficher1('Timer');
                                                    </script>
                                                
                                            </body>
                                        </html>
<?php } ?>