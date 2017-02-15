<?php
require_once 'flow.php';

function parseMessage($message, $type, $actor) {
	$responseMessage = [
		'type'    => 'text',
		'message' => 'วันหลังมาใหม่นะครับ'
	];

	$state = getState($actor);

	switch ($state) {
		case INIT :
			setState($actor, ASKING);

			$responseMessage = [
				'type'    => 'confirm',
				'message' => 'ให้ไปเก็บขยะใช่มั้ยครับ'
			];
			break;

		case ASKING :
			if ($message['text'] == 'Yes') {
				setState($actor, LOCATION);

				$responseMessage = [
					'type'    => 'text',
					'message' => 'ขอตำแหน่งที่ตั้งของคุณหน่อยครับ'
				];
			}
			break;

		case LOCATION:
			if ($type == 'location') {
				setState($actor, INIT);

				requestPickup($actor, $message['latitude'], $message['longitude']);

				$responseMessage = [
					'type'    => 'text',
					'message' => 'เดี๋ยวจะส่งคนไปเก็บครับ ขอบคุณครับ'
				];
			}
			break;
	}

	return $responseMessage;
}