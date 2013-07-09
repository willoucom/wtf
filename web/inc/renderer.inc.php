<?php



// neutralise la redirection si on est sur la bonne page (ça peux servir)
if ($messages['notification']['redirect'] == basename($_SERVER['SCRIPT_NAME'])) {
    $messages['notification']['redirect'] = '';
    $messages['notification']['redirect_time'] = '';
}


// le rendu est effectué à partir de la variable $messages
if (array_key_exists('notification', $messages)) {
    $affichage['notification'] = $messages['notification'];
}
if (array_key_exists('contenu', $messages)) {
    $affichage['contenu'] = $messages['contenu'];
}
if (array_key_exists('request', $messages)) {
    $affichage['request'] = $messages['request'];
}
if (array_key_exists('rubrique', $messages)) {
    $affichage['rubrique'] = $messages['rubrique'];
}
if (array_key_exists('sous_rubrique', $messages)) {
    $affichage['sous_rubrique'] = $messages['sous_rubrique'];
}


// on retourne le résultat en html via smarty
// imp($messages);
// imp($affichage);
require('../libs/Smarty/Smarty.class.php');
$smarty = new Smarty();
$smarty->setTemplateDir($config['smarty']['template']);
$smarty->setCompileDir($config['smarty']['compile']);
$smarty->setCacheDir($config['smarty']['cache']);
$smarty->setConfigDir($config['smarty']['config']);
$smarty->force_compile = true;

// $smarty->debugging = true;

$smarty->assign('traduction', $textes);
$smarty->assign('affiche_menu', $affiche_menu);
$smarty->assign('affichage', $affichage);
$smarty->assign('tpl', $template);
$smarty->display('index.tpl');


?>
