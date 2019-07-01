<?php
session_start();
require('config/database.php');

if (isset($_GET['t'], $_GET['id']) AND !empty($_GET['t']) AND !empty($_GET['id']))
{

    $getid = (int) $_GET['id'];
    $gett = (int) $_GET['t'];

    $sessionid = $_SESSION['id'];

    $check = $bdd->prepare("SELECT id from images WHERE id = ?");
    $check->execute(array($getid));

    if ($check->rowCount() == 1)
    {
        if ($gett == 1)
        {

            $check_like = $bdd->prepare("SELECT id FROM likes WHERE id_images = ? AND id_pseudo = ?");
            $check_like->execute(array($getid, $sessionid));


            $del = $bdd->prepare("DELETE FROM dislikes WHERE id_images = ? AND id_pseudo = ?");
            $del->execute(array($getid, $sessionid));

            if ($check_like->rowCount() == 1)
            {
                $del = $bdd->prepare("DELETE FROM likes WHERE id_images = ? AND id_pseudo = ?");
                $del->execute(array($getid, $sessionid));

            }
            else
            {

                $ins = $bdd->prepare("INSERT INTO likes(id_images, id_pseudo) VALUES (?, ?)");
                $ins->execute(array($getid, $sessionid));
            }

        }
        elseif ($gett == 2)
        {

            $check_like = $bdd->prepare("SELECT id FROM dislikes WHERE id_images = ? AND id_pseudo = ?");
            $check_like->execute(array($getid, $sessionid));

            $del = $bdd->prepare("DELETE FROM likes WHERE id_images = ? AND id_pseudo = ?");
            $del->execute(array($getid, $sessionid));

            if ($check_like->rowCount() == 1)
            {
                $del = $bdd->prepare("DELETE FROM dislikes WHERE id_images = ? AND id_pseudo = ?");
                $del->execute(array($getid, $sessionid));

            }
            else
            {
                $ins = $bdd->prepare("INSERT INTO dislikes(id_images, id_pseudo) VALUES (?, ?)");
                $ins->execute(array($getid, $sessionid));
            }  
        }
        header('Location: http://localhost:8888/Camagru_part3/camagru_connect.php?id='.$getid);
    }
    else
    {
        header('Location: connexion.php');
    }   
}
else
{
    header('Location: connexion.php');
}

?>