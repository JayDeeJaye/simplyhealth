<?php

    ini_set("display_errors","1");
    ERROR_REPORTING(E_ALL);

    require_once('apiHeader.php');
    require_once('SessionFunctions.php');
    require_once('../php/MySQLDAOFactory.php');
    require_once('../php/UsersDTO.php');
    require_once('../php/RolesDTO.php');

// Open a connection to the database
$dbConn= new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

    function getData($inData) {
        $p = new UsersDTO();

        $p->setUserName($inData['userName']);
        $p->setPassword($inData['password']);
        
        return $p;
    }

    $myDAOFactory = DAOFactory::getDAOFactory(DAOFactory::DB_MYSQL);
    $usersDAO = $myDAOFactory->getUsersDAO();
    $rolesDAO = $myDAOFactory->getRolesDAO();
    
    switch($verb) {
        case 'POST':
            if (isset($params)) {
                $user = getData($params);
                try {
                    $data = $usersDAO->findByUserName($user->getUserName());
                    if ($data === null) {
                        throw new Exception("User Not Found!", 404);
                    } else {
                        if(($user->getUserName() == $data->getUserName()) && 
                                ($user->getPassword() == $data->getPassword()))
                        {
                            $username = $user->getUserName();
                            if (sessionClass::singleton()->isUserLoggedIn($username)){
                                sessionClass::singleton()->userLogout();
                            }
                            sessionClass::singleton()->userLogin($username);
                            $roleId = $data->getRoleId();
                            try {
                                $data = $rolesDAO->findById($roleId);
                                if ($data === null) {
                                    throw new Exception("Roles Not Found!",404);
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
                            throw new Exception("Username or Password does not match!","404");
                        }
                    }
                } catch (Exception $e) {
                    if ($e->getCode() == 404) {
                        throw $e;
                    } else {
                        throw new Exception($e->getMessage(),500);
                    }
                }
            } else {
                throw new Exception("Missing data");                    
            }
            break;
        case 'GET':
            if ($url_pieces[1] == "whoami") {
                $user = sessionClass::singleton()->getUserLoggedIn();
                if($user != "") {
                    $sql = "SELECT roles.rolename FROM roles INNER JOIN users "
                            . "ON roles.id = users.roleid WHERE users.username='$user'";
                    if ($result = $dbConn->query($sql)) {
                        $row = $result->fetch_array(MYSQLI_ASSOC);
                        $rolename = $row['rolename'];
                        try {
                            if($rolename == "Patient") {
                                $data = $usersDAO->findPatientByUserName($user);
                            } else {
                                $data = $usersDAO->findStaffByUserName($user);                                
                            }
                        } catch (Exception $e) {
                            if ($e->getCode() == 404) {
                                throw $e;
                            } else {
                                throw new Exception($e->getMessage(),500);
                            }
                        }
                    } else {
                        throw new Exception("No roles found in database.","400");            
                    }
                } else {
                    throw new Exception("No user logged in.","400");            
                }
            } else {
                throw new Exception("No Method Implemented.","404");            
            }
            break;
        default:
            throw new Exception("$verb not implemented","405");
            break;
            
    }

    if (isset($data)) {
        echo json_encode($data,JSON_PRETTY_PRINT);
    }
