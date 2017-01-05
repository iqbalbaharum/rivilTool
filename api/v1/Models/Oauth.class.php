<?php

	require('Models/ApiKey');
	
	class Oauth extends API
	{
		protected $User;
		
		/***
		* 
		* @param $request
		*			Authorization : Bearer <APIKey>
		*/
		public function __construct($request, $origin) {
			parent::__construct($request);
			
			$APIKey = Models/ApiKey();
			
			if (!array_key_exists('apiKey', $this->request)) {
				throw new Exception('No API Key provided');
			} else if (!$APIKey->verifyKey($this->request['apiKey'], $origin)) {
				throw new Exception('Invalid API Key');
			}
		}
		
		/**
     * Example of an Endpoint
     */
     protected function authenticate($args) {
        if ($this->method == 'POST') {
			// Validate username & password
			// Create user token
			
			// Store token
			
			// pass token
			
        } else {
            return "Only accepts GET requests";
        }
     }
		
	}
?>