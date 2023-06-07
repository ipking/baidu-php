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
	'method'       => "filemanager",
	'opera'        => "rename",
];
$body = [
	'async'    => 1,
	'filelist' => json_encode([
		[
			"path"=>"/copy.PanD",
			"newname"=>"2.PanD",
		]
	]),
	'ondup'    => "overwrite",
];
$rsp = $api->manager($query,$body);
if(!$api->isSuccess()){
	die();
}