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
 
    if (isset($_POST['newpseudo']) AND !empty($_POST['newpseudo']) AND $_POST['newpseudo'] != $user['pseudo'])
    {
        $newpseudo = htmlspecialchars($_POST['newpseudo']);
        
        if (strlen($newpseudo) >= 6 AND strlen($newpseudo) <= 255)
        {
            $insertpseudo = $bdd->prepare("UPDATE membres SET pseudo = ? WHERE id = ?");
            $insertpseudo->execute(array($newpseudo, $_SESSION['id']));
            header("Location: modify_account.php?id=".$_SESSION['id']);
        }
        else
        {
            ?>
                <script>
                    function myFunction() {
                    alert("Vos mots de passes ne correspondent pVotre pseudo ne doit pas dépasser 255 caractères et doit etre superieur à 6 caractère !");
                    }
                </script>
            <?php
        }
    }

    if (isset($_POST['newmail']) AND !empty($_POST['newmail']) AND $_POST['newmail'] != $user['mail'])
    {
        $newmail = htmlspecialchars($_POST['newmail']);
        $insertmail = $bdd->prepare("UPDATE membres SET mail = ? WHERE id = ?");
        $insertmail->execute(array($newmail, $_SESSION['id']));
        header("Location: modify_account.php?id=".$_SESSION['id']);
    }

    if (isset($_POST['newmdp1']) AND !empty($_POST['newmdp1']) AND isset($_POST['newmdp2']) AND !empty($_POST['newmdp2']))
    {
    $mdpnotH = $_POST['newmdp1'];
    $mdp2notH = $_POST['newmdp2'];
    $mdp1 = hash('whirlpool', $_POST['newmdp1']);
    $mdp2 = hash('whirlpool', $_POST['newmdp2']);

    if ((strlen($mdpnotH) >= 8) AND (strlen($mdp2notH) >= 8))
    {
        if ((preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $mdpnotH)) AND (preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $mdp2notH)))
        {
            if ($mdp1 == $mdp2)
            {
                $insertmdp = $bdd->prepare("UPDATE membres SET motdepasse = ? WHERE  id = ?");
                $insertmdp->execute(array($mdp1, $_SESSION['id']));
                header("Location: modify_account.php?id=".$_SESSION['id']);       
            }
            else
            {
                ?>
                    <script>
                    function myFunction() {
                    alert("Vos mots de passes ne correspondent pas !");
                    }
                    </script>
                <?php
            }
        }
        else
        {

            ?>
                <script>
                function myFunction() {
                alert("Votre mot de passe doit faire au moins 8 charactères (1 majuscule, 1 minuscule, 1 chiffre, 1 charactère spécial) !");
                }
                </script>
            <?php
        }

    }
    else
    {

        ?>
            <script>
            function myFunction() {
            alert("Votre mot de passe doit faire au moins 8 charactères (1 majuscule, 1 minuscule, 1 chiffre, 1 charactère spécial) !");
            }
            </script>
        <?php
    }
}
else
{
    ?>
         <script>
            function myFunction() {
            alert("Veuillez insérer toutes les bonnes modifications !");
            }
        </script>
    <?php
}

    // A VOIR////////////////////////////////////////////////


    // if (isset($_POST['newpseudo']) AND $_POST['newpseudo'] == $user['pseudo'])
    // {
    //     header("Location: modify_account.php?id=".$_SESSION['id']);
    // }

    /////////////////////////////////////////////////////////////////////////////

    $req = $bdd->prepare("SELECT * FROM membres WHERE id = ?");
    $req->execute(array($_SESSION['id']));
    while($d = $req->fetch(PDO::FETCH_OBJ)):

        ///etat de la notif active ou pas
        // echo $d->notif_mail;

    //activation des notif qui envoie des mails
    if (isset($_POST['notif']))
    {
        $insertnotif = $bdd->prepare("UPDATE membres SET notif_mail = ? WHERE id = ?");
        $insertnotif->execute(array(1, $_SESSION['id']));
    }
    else
    {
        ?>
            <script>
                function myFunctionNotif() {
                alert("Vous avez désactiver l'envoie de notifications !");
                }
            </script>
        <?php
    }

    //desactiver les notif des commentaire qui envoie des mails
    if (isset($_POST['no_notif']))
    {
        $insertnotif = $bdd->prepare("UPDATE membres SET notif_mail = ? WHERE id = ?");
        $insertnotif->execute(array(0, $_SESSION['id']));
    }
    else
    {
        ?>
            <script>
                function myFunctionNotif() {
                alert("L'envoie de notifications est activé !");
                }
            </script>
        <?php
        
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
    <link rel="stylesheet" href="style/modifyAccount.css">
    <title>Modify Account</title>
</head>
<body>
<div class="card" style="width:40%; margin-left: 30%; margin-top: 5%;">
        <br/>
        <center><h4 class="title_card">Modification du profil</h4></center>
        <br/>
        <!-- <br/> -->
        <center style="color: #84837D;">Mettez votre compte Camagru à jour.</center>
        <br/>
        <br/>
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Nom d'utilisateur" name="newpseudo" value="<?php echo $user['pseudo']; ?>">
                <br/>
                <input type="email" class="form-control" aria-describedby="emailHelp" placeholder="E-mail" name="newmail" value="<?php echo $user['mail']; ?>">
                <br/>
                <input type="password" pattern=".{8,}" required title="8 caracteres minimum (chiffre, maj, minuscule, char special)" class="form-control" placeholder="Mot de passe" name="newmdp1">
                <br/>
                <input type="password" pattern=".{8,}" required title="8 caracteres minimum (chiffre, maj, minuscule, char special)" class="form-control" placeholder="Reconfirmer le mot de passe" name="newmdp2">
                <br/>
            </div>
            <center><input type="submit" class="btn btn-outline-primary" style="padding-left:17%; padding-right:17%;" value="Mettre à jour" onclick="myFunction()"></center>
            <hr width="80%">
            <center><h6>Vous voulez supprimer votre compte ? <a style="text-decoration:none; color:#88C2F5;" href="delete_account.php?id=<?php echo $_SESSION['id'] ?>">Cliquer içi</a></h6></center>   
        </form>
        <hr width="100%">
        <!-- notifications email -->
        <form action="" method="post">
            <input name="notif" type="submit" id="notif" class="btn btn-outline-primary" style="padding-left:9%; padding-right:9%; margin-left: 4px;" value="Activer les notifications" onclick="myFunctionNotif()">
            <input name="no_notif" type="submit" id="nonotif" class="btn btn-outline-primary" style="padding-left:9%; padding-right:7%;" value="Désactiver les notifications" onclick="myFunctionNotif()">
        </form>
        <br/>
    </div>
    <br/> <br/><br/>
</body>
</html>
<?php endwhile; ?>

<?php
}
else
{
header("Location: connexion.php");
}
?>

