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
class PatientDTO implements JsonSerializable, MongoDB\BSON\Persistable {
    
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
    }

    function bsonSerialize()
    {
        foreach (get_object_vars($this) as $key => $value) {
            if ($key === "id") {
                if ($value !== null) {
                    $result['_id'] = new MongoDB\BSON\ObjectId($value);
                }
            } else {
                $result[$key] = $value;
            }
        }
        return (object) $result;
    }

    function bsonUnserialize(array $data)
    {
        $this->id = (string) $data['_id'];
        $this->userId = (string) $data['userId'];
        $this->firstName = $data['firstName'];
        $this->lastName = $data['lastName'];
        $this->email = $data['email'];
        $this->address1 = $data['address1'];
        $this->address2 = $data['address2'];
        $this->city = $data['city'];
        $this->state = $data['state'];
        $this->zipCode = $data['zipCode'];
        $this->phone = $data['phone'];
        $this->emergencyContactName = $data['emergencyContactName'];
        $this->emergencyContactPhone = $data['emergencyContactPhone'];
    }
    
}
