<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('DAOFactory.php');
include_once('DBConfig.php');
include_once('MySQLPatientDAO.php');
/**
 * Description of MySQLDAOFactory
 *
 * @author julie
 */
class MySQLDAOFactory extends  DAOFactory {

    public static function createConnection() {
        $dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
        return $dbConn;
    }

    public function getPatientDAO() {
        return new MySQLPatientDAO();
    }    
}