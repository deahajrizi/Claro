<?php
$title = "Editions | Claro";
include "components/header.php";
include "components/navbar.php";
// On récupère l'id de la catégorie qui a été passé dans le lien dans 'editions.php'
$id = $_GET['id'];
// Vérification si l'id de la catégorie a été fourni dans l'URL
// Si non, rediriger vers la page des éditions
if(!isset($_GET['id']) || empty($_GET['id'])){
    header("Location: editions.php");
    exit();
}
// Connexion à la BDD
require_once "db.php";


// Requête pour récupérer les informations de la catégorie spécifiée
$sql = "SELECT * FROM categories WHERE category_id = ?";
$req = $db->prepare($sql);
$req->bindvalue(1, $id, PDO::PARAM_INT);
$req->execute();
$categories = $req->fetch();

// Vérification si la catégorie existe bien
if(!$categories){
    http_response_code(404);
    header("Location: error404.php");
    echo "Category not found";
}

// Traitement de la soumission du formulaire de réponse aux cartes
if(!empty($_POST['answers'])){
    // Ici le formulaire est complet
    if(isset($_POST["answer"]) && !empty($_POST["answer"]) && isset($_POST["card_id"])){
        // On récupère la réponse de l'utilisateur en la protégeant
       $answer = strip_tags($_POST["answer"]);
       // On récupère le card_id à travers le hidden input
       $card_id = $_POST["card_id"];
       // SQL pour la requête préparée
        $sql = "INSERT INTO answers (answer, user_id, card_id) VALUES (:answer, :user_id, :card_id)";
        $req = $db->prepare($sql);
        $req->bindvalue(":answer", $answer);
        $req->bindvalue(":user_id", $user_id);
        $req->bindvalue(":card_id", $card_id);

        if(!$req->execute()){
            die("Sorry, your answer could not be sent.");
        } else {
        }
    } else {
        die("Please ensure all fields are filled.");
    }
    }

// Requête pour récupérer toutes les cartes de la catégorie spécifiée
$sql = "SELECT * FROM cards WHERE category_id = :category_id";
$req = $db->prepare($sql);
$req->bindvalue(":category_id", $id, PDO::PARAM_INT);
$req->execute();
$cards = $req->fetchAll();

