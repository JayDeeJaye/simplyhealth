<?php

class RolesDTO implements JsonSerializable, MongoDB\BSON\Persistable {
    
    private $id;
    private $roleName;

    public function getId() {
        return $this->id;
    }

    public function getRoleName() {
        return $this->roleName;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setRoleName($roleName) {
        $this->roleName = $roleName;
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
            } else {
                $result[$key] = $value;
            }
        }
        return (object) $result;
    }

    function bsonUnserialize(array $data)
    {
        $this->id = (string) $data['_id'];
        $this->roleName = $data['roleName'];
    }
}

