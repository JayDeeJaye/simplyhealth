<?php
    ini_set("display_errors","1");
    ERROR_REPORTING(E_ALL);

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
                try {
                    $data = $patientDAO->findAll();
                    if ($data === null) {
                        throw new Exception("Not Found",404);
                    } else {
                        $status = "200";
                        $header="Content-Type: application/json";
                    }
                } catch (Exception $e) {
                    // TODO: differentiate between 404 (not found) and 500 (system error)
                    throw new Exception($e->getMessage(),500);
                }
//                $sql = "SELECT id, userid, firstname, lastname, email, phone, address1, address2, city, state, zipcode, "
//                    . "emergency_contact_name, emergency_contact_phone FROM patient";
//                if ($result = $dbConn->query($sql)) {
//                    if ($result->num_rows > 0) {
//                        $i = 0;
//                        while ($row = $result->fetch_assoc()) {
//                            $data[$i++] = [
//                              "id"                      => $row["id"],
//                              "userId"                  => $row["userid"],
//                              "firstName"               => $row["firstname"],
//                              "lastName"                => $row["lastname"],
//                              "email"                   => $row["email"],
//                              "phone"                   => $row["phone"],
//                              "address1"                => $row["address1"],
//                              "address2"                => $row["address2"],
//                              "city"                    => $row["city"],
//                              "state"                   => $row["state"],
//                              "zipCode"                 => $row["zipcode"],
//                              "emergencyContactName"    => $row["zipcode"],
//                              "emergencyContactPhone"   => $row["zipcode"]
//                            ];
//                        }
//                    }
//                } else {
//                    throw new Exception(mysqli_error($dbConn),"500");
//                }
            } else {
                // GET one by id
                $patientId = $url_pieces[1];
                try {
                    $data = $patientDAO->findById($patientId);
                    if ($data === null) {
                        throw new Exception("Not Found",404);
                    } else {
                        $status = "200";
                        $header="Content-Type: application/json";
                    }
                } catch (Exception $e) {
                    // TODO: differentiate between 404 (not found) and 500 (system error)
                    throw new Exception($e->getMessage(),500);
                }
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
                    $url="api/patients.php/{$patient->getId()}";
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

                $header = "Location: api/patients/";
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
  
