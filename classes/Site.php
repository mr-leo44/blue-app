<?php
class Site
{

    // database connection and table name
    private $conn;
    private $table_name = "t_param_site_production";

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
    private $connection;
    private $OFF_SET = ',';
    // private $OFF_SET=' OFFSET ';


    public function __construct($db)
    {
        $this->conn = $db;
    }

    function Create()
    {
        $this->intitule_site = htmlspecialchars(strip_tags($this->intitule_site));
        //verification duplicate
        $query = "select intitule_site from  " . $this->table_name . " where
		intitule_site=:intitule_site";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":intitule_site", $this->intitule_site);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $num = $stmt->rowCount();
        if ($num > 0) {
            $result["error"] = 1;
            $result["message"] = 'Il y a déjà un site nommé (' . $this->intitule_site . ')';
            return $result;
        }

        $query = "INSERT INTO " . $this->table_name . "  SET code_site=:code_site,intitule_site=:intitule_site,adresse_site=:adresse_site,contact_site=:contact_site,province_id=:province_id,n_user_create=:n_user_create";
        $stmt = $this->conn->prepare($query);
        $this->code_site = strip_tags($this->code_site);
        $this->intitule_site = strip_tags($this->intitule_site);
        $this->adresse_site = strip_tags($this->adresse_site);
        $this->contact_site = strip_tags($this->contact_site);
        $this->province_id = strip_tags($this->province_id);
        $this->n_user_create = strip_tags($this->n_user_create);
        $this->datesys = date("Y-m-d H:i:s");

        $stmt->bindParam(":code_site", $this->code_site);
        $stmt->bindParam(":intitule_site", $this->intitule_site);
        $stmt->bindParam(":adresse_site", $this->adresse_site);
        $stmt->bindParam(":contact_site", $this->contact_site);
        $stmt->bindParam(":province_id", $this->province_id);
        $stmt->bindParam(":n_user_create", $this->n_user_create);
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
        $this->intitule_site = htmlspecialchars(strip_tags($this->intitule_site));

        //verification duplicate
        $query = "select code_site,intitule_site from  " . $this->table_name . " where
		intitule_site=:intitule_site";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":intitule_site", $this->intitule_site);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $num = $stmt->rowCount();
        if ($num > 0) {
            if ($row["code_site"] == $this->code_site) {
            } else {
                $result["error"] = 1;
                $result["message"] = 'Il y a déjà un site nommé (' . $this->intitule_site . ')';
                return $result;
            }
        }

        $query = "UPDATE " . $this->table_name . "  SET intitule_site=:intitule_site,adresse_site=:adresse_site,contact_site=:contact_site,province_id=:province_id,n_user_update=:n_user_update,date_update=:date_update WHERE code_site=:code_site";
        $stmt = $this->conn->prepare($query);
        $this->code_site = strip_tags($this->code_site);
        $this->intitule_site = strip_tags($this->intitule_site);
        $this->adresse_site = strip_tags($this->adresse_site);
        $this->contact_site = strip_tags($this->contact_site);
        $this->province_id = strip_tags($this->province_id);
        $this->n_user_update = strip_tags($this->n_user_update);
        $this->date_update = strip_tags($this->date_update);
        $this->date_update = date("Y-m-d H:i:s");

        $stmt->bindParam(":code_site", $this->code_site);
        $stmt->bindParam(":intitule_site", $this->intitule_site);
        $stmt->bindParam(":adresse_site", $this->adresse_site);
        $stmt->bindParam(":contact_site", $this->contact_site);
        $stmt->bindParam(":province_id", $this->province_id);
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

    /* function Supprimer(){ 
        //write query
        $query = "DELETE FROM " . $this->table_name . " WHERE code_site=:code_site"; 
        $stmt = $this->conn->prepare($query);
        $this->code_site=htmlspecialchars(strip_tags($this->code_site));
		$stmt->bindParam(":code_site", $this->code_site);
		 if($stmt->execute()){
            return true;
        }else{
			return false;
        }
	 
	 }*/

    function Supprimer()
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE code_site=:code_site";
        $stmt = $this->conn->prepare($query);
        $this->code_site = strip_tags($this->code_site);
        $stmt->bindParam(":code_site", $this->code_site);
        if ($stmt->execute()) {
            $result["error"] = 0;
            $result["message"] = "Suppression effectuée avec succès";
        } else {
            $result["error"] = 1;
            $result["message"] = "L'opératon de la suppression a échoué.";
        }
        return $result;
    }

