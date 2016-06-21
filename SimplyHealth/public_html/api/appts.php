<?php

require_once('apiHeader.php');

// Open a connection to the database
$dbConn= new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);

switch($verb) {
    case 'POST':
        break;
    case 'GET':
        if ($url_pieces[1] == "today") {
            $sql = "SELECT appts.id appt_id, patient.firstname pfname, patient.lastname plname, "
                    . "staffs.firstname sfname, staffs.lastname slname, "
                    . "appts.reason reason, appts.appt_date apptdate, appts.check_in check_in, "
                    . "appts.check_out check_out from appts inner join patient on "
                    . "patient.id = appts.patient_id inner join staffs on "
                    . "staffs.id = appts.doctor_id where status = 'Confirmed' and "
                    . "appt_date >= cast((now()) as date) and appt_date < cast((now() + interval 1 day) as date) "
                    . "ORDER BY appts.appt_date";
            if ($result = $dbConn->query($sql)) {
                if ($result->num_rows > 0) {
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        $data[$i++] = [
                            "appt_id"             => $row["appt_id"],
                            "patient_name"        => $row["pfname"] . " " . $row["plname"],
                            "doctor_name"         => $row["sfname"] . " " . $row["slname"],
                            "reason"              => $row["reason"],
                            "date"                => $row["apptdate"],
                            "check_in"            => $row["check_in"],
                            "check_out"           => $row["check_out"]
                        ];
                    }
                    $status = "200";
                    $header="Content-Type: application/json";
                    $result->close();
                }
            } else {
                throw new Exception(mysqli_error($dbConn),"500");
            }
        } else if($url_pieces[1] == "pending") {
            $sql = "SELECT appts.id appt_id, patient.firstname pfname, patient.lastname plname, "
                    . "staffs.firstname sfname, staffs.lastname slname, appts.reason reason, "
                    . "appts.appt_date apptdate, appts.status status from appts "
                    . "inner join patient on patient.id = appts.patient_id "
                    . "LEFT join staffs on staffs.id = appts.doctor_id "
                    . "where status = 'Pending' ORDER BY appts.appt_date";
            if ($result = $dbConn->query($sql)) {
                if ($result->num_rows > 0) {
                    $i = 0;
                    while ($row = $result->fetch_assoc()) {
                        $data[$i++] = [
                            "appt_id"             => $row["appt_id"],
                            "patient_name"        => $row["pfname"] . " " . $row["plname"],
                            "doctor_name"        => $row["sfname"] . " " . $row["slname"],
                            "reason"              => $row["reason"],
                            "date"                => $row["apptdate"]
                        ];
                    }
                    $status = "200";
                    $header="Content-Type: application/json";
                    $result->close();
                }
            } else {
                throw new Exception(mysqli_error($dbConn),"500");
            }

        } else if($url_pieces[1] == "patient_id") {
            // GET one by id
            if(isset($url_pieces[2])) {
                $patientId = $url_pieces[2];
                $sql = "SELECT appts.id appt_id, staffs.firstname sfname, "
                        . "staffs.lastname slname, appts.reason reason, "
                        . "appts.appt_date apptdate, appts.status status from appts "
                        . "inner join patient on patient.id = appts.patient_id "
                        . "LEFT join staffs on staffs.id = appts.doctor_id "
                        . "where patient.id = $patientId ORDER BY appts.appt_date";
                if ($result = $dbConn->query($sql)) {
                    if ($result->num_rows > 0) {
                        $i = 0;
                        while ($row = $result->fetch_assoc()) {
                            $data[$i++] = [
                                "appt_id"             => $row["appt_id"],
                                "doctor_name"        => $row["sfname"] . " " . $row["slname"],
                                "reason"              => $row["reason"],
                                "date"                => $row["apptdate"],
                                "status"              => $row["status"]
                            ];
                        }
                        $status = "200";
                        $header="Content-Type: application/json";
                        $result->close();
                    }
                } else {
                    throw new Exception(mysqli_error($dbConn),"500");
                }
            } else {
                throw new Exception($url_pieces[1] . " has not been passed","404");
            }
        } else {
            throw new Exception($url_pieces[1] . " not implemented","404");
        }
        break;
    case 'PUT':
        // update the indicated id. This is the simplest update, requiring
        // all data. TODO: implement PATCH, update a subset of columns
        if (isset($url_pieces[1])) {
            $apptId = $url_pieces[1];
            if (isset($params)) {
                $check_in     = array_key_exists('check_in', $params) ? $dbConn->real_escape_string($params['check_in']) : '';
                $check_out    = array_key_exists('check_out', $params) ? $dbConn->real_escape_string($params['check_out']) : '';
                $doctor_id    = array_key_exists('doctor_id', $params) ? $dbConn->real_escape_string($params['doctor_id']) : '';
                $appt_date    = array_key_exists('date', $params) ? $dbConn->real_escape_string($params['date']) : '';
                $reason    = array_key_exists('reason', $params) ? $dbConn->real_escape_string($params['reason']) : '';

                if($check_in != '') {
                    $sql = "UPDATE appts "
                        . "SET check_in='$check_in' "
                        . "WHERE id = $apptId";
                }
                if($check_out != '') {
                    $sql = "UPDATE appts "
                        . "SET check_out='$check_out' "
                        . "WHERE id = $apptId";                    
                }
                if($appt_date != '' || $doctor_id != '' || $reason != '') {
                    $sql = "UPDATE appts "
                        . "SET appt_date='$appt_date', "
                        . "doctor_id=$doctor_id, "
                        . "reason='$reason', "
                        . "status='Confirmed' "
                        . "WHERE id = $apptId";                    
                }

                $result = $dbConn->query($sql);
                if ($result) {
                    $header = "Location: api/appts/$apptId";
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
    default:
        throw new Exception("$verb not implemented","405");
        break;
}
// Send the response

$dbConn->close();

header($header,null,$status);
echo json_encode($data);
