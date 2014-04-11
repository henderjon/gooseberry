<?php

require_once "vendor/autoload.php";

FUnit::test("Gooseberry::get()", function(){

	$dingle = new \Gooseberry\Gooseberry("http://httpbin.org", ["get", "post", "headers"]);
	$response = $dingle->get("get", array("test_key" => "test_value"));

	$decoded = json_decode($response, true);

	FUnit::equal($decoded["args"], ["test_key" => "test_value"], "get response 'form' property");
	FUnit::equal($decoded["url"], "http://httpbin.org/get?test_key=test_value", "get response 'url' property");

});

FUnit::test("Gooseberry::post()", function(){

	$dingle = new \Gooseberry\Gooseberry("http://httpbin.org", ["get", "post", "headers"]);
	$response = $dingle->post("post", array("test_key" => "test_value"));

	$decoded = json_decode($response, true);

	FUnit::equal($decoded["form"], ["test_key" => "test_value"], "post response 'form' property");
	FUnit::equal($decoded["url"], "http://httpbin.org/post", "post response 'url' property");

});

FUnit::test("Gooseberry::getInfo()", function(){//wrapper_type

	$dingle = new \Gooseberry\Gooseberry("http://httpbin.org", ["get", "post", "headers"]);
	$response = $dingle->post("post", array("test_key" => "test_value"));

	$info = $dingle->getInfo();

	FUnit::equal($info["wrapper_type"], "http");

});

FUnit::test("Gooseberry::setHeaders()", function(){

	$dingle = new \Gooseberry\Gooseberry("http://httpbin.org", ["get", "post", "headers"]);

	$dingle->setHeaders([
		"User-Agent" => "PHP CLI - Gooseberry",
	]);

	$response = $dingle->post("post", array("test_key" => "test_value"));

	$decoded = json_decode($response, true);

	FUnit::equal($decoded["headers"]["User-Agent"], "PHP CLI - Gooseberry");

});