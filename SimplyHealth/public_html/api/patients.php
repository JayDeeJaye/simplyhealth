<?php
    // Set up database configuration, exception handler, request variables
    require_once('apiHeader.php');

    $dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

    if ($verb == "POST") {
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
    } // POST (create)
    
    if ($verb == "GET") {
        if (!isset($url_pieces[1])) {
            // GET all
            $sql = "SELECT id, userid, firstname, lastname, email, address1, address2, city, state, zipcode FROM patient";
            if ($result = $dbConn->query($sql)) {
                if ($result->num_rows > 0) {
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        $patients[$i++] = [
                          "id"          => $row["id"],
                          "userId"      => $row["userid"],
                          "firstName"   => $row["firstname"],
                          "lastName"    => $row["lastname"],
                          "email"       => $row["email"],
                          "address1"    => $row["address1"],
                          "address2"    => $row["address2"],
                          "city"        => $row["city"],
                          "state"       => $row["state"],
                          "zipcode"     => $row["zipcode"]
                        ];
                    }
                }
            } else {
                throw new Exception(mysqli_error($dbConn),"400");
            }
        } elseif ($url_pieces[1] == "id") {
            // GET one by id
            $patientId = $url_pieces[2];

            $sql = "SELECT id, userid, firstname, lastname, email, address1, address2, city, state, zipcode FROM patient WHERE id = $patientId";

            if ($result = $dbConn->query($sql)) {
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();

                    $data['patientId'] = $row['id'];
                    $data['userId'] = $row['userid'];
                    $data['firstName'] = $row['firstname'];
                    $data['lastName'] = $row['lastname'];
                    $data['email'] = $row['email'];
                    $data['address1'] = $row['address1'];
                    $data['address2'] = $row['address2'];
                    $data['city'] = $row['city'];
                    $data['state'] = $row['state'];
                    $data['zipCode'] = $row['zipcode'];

                    $status = "200";
                    $header="Content-Type: application/json";
                } else {
                    // No such record in the database
                    throw new Exception("Patient not found","404");
                } // fetch patient
                $result->close();
            } else {
                throw new Exception(mysqli_error($dbConn),"400");
            } // execute query
        } else {
            throw new Exception("Unsupported filter","404");
        } // GET route
    } // GET method
    // send the response
    $dbConn->close();
    header($header,null,$status);
    echo json_encode($data);
  