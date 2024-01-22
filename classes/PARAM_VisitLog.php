<?php

class PARAM_VisitLog
{

  public function __construct($db)
  {
    $this->connection = $db;
  }

  public $ref_log_visite;
  public $ref_adresse;
  public $datesys;
  public $date_update;
  public $is_sync;
  public $annule;
  public $statut_accessibilite;
  public $n_user_create;
  public $num_pa;
  public $type_motif_visite;
  public $site_id;
  public $cvs_id;
  public $commentaire;
  public $date_rendez_vous;

  //ADRESSE
  public $adress_id;
  public $quartier_id;
  public $commune_id;
  public $ville_id;
  public $province_id;
  public $numero;
  public $avenue;
  //ADRESSE

  //ASSIGN		 
  public $assign_id;
  //ASSIGN


  private $table_name = 't_param_log_visite_pa';
  private $connection;





  function Supprimer()
  {

    //suppression effective
    $query = "DELETE FROM " . $this->table_name . " WHERE ref_log_visite=:ref_log_visite";
    $stmt = $this->connection->prepare($query);
    $this->id_ = (strip_tags($this->id_));
    $stmt->bindParam(":ref_log_visite", $this->ref_log_visite);
    if ($stmt->execute()) {

      return true;
    } else {
      return false;
    }
  }

  function Create()
  {

    $this->datesys = date("Y-m-d H:i:s");
    $id_adress = '';
    //Récupération et création LOG Adresse 

    if (isset($this->adress_id)) {
      $id_adress = $this->adress_id;
    } else {
      $adress_item = new  AdresseEntity($this->connection);
      $adress_item->n_user_create = $this->n_user_create;
      $adress_item->datesys = $this->datesys;
      $id_adress = $adress_item->GetOrCreateAdressId($this->ville_id, $this->commune_id, $this->quartier_id, $this->avenue, $this->numero);
    }

    //END Récupération et création LOG Adresse 





    $query = "INSERT INTO " . $this->table_name . "  SET  ref_log_visite=:ref_log_visite,ref_adresse=:ref_adresse,datesys=:datesys, 
statut_accessibilite=:statut_accessibilite,n_user_create=:n_user_create,
num_pa=:num_pa,type_motif_visite=:type_motif_visite,site_id=:site_id,cvs_id=:cvs_id,commentaire=:commentaire,date_rendez_vous=:date_rendez_vous";

    $stmt = $this->connection->prepare($query);
    $this->ref_log_visite = strip_tags($this->ref_log_visite);
    $this->ref_adresse = $id_adress; // strip_tags($this->ref_adresse);
    $this->statut_accessibilite = strip_tags($this->statut_accessibilite);
    $this->n_user_create = strip_tags($this->n_user_create);
    $this->num_pa = strip_tags($this->num_pa);
    $this->type_motif_visite = strip_tags($this->type_motif_visite);
    $this->site_id = strip_tags($this->site_id);
    $this->cvs_id = strip_tags($this->cvs_id);
    $this->commentaire = strip_tags($this->commentaire);
    $this->date_rendez_vous = strip_tags($this->date_rendez_vous);
    $this->is_sync = 0; //strip_tags($this->is_sync);

    $stmt->bindParam(":ref_log_visite", $this->ref_log_visite);
    $stmt->bindParam(":ref_adresse", $this->ref_adresse);
    $stmt->bindParam(":datesys", $this->datesys);
    $stmt->bindParam(":statut_accessibilite", $this->statut_accessibilite);
    $stmt->bindParam(":n_user_create", $this->n_user_create);
    $stmt->bindParam(":num_pa", $this->num_pa);
    $stmt->bindParam(":type_motif_visite", $this->type_motif_visite);
    $stmt->bindParam(":site_id", $this->site_id);
    $stmt->bindParam(":cvs_id", $this->cvs_id);
    $stmt->bindParam(":commentaire", $this->commentaire);
    $stmt->bindValue(":date_rendez_vous", Utils::ClientToDbDateFormat($this->date_rendez_vous));





    //$stmt->bindParam(":is_sync", $this->is_sync);
    if ($stmt->execute()) {
      //SIGNALISATION RENDEZ POUR ASSIGNATION
      if (isset($this->assign_id)) {
        $query = "update t_param_assignation set comment_rendez_vous=:comment_rendez_vous,date_rendez_vous=:date_rendez_vous,user_accessibility=:user_accessibility,accesibility=:accesibility,date_accessibility=:date_accessibility  where id_assign=:id_assign";
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(":id_assign", $this->assign_id);
        $stmt->bindValue(":date_rendez_vous", Utils::ClientToDbDateFormat($this->date_rendez_vous));
        $stmt->bindValue(":comment_rendez_vous", $this->commentaire);
        $stmt->bindValue(":date_accessibility", $this->datesys);
        $stmt->bindValue(":accesibility", $this->statut_accessibilite);
        $stmt->bindValue(":user_accessibility", $this->n_user_create);
        $stmt->execute();
      }
      //////////////


      $result["error"] = 0;
      $result["message"] = "Envoi effectué avec succès";
    } else {
      $result["error"] = 1;
      $result["message"] = "L'opératon de l'envoi a échoué.";
    }
    return $result;
  }

