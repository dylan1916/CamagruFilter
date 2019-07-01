<?php
session_start();
require('includes/header1.php');
require('includes/footer.php');
require('config/database.php');
include_once('fonctions_mail.php');

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


///////////////////////////////////////////////////////////////////////////
$requser = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
$requser->execute(array($_SESSION['id']));
 while($d = $requser->fetch(PDO::FETCH_OBJ)):

    ///etat de la notif active ou pas
    $notif_mail = $d->notif_mail;
    // echo $notif_mail;
/////////////////////////////////////////////////////////////////////////////

//verification pour ajouter le commentaire en bdd
if (isset($_GET['id'], $_GET['p'], $_GET['m']) AND !empty($_GET['id']) AND !empty($_GET['p']) AND !empty($_GET['m']))
{
    $idImg = $_GET['id'];
    //le pseudo de la personne qui va ajouter le commentaire et le mail aussi
    $pseudo = $_SESSION['pseudo'];
    $mail = $_SESSION['mail'];
    //le pseudo de la personne qui a ajouté le post image et le mail aussi
    $pseudoImg = $_GET['p'];
    $email = $_GET['m'];

    if (isset($_POST['valider']))
    {
        if (isset($_POST['phrase']) AND !empty($_POST['phrase']))
        {
            $commentaire = htmlspecialchars($_POST['phrase']);

            $check = $bdd->prepare("SELECT id from images WHERE id = ?");
            $check->execute(array($idImg));

            if ($check->rowCount() == 1)
            {
                $check_like = $bdd->prepare("SELECT id FROM commentaire WHERE id_images = ? AND pseudo = ?");
                $check_like->execute(array($idImg, $pseudo));

                if ($check_like)
                {
                    if ($notif_mail == 1)
                    {
                    $ins= $bdd->prepare("INSERT INTO commentaire(pseudo, commentaire, id_images, creation, mail) VALUES(?, ?, ?, NOW(), ?)");
                    $ins->execute(array($pseudo, $commentaire, $idImg, $email));

                    $subject = 'Nouveau comentaire sur votre post Camagru';
                                $exp = $email;
                                $message = '
                                <html>
                                    <body>
                                        <div align="center">
                                            <h4>Votre nouveau post sur Camagru vien d\'être commenter par '.$pseudo.' ('.$mail.') .</h4>
                                        </div>
                                    </body>
                                </html>
                                ';
    
                                sendmail($subject , $message, $exp);   

                    }
                    elseif ($notif_mail == 0)
                    {
                        $ins= $bdd->prepare("INSERT INTO commentaire(pseudo, commentaire, id_images, creation, mail) VALUES(?, ?, ?, NOW(), ?)");
                        $ins->execute(array($pseudo, $commentaire, $idImg, $email));
                    }    
                }
                else
                {
                    header("Location: camagru_connect.php");
                }

            }
            else
            {
                header("Location: camagru_connect.php");

            }

        }
        else
        {
            ?>
                <script>
                    function myFunction() {
                    alert("Veuillez entrez un commentaire puis le valider !");
                    }
                </script>
            <?php
        }
    }
    else
    {
       ///l'image ajouter riem mettre ici
    }

}
else
{
    ?>
        <script>
            function myFunction() {
            alert("Veuillez vous connectez pour pouvoir commenter une image !");
            }
        </script>
    <?php
}

$req = $bdd->prepare('SELECT * FROM images WHERE id = ?');
$req->execute(array($idImg));
while($d = $req->fetch(PDO::FETCH_OBJ)):

?>
    
<!DOCTYPE html>
<html lang="en">
<body>
    <br/>
    <center>
        <div class="card" style="width: 32rem;">
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
                <h5 class="card-title" style="font-family:fantasy; color:#3897EF;">Commenter l'image publier par <?php echo $pseudoImg ?></h5>
                <center>
                <form action="" method="POST">
                    <textarea name="phrase" cols="55" rows="5" placeholder="Ajouter votre commentaire..."></textarea><br/><br/>
                    <input type="submit" name="valider" class="btn btn-outline-primary"  value="Valider votre commentaire" onclick="myFunction()">
                </form>
                </center>
            </div>
        </div>
    </center>
</body>
</html>

<?php endwhile;?>
<?php endwhile;?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="style/camagruDisconnect.css">
    <title>Camagru ConnectAddComment</title>
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