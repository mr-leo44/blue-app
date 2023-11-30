<?php
class Database{
  
    // specify your own database credentials
    private $host = "localhost";
    private $db_name = "c1639470c_blue_test";
    private $username = "c1639470c_echodata";
    private $password = "!^WAEy,.hPD^";
    private $port = 3306;
    public $conn; 	
    // get the database connection
    public function getConnection(){  
        $this->conn = null;  
        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";port=" . $this->port.';charset=utf8', $this->username, $this->password);
			
			// $this->conn = new PDO("mysql:host=localhost;dbname=blue_app;". "port=3308;charset=utf8","root", "killer");
			// $this->conn = new PDO("mysql:host=localhost;dbname=blue_app_tuning;". "port=3309;charset=utf8","root", "killer");
			// $this->conn = new PDO("mysql:host=localhost;dbname=blue_app;". "port=3309;charset=utf8","root", "killer");
			// $this->conn = new PDO("pgsql:host=localhost;dbname=blue_app;". "port=5432;","postgres", "killer");
			 // $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
			//Online
			 // $this->conn = new PDO("mysql:host=mysql-marjo.alwaysdata.net;dbname=marjo_blue_app;","marjo_inpp", "inpp_56");	 
			//Online Lubumbashi
			 // $this->conn = new PDO("mysql:host=mysql-taxing.alwaysdata.net;dbname=taxing_blue_app;","taxing", "AstraComma");	
			 // $this->conn = new PDO("mysql:host=mysql-taxing.alwaysdata.net;dbname=taxing_db;","taxing", "AstraComma");	
			 $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 
			
			/*
			 $this->dsn='mysql:host=mysql-marjo.alwaysdata.net;dbname='.$dbname.';charset=utf8';
			 $this->cnx =new PDO($this->dsn, $this->user, $this->pass,  array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")) ;
			 $this->cnx->exec("SET CHARACTER SET utf8");*/
 
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }  
        return $this->conn;
    }
}
?>