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
        throw new Exception(mysqli_error($dbConn),"400");
    }
} else if ($verb == 'GET') {
    if ($url_pieces[1] == "me") {
        // Get the current user and info
        session_start();

        if (!($me = $_SESSION['username'])) {
            throw new Exception("No current user!");
        }
        $sql = "SELECT u.id userId, p.id patientId, p.firstname firstName "
                 . "FROM users u LEFT JOIN patient p ON (p.userid = u.id) "
                 . "WHERE u.username='$me'";

        if ($result = $dbConn->query($sql)) {

            $row = $result->fetch_array(MYSQLI_ASSOC);
            $data['userId'] = $row['userId'];
            $data['patient'] = [
              "id" => $row['patientId'],
              "firstName" => $row['firstName']
            ];
            $status = "200";
            $header="Content-Type: application/json";
            $result->close();
        } else {
            throw new Exception(mysqli_error($dbConn),"400");
        }
    } else {
        throw new Exception($url_pieces[1] . " not implemented","404");
    }
    
} else {
    throw new Exception("Method Not Supported: $verb", 405);
}
// Send the response

$dbConn->close();

header($header,null,$status);
echo json_encode($data);
