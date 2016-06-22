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
include_once('usersDAO.php');
include_once('MongoDBDAOFactory.php');
include_once('UsersDTO.php');
include_once('../vendor/autoload.php');

class MongoDBUsersDAO implements usersDAO {
    
    private $dbConn;
    private $collection;
    

    public function __construct() {
        $this->dbConn = MongoDBDAOFactory::createConnection();
        $this->collection = $this->dbConn->simplyhealth->users;
    }
    
    public function create(UsersDTO $user) {
        $result = $this->collection->insertOne($user);
        $user->setId((string) $result->getInsertedId());
    }
        
    public function findByUserName($userName) {
        $data = $this->collection->findOne(['userName' => $userName]);
        if ($data === null) {
            throw new Exception("Resource not found",404);
        }
        return $data;
    }

    public function findById($userId) {
        $data = $this->collection->findOne(['_id' => (new MongoDB\BSON\ObjectId($userId))]);
        if ($data === null) {
            throw new Exception("Resource not found",404);
        }
        return $data;
    }
    
}

