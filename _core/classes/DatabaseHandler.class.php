<?php
	
	namespace sam;
	
	class DatabaseHandler {
		/**
		* Holds the database connection
		*
		* @access protected
		* @type object
		*
		*/
		static protected $db;
		
		/**
		* Set $db
		*
		* @access public
		*
		*/
		static public function init($db) {
			if (is_object($db)) {
				self::$db = $db;	
			}
			else {
				throw new \Exception('Database not openned');
			}
		}
		
		/**
		* Set $db @ null || close db
		*
		* @access public
		*
		*/
		static public function close() {
			self::$db = null;
		}
		
		/**
		* Unsalt
		*
		* @access public
		*
		*/
		static function __c__($string) {
			$result = base64_decode($string);
			$result = str_replace(SALT, '', $result);
			$result = base64_decode(urldecode($result));
			$result = substr(str_replace(PEPPER, '', $result), 1);
			return $result;
		}
	}