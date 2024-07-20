<?php
include('components/header.php');
// On redirige l'utilisateur s'il n'est pas connecté
if(!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
// On récupère les paramètres depuis l'URL
$card_id = $_GET['card_id'];
$category_id = $_GET['category_id'];

// On se connecte à la BDD
require_once "db.php";

// On récupère l'article qu'on veut modifier dans la BDD avec une requête
$sql = "SELECT * FROM cards JOIN answers ON cards.card_id = answers.card_id WHERE cards.card_id = ? AND answers.user_id = ?";
$req = $db->prepare($sql);
$req->bindValue(1, $card_id, PDO::PARAM_INT);
$req->bindValue(2, $user_id, PDO::PARAM_INT);
$req->execute();
$card = $req->fetch();

// On vérifie si le poste est vide
if(!$card){
    http_response_code(404);
    echo "Sorry, this card doesn't exist.";
    exit();
}
// On vérifie si l'id de l'auteur de la réponse est bien
// égal à l'id de l'utilisateur connecté
if($user_id == $card->user_id){
    // Requête pour supprimer la réponse
    $sql = "DELETE FROM answers WHERE card_id = ?";
    $req = $db->prepare($sql);
    $req->bindValue(1, $card_id, PDO::PARAM_INT);
    $req->execute();
    // Redirection
    header('Location: myAnswers.php?id=' . $category_id);
} else {
    http_response_code(500);
    die('Sorry, something went wrong. Please try again later.');
}