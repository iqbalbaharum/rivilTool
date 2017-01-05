<?php

require_once('lib.php');

class LibUser extends Lib {
	
	private $database;
	private $error;
	
	function __construct() {
		// initialise parent
		parent::__construct();
		$this->database = new Database();
		$this->database->connect();
	}

    /***
     * When user online, create new row
     * @param $user
     * @return bool
     */
	public function insert(&$user) {

        $aObj = null;
        
        // at default, each user is basic user
        if(is_null($user->level))
            $aObj["level"] = User::AC_BASIC;
        else
            $aObj["level"] = $user->level;    
        
		$aObj["username"] = $user->username;
        $aObj['password'] = $user->password;
        $aObj["timestamp"] = parent::currentDateTime();

		if(!$this->database->insert(Database::R_USER, $aObj)) {
			$this->error = $this->database->getError();
			return false;
		}

		return true;
	}

    /***
     * Read from MySQL database
     * @param $user
     * @return bool
     */
	public function get(&$user) {

        $aObj = null;

		if(!is_null($user->username)) {
            $aObj["username"] = $user->username;
        }

        if(!$this->database->select(Database::R_USER, $aObj, $aResult)) {
            $this->error = $this->database->getError();
            return false;
        }

        if(is_null($aResult)) {
            $er = null; // empty
        } else {
            $aUser = $aResult->fetch_assoc();
            $user->id = $aUser['id'];
            $user->username = $aUser['username'];
            $user->level = $aUser['level'];
            $user->timestamp = $aUser["timestamp"];
        }

        return true;
	}

    public function getAll($user, &$array) {

        $aObj = null;

        if(!is_null($user->username)) {
            $aObj["username"] = $user->username;
        }

        if(!$this->database->select(Database::R_USER, $aObj, $aResult)) {
            $this->error = $this->database->getError();
            return false;
        }

        if(is_null($aResult)) {
            $array = null; // empty
        } else {

            while($aUser = $aResult->fetch_assoc()) {

                $user = new User();
                
                $user->id = $aUser['id'];
                $user->username = $aUser['username'];
                $user->level = $aUser['level'];
                $user->timestamp = $aUser["timestamp"];

                $array[] = $user;
            }
            
        }

        return true;

    }

    public function recordExists($user) {

        $aObj = null;
        $rowCount = 0;

        if(!is_null($user->username)) {
            
            $aObj["username"] = $user->username;
            
            if(!$this->database->rowCount(Database::R_USER, $aObj, $rowCount)) {
                $this->error = $this->database->getError();
                return false;
            }

            if($rowCount > 0) {
                return true;
            }    
        }


        return false;
    }

	/***
	 * Update USER record
	 * @param $user
	 * @return bool
	 */
	public function update($user) {

        $aPrimary = null;

		// update
		if(!is_null($user->username)) {
            $aPrimary["username"] = $user->username;
		}

        $aUpdate['password'] = $user->password;
        $aUpdate['level'] = $user->level;
        $aUpdate["timetamp"] = $er->timestamp;

        if(!$this->database->update(Database::R_USER, $aUpdate, $aPrimary)) {
            $this->error = $this->database->getError();
            return false;
        }

        return true;
	}

    /******************************************* SETTER *******************************************/
    public function setError($error) {
        $this->error = $error;
    }

    /******************************************* GETTER *******************************************/

    public function getError() {
        return $this->error;
    }

}