<?php
session_start();
require('includes/header1.php');
require('includes/footer.php');
require('config/database.php');

$sessionid = (int) $_SESSION['id'];
if (isset($_SESSION['id']))
{
    $requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
    $requser->execute(array($sessionid));
    $user = $requser->fetch();
}
else
{
  header("Location: connexion.php");
}

///////////////////////////////////////////////////////////////
$msg = "";
$pseudo = $_SESSION['pseudo'];
$mail = $_SESSION['mail'];

//if upload button is pressed
if (isset($_POST['upload']))
{
    //the path to store the uploaded image
    $target = "images/".basename($_FILES['image']['name']);

    //get all the submitted data from the form
    $image = $_FILES['image']['name'];

    $sql = $bdd->prepare("INSERT INTO images(data, date_creation, id_pseudo, pseudo, mail, type_img) VALUES (?, NOW(), ?, ?, ?, ? )");
    $sql->execute(array($image, $sessionid, $pseudo, $mail, 2));

    //push in the folderimages
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target))
    {
        $msg = "Image upload sucessfully";
    }
    else
    {
        $msg = "There was a problem uploading image";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
     <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="style/webcam.css">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/webcam.js"></script>
    <title>Webcam</title>
</head>
<body>
    <!-- filtres -->
    <center>
        <img onclick="myFunction('img/f.png')" id="bird" src="img/f.png" width="100" class="item">
		<img onclick="myFunction('img/glc.png')" id="glc" src="img/glc.png"  width="100" class="item">
		<img onclick="myFunction('img/dog.png')" id="dog" src="img/dog.png"  width="100" class="item">
        <img onclick="myFunction('img/ang.png')" id="angel" src="img/ang.png" width="100" class="item">
        
        <!-- upload d'image -->
         <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="size" value="1000000">
            <input type="file" name="image" id="">
            <input type="submit" name="upload" value="Uploder une image" class="btn btn-outline-primary">
        </form>  
    </center>
   
    <div class="container-fluid"> 
      <div id="content">
        <br/>
        <video id="video" width="450" height="350" autoplay></video><br/>
        <canvas id="canvas" width="450" height="300"></canvas>
      </div>

      <center>
        <div id="button">
            <a href="my_pictures.php?id=<?php echo $sessionid ?>"><button type="button" class="btn btn-outline-primary">Voir mes photos</button></a><br/><br/>
            <button type="button" class="btn btn-outline-primary" id="snap">Prendre une photo</button><br/><br/>
            <button type="button" class="btn btn-outline-primary" id="save">Sauvegarder la photo</button>
        </div>
      </center>   
    <center><br/><br/><div id="result"></div></center>    
    </div>
  
<br/><br/><br/>
</body>
</html>