<?php

include '.config.php';
include '.token.php';
/**
 * @var array $options
 * @var array $token
 */

$api = new \Baidu\Xpan\Api\Token();
$api->setEndpoint('http://openapi.baidu.com/oauth/2.0');

$query = [
	'grant_type'    => 'refresh_token',
	'refresh_token' => $token['refresh_token'],
	'client_id'     => $options['app_key'],
	'client_secret' => $options['secret_key'],
];

$rsp = $api->token($query);
if(!$api->isSuccess()){
	die();
}

$sts_token = '$token';

$content = <<<EOL
<?php
$sts_token = [
	'refresh_token'   =>'{$rsp['refresh_token']}',
	'access_token'    =>'{$rsp['access_token']}',
];

EOL;

file_put_contents('.token.php',$content);