<?php

class UsersDTO implements JsonSerializable, MongoDB\BSON\Persistable  {
    
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

    function bsonSerialize()
    {
        // TODO: add ObjectId conversion for userid to reference users
        // collection
        foreach (get_object_vars($this) as $key => $value) {
            if ($key === "id") {
                if ($value !== null) {
                    $result['_id'] = new MongoDB\BSON\ObjectId($value);
                }
            } else if ($key === "roleId") {
                if ($value !== null) {
                    $result['roleId'] = new MongoDB\BSON\ObjectId($value);
                }
            }else {
                $result[$key] = $value;
            }
        }
        return (object) $result;
    }

    function bsonUnserialize(array $data)
    {
        $this->id = (string) $data['_id'];
        $this->roleId = (string) $data['roleId'];
        $this->userName = $data['userName'];
        $this->password = $data['password'];
    }
}
