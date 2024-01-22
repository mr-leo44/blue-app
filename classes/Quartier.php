<?php

class Quartier
{

  public function __construct($db)
  {
    $this->connection = $db;
  }
  public $code;
  public $libelle;
  public $code_province;
  public $id_commune;
  public $n_user_create;
  public $datesys;
  public $n_user_update;
  public $date_update;
  public $annule;
  public $n_user_annule;
  public $motif_annulation;
  public $date_synchro;
  public $is_sync;
  private $table_name = 't_param_quartier';
  private $connection;

  function Create()
  {
    $query = "INSERT INTO " . $this->table_name . "  SET code=:code,libelle=:libelle,code_province=:code_province,id_commune=:id_commune,n_user_create=:n_user_create,annule=:annule,n_user_annule=:n_user_annule,motif_annulation=:motif_annulation,date_synchro=:date_synchro,is_sync=:is_sync";
    $stmt = $this->connection->prepare($query);
    $this->code = strip_tags($this->code);
    $this->libelle = strip_tags($this->libelle);
    $this->code_province = strip_tags($this->code_province);
    $this->id_commune = strip_tags($this->id_commune);
    $this->n_user_create = strip_tags($this->n_user_create);
    $this->annule = strip_tags($this->annule);
    $this->n_user_annule = strip_tags($this->n_user_annule);
    $this->motif_annulation = strip_tags($this->motif_annulation);
    $this->date_synchro = strip_tags($this->date_synchro);
    $this->is_sync = strip_tags($this->is_sync);
    $this->datesys = date("Y-m-d H:i:s");

    $stmt->bindParam(":code", $this->code);
    $stmt->bindParam(":libelle", $this->libelle);
    $stmt->bindParam(":code_province", $this->code_province);
    $stmt->bindParam(":id_commune", $this->id_commune);
    $stmt->bindParam(":n_user_create", $this->n_user_create);
    $stmt->bindParam(":annule", $this->annule);
    $stmt->bindParam(":n_user_annule", $this->n_user_annule);
    $stmt->bindParam(":motif_annulation", $this->motif_annulation);
    $stmt->bindParam(":date_synchro", $this->date_synchro);
    $stmt->bindParam(":is_sync", $this->is_sync);
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
    $query = "UPDATE " . $this->table_name . "  SET libelle=:libelle,code_province=:code_province,id_commune=:id_commune,n_user_update=:n_user_update,date_update=:date_update,annule=:annule,n_user_annule=:n_user_annule,motif_annulation=:motif_annulation,date_synchro=:date_synchro,is_sync=:is_sync WHERE code=:code";
    $stmt = $this->connection->prepare($query);
    $this->code = strip_tags($this->code);
    $this->libelle = strip_tags($this->libelle);
    $this->code_province = strip_tags($this->code_province);
    $this->id_commune = strip_tags($this->id_commune);
    $this->n_user_update = strip_tags($this->n_user_update);
    $this->date_update = strip_tags($this->date_update);
    $this->annule = strip_tags($this->annule);
    $this->n_user_annule = strip_tags($this->n_user_annule);
    $this->motif_annulation = strip_tags($this->motif_annulation);
    $this->date_synchro = strip_tags($this->date_synchro);
    $this->is_sync = strip_tags($this->is_sync);
    $this->date_update = date("Y-m-d H:i:s");

    $stmt->bindParam(":code", $this->code);
    $stmt->bindParam(":libelle", $this->libelle);
    $stmt->bindParam(":code_province", $this->code_province);
    $stmt->bindParam(":id_commune", $this->id_commune);
    $stmt->bindParam(":n_user_update", $this->n_user_update);
    $stmt->bindParam(":date_update", $this->date_update);
    $stmt->bindParam(":annule", $this->annule);
    $stmt->bindParam(":n_user_annule", $this->n_user_annule);
    $stmt->bindParam(":motif_annulation", $this->motif_annulation);
    $stmt->bindParam(":date_synchro", $this->date_synchro);
    $stmt->bindParam(":is_sync", $this->is_sync);
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
    $query = "DELETE FROM " . $this->table_name . " WHERE code=:code";
    $stmt = $this->connection->prepare($query);
    $this->code = strip_tags($this->code);
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
  function read()
  {
    $query = "SELECT code,libelle,code_province,id_commune,annule,n_user_annule,motif_annulation,date_synchro,is_sync FROM " . $this->table_name . " ORDER BY libelle";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }
  function readAll($from_record_num, $records_per_page)
  {
    $query = "SELECT code,libelle,code_province,id_commune,annule,n_user_annule,motif_annulation,date_synchro,is_sync FROM " . $this->table_name . " ORDER BY libelle ASC LIMIT {$from_record_num}, {$records_per_page}";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }
  public function search($search_term, $from_record_num, $records_per_page)
  {
    $query = "SELECT code,libelle,code_province,id_commune,annule,n_user_annule,motif_annulation,date_synchro,is_sync  FROM " . $this->table_name  . " WHERE libelle LIKE :search_term  ORDER BY libelle ASC LIMIT :from, :offset";
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