    // used by select drop-down list
    function read()
    {
        //select all data
        $query = "SELECT *
                FROM
                    " . $this->table_name . "
                ORDER BY
                    intitule_site";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    /*  function GetDetail(){
		 $query = "SELECT code_site as z,intitule_site as i,adresse_site as dr, province_id as v,commune_id as f   
			FROM " . $this->table_name . "
			WHERE code_site = ?
			LIMIT 0,1";
	 
		$stmt = $this->conn->prepare( $query );
		 $this->code_site=htmlspecialchars(strip_tags($this->code_site));
		$stmt->bindParam(1,$this->code_site);
		$stmt->execute(); 
		$row = $stmt->fetch(PDO::FETCH_ASSOC); 
		return $row;
    }*/
    function GetDetail()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE code_site = ? 	LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $this->code_site = strip_tags($this->code_site);
        $stmt->bindParam(1, $this->code_site);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    function GetDetailIN()
    {
        $query = "SELECT t_param_site_production.code_site,t_param_site_production.intitule_site,t_param_site_production.adresse_site,t_param_site_production.province_id FROM t_param_site_production 
			WHERE code_site = ?
			LIMIT 0" . $this->OFF_SET . "1";

        $stmt = $this->conn->prepare($query);
        $this->code_site = htmlspecialchars(strip_tags($this->code_site));
        $stmt->bindParam(1, $this->code_site);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (isset($row['code_site'])) {
            $this->code_site = $row['code_site'];
        }

        if (isset($row['intitule_site'])) {
            $this->intitule_site = $row['intitule_site'];
        }

        if (isset($row['province_id'])) {
            $this->province_id = $row['province_id'];
        }
    }

    function GetSiteAccessibleForProvince($user, $province)
    {
        $query = 'SELECT t_param_site_production.code_site,t_param_site_production.intitule_site FROM t_param_site_production  INNER JOIN t_utilisateur_site_accessible ON t_param_site_production.code_site = t_utilisateur_site_accessible.code_site WHERE t_param_site_production.province_id =:province_id and t_utilisateur_site_accessible.code_user=:code_user';
        $stmt = $this->conn->prepare($query);
        $province = htmlspecialchars(strip_tags($province));
        $stmt->bindParam(":province_id", $province);
        $stmt->bindParam(":code_user", $user);
        $stmt->execute();
        return $stmt;
    }

    function GetAll()
    {
        $query = "SELECT code_site as code , intitule_site as libelle  FROM t_param_site_production";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }


    function GetAllSiteAccessibleForUser($user_context, $multi = false)
    {
        $query = "SELECT t_param_site_production.code_site,t_param_site_production.intitule_site,t_param_site_production.annule,t_utilisateur_site_accessible.code_user
FROM t_param_site_production INNER JOIN t_utilisateur_site_accessible ON t_param_site_production.code_site = t_utilisateur_site_accessible.code_site WHERE t_utilisateur_site_accessible.code_user=:code_user";
        //$code_user=(strip_tags($code_user));
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":code_user", $user_context->code_utilisateur);
        $stmt->execute();