// Traitement de la soumission du formulaire de commentaire
if(!empty($_POST['comments'])){
    // Ici le formulaire est complet
    if(isset($_POST["content"]) && !empty($_POST["content"]) && isset($_POST["card_id"])){ // Added check for card_id
        // On récupère les données entrées
        $content = strip_tags($_POST["content"]);
        $card_id = $_POST["card_id"]; // Hidden input

        // SQL pour insérer le commentaire dans la BDD
        $sql = "INSERT INTO comments (content, user_id, card_id) VALUES (:content, :user_id, :card_id)";
        $req = $db->prepare($sql);
        $req->bindValue(":content", $content);
        $req->bindValue(":user_id", $user_id);
        $req->bindValue(":card_id", $card_id);

        if(!$req->execute()){
            http_response_code(500);
            die("Ooooops! Comment insertion failed.");
            exit();
        }
    } else {
        die("Your comment was not sent. Please ensure all fields are filled.");
    }
}
// Traitement de la mise à jour d'un commentaire existant
if(!empty($_POST['editCommentInput'])){
    // On récupère les données entrées
    $editedComment = strip_tags($_POST["editCommentInput"]);
    $comment_id = $_POST["editedCommentId"];

    // SQL pour mettre à jour le commentaire
    $sql = "UPDATE comments SET content = :content WHERE comment_id = :comment_id AND user_id = :user_id";
    $req = $db->prepare($sql);
    $req->bindvalue(":content", $editedComment);
    $req->bindvalue(":comment_id", $comment_id);
    $req->bindvalue(":user_id", $user_id);

    // Exécution de la requête
    if(!$req->execute()){
        http_response_code(500);
        echo "Sorry, something went wrong.";
        exit();
    } else {
        // Redirection pour éviter la soumission multiple du formulaire
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}


?>

    <section id="hero">
        <div id="heroText">
            <h2><?= ucfirst($categories->category_name) ?></h2>
            <p id="heroSubtitle">Welcome to the <?= ucfirst($categories->category_name) ?> edition!</p>
        </div>
    </section>

    <section id="editionPage">
        <?php
        // Boucle pour afficher chaque carte dans la catégorie
        foreach ($cards as $card):?>
            <div id="editionCardSection">
                <input id="editionToggleCard" type="checkbox">
                <div id="cardContainer">
                    <div id="frontCard"><p><?= strtoupper($categories->category_name)?></p></div>
                    <div id="backCard"><p><?= strtoupper($card->content)?></p></div> <!-- Changed from $card->content to $card['content'] -->
                </div>
                <div id="editionCardInputContainer">
                    <?php
                    // Requête pour vérifier si la carte a déjà une réponse de l'utilisateur actuel
                    $sql = "SELECT * FROM answers WHERE user_id = :user_id AND card_id = :card_id";
                    $req = $db->prepare($sql);
                    $req->bindValue(":user_id", $user_id, PDO::PARAM_INT);
                    $req->bindValue(":card_id", $card->card_id, PDO::PARAM_INT);
                    $req->execute();
                    $answered = $req->fetch();
                    // Afficher l'input pour répondre uniquement si la carte n'a pas encore de réponse
                    if($answered):?>
                    <div>
                       <p><span style="text-align: center"><span id="currentAnswerDisplay">YOUR CURRENT ANSWER:</span> <span id="currentAnswer"><?= strip_tags($answered->answer) ?></span></p>
                        <a id="editAnswerText" href="updateAnswer.php?answer_id=<?=$answered->answer_id?>&card_id=<?=$card->card_id?>&category_id=<?=$id?>">Edit Answer</a>
                    </div>
                    <?php else: ?>
                    <form id="cardForm" method="post">
                        <input type="hidden" name="answers" value="answers">
                        <input name="answer" id="editionCardInput" type="text" placeholder="TYPE YOUR ANSWER HERE...">
                        <input type="hidden" name="card_id" value="<?= $card->card_id ?>"> <!-- Added hidden input for card_id -->
                        <button type="submit" name="homeSubmit" id="submitButton2">SUBMIT</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>

            <div id="editionCommentsSection">
                <div id="commentsSection">
                    <div id="comments">
                        <?php
                        // Requête pour récupérer les commentaires pour cette carte spécifique
                        $sql = "SELECT comments.*, users.username FROM comments JOIN users ON comments.user_id = users.user_id WHERE comments.card_id = :card_id";
                        $req = $db->prepare($sql);
                        $req->bindValue(":card_id", $card->card_id, PDO::PARAM_INT);
                        $req->execute();
                        $comments = $req->fetchAll();

                        // Affichage de chaque commentaire
                        foreach ($comments as $comment):?>
                            <div id="userComment">
                                <div class="userImageContainer">
                                    <img src="./img/accountIcon.png" alt="">
                                </div>
                                <div id="usernameContent">
                                    <p class="username"><?= strip_tags($comment->username) ?></p>
                                    <?php
                                    // Si l'utilisateur a cliqué sur 'editCommentButton', afficher le formulaire qui permet de mettre à jour le commentaire
                                    if(isset($_POST['editCommentButton']) && $_POST['editCommentButton'] == $comment->comment_id && $comment->user_id == $user_id): ?>
                                    <form id="editingCommentForm"  name="editedComment" method="post">
                                        <input type="hidden" name="editedCommentId" value="<?=$comment->comment_id?>">
                                        <input id="editCommentInput" name="editCommentInput" type="text" value="<?=$comment->content?>">
                                        <button type="submit" id="doneButton" name="doneButton">Done</button>
                                    </form>
                                    <?php else: ?>
                                    <p><?= strip_tags($comment->content) ?></p>
                                    <?php endif;

                                    // Ne montrer que ces options si l'utilisateur connecté est bien celui à qui appartient le commentaire
                                    if($comment->user_id == $user_id) :?>
                                    <div id="editingComment">
                                        <form method="post">
                                            <button type='submit' id="editCommentButton" name="editCommentButton" value="<?= $comment->comment_id ?>">Edit</button>
                                            <a id="deleteCommentButton" href="deleteComment.php?category_id=<?=$categories->category_id?>&comment_id=<?=$comment->comment_id?>&user_id=<?=$comment->user_id?>">Delete</a>
                                        </form>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div id="newCommentSection">
                        <div class="userImageContainer">
                            <img src="./img/accountIcon.png" alt="">
                        </div>
                        <form id="formComments" method="post">
                            <input type="hidden" name="comments" value="comments">
                            <textarea id="writeComment" name="content" placeholder="Write a comment.."></textarea>
                            <input type="hidden" name="card_id" value="<?= $card->card_id ?>">
                            <button type="submit" class="buttons">SEND</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

<?php
include "components/footer.php";
?>