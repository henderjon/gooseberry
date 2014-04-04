<?php

namespace Gooseberry;

abstract class cURLOptions {

	/**
	 * Default options
	 */
	protected $options = array(
		CURLOPT_RETURNTRANSFER => true,
	);

	/**
	 * method to set up cURL for a proper POST reqeust
	 * @param string $url The URL
	 * @param array $data The parameters of the reqeust
	 * @return
	 */
	function setPostRequest($url, array $data = array()){
		$this->options[CURLOPT_URL]            = $url;
		if($data){
			$this->options[CURLOPT_POST]       = true;
			$this->options[CURLOPT_POSTFIELDS] = $data;
		}
	}

	/**
	 * method to set up cURL for a proper GET reqeust
	 * @param string $url The URL
	 * @param array $data The parameters of the reqeust
	 * @return
	 */
	function setGetRequest($url, array $data = array()){
		if($data){
			$url = sprintf("%s?%s", $url, http_build_query($data, "no_", "&"));
		}
		$this->options[CURLOPT_HTTPGET] = true;
		$this->options[CURLOPT_URL]     = $url;
	}

	/**
	 * method to set up cURL for a proper PUT reqeust
	 * @param string $url The URL
	 * @param array $data The parameters of the reqeust
	 * @return
	 */
	function setPutRequest($url, array $data = array()){
		$this->options[CURLOPT_PUT]        = true;
		$this->options[CURLOPT_INFILE]     = $data[CURLOPT_INFILE];
		$this->options[CURLOPT_INFILESIZE] = $data[CURLOPT_INFILESIZE];
		unset($data[CURLOPT_INFILE], $data[CURLOPT_INFILESIZE]);
		if($data){
			$url = sprintf("%s?%s", $url, http_build_query($data, "no_", "&"));
		}
		$this->options[CURLOPT_URL] = $url;
	}

	/**
	 * method to set up cURL for a proper reqeust not already handled by name
	 * @param string $url The URL
	 * @param array $data The parameters of the reqeust
	 * @return
	 */
	function setOtherRequest($action, $url, array $data = array()){
		if($data){
			$url = sprintf("%s?%s", $url, http_build_query($data, "no_", "&"));
		}
		$this->options[CURLOPT_CUSTOMREQUEST] = $action;
		$this->options[CURLOPT_URL]           = $url;
	}
	/**
	 * method to set additional cURL options
	 * @param array $headers The options as an array
	 * @return type
	 */
	function setAdditionalOptions(array $headers){
		foreach($options as $option => $value){
			$this->options[$option] = $value;
		}
	}

	/**
	 * method to set specific headers for the cURL request
	 * @param array $headers The headers to send
	 * @param bool $fresh Flag to add/replace current headers
	 * @return
	 */
	function setAdditionalHeaders(array $headers, $fresh = false){
		$final = array();

		if(array_key_exists(CURLOPT_HTTPHEADER, $this->options)){
			if($fresh){
				unset($this->options[CURLOPT_HTTPHEADER]);
			}else{
				$final = $this->options[CURLOPT_HTTPHEADER];
			}
		}

		foreach($headers as $name => $value){
			$final[] = sprintf("%s: %s", ucwords($name), strtr($value, "\n", "-"));
		}

		if($final){
			// $this->options[CURLOPT_HEADER] = true;
			// $this->options[CURLINFO_HEADER_OUT] = true;
			$this->options[CURLOPT_HTTPHEADER] = $final;
		}
	}

}