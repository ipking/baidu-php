<?php

include '.config.php';
include '.token.php';
/**
 * @var array $options
 * @var array $token
 */

$api = new \Baidu\Xpan\Api\File();
$api->setEndpoint($options['endpoint']);


$dir = '/';
$search_arr = [
	'xxx' => '',
	'zzz' => '',
];

foreach($search_arr as $search => $rr){
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
			
			if($item['isdir'] == 0){
				$flag = 0;
				foreach($search_arr as $s=>$r){
					if(strpos($tmp_name,$s)){
						$tmp_name = str_replace($s,$r,$tmp_name);
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
			if($page > 1){
				$page = 1;
				goto loop;
			}
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