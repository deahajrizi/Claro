<?php
$title = "My Answers | Claro";
include('components/header.php');
include('components/navbar.php');
if(!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
// On récupère les paramètres de l'URL
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

// On vérifie si la carte existe
if(!$card){
    http_response_code(404);
    echo "Sorry, this card doesn't exist.";
    exit();
}
// Vérification si c'est bien l'utilisateur qui a répondu à cette carte
if($user_id == $card->user_id){
        if(!empty($_POST['userAnswer'])  && isset($_POST['userAnswer'])){
            // On récupère les données entrées
            $userAnswer = strip_tags($_POST['userAnswer']);
            // SQL pour mettre à jour la réponse
            $sql = "UPDATE answers SET answer = :answer, user_id = :user_id WHERE card_id = :card_id";
            $req = $db->prepare($sql);
            $req->bindValue(":answer", $userAnswer);
            $req->bindValue(":user_id", $user_id);
            $req->bindValue(":card_id", $card_id);

            // Exécution de la requête
            if(!$req->execute()){
                http_response_code(500);
                echo "Sorry, something went wrong.";
                exit();
            }
            // Redirection
            header('Location: myAnswers.php?id=' . $category_id );
        }
}
?>
    <section id="answersContainer">
        <div id="answers">
                <div id="answerCardContainer">
                    <div id="backCardAnswers"><p><?= strtoupper($card->content)?></p></div>
                    <form method="post">
                        <input id="userAnswer" name="userAnswer" type="text" value="<?= htmlspecialchars($card->answer)?>">
                        <button class="buttons" id="submitButton" type="submit">Submit</button>
                    </form>
                </div>
        </div>
    </section>
<?php
include 'components/footer.php';
