<?php

    function setPatientHistory($values) {
        return [
            "patientId"       => $values["patient_id"],
            "eczemaSelfInd"   => $values["eczema_self_ind"],
            "highCholSelfInd" => $values["highchol_self_ind"],
            "highBpSelfInd"   => $values["highbp_self_ind"],
            "mentalSelfInd"   => $values["mental_self_ind"],
            "obesitySelfInd"  => $values["obesity_self_ind"]
        ];
        
    }
    // Set up database configuration, exception handler, request variables
    require_once('apiHeader.php');

    $dbConn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

    switch($verb) {

        case 'POST': 
            $patientId          = $params['patientId'];
            $eczemaSelfInd      = array_key_exists('eczemaSelfInd', $params) ? $dbConn->real_escape_string($params['eczemaSelfInd']) : null;
            $highCholSelfInd    = array_key_exists('highCholSelfInd', $params) ? $dbConn->real_escape_string($params['highCholSelfInd']) : null;
            $highBpSelfInd      = array_key_exists('highBpSelfInd', $params) ? $dbConn->real_escape_string($params['highBpSelfInd']) : null;
            $mentalSelfInd      = array_key_exists('mentalSelfInd', $params) ? $dbConn->real_escape_string($params['mentalSelfInd']) : null;
            $obesitySelfInd     = array_key_exists('obesitySelfInd', $params) ? $dbConn->real_escape_string($params['obesitySelfInd']) : null;
            $sql = "INSERT INTO patient_history ("
                            . "patient_id,"
                            . "eczema_self_ind,"
                            . "highchol_self_ind,"
                            . "highbp_self_ind,"
                            . "mental_self_ind,"
                            . "obesity_self_ind) values ("
                            .    $patientId
                            . ",'$eczemaSelfInd'"
                            . ",'$highCholSelfInd'"
                            . ",'$highBpSelfInd'"
                            . ",'$mentalSelfInd'"
                            . ",'$obesitySelfInd')";
            if ($dbConn->query($sql)) {
                // success
                $patientId = $dbConn->insert_id;
                $status = "201";
                $url="api/patient_history.php/$patientId";
                $header="Location: $url";
            } else {
                throw new Exception(mysqli_error($dbConn),"500");
            }
            break;
        case 'GET':
            if (!isset($url_pieces[1])) {
                // GET all
                $sql = "SELECT patient_id, eczema_self_ind, highchol_self_ind, highbp_self_ind, mental_self_ind, obesity_self_ind FROM patient_history";
                if ($result = $dbConn->query($sql)) {
                    if ($result->num_rows > 0) {
                        $i = 0;
                        while ($row = $result->fetch_assoc()) {
                            $data[$i++] = setPatientHistory($row);
                        }
                    }
                } else {
                    throw new Exception(mysqli_error($dbConn),"500");
                }
            } else {
                // GET one by id
                $patientId = $url_pieces[1];

                $sql = "SELECT patient_id, eczema_self_ind, highchol_self_ind, highbp_self_ind, mental_self_ind, obesity_self_ind "
                    . "FROM patient_history WHERE patient_id = $patientId";

                if ($result = $dbConn->query($sql)) {
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $data = setPatientHistory($row);
                    } else {
                        // No such record in the database
                        throw new Exception("Patient not found","404");
                    } // fetch patient
                    $result->close();
                } else {
                    throw new Exception(mysqli_error($dbConn),"500");
                } // execute query
            } // GET route
            $header="Content-Type: application/json";
            $status="200";

            break;
        case 'PUT':
            // update the indicated id. This is the simplest update, requiring
            // all data. TODO: implement PATCH, update a subset of columns
            if (isset($url_pieces[1])) {
                $patientId = $url_pieces[1];
                if (isset($params)) {
                    $eczemaSelfInd      = array_key_exists('eczemaSelfInd', $params) ? $dbConn->real_escape_string($params['eczemaSelfInd']) : null;
                    $highCholSelfInd    = array_key_exists('highCholSelfInd', $params) ? $dbConn->real_escape_string($params['highCholSelfInd']) : null;
                    $highBpSelfInd      = array_key_exists('highBpSelfInd', $params) ? $dbConn->real_escape_string($params['highBpSelfInd']) : null;
                    $mentalSelfInd      = array_key_exists('mentalSelfInd', $params) ? $dbConn->real_escape_string($params['mentalSelfInd']) : null;
                    $obesitySelfInd     = array_key_exists('obesitySelfInd', $params) ? $dbConn->real_escape_string($params['obesitySelfInd']) : null;

                    $sql = "UPDATE patient_history "
                        . "SET eczema_self_ind='$eczemaSelfInd', "
                        .     "highchol_self_ind='$highCholSelfInd', "
                        .     "highbp_self_ind='$highBpSelfInd', "
                        .     "mental_self_ind='$mentalSelfInd', "
                        .     "obesity_self_ind='$obesitySelfInd' "
                        . "WHERE patient_id = $patientId";
                    
                    $result = $dbConn->query($sql);
                    if ($result) {
                        $header = "Location: /api/patient_history/$patientId";
                        $status = "204";
                    } else {
                        throw new Exception(mysqli_error($dbConn));
                    } // execute query
                } else {
                    throw new Exception("Missing data");
                }
            } else {
                throw new Exception("Missing target in ".$url_pieces);
            }
            break;
        case 'DELETE':
            // remove the indicated resource. 
            if (isset($url_pieces[1])) {
                $patientId = $url_pieces[1];
                $sql = "DELETE FROM patient_history WHERE id = $patientId";

                if ($result = $dbConn->query($sql)) {
                    $header = "Location: /api/patient_history";
                    $status = "204";
                } else {
                    throw new Exception(mysqli_error($dbConn));
                } // execute query
            } else {
                throw new Exception("Missing target in ".$url_pieces);
            }
            break;
        default:
            throw new Exception("$verb not implemented","405");
    }
    // send the response
    
    $dbConn->close();
    header($header,null,$status);
    if (isset($data)) {
        echo json_encode($data);
    }
  