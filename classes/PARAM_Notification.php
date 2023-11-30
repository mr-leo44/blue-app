<?php

class PARAM_Notification
{

  public function __construct($db)
  {
    $this->connection = $db;
  }
  public $ref_log;
  public $ref_identif;
  public $num_compteur;
  public $commentaire;
  public $statuts_notification;
  public $n_user_vu;
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
  public $date_vu;
  public $type_notification;
  public $numero_ticket;
  public $date_creation_ticket;
  public $n_user_create_ticket;
  public $tarif;
  public $ref_transaction;
  private $table_name = 't_param_notification_log';
  private $connection;

  private $cvs_id;
  private $nom_client;
  public $adresse;
  private $sort_type = "ASC";

  function Create()
  {
    $query = "INSERT INTO " . $this->table_name . "  SET ref_log=:ref_log,ref_identif=:ref_identif,num_compteur=:num_compteur,commentaire=:commentaire,statuts_notification=:statuts_notification,n_user_vu=:n_user_vu,n_user_create=:n_user_create,code_province=:code_province,id_site=:id_site,date_annule=:date_annule,activated=:activated,date_vu=:date_vu,type_notification=:type_notification,numero_ticket=:numero_ticket,date_creation_ticket=:date_creation_ticket,n_user_create_ticket=:n_user_create_ticket,tarif=:tarif,ref_transaction=:ref_transaction";
    $stmt = $this->connection->prepare($query);
    $this->ref_log = strip_tags($this->ref_log);
    $this->ref_identif = strip_tags($this->ref_identif);
    $this->num_compteur = strip_tags($this->num_compteur);
    $this->commentaire = strip_tags($this->commentaire);
    $this->statuts_notification = strip_tags($this->statuts_notification);
    $this->n_user_vu = strip_tags($this->n_user_vu);
    $this->n_user_create = strip_tags($this->n_user_create);
    $this->code_province = strip_tags($this->code_province);
    $this->id_site = strip_tags($this->id_site);
    $this->date_annule = strip_tags($this->date_annule);
    $this->activated = strip_tags($this->activated);
    $this->date_vu = strip_tags($this->date_vu);
    $this->type_notification = strip_tags($this->type_notification);
    $this->numero_ticket = strip_tags($this->numero_ticket);
    $this->date_creation_ticket = strip_tags($this->date_creation_ticket);
    $this->n_user_create_ticket = strip_tags($this->n_user_create_ticket);
    $this->tarif = strip_tags($this->tarif);
    $this->ref_transaction = strip_tags($this->ref_transaction);
    $this->datesys = date("Y-m-d H:i:s");

    $stmt->bindParam(":ref_log", $this->ref_log);
    $stmt->bindParam(":ref_identif", $this->ref_identif);
    $stmt->bindParam(":num_compteur", $this->num_compteur);
    $stmt->bindParam(":commentaire", $this->commentaire);
    $stmt->bindParam(":statuts_notification", $this->statuts_notification);
    $stmt->bindParam(":n_user_vu", $this->n_user_vu);
    $stmt->bindParam(":n_user_create", $this->n_user_create);
    $stmt->bindParam(":code_province", $this->code_province);
    $stmt->bindParam(":id_site", $this->id_site);
    $stmt->bindParam(":date_annule", $this->date_annule);
    $stmt->bindParam(":activated", $this->activated);
    $stmt->bindParam(":date_vu", $this->date_vu);
    $stmt->bindParam(":type_notification", $this->type_notification);
    $stmt->bindParam(":numero_ticket", $this->numero_ticket);
    $stmt->bindParam(":date_creation_ticket", $this->date_creation_ticket);
    $stmt->bindParam(":n_user_create_ticket", $this->n_user_create_ticket);
    $stmt->bindParam(":tarif", $this->tarif);
    $stmt->bindParam(":ref_transaction", $this->ref_transaction);

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
    $query = "UPDATE " . $this->table_name . "  SET ref_identif=:ref_identif,num_compteur=:num_compteur,commentaire=:commentaire,statuts_notification=:statuts_notification,n_user_vu=:n_user_vu,n_user_update=:n_user_update,date_update=:date_update,code_province=:code_province,id_site=:id_site,date_annule=:date_annule,activated=:activated,date_vu=:date_vu,type_notification=:type_notification,numero_ticket=:numero_ticket,date_creation_ticket=:date_creation_ticket,n_user_create_ticket=:n_user_create_ticket,tarif=:tarif,ref_transaction=:ref_transaction WHERE ref_log=:ref_log";
    $stmt = $this->connection->prepare($query);
    $this->ref_log = strip_tags($this->ref_log);
    $this->ref_identif = strip_tags($this->ref_identif);
    $this->num_compteur = strip_tags($this->num_compteur);
    $this->commentaire = strip_tags($this->commentaire);
    $this->statuts_notification = strip_tags($this->statuts_notification);
    $this->n_user_vu = strip_tags($this->n_user_vu);
    $this->n_user_update = strip_tags($this->n_user_update);
    $this->date_update = strip_tags($this->date_update);
    $this->code_province = strip_tags($this->code_province);
    $this->id_site = strip_tags($this->id_site);
    $this->date_annule = strip_tags($this->date_annule);
    $this->activated = strip_tags($this->activated);
    $this->date_vu = strip_tags($this->date_vu);
    $this->type_notification = strip_tags($this->type_notification);
    $this->numero_ticket = strip_tags($this->numero_ticket);
    $this->date_creation_ticket = strip_tags($this->date_creation_ticket);
    $this->n_user_create_ticket = strip_tags($this->n_user_create_ticket);
    $this->tarif = strip_tags($this->tarif);
    $this->ref_transaction = strip_tags($this->ref_transaction);
    $this->date_update = date("Y-m-d H:i:s");

    $stmt->bindParam(":ref_log", $this->ref_log);
    $stmt->bindParam(":ref_identif", $this->ref_identif);
    $stmt->bindParam(":num_compteur", $this->num_compteur);
    $stmt->bindParam(":commentaire", $this->commentaire);
    $stmt->bindParam(":statuts_notification", $this->statuts_notification);
    $stmt->bindParam(":n_user_vu", $this->n_user_vu);
    $stmt->bindParam(":n_user_update", $this->n_user_update);
    $stmt->bindParam(":date_update", $this->date_update);
    $stmt->bindParam(":code_province", $this->code_province);
    $stmt->bindParam(":id_site", $this->id_site);
    $stmt->bindParam(":date_annule", $this->date_annule);
    $stmt->bindParam(":activated", $this->activated);
    $stmt->bindParam(":date_vu", $this->date_vu);
    $stmt->bindParam(":type_notification", $this->type_notification);
    $stmt->bindParam(":numero_ticket", $this->numero_ticket);
    $stmt->bindParam(":date_creation_ticket", $this->date_creation_ticket);
    $stmt->bindParam(":n_user_create_ticket", $this->n_user_create_ticket);
    $stmt->bindParam(":tarif", $this->tarif);
    $stmt->bindParam(":ref_transaction", $this->ref_transaction);
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
    $query = "DELETE FROM " . $this->table_name . " WHERE ref_log=:ref_log";
    $stmt = $this->connection->prepare($query);
    $this->ref_log = strip_tags($this->ref_log);
    $stmt->bindParam(":ref_log", $this->ref_log);
    if ($stmt->execute()) {
      $result["error"] = 0;
      $result["message"] = "Suppression effectuée avec succès";
    } else {
      $result["error"] = 1;
      $result["message"] = "L'opératon de la suppression a échoué.";
    }
    return $result;
  }
  //assign_ticket
  function AssignerTicket($ref_log, $num_ticket)
  {
    $date_creation_ticket = date("Y-m-d H:i:s");
    $query = "UPDATE  " . $this->table_name . " SET  numero_ticket=:numero_ticket,date_creation_ticket=:date_creation_ticket,n_user_create_ticket=:n_user_create_ticket WHERE ref_log=:ref_log";
    $stmt = $this->connection->prepare($query);
    $this->ref_log = strip_tags($this->ref_log);
    $stmt->bindParam(":ref_log", $ref_log);
    $stmt->bindParam(":numero_ticket", $num_ticket);
    $stmt->bindParam(":date_creation_ticket", $date_creation_ticket);
    $stmt->bindParam(":n_user_create_ticket", $this->n_user_create);
    if ($stmt->execute()) {
      $result["error"] = 0;
      $result["message"] = "Assignation Ticket effectuée avec succès";
    } else {
      $result["error"] = 1;
      $result["message"] = "L'opératon d'Assignation a échoué.";
    }
    return $result;
  }


