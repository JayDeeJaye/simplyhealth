<?php

/**
 * Very quick-and-dirty class to give us a backend for our
 * RESTful service example
 */
class Patient {

    public function create($data) {
        if(isset($data['userid']) && 
            isset($data['firstname']) &&
            isset($data['lastname']) &&
            isset($data['email']) &&
            isset($data['address1']) &&
            isset($data['city']) &&
            isset($data['state']) &&
            isset($data['zip'])) {
            $id = $this->generateID();
            $this->items[$id] = [
                "url" => $this->makeUrlFromIndex($id),
                "name" => $data['name'],
                "link" => $data['link']
                ];
            $item = $this->getOne($id);
            return $item;
        }
        throw new UnexpectedValueException("Could not create item");
    }

    
    
    function 
    
}
