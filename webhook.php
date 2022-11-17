<?php
error_reporting(1);

class WhatsApp
{
		// Properties
	public $token;
	public $to;
	public $msg_body;
	public $welcomeString;
	public $radioStationTypeString;
	public $radioStations;
	public $informationTypes;
	public $finalResponses;
	  // Constructor
	public function __construct()	{
			// echo 'The class "' . __CLASS__ . '" was initiated!<br>';
		$this->welcomeString = ['hi','hello','heita','howzit'];
		$this->radioStationTypeString = ['english_radio','african_lang'];
		$this->radioStations = ['metrofm','5fm','safm','lotusfm','goodhopefm'];
		$this->informationTypes = ['1','2','3'];
		$this->finalResponses = ['4','5','6'];
	}

	  // Destructor
	  // public function __destruct()
	  // {
	  //   echo 'The class "' . __CLASS__ . '" was destroyed!';
	  // }

		// Methods
	function setContent($to,$msg_body) {
		$this->to = $to;
		$this->msg_body = $msg_body;
	}

	  	// Check Content Type
	function checkContent() {

		if(in_array($this->msg_body, $this->welcomeString)) {
			return $this->sendWelcomeMsg();
		}

		if($this->msg_body == 'engishStation' || $this->msg_body == 'africanStation') {
			return $this->sendRadioStations();
		}

		if(in_array($this->msg_body, $this->radioStations)) {
			return $this->sendInformationAccordingStation();
		}

		if(in_array($this->msg_body, $this->informationTypes)) {
			return $this->finalResponse();
		}

			// In progress
			// if(in_array($this->msg_body, $this->finalResponses)) {
			// 	return $this->finalResponse();
			// }				  
	}

	  	// Welcome Message
	function sendWelcomeMsg() {

		$mappedData = [
			"messaging_product" => "whatsapp",    
			"recipient_type" => "individual",
			"to" => $this->to,
			"type" => "interactive",
			"interactive" => [
				"type" => "button",       
				"body" => [
					"text" => "HEITA, WELCOME TO THE SABC DIGITAL AE. WHAT DO YOU NEED HELP WITH ?"
				],             
				"action" => [
					"buttons" => [
						[
							"type" => "reply",
							"reply" => [
								"id" => "engishStation",
								"title" => "ENGLISH STN" 
							]
						],
						[
							"type" => "reply",
							"reply" => [
								"id" => "africanStation",
								"title" => "AFRICAN STN" 
							]
						]
					] 
				]      
			]
		];

		$data = json_encode($mappedData);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://graph.facebook.com/v15.0/102472692687000/messages',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>$data,
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer EAAJJRX2EG5MBAHZCTLVJV6qrhJeHzQPcPX3u7nQmJQ1bUfq9yvNyYeZAPSFnHoxoG6tQttj4FnJ4wUZC9anAQA3s4SBah3FSjPfy5gdZCLdAb1LZA95r4ZB4HpMlBUqQFtdEbC5XDn20USnKrl7Nh7kH720Oq6UaZCdwnzAbZCjDP88WZB6CPTLj9Q2PUaS2Hhxs2ATghnHZA0DQZDZD',
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		return $response;
	}

