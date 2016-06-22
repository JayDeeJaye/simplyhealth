<?php

include_once('rolesDAO.php');
include_once('MySQLDAOFactory.php');
include_once('RolesDTO.php');
include_once('MySQLHelper.php');

class MySQLRolesDAO implements rolesDAO {
    
    private $dbConn;
    
    const SQL_FIND_BY_ID = <<<SQL
        SELECT  id, rolename
        FROM roles
        WHERE id = ?
SQL;

    const SQL_FIND_BY_ROLE_NAME = <<<SQL
        SELECT  id, rolename
        FROM roles
        WHERE rolename = '?'
SQL;

    public function __destruct() {
        if (isset($this->dbConn)){
            $this->dbConn->close();
            unset($this);
        }
    }
    
    public function findById($roleId) {
        $this->dbConn = MySQLDAOFactory::createConnection();
        $sql = MySQLHelper::prepareSQL(self::SQL_FIND_BY_ID, array($roleId));
        if ($result = $this->dbConn->query($sql)) {
            if ($row = $result->fetch_assoc()) {
                $data = $this->mapRsData($row);
                return $data;
            }
        } else {
            throw new Exception(mysqli_error($this->dbConn));
        }               
    }

    public function findByRoleName($roleName) {
        $this->dbConn = MySQLDAOFactory::createConnection();
        $sql = MySQLHelper::prepareSQL(self::SQL_FIND_BY_ROLE_NAME, array($roleName));
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
        $p = new RolesDTO();
        $p->setId($row["id"]);
        $p->setRoleName($row["rolename"]);
        
        return $p;
    }

    // Transfers data from the DTO into the query parameters array
    // Must match the order of placeholders in the INSERT and UPDATE
    // queries. Extra values are OK.
    // returns @array
    private function setValues (UsersDTO $p) {
        $values = [
          $p->getRoleName(),
          $p->getId()
        ];
        return $values;
    }
}


