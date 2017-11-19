<?php
$access_token = 'SG3JBmTZEAbFxdexsK8JwxrPOle/+IFg/R3TmQKfVIUuJSrcRJOsffKPXXtrEy0q3HahfPxP4P4lxrFbFFWpC7fgOPtEAtxSoSChY3fTJbK2JQfaTqHOxh+EMffXSCLdBlzmkn5OVTtLk3lyD+7VkQdB04t89/1O/w1cDnyilFU=';
$ composer require linecorp/line-bot-sdk
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient($access_token);
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => '3d61b46618a33ed31fca7f69eb01ac53']);

$content = file_get_contents('php://input');
$events = json_decode($content, true);
if(!is_null($events['events']) ){
		foreach ($events['events'] as $event) {
			// Reply only when message sent is in 'text' format
			if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
				// Get text sent
				$text = $event['message']['text'];
				// Get replyToken
				$replyToken = $event['replyToken'];
        $response = $bot->replyText($replyToken, 'hello!');
			}
      }
	}
echo "OK";

