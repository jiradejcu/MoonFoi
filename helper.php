<?php

define('URL', 'http://api.moonfoi.bumbliss.com/');

/**
 * Send a POST requst using cURL
 *
 * @param string $url to request
 * @param array $post values to send
 * @param array $options for cURL
 *
 * @return string
 */
function curl_post($url, array $post = null, array $options = array()) {
	$defaults = array(
		CURLOPT_POST           => 1,
		CURLOPT_HEADER         => 0,
		CURLOPT_URL            => URL . $url,
		CURLOPT_FRESH_CONNECT  => 1,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_FORBID_REUSE   => 1,
		CURLOPT_TIMEOUT        => 4,
		CURLOPT_POSTFIELDS     => http_build_query($post)
	);

	$ch = curl_init();
	curl_setopt_array($ch, ($options + $defaults));
	if (!$result = curl_exec($ch)) {
		trigger_error(curl_error($ch));
	}
	curl_close($ch);

	return $result;
}

/**
 * Send a GET requst using cURL
 *
 * @param string $url to request
 * @param array $get values to send
 * @param array $options for cURL
 *
 * @return string
 */
function curl_get($url, array $get = null, array $options = array()) {
	$defaults = array(
		CURLOPT_URL            => URL . $url . (strpos($url, '?') === false ? '?' : '') . http_build_query($get),
		CURLOPT_HEADER         => 0,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT        => 4
	);

	$ch = curl_init();
	curl_setopt_array($ch, ($options + $defaults));
	if (!$result = curl_exec($ch)) {
		trigger_error(curl_error($ch));
	}
	curl_close($ch);

	return $result;
}