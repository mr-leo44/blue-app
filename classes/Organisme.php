<?php
 
class Organisme{
   
  public function __construct($db){
   $this->connection = $db;
  }
  public $ref_organisme;
  public $denomination;
  public $adresse;
  public $contact_organisme;
  public $ref_org_principal;
  public $phone;
  public $n_user_create;
  public $n_user_update;
  public $datelastupdate;
  public $datesys;
  public $annule_;
  public $type_;
  public $signature_id;
  public $is_sync;
  public $is_blue_energy;
  public $id_province;
  public $id_commune;
  public $id_quartier; 
  public $id_ville; 
  private $table_name='t_param_organisme';
  private $connection;
  
  function Create(){
   $query = "INSERT INTO " . $this->table_name . "  SET ref_organisme=:ref_organisme,denomination=:denomination,adresse=:adresse,contact_organisme=:contact_organisme,ref_org_principal=:ref_org_principal,phone=:phone,n_user_create=:n_user_create,is_blue_energy=:is_blue_energy,id_province=:id_province,id_ville=:id_ville,id_commune=:id_commune,id_quartier=:id_quartier";
   $stmt = $this->connection->prepare($query);
   $this->ref_organisme=strip_tags($this->ref_organisme);
   $this->denomination=strip_tags($this->denomination);
   $this->adresse=strip_tags($this->adresse);
   $this->contact_organisme=strip_tags($this->contact_organisme);
   $this->ref_org_principal=strip_tags($this->ref_org_principal);
   
   $this->phone=strip_tags($this->phone);
   $this->is_blue_energy=strip_tags($this->is_blue_energy);
   $this->id_province=strip_tags($this->id_province);
   $this->id_ville=strip_tags($this->id_ville);
   $this->id_commune=strip_tags($this->id_commune);
   $this->id_quartier=strip_tags($this->id_quartier);
   
   
   $this->n_user_create=strip_tags($this->n_user_create);
   //$this->annule_=strip_tags($this->annule_);
  // $this->type_=strip_tags($this->type_);
  // $this->signature_id=strip_tags($this->signature_id);
   //$this->is_sync=strip_tags($this->is_sync);
   $this->datesys = date("Y-m-d H:i:s");
   
   $stmt->bindParam(":ref_organisme", $this->ref_organisme);
   $stmt->bindParam(":denomination", $this->denomination);
   $stmt->bindParam(":adresse", $this->adresse);
   $stmt->bindParam(":contact_organisme", $this->contact_organisme);
   $stmt->bindParam(":ref_org_principal", $this->ref_org_principal);
   $stmt->bindParam(":phone", $this->phone);
    
   $stmt->bindParam(":is_blue_energy", $this->is_blue_energy);
   $stmt->bindParam(":id_province", $this->id_province);
   $stmt->bindParam(":id_ville", $this->id_ville);
   $stmt->bindParam(":id_commune", $this->id_commune);
   $stmt->bindParam(":id_quartier", $this->id_quartier);
   
   
   
   $stmt->bindParam(":n_user_create", $this->n_user_create);
  // $stmt->bindParam(":annule_", $this->annule_);
  // $stmt->bindParam(":type_", $this->type_);
  // $stmt->bindParam(":signature_id", $this->signature_id);
  // $stmt->bindParam(":is_sync", $this->is_sync);
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
   $query = "UPDATE " . $this->table_name . "  SET denomination=:denomination,adresse=:adresse,contact_organisme=:contact_organisme,ref_org_principal=:ref_org_principal,phone=:phone,n_user_update=:n_user_update,datelastupdate=:datelastupdate,is_blue_energy=:is_blue_energy,id_province=:id_province,id_ville=:id_ville,id_commune=:id_commune,id_quartier=:id_quartier WHERE ref_organisme=:ref_organisme";
   $stmt = $this->connection->prepare($query);
   $this->ref_organisme=strip_tags($this->ref_organisme);
   $this->denomination=strip_tags($this->denomination);
   $this->adresse=strip_tags($this->adresse);
   $this->contact_organisme=strip_tags($this->contact_organisme);
   $this->ref_org_principal=strip_tags($this->ref_org_principal);
   $this->phone=strip_tags($this->phone);
   $this->n_user_update=strip_tags($this->n_user_update);
   $this->datelastupdate=strip_tags($this->datelastupdate);
   $this->annule_=strip_tags($this->annule_);
   $this->type_=strip_tags($this->type_);
   $this->signature_id=strip_tags($this->signature_id);
   $this->is_sync=strip_tags($this->is_sync);
   $this->date_update = date("Y-m-d H:i:s");
   
   
   $this->is_blue_energy=strip_tags($this->is_blue_energy);
   $this->id_province=strip_tags($this->id_province);
   $this->id_ville=strip_tags($this->id_ville);
   $this->id_commune=strip_tags($this->id_commune);
   $this->id_quartier=strip_tags($this->id_quartier);
   
   
   $stmt->bindParam(":ref_organisme", $this->ref_organisme);
   $stmt->bindParam(":denomination", $this->denomination);
   $stmt->bindParam(":adresse", $this->adresse);
   $stmt->bindParam(":contact_organisme", $this->contact_organisme);
   $stmt->bindParam(":ref_org_principal", $this->ref_org_principal);
   $stmt->bindParam(":phone", $this->phone);
   $stmt->bindParam(":n_user_update", $this->n_user_update);
   $stmt->bindParam(":datelastupdate", $this->datelastupdate);
   
   
   $stmt->bindParam(":is_blue_energy", $this->is_blue_energy);
   $stmt->bindParam(":id_province", $this->id_province);
   $stmt->bindParam(":id_ville", $this->id_ville);
   $stmt->bindParam(":id_commune", $this->id_commune);
   $stmt->bindParam(":id_quartier", $this->id_quartier);
   
   /* $stmt->bindParam(":annule_", $this->annule_);
  $stmt->bindParam(":type_", $this->type_);
   $stmt->bindParam(":signature_id", $this->signature_id);
   $stmt->bindParam(":is_sync", $this->is_sync);*/
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
   $query = "DELETE FROM " . $this->table_name . " WHERE ref_organisme=:ref_organisme";
   $stmt = $this->connection->prepare($query);
   $this->ref_organisme=strip_tags($this->ref_organisme);
   $stmt->bindParam(":ref_organisme", $this->ref_organisme);
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
   $query = "SELECT * FROM " . $this->table_name . " WHERE ref_organisme = ? 	LIMIT 0,1";
   $stmt = $this->connection->prepare($query);
    $this->ref_organisme=strip_tags($this->ref_organisme);
   $stmt->bindParam(1,$this->ref_organisme);
   $stmt->execute(); 
   $row = $stmt->fetch(PDO::FETCH_ASSOC); 
   return $row;
  }
  function GetDetailIN(){
   $query = "SELECT * FROM " . $this->table_name . " WHERE ref_organisme = ? 	LIMIT 0,1";
   $stmt = $this->connection->prepare($query);
    $this->ref_organisme=strip_tags($this->ref_organisme);
   $stmt->bindParam(1,$this->ref_organisme);
   $stmt->execute(); 
   $row = $stmt->fetch(PDO::FETCH_ASSOC);    
   $this->denomination=strip_tags($row["denomination"]);
  }
  function read(){ 
   $query = "SELECT ref_organisme,denomination,adresse,contact_organisme,ref_org_principal,phone,annule_,type_,signature_id,is_sync FROM " . $this->table_name . " where annule_='2' ORDER BY denomination";
   $stmt = $this->connection->prepare( $query );
   $stmt->execute();
   return $stmt;
  }
  function readExclusive($user_context,$setting_value){ 
  $v = $user_context->GetSettingValue($setting_value);
  if($v == '1'){
	  $query = "SELECT ref_organisme,denomination,adresse,contact_organisme,ref_org_principal,phone,annule_,type_,signature_id,is_sync FROM " . $this->table_name . " where is_blue_energy='on' ORDER BY denomination";
   $stmt = $this->connection->prepare( $query );
   $stmt->execute();
   return $stmt;
  }else{
	$query = "SELECT ref_organisme,denomination,adresse,contact_organisme,ref_org_principal,phone,annule_,type_,signature_id,is_sync FROM " . $this->table_name . " ORDER BY denomination";
   $stmt = $this->connection->prepare( $query );
   $stmt->execute();
   return $stmt;  
	  
  }
   
  }
  function readAll($from_record_num, $records_per_page){ 
   $query = "SELECT ref_organisme,denomination,adresse,contact_organisme,ref_org_principal,phone,annule_,type_,signature_id,is_sync FROM " . $this->table_name . " ORDER BY denomination ASC LIMIT {$from_record_num}, {$records_per_page}";
   $stmt = $this->connection->prepare( $query );
   $stmt->execute();
   return $stmt;
  }
  public function search($search_term, $from_record_num, $records_per_page){
   $query = "SELECT ref_organisme,denomination,adresse,contact_organisme,ref_org_principal,phone,annule_,type_,signature_id,is_sync  FROM " . $this->table_name  . " WHERE denomination LIKE :search_term  ORDER BY denomination ASC LIMIT :from, :offset";
   $stmt = $this->connection->prepare( $query );
   $search_term = "%{$search_term}%";
   $stmt->bindParam(':search_term', $search_term);
   $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
   $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
   $stmt->execute(); 
   return $stmt;
  }
  public function countAll(){ 
   $query = "SELECT ref_organisme FROM " . $this->table_name;
   $stmt = $this->connection->prepare( $query );
   $stmt->execute();
   $num = $stmt->rowCount();
   return $num;
  }
  public function countAll_BySearch($search_term){
   $query = "SELECT COUNT(*) as total_rows FROM  " . $this->table_name . " WHERE denomination LIKE :search_term";
   $stmt = $this->connection->prepare( $query ); 
   $search_term = "%{$search_term}%";
   $stmt->bindParam(":search_term", $search_term);
   $stmt->execute();
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   return $row["total_rows"];
  }
}
?>
