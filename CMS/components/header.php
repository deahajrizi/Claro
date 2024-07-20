<?php
session_start();
// On récupère l'id de l'utilisateur connecté
$user_id = $_SESSION['user']['id'];

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="./CSS/style.css" type="text/css">
    <title><?= $title?? "Claro" ?></title>
</head>
<body>