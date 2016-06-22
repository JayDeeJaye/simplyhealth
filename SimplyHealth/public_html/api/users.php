<?php
    ini_set("display_errors","1");
    ERROR_REPORTING(E_ALL);

    require_once('apiHeader.php');
    require_once('../php/MySQLDAOFactory.php');
    require_once('../php/UsersDTO.php');

    function getData($inData) {
        $p = new UsersDTO();

        $p->setUserName($inData['userName']);
        $p->setPassword($inData['password']);
        $p->setRoleId($inData['roleId']);

        return $p;
    }

    $myDAOFactory = DAOFactory::getDAOFactory(DAOFactory::DB_MYSQL);
    $usersDAO = $myDAOFactory->getUsersDAO();

    switch($verb) {
        case 'POST':
            $user = getData($params);
            
            try {
                $usersDAO->create($user);
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }

            $url="api/users.php/{$user->getId()}";
            header("Location: $url",null,"201");
            header("Content-Type: application/json");
            $data['id']=$user->getId();
            break;
        default:
            throw new Exception("$verb not implemented","405");
            break;
    }

    if (isset($data)) {
        echo json_encode($data,JSON_PRETTY_PRINT);
    }
