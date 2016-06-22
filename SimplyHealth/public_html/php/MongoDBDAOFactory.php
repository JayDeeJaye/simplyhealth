<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
include_once('DAOFactory.php');
include_once('MongoDBPatientDAO.php');
include_once('MongoDBUsersDAO.php');
include_once('MongoDBStaffsDAO.php');
include_once('MongoDBRolesDAO.php');
include_once('../vendor/autoload.php');
/**
 * Description of MongoDBDAOFactory
 *
 * @author julie
 */
class MongoDBDAOFactory extends  DAOFactory {

    public static function createConnection() {
        $dbConn = new MongoDB\Client();
        return $dbConn;
    }

    public function getPatientDAO() {
        return new MongoDBPatientDAO();
    }    

    public function getUsersDAO() {
        return new MongoDBUsersDAO();
    }    

    public function getRolesDAO() {
        return new MongoDBRolesDAO();
    }    

    public function getStaffsDAO() {
        return new MongoDBStaffsDAO();
    }    
}
