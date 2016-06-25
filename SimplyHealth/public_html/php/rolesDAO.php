<?php

interface rolesDAO {

    public function create(RolesDTO $roles);
    
    public function findById($roleId);

    public function findByRoleName($roleName);
}