  function CreatePhoto($ref_log, $ref_photo, $label_photo)
  {
    //valide
    $query = "INSERT INTO t_param_notification_photo SET ref_photo=:ref_photo,ref_log=:ref_log,category_pic=:category_pic,n_user_create=:n_user_create,label_photo=:label_photo,datesys=:datesys";
    $stmt = $this->connection->prepare($query);

    //$valide=strip_tags($this->code_site);


    $stmt->bindValue(":ref_photo", $ref_photo);
    $stmt->bindValue(":ref_log", $ref_log);
    $stmt->bindValue(":category_pic", "6");
    $stmt->bindValue(":label_photo", $label_photo);
    $stmt->bindValue(":n_user_create", $this->n_user_create);
    $stmt->bindValue(":datesys", $this->datesys);
    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  function GetPhoto($ref_log)
  {

    $photos = array();
    //RECUPERATION PHOTOS GALLERY PA
    $query = "select  ref_photo,label_photo FROM t_param_notification_photo where ref_log=:ref_log";
    $stmt = $this->connection->prepare($query);
    $stmt->bindValue(":ref_log", $ref_log);
    $stmt->execute();
    $result["error"] = false;
    $result["message"] = "";
    $result["count"] = $stmt->rowCount();
    if ($stmt->rowCount() > 0) {
      $rw = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($rw as $record) {
        $photos[] = $record;
      }
    }
    $result["photos"] = $photos;
    return $result;
  }


  function CreateTicketDemande($fiche, $compteur, $marque, $type_cpteur, $site_id)
  {
    /*	//EVITER DUPLICATE COMPTEUR
		$stmt = $this->connection->prepare('SELECT id_,num_compteur_actuel,ref_installation_actuel,ref_dernier_log_controle FROM t_main_data where num_compteur_actuel=:compteur');
		$stmt->bindValue(':compteur', $compteur);
		//$stmt->bindParam(1, $_GET['id'], PDO::PARAM_INT);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if(!$row)
		{
			//A AJOUTER VERIFIER SI LE NUMERO DU COMPTEUR EXISTE DANS LA TABLE DES COMPTEURS
			//echo 'nothing found';
		}else{
			$result["error"] = true;
            $result["message"] = "Le numéro de série du compteur (" . $compteur .") est déjà assigné à une autre installation.\n Veuillez bien vérifier le numéro saisi. ";
			return $result;
		}*/

    //RECUPERATION REF_LAST_INSTALL LOG AND REF_LAST_LOG_CONTROL
    $query = "SELECT id_,ref_installation_actuel,cvs_id,nom_client_blue,adresse_id,ref_dernier_log_controle FROM t_main_data where id_=:id_";
    $stmt = $this->connection->prepare($query);
    $stmt->bindValue(":id_", $fiche);
    $stmt->execute();
    $row_log = $stmt->fetch(PDO::FETCH_ASSOC);
    $cvs_  = $row_log['cvs_id'];
    $client_ = $row_log['nom_client_blue'];
    $adresse_ = $row_log['adresse_id'];
    ///////	

    //Generation Demande Ticket
    $ref_log = Utils::uniqUid('t_param_notification_log', "ref_log", $this->connection);
    $query = "INSERT INTO t_param_notification_log SET ref_log=:ref_log,ref_identif=:ref_identif,statuts_notification=:statuts_notification,type_notification=:type_notification,id_site=:id_site,n_user_create=:n_user_create,num_compteur=:num_compteur,datesys=:datesys,cvs_id=:cvs_id,nom_client=:nom_client,adresse_id=:adresse";
    $stmt = $this->connection->prepare($query);
    $stmt->bindValue(":datesys", date('Y-m-d H:i:s'));
    $stmt->bindValue(":ref_log", $ref_log);
    $stmt->bindValue(":ref_identif", $fiche);
    $stmt->bindValue(":statuts_notification", '0'); //(0)Non vu, (1) Vu		
    $stmt->bindValue(":type_notification", "4"); // (2) REMPLACEMENT COMPTEUR - (3)DEMANDE DE RE-LEGALISATION - (4) DEMANDE TICKET	
    $stmt->bindValue(":id_site", $site_id);
    $stmt->bindValue(":n_user_create", $this->n_user_create);
    $stmt->bindValue(":num_compteur", $compteur);
    //$stmt->bindValue(":ref_transaction", '');
    $stmt->bindValue(":cvs_id", $cvs_);
    $stmt->bindValue(":nom_client", $client_);
    $stmt->bindValue(":adresse", $adresse_);
    //$stmt->bindValue(":tarif", $this->tarif);	




    if ($stmt->execute()) {
      $result["error"] = 0;
      $result["message"] = "Demande Ticket envoyée avec succès";
    } else {
      $result["error"] = 1;
      $result["message"] = "L'opératon de la demande a échoué.";
    }
    return $result;
  }

  function GetDetail()
  {
    $query = "SELECT * FROM " . $this->table_name . " WHERE ref_log = ? 	LIMIT 0,1";
    $stmt = $this->connection->prepare($query);
    $this->ref_log = strip_tags($this->ref_log);
    $stmt->bindParam(1, $this->ref_log);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
  }


  function GetUserFilter($user_context)
  {
    $user_filtre =  '';
    /*if($this->type_notification == '4'){//demande ticket
	   $user_filtre = $user_context->GetUserFilterInstallation();
  }else  if($this->type_notification == '3'){//Demande relegalisation
	   $user_filtre = $user_context->GetUserFilterControl();
  }else  if($this->type_notification == '2'){//Demande Remplacement
	   $user_filtre = $user_context->GetUserFilterControl();
  }*/

    if ($user_context->id_service_group ==  '3' || $user_context->HasGlobalAccess()) {
      $user_filtre = "";
    } else if ($user_context->is_chief == '1') {
      $lst_user_chief = '';
      //$lst_user_chief= "'" . $user_context->code_utilisateur . "'";
      /*$stmt_chief = $user_context->GetCurrentUserListIdentificateurs($user_context->code_utilisateur,$user_context->id_organisme,$user_context->is_chief);
			$row_chief = $stmt_chief->fetchAll(PDO::FETCH_ASSOC);
			if(count($row_chief)>0){
				$lst_user_chief .= ",";
				foreach ($row_chief as $item) {
						$lst_user_chief .= "'" . $item["code_utilisateur"] . "',";
					}
			}*/
      $row_chief = $user_context->GenerateUserTree($user_context->code_utilisateur);
      if (count($row_chief) > 0) {
        foreach ($row_chief as $item) {
          $lst_user_chief .= "'" . $item . "',";
        }
      }
      $clean = rtrim($lst_user_chief, ",");
      $user_filtre = " and t_param_notification_log.n_user_create in (" . $clean . ")";
    } else {
      $user_filtre = " and t_param_notification_log.n_user_create='" . $user_context->code_utilisateur  . "'";
    }

    return $user_filtre;
  }
  function read()
  {

    $query = "SELECT ref_log,ref_identif,DATE_FORMAT(datesys,'%d/%m/%Y %H:%i:%S')  as datesys_fr,DATE_FORMAT(date_creation_ticket,'%d/%m/%Y %H:%i:%S')  as date_creation_ticket_fr,num_compteur,cvs_id,nom_client,adresse_id,commentaire,statuts_notification,n_user_vu,annule,n_user_annule,motif_annulation,date_synchro,is_sync,code_province,id_site,date_annule,activated,date_vu,type_notification,numero_ticket,date_creation_ticket,n_user_create_ticket,tarif,ref_transaction, from_control FROM " . $this->table_name . " where t_param_notification_log.type_notification='" . $this->type_notification . "' ORDER BY datesys " . $this->sort_type;
    $stmt = $this->connection->prepare($query);
    $stmt->execute();
    return $stmt;
  }
  function readAll($from_record_num, $records_per_page, $user_context, $filtre)
  {
    //->site_id
    $user_filtre = $this->GetUserFilter($user_context);
    $user_filtre .= $filtre;
    $query = "SELECT
      t_param_notification_log.ref_log,
      t_param_notification_log.ref_identif,
      DATE_FORMAT(t_param_notification_log.datesys,'%d/%m/%Y %H:%i:%S') AS datesys_fr,
      DATE_FORMAT(t_param_notification_log.date_creation_ticket,'%d/%m/%Y %H:%i:%S') AS date_creation_ticket_fr,
      t_param_notification_log.num_compteur,
      t_param_notification_log.cvs_id,
      t_param_notification_log.nom_client,
      t_param_notification_log.adresse_id,
      t_param_notification_log.commentaire,
      t_param_notification_log.statuts_notification,
      t_param_notification_log.n_user_vu,
      t_param_notification_log.annule,
      t_param_notification_log.n_user_annule,
      t_param_notification_log.motif_annulation,
      t_param_notification_log.date_synchro,
      t_param_notification_log.is_sync,
      t_param_notification_log.code_province,
      t_param_notification_log.id_site,
      t_param_notification_log.date_annule,
      t_param_notification_log.activated,
      t_param_notification_log.date_vu,
      t_param_notification_log.type_notification,
      t_param_notification_log.numero_ticket,
      t_param_notification_log.date_creation_ticket,
      t_param_notification_log.n_user_create_ticket,
      t_param_notification_log.tarif,
      t_param_notification_log.from_control,
      t_param_notification_log.ref_transaction,
      t_main_data.id_,
      t_param_cvs.libelle,
      t_main_data.p_a,
      t_param_notification_log.ref_transaction 
      FROM t_param_notification_log
        INNER JOIN t_main_data ON t_param_notification_log.ref_identif = t_main_data.id_ 
        INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id 
        INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id 
        INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` 
        INNER JOIN t_param_adresse_entity AS e_commune ON t_log_adresses.commune_id = e_commune.`code` 
        INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
        INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme 
        INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`  
      where t_param_notification_log.type_notification='" . $this->type_notification . "'  and t_param_notification_log.id_site=:id_site " . $user_filtre . " ORDER BY t_param_notification_log.datesys " . $this->sort_type . " LIMIT {$from_record_num}, {$records_per_page}";

    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':id_site', $user_context->site_id);
    $stmt->execute();
    return $stmt;
  }

