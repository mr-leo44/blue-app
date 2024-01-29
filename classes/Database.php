<?php
require_once dirname(__DIR__) .  '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
class Database
{

    // specify your own database credentials
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    public $conn;
    // get the database connection

    public function __construct()
    {
        $this->host = $_ENV['DATABASE_HOST'] ?? "";
        $this->port = $_ENV['DATABASE_PORT'] ?? "";
        $this->password = $_ENV['DATABASE_PASSWORD'] ?? "";
        $this->username = $_ENV['DATABASE_USERNAME'] ?? "";
        $this->db_name = $_ENV['DATABASE_NAME'] ?? "";
    }

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";port=" . $this->port . ';charset=utf8', $this->username, $this->password);

            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
