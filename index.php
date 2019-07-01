<?php
require('includes/header.php');
require('includes/footer.php');
include_once('fonctions_mail.php');
require('config/database.php');

if (isset($_POST['forminscription']))
{
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $mail = htmlspecialchars($_POST['mail']);
    $mail2 = htmlspecialchars($_POST['mail2']);
    $mdp = hash('whirlpool', $_POST['mdp']);
    $mdp2 = hash('whirlpool', $_POST['mdp2']);
    $notif_mail = 1;

    if (!empty($_POST['pseudo']) AND !empty($_POST['pseudo']) AND !empty($_POST['mail']) AND !empty($_POST['mail2']) AND !empty($_POST['mdp']) AND !empty($_POST['mdp2']))
    { 
        $pseudolength = strlen($pseudo);
        //securité niveau mdp
        $mdpnotH = $_POST['mdp'];
        $mdp2notH = $_POST['mdp2'];
        if ($pseudolength <= 255 AND $pseudolength >= 6)
        {
            if ($mail == $mail2)
            {
                if (filter_var($mail, FILTER_VALIDATE_EMAIL))
                {
                    $reqmail = $bdd->prepare("SELECT * FROM membres WHERE mail = ?");
                    $reqmail->execute(array($mail));
                    $mailexist =  $reqmail->rowCount();
                    if ($mailexist == 0)
                    {
                        if ((strlen($mdpnotH) >= 8) AND (strlen($mdp2notH) >= 8))
                        {
                            if ((preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $mdpnotH)) AND (preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $mdp2notH)))
                            {
                                if ($mdp == $mdp2)
                                {         
                                    $longueurKey = 15;
                                    $key = "";
                                    for ($i = 1; $i < $longueurKey; $i++)
                                    {
                                        $key .= mt_rand(0, 9);
                                    }
        
                                    $insertmbr = $bdd->prepare("INSERT INTO membres(pseudo, mail, motdepasse, confirmkey, confirme, notif_mail) VALUES (?, ?, ?, ?, ?, ?)");
                                    $insertmbr->execute(array($pseudo, $mail, $mdp, $key, 0, $notif_mail));
                                    
                                    $subject = 'Confirmation de compte';
                                    // $exp = 'elietordjman98@gmail.com';
                                    $exp = $mail;
                                    $message = '
                                    <html>
                                        <body>
                                            <div align="center">
                                            <a href="http://localhost:8888/Camagru%20part2/confirmation.php?pseudo='.urlencode($pseudo).'&key='.$key.'">Confirmez votre compte !</a>
                                            </div>
                                        </body>
                                    </html>
                                    ';
        
                                    sendmail($subject , $message, $exp);
        
                                    $erreur = "Votre compte à bien été crée ! Veuillez confirmer votre compte puis vous connectez.</a>";
                                    // FAIRE ICI LE LOCATION POUR REDIRIGER SUR UNE PAGE QUAND LE COMPTE A ETE CREE
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
                            alert("Adresse mail déjà utilisée !");
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
                        alert("Votre adresse mail n'est pas valide !");
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
                    alert("Vos adresses mail ne correspondent pas !");
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
                alert("Votre pseudo ne doit pas dépasser 255 caractères et doit etre superieur à 6 caractère !");
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
            alert("Tous les champs doivent être complétés !");
            }
            </script>
        <?php
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
    <link rel="stylesheet" href="style/index.css">
    <title>Index/Inscription</title>
</head>
<body>
<br/>

<?php
if (isset($erreur))
{
    ?>
        <div class="alert alert-success" role="alert">
            <?php  echo $erreur ?>
        </div>
    <?php
}
?>

<div class="center">
    
    <div>
        <img src="img/polaroid.png" alt="" class="img_polaroid">
    </div>
    
    <div class="card" style="width:35rem; margin-left: 130px; margin-top: 110px;">
        <center><h4 class="title_card">Camagru</h4></center>
        <br/>
        <form method="POST" action="">
            <div class="form-group">
                <input type="text" class="form-control"  placeholder="Nom d'utilisateur" id="pseudo" name="pseudo" value="<?php if(isset($pseudo)) { echo "$pseudo"; } ?>">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" id="mail" aria-describedby="emailHelp" placeholder="E-mail" name="mail" value="<?php if(isset($mail)) { echo "$mail"; } ?>">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" id="mail2" aria-describedby="emailHelp" placeholder="Confirmation E-mail" name="mail2" value="<?php if(isset($mail2)) { echo "$mail2"; } ?>">
            </div>
            <div class="form-group">
                <input type="password" pattern=".{8,}" required title="8 caracteres minimum (chiffre, maj, minuscule, char special)" class="form-control" placeholder="Mot de passe" id="mdp" name="mdp">
            </div>
            <div class="form-group">
                <input type="password" pattern=".{8,}" required title="8 caracteres minimum et un chiffre (chiffre, maj, minuscule, char special)" class="form-control" placeholder="Reconfirmer le mot de passe" id="mdp2" name="mdp2">
            </div>
            <center><input type="submit" class="btn btn-outline-primary" style="padding-left:19%;padding-right:19%;" name="forminscription" value="Suivant" onclick="myFunction()"></center>
            <br/>
            <center><a href="camagru_disconnect.php"><button type="button" class="btn btn-outline-primary">Accéder directement au Camagru</button></a></center>
            <hr width="90%">
            <center><h6>Vous avez déjà un compte ? <a style="text-decoration:none; color:#88C2F5;" href="connexion.php">Connectez-vous</a></h6></center>
        </form>
    </div>
</div>
</body>
</html>