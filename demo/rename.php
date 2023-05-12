<?php

include '.config.php';
/**
 * @var array $options
 */

$api = new \Baidu\Xpan\Api\File();
$api->setEndpoint($options['endpoint']);

$query = [
	'access_token' => $options['access_token'],
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
	print_r($rsp);
	die();
}


print_r($rsp);