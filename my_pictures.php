<?php
session_start();
require('includes/header1.php');
require('includes/footer.php');
require('config/database.php');

// verificatiob si l'utilisateur est connecté
if (isset($_SESSION['id']))
{
    $requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
    $requser->execute(array($_SESSION['id']));
    $user = $requser->fetch();
      
}
else
{
    header("Location: connexion.php");
}

$id = $_SESSION['id'];

$req = $bdd->prepare('SELECT * FROM images WHERE id_pseudo = ? ORDER BY id DESC');
$req->execute(array($id));
while($d = $req->fetch(PDO::FETCH_OBJ)):

$idImg = $d->id;
///pour le type img upload ou webcam
$typeImg = $d->type_img;

?>

<!DOCTYPE html>
<html lang="en">
<body>
<br/><br/>
    <center>
        <div class="card" style="width: 32rem;">
            <?php if ($typeImg == 1) {?>
                <div class="pngcontainer1"> 
                    <img src="img/torah.png"/>
                </div> 
            <img style="border: 1px solid black" src="<?php echo $d->data;?>" class="card-img-top">
            <?php } else { ?>
            <!-- ////aranger le /images -->
            <img class="card-img-top" src="images/<?php echo $d->data;?>"  alt="Card image cap" style="border: 1px solid black">
            <?php } ?>
            <div class="card-body">
                <h5 class="card-title" style="font-family:fantasy; color:#3897EF;">Mes photos prises récemment</h5>
                <a href="delete_picture.php?id=<?php echo $idImg ?>"><input type="submit" name="delete" class="btn btn-outline-primary"  value="Supprimer cette photo" onclick="myFunction()"></a>
            </div>
        </div>
    </center>
    <br/><br/><br/><br/>
</body>
</html>

<?php endwhile;?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/footer.css">
    <title>Mes photos</title>
</head>
<style type="text/css">
.pngcontainer1, .pngcontainer1 img {
  position: relative;
  
}
.pngcontainer1 img {
  z-index: 101;
}
.pngcontainer1 img:first-child {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 100;
}
</style>