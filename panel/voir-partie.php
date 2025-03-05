<?php
session_start();
error_reporting(0);
include('include/config.php');
if (strlen($_SESSION['id'] == 0)) {
	header('location:logout.php');
} else {
	$id = intval($_GET['id']); // get value
	if (isset($_POST['update'])) {
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
		$structure = $_POST['structure'];
		$jetons = $_POST['jetons'];
		$userid = $_GET['uid'];
		$msg = mysqli_query($con, "UPDATE 'activite' SET date_depart='$date_depart',heure_depart='$heure_depart',ville='$ville',id-membre='$idmembre',places='$places',rake='$rake',buyin='$buyin',bounty='$bounty',recave='$recave' ,addon='$addon' ,ante='$ante' ,commentaire='$commentaire' ,structure='$structure' ,jetons='$jetons' where id='$userid'");
		$_SESSION['msg'] = "MAJ Ok !!";
	}
	?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<title>Admin | Voir Activite</title>
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
		<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
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
								<div class="col-sm-8">
									<h1 class="mainTitle">Admin | EDITION DES PARTIES</h1>
								</div>
								<ol class="breadcrumb">
									<li>
										<span>Admin</span>
									</li>
									<li class="active">
										<span>Edition</span>
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
													<h3 class="panel-title">Edition</h3>
												</div>
												<div class="panel-body">
													<p style="color:red;">
														<?php echo htmlentities($_SESSION['msg']); ?>
														<?php echo htmlentities($_SESSION['msg'] = ""); ?>
													</p>
													<!--		<form role="form" name="dcotorspcl" method="post" > -->
													<div class="form-group">
														<!-- <label for="exampleInputEmail1">
																Voir Individu
															</label> -->

														<?php

														$userid = $_GET['uid'];
														$query = mysqli_query($con, "SELECT * FROM `activite` WHERE `id-activite` = '$userid'");
														while ($result = mysqli_fetch_array($query)) { ?>
															<h1 class="mt-4"> Partie
																<?php echo $result['titre-activite']; ?>
															</h1>
															<div class="card mb-4">
																<form method="post">
																	<div class="card-body">
																		<table class="table table-bordered">
																			<tr>
																				<th>Date</th>
																				<td><input class="form-control" id="date_depart"
																						name="date_depart" type="date"
																						value="<?php echo $result['date_depart']; ?>"
																						required /></td>
																			</tr>
																			<tr>
																				<th>Heure</th>
																				<td><input class="form-control"
																						id="heure_depart" name="heure_depart"
																						type="time"
																						value="<?php echo $result['heure_depart']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<th>Lieu</th>
																				<td><input class="form-control" id="ville"
																						name="ville" type="text"
																						value="<?php echo $result['ville']; ?>"
																						required /></td>
																			</tr>
																			<tr>
																				<th>Adresse</th>
																				<td><input class="form-control" id="ville"
																						name="ville" type="text"
																						value="<?php echo $result['ville']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<th>Organisateur</th>
																				<td><input class="form-control" id="id-membre"
																						name="id-membre" type="text"
																						value="<?php echo $result['id-membre']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<th>Nb Joueurs Max</th>
																				<td><input class="form-control" id="places"
																						name="places" type="text"
																						value="<?php echo $result['places']; ?>"
																						required /></td>
																			</tr>
																			<tr>
																				<th>Buyin</th>
																				<td><input class="form-control" id="buyin"
																						name="buyin" type="text"
																						value="<?php echo $result['buyin']; ?>"
																						required /></td>
																			</tr>
																			<tr>
																				<th>Rake</th>
																				<td><input class="form-control" id="rake"
																						name="rake" type="text"
																						value="<?php echo $result['rake']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<th>Bounty</th>
																				<td><input class="form-control" id="bounty"
																						name="bounty" type="text"
																						value="<?php echo $result['bounty']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<th>Nb Recave</th>
																				<td><input class="form-control" id="recave"
																						name="recave" type="text"
																						value="<?php echo $result['recave']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<th>Addon</th>
																				<td><input class="form-control" id="addon"
																						name="addon" type="text"
																						value="<?php echo $result['addon']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<th>Ante</th>
																				<td><input class="form-control" id="ante"
																						name="ante" type="text"
																						value="<?php echo $result['ante']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<th>Structure</th>
																				<td><input class="form-control" id="structure"
																						name="structure" type="text"
																						value="<?php echo $result['structure']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<th>Stack</th>
																				<td><input class="form-control" id="jetons"
																						name="jetons" type="text"
																						value="<?php echo $result['jetons']; ?>">
																				</td>
																			</tr>
																			<!--       < <tr>
									   <td colspan="4" style="text-align:center ;"><button type="submit" class="btn btn-primary btn-block" name="update">Update</button></td>
								   </tr> -->
																			</tbody>
																		</table>
																	</div>
																</form>
															</div>
														<?php } ?>
													</div>

													<a href="gestion-parties.php"> ------------------------- Quitter
														------------------------- </a>
													<!--		<button type="submit" name="submit" class="btn btn-o btn-primary">
															Update
														</button> -->
													<!--	</form> -->
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-12 col-md-12">
									<div class="panel panel-white">

									</div>
								</div>
							</div>
							<!-- form -->
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
																				<li class="breadcrumb-item"><a
																						href="dashboard.php">Dashboard</a>
																				</li>
																				<li class="breadcrumb-item active">Joueurs
																					Inscrits</li>
																			</ol>
																			<div class="card mb-4">
																				<!--   <div class="card-header">
																					<i class="fas fa-table me-1"></i>
																					Registered User Details
																				</div> -->
																				<div class="card-body">
																					<table id="datatablesSimple">
																						<thead>
																							<tr>
																								<th>Pseudo</th>
																								<th>Date </th>
																								<th>Commentaire</th>
																								<th>Supprimer</th>
																							</tr>
																						</thead>
																						<tfoot>
																							<tr>
																								<th>Pseudo</th>
																								<th>Date </th>
																								<th>Commentaire</th>
																								<th>Supprimer</th>
																							</tr>
																						</tfoot>
																						<tbody>
																							<?php $ret = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-activite` = '$userid'");
																							$cnt = 1;

																							while ($row = mysqli_fetch_array($ret)) { ?>
																								<?php
																								$id2 = $row['id-membre'];
																								echo $id;
																								$sql2 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` = '$id'");
																								while ($row2 = mysqli_fetch_array($sql2)) { ?>
																									<tr>
																										<td>
																											<?php echo $row2['pseudo']; ?>
																										</td>
																										<td>
																											<?php echo $row2['posting_date']; ?>
																										</td>
																										<td>
																											<?php echo $row['option']; ?>
																										</td>
																									<?php } ?>
																									<td>
																										<!--<a href="edit-competences.php?id=<?php echo $row['id']; ?>" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit"><i class="fa fa-pencil"></i></a>
																									<i class="fas fa-edit"></i></a> -->
																										<a href="add-competences.php?id=<?php echo $row['id'] ?>&del=deleteind"
																											onClick="return confirm('Are you sure you want to delete?')"
																											class="btn btn-transparent btn-xs tooltips"
																											tooltip-placement="top"
																											tooltip="Remove"><i
																												class="fa fa-times fa fa-white"></i></a>
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
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"
			crossorigin="anonymous"></script>
		<script src="../js/scripts.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
		<script src="../js/datatables-simple-demo.js"></script>
	</body>

	</html>
<?php } ?>