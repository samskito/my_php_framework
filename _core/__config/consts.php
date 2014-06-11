<?php
	
	if (!defined('ROOT') || !defined('PHP_FILES') || !defined('PHP_EXT')) {throw new \Exception('Can not load configuration file');}
	
	// MAIN
	define('CONTROLLERS', ROOT.PHP_FILES.'controllers/');
	define('MODELS', ROOT.PHP_FILES.'models/');
	
	// FOLDERS
	define('PUBLIC_DIR', '_public/');
	define('VIEWS', ROOT.PUBLIC_DIR.'views/');
	define('TEMPLATE', 'template/');
	
	// SITE 
	define('SITE_URL', '');
	
	// PUBLIC DOCUMENTS
	define('TEMPLATE_CSS', 'http://'.SITE_URL.'/'.PUBLIC_DIR.TEMPLATE.'css/');
	define('TEMPLATE_JS', 'http://'.SITE_URL.'/'.PUBLIC_DIR.TEMPLATE.'js/');
	
	// DEFAULT CONFIGURATION
	define('DEFAULT_PAGE', 'home');
	define('ERROR_PAGE', 'notfound');
	define('DEFAULT_ACTION', 'index.php');
	define('HANDLER', PHP_FILES.'Handler'.PHP_EXT);
	define('DISPLAY_HANDLER', PHP_FILES.'DisplayHandler'.PHP_EXT);
	define('DEFAULT_TEMPLATE', true);
	define('HEADER', 'header.php');
	define('FOOTER', 'footer.php');