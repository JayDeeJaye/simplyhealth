<?php

// Common exception handler, returns an error  response with the message in it
set_exception_handler(function ($e) {
	$code = $e->getCode() ?: 400;
        if (isset($dbConn)) {
            $dbConn->close();
        }
	header("Content-Type: application/json", NULL, $code);
	echo json_encode(["error" => $e->getMessage()]);
	exit;
});

// Get the request verb
$verb = $_SERVER['REQUEST_METHOD'];

switch($verb) {
    case 'GET':
    case 'PUT':
    case 'DELETE':
    //GET, PUT, and DELETE requests have the form api.php/target
    $url_pieces = explode('/', $_SERVER['REQUEST_URI']);
}

switch($verb) {
    case 'PUT':
    case 'POST':
        $params = json_decode(file_get_contents("php://input"), true);
        if(!$params) {
            throw new Exception("Data missing or invalid");
        }
}

// Database configuration
require_once('../php/DBConfig.php');
