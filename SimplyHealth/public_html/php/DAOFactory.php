<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DAOFactory
 *
 * @author julie
 */
abstract class DAOFactory {
    
    // Database options
    
    const DB_MYSQL = 0;
    const DB_MONGODB = 1;
    
    abstract public function getPatientDAO();
    
    public static function getDAOFactory ($database) {
        switch ($database) {
            case self::DB_MYSQL :
                return new MySQLDAOFactory();
            case self::DB_MONGODB :
                return new MongoDBDAOFactory();
        }
    }
}
