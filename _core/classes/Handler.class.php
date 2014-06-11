<?php
	
	namespace sam;
	use sam\DisplayHandler;
	
	class Handler {
		/**
		* Holds the array of exploded requested uri
		*
		* @access private
		* @type array
		*
		*/
		private $uri_pieces;
		
		/**
		* Holds the array of parameters from the uri = $uri_pieces exept its first element
		*
		* @access private
		* @type array
		*
		*/
		private $params;
		
		/**
		* Holds the page to display
		*
		* @access private
		* @type string
		*
		*/
		private $page;
		
		/**
		* @param array $uri_pieces array of requested uri pieces
		* @set $this->uri_pieces
		* @set $this->page
		* @set $this->params
		* @use $this->handle()
		* @use $this->updateParams()
		* @use $this->handle()
		*
		*/
		function __construct($uri_pieces = NULL) {
			if (!is_array($uri_pieces)) throw new \Exception('Can not handle something other than array');
			
			$this->uri_pieces = $uri_pieces;
			$this->page = $this->handle();
			$this->params = $this->updateParams();
			
			$this->getDisplayHandler();
		}
		
		/**
		* Check the first page to display
		*
		* @set $this->uri_pieces
		* @use $this->checkController
		* @return string
		*
		*/
		private function handle() {
			if (isset($this->uri_pieces[0]) && trim($this->uri_pieces[0]) != '') {
				$page = trim($this->uri_pieces[0]);
			}
			else {
				$page = $this->getDefaultPage();
			}
			
			$page = $this->checkController($page);
			
			return ucfirst($page);
		}
		
		/**
		* Check the first page to display
		*
		* @param string $page is the fist piece of uri
		* @use $this->getFolder()
		* @use CONTROLLERS
		* @return string
		*
		*/
		private function checkController($page = NULL) {
			if (trim($page) != '' || trim($page) != NULL) {
				if (defined('CONTROLLERS')) {
					$controllers = self::getFolder(CONTROLLERS);
					if (in_array(ucfirst(trim($page)).PHP_EXT, $controllers)) {
						return ucfirst(trim($page));
					}
					else {
						return $this->getErrorPage();
					}
				}
				else {
					throw new \Exception('No controllers defined');
				}
			}
			else {
				return $this->getDefaultPage();
			}
		}
		
		/**
		* Remove page from uri_pieces, update the keys and return the new array
		*
		* @set $this->uri_pieces
		* @return array
		*
		*/
		private function updateParams() {
			if (isset($this->uri_pieces[0]) && sizeof($this->uri_pieces) != 1) {
				$tmp = $this->uri_pieces;
				unset($tmp[0]);
				
				return array_combine(range(0, count($tmp)-1), $tmp);
			}
			else {
				return array();
			}
		}
		
		/**
		* Includes the requested file
		*
		* @param string $file name of file to include
		*
		*/
		static function getFile($file = NULL) {
			if (trim($file) == '' || $file == NULL || !file_exists($file)) throw new \Exception('File "'.$file.'" not found');
			require_once($file);
		}
		
		/**
		* Return the default page
		*
		* @use DEFAULT_PAGE
		*
		*/
		private function getDefaultPage() {
			return defined('DEFAULT_PAGE') ? DEFAULT_PAGE : NULL;
		}
		
		/**
		* Return the error page
		*
		* @use ERROR_PAGE
		*
		*/
		private function getErrorPage() {
			return defined('ERROR_PAGE') ? ERROR_PAGE : NULL;
		}
		
		/**
		* Return the list of file in the folder
		*
		* @param string $folder name of the folder to go through
		* @return array
		*
		*/
		static function getFolder($folder = NULL) {
			if (trim($folder) == '' || $folder == NULL) return array();
			
			if (is_dir($folder)) {
				$files = scandir($folder);
				$not_want = array('.', '..');
				
				$files = array_diff($files, $not_want);
				
				return $files;
			}
			else {
				return array();
			}
		}
		
		/**
		* Include the displayHandler
		*
		* @use $this->page
		* @use $this->params
		* @use CONTROLLERS
		* @use DISPLAY_HANDLER
		*
		*/
		private function getDisplayHandler() {
			if (!defined('CONTROLLERS')) throw new \Exception('No controllers defined');
			if (!file_exists(CONTROLLERS.$this->page.PHP_EXT)) throw new \Exception('The controller "'.$this->page.'" is not found');
			if (!defined('DISPLAY_HANDLER')) throw new \Exception('Need a display handler');
				
			self::getFile(DISPLAY_HANDLER);
			$display = new DisplayHandler($this->page, $this->params);
		}
			
	};