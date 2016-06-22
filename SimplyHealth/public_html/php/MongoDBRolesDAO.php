<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MongoDBUsersDAO
 *
 * @author renu
 */
include_once('rolesDAO.php');
include_once('MongoDBDAOFactory.php');
include_once('RolesDTO.php');
include_once('../vendor/autoload.php');

class MongoDBRolesDAO implements rolesDAO {
    
    private $dbConn;
    private $collection;
    

    public function __construct() {
        $this->dbConn = MongoDBDAOFactory::createConnection();
        $this->collection = $this->dbConn->simplyhealth->personRoles;
    }
    
    public function findByRoleName($roleName) {
        $data = $this->collection->findOne(['roleName' => $roleName]);
        if ($data === null) {
            throw new Exception("Resource not found",404);
        }
        return $data;
    }

    public function findById($roleId) {
        $data = $this->collection->findOne(['_id' => (new MongoDB\BSON\ObjectId($roleId))]);
        if ($data === null) {
            throw new Exception("Resource not found",404);
        }
        return $data;
    }
    
}


