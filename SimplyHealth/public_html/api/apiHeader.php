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

// POST, PUT data
if ($verb == 'GET') {
    //GET requests have the form api.php/route/target
    $url_pieces = explode('/', $_SERVER['PATH_INFO']);
} else {
    $params = json_decode(file_get_contents("php://input"), true);
    if(!$params) {
        throw new Exception("Data missing or invalid");
    }
}

// Database configuration
require_once('../php/DBConfig.php');
