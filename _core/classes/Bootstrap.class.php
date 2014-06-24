<?php
	
	namespace sam;
	
	use sam\Handler;
	use sam\DatabaseHandler as DbHandler;
	use \PDO;
	
	if (!defined('BOOT_OK')) {throw new \Exception('Can not load Bootstrap');}
	
	class Bootstrap {
		/**
		* Holds the full server requested uri
		*
		* @access private
		* @type string
		*
		*/
		private $uri;
		
		/**
		* Holds the array of exploded requested uri
		*
		* @access private
		* @type array
		*
		*/
		private $uri_pieces;
		
		/**
		* Holds the delimiter to explode the uri
		*
		* @access private
		* @type string
		*
		*/
		private $uri_delimiter = '/';
		
		/**
		* @param string $uri is the server requested uri
		* @set $this->uri
		* @set $this->uri_pieces
		* @use $this->explodeUri()
		* @use $this->load()
		* @use $this->makeRequest()
		*
		*/
		function __construct($uri = NULL) {
			$this->uri = $uri;
			$this->uri_pieces = $this->explodeUri();
			$this->load();
			$this->makeRequest();
			$this->closeDb();
		}
		
		/**
		* Explode the server uri string with the uri_delimiter
		*
		* @access private
		* @use $this->uri
		* @use $this->uri_delimiter
		* @return associative array
		*
		*/
		private function explodeUri() {
			$uri_pieces = explode($this->uri_delimiter, $this->uri);
			unset($uri_pieces[0]); // first piece always null
			
			return array_combine(range(0, count($uri_pieces)-1), $uri_pieces);
		}
		
		/**
		* Print_r the uri pieces
		*
		* @access private
		* @use $this->uri_pieces
		*
		*/
		private function dumpUri() {
			print_r($this->uri_pieces);
		}
		
		/**
		* Call the main handler function
		*
		* @access private
		* @use $this->uri_pieces
		* @use Handler::handle()
		*
		*/
		private function makeRequest() {
			$handler = new Handler($this->uri_pieces);
		}
		
		/**
		* Load everything we need
		*
		* @access private
		* @use $this->loadConfigs()
		* @use Handler::getFile()
		*
		*/
		private function load() {
			$this->loadConfigs();
			$this->loadDb();
				
			if (defined('HANDLER') && file_exists(HANDLER))
				require_once(HANDLER);
			else
				throw new \Exception('No Handler found');
			
			if (defined('DB_CLASS') && file_exists(DB_CLASS))
				require_once(DB_CLASS);
			else
				throw new \Exception('No Db_class found');
				
			$db = $this->initDb(DbHandler::__c__(DB_PW));
			DbHandler::init($db);
		}
		
		/**
		* Load the configs files
		*
		* @access private
		*
		*/
		private function loadConfigs() {
			if (!defined('CONFIG_FILES') || !is_dir(CONFIG_FILES)) {throw new \Exception('Can not load configuration files');}
			if (!include_once(CONFIG_FILES.'consts.php')) {throw new \Exception('Something happened when loading the configuration files');}
		}
		
		/**
		* Load the database
		*
		* @access private
		*
		*/
		private function loadDb() {
			if (defined('INI_FILE') && defined('SYSTEM')) {
				$ini_array = parse_ini_file(SYSTEM.'/'.INI_FILE);
				
				foreach ($ini_array as $key => &$value) {
					if (!defined($key)) {
						define($key, $value);
					}
				}
			}
		}
		
		/**
		* Init db
		*
		* @access private
		*
		*/
		private function initDb($secret) {
			if (defined('DB_SERVER') && defined('DB_USER') && defined('DB_BASE') && trim($secret) != '') {
				return new PDO('mysql:host='.DB_SERVER.';dbname='.DB_BASE, DB_USER, $secret);
			}
			else {
				throw new \Exception('Db not initiated');
			}
		}
		
		/**
		* Close database connection
		*
		* @access private
		*
		*/
		private function closeDb() {
			DbHandler::close();
		}
	};