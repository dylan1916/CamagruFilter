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

   $idUtilisateur = $_SESSION['id'];

   //supprimer le membres
   $del_req = $bdd->prepare("DELETE FROM membres WHERE id = ?"); 
   $del_req->execute(array($idUtilisateur));
   //supprimer limage
   $del_req = $bdd->prepare("DELETE FROM images WHERE id_pseudo = ?"); 
   $del_req->execute(array($idUtilisateur));
   //rediriger l'utilisateur vers la page de creation de compte apres son ancien compte supprimer
   header("Location: index.php");
      
}
else
{
    header("Location: connexion.php");
}

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="style/header.css">
    <link rel="stylesheet" href="style/footer.css">
    <title>Suppresion de l'utilisateur</title>
</head>