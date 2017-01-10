<?php

    require_once "manager.php";
	require_once("obj/model.element.php");
	require_once("json/json_response.php");
	
	class ElementManager extends Manager{

		private $libElement;

		const TAG = "Element";
		const RESP_TAG_POINTA = "Point A";
		const RESP_TAG_POINTB = "Point B";

		public function __construct() {
			$this->libElement = new LibElement();
		}

		public function getTiles($args) {

			$aJSON = new JSONResponse();

			$pointA = isset($args["a"]) ? $args["a"] : null;
			$pointB = isset($args["b"]) ? $args["b"] : null;

			$bRetCode = true;

			if(is_null($pointA)) {
				$aJSON->status = JSONResponse::STATUS_FAIL;
				$aJSON->setFailData(JSONResponse::JSON_F_MISSING_PARAM, self::RESP_TAG_POINTA);
				$bRetCode = false;
			}

			if($bRetCode & is_null($pointB)) {
				$aJSON->status = JSONResponse::STATUS_FAIL;
				$aJSON->setFailData(JSONResponse::JSON_F_MISSING_PARAM, self::RESP_TAG_POINTB);
				$bRetCode = false;
			}

			if($bRetCode) {
				$aJSON->status = JSONResponse::STATUS_SUCCESS;
				$aJSON->data = $coveringTiles($pointA, $pointB);
			}

			return $aJSON->build();
		}

		// convering tiles
		private function coveringTiles($pointA, $pointB) {
			
			$tiles = array();
				
			$pointA = explode(",", $pointA);
			$pointB = explode(",", $pointB);

			$zoom = 17;
			$tileXA = floor((($pointA[1] + 180) / 360) * pow(2, $zoom));
			$tileYA = floor((1 - log(tan(deg2rad($pointA[1])) + 1 / cos(deg2rad($pointA[0]))) / pi()) /2 * pow(2, $zoom));
			$tileXB = floor((($pointB[1] + 180) / 360) * pow(2, $zoom));
			$tileYB = floor((1 - log(tan(deg2rad($pointB[1])) + 1 / cos(deg2rad($pointB[0]))) / pi()) /2 * pow(2, $zoom));

			// which bigger?
			$startX = 0;
			$endX = 0;
			$startY = 0;
			$endY = 0;

			if($tileXA > $tileXB) {
				$startX = $tileXB;
				$endX = $tileXA;
			} else {
				$startX = $tileXA;
				$endX = $tileXB;
			}

			if($tileYA > $tileYB) {
				$startY = $tileYB;
				$endY = $tileYA;
			} else {
				$startY = $tileYA;
				$endY = $tileYB;
			}
			
			// insert all tiles
			for($x = $startX; $x <= $endX; $x++) {
				for($y = $startY; $y <= $endY; $y++) {
					$tiles[] = array(
						"x" => $x,
						"y" => $y
						);
				}
			}

			return $tiles[];
		}

		private function getElements($tilesX, $tileY) {
			
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