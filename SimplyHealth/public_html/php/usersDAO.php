<?php

interface usersDAO {

    public function create(UsersDTO $user);
        
    public function findByUserName($userName);

    public function findById($userId);
    
}
