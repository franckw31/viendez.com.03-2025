<link href="style.css" rel="stylesheet">
<?php
session_start();
error_reporting(E_ALL ^ E_WARNING);
include('panel/include/config.php');

$num_membre = $_GET['membre']; // get value
$num_activite = $_GET['activite']; // get value
$code = $_GET['code']; // get value

$sql = mysqli_query($con, "SELECT * FROM `membres` WHERE `id-membre` = '$num_membre' ");
$result = mysqli_fetch_array($sql) ;
$email = $result['email'];
$mdp = $result['password'];
$codev = $result['CodeV'];
if (isset($_SESSION['login'])) {
  header("Location: indexnav.html");
  die();
}
include('config.php');
$msg = "";
$Error_Pass = "";
if (isset($_GET['Verification'])) {
  $raquet = mysqli_query($conx, "SELECT * FROM membres WHERE CodeV='{$_GET['Verification']}'");
  if (mysqli_num_rows($raquet) > 0) {
    $query = mysqli_query($conx, "UPDATE membres SET verification='1' WHERE CodeV='{$_GET['Verification']}'");
    if ($query) {
      $rowv = mysqli_fetch_assoc($raquet);
//      header("Location: /panel/dashboard.php?id='{$rowv['id']}'");
	  header("Location: /indexnav.html");
    }else{
      header("Location: /index.php");
    }
  } else {
    header("Location: /index.php");
  }
}
if (isset($_POST['submit'])) {
  $email = mysqli_real_escape_string($conx, $_POST['email']);
//  $Pass = mysqli_real_escape_string($conx, md5($_POST['Password']));
    $Pass = mysqli_real_escape_string($conx, $_POST['Password']);

  $sql = "SELECT * FROM membres WHERE email='{$email}' and Password='{$Pass}'";
  $resulte = mysqli_query($conx, $sql);
  if (mysqli_num_rows($resulte) === 1) {
    $row = mysqli_fetch_assoc($resulte);
    if ($row['verification'] === '1') {
		$_SESSION['login']=$row['pseudo'];
		$_SESSION['id']=$row['id-membre'];
  //    $_SESSION['Email_Session']=$email;
      header("Location: /indexnav.html");
    }else{$msg = "<div class='alert alert-info'>Compte non validé par retour Email</div>";}
  }else{
    $msg = "<div class='alert alert-danger'>Email ou Mot de Passe non reconnu</div>";
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- <script src="https://kit.fontawesome.com/64d58efce2.js" crossorigin="anonymous"></script>
-->
  <link rel="stylesheet" href="style.css" />
  <title>Formulaire de connexion</title>
  <style>
    .alert {
      padding: 1rem;
      border-radius: 5px;
      color: white;
      margin: 1rem 0;
      font-weight: 500;
      width: 65%;
    }

    .alert-success {
      background-color: #42ba96;
    }

    .alert-danger {
      background-color: #fc5555;
    }

    .alert-info {
      background-color: #2E9AFE;
    }

    .alert-warning {
      background-color: #ff9966;
    }
    .Forget-Pass{
      display: flex;
      width: 65%;
    }
    .Forget{
      color: #2E9AFE;
      font-weight: 500;
      text-decoration: none;
      margin-left: auto;
      
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="forms-container">
      <div class="signin-signup">
        <form action="" method="POST" class="sign-in-form">
          <h2 class="title">Saisissez vos identifiants</h2>
          <?php echo $msg ?>
          <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" name="email" placeholder="Email" />
          </div>
          <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" name="Password" placeholder="Mot de Passe" />
          </div>
          <div class="Forget-Pass">
            <a href="/Forget.php" class="Forget" style="align:left">Mot de passe oublié</a>
            <a href="/SignUp.php" class="Forget" id="sign-in-btn" style="align:left">ou Création compte</a>
          </div>
          <input type="submit" name="submit" value="Valider" class="btn solid" />          
        </form>
      </div>
    </div>
  </div>
  <script src="app.js"></script>
</body>

</html>
