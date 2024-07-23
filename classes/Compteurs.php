<?php

//require_once 'utils/PHPExcel-1.8/Classes/PHPExcel.php';

class Compteurs
{

    public function __construct($db)
    {
        $this->connection = $db;
    }
    public $ref_produit_series;
    public $n_user_create;
    public $n_user_update;
    public $date_update;
    public $datesys;
    public $annule;
    public $code_user_create;
    public $serial_number;
    public $sts_serial_number;
    public $order_number;
    public $manufacturer_ref;
    public $site_id_affectation;
    public $ref_sous_traitant;
    public $nom_sous_traitant;
    public $deja_affected;
    public $date_affectation_first_afectation;
    public $n_user_annule;
    public $date_annule;
    public $motif_annulation;
    public $statut_compteur;
    public $is_sync;
    public $date_desaffectation;
    public $motif_desaffectation;
    public $annee_fabrication;
    public $date_actuelle_affectation;
    private $table_name = 't_param_liste_compteurs';
    private $connection;
    private $file_name;

    function Create()
    {
        $query = "INSERT INTO " . $this->table_name . "  SET ref_produit_series=:ref_produit_series,n_user_create=:n_user_create,code_user_create=:code_user_create,serial_number=:serial_number,sts_serial_number=:sts_serial_number,order_number=:order_number,manufacturer_ref=:manufacturer_ref,site_id_affectation=:site_id_affectation,annee_fabrication=:annee_fabrication,datesys=now()";
        $stmt_select = $this->connection->prepare('SELECT ref_produit_series,serial_number FROM t_param_liste_compteurs where serial_number=:numero_serie');


        $stmt = $this->connection->prepare($query);
        $this->ref_produit_series = strip_tags($this->ref_produit_series);
        $this->n_user_create = strip_tags($this->n_user_create);
        $this->code_user_create = strip_tags($this->code_user_create);
        $this->serial_number = strip_tags(trim($this->serial_number));
        $this->sts_serial_number = strip_tags($this->sts_serial_number);
        $this->order_number = strip_tags($this->order_number);
        $this->manufacturer_ref = strip_tags($this->manufacturer_ref);
        $this->site_id_affectation = strip_tags($this->site_id_affectation);
        $this->annee_fabrication = strip_tags($this->annee_fabrication);
        // $this->date_actuelle_affectation=strip_tags($this->date_actuelle_affectation);
        $this->datesys = date("Y-m-d H:i:s");
        $stmt_select->bindValue(':numero_serie', $this->serial_number);
        $stmt_select->execute();
        $data_row = $stmt_select->fetch(PDO::FETCH_ASSOC);
        if (!$data_row) {
            $stmt->bindParam(":ref_produit_series", $this->ref_produit_series);
            $stmt->bindParam(":n_user_create", $this->n_user_create);
            $stmt->bindParam(":code_user_create", $this->code_user_create);
            $stmt->bindParam(":serial_number", $this->serial_number);
            $stmt->bindParam(":sts_serial_number", $this->sts_serial_number);
            $stmt->bindParam(":order_number", $this->order_number);
            $stmt->bindParam(":manufacturer_ref", $this->manufacturer_ref);
            $stmt->bindParam(":site_id_affectation", $this->site_id_affectation);
            $stmt->bindParam(":annee_fabrication", $this->annee_fabrication);

            //$stmt->bindParam(":date_actuelle_affectation", $this->date_actuelle_affectation);
            if ($stmt->execute()) {
                $result["error"] = 0;
                $result["message"] = "Création effectuée avec succès";
            } else {
                $result["error"] = 1;
                $result["message"] = "L'opératon de la création a échoué.";
            }
        } else {
            $result["error"] = 1;
            $result["message"] = "Le numéro compteur (" . $this->serial_number . ") existe déjà dans la liste des compteurs";
        }
        return $result;
    }

