<?php
$title = "My Answers | Claro";
include "components/header.php";
$page = "error404.php";
include "components/navbar.php";

// Connexion à la BDD
require_once "db.php";

// On vérifie la présence d'un id dans l'URL
if(!isset($_GET['id']) || empty($_GET['id'])){
    header("Location: myAnswersEditions.php");
    exit();
}
// On va chercher le paramètre 'id' de l'URL
$id = $_GET['id'];

// Requête pour récupérer les informations de la catégorie choisie
$sql = "SELECT * FROM categories WHERE category_id = ?";
$req = $db->prepare($sql);
$req->bindvalue(1, $id, PDO::PARAM_INT);
$req->execute();
$category = $req->fetch();
// Redirection si la catégorie n'existe pas
if (!$category) {
    header("Location: myAnswersEditions.php");
    exit();
}
// Requête pour récuperer les cartes et réponses associées pour la catégorie choisie
$sql = "SELECT cards.*, answers.answer
        FROM cards
        JOIN answers ON cards.card_id = answers.card_id 
        WHERE cards.category_id = :category_id AND answers.user_id = :user_id";
$req = $db->prepare($sql);
$req->bindValue(":category_id", $id, PDO::PARAM_INT);
$req->bindValue(":user_id", $user_id, PDO::PARAM_INT);
$req->execute();
$cards = $req->fetchAll();
?>
    <section id="hero">
        <div id="heroText">
            <h2>Your answers for the <span id="specialH1"><?= ucfirst($category->category_name)?></span> edition. </h2>
        </div>
    </section>
<section id="answersContainer">
    <div id="answers">
        <!-- On boucle sur toutes les cartes de la catégorie choisie ayant une réponse -->
        <?php foreach ($cards as $card):?>
        <div id="answerCardContainer">
            <div id="backCardAnswers"><p><?= strtoupper($card->content)?></p></div>
            <p id="userAnswer"><?= strip_tags($card->answer)?></p>
            <div id="answerIcons">
                <!-- Liens pour éditer et supprimer les réponses -->
                <a href="updateAnswer.php?card_id=<?=$card->card_id?>&category_id=<?=$id?>"><img src="img/editIcon.png" alt="Icon of a pencil"></a>
                <a href="deleteAnswer.php?card_id=<?=$card->card_id?>&category_id=<?=$id?>"><img src="img/deleteIcon.png" alt="Icon of a trashcan"></a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</section>


<?php
include "components/footer.php";

