<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author julie
 */
interface patientDAO {

    public function create(PatientDTO $patient);
    
    public function findAll();

    public function findById($patientId);
    
    public function update(PatientDTO $patient);
    
    public function delete($patientId);
    
    public function findByUserId($userId);
}
