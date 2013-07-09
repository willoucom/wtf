<?php

include('config.inc.php');
include('inc/fonctions.inc.php');
include('inc/pdo.inc.php');
include('inc/header.inc.php');
// des traitements (lol)


$affiche_menu = 0;

$messages = $utilisateur->deconnexion($messages);

include('inc/footer.inc.php');
include('inc/renderer.inc.php');

?>