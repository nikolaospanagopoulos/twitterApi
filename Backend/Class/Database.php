<?php
include_once '../init.php';


class Database
{


    protected $pdo;
    protected $dsn;
    protected $options;
    protected static $instance;

    protected function __construct()
    {

        try {
            $this->options = [
                \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $this->dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4;port=" . DB_PORT;
            $this->pdo = new PDO($this->dsn, DB_USER, DB_PASS, $this->options);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }

    public static function instance()
    {


        if (self::$instance === null) {

            self::$instance = new self;
        }
        return self::$instance;
    }


    public function __call($method, $arguments)
    {

        return call_user_func_array(array($this->pdo, $method), $arguments);
    }
}
