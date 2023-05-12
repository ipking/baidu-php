<?php

namespace Baidu\Xpan\Api;

use Baidu\Core\Client;

class File extends Client {

	/**
	 * Operation getList
	 * @param array $query
	 */
	public function getList($query = [])
	{
		return $this->send("/rest/2.0/xpan/file", [
		  'method' => Client::METHOD_GET,
		  'query'  => $query,
		]);
	}
	
	/**
	 * Operation manager
	 * @param array $query
	 * @param array $body
	 */
	public function manager($query = [],$body = [])
	{
		return $this->send("/rest/2.0/xpan/file", [
		  'method' => Client::METHOD_POST,
		  'query'  => $query,
		  'fields'  => $body,
		]);
	}
}
