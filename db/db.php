<?php 
require_once dirname(dirname(__FILE__)).'/config.php';
class Database {
    private $_db;

    static $_instance;
    static $_backendInstance;

    private function __construct($host, $db, $user, $pass) {
        $this->_db = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function __clone(){}

    /**
     * @return PDO
     */
    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new Database(DB_FRONTEND_HOST, DB_FRONTEND_DATABASE, DB_FRONTEND_USER, DB_FRONTEND_PASSWORD);
        }
        return self::$_instance;
    }

    public static function getBackendInstance() {
        if (!(self::$_backendInstance instanceof self)) {
            self::$_backendInstance = new Database(DB_MEMBER_HOST, DB_MEMBER_DATABASE, DB_MEMBER_USER, DB_MEMBER_PASSWORD);
        }
        return self::$_backendInstance;
    }

    public function __call ( $method, $args ) {
        if ( is_callable(array($this->_db, $method)) ) {
            return call_user_func_array(array($this->_db, $method), $args);
        }
        else {
            throw new BadMethodCallException('Undefined method Database::' . $method);
        }
    }
}

function getrealip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
        $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

?>
