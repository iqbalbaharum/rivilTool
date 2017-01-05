<?php
	
	class Token {
		
		private $name = "Hello";
		
		public function get($token, $requestToken) {
			
			/*
			if($token == $requestToken) {
				return $this->name;
			}
			
			return false;
			*/
			
			return true;
		}
		
		public function getName() {
			return $this->name;
		}
	}
?>