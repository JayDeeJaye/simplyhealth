<?php

$servername = "localhost";
$dbuser = "root";
$dbpwd = "";
$dbname = "simplyhealth";

/*
 * Following code will create a new product row
 * All product details are read from HTTP Post Request
 */
 // This is the one and only public include file for uLogin.
// Include it once on every authentication and for every protected page.

// array for JSON response
$response = array();

require_once('MySqlFunctions.php');
require_once('SessionFunctions.php');

// check for required fields
if (isset($_GET['action'])) {
    $mysqlObj = new mySQLClass();
    $sessionObj = new SessionClass();
    $action = $_GET['action'];
 
    if($action == 'login') {
        if(isset($_GET['username']) && isset($_GET['password'])) {
            $username = $_GET['username'];
            $password = $_GET['password'];
         
            if ($sessionObj->isUserLoggedIn($username)){
                $sessionObj->userLogout();
            }

            //Select username, pwd from users table
            $sql = "Select username, password FROM users where username='$username'"; 
            $result = $mysqlObj->executeQuery($sql);

            if($result != NULL) {
                if($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    if(($username == $row['username']) && ($password == $row['password']))
                    {
                        $sessionObj->userLogin($username);
                        $response["success"] = 0;
                        $response["message"] = "User successfully loggedin - " . $_SESSION['username'];
                    } else {
                        $response["success"] = -1;
                        $response["message"] = "Authentication Failed, Invalid Username or Password!";
                    }
                }
                else {
                    $response["success"] = -1;
                    $response["message"] = "Authentication Failed, Invalid Username or Password!";
                }
            }
        } 
        echo json_encode($response);
        
    } else if($action == 'create') {
        if (isset($_GET['fname']) && isset($_GET['lname']) && isset($_GET['email']) && isset($_GET['username']) && isset($_GET['password'])
                && isset($_GET['address1']) && isset($_GET['address2']) && isset($_GET['city']) && isset($_GET['state']) 
                && isset($_GET['zipcode']) && isset($_GET['role'])) {
            $fname = $_GET['fname'];
            $lname = $_GET['lname'];
            $email = $_GET['email'];
            $username = $_GET['username'];
            $password = $_GET['password'];
            $address1 = $_GET['address1'];
            $address2 = $_GET['address2'];
            $city = $_GET['city'];
            $state = $_GET['state'];
            $zipcode = $_GET['zipcode'];
            $role = $_GET['role'];

            //Select username, pwd from users table
            $sql = "Select username, password FROM users where username='$username'"; 
            $result = $mysqlObj->executeQuery($sql);

            if($result != NULL) {
                if($result->num_rows > 0) {
                   $response["success"] = -1;
                   $response["message"] = "Unable to create the user, it is already exist.";
                } else {
                    //create the user in users table and persons table
                    $sql = "INSERT INTO users (username, password) VALUES ('$username', $password)";
                    $result = $mysqlObj->executeQuery($sql);
                    //echo $sql;
                    if($result === false) {                         
                        $response["success"] = -1;
                        $response["message"] = "Unable to create the user.";
                    } else {
                        $sql = "Select id FROM users where username='$username'";
                        $userid = "";
                        $roleid = "";
                        $result = $mysqlObj->executeQuery($sql);

                        if($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $userid = $row['id'];
                        }
                        $sql = "Select id FROM roles where rolename='$role'";
                        $result = $mysqlObj->executeQuery($sql);

                        if($result->num_rows > 0) {
                            $row = $result->fetch_assoc();
                            $roleid = $row['id'];
                        }

                        $sql = "INSERT INTO persons (firstname, lastname, email, address1, address2, city, state, zipcode, userid, roleid) "
                            . "VALUES ('$fname','$lname','$email','$address1', '$address2', '$city', '$state', '$zipcode', '$userid', '$roleid')";
                        //echo $sql . "<br>";
                        $result = $mysqlObj->executeQuery($sql);
                        if($result === false) {
                            $response["success"] = -1;
                            $response["message"] = "Unable to create the user on the persons table.";                             
                        } else {
                            $response["success"] = 0;
                            $response["message"] = "Successfully created the role.";                         
                        }                         
                    }
                }
            }

        } else {
            // required field is missing
            $response["success"] = -1;
            $response["message"] = "Required field(s) is missing to create the User.";

        }
        // echoing JSON response
        echo json_encode($response);
    } else if ($action == 'getFullName') {
        if($sessionObj->isAnyUserLoggedIn()) {
            $username = $sessionObj->getUserLoggedIn();
            $sql = "SELECT persons.firstname, persons.lastname FROM persons INNER JOIN users "
                    . "ON persons.userid=users.id WHERE users.username='$username'";
            $result = $mysqlObj->executeQuery($sql);

            $firstname = "";
            $lastname = "";
            if($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $firstname = $row['firstname'];
                $lastname = $row['lastname'];
            }
            $response["success"] = 1;
            $response["message"] = $firstname . " " . $lastname;
        } else {
            $response["success"] = 0;
            $response["message"] = "Session timed out, log in again.";
        }
        echo json_encode($response);
    }         
} else {
    // required field is missing
    $response["success"] = -1;
    $response["message"] = "Required field(s) is missing.";
    echo json_encode($response);
}
?>