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
class PatientHistoryDTO implements JsonSerializable, MongoDB\BSON\Persistable {
    
    private $patientId;      
    private $eczemaSelfInd;  
    private $highCholSelfInd;
    private $highBpSelfInd;  
    private $mentalSelfInd;  
    private $obesitySelfInd; 

    public function getPatientId() {
        return $this->patientId;
    }

    public function getEczemaSelfInd() {
        return $this->eczemaSelfInd;
    }

    public function getHighCholSelfInd() {
        return $this->highCholSelfInd;
    }

    public function getHighBpSelfInd() {
        return $this->highBpSelfInd;
    }

    public function getMentalSelfInd() {
        return $this->mentalSelfInd;
    }

    public function getObesitySelfInd() {
        return $this->obesitySelfInd;
    }

    public function setPatientId($patientId) {
        $this->patientId = $patientId;
    }

    public function setEczemaSelfInd($eczemaSelfInd) {
        $this->eczemaSelfInd = $eczemaSelfInd;
    }

    public function setHighCholSelfInd($highCholSelfInd) {
        $this->highCholSelfInd = $highCholSelfInd;
    }

    public function setHighBpSelfInd($highBpSelfInd) {
        $this->highBpSelfInd = $highBpSelfInd;
    }

    public function setMentalSelfInd($mentalSelfInd) {
        $this->mentalSelfInd = $mentalSelfInd;
    }

    public function setObesitySelfInd($obesitySelfInd) {
        $this->obesitySelfInd = $obesitySelfInd;
    }

    public function jsonSerialize() {
        $result = get_object_vars($this);
        return (object) $result;
    }

    function bsonSerialize()
    {
        foreach (get_object_vars($this) as $key => $value) {
            if ($key === "patientId") {
                if ($value !== null) {
                    $result['patientId'] = new MongoDB\BSON\ObjectId($value);
                }
            } else {
                $result[$key] = $value;
            }
        }
        return (object) $result;
    }

    function bsonUnserialize(array $data)
    {
        $this->patientId        = (string) $data['patientId'];
        $this->eczemaSelfInd    = $data['eczemaSelfInd'];  
        $this->highCholSelfInd  = $data['highCholSelfInd'];
        $this->highBpSelfInd    = $data['highBpSelfInd'];  
        $this->mentalSelfInd    = $data['mentalSelfInd'];  
        $this->obesitySelfInd   = $data['obesitySelfInd']; 
    }
    
}
