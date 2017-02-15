<?php
require_once 'helper.php';

define('INIT', 0);
define('ASKING', 1);
define('LOCATION', 2);

function getState($actor) {
	$state = curl_get('state', ['customer_id' => $actor]);

	return $state;
}

function setState($actor, $state) {
	curl_post('state', [
		'customer_id' => $actor,
		'state'       => $state
	]);
}

function requestPickup($actor, $lat, $lon) {
	curl_post('state', [
		'customer_id' => $actor,
		'lat'         => $lat,
		'lon'         => $lon
	]);
}