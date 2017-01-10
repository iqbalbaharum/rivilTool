<?php

	class DatabaseMongo {

		private $connection;

		/**
		 * CONSTRUCTOR
		 */
		function __construct() {}
		
		/***************************************************************
		 * Start connection to database
		 ***************************************************************/
		public function connect(){
			
			$this->connection = new MongoClient();

			$this->connection = new mysqli(self::SERVER_ADDRESS, self::SERVER_USERNAME, 
			self::SERVER_PASSWORD, self::SERVER_DB);
			
			// return false on error
			if($this->connection->connect_errno != 0) {
				$this->error_code = $this->connection->connect_errno;
				return false;
			}
			
			// PHP 5.2.9 and 5.3.0
			if(mysqli_connect_error()) {
				$this->error_code = $this->connection->mysqli_connect_errno();
				return false;
			}
			return true;
		}
	}
?>