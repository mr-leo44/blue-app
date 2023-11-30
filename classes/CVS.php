<?php

class CVS
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
  public $n_user_annule;
  public $motif_annulation;
  public $date_synchro;
  public $is_sync;
  public $code_province;
  public $id_site;
  public $date_annule;
  public $activated;
  public $id_commune;
  public $id_organisme;
  private $table_name = 't_param_cvs';
  private $connection;

  function Create()
  {


    $this->code = strip_tags($this->code);
    $this->libelle = strip_tags($this->libelle);
    //verification duplicate
    $query = "select code,libelle from  " . $this->table_name . " where
		libelle=:libelle";
    $stmt = $this->connection->prepare($query);
    $stmt->bindValue(":libelle", $this->libelle);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $num = $stmt->rowCount();
    if ($num > 0) {
      $result["error"] = 1;
      $result["message"] = 'Il y a déjà un CVS nommé (' . $this->libelle . ')';
      return $result;
    }



    if (strlen($this->id_site) == 0) {
      $result["error"] = 1;
      $result["message"] = "L'utilisateur n'est pas lié à un site.";
      return $result;
    }
    $query = "INSERT INTO " . $this->table_name . "  SET code=:code,libelle=:libelle,n_user_create=:n_user_create,code_province=:code_province,id_commune=:id_commune,id_site=:id_site";
    $stmt = $this->connection->prepare($query);
    // $this->id_organisme=strip_tags($this->id_organisme);
    //$this->n_user_create=strip_tags($this->n_user_create);
    //$this->annule=strip_tags($this->annule);
    //$this->n_user_annule=strip_tags($this->n_user_annule);
    //$this->motif_annulation=strip_tags($this->motif_annulation);
    //$this->date_synchro=strip_tags($this->date_synchro);
    //$this->is_sync=strip_tags($this->is_sync);
    $this->code_province = strip_tags($this->code_province);
    $this->id_site = strip_tags($this->id_site);
    //$this->date_annule=strip_tags($this->date_annule);
    //$this->activated=strip_tags($this->activated);
    $this->id_commune = strip_tags($this->id_commune);
    $this->datesys = date("Y-m-d H:i:s");

    $stmt->bindParam(":code", $this->code);
    $stmt->bindParam(":libelle", $this->libelle);
    $stmt->bindParam(":n_user_create", $this->n_user_create);
    //$stmt->bindParam(":annule", $this->annule);
    //$stmt->bindParam(":n_user_annule", $this->n_user_annule);
    //$stmt->bindParam(":motif_annulation", $this->motif_annulation);
    //$stmt->bindParam(":date_synchro", $this->date_synchro);
    //$stmt->bindParam(":is_sync", $this->is_sync);
    //  $stmt->bindParam(":id_organisme", $this->id_organisme);
    $stmt->bindParam(":code_province", $this->code_province);
    $stmt->bindParam(":id_site", $this->id_site);
    // $stmt->bindParam(":date_annule", $this->date_annule);
    // $stmt->bindParam(":activated", $this->activated);
    $stmt->bindParam(":id_commune", $this->id_commune);
    if ($stmt->execute()) {
      if (is_array($this->id_organisme)) {
        //Suppression de tous les Organismes liés au CVS pour actualiser les Organismes selectionnés
        $query_ven = "delete from t_param_cvs_organisme  where id_cvs=:id_cvs";
        $stmt_ven = $this->connection->prepare($query_ven);
        $stmt_ven->bindValue(':id_cvs', $this->code);
        $stmt_ven->execute();


        $query = "INSERT INTO t_param_cvs_organisme (ref_link,id_cvs,id_organisme,n_user_create,datesys) values (:ref_link,:id_cvs,:id_organisme,:n_user_create,:datesys);";
        $stmt = $this->connection->prepare($query);
        //$k => $v		
        foreach ($this->id_organisme as $value) {
          $ref_link = Utils::uniqUid("t_param_cvs_organisme", "ref_link", $this->connection);
          $stmt->bindValue(':ref_link', $ref_link);
          $stmt->bindValue(':id_cvs', $this->code);
          $stmt->bindValue(':id_organisme', $value);
          $stmt->bindValue(':n_user_create', $this->n_user_update);
          $stmt->bindValue(':datesys', $this->date_update);
          $stmt->execute();
        }
      }
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

    $this->code = strip_tags($this->code);
    $this->libelle = strip_tags($this->libelle);
    //VERIFICATION DUPLICATE
    $query = "select code,libelle from  " . $this->table_name . " where
		libelle=:libelle";
    $stmt = $this->connection->prepare($query);
    $stmt->bindValue(":libelle", $this->libelle);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $num = $stmt->rowCount();
    if ($num > 0) {
      if ($this->code != $row["code"]) {
        $result["error"] = 1;
        $result["message"] = 'Il y a déjà un CVS nommé (' . $this->libelle . ')';
        return $result;
      }
    }

    $query = "UPDATE " . $this->table_name . "  SET libelle=:libelle,n_user_update=:n_user_update,date_update=:date_update,is_sync=:is_sync,code_province=:code_province,id_commune=:id_commune,id_site=:id_site WHERE code=:code";
    $stmt = $this->connection->prepare($query);
    $this->n_user_update = strip_tags($this->n_user_update);
    $this->date_update = strip_tags($this->date_update);
    // $this->annule=strip_tags($this->annule);
    // $this->n_user_annule=strip_tags($this->n_user_annule);
    //$this->motif_annulation=strip_tags($this->motif_annulation);
    // $this->date_synchro=strip_tags($this->date_synchro);
    $this->is_sync = 0; //strip_tags($this->is_sync);
    $this->code_province = strip_tags($this->code_province);
    $this->id_site = strip_tags($this->id_site);
    // $this->date_annule=strip_tags($this->date_annule);
    // $this->activated=strip_tags($this->activated);
    $this->id_commune = strip_tags($this->id_commune);
    //$this->id_organisme=strip_tags($this->id_organisme);
    $this->date_update = date("Y-m-d H:i:s");

    $stmt->bindParam(":code", $this->code);
    $stmt->bindParam(":libelle", $this->libelle);
    $stmt->bindParam(":n_user_update", $this->n_user_update);
    $stmt->bindParam(":date_update", $this->date_update);
    //$stmt->bindParam(":annule", $this->annule);
    //$stmt->bindParam(":n_user_annule", $this->n_user_annule);
    //$stmt->bindParam(":motif_annulation", $this->motif_annulation);
    //$stmt->bindParam(":date_synchro", $this->date_synchro);
    $stmt->bindParam(":is_sync", $this->is_sync);
    $stmt->bindParam(":code_province", $this->code_province);
    $stmt->bindParam(":id_site", $this->id_site);
    //$stmt->bindParam(":date_annule", $this->date_annule);
    //$stmt->bindParam(":activated", $this->activated);
    $stmt->bindParam(":id_commune", $this->id_commune);
    // $stmt->bindParam(":id_organisme", $this->id_organisme);
    if ($stmt->execute()) {

      if (is_array($this->id_organisme)) {
        //Suppression de tous les Organismes liés au CVS pour actualiser les Organismes selectionnés
        $query_ven = "delete from t_param_cvs_organisme  where id_cvs=:id_cvs";
        $stmt_ven = $this->connection->prepare($query_ven);
        $stmt_ven->bindValue(':id_cvs', $this->code);
        $stmt_ven->execute();


        $query = "INSERT INTO t_param_cvs_organisme (ref_link,id_cvs,id_organisme,n_user_create,datesys) values (:ref_link,:id_cvs,:id_organisme,:n_user_create,:datesys);";
        $stmt = $this->connection->prepare($query);
        //$k => $v		
        foreach ($this->id_organisme as $value) {
          $ref_link = Utils::uniqUid("t_param_cvs_organisme", "ref_link", $this->connection);
          $stmt->bindValue(':ref_link', $ref_link);
          $stmt->bindValue(':id_cvs', $this->code);
          $stmt->bindValue(':id_organisme', $value);
          $stmt->bindValue(':n_user_create', $this->n_user_update);
          $stmt->bindValue(':datesys', $this->date_update);
          $stmt->execute();
        }
      }


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



  function GetOrganismeList($id_cvs)
  {
    $items = array();
    $query = "SELECT t_param_cvs_organisme.id_cvs,t_param_organisme.ref_organisme as code,t_param_organisme.denomination FROM t_param_cvs_organisme INNER JOIN t_param_organisme ON t_param_cvs_organisme.id_organisme = t_param_organisme.ref_organisme where t_param_cvs_organisme.id_cvs=:id_cvs";
    $stmt = $this->connection->prepare($query);
    $stmt->bindValue(":id_cvs", $id_cvs);
    $stmt->execute();
    while ($rw = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $rw['denomination'];
    }
    return implode(', ', $items);
  }


  function GetDetail()
  {
    $items = array();
    $query = "SELECT * FROM " . $this->table_name . " WHERE code = ? 	LIMIT 0,1";
    $stmt = $this->connection->prepare($query);
    $this->code = strip_tags($this->code);
    $stmt->bindParam(1, $this->code);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $result["data"] = $row;
    $result["error"] = 0;
    $query = "SELECT t_param_cvs_organisme.ref_link,t_param_cvs_organisme.id_cvs,t_param_organisme.ref_organisme as code,t_param_organisme.denomination FROM t_param_cvs_organisme INNER JOIN t_param_organisme ON t_param_cvs_organisme.id_organisme = t_param_organisme.ref_organisme where t_param_cvs_organisme.id_cvs=:id_cvs";
    $stmt = $this->connection->prepare($query);
    $stmt->bindValue(":id_cvs", $this->code);
    $stmt->execute();
    while ($rw = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $items[] = $rw;
    }
    $result["items"] = $items;
    return $result;
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

  function GetCommuneCVS($id_commune)
  {
    $query = $query = "SELECT code,libelle  
			FROM " . $this->table_name . "
			WHERE id_commune =:id_commune";
    $stmt = $this->connection->prepare($query);
    $id_commune = (strip_tags($id_commune));
    $stmt->bindParam(":id_commune", $id_commune);
    $stmt->execute();
    return $stmt;
  }
  function GetSiteCVS($id_site)
  {
    $query = $query = "SELECT code,libelle  
			FROM " . $this->table_name . "
			WHERE id_site =:id_site  and annule=0";
    $stmt = $this->connection->prepare($query);
    $id_site = (strip_tags($id_site));
    $stmt->bindParam(":id_site", $id_site);
    $stmt->execute();
    return $stmt;
  }
  function read()
  {
    $query = "SELECT code,libelle,annule,id_organisme,n_user_annule,motif_annulation,date_synchro,is_sync,code_province,id_site,date_annule,activated,id_commune FROM " . $this->table_name . " WHERE annule=0 ORDER BY libelle";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }
  function readAll($from_record_num, $records_per_page)
  {
    $query = "SELECT code,libelle,annule,id_organisme,n_user_annule,motif_annulation,date_synchro,is_sync,code_province,id_site,date_annule,activated,id_commune FROM " . $this->table_name . "  WHERE annule=0 ORDER BY libelle ASC LIMIT {$from_record_num}, {$records_per_page}";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }
  public function search($search_term, $from_record_num, $records_per_page)
  {
    $query = "SELECT code,libelle,annule,id_organisme,n_user_annule,motif_annulation,date_synchro,is_sync,code_province,id_site,date_annule,activated,id_commune  FROM " . $this->table_name  . " WHERE libelle LIKE :search_term  and annule=0 ORDER BY libelle ASC LIMIT :from, :offset";
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
    $query = "SELECT code FROM " . $this->table_name . " WHERE annule=0";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    return $num;
  }
  public function countAll_BySearch($search_term)
  {
    $query = "SELECT COUNT(*) as total_rows FROM  " . $this->table_name . " WHERE libelle LIKE :search_term  and annule=0";
    $stmt = $this->connection->prepare($query);
    $search_term = "%{$search_term}%";
    $stmt->bindParam(":search_term", $search_term);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row["total_rows"];
  }
}
