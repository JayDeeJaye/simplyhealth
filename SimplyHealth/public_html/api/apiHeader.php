<?php
set_exception_handler(function ($e) {
	$code = $e->getCode() ?: 400;
	header("Content-Type: application/json", NULL, $code);
	echo json_encode(["error" => $e->getMessage()]);
	exit;
});

$verb = $_SERVER['REQUEST_METHOD'];

if ($verb != 'GET') {
    $params = json_decode(file_get_contents("php://input"), true);
    if(!$params) {
        throw new Exception("Data missing or invalid");
    }
}

require_once('../php/DBConfig.php');
