<?php
	session_start();
	include_once('/panel/include/config.php');
	error_reporting(0);

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	include_once('include/config.php');
	//$modifmembre = 0;
	$ret = mysqli_query($con, "SELECT * FROM `activite` WHERE 1");
	while ($row = mysqli_fetch_array($ret)) {
		$pointeur = $row["id-activite"];
		$pointeur_ordre = 0;
		$ret2 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = '$pointeur' AND `option` LIKE 'Reservation') OR (`id-activite` = '$pointeur' AND `option` LIKE 'Option')
			OR (`id-activite` = '$pointeur' AND `option` LIKE 'Inscrit') OR (`id-activite` = '$pointeur' AND `option` LIKE 'Confirme') OR (`id-activite` = '$pointeur' AND `option` LIKE 'Elimine') ORDER BY `ordre` ASC");
		while ($row2 = mysqli_fetch_array($ret2)) {
			$id = $row2['id-participation'];
			$pointeur_ordre = $pointeur_ordre + 1;
		}
	}

	require 'vendor/autoload.php';
	if (strlen($_SESSION['id'] == 0)) {
		header('location:logout.php');
		exit;
	} else {
		$id = intval($_GET['uid']); 
		
		if (isset($_POST['submit'])) {
			// Sanitize and validate inputs
			$titre_activite = mysqli_real_escape_string($con, $_POST['titre-activite']);
			$date_depart = mysqli_real_escape_string($con, $_POST['date_depart']);			
			// Validate time format
			$heure_depart = mysqli_real_escape_string($con, $_POST['heure_depart']);
			//echo "*".$heure_depart."*";
			if (!empty($heure_depart)) {
				$time = DateTime::createFromFormat('H:i', $heure_depart);
				//echo "-".$time."-";
				if (1) {
					//echo "oui";
					//$heure_depart = $time;
				//	$heure_depart = $time->format('H:i');
				} else {
					//echo "non";
					$heure_depart = '00:01';
				}
			}; 
			//echo "/".$heure_depart."/";
			$ville = mysqli_real_escape_string($con, $_POST['ville']);
			$places = intval($_POST['places']);
			$rake = floatval($_POST['rake']);
			$buyin = floatval($_POST['buyin']);
			$bounty = floatval($_POST['bounty']); 
			$recave = intval($_POST['recave']);
			$addon = intval($_POST['addon']);  // Only define once
			$ante = floatval($_POST['ante']);
			$idmembre = intval($_POST['id-membre']);
			$commentaire = mysqli_real_escape_string($con, $_POST['commentaire']);
			$structure = intval($_POST['id-structure']);
			$jetons = intval($_POST['jetons']);
			$bonus = intval($_POST['bonus']);
			$lng = floatval($_POST['lng']);
			$lat = floatval($_POST['lat']);
			$nb_tables = intval($_POST['nb-tables']);
			$idmembresession = intval($_SESSION['id']);
			$id_org = intval($_POST['id_org']);

			// Get organizer info
			$orga = mysqli_query($con, "SELECT * FROM `activite` WHERE `id-activite` = '$id'");
			$resorga = mysqli_fetch_array($orga);
		//	$id_org = $resorga["id-membre"];
			
			if (($idmembresession == $idmembre) || ($idmembresession == 265)) {
				if ($idmembre == $id_org) {
					// Update without changing organizer
					$sql = "UPDATE `activite` SET 
						`titre-activite` = '$titre_activite',
						`date_depart` = '$date_depart',
						`heure_depart` = '$heure_depart',
						`ville` = '$ville',
						`places` = '$places',
						`nb-tables` = '$nb_tables',
						`commentaire` = '$commentaire',
						`buyin` = '$buyin',
						`rake` = '$rake',
						`bounty` = '$bounty',
						`jetons` = '$jetons',
						`recave` = '$recave',
						`addon` = '$addon',
						`ante` = '$ante',
						`bonus` = '$bonus',
						`lng` = '$lng',
						`lat` = '$lat' 
						WHERE `id-activite` = '$id'";
				} else {
					// Update including organizer change
					$sql = "UPDATE `activite` SET 
						`id-membre` = '$idmembre',
						`titre-activite` = '$titre_activite',
						`date_depart` = '$date_depart', 
						`heure_depart` = '$heure_depart',
						`ville` = '$ville',
						`places` = '$places',
						`nb-tables` = '$nb_tables',
						`commentaire` = '$commentaire',
						`buyin` = '$buyin',
						`rake` = '$rake', 
						`bounty` = '$bounty',
						`jetons` = '$jetons',
						`recave` = '$recave',
						`addon` = '$addon',
						`ante` = '$ante',
						`bonus` = '$bonus',
						`lng` = '$lng',
						`lat` = '$lat'
						WHERE `id-activite` = '$id'";
				}
				
				$msg = mysqli_query($con, $sql);
				if (!$msg) {
					// Handle error
					echo "Error updating record: " . mysqli_error($con);
				}
			}
		}
		
		if (isset($_POST['submitinsmanu'])) {
			$lois = $_POST['lois'];
			// echo $lois;
			$activi = $_SESSION['id'];
			$activi = $_POST['activi'];
			$sql0 = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-membre` = '$lois' AND `id-activite` = '$activi' ");
			$rowcount = mysqli_num_rows($sql0);
			if (1) {
				$ordre = "0";
				$sql1 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = '$activi' AND `option` LIKE 'Reservation') OR (`id-activite` = '$activi' AND `option` LIKE 'Option') OR (`id-activite` = '$activi' AND `option` LIKE 'Inscrit') ");
				$ordre = mysqli_num_rows($sql1);
				$intordre = (int) $ordre;
				$intordre = $intordre + 1;
				$ordre = (string) $intordre;
				// echo $lois;
				$sql4 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  $lois ");
				// echo $lois;
				while ($row4 = mysqli_fetch_array($sql4)){ 
				$nom_mem = $row4['pseudo'];
				// echo "***".$nom_mem."***" ;
				};
				$sql2 = mysqli_query($con, "INSERT INTO `participation` (`id-membre`, `id-activite`, `ordre`, `nom-membre` , `id-siege` , `id-table`) VALUES ( '$lois', '$activi','$ordre','$nom_mem' ,0,0)");
				$sql4 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  $lois ");
				while ($result = mysqli_fetch_array($sql4)) {
					$nomm = $result['pseudo'];
					$mdpm = $result['password'];
					$email = $result['email'];
					$num_membre = $result['id-membre'];
					$num_activite = $activi;
					$reset = $result['CodeV'];
				}
				if (strlen($email == 0)) {
					$email = "franck.wenger@wanadoo.fr";
					$num_membre = "265";
					$reset = "";
				};
				// echo "--".$email."--";
				$mail = new PHPMailer(true);
				try {
					$mail->SMTPDebug = 0;
					$mail->isSMTP();
					$mail->Host = 'smtp.free.fr';
					$mail->SMTPAuth = true;
					$mail->Username = 'contact.poker31@free.fr';
					$mail->Password = 'Kookies7*fb';
					$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
					$mail->Port = 465;
					$mail->setFrom('contact.poker31@free.fr', 'contact.poker31@free.fr');
					$mail->addAddress($email, $nomm);
					$mail->addReplyTo('contact.poker31@free.fr', 'contact.poker31@free.fr');
					//$mail->addCC('wenger.franck@gmail.com');
					$mail->addBCC('franck.wenger@wanadoo.fr');
					$mail->isHTML(true);
					$mail->Subject = 'AR Inscription www.viendez.com';
					$mail->Body = '<p>Votre inscription est prise en Compte</p><p>Votre nom d inscription est : ' . $nomm . '</p><p>Votre inscription est prise en Compte</p><p>Votre mot de passe est : ' . $mdpm . '</p><p> Reset mot de passe : <a href="http://viendez.com/reg/change-Password.php?Reset=' . $reset . '">"http://viendez.com/reg/change-Password.php?Reset=' . $reset . '"</a></p>' . '<p> Lien activité : <b><a href="http://viendez.com/panel/voir-activite.php?uid=' . $num_activite . '">"http://viendez.com/panel/voir-activite.php?uid=' . $num_activite . '"</a></p>';
					$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
					$mail->send();
				} catch (Exception $e) {
				}
			}
			?>
			<script type="text/javascript">
				window.location.replace("/panel/voir-activite.php?uid=<?php echo $activi ?>");
			</script>
			<?php
		}

		if (isset($_POST['submit-ins'])) {
			$lois = $_SESSION['id'];
			$activi = $id;
			$sql0 = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-membre` = '$lois' AND `id-activite` = '$activi' ");
			$rowcount = mysqli_num_rows($sql0);
			if (1) {
				$ordre = "0";
				$sql1 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = '$activi' AND `option` LIKE 'Reservation') OR (`id-activite` = '$activi' AND `option` LIKE 'Option') OR (`id-activite` = '$activi' AND `option` LIKE 'Inscrit') ");
				$ordre = mysqli_num_rows($sql1);
				$intordre = (int) $ordre;
				$intordre = $intordre + 1;
				$ordre = (string) $intordre;
				$sql4 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  $lois ");
				// echo $lois;
				while ($row4 = mysqli_fetch_array($sql4)){ 
				$nom_mem = $row4['pseudo'];
				// echo "***".$nom_mem."***" ;
				};
				$sql2 = mysqli_query($con, "INSERT INTO `participation` (`id-membre`, `id-activite`, `ordre`, `nom-membre` , `id-siege` , `id-table`) VALUES ( '$lois', '$activi','$ordre','$nom_mem' ,0,0)");
				$sql4 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  $lois ");
				while ($result = mysqli_fetch_array($sql4)) {
					$nomm = $result['pseudo'];
					$mdpm = $result['password'];
					$email = $result['email'];
					$num_membre = $result['id-membre'];
					$num_activite = $activi;
					$reset = $result['CodeV'];
				}
				if (strlen($email == 0)) {
					$email = "franck.wenger@wanadoo.fr";
					$num_membre = "265";
					$reset = "";
				};
				// echo "--".$email."--";
				$mail = new PHPMailer(true);
				try {
					$mail->SMTPDebug = 0;
					$mail->isSMTP();
					$mail->Host = 'smtp.free.fr';
					$mail->SMTPAuth = true;
					$mail->Username = 'contact.poker31@free.fr';
					$mail->Password = 'Kookies7*fb';
					$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
					$mail->Port = 465;
					$mail->setFrom('contact.poker31@free.fr', 'contact.poker31@free.fr');
					$mail->addAddress($email, $nomm);
					$mail->addReplyTo('contact.poker31@free.fr', 'contact.poker31@free.fr');
					//$mail->addCC('wenger.franck@gmail.com');
					$mail->addBCC('franck.wenger@wanadoo.fr');
					$mail->isHTML(true);
					$mail->Subject = 'AR Inscription www.viendez.com';
					$mail->Body = '<p>Votre inscription est prise en Compte</p><p>Votre nom d inscription est : ' . $nomm . '</p><p>Votre inscription est prise en Compte</p><p>Votre mot de passe est : ' . $mdpm . '</p><p> Reset mot de passe : <a href="http://viendez.com/reg/change-Password.php?Reset=' . $reset . '">"http://viendez.com/reg/change-Password.php?Reset=' . $reset . '"</a></p>' . '<p> Lien activité : <b><a href="http://viendez.com/panel/voir-activite.php?uid=' . $num_activite . '">"http://viendez.com/panel/voir-activite.php?uid=' . $num_activite . '"</a></p>';
					$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
					$mail->send();
				} catch (Exception $e) {
				}
			}
			?>
			<script type="text/javascript">
				window.location.replace("/panel/voir-activite.php?uid=<?php echo $num_activite ?>");
			</script>
			<?php
		}

		if (($_POST['submitpl'])) {
			$particip = $_POST['submitpl'];
			header('location:voir-participation.php?id=' . $particip);
		}

		if (($_POST['submitplb'])) {
			$sql = mysqli_query($con, "UPDATE `participation` SET `id-membre`='$id_membre',`id-membre-vainqueur`='$id_membre_vainqueur',`id-activite`='$id_activite',`id-siege`='$id_siege',`id-table`='$id_table',`id-challenge`='$id_challenge',`option`='$option',`ordre`='$ordre',`valide`='$valide',`commentaire`='$commentaire',`classement`='$classement',`points`='$points',`gain`='$gain',`ds`= CURRENT_TIMESTAMP,`ip-ins`='1',`ip-mod`='2',`ip-sup`='3' WHERE `participation`.`id-participation` = '$id'");
		}

		if (($_POST['xxx'])) {
			$lois = $_SESSION['id'];
			$activi = $id;
			$sql2 = mysqli_query($con, "INSERT INTO `participation` (`id-membre`, `id-membre-vainqueur`, `id-activite`, `id-siege`, `id-table`, `id-challenge`, `option`, `ordre`, `valide`, `commentaire`, `classement`, `points`, `gain`, `ds`, `ip-ins`, `ip-mod`, `ip-sup`, `bounty`) VALUES ( '$lois', '', '$activi', '', '', '', 'Reservation', '$ordre', 'Actif', NULL, '1', '0', '0', CURRENT_TIMESTAMP, '', '', '', '0')");
		}

		if ($_POST['btn-info']) {
			?>
			<script type="text/javascript" language="javascript">
				afficher('infos');
			</script>
			<?php
		}

		if ($_POST['pause']) {
			$pau = $_POST['pause'];
			$_SESSION['pause' . $id] = $pau;
			?>
			<script type="text/javascript">
				window.location.replace("/panel/voir-blindes.php?uid=<?php echo $id ?>");
			</script>
			<?php
		}

		if (($_POST['submit2'])) {
			$compet = $_POST['compet'];
			$sql2 = mysqli_query($con, "INSERT INTO `competences-individu` (`id-indiv`, `id-comp`) VALUES ('$id', '$compet')");
		}

		if (isset($_POST['submit-desins'])) {
			$lois = $_SESSION['id'];
			$activi = $id;
			if ($option == 'Annule') {
				$id_table = '';
				$id_siege = '';
			} else
				$sql0 = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-membre` = '$lois' AND `id-activite` = '$activi' ");
			$rowcount = mysqli_num_rows($sql0);
			if ($rowcount == '1') {
				$sql10 = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-membre` = '$lois' AND `id-activite` = '$activi' ");
				$part = mysqli_fetch_array($sql10);
				$id_part = $part['id-participation'];
				$sql2 = mysqli_query($con, "UPDATE `participation` SET `id-membre`='$lois',`position`='0',`id-table`='1',`id-siege`='0',`option`='Annule',`ds`= CURRENT_TIMESTAMP WHERE `participation`.`id-participation` = '919'");
			}
			?>
			<script type="text/javascript">
				window.location.replace("/panel/voir-activite.php?uid=<?php echo $num_activite ?>");
			</script>
			<?php
		}

		if (isset($_POST['submit-desinsbis'])) {
			$lois = $_SESSION['id'];
			$activi = $id;
			if ($option == 'Annule') {
				$id_table = '';
				$id_siege = '';
			} else
				$sql0 = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-membre` = '$lois' AND `id-activite` = '$activi' ");
			$rowcount = mysqli_num_rows($sql0);
			if ($rowcount == '1') {
				$sql10 = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-membre` = '$lois' AND `id-activite` = '$activi' ");
				$part = mysqli_fetch_array($sql10);
				$id_part = $part['id-participation'];
				$sql2 = mysqli_query($con, "UPDATE `participation` SET `id-membre`='$lois',`position`='0',`id-table`='1',`id-siege`='0',`option`='Annule',`ds`= CURRENT_TIMESTAMP WHERE `participation`.`id-participation` = '919'");
			}
			?>
			<script type="text/javascript">
				window.location.replace("/panel/voir-activite.php?uid=<?php echo $num_activite ?>");
			</script>
			<?php
		}
		?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-16" />
	<meta http-equiv="refresh" content="120">
	<title>Admin | Edition Membre</title>
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
	<link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
	<link rel="stylesheet" href="assets/css/styles.css">
	<link rel="stylesheet" href="assets/css/plugins.css">
	<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
	<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/luxon/2.3.1/luxon.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#example').DataTable({
				order: [
					[0, 'asc']
				],
				pageLength: 8,
				language: {
					url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
				}
			});
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#example2').DataTable({
				pageLength: 8,
				language: {
					url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
				}
			});
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#example3').DataTable({
				pageLength: 8,
				language: {
					url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
				}
			});
		});
	</script>
	<link rel="stylesheet" href="css/mes-styles.css">
	<link rel="stylesheet" href="css/les-styles.css">

	<script>
		responsiveVoice.setDefaultVoice("French Female")
	</script>
	<style>
		.square-box {
			position: absolute;
			width: 87%;
			height: 62%;
			overflow: hidden;
			background-size: 100% 100%;
			background-image: url('/panel/images/table-empire-10j.jpg');
			background-repeat: no-repeat;
			opacity: 1;
			left: 0;
			right: 0;
			top: -100px;
			bottom: 0;
			border-radius: 200px;
			border: 1px solid white;
		}

		.info1 {
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

		.info2 {
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
			width: 100%;
			height: 100%;
			font-size: 2vw;
		}

		.info2-content {
			position: absolute;
			top: 82%;
			left: 10%;
			color: green;
			width: 100%;
			height: 100%;
			font-size: 2vw;
		}

		.info3-content {
			position: absolute;
			top: 87%;
			left: 10%;
			color: black;
			width: 100%;
			height: 100%;
			font-size: 2vw;
		}

		.info4-content {
			position: absolute;
			top: 92%;
			left: 10%;
			color: red;
			width: 100%;
			height: 100%;
			font-size: 2vw;
		}

		.info5-content {
			position: absolute;
			top: 97%;
			left: 10%;
			color: grey;
			width: 100%;
			height: 100%;
			font-size: 2vw;
		}

		.info6-content {
			position: absolute;
			top: 82%;
			left: 22%;
			color: red;
			width: 100%;
			height: 100%;
			font-size: 2vw;
		}

		.info7-content {
			position: absolute;
			top: 88%;
			left: 34%;
			color: green;
			width: 100%;
			height: 100%;
			font-size: 2vw;
		}

		.info8-content {
			position: absolute;
			top: 94%;
			left: 5%;
			color: green;
			width: 90%;
			height: 100%;
			font-size: 1.5vw;
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
			width: 100%;
			height: 100%;
			font-size: 2.25vw;
		}

		.square2-content {
			position: absolute;
			top: 53%;
			left: 28%;
			color: white;
			width: 100%;
			height: 100%;
			font-size: 2.25vw;
		}

		.place-content {
			position: relative;
			top: 33px;
			left: -60px;
			color: white;
			width: 100%;
			height: 100%;
			font-size: 2.25vw;
		}

		.place2-content {
			position: relative;
			top: 33px;
			left: 80px;
			color: white;
			width: 100%;
			height: 100%;
			font-size: 2.25vw;
		}

		.place3-content {
			position: absolute;
			top: 120px;
			left: 140px;
			color: white;
			width: 80%;
			height: 100%;
			font-size: 1.5vw;
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
			left: 50%;
			-webkit-transform: translatex(-50%) translatey(-50%);
			transform: translatex(-50%) translatey(-50%);
		}

		.players .player.player-1p {
			top: 20%;
			left: 49%;
			color: white;
			-webkit-transform: translatex(-50%) translatey(-50%);
			transform: translatex(-50%) translatey(-50%);
		}

		.players .player.player-2 {
			top: 14%;
			left: 73%;
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
			background-color: lightcoral;
			border-radius: 100%;
			position: relative;
			top: 5px;
			z-index: 1;
		}

		#main {
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

		.p1p {
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			color: #666;
			font-weight: bold;
			font-size: 17px;
		}

		.p1 {
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			color: #666;
			font-weight: bold;
			font-size: 17px;
		}

		.p2 {
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			color: #fff;
			font-weight: bold;
			font-size: 17px;
			opacity: 0.95;
		}

		.p3 {
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			color: #fff;
			font-weight: bold;
			font-size: 2.5vw;
			opacity: 0.95;
		}

		.p4 {
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			color: #fff;
			font-weight: bold;
			font-size: 2.5vw;
			opacity: 0.9;
		}

		.p5 {
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			color: #fff;
			font-weight: bold;
			font-size: 17px;
		}

		.p6 {
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			color: #fff;
			font-weight: bold;
			font-size: 2.5vw;
			opacity: 0.90;
		}

		.p7 {
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			color: #fff;
			font-weight: bold;
			font-size: 2.5vw;
			opacity: 0.95;
		}

		.p8 {
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			color: #fff;
			font-weight: bold;
			font-size: 17px;
		}

		.p9 {
			display: flex;
			align-items: center;
			justify-content: center;
			text-align: center;
			color: #fff;
			font-weight: bold;
			font-size: 17px;
		}

		.p10 {
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
				success: function(data) {
					$("#email-availability-status").html(data);
					$("#loaderIcon").hide();
				},
				error: function() {}
			});
		}
	</script>
	<script>
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
	</script>
