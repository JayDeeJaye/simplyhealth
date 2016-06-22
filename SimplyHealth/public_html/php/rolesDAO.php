<?php

interface rolesDAO {

    public function findById($roleId);

    public function findByRoleName($roleName);
}

