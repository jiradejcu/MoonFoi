<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'parser.php';

use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;

define('CHANNEL_ACCESS_TOKEN', '7cjzSpFFymohVQYGhOeEU7ilbD63+vJi0kq370gDvVJAmEcQbFPGx510jN4Ler+MJqlWGp4Z4pud50p3awCOA2hTaYXyt8LFbmPY6jYI9jsdQUz/HUn7cnfz/fgNGBRxWVzd0pWTcMTmjf2p3ET1+gdB04t89/1O/w1cDnyilFU=');
define('CHANNEL_SECRET', '0fbe9cf82e4136a094f52111c69a7f10');

$httpClient = new CurlHTTPClient(CHANNEL_ACCESS_TOKEN);
$bot = new LINEBot($httpClient, ['channelSecret' => CHANNEL_SECRET]);

$content = file_get_contents('php://input');
$events = json_decode($content, true);

if (!empty($events['events'])) {
	foreach ($events['events'] as $event) {
		switch ($event['type']) {
			case 'message':
				$message = $event['message'];
				if (in_array($message['type'], ['text', 'location'])) {
					$response_message = parseMessage($message, $message['type'], $event['source']['userId']);
				} else {
					error_log("Unsupported message type: " . $message['type']);
				}

				if (!empty($response_message)) {
					$messageBuilder = null;
					if ($response_message['type'] == 'text') {
						$messageBuilder = new TextMessageBuilder($response_message['message']);
					} else if ($response_message['type'] == 'button') {

					} else if ($response_message['type'] == 'confirm') {
						$confirm = new ConfirmTemplateBuilder($response_message['message'], [
							new MessageTemplateActionBuilder('Yes', 'Yes'),
							new MessageTemplateActionBuilder('No', 'No'),
						]);
						$messageBuilder = new TemplateMessageBuilder($response_message['message'], $confirm);
					}

					if (!empty($messageBuilder)) {
						$response = $bot->replyMessage($event['replyToken'], $messageBuilder);
						if ($response->isSucceeded()) {
							error_log('Success');

							return;
						}
					}

					error_log('Fail ' . $response->getRawBody());
					break;
				}
			default:
				error_log("Unsupported event type: " . $event['type']);
				break;
		}
	}
}