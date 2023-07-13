<?php

include '.config.php';
include '.token.php';
/**
 * @var array $options
 * @var array $token
 */

$api = new \Baidu\Xpan\Api\File();
$api->setEndpoint($options['endpoint']);


$dir = '/我的资源/1.视频/';
$search_arr = [
	'.mp4',
	'.sz',
	'.vop',
];

foreach($search_arr as $search){
	$page = 0;
	while(1){
		$page++;
		loop:
		$query = [
			'access_token' => $token['access_token'],
			'method'       => "search",
			'key'          => $search,
			'recursion'    => 1,
			'dir'          => $dir,
			'page'         => $page,
		];
		$rsp = $api->getList($query);
		if(!$api->isSuccess()){
			if($rsp['errno'] == 31034){
				//接口请求过于频繁，注意控制。
				sleep(5);
				goto loop;
			}
			//die(__LINE__);
		}
		
		$path_list = [];
		foreach($rsp['list']?:[] as $item){
			$tmp_name = $item['server_filename'];
			$tmp_path = str_replace($item['server_filename'],'',$item['path']);
			if($item['isdir'] == 0){
				$flag = 0;
				if(strpos($tmp_name,$search)){
					$flag = 1;
				}
				if($flag){
					$path_list[md5($tmp_path)][] = $item['path'];
				}
			}
		}
		foreach($path_list?:[] as $lis){
			bdelete($token, $api, $lis);
		}
		
		
		if($rsp['has_more'] == 0){
			break;
		}
	}
}



/**
 * @param array $token
 * @param \Baidu\Xpan\Api\File $api
 * @param array $path_list
 * @return void
 */
function bdelete($token, $api, $path_list){
	if(!$path_list){
		return;
	}
	$query = [
		'access_token' => $token['access_token'],
		'method'       => "filemanager",
		'opera'        => "delete",
	];
	$body = [
		'async'    => 0,
		'filelist' => json_encode($path_list,320),
		'ondup'    => "overwrite",
	];
	do{
		$rsp = $api->manager($query,$body);
		if(!$api->isSuccess()){
			//die(__LINE__);
		}
		if($rsp['errno'] == 31034){
			sleep(5);
			continue;
		}
		break;
	}while(1);
	
}