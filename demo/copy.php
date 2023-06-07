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
	'opera'        => "copy",
];
$body = [
	'async'    => 1,
	'filelist' => json_encode([
					[
						"path"=>"/1.PanD",
						"dest"=>"/",
						"newname"=>"copy.PanD",
						"ondup"=>"fail",
					]
				]),
];
$rsp = $api->manager($query,$body);
if(!$api->isSuccess()){
	die();
}