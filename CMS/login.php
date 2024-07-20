<?php
$title = "Log In | Claro";
include "components/header.php";
include "components/navbar.php";

// On vérifie si le formulaire est envoyé
if(!empty($_POST)){
    // Ici le formulaire est complet
    if(isset($_POST["email"], $_POST["password"]) && !empty($_POST["email"]) && !empty($_POST["password"])){
        // On vérifie si l'email est bien un email
        if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
            die("L'adresse email est incorrecte");
        }
        // On peut enregistrer notre user
        // On se connecte à la BDD
        require_once "db.php";
        // Requête préparée pour récupérer l'email
        $sql = "SELECT * FROM users WHERE email = :email";
        $req = $db->prepare($sql);
        $req->bindValue(":email", $_POST["email"]);
        $req->execute();
        $user = $req->fetch();

        // Si l'email entré dans le formulaire n'existe pas dans la BDD
        if(!$user){
            die("Invalid informations.");
        }
        // Ici j'ai un utilisateur inscrit en BDD
        // Je compare les mots de passe
        if(!password_verify($_POST["password"], $user->password)){
            die("Invalid informations.");
        }
        // Ici on a un utilisateur qui a le droit de se connecter
        // On stock les infos dans la session
        $_SESSION['user'] = [
            "id" => $user->user_id,
            "username" => $user->username,
            "email" => $user->email
        ];
        header('Location: index.php');
    } else {
        die('Please fill all the required fields.');
    }
}
?>
<section id="hero">
    <div id="heroText">
        <h2>Log In</h2>
        <p id="heroSubtitle">Welcome Back! We're so happy to see you again :)</p>
    </div>
</section>
    <form class="form" method="post">
        <div id="formContent">
            <div class="formField">
                <label for="email">Email Address</label>
                <input type="email" name="email" placeholder="johndoe@gmail.com">
            </div>
            <div class="formField">
                <label for="password">Password</label>
                <input type="password" name="password" placeholder="yourpasswordhere">
                <p id="margin">Don't have an account? <a href="register.php" id="highlightedWord">Sign up here!</a></p>

            </div>
            <button class="buttons" id="submitButton" type="submit">Submit</button>
        </div>
    </form>
<?php
include "components/footer.php";
?>