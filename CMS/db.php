<?php

// Constantes d'environnement
const DBHOST = "localhost";
const DBUSER = "root";
const DBPASS = "";
const DBNAME = "claro";

// On crée notre DSN de connection (data source name)
$dsn = "mysql:dbname=".DBNAME.";host=".DBHOST;

try {
    // On instancie PDO
    $db = new PDO($dsn, DBUSER, DBPASS);
    // On configure nos échanges avec la BDD en utf8
    $db->exec("SET NAMES utf8");
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
} catch (PDOException $exception) {
    // On arrête le code et on affiche l'erreur en cas de problème
    die($exception->getMessage());
}