  /*
  function read(){ 
  // $query = "SELECT code,libelle,is_sync FROM " . $this->table_name . " ORDER BY libelle";
   $stmt = $this->connection->prepare( $query );
   $stmt->execute();
   return $stmt;
  }
  */
  function readAll($from_record_num, $records_per_page, $site)
  {
    $query = "SELECT t_main_data.id_,t_main_data.date_identification,t_main_data.p_a,t_main_data.gps_longitude,t_main_data.gps_latitude,t_main_data.nom_proprietaire_facture_snel,t_main_data.phone_proprietaire_facture_snel,t_main_data.nom_client_blue,t_main_data.phone_client_blue,t_main_data.adresse,t_main_data.cvs_id,t_main_data.commune_id,t_main_data.quartier,t_main_data.numero_avenue,t_main_data.num_compteur_actuel,t_param_assignation.id_assign,t_param_assignation.id_organe,t_param_assignation.datesys,DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,t_param_assignation.statut_,t_param_assignation.type_assignation,t_param_assignation.is_valid,t_param_assignation.annule,t_param_assignation.id_chef_operation  FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif  where t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation=1 ORDER BY t_param_assignation.datesys  DESC LIMIT {$from_record_num}, {$records_per_page}";
    $stmt = $this->connection->prepare($query);
    $stmt->bindValue(":ref_site_identif", $site);
    $stmt->execute();
    return $stmt;
  }
  public function search($search_term, $from_record_num, $records_per_page, $site)
  {
    $query = "SELECT code,libelle,is_sync,annule  FROM " . $this->table_name  . " WHERE libelle LIKE :search_term  ORDER BY libelle ASC LIMIT :from, :offset";


    $query = "SELECT t_main_data.id_,t_main_data.date_identification,t_main_data.p_a,t_main_data.gps_longitude,t_main_data.gps_latitude,t_main_data.nom_proprietaire_facture_snel,t_main_data.phone_proprietaire_facture_snel,t_main_data.nom_client_blue,t_main_data.phone_client_blue,t_main_data.adresse,t_main_data.cvs_id,t_main_data.commune_id,t_main_data.quartier,t_main_data.numero_avenue,t_main_data.num_compteur_actuel,t_param_assignation.id_assign,t_param_assignation.id_organe,t_param_assignation.datesys,DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,t_param_assignation.statut_,t_param_assignation.type_assignation,t_param_assignation.is_valid,t_param_assignation.annule,t_param_assignation.id_chef_operation FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif  where (t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation=1) and (num_compteur_actuel LIKE :search_term or nom_client_blue LIKE :search_term or phone_client_blue LIKE :search_term) ORDER BY t_param_assignation.datesys  DESC LIMIT :from, :offset";

    $stmt = $this->connection->prepare($query);
    $search_term = "%{$search_term}%";
    $stmt->bindParam(':search_term', $search_term);
    $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
    $stmt->bindValue(":ref_site_identif", $site);
    $stmt->execute();
    return $stmt;
  }
  public function search_advanced($du, $au, $search_term, $from_record_num, $records_per_page, $site)
  {

    $query = "SELECT t_main_data.id_,t_main_data.date_identification,t_main_data.p_a,t_main_data.gps_longitude,t_main_data.gps_latitude,t_main_data.nom_proprietaire_facture_snel,t_main_data.phone_proprietaire_facture_snel,t_main_data.nom_client_blue,t_main_data.phone_client_blue,t_main_data.adresse,t_main_data.cvs_id,t_main_data.commune_id,t_main_data.quartier,t_main_data.numero_avenue,t_main_data.num_compteur_actuel,t_param_assignation.id_assign,t_param_assignation.id_organe,t_param_assignation.datesys,DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,t_param_assignation.statut_,t_param_assignation.type_assignation,t_param_assignation.is_valid,t_param_assignation.annule,t_param_assignation.id_chef_operation FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif  where (t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation=1) and (num_compteur_actuel LIKE :search_term or nom_client_blue LIKE :search_term or phone_client_blue LIKE :search_term) and (DATE_FORMAT(t_param_assignation.datesys,'%Y-%m-%d')  between :du and :au) ORDER BY t_param_assignation.datesys  DESC LIMIT :from, :offset";

    $stmt = $this->connection->prepare($query);
    $search_term = "%{$search_term}%";
    $stmt->bindParam(':search_term', $search_term);
    $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
    $stmt->bindValue(":ref_site_identif", $site);
    $stmt->bindParam(':du', $du);
    $stmt->bindParam(':au', $au);
    $stmt->execute();
    return $stmt;
  }
  public function search_advanced_DateOnly($du, $au,  $from_record_num, $records_per_page, $site)
  {

    $query = "SELECT t_main_data.id_,t_main_data.date_identification,t_main_data.p_a,t_main_data.gps_longitude,t_main_data.gps_latitude,t_main_data.nom_proprietaire_facture_snel,t_main_data.phone_proprietaire_facture_snel,t_main_data.nom_client_blue,t_main_data.phone_client_blue,t_main_data.adresse,t_main_data.cvs_id,t_main_data.commune_id,t_main_data.quartier,t_main_data.numero_avenue,t_main_data.num_compteur_actuel,t_param_assignation.id_assign,t_param_assignation.id_organe,t_param_assignation.datesys,DATE_FORMAT(t_param_assignation.datesys,'%d/%m/%Y %H:%i:%S')  as date_sys_fr,t_param_assignation.statut_,t_param_assignation.type_assignation,t_param_assignation.is_valid,t_param_assignation.annule,t_param_assignation.id_chef_operation FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif  where (t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation=1) and (DATE_FORMAT(t_param_assignation.datesys,'%Y-%m-%d')  between :du and :au) ORDER BY t_param_assignation.datesys  DESC LIMIT :from, :offset";

    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
    $stmt->bindValue(":ref_site_identif", $site);
    $stmt->bindParam(':du', $du);
    $stmt->bindParam(':au', $au);
    $stmt->execute();
    return $stmt;
  }
  public function countAll($site)
  {
    $query = "SELECT t_main_data.id_ FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif where t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.type_assignation=1 ";
    $stmt = $this->connection->prepare($query);
    $stmt->bindValue(":ref_site_identif", $site);
    $stmt->execute();
    $num = $stmt->rowCount();
    return $num;
  }
  public function countAll_BySearch($search_term, $site)
  {
    $query = "SELECT COUNT(*) as total_rows  FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif  where (t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation=1) and (num_compteur_actuel LIKE :search_term or nom_client_blue LIKE :search_term or phone_client_blue LIKE :search_term)";
    $stmt = $this->connection->prepare($query);
    $search_term = "%{$search_term}%";
    $stmt->bindParam(":search_term", $search_term);
    $stmt->bindValue(":ref_site_identif", $site);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row["total_rows"];
  }
  public function countAll_BySearch_advanced($du, $au, $search_term, $site)
  {
    $query = "SELECT COUNT(*) as total_rows  FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif  where (t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation=1) and (num_compteur_actuel LIKE :search_term or nom_client_blue LIKE :search_term or phone_client_blue LIKE :search_term)  and (DATE_FORMAT(t_param_assignation.datesys,'%Y-%m-%d')  between :du and :au)";
    $stmt = $this->connection->prepare($query);
    $search_term = "%{$search_term}%";
    $stmt->bindParam(":search_term", $search_term);
    $stmt->bindValue(":ref_site_identif", $site);
    $stmt->bindParam(':du', $du);
    $stmt->bindParam(':au', $au);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row["total_rows"];
  }
  public function countAll_BySearch_advanced_DateOnly($du, $au, $site)
  {
    $query = "SELECT COUNT(*) as total_rows  FROM t_main_data INNER JOIN t_param_assignation ON t_main_data.id_ = t_param_assignation.id_fiche_identif  where (t_main_data.ref_site_identif=:ref_site_identif and t_param_assignation.annule=0 and t_param_assignation.type_assignation=1)  and (DATE_FORMAT(t_param_assignation.datesys,'%Y-%m-%d')  between :du and :au)";
    $stmt = $this->connection->prepare($query);
    $stmt->bindValue(":ref_site_identif", $site);
    $stmt->bindParam(':du', $du);
    $stmt->bindParam(':au', $au);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row["total_rows"];
  }
}
