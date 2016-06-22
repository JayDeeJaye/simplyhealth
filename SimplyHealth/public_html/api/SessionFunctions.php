<?php
class sessionClass
{
    // Hold an instance of the class
    private static $instance;
 
    // The singleton method
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            self::$instance = new sessionClass();
        }
        return self::$instance;
    }
    
    public function __construct(){
        // Set handler to overide SESSION
        session_set_save_handler(
            array($this, "_open"),
            array($this, "_close"),
            array($this, "_read"),
            array($this, "_write"),
            array($this, "_destroy"),
            array($this, "_clean")
        );
        
        // Start the session
        session_start();
    }
    
    /**
     * Open
     */
    public function _open(){
        global $_sess_db;

        if ($_sess_db = @mysql_connect('127.0.0.1', 'root', '')) {
            return @mysql_select_db('simplyhealth', $_sess_db);
        }

        return FALSE;
    }
    
    public function _close()
    {
        global $_sess_db;

        return mysql_close($_sess_db);
    }
    
    public function _read($id)
    {
        global $_sess_db;

        $id = mysql_real_escape_string($id);

        $sql = "SELECT data
                FROM   sessions
                WHERE  id = '$id'";

        if ($result = mysql_query($sql, $_sess_db)) {
            if (mysql_num_rows($result)) {
                $record = mysql_fetch_assoc($result);

                return $record['data'];
            }
        }
        return '';
    }

    public function _write($id, $data)
    {
        global $_sess_db;

        $access = time();

        $id = mysql_real_escape_string($id);
        $access = mysql_real_escape_string($access);
        $data = mysql_real_escape_string($data);

        $sql = "REPLACE
                INTO    sessions
                VALUES  ('$id', '$access', '$data')";

        return mysql_query($sql, $_sess_db);
    }
 
    public function _destroy($id)
    {
        global $_sess_db;

        $id = mysql_real_escape_string($id);

        $sql = "DELETE
                FROM   sessions
                WHERE  id = '$id'";

        return mysql_query($sql, $_sess_db);
    }

    public function _clean($max)
    {
        global $_sess_db;

        $old = time() - $max;
        $old = mysql_real_escape_string($old);

        $sql = "DELETE
                FROM   sessions
                WHERE  access < '$old'";

        return mysql_query($sql, $_sess_db);
    }

    public function isUserLoggedIn($username){
        if(isset($_SESSION['username']) && isset($_SESSION['loggedIn'])) {
            if ($_SESSION['username'] === $username && $_SESSION['loggedIn']===true) {
                return true;
            }
        }
        return false;
    }

    public function isAnyUserLoggedIn(){
        if(isset($_SESSION['username']) && isset($_SESSION['loggedIn'])) {
            return true;
        }
        return false;
    }

    public function getUserLoggedIn() {
        if(isset($_SESSION['username']) && isset($_SESSION['loggedIn'])) {
            return $_SESSION['username'];
        }
        return "";        
    }
    
    public function userLogin($username){
        $_SESSION['username'] = $username;
        $_SESSION['loggedIn'] = true;

            return true;
    }

    public function userLoginFail($uid, $username){
        // Note, in case of a failed login, $uid, $username or both
        // might not be set (might be NULL).
        return false;
    }

    public function userLogout(){
        unset($_SESSION['username']);
        $_SESSION['loggedIn'] = false;
    }

}

?>