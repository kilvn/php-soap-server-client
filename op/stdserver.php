<?php

$soap_dir = '../tmp/';
ini_set('soap.wsdl_cache_dir', $soap_dir);

include_once('./functions.php');
$server = new SoapServer('./soap.wsdl', array('soap_version' => SOAP_1_2));

function doAct($func, $args){
	return method::$func($args);
}

$server->addFunction('doAct');
$server->handle();