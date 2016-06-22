<?php
    ini_set("display_errors","1");
    ERROR_REPORTING(E_ALL);

    // Set up database configuration, exception handler, request variables
    require_once('apiHeader.php');
    require_once('../php/MySQLDAOFactory.php');
    require_once('../php/PatientDTO.php');
    require_once('../php/DAOFactory.php');

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
        $p->setZipCode($inData['zipCode']);
        $p->setUserId($inData['userId']);
        $p->setEmergencyContactName($inData['emergencyContactName']);
        $p->setEmergencyContactPhone($inData['emergencyContactPhone']);
        
        return $p;
    }

    $myDAOFactory = DAOFactory::getDAOFactory(DB_TYPE);
    $patientDAO = $myDAOFactory->getPatientDAO();
    
    switch($verb) {

        case 'POST': 
            $patient = getData($params);
            
            try {
                $patientDAO->create($patient);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }

            $url="api/patients.php/{$patient->getId()}";
            header("Location: $url",null,"201");
            header("Content-Type: application/json");
            $data['id']=$patient->getId();
            break;
        case 'GET':
            if (!isset($url_pieces[1])) {
                // GET all
                try {
                    $data = $patientDAO->findAll();
                    if ($data === null) {
                        throw new Exception("Not Found",404);
                    } else {
                        header("Content-Type: application/json",null,"200");
                    }
                } catch (Exception $e) {
                    // TODO: differentiate between 404 (not found) and 500 (system error)
                    throw new Exception($e->getMessage(),500);
                }
            } else {
                // GET one by id
                $patientId = $url_pieces[1];
                try {
                    $data = $patientDAO->findById($patientId);
                    if ($data === null) {
                        throw new Exception("Not Found",404);
                    } else {
                        header("Content-Type: application/json",null,"200");
                    }
                } catch (Exception $e) {
                    if ($e->getCode() == 404) {
                        throw $e;
                    } else {
                        throw new Exception($e->getMessage(),500);
                    }
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
                    // Make sure the target exists first
                    try {
                        $data1 = $patientDAO->findById($patient->getId());
                        if ($data1 === null) {
                            throw new Exception("Not Found",404);
                        }
                    } catch (Exception $e) {
                        if ($e->getCode() == 404) {
                            throw $e;
                        } else {
                            throw new Exception($e->getMessage(),500);
                        }
                    }
                    // Cool. Do the update
                    try {
                        $patientDAO->update($patient);
                    } catch (Exception $e) {
                        throw new Exception($e->getMessage());
                    }

                    $url="api/patients.php/{$patient->getId()}";
                    header("Location: $url",null,"204");
                    header("Content-Type: application/json");
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
                } catch (Exception $e) {
                    throw new Exception($e->getMessage());
                }

                header("Location: api/patients.php",null,"204");
            } else {
                throw new Exception("Missing target in ".$url_pieces);
            }
            break;
        default:
            throw new Exception("$verb not implemented","405");
    }
    // send the response
    
    if (isset($data)) {
        echo json_encode($data,JSON_PRETTY_PRINT);
    }
  