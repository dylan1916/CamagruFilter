<?php
session_start();
require('includes/header.php');
require('includes/footer.php');
require('config/database.php');
include_once('fonctions_mail.php');

if (isset($_GET['section']))
{
    $section = htmlspecialchars($_GET['section']);
}
else
{
    $section = "";
}

if (isset($_POST['recup_submit'], $_POST['recup_mail']))
{
    if (!empty($_POST['recup_mail']))
    {
        $recup_mail = htmlspecialchars($_POST['recup_mail']);
        if (filter_var($recup_mail, FILTER_VALIDATE_EMAIL))
        {
            $mailexist = $bdd->prepare('SELECT id, pseudo FROM membres WHERE mail = ?');
            $mailexist->execute(array($recup_mail));
            $mailexist_count = $mailexist->rowCount();
            if ($mailexist_count == 1)
            {
                $pseudo = $mailexist->fetch();
                $pseudo = $pseudo['pseudo'];
                $_SESSION['recup_mail'] = $recup_mail;
                $recup_code = "";
                
                for ($i=0; $i < 8 ; $i++)
                { 
                    $recup_code .= mt_rand(0, 9);
                }

                $mail_recup_exist = $bdd->prepare("SELECT id FROM recuperation WHERE mail = ?");
                $mail_recup_exist->execute(array($recup_mail));
                $mail_recup_exist = $mail_recup_exist->rowCount();

                if ($mail_recup_exist == 1)
                {
                    $recup_insert = $bdd->prepare("UPDATE recuperation SET code = ? WHERE mail = ?");
                    $recup_insert->execute(array($recup_code, $recup_mail));
                }
                else
                {
                    $recup_insert = $bdd->prepare("INSERT INTO recuperation(mail, code, confirme) VALUES (?, ?, ?)");
                    $recup_insert->execute(array($recup_mail, $recup_code, 0));
                }

                $subject = 'Recuperation de mot de passe';
                // $exp = 'elietordjman98@gmail.com';
                $exp = $recup_mail;
                $message = '
                <html>
                    <body>
                        <div align="center">
                        Bonjour <b>'.$pseudo.'</b><br/>
                        Voici votre code de récupération : <b>'.$recup_code.'</b><br/><br/>
                        Puis cliquer <a href="http://localhost:8888/Camagru_part3/forget_password.php?section=code">ici</a>
                        </div>
                    </body>
                </html>
                ';

                sendmail($subject , $message, $exp);

            }
            else
            {
                ?>
                    <script>
                        function myFunction() {
                        alert("Cette adresse mail n'est pas enregistrée");
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
                    alert("Adresse mail invalide");
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
                alert("Veuillez entrer votre adresse mail !");
                 }
            </script>
        <?php
    }
}


if (isset($_POST['verif_submit'], $_POST['verif_code']))
{
    if (!empty($_POST['verif_code']))
    {
        $verif_code = htmlspecialchars($_POST['verif_code']);
        $verif_req = $bdd->prepare("SELECT id FROM recuperation WHERE mail = ? AND code = ?");
        $verif_req->execute(array($_SESSION['recup_mail'], $verif_code));
        $verif_req = $verif_req->rowCount();

        if ($verif_req == 1)
        {
            $up_req = $bdd->prepare("UPDATE recuperation SET confirme = 1 WHERE mail = ?");
            $up_req->execute(array($_SESSION['recup_mail']));
            header("Location: http://localhost:8888/Camagru_part3/forget_password.php?section=changemdp");
        }
        else
        {
            ?>
                <script>
                    function myFunction() {
                    alert("Code invalide");
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
                alert("Veuillez entrer votre code de confirmation");
                 }
            </script>
        <?php        
    }
}


if (isset($_POST['change_submit']))
{
    if (isset($_POST['change_mdp'], $_POST['change_mdpc']))
    {
        $verif_confirme = $bdd->prepare("SELECT confirme FROM recuperation WHERE mail = ?");
        $verif_confirme->execute(array($_SESSION['recup_mail']));
        $verif_confirme = $verif_confirme->fetch();
        $verif_confirme = $verif_confirme['confirme'];
        
        if ($verif_confirme == 1)
        {
            $mdp = htmlspecialchars($_POST['change_mdp']);
            $mdpc = htmlspecialchars($_POST['change_mdpc']);


            if (!empty($mdp) AND !empty($mdpc))
            {
                if ((strlen($mdp) >= 8) AND (strlen($mdpc) >= 8))
                {
                    if ((preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $mdp)) AND (preg_match('#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)#', $mdpc)))
                    {
                        if ($mdp == $mdpc)
                        {
                            $mdp = hash('whirlpool', $mdp);

                            $ins_mdp = $bdd->prepare("UPDATE membres SET motdepasse = ? WHERE mail = ?");
                            $ins_mdp->execute(array($mdp, $_SESSION['recup_mail']));
                            $del_req = $bdd->prepare("DELETE FROM recuperation WHERE mail = ?");
                            $del_req->execute(array($_SESSION['recup_mail']));
                            header("Location: http://localhost:8888/Camagru_part3/connexion.php");
                        }
                        else
                        {  
                            ?>
                                <script>
                                    function myFunction() {
                                    alert("Vos mots de passes ne correspondent pas");
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
                        alert("Veuillez remplir tous les champs");
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
                    alert("Veuillez valider votre code de vérification qui vous a été envoyé par mail");
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
                alert("Veuillez remplir tous les champs");
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
    <link rel="stylesheet" href="style/forget.css">
    <title>Forget Password</title>
</head>
<body>

<?php if ($section == 'code') { ?>
<div class="card" style="width:40%; margin-left: 30%; margin-top: 7%;">
        <br/>
        <center><h4 class="title_card">Problème de connexion ?</h4></center>
        <br/>
        <br/>
        <center style="color: #84837D;">Code de vérification pour <?= $_SESSION['recup_mail'] ?></center>
        <br/>
        <br/>
        <form method="POST" action="">
            <div class="form-group">
                <input type="number" class="form-control" placeholder="Code de vérification" name="verif_code">
            </div>
            <br/>
            <br/>
            <center><input type="submit" value="Valider" name="verif_submit" onclick="myFunction()" class="btn btn-outline-primary" style="padding-left:17%; padding-right:17%;"></center>
            <br/>
            <hr width="90%">
            <center><a style="text-decoration:none; color:#385185;" href="index.php">Créer un compte</a></center>
            <br/>
        </form>
    </div>

<?php } else if ($section == 'changemdp') { ?>
<div class="card" style="width:40%; margin-left: 30%; margin-top: 7%;">
        <br/>
        <center><h4 class="title_card">Problème de connexion ?</h4></center>
        <br/>
        <br/>
        <center style="color: #84837D;"> Nouveau mot de passe pour <?= $_SESSION['recup_mail'] ?></center>
        <br/>
        <br/>
        <form method="POST" action="">
            <div class="form-group">
                <input type="password" name="change_mdp"pattern=".{8,}" required title="8 caracteres minimum (chiffre, maj, minuscule, char special)" class="form-control"  placeholder="Nouveau mot de passse">
            </div>
            <br/>
            <div class="form-group">
                <input type="password" name="change_mdpc" pattern=".{8,}" required title="8 caracteres minimum (chiffre, maj, minuscule, char special)" class="form-control" placeholder="Confirmation du mot de passse">
            </div>
            <br/>
            <br/>
            <center><input type="submit" value="Changer le mot de passe" name="change_submit" onclick="myFunction()" class="btn btn-outline-primary" style="padding-left:17%; padding-right:17%;"></center>
            <br/>
            <hr width="90%">
            <center><a style="text-decoration:none; color:#385185;" href="index.php">Créer un compte</a></center>
            <br/>
        </form>
    </div>
    
<?php } else { ?>
<div class="card" style="width:40%; margin-left: 30%; margin-top: 7%;">
        <br/>
        <center><h4 class="title_card">Problème de connexion ?</h4></center>
        <br/>
        <br/>
        <center style="color: #84837D;">Entrez votre adresse e-mail et nous vous enverrons un<br/> lien pour récupérer votre compte.</center>
        <br/>
        <br/>
        <form method="POST" action="">
            <div class="form-group">
                <input type="email" class="form-control" aria-describedby="emailHelp" placeholder="E-mail"  name="recup_mail">
            </div>
            <br/>
            <br/>
            <center><input type="submit" class="btn btn-outline-primary" style="padding-left:17%; padding-right:17%;" value="Envoyer un lien de connexion" name="recup_submit" onclick="myFunction()"></center>
            <br/>
            <hr width="90%">
            <center><a style="text-decoration:none; color:#385185;" href="index.php">Créer un compte</a></center>
            <br/>
        </form>
    </div>
<?php } ?>
    
</body>
</html>