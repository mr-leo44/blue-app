<?php
 
class SiteProduction{
   
  public function __construct($db){
   $this->connection = $db;
  }
  public $code_site;
  public $intitule_site;
  public $adresse_site;
  public $contact_site;
  public $province_id;
  public $annule;
  public $n_user_create;
  public $datesys;
  public $n_user_update;
  public $date_update;
  public $n_user_annule;
  public $date_annule;
  private $table_name='t_param_site_production';
  private $connection;
  
  function Create(){
   $query = "INSERT INTO " . $this->table_name . "  SET code_site=:code_site,intitule_site=:intitule_site,adresse_site=:adresse_site,contact_site=:contact_site,province_id=:province_id,annule=:annule,n_user_create=:n_user_create,n_user_annule=:n_user_annule,date_annule=:date_annule";
   $stmt = $this->connection->prepare($query);
   $this->code_site=strip_tags($this->code_site);
   $this->intitule_site=strip_tags($this->intitule_site);
   $this->adresse_site=strip_tags($this->adresse_site);
   $this->contact_site=strip_tags($this->contact_site);
   $this->province_id=strip_tags($this->province_id);
   $this->annule=strip_tags($this->annule);
   $this->n_user_create=strip_tags($this->n_user_create);
   $this->n_user_annule=strip_tags($this->n_user_annule);
   $this->date_annule=strip_tags($this->date_annule);
   $this->datesys = date("Y-m-d H:i:s");
   
   $stmt->bindParam(":code_site", $this->code_site);
   $stmt->bindParam(":intitule_site", $this->intitule_site);
   $stmt->bindParam(":adresse_site", $this->adresse_site);
   $stmt->bindParam(":contact_site", $this->contact_site);
   $stmt->bindParam(":province_id", $this->province_id);
   $stmt->bindParam(":annule", $this->annule);
   $stmt->bindParam(":n_user_create", $this->n_user_create);
   $stmt->bindParam(":n_user_annule", $this->n_user_annule);
   $stmt->bindParam(":date_annule", $this->date_annule);
   if($stmt->execute()){
    $result["error"] = 0;
    $result["message"] = "Création effectuée avec succès";
   }else{
    $result["error"] = 1;
    $result["message"] = "L'opératon de la création a échoué.";
   }
   return $result;
  }
  
  function Modifier(){
   $query = "UPDATE " . $this->table_name . "  SET intitule_site=:intitule_site,adresse_site=:adresse_site,contact_site=:contact_site,province_id=:province_id,annule=:annule,n_user_update=:n_user_update,date_update=:date_update,n_user_annule=:n_user_annule,date_annule=:date_annule WHERE code_site=:code_site";
   $stmt = $this->connection->prepare($query);
   $this->code_site=strip_tags($this->code_site);
   $this->intitule_site=strip_tags($this->intitule_site);
   $this->adresse_site=strip_tags($this->adresse_site);
   $this->contact_site=strip_tags($this->contact_site);
   $this->province_id=strip_tags($this->province_id);
   $this->annule=strip_tags($this->annule);
   $this->n_user_update=strip_tags($this->n_user_update);
   $this->date_update=strip_tags($this->date_update);
   $this->n_user_annule=strip_tags($this->n_user_annule);
   $this->date_annule=strip_tags($this->date_annule);
   $this->date_update = date("Y-m-d H:i:s");
   
   $stmt->bindParam(":code_site", $this->code_site);
   $stmt->bindParam(":intitule_site", $this->intitule_site);
   $stmt->bindParam(":adresse_site", $this->adresse_site);
   $stmt->bindParam(":contact_site", $this->contact_site);
   $stmt->bindParam(":province_id", $this->province_id);
   $stmt->bindParam(":annule", $this->annule);
   $stmt->bindParam(":n_user_update", $this->n_user_update);
   $stmt->bindParam(":date_update", $this->date_update);
   $stmt->bindParam(":n_user_annule", $this->n_user_annule);
   $stmt->bindParam(":date_annule", $this->date_annule);
   if($stmt->execute()){
    $result["error"] = 0;
    $result["message"] = "Modification effectuée avec succès";
   }else{
    $result["error"] = 1;
    $result["message"] = "L'opératon de la modification a échoué.";
   }
   return $result;
  }
  
  function Supprimer(){
   $query = "DELETE FROM " . $this->table_name . " WHERE code_site=:code_site";
   $stmt = $this->connection->prepare($query);
   $this->code_site=strip_tags($this->code_site);
   $stmt->bindParam(":code_site", $this->code_site);
   if($stmt->execute()){
    $result["error"] = 0;
    $result["message"] = "Suppression effectuée avec succès";
   }else{
    $result["error"] = 1;
    $result["message"] = "L'opératon de la suppression a échoué.";
   }
   return $result;
  }
  
  function GetDetail(){
   $query = "SELECT * FROM " . $this->table_name . " WHERE code_site = ? 	LIMIT 0,1";
   $stmt = $this->connection->prepare($query);
    $this->code_site=strip_tags($this->code_site);
   $stmt->bindParam(1,$this->code_site);
   $stmt->execute(); 
   $row = $stmt->fetch(PDO::FETCH_ASSOC); 
   return $row;
  }
  function read(){ 
   $query = "SELECT code_site,intitule_site,adresse_site,contact_site,province_id,annule,n_user_annule,date_annule FROM " . $this->table_name . " ORDER BY intitule_site";
   $stmt = $this->connection->prepare( $query );
   $stmt->execute();
   return $stmt;
  }
  function readAll($from_record_num, $records_per_page){ 
   $query = "SELECT code_site,intitule_site,adresse_site,contact_site,province_id,annule,n_user_annule,date_annule FROM " . $this->table_name . " ORDER BY intitule_site ASC LIMIT {$from_record_num}, {$records_per_page}";
   $stmt = $this->connection->prepare( $query );
   $stmt->execute();
   return $stmt;
  }
  public function search($search_term, $from_record_num, $records_per_page){
   $query = "SELECT code_site,intitule_site,adresse_site,contact_site,province_id,annule,n_user_annule,date_annule  FROM " . $this->table_name  . " WHERE intitule_site LIKE :search_term  ORDER BY intitule_site ASC LIMIT :from, :offset";
   $stmt = $this->connection->prepare( $query );
   $search_term = "%{$search_term}%";
   $stmt->bindParam(':search_term', $search_term);
   $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
   $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
   $stmt->execute(); 
   return $stmt;
  }
  public function countAll(){ 
   $query = "SELECT code_site FROM " . $this->table_name;
   $stmt = $this->connection->prepare( $query );
   $stmt->execute();
   $num = $stmt->rowCount();
   return $num;
  }
  public function countAll_BySearch($search_term){
   $query = "SELECT COUNT(*) as total_rows FROM  " . $this->table_name . " WHERE intitule_site LIKE :search_term";
   $stmt = $this->connection->prepare( $query ); 
   $search_term = "%{$search_term}%";
   $stmt->bindParam(":search_term", $search_term);
   $stmt->execute();
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   return $row["total_rows"];
  }
}
?>
