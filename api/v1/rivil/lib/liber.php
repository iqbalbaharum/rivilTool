<?php

require_once('lib.php');

class LibERecord extends Lib {
	
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
	public function insert(&$er) {

        $aObj = null;
        
		$aObj["er_no"] = $er->er_no;
		$aObj["event_date"] = $er->event_date;
        $aObj["location"] = $er->location;
        $aObj["description"] = $er->description;
        $aObj["coordinate"] = $er->coordinate;
        $aObj["relevant_doc"] = $er->relevant_doc;
		// etc
        $aObj["cause"] = $er->cause;
        $aObj["effect"] = $er->effect;
        $aObj["mitigation"] = $er->mitigation;
        $aObj["timestamp"] = parent::currentDateTime();

		if(!$this->database->insert(Database::R_ER, $aObj)) {
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
	public function get(&$er) {

        $aObj = null;

		if(!is_null($er->id)) {
            $aObj["id"] = $er->id;
        }

        if(!is_null($er->er_no)) {
            $aObj["er_no"] = $er->er_no;
        }

        if(!$this->database->select(Database::R_ER, $aObj, $aResult)) {
            $this->error = $this->database->getError();
            return false;
        }

        if(is_null($aResult)) {
            $er = null; // empty
        } else {
            $aER = $aResult->fetch_assoc();
            $er->id = $aER['id'];
            $er->er_no = $aER['er_no'];
            $er->event_date = $aER['event_date'];
            $er->location = $aER['location'];
            $er->description = $aER['description'];
            $er->coordinate = $aER["coordinate"];
            $er->relevant_doc = $aER['relevant_doc'];
            $er->cause = $aER["cause"];
            $er->effect = $aER["effect"];
            $er->mitigation = $aER["mitigation"];
            $er->timestamp = $aER["timestamp"];
        }

        return true;
	}

    public function getAll($er, &$array) {

        $aObj = null;

        if(!is_null($er->id)) {
            $aObj["id"] = $er->id;
        }

        if(!is_null($er->er_no)) {
            $aObj["er_no"] = $er->er_no;
        }

        if(!$this->database->select(Database::R_ER, $aObj, $aResult)) {
            $this->error = $this->database->getError();
            return false;
        }

        if(is_null($aResult)) {
            $array = null; // empty
        } else {

            while($aER = $aResult->fetch_assoc()) {

                $er = new ERecord();

                $er->id = $aER['id'];
                $er->er_no = $aER['er_no'];
                $er->event_date = $aER['event_date'];
                $er->location = $aER['location'];
                $er->description = $aER['description'];
                $er->coordinate = $aER["coordinate"];
                $er->relevant_doc = $aER['relevant_doc'];
                $er->cause = $aER["cause"];
                $er->effect = $aER["effect"];
                $er->mitigation = $aER["mitigation"];
                $er->timestamp = $aER["timestamp"];

                $array[] = $er;
            }
            
        }

        return true;

    }

	/***
	 * Update USER record
	 * @param $er
	 * @return bool
	 */
	public function update($er) {

        $aPrimary = null;

		// update
		if(!is_null($er->er_no)) {
            $aPrimary["er_no"] = $er->er_no;
		}

        $aUpdate["event_date"] = $er->event_date;
        $aUpdate["location"] = $er->location;
        $aUpdate["description"] = $er->description;
        $aUpdate["coordinate"] = $er->coordinate;
        $aUpdate["relevant_doc"] = $er->relevant_doc;
        // etc
        $aUpdate["cause"] = $er->cause;
        $aUpdate["effect"] = $er->effect;
        $aUpdate["mitigation"] = $er->mitigation;
        $aUpdate["timetamp"] = $er->timestamp;

        if(!$this->database->update(Database::R_ER, $aUpdate, $aPrimary)) {
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