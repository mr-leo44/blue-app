<?php
class Monnaie
{

	// database connection and table name
	private $conn;
	private $table_name = "t_monnaie";

	public $id_monnaie;
	public $symbole;
	public $n_user_create;
	public $datesys;
	public $n_user_update;
	public $date_update;
	public $annule;
	public $n_user_annule;
	public $motif_annulation;
	public $date_synchro;
	public $is_sync;
	public $id_user_gouv;

	public function __construct($db)
	{
		$this->conn = $db;
	}

	function read()
	{
		//select all data
		$query = "SELECT id_monnaie,symbole FROM
                    " . $this->table_name . " ORDER BY symbole";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		return $stmt;
	}
	function GetDetail()
	{
		$query = "SELECT id_monnaie,symbole  
			FROM " . $this->table_name . "
			WHERE id_monnaie = ?
			LIMIT 0,1";

		$stmt = $this->conn->prepare($query);
		$this->id_monnaie = htmlspecialchars(strip_tags($this->id_monnaie));
		$stmt->bindParam(1, $this->id_monnaie);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->id_monnaie = $row['id_monnaie'];
		$this->symbole = $row['symbole'];
	}
}
