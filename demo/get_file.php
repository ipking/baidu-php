<?php

include '.config.php';
include '.token.php';
/**
 * @var array $options
 * @var array $token
 */

$api = new \Baidu\Xpan\Api\File();
$api->setEndpoint($options['endpoint']);

$query = [
	'access_token' => $token['access_token'],
	'method'       => "search",
	'key'          => ".PanD",
	'recursion'    => 1,
	'dir'          => "/",
	'page'         => 1,
	'num'          => 500,
];
$rsp = $api->getList($query);
if(!$api->isSuccess()){
	die();
}