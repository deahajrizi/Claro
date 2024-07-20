<?php
$title = "Editions | Claro";
include "components/header.php";
include "components/navbar.php";

// Connexion à la BDD
require_once "db.php";
// Requête pour aller chercher toutes les éditions
$sql = "SELECT * FROM categories";
// Données non sensibles donc requête non préparée
$req = $db->query($sql);
// On récupère toutes les données de la BDD
$categories = $req->fetchALL();
?>
<div id="modalContainer">
    <div id="modal">
        <p id="modalText">Sorry! Log in or create an account to access this page.</p>
        <a id="home" class="buttons modalHome" href="indexNotLogged.php">Home</a>
        <a id="login" class="buttons" href="login.php">Log In</a>
    </div>
</div>
<section id="hero">
    <div id="heroText">
        <h2>Editions.</h2>
        <p id="heroSubtitle">Discover our different editions with diverse themes and styles, each sparking introspection and self-discovery uniquely.</p>
    </div>
</section>
<section class="editions" id="noClick">
    <!-- On boucle sur les 'a' afin de créer des cartes en fonction du nombre de catégories qu'on a -->
    <?php foreach ($categories as $category) : ?>
        <a class="editionCard"><p><?= strtoupper($category->category_name) ?></p></a>
    <?php endforeach; ?>
</section>


<?php
include "components/footer.php";
?>
