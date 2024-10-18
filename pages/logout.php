<?php
session_start();

// Détruire toutes les variables de session
session_unset();

// Détruire la session
session_destroy();

// Rediriger vers la page de connexion ou toute autre page souhaitée
header("Location: login.php");
exit();



 //bouton poru se déco
// <form action="logout.php" method="post">
//<button type="submit">logout</button>
//</form>