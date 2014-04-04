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

$response = $dingle->post("post", array("test_key" => "test_value"));

print_r($response);
