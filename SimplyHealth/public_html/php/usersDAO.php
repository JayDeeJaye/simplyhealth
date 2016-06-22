<?php

interface usersDAO {

    public function create(UsersDTO $user);
        
    public function findByUserName($userName);

    public function findPatientByUserName($userName);
    
    public function findStaffByUserName($userName);
    
}
