<?php

class mySQLClass
{
    public $servername = "localhost";
    public $dbuser = "root";
    public $dbpwd = "";
    public $dbname = "simplyhealth";

    public function sqlConnection() {
        $conn = new mysqli($this->servername, $this->dbuser, $this->dbpwd, $this->dbname);

        if($conn->connect_error) {
            die("Connection Failed: " . $conn->connect_error  . "<br>");
        }
        else {
//            echo "Successfully connected."  . "<br>";
        }
        return $conn;

    }

    public function executeQuery($sql)
    {
        $conn = $this->sqlConnection();
        $result = $conn->query($sql);

        if($result == NULL) {
            $response["success"] = -1;
            $response["message"] = "Result of the query is NULL, check the query!";
            echo json_encode($response);
        }
        
        mysqli_close($conn);
        
        return $result;
    }
    
}

?>