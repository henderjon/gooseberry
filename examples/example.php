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

$dingle->setHeaders([
	"User-Agent"    => "PHP CLI - Gooseberry",
]);

//$var = $Obj->HTTPMethod($endoint, $queryData);
$response = $dingle->post("post", array("test_key" => "test_value"));

print_r($response);

