<?php

require_once('apiHeader.php');
require_once('SessionFunctions.php');

// Open a connection to the database
$dbConn= new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

if ($verb == 'POST') {

    // check the user and paswword in the users table
    $username = mysqli_real_escape_string($dbConn,$params['username']);
    $password = mysqli_real_escape_string($dbConn,$params['password']);
    $sql = "Select username, password, roleid FROM users where username='$username'"; 

    if ($result = $dbConn->query($sql)) {

        $row = $result->fetch_array(MYSQLI_ASSOC);
        if(($username == $row['username']) && ($password == $row['password']))
        {
            
            if (sessionClass::singleton()->isUserLoggedIn($username)){
                sessionClass::singleton()->userLogout();
            }
            sessionClass::singleton()->userLogin($username);
            $rowid = $row['roleid'];
            $sql = "Select rolename FROM roles where id=$rowid"; 
            
            if ($result = $dbConn->query($sql)) {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $rolename = $row['rolename'];
                $status = "201";
                $url="api/login.php/$rolename";
                $header="Location: api/login.php/$username; Content-Type: application/json";
                $data['rolename'] = $rolename;
                $data['username'] = $username;
            } else {
                throw new Exception($sql,"404");
            }
        } else {
            throw new Exception("Username or Password does not match!","404");
        }
    } else {
        throw new Exception(mysqli_error($dbConn),"400");
    }

} else if ($verb == 'GET') {
    if ($url_pieces[1] == "whoami") {
        $user = sessionClass::singleton()->getUserLoggedIn();
        if($user != "") {
            $data['username'] = $user;
            $sql = "SELECT roles.rolename FROM roles INNER JOIN users "
                    . "ON roles.id = users.roleid WHERE users.username='$user'";
            if ($result = $dbConn->query($sql)) {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $rolename = $row['rolename'];
                if($rolename == "Patient") {
                    $sql = "SELECT patient.id, patient.firstname, patient.lastname FROM patient INNER JOIN users "
                            . "ON patient.userid=users.id WHERE users.username='$user'";                    
                } else {
                    $sql = "SELECT staffs.id, staffs.firstname, staffs.lastname FROM staffs INNER JOIN users "
                            . "ON staffs.userid=users.id WHERE users.username='$user'";                    
                }
                if ($result = $dbConn->query($sql)) {
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    if($rolename == "Patient") {
                        $data['patient'] = [
                            "id" => $row['id'],
                            "firstName" => $row['firstname']
                        ];
                    } else {
                        $data['staff'] = [
                            "id" => $row['id'],
                            "firstName" => $row['firstname']
                        ];
                    }
                    $status = "200";
                    $header="Content-Type: application/json";
                    $result->close();
                }
            }
        } else {
            throw new Exception("No user logged in.","400");            
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
