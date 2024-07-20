<?php
include('components/header.php');
// Redirection si l'utilisateur n'est pas connecté
if(!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
// Récupération de l'id de l'utilisateur connecté et des paramètres de l'URL
$connected_user = $_SESSION['user']['id'];
$user_id = $_GET['user_id'];
$comment_id = $_GET['comment_id'];
$category_id = $_GET['category_id'];

// Connexion à la base de données
require_once "db.php";

// Requête pour récupérer le commentaire concerné en vérifiant
// qu'il appartienne bien à l'utilisateur connecté
$sql = "SELECT * FROM comments WHERE comment_id = ? AND user_id = ?";
$req = $db->prepare($sql);
$req->bindValue(1, $comment_id, PDO::PARAM_INT);
$req->bindValue(2, $user_id, PDO::PARAM_INT);
$req->execute();
$comment = $req->fetch();

// On vérifie que le commentaire existe
if(!$comment){
    http_response_code(404);
    header("Location: error404.php");
    exit();
}

// On vérifie une dernière fois si l'id de l'auteur du commentaire est bien égal
// à celui de l'utilisateur connecté
if($connected_user == $user_id){
    // On supprime le commentaire
    $sql = "DELETE FROM comments WHERE comment_id = ?;";
    $req = $db->prepare($sql);
    $req->bindValue(1, $comment_id, PDO::PARAM_INT);
    $req->execute();
    // Redirection vers la page de l'édition correspondante
    header('Location: editionPage.php?id=' . $category_id);
} else {
    http_response_code(500);
    die('Sorry, something went wrong. Please try again later.');
}