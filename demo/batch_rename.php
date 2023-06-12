<?php

include '.config.php';
include '.token.php';
/**
 * @var array $options
 * @var array $token
 */

$api = new \Baidu\Xpan\Api\File();
$api->setEndpoint($options['endpoint']);

$page = 0;
$dir = '/';
$search_arr = [
	'xxx',
	'zzz',
];
$replace = 'yyy';

foreach($search_arr as $search){
	while(1){
		$page++;
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
			die(__LINE__);
		}
		
		$path_list = [];
		foreach($rsp['list']?:[] as $item){
			$tmp_name = $server_filename = $item['server_filename'];
			
			if($item['isdir'] == 0){
				$flag = 0;
				foreach($search_arr as $s){
					if(strpos($tmp_name,$s)){
						$tmp_name = str_replace($s,$replace,$tmp_name);
						$flag = 1;
					}
				}
				if($flag){
					$path_list[] = [
						"path"=>$item['path'],
						"newname"=>$tmp_name,
					];
				}
			}
		}
		
		brename($token, $api, $path_list);
		
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
function brename($token, $api, $path_list){
	if(!$path_list){
		return;
	}
	$query = [
		'access_token' => $token['access_token'],
		'method'       => "filemanager",
		'opera'        => "rename",
	];
	$body = [
		'async'    => 0,
		'filelist' => json_encode($path_list),
		'ondup'    => "overwrite",
	];
	$rsp = $api->manager($query,$body);
	if(!$api->isSuccess()){
		//die(__LINE__);
	}
}