<?php
session_start();
require('config/database.php');

$idUtilisateur = $_SESSION['id'];
$pseudo = $_SESSION['pseudo'];
$mail = $_SESSION['mail'];

if (isset($_POST['pic']))
{
    $pic = strip_tags($_POST['pic']);
    $req = $bdd->prepare('INSERT INTO images (data, date_creation, id_pseudo, pseudo, mail, type_img) VALUES (:pic, NOW(), :id, :pseudo, :mail, 1)');
    $req->execute(array(':pic'=>$pic, ':id'=>$idUtilisateur, ':pseudo'=>$pseudo, ':mail'=>$mail));

    $response = array('success'=>true,'img'=>$pic);
    
    echo json_encode($response);
}
else
{
    echo "ERROR";
}
?>