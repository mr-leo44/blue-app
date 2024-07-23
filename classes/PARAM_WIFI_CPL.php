<?php

class PARAM_WIFI_CPL
{

  public function __construct($db)
  {
    $this->connection = $db;
  }
  public $code;
  public $libelle;
  public $datesys;
  public $date_update;
  public $is_sync;
  public $annule;
  private $table_name = 't_param_wifi_cpl';
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
    $query = "SELECT code,libelle,is_sync FROM " . $this->table_name . " ORDER BY libelle";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }
  function readAll($from_record_num, $records_per_page)
  {
    $query = "SELECT code,libelle,is_sync,annule FROM " . $this->table_name . " ORDER BY libelle ASC LIMIT {$from_record_num}, {$records_per_page}";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }
  public function search($search_term, $from_record_num, $records_per_page)
  {
    $query = "SELECT code,libelle,is_sync,annule  FROM " . $this->table_name  . " WHERE libelle LIKE :search_term  ORDER BY libelle ASC LIMIT :from, :offset";
    $stmt = $this->connection->prepare($query);
    $search_term = "%{$search_term}%";
    $stmt->bindParam(':search_term', $search_term);
    $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
  }
  public function countAll()
  {
    $query = "SELECT code FROM " . $this->table_name;
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    return $num;
  }
  public function countAll_BySearch($search_term)
  {
    $query = "SELECT COUNT(*) as total_rows FROM  " . $this->table_name . " WHERE libelle LIKE :search_term";
    $stmt = $this->connection->prepare($query);
    $search_term = "%{$search_term}%";
    $stmt->bindParam(":search_term", $search_term);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row["total_rows"];
  }
}
