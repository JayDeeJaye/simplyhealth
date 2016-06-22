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
interface patientHistoryDAO {

    public function create(PatientHistoryDTO $patientHistory);

    public function findById($patientId);
    
    public function update(PatientHistoryDTO $patientHistory);
    
    public function delete($patientId);
    
}
