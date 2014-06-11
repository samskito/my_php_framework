<?php
	
	namespace sam;
	
	class DisplayHandler extends Handler {
		/**
		* Holds the controller to display
		*
		* @access private
		* @type string
		*
		*/
		private $controller = null;
		
		/**
		* Holds the controller's object
		*
		* @access private
		* @type object
		*
		*/
		private $controller_object = null;
		
		/**
		* Holds the array of parameters such as a method to execute; can be empty
		*
		* @access private
		* @type array
		*
		*/
		private $params = array();
		
		/**
		* Holds if the class is part of the template - for the view
		*
		* @access private
		* @type bool
		*
		*/
		private $is_template = false;
		
		/**
		* Holds the page template
		*
		* @access private
		* @type array
		*
		*/
		private $template = array('header' => null,
								  'footer' => null);
		
		/**
		* Holds the view
		*
		* @access private
		* @type string
		*
		*/
		private $view = null;
		
		/**
		* @param string $page name of the page to display
		* @param array $params array of parameters
		* @set $this->controller
		* @set $this->params
		*
		*/
		function __construct($page = NULL, $params = array()) {
			if (trim($page) == '' || $page == NULL) throw new \Exception('No page asked');
			
			$this->controller = $page;
			$this->params = $params;
			
			$this->getController($this->controller);
			$full_qualified_class_name = 'sam\\'.$this->controller;
			
			if (!class_exists($full_qualified_class_name)) throw new \Exception('Class "'.$full_qualified_class_name.'" not found');
			
			$this->controller_object = new $full_qualified_class_name($this->params);
			
			$this->is_template = $this->checkTemplate($this->controller_object);
			
			$this->initTemplate();
		}
		
		/**
		* Include the controller
		*
		* @use $this->controller
		* @use CONTROLLERS
		* @use PHP_EXT
		*
		*/
		private function getController() {
			if (!file_exists(CONTROLLERS.$this->controller.PHP_EXT)) throw new \Exception('The controller "'.$this->controller.'" is not found');
			require_once(CONTROLLERS.$this->controller.PHP_EXT);
		}
		
		/**
		* Check if controller is part of the template
		*
		* @param object $controller is the object of the page
		* @use $controller->template()
		* @return bool
		*
		*/
		private function checkTemplate() {
			if (!is_object($this->controller_object)) throw new \Exception('Controller not an object');
			
			return method_exists($this->controller_object, 'template') ? $this->controller_object->template() : (defined('DEFAULT_TEMPLATE') ? DEFAULT_TEMPLATE : false);
		}
		
		/**
		* Init template, default or custom depends on controller
		*
		* @use $this->is_template
		* @use $this->defaultTemplate()
		* @use $this->customTemplate()
		*
		*/
		private function initTemplate() {
			// Array
			if (is_array($this->is_template)) {
				// header
				if (isset($this->is_template['header']) && trim($this->is_template['header']) != '' && trim($this->is_template['header']) != 'template') {
					$this->template['header'] = VIEWS.strtolower($this->controller).'/'.HEADER;
				}
				else {
					$this->template['header'] = ROOT.PUBLIC_DIR.TEMPLATE.HEADER;
				}
				
				// footer
				if (isset($this->is_template['footer']) && trim($this->is_template['footer']) != '' && trim($this->is_template['footer']) != 'template') {
					$this->template['footer'] = VIEWS.strtolower($this->controller).'/'.FOOTER;
				}
				else {
					$this->template['footer'] = ROOT.PUBLIC_DIR.TEMPLATE.FOOTER;
				}
			}
			// Bool
			else {
				if ($this->is_template) {
					$this->template['header'] = ROOT.PUBLIC_DIR.TEMPLATE.HEADER;
					$this->template['footer'] = ROOT.PUBLIC_DIR.TEMPLATE.FOOTER;
				}
				else {
					$this->template['header'] = VIEWS.strtolower($this->controller).'/'.HEADER;
					$this->template['footer'] = VIEWS.strtolower($this->controller).'/'.FOOTER;
				}
			}
			
			if (isset($this->is_template['view']) && trim($this->is_template['view']) != '')
				$this->template['view'] = $this->is_template['view'];
			
			$this->get_data();
			
			$this->view = $this->get_view();
			
			$this->display();
		}
		
		/**
		* Display everything
		*
		* @use $this->controller_object
		* @use TEMPLATE_CSS
		* @use TEMPLATE_JS
		*
		*/
		private function display() {
			ob_start();
			self::getFile($this->template['header']);
			self::getFile($this->view);
			self::getFile($this->template['footer']);
			ob_end_flush();
		}
		
		/**
		* Get data from controller
		*
		* @use $this->controller_object
		* @use TEMPLATE_CSS
		* @use TEMPLATE_JS
		*
		*/
		private function get_data() {
			if (method_exists($this->controller_object, 'init'))
				$GLOBALS['data'] = $this->controller_object->init();
			else 
				$GLOBALS['data'] = NULL;
				
			// NEED TO FORMAT DATA
			$GLOBALS['sitename'] = '';
			$GLOBALS['css'] = TEMPLATE_CSS;
			$GLOBALS['js'] = TEMPLATE_JS;
		}
		
		/**
		* Get the view
		*
		* @use $GLOBALS['data']['view']
		* @use DEFAULT_ACTION.ROOT.PUBLIC_DIR.TEMPLATE
		* @use $this->is_template
		* @use $this->controller
		* @return string
		*
		*/
		private function get_view() {
			$view = isset($GLOBALS['data']['view']) ? $GLOBALS['data']['view'] : DEFAULT_ACTION;
			
			if (isset($this->template['view']) && trim($this->template['view']) != '') {
				$view = ROOT.PUBLIC_DIR.TEMPLATE.$this->template['view'];
			}
			else {
				$view = VIEWS.strtolower($this->controller).'/'.$view;
			}
			
			$test = strstr($view, '.php');
			
			if (!$test) {
				$view .= '.php';
			}
			
			return $view;
		}
	}