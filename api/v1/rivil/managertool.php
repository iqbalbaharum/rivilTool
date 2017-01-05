<?php

    require_once "manager.php";
	require_once("obj/model.point.php");
	require_once("json/json_response.php");
	
	class ToolManager extends Manager{

		const TAG = "Tool";
		const RESP_TAG_ORIGIN = "Origin";
		const RESP_TAG_DESTINATION = "Destination";

		const EARTH_RADIUS_IN_M = 6371000;

		public function __construct() {
			
		}

		public function get($args) {

			$aJSON = new JSONResponse();

			$origin = isset($args["origin"]) ? $args["origin"] : null;
			$destination = isset($args["destination"]) ? $args["destination"] : null;
			$maxDistance = isset($args["max"]) ? $args["max"] : null;

			$bRetCode = true;

			if(is_null($origin)) {
				$aJSON->status = JSONResponse::STATUS_FAIL;
				$aJSON->setFailData(JSONResponse::JSON_F_MISSING_PARAM, self::RESP_TAG_ORIGIN);
				$bRetCode = false;
			}

			if($bRetCode & is_null($destination)) {
				$aJSON->status = JSONResponse::STATUS_FAIL;
				$aJSON->setFailData(JSONResponse::JSON_F_MISSING_PARAM, self::RESP_TAG_DESTINATION);
				$bRetCode = false;
			}

			if($bRetCode) {

				if(!is_null($maxDistance)) {
					$maxDistance = intval($maxDistance);
				} else {
					$maxDistance = 1500; // default
				}
				
			}

			if($bRetCode) {

				$nodes = $this->getNodes($origin, $destination, $maxDistance);
				//print_r($nodes);
			}

			return $aJSON->build();
		}

		private function getNodes($origin, $destination, $maxDistance) {
			
			// store Point Class
			$paths = array();

			$url = "https://maps.googleapis.com/maps/api/directions/json?origin=".urlencode($origin)."&destination=".urlencode($destination)."&key=AIzaSyBXhGkSYgByF17DhDDJ4xgY4yeA_xqQ07g";

			$json = $this->sendGETRequest($url);

			if(!is_null($json)) {
				$response = json_decode($json, true);
				// get the steps
				$steps = $response["routes"][0]["legs"][0]["steps"];
				// loop to compare coordinates and store in array

				//print_r($steps);
				for($i=0; $i < count($steps); $i++) {

					$step = $steps[$i];

					$point = new Point();
					$lat = $step["start_location"]["lat"];
					$lon = $step["start_location"]["lng"];

					$zoom = 17;
					$xtile = floor((($lon + 180) / 360) * pow(2, $zoom));
					$ytile = floor((1 - log(tan(deg2rad($lat)) + 1 / cos(deg2rad($lat))) / pi()) /2 * pow(2, $zoom));

					$n = pow(2, $zoom);
					$lon_deg = $xtile / $n * 360.0 - 180.0;
					$lat_deg = rad2deg(atan(sinh(pi() * (1 - 2 * $ytile / $n))));

					//echo $lon_deg." ".$lat_deg."\n";
					echo $xtile." ".$ytile."\n";
				}
			}

			return $paths;
		}

		private function analysePathsToNode($paths, $maxDistance) {
		}

		/**
		 * Calculates the great-circle distance between two points, with
		 * the Haversine formula.
		 * @param Point $pointFrom Start Point
		 * @param Point $pointTo End Point
		 * @return float Distance between points in [km] (same as earthRadius)
		 */
		private function haversineGreatCircleDistance($pointFrom, $pointTo){
			// convert from degrees to radians
			$latFrom = deg2rad($pointFrom->latitude);
			$lonFrom = deg2rad($pointFrom->longitude);
			$latTo = deg2rad($pointTo->latitude);
			$lonTo = deg2rad($pointTo->longitude);

			$latDelta = $latTo - $latFrom;
			$lonDelta = $lonTo - $lonFrom;

			$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
			cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
			return $angle * self::EARTH_RADIUS_IN_M;
		}

		private function sendGETRequest($url) {

			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => $url,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "GET",
			  CURLOPT_HTTPHEADER => array(
			    "cache-control: no-cache"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err)
			  echo $err;
			else
			  return $response;
		}

		/***
         * Format to:
         * @param $user
         * @return array
         */
		public function formatJSONItem($user) {

			$aResult = array();

			$aResult["id"] = $user->id;
			$aResult["username"] = $user->username;
			$aResult['level'] = $user->level;
	        $aResult["timestamp"] = $user->timestamp;

			return $aResult;
		}
	}

?>