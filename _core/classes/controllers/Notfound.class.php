<?php
	namespace sam;
	
	class Notfound extends DisplayHandler {
		private $text = null;
		private $url = null;
	
		function __construct($params = array()) {
			$this->text = 'Page not found: <br/>';
			$this->url = '<span class="not_found_uri">'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'</span>';
		}
		
		function init() {
			return array('error_message' => $this->text.$this->url,
						 'title' => 'Page not found');
		}
		
		protected function template() {
			$options = array('header' => 'template',
							 'footer' => 'template',
							 'view' => '404');
			return $options;
		}
	}