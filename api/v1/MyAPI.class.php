<?php
require('api.class.php');
//require_once('models/ApiKey.class.php');
//require_once('models/User.class.php');

require_once('rivil/managerelement.php');

class MyAPI extends API
{
	protected $User;
	
	/***
	* 
	* @param $request
	*			Authorization : Bearer <APIKey>
	*/
	public function __construct($request, $origin) {
		parent::__construct($request);
		
		/*
		$APIKey = new APIKey();
		$User = new User();
		
		if (!array_key_exists('apiKey', $this->request)) {
            throw new Exception('No API Key provided');
        } else if (!$APIKey->verifyKey($this->request['apiKey'], $origin)) {
            throw new Exception('Invalid API Key');
        } else if (array_key_exists('token', $this->request) &&
             !$User->get('token', $this->request['token'])) {

            throw new Exception('Invalid User Token');
        }
		*/
	}

	/**
	 * Login
	 */
	public function element() {

		echo "hello";
		
		$manager = new ElementManager();
		
		// create class
		switch($this->method) {
			case 'GET':
				switch($this->verb) {
					case "tiles":
						echo $manager->getTiles($this->args);
					break;
				}
				break;
		}
	}
	
}