        return $stmt;
    }

    function GetAllSiteAccessibleForUserExcludeDefault($user_context, $multi = false)
    {
        $query = "SELECT t_param_site_production.code_site,t_param_site_production.intitule_site,t_param_site_production.annule,t_utilisateur_site_accessible.code_user
FROM t_param_site_production INNER JOIN t_utilisateur_site_accessible ON t_param_site_production.code_site = t_utilisateur_site_accessible.code_site WHERE t_utilisateur_site_accessible.code_user=:code_user and t_utilisateur_site_accessible.code_site!=:default_site";
        //$code_user=(strip_tags($code_user));
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(":code_user", $user_context->code_utilisateur);
        $stmt->bindValue(":default_site", $user_context->site_id);
        $stmt->execute();

        return $stmt;
    }
    /*
    function GetAllSiteAccessibleForUser($code_user){
		 $query = "SELECT t_param_site_production.code_site,t_param_site_production.intitule_site,t_param_site_production.annule,t_utilisateur_site_accessible.code_user
FROM t_param_site_production INNER JOIN t_utilisateur_site_accessible ON t_param_site_production.code_site = t_utilisateur_site_accessible.code_site WHERE t_utilisateur_site_accessible.code_user=:code_user";
		$code_user=(strip_tags($code_user));
		$stmt = $this->conn->prepare( $query );		
		$stmt->bindValue(":code_user",$code_user);
		$stmt->execute();	
return $stmt;		
	}*/

    function SiteAccessibleForUser($code_user)
    {
        $query = "SELECT id_,code_user,code_site FROM t_utilisateur_site_accessible WHERE code_user = ?";
        $stmt = $this->conn->prepare($query);
        $code_user = htmlspecialchars(strip_tags($code_user));
        $stmt->bindParam(1, $code_user);
        $stmt->execute();
        $row_access = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $list_droits = "";
        $query_group_assign = " SELECT code_site,intitule_site,annule FROM " . $this->table_name . "  where annule=0 order by intitule_site";
        $stmt_group_assign = $this->conn->prepare($query_group_assign);
        $stmt_group_assign->execute();
        while ($row = $stmt_group_assign->fetch(PDO::FETCH_ASSOC)) {
            $list_droits .= '<li class="list-group-item rounded-0">
                                <div class="custom-control custom-checkbox">';
            $granted = false;
            foreach ($row_access as $revP) {
                if ($revP['code_site'] == $row["code_site"]) {
                    $granted = true;
                    break;
                }
            }
            if ($granted == false) {
                $list_droits .= '<input class="custom-control-input" id="chk_' . $row["code_site"] . '" name="tbl-checkbox[]" type="checkbox" value="' . $row["code_site"] . '">';
            } else {
                $list_droits .= '<input class="custom-control-input" id="chk_' . $row["code_site"] . '" name="tbl-checkbox[]" type="checkbox"  checked="checked"  value="' . $row["code_site"] . '">';
            }
            $list_droits .= '<label class="cursor-pointer font-italic d-block custom-control-label" for="chk_' . $row["code_site"] . '"> ' . $row["intitule_site"] . '</label>
						</div>
					</li>';
        }
        return $list_droits;
    }


    function GrantSiteAccess($POST, $user)
    {
        $user = htmlspecialchars(strip_tags($user));
        $datesys = date("Y-m-d H:i:s");
        $query_ven = "delete from t_utilisateur_site_accessible  where code_user=:code_user";
        $stmt_ven = $this->conn->prepare($query_ven);
        $stmt_ven->bindValue(':code_user', $user);
        $stmt_ven->execute();


        $query = "INSERT INTO t_utilisateur_site_accessible (id_,code_user,code_site,n_user_create,datesys) values (:id_,:code_user,:code_site,:n_user_create,:datesys);";
        $stmt = $this->conn->prepare($query);
        //$k => $v
        foreach ($POST as $value) {
            $id_assign = $this->uniqUid("t_utilisateur_site_accessible", "id_");

            $value = htmlspecialchars(strip_tags($value));
            $stmt->bindValue(':id_', $id_assign);
            $stmt->bindValue(':code_user', $user);
            $stmt->bindValue(':code_site', $value);
            $stmt->bindValue(':n_user_create', $this->n_user_create);
            $stmt->bindValue(':datesys', $datesys);
            $stmt->execute();
        }
        $result["error"] = 0;
        $result["message"] = "Opération effectuée avec succès";
        $result["data"] = null;
        return $result;
    }

    function GrantArticleAccess($POST, $c)
    {
        $c = htmlspecialchars(strip_tags($c));
        $datesys = date("Y-m-d H:i:s");
        $query_ven = "delete from t_site_article_accessible  where code_site=:code_site";
        $stmt_ven = $this->conn->prepare($query_ven);
        $stmt_ven->bindValue(':code_site', $c);
        $stmt_ven->execute();


        $this->n_user_create = htmlspecialchars(strip_tags($this->n_user_create));
        $query = "INSERT INTO t_site_article_accessible (id_,code_article,code_site,n_user_create,datesys) values (:id_,:code_article,:code_site,:n_user_create,:datesys);";
        $stmt = $this->conn->prepare($query);
        //$k => $v
        foreach ($POST as $value) {
            $id_assign = $this->uniqUid("t_utilisateur_site_accessible", "id_");

            $value = htmlspecialchars(strip_tags($value));
            $stmt->bindValue(':id_', $id_assign);
            $stmt->bindValue(':code_article', $value);
            $stmt->bindValue(':code_site', $c);
            $stmt->bindValue(':n_user_create', $this->n_user_create);
            $stmt->bindValue(':datesys', $datesys);
            $stmt->execute();
        }
        $result["error"] = 0;
        $result["message"] = "Opération effectuée avec succès";
        $result["data"] = null;
        return $result;
    }



    function ArticleAccessibleForSite()
    {
        $query = "SELECT id_,code_article,code_site FROM t_site_article_accessible WHERE code_site = ?";
        $stmt = $this->conn->prepare($query);
        $this->code_site = htmlspecialchars(strip_tags($this->code_site));
        $stmt->bindParam(1, $this->code_site);
        $stmt->execute();
        $row_access = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $list_droits = "";
        $query_group_assign = " SELECT ref_produit,designation,annule FROM t_produit  where annule=0 order by designation";
        $stmt_group_assign = $this->conn->prepare($query_group_assign);
        $stmt_group_assign->execute();
        while ($row = $stmt_group_assign->fetch(PDO::FETCH_ASSOC)) {
            $list_droits .= '<li class="list-group-item rounded-0">
                                <div class="custom-control custom-checkbox">';
            $granted = false;
            foreach ($row_access as $revP) {
                if ($revP['code_article'] == $row["ref_produit"]) {
                    $granted = true;
                    break;
                }
            }
            if ($granted == false) {
                $list_droits .= '<input class="custom-control-input" id="chk_' . $row["ref_produit"] . '" name="tbl-checkbox[]" type="checkbox" value="' . $row["ref_produit"] . '">';
            } else {
                $list_droits .= '<input class="custom-control-input" id="chk_' . $row["ref_produit"] . '" name="tbl-checkbox[]" type="checkbox"  checked="checked"  value="' . $row["ref_produit"] . '">';
            }
            $list_droits .= '<label class="cursor-pointer font-italic d-block custom-control-label" for="chk_' . $row["ref_produit"] . '"> ' . $row["designation"] . '</label>
						</div>
					</li>';
        }
        return $list_droits;
    }


    function readAll($from_record_num, $records_per_page)
    {
        $query = "SELECT code_site,intitule_site,adresse_site,contact_site,province_id,annule,n_user_annule,date_annule FROM " . $this->table_name . " ORDER BY intitule_site ASC LIMIT {$from_record_num}, {$records_per_page}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function search($search_term, $from_record_num, $records_per_page)
    {
        $query = "SELECT code_site,intitule_site,adresse_site,contact_site,province_id,annule,n_user_annule,date_annule  FROM " . $this->table_name  . " WHERE intitule_site LIKE :search_term  ORDER BY intitule_site ASC LIMIT :from, :offset";
        $stmt = $this->conn->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(':search_term', $search_term);
        $stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }
    public function countAll()
    {
        $query = "SELECT code_site FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $num = $stmt->rowCount();
        return $num;
    }
    public function countAll_BySearch($search_term)
    {
        $query = "SELECT COUNT(*) as total_rows FROM  " . $this->table_name . " WHERE intitule_site LIKE :search_term";
        $stmt = $this->conn->prepare($query);
        $search_term = "%{$search_term}%";
        $stmt->bindParam(":search_term", $search_term);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row["total_rows"];
    }

    function uniqUid($table, $key_fld)
    {
        //uniq gives 13 CHARS BUT YOU COULD ADJUST IT TO YOUR NEEDS
        $bytes = md5(mt_rand());
        //Phase 2 verification existance avant retour code
        if ($this->VerifierExistance($key_fld, $bytes, $table)) {
            $bytes = uniqUid($table, $key_fld);
        }
        return $bytes;
        //return substr(bin2hex($bytes),0,$len);
    }

    function VerifierExistance($pKey, $NoGenerated, $table)
    {
        //global $cnx;	
        $retour = false;
        $sql = "select $pKey from $table where $pKey=:NoGenerated";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(":NoGenerated", $NoGenerated);
        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $retour = true;
        } else {
            $retour = false;
        }
        return $retour;
    }
}
