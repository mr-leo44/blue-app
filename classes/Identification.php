<?php

class Identification
{

    // database connection and table name
    private $connection;
    private $is_valid = 0;
    //private $table_name = "t_log_identification"; 
    private $table_name = "t_main_data";
    public $id_;
    public $date_identification;
    public $date_debut_identification;
    public $p_a;
    public $code_identificateur;
    public $gps_longitude;
    public $gps_latitude;
    public $nom_remplacant;
    public $phone_remplacant;
    public $nom_client_blue;
    public $phone_client_blue;
    public $photo_compteur;
    public $nbre_branchement;
    public $signature_electronique;
    public $section_cable;
    public $materiels;
    public $cvs_id;
    public $lst_materiels;
    public $site_id;
    public $numero_piece_identity;
    public $accessibility_client;
    public $tarif_identif;
    public $infos_supplementaires;
    public $nbre_menage_a_connecter;
    public $numero_depart;
    public $numero_poteau_identif;
    public $type_raccordement_identif;
    public $type_compteur;
    public $type_construction;
    public $nbre_appartement;
    public $nbre_habitant;
    public $type_activites;
    public $conformites_installation;
    public $avis_technique_blue;
    public $avis_occupant;
    public $chef_equipe;
    public $titre_responsable;
    public $titre_remplacant;

    public $statut_client;
    public $statut_occupant;
    public $nom_occupant_trouver;
    public $phone_occupant_trouver;
    public $type_raccordement_propose;
    public $nature_activity;
    public $type_client;
    public $consommateur_gerer;
    public $id_organisme_allouer;
    public $cabine_id;
    public $index_consommation;
    public $identificateur;
    public $n_user_create;
    public $n_user_update;
    public $id_equipe_identification;


    public $adresse_id;
    public $ville_id;
    public $commune_id;
    public $quartier_id;
    public $avenue;
    public $numero;
    public $datesys;
    public $is_draft;
    public $presence_inversor;
    public $client_id;
    public $occupant_id;
    public $reference_appartement;

    public $motif_annulation;
    private $cache;


    public function __construct($db)
    {
        $this->connection = $db;
    }