</head>

<body>
	<div id="app">
		<?php include('include/sidebar.php'); ?>
		<div class="app-content">
			<?php include('include/header.php'); ?>
			<div class="main-content">
				<div class="wrap-content container" id="container">
					<section id="page-title">
					</section>
					<div id="conteneur">
						<div id="contenu">
							<div id="auCentre">
								<?php
								$id = intval($_GET['uid']);
								$reqnbt = mysqli_query($con, "SELECT * FROM `activite` WHERE `id-activite` = '$id' ");
								$res = mysqli_fetch_array($reqnbt);
								$nbt = $res["nb-tables"];

								if ($nbt == '1') { ?>
									<div id="bMenu">
										<a href="#" id="infos" class="btnnav" onmouseover="afficher1('infos')">Infos</a>
										<a href="#" id="inscrits" class="btnnav" onmouseover="afficher1('inscrits')">Joueurs</a>
										<a href="#" id="t1" class="btnnav" onmouseover="afficher1('t1')">Table 1</a>
										<a href="voir-blindes.php?uid=<?php echo $id; ?>" id="blindes" class="btnnav" onclick="window.location=this.href">Timer</a>
										<a href="sieges.php?ac=<?php echo $id; ?>" id="blindes" class="btnnav" onclick="return confirm('Attention Réaffectation des Sieges ! ');">Place (re)</a>
										<a href="creation-blindes.php?zero=1&act=<?php echo $id; ?>&sou=/panel/voir-activite.php" id="blindes" class="btnnav" onclick="return confirm('Attention Reset Horloge ! ');">Start (re)</a>
									</div>
								<?php };
								if ($nbt == '2') { ?>
									<div id="bMenu">
										<a href="#" id="infos" class="btnnav" onmouseover="afficher2('infos')">Infos</a>
										<a href="#" id="inscrits" class="btnnav" onmouseover="afficher2('inscrits')">Joueurs</a>
										<a href="#" id="t1" class="btnnav" onmouseover="afficher2('t1')">Table 1</a>
										<a href="#" id="t2" class="btnnav" onmouseover="afficher2('t2')">Table 2</a>
										<a href="voir-blindes.php?uid=<?php echo $id; ?>" id="blindes" class="btnnav" onclick="window.location=this.href">Timer</a>
										<a href="sieges.php?ac=<?php echo $id; ?>" id="blindes" class="btnnav" onclick="return confirm('Attention Réaffectation des Sieges ! ');">Place (re)</a>
										<a href="creation-blindes.php?zero=1&act=<?php echo $id; ?>&sou=/panel/voir-activite.php" id="blindes" class="btnnav" onclick="return confirm('Attention Reset Horloge ! ');">Start (re)</a>
									</div>
								<?php }
								;

								if ($nbt == '3') { ?>
									<div id="bMenu">
										<a href="#" id="infos" class="btnnav" onmouseover="afficher3('infos')">Infos</a>
										<a href="#" id="inscrits" class="btnnav" onmouseover="afficher3('inscrits')">Joueurs</a>
										<a href="#" id="t1" class="btnnav" onmouseover="afficher3('t1')">Table 1</a>
										<a href="#" id="t2" class="btnnav" onmouseover="afficher3('t2')">Table 2</a>
										<a href="#" id="t3" class="btnnav" onmouseover="afficher3('t3')">Table 3</a>
										<a href="voir-blindes.php?uid=<?php echo $id; ?>" id="blindes" class="btnnav" onclick="window.location=this.href">Timer</a>
										<a href="sieges.php?ac=<?php echo $id; ?>" id="blindes" class="btnnav" onclick="return confirm('Attention Réaffectation des Sieges ! ');">Place (re)</a>
										<a href="creation-blindes.php?act=<?php echo $id; ?>&sou=/panel/voir-activite.php" id="blindes" class="btnnav" onclick="return confirm('Attention Reset Horloge ! ');">Start (re)</a>
									</div>
								</div>
								<?php }
								;

								if ($nbt == '4') { ?>
									<div id="bMenu">
										<a href="#" id="infos" class="btnnav" onmouseover="afficher4('infos')">Infos</a>
										<a href="#" id="inscrits" class="btnnav" onmouseover="afficher4('inscrits')">Joueurs</a>
										<a href="#" id="t1" class="btnnav" onmouseover="afficher4('t1')">Table 1</a>
										<a href="#" id="t2" class="btnnav" onmouseover="afficher4('t2')">Table 2</a>
										<a href="#" id="t3" class="btnnav" onmouseover="afficher4('t3')">Table 3</a>
										<a href="#" id="t4" class="btnnav" onmouseover="afficher4('t4')">Table 4</a>
										<a href="voir-blindes.php?uid=<?php echo $id; ?>" id="blindes" class="btnnav" onclick="window.location=this.href">Timer</a>
										<a href="sieges.php?ac=<?php echo $id; ?>" id="blindes" class="btnnav" onclick="return confirm('Attention Réaffectation des Sieges ! ');">Place (re)</a>
										<a href="creation-blindes.php?act=<?php echo $id; ?>&sou=/panel/voir-activite.php" id="blindes" class="btnnav" onclick="return confirm('Attention Reset Horloge ! ');">Start (re)</a>
									</div>
								<?php }; ?>
								<div id="bSection">
									<div id="infosE">
										<script src="voice.js?key=ncsRFoXJ"></script>
										<div class="wrap-content container" id="container">
											<div class="container-fluid bbg-pink">
												<div class="col-md-12">
													<div class="row margin-top-30">
														<div class="panel-wwhite">
															<div class="ppanel-body">
																<div class="form-group">
																	<?php
																	$id = intval($_GET['uid']);
																	$_SESSION['pause' . '$id'] = 1;
																	$sql = mysqli_query($con, "SELECT * FROM `activite` WHERE `id-activite` =  '$id'");
																	while ($row = mysqli_fetch_array($sql)) {
																		$id_org = $row["id-membre"];
																		echo "++".$id_org."++".$old_org."++";
																		$sql2 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  '$id_org'");
																		while ($row2 = mysqli_fetch_array($sql2)) {
																			$nom_org = $row2['pseudo'];
																			echo "**++".$id_org."++**";
																		} ?>
																		<table class="table table-bordered">
																			<tr>
																				<td rowspan="2"><img src="images/<?php echo $row['photo'] ?>" width="85" height="85" style="text-align:center">
																					<form id="image_upload_form" enctype="multipart/form-data" action="upload-photo-activite.php?editid=<?php echo $id ?>" method="post" class="change-pic">
																						<input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
																						<div>
																							<input type="file" class="fa fa-camera" id="file" name="fileToUpload" style="display:none;" /><input type="button" onClick="fileToUpload.click();" value="Modifier" />
																							<i class="fa fa-camera"></i>
																						</div>
																						<script type="text/javascript">
																							document.getElementById("file")
																								.onchange = function() {
																									document.getElementById(
																											"image_upload_form")
																										.submit();
																								};
																						</script>
																					</form>
																				</td>
																				<form method="post">
																					<td colspan="3"><input class="form-control" id="titre-activite" name="titre-activite" type="text" style="text-align:center; font-size:22px" value="<?php echo $row['titre-activite']; ?>">
																					</td>
																			</tr>
																			<tr>
																				<td style="text-align:center ; display:none">
																					<button type="submit" name="submit" id="submit" class="btn btn-oo btn-primary">
																						Mise à jour</button>
																				</td>
																				<td style="text-align:center ;">
																					<button type="submit" id="submit-ins" class="btn btn-primaryg btn-block" name="submit-ins">S'inscrire
																					</button>
																				</td>
																				<td style="text-align:center ;">
																					<button type="submit" class="btn btn-primary btn-block" name="submit">Modifier</button>
																				</td>
																				<td style="text-align:center ;">
																					<button type="submit" class="btn btn-primary-rouge btn-block" name="submit-desins">Se
																						désinscrire</button>
																				</td>
																			</tr>
																			</tr>

																			<tr>
																				<th style="color: #ffffff !important;">Date</th>
																				<td><input class="form-control" id="date_depart" name="date_depart" type="timestamp" value="<?php echo $row['date_depart']; ?>">
																				</td>
																				<th style="color: #ffffff !important;"><a href="creation-blindes.php?act=<?php echo $row['id-activite']; ?>&sou=/panel/voir-activite.php">Heure</a>
																				</th>
																				<td><input class="fform-control" id="heure_depart" name="heure_depart" type="text" value="<?php echo $row['heure_depart']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<th style="color: #ffffff !important;"><a href="voir-membre.php?id=<?php echo $row['id-membre']; ?>">Orga:
																					<?php echo "" . $nom_org . ""; $old_org=$row['id-membre']; ?></a>
																				</th>
																				<td><?php
																					$membres = mysqli_query($con, "SELECT `id-membre`,`pseudo` FROM `membres` ORDER BY `pseudo` ASC");
																					echo "<align='center' class='rougesurblanc'><select name=id-membre><option value=$monmembre>--> Changer Organisateur IcI <--";
																					while ($choix = mysqli_fetch_assoc($membres)) {
																						$listepseudo = $choix['pseudo'];
																						$modifmembre = "1";
																						echo "<option value={$choix["id-membre"]}>{$choix["pseudo"]}\n";
																					}
																					$modifmembre = "1";
																					echo "</select>";
																					?>
																				</td>
																				<th style="color: #ffffff !important;">ville</th>
																				<td>
																					<input class="form-control" id="ville" name="ville" type="text" value="<?php echo $row['ville']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<th style="color: #ffffff !important;">lng</th>
																				<td><input class="form-control" id="lng" name="lng" type="text" value="<?php echo $row['lng']; ?>">
																				</td>
																				<th style="color: #ffffff !important;">lat</th>
																				<td><input class="form-control" id="lat" name="lat" type="text" value="<?php echo $row['lat']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<th style="color: #ffffff !important;">places</th>
																				<td><input class="form-control" id="places" name="places" type="text" value="<?php echo $row['places']; ?>">
																				</td>
																				<script type="text/javascript">
																					document.getElementById("places").onchange =
																						function() {
																							document.getElementById("submit")
																								.submit();
																						};
																				</script>
																				<th style="color: #ffffff !important;">nb tables</th>
																				<td><input class="form-control" id="nb-tables" name="nb-tables" type="text" value="<?php echo $row['nb-tables']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<th style="color: #ffffff !important;">buyin</th>
																				<td><input class="form-control" id="pasbuyinsword" name="buyin" type="text" value="<?php echo $row['buyin']; ?>">
																				</td>
																				<th style="color: #ffffff !important;">rake</th>
																				<td><input class="form-control" id="rake" name="rake" type="text" value="<?php echo $row['rake']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<th style="color: #ffffff !important;">recave</th>
																				<td><input class="form-control" id="recave" name="recave" type="text" value="<?php echo $row['recave']; ?>">
																				</td>
																				<th style="color: #ffffff !important;">addon</th>
																				<td><input class="form-control" id="addon" name="addon" type="text" value="<?php echo $row['addon']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<th style="color: #ffffff !important;">bounty</th>
																				<td><input class="form-control" id="bounty" name="bounty" type="text" value="<?php echo $row['bounty']; ?>">
																				</td>
																				<th style="color: #ffffff !important;">ante</th>
																				<td><input class="form-control" id="ante" name="ante" type="text" value="<?php echo $row['ante']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<th style="color: #ffffff !important;">jetons</th>
																				<td><input class="form-control" id="jetons" name="jetons" type="text" value="<?php echo $row['jetons']; ?>">
																				</td>
																				<th style="color: #ffffff !important;">dont bonus</th>
																				<td><input class="form-control" id="bonus" name="bonus" type="text" value="<?php echo $row['bonus']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<th style="color: #ffffff !important;">structure</th>
																				<td><input class="form-control" id="structure" name="structure" type="text" value="<?php echo $row['id-structure']; ?>">
																				</td>
																				<th style="color: #ffffff !important;">commentaire</th>
																				<td><input class="form-control" id="commentaire" name="commentaire" type="text" value="<?php echo $row['commentaire']; ?>">
																				</td>
																			</tr>
																			<tr>
																				<td style="display:none" ; colspan="2">
																					<select name="lois" value="lois" class="form-control" required="false">
																						<option value="<?php echo htmlentities($_SESSION['id']); ?>">
																							<?php echo htmlentities($_SESSION['id']); ?>
																						</option>
																					</select>
																				</td>
																				<td style="display:none" ; colspan="2">
																					<select name="activi" value="activi" class="form-control" required="false">
																						<option value="<?php echo htmlentities($row['id-activite']); ?>">
																							<?php echo htmlentities($row['id-activite']); ?>
																						</option>
																					</select>
																				</td>
																				<td style="display:none;" style="text-align:center ;">
																					<button type="submit" class="btn btn-primary btn-block" name="submit">Mise à
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
										<script>
											responsiveVoice.setDefaultVoice("French Female")
										</script>
										<script>
											var audio3 = new Audio("popa.mp3");
											var audio2 = new Audio("http://glpjt.s3.amazonaws.com/so/av/a12.mp3");
											var audio = new Audio(
												"https://s3.amazonaws.com/audio-experiments/examples/elon_mono.wav");

											function playAudio() {
												audio.play();
											}

											function pauseAudio() {
												audio.play();
											}

											function cancelAudio() {
												audio.pause();
												audio.currentTime = 0;
											}
										</script>
										<div class="ccontainer-fluid ccontainer-fullw bg-dark-bricky " style="background-color:grey;opacity:1">
											<?php
											$rowcountocc = "0";
											$scru = "0";
											$sqlv = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-activite` = '$id' ");
											$rowcountv = mysqli_num_rows($sqlv);
											while ((int) $rowcountocc < 1 and $rowcountv > 0) {
												(int) $scru++;
												$sqla = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = '$id' AND `id-table` = '$scru' ) LIMIT 1 ");
												$rowcountocc = mysqli_num_rows($sqla);
											}
											;
											(int) $tableaff = (int) $scru;
											$cnt = 0;
											unset($idmembre);
											unset($position);
											unset($idparticipation);
											unset($siege);
											unset($option);
											unset($pseudo);
											$sql = mysqli_query($con, "SELECT  `id-membre`,`position`,`id-participation`,`id-siege`,`option` FROM `participation` WHERE (`id-activite` = '$id' AND `id-table` = '$tableaff' )  ORDER BY `id-siege` ");
											$nb_lignes = mysqli_num_rows($sql);
											while ($row = mysqli_fetch_array($sql)) {
												$cnt = $cnt + 1;
												if ($row['id-siege'] <> $cnt)
													$cnt = $cnt + 1;
												if ($row['id-siege'] <> $cnt)
													$cnt = $cnt + 1;
												if ($row['id-siege'] <> $cnt)
													$cnt = $cnt + 1;
												if ($row['id-siege'] <> $cnt)
													$cnt = $cnt + 1;
												if ($row['id-siege'] <> $cnt)
													$cnt = $cnt + 1;
												if ($row['id-siege'] <> $cnt)
													$cnt = $cnt + 1;
												if ($row['id-siege'] <> $cnt)
													$cnt = $cnt + 1;
												if ($row['id-siege'] <> $cnt)
													$cnt = $cnt + 1;
												if ($row['id-siege'] <> $cnt)
													$cnt = $cnt + 1;
												$idmembre[$cnt] = $row['id-membre'];
												$position[$cnt] = $row['position'];
												$idparticipation[$cnt] = $row['id-participation'];
												$siege[$cnt] = $row['id-siege'];
												$option[$cnt] = $row['option'];
												$sql2 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  '$idmembre[$cnt]'");
												while ($row2 = mysqli_fetch_array($sql2)) {
													$pseudo[$cnt] = $row2['pseudo'];
												}
											}
											;
											?>

											<div id="main">
												<div class="players">
													<div class="player player-1 playing" id="player1">
														<div class="avatar p1" style="background: blue ;font-size: 1.7vw">
															<form method="post">
																<?php if ($option[1] == 'Elimine')
																	$nom = 'X';
																else
																	$nom = $pseudo[1]; ?>
																<button type="submit" id='submitpl' value=<?php echo $idparticipation[1] ?> class="btbn btn-pokerblue btn-block " name="submitpl"><?php echo $nom ?>
																</button>
															</form>
														</div>
													</div>
													<div class="player player-2 playing" id="player2">
														<div class="avatar p2" style="background: red ;font-size: 1.7vw">
															<form method="post">
																<?php if ($option[2] == 'Elimine')
																	$nom = 'X';
																else
																	$nom = $pseudo[2]; ?>
																<button type="submit" id='submitpl' value=<?php echo $idparticipation[2] ?> class="btbn btn-pokerred btn-block " name="submitpl"><?php echo $nom ?>
																</button>
															</form>
														</div>
													</div>

													<div class="player player-3 playing" id="player3">
														<div class="avatar p3" style="background: black ;font-size:18px">
															<form method="post">
																<?php if ($option[3] == 'Elimine')
																	$nom = 'X';
																else
																	$nom = $pseudo[3]; ?>
																<button type="submit" id='submitpl' value=<?php echo $idparticipation[3] ?> class="btnn btn-primary-noir btn-block " name="submitpl"><?php echo $nom ?>
																</button>
															</form>
														</div>
													</div>

													<div class="player player-4 playing" id="player4">
														<div class="avatar p4" style="background: orange; font-size:18px">
															<form method="post">
																<?php if ($option[4] == 'Elimine')
																	$nom = 'X';
																else
																	$nom = $pseudo[4]; ?>
																<button type="submit" id='submitpl' value=<?php echo $idparticipation[4] ?> class="btnn btn-primary-orange2 btn-block " name="submitpl"><?php echo $nom ?>
																</button>
															</form>
														</div>
													</div>

													<div class="player player-5 playing" id="player5">
														<div class="avatar p5" style="background: grey; font-size:18px">
															<form method="post">
																<?php if ($option[5] == 'Elimine')
																	$nom = 'X';
																else
																	$nom = $pseudo[5]; ?>
																<button type="submit" id='submitpl' value=<?php echo $idparticipation[5] ?> class="btnn btn-primary-grey btn-block " name="submitpl"><?php echo $nom ?>
																</button>
															</form>
														</div>
													</div>

													<div class="player player-6 playing" id="player6">
														<div class="avatar p6" style="background: brown; font-size:18px">
															<form method="post">
																<?php if ($option[6] == 'Elimine')
																	$nom = 'X';
																else
																	$nom = $pseudo[6]; ?>
																<button type="submit" id='submitpl' value=<?php echo $idparticipation[6] ?> class="btnn btn-primary-brown btn-block" name="submitpl"><?php echo $nom ?>
																</button>
															</form>
														</div>
													</div>

													<div class="player player-7 playing" id="player7">
														<div class="avatar p7" style="background: pink; font-size:18px">
															<form method="post">
																<?php if ($option[7] == 'Elimine')
																	$nom = 'X';
																else
																	$nom = $pseudo[7]; ?>
																<button type="submit" id='submitpl' value=<?php echo $idparticipation[7] ?> class="btnn btn-primary-pink btn-block" name="submitpl"><?php echo $nom ?>
																</button>
															</form>
														</div>
													</div>

													<div class="player player-8 playing" id="player8">
														<div class="avatar p8" style="background: purple; font-size:18px">
															<form method="post">
																<?php if ($option[8] == 'Elimine')
																	$nom = 'X';
																else
																	$nom = $pseudo[8]; ?>
																<button type="submit" id='submitpl' value=<?php echo $idparticipation[8] ?> class="btnn btn-primary-purple btn-block" name="submitpl"><?php echo $nom ?>
																</button>
															</form>
														</div>
													</div>

													<div class="player player-9 playing" id="player9">
														<div class="avatar p9" style="background: grey; font-size:18px">
															<form method="post">
																<?php if ($option[9] == 'Elimine')
																	$nom = 'X';
																else
																	$nom = $pseudo[9]; ?>
																<button type="submit" id='submitpl' value=<?php echo $idparticipation[9] ?> class="btnn btn-primary-orange2 btn-block" name="submitpl"><?php echo $nom ?>
																</button>
															</form>
														</div>
													</div>

													<div class="player player-10 playing" id="player10">
														<div class="avatar p10" style="background: green; font-size:18px">
															<form method="post">
																<?php if ($option[10] == 'Elimine')
																	$nom = 'X';
																else
																	$nom = $pseudo[10]; ?>
																<button type="submit" id='submitpl' value=<?php echo $idparticipation[10] ?> class="btnn btn-primary-noir btn-block" name="submitpl"><?php echo $nom ?>
																</button>
														</div>
													</div>
												</div>

												<div class='square-box' opacity:0.85>
													<div class='square-content'>
														<?php if (1) { ?>
															<div id="response"></div><?php }
																				; ?>
													</div>
												</div>
											</div>
										</div>

										<?php
										date_default_timezone_set('Europe/Paris');
										$req = mysqli_query($con, "SELECT * FROM `blindes-live` WHERE (`id-activite` = $id AND `ordre` = 1)");
										$row = mysqli_fetch_array($req);
										$commence = $row["fin"];
										$actu = strtotime(date("Y-m-d H:i:s"));
										$debu = strtotime($commence);
										$ecar = $actu - $debu;
										if ($ecar > 0) {
										?>
											<div class='info6-content '> <?php echo $star; ?> </div>
											<?php
											$sqla = mysqli_query($con, "SELECT * FROM `participation` WHERE ((`id-activite` = $id AND `option` NOT LIKE 'Annule' ) AND (`id-activite` = $id AND `option` NOT LIKE 'Elimine' ) AND (`id-activite` = $id AND `id-membre` <> '2' ))");
											$rowcounta = mysqli_num_rows($sqla);
											$sqlb = mysqli_query($con, "SELECT * FROM `activite` WHERE `id-activite` = $id");
											$resb = mysqli_fetch_array($sqlb);
											$joueursmax = $resb["places"];
											?>
											<div class='info7-content '>
												<?php echo $rowcounta . " Joueurs inscrits sur " . $joueursmax . " max" ?>
											</div>
											<div class='info8-content'>
												<table class="table ttable-bordered">
													<form method="post">
														<tr>
															<td style="text-align:center ; display:none">
																<button type="submit" name="xxx" id="submit" class="btn btn-oo btn-primary">
																	Mise à jour
																</button>
															</td>
															<td style="text-align:center ;">
																<button type="submit" class="btn btn-primaryg btn-block" name="submit-ins">S'inscrire
																</button>
															</td>
															<td style="text-align:center ;">
																<button type="submit" class="btn btn-primary btn-block" name="btn-info">Infos
																</button>
															</td>
															<td style="text-align:center ;">
																<button type="submit" class="btn btn-primary-rouge btn-block" name="submit-deins">Se
																	désinscrire
																</button>
															</td>
														</tr>
													</form>
												</table>
											</div>

										<?php } else {
										?>
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
												while ($res = mysqli_fetch_array($req)) {
													$eli1 = $res["id-membre"];
													$res2 = mysqli_query($con, "SELECT * FROM `membres` WHERE (`id-membre` = $eli1)");
													$row2 = mysqli_fetch_array($res2);
													$nom1 = $row2["pseudo"];
												}
												;
												echo $nom1;
												?>
											</div>

											<div class='info2-content '> <?php echo "Bulle : " ?>
												<?php
												if ($rowcount2 > 0) {
													$bul = 2;
												}
												;
												if ($rowcount2 > 5) {
													$bul = 3;
												}
												;
												if ($rowcount2 > 8) {
													$bul = 4;
												}
												;
												if ($rowcount2 > 13) {
													$bul = 5;
												}
												;
												if ($rowcount2 > 20) {
													$bul = 6;
												}
												;
												$sql = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Elimine' )");
												$rowcount = mysqli_num_rows($sql);
												$sql = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule' )");
												$rowcount2 = mysqli_num_rows($sql);
												$sql = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule' ) OR (`id-activite` = $id AND `option` NOT LIKE 'Elimine' )");
												$rowcount3 = mysqli_num_rows($sql);
												$req = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-activite` = $id AND `option` LIKE 'Elimine' ORDER BY `ds` ASC");
												$reqbul = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-activite` = $id AND `option` LIKE 'Elimine' AND `classement` = $bul");
												$rowcounteli = mysqli_num_rows($req);
												while ($res = mysqli_fetch_array($reqbul)) {
													$eli1 = $res["id-membre"];
													$res2 = mysqli_query($con, "SELECT * FROM `membres` WHERE (`id-membre` = $eli1)");
													$row2 = mysqli_fetch_array($res2);
													$nom1 = $row2["pseudo"];
												}
												;
												echo $nom1;
												?>
											</div>

											<div class='info2-content'>
												<div id="car-pause">
												</div>
											</div>
											<?php
											if ($rowcount2 > 5) {
												$payes = 2;
											$r1 = 0.6;
											$r2 = 0.4;
											}
											;
											if ($rowcount2 > 8) {
												$payes = 3;
											$r1 = 0.5;
											$r2 = 0.3;
											$r3 = 0.2;
											}
											;
											if ($rowcount2 > 13) {
												$payes = 4;
											$r1 = 0.51;
											$r2 = 0.25;
											$r3 = 0.17;
											$r4 = 0.07;
											}
											;
											if ($rowcount2 > 20) {
												$payes = 5;
											$r1 = 0.35;
											$r2 = 0.25;
											$r3 = 0.18;
											$r4 = 0.13;
											$r5 = 0.09;
											}
											;
											?>
											<?php if ($rowcount2 - $rowcounteli - $payes > 0) { ?>
												<div class='info3-content '>
													<?php echo "Premier des " . $payes . " payés dans " . $rowcount2 - $rowcounteli - $payes . " Joueurs sur " . $rowcount2 ?>
												</div>
											<?php } else { ?>
												<div class='info3-content '>
													<?php echo $payes . " payés maintenant sur les " . $rowcount2 . " joueurs" ?>
												</div>
											<?php }
											;
											$sql2 = mysqli_query($con, "SELECT * FROM `activite` WHERE (`id-activite` = $id  )");
											$res2 = mysqli_fetch_array($sql2);
											$buyin = $res2["buyin"];
											$jetons = $res2["jetons"];
											$pot = 0;
											$nbr = 0;
											$nba = 0;
											$req3 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule') ");
											while ($res3 = mysqli_fetch_array($req3)) {
												$pot = $pot + (((int) ($res3["recave"]) + (int) ($res3["addon"])));
												$nbr = $nbr + $res3["recave"];
												$nba = $nba + $res3["addon"];
											}
											;
											$tot = $pot + $rowcount2;
											$final = $tot * $buyin;
											if ($payes == 2) {
												$p2 = $final * $r2;
											$p2 = $p2 / 10;
											$p2 = round($p2, 0);
											$p2 = $p2 * 10;
											$p1 = $final - $p2;
											?>
												<div class='info4-content '>
													<?php echo "Pot total : " . $final . "€ soit : " . "P1=" . $p1 . "€, P2=" . $p2 . "€" ?>
												</div>
											<?php
											}
											;
											if ($payes == 3) {
												$p3 = $final * $r3;
											$p3 = $p3 / 10;
											$p3 = round($p3, 0);
											$p3 = $p3 * 10;
											$p2 = $final * $r2;
											$p2 = $p2 / 10;
											$p2 = round($p2, 0);
											$p2 = $p2 * 10;
											$p1 = $final - $p3 - $p2;
											?>
												<div class='info4-content '>
													<?php echo "Pot total : " . $final . "€ soit : " . "P1=" . $p1 . "€, P2=" . $p2 . "€, P3=" . $p3 . "€" ?>
												</div>
											<?php
											}
											;
											if ($payes == 4) {
												$p4 = $final * $r4;
											$p4 = $p4 / 10;
											$p4 = round($p4, 0);
											$p4 = $p4 * 10;
											$p3 = $final * $r3;
											$p3 = $p3 / 10;
											$p3 = round($p3, 0);
											$p3 = $p3 * 10;
											$p2 = $final * $r2;
											$p2 = $p2 / 10;
											$p2 = round($p2, 0);
											$p2 = $p2 * 10;
											$p1 = $final - $p4 - $p3 - $p2;
											?>
												<div class='info4-content '>
													<?php echo "Pot total : " . $final . " soit : " . "P1=" . $p1 . ", P2=" . $p2 . ", P3=" . $p3 . ", P4=" . $p4 . " Cacahuètes !" ?>
												</div>
											<?php
											}
											;
											if ($payes == 5) {
												$p5 = $final * $r5;
											$p5 = $p5 / 10;
											$p5 = round($p5, 0);
											$p5 = $p5 * 10;
											$p4 = $final * $r4;
											$p4 = $p4 / 10;
											$p4 = round($p4, 0);
											$p4 = $p4 * 10;
											$p3 = $final * $r3;
											$p3 = $p3 / 10;
											$p3 = round($p3, 0);
											$p3 = $p3 * 10;
											$p2 = $final * $r2;
											$p2 = $p2 / 10;
											$p2 = round($p2, 0);
											$p2 = $p2 * 10;
											$p1 = $final - $p5 - $p4 - $p3 - $p2;
											?>
												<div class='info4-content '>
													<?php echo "Pot total : " . $final . "€ soit : " . "P1=" . $p1 . "€, P2=" . $p2 . "€, P3=" . $p3 . "€, P4=" . $p4 . "€, P5=" . $p5 . "€" ?>
												</div>
											<?php
											}
											;
											$enjeu = ($rowcount2 - $rowcounteli);
											if ($enjeu == 0)
												$enjeu = 1;
											else
												$enjeu = $rowcount2 - $rowcounteli;
											?>

											<div class='info5-content '>
												<?php echo "Stack Moyen : " . ($jetons * ($rowcount2 + $nbr + $nba)) / ($enjeu) . " sur " . ($jetons * ($rowcount2 + $nbr + $nba)) . " = (" . $rowcount2 . "B ) + " . " (" . $nbr . "R ) + " . " (" . $nba . "A )" ?>
											</div>

											<?php $_SESSION["act"] = $id;
											?>
											<?php $_SESSION["act"] = $id;
											?>
										<?php }
										; ?>
									</div>
									<div id="t2E">
										<script>
											responsiveVoice.setDefaultVoice("French Female")
										</script>
										<?php if ($nbt >= '2') { ?>
											<script>
												var audio3 = new Audio("popa.mp3");
												var audio2 = new Audio("http://glpjt.s3.amazonaws.com/so/av/a12.mp3");
												var audio = new Audio(
													"https://s3.amazonaws.com/audio-experiments/examples/elon_mono.wav");

												function playAudio() {
													audio3.play();
												}

												function pauseAudio() {
													audio2.play();
												}

												function cancelAudio() {
													audio.pause();
													audio.currentTime = 0;
												}
											</script>
											<div class="ccontainer-fluid ccontainer-fullw bg-dark-bricky " style="background-color:grey;opacity:1">
												<?php
												$rowcountocc = "0";
												$scru = $tableaff;
												if ((int) $rowcountocc < 1) {
													(int) $scru++;
													$sqla = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = '$id' AND `id-table` = '$scru' ) LIMIT 1 ");
													$rowcountocc = mysqli_num_rows($sqla);
												}
												;
												(int) $tableaff = (int) $scru;
												$tableaff = 2;
												$cnt = 0;
												unset($idmembre);
												unset($position);
												unset($idparticipation);
												unset($siege);
												unset($option);
												unset($pseudo);
												$sql = mysqli_query($con, "SELECT  `id-membre`,`position`,`id-participation`,`id-siege`,`option` FROM `participation` WHERE (`id-activite` = '$id' AND `id-table` = '$tableaff' )  ORDER BY `id-siege` ");
												$nb_lignes = mysqli_num_rows($sql);
												while ($row = mysqli_fetch_array($sql)) {
													$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													$idmembre[$cnt] = $row['id-membre'];
													$position[$cnt] = $row['position'];
													$idparticipation[$cnt] = $row['id-participation'];
													$siege[$cnt] = $row['id-siege'];
													$option[$cnt] = $row['option'];
													$sql2 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  '$idmembre[$cnt]'");
													while ($row2 = mysqli_fetch_array($sql2)) {
														$pseudo[$cnt] = $row2['pseudo'];
													}
												}
												;
												?>

												<div id="main">
													<div class="players">
														<div class="player player-1 playing" id="player1">
															<div class="avatar p1" style="background: blue ;font-size: 1.7vw">
																<form method="post">
																	<?php if ($option[1] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[1]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[1] ?> class="btbn btn-pokerblue btn-block " name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-2 playing" id="player2">
															<div class="avatar p2" style="background: red ;font-size: 1.7vw">
																<form method="post">
																	<?php if ($option[2] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[2]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[2] ?> class="btbn btn-pokerred btn-block " name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-3 playing" id="player3">
															<div class="avatar p3" style="background: black ;font-size:18px">
																<form method="post">
																	<?php if ($option[3] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[3]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[3] ?> class="btnn btn-primary-noir btn-block " name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-4 playing" id="player4">
															<div class="avatar p4" style="background: orange; font-size:18px">
																<form method="post">
																	<?php if ($option[4] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[4]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[4] ?> class="btnn btn-primary-orange2 btn-block " name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-5 playing" id="player5">
															<div class="avatar p5" style="background: grey; font-size:18px">
																<form method="post">
																	<?php if ($option[5] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[5]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[5] ?> class="btnn btn-primary-grey btn-block " name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-6 playing" id="player6">
															<div class="avatar p6" style="background: brown; font-size:18px">
																<form method="post">
																	<?php if ($option[6] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[6]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[6] ?> class="btnn btn-primary-brown btn-block" name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-7 playing" id="player7">
															<div class="avatar p7" style="background: pink; font-size:18px">
																<form method="post">
																	<?php if ($option[7] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[7]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[7] ?> class="btnn btn-primary-pink btn-block" name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-8 playing" id="player8">
															<div class="avatar p8" style="background: purple; font-size:18px">
																<form method="post">
																	<?php if ($option[8] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[8]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[8] ?> class="btnn btn-primary-purple btn-block" name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-9 playing" id="player9">
															<div class="avatar p9" style="background: grey; font-size:18px">
																<form method="post">
																	<?php if ($option[9] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[9]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[9] ?> class="btnn btn-primary-orange2 btn-block" name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-10 playing" id="player10">
															<div class="avatar p10" style="background: green; font-size:18px">
																<form method="post">
																	<?php if ($option[10] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[10]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[10] ?> class="btnn btn-primary-noir btn-block" name="submitpl"><?php echo $nom ?>
																	</button>
															</div>
														</div>
													</div>

													<div class='square-box' opacity:0.85>
														<div class='square-content'>
															<?php if (1) { ?>
																<div id="response"></div><?php }
																					; ?>
														</div>
													</div>
												</div>
											</div>

											<?php
											date_default_timezone_set('Europe/Paris');
											$req = mysqli_query($con, "SELECT * FROM `blindes-live` WHERE (`id-activite` = $id AND `ordre` = 1)");
											$row = mysqli_fetch_array($req);
											$commence = $row["fin"];
											$actu = strtotime(date("Y-m-d H:i:s"));
											$debu = strtotime($commence);
											$ecar = $actu - $debu;
											if ($ecar > 0) {
											?>
												<div class='info6-content '> <?php echo $star; ?> </div>
												<?php
												$sqla = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule' ) OR (`id-activite` = $id AND `option` NOT LIKE 'Elimine' )");
												$rowcounta = mysqli_num_rows($sqla);
												$sqlb = mysqli_query($con, "SELECT * FROM `activite` WHERE `id-activite` = $id");
												$resb = mysqli_fetch_array($sqlb);
												$joueursmax = $resb["places"];
												$activi = $id;

												?>
												<div class='info7-content '>
													<?php echo $rowcounta . " Joueurs inscrits sur " . $joueursmax . " max" ?>
												</div>
												<div class='info8-content'>
													<table class="table ttable-bordered">
														<form method="post">
															<tr>
																<td style="text-align:center ; display:none">
																	<button type="submit" name="xxx" id="submit" class="btn btn-oo btn-primary">
																		Mise à jour
																	</button>
																</td>
																<td style="text-align:center ;">
																	<button type="submit" class="btn btn-primaryg btn-block" name="submit-ins">S'inscrire
																	</button>
																</td>
																<td style="text-align:center ;">
																	<button type="submit" class="btn btn-primary btn-block" name="xxx">Infos
																	</button>
																</td>
																<td style="text-align:center ;">
																	<button type="submit" class="btn btn-primary-rouge btn-block" name="submit-desinsbis">Se
																		désinscrire
																	</button>
																</td>
															</tr>
														</form>
													</table>
												</div>
											<?php } else {
											?>
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
													while ($res = mysqli_fetch_array($req)) {
														$eli1 = $res["id-membre"];
														$res2 = mysqli_query($con, "SELECT * FROM `membres` WHERE (`id-membre` = $eli1)");
														$row2 = mysqli_fetch_array($res2);
														$nom1 = $row2["pseudo"];
													}
													;
													echo $nom1;
													?>
												</div>

												<div class='info2-content'>
													<div id="car-pause">
													</div>
												</div>
												<?php
												if ($rowcount2 > 5) {
													$payes = 2;
												$r1 = 0.6;
												$r2 = 0.4;
												}
												;
												if ($rowcount2 > 8) {
													$payes = 3;
												$r1 = 0.5;
												$r2 = 0.3;
												$r3 = 0.2;
												}
												;
												if ($rowcount2 > 13) {
													$payes = 4;
												$r1 = 0.4;
												$r2 = 0.28;
												$r3 = 0.19;
												$r4 = 0.13;
												}
												;
												if ($rowcount2 > 20) {
													$payes = 5;
												$r1 = 0.35;
												$r2 = 0.25;
												$r3 = 0.18;
												$r4 = 0.13;
												$r5 = 0.09;
												}
												;
												?>
												<?php if ($rowcount2 - $rowcounteli - $payes > 0) { ?>
													<div class='info3-content '>
														<?php echo "Premier des " . $payes . " payés dans " . $rowcount2 - $rowcounteli - $payes . " Joueurs sur " . $rowcount2 ?>
													</div>
												<?php } else { ?>
													<div class='info3-content '>
														<?php echo $payes . " payés maintenant sur les " . $rowcount2 . " joueurs" ?>
													</div>
												<?php }
												;
												$sql2 = mysqli_query($con, "SELECT * FROM `activite` WHERE (`id-activite` = $id  )");
												$res2 = mysqli_fetch_array($sql2);
												$buyin = $res2["buyin"];
												$jetons = $res2["jetons"];
												$pot = 0;
												$nbr = 0;
												$nba = 0;
												$req3 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule') ");
												while ($res3 = mysqli_fetch_array($req3)) {
													$pot = $pot + (((int) ($res3["recave"]) + (int) ($res3["addon"])));
													$nbr = $nbr + $res3["recave"];
													$nba = $nba + $res3["addon"];
												}
												;
												$tot = $pot + $rowcount2;
												$final = $tot * $buyin;
												if ($payes == 2) {
													$p2 = $final * $r2;
												$p2 = $p2 / 10;
												$p2 = round($p2, 0);
												$p2 = $p2 * 10;
												$p1 = $final - $p2;
													?>
													<div class='info4-content '>
														<?php echo "Pot total : " . $final . "€ soit : " . "P1=" . $p1 . "€, P2=" . $p2 . "€" ?>
													</div>
												<?php
												}
												;
												if ($payes == 3) {
													$p3 = $final * $r3;
												$p3 = $p3 / 10;
												$p3 = round($p3, 0);
												$p3 = $p3 * 10;
												$p2 = $final * $r2;
												$p2 = $p2 / 10;
												$p2 = round($p2, 0);
												$p2 = $p2 * 10;
												$p1 = $final - $p3 - $p2;
													?>
													<div class='info4-content '>
														<?php echo "Pot total : " . $final . "€ soit : " . "P1=" . $p1 . "€, P2=" . $p2 . "€, P3=" . $p3 . "€" ?>
													</div>
												<?php
												}
												;
												if ($payes == 4) {
													$p4 = $final * $r4;
												$p4 = $p4 / 10;
												$p4 = round($p4, 0);
												$p4 = $p4 * 10;
												$p3 = $final * $r3;
												$p3 = $p3 / 10;
												$p3 = round($p3, 0);
												$p3 = $p3 * 10;
												$p2 = $final * $r2;
												$p2 = $p2 / 10;
												$p2 = round($p2, 0);
												$p2 = $p2 * 10;
												$p1 = $final - $p4 - $p3 - $p2;
													?>
													<div class='info4-content '>
														<?php echo "Pot total : " . $final . "€ soit : " . "P1=" . $p1 . "€, P2=" . $p2 . "€, P3=" . $p3 . "€, P4=" . $p4 . "€" ?>
													</div>
												<?php
												}
												;
												if ($payes == 5) {
													$p5 = $final * $r5;
												$p5 = $p5 / 10;
												$p5 = round($p5, 0);
												$p5 = $p5 * 10;
												$p4 = $final * $r4;
												$p4 = $p4 / 10;
												$p4 = round($p4, 0);
												$p4 = $p4 * 10;
												$p3 = $final * $r3;
												$p3 = $p3 / 10;
												$p3 = round($p3, 0);
												$p3 = $p3 * 10;
												$p2 = $final * $r2;
												$p2 = $p2 / 10;
												$p2 = round($p2, 0);
												$p2 = $p2 * 10;
												$p1 = $final - $p5 - $p4 - $p3 - $p2;
													?>
													<div class='info4-content '>
														<?php echo "Pot total : " . $final . "€ soit : " . "P1=" . $p1 . "€, P2=" . $p2 . "€, P3=" . $p3 . "€, P4=" . $p4 . "€, P5=" . $p5 . "€" ?>
													</div>
												<?php
												}
												;
												$enjeu = ($rowcount2 - $rowcounteli);
												if ($enjeu == 0)
													$enjeu = 1;
												else
													$enjeu = $rowcount2 - $rowcounteli;
												?>

												<div class='info5-content '>
													<?php echo "Stack Moyen : " . ($jetons * ($rowcount2 + $nbr + $nba)) / ($enjeu) . " sur " . ($jetons * ($rowcount2 + $nbr + $nba)) . " = (" . $rowcount2 . "B ) + " . " (" . $nbr . "R ) + " . " (" . $nba . "A )" ?>
												</div>

												<?php $_SESSION["act"] = $id;
												?>
												<?php $_SESSION["act"] = $id;
												?>
											<?php }
											; ?>
											<?php } ?>
									</div>
									<div id="t3E">
										<?php if ($nbt >= '3') { ?>
											<div class="ccontainer-fluid ccontainer-fullw bg-dark-bricky " style="background-color:grey;opacity:1">
												<?php
												$rowcountocc = "0";
												$scru = $tableaff;
												if ((int) $rowcountocc < 1) {
													(int) $scru++;
													$sqla = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = '$id' AND `id-table` = '$scru' ) LIMIT 1 ");
													$rowcountocc = mysqli_num_rows($sqla);
												}
												;
												(int) $tableaff = (int) $scru;
												$tableaff = 3;
												$cnt = 0;
												unset($idmembre);
												unset($position);
												unset($idparticipation);
												unset($siege);
												unset($option);
												unset($pseudo);
												$sql = mysqli_query($con, "SELECT  `id-membre`,`position`,`id-participation`,`id-siege`,`option` FROM `participation` WHERE (`id-activite` = '$id' AND `id-table` = '$tableaff' )  ORDER BY `id-siege` ");
												$nb_lignes = mysqli_num_rows($sql);
												while ($row = mysqli_fetch_array($sql)) {
													$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													$idmembre[$cnt] = $row['id-membre'];
													$position[$cnt] = $row['position'];
													$idparticipation[$cnt] = $row['id-participation'];
													$siege[$cnt] = $row['id-siege'];
													$option[$cnt] = $row['option'];
													$sql2 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  '$idmembre[$cnt]'");
													while ($row2 = mysqli_fetch_array($sql2)) {
														$pseudo[$cnt] = $row2['pseudo'];
													}
												}
												;
												?>

												<div id="main">
													<div class="players">
														<div class="player player-1 playing" id="player1">
															<div class="avatar p1" style="background: blue ;font-size: 1.7vw">
																<form method="post">
																	<?php if ($option[1] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[1]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[1] ?> class="btbn btn-pokerblue btn-block " name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-2 playing" id="player2">
															<div class="avatar p2" style="background: red ;font-size: 1.7vw">
																<form method="post">
																	<?php if ($option[2] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[2]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[2] ?> class="btbn btn-pokerred btn-block " name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-3 playing" id="player3">
															<div class="avatar p3" style="background: black ;font-size:18px">
																<form method="post">
																	<?php if ($option[3] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[3]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[3] ?> class="btnn btn-primary-noir btn-block " name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-4 playing" id="player4">
															<div class="avatar p4" style="background: orange; font-size:18px">
																<form method="post">
																	<?php if ($option[4] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[4]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[4] ?> class="btnn btn-primary-orange2 btn-block " name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-5 playing" id="player5">
															<div class="avatar p5" style="background: grey; font-size:18px">
																<form method="post">
																	<?php if ($option[5] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[5]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[5] ?> class="btnn btn-primary-grey btn-block " name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-6 playing" id="player6">
															<div class="avatar p6" style="background: brown; font-size:18px">
																<form method="post">
																	<?php if ($option[6] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[6]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[6] ?> class="btnn btn-primary-brown btn-block" name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-7 playing" id="player7">
															<div class="avatar p7" style="background: pink; font-size:18px">
																<form method="post">
																	<?php if ($option[7] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[7]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[7] ?> class="btnn btn-primary-pink btn-block" name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-8 playing" id="player8">
															<div class="avatar p8" style="background: purple; font-size:18px">
																<form method="post">
																	<?php if ($option[8] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[8]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[8] ?> class="btnn btn-primary-purple btn-block" name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-9 playing" id="player9">
															<div class="avatar p9" style="background: grey; font-size:18px">
																<form method="post">
																	<?php if ($option[9] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[9]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[9] ?> class="btnn btn-primary-orange2 btn-block" name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-10 playing" id="player10">
															<div class="avatar p10" style="background: green; font-size:18px">
																<form method="post">
																	<?php if ($option[10] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[10]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[10] ?> class="btnn btn-primary-noir btn-block" name="submitpl"><?php echo $nom ?>
																	</button>
															</div>
														</div>
													</div>

													<div class='square-box' opacity:0.85>
														<div class='square-content'>
															<div id="response"></div>
														</div>
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
												while ($res = mysqli_fetch_array($req)) {
													$eli1 = $res["id-membre"];
													$res2 = mysqli_query($con, "SELECT * FROM `membres` WHERE (`id-membre` = $eli1)");
													$row2 = mysqli_fetch_array($res2);
													$nom1 = $row2["pseudo"];
												}
												;
												echo $nom1;
												?>
											</div>

											<div class='info2-content '>
												<?php echo "Pause et fin des recaves dans : .. minutes" ?>
											</div>
											<?php

											if ($rowcount2 > 5) {
												$payes = 2;
											$r1 = 0.6;
											$r2 = 0.4;
											}
											;
											if ($rowcount2 > 8) {
												$payes = 3;
											$r1 = 0.5;
											$r2 = 0.3;
											$r3 = 0.2;
											}
											;
											if ($rowcount2 > 13) {
												$payes = 4;
											$r1 = 0.4;
											$r2 = 0.28;
											$r3 = 0.19;
											$r4 = 0.13;
											}
											;
											if ($rowcount2 > 20) {
												$payes = 5;
											$r1 = 0.35;
											$r2 = 0.25;
											$r3 = 0.18;
											$r4 = 0.13;
											$r5 = 0.09;
											}
											;
											?>
											<?php if ($rowcount2 - $rowcounteli - $payes > 0) { ?>
												<div class='info3-content '>
													<?php echo "Premier des " . $payes . " payés dans " . $rowcount2 - $rowcounteli - $payes . " Joueurs sur " . $rowcount2 ?>
												</div>
											<?php } else { ?>
												<div class='info3-content '>
													<?php echo $payes . " payés maintenant sur les " . $rowcount2 . " joueurs" ?>
												</div>
											<?php }
											;

											$sql2 = mysqli_query($con, "SELECT * FROM `activite` WHERE (`id-activite` = $id  )");
											$res2 = mysqli_fetch_array($sql2);
											$buyin = $res2["buyin"];
											$jetons = $res2["jetons"];
											$pot = 0;
											$nbr = 0;
											$nba = 0;
											$req3 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule') ");
											while ($res3 = mysqli_fetch_array($req3)) {
												$pot = $pot + (((int) ($res3["recave"]) + (int) ($res3["addon"])));
												$nbr = $nbr + $res3["recave"];
												$nba = $nba + $res3["addon"];
											}
											;
											$tot = $pot + $rowcount2;
											$final = $tot * $buyin;
											if ($payes == 2) {
												$p2 = $final * $r2;
											$p2 = $p2 / 10;
											$p2 = round($p2, 0);
											$p2 = $p2 * 10;
											$p1 = $final - $p2;
											?>
												<div class='info4-content '>
													<?php echo "Pot total : " . $final . "€ soit : " . "P1=" . $p1 . "€, P2=" . $p2 . "€" ?>
												</div>
											<?php
											}
											;
											if ($payes == 3) {
												$p3 = $final * $r3;
											$p3 = $p3 / 10;
											$p3 = round($p3, 0);
											$p3 = $p3 * 10;
											$p2 = $final * $r2;
											$p2 = $p2 / 10;
											$p2 = round($p2, 0);
											$p2 = $p2 * 10;
											$p1 = $final - $p3 - $p2;
											?>
												<div class='info4-content '>
													<?php echo "Pot total : " . $final . "€ soit : " . "P1=" . $p1 . "€, P2=" . $p2 . "€, P3=" . $p3 . "€" ?>
												</div>
											<?php
											}
											;
											if ($payes == 4) {
												$p4 = $final * $r4;
											$p4 = $p4 / 10;
											$p4 = round($p4, 0);
											$p4 = $p4 * 10;
											$p3 = $final * $r3;
											$p3 = $p3 / 10;
											$p3 = round($p3, 0);
											$p3 = $p3 * 10;
											$p2 = $final * $r2;
											$p2 = $p2 / 10;
											$p2 = round($p2, 0);
											$p2 = $p2 * 10;
											$p1 = $final - $p4 - $p3 - $p2;
											?>
												<div class='info4-content '>
													<?php echo "Pot total : " . $final . "€ soit : " . "P1=" . $p1 . "€, P2=" . $p2 . "€, P3=" . $p3 . "€, P4=" . $p4 . "€" ?>
												</div>
											<?php
											}
											;
											if ($payes == 5) {
												$p5 = $final * $r5;
											$p5 = $p5 / 10;
											$p5 = round($p5, 0);
											$p5 = $p5 * 10;
											$p4 = $final * $r4;
											$p4 = $p4 / 10;
											$p4 = round($p4, 0);
											$p4 = $p4 * 10;
											$p3 = $final * $r3;
											$p3 = $p3 / 10;
											$p3 = round($p3, 0);
											$p3 = $p3 * 10;
											$p2 = $final * $r2;
											$p2 = $p2 / 10;
											$p2 = round($p2, 0);
											$p2 = $p2 * 10;
											$p1 = $final - $p5 - $p4 - $p3 - $p2;
											?>
												<div class='info4-content '>
													<?php echo "Pot total : " . $final . "€ soit : " . "P1=" . $p1 . "€, P2=" . $p2 . "€, P3=" . $p3 . "€, P4=" . $p4 . "€, P5=" . $p5 . "€" ?>
												</div>
											<?php
											}
											;
											$enjeu = ($rowcount2 - $rowcounteli);
											if ($enjeu == 0)
												$enjeu = 1;
											else
												$enjeu = $rowcount2 - $rowcounteli;
											?>
											<div class='info5-content '>
												<?php echo "Stack Moyen : " . ($jetons * ($rowcount2 + $nbr + $nba)) / ($enjeu) . " sur " . ($jetons * ($rowcount2 + $nbr + $nba)) . " = (" . $rowcount2 . "B ) + " . " (" . $nbr . "R ) + " . " (" . $nba . "A )" ?>
											</div>
											<?php } ?>
									</div>
									<div id="t4E">
										<?php if ($nbt >= '4') { ?>
											<div class="ccontainer-fluid ccontainer-fullw bg-dark-bricky " style="background-color:grey;opacity:1">
												<?php
												$rowcountocc = "0";
												$scru = $tableaff;
												if ((int) $rowcountocc < 1) {
													(int) $scru++;
													$sqla = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = '$id' AND `id-table` = '$scru' ) LIMIT 1 ");
													$rowcountocc = mysqli_num_rows($sqla);
												}
												;
												(int) $tableaff = (int) $scru;
												$tableaff = 4;
												$cnt = 0;
												unset($idmembre);
												unset($position);
												unset($idparticipation);
												unset($siege);
												unset($option);
												unset($pseudo);
												$sql = mysqli_query($con, "SELECT  `id-membre`,`position`,`id-participation`,`id-siege`,`option` FROM `participation` WHERE (`id-activite` = '$id' AND `id-table` = '$tableaff' )  ORDER BY `id-siege` ");
												$nb_lignes = mysqli_num_rows($sql);
												while ($row = mysqli_fetch_array($sql)) {
													$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													if ($row['id-siege'] <> $cnt)
														$cnt = $cnt + 1;
													$idmembre[$cnt] = $row['id-membre'];
													$position[$cnt] = $row['position'];
													$idparticipation[$cnt] = $row['id-participation'];
													$siege[$cnt] = $row['id-siege'];
													$option[$cnt] = $row['option'];
													$sql2 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` =  '$idmembre[$cnt]'");
													while ($row2 = mysqli_fetch_array($sql2)) {
														$pseudo[$cnt] = $row2['pseudo'];
													}
												}
												;
												?>

												<div id="main">
													<div class="players">
														<div class="player player-1 playing" id="player1">
															<div class="avatar p1" style="background: blue ;font-size: 1.7vw">
																<form method="post">
																	<?php if ($option[1] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[1]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[1] ?> class="btbn btn-pokerblue btn-block " name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-2 playing" id="player2">
															<div class="avatar p2" style="background: red ;font-size: 1.7vw">
																<form method="post">
																	<?php if ($option[2] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[2]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[2] ?> class="btbn btn-pokerred btn-block " name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-3 playing" id="player3">
															<div class="avatar p3" style="background: black ;font-size:18px">
																<form method="post">
																	<?php if ($option[3] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[3]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[3] ?> class="btnn btn-primary-noir btn-block " name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-4 playing" id="player4">
															<div class="avatar p4" style="background: orange; font-size:18px">
																<form method="post">
																	<?php if ($option[4] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[4]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[4] ?> class="btnn btn-primary-orange2 btn-block " name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-5 playing" id="player5">
															<div class="avatar p5" style="background: grey; font-size:18px">
																<form method="post">
																	<?php if ($option[5] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[5]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[5] ?> class="btnn btn-primary-grey btn-block " name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-6 playing" id="player6">
															<div class="avatar p6" style="background: brown; font-size:18px">
																<form method="post">
																	<?php if ($option[6] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[6]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[6] ?> class="btnn btn-primary-brown btn-block" name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-7 playing" id="player7">
															<div class="avatar p7" style="background: pink; font-size:18px">
																<form method="post">
																	<?php if ($option[7] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[7]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[7] ?> class="btnn btn-primary-pink btn-block" name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-8 playing" id="player8">
															<div class="avatar p8" style="background: purple; font-size:18px">
																<form method="post">
																	<?php if ($option[8] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[8]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[8] ?> class="btnn btn-primary-purple btn-block" name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-9 playing" id="player9">
															<div class="avatar p9" style="background: grey; font-size:18px">
																<form method="post">
																	<?php if ($option[9] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[9]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[9] ?> class="btnn btn-primary-orange2 btn-block" name="submitpl"><?php echo $nom ?>
																	</button>
																</form>
															</div>
														</div>

														<div class="player player-10 playing" id="player10">
															<div class="avatar p10" style="background: green; font-size:18px">
																<form method="post">
																	<?php if ($option[10] == 'Elimine')
																		$nom = 'X';
																	else
																		$nom = $pseudo[10]; ?>
																	<button type="submit" id='submitpl' value=<?php echo $idparticipation[10] ?> class="btnn btn-primary-noir btn-block" name="submitpl"><?php echo $nom ?>
																	</button>
															</div>
														</div>
													</div>

													<div class='square-box' opacity:0.85>
														<div class='square-content'>
															<div id="response"></div>
														</div>
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
												while ($res = mysqli_fetch_array($req)) {
													$eli1 = $res["id-membre"];
													$res2 = mysqli_query($con, "SELECT * FROM `membres` WHERE (`id-membre` = $eli1)");
													$row2 = mysqli_fetch_array($res2);
													$nom1 = $row2["pseudo"];
												}
												;
												echo $nom1;
												?>
											</div>

											<div class='info2-content '>
												<?php echo "Pause et fin des recaves dans : .. minutes" ?>
											</div>
											<?php

											if ($rowcount2 > 5) {
												$payes = 2;
											$r1 = 0.6;
											$r2 = 0.4;
											}
											;
											if ($rowcount2 > 8) {
												$payes = 3;
											$r1 = 0.5;
											$r2 = 0.3;
											$r3 = 0.2;
											}
											;
											if ($rowcount2 > 13) {
												$payes = 4;
											$r1 = 0.4;
											$r2 = 0.28;
											$r3 = 0.19;
											$r4 = 0.13;
											}
											;
											if ($rowcount2 > 20) {
												$payes = 5;
											$r1 = 0.35;
											$r2 = 0.25;
											$r3 = 0.18;
											$r4 = 0.13;
											$r5 = 0.09;
											}
											;
											?>
											<?php if ($rowcount2 - $rowcounteli - $payes > 0) { ?>
												<div class='info3-content '>
													<?php echo "Premier des " . $payes . " payés dans " . $rowcount2 - $rowcounteli - $payes . " Joueurs sur " . $rowcount2 ?>
												</div>
											<?php } else { ?>
												<div class='info3-content '>
													<?php echo $payes . " payés maintenant sur les " . $rowcount2 . " joueurs" ?>
												</div>
											<?php }
											;

											$sql2 = mysqli_query($con, "SELECT * FROM `activite` WHERE (`id-activite` = $id  )");
											$res2 = mysqli_fetch_array($sql2);
											$buyin = $res2["buyin"];
											$jetons = $res2["jetons"];
											$pot = 0;
											$nbr = 0;
											$nba = 0;
											$req3 = mysqli_query($con, "SELECT * FROM `participation` WHERE (`id-activite` = $id AND `option` NOT LIKE 'Annule') ");
											while ($res3 = mysqli_fetch_array($req3)) {
												$pot = $pot + (((int) ($res3["recave"]) + (int) ($res3["addon"])));
												$nbr = $nbr + $res3["recave"];
												$nba = $nba + $res3["addon"];
											}
											;
											$tot = $pot + $rowcount2;
											$final = $tot * $buyin;
											if ($payes == 2) {
												$p2 = $final * $r2;
											$p2 = $p2 / 10;
											$p2 = round($p2, 0);
											$p2 = $p2 * 10;
											$p1 = $final - $p2;
											?>
												<div class='info4-content '>
													<?php echo "Pot total : " . $final . "€ soit : " . "P1=" . $p1 . "€, P2=" . $p2 . "€" ?>
												</div>
											<?php
											}
											;
											if ($payes == 3) {
												$p3 = $final * $r3;
											$p3 = $p3 / 10;
											$p3 = round($p3, 0);
											$p3 = $p3 * 10;
											$p2 = $final * $r2;
											$p2 = $p2 / 10;
											$p2 = round($p2, 0);
											$p2 = $p2 * 10;
											$p1 = $final - $p3 - $p2;
											?>
												<div class='info4-content '>
													<?php echo "Pot total : " . $final . "€ soit : " . "P1=" . $p1 . "€, P2=" . $p2 . "€, P3=" . $p3 . "€" ?>
												</div>
											<?php
											}
											;
											if ($payes == 4) {
												$p4 = $final * $r4;
											$p4 = $p4 / 10;
											$p4 = round($p4, 0);
											$p4 = $p4 * 10;
											$p3 = $final * $r3;
											$p3 = $p3 / 10;
											$p3 = round($p3, 0);
											$p3 = $p3 * 10;
											$p2 = $final * $r2;
											$p2 = $p2 / 10;
											$p2 = round($p2, 0);
											$p2 = $p2 * 10;
											$p1 = $final - $p4 - $p3 - $p2;
											?>
												<div class='info4-content '>
													<?php echo "Pot total : " . $final . "€ soit : " . "P1=" . $p1 . "€, P2=" . $p2 . "€, P3=" . $p3 . "€, P4=" . $p4 . "€" ?>
												</div>
											<?php
											}
											;
											if ($payes == 5) {
												$p5 = $final * $r5;
											$p5 = $p5 / 10;
											$p5 = round($p5, 0);
											$p5 = $p5 * 10;
											$p4 = $final * $r4;
											$p4 = $p4 / 10;
											$p4 = round($p4, 0);
											$p4 = $p4 * 10;
											$p3 = $final * $r3;
											$p3 = $p3 / 10;
											$p3 = round($p3, 0);
											$p3 = $p3 * 10;
											$p2 = $final * $r2;
											$p2 = $p2 / 10;
											$p2 = round($p2, 0);
											$p2 = $p2 * 10;
											$p1 = $final - $p5 - $p4 - $p3 - $p2;
											?>
												<div class='info4-content '>
													<?php echo "Pot total : " . $final . "€ soit : " . "P1=" . $p1 . "€, P2=" . $p2 . "€, P3=" . $p3 . "€, P4=" . $p4 . "€, P5=" . $p5 . "€" ?>
												</div>
											<?php
											}
											;
											$enjeu = ($rowcount2 - $rowcounteli);
											if ($enjeu == 0)
												$enjeu = 1;
											else
												$enjeu = $rowcount2 - $rowcounteli;
											?>
											<div class='info5-content '>
												<?php echo "Stack Moyen : " . ($jetons * ($rowcount2 + $nbr + $nba)) / ($enjeu) . " sur " . ($jetons * ($rowcount2 + $nbr + $nba)) . " = (" . $rowcount2 . "B ) + " . " (" . $nbr . "R ) + " . " (" . $nba . "A )" ?>
											</div>
											<?php } ?>
									</div>
									<div id="inscritsE">
                                    <div class="main-content">
                <div class="wrap-content container" id="container10">
                    
                    <div class="container-fluid container-fullw bg-white">
                        
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
																			<mai>
																				<?php
																				// config.php
	define('DB_CONFIG', [
		'host'     => 'localhost',
		'user'     => 'root',
		'password' => 'Kookies7*',
		'name'     => 'dbs9616600',
		'charset'  => 'utf8mb4'
	]);

	function getDBConnection() {
		static $conn = null;
		if ($conn === null) {
			$conn = mysqli_connect(DB_CONFIG['host'], DB_CONFIG['user'], DB_CONFIG['password'], DB_CONFIG['name']);
			if (!$conn) die('Erreur de connexion : ' . mysqli_connect_error());
			mysqli_set_charset($conn, DB_CONFIG['charset']);
		}
		return $conn;
	}

	function fetchMembres() {
		$conn = getDBConnection();
		$id = intval($_GET['uid']); 
//        $ret = mysqli_query($con, "SELECT * FROM `participation` WHERE `id-activite` = '$id' ");
		$result = mysqli_query($conn, "SELECT * FROM `participation` WHERE `id-activite` = '$id' ");
//        $result = mysqli_query($conn, "SELECT * FROM `membres` WHERE `id-membre` = '$id2' ORDER BY 'classement' ASC");
		return mysqli_num_rows($result) > 0 ? $result : [];
	}
	?>
	<main>
														<div class="container-fluid px-4">
															<h1 class="mt-4">Liste des Joueurs</h1>
															<ol class="breadcrumb mb-4">
																<li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
																<li class="breadcrumb-item active">Liste des Joueurs</li>
															</ol>
															<div class="card mb-4">
																<div class="card-body p-2">
																	<div class="table-responsive">
																		<table id="employeeTable" class="table table-hover w-100">
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
																									</tr>
																			</thead>
																			<tbody>
																			  
																				<?php foreach(fetchMembres() as $row): ?>
																				<tr class="clickable-row" data-id="<?= $row['id-membre'] ?>">
																				    <td style="color: #ffffff !important;">
																											<?php echo $row['classement']; ?>
																										</td>
																										<td style="color: #ffffff !important;">
																										<?php
																										$id2 = $row['id-membre'];
																										$sql2 = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` = '$id2' ");
																										$row2 = mysqli_fetch_array($sql2); 
																										?>
																											<a style="color:rgb(114, 252, 119) !important;" href="voir-membre.php?id=<?php echo $row['id-membre']; ?>"><?php echo $row2['pseudo']; ?></a>
																										</td>
																										<td style="color: #ffffff !important;">
																											<?php echo $row['option']; ?>
																										</td>
																										<td style="color: #ffffff !important;">
																											<?php echo $row['id-table']; ?>
																										</td>
																										<td style="color: #ffffff !important;">
																											<?php echo $row['id-siege']; ?>
																										</td>
																										<td style="color: #ffffff !important;">
																											<?php echo $row['bounty']; ?>
																										</td>
																				                        <td style="color: #ffffff !important;">
																											<?php echo $row['recave']; ?>
																										</td>
																										<td style="color: #ffffff !important;">
																											<?php echo $row['addon']; ?>
																										</td>
																				</tr>
																				<?php endforeach; ?>
																			</tbody>
																		</table>
																	</div>
																</div>
															</div>
														</div>
													</main>
                                                                                </div>
                                                                                </div>
                                                                                </div>
													 <!-- #region -->                       </div>
																			</div>
																			<form method="post">
																				<table>
																					<tr>
																						<td colspan="3">
																							<select name="lois" class="form-control" required="true">
																								<option value="lois">- Cliquer ici pour ajouter un participant manuellement -
																								</option>
																								<?php $ret2 = mysqli_query($con, "select * from membres ORDER BY `pseudo` ASC");
																								while ($row2 = mysqli_fetch_array($ret2)) {
																								?>
																									<option value="<?php echo htmlentities($row2['id-membre']); ?>">
																										<?php echo htmlentities($row2['pseudo']); ?>
																									</option>;
																									<?php $idm=$row2['id-membre']; ?>
																								<?php } ?>
																							</select>
																						</td>
																						<?php $idm=$row2['id-membre']; ?>
																						<td style="display:none" ; colspan="1">
																							<select name="activi" value="activi" class="form-control" required="false">
																								<option value="<?php echo htmlentities($id); ?>">
																								<!--	<?php echo htmlentities($id); ?> -->
																								</option>
																							</select>
																						</td>
																						<td>
																							<button type="submit" name="submitinsmanu" id="submitinsmanu" class="btn btn-o btn-primary">
																								Valider IcI
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
					</div>
					<?php include('include/footer.php'); ?>
					<?php include('include/setting.php'); ?>
				</div>
				<script src="vendor/jquery/jquery.min.js"></script>
				<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
				<script src="vendor/modernizr/modernizr.js"></script>
				<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
				<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
				<script src="vendor/switchery/switchery.min.js"></script>
				<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
				<script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
				<script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
				<script src="vendor/autosize/autosize.min.js"></script>
				<script src="vendor/selectFx/classie.js"></script>
				<script src="vendor/selectFx/selectFx.js"></script>
				<script src="vendor/select2/select2.min.js"></script>
				<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
				<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
				<script src="assets/js/main.js"></script>
				<script src="assets/js/form-elements.js"></script>
				<script>
					jQuery(document).ready(function() {
						Main.init();
						FormElements.init();
					});
				</script>
				<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
				</script>
				<script src="../js/scripts.js"></script>
				<script>
		$(document).ready(function() {
			const table = $('#employeeTable').DataTable({
				language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/fr-FR.json' },
				dom: '<"row"<"col"B><"col"f>>rt<"row"<"col"i><"col"p>>',
				buttons: ['copy', 'excel', 'pdf', 'print'],
				pageLength: 8,
				order: [[0, 'asc']],
				columnDefs: [
					{ targets: 5, type: 'date-eu' },
					{ targets: 6, className: 'points-cell' }
				],
				responsive: true
			});

			$('#employeeTable').on('click', 'tr.clickable-row', function() {
				window.location.href = 'voir-membre.php?id=' + $(this).data('id');
			});
		});
	</script>



				<script type="text/javascript" language="javascript">
					function afficher1(id) {
						var leCalque = document.getElementById(id);
						var leCalqueE = document.getElementById(id + "E");

						document.getElementById("infosE").className = "rubrique bgImg";
						document.getElementById("inscritsE").className = "rubrique bgImg";
						document.getElementById("t1E").className = "rubrique bgImg";

						leCalqueE.className += " montrer";
						leCalque.className = "btnnavA";
					}

					function afficher2(id) {
						var leCalque = document.getElementById(id);
						var leCalqueE = document.getElementById(id + "E");

						document.getElementById("infosE").className = "rubrique bgImg";
						document.getElementById("inscritsE").className = "rubrique bgImg";
						document.getElementById("t1E").className = "rubrique bgImg";
						document.getElementById("t2E").className = "rubrique bgImg";

						leCalqueE.className += " montrer";
						leCalque.className = "btnnavA";
					}

					function afficher3(id) {
						var leCalque = document.getElementById(id);
						var leCalqueE = document.getElementById(id + "E");

						document.getElementById("infosE").className = "rubrique bgImg";
						document.getElementById("inscritsE").className = "rubrique bgImg";
						document.getElementById("t1E").className = "rubrique bgImg";
						document.getElementById("t2E").className = "rubrique bgImg";
						document.getElementById("t3E").className = "rubrique bgImg";

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

						leCalqueE.className += " montrer";
						leCalque.className = "btnnavA";
					}
				</script>
				<?php
				$onglet = $_GET['onglet'];
				if ($onglet == 'inf') {
				?>
					<script type="text/javascript" language="javascript">
						afficher('infos');
					</script>;
				<?php
				}
				;
				if ($onglet == 'ins') {
				?>
					<script type="text/javascript" language="javascript">
						afficher('inscrits');
					</script>;
				<?php
				}
				;
				if ($onglet == '1') {
				?>
					<script type="text/javascript" language="javascript">
						afficher('t1');
					</script>;
				<?php
				}
				;
				if ($onglet == '2') {
				?>
					<script type="text/javascript" language="javascript">
						afficher('t2');
					</script>;
				<?php
				}
				;
				if ($onglet == '3') {
				?>
					<script type="text/javascript" language="javascript">
						afficher('t3');
					</script>;
				<?php
				}
				;
				if ($onglet == '4') {
				?>
					<script type="text/javascript" language="javascript">
						afficher('t4');
					</script>;
				<?php
				}
				;
				if ($onglet == '' and $nbt == "1") {
				?>
					<script type="text/javascript" language="javascript">
						afficher1('t1');
					</script>;
				<?php
				}
				;

				if ($onglet == '' and $nbt == "2") {
				?>
					<script type="text/javascript" language="javascript">
						afficher2('t1');
					</script>;
				<?php
				}
				;

				if ($onglet == '' and $nbt == "3") {
				?>
					<script type="text/javascript" language="javascript">
						afficher3('t1');
					</script>;
				<?php
				}
				;
				if ($onglet == '' and $nbt == "4") {
				?>
					<script type="text/javascript" language="javascript">
						afficher4('t1');
					</script>;
				<?php
				}
				;
				?>
</body>

</html>
<?php } ?>
