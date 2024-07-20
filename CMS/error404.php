<?php
$title = "ERROR 404 | Claro";
include "components/header.php";
include "components/navbar.php";
http_response_code(404);
?>
<br>
<h1>ERROR 404</h1>
<h3>Page not found</h3>
<br>
<?php
include "components/footer.php";

