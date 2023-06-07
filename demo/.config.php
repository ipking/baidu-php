<?php


include dirname(__DIR__).'/src/autoload.inc.php';

$options = [
	'app_id' => '',//应用id
	'app_key' => '', //应用key
	'secret_key' => '', //秘钥key
	'sign_key' => '', //签名key
	'endpoint' => 'http://pan.baidu.com', //接入域名//接口鉴权参数
];

//设置 请求成功时的 回调函数 可以用于收集日志记录 给请求加上请求 ID  用于跟踪
//根据自己的系统 业务需要 保存到 文件 数据库等等地方
Baidu\Core\Client::setSendCallback(function(Baidu\Core\Client $client){
	echo $client->getMethod().' ';
	echo $client->getUrl().PHP_EOL;
	echo $client->getData().PHP_EOL;
	echo json_encode(json_decode($client->getResponse()[0],1),320).PHP_EOL;
	
});