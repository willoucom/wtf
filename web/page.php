<?php

include('config.inc.php');
include('inc/fonctions.inc.php');
include('inc/pdo.inc.php');
include('inc/header.inc.php');

$textes = $traduction->charger($textes,'General');

// echo "hello world";


include('inc/footer.inc.php');
include('inc/renderer.inc.php');

?>