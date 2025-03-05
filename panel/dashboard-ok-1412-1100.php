<?php
session_start();
error_reporting(0);
include('include/config.php');
if (strlen($_SESSION['id'] == 0)) {
	header('location:logout.php');
} else {


	?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<title>AdmiN | Dashboard</title>

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
			<?php
			$fiche = $_SESSION['id'];
			// echo "/".$fiche."/";
			$result = mysqli_query($con, "SELECT * FROM membres  WHERE 'id-membre' = $fiche");
			$num = mysqli_fetch_array($result);
			$droits = $num['droits'];
			// echo '-----'.$fiche.'------'.$droits;
			// if ($droits = '1') {
			// 	include('include/sidebaruser.php');
			// } else {
			// 	if ($droits = '2')
			// 		include('include/sidebar.php');
			// }
			include('include/sidebar.php');
			?>
			<div class="app-content">

				<?php include('include/header.php'); ?>

				<!-- end: TOP NAVBAR -->
				<div class="main-content">
					<div class="wrap-content container" id="container">
						<!-- start: PAGE TITLE -->
						<section id="page-title">
							<div class="row">
								<div class="col-sm-8">
									<h1 class="mainTitle">.</h1>
									<?php
									$result = mysqli_query($con, "SELECT * FROM joueurs  WHERE id=$fiche");
									$num = mysqli_fetch_array($result);
									$droits = $num['droits'];
									//	echo '-----'.$fiche.'------';
									//	echo $num['prenom'].' ('.$_SESSION['id'].')'.'('.$droits.')'; ?>
								</div>
								<ol class="breadcrumb">
									<li>
										<span>Admin.</span>
									</li>
									<li class="active">
										<span>Dashboard</span>
									</li>
								</ol>
							</div>
						</section>
						<!-- end: PAGE TITLE -->
						<!-- start: BASIC EXAMPLE bg-white-->
						<div class="container-fluid container-fullw ">
							<div class="row">
								<div class="col-sm-4">
									<div class="panel panel-white no-radius text-center">
										<!-- <div class="panel panel-white no-radius text-center"> -->
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i class="fa fa-square fa-stack-2x text-primary"></i><a href="liste-membres-container.php"> <i
													class="fa fa-smile-o fa-stack-1x fa-inverse"></i> </span>
											<h2 class="StepTitle">Gestion des Membres</h2>

											<p class="cl-effect-1">
												<a href="liste-membres-container.php">
													<?php $result = mysqli_query($con, "SELECT * FROM membres ");
													$num_rows = mysqli_num_rows($result); {
														?>
														Nb de Membres :
														<?php echo htmlentities($num_rows);
													} ?>
												</a>
											</p>
										</div>
									</div>
								</div>
								<div class="col-sm-4"><a href="liste-activites.php">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i
													class="fa fa-square fa-stack-2x text-primary"></i> <i
													class="fa fa-users fa-stack-1x fa-inverse"></i> </span>
											<h2 class="StepTitle">Gestion des Activités</h2>

											<p class="cl-effect-1">
												<a href="liste-activites.php">
													<?php $result1 = mysqli_query($con, "SELECT * FROM activite ");
													$num_rows1 = mysqli_num_rows($result1); {
														?>
														Nb d activités :
														<?php echo htmlentities($num_rows1);
													} ?>
												</a>

											</p>
										</div>
									</div>
								</div>
								<div class="col-sm-4"><a href="prochaines-activites.php">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i
													class="fa fa-square fa-stack-2x text-primary"></i> <i
													class="fa fa-users fa-stack-1x fa-inverse"></i> </span>
											<h2 class="StepTitle">Prochaines Activités</h2>

											<p class="cl-effect-1">
												<a href="prochaines-activites.php">
													<?php $result1 = mysqli_query($con, "SELECT * FROM activite where date_depart <> '0000-00-00' and datediff(date_depart,now())>-1 order by date_depart");
													$num_rows1 = mysqli_num_rows($result1); {
														?>
														Nb d activités :
														<?php echo htmlentities($num_rows1);
													} ?>
												</a>

											</p>
										</div>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i
													class="fa fa-square fa-stack-2x text-primary"></i> <i
													class="fa fa-terminal fa-stack-1x fa-inverse"></i> </span>
											<h2 class="StepTitle"> Gestion des Competences</h2>

											<p class="links cl-effect-1">
												<a href="book-appointment.php">
													<a href="ajout-competences.php">
														<?php $sql = mysqli_query($con, "SELECT * FROM competences");
														$num_rows2 = mysqli_num_rows($sql); {
															?>
															Nb de Competences :
															<?php echo htmlentities($num_rows2);
														} ?>
													</a>
												</a>
											</p>
										</div>
									</div>
								</div>

								<div class="col-sm-4">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i
													class="fa fa-square fa-stack-2x text-primary"></i> <i
													class="fa fa-terminal fa-stack-1x fa-inverse"></i> </span>
											<h2 class="StepTitle"> Gestion des Loisirs</h2>

											<p class="links cl-effect-1">
												<a href="book-appointment.php">
													<a href="ajout-loisir.php">
														<?php $sql = mysqli_query($con, "SELECT * FROM loisirs");
														$num_rows2 = mysqli_num_rows($sql); {
															?>
															Nb de loisirs :
															<?php echo htmlentities($num_rows2);
														} ?>
													</a>
												</a>
											</p>
										</div>
									</div>
								</div>

								<div class="col-sm-4">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i
													class="fa fa-square fa-stack-2x text-primary"></i> <i
													class="fa fa-terminal fa-stack-1x fa-inverse"></i> </span>
											<h2 class="StepTitle">Gestion des Challenges</h2>

											<p class="links cl-effect-1">
												<a href="gestion-challenge.php">
													<?php $result = mysqli_query($con, "SELECT * FROM challenge ");
													$num_rows = mysqli_num_rows($result); {
														?>
														Total Challenge :
														<?php echo htmlentities($num_rows);
													} ?>
												</a>
											</p>
										</div>
									</div>
								</div>





								<div class="col-sm-4">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i class="ti-files fa-1x text-primary"></i> <i
													class="fa fa-terminal fa-stack-1x fa-inverse"></i> </span>
											<h2 class="StepTitle">Recherche par Compétence</h2>

											<p class="links cl-effect-1">
												<a href="book-appointment.php">
													<a href="recherche-competence.php">
														<?php
														$sql = mysqli_query($con, "SELECT * FROM tblcontactus where  IsRead is null");
														$num_rows22 = mysqli_num_rows($sql);
														?>
														Lancer recherche :
														<?php echo htmlentities($num_rows22); ?>
													</a>
												</a>
											</p>
										</div>
									</div>
								</div>

								<div class="col-sm-4">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i class="ti-files fa-1x text-primary"></i> <i
													class="fa fa-terminal fa-stack-1x fa-inverse"></i> </span>
											<h2 class="StepTitle">Recherche par Loisir</h2>

											<p class="links cl-effect-1">
												<a href="book-appointment.php">
													<a href="recherche-loisir.php">
														<?php
														$sql = mysqli_query($con, "SELECT * FROM tblcontactus where  IsRead is null");
														$num_rows22 = mysqli_num_rows($sql);
														?>
														Lancer recherche :
														<?php echo htmlentities($num_rows22); ?>
													</a>
												</a>
											</p>
										</div>
									</div>
								</div>

								<div class="col-sm-4">
									<div class="panel panel-white no-radius text-center">
										<div class="panel-body">
											<span class="fa-stack fa-2x"> <i class="ti-files fa-1x text-primary"></i> <i
													class="fa fa-terminal fa-stack-1x fa-inverse"></i> </span>
											<h2 class="StepTitle">Recherche Globale</h2>

											<p class="links cl-effect-1">
												<a href="book-appointment.php">
													<a href="recherche-loisir.php">
														<?php
														$sql = mysqli_query($con, "SELECT * FROM tblcontactus where  IsRead is null");
														$num_rows22 = mysqli_num_rows($sql);
														?>
														-> Initier la recherche <- </a>
													</a>
											</p>
										</div>
									</div>
								</div>

							</div>
						</div>






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