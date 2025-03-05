<html>
  <head>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style media="screen">
    .upload{
      width: 140px;
      position: relative;
      margin: auto;
      text-align: center;
    }
    .upload img{
      border-radius: 50%;
      border: 8px solid #DCDCDC;
      width: 125px;
      height: 125px;
    }
    .upload .rightRound{
      position: absolute;
      bottom: 0;
      right: 0;
      background: #00B4FF;
      width: 32px;
      height: 32px;
      line-height: 33px;
      text-align: center;
      border-radius: 50%;
      overflow: hidden;
      cursor: pointer;
    }
    .upload .leftRound{
      position: absolute;
      bottom: 0;
      left: 0;
      background: red;
      width: 32px;
      height: 32px;
      line-height: 33px;
      text-align: center;
      border-radius: 50%;
      overflow: hidden;
      cursor: pointer;
    }
    .upload .fa{
      color: white;
    }
    .upload input{
      position: absolute;
      transform: scale(2);
      opacity: 0;
    }
    .upload input::-webkit-file-upload-button, .upload input[type=submit]{
      cursor: pointer;
    }
  </style>
    <body>
      <?php
      define('DB_SERVER','db5011397709.hosting-data.io');
      define('DB_USER','dbu5472475');
      define('DB_PASS' ,'Kookies7*');
      define('DB_NAME', 'dbs9616600');
      $con = mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
      
$target_dir = "images/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$aimg=$_FILES["fileToUpload"]["name"];
$aniid=$_GET['editid'];

  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
    {
    $query=mysqli_query($con, "UPDATE `membres` SET `photo` = '$aimg' WHERE `membres`.`id-membre` = $aniid");
    echo $aimg."-ok-".$aniid;
	  header('Location: http://poker31.org');
    }
    else
    {
      echo "Sorry, there was an error uploading your file.";
    };

?>
  <script type="text/javascript">window.location.replace("voir-membre.php?id=<?php echo $aniid; ?>");</script> 

