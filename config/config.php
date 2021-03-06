<?php
	define( "DS"          , "/" );
	// Configuration général du serveur
	
	
	
	// Ajouter en fin de SERVER_URL le chemin a partir du serveur web
	define( "SERVER_URL"   , 'http://'.$_SERVER["SERVER_NAME"].DS );
	
	//
	define( "BASE_DIR"     , getcwd().DS );
	define( "LIBDIR_PHP"   , BASE_DIR."script/php/");
	define( "LIBDIR_JS"    , SERVER_URL."script/js/");
	
	// Configuration de la Base de donnée
	require_once BASE_DIR."config/config_db.php";
	
	// Configuration de l'autoload
	require_once BASE_DIR."config/autoload.php";
	
?>