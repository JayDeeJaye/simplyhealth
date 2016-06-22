<?php

interface staffsDAO {

    public function create(StaffsDTO $staff);
    
    public function findById($staffId);
    
    public function findAll();
    
    public function findAllDoctors();
}

