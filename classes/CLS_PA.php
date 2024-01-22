<?php

class CLS_PA
{

  public function __construct($db)
  {
    $this->connection = $db;
  }
  public $code;
  public $pa_num;
  public $n_user_create;
  public $datesys;
  public $n_user_update;
  public $date_update;
  public $annule;
  public $n_user_annule;
  public $motif_annulation;
  public $date_synchro;
  public $is_sync;
  public $code_province;
  public $id_site;
  public $date_annule;
  public $activated;
  public $adresse;
  public $cvs_id;
  public $statut_accessibility;
  public $ref_last_visit_log_id;
  private $table_name = 't_param_pa';
  private $connection;

  function Create()
  {
    $query = "INSERT INTO " . $this->table_name . "  SET code=:code,pa_num=:pa_num,n_user_create=:n_user_create,code_province=:code_province,id_site=:id_site,adresse=:adresse,cvs_id=:cvs_id,statut_accessibility=:statut_accessibility";
    $stmt = $this->connection->prepare($query);
    $this->code = strip_tags($this->code);
    $this->pa_num = strip_tags($this->pa_num);
    $this->n_user_create = strip_tags($this->n_user_create);
    $this->code_province = strip_tags($this->code_province);
    $this->id_site = strip_tags($this->id_site);
    $this->date_annule = strip_tags($this->date_annule);
    $this->adresse = strip_tags($this->adresse);
    $this->cvs_id = strip_tags($this->cvs_id);
    $this->statut_accessibility = strip_tags($this->statut_accessibility);
    $this->ref_last_visit_log_id = strip_tags($this->ref_last_visit_log_id);
    $this->datesys = date("Y-m-d H:i:s");

    $stmt->bindParam(":code", $this->code);
    $stmt->bindParam(":pa_num", $this->pa_num);
    $stmt->bindParam(":n_user_create", $this->n_user_create);
    $stmt->bindParam(":code_province", $this->code_province);
    $stmt->bindParam(":id_site", $this->id_site);
    // $stmt->bindParam(":date_annule", $this->date_annule);
    $stmt->bindParam(":adresse", $this->adresse);
    $stmt->bindParam(":cvs_id", $this->cvs_id);
    $stmt->bindValue(":statut_accessibility", '0'); //$this->statut_accessibility);
    //  $stmt->bindParam(":ref_last_visit_log_id", $this->ref_last_visit_log_id);
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
    $query = "UPDATE " . $this->table_name . "  SET pa_num=:pa_num,n_user_update=:n_user_update,date_update=:date_update,id_site=:id_site,cvs_id=:cvs_id,statut_accessibility=:statut_accessibility,adresse=:adresse WHERE code=:code";
    $stmt = $this->connection->prepare($query);
    $this->code = strip_tags($this->code);
    $this->pa_num = strip_tags($this->pa_num);
    $this->n_user_update = strip_tags($this->n_user_update);
    $this->date_update = strip_tags($this->date_update);
    //$this->code_province=strip_tags($this->code_province);
    $this->id_site = strip_tags($this->id_site);
    $this->date_annule = strip_tags($this->date_annule);
    $this->adresse = strip_tags($this->adresse);
    $this->cvs_id = strip_tags($this->cvs_id);
    $this->statut_accessibility = strip_tags($this->statut_accessibility);
    $this->ref_last_visit_log_id = strip_tags($this->ref_last_visit_log_id);
    $this->date_update = date("Y-m-d H:i:s");

    $stmt->bindParam(":code", $this->code);
    $stmt->bindParam(":pa_num", $this->pa_num);
    $stmt->bindParam(":n_user_update", $this->n_user_update);
    $stmt->bindParam(":date_update", $this->date_update);
    //$stmt->bindParam(":code_province", $this->code_province);
    $stmt->bindParam(":id_site", $this->id_site);
    // $stmt->bindParam(":date_annule", $this->date_annule);
    $stmt->bindParam(":adresse", $this->adresse);
    $stmt->bindParam(":cvs_id", $this->cvs_id);
    $stmt->bindParam(":statut_accessibility", $this->statut_accessibility);
    // $stmt->bindParam(":ref_last_visit_log_id", $this->ref_last_visit_log_id);
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
    $query = "SELECT code,pa_num,annule,n_user_annule,motif_annulation,date_synchro,is_sync,code_province,id_site,date_annule,activated,adresse,cvs_id,statut_accessibility,ref_last_visit_log_id FROM " . $this->table_name . " ORDER BY pa_num";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }
  function readAll($from_record_num, $records_per_page)
  {
    $query = "SELECT code,pa_num,annule,n_user_annule,motif_annulation,date_synchro,is_sync,code_province,id_site,date_annule,activated,adresse,cvs_id,statut_accessibility,ref_last_visit_log_id FROM " . $this->table_name . " ORDER BY pa_num ASC LIMIT {$from_record_num}, {$records_per_page}";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }
  public function search($search_term, $from_record_num, $records_per_page)
  {
    $query = "SELECT code,pa_num,annule,n_user_annule,motif_annulation,date_synchro,is_sync,code_province,id_site,date_annule,activated,adresse,cvs_id,statut_accessibility,ref_last_visit_log_id  FROM " . $this->table_name  . " WHERE pa_num LIKE :search_term  ORDER BY pa_num ASC LIMIT :from, :offset";
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
    $query = "SELECT COUNT(*) as total_rows FROM  " . $this->table_name . " WHERE pa_num LIKE :search_term";
    $stmt = $this->connection->prepare($query);
    $search_term = "%{$search_term}%";
    $stmt->bindParam(":search_term", $search_term);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row["total_rows"];
  }
}
