<?php

session_start();
$params = json_decode(file_get_contents("php://input"), true);
if (isset($_SESSION['username'])) {
    unset($_SESSION['username']);
}
$_SESSION['username'] = $params['username'];
echo json_encode($_SESSION);
