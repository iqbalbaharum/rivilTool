<?php

	require_once('ccms/obj/database.php');
	
	abstract class Lib {
		
		function __construct() {}
		
		function currentDateTimeISO8601Format() {
			$currentDateTime = date('Y-m-d H:i:s');		
			return date('c', strtotime($currentDateTime)); // ISO-8601
		}
		
		function currentDateTime() {
			return date('Y-m-d H:i:s');	
		}

		abstract public function insert(&$data);
		abstract public function get(&$data);
		abstract public function update($data);
	}
?>