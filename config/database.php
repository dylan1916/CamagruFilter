<?php

$DB_DSN = "mysql:host=localhost;dbname=camagru;charset=utf8";
$DB_USER = "root";
$_DB_PASSWORD = "root";

try 
{
    $bdd = new PDO($DB_DSN, $DB_USER, $_DB_PASSWORD);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $bdd->exec("SET NAMES 'UTF8'");
}
catch (Exception $error)
{
    print "Error while connecting to the new database !: " . $error->getMessage() . "<br/>";
	die();
}

?>