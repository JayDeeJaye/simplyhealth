<?php
    // Set up database configuration, exception handler, request variables
    require_once('apiHeader.php');
    require_once('../php/MySQLDAOFactory.php');
    require_once('../php/PatientDTO.php');

    function getData($inData) {
        $p = new PatientDTO();

        $p->setFirstName($inData['firstName']);
        $p->setLastName($inData['lastName']);
        $p->setEmail($inData['email']);
        $p->setPhone($inData['phone']);
        $p->setAddress1($inData['address1']);
        $p->setAddress2($inData['address2']);
        $p->setCity($inData['city']);
        $p->setState($inData['state']);
        $p->setZipCode($inData['zip']);
        $p->setUserId($inData['userId']);
        $p->setEmergencyContactName($inData['emergencyContactName']);
        $p->setEmergencyContactPhone($inData['emergencyContactPhone']);
        
        return $p;
    }

    $myDAOFactory = DAOFactory::getDAOFactory(DAOFactory::DB_MYSQL);
    $patientDAO = $myDAOFactory->getPatientDAO();
    
    switch($verb) {

        case 'POST': 
            $patient = getData($params);
            
            try {
                $patientDAO->create($patient);
            } catch (Exception $exc) {
                throw new Exception($exc->getMessage());
            }

            $status = "201";
            $url="api/patients.php/{$patient->getId()}";
            $header="Location: $url; Content-Type: application/json";
            $data['id']=$patient->getId();
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
            if (isset($url_pieces[1])) {
                if (isset($params)) {
                    $patient = getData($params);
                    $patient->setId($url_pieces[1]);
                    try {
                        $patientDAO->update($patient);
                    } catch (Exception $exc) {
                        throw new Exception($exc->getMessage());
                    }

                    $status = "204";
                    $url="/api/patients.php/{$patient->getId()}";
                    $header="Location: $url; Content-Type: application/json";
                } else {
                    throw new Exception("Missing data");
                }
            } else {
                throw new Exception("Missing target in ".$url_pieces);
            }
            break;
        case 'DELETE':
            // remove the indicated resource. 
            if (isset($url_pieces[1])) {
                $patientId = $url_pieces[1];
                
                try {
                    $patientDAO->delete($patientId);
                } catch (Exception $exc) {
                    throw new Exception($exc->getMessage());
                }

                $header = "Location: /api/patients/";
                $status = "204";
            } else {
                throw new Exception("Missing target in ".$url_pieces);
            }
            break;
        default:
            throw new Exception("$verb not implemented","405");
    }
    // send the response
    
    header($header,null,$status);
    if (isset($data)) {
        echo json_encode($data);
    }
  