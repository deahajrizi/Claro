<?php
// Redirection de l'utilisateur pour qu'il ne puisse pas accéder à certaines pages avec l'URL
// $page est défini lorsque besoin, sinon rediriger vers login
if(!isset($_SESSION['user'])){
    if(!isset($page)){
        $page = "login.php";
    } else {
        header("Location: ". $page);
    }
}

?>

<nav id="mainNav" role="navigation" aria-label="main navigation">
    <div id="navBrand">
        <div id="logo">Claro</div>
    </div>
    <div id="navMenu">

        <a id="home"
           <?php if(isset($_SESSION["user"])): ?>
               href="index.php"
            <?php else: ?>
           href="indexNotLogged.php"
           <?php endif; ?>
        >Home</a>
        <a id="editions"
           <?php if(isset($_SESSION["user"])): ?>
           href="editions.php"
           <?php else: ?>
                href="editionsNotLogged.php"
           <?php endif; ?>
        >Editions</a>
    </div>
    <div id="navButtons">
        <?php if(!isset($_SESSION["user"])): ?>
        <a id="register" class="buttons" href="register.php">Sign Up</a>
        <a id="login" class="buttons" href="login.php">Log In</a>
        <?php else: ?>
            <div id="profileIconContainer">
                <img src="./img/accountIcon.png" alt="Icon of a person's silhouette">
            </div>
            <div id="loggedSideNav">
                <ul>
                    <li><a href="myAnswersEditions.php">MY ANSWERS</a></li>
                    <a id="login" class="buttons" href="logout.php">Log Out</a>

                </ul>
            </div>
        <?php endif; ?>
    </div>

</nav>
