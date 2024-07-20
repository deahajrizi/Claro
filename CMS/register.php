<?php
$title = "Sign Up | Claro";
include "components/header.php";
include "components/navbar.php";

// On vérifie si le formulaire est envoyé
if(!empty($_POST)){
    // Ici le formulaire est envoyé
    // On vérifie que tous les champs soient remplis
    if(isset($_POST["username"], $_POST["email"], $_POST["password"]) && !empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["password"])){
        // On peut récupérer les données et les protéger
        $username = strip_tags($_POST["username"]);

        // On vérifie si l'email est bien un email
        if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
            die("L'adresse email est incorrecte");
        }
        // On hash le mot de passe
        $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

        // On peut enregistrer notre user
        // On se connecte à la BDD
        require_once "db.php";

        // Requête préparée
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $req = $db->prepare($sql);
        $req->bindValue(":username", $username);
        $req->bindValue(":email", $_POST["email"]);
        $req->bindValue(":password", $password);
        $req->execute();

        // on récupère l'ID de l'utilisateur créé
        $id = $db->lastInsertId();
        // on stock dans $_SESSION les informations de l'utilisateur
        $_SESSION['user'] = [
            "id" => $id,
            "username" => $username,
            "email" => $_POST["email"]
        ];

        // On redirige l'utilisateur vers la page blog
        header("Location: index.php");
    } else {
        die("Please fill in all the fields.");
    }
}
?>
<section id="hero">
    <div id="heroText">
        <h2>Sign Up</h2>
        <p id="heroSubtitle">Create an account and unlock access to all editions and cards, community features and many more.</p>
    </div>
</section>
<form class="form" method="post">
    <div id="formContent">
        <div class="formField">
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="johndoe123">
        </div>
        <div class="formField">
            <label for="email">Email Address</label>
            <input type="email" name="email" placeholder="johndoe@gmail.com">
        </div>
        <div class="formField">
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="yourpasswordhere">
            <p id="margin">Already have an account? <a href="login.php" id="highlightedWord">Log in here!</a></p>
        </div>
        <button class="buttons" id="submitButton" type="submit">Submit</button>
    </div>
</form>
<?php
include "components/footer.php";
?>