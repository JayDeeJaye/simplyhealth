<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MySQLPatientHistoryDAO
 *
 * @author julie
 */
include_once('patientHistoryDAO.php');
include_once('MySQLDAOFactory.php');
include_once('PatientHistoryDTO.php');
include_once('MySQLHelper.php');

class MySQLPatientHistoryDAO implements patientHistoryDAO {
    
    private $dbConn;
    
    // SQL statements for each operation
    const SQL_INSERT = <<<SQL
        INSERT 
        INTO patient_history(
                eczema_self_ind, highchol_self_ind, highbp_self_ind, 
                mental_self_ind, obesity_self_ind, patient_id
             ) VALUES ('?','?','?','?','?',?)
SQL;
    const SQL_FIND_BY_ID = <<<SQL
        SELECT patient_id, eczema_self_ind, highchol_self_ind, highbp_self_ind, 
               mental_self_ind, obesity_self_ind FROM patient_history 
         WHERE patient_id = ?
SQL;
        
    const SQL_UPDATE = <<<SQL
        UPDATE patient_history 
           SET eczema_self_ind='?',
               highchol_self_ind='?',
               highbp_self_ind='?',
               mental_self_ind='?',
               obesity_self_ind='?' 
         WHERE patient_id=?
SQL;
    const SQL_DELETE = "DELETE FROM patient_history WHERE patient_id = ?";

    public function __destruct() {
        if (isset($this->dbConn)){
            $this->dbConn->close();
            unset($this);
        }
    }
    
    public function create(PatientHistoryDTO $p) {
        $values = $this->setValues($p);
        $this->dbConn = MySQLDAOFactory::createConnection();
        $sql = MySQLHelper::prepareSQL(self::SQL_INSERT, $values);
        if ($this->dbConn->query($sql)) {
            //No worries
        } else {
            throw new Exception(mysqli_error($this->dbConn));
        }               
    }
        
    public function delete($patientId) {
        $this->dbConn = MySQLDAOFactory::createConnection();
        $sql = MySQLHelper::prepareSQL(self::SQL_DELETE, array($patientId));
        if ($this->dbConn->query($sql)) {
            // No worries
        } else {
            throw new Exception(mysqli_error($this->dbConn));
        }               
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

    public function update(PatientHistoryDTO $p) {
        $values = $this->setValues($p);
        $this->dbConn = MySQLDAOFactory::createConnection();
        $sql = MySQLHelper::prepareSQL(self::SQL_UPDATE, $values);
        if ($this->dbConn->query($sql)) {
            // success
        } else {
            throw new Exception(mysqli_error($this->dbConn));
        }               
    }

    private function mapRsData ($row) {
        $p = new PatientHistoryDTO();
        $p->setPatientId($row["patient_id"]);
        $p->setEczemaSelfInd($row['eczema_self_ind']);  
        $p->setHighCholSelfInd($row['highchol_self_ind']);
        $p->setHighBpSelfInd($row['highbp_self_ind']);  
        $p->setMentalSelfInd($row['mental_self_ind']);  
        $p->setObesitySelfInd($row['obesity_self_ind']); 
        return $p;
    }
    
    // Transfers data from the DTO into the query parameters array
    // Must match the order of placeholders in the INSERT and UPDATE
    // queries. Extra values are OK.
    // returns @array
    private function setValues (PatientHistoryDTO $p) {
        $values = [
            $p->getEczemaSelfInd(),  
            $p->getHighCholSelfInd(),
            $p->getHighBpSelfInd(),  
            $p->getMentalSelfInd(),  
            $p->getObesitySelfInd(), 
            $p->getPatientId()
        ];
        return $values;
    }
}
