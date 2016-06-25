<?php

    ini_set("display_errors","1");
    ERROR_REPORTING(E_ALL);

    require_once('apiHeader.php');
    require_once('../php/MySQLDAOFactory.php');
    require_once('../php/RolesDTO.php');

    $myDAOFactory = DAOFactory::getDAOFactory(DB_TYPE);
    $rolesDAO = $myDAOFactory->getRolesDAO();

    function getData($inData) {
        $p = new RolesDTO();

        $p->setRoleName($inData['roleName']);

        return $p;
    }

    switch($verb) {
       case 'POST':
            $roles = getData($params);
            
            try {
                $rolesDAO->create($roles);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }

            $url="api/roles.php/{$roles->getId()}";
            header("Location: $url",null,"201");
            header("Content-Type: application/json");
            $data['id']=$roles->getId();
            break;
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
