<?php
$access_token = 'SG3JBmTZEAbFxdexsK8JwxrPOle/+IFg/R3TmQKfVIUuJSrcRJOsffKPXXtrEy0q3HahfPxP4P4lxrFbFFWpC7fgOPtEAtxSoSChY3fTJbK2JQfaTqHOxh+EMffXSCLdBlzmkn5OVTtLk3lyD+7VkQdB04t89/1O/w1cDnyilFU=';
$Token_anto = 'TRJxeh7OfX0WY9dEY7IBdq62h4nwkpNDJu0I6wEb';
$keys = 'NodeMCU';
$Chanel = 'Status';
$_Status = -1;
//$access_token = 'iA00aKCsapdGJ2NY1g1W4XIqjaMCYUbVShtwKRb9psC';
// Get POST body content
$content = file_get_contents('php://input');
$Url = "https://api.anto.io/channel/get/".$Token_anto."/".$keys."/".$Chanel;
$Url_Update = "https://api.anto.io/channel/set/".$Token_anto."/".$keys."/".$Chanel."/";
$Get_Status = file_get_contents($Url);
//$Get_Status = file_get_contents('https://api.anto.io/channel/get/TRJxeh7OfX0WY9dEY7IBdq62h4nwkpNDJu0I6wEb/NodeMCU/Status');

// Parse JSON
$events = json_decode($content, true);
$event_Status = json_decode($Get_Status, true);
$messages = ['type' => 'text','text' => 'Begin'];
// Validate parsed JSON data
function Check_Status($Result,$Value){
	if ($Result == "true"){
		if( $Value == "0") {
			$messages = [
			'type' => 'text',
			'text' => 'ปิด'
			];
			return 0;	
		} else {
			$messages = [
			'type' => 'text',
			'text' => 'เปิด'
			];			
			return 1;
		}
	
	}else{
		$messages = [
		'type' => 'text',
		'text' => ' Error Not Resporn value'. $event_Status['value'] .' result ' . $event_Status['result']
		];	
	}	
		return -1;
}

if (!is_null($events['events'])) {
	// Loop through each event	
	foreach ($events['events'] as $event) {
		// Reply only when message sent is in 'text' format
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			// Get text sent
			$text = $event['message']['text'];
			// Get replyToken
			$replyToken = $event['replyToken'];
			// Build message to reply back
			if(strtoupper($text) == "GETSTATUS" || $text == "สถานะ"){				
				$messages = [
				'type' => 'text',
				'text' => 'สถานะ ส่ง = '.Check_Status($event_Status['result'],$event_Status['value'])
				];	
			} elseif ($text == strtoupper("ON") || $text == "เปิด" ) {
				$_Status = 1;
				$messages = [
				'type' => 'text',
				'text' => 'เปิด เรียบร้อย'
				];
			} elseif ($text == strtoupper("OFF") || $text == "ปิด" ) {
				$_Status = 0;		
				$messages = [
				'type' => 'text',
				'text' => 'ปิด เรียบร้อย'
				];					
			}else {				
				$messages = [
				'type' => 'text',
				'text' => 'กรุณากรอกใหม่...'
				];				
			} 
				
			if ($_Status > -1) {
			   	$Get_Status = file_get_contents($Url_Update.$_Status);	
				$event_Status = json_decode($Get_Status, true);
				Check_Status($event_Status['result'],$event_Status['value']);
			}
			// Make a POST Request to Messaging API to reply to sender
			$url = 'https://api.line.me/v2/bot/message/reply';
			$data = [
				'replyToken' => $replyToken,
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
