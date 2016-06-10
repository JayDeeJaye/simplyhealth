<?php

require_once('apiHeader.php');

// Open a connection to the database
$dbConn= new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

if ($verb == 'POST') {
    // Add the user to the database
    $username = mysqli_real_escape_string($dbConn,$params['username']);
    $password = mysqli_real_escape_string($dbConn,$params['password']);
    $sql ="INSERT INTO users (username,password) values ('$username','$password')";

    if ($dbConn->query($sql)) {
        // success
        $userId = $dbConn->insert_id;
        $status = "201";
        $url="api/users.php/$userId";
        $header="Location: api/users.php/$userId; Content-Type: application/json";
        $data['id']=$userId;
    } else {
        $status="400";
        $data['error']=  mysqli_error($dbConn);
        $header="Content-Type: application/json";
    }
} else {
    // unhandled function
}
// Send the response

$dbConn->close();

header($header,null,$status);
echo json_encode($data);