  public function search($search_term, $from_record_num, $records_per_page, $user_context, $filtre)
  {

    $user_filtre = $this->GetUserFilter($user_context);
    $user_filtre .= $filtre;
    /* var_dump($user_filtre);
  exit;*/
    $query = "SELECT
t_param_notification_log.ref_log,
t_param_notification_log.ref_identif,
DATE_FORMAT(t_param_notification_log.datesys,'%d/%m/%Y %H:%i:%S') AS datesys_fr,
DATE_FORMAT(t_param_notification_log.date_creation_ticket,'%d/%m/%Y %H:%i:%S') AS date_creation_ticket_fr,
t_param_notification_log.num_compteur,
t_param_notification_log.cvs_id,
t_param_notification_log.nom_client,
t_param_notification_log.adresse_id,
t_param_notification_log.commentaire,
t_param_notification_log.statuts_notification,
t_param_notification_log.n_user_vu,
t_param_notification_log.annule,
t_param_notification_log.n_user_annule,
t_param_notification_log.motif_annulation,
t_param_notification_log.date_synchro,
t_param_notification_log.is_sync,
t_param_notification_log.code_province,
t_param_notification_log.id_site,
t_param_notification_log.date_annule,
t_param_notification_log.activated,
t_param_notification_log.date_vu,
t_param_notification_log.type_notification,
t_param_notification_log.numero_ticket,
t_param_notification_log.date_creation_ticket,
t_param_notification_log.n_user_create_ticket,
t_param_notification_log.from_control,
t_param_notification_log.tarif,
t_param_notification_log.ref_transaction,
t_main_data.id_,
t_param_cvs.libelle FROM t_param_notification_log
INNER JOIN t_main_data ON t_param_notification_log.ref_identif = t_main_data.id_ 
INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id 
INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id 
INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` 
INNER JOIN t_param_adresse_entity AS e_commune ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme 
INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`  
  WHERE (t_param_notification_log.num_compteur LIKE :search_term OR t_param_notification_log.nom_client LIKE :search_term) and t_param_notification_log.type_notification='" . $this->type_notification . "' " . $user_filtre . " and t_param_notification_log.id_site=:id_site ORDER BY t_param_notification_log.datesys " . $this->sort_type . "  LIMIT :from, :offset";
    $stmt = $this->connection->prepare($query);
    $search_term = "%{$search_term}%";
    $stmt->bindParam(':id_site', $user_context->site_id);
    $stmt->bindParam(':search_term', $search_term);
    $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt;
  }
  public function countAll($user_context, $filtre)
  {

    $user_filtre = $this->GetUserFilter($user_context);
    $user_filtre .= $filtre;

    $query = "SELECT ref_log FROM t_param_notification_log
    
INNER JOIN t_main_data ON t_param_notification_log.ref_identif = t_main_data.id_ 
INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id 
INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id 
INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` 
INNER JOIN t_param_adresse_entity AS e_commune ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme 
INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` where t_param_notification_log.type_notification='" . $this->type_notification . "'  and t_param_notification_log.id_site=:id_site  " . $user_filtre;
    $stmt = $this->connection->prepare($query);
    $stmt->bindParam(':id_site', $user_context->site_id);
    $stmt->execute();
    $num = $stmt->rowCount();
    return $num;
  }
  public function countAll_BySearch($search_term, $user_context, $filtre)
  {

    $user_filtre = $this->GetUserFilter($user_context);
    $user_filtre .= $filtre;

    $query = "SELECT COUNT(*) as total_rows FROM  t_param_notification_log
INNER JOIN t_main_data ON t_param_notification_log.ref_identif = t_main_data.id_ 
INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id 
INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id 
INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` 
INNER JOIN t_param_adresse_entity AS e_commune ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme 
INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` 
 WHERE t_param_notification_log.num_compteur LIKE :search_term and t_param_notification_log.type_notification='" . $this->type_notification . "'  and t_param_notification_log.id_site=:id_site  " . $user_filtre;
    $stmt = $this->connection->prepare($query);
    $search_term = "%{$search_term}%";
    $stmt->bindParam(":search_term", $search_term);
    $stmt->bindParam(':id_site', $user_context->site_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row["total_rows"];
  }
}
