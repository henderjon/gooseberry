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
	 * method to set te auth headers
	 * @param string $user Username
	 * @param string $pass Password
	 * @return
	 */
	function setAuth($user, $pass = "", $method = CURLAUTH_BASIC){
		$this->setAdditionalOptions(array(
			CURLOPT_USERPWD => sprintf("%s:%s", $user, $pass),
			CURLOPT_HTTPAUTH => $method,
		));
	}
	/***/
	function get($endpoint, array $data = array()){
		$url = $this->normalizeEndpoint($endpoint);
		if($url){
			$this->setGetRequest($url, $data);
			return $this->ping();
		}
		throw new Exceptions\InvalidEndpointException("That endpoint doesn't exist");
	}
	/***/
	function post($endpoint, array $data = array()){
		$url = $this->normalizeEndpoint($endpoint);
		if($url){
			$this->setPostRequest($url, $data);
			return $this->ping();
		}
		throw new Exceptions\InvalidEndpointException("That endpoint doesn't exist");
	}
	/***/
	function put($endpoint, array $data = array()){
		$url = $this->normalizeEndpoint($endpoint);
		if($url){
			$this->setPutRequest($url, $data);
			return $this->ping();
		}
		throw new Exceptions\InvalidEndpointException("That endpoint doesn't exist");
	}
	/***/
	function getInfo($opt = 0){
		return curl_getinfo($this->handle, $opt);
	}
	/***/
	protected function normalizeEndpoint($endpoint){
		$url = "";
		// drop($endpoint, $this->endpoints);
		if(in_array($endpoint, $this->endpoints)){
			$url = "{$this->apiURL}/{$endpoint}";
		}
		return $url;
	}
	/**
	 * magic method catchall
	 * @param string $name The name of the endpoint as a method call
	 * @param array $args An array of parameters
	 * @return object
	 */
	protected function ping(){
		$handle = curl_init();
		curl_setopt_array($handle, $this->options);
		$response           = curl_exec($handle);
		$this->responseInfo = curl_getinfo($handle);
		curl_close($handle);
		return $response;
	}
}