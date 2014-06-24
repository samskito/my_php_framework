<?php
	namespace sam;
	
	class Home extends DisplayHandler {
		private $params;
	
		function __construct($params = array()) {
			$this->params = $params;
		}
		
		function init() {
			// return array
			return array('title' => 'Home');
		}
		
		/**
		* Tells if the page is part of the template
		*
		* @return bool
		*
		*/
		public function template() {
			return false;
		}
	}