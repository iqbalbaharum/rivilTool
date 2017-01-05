<?php
	abstract class Manager {
		
		function convertDateToISO8601Format($date) {
			return date('c', strtotime($date));
		}

		function convertISO8601ToDate($iso) {
			return date('Y-m-d', strtotime($iso));
		}

		abstract function formatJSONItem($item);
	}
?>