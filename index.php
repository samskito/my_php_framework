<?php

	ini_set('display_errors',1);
	ini_set('display_startup_errors',true);
	ini_set('error_reporting',E_ALL);
	
	define('ROOT', $_SERVER['DOCUMENT_ROOT'].'/');
	define('CORE', '_core/');
	define('PHP_FILES', CORE.'classes/');
	define('CONFIG_FILES', ROOT.CORE.'__config/');
	define('PHP_EXT', '.class.php');
	define('BOOTSTRAP', ROOT.PHP_FILES.'Bootstrap'.PHP_EXT);
	define('ERROR', ROOT.PHP_FILES.'Error'.PHP_EXT);
	define('BOOT_OK', true);
	
	use sam\Bootstrap as App;
	use sam\Error as Error;
	
	try {
		if (defined('BOOTSTRAP') && file_exists(BOOTSTRAP)) {
			require_once(BOOTSTRAP);
			$app = new App($_SERVER['REQUEST_URI']);	
		}
		else
			die('DOWN');
	}
	catch (\Exception $e) {
		if (defined('ERROR') && file_exists(ERROR)) {
			require_once(ERROR);
			$error = new Error($e->getMessage());	
		}
		else
			die('DOWN');
	}