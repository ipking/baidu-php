<?php

include '.config.php';
/**
 * @var array $options
 */

$api = new \Baidu\Xpan\Api\File();
$api->setEndpoint($options['endpoint']);

$query = [
	'access_token' => $options['access_token'],
	'method'       => "search",
	'key'          => ".PanD",
	'recursion'    => 1,
	'dir'          => "/",
	'page'         => 1,
	'num'          => 500,
];
$rsp = $api->getList($query);
if(!$api->isSuccess()){
	print_r($rsp);
	die();
}

print_r($rsp);