    function GetGPSCoordinates($fiche)
    {
        $stmt = $this->connection->prepare('SELECT id_,num_compteur_actuel,ref_installation_actuel,ref_dernier_log_controle,gps_longitude,gps_latitude FROM t_main_data where id_=:fiche');
        $stmt->bindValue(':fiche', $fiche);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            //NUMERO FICHE INTROUVABLE
            $result["error"] = true;
            $result["message"] = "Le référence de la fiche n'est pas valide";
        } else {

            if (empty($row["gps_longitude"]) || empty($row["gps_latitude"])) {
                $result["error"] = true;
                $result["message"] = "Aucune coordonnée GPS trouvée";
            } else {
                $result["error"] = false;
                $result["gps_longitude"] = $row["gps_longitude"];
                $result["gps_latitude"] = $row["gps_latitude"];
            }
        }
        return $result;
    }

    function GetCvsCompteur($id_cvs_)
    {
        $query = $query = "SELECT t_main_data.id_,
        t_main_data.gps_longitude,
        t_main_data.gps_latitude,
        t_main_data.p_a,
        Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,
        t_main_data.num_compteur_actuel,
        t_main_data.adresse_id,
        t_main_data.cvs_id,t_main_data.code_identificateur,
        t_main_data.etat_compteur,
        t_param_cvs.libelle FROM t_main_data INNER JOIN t_param_cvs ON t_main_data.cvs_id = t_param_cvs.`code`  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id 	WHERE t_main_data.cvs_id =:cvs_id and t_main_data.annule=" . $this->is_valid;
        $stmt = $this->connection->prepare($query);
        $id_cvs_ = (strip_tags($id_cvs_));
        $stmt->bindParam(":cvs_id", $id_cvs_);
        $stmt->execute();
        $adress_item = new  AdresseEntity($this->connection);
        $result = array();
        $item = array();
        $result['items'] = array();
        $row_ = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($row_) > 0) {
            foreach ($row_ as $vl) {
                $item['data'] = $vl;
                $item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
                $result['items'][] = $item;
            }
        }
        $result["error"] = 0;

        return $result;
    }


    public function GetCvsCompteurForInstallSearch($csv_id, $user_context, $search_term)
    {
        $user_filtre = $this->GetUserFilterSearch($user_context);
        $result = array();
        $item = array();
        $result['items'] = array();
        $cvs_query = '';
        if (isset($csv_id) && strlen(trim($csv_id)) > 0) {
            $cvs_query = '  and t_main_data.cvs_id=:cvs_id  ';
        }
        $adress_item = new  AdresseEntity($this->connection);
        $query = "";
        $search_term = trim($search_term);
        if ($search_term != '') {
            $query = "SELECT t_main_data.id_,
        t_main_data.gps_longitude,
        t_main_data.gps_latitude,
        t_main_data.p_a,t_main_data.reference_appartement,
        Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue,
        t_main_data.num_compteur_actuel,
        t_main_data.adresse_id,
        t_main_data.cvs_id,t_main_data.identificateur,
        t_main_data.etat_compteur,
        t_param_cvs.libelle  FROM t_main_data INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
        INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
        INNER JOIN t_utilisateurs  as t_identificateur ON t_main_data.identificateur = t_identificateur.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_main_data.chef_equipe = t_chef_equipe.code_utilisateur  INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme 
        INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`
        INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id 
        WHERE (t_main_data.p_a Like :search_term or Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) Like :search_term or identite_client.phone_number Like :search_term or t_main_data.num_compteur_actuel Like :search_term or t_main_data.id_ Like :search_term or e_avenue.libelle Like :search_term or e_quartier.libelle Like :search_term or e_commune.libelle Like :search_term or e_ville.libelle Like :search_term OR t_log_adresses.numero Like :search_term Or t_identificateur.nom_complet LIKE :search_term) and t_main_data.annule=" . $this->is_valid . "  and t_main_data.is_draft='0'  and t_main_data.est_installer='0' and t_main_data.deja_assigner=0 " . $user_filtre . $cvs_query;
                    // echo ($query);
                } else {
                    $query = "SELECT t_main_data.id_,
        t_main_data.gps_longitude,
        t_main_data.gps_latitude,
        t_main_data.p_a,t_main_data.reference_appartement,
        Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue,
        t_main_data.num_compteur_actuel,
        t_main_data.adresse_id,
        t_main_data.cvs_id,t_main_data.identificateur,
        t_main_data.etat_compteur,
        t_param_cvs.libelle  FROM t_main_data INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
        INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
        INNER JOIN t_utilisateurs ON t_main_data.identificateur = t_utilisateurs.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_main_data.chef_equipe = t_chef_equipe.code_utilisateur  INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme 
        INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`
        INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id 
        
        WHERE t_main_data.deja_assigner=0 and t_main_data.is_draft='0' and t_main_data.est_installer='0' and t_main_data.annule=" . $this->is_valid . $user_filtre . $cvs_query;
            // WHERE   t_main_data.annule=" . $this->is_valid . "  and t_param_assignation.is_valid='0' and t_main_data.cvs_id=:cvs_id  and t_main_data.is_draft='0'  and t_main_data.est_installer='0' and t_main_data.deja_assigner=0 "; 
            // 99c5d008e5c71470ff1d7dd132da9b0e
            // 114222021
        }
        //ORDER BY t_main_data.date_identification";
        // desc LIMIT :from, :offset";
        $stmt = $this->connection->prepare($query);
        $search_term = trim($search_term);
        if ($search_term != '') {
            $search_term = "%{$search_term}%";
            $stmt->bindParam(':search_term', $search_term);
        }
        if (isset($csv_id) && strlen($csv_id) > 0) {
            $stmt->bindParam(':cvs_id', $csv_id);
        }
        // $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
        // $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
        // $stmt->bindValue(":ref_site_identif", $user_context->site_id);
        $stmt->execute();
        $query_avoid = "select id_fiche_identif from t_param_assignation where id_fiche_identif=:fiche_id and is_valid='1'";
        $stmt_avoid = $this->connection->prepare($query_avoid);

        $row_ = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($row_) > 0) {
            foreach ($row_ as $vl) {
                //EVITER LES COMPTEURS DEJA ASSIGNES
                //EVITER LES COMPTEURS DEJA ASSIGNES	
                $stmt_avoid->bindValue(":fiche_id", $vl['id_']);
                $stmt_avoid->execute();
                $row_avoid = $stmt_avoid->fetch(PDO::FETCH_ASSOC);
                if (!$row_avoid) {
                    $item['data'] = $vl;
                    $item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
                    $item['data']['nom_complet_identificateur'] = $user_context->GetUserDetailName($vl['identificateur']);
                    $result['items'][] = $item;
                }



                /*
					$item['data']= $vl;
					$item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
					$result['items'][]= $item;*/
            }
        }
        $result["error"] = 0;



        return $result;
    }



    function GetCvsCompteurForInstall($id_cvs_, $user_context, $search_param)
    {
        $query = $query = "SELECT t_main_data.id_,
t_main_data.gps_longitude,
t_main_data.gps_latitude,
t_main_data.p_a,t_main_data.reference_appartement,
 Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue,
t_main_data.num_compteur_actuel,
t_main_data.adresse_id,
t_main_data.cvs_id,t_main_data.identificateur,
t_main_data.etat_compteur,
t_param_cvs.libelle FROM t_main_data INNER JOIN t_param_cvs ON t_main_data.cvs_id = t_param_cvs.`code`  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id WHERE t_main_data.cvs_id =:cvs_id and t_main_data.is_draft='0' and t_main_data.est_installer='0' and t_main_data.annule=" . $this->is_valid;
        $stmt = $this->connection->prepare($query);
        $id_cvs_ = (strip_tags($id_cvs_));
        $stmt->bindParam(":cvs_id", $id_cvs_);
        $stmt->execute();

        $query_avoid = "select id_fiche_identif from t_param_assignation where id_fiche_identif=:fiche_id and is_valid='1'";
        $stmt_avoid = $this->connection->prepare($query_avoid);

        $adress_item = new  AdresseEntity($this->connection);
        $result = array();
        $item = array();
        $result['items'] = array();
        $row_ = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($row_) > 0) {
            foreach ($row_ as $vl) {
                //EVITER LES COMPTEURS DEJA ASSIGNES	
                $stmt_avoid->bindValue(":fiche_id", $vl['id_']);
                $stmt_avoid->execute();
                $row_avoid = $stmt_avoid->fetch(PDO::FETCH_ASSOC);
                if (!$row_avoid) {
                    $item['data'] = $vl;
                    $item['data']['nom_complet_identificateur'] = $user_context->GetUserDetailName($vl['identificateur']);
                    $item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
                    $result['items'][] = $item;
                }
            }
        }
        $result["error"] = 0;

        return $result;
    }

    function GetCvsCompteurForControle($id_cvs_, $filtre, $jour)
    {
        $user_filtre = "";
        if ($filtre == "t_main_data.ref_dernier_log_controle IS NULL") {
            $user_filtre = " and t_main_data.ref_dernier_log_controle IS NULL";
        } else if ($filtre == "t_main_data.ref_dernier_log_controle IS NOT NULL") {
            $user_filtre = " and t_main_data.ref_dernier_log_controle IS NOT NULL";
        } else if ($filtre == ">") {
            $user_filtre = " and (datediff(now(),t_main_data.date_dernier_controle)) >= " . $jour;
        } else if ($filtre == "<") {
            $user_filtre = " and (datediff(now(),t_main_data.date_dernier_controle)) <= " . $jour;
        }

        $query = $query = "SELECT t_main_data.id_,coalesce((datediff(now(),t_main_data.date_dernier_controle)),'-') AS jour_passer_dernier_controle,coalesce(DATE_FORMAT(t_main_data.date_dernier_controle,'%d/%m/%Y %H:%i:%S'),'-')  as date_dernier_controle_fr,
t_main_data.gps_longitude,
t_main_data.gps_latitude,
t_main_data.p_a,t_main_data.reference_appartement,
 Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue,
t_main_data.num_compteur_actuel,
t_main_data.adresse_id,
t_main_data.cvs_id, 
t_main_data.etat_compteur,
t_param_cvs.libelle FROM t_main_data INNER JOIN t_param_cvs ON t_main_data.cvs_id = t_param_cvs.`code`  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id 	WHERE t_main_data.cvs_id =:cvs_id and t_main_data.est_installer='1' and t_main_data.deja_assigner=0  and t_main_data.annule=" . $this->is_valid . $user_filtre;
        $stmt = $this->connection->prepare($query);
        $id_cvs_ = (strip_tags($id_cvs_));
        $stmt->bindParam(":cvs_id", $id_cvs_);
        // $stmt->bindParam(":filtre",$filtre); 
        // $stmt->bindParam(":jour",$jour); 
        $stmt->execute();

        $query_avoid = "select id_fiche_identif from t_param_assignation where id_fiche_identif=:fiche_id and is_valid='1'";
        $stmt_avoid = $this->connection->prepare($query_avoid);


        $adress_item = new  AdresseEntity($this->connection);
        $result = array();
        $item = array();
        $result['items'] = array();
        $row_ = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($row_) > 0) {
            foreach ($row_ as $vl) {
                //EVITER LES COMPTEURS DEJA ASSIGNES	
                $stmt_avoid->bindValue(":fiche_id", $vl['id_']);
                $stmt_avoid->execute();
                $row_avoid = $stmt_avoid->fetch(PDO::FETCH_ASSOC);
                if (!$row_avoid) {
                    $item['data'] = $vl;
                    $item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
                    $result['items'][] = $item;
                }
                /*
					$item['data']= $vl;
					$item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
					$result['items'][]= $item;*/
            }
        }
        $result["error"] = 0;



        return $result;
    }


    public function GetCvsCompteurForControlSearch($csv_id, $search_term, $filtre, $jour, $user_context)
    {
        //$user_filtre = $this->GetUserFilter($user_context);
        $result = array();
        $item = array();
        $search_term = trim($search_term);
        $items_deja_asign = array();
        $result['items'] = array();
        $user_filtre = "";
        $jour = trim($jour);
        $cvs_query = "";
        $csv_id = trim($csv_id);
        if ($csv_id != "") {
            $cvs_query = " and t_main_data.cvs_id=:cvs_id ";
        }
        if ($filtre == "t_main_data.ref_dernier_log_controle IS NULL") {
            $user_filtre = " and t_main_data.ref_dernier_log_controle IS NULL";
        } else if ($filtre == "t_main_data.ref_dernier_log_controle IS NOT NULL") {
            $user_filtre = " and t_main_data.ref_dernier_log_controle IS NOT NULL";
        } else if ($filtre == ">") {
            if ($jour != "") {
                $user_filtre = " and (datediff(now(),t_main_data.date_dernier_controle)) >= " . $jour;
            }
        } else if ($filtre == "<") {
            if ($jour != "") {
                $user_filtre = " and (datediff(now(),t_main_data.date_dernier_controle)) <= " . $jour;
            }
        }

        $user_filtre .= $this->GetUserFilterSearch($user_context);

        $adress_item = new  AdresseEntity($this->connection);
        /*  $query = "SELECT t_main_data.id_,
t_main_data.gps_longitude,
t_main_data.gps_latitude,t_main_data.client_id,
t_main_data.p_a,t_main_data.reference_appartement,
 Concat(identite_client.nom,' ',identite_client.postnom,' ',identite_client.prenom) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue,
t_main_data.num_compteur_actuel,
t_main_data.adresse_id,
t_main_data.cvs_id, 
t_main_data.etat_compteur,
 DATE_FORMAT(date_identification,'%d/%m/%Y %H:%i:%S') AS date_fr,
t_main_data.date_identification,t_main_data.reference_appartement, 
 DATE_FORMAT(date_installation_actuel,'%d/%m/%Y %H:%i:%S') AS date_installation_actuel_fr,t_param_cvs.libelle,e_quartier.libelle as quartier,e_commune.libelle as commune,t_utilisateurs.nom_utilisateur,t_utilisateurs.nom_complet,t_chef_equipe.nom_complet as nom_chef_equipe,t_utilisateurs.code_utilisateur,t_param_organisme.denomination,t_main_data.is_draft, Concat(identite_client.nom,' ',identite_client.postnom,' ',identite_client.prenom) as nom_client_blue,identite_client.phone_number as phone_client_blue*/

        $query = "";
        if ($search_term == "") {
            $query = "SELECT t_main_data.id_,coalesce((datediff(now(),t_main_data.date_dernier_controle)),'-') AS jour_passer_dernier_controle,coalesce(DATE_FORMAT(t_main_data.date_dernier_controle,'%d/%m/%Y %H:%i:%S'),'-')  as date_dernier_controle_fr,
t_main_data.gps_longitude,
t_main_data.gps_latitude,
t_main_data.p_a,t_main_data.reference_appartement,
 Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue,
t_main_data.num_compteur_actuel,
t_main_data.adresse_id,
t_main_data.cvs_id, 
t_main_data.etat_compteur,
t_param_cvs.libelle FROM t_main_data INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs ON t_main_data.identificateur = t_utilisateurs.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_main_data.chef_equipe = t_chef_equipe.code_utilisateur  INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme 
INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`
INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id 
   WHERE  t_main_data.annule=" . $this->is_valid . $cvs_query . " and t_main_data.est_installer='1' " . $user_filtre;
            // and t_param_assignation.is_valid='0'
        } else {
            $query = "SELECT t_main_data.id_,coalesce((datediff(now(),t_main_data.date_dernier_controle)),'-') AS jour_passer_dernier_controle,coalesce(DATE_FORMAT(t_main_data.date_dernier_controle,'%d/%m/%Y %H:%i:%S'),'-')  as date_dernier_controle_fr,
t_main_data.gps_longitude,
t_main_data.gps_latitude,
t_main_data.p_a,t_main_data.reference_appartement,
 Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue,
t_main_data.num_compteur_actuel,
t_main_data.adresse_id,
t_main_data.cvs_id, 
t_main_data.etat_compteur,
t_param_cvs.libelle FROM t_main_data INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs ON t_main_data.identificateur = t_utilisateurs.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_main_data.chef_equipe = t_chef_equipe.code_utilisateur  INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme 
INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`
INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id 
   WHERE (((t_main_data.p_a Like :search_term or Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) Like :search_term or identite_client.phone_number Like :search_term or t_main_data.num_compteur_actuel Like :search_term or t_main_data.id_ Like :search_term or e_avenue.libelle Like :search_term or e_quartier.libelle Like :search_term or e_commune.libelle Like :search_term or e_ville.libelle Like :search_term OR t_log_adresses.numero Like :search_term) and t_main_data.annule=" . $this->is_valid . $cvs_query   . " and t_main_data.est_installer='1' ) OR t_main_data.num_compteur_actuel Like :search_term) " . $user_filtre;
            // and t_param_assignation.is_valid='0'
        }
        //ORDER BY t_main_data.date_identification";
        // desc LIMIT :from, :offset";

        // echo ($query);
        // echo ($query);
        // exit;
        $stmt = $this->connection->prepare($query);


        $query_avoid = "select id_fiche_identif from t_param_assignation where id_fiche_identif=:fiche_id and is_valid='1'";
        $stmt_avoid = $this->connection->prepare($query_avoid);

        if ($search_term != "") {
            $search_term = "%{$search_term}%";
            $stmt->bindParam(':search_term', $search_term);
        }
        if ($csv_id != "") {
            $stmt->bindParam(':cvs_id', $csv_id);
        }
        // $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
        // $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
        // $stmt->bindValue(":ref_site_identif", $user_context->site_id);

        // echo ($query);
        // exit;
        $stmt->execute();
        $row_ = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($row_) > 0) {
            foreach ($row_ as $vl) {
                //EVITER LES COMPTEURS DEJA ASSIGNES

                //EVITER LES COMPTEURS DEJA ASSIGNES	
                $stmt_avoid->bindValue(":fiche_id", $vl['id_']);
                $stmt_avoid->execute();
                $row_avoid = $stmt_avoid->fetch(PDO::FETCH_ASSOC);
                if (!$row_avoid) {
                    $item['data'] = $vl;
                    $item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
                    $result['items'][] = $item;
                } else {
                    $items_deja_asign[] = $vl['num_compteur_actuel'];
                }


                /*
					$item['data']= $vl;
					$item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
					$result['items'][]= $item;*/
            }
        }
        $result["error"] = 0;
        $result["deja_assigner_count"] = count($items_deja_asign);


        return $result;
    }



    function GetCvsCompteurForReplace($id_cvs_)
    {
        $query = $query = "SELECT t_main_data.id_,
t_main_data.gps_longitude,
t_main_data.gps_latitude,
t_main_data.p_a,t_main_data.reference_appartement,
 Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue,
t_main_data.num_compteur_actuel,
t_main_data.adresse_id,
t_main_data.cvs_id, 
t_main_data.etat_compteur,
t_param_cvs.libelle FROM t_main_data INNER JOIN t_param_cvs ON t_main_data.cvs_id = t_param_cvs.`code`  INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id 	WHERE t_main_data.cvs_id =:cvs_id and t_main_data.est_installer='1' and t_main_data.deja_assigner=0  and t_main_data.annule=" . $this->is_valid;
        $stmt = $this->connection->prepare($query);
        $id_cvs_ = (strip_tags($id_cvs_));
        $stmt->bindParam(":cvs_id", $id_cvs_);
        $stmt->execute();

        $query_avoid = "select id_fiche_identif from t_param_assignation where id_fiche_identif=:fiche_id and is_valid='1'";
        $stmt_avoid = $this->connection->prepare($query_avoid);


        $adress_item = new  AdresseEntity($this->connection);
        $result = array();
        $item = array();
        $result['items'] = array();
        $row_ = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($row_) > 0) {
            foreach ($row_ as $vl) {
                //EVITER LES COMPTEURS DEJA ASSIGNES	
                $stmt_avoid->bindValue(":fiche_id", $vl['id_']);
                $stmt_avoid->execute();
                $row_avoid = $stmt_avoid->fetch(PDO::FETCH_ASSOC);
                if (!$row_avoid) {
                    $item['data'] = $vl;
                    $item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
                    $result['items'][] = $item;
                }
                /*
					$item['data']= $vl;
					$item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
					$result['items'][]= $item;*/
            }
        }
        $result["error"] = 0;



        return $result;
    }

    // public function GetCvsCompteurForReplaceSearch($csv_id, $search_term, $from_record_num, $records_per_page) {
    public function GetCvsCompteurForReplaceSearch($csv_id, $search_term, $user_context)
    {
        $user_filtre = $this->GetUserFilterSearch($user_context);
        $result = array();
        $item = array();
        $items_deja_asign = array();
        $result['items'] = array();
        $cvs_query = '';
        if (isset($csv_id) && strlen(trim($csv_id)) > 0) {
            $cvs_query = '  and t_main_data.cvs_id=:cvs_id  ';
        }

        $adress_item = new  AdresseEntity($this->connection);
        $query = "";
        $len_search_term = strlen(trim($search_term));
        // var_dump($search_term);
        if ($len_search_term == 0) {
            $query = "SELECT t_main_data.id_,
t_main_data.gps_longitude,
t_main_data.gps_latitude,t_main_data.client_id,
t_main_data.p_a,t_main_data.reference_appartement,
 Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue,
t_main_data.num_compteur_actuel,
t_main_data.adresse_id,
t_main_data.cvs_id, 
t_main_data.etat_compteur,
 DATE_FORMAT(date_identification,'%d/%m/%Y %H:%i:%S') AS date_fr,
t_main_data.date_identification,t_main_data.reference_appartement, 
 DATE_FORMAT(date_installation_actuel,'%d/%m/%Y %H:%i:%S') AS date_installation_actuel_fr,t_param_cvs.libelle,e_quartier.libelle as quartier,e_commune.libelle as commune,t_utilisateurs.nom_utilisateur,t_utilisateurs.nom_complet,t_chef_equipe.nom_complet as nom_chef_equipe,t_utilisateurs.code_utilisateur,t_param_organisme.denomination,t_main_data.is_draft  FROM t_main_data INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs ON t_main_data.identificateur = t_utilisateurs.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_main_data.chef_equipe = t_chef_equipe.code_utilisateur  INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme 
INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`
INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id     WHERE t_main_data.annule=" . $this->is_valid . $cvs_query . " and t_main_data.est_installer='1' " . $user_filtre;
            // and t_main_data.deja_assigner=0 ORDER BY t_main_data.date_identification";
            // desc LIMIT :from, :offset";

        } else {
            $query = "SELECT t_main_data.id_,
t_main_data.gps_longitude,
t_main_data.gps_latitude,t_main_data.client_id,
t_main_data.p_a,t_main_data.reference_appartement,
 Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue,
t_main_data.num_compteur_actuel,
t_main_data.adresse_id,
t_main_data.cvs_id, 
t_main_data.etat_compteur,
 DATE_FORMAT(date_identification,'%d/%m/%Y %H:%i:%S') AS date_fr,
t_main_data.date_identification,t_main_data.reference_appartement, 
 DATE_FORMAT(date_installation_actuel,'%d/%m/%Y %H:%i:%S') AS date_installation_actuel_fr,t_param_cvs.libelle,e_quartier.libelle as quartier,e_commune.libelle as commune,t_utilisateurs.nom_utilisateur,t_utilisateurs.nom_complet,t_chef_equipe.nom_complet as nom_chef_equipe,t_utilisateurs.code_utilisateur,t_param_organisme.denomination,t_main_data.is_draft  FROM t_main_data INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs ON t_main_data.identificateur = t_utilisateurs.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_main_data.chef_equipe = t_chef_equipe.code_utilisateur  INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme 
INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`
INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id     WHERE (t_main_data.p_a Like :search_term or Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) Like :search_term or coalesce(identite_client.phone_number,'') Like :search_term or coalesce(t_main_data.num_compteur_actuel,'') Like :search_term or t_main_data.id_ Like :search_term or e_avenue.libelle Like :search_term or e_quartier.libelle Like :search_term or e_commune.libelle Like :search_term or e_ville.libelle Like :search_term OR t_log_adresses.numero Like :search_term OR t_utilisateurs.nom_complet Like :search_term) and t_main_data.annule=" . $this->is_valid . $cvs_query . "  and t_main_data.est_installer='1' " . $user_filtre;
            // and t_main_data.deja_assigner=0 ORDER BY t_main_data.date_identification";
            // desc LIMIT :from, :offset";

        }
        // echo $query;
        // exit;
        $stmt = $this->connection->prepare($query);
        $search_term = "%{$search_term}%";
        // $stmt->bindParam(':search_term', $search_term);


        if ($len_search_term > 0) {
            $search_term = "%{$search_term}%";
            $stmt->bindParam(':search_term', $search_term);
        }
        // $stmt->bindParam(':cvs_id', $csv_id);
        if (isset($csv_id) && strlen(trim($csv_id)) > 0) {
            $stmt->bindParam(':cvs_id', $csv_id);
        }

        // $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
        // $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
        // $stmt->bindValue(":ref_site_identif", $user_context->site_id);
        $stmt->execute();
        $row_ = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $query_avoid = "select id_fiche_identif from t_param_assignation where id_fiche_identif=:fiche_id and is_valid='1'";
        $stmt_avoid = $this->connection->prepare($query_avoid);

        if (count($row_) > 0) {
            foreach ($row_ as $vl) {
                //EVITER LES COMPTEURS DEJA ASSIGNES	
                $stmt_avoid->bindValue(":fiche_id", $vl['id_']);
                $stmt_avoid->execute();
                $row_avoid = $stmt_avoid->fetch(PDO::FETCH_ASSOC);
                if (!$row_avoid) {
                    $item['data'] = $vl;
                    $item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
                    $result['items'][] = $item;
                } else {
                    $items_deja_asign[] = $vl['num_compteur_actuel'];
                }
                //EVITER LES COMPTEURS DEJA ASSIGNES
                /*
					$item['data']= $vl;
					$item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
					$result['items'][]= $item;*/
            }
        }
        $result["error"] = 0;
        $result["deja_assigner_count"] = count($items_deja_asign);
        $result["items_count"] = count($result['items']);
        return $result;
    }



    /*function GetCvsCompteurForReplaceSearch($csv_id, $search_term, $from_record_num, $records_per_page) {
		$user_filtre = $this->GetUserFilter($user_context);
		
        $query = "SELECT t_main_data.id_,DATE_FORMAT(date_identification,'%d/%m/%Y %H:%i:%S') AS date_fr,
t_main_data.date_identification,t_main_data.reference_appartement,t_main_data.date_debut_identification,DATE_FORMAT(date_debut_identification,'%d/%m/%Y %H:%i:%S') AS date_deb_fr,
t_main_data.p_a,t_main_data.nbre_appartement,t_main_data.code_identificateur,
t_main_data.gps_longitude,t_main_data.gps_latitude,
t_main_data.client_id,t_main_data.occupant_id,
t_main_data.photo_compteur,t_main_data.nbre_branchement,
t_main_data.signature_electronique,t_main_data.section_cable,
t_main_data.date_update,t_main_data.n_user_update,
t_main_data.photo_pa_avant,t_main_data.cvs_id,
t_main_data.num_compteur_actuel,DATE_FORMAT(date_installation_actuel,'%d/%m/%Y %H:%i:%S') AS date_installation_actuel_fr,
t_main_data.adresse_id,t_main_data.tarif_identif,
t_main_data.est_installer,t_main_data.id_equipe_identification,
t_main_data.identificateur,t_main_data.chef_equipe,t_param_cvs.libelle,e_quartier.libelle as quartier,e_commune.libelle as commune,t_utilisateurs.nom_utilisateur,t_utilisateurs.nom_complet,t_chef_equipe.nom_complet as nom_chef_equipe,t_utilisateurs.code_utilisateur,t_param_organisme.denomination,t_main_data.is_draft, Concat(identite_client.nom,' ',identite_client.postnom,' ',identite_client.prenom) as nom_client_blue,identite_client.phone_number as phone_client_blue  FROM t_main_data INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
INNER JOIN t_utilisateurs ON t_main_data.identificateur = t_utilisateurs.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_main_data.chef_equipe = t_chef_equipe.code_utilisateur  INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme 
INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`
INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id 
   WHERE (t_main_data.p_a Like :search_term or Concat(identite_client.nom,' ',identite_client.postnom,' ',identite_client.prenom) Like :search_term or identite_client.phone_number Like :search_term or t_main_data.num_compteur_actuel Like :search_term or t_main_data.id_ Like :search_term or e_avenue.libelle Like :search_term or e_quartier.libelle Like :search_term or e_commune.libelle Like :search_term or e_ville.libelle Like :search_term OR t_utilisateurs.nom_complet Like :search_term  OR t_chef_equipe.nom_complet Like :search_term OR DATE_FORMAT(date_identification,'%d/%m/%Y %H:%i:%S')  Like :search_term OR t_param_organisme.denomination  Like :search_term OR t_log_adresses.numero Like :search_term) and  t_main_data.ref_site_identif=:ref_site_identif " . $filtre  . $user_filtre . "  and t_main_data.annule=" . $this->is_valid . " ORDER BY t_main_data.date_identification desc LIMIT :from, :offset";
        $stmt = $this->connection->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(':search_term', $search_term);
        $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
        $stmt->bindValue(":ref_site_identif", $user_context->site_id);
        $stmt->execute();
        return $stmt;
    }*/


    function GetUserFilter($user_context)
    {
        $user_filtre = "";
        if ($user_context->id_service_group ==  '3' || $user_context->HasGlobalAccess()) {
            $user_filtre = "";
        } else if ($user_context->is_chief == '1') {
            $lst_user_chief = '';
            /*$lst_user_chief= "'" . $user_context->code_utilisateur . "'";
			$stmt_chief = $user_context->GetCurrentUserListIdentificateurs($user_context->code_utilisateur,$user_context->id_organisme,$user_context->is_chief);
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
            $user_filtre = " and identificateur in (" . $clean . ")";
        } else {
            $user_filtre = " and identificateur='" . $user_context->code_utilisateur  . "'";
        }
        return $user_filtre;
    }

    function GetUserFilterSearch($user_context)
    {
        $user_filtre = "";
        if ($user_context->id_service_group ==  '3' || $user_context->HasGlobalAccess()) {
            $user_filtre = "";
        } else if ($user_context->is_chief == '1') {
            $lst_user_chief = '';
            $row_chief = $user_context->GenerateUserTree($user_context->code_utilisateur);
            if (count($row_chief) > 0) {
                foreach ($row_chief as $item) {
                    $lst_user_chief .= "'" . $item . "',";
                }
            }
            $lst_user_chief .= "'non',"; //Compteur migré
            $clean = rtrim($lst_user_chief, ",");
            $user_filtre = " and identificateur in (" . $clean . ")";
        } else {
            $user_filtre = " and identificateur='" . $user_context->code_utilisateur  . "'";
        }
        return $user_filtre;
    }


    function CreatePhoto($ref_photo)
    {
        //valide
        $query = "INSERT INTO t_main_data_gallery SET ref_photo=:ref_photo,ref_fiche=:ref_fiche,category_pic=:category_pic,n_user_create=:n_user_create,datesys=:datesys";
        $stmt = $this->connection->prepare($query);

        //$valide=strip_tags($this->code_site);


        $stmt->bindValue(":ref_photo", $ref_photo);
        $stmt->bindValue(":ref_fiche", $this->id_);
        $stmt->bindValue(":category_pic", "5");
        $stmt->bindValue(":n_user_create", $this->n_user_create);
        $stmt->bindValue(":datesys", $this->datesys);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function SaveMateriels_Identif($identif, $materiels)
    {
        $datesys = date("Y-m-d H:i:s");
        if (!is_array($materiels)) {
            return;
        }
        $stmt = $this->connection->prepare("DELETE FROM t_log_identification_materiels WHERE ref_identification=:ref_identification");
        $stmt->bindValue(':ref_identification', $identif);
        $stmt->execute();

        $query = "INSERT INTO t_log_identification_materiels (id_mat,ref_article,ref_identification,qte_identification,datesys) values (:id_mat,:ref_article,:ref_identification,:qte_identification,:datesys)";
        $stmt = $this->connection->prepare($query);
        foreach ($materiels as $value) {
            $id_mat = $this->uniqUid("t_log_identification_materiels", "id_mat");
            $stmt->bindValue(':id_mat', $id_mat);
            $stmt->bindValue(':ref_article', $value->libelle);
            $stmt->bindValue(':ref_identification', $identif);
            $stmt->bindValue(':qte_identification', $value->qte);
            $stmt->bindValue(':datesys', $datesys);
            $stmt->execute();
        }
        return true;
    }

    function Modifier()
    {

        /* $this->nom_client_blue=(strip_tags($this->nom_client_blue));

          $query = "select id_,nom_client_blue from  " . $this->table_name . " where nom_client_blue=:nom_client_blue";
          $stmt = $this->connection->prepare($query);
          $stmt->bindValue(":nom_client_blue", $this->nom_client_blue);
          $stmt->execute();
          $row = $stmt->fetch(PDO::FETCH_ASSOC);
          $num = $stmt->rowCount();
          if ($num > 0) {
          if ($row["id_"] == $this->id_) {

          }else{
          $result["error"] = 1;
          $result["message"] = 'Il y a déjà un abonné nommé ('.$this->nom_client_blue.')';
          return $result;
          }
          } */
        $this->date_update = date('Y-m-d H:i:s');

        //Récupération et création LOG Adresse 
        $adress_item = new  AdresseEntity($this->connection);
        $adress_item->n_user_create = $this->n_user_create;
        $adress_item->datesys = $this->date_update;
        $id_adress = $adress_item->GetOrCreateAdressId($this->ville_id, $this->commune_id, $this->quartier_id, $this->avenue, $this->numero);
        //END Récupération et création LOG Adresse 



        $query = "UPDATE " . $this->table_name . " SET  p_a = :p_a,gps_latitude = :gps_latitude,gps_longitude = :gps_longitude,cvs_id = :cvs_id,client_id =:client_id,occupant_id =:occupant_id  ,nbre_branchement = :nbre_branchement,section_cable = :section_cable,code_identificateur = :code_identificateur,numero_piece_identity= :numero_piece_identity,accessibility_client= :accessibility_client,tarif_identif= :tarif_identif,infos_supplementaires= :infos_supplementaires,numero_depart= :numero_depart,numero_poteau_identif= :numero_poteau_identif,type_raccordement_identif= :type_raccordement_identif,type_compteur= :type_compteur,type_construction= :type_construction,nbre_appartement= :nbre_appartement,type_activites= :type_activites,conformites_installation= :conformites_installation,avis_technique_blue= :avis_technique_blue,avis_occupant= :avis_occupant,chef_equipe= :chef_equipe,titre_responsable= :titre_responsable,titre_remplacant= :titre_remplacant,type_raccordement_propose= :type_raccordement_propose,nature_activity= :nature_activity,type_client= :type_client,consommateur_gerer=:consommateur_gerer,cabine_id=:cabine_id,index_consommation= :index_consommation,identificateur=:identificateur,n_user_update = :n_user_update,reference_appartement=:reference_appartement,date_update = :date_update,id_equipe_identification=:id_equipe_identification,adresse_id=:adresse_id,is_draft=:is_draft,presence_inversor=:presence_inversor,date_identification=(case when coalesce(date_identification,'') != '' then date_identification else (case when :is_draft = '0' then  :date_identification else NULL end) end)  WHERE id_ = :id_";


        $stmt = $this->connection->prepare($query);

        $this->id_ = (strip_tags($this->id_));
        $this->n_user_update = (strip_tags($this->n_user_update));

        //  $this->num_compteur_actuel = (strip_tags($this->num_compteur_actuel));
        //$this->commune_id = (strip_tags($this->commune_id));
        //$this->adresse = (strip_tags($this->adresse));
        //$this->quartier = strip_tags($this->quartier);
        //$this->numero_avenue = strip_tags($this->numero_avenue);

        $this->p_a = (strip_tags($this->p_a));
        $this->cvs_id = (strip_tags($this->cvs_id));
        $this->client_id = (strip_tags($this->client_id));
        $this->occupant_id = (strip_tags($this->occupant_id));
        $this->nbre_branchement = (strip_tags($this->nbre_branchement));
        $this->section_cable = (strip_tags($this->section_cable));
        $this->gps_longitude = (strip_tags($this->gps_longitude));
        $this->gps_latitude = (strip_tags($this->gps_latitude));
        $this->code_identificateur = (strip_tags($this->code_identificateur));
        $this->numero_piece_identity = strip_tags($this->numero_piece_identity);
        $this->accessibility_client = strip_tags($this->accessibility_client);
        $this->tarif_identif = strip_tags($this->tarif_identif);
        $this->infos_supplementaires = strip_tags($this->infos_supplementaires);
        // $this->nbre_menage_a_connecter = strip_tags($this->nbre_menage_a_connecter); 
        $this->numero_depart = strip_tags($this->numero_depart);
        $this->numero_poteau_identif = strip_tags($this->numero_poteau_identif);
        $this->type_raccordement_identif = strip_tags($this->type_raccordement_identif);
        $this->type_compteur = strip_tags($this->type_compteur);
        $this->type_construction = strip_tags($this->type_construction);
        $this->nbre_appartement = strip_tags($this->nbre_appartement);
        $this->type_activites = strip_tags($this->type_activites);
        $this->conformites_installation = strip_tags($this->conformites_installation);
        $this->avis_technique_blue = strip_tags($this->avis_technique_blue);
        $this->avis_occupant = strip_tags($this->avis_occupant);
        $this->chef_equipe = strip_tags($this->chef_equipe);
        $this->titre_responsable = strip_tags($this->titre_responsable);
        $this->titre_remplacant = strip_tags($this->titre_remplacant);
        $this->type_raccordement_propose = strip_tags($this->type_raccordement_propose);
        $this->nature_activity = strip_tags($this->nature_activity);
        $this->type_client = strip_tags($this->type_client);
        $this->consommateur_gerer = strip_tags($this->consommateur_gerer);
        $this->cabine_id = strip_tags($this->cabine_id);
        $this->index_consommation = strip_tags($this->index_consommation);
        $this->identificateur = strip_tags($this->identificateur);
        $this->id_equipe_identification = strip_tags($this->id_equipe_identification);
        $this->reference_appartement = strip_tags($this->reference_appartement);




        // $stmt->bindParam(":num_compteur_actuel", $this->num_compteur_actuel);
        // $stmt->bindParam(":commune_id", $this->commune_id);
        //$stmt->bindParam(":adresse", $this->adresse);
        //$stmt->bindParam(":quartier", $this->quartier);
        //$stmt->bindParam(":numero_avenue", $this->numero_avenue);

        $stmt->bindParam(":id_", $this->id_);
        $stmt->bindParam(":n_user_update", $this->n_user_update);
        $stmt->bindParam(":date_update", $this->date_update);
        $stmt->bindParam(":p_a", $this->p_a);
        $stmt->bindParam(":cvs_id", $this->cvs_id);
        $stmt->bindParam(":client_id", $this->client_id);
        $stmt->bindParam(":occupant_id", $this->occupant_id);
        $stmt->bindParam(":nbre_branchement", $this->nbre_branchement);
        $stmt->bindParam(":section_cable", $this->section_cable);
        $stmt->bindParam(":code_identificateur", $this->code_identificateur);
        $stmt->bindParam(":numero_piece_identity", $this->numero_piece_identity);
        $stmt->bindParam(":accessibility_client", $this->accessibility_client);
        $stmt->bindParam(":tarif_identif", $this->tarif_identif);
        $stmt->bindParam(":infos_supplementaires", $this->infos_supplementaires);
        // $stmt->bindParam(":nbre_menage_a_connecter", $this->nbre_menage_a_connecter); 
        $stmt->bindParam(":numero_depart", $this->numero_depart);
        $stmt->bindParam(":numero_poteau_identif", $this->numero_poteau_identif);
        $stmt->bindParam(":type_raccordement_identif", $this->type_raccordement_identif);
        $stmt->bindParam(":type_compteur", $this->type_compteur);
        $stmt->bindParam(":type_construction", $this->type_construction);
        $stmt->bindParam(":nbre_appartement", $this->nbre_appartement);
        $stmt->bindParam(":type_activites", $this->type_activites);
        $stmt->bindParam(":conformites_installation", $this->conformites_installation);
        $stmt->bindParam(":avis_technique_blue", $this->avis_technique_blue);
        $stmt->bindParam(":avis_occupant", $this->avis_occupant);
        $stmt->bindParam(":chef_equipe", $this->chef_equipe);
        $stmt->bindParam(":titre_responsable", $this->titre_responsable);
        $stmt->bindParam(":titre_remplacant", $this->titre_remplacant);
        $stmt->bindParam(":type_raccordement_propose", $this->type_raccordement_propose);
        $stmt->bindParam(":nature_activity", $this->nature_activity);
        $stmt->bindParam(":type_client", $this->type_client);
        $stmt->bindParam(":consommateur_gerer", $this->consommateur_gerer);
        $stmt->bindParam(":cabine_id", $this->cabine_id);
        $stmt->bindParam(":index_consommation", $this->index_consommation);
        $stmt->bindParam(":identificateur", $this->identificateur);
        $stmt->bindParam(":id_equipe_identification", $this->id_equipe_identification);
        $stmt->bindParam(":is_draft", $this->is_draft);
        $stmt->bindParam(":presence_inversor", $this->presence_inversor);
        $stmt->bindParam(":adresse_id", $id_adress);
        $stmt->bindParam(":date_identification",  $this->date_update);
        $stmt->bindParam(":reference_appartement",  $this->reference_appartement);
        $stmt->bindParam(":gps_latitude", $this->gps_latitude);
        $stmt->bindParam(":gps_longitude", $this->gps_longitude);

        if ($stmt->execute()) {
            $this->SaveMateriels_Identif($this->id_, $this->lst_materiels);
            $result["error"] = 0;
            $result["id"] = $this->id_;
            $result["message"] = 'Enregistrement effectué avec succès';
        } else {
            $result["error"] = 1;
            $result["message"] = "L'opération de l'enregistrement a échoué.";
        }
        return $result;
    }

    function CreateTemporaire($user_context)
    {
        $result = array();
        $item_site = new SiteProduction($this->connection);
        $item_site->code_site = $user_context->site_id;
        $_jiko_item = $item_site->GetDetail();
        $generer = new Generateur($this->connection, TRUE);
        $generer->has_signature = TRUE;
        $generer->Signature_fld = 'signature_id';
        $generer->Signature_Value = $_jiko_item["site_short_code"];
        $uuid = $generer->getUID('generateur_main', 'num_identification', 'Y', 't_main_data', 'id_');

        // is_draft
        //Creation d'un Identifiant lors de l'aperçu du formulaire
        $query = " INSERT INTO t_main_data SET id_=:id, client_id='Non', occupant_id='Non',n_user_create=:n_user_create,date_debut_identification=now(),datesys=:datesys,ref_site_identif=:ref_site_identif";
        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(":id", $uuid);
        $stmt->bindParam(":n_user_create", $this->n_user_create);
        $stmt->bindParam(":datesys", $datesys);
        $stmt->bindParam(":ref_site_identif", $user_context->site_id);
        if ($stmt->execute()) {
            $result["error"] = 0;
            $result["uid"] = $uuid;
        } else {
            $result["error"] = 1;
            $result["message"] = 'Echec de la préparation du Formulaire';
        }
        return $result;
    }
    function CreateWeb()
    {

        /*      $this->nom_client_blue = (strip_tags($this->nom_client_blue));
        $query = "select id_,nom_client_blue from  " . $this->table_name . " where nom_client_blue=:nom_client_blue";
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(":nom_client_blue", $this->nom_client_blue);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $num = $stmt->rowCount();
        if ($num > 0){ 
                $result["error"] = 1;
                $result["message"] = 'Il y a déjà un abonné nommé (' . $this->nom_client_blue . ')';
                return $result; 
        }
*/


        //Récupération et création LOG Adresse 
        $adress_item = new  AdresseEntity($this->connection);
        $adress_item->n_user_create = $this->n_user_create;
        $adress_item->datesys = date('Y-m-d H:i:s'); ///;$this->datesys;
        $id_adress = $adress_item->GetOrCreateAdressId($this->ville_id, $this->commune_id, $this->quartier_id, $this->avenue, $this->numero);
        //END Récupération et création LOG Adresse 

        $query = "INSERT INTO " . $this->table_name . " SET  id_ = :id_, p_a = :p_a,cvs_id = :cvs_id,client_id =:client_id,occupant_id = :occupant_id  ,nbre_branchement = :nbre_branchement,section_cable = :section_cable,code_identificateur = :code_identificateur,date_identification=(case when :is_draft = '0' then  :date_identification else NULL end),gps_latitude = :gps_latitude,gps_longitude = :gps_longitude,ref_site_identif=:ref_site_identif,numero_piece_identity= :numero_piece_identity,accessibility_client= :accessibility_client,tarif_identif= :tarif_identif,infos_supplementaires= :infos_supplementaires,numero_depart= :numero_depart,numero_poteau_identif= :numero_poteau_identif,type_raccordement_identif= :type_raccordement_identif,type_compteur= :type_compteur,type_construction= :type_construction,nbre_appartement= :nbre_appartement,type_activites= :type_activites,conformites_installation= :conformites_installation,avis_technique_blue= :avis_technique_blue,avis_occupant= :avis_occupant,chef_equipe= :chef_equipe,titre_responsable= :titre_responsable,titre_remplacant= :titre_remplacant,type_raccordement_propose= :type_raccordement_propose,nature_activity= :nature_activity,type_client= :type_client,consommateur_gerer=:consommateur_gerer,cabine_id=:cabine_id,index_consommation= :index_consommation,identificateur=:identificateur,reference_appartement=:reference_appartement,n_user_create=:n_user_create,id_equipe_identification=:id_equipe_identification,adresse_id=:adresse_id,is_draft=:is_draft,presence_inversor=:presence_inversor,date_debut_identification=now()";

        $stmt = $this->connection->prepare($query);

        $this->id_ = (strip_tags($this->id_));
        $this->p_a = (strip_tags($this->p_a));
        //$this->num_compteur_actuel = (strip_tags($this->num_compteur_actuel));
        //$this->commune_id = (strip_tags($this->commune_id));
        $this->cvs_id = (strip_tags($this->cvs_id));
        $this->client_id = (strip_tags($this->client_id));
        $this->occupant_id = (strip_tags($this->occupant_id));
        $this->nbre_branchement = (strip_tags($this->nbre_branchement));
        $this->section_cable = (strip_tags($this->section_cable));
        $this->gps_longitude = (strip_tags($this->gps_longitude));
        $this->gps_latitude = (strip_tags($this->gps_latitude));
        $this->code_identificateur = (strip_tags($this->code_identificateur));
        //$this->adresse = (strip_tags($this->adresse));
        $this->date_identification = date('Y-m-d H:i:s');

        $this->numero_piece_identity = strip_tags($this->numero_piece_identity);
        // $this->quartier = strip_tags($this->quartier);
        //$this->numero_avenue = strip_tags($this->numero_avenue);
        $this->accessibility_client = strip_tags($this->accessibility_client);
        $this->tarif_identif = strip_tags($this->tarif_identif);
        $this->infos_supplementaires = strip_tags($this->infos_supplementaires);
        // $this->nbre_menage_a_connecter = strip_tags($this->nbre_menage_a_connecter); 
        $this->numero_depart = strip_tags($this->numero_depart);
        $this->numero_poteau_identif = strip_tags($this->numero_poteau_identif);
        $this->type_raccordement_identif = strip_tags($this->type_raccordement_identif);
        $this->type_compteur = strip_tags($this->type_compteur);
        $this->type_construction = strip_tags($this->type_construction);
        $this->nbre_appartement = strip_tags($this->nbre_appartement);
        $this->type_activites = strip_tags($this->type_activites);
        $this->conformites_installation = strip_tags($this->conformites_installation);
        $this->avis_technique_blue = strip_tags($this->avis_technique_blue);
        $this->avis_occupant = strip_tags($this->avis_occupant);
        $this->chef_equipe = strip_tags($this->chef_equipe);
        //$this->statut_occupant = strip_tags($this->statut_occupant);
        $this->titre_responsable = strip_tags($this->titre_responsable);
        $this->titre_remplacant = strip_tags($this->titre_remplacant);



        $this->type_raccordement_propose = strip_tags($this->type_raccordement_propose);
        $this->nature_activity = strip_tags($this->nature_activity);
        $this->type_client = strip_tags($this->type_client);
        $this->consommateur_gerer = strip_tags($this->consommateur_gerer);
        //$this->id_organisme_allouer = strip_tags($this->id_organisme_allouer); 
        $this->cabine_id = strip_tags($this->cabine_id);
        $this->index_consommation = strip_tags($this->index_consommation);
        $this->identificateur = strip_tags($this->identificateur);
        $this->id_equipe_identification = strip_tags($this->id_equipe_identification);
        $this->reference_appartement = strip_tags($this->reference_appartement);

        $stmt->bindParam(":id_", $this->id_);
        $stmt->bindParam(":p_a", $this->p_a);
        // $stmt->bindParam(":num_compteur_actuel", $this->num_compteur_actuel);
        // $stmt->bindParam(":commune_id", $this->commune_id);
        $stmt->bindParam(":cvs_id", $this->cvs_id);
        $stmt->bindParam(":client_id", $this->client_id);
        $stmt->bindParam(":occupant_id", $this->occupant_id);
        $stmt->bindParam(":nbre_branchement", $this->nbre_branchement);
        $stmt->bindParam(":section_cable", $this->section_cable);
        $stmt->bindParam(":gps_latitude", $this->gps_latitude);
        $stmt->bindParam(":gps_longitude", $this->gps_longitude);
        $stmt->bindParam(":code_identificateur", $this->code_identificateur);
        $stmt->bindParam(":date_identification", $this->date_identification);
        $stmt->bindParam(":ref_site_identif", $this->site_id);
        //$stmt->bindParam(":adresse", $this->adresse);


        $stmt->bindParam(":numero_piece_identity", $this->numero_piece_identity);
        //$stmt->bindParam(":quartier", $this->quartier);
        //$stmt->bindParam(":numero_avenue", $this->numero_avenue);
        $stmt->bindParam(":accessibility_client", $this->accessibility_client);
        $stmt->bindParam(":tarif_identif", $this->tarif_identif);
        $stmt->bindParam(":infos_supplementaires", $this->infos_supplementaires);
        $stmt->bindParam(":numero_depart", $this->numero_depart);
        $stmt->bindParam(":numero_poteau_identif", $this->numero_poteau_identif);
        $stmt->bindParam(":type_raccordement_identif", $this->type_raccordement_identif);
        $stmt->bindParam(":type_compteur", $this->type_compteur);
        $stmt->bindParam(":type_construction", $this->type_construction);
        $stmt->bindParam(":nbre_appartement", $this->nbre_appartement);
        $stmt->bindParam(":type_activites", $this->type_activites);
        $stmt->bindParam(":conformites_installation", $this->conformites_installation);
        $stmt->bindParam(":avis_technique_blue", $this->avis_technique_blue);
        $stmt->bindParam(":avis_occupant", $this->avis_occupant);
        $stmt->bindParam(":chef_equipe", $this->chef_equipe);
        $stmt->bindParam(":titre_responsable", $this->titre_responsable);
        $stmt->bindParam(":titre_remplacant", $this->titre_remplacant);
        $stmt->bindParam(":type_raccordement_propose", $this->type_raccordement_propose);
        $stmt->bindParam(":nature_activity", $this->nature_activity);
        $stmt->bindParam(":type_client", $this->type_client);
        $stmt->bindParam(":consommateur_gerer", $this->consommateur_gerer);
        //$stmt->bindParam(":id_organisme_allouer", $this->id_organisme_allouer);
        $stmt->bindParam(":cabine_id", $this->cabine_id);
        $stmt->bindParam(":index_consommation", $this->index_consommation);
        $stmt->bindParam(":identificateur", $this->identificateur);
        $stmt->bindParam(":n_user_create", $this->n_user_create);
        $stmt->bindParam(":id_equipe_identification", $this->id_equipe_identification);
        $stmt->bindParam(":is_draft", $this->is_draft);
        $stmt->bindParam(":presence_inversor", $this->presence_inversor);
        $stmt->bindParam(":reference_appartement", $this->reference_appartement);
        $stmt->bindParam(":adresse_id", $id_adress);
        if ($stmt->execute()) {
            $this->SaveMateriels_Identif($this->id_, $this->lst_materiels);
            $result["error"] = 0;
            $result["id"] = $this->id_;
            $result["message"] = 'Identification effectuée avec succès';
        } else {
            $result["error"] = 1;
            $result["message"] = "L'opératon de l'identification a échoué.";
        }
        return $result;
    }

    function Supprimer()
    {
        $query = "UPDATE " . $this->table_name . " set annule='1',n_user_annule=:n_user_annule,motif_annulation=:motif_annulation WHERE id_=:id_";
        $stmt = $this->connection->prepare($query);
        $this->id_ = (strip_tags($this->id_));
        $stmt->bindParam(":id_", $this->id_);
        $stmt->bindParam(":n_user_annule", $this->n_user_update);
        $stmt->bindParam(":motif_annulation", $this->motif_annulation);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    function GetListeIdentifs()
    {
        $query = "SELECT id_,date_identification,date_debut_identification,p_a,code_identificateur,gps_longitude,gps_latitude,client_id,occupant_id,photo_compteur,nbre_branchement,signature_electronique,section_cable,date_update,n_user_update,adresse_id,photo_pa_avant,num_compteur_actuel,est_installer,conformites_installation,id_equipe_identification,identificateur  
                FROM
                    " . $this->table_name . " where t_main_data.annule=" . $this->is_valid . "   ORDER BY
                    date_identification desc";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $result = array();
        $data = array();
        while ($row_spin = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row_spin;
        }
        if (isset($data)) {
            $result["error"] = false;
            $result["message"] = "Opération effectuée avec succès";
            $result["data"] = $data;
        } else {
            $result["error"] = true;
            $result["message"] = "Il n'y a pas de données";
            $result["data"] = null;
        }
        return $result;
    }

    function read()
    {
        $query = "SELECT  id_,DATE_FORMAT(date_identification,'%d/%m/%Y %H:%i:%s')  as date_fr,date_identification,reference_appartement,date_debut_identification,p_a,code_identificateur,gps_longitude,gps_latitude,client_id,occupant_id,photo_compteur,nbre_branchement,signature_electronique,section_cable,date_update,n_user_update,photo_pa_avant,cvs_id,num_compteur_actuel,DATE_FORMAT(date_installation_actuel,'%d/%m/%Y %H:%i:%S')  as date_installation_actuel_fr,adresse_id,tarif_identif,est_installer,conformites_installation,id_equipe_identification,identificateur FROM
                    " . $this->table_name .  " where t_main_data.annule=" . $this->is_valid;
        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    function GetDetail()
    {
        $query = "SELECT id_,client_id,scelle_actuel_compteur,
scelle_actuel_coffret,date_pose_scelle_actuel,type_pose_scelle_actuel,id_fiche_pose_scelle_actuel,occupant_id,reference_appartement,date_identification,DATE_FORMAT(date_identification,'%d/%m/%Y %H:%i:%s')  as date_identification_fr,date_debut_identification,p_a,code_identificateur,gps_longitude,gps_latitude,photo_compteur,nbre_branchement,signature_electronique,section_cable,date_update,n_user_update,photo_pa_avant,cvs_id,num_compteur_actuel,DATE_FORMAT(date_installation_actuel,'%d/%m/%Y %H:%i:%S')  as date_installation_actuel_fr,numero_piece_identity,accessibility_client,tarif_identif,infos_supplementaires,numero_depart,numero_poteau_identif,type_raccordement_identif,type_compteur,type_construction,nbre_appartement,type_activites,conformites_installation,avis_technique_blue,avis_occupant,chef_equipe,titre_remplacant,type_raccordement_propose,nature_activity,type_client,consommateur_gerer,id_organisme_allouer,cabine_id,presence_inversor,index_consommation,identificateur,id_equipe_identification,ref_installation_actuel,adresse_id,is_draft,presence_inversor FROM " . $this->table_name . "
			WHERE id_ = ?  and t_main_data.annule=" . $this->is_valid . " LIMIT 0,1";
        $result = array();
        $items = array();
        $photos = array();
        $stmt = $this->connection->prepare($query);
        $e_adresse = new AdresseEntity($this->connection);
        $this->id_ = (strip_tags($this->id_));
        $stmt->bindParam(1, $this->id_);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $result["error"] = 0;
        $result["data"] = $row;
        $result["count"] = $stmt->rowCount();
        $this->adresse_id =  $row['adresse_id'];
        $client_id =  $row['client_id'];
        $occupant_id =  $row['occupant_id'];
        // recuperer marque compteur si installé
        if (isset($row["ref_installation_actuel"]) || isset($row["num_compteur_actuel"])) {
            $query = "SELECT t_log_installation.numero_compteur,t_log_installation.marque_compteur,t_log_installation.id_install,t_log_installation.ref_identific,
t_log_installation.cabine,
t_log_installation.scelle_un_cpteur,
t_log_installation.scelle_deux_coffret,
t_log_installation.num_depart,
t_log_installation.num_poteau,
t_log_installation.type_raccordement,
t_log_installation.type_cpteur_raccord,
t_log_installation.nbre_alimentation,
t_log_installation.section_cable_alimentation,t_log_installation.section_cable_alimentation_deux,
t_log_installation.section_cable_sortie,
t_log_installation.presence_inverseur FROM t_log_installation WHERE id_install = ?  Or numero_compteur =?";
            // t_log_installation.presence_inverseur FROM t_log_installation WHERE id_install = ? Or numero_compteur =?";
            $stm = $this->connection->prepare($query);
            $this->id_ = (strip_tags($this->id_));
            $stm->bindParam(1, $row["ref_installation_actuel"]);
            $stm->bindParam(2, $row["num_compteur_actuel"]);
            $stm->execute();
            $rw = $stm->fetch(PDO::FETCH_ASSOC);
            $result["numero_compteur_installed"] = $rw["numero_compteur"];
            $result["marque_compteur_installed"] = $rw["marque_compteur"];
            $result["cabine"] = $rw["cabine"];
            $result["num_depart"] = $rw["num_depart"];
            $result["num_poteau"] = $rw["num_poteau"];
            $result["type_raccordement"] = $rw["type_raccordement"];
            $result["type_cpteur_raccord"] = $rw["type_cpteur_raccord"];
            $result["nbre_alimentation"] = $rw["nbre_alimentation"];
            $result["section_cable_alimentation"] = $rw["section_cable_alimentation"];
            $result["section_cable_alimentation_deux"] = $rw["section_cable_alimentation_deux"];
            $result["section_cable_sortie"] = $rw["section_cable_sortie"];
            $result["presence_inverseur"] = $rw["presence_inverseur"];

            if (!empty($row['scelle_actuel_compteur'])) {
                $result["scelle_un_cpteur"] = $row["scelle_actuel_compteur"];
            } else {
                $result["scelle_un_cpteur"] = $rw["scelle_un_cpteur"];
            }

            if (!empty($row['scelle_actuel_coffret'])) {
                $result["scelle_deux_coffret"] = $row["scelle_actuel_coffret"];
            } else {
                $result["scelle_deux_coffret"] = $rw["scelle_deux_coffret"];
            }
        } else {
            $result["numero_compteur_installed"] = "";
            $result["marque_compteur_installed"] = "";
            $result["cabine"] = "";
            $result["num_depart"] = "";
            $result["num_poteau"] = "";
            $result["type_raccordement"] = "";
            $result["type_cpteur_raccord"] = "";
            $result["nbre_alimentation"] = "";
            $result["section_cable_alimentation"] = "";
            $result["section_cable_alimentation_deux"] = "";
            $result["section_cable_sortie"] = "";
            $result["presence_inverseur"] = "";
            $result["scelle_un_cpteur"] = "";
            $result["scelle_deux_coffret"] = "";
        }
        //
        $adress_item = new  AdresseEntity($this->connection);
        $result["adresse"] = $adress_item->GetAdressInfo($this->adresse_id);
        $result["adresseTexte"] = $adress_item->GetAdressInfoTexte($this->adresse_id);

        /*
//START RECUPERATION ADRESSE
$query = "select  id,quartier_id,commune_id,ville_id,province_id,numero,avenue,is_deleted,status   FROM t_log_adresses where t_log_adresses.id=:id";
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(":id", $this->adresse_id);
        $stmt->execute();
		$result["adresse"] = array();
        if ($ro = $stmt->fetch(PDO::FETCH_ASSOC)) { 
            $result["adresse"] =  $ro;            
        }
//END RECUPERATION ADRESSE*/

        $query = "select  t_log_identification_materiels.id_mat,t_log_identification_materiels.ref_article, t_log_identification_materiels.qte_identification,t_param_liste_materiels.designation  FROM t_log_identification_materiels INNER JOIN t_param_liste_materiels ON t_log_identification_materiels.ref_article = t_param_liste_materiels.ref_produit where t_log_identification_materiels.ref_identification=:ref_identification";
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(":ref_identification", $this->id_);
        $stmt->execute();
        if ($ro = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result["error"] = false;
            $result["message"] = "";
            $items[] = $ro;
            while ($rw = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $items[] = $rw;
            }
        }
        $result["items"] = $items;

        //RECUPERATION PHOTOS GALLERY PA
        $query = "select  ref_photo FROM t_main_data_gallery where ref_fiche=:ref_identification";
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(":ref_identification", $this->id_);
        $stmt->execute();
        if ($ro = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result["error"] = false;
            $result["message"] = "";
            $photos[] = $ro;
            while ($rw = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $photos[] = $rw;
            }
        }
        $result["photos"] = $photos;
        $result["client"] =  $e_adresse->GetMenageDetail($client_id);
        $result["occupant"] =  $e_adresse->GetMenageDetail($occupant_id);
        return $result;
    }
    function readAll($from_record_num, $records_per_page, $user_context, $filtre)
    {

        $user_filtre = $this->GetUserFilter($user_context);
        $query = "SELECT DISTINCT t_main_data.id_,DATE_FORMAT(date_identification,'%d/%m/%Y %H:%i:%S') AS date_fr,
            t_main_data.date_identification,t_main_data.date_debut_identification,DATE_FORMAT(date_debut_identification,'%d/%m/%Y %H:%i:%S') AS date_deb_fr,
            t_main_data.p_a,t_main_data.nbre_appartement,t_main_data.code_identificateur,
            t_main_data.gps_longitude,t_main_data.gps_latitude,t_main_data.reference_appartement,
            t_main_data.client_id,t_main_data.occupant_id,
            t_main_data.photo_compteur,t_main_data.nbre_branchement,
            t_main_data.signature_electronique,t_main_data.section_cable,
            t_main_data.date_update,t_main_data.n_user_update,
            t_main_data.photo_pa_avant,t_main_data.cvs_id,
            t_main_data.num_compteur_actuel,DATE_FORMAT(date_installation_actuel,'%d/%m/%Y %H:%i:%S') AS date_installation_actuel_fr,
            t_main_data.adresse_id,t_main_data.tarif_identif,
            t_main_data.est_installer,t_main_data.id_equipe_identification,
            t_main_data.identificateur,t_main_data.chef_equipe,t_param_cvs.libelle,e_quartier.libelle as quartier,e_commune.libelle as commune,t_chef_equipe.nom_complet as nom_chef_equipe,t_utilisateurs.nom_utilisateur,t_utilisateurs.nom_complet,t_utilisateurs.code_utilisateur,t_param_organisme.denomination,t_main_data.is_draft, Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,identite_client.phone_number as phone_client_blue  FROM t_main_data INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
            INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
            INNER JOIN t_utilisateurs ON t_main_data.identificateur = t_utilisateurs.code_utilisateur 
            INNER JOIN t_utilisateurs as t_chef_equipe ON t_main_data.chef_equipe = t_chef_equipe.code_utilisateur INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme 
            INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`
            INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id 
            where t_main_data.annule=" . $this->is_valid .  $filtre  . $user_filtre;
            $user_context->id_service_group !==  '3' || !($user_context->HasGlobalAccess()) ? " and t_main_data.ref_site_identif=:ref_site_identif" : "";
            $query .= " ORDER BY 
            (case when coalesce(t_main_data.date_identification,'') != '' then t_main_data.date_identification else t_main_data.date_debut_identification end) desc LIMIT {$from_record_num}, {$records_per_page}";
        $stmt = $this->connection->prepare($query);
        // $stmt->bindValue(":ref_site_identif", $user_context->site_id);

        $stmt->execute();
        return $stmt;
    }

    function countAll($user_context, $filtre)
    {
        $user_filtre = $this->GetUserFilter($user_context);
        $query = "SELECT COUNT(DISTINCT id_) as total_rows from t_main_data INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
            INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
            INNER JOIN t_utilisateurs ON t_main_data.identificateur = t_utilisateurs.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_main_data.chef_equipe = t_chef_equipe.code_utilisateur  INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  where t_main_data.annule=" . $this->is_valid  . $filtre . $user_filtre ;
        $stmt = $this->connection->prepare($query);
        // $stmt->bindValue(":ref_site_identif",  $user_context->site_id);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_rows'];
    }

    function search($search_term, $from_record_num, $records_per_page, $user_context, $filtre)
    {
        $user_filtre = $this->GetUserFilterSearch($user_context);

        $query = "SELECT DISTINCT t_main_data.id_,DATE_FORMAT(date_identification,'%d/%m/%Y %H:%i:%S') AS date_fr,
            t_main_data.date_identification,t_main_data.reference_appartement,t_main_data.date_debut_identification,DATE_FORMAT(date_debut_identification,'%d/%m/%Y %H:%i:%S') AS date_deb_fr,
            t_main_data.p_a,t_main_data.nbre_appartement,t_main_data.code_identificateur,
            t_main_data.gps_longitude,t_main_data.gps_latitude,
            t_main_data.client_id,t_main_data.occupant_id,
            t_main_data.photo_compteur,t_main_data.nbre_branchement,
            t_main_data.signature_electronique,t_main_data.section_cable,
            t_main_data.date_update,t_main_data.n_user_update,
            t_main_data.photo_pa_avant,t_main_data.cvs_id,
            t_main_data.num_compteur_actuel,DATE_FORMAT(date_installation_actuel,'%d/%m/%Y %H:%i:%S') AS date_installation_actuel_fr,
            t_main_data.adresse_id,t_main_data.tarif_identif,
            t_main_data.est_installer,t_main_data.id_equipe_identification,
            t_main_data.identificateur,t_main_data.chef_equipe,t_param_cvs.libelle,e_quartier.libelle as quartier,e_commune.libelle as commune,t_utilisateurs.nom_utilisateur,t_utilisateurs.nom_complet,t_chef_equipe.nom_complet as nom_chef_equipe,t_utilisateurs.code_utilisateur,t_param_organisme.denomination,t_main_data.is_draft, Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,identite_client.phone_number as phone_client_blue  FROM t_main_data INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
            INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
            INNER JOIN t_utilisateurs ON t_main_data.identificateur = t_utilisateurs.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_main_data.chef_equipe = t_chef_equipe.code_utilisateur  INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme 
            INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`
            INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id 
            WHERE (t_main_data.p_a Like :search_term or Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) Like :search_term or identite_client.phone_number Like :search_term or t_main_data.num_compteur_actuel Like :search_term or t_main_data.id_ Like :search_term or e_avenue.libelle Like :search_term or e_quartier.libelle Like :search_term or e_commune.libelle Like :search_term or e_ville.libelle Like :search_term OR t_utilisateurs.nom_complet Like :search_term  OR t_chef_equipe.nom_complet Like :search_term OR DATE_FORMAT(date_identification,'%d/%m/%Y %H:%i:%S')  Like :search_term OR t_param_organisme.denomination  Like :search_term OR t_log_adresses.numero Like :search_term)" . $filtre  . $user_filtre . "  and t_main_data.annule=" . $this->is_valid . " ORDER BY t_main_data.date_identification desc LIMIT :from, :offset";
        $stmt = $this->connection->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(':search_term', $search_term);
        $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
        // $stmt->bindValue(":ref_site_identif", $user_context->site_id);

        $stmt->execute();
        return $stmt;
    }

    function countAll_BySearch($search_term, $user_context, $filtre)
    {
        $user_filtre = $this->GetUserFilterSearch($user_context);
        $query = "SELECT COUNT(DISTINCT id_) as total_rows FROM t_main_data INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
            INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
            INNER JOIN t_utilisateurs ON t_main_data.identificateur = t_utilisateurs.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_main_data.chef_equipe = t_chef_equipe.code_utilisateur  INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id WHERE (t_main_data.p_a Like :search_term or Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) Like :search_term or identite_client.phone_number Like :search_term or t_main_data.num_compteur_actuel Like :search_term or t_main_data.id_ Like :search_term or e_avenue.libelle Like :search_term or e_quartier.libelle Like :search_term or e_commune.libelle Like :search_term or e_ville.libelle Like :search_term OR t_utilisateurs.nom_complet Like :search_term   OR t_chef_equipe.nom_complet Like :search_term OR DATE_FORMAT(date_identification,'%d/%m/%Y %H:%i:%S')  Like :search_term OR t_param_organisme.denomination  Like :search_term OR t_log_adresses.numero Like :search_term)" . $filtre  . $user_filtre . " and t_main_data.annule=" . $this->is_valid;
        $stmt = $this->connection->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(':search_term', $search_term);
        // $stmt->bindValue(":ref_site_identif", $user_context->site_id);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_rows'];
    }

    function search_advanced($du, $au, $search_term, $from_record_num, $records_per_page, $user_context, $filtre)
    {
        $user_filtre = $this->GetUserFilterSearch($user_context);

        $query = "SELECT DISTINCT t_main_data.id_,t_main_data.reference_appartement,DATE_FORMAT(date_identification,'%d/%m/%Y %H:%i:%S') AS date_fr,
            t_main_data.date_identification,t_main_data.date_debut_identification,DATE_FORMAT(date_debut_identification,'%d/%m/%Y %H:%i:%S') AS date_deb_fr,
            t_main_data.p_a,t_main_data.nbre_appartement,t_main_data.code_identificateur,
            t_main_data.gps_longitude,t_main_data.gps_latitude,
            t_main_data.client_id,t_main_data.occupant_id,
            t_main_data.photo_compteur,t_main_data.nbre_branchement,
            t_main_data.signature_electronique,t_main_data.section_cable,
            t_main_data.date_update,t_main_data.n_user_update,
            t_main_data.photo_pa_avant,t_main_data.cvs_id,
            t_main_data.num_compteur_actuel,DATE_FORMAT(date_installation_actuel,'%d/%m/%Y %H:%i:%S') AS date_installation_actuel_fr,
            t_main_data.adresse_id,t_main_data.tarif_identif,
            t_main_data.est_installer,t_main_data.id_equipe_identification,
            t_main_data.identificateur,t_chef_equipe.nom_complet as nom_chef_equipe,t_param_cvs.libelle,e_quartier.libelle as quartier,e_commune.libelle as commune,t_utilisateurs.nom_utilisateur,t_utilisateurs.nom_complet,t_utilisateurs.code_utilisateur,t_param_organisme.denomination,t_main_data.is_draft, Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,identite_client.phone_number as phone_client_blue FROM  t_main_data INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
            INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
            INNER JOIN t_utilisateurs ON t_main_data.identificateur = t_utilisateurs.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_main_data.chef_equipe = t_chef_equipe.code_utilisateur INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id WHERE (t_main_data.p_a Like :search_term or Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) Like :search_term or identite_client.phone_number Like :search_term or t_main_data.num_compteur_actuel Like :search_term or t_main_data.id_ Like :search_term or e_avenue.libelle Like :search_term or e_quartier.libelle Like :search_term or e_commune.libelle Like :search_term or e_ville.libelle Like :search_term OR t_utilisateurs.nom_complet Like :search_term   OR t_chef_equipe.nom_complet Like :search_term OR DATE_FORMAT(date_identification,'%d/%m/%Y %H:%i:%S')  Like :search_term OR t_param_organisme.denomination  Like :search_term OR t_log_adresses.numero Like :search_term) and (DATE_FORMAT(t_main_data.date_identification,'%Y-%m-%d')  between :du and :au)"  . $filtre  . $user_filtre . "  and t_main_data.annule=" . $this->is_valid . " ORDER BY t_main_data.date_identification desc LIMIT :from, :offset";
        $stmt = $this->connection->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(':search_term', $search_term);
        $stmt->bindParam(':du', $du);
        $stmt->bindParam(':au', $au);
        $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
        // $stmt->bindValue(":ref_site_identif", $user_context->site_id);

        $stmt->execute();
        return $stmt;
    }


    function countAll_BySearch_advanced($du, $au, $search_term, $user_context, $filtre)
    {
        $user_filtre = $this->GetUserFilterSearch($user_context);
        $query = "SELECT COUNT(DISTINCT id_) as total_rows  FROM t_main_data INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
            INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
            INNER JOIN t_utilisateurs ON t_main_data.identificateur = t_utilisateurs.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_main_data.chef_equipe = t_chef_equipe.code_utilisateur  INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id WHERE (t_main_data.p_a Like :search_term or Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) Like :search_term or identite_client.phone_number Like :search_term or t_main_data.num_compteur_actuel Like :search_term or t_main_data.id_ Like :search_term or e_avenue.libelle Like :search_term or e_quartier.libelle Like :search_term or e_commune.libelle Like :search_term or e_ville.libelle Like :search_term OR t_utilisateurs.nom_complet Like :search_term   OR t_chef_equipe.nom_complet Like :search_term OR DATE_FORMAT(date_identification,'%d/%m/%Y %H:%i:%S')  Like :search_term OR t_param_organisme.denomination  Like :search_term OR t_log_adresses.numero Like :search_term) and (DATE_FORMAT(t_main_data.date_identification,'%Y-%m-%d')  between :du and :au)"  . $filtre  . $user_filtre . " and t_main_data.annule=" . $this->is_valid;
        $stmt = $this->connection->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(':search_term', $search_term);
        $stmt->bindParam(':du', $du);
        $stmt->bindParam(':au', $au);
        // $stmt->bindValue(":ref_site_identif", $user_context->site_id);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_rows'];
    }



    function search_advanced_DateOnly($du, $au, $from_record_num, $records_per_page, $user_context, $filtre)
    {
        $user_filtre = $this->GetUserFilterSearch($user_context);
        $query = "SELECT DISTINCT t_main_data.id_,t_main_data.reference_appartement,DATE_FORMAT(date_identification,'%d/%m/%Y %H:%i:%S') AS date_fr,
            t_main_data.date_identification,t_main_data.date_debut_identification,DATE_FORMAT(date_debut_identification,'%d/%m/%Y %H:%i:%S') AS date_deb_fr,
            t_main_data.p_a,t_main_data.nbre_appartement,t_main_data.code_identificateur,t_main_data.chef_equipe,t_chef_equipe.nom_complet as nom_chef_equipe,
            t_main_data.gps_longitude,t_main_data.gps_latitude,
            t_main_data.client_id,t_main_data.occupant_id,
            t_main_data.photo_compteur,t_main_data.nbre_branchement,
            t_main_data.signature_electronique,t_main_data.section_cable,
            t_main_data.date_update,t_main_data.n_user_update,
            t_main_data.photo_pa_avant,t_main_data.cvs_id,
            t_main_data.num_compteur_actuel,DATE_FORMAT(date_installation_actuel,'%d/%m/%Y %H:%i:%S') AS date_installation_actuel_fr,
            t_main_data.adresse_id,t_main_data.tarif_identif,
            t_main_data.est_installer,t_main_data.id_equipe_identification,
            t_main_data.identificateur,t_param_cvs.libelle,e_quartier.libelle as quartier,e_commune.libelle as commune,t_utilisateurs.nom_utilisateur,t_utilisateurs.nom_complet,t_utilisateurs.code_utilisateur,t_param_organisme.denomination,t_main_data.is_draft, Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) as nom_client_blue,identite_client.phone_number as phone_client_blue FROM  t_main_data INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
            INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
            INNER JOIN t_utilisateurs ON t_main_data.identificateur = t_utilisateurs.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_main_data.chef_equipe = t_chef_equipe.code_utilisateur  INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id WHERE (DATE_FORMAT(t_main_data.date_identification,'%Y-%m-%d')  between :du and :au)  and  ref_site_identif=:ref_site_identif "  . $filtre  . $user_filtre . "  and t_main_data.annule=" . $this->is_valid . " ORDER BY date_identification desc LIMIT :from, :offset";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':du', $du);
        $stmt->bindParam(':au', $au);
        $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
        $stmt->bindValue(":ref_site_identif", $user_context->site_id);

        $stmt->execute();
        return $stmt;
    }



    function countAll_BySearch_advanced_DateOnly($du, $au, $user_context, $filtre)
    {
        $user_filtre = $this->GetUserFilterSearch($user_context);
        $query = "SELECT COUNT(DISTINCT id_) as total_rows FROM  t_main_data INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
            INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
            INNER JOIN t_utilisateurs ON t_main_data.identificateur = t_utilisateurs.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_main_data.chef_equipe = t_chef_equipe.code_utilisateur  INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id WHERE (DATE_FORMAT(t_main_data.date_identification,'%Y-%m-%d')  between :du and :au)  and  ref_site_identif=:ref_site_identif "  . $filtre  . $user_filtre .  " and t_main_data.annule=" . $this->is_valid;
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':du', $du);
        $stmt->bindParam(':au', $au);
        $stmt->bindValue(":ref_site_identif", $user_context->site_id);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_rows'];
    }

    //public function uniqUid($len = 13) {  
    function uniqUid($table, $key_fld)
    {
        //uniq gives 13 CHARS BUT YOU COULD ADJUST IT TO YOUR NEEDS
        $bytes = md5(mt_rand());
        if ($this->VerifierEx($key_fld, $bytes, $table)) {
            $bytes = $this->uniqUid($table, $key_fld);
        }
        return $bytes;
    }

    function VerifierEx($pKey, $NoGenerated, $table)
    {
        $retour = false;
        $sql = "select $pKey from $table where $pKey=:NoGenerated";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(":NoGenerated", $NoGenerated);
        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $retour = true;
        } else {
            $retour = false;
        }
        return $retour;
    }

    function CvsSpinner($json)
    {
        $result = array();
        $items = array();
        $value = json_decode($json);
        $query = "select  code,libelle as name,'' as description, activated,id_commune  FROM t_cvs where id_commune=:id_commune";
        $value->commune_id = (strip_tags($value->commune_id));
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(":id_commune", $value->commune_id);
        $stmt->execute();
        if ($ro = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result["error"] = false;
            $result["message"] = "";
            $items[] = $ro;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $items[] = $row;
            }
            $result["data"] = $items;
        } else {
            $result["error"] = true;
            $result["message"] = "Aucun CVS trouvé";
            $result["data"] = null;
        }
        echo json_encode($result);
    }

    function CommuneSpinner()
    {
        $query = "select  code,libelle as name FROM t_commune";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        if ($ro = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result["error"] = false;
            $result["message"] = "";
            $items[] = $ro;
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $items[] = $row;
            }
            $result["data"] = $items;
        } else {
            $result["error"] = true;
            $result["message"] = "Aucune information trouvée";
            $result["data"] = null;
        }
        echo json_encode($result);
    }


    public function GetCompteurAdresseForControl($num_compteur, $user_context)
    {
        //$user_filtre = $this->GetUserFilter($user_context);
        $result = array();
        $item = array();
        // $search_term=trim($search_term);
        $items_deja_asign = array();
        $result['items'] = array();
        // $user_filtre="";

        // $user_filtre .= $this->GetUserFilterSearch($user_context);

        $adress_item = new  AdresseEntity($this->connection);
        // $query = "";
        $query = "SELECT t_main_data.id_,coalesce((datediff(now(),t_main_data.date_dernier_controle)),'-') AS jour_passer_dernier_controle,coalesce(DATE_FORMAT(t_main_data.date_dernier_controle,'%d/%m/%Y %H:%i:%S'),'-')  as date_dernier_controle_fr,
            t_main_data.gps_longitude,
            t_main_data.gps_latitude,
            t_main_data.p_a,t_main_data.reference_appartement,
            Concat(coalesce(identite_client.nom,' '),' ',coalesce(identite_client.postnom,' '),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, coalesce(identite_client.phone_number,'-') as phone_client_blue,
            t_main_data.num_compteur_actuel,
            t_main_data.adresse_id,
            t_main_data.cvs_id, 
            t_main_data.etat_compteur,
            t_param_cvs.libelle FROM t_main_data INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 
            INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
            INNER JOIN t_utilisateurs ON t_main_data.identificateur = t_utilisateurs.code_utilisateur INNER JOIN t_utilisateurs as t_chef_equipe ON t_main_data.chef_equipe = t_chef_equipe.code_utilisateur  INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme 
            INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`
            INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id 
            WHERE  t_main_data.annule=" . $this->is_valid . " and t_main_data.est_installer='1' and t_main_data.num_compteur_actuel=:num_compteur_";

        $stmt = $this->connection->prepare($query);


        $query_avoid = "select id_fiche_identif from t_param_assignation where id_fiche_identif=:fiche_id and is_valid='1'";
        $stmt_avoid = $this->connection->prepare($query_avoid);

        // if($search_term!=""){ 
        // $search_term = "{$search_term}";
        $stmt->bindParam(':num_compteur_', $num_compteur);

        // if($csv_id != ""){
        // $stmt->bindParam(':cvs_id', $csv_id);
        // }
        // $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
        // $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
        // $stmt->bindValue(":ref_site_identif", $user_context->site_id);

        // echo ($query);
        // exit;
        $stmt->execute();
        $result["error"] = 0;
        $row_ =  $stmt->fetchAll(PDO::FETCH_ASSOC);

        // $stmt->execute();
        // $result["error"] = 0;
        // $row_ = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($row_) > 0) {
            // foreach ($row_ as $vl) {
            $vl = $row_[0];
            //EVITER LES COMPTEURS DEJA ASSIGNES

            //EVITER LES COMPTEURS DEJA ASSIGNES	
            $stmt_avoid->bindValue(":fiche_id", $vl['id_']);
            $stmt_avoid->execute();
            $row_avoid = $stmt_avoid->fetch(PDO::FETCH_ASSOC);
            if (!$row_avoid) {
                $item['data'] = $vl;
                $item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
                $result['items'][] = $item;
            } else {
                $items_deja_asign[] = $vl['num_compteur_actuel'];
                $result["error"] = 1;
                $result["message"] = "a déjà une assignation valide";
                $result["error_type"] = "warning";
            }


            /*
					$item['data']= $vl;
					$item["adresseTexte"] = $adress_item->GetAdressInfoTexte($vl["adresse_id"]);
					$result['items'][]= $item;*/
            // }
        } else {
            $result["error"] = 1;
            $result["message"] = "Non trouvé";
            $result["error_type"] = "error";
        }
        $result["deja_assigner_count"] = count($items_deja_asign);
        return $result;
    }
}
