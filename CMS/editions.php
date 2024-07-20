<?php
$title = "Editions | Claro";
include "components/header.php";
$page = "editionsNotLogged.php";
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
<section id="hero">
    <div id="heroText">
        <h2>Editions.</h2>
        <p id="heroSubtitle">Discover our different editions with diverse themes and styles, each sparking introspection and self-discovery uniquely.</p>
    </div>
</section>
<section class="editions">
    <!-- On boucle sur les 'a' afin de créer des cartes en fonction du nombre de catégories qu'on a -->
    <?php foreach ($categories as $category) : ?>
    <!-- On récupère l'id de l'édition choisie pour diriger vers l'editionPage correspondante -->
    <a href="editionPage.php?id=<?= $category->category_id ?>" class="editionCard"><p><?= strtoupper($category->category_name) ?> EDITION</p></a>
    <?php endforeach; ?>
</section>
<?php
include "components/footer.php";
?>
