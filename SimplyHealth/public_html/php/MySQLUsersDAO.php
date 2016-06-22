<?php

include_once('usersDAO.php');
include_once('MySQLDAOFactory.php');
include_once('UsersDTO.php');
include_once('MySQLHelper.php');

class MySQLUsersDAO implements usersDAO {
    
    private $dbConn;
    
    // SQL statements for each operation
    const SQL_INSERT = <<<SQL
        INSERT INTO users
            (
                username,password,roleid
            ) 
        VALUES ('?','?',?)
SQL;

    const SQL_FIND_BY_ID = <<<SQL
        SELECT  id, username, password, roleid 
        FROM users
        WHERE id = ?
SQL;

    const SQL_FIND_BY_USER_NAME = <<<SQL
        SELECT  id, username, password, roleid 
        FROM users
        WHERE username = '?'
SQL;

    public function __destruct() {
        if (isset($this->dbConn)){
            $this->dbConn->close();
            unset($this);
        }
    }
    
    public function create(UsersDTO $user) {
        $values = $this->setValues($user);
        $this->dbConn = MySQLDAOFactory::createConnection();
        $sql = MySQLHelper::prepareSQL(self::SQL_INSERT, $values);
        if ($this->dbConn->query($sql)) {
            $user->setId($this->dbConn->insert_id);
        } else {
            throw new Exception(mysqli_error($this->dbConn));
        }               
    }

    public function findByUserName($userName) {
        $this->dbConn = MySQLDAOFactory::createConnection();
        $sql = MySQLHelper::prepareSQL(self::SQL_FIND_BY_USER_NAME, array($userName));
        if ($result = $this->dbConn->query($sql)) {
            if ($row = $result->fetch_assoc()) {
                $data = $this->mapRsData($row);
                return $data;
            }
        } else {
            throw new Exception(mysqli_error($this->dbConn));
        }               
    }

    public function findById($userId) {
        $this->dbConn = MySQLDAOFactory::createConnection();
        $sql = MySQLHelper::prepareSQL(self::SQL_FIND_BY_ID, array($userId));
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
        $p = new UsersDTO();
        $p->setId($row["id"]);
        $p->setRoleId($row["roleid"]);
        $p->setUserName($row["username"]);
        $p->setPassword($row["password"]);
        
        return $p;
    }
    
    // Transfers data from the DTO into the query parameters array
    // Must match the order of placeholders in the INSERT and UPDATE
    // queries. Extra values are OK.
    // returns @array
    private function setValues (UsersDTO $p) {
        $values = [
          $p->getUserName(),
          $p->getPassword(),
          $p->getRoleId(),
          $p->getId()
        ];
        return $values;
    }
}

