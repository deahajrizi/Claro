<?php
// On démarre la session
session_start();
// On empêche l'utilisateur non connecté de venir ici par l'URL
if(!isset($_SESSION['user'])){
    header('Location: login.php');
}
// On supprime la partie "user" de la session
unset($_SESSION['user']);
// On redirige l'utilisateur
header('Location: login.php');