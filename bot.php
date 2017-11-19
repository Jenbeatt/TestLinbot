<?php
$access_token = 'SG3JBmTZEAbFxdexsK8JwxrPOle/+IFg/R3TmQKfVIUuJSrcRJOsffKPXXtrEy0q3HahfPxP4P4lxrFbFFWpC7fgOPtEAtxSoSChY3fTJbK2JQfaTqHOxh+EMffXSCLdBlzmkn5OVTtLk3lyD+7VkQdB04t89/1O/w1cDnyilFU=';
//$access_token = 'iA00aKCsapdGJ2NY1g1W4XIqjaMCYUbVShtwKRb9psC';
$Token_anto = 'TRJxeh7OfX0WY9dEY7IBdq62h4nwkpNDJu0I6wEb';
$key = 'NodeMCU';

// Get POST body content

$content = file_get_contents('php://input');
$Get_Status = file_get_contents('https://api.anto.io/channel/get/'. $Token_anto .'/'.$key.'/Status');
// Parse JSON
$events = json_decode($content, true);
$event_Status = json_decode($Get_Status, true);
// Validate parsed JSON data
function Check_Status($_xStaus){
	if ($_xStaus == "0"){
		$messages = [
		'type' => 'text',
		'text' => 'ปิด'//$event_Status['events']['value'];
		];
	}else{
		$messages = [
		'type' => 'text',
		'text' => 'เปิด'//$event_Status['events']['value'];
		];
	}		   
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
			if($text == strtoupper("GETSTATUS") || $text == "สถานะ" ){				
				if ($event_Status['result'] == "true") {
					Check_Status($event_Status['value']);
				}	
				
							
			}else if ($text == strtoupper("ON") || $text == "เปิด" ){
				$_Status = 1
				$messages = [
				'type' => 'text',
				'text' => 'เปิด เรียบร้อย'
				];
			}else if ($text == strtoupper("OFF") || $text == "ปิด" ){
				$_Status = 0				
				$messages = [
				'type'$_Status = 0 => 'text',
				'text' => 'ปิด เรียบร้อย'
				];
			}else{ $_Status = -1 }
			if(!$_Status == -1){
				$Get_Status = file_get_contents('https://api.anto.io/channel/set/'. $Token_anto .'/'.$key.'/Status/'.$_Status);
				$event_Status = json_decode($Get_Status, true);
				if ($event_Status['result'] == "true") {
					Check_Status($event_Status['value']);
				}	
			}else
			{
				$messages = [
				'type'$_Status = 0 => 'text',
				'text' => 'ไม่โนเงื่อนไขเหี่้ยออะไรเลย'
				];
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
