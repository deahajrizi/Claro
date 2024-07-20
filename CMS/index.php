<?php
$title = "Home | Claro";
include "components/header.php";
$page = "indexNotLogged.php";
include "components/navbar.php";

// Connexion à la BDD
require_once "db.php";

// Requête SQL pour générer une carte aléatoirement qui n'a pas encore de réponse
// On met en relation les tables cards & categories par le 'category_id'
// On filtre les 'card_id' qui sont déjà présents dans la table 'answers' pour le 'user_id' donné
$sql = "
    SELECT * FROM cards
    JOIN categories ON cards.category_id = categories.category_id
    WHERE cards.card_id NOT IN (
        SELECT card_id FROM answers WHERE user_id = :user_id
    )
    ORDER BY RAND()
";

// Préparer et exécuter la requête
$req = $db->prepare($sql);
$req->bindValue(":user_id", $user_id, PDO::PARAM_INT);
$req->execute();
$card = $req->fetch(PDO::FETCH_ASSOC);

// Si aucune carte n'est trouvée, définir un message par défaut
if(!$card) {
    $card = [
            'category_name' => 'No more cards available',
            'content' => 'You have answered all the cards. Come back later for more!'
    ];
}
// Vérification du formulaire des réponses
if(!empty($_POST['answers'])){
    // Ici le formulaire est complet
    if(isset($_POST["answer"]) && !empty($_POST["answer"]) && isset($_POST["card_id"])){
        // On récupère la réponse de l'utilisateur en la protégeant contre les failles et injections
        $answer = strip_tags($_POST["answer"]);
        // On récupère le card_id à travers le hidden input plus bas
        $card_id = $_POST["card_id"];
        // SQL pour la requête préparée
        $sql = "INSERT INTO answers (answer, user_id, card_id) VALUES (:answer, :user_id, :card_id)";
        // On prépare la requête
        $req = $db->prepare($sql);
        // On bind les valeurs
        $req->bindvalue(":answer", $answer);
        $req->bindvalue(":user_id", $user_id);
        $req->bindvalue(":card_id", $card_id);

        // On exécute la requête + traite les erreurs
        if(!$req->execute()){
            die("Sorry, your answer could not be sent.");
        } else {
        }
    } else {
        die("Please ensure all fields are filled.");
    }
}

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
<section id="cardSection">
    <input id="toggleCard" type="checkbox">
    <div id="cardContainer">
        <!-- Afficher le nom de la catégorie et la question -->
        <div id="frontCard"><p><?= strtoupper($card['category_name'])?></p></div>
        <div id="backCard"><p><?= strtoupper($card['content'])?></p></div>
    </div>
</section>
<!-- Enlever le formulaire pour répondre aux cartes si la carte n'existe pas -->
<?php if ($card['category_name'] !== 'No more cards available'): ?>
<div id="cardInputContainer">
    <form method="post" id="formComments">
        <input type="hidden" name="answers" value="answers">
        <input id="cardInput" type="text" name="answer" placeholder="TYPE YOUR ANSWER HERE...">
        <!-- Si la carte n'existe pas, prendre un id null -->
        <input type="hidden" name="card_id" value="<?= $card['card_id'] ?? ''?>">
        <button type="submit" name="homeSubmit" id="submitButton2">SUBMIT</button>
    </form>
</div>
<?php
endif;
include "components/footer.php";
?>