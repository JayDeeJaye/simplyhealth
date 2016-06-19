<?php

class MySQLHelper {

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

    public static function prepareSQL ($sql, $values) {
        foreach ($values as $value) {
            $sql = preg_replace('(\?)',$value, $sql, 1);
        }
        return $sql;
    }

}