		// Send Radio Station Details
	function sendRadioStations() {

		$mappedData = [
			"messaging_product" => "whatsapp",    
			"recipient_type" => "individual",
			"to" => $this->to,
			"type" => "interactive",
			"interactive" => [
				"type" => "list",			      
				"body" => [
					"text" => "SELECT YOUR FAVOURITE STATION"
				],			             
				"action" => [
					"button" => "SELECT STATION",
					"sections" => [
						[			              
							"title" => "Choose station",
							"rows" => [
								[
									"id" => "metrofm",
									"title" => "METRO FM",
								],
								[
									"id" => "5fm",
									"title" => "5FM",
								],
								[
									"id" => "safm",
									"title" => "SAFM",
								],
								[
									"id" => "lotusfm",
									"title" => "LOTUS FM",
								],
								[
									"id" => "goodhopefm",
									"title" => "GOOD HOPE FM",
								],
							]
						],			                          
					],
				],

			],
		];

		$data = json_encode($mappedData);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://graph.facebook.com/v15.0/102472692687000/messages',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>$data,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				'Authorization: Bearer EAAJJRX2EG5MBAHZCTLVJV6qrhJeHzQPcPX3u7nQmJQ1bUfq9yvNyYeZAPSFnHoxoG6tQttj4FnJ4wUZC9anAQA3s4SBah3FSjPfy5gdZCLdAb1LZA95r4ZB4HpMlBUqQFtdEbC5XDn20USnKrl7Nh7kH720Oq6UaZCdwnzAbZCjDP88WZB6CPTLj9Q2PUaS2Hhxs2ATghnHZA0DQZDZD'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

		// Send information by station Id
	function sendInformationAccordingStation() {

		$mappedData = [
			"messaging_product" => "whatsapp",    
			"recipient_type" => "individual",
			"to" => $this->to,
			"type" => "text",
			"text" => [
				"preview_url" => false,
				"body" => 
				"WHAT WOULD YOU LIKE TO KNOW ?                                   1.STATION INFORMATION.           2.THE SHOWS                             3.RATES"
			]
		];

		$data = json_encode($mappedData);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://graph.facebook.com/v15.0/102472692687000/messages',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>$data,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				'Authorization: Bearer EAAJJRX2EG5MBAHZCTLVJV6qrhJeHzQPcPX3u7nQmJQ1bUfq9yvNyYeZAPSFnHoxoG6tQttj4FnJ4wUZC9anAQA3s4SBah3FSjPfy5gdZCLdAb1LZA95r4ZB4HpMlBUqQFtdEbC5XDn20USnKrl7Nh7kH720Oq6UaZCdwnzAbZCjDP88WZB6CPTLj9Q2PUaS2Hhxs2ATghnHZA0DQZDZD'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}

		// Final Response
	function finalResponse() {

		$mappedData = [
			"messaging_product" => "whatsapp",    
			"recipient_type" => "individual",
			"to" => $this->to,
			"type" => "interactive",
			"interactive" => [
				"type" => "button",       
				"body" => [
					"text" => "Thanks For using our Service !"
				],             
				"action" => [
					"buttons" => [
						[
							"type" => "reply",
							"reply" => [
								"id" => "4",
								"title" => "CALL ME BACK" 
							]
						],
						[
							"type" => "reply",
							"reply" => [
								"id" => "5",
								"title" => "MAIN MENU" 
							]
						],
						[
							"type" => "reply",
							"reply" => [
								"id" => "6",
								"title" => "SHARE" 
							]
						]
					] 
				]      
			]
		];

		$data = json_encode($mappedData);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://graph.facebook.com/v15.0/102472692687000/messages',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS =>$data,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				'Authorization: Bearer EAAJJRX2EG5MBAHZCTLVJV6qrhJeHzQPcPX3u7nQmJQ1bUfq9yvNyYeZAPSFnHoxoG6tQttj4FnJ4wUZC9anAQA3s4SBah3FSjPfy5gdZCLdAb1LZA95r4ZB4HpMlBUqQFtdEbC5XDn20USnKrl7Nh7kH720Oq6UaZCdwnzAbZCjDP88WZB6CPTLj9Q2PUaS2Hhxs2ATghnHZA0DQZDZD'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		return $response;
	}
}

	// Takes raw data from the request
$json = file_get_contents('php://input');

	// // Converts it into a PHP object
$inputdata = json_decode($json);

	// THIS IS CALLED FOR GENERATING THE TOKEN.
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

	$verify_token = "whatsapp";
	$mode = $_GET["hub.mode"];
	$token = $_GET["hub.verify_token"];
	$challenge = $_GET["hub.challenge"];

	if ($mode && $token) {
		    // Check the mode and token sent are correct
		if ($mode === "subscribe" && $token === $verify_token) {
		      // Respond with 200 OK and challenge token from the request
			echo $challenge;
		} else {
		      // Responds with '403 Forbidden' if verify tokens do not match
			http_response_code(403);
			echo json_encode(array("message" => 'not verified'));
		}
	}
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$inputdata = json_decode(json_encode($inputdata), true);

	$to = $inputdata->to;
	$msg_body = strtolower($inputdata->msg_body);

	$to = (isset($inputdata->to) ? $inputdata->to : '917363807606');
	$msg_body = (isset($inputdata->msg_body) ? $inputdata->msg_body : '');

	$instance = new WhatsApp();
	$instance->setContent($to,$msg_body);
	$json = $instance->checkContent();
	$res = json_decode($json);
	$lastMsgId = $res->messages[0]->id;

	$respopnseData = array(
		"status" => true,
		"response" => 200,
		"previousMsgId" => $lastMsgId
	);

	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($respopnseData);
}


?>
