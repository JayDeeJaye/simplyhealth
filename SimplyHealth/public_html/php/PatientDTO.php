<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PatientDTO
 *
 * @author julie
 */
class PatientDTO implements JsonSerializable {
    
    private $id;
    private $userId;
    private $firstName;
    private $lastName;
    private $email;
    private $address1;
    private $address2;
    private $city;
    private $state;
    private $zipCode;
    private $phone;
    private $emergencyContactName;
    private $emergencyContactPhone;

    public function getId() {
        return $this->id;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getLastName() {
        return $this->lastName;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getAddress1() {
        return $this->address1;
    }

    public function getAddress2() {
        return $this->address2;
    }

    public function getCity() {
        return $this->city;
    }

    public function getState() {
        return $this->state;
    }

    public function getZipCode() {
        return $this->zipCode;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function getEmergencyContactName() {
        return $this->emergencyContactName;
    }

    public function getEmergencyContactPhone() {
        return $this->emergencyContactPhone;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setUserId($userId) {
        $this->userId = $userId;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setAddress1($address1) {
        $this->address1 = $address1;
    }

    public function setAddress2($address2) {
        $this->address2 = $address2;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function setZipCode($zipCode) {
        $this->zipCode = $zipCode;
    }

    public function setPhone($phone) {
        $this->phone = $phone;
    }

    public function setEmergencyContactName($emergencyContactName) {
        $this->emergencyContactName = $emergencyContactName;
    }

    public function setEmergencyContactPhone($emergencyContactPhone) {
        $this->emergencyContactPhone = $emergencyContactPhone;
    }

    public function jsonSerialize() {
        $result = get_object_vars($this);
        return (object) $result;
//        $result = [];
//        foreach ($this as $key => $value) {
//            $result[$key]=$value;
//        }
//        return $result;
    }

}
