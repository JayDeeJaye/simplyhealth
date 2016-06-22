<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MongoDBStaffsDAO
 *
 * @author renu
 */
include_once('staffsDAO.php');
include_once('MongoDBDAOFactory.php');
include_once('StaffsDTO.php');
include_once('../vendor/autoload.php');

class MongoDBStaffsDAO implements staffsDAO {
    
    private $dbConn;
    private $collection;
    

    public function __construct() {
        $this->dbConn = MongoDBDAOFactory::createConnection();
        $this->collection = $this->dbConn->simplyhealth->staffs;
    }
    
    public function create(StaffsDTO $staff) {
        $result = $this->collection->insertOne($staff);
        $staff->setId((string) $result->getInsertedId());
    }
        
    public function findAll() {
        $cursor = $this->collection->find();
        $data = [];
        foreach ($cursor as $staff) {
            array_push($data, $staff);
        }
        return $data;
    }

    public function findByUserId($userId) {
        $data = $this->collection->findOne(['userId' => (new MongoDB\BSON\ObjectId($userId))]);
        if ($data === null) {
            throw new Exception("Resource not found",404);
        }
        return $data;
    }

    public function findById($staffId) {
        $data = $this->collection->findOne(['_id' => (new MongoDB\BSON\ObjectId($staffId))]);
        if ($data === null) {
            throw new Exception("Resource not found",404);
        }
        return $data;
    }
    
    public function findAllDoctors() {
        
        //check with Julie
        $cursor = $this->collection->find();
        $data = [];
        foreach ($cursor as $staff) {
            array_push($data, $staff);
        }
        return $data;
    }
}

