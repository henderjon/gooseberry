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
	/**
	 * attributes required by stream_context_create()
	 */
	protected $method, $user_agent;
	/**
	 * attribute to store the headers to be used for the request
	 */
	protected $headers = array();
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
	/**
	 * method to get the info of the last request
	 * @return array
	 */
	function getInfo(){
		return $this->responseInfo;
	}
	/**
	 * Method to trigger a GET request to a given endpoint with the given data
	 * @param string $endpoint A valid endpoint
	 * @param array $data The data to send in the request
	 * @return string
	 */
	function get($endpoint, array $data = array()){
		$url = $this->normalizeEndpoint($endpoint, $data);

		$context = $this->createContext("GET");

		return $this->ping($url, $context);
	}
	/**
	 * Method to trigger a POST request to a given endpoint with the given data
	 * @param string $endpoint A valid endpoint
	 * @param array $data The data to send in the request
	 * @return string
	 */
	function post($endpoint, array $data = array()){
		$url = $this->normalizeEndpoint($endpoint);

		$this->setHeaders(["Content-type" => "application/x-www-form-urlencoded"]);

		$context = $this->createContext("POST", $data);

		return $this->ping($url, $context);
	}
	/**
	 * method to set additional headers to be sent with the request
	 * @param array $headers An array of key => value pairs to use to create headers
	 * @return
	 */
	function setHeaders(array $headers){
		foreach($headers as $header => $value){
			$this->headers[strtolower($header)] = trim($value);
		}
	}
	/**
	 * Method to combine the various information necessary to create a stream
	 * context. POST requests send data here
	 *
	 * @param string $method The HTTP method
	 * @param array $data The content of the request
	 * @return resource
	 */
	protected function createContext($method, array $data = array()){

		$opts = array("http" => array("method" => $method));

		if($this->headers){
			$opts["http"]["header"] = $this->assembleHeaders();
		}

		if($this->user_agent){ // can be set as a header
			$opts["http"]["user_agent"] = $this->user_agent;
		}

		if($data){
			$opts["http"]["content"] = $this->toQueryString($data);
		}

		return stream_context_create($opts);
	}
	/**
	 * method to validate and normalize the URL/endpoint of the request. GET
	 * requests send their data here
	 *
	 * @param string $endpoint The endpoint to access
	 * @param array $data The content of the request
	 * @return string
	 */
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
	/**
	 * method to take an array of data and create a valid query string to send as
	 * part of the URL or as the body of the request
	 * @param array $data The data to encode
	 * @return string
	 */
	protected function toQueryString(array $data = array()){
		if($data){
			return http_build_query($data, "no_", "&");
		}
	}
	/**
	 * method to normalize the headers
	 * @return string
	 */
	protected function assembleHeaders(){
		$finalHeader = array();
		foreach($this->headers as $header => $value){
			$finalHeader[] = sprintf("%s: %s", ucwords($header), trim($value));
		}
		return implode("\r\n", $finalHeader);
	}
	/**
	 * method to execute the request.
	 * @param type $url
	 * @param type $context
	 * @return type
	 */
	protected function ping($url, $context){
		$stream = fopen($url, 'r', false, $context);
		$this->responseInfo = stream_get_meta_data($stream);
		$response = stream_get_contents($stream);
		fclose($stream);
		return $response;
	}
}

