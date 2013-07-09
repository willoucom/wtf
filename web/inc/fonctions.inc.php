<?php

/*
 * *************************************************
  La boite à outils du petit développeur fainéant
 * *************************************************
 */

/**
 * Fonction imprimer joli
 * @param mixed $truc Variable à afficher
 * @param string $libelle Titre du bloc
 * @param boolean $die Valide ou non l'arrêt de l'éxécution
 */
function imp($truc, $libelle = '', $die = false) {
    echo '<h3 style="color: red;"><b>var_dump :: <u>' . $libelle . '</u></b></h3>';
    var_dump($truc);
    echo "<hr/>";
    if ($die === true) {
        die;
    }
}

/**
 * Fonction indenter joli
 * @param string $json du json
 */
function indent_json($json) {

    $result = '';
    $pos = 0;
    $strLen = strlen($json);
    $indentStr = '  ';
    $newLine = "\n";
    $prevChar = '';
    $outOfQuotes = true;

    for ($i = 0; $i <= $strLen; $i++) {

        // Grab the next character in the string.
        $char = substr($json, $i, 1);

        // Are we inside a quoted string?
        if ($char == '"' && $prevChar != '\\') {
            $outOfQuotes = !$outOfQuotes;

            // If this character is the end of an element, 
            // output a new line and indent the next line.
        } else if (($char == '}' || $char == ']') && $outOfQuotes) {
            $result .= $newLine;
            $pos--;
            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }

        // Add the character to the result string.
        $result .= $char;

        // If the last character was the beginning of an element, 
        // output a new line and indent the next line.
        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
                $pos++;
            }

            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }

        $prevChar = $char;
    }

    return $result;
}

// permet de conserver les balises html lors d'un htmlentities
function keephtml($string) {
    $res = htmlentities($string);
    $res = str_replace("&lt;", "<", $res);
    $res = str_replace("&gt;", ">", $res);
    $res = str_replace("&quot;", '"', $res);
    $res = str_replace("&amp;", '&', $res);
    return $res;
}

/**
 * Fonction permettant d'assainir une chaine de caractère
 * @param string $string Chaine de caractères à assainir
 * @return string Chaine de caractère sans caractères spéciaux
 */
function sanitize_string($string, $charset = 'utf-8') {
    $str = trim($string);
    //$str = strtolower($str);
    $str = htmlentities($str, ENT_NOQUOTES, $charset);

    $str = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
    $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
    $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères

    $str = str_replace(' ', '_', $str);
    $str = strtolower($str);
    return $str;
}

/**
 * Cette fonction permet de récupérer les traductions depuis le cache ou de les générer, les mettre en cache et les récupérer
 * @param Traduction $traduction Objet traduction
 * @param Array $textes Tableau de textes
 * @param string $module Nom du module
 * @param int $cache_time Temps en seconde du cache
 * @return Array Tableau de traduction
 * @see Traduction
 */
function cache_trad($traduction, $textes = array(), $module = 'General', $cache_time = 1800) {
    if (function_exists('apc_store') && function_exists('apc_fetch') && ini_get('apc.enabled') && ($fetched = apc_fetch($module . '_traductions')) === false) {
        $textes = $traduction->charger($textes, 'Base');
        apc_store($module . '_traductions', serialize($textes), $cache_time); //Mise en cache des traductions pendant 2h
    } else {
        $textes = unserialize($fetched);
    }
    return $textes;
}