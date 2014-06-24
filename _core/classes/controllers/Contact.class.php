<?php
	namespace sam;
	
	class Contact extends DisplayHandler {
		public $test;
		public $title = 'Contact page';
	
		function __construct($params = array()) {
			print_r($params);
			if (isset($_GET['test'])) {
				$this->test = 'get is set and value = '.$_GET['test'];
			}
		}
		
		protected function init() {
			echo $this->test;
			return array('view' => 'facebook',
						 'title' => 'Facebook');
		}
		
		protected function template() {
			$options = array('header' => 'template',
							 'footer' => 'template',
							 'view' => null);
			return $options;
		}
	}