<?php

require_once('apiHeader.php');

// Open a connection to the database
$dbConn= new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

if ($verb == 'GET') {
    // GET rolename
    $rolename = $url_pieces[count($url_pieces) - 1];
    $sql = "SELECT id FROM roles WHERE rolename = '$rolename'";

    if ($result = $dbConn->query($sql)) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            $data['id'] = $row['id'];

            $status = "200";
            $header="Content-Type: application/json";
        } else {
            // No such record in the database
            throw new Exception("Role not found","404");
        } // fetch patient
        $result->close();
    } else {
        throw new Exception(mysqli_error($dbConn),"500");
    } // execute query
} else {
    throw new Exception("Method Not Supported: $verb", 405);
}
// Send the response

$dbConn->close();

header($header,null,$status);
echo json_encode($data);
