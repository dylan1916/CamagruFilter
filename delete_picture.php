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

$idImg = $_GET['id'];
// echo $idImg;

$req = $bdd->prepare('SELECT * FROM images WHERE id = ?');
$req->execute(array($idImg));
while($d = $req->fetch(PDO::FETCH_OBJ)):

//suppression de l'image
if (isset($_POST['delete']))
{
    $del_req = $bdd->prepare("DELETE FROM images WHERE id = ?");
    $del_req->execute(array($idImg));

    ?>
         <script>
            function myFunction() {
            alert("Le post a bien était supprimer !");
            }
        </script>
    <?php
}
else
{
    ?>
         <script>
            function myFunction() {
            alert("Veuillez cliquer sur le bouton pour pouvoir supprimer cette image !");
            }
        </script>
    <?php
}
?>

<!DOCTYPE html>
<html lang="en">
<body>

<br/><br/>
    <center>
        <div class="card" style="width: 40rem;">
            <?php if ($d->type_img == 1) {?>
            <div class="pngcontainer1"> 
                <img src="img/torah.png"/>
            </div> 
            <img style="border: 1px solid black" src="<?php echo $d->data;?>" class="card-img-top">
            <?php } else { ?>
            <!-- ////aranger le /images -->
            <img class="card-img-top" src="images/<?php echo $d->data;?>"  alt="Card image cap" style="border: 1px solid black">
            <?php } ?>
            <div class="card-body">
                <h5 class="card-title" style="font-family:fantasy; color:#3897EF;">Voulez-vous vraiment supprimer ce post ?</h5>
                <form action="" method="post">
                    <input type="submit" name="delete" class="btn btn-outline-primary"  value="Cliquer içi" onclick="myFunction()">
                </form>
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
    <title>Suppresion de l'image</title>
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