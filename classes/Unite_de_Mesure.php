<?php
 
class Unite_de_Mesure{
   
  public function __construct($db){
   $this->connection = $db;
  }
  public $code_unite;
  public $libelle_unite;
  public $symbole_unite;
  public $stateUpdate;
  public $date_sync;
  public $datesys;
  public $dateUpdate;
  public $id_boutique;
  private $table_name='t_param_unite_de_mesure';
  private $connection;
  
  function Create(){
   $query = "INSERT INTO " . $this->table_name . "  SET code_unite=:code_unite,libelle_unite=:libelle_unite,symbole_unite=:symbole_unite,stateUpdate=:stateUpdate,date_sync=:date_sync,dateUpdate=:dateUpdate,id_boutique=:id_boutique";
   $stmt = $this->connection->prepare($query);
   $this->code_unite=strip_tags($this->code_unite);
   $this->libelle_unite=strip_tags($this->libelle_unite);
   $this->symbole_unite=strip_tags($this->symbole_unite);
   $this->stateUpdate=strip_tags($this->stateUpdate);
   $this->date_sync=strip_tags($this->date_sync);
   $this->dateUpdate=strip_tags($this->dateUpdate);
   $this->id_boutique=strip_tags($this->id_boutique);
   $this->datesys = date("Y-m-d H:i:s");
   
   $stmt->bindParam(":code_unite", $this->code_unite);
   $stmt->bindParam(":libelle_unite", $this->libelle_unite);
   $stmt->bindParam(":symbole_unite", $this->symbole_unite);
   $stmt->bindParam(":stateUpdate", $this->stateUpdate);
   $stmt->bindParam(":date_sync", $this->date_sync);
   $stmt->bindParam(":dateUpdate", $this->dateUpdate);
   $stmt->bindParam(":id_boutique", $this->id_boutique);
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
   $query = "UPDATE " . $this->table_name . "  SET libelle_unite=:libelle_unite,symbole_unite=:symbole_unite,stateUpdate=:stateUpdate,date_sync=:date_sync,dateUpdate=:dateUpdate,id_boutique=:id_boutique WHERE code_unite=:code_unite";
   $stmt = $this->connection->prepare($query);
   $this->code_unite=strip_tags($this->code_unite);
   $this->libelle_unite=strip_tags($this->libelle_unite);
   $this->symbole_unite=strip_tags($this->symbole_unite);
   $this->stateUpdate=strip_tags($this->stateUpdate);
   $this->date_sync=strip_tags($this->date_sync);
   $this->dateUpdate=strip_tags($this->dateUpdate);
   $this->id_boutique=strip_tags($this->id_boutique);
   $this->date_update = date("Y-m-d H:i:s");
   
   $stmt->bindParam(":code_unite", $this->code_unite);
   $stmt->bindParam(":libelle_unite", $this->libelle_unite);
   $stmt->bindParam(":symbole_unite", $this->symbole_unite);
   $stmt->bindParam(":stateUpdate", $this->stateUpdate);
   $stmt->bindParam(":date_sync", $this->date_sync);
   $stmt->bindParam(":dateUpdate", $this->dateUpdate);
   $stmt->bindParam(":id_boutique", $this->id_boutique);
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
   $query = "DELETE FROM " . $this->table_name . " WHERE code_unite=:code_unite";
   $stmt = $this->connection->prepare($query);
   $this->code_unite=strip_tags($this->code_unite);
   $stmt->bindParam(":code_unite", $this->code_unite);
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
   $query = "SELECT * FROM " . $this->table_name . " WHERE code_unite = ? 	LIMIT 0,1";
   $stmt = $this->connection->prepare($query);
    $this->code_unite=strip_tags($this->code_unite);
   $stmt->bindParam(1,$this->code_unite);
   $stmt->execute(); 
   $row = $stmt->fetch(PDO::FETCH_ASSOC); 
   return $row;
  }
  function read(){ 
   $query = "SELECT code_unite,libelle_unite,symbole_unite,stateUpdate,date_sync,dateUpdate,id_boutique FROM " . $this->table_name . " ORDER BY libelle_unite";
   $stmt = $this->connection->prepare( $query );
   $stmt->execute();
   return $stmt;
  }
  function readAll($from_record_num, $records_per_page){ 
   $query = "SELECT code_unite,libelle_unite,symbole_unite,stateUpdate,date_sync,dateUpdate,id_boutique FROM " . $this->table_name . " ORDER BY libelle_unite ASC LIMIT {$from_record_num}, {$records_per_page}";
   $stmt = $this->connection->prepare( $query );
   $stmt->execute();
   return $stmt;
  }
  public function search($search_term, $from_record_num, $records_per_page){
   $query = "SELECT code_unite,libelle_unite,symbole_unite,stateUpdate,date_sync,dateUpdate,id_boutique  FROM " . $this->table_name  . " WHERE libelle_unite LIKE :search_term  ORDER BY libelle_unite ASC LIMIT :from, :offset";
   $stmt = $this->connection->prepare( $query );
   $search_term = "%{$search_term}%";
   $stmt->bindParam(':search_term', $search_term);
   $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
   $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
   $stmt->execute(); 
   return $stmt;
  }
  public function countAll(){ 
   $query = "SELECT code_unite FROM " . $this->table_name;
   $stmt = $this->connection->prepare( $query );
   $stmt->execute();
   $num = $stmt->rowCount();
   return $num;
  }
  public function countAll_BySearch($search_term){
   $query = "SELECT COUNT(*) as total_rows FROM  " . $this->table_name . " WHERE libelle_unite LIKE :search_term";
   $stmt = $this->connection->prepare( $query ); 
   $search_term = "%{$search_term}%";
   $stmt->bindParam(":search_term", $search_term);
   $stmt->execute();
   $row = $stmt->fetch(PDO::FETCH_ASSOC);
   return $row["total_rows"];
  }
}
?>
