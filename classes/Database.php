<?php
try {
    $files = glob('../env*');
    foreach ($files as $file) {
        $envVariables = parse_ini_file($file);
        if ($envVariables !== false) {
            foreach ($envVariables as $key => $value) {
                $_ENV[$key] = $value;
            }
        }
    }
} catch (Exception $e) {
}
class Database
{

    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "blue_app";
    private $username = "root";
    private $password = "";
    private $port = 3306;
    public $conn;
    // get the database connection

    public function __construct()
    {
        if (isset($_ENV['DATABASE_HOST']) and $_ENV['DATABASE_HOST'] != "") {
            $this->host = $_ENV['DATABASE_HOST'];
        }

        if (isset($_ENV['DATABASE_PORT']) and $_ENV['DATABASE_PORT'] != "") {
            $this->port = $_ENV['DATABASE_PORT'];
        }

        if (isset($_ENV['DATABASE_PASSWORD']) and $_ENV['DATABASE_PASSWORD'] != "") {
            $this->password = $_ENV['DATABASE_PASSWORD'];
        }

        if (isset($_ENV['DATABASE_USERNAME']) and $_ENV['DATABASE_USERNAME'] != "") {
            $this->username = $_ENV['DATABASE_USERNAME'];
        }

        if (isset($_ENV['DATABASE_NAME']) and $_ENV['DATABASE_NAME'] != "") {
            $this->db_name = $_ENV['DATABASE_NAME'];
        }
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
