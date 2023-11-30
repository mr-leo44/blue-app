<?php

class LogVisitIdentification
{

    // database connection and table name
    private $connection;
    private $is_valid = 2;
    private $table_name = "t_param_log_visite_pa";
    public $id_;
    public $date_identification;


    public function __construct($db)
    {
        $this->connection = $db;
    }



    function GetUserFilter($user_context)
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
            $clean = rtrim($lst_user_chief, ",");
            $user_filtre = " and n_user_create in (" . $clean . ")";
        } else {
            $user_filtre = " and n_user_create='" . $user_context->code_utilisateur  . "'";
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


    function readAll($from_record_num, $records_per_page, $user_context, $filtre)
    {
        $user_filtre = $this->GetUserFilter($user_context);
        $query = "SELECT t_param_log_visite_pa.ref_log_visite,
t_param_log_visite_pa.ref_adresse,
t_param_log_visite_pa.datesys,DATE_FORMAT(t_param_log_visite_pa.datesys,'%d/%m/%Y %h:%i:%s') AS date_visite,
t_param_log_visite_pa.date_update,
t_param_log_visite_pa.is_sync,
t_param_log_visite_pa.annule,
t_param_log_visite_pa.statut_accessibilite,
t_param_log_visite_pa.n_user_create,
t_param_log_visite_pa.num_pa,
t_param_log_visite_pa.type_motif_visite,
t_param_log_visite_pa.site_id,
t_param_log_visite_pa.cvs_id,
t_param_log_visite_pa.commentaire,
t_param_log_visite_pa.date_rendez_vous,DATE_FORMAT(t_param_log_visite_pa.date_rendez_vous,'%d/%m/%Y %h:%i:%s') AS date_rendez_vous_prev,
t_log_adresses.numero,
t_param_cvs.libelle, 
t_log_adresses.avenue,
e_avenue.libelle as avenue_lab, 
e_quartier.libelle as quartier_lab,
e_commune.libelle as commune_lab,
e_ville.libelle as ville_lab FROM t_param_log_visite_pa INNER JOIN t_log_adresses ON t_param_log_visite_pa.ref_adresse = t_log_adresses.id  INNER JOIN t_param_cvs ON t_param_log_visite_pa.cvs_id = t_param_cvs.`code` INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune ON t_log_adresses.commune_id = e_commune.`code` INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code`   where site_id=:site_id " . $filtre  . $user_filtre . "  and t_param_log_visite_pa.annule=" . $this->is_valid . " ORDER BY 
 t_param_log_visite_pa.datesys desc LIMIT {$from_record_num}, {$records_per_page}";
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(":site_id", $user_context->site_id);
        $stmt->execute();
        //$j=$stmt->fetchAll();
        //echo "from_record_num ".$from_record_num;
        //echo $user_site;
        //echo "  records_per_page ".$records_per_page;
        //print_r($j);
        //exit();
        return $stmt;
    }


    function countAll($user_context, $filtre)
    {
        $user_filtre = $this->GetUserFilter($user_context);
        $query = "SELECT count(*) as nbre FROM t_param_log_visite_pa INNER JOIN t_log_adresses ON t_param_log_visite_pa.ref_adresse = t_log_adresses.id  INNER JOIN t_param_cvs ON t_param_log_visite_pa.cvs_id = t_param_cvs.`code` INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune ON t_log_adresses.commune_id = e_commune.`code` INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code`   where site_id=:site_id " . $filtre . $user_filtre . " and t_param_log_visite_pa.annule=" . $this->is_valid;
        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(":site_id",  $user_context->site_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // $num = $stmt->rowCount();
        return $row['nbre'];
    }

    function search($search_term, $from_record_num, $records_per_page, $user_context, $filtre)
    {
        // OR t_utilisateurs.nom_complet Like :search_term 
        $user_filtre = $this->GetUserFilterSearch($user_context);

        $query = "SELECT t_param_log_visite_pa.ref_log_visite,
t_param_log_visite_pa.ref_adresse,
t_param_log_visite_pa.datesys,DATE_FORMAT(t_param_log_visite_pa.datesys,'%d/%m/%Y %h:%i:%s') AS date_visite,
t_param_log_visite_pa.date_update,
t_param_log_visite_pa.is_sync,
t_param_log_visite_pa.annule,
t_param_log_visite_pa.statut_accessibilite,
t_param_log_visite_pa.n_user_create,
t_param_log_visite_pa.num_pa,
t_param_log_visite_pa.type_motif_visite,
t_param_log_visite_pa.site_id,
t_param_log_visite_pa.cvs_id,
t_param_log_visite_pa.commentaire,
t_param_log_visite_pa.date_rendez_vous,DATE_FORMAT(t_param_log_visite_pa.date_rendez_vous,'%d/%m/%Y %h:%i:%s') AS date_rendez_vous_prev,
t_log_adresses.numero,
t_param_cvs.libelle, 
t_log_adresses.avenue,
e_avenue.libelle as avenue_lab, 
e_quartier.libelle as quartier_lab,
e_commune.libelle as commune_lab,
e_ville.libelle as ville_lab FROM t_param_log_visite_pa INNER JOIN t_log_adresses ON t_param_log_visite_pa.ref_adresse = t_log_adresses.id  INNER JOIN t_param_cvs ON t_param_log_visite_pa.cvs_id = t_param_cvs.`code` INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune ON t_log_adresses.commune_id = e_commune.`code` INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` WHERE (e_avenue.libelle Like :search_term or e_quartier.libelle Like :search_term or e_ville.libelle Like :search_term OR  e_commune.libelle Like :search_term  OR DATE_FORMAT(t_param_log_visite_pa.date_rendez_vous,'%d/%m/%Y')  Like :search_term OR t_log_adresses.numero Like :search_term) and  t_param_log_visite_pa.site_id=:site_id " . $filtre  . $user_filtre . "  and t_param_log_visite_pa.annule=" . $this->is_valid . " ORDER BY t_param_log_visite_pa.datesys desc LIMIT :from, :offset";
        $stmt = $this->connection->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(':search_term', $search_term);
        $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
        $stmt->bindValue(":site_id", $user_context->site_id);
        $stmt->execute();
        return $stmt;
    }

    function countAll_BySearch($search_term, $user_context, $filtre)
    {
        $user_filtre = $this->GetUserFilterSearch($user_context);
        $query = "SELECT COUNT(*) as total_rows  FROM t_param_log_visite_pa INNER JOIN t_log_adresses ON t_param_log_visite_pa.ref_adresse = t_log_adresses.id  INNER JOIN t_param_cvs ON t_param_log_visite_pa.cvs_id = t_param_cvs.`code` INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune ON t_log_adresses.commune_id = e_commune.`code` INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` WHERE (e_avenue.libelle Like :search_term or e_quartier.libelle Like :search_term or e_ville.libelle Like :search_term OR  e_commune.libelle Like :search_term  OR DATE_FORMAT(t_param_log_visite_pa.date_rendez_vous,'%d/%m/%Y')  Like :search_term OR t_log_adresses.numero Like :search_term) and  t_param_log_visite_pa.site_id=:site_id " . $filtre  . $user_filtre . "  and t_param_log_visite_pa.annule=" . $this->is_valid;
        $stmt = $this->connection->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(':search_term', $search_term);
        $stmt->bindValue(":site_id", $user_context->site_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_rows'];
    }

    function search_advanced($du, $au, $search_term, $from_record_num, $records_per_page, $user_context, $filtre)
    {
        $user_filtre = $this->GetUserFilterSearch($user_context);


        $query = "SELECT t_param_log_visite_pa.ref_log_visite,
t_param_log_visite_pa.ref_adresse,
t_param_log_visite_pa.datesys,DATE_FORMAT(t_param_log_visite_pa.datesys,'%d/%m/%Y %h:%i:%s') AS date_visite,
t_param_log_visite_pa.date_update,
t_param_log_visite_pa.is_sync,
t_param_log_visite_pa.annule,
t_param_log_visite_pa.statut_accessibilite,
t_param_log_visite_pa.n_user_create,
t_param_log_visite_pa.num_pa,
t_param_log_visite_pa.type_motif_visite,
t_param_log_visite_pa.site_id,
t_param_log_visite_pa.cvs_id,
t_param_log_visite_pa.commentaire,
t_param_log_visite_pa.date_rendez_vous,DATE_FORMAT(t_param_log_visite_pa.date_rendez_vous,'%d/%m/%Y %h:%i:%s') AS date_rendez_vous_prev,
t_log_adresses.numero,
t_param_cvs.libelle, 
t_log_adresses.avenue,
e_avenue.libelle as avenue_lab, 
e_quartier.libelle as quartier_lab,
e_commune.libelle as commune_lab,
e_ville.libelle as ville_lab FROM t_param_log_visite_pa INNER JOIN t_log_adresses ON t_param_log_visite_pa.ref_adresse = t_log_adresses.id  INNER JOIN t_param_cvs ON t_param_log_visite_pa.cvs_id = t_param_cvs.`code` INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune ON t_log_adresses.commune_id = e_commune.`code` INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` WHERE (e_avenue.libelle Like :search_term or e_quartier.libelle Like :search_term or e_ville.libelle Like :search_term OR  e_commune.libelle Like :search_term  OR DATE_FORMAT(t_param_log_visite_pa.date_rendez_vous,'%d/%m/%Y')  Like :search_term OR t_log_adresses.numero Like :search_term) and (DATE_FORMAT(t_param_log_visite_pa.datesys,'%Y-%m-%d')  between :du and :au) and  t_param_log_visite_pa.site_id=:site_id " . $filtre  . $user_filtre . "  and t_param_log_visite_pa.annule=" . $this->is_valid . " ORDER BY t_param_log_visite_pa.datesys desc LIMIT :from, :offset";
        $stmt = $this->connection->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(':search_term', $search_term);
        $stmt->bindParam(':du', $du);
        $stmt->bindParam(':au', $au);
        $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
        $stmt->bindValue(":site_id", $user_context->site_id);
        $stmt->execute();
        return $stmt;
    }


    function countAll_BySearch_advanced($du, $au, $search_term, $user_context, $filtre)
    {
        $user_filtre = $this->GetUserFilterSearch($user_context);
        $query = "SELECT COUNT(*) as total_rows  FROM t_param_log_visite_pa INNER JOIN t_log_adresses ON t_param_log_visite_pa.ref_adresse = t_log_adresses.id  INNER JOIN t_param_cvs ON t_param_log_visite_pa.cvs_id = t_param_cvs.`code` INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune ON t_log_adresses.commune_id = e_commune.`code` INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` WHERE (e_avenue.libelle Like :search_term or e_quartier.libelle Like :search_term or e_ville.libelle Like :search_term OR  e_commune.libelle Like :search_term  OR DATE_FORMAT(t_param_log_visite_pa.date_rendez_vous,'%d/%m/%Y')  Like :search_term OR t_log_adresses.numero Like :search_term) and (DATE_FORMAT(t_param_log_visite_pa.datesys,'%Y-%m-%d')  between :du and :au) and  t_param_log_visite_pa.site_id=:site_id " . $filtre  . $user_filtre . "  and t_param_log_visite_pa.annule=" . $this->is_valid;
        $stmt = $this->connection->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(':search_term', $search_term);
        $stmt->bindParam(':du', $du);
        $stmt->bindParam(':au', $au);
        $stmt->bindValue(":site_id", $user_context->site_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_rows'];
    }



    function search_advanced_DateOnly($du, $au, $from_record_num, $records_per_page, $user_context, $filtre)
    {
        $user_filtre = $this->GetUserFilterSearch($user_context);
        $query = "SELECT t_param_log_visite_pa.ref_log_visite,
                t_param_log_visite_pa.ref_adresse,
                t_param_log_visite_pa.datesys,DATE_FORMAT(t_param_log_visite_pa.datesys,'%d/%m/%Y %h:%i:%s') AS date_visite,
                t_param_log_visite_pa.date_update,
                t_param_log_visite_pa.is_sync,
                t_param_log_visite_pa.annule,
                t_param_log_visite_pa.statut_accessibilite,
                t_param_log_visite_pa.n_user_create,
                t_param_log_visite_pa.num_pa,
                t_param_log_visite_pa.type_motif_visite,
                t_param_log_visite_pa.site_id,
                t_param_log_visite_pa.cvs_id,
                t_param_log_visite_pa.commentaire,
                t_param_log_visite_pa.date_rendez_vous,DATE_FORMAT(t_param_log_visite_pa.date_rendez_vous,'%d/%m/%Y %h:%i:%s') AS date_rendez_vous_prev,
                t_log_adresses.numero,
                t_param_cvs.libelle, 
                t_log_adresses.avenue,
                e_avenue.libelle as avenue_lab, 
                e_quartier.libelle as quartier_lab,
                e_commune.libelle as commune_lab,
                e_ville.libelle as ville_lab 
            FROM t_param_log_visite_pa 
            INNER JOIN t_log_adresses 
                ON t_param_log_visite_pa.ref_adresse = t_log_adresses.id  
            INNER JOIN t_param_cvs ON t_param_log_visite_pa.cvs_id = t_param_cvs.`code` 
            INNER JOIN t_param_adresse_entity AS e_avenue 
                ON t_log_adresses.avenue = e_avenue.`code` 
            INNER JOIN t_param_adresse_entity AS e_quartier 
                ON t_log_adresses.quartier_id = e_quartier.`code` 
            INNER JOIN t_param_adresse_entity AS e_commune 
                ON t_log_adresses.commune_id = e_commune.`code` 
            INNER JOIN t_param_adresse_entity AS e_ville 
                ON t_log_adresses.ville_id = e_ville.`code`  
            WHERE (DATE_FORMAT(t_param_log_visite_pa.datesys,'%Y-%m-%d')  between :du and :au) and  t_param_log_visite_pa.site_id=:site_id " . $filtre  . $user_filtre . "  and t_param_log_visite_pa.annule=" . $this->is_valid . " ORDER BY t_param_log_visite_pa.datesys desc LIMIT :from, :offset";
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':du', $du);
        $stmt->bindParam(':au', $au);
        $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
        $stmt->bindValue(":site_id", $user_context->site_id);
        $stmt->execute();
        return $stmt;
    }



    function countAll_BySearch_advanced_DateOnly($du, $au, $user_context, $filtre)
    {
        $user_filtre = $this->GetUserFilterSearch($user_context);
        $query = "SELECT COUNT(*) as total_rows FROM t_param_log_visite_pa INNER JOIN t_log_adresses ON t_param_log_visite_pa.ref_adresse = t_log_adresses.id  INNER JOIN t_param_cvs ON t_param_log_visite_pa.cvs_id = t_param_cvs.`code` INNER JOIN t_param_adresse_entity AS e_avenue ON t_log_adresses.avenue = e_avenue.`code` INNER JOIN t_param_adresse_entity AS e_quartier ON t_log_adresses.quartier_id = e_quartier.`code` INNER JOIN t_param_adresse_entity AS e_commune ON t_log_adresses.commune_id = e_commune.`code` INNER JOIN t_param_adresse_entity AS e_ville ON t_log_adresses.ville_id = e_ville.`code` WHERE (DATE_FORMAT(t_param_log_visite_pa.datesys,'%Y-%m-%d')  between :du and :au) and  t_param_log_visite_pa.site_id=:site_id " . $filtre  . $user_filtre . "  and t_param_log_visite_pa.annule=" . $this->is_valid;
        $stmt = $this->connection->prepare($query);
        $stmt->bindParam(':du', $du);
        $stmt->bindParam(':au', $au);
        $stmt->bindValue(":site_id", $user_context->site_id);
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
}
