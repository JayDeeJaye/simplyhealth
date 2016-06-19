<?php
    // Set up database configuration, exception handler, request variables
    require_once('apiHeader.php');
    require_once('SessionFunctions.php');

    $dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

    $sessionObj = new SessionClass();
    
    switch($verb) {

        case 'POST': 

            $firstName  = $dbConn->real_escape_string($params['firstName']);
            $lastName   = $dbConn->real_escape_string($params['lastName']);
            $email      = $dbConn->real_escape_string($params['email']);
            $phone      = $dbConn->real_escape_string($params['phone']);
            $address1   = $dbConn->real_escape_string($params['address1']);
            $address2   = $dbConn->real_escape_string($params['address2']);
            $city       = $dbConn->real_escape_string($params['city']);
            $state      = $dbConn->real_escape_string($params['state']);
            $zip        = $dbConn->real_escape_string($params['zip']);
            $userid     = $dbConn->real_escape_string($params['userId']);
            $sql = "INSERT INTO patient (userid,"
                            . "firstname,"
                            . "lastname,"
                            . "email,"
                            . "phone,"
                            . "address1,"
                            . "address2,"
                            . "city,"
                            . "state,"
                            . "zipcode) values ("
                            . $userid
                            . ",'$firstName'"
                            . ",'$lastName'"
                            . ",'$email'"
                            . ",'$phone'"
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
                throw new Exception(mysqli_error($dbConn));
            }
            break;
        case 'GET':
            if($url_pieces[count($url_pieces)-1] == "patients.php") {
//            if (!isset($url_pieces[1])) {
                // GET all
                $sql = "SELECT id, userid, firstname, lastname, email, phone, address1, address2, city, state, zipcode, "
                    . "emergency_contact_name, emergency_contact_phone FROM patient";
                if ($result = $dbConn->query($sql)) {
                    if ($result->num_rows > 0) {
                        $i = 0;
                        while ($row = $result->fetch_assoc()) {
                            $data[$i++] = [
                              "id"                      => $row["id"],
                              "userId"                  => $row["userid"],
                              "firstName"               => $row["firstname"],
                              "lastName"                => $row["lastname"],
                              "email"                   => $row["email"],
                              "phone"                   => $row["phone"],
                              "address1"                => $row["address1"],
                              "address2"                => $row["address2"],
                              "city"                    => $row["city"],
                              "state"                   => $row["state"],
                              "zipCode"                 => $row["zipcode"],
                              "emergencyContactName"    => $row["zipcode"],
                              "emergencyContactPhone"   => $row["zipcode"]
                            ];
                        }
                    }
                } else {
                    throw new Exception(mysqli_error($dbConn),"500");
                }
            } else {
                // GET one by id
                $patientId = $url_pieces[count($url_pieces)-1];

                $sql = "SELECT id, userid, firstname, lastname, email, phone, address1, address2, city, state, zipcode, "
                    . " emergency_contact_name, emergency_contact_phone FROM patient WHERE id = $patientId";

                if ($result = $dbConn->query($sql)) {
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();

                        $data['id']                     = $row['id'];
                        $data['userId']                 = $row['userid'];
                        $data['firstName']              = $row['firstname'];
                        $data['lastName']               = $row['lastname'];
                        $data['email']                  = $row['email'];
                        $data['phone']                  = $row['phone'];
                        $data['address1']               = $row['address1'];
                        $data['address2']               = $row['address2'];
                        $data['city']                   = $row['city'];
                        $data['state']                  = $row['state'];
                        $data['zipCode']                = $row['zipcode'];
                        $data['emergencyContactName']   = $row['emergency_contact_name'];
                        $data['emergencyContactPhone']  = $row['emergency_contact_phone'];

                        $status = "200";
                        $header="Content-Type: application/json";
                    } else {
                        // No such record in the database
                        throw new Exception("Patient not found","404");
                    } // fetch patient
                    $result->close();
                } else {
                    throw new Exception(mysqli_error($dbConn),"500");
                } // execute query
            } // GET route
            break;
        case 'PUT':
            // update the indicated id. This is the simplest update, requiring
            // all data. TODO: implement PATCH, update a subset of columns
            if($url_pieces[count($url_pieces)-1] != "patients.php") {
//            if (isset($url_pieces[1])) {
                $patientId = $url_pieces[count($url_pieces)-1];
                if (isset($params)) {
                    $sql = "UPDATE patient SET "
                        . "userid="                     . $params['userId'] . ", "
                        . "firstname='"                 . $params['firstName'] . "', "
                        . "lastname='"                  . $params['lastName'] . "', "
                        . "email='"                     . $params['email'] . "', "
                        . "phone='"                     . $params['phone'] . "', "
                        . "address1='"                  . $params['address1'] . "', "
                        . "address2='"                  . $params['address2'] . "', "
                        . "city='"                      . $params['city'] . "', "
                        . "state='"                     . $params['state'] . "', "
                        . "zipcode='"                   . $params['zipCode'] . "', " 
                        . "emergency_contact_name='"    . $params['emergencyContactName'] . "', " 
                        . "emergency_contact_phone='"   . $params['emergencyContactPhone'] 
                        . "' WHERE id = $patientId";

                    $result = $dbConn->query($sql);
                    if ($result) {
                        $header = "Location: api/patients/$patientId";
                        $status = "204";
                    } else {
                        throw new Exception(mysqli_error($dbConn));
                    } // execute query
                } else {
                    throw new Exception("Missing data");
                }
            } else {
                throw new Exception("Missing target in ".$url_pieces);
            }
            break;
        case 'DELETE':
            // remove the indicated resource. 
            if($url_pieces[count($url_pieces)-1] != "patients.php") {
            //if (isset($url_pieces[1])) {
                $patientId = $url_pieces[count($url_pieces)-1];
                $sql = "DELETE FROM patient WHERE id = $patientId";

                if ($result = $dbConn->query($sql)) {
                    $header = "Location: api/patients/";
                    $status = "204";
                } else {
                    throw new Exception(mysqli_error($dbConn));
                } // execute query
            } else {
                throw new Exception("Missing target in ".$url_pieces);
            }
            break;
        default:
            throw new Exception("$verb not implemented","405");
    }
    // send the response
    
    $dbConn->close();
    header($header,null,$status);
    if (isset($data)) {
        echo json_encode($data);
    }
  