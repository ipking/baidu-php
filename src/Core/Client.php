<?php

namespace Baidu\Core;

abstract class Client{
	
	const METHOD_GET = 'GET';
	const METHOD_POST = 'POST';
	
	private static $success_codes = [0];
	
	protected static $callback;
	
	protected $method;
	
	protected $endpoint;
	
	protected $url;
	
	protected $data;
	
	protected $client_response;
	
	protected $response_code;
	
	protected $response_body;
	
	/**
	 * @param $cb
	 */
	public static function setSendCallback($cb){
		self::$callback = $cb;
	}
	
	
	/**
	 * @return string
	 */
	public function getMethod(){
		return $this->method;
	}
	
	/**
	 * @return string
	 */
	public function getUrl(){
		return $this->url;
	}
	
	/**
	 * @return string
	 */
	public function getData(){
		return $this->data;
	}
	
	/**
	 * @return string
	 */
	public function getResponse(){
		return $this->client_response;
	}
	
	/**
	 * @param string $endpoint 请求接入点域名(xx.[http://pan.baidu.com)
	 */
	public function setEndpoint($endpoint)
	{
		$this->endpoint = $endpoint;
	}
	
	/**
	 * @param string $uri
	 * @param array $requestOptions
	 * @return array
	 * @throws HttpException|\Exception
	 */
	protected function send($uri, $requestOptions = []){
		$this->data = null;
		$this->method = strtoupper($requestOptions['method']);
		$this->url = $this->endpoint.$uri;
		
		if (isset($requestOptions['query'])) {
			$this->url .= '?' . http_build_query($requestOptions['query']);
		}
		
		$header_arr = [];
		
		switch($this->method){
			case self::METHOD_GET:
				$opt = array(
					CURLOPT_HTTPHEADER     => $header_arr,
				);
				
				return $this->execute($this->url,$opt);
			case self::METHOD_POST:
				$data = [];
				if($requestOptions['fields']){
					$data = http_build_query($requestOptions['fields']);
					$header_arr[] = 'Content-Type: application/x-www-form-urlencoded';
				}
				$opt = array(
					CURLOPT_POST           => true,
					CURLOPT_HTTPHEADER     => $header_arr,
					CURLOPT_POSTFIELDS     => $data,
				);
				$this->data = $data;
				return $this->execute($this->url,$opt);
			default :
				throw new \Exception('Not support method :'.$this->method);
		}
		
	}
	
	/**
	 * @param string $url
	 * @param array $opt
	 * @return array|mixed
	 * @throws HttpException
	 */
	public function execute($url, $opt){
		$this->response_code = '';
		$this->client_response = Curl::execute($url,$opt);
		list($response_body,$response_code) = $this->client_response;
		$this->response_code = $response_code;
		
		if(is_callable(self::$callback)){
			$callback = self::$callback;
			$callback($this);
		}
		$this->response_body = $response_body?json_decode($response_body, true):'';
		return $this->response_body;
	}
	
	/**
	 * @return bool
	 */
	public function isSuccess(){
		return in_array($this->response_body['errno'],self::$success_codes);
	}
}