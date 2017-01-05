<?php
require('api.class.php');
//require_once('models/ApiKey.class.php');
//require_once('models/User.class.php');

require_once('rivil/managertool.php');

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
	public function tool() {

		$manager = new ToolManager();
		
		// create class
		switch($this->method) {
			case 'GET':
				echo $manager->get($this->request);
				break;
		}
	}
	
}