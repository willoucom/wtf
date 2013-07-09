<?php
// header
session_start();


$affiche_menu = 1;

// Rubrique
$filename = basename($_SERVER['PHP_SELF']);
$filename = explode("_",$filename);
$messages['rubrique'] = $filename[0];
$messages['sous_rubrique'] = $filename[0];



$messages['notification']['message'] = '' ; // message à affiche en cas d’erreur/réussite
$messages['notification']['niveau'] = '' ; // niveau d’alerte (la couleur de l’alerte)
$messages['notification']['redirect'] = '' ; // page suivante (seulement en html)
$messages['notification']['redirect_time'] = '' ; // temps du redirection (seulement en html)
$messages['request'] = $_REQUEST ; 
$messages['retour'] = '' ; // le retour de l’objet sera remplis ici (pour passage à un éventuel autre objet)
$messages['contenu'] = '' ; //  le retour de l’objet qui sera envoyé à smarty pour affichage

$mode = '';
if (isset($_REQUEST) && array_key_exists('m', $_REQUEST)) {
    $mode = $_REQUEST['m'];
    if($mode=='json' && array_key_exists('json', $_REQUEST)) {
    	$messages['request'] = json_decode($_REQUEST['json'],true);    	
    }
}

$debug=0;
if (isset($_REQUEST) && array_key_exists('debug', $_REQUEST)) {
    $debug = $_REQUEST['debug'];
    if($debug!=1) {
    	$debug = 0;
    }
}

unset($messages['request']['__utma']);
unset($messages['request']['__utmz']);
unset($messages['request']['PHPSESSID']);



$basename = basename($_SERVER['SCRIPT_NAME'],'.php');
$template = '';
if($basename != 'index'){
	$template = $basename . ".tpl";
}

// gere la traduction 
require($config['basedir'].'/models/Traduction.class.php');
$traduction = new Traduction($dbh);
$textes = array();
$textes = $traduction->charger($textes,'General');

// gere l'authentification de l'utilisateur
require($config['basedir'].'/models/Utilisateur.class.php');
$utilisateur = new Utilisateur($dbh,$traduction);
$messages = $utilisateur->verifie_connexion($messages);

