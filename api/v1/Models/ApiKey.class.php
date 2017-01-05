<?php
	
	class APIKey {
		
		public function verifyKey($apikey, $origin) {
			
			/*
			// Server data must have the same api key as the request
			if($origin['APIKey'] == $apikey) {
				// the correct api key
				if($apikey == 'ABDCDE12345') {
					return true;
				}
			}
			
			return false;
			*/
			
			return true;
		}
	}
?>