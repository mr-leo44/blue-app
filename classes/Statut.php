<?php
class Statut{
 
    // database connection and table name
    private $conn;
    private $table_name = "t_param_etat";
  
	public $code;
	public $libelle;
    public function __construct($db){
        $this->conn = $db;
    }
	
    function GetDetail(){
		 $query = "SELECT code,libelle FROM " . $this->table_name . "
			WHERE code = ?
			LIMIT 0,1";
	 
		$stmt = $this->conn->prepare( $query );
		 $this->code=htmlspecialchars(strip_tags($this->code));
		$stmt->bindParam(1,$this->code);
		$stmt->execute(); 
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->code= $row['code'];
		$this->libelle= $row['libelle'];
    }
}
?>