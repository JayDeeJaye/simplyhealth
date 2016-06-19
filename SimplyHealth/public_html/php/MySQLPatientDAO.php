<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MySQLPatientDAO
 *
 * @author julie
 */
include_once('patientDAO.php');
include_once('MySQLDAOFactory.php');
include_once('PatientDTO.php');
include_once('MySQLHelper.php');

class MySQLPatientDAO implements patientDAO {
    
    private $dbConn;
    
    // SQL statements for each operation
    const SQL_INSERT = <<<SQL
        INSERT INTO patient
            (
                userid,firstname,lastname,email,
                phone,address1,address2,
                city,state,zipcode,
                emergency_contact_name,
                emergency_contact_phone
             ) 
        VALUES (?,'?','?','?','?','?','?','?','?','?','?','?')
SQL;
//    const SQL_FIND_ALL = <<<SQL
//        SELECT  id,userid,firstname,lastname,email,
//                phone,address1,address2,
//                city,state,zipcode,
//                emergency_contact_name,
//                emergency_contact_phone
//        FROM patient
//SQL;
    const SQL_FIND_ALL = <<<SQL
        SELECT id, userid, firstname, lastname, email, address1, address2,
               city, state, zipcode, phone, emergency_contact_name,
               emergency_contact_phone
        FROM patient
SQL;
    const SQL_FIND_BY_ID = <<<SQL
        SELECT  id,userid,firstname,lastname,email,
                phone,address1,address2,
                city,state,zipcode,
                emergency_contact_name,
                emergency_contact_phone
        FROM patient
        WHERE id = ?
SQL;
        
    const SQL_UPDATE = <<<SQL
        UPDATE patient
            SET userid                  =  ?,
                firstname               = '?',
                lastname                = '?',
                email                   = '?',
                phone                   = '?',
                address1                = '?',
                address2                = '?',
                city                    = '?',
                state                   = '?',
                zipcode                 = '?',
                emergency_contact_name  = '?',
                emergency_contact_phone = '?'
            WHERE id = ?
SQL;
    const SQL_DELETE = "DELETE FROM patient WHERE id = ?";

    public function __destruct() {
        if (isset($this->dbConn)){
            $this->dbConn->close();
        }
    }
    
    public function create(PatientDTO $patient) {
        $values = $this->setValues($patient);
        $this->dbConn = MySQLDAOFactory::createConnection();
        $sql = MySQLHelper::prepareSQL(self::SQL_INSERT, $values);
        if ($this->dbConn->query($sql)) {
            $patient->setId($this->dbConn->insert_id);
        } else {
            throw new Exception(mysqli_error($this->dbConn));
        }               
    }
        
    public function delete($patientId) {
        $this->dbConn = MySQLDAOFactory::createConnection();
        $sql = MySQLHelper::prepareSQL(self::SQL_DELETE, array($patientId));
        if ($this->dbConn->query($sql)) {
            // success
        } else {
            throw new Exception(mysqli_error($this->dbConn));
        }               
    }

    public function findAll() {
        $this->dbConn = MySQLDAOFactory::createConnection();
        $sql = self::SQL_FIND_ALL;
        $data = [];
        echo $sql;
        
        try {
            $result = $this->dbConn->query($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        //return $result->fetch_all(MYSQLI_ASSOC);
//        if ($result = $this->dbConn->query($sql)) {
//            while ($row = $result->fetch_assoc()) {
//                array_push($data, $this->mapRsData($row));
//            }
//            return $data;
//        } else {
//            throw new Exception(mysqli_error($this->dbConn));
//        }               
    }

    public function findById($patientId) {
        $this->dbConn = MySQLDAOFactory::createConnection();
        $sql = MySQLHelper::prepareSQL(self::SQL_FIND_BY_ID, array($patientId));
        if ($result = $this->dbConn->query($sql)) {
            if ($row = $result->fetch_assoc()) {
                $data = $this->mapRsData($row);
                return $data;
            }
        } else {
            throw new Exception(mysqli_error($this->dbConn));
        }               
    }

    public function update(PatientDTO $patient) {
        $values = $this->setValues($patient);
        $this->dbConn = MySQLDAOFactory::createConnection();
        $sql = MySQLHelper::prepareSQL(self::SQL_UPDATE, $values);
        if ($this->dbConn->query($sql)) {
            // success
        } else {
            throw new Exception(mysqli_error($this->dbConn));
        }               
    }

    private function mapRsData ($row) {
        $p = new PatientDTO();
        $p->setId($row["id"]);
        $p->setUserId($row["userid"]);
        $p->setFirstName($row["firstname"]);
        $p->setLastName($row["lastname"]);
        $p->setEmail($row["email"]);
        $p->setPhone($row["phone"]);
        $p->setAddress1($row["address1"]);
        $p->setAddress2($row["address2"]);
        $p->setCity($row["city"]);
        $p->setState($row["state"]);
        $p->setZipCode($row["zipcode"]);
        $p->setEmergencyContactName($row["emergency_contact_name"]);
        $p->setEmergencyContactPhone($row["emergency_contact_phone"]);
        return $p;
    }
    
    // Transfers data from the DTO into the query parameters array
    // Must match the order of placeholders in the INSERT and UPDATE
    // queries. Extra values are OK.
    // returns @array
    private function setValues (PatientDTO $p) {
        $values = [
          $p->getUserId(),
          $p->getFirstName(),
          $p->getLastName(),
          $p->getEmail(),
          $p->getPhone(),
          $p->getAddress1(),
          $p->getAddress2(),
          $p->getCity(),
          $p->getState(),
          $p->getZipCode(),
          $p->getEmergencyContactName(),
          $p->getEmergencyContactPhone(),
          $p->getId()
        ];
        return $values;
    }
}
