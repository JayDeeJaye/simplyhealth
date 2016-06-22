<?php

class UsersDTO implements JsonSerializable {
    
    private $id;
    private $userName;
    private $password;
    private $roleId;
    private $patient;
    private $staff;

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

    public function getPatient() {
        return $this->patient;
    }

    public function getStaff() {
        return $this->staff;
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

    public function setPatient($patient) {
        $this->patient = $patient;
    }

    public function setStaff($staff) {
        $this->staff = $staff;
    }

    public function jsonSerialize() {
        $result = get_object_vars($this);
        return (object) $result;
    }

}
