<?php

    ini_set("display_errors","1");
    ERROR_REPORTING(E_ALL);
    
    // Set up database configuration, exception handler, request variables
    require_once('apiHeader.php');
    require_once('../php/MySQLDAOFactory.php');
    require_once('../php/StaffsDTO.php');

    function getData($inData) {
        $p = new StaffsDTO();

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
        
        return $p;
    }

    $myDAOFactory = DAOFactory::getDAOFactory(DB_TYPE);
    $staffsDAO = $myDAOFactory->getStaffsDAO();
    
    switch($verb) {

        case 'POST': 
            $staff = getData($params);
            try {
                $staffsDAO->create($staff);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }

            $url="api/staffs.php/{$staff->getId()}";
            header("Location: $url",null,"201");
            header("Content-Type: application/json");
            $data['id']=$staff->getId();
            break;
        case 'GET':
            if (!isset($url_pieces[1])) {
                // GET all
                try {
                    $data = $staffsDAO->findAll();
                    if ($data === null) {
                        throw new Exception("Staffs Not Found",404);
                    } else {
                        header("Content-Type: application/json",null,"200");
                    }
                } catch (Exception $e) {
                    // TODO: differentiate between 404 (not found) and 500 (system error)
                    throw new Exception($e->getMessage(),500);
                }
            } else if($url_pieces[1] == "doctors") {
                try {
                    $data = $staffsDAO->findAllDoctors();
                    if ($data === null) {
                        throw new Exception("Doctors Not Found",404);
                    } else {
                        header("Content-Type: application/json",null,"200");
                    }
                } catch (Exception $e) {
                    // TODO: differentiate between 404 (not found) and 500 (system error)
                    throw new Exception($e->getMessage(),500);
                }

            } else {
                $staffId = $url_pieces[1];
                try {
                    $data = $staffsDAO->findById($staffId);
                    if ($data === null) {
                        throw new Exception("Staffs Not Found",404);
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
        default:
            throw new Exception("$verb not implemented","405");
            break;
    }
    // send the response
    
    if (isset($data)) {
        echo json_encode($data,JSON_PRETTY_PRINT);
    }
  