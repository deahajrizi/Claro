<?php
$title = "Home | Claro";
include "components/header.php";
include "components/navbar.php";

// Connexion à la BDD
require_once "db.php";
// Requête pour afficher les 3 premières cartes qui ont été créées
$sql = "SELECT * FROM cards, categories WHERE cards.category_id = categories.category_id LIMIT 3";
// Données non sensibles donc requête non préparée
$req = $db->query($sql);
// On va chercher toutes les cartes dont on a besoin
$cards = $req->fetchAll();
?>
    <section id="heroMain">
        <div id="heroMainText">
            <h1>Welcome to <span id="specialH1">Claro</span>.</h1>
            <p id="heroSubtitle">Are you ready to explore love, relationships, and self-discovery with our thoughtfully crafted reflection cards?</p>
        </div>
        <div id="heroMainImgContainer">
            <img id="heroMainImg" src="https://doodleipsum.com/700/hand-drawn?i=5c8453011df3db1051a6654c0bff4d07" alt="Sitting by Irene Falgueras" />
        </div>
    </section>
<!-- On boucle sur les 3 cartes -->
    <?php foreach ($cards as $card):?>
    <section id="cardSection">
        <input id="toggleCard" type="checkbox">
        <div id="cardContainer">
            <!-- Afficher le nom de la catégorie et la question -->
            <div id="frontCard"><p><?= strtoupper($card->category_name)?></p></div>
            <div id="backCard"><p><?= strtoupper($card->content)?></p></div>
        </div>
    </section>
<!-- Formulaire de réponse qui ne sauvegarde pas la réponse de l'utilisateur puisqu'il n'est pas connecté -->
    <div id="cardInputContainer">
        <form id="cardForm">
            <input id="cardInput" type="text" name="answer" placeholder="TYPE YOUR ANSWER HERE...">
            <button type="submit" name="homeSubmit" id="submitButton2">SUBMIT</button>
        </form>
    </div>
<?php endforeach;
include "components/footer.php";
?>