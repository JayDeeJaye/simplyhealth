<?php

include_once('staffsDAO.php');
include_once('MySQLDAOFactory.php');
include_once('StaffsDTO.php');
include_once('MySQLHelper.php');

class MySQLStaffsDAO implements staffsDAO {
    
    private $dbConn;
    
    // SQL statements for each operation
    const SQL_INSERT = <<<SQL
        INSERT INTO staffs
            (
                userid,firstname,lastname,email,
                phone,address1,address2,
                city,state,zipcode
             ) 
        VALUES (?,'?','?','?','?','?','?','?','?','?')
SQL;
    
    const SQL_FIND_ALL = <<<SQL
        SELECT id, userid, firstname, lastname, email, address1, address2,
               city, state, zipcode, phone
        FROM staffs
SQL;

    const SQL_FIND_ALL_DOCTORS = <<<SQL
        SELECT staffs.id, userid, firstname, lastname, email, address1, address2,
               city, state, zipcode, phone FROM staffs
        INNER JOIN users on users.id = staffs.userid
        INNER JOIN ROLES on roles.id = users.roleid
        WHERE roles.id = 3
        ORDER BY staffs.firstname
SQL;

    const SQL_FIND_BY_ID = <<<SQL
        SELECT  id,userid,firstname,lastname,email,
                phone,address1,address2,
                city,state,zipcode
        FROM staffs
        WHERE id = ?
SQL;
        
    public function __destruct() {
        if (isset($this->dbConn)){
            $this->dbConn->close();
            unset($this);
        }
    }
    
    public function create(StaffsDTO $staff) {
        $values = $this->setValues($staff);
        $this->dbConn = MySQLDAOFactory::createConnection();
        $sql = MySQLHelper::prepareSQL(self::SQL_INSERT, $values);
        if ($this->dbConn->query($sql)) {
            $staff->setId($this->dbConn->insert_id);
        } else {
            throw new Exception(mysqli_error($this->dbConn));
        }               
    }
        
    public function findAll() {
        $this->dbConn = MySQLDAOFactory::createConnection();
        $sql = self::SQL_FIND_ALL;
        $data = [];
        try {
            $result = $this->dbConn->query($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        while ($row = $result->fetch_assoc()) {
            array_push($data, $this->mapRsData($row));
        }
        return $data;
    }

    public function findAllDoctors() {
        $this->dbConn = MySQLDAOFactory::createConnection();
        $sql = self::SQL_FIND_ALL_DOCTORS;
        $data = [];
        try {
            $result = $this->dbConn->query($sql);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
        while ($row = $result->fetch_assoc()) {
            array_push($data, $this->mapRsData($row));
        }
        return $data;
    }

    public function findById($staffId) {
        $this->dbConn = MySQLDAOFactory::createConnection();
        $sql = MySQLHelper::prepareSQL(self::SQL_FIND_BY_ID, array($staffId));
        if ($result = $this->dbConn->query($sql)) {
            if ($row = $result->fetch_assoc()) {
                $data = $this->mapRsData($row);
                return $data;
            }
        } else {
            throw new Exception(mysqli_error($this->dbConn));
        }               
    }

    private function mapRsData ($row) {
        $p = new StaffsDTO();
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
        return $p;
    }
    
    // Transfers data from the DTO into the query parameters array
    // Must match the order of placeholders in the INSERT and UPDATE
    // queries. Extra values are OK.
    // returns @array
    private function setValues (StaffsDTO $p) {
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
          $p->getId()
        ];
        return $values;
    }
}