    function Modifier()
    {
        $query = "UPDATE " . $this->table_name . "  SET n_user_update=:n_user_update,date_update=:date_update,serial_number=:serial_number,sts_serial_number=:sts_serial_number,order_number=:order_number,manufacturer_ref=:manufacturer_ref,site_id_affectation=:site_id_affectation,annee_fabrication=:annee_fabrication  WHERE ref_produit_series=:ref_produit_series";
        $stmt = $this->connection->prepare($query);

        $stmt_select = $this->connection->prepare('SELECT ref_produit_series,serial_number FROM t_param_liste_compteurs where serial_number=:numero_serie');

        $stmt_select->bindValue(':numero_serie', $this->serial_number);
        $stmt_select->execute();
        $data_row = $stmt_select->fetch(PDO::FETCH_ASSOC);
        if ($data_row["ref_produit_series"] != $this->ref_produit_series) {
            $result["error"] = 1;
            $result["message"] = "Il existe déjà un compteur avec le numéro ("  . $this->serial_number . ")";
        } else {
            $this->ref_produit_series = strip_tags($this->ref_produit_series);
            $this->n_user_update = strip_tags($this->n_user_update);
            $this->date_update = strip_tags($this->date_update);
            $this->serial_number = strip_tags($this->serial_number);
            $this->sts_serial_number = strip_tags($this->sts_serial_number);
            $this->order_number = strip_tags($this->order_number);
            $this->manufacturer_ref = strip_tags($this->manufacturer_ref);
            $this->site_id_affectation = strip_tags($this->site_id_affectation);
            //$this->deja_affected=strip_tags($this->deja_affected);
            $this->annee_fabrication = strip_tags($this->annee_fabrication);
            $this->date_update = date("Y-m-d H:i:s");

            $stmt->bindParam(":ref_produit_series", $this->ref_produit_series);
            $stmt->bindParam(":n_user_update", $this->n_user_update);
            $stmt->bindParam(":date_update", $this->date_update);
            $stmt->bindParam(":serial_number", $this->serial_number);
            $stmt->bindParam(":sts_serial_number", $this->sts_serial_number);
            $stmt->bindParam(":order_number", $this->order_number);
            $stmt->bindParam(":manufacturer_ref", $this->manufacturer_ref);
            $stmt->bindParam(":site_id_affectation", $this->site_id_affectation);
            //$stmt->bindParam(":deja_affected", $this->deja_affected);
            $stmt->bindParam(":annee_fabrication", $this->annee_fabrication);
            //$stmt->bindParam(":date_actuelle_affectation", $this->date_actuelle_affectation);
            if ($stmt->execute()) {
                $result["error"] = 0;
                $result["message"] = "Modification effectuée avec succès";
            } else {
                $result["error"] = 1;
                $result["message"] = "L'opératon de la modification a échoué.";
            }
        }
        return $result;
    }

