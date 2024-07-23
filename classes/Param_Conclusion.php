<?php

class Param_Conclusion
{

  public function __construct($db)
  {
    $this->connection = $db;
  }
  public $code;
  public $libelle;
  public $n_user_create;
  public $datesys;
  public $n_user_update;
  public $date_update;
  public $annule;
  public $date_synchro;
  public $is_sync;
  private $table_name = 't_param_conclusion_controle';
  private $connection;




  function GetDetail()
  {
    $query = "SELECT * FROM " . $this->table_name . " WHERE code = ? 	LIMIT 0,1";
    $stmt = $this->connection->prepare($query);
    $this->code = strip_tags($this->code);
    $stmt->bindParam(1, $this->code);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
  }

  function GetDetailIN()
  {
    $query = "SELECT * FROM " . $this->table_name . " WHERE code = ? 	LIMIT 0,1";
    $stmt = $this->connection->prepare($query);
    $this->code = strip_tags($this->code);
    $stmt->bindParam(1, $this->code);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $this->code = $row['code'];
    $this->libelle = $row['libelle'];
  }
  function read()
  {
    $query = "SELECT code,libelle FROM " . $this->table_name . " ORDER BY libelle";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }
}
