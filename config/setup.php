<?php
require 'database.php';

try
{
    $bdd = new PDO($DB_DSN, $DB_USER, $_DB_PASSWORD);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$bdd->exec("SET NAMES 'UTF8'");
    $bdd->query("DROP DATABASE IF EXISTS camagru");
	$bdd->query("CREATE DATABASE camagru");
    $bdd->query("use camagru");
    
    //table membres
    $bdd->query("CREATE TABLE membres(
                id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
                pseudo VARCHAR(255) NOT NULL,
                mail VARCHAR(255) NOT NULL,
                motdepasse TEXT NOT NULL,
                confirmkey VARCHAR(255) NOT NULL,
                confirme INT(1) NOT NULL,
                notif_mail INT(1) NOT NULL)");

    //table recuperation mot de passe
    $bdd->query("CREATE TABLE recuperation(
                id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
                mail VARCHAR(255) NOT NULL,
                code INT(11) NOT NULL,
                confirme INT(11) NOT NULL)");

    //table des photos prises
    $bdd->query("CREATE TABLE images(
        id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
        data LONGBLOB NOT NULL,
        date_creation datetime NOT NULL,
        id_pseudo INT(11) NOT NULL,
        pseudo VARCHAR(255) NOT NULL,
        mail VARCHAR(255) NOT NULL,
        type_img INT (1) NOT NULL)");

    //table likes
    $bdd->query("CREATE TABLE likes(
        id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
        id_images INT (11) NOT NULL,
        id_pseudo INT (11) NOT NULL)");

    //table dislike
    $bdd->query("CREATE TABLE dislikes(
        id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
        id_images INT (11) NOT NULL,
        id_pseudo INT (11) NOT NULL)");

    //tables commmentaire
    $bdd->query("CREATE TABLE commentaire(
        id INT(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
        pseudo VARCHAR(255) NOT NULL,
        commentaire TEXT NOT NULL,
        id_images INT (11) NOT NULL,
        creation DATETIME NOT NULL,
        mail VARCHAR (255) NOT NULL)");

}
catch (Exception $error)
{
    print "Error while connecting to the new database !: " . $error->getMessage() . "<br/>";
	die();
}
?>
