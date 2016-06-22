<?php

class UsersDTO implements JsonSerializable {
    
    private $id;
    private $userName;
    private $password;
    private $roleId;

    public function getId() {
        return $this->id;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRoleId() {
        return $this->roleId;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUserName($userName) {
        $this->userName = $userName;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setRoleId($roleId) {
        $this->roleId = $roleId;
    }

    public function jsonSerialize() {
        $result = get_object_vars($this);
        return (object) $result;
    }

}
