<?php
require_once 'flow.php';

function parseMessage($message, $actor) {
	$responseMessage = $message;

	$state = getState($actor);

	if ($state == INIT) {
		$responseMessage = [
			'type'    => 'confirm',
			'message' => 'ให้ไปเก็บขยะใช่มั้ยครับ'
		];
	}

	return $responseMessage;
}