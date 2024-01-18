<?php

// Supprime toutes les variables de session
$_COOKIE = array();
setcookie("user",null, -1, "/");
// Détruit la session

// Redirige vers la page de connexion
if(!isset($_COOKIE['user'])){
    header("location: login.php");
}
?>
