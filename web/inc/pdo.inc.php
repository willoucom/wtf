<?php

if(!$config['bdd']['host']){
	die();
}

try {
    $dbh = new PDO('mysql:host='.$config['bdd']['host'].';dbname='.$config['bdd']['base'], $config['bdd']['login'], $config['bdd']['pass']);
	$dbh->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION ); 
	/*
	if(defined(PDO::ATTR_DEFAULT_FETCH_MODE)){
		$dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE , PDO::FETCH_ASSOC); 
	}
	*/
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}

?>