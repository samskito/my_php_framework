<?php
	
	namespace sam;
	
	class Error {
		/**
		* Holds the error (Exception)
		*
		* @access private
		* @type string
		*
		*/
		private $error;
		
		/**
		* Holds the beginning of html for styling the error
		*
		* @access private
		* @type string
		*
		*/
		private $before = '<span class="error_handler" style="background:#FFD1D1;border:1px solid #FF665A;padding:10px;max-width:900px;margin:30px auto;display:block;text-align:center;"><strong>Error:</strong> ';
		
		/**
		* Holds the end of html for styling the error
		*
		* @access private
		* @type string
		*
		*/
		private $end = '</span>';
		
		/**
		* Set $this->error
		*
		* @param string $exception is the message of a thrown exception
		* @call $this->show
		*
		*/
		function __construct($exception = NULL) {
			$this->error = $exception;
			$this->show();
		}
		
		/**
		* Show $this->error
		*
		* @use $this->error
		* @use $this->before
		* @use $this->after
		*
		*/
		private function show() {
			echo $this->before;
			echo $this->error;
			echo $this->end;
		}
	}
