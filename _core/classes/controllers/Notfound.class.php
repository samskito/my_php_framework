<?php
	namespace sam;
	
	class Notfound extends DisplayHandler {
		function __construct($params = array()) {
			
		}
		
		function init() {
			// Do stuff here
			// return array
			//return array('view' => '404');
		}
		
		protected function template() {
			$options = array('header' => 'template',
							 'footer' => 'template',
							 'view' => '404');
			return $options;
		}
	}