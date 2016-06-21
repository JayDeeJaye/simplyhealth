<?php

// Common exception handler, returns an error  response with the message in it
set_exception_handler(function ($e) {
	$code = $e->getCode() ?: 400;
	header("Content-Type: application/json", NULL, $code);
	echo json_encode(["error" => $e->getMessage()],JSON_PRETTY_PRINT);
	exit;
});

// Get the request verb
$verb = $_SERVER['REQUEST_METHOD'];

switch($verb) {
    case 'GET':
    case 'PUT':
    case 'DELETE':
    //GET, PUT, and DELETE requests have the form api.php/target

    $my_path_info = str_replace($_SERVER['SCRIPT_NAME'],'',$_SERVER['REQUEST_URI']);
    $url_pieces = explode('/', $my_path_info);
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
