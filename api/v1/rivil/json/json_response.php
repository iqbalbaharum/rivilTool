<?php
class JSONResponse {

	/***
	 */

	const JSON_SUCCESS = 0;

	// error
	const JSON_E_INTERNAL_ERROR = 1000;

	const JSON_F_MISSING_PARAM = 5001;
	const JSON_F_EMPTY_DATA = 5002;
    const JSON_F_VERIFY_FAIL = 5003;
    const JSON_F_VERIFY_DONE = 5004;
	const JSON_F_RECORD_EXISTS = 5005;
	const JSON_F_INVALID_DATA = 5006;

	// tag
	const TAG_STATUS = "status";
	const TAG_DATA = "data";
	const TAG_MESSAGE = "message";
	const TAG_CODE = "code";

	const STATUS_SUCCESS = "success";
	const STATUS_FAIL = "fail";
	const STATUS_ERROR = "error";

	// all
	public $status;
	public $data = array();

	// fail
	public $code;
	public $source;
	public $message;

	/***
	 * Build json structure
	 */
	public function build() {

		$json = array();

		$json[self::TAG_STATUS] = $this->status;
		switch ($this->status) {
			case self::STATUS_SUCCESS:
				$json[self::TAG_DATA] = $this->data;
				break;
			case self::STATUS_FAIL:
				$json[self::TAG_MESSAGE] = $this->buildFail($this->code, $this->source);
                $json[self::TAG_CODE] = $this->code;
				break;
			case self::STATUS_ERROR:
				$json[self::TAG_MESSAGE] = $this->message;
				$json[self::TAG_CODE] = $this->code;
				break;
		}

		return json_encode($json);
	}

	private function buildFail($code, $source) {

		$error = "$source : ";

		switch ($code) {
			case self::JSON_F_MISSING_PARAM:
				$error .= "Parameter required";
				break;
			case self::JSON_F_RECORD_EXISTS:
				$error .= "Record already exists";
				break;
			case self::JSON_F_EMPTY_DATA:
				$error .= "No record(s) found";
				break;
            case self::JSON_F_VERIFY_FAIL:
                $error .= "Verification failed";
                break;
            case self::JSON_F_VERIFY_DONE:
                $error .= "Has already verified";
                break;
			case self::JSON_F_INVALID_DATA:
				$error .= "Invalid";
				break;
		}

		return $error;
	}

	public function setFailData($code, $src) {
		$this->code = $code;
		$this->source = $src;
	}
}
?>