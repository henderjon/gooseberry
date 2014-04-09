<?php

namespace Gooseberry;

class Gooseberry extends cURLOptions {
	/**
	 * Explicitly define the base url/version
	 */
	protected $apiURL = "";
	/**
	 * Explicitly define the endpoints
	 */
	protected $endpoints = array();
	/**
	 * hold the cURL connection info
	 */
	protected $responseInfo = array();

	protected $method, $user_agent;

	protected $headers = array();

	function setHeaders(array $headers){
		foreach($headers as $header => $value){
			$this->headers[$header] = trim($value);
		}
	}
	/**
	 * Description
	 * @param type $user
	 * @param type $pass
	 * @return type
	 */
	function __construct($api, array $endpoints){
		$this->apiURL    = $api;
		$this->endpoints = $endpoints;
	}
	/***/
	function get($endpoint, array $data = array()){
		$url = $this->normalizeEndpoint($endpoint, $data);

		$context = $this->createContext("GET");

		return $this->ping($url, $context);
	}
	/***/
	function post($endpoint, array $data = array()){
		$url = $this->normalizeEndpoint($endpoint);

		$this->setHeaders(["Content-type" => "application/x-www-form-urlencoded"]);

		$context = $this->createContext("POST", $data);

		return $this->ping($url, $context);
	}
	/***/
	protected function createContext($method, array $data = array()){

		$opts = array("http" => array("method" => $method));

		if($this->headers){
			$opts["http"]["header"] = $this->assembleHeaders();
		}

		if($this->user_agent){
			$opts["http"]["user_agent"] = $this->user_agent;
		}

		if($data){
			$opts["http"]["content"] = $this->toQueryString($data);
		}

		return stream_context_create($opts);
	}
	/***/
	protected function normalizeEndpoint($endpoint, array $data = array()){
		$url = "";

		if(in_array($endpoint, $this->endpoints)){
			$url = "{$this->apiURL}/{$endpoint}";
			if($data){
				$url = sprintf("%s?%s", rtrim($url, " ?"), $this->toQueryString($data));
			}
		}else{
			throw new Exceptions\InvalidEndpointException("That endpoint doesn't exist");
		}

		return $url;
	}
	/***/
	protected function toQueryString(array $data = array()){
		if($data){
			return http_build_query($data, "no_", "&");
		}
	}
	/***/
	protected function assembleHeaders(){
		$finalHeader = array();
		foreach($this->headers as $header => $value){
			$finalHeader[] = "{$header}: {$value}";
		}
		return implode("\r\n", $finalHeader);
	}
	/***/
	protected function ping($url, $context){
		$stream = fopen($url, 'r', false, $context);
		$this->responseInfo = stream_get_meta_data($stream);
		$response = stream_get_contents($stream);
		fclose($stream);
		return $response;
	}
}

// http://www.daniweb.com/web-development/php/threads/370401/multiple-file-upload-problem-using-stream_context_create-function