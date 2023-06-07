<?php

namespace Baidu\Xpan\Api;

use Baidu\Core\Client;

class Token extends Client {

	/**
	 * Operation token
	 * @param array $query
	 */
	public function token($query = [])
	{
		return $this->send("/token", [
		  'method' => Client::METHOD_GET,
		  'query'  => $query,
		]);
	}
}
