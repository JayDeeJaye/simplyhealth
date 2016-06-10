<?php
    // Set up database configuration, exception handler, request variables
    require_once('apiHeader.php');

    $dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

    $firstName = $dbConn->real_escape_string($params['firstName']);
    $lastName = $dbConn->real_escape_string($params['lastName']);
    $email = $dbConn->real_escape_string($params['email']);
    $address1 = $dbConn->real_escape_string($params['address1']);
    $address2 = $dbConn->real_escape_string($params['address2']);
    $city = $dbConn->real_escape_string($params['city']);
    $state = $dbConn->real_escape_string($params['state']);
    $zip = $dbConn->real_escape_string($params['zip']);
    $userid = $dbConn->real_escape_string($params['userId']);
    $sql = "INSERT INTO patient (userid,"
                    . "firstname,"
                    . "lastname,"
                    . "email,"
                    . "address1,"
                    . "address2,"
                    . "city,"
                    . "state,"
                    . "zipcode) values ("
                    . $userid
                    . ",'$firstName'"
                    . ",'$lastName'"
                    . ",'$email'"
                    . ",'$address1'"
                    . ",'$address2'"
                    . ",'$city'"
                    . ",'$state'"
                    . ",'$zip')";
    if ($dbConn->query($sql)) {
        // success
        $patientId = $dbConn->insert_id;
        $status = "201";
        $url="api/patients.php/$patientId";
        $header="Location: $url; Content-Type: application/json";
        $data['id']=$patientId;
    } else {
        $status="400";
        $data['error']=  $dbConn->error();
        $header="Content-Type: application/json";        
    }

    // send the response
    $dbConn->close();
    header($header,null,$status);
    echo json_encode($data);
    
  