    function Supprimer()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE ref_produit_series=:ref_produit_series";
        $stmt = $this->connection->prepare($query);
        $this->ref_produit_series = strip_tags($this->ref_produit_series);
        $stmt->bindParam(":ref_produit_series", $this->ref_produit_series);
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
        $query = "SELECT * FROM " . $this->table_name . " WHERE ref_produit_series = ? 	LIMIT 0,1";
        $stmt = $this->connection->prepare($query);
        $this->ref_produit_series = strip_tags($this->ref_produit_series);
        $stmt->bindParam(1, $this->ref_produit_series);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }



    function GetCompteurInfo($numero_serie)
    {
        $stmt = $this->connection->prepare('SELECT ref_produit_series,serial_number,sts_serial_number,order_number,manufacturer_ref  FROM t_param_liste_compteurs where serial_number=:numero_serie');
        $stmt->bindValue(':numero_serie', $numero_serie);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $result = array();
        if (!$row) {
            //NUMERO INTROUVABLE
            $result["error"] = true;
            $result["message"] = "Numéro série compteur non répértorié";
            $result["serial_number"] = '';
            $result["manufacturer_ref"] = '';
            $result["ref_produit_series"] = '';
        } else {
            if (empty($row["serial_number"])) {
                $result["error"] = true;
                $result["message"] = "Numéro série non valide";
                $result["serial_number"] = '';
                $result["manufacturer_ref"] = '';
                $result["ref_produit_series"] = '';
            } else {
                $result["error"] = false;
                $result["serial_number"] = $row["serial_number"];
                $result["manufacturer_ref"] = $row["manufacturer_ref"];
                $result["ref_produit_series"] = $row["ref_produit_series"];
            }
        }
        return $result;
    }


    function VerifyCompteurInfo($numero_serie, $user_context, $sendTicketDemand = false)
    {
        $stmt = $this->connection->prepare('SELECT ref_produit_series,serial_number,sts_serial_number,order_number,manufacturer_ref,site_id_affectation  FROM t_param_liste_compteurs where serial_number=:numero_serie');
        $stmt->bindValue(':numero_serie', $numero_serie);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            //NUMERO INTROUVABLE
            $result["error"] = 1;
            $result["message"] = "Numéro série compteur non répértorié";
        } else {
            $site_id_affectation = $row['site_id_affectation'];
            //NUMERO REPERTORIE			
            if (empty($row["serial_number"])) {
                $result["error"] = 1;
                $result["message"] = "Numéro série non valide";
            } else {
                //VERIFIER SI COMPTEUR DEJA ATTRIBUER
                $stmt = $this->connection->prepare('SELECT id_,num_compteur_actuel,ref_installation_actuel,ref_dernier_log_controle FROM t_main_data where num_compteur_actuel=?');
                $stmt->bindParam(1, $numero_serie);
                $stmt->execute();
                $row_v = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$row_v) {
                    if ($site_id_affectation == $user_context->site_id) {
                        $result["error"] = 0;
                        $result["serial_number"] = $row["serial_number"];
                        $result["manufacturer_ref"] = $row["manufacturer_ref"];
                        $result["ref_produit_series"] = $row["ref_produit_series"];
                    } else {
                        $result["error"] = 1;
                        $result["message"] = "Le numéro de série du compteur (" . $numero_serie . ") n'est pas affecté à votre site. ";
                    }
                } else {
                    //A AJOUTER VERIFIER SI La fiche est différente à la fiche actuelle				
                    if ($sendTicketDemand == false) {
                        $result["error"] = 1;
                        $result["message"] = "Le numéro de série du compteur (" . $numero_serie . ") est déjà assigné à une autre installation.\n Veuillez bien vérifier le numéro saisi. ";
                    } else {
                        // Envoi de la demande automatique 
                        // $notification->
                        try {
                            $utilisateur = new Utilisateur($this->connection);
                            // $item = new Installation($this->connection);
                            // $stmt = $item->search($numero_serie, null, 10, $utilisateur, ["t_log_installation.numero_compteur" => $numero_serie]);
                            $filtre =  " t_log_installation.numero_compteur = :numero_compteur";

                            // $query = "SELECT * FROM t_log_installation WHERE numero_compteur = :numero_compteur LIMIT 1";
                            $query = "SELECT 
                            t_log_installation.id_install,
                            t_log_installation.is_draft_install,
                            t_log_installation.etat_compteur_reaffected,
                            t_log_installation.type_installation,
                            t_log_installation.id_equipe,
                            t_log_installation.type_new_cpteur, 
                            t_log_installation.ref_site_install, 
                            t_log_installation.ref_identific,
                            DATE_FORMAT( t_log_installation.date_debut_installation,'%d/%m/%Y %H:%i:%S')  as date_debut_installation_fr, 
                            t_log_installation.date_debut_installation, 
                            t_log_installation.date_fin_installation,
                            DATE_FORMAT(date_fin_installation,'%d/%m/%Y %H:%i:%S')  as date_fin_installation_fr, 
                            t_log_installation.p_a, 
                            t_log_installation.nom_installateur, 
                            t_log_installation.nom_equipe, 
                            t_log_installation.numero_compteur,
                            t_log_installation.num_serie_cpteur_post_paie,
                            t_main_data.num_compteur_actuel,
                            t_main_data.identificateur, 
                            t_log_installation.photo_compteur, 
                            t_log_installation.marque_compteur,  
                            t_log_installation.n_user_create,  
                            t_log_installation.datesys, 
                            t_log_installation.date_update, 
                            t_log_installation.code_installateur,Concat(coalesce(identite_client.nom,''),' ',coalesce(identite_client.postnom,''),' ',coalesce(identite_client.prenom,'')) as nom_client_blue, 
                            coalesce(identite_client.phone_number,'-') as phone_client_blue, 
                            t_main_data.adresse_id, t_main_data.photo_pa_avant, 
                            t_main_data.cvs_id, 
                            t_main_data.section_cable, 
                            t_main_data.nbre_branchement,
                            t_log_installation.statut_installation,
                            t_log_installation.approbation_installation,
                            t_param_cvs.libelle,e_quartier.libelle as quartier,
                            e_commune.libelle as commune,t_utilisateurs.nom_utilisateur,
                            t_utilisateurs.nom_complet,
                            t_chef_equipe.nom_complet as nom_chef_equipe,
                            t_utilisateurs.code_utilisateur,
                            t_param_organisme.denomination,
                            t_main_data.is_draft, 
                            t_main_data.cvs_id,
                            t_main_data.adresse_id,
                            t_main_data.client_id,
                            identite_client.nom as nom_client
                            FROM t_log_installation 
                            INNER JOIN t_main_data 
                            ON t_log_installation.ref_identific = t_main_data.id_  
                            INNER JOIN t_param_cvs ON t_param_cvs.`code` = t_main_data.cvs_id 
                            INNER JOIN t_log_adresses ON t_main_data.adresse_id = t_log_adresses.id 
                            INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` 
                            INNER JOIN t_param_adresse_entity AS e_commune  ON t_log_adresses.commune_id = e_commune.`code` 

                            INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` 
                            INNER JOIN t_utilisateurs ON t_log_installation.code_installateur = t_utilisateurs.code_utilisateur 
                            INNER JOIN t_utilisateurs as t_chef_equipe ON t_log_installation.chef_equipe = t_chef_equipe.code_utilisateur  
                            INNER JOIN t_param_organisme ON t_main_data.id_equipe_identification = t_param_organisme.ref_organisme 
                            INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code`  
                            INNER JOIN t_param_identite AS identite_client ON t_main_data.client_id = identite_client.id  
                            where "  . $filtre .  " ORDER BY t_log_installation.date_fin_installation DESC LIMIT 1";

                            $stmt = $this->connection->prepare($query);
                            $stmt->bindParam(":numero_compteur", $numero_serie);

                            $stmt->execute();
                            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if (!count($results)) {
                                return;
                            }

                            $installation = $results[0];

                            //Generation Demande Ticket
                            $ref_log = $this->uniqUid('t_param_notification_log', "ref_log");
                            $query = "INSERT INTO t_param_notification_log 
                            SET 
                                ref_log=:ref_log,
                                ref_identif=:ref_identif,
                                statuts_notification=:statuts_notification,
                                type_notification=:type_notification,
                                id_site=:id_site,
                                n_user_create=:n_user_create,
                                num_compteur=:num_compteur,
                                ref_transaction=:ref_transaction,
                                datesys=:datesys,
                                cvs_id=:cvs_id,
                                nom_client=:nom_client,
                                adresse_id=:adresse,
                                from_control=:from_control";
                            $stmt = $this->connection->prepare($query);

                            $stmt->bindValue(":datesys", date('Y-m-d H:i:s'));
                            $stmt->bindValue(":ref_log", $ref_log);
                            $stmt->bindValue(":ref_identif", $installation['ref_identific']);
                            $stmt->bindValue(":statuts_notification", '0'); //(0)Non vu, (1) Vu		
                            $stmt->bindValue(":type_notification", "4"); // (2) REMPLACEMENT COMPTEUR - (3)DEMANDE DE RE-LEGALISATION - (4) DEMANDE TICKET	
                            $stmt->bindValue(":id_site", $installation['ref_site_install']);
                            $stmt->bindValue(":n_user_create", $installation['n_user_create']);
                            $stmt->bindValue(":num_compteur", $installation['numero_compteur']);
                            $stmt->bindValue(":ref_transaction", $installation['id_install']);
                            $stmt->bindValue(":cvs_id", $installation['cvs_id']);
                            $stmt->bindValue(":nom_client", $installation['nom_client']);
                            $stmt->bindValue(":adresse", $installation['adresse_id']);
                            $stmt->bindValue(":from_control", 1);


                            if ($stmt->execute()) {
                                $result['ticket'] = true;
                                $result["error"] = 0;
                                $result["serial_number"] = $row["serial_number"];
                                $result["manufacturer_ref"] = $row["manufacturer_ref"];
                                $result["ref_produit_series"] = $row["ref_produit_series"];
                                $result["message"] = "Le numéro de série du compteur (" . $numero_serie . ") correspond bien à une installation déjà assignée.\n La demande automatique a bien été envoyée !";
                            } else {
                                $result["error"] = 1;
                                $result['ticket'] = false;

                                $result['message'] = "La demande de ticket n'a pas été envoyé ! Une erreur est survenue !";
                            }
                        } catch (Exception $e) {
                        }
                    }
                }
            }
        }
        return $result;
    }

    //public function uniqUid($len = 13) {  
    function uniqUid($table, $key_fld)
    {
        //uniq gives 13 CHARS BUT YOU COULD ADJUST IT TO YOUR NEEDS
        $bytes = md5(mt_rand());
        if ($this->VerifierExistance($key_fld, $bytes, $table)) {
            $bytes = $this->uniqUid($table, $key_fld);
        }
        return $bytes;
    }

    function VerifierExistance($pKey, $NoGenerated, $table)
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

    function read()
    {
        $query = "SELECT ref_produit_series,annule,code_user_create,serial_number,sts_serial_number,order_number,manufacturer_ref,site_id_affectation,ref_sous_traitant,nom_sous_traitant,deja_affected,date_affectation_first_afectation,n_user_annule,date_annule,motif_annulation,statut_compteur,is_sync,date_desaffectation,motif_desaffectation,annee_fabrication,date_actuelle_affectation FROM " . $this->table_name . " ORDER BY serial_number";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    function readAll($from_record_num, $records_per_page)
    {
        $query = "SELECT ref_produit_series,annule,code_user_create,serial_number,sts_serial_number,order_number,manufacturer_ref,site_id_affectation,ref_sous_traitant,nom_sous_traitant,deja_affected,date_affectation_first_afectation,n_user_annule,date_annule,motif_annulation,statut_compteur,is_sync,date_desaffectation,motif_desaffectation,annee_fabrication,date_actuelle_affectation FROM " . $this->table_name . " ORDER BY serial_number ASC LIMIT {$from_record_num}, {$records_per_page}";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function search($search_term, $from_record_num, $records_per_page)
    {
        $query = "SELECT ref_produit_series,annule,code_user_create,serial_number,sts_serial_number,order_number,manufacturer_ref,site_id_affectation,ref_sous_traitant,nom_sous_traitant,deja_affected,date_affectation_first_afectation,n_user_annule,date_annule,motif_annulation,statut_compteur,is_sync,date_desaffectation,motif_desaffectation,annee_fabrication,date_actuelle_affectation  FROM " . $this->table_name  . " WHERE serial_number LIKE :search_term  ORDER BY serial_number ASC LIMIT :from, :offset";
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
        $query = "SELECT ref_produit_series FROM " . $this->table_name;
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();
        return $num;
    }
    public function countAll_BySearch($search_term)
    {
        $query = "SELECT COUNT(*) as total_rows FROM  " . $this->table_name . " WHERE serial_number LIKE :search_term";
        $stmt = $this->connection->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(":search_term", $search_term);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row["total_rows"];
    }


    function GetIDonLabel($LABEL)
    {
        $query = "SELECT code,libelle FROM t_param_marque_compteur WHERE libelle = ? 	LIMIT 0,1"; //
        $stmt = $this->connection->prepare($query);
        $LABEL = strip_tags($LABEL);
        $stmt->bindParam(1, $LABEL);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['code'];
    }


    //VERIFICATION AUTOMATIC DOSSIER ET CREATION A AJOUTER
    /*function import($site_id, $FILES, $user_context,$marque_compteur) {
	  $t_Task_ = new Thread_Import($site_id, $FILES, $user_context,$marque_compteur,$this->connection);
	   $t_Task_->start();
  }*/
    function import($site_id, $FILES, $user_context, $marque_compteur)
    {
        set_time_limit(0);
        $result = array();
        /*
        $frm_id = $this->uniqUid("surveys_entity_questionnaires", "id");
        $filePath = $location . '/' . $frm_id . '/';
        if (is_dir($filePath) === false) {
            mkdir($filePath);
        }
        $filePath = $filePath . 'xlsform/';
        if (is_dir($filePath) === false) {
            mkdir($filePath);
        }
        $filePath.=$FILES['frm']['name'];


        if (move_uploaded_file($FILES['frm']['tmp_name'], $filePath)) {
            $this->result_array["error"] = 0;
            $this->result_array["message"] = "Importation effectuée avec succès";
        } else {
            $this->result_array["error"] = 1;
            $this->result_array["message"] = "Echec d'importation du fichier";
            return $this->result_array;
        }*
		
		$this->file_name = $filePath;
        $objPHPExcel = new PHPExcel();
        $input_file_type = PHPExcel_IOFactory::identify($filePath);
        $obj_reader = PHPExcel_IOFactory::createReader($input_file_type);
        $objPHPExcel = $obj_reader->load($filePath);*/
        $headers = array();
        $rows = array();

        if (isset($_FILES['frm']['name']) && $_FILES['frm']['name'] != "") {
            $allowedExtensions = array("xls", "xlsx");
            $ext = pathinfo($_FILES['frm']['name'], PATHINFO_EXTENSION);
            if (in_array($ext, $allowedExtensions)) {
                $file_size = $_FILES['frm']['size'] / 1024;
                if ($file_size < 1024) { //Ko
                    // $file = "uploads/" . $_FILES['frm']['name'];
                    $file = $_FILES['frm']['tmp_name'];
                    // $isUploaded = copy($_FILES['frm']['tmp_name'], $file);
                    $isUploaded = false;
                    if (isset($_FILES['frm']['tmp_name'])) {
                        $isUploaded = $_FILES['frm']["error"] == 0;
                    }
                    if ($isUploaded) {
                        try {

                            /** Create a new Reader of the type defined in $inputFileType **/
                            // $objReader = PHPExcel_IOFactory::createReader($ext);
                            /** Advise the Reader that we only want to load cell data, not formatting **/
                            // $objReader->setReadDataOnly(true);
                            /**  Load $inputFileName to a PHPExcel Object  **/
                            // $objPHPExcel = $objReader->load($file);
                            //Load the excel(.xls/.xlsx) file
                            $objPHPExcel = PHPExcel_IOFactory::load($file);
                        } catch (Exception $e) {
                            //  die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME). '": ' . $e->getMessage());
                            $result["error"] = true;
                            $result["message"] = "Echec de la lecture du fichier";
                        }

                        //An excel file may contains many sheets, so you have to specify which one you need to read or work with.
                        $sheet = $objPHPExcel->getSheet(0);
                        //It returns the highest number of rows
                        $total_rows = $sheet->getHighestRow();
                        //It returns the highest number of columns
                        $total_columns = $sheet->getHighestColumn();

                        //echo '<h4>Data from excel file</h4>';
                        //echo '<table cellpadding="5" cellspacing="1" border="1" class="responsive">';
                        //DEBUT TRANSACTION
                        try {
                            $this->connection->beginTransaction();

                            $stmt_select = $this->connection->prepare('SELECT ref_produit_series,serial_number FROM t_param_liste_compteurs where serial_number=:numero_serie');

                            $query = "INSERT INTO " . $this->table_name . "  SET ref_produit_series=:ref_produit_series,n_user_create=:n_user_create,serial_number=:serial_number,sts_serial_number=:sts_serial_number,order_number=:order_number,manufacturer_ref=:manufacturer_ref,site_id_affectation=:site_id_affectation,datesys=now()";
                            $stmt = $this->connection->prepare($query);

                            $query_update = "UPDATE " . $this->table_name . "  SET n_user_update=:n_user_create,serial_number=:serial_number,sts_serial_number=:sts_serial_number,order_number=:order_number,manufacturer_ref=:manufacturer_ref,site_id_affectation=:site_id_affectation,date_update=now() WHERE ref_produit_series=:ref_produit_series";
                            $stmt_update = $this->connection->prepare($query_update);



                            //$query = "insert into `user_details` (`id`, `name`, `mobile`, `country`) VALUES ";
                            $has_ro = 0;
                            //Loop through each row of the worksheet
                            for ($row = 2; $row <= $total_rows; $row++) {
                                //$sleep = mt_rand(1, 10);
                                $has_ro++;
                                //[Serial Number(0)]	[STS Serial Number(1)]	[Order No(2)]	[Manufacturer(3)]
                                //Read a single row of data and store it as a array.
                                //This line of code selects range of the cells like A1:D1
                                $single_row = $sheet->rangeToArray('A' . $row . ':' . $total_columns . $row, NULL, TRUE, FALSE);
                                //echo "<tr>";
                                //Creating a dynamic query based on the rows from the excel file
                                //$query .= "(";
                                //Print each cell of the current row
                                /*foreach($single_row[0] as $key=>$value) {
										echo "<td>".$value."</td>";
										$query .= "'".mysqli_real_escape_string($con, $value)."',";
									}
									$query = substr($query, 0, -1);
									$query .= "),";
									echo "</tr>";*/


                                $str = trim($single_row[0][1]); //sts_serial_number REAL COMPTEUR NUMBER
                                $serial_number = preg_replace("/\s+/", "", $str);
                                if ($serial_number != "") {
                                    $stmt_select->bindValue(':numero_serie', $serial_number);
                                    $stmt_select->execute();
                                    $data_row = $stmt_select->fetch(PDO::FETCH_ASSOC);
                                    if (!$data_row) {
                                        $ref_produit_series = Utils::uniqUid("t_param_liste_compteurs", "ref_produit_series", $this->connection);
                                        $sts_serial_number = $single_row[0][0];
                                        $order_number = $single_row[0][2];
                                        /*$manufacturer_ref = $single_row[0][3];
											$manufacturer_ref_ID = $this->GetIDonLabel($manufacturer_ref) . "";
											if(strlen($manufacturer_ref_ID) == 0){
												$result["error"] = true;
												$result["message"] = "Manufacturer non repertorié";
												break;
											}*/
                                        $stmt->bindParam(":ref_produit_series", $ref_produit_series);
                                        $stmt->bindParam(":n_user_create", $this->n_user_create);
                                        $stmt->bindParam(":serial_number", $serial_number);
                                        $stmt->bindParam(":sts_serial_number", $sts_serial_number);
                                        $stmt->bindParam(":order_number", $order_number);
                                        $stmt->bindParam(":manufacturer_ref", $marque_compteur);
                                        $stmt->bindParam(":site_id_affectation", $site_id);
                                        //$stmt->bindParam(":annee_fabrication", $this->annee_fabrication);
                                        //$stmt->bindParam(":date_actuelle_affectation", $this->date_actuelle_affectation);
                                        $stmt->execute();
                                    } else {
                                        $sts_serial_number = $single_row[0][0];
                                        $order_number = $single_row[0][2];
                                        $stmt_update->bindParam(":ref_produit_series", $data_row['ref_produit_series']);
                                        $stmt_update->bindParam(":n_user_create", $this->n_user_create);
                                        $stmt_update->bindParam(":serial_number", $serial_number);
                                        $stmt_update->bindParam(":sts_serial_number", $sts_serial_number);
                                        $stmt_update->bindParam(":order_number", $order_number);
                                        $stmt_update->bindParam(":manufacturer_ref", $marque_compteur);
                                        $stmt_update->bindParam(":site_id_affectation", $site_id);
                                        $stmt_update->execute();
                                    }
                                }
                                // usleep(10000);
                                // sleep($sleep);
                                // doEvents();
                            }
                            $this->connection->commit();
                            if ($has_ro > 0) {
                                $result["error"] = false;
                                $result["message"] = "Importation effectuée avec succès";
                            } else {
                                $result["error"] = false;
                                $result["message"] = "Fichier sans donnée à importer";
                            }
                        } catch (\Exception $e) {
                            if ($this->connection->inTransaction()) {
                                $this->connection->rollback();
                                $result["error"] = true;
                                $result["message"] = "Echec opération";
                                $result["data"] = $e->getMessage();
                            }
                        }
                        /*Free memory when you are done with a file*/
                        $objPHPExcel->disconnectWorksheets();
                        unset($objPHPExcel);
                        // Finally we will remove the file from the uploads folder (optional) 
                        unlink($file);
                    } else {
                        // echo '<span class="msg">File not uploaded!</span>';
                        $result["error"] = true;
                        $result["message"] = "Fichier non téléchargé";
                    }
                } else {
                    // echo '<span class="msg">Maximum file size should not cross 50 KB on size!</span>';  
                    $result["error"] = true;
                    $result["message"] = "La taille maximale du fichier requise est 50kb";
                }
            } else {
                // echo '<span class="msg">This type of file not allowed!</span>';
                $result["error"] = true;
                $result["message"] = "Le type de fichier non prise en charge";
            }
        } else {
            //echo '<span class="msg">Select an excel file first!</span>';
            $result["error"] = true;
            $result["message"] = "Veuillez sélectionner le fichier";
        }



        return     $result;
    }
}
