<?php

class Materiels
{

  public function __construct($db)
  {
    $this->connection = $db;
  }
  public $ref_produit;
  public $designation;
  public $n_user_create;
  public $n_user_update;
  public $date_update;
  public $datesys;
  public $annule;
  public $id_categorie;
  public $unite_de_mesure;
  public $description;
  public $motif_annulation;
  public $date_annule;
  public $n_user_annule;
  public $is_sync;
  private $table_name = 't_param_liste_materiels';
  private $connection;

  function Create()
  {
    $query = "select ref_produit,designation from  " . $this->table_name . " where
		designation=:designation";
    $stmt = $this->connection->prepare($query);
    $stmt->bindValue(":designation", trim($this->designation));
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $num = $stmt->rowCount();
    if ($num > 0) {
      $result["error"] = 1;
      $result["message"] = 'Il y a déjà un matériel (' . $this->designation . ')';
      return $result;
    }
    $generer = new Generateur($this->connection, TRUE);
    $this->ref_produit = $generer->getUID('generateur_main', 'num_materiel', 'N', 't_param_liste_materiels', 'ref_produit');

    $query = "INSERT INTO " . $this->table_name . "  SET ref_produit=:ref_produit,designation=:designation,unite_de_mesure=:unite_de_mesure,n_user_create=:n_user_create,datesys=:datesys";
    $stmt = $this->connection->prepare($query);
    $this->ref_produit = strip_tags($this->ref_produit);
    $this->designation = strip_tags($this->designation);
    $this->unite_de_mesure = strip_tags($this->unite_de_mesure);
    $this->n_user_create = strip_tags($this->n_user_create);
    $this->datesys = date("Y-m-d H:i:s");

    $stmt->bindParam(":ref_produit", $this->ref_produit);
    $stmt->bindParam(":designation", $this->designation);
    $stmt->bindParam(":unite_de_mesure", $this->unite_de_mesure);
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
    $query = "select ref_produit,designation from  " . $this->table_name . " where
		designation=:designation";
    $stmt = $this->connection->prepare($query);
    $stmt->bindValue(":designation", trim($this->designation));
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $num = $stmt->rowCount();
    if ($num > 0) {
      if ($this->ref_produit !=  $row['ref_produit']) {
        $result["error"] = 1;
        $result["message"] = 'Il y a déjà un matériel (' . $this->designation . ')';
        return $result;
      }
    }
    $query = "UPDATE " . $this->table_name . "  SET designation=:designation,unite_de_mesure=:unite_de_mesure,n_user_update=:n_user_update,date_update=:date_update WHERE ref_produit=:ref_produit";
    $stmt = $this->connection->prepare($query);
    $this->ref_produit = strip_tags($this->ref_produit);
    $this->designation = strip_tags($this->designation);
    $this->unite_de_mesure = strip_tags($this->unite_de_mesure);
    $this->n_user_update = strip_tags($this->n_user_update);
    $this->date_update = date("Y-m-d H:i:s");

    $stmt->bindParam(":ref_produit", $this->ref_produit);
    $stmt->bindParam(":designation", $this->designation);
    $stmt->bindParam(":unite_de_mesure", $this->unite_de_mesure);
    $stmt->bindParam(":n_user_update", $this->n_user_update);
    $stmt->bindParam(":date_update", $this->date_update);
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
    $query = "DELETE FROM " . $this->table_name . " WHERE ref_produit=:ref_produit";
    $stmt = $this->connection->prepare($query);
    $this->ref_produit = strip_tags($this->ref_produit);
    $stmt->bindParam(":ref_produit", $this->ref_produit);
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
    $query = "SELECT * FROM " . $this->table_name . " WHERE ref_produit = ? 	LIMIT 0,1";
    $stmt = $this->connection->prepare($query);
    $this->ref_produit = strip_tags($this->ref_produit);
    $stmt->bindParam(1, $this->ref_produit);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
  }
  function read()
  {
    $query = "SELECT  t_param_liste_materiels.ref_produit,t_param_liste_materiels.designation,t_param_liste_materiels.annule,t_param_liste_materiels.id_categorie,t_param_liste_materiels.unite_de_mesure,t_param_liste_materiels.description,t_param_liste_materiels.motif_annulation,t_param_liste_materiels.date_annule,t_param_liste_materiels.n_user_annule,t_param_liste_materiels.is_sync,t_param_unite_de_mesure.libelle_unite FROM t_param_liste_materiels INNER JOIN t_param_unite_de_mesure ON t_param_liste_materiels.unite_de_mesure = t_param_unite_de_mesure.code_unite WHERE t_param_liste_materiels.annule=0  ORDER BY  t_param_liste_materiels.designation ASC";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }
  function readAll($from_record_num, $records_per_page)
  {
    $query = "SELECT ref_produit,designation,annule,id_categorie,unite_de_mesure,description,motif_annulation,date_annule,n_user_annule,is_sync FROM " . $this->table_name . " WHERE t_param_liste_materiels.annule='0' ORDER BY designation ASC LIMIT {$from_record_num}, {$records_per_page}";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }
  public function search($search_term, $from_record_num, $records_per_page)
  {
    $query = "SELECT ref_produit,designation,annule,id_categorie,unite_de_mesure,description,motif_annulation,date_annule,n_user_annule,is_sync  FROM " . $this->table_name  . " WHERE designation LIKE :search_term  ORDER BY designation ASC LIMIT :from, :offset";
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
    $query = "SELECT ref_produit FROM " . $this->table_name . "  WHERE t_param_liste_materiels.annule='0' ";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    $num = $stmt->rowCount();
    return $num;
  }
  public function countAll_BySearch($search_term)
  {
    $query = "SELECT COUNT(*) as total_rows FROM  " . $this->table_name . " WHERE designation LIKE :search_term" . " and  t_param_liste_materiels.annule='0' ";
    $stmt = $this->connection->prepare($query);
    $search_term = "%{$search_term}%";
    $stmt->bindParam(":search_term", $search_term);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row["total_rows"];
  }
}
