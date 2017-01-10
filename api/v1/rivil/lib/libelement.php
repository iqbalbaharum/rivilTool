<?php

require_once('lib.php');

class LibElement extends Lib {
	
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
	public function insert(&$element) {

        $aObj = null;

        if(!is_null($element->tileX) 
        	&& !is_null($element->tileY)) {

        	$aObj["tileX"] = $tileX;
        	$aObj["tileY"] = $tileY;
        	$aObj["mapRefId"] = $element->mapRefId;
	        $aObj['type'] = $element->type;
	        $aObj["kind"] = $element->kind;

			if(!$this->database->insert(Database::R_ELEMENT, $aObj)) {
				$this->error = $this->database->getError();
				return false;
			}

			return true;
        }
        
        return false;
	}

	// get list of elements
    public function getAll($element, &$array) {

        $aObj = null;

        if(!is_null($element->tileX) 
        	&& !is_null($element->tileY)) {

        	$aObj["tileX"] = $element->tileX;
        	$aObj["tileY"] = $element->tileY;
        }

        if(!is_null($element->type)) {
    		$aObj["type"] = $element->type;
    	}

    	if(!is_null($element->mapRefId)) {
    		$aObj["mapRefId"] = $element->mapRefId;
    	}

    	if(!$this->database->select(Database::R_ELEMENT, $aObj, $aResult)) {
            $this->error = $this->database->getError();
            return false;
        }

        if(is_null($aResult)) {
            $array = null; // empty
        } else {

            while($aElement = $aResult->fetch_assoc()) {

            	$element = new Element();

            	$element->id = $aElement["id"];
            	$element->mapRefId = $aElement["mapRefId"];
            	$element->type = $aElement["type"];
            	$element->kind = $aElement["kind"];
            	$element->tileX = $aElement["tileX"];
            	$element->tileY = $aElement["tileY"];

                $array[] = $element;
            }
            
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
?>