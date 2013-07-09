<?php
// xdebug_start_trace(null,1);

// a desactiver en prod
error_reporting (E_ALL);
ini_set('display_errors',1);

// a modifier en fonction
$config['basedir'] = '#SET#';

// config bdd
$config['bdd']['host'] = '#SET#';
$config['bdd']['base'] = '#SET#';
$config['bdd']['login'] = '#SET#';
$config['bdd']['pass'] = '#SET#';

// config smarty
$config['smarty']['template'] = $config['basedir'].'/template/';
$config['smarty']['compile'] = $config['basedir'].'/cache/compile/';
$config['smarty']['cache'] = $config['basedir'].'/cache/cache/';
$config['smarty']['config'] = $config['basedir'].'/cache/config/';

// verifications de la config smarty
if(!is_dir($config['smarty']['compile'])){
	mkdir($config['smarty']['compile']);
}
if(!is_writable($config['smarty']['compile'])){
	echo $config['smarty']['compile'] . "not writable \n";
}
if(!is_dir($config['smarty']['cache'])){
	mkdir($config['smarty']['cache']);
}
if(!is_writable($config['smarty']['cache'])){
	echo $config['smarty']['cache'] . "not writable \n";
}

?>