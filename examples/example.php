#!/usr/bin/env php
<?php

require "vendor/autoload.php";

$api = "http://httpbin.org";

$endpoints = array(
	"get",
	"post",
	"user-agent",
	"headers",
	"ip",
);

$dingle = new \Gooseberry\Gooseberry($api, $endpoints);

//$var = $Obj->HTTPMethod($endoint, $queryData);

$dingle->setHeaders([
	"X-test-header" => "this is totally a test header",
	"User-Agent"    => "does this work?",
]);

$response = $dingle->post("post", array("test_key" => "test_value"));

print_r($response);

