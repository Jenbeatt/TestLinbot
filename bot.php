<?php
$access_token = 'SG3JBmTZEAbFxdexsK8JwxrPOle/+IFg/R3TmQKfVIUuJSrcRJOsffKPXXtrEy0q3HahfPxP4P4lxrFbFFWpC7fgOPtEAtxSoSChY3fTJbK2JQfaTqHOxh+EMffXSCLdBlzmkn5OVTtLk3lyD+7VkQdB04t89/1O/w1cDnyilFU=';
$Token_anto = 'TRJxeh7OfX0WY9dEY7IBdq62h4nwkpNDJu0I6wEb';
$keys = 'NodeMCU';
$Chanel = 'Status';
$_Status = -1;
//$access_token = 'iA00aKCsapdGJ2NY1g1W4XIqjaMCYUbVShtwKRb9psC';
// Get POST body content
$content = file_get_contents('php://input');
$events = json_decode($content, true);
$Url = "https://api.anto.io/channel/get/".$Token_anto."/".$keys."/".$Chanel;
$Url_Update = "https://api.anto.io/channel/set/".$Token_anto."/".$keys."/".$Chanel."/";

// Parse JSON



// Validate parsed JSON data
function Check_Status($contents){
$event_Status = json_decode($contents, true);
	if ($event_Status['result'] == "true"){
		if( $event_Status['value'] == "0") {		
			return "ปิด";	
		} else {				
			return "เปิด";
		}	
	}
	return "เออเรอ";		
}

		// Loop through each event	
		if(!is_null($events['events']) ){
		foreach ($events['events'] as $event) {
			// Reply only when message sent is in 'text' format
			if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
				// Get text sent
				$text = $event['message']['text'];
				// Get replyToken
				$replyToken = $event['replyToken'];				
				// Build message to reply back						
				$Userid = ["U8b1b238e78d5195aeed5c971023f548f"]	;
					$messages = [
					'type' => 'text',
					'text' => 'Test'
					];
				// Make a POST Request to Messaging API to reply to sender				
				$url = ' https://api.line.me/v2/bot/message/multicast';				
				$data = [
					'to' => [$Userid],				
					'messages' => [$messages],									
				];
				$post = json_encode($data);
				$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
				$result = curl_exec($ch);
				curl_close($ch);
				echo $result . "\r\n";

			}
		}
	}

echo "OK";
echo "<br>".$Url;
