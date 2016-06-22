<?php

    ini_set("display_errors","1");
    ERROR_REPORTING(E_ALL);

    require_once('apiHeader.php');
    require_once('../php/MySQLDAOFactory.php');
    require_once('../php/RolesDTO.php');

    $myDAOFactory = DAOFactory::getDAOFactory(DB_TYPE);
    $rolesDAO = $myDAOFactory->getRolesDAO();

    switch($verb) {
       case 'GET': 
            if (isset($url_pieces[1])) {
                $roleName = $url_pieces[1];
                try {
                    $data = $rolesDAO->findByRoleName($roleName);
                    if ($data === null) {
                        throw new Exception("Roles Not Found",404);
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
            } else {
                throw new Exception("Method not implemented","405");
            }
            break;
        default:
            throw new Exception("$verb not implemented","405");
            break;
    }

    if (isset($data)) {
        echo json_encode($data,JSON_PRETTY_PRINT);
    }
