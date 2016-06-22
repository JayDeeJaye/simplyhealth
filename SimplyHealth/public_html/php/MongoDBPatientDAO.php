<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MongoDBPatientDAO
 *
 * @author julie
 */
include_once('patientDAO.php');
include_once('MongoDBDAOFactory.php');
include_once('PatientDTO.php');
include_once('../vendor/autoload.php');

class MongoDBPatientDAO implements patientDAO {
    
    private $dbConn;
    private $collection;
    

    public function __construct() {
        $this->dbConn = MongoDBDAOFactory::createConnection();
        $this->collection = $this->dbConn->simplyhealth->patients;
    }
    
    public function create(PatientDTO $patient) {
        $result = $this->collection->insertOne($patient);
        $patient->setId((string) $result->getInsertedId());
    }
        
    public function delete($patientId) {
        $result = $this->collection->deleteOne(['_id' => (new MongoDB\BSON\ObjectId($patientId))]);
        if ($result->getDeletedCount() != 1) {
            throw new Exception("Resource not found",404);
        }
    }

    public function findAll() {
        $cursor = $this->collection->find();
        $data = [];
        foreach ($cursor as $patient) {
            array_push($data, $patient);
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

    public function findById($patientId) {
        $data = $this->collection->findOne(['_id' => (new MongoDB\BSON\ObjectId($patientId))]);
        if ($data === null) {
            throw new Exception("Resource not found",404);
        }
        return $data;
    }

    public function update(PatientDTO $patient) {
        $result = $this->collection->replaceOne(['_id' => (new MongoDB\BSON\ObjectId($patient->getId()))],$patient);
        if ($result->getMatchedCount() == 0) {
            throw new Exception("Resource not found",404);
        }
    }
    
    public function findByUserId($userId) {
        
    }

}
