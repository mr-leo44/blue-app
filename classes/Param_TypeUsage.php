<?php

class Param_TypeUsage
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
  private $table_name = 't_param_type_usage';
  private $connection;



  function Create()
  {
    $query = "INSERT INTO " . $this->table_name . "  SET code=:code,libelle=:libelle,n_user_create=:n_user_create,datesys=:datesys";
    $stmt = $this->connection->prepare($query);
    $this->code = strip_tags($this->code);
    $this->libelle = strip_tags($this->libelle);
    $this->n_user_create = strip_tags($this->n_user_create);
    $this->datesys = date("Y-m-d H:i:s");

    $stmt->bindParam(":code", $this->code);
    $stmt->bindParam(":libelle", $this->libelle);
    $stmt->bindParam(":n_user_create", $this->n_user_create);
    $stmt->bindParam(":datesys", $this->datesys);
    if ($stmt->execute()) {
      $result["error"] = 0;
      $result["message"] = "Création effectuée avec succès";
    } else {
      $result["error"] = 1;
      $result["message"] = "L'opératon de la création a échoué.";
    }
    return $result;
  }



  function Modifier()
  {
    $query = "Update " . $this->table_name . "  SET libelle=:libelle,n_user_update=:n_user_update,date_update=:date_update where code=:code";
    $stmt = $this->connection->prepare($query);
    $this->code = strip_tags($this->code);
    $this->libelle = strip_tags($this->libelle);
    $this->n_user_create = strip_tags($this->n_user_create);
    $this->datesys = date("Y-m-d H:i:s");

    $stmt->bindParam(":code", $this->code);
    $stmt->bindParam(":libelle", $this->libelle);
    $stmt->bindParam(":n_user_update", $this->n_user_create);
    $stmt->bindParam(":date_update", $this->datesys);
    if ($stmt->execute()) {
      $result["error"] = 0;
      $result["message"] = "Modification effectuée avec succès";
    } else {
      $result["error"] = 1;
      $result["message"] = "L'opératon de la modification a échoué.";
    }
    return $result;
  }



  function Supprimer()
  {
    $query = "Delete From " . $this->table_name . " where code=:code";
    $stmt = $this->connection->prepare($query);
    $this->code = strip_tags($this->code);
    $this->n_user_create = strip_tags($this->n_user_create);
    $this->datesys = date("Y-m-d H:i:s");
    $stmt->bindParam(":code", $this->code);
    if ($stmt->execute()) {
      $result["error"] = 0;
      $result["message"] = "Suppression effectuée avec succès";
    } else {
      $result["error"] = 1;
      $result["message"] = "L'opératon de la suppression a échoué.";
    }
    return $result;
  }



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
