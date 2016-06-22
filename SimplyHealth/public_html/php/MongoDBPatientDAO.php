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
//include_once('MySQLHelper.php');

class MongoDBPatientDAO implements patientDAO {
    
    private $dbConn;
    private $collection;
    

    public function __construct() {
        $this->dbConn = MongoDBDAOFactory::createConnection();
        $this->collection = $this->dbConn->simplyhealth->patients;
    }

    
//    public function __destruct() {
//        if (isset($this->dbConn)){
//            $this->dbConn->close();
//            unset($this);
//        }
//    }
    
    public function create(PatientDTO $patient) {
        $result = $this->collection->insertOne($patient);
        $patient->setId((string) $result->getInsertedId());
    }
        
    public function delete($patientId) {
        $result = $this->collection->deleteOne(['_id' => (new MongoDB\BSON\ObjectId($patientId))]);
        if ($result->getDeletedCount() != 1) {
            throw new Exception("Resource not found",404);
        }
//        $this->dbConn = MySQLDAOFactory::createConnection();
//        $sql = MySQLHelper::prepareSQL(self::SQL_DELETE, array($patientId));
//        if ($this->dbConn->query($sql)) {
//            // success
//        } else {
//            throw new Exception(mysqli_error($this->dbConn));
//        }               
    }

    public function findAll() {
//        $this->dbConn = MySQLDAOFactory::createConnection();
//        $sql = self::SQL_FIND_ALL;
//        $data = [];
//        try {
//            $result = $this->dbConn->query($sql);
//        } catch (Exception $e) {
//            throw new Exception($e->getMessage());
//        }
//        while ($row = $result->fetch_assoc()) {
//            array_push($data, $this->mapRsData($row));
//        }
//        return $data;
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
//        $this->dbConn = MySQLDAOFactory::createConnection();
//        $sql = MySQLHelper::prepareSQL(self::SQL_FIND_BY_ID, array($patientId));
//        if ($result = $this->dbConn->query($sql)) {
//            if ($row = $result->fetch_assoc()) {
//                $data = $this->mapRsData($row);
//                return $data;
//            }
//        } else {
//            throw new Exception(mysqli_error($this->dbConn));
//        }               
    }

    public function update(PatientDTO $patient) {
//        $values = $this->setValues($patient);
//        $this->dbConn = MySQLDAOFactory::createConnection();
//        $sql = MySQLHelper::prepareSQL(self::SQL_UPDATE, $values);
//        if ($this->dbConn->query($sql)) {
//            // success
//        } else {
//            throw new Exception(mysqli_error($this->dbConn));
//        }               
    }

//    private function mapRsData ($row) {
//        $p = new PatientDTO();
//        $p->setId($row["id"]);
//        $p->setUserId($row["userid"]);
//        $p->setFirstName($row["firstname"]);
//        $p->setLastName($row["lastname"]);
//        $p->setEmail($row["email"]);
//        $p->setPhone($row["phone"]);
//        $p->setAddress1($row["address1"]);
//        $p->setAddress2($row["address2"]);
//        $p->setCity($row["city"]);
//        $p->setState($row["state"]);
//        $p->setZipCode($row["zipcode"]);
//        $p->setEmergencyContactName($row["emergency_contact_name"]);
//        $p->setEmergencyContactPhone($row["emergency_contact_phone"]);
//        return $p;
//    }
    
    // Transfers data from the DTO into the query parameters array
    // Must match the order of placeholders in the INSERT and UPDATE
    // queries. Extra values are OK.
    // returns @array
//    private function setValues (PatientDTO $p) {
//        $values = [
//          $p->getUserId(),
//          $p->getFirstName(),
//          $p->getLastName(),
//          $p->getEmail(),
//          $p->getPhone(),
//          $p->getAddress1(),
//          $p->getAddress2(),
//          $p->getCity(),
//          $p->getState(),
//          $p->getZipCode(),
//          $p->getEmergencyContactName(),
//          $p->getEmergencyContactPhone(),
//          $p->getId()
//        ];
//        return $values;
//    }
}
