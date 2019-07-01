<?php
 session_start();
 require('includes/header1.php');
 require('includes/footer.php');
 require('config/database.php');

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

//afficher les images qui ont etait ajouter en BDD
$req = $bdd->prepare('SELECT * FROM images ORDER BY id DESC');
$req->execute();
while($d = $req->fetch(PDO::FETCH_OBJ)):

    $getid = (int) $_SESSION['id'];
    
    $article = $bdd->prepare("SELECT * FROM images WHERE id = ?");
    $article->execute(array($getid));
    $article = $article->fetch();

    $id = $article['id'];

    $likes = $bdd->prepare("SELECT id FROM likes WHERE id_images= ?");
    $likes->execute(array($d->id));
    $likes = $likes->rowCOunt();

    $dislikes = $bdd->prepare("SELECT id FROM dislikes WHERE id_images = ?");
    $dislikes->execute(array($d->id));
    $dislikes = $dislikes->rowCOunt();

    // commentaire

    $pseudo = $_SESSION['pseudo'];
    $idImg= $d->id;
    $authorImg = $d->pseudo;
    $mail = $d->mail;
    
    $commentaires = $bdd->prepare("SELECT * FROM commentaire WHERE id_images = ? ORDER BY id DESC");
    $commentaires->execute(array($idImg));

    ///pour le type img upload ou webcam
    $typeImg = $d->type_img;

?>

<!DOCTYPE html>
<html lang="en">
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
<body>
<center>
    <br/><br/>
    <!-- image -->
    <div class="card" style="width: 35rem;">
    <?php if ($typeImg == 1) {?>
          <div class="pngcontainer1"> 
            <img src="img/torah.png"/>
        </div> 
        <img class="card-img-top" src="<?php echo $d->data ?>" alt="Card image cap" style="border: 1px solid black;"> 
    
    <?php } else { ?>
    <!-- ////aranger le /images -->
        <img class="card-img-top" src="images/<?php echo $d->data;?>"  alt="Card image cap" style="border: 1px solid black">
    <?php } ?>
    <!-- //////////////////////// -->
    <div class="card-body">
    <!-- like -->
    <ul class="list-group list-group-flush">
        <a style="text-decoration:none;" href="action.php?t=1&id=<?php echo $d->id;?>"><li class="list-group-item">J'aime (<?= $likes ?>)</li></a>
        <a style="text-decoration:none;" href="action.php?t=2&id=<?php echo $d->id;?>"><li class="list-group-item">J'aime pas (<?= $dislikes ?>)</li></a>
    </ul>
    <br/>

    <!-- commentaire -->

    <h6 style="font-family:fantasy; color:#3897EF;">Les commentaires</h6>        
        <?php
        while ($c = $commentaires->fetch())
        {
        ?>
        <hr>
        <p class="card-text" align="left" style="font-family:fantasy;">
        <b><?= $c['pseudo'];?> : </b><?= $c['commentaire'];?><br/>    
        <?php
        } 
        ?>
        <br/><br/>
        </p>
    <a style="text-decoration:none;" href="add_comment.php?id=<?php echo $idImg ?>&p=<?php echo $authorImg?>&m=<?php echo $mail ?>"><input type="submit" value="Ajouter un commentaire" class="btn btn-outline-primary" name="add_commentaire"></a>

    <!-- //// -->

    </div>
    </div>
    <br/><br/><br/>
</center>
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
    <link rel="stylesheet" href="style/camagruDisconnect.css">
    <title>Camagru Connect</title>
</head>

