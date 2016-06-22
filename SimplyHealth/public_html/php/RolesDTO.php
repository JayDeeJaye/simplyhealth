<?php

class RolesDTO implements JsonSerializable {
    
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

}

