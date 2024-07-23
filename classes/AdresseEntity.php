<?php

//var_dump(FS_PATH);

//require_once FS_PATH.'utils/PHPExcel-1.8/Classes/PHPExcel.php';
class AdresseEntity
{

	public function __construct($db)
	{
		$this->connection = $db;
	}
	/* public $code;
  public $libelle;
  public $annule;
  public $n_user_annule;
  public $motif_annulation;
  public $date_synchro;*/
	public $is_sync;
	private $table_name = 't_param_adresse_entity';

	public $n_user_create;
	public $datesys;
	public $n_user_update;
	public $date_update;

	public $code;
	public $category_id;
	public $parent_id;
	public $libelle;
	private $connection;

	//ADRESSE
	public $quartier_id;
	public $commune_id;
	public $ville_id;
	public $province_id;
	public $numero;
	public $avenue;
	//ADRESSE

	function GetOrCreateAdressId($ville_id, $commune_id, $quartier_id, $avenue, $numero)
	{
		$this->datesys = date("Y-m-d H:i:s");
		$id_adress = '';
		//Récupération et création LOG Adresse 
		$stmt = $this->connection->prepare('SELECT id,quartier_id,commune_id,ville_id,province_id,numero,avenue,is_deleted,status  FROM t_log_adresses where quartier_id=:quartier_id and commune_id=:commune_id and ville_id=:ville_id and numero=:numero and avenue=:avenue ');

		$stmt->bindValue(":quartier_id", $quartier_id);
		$stmt->bindValue(":commune_id", $commune_id);
		$stmt->bindValue(":ville_id", $ville_id);
		//$stmt->bindValue(":province_id",$this->province_id);
		$stmt->bindValue(":numero", $numero);
		$stmt->bindValue(":avenue", $avenue);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$row) {
			//A AJOUTER VERIFIER SI LE NUMERO DU COMPTEUR EXISTE DANS LA TABLE DES COMPTEURS
			//echo 'nothing found';
			//CREER
			$stmt = $this->connection->prepare('INSERT INTO t_log_adresses(id,quartier_id,commune_id,ville_id,numero,avenue,n_user_create,datesys) values (:id,:quartier_id,:commune_id,:ville_id,:numero,:avenue,:n_user_create,:datesys)');
			$id_adress = Utils::uniqUid('t_log_adresses', 'id',  $this->connection);
			$stmt->bindValue(":id", $id_adress);
			$stmt->bindValue(":quartier_id", $quartier_id);
			$stmt->bindValue(":commune_id", $commune_id);
			$stmt->bindValue(":ville_id", $ville_id);
			//$stmt->bindValue(":province_id",$this->province_id);
			$stmt->bindValue(":numero", $numero);
			$stmt->bindValue(":avenue", $avenue);
			$stmt->bindValue(":n_user_create", $this->n_user_create);
			$stmt->bindValue(":datesys", $this->datesys);
			$stmt->execute();
		} else {
			$id_adress = $row['id'];
		}
		return $id_adress;
	}

	function GetAdressInfo($_id)
	{
		$stmt = $this->connection->prepare('SELECT id,quartier_id,commune_id,ville_id,province_id,numero,avenue  FROM t_log_adresses where id=:id');
		$stmt->bindValue(":id", $_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}

	function GetAdressMenage($_id)
	{
		$stmt = $this->connection->prepare('SELECT id,id_adress,nom,postnom,prenom,lieu_naissance,sexe,motif,phone_number,num_piece_identity,statut_identity  FROM t_param_identite where id_adress=:id_adress and annule=0 ORDER BY nom,postnom,prenom');
		$stmt->bindValue(":id_adress", $_id);
		$stmt->execute();
		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$result["count"] = $stmt->rowCount();
		$result["data"] = $row;
		return $result;
	}

	function CreateOrUpdateIdentite($identite_adress_id, $identite_nom, $identite_postnom, $identite_prenom, $identite_sexe, $identite_lieu, $identite_piece, $identite_phone, $identite_statut, $site_id)
	{
		$this->datesys = date("Y-m-d H:i:s");

		$stmt_insert = $this->connection->prepare('INSERT INTO t_param_identite SET  id=:id,id_adress=:id_adress,nom=:nom,postnom=:postnom,prenom=:prenom,lieu_naissance=:lieu_naissance,sexe=:sexe,phone_number=:phone_number,num_piece_identity=:num_piece_identity,statut_identity=:statut_identity,user_create=:n_user_create,date_create=:date_create,site_id=:site_id');
		$stmt_update = $this->connection->prepare('UPDATE t_param_identite SET  id_adress=:id_adress,nom=:nom,postnom=:postnom,prenom=:prenom,lieu_naissance=:lieu_naissance,sexe=:sexe,phone_number=:phone_number,num_piece_identity=:num_piece_identity,statut_identity=:statut_identity,user_update=:user_update,date_update=:date_update,site_id=:site_id,annule=:annule  WHERE id=:id');

		$stmt = $this->connection->prepare('SELECT id,id_adress,nom,postnom,prenom  FROM t_param_identite where id_adress=:id_adress and nom=:nom and postnom=:postnom and prenom=:prenom');
		$stmt->bindValue(":id_adress", $identite_adress_id);
		$stmt->bindValue(":nom", $identite_nom);
		$stmt->bindValue(":postnom", $identite_postnom);
		$stmt->bindValue(":prenom", $identite_prenom);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$row) {
			$identite_id = Utils::uniqUid('t_param_identite', 'id',  $this->connection);
			$stmt_insert->bindValue(":id", $identite_id);
			$stmt_insert->bindValue(":id_adress", $identite_adress_id);
			$stmt_insert->bindValue(":nom", $identite_nom);
			$stmt_insert->bindValue(":postnom", $identite_postnom);
			$stmt_insert->bindValue(":prenom", $identite_prenom);
			$stmt_insert->bindValue(":lieu_naissance", $identite_lieu);
			$stmt_insert->bindValue(":sexe", $identite_sexe);
			$stmt_insert->bindValue(":phone_number", $identite_phone);
			$stmt_insert->bindValue(":num_piece_identity", $identite_piece);
			$stmt_insert->bindValue(":statut_identity", $identite_statut);
			$stmt_insert->bindValue(":n_user_create", $this->n_user_create);
			$stmt_insert->bindValue(":date_create", $this->datesys);
			$stmt_insert->bindValue(":site_id", $site_id);
			$stmt_insert->execute();
		} else {
			$identite_id = $row['id'];
			$stmt_update->bindValue(":id", $identite_id);
			$stmt_update->bindValue(":id_adress", $identite_adress_id);
			$stmt_update->bindValue(":nom", $identite_nom);
			$stmt_update->bindValue(":postnom", $identite_postnom);
			$stmt_update->bindValue(":prenom", $identite_prenom);
			$stmt_update->bindValue(":lieu_naissance", $identite_lieu);
			$stmt_update->bindValue(":sexe", $identite_sexe);
			$stmt_update->bindValue(":phone_number", $identite_phone);
			$stmt_update->bindValue(":num_piece_identity", $identite_piece);
			$stmt_update->bindValue(":statut_identity", $identite_statut);
			$stmt_update->bindValue(":user_update", $this->n_user_create);
			$stmt_update->bindValue(":date_update", $this->datesys);
			$stmt_update->bindValue(":site_id", $site_id);
			$stmt_update->bindValue(":annule", 0);
			$stmt_update->execute();
		}
		$result["identite_id"] = $identite_id;
		return $result;
	}

	function UpdateIdentite($identite_id, $identite_nom, $identite_postnom, $identite_prenom, $identite_sexe, $identite_lieu, $identite_piece, $identite_phone, $identite_statut, $site_id)
	{

		$result = array();
		$this->datesys = date("Y-m-d H:i:s");

		$stmt_update = $this->connection->prepare('UPDATE t_param_identite SET  nom=:nom,postnom=:postnom,prenom=:prenom,lieu_naissance=:lieu_naissance,sexe=:sexe,phone_number=:phone_number,num_piece_identity=:num_piece_identity,statut_identity=:statut_identity,user_update=:user_update,date_update=:date_update,site_id=:site_id WHERE id=:id');

		$stmt = $this->connection->prepare('SELECT id,nom,postnom,prenom  FROM t_param_identite where nom=:nom and postnom=:postnom and prenom=:prenom');
		$stmt->bindValue(":nom", $identite_nom);
		$stmt->bindValue(":postnom", $identite_postnom);
		$stmt->bindValue(":prenom", $identite_prenom);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		if ($row && $row["id"] != trim($identite_id)) {
			$result["is_done"] = false;
			$result["message"] = "Vous avez déjà un menage nommé " . $identite_nom . ' ' . $identite_postnom . ' ' . $identite_prenom;
		} else {
			$stmt_update->bindValue(":id", $identite_id);
			$stmt_update->bindValue(":nom", $identite_nom);
			$stmt_update->bindValue(":postnom", $identite_postnom);
			$stmt_update->bindValue(":prenom", $identite_prenom);
			$stmt_update->bindValue(":lieu_naissance", $identite_lieu);
			$stmt_update->bindValue(":sexe", $identite_sexe);
			$stmt_update->bindValue(":phone_number", $identite_phone);
			$stmt_update->bindValue(":num_piece_identity", $identite_piece);
			$stmt_update->bindValue(":statut_identity", $identite_statut);
			$stmt_update->bindValue(":user_update", $this->n_user_create);
			$stmt_update->bindValue(":date_update", $this->datesys);
			$stmt_update->bindValue(":site_id", $site_id);
			$IsDone = $stmt_update->execute();
			if ($IsDone == true) {
				$result["is_done"] = true;
			} else {
				$result["is_done"] = false;
				$result["message"] = "Echec de la mise à jour du menage";
			}
		}
		return $result;
	}


	function DeleteIdentite($identite_id, $invalidation_motif)
	{
		$result = array();

		$this->datesys = date("Y-m-d H:i:s");
		$stmt = $this->connection->prepare('UPDATE t_param_identite SET annule=1,motif=:motif,date_annule=:date_annule,user_annule=:user_annule where id=:id');
		$stmt->bindValue(":id", $identite_id);
		$stmt->bindValue(":user_annule", $this->n_user_create);
		$stmt->bindValue(":motif", $invalidation_motif);
		$stmt->bindValue(":date_annule", $this->datesys);
		if ($stmt->execute()) {
			$result["error"] = 0;
			$result["message"] = "Invalidation effectuée avec succès";
		} else {
			$result["error"] = 1;
			$result["message"] = "Echec de l'invalidation";
		}
		return $result;
	}

	function GetMenageDetail($_id)
	{
		$stmt = $this->connection->prepare("SELECT id,id_adress,trim(concat(coalesce(nom,''),' ',coalesce(postnom,''),' ',coalesce(prenom,''))) as noms,nom,postnom,prenom,lieu_naissance,sexe,motif,phone_number,num_piece_identity,statut_identity  FROM t_param_identite WHERE id=:id");

		$stmt->bindValue(":id", $_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}

	function GetLabel($_id)
	{
		$stmt = $this->connection->prepare('select code,libelle,category_id FROM t_param_adresse_entity where code=:id');
		$stmt->bindValue(":id", $_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['libelle'];
	}


	function GetAdressInfoTexte($_id)
	{
		$stmt = $this->connection->prepare('SELECT id,quartier_id,commune_id,ville_id,province_id,numero,avenue  FROM t_log_adresses where id=:id');
		$stmt->bindValue(":id", $_id);

		$cacher = new Cacher();

		$row = $cacher->get(['adresseEntity-get-adress-info-text', $_id], function () use ($stmt) {
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
		});

		$this->code = 	$row['ville_id'];
		$ville_id = $this->GetDetail();

		$this->code = 	$row['commune_id'];
		$commune_id = $this->GetDetail();

		$this->code = 	$row['quartier_id'];
		$quartier_id = $this->GetDetail();

		$this->code = 	$row['avenue'];
		$avenue = $this->GetDetail();

		$numero = $row['numero'];

		$adresse_texte = $ville_id['libelle'] . ", N° " . $numero . " Av/" . $avenue['libelle'] . " Q/" . $quartier_id['libelle'] . " C/" . $commune_id['libelle'];

		//code,category_id,parent_id,libelle 
		return $adresse_texte;
	}
	function Create()
	{
		$this->libelle = strip_tags($this->libelle);
		//verification duplicate
		$query = "select code,libelle,category_id from  " . $this->table_name . " where
		libelle=:libelle and parent_id=:parent_id";
		$stmt = $this->connection->prepare($query);
		$stmt->bindValue(":libelle", $this->libelle);
		$stmt->bindValue(":parent_id", $this->parent_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if ($num > 0) {
			$result["error"] = 1;
			$result["message"] =  $this->libelle . ' existe déjà';
			return $result;
		}


		$query = "INSERT INTO " . $this->table_name . "  SET code=:code,category_id=:category_id,parent_id=:parent_id,libelle=:libelle,n_user_create=:n_user_create,datesys=:datesys";
		$stmt = $this->connection->prepare($query);
		// $this->code=strip_tags($this->code);
		$this->n_user_create = strip_tags($this->n_user_create);
		$this->datesys = date("Y-m-d H:i:s");

		$stmt->bindParam(":code", $this->code);
		$stmt->bindParam(":libelle", $this->libelle);
		$stmt->bindParam(":category_id", $this->category_id);
		$stmt->bindParam(":parent_id", $this->parent_id);
		$stmt->bindParam(":datesys", $this->datesys);
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
		//verification duplicate
		$query = "select code,libelle,category_id from  " . $this->table_name . " where
		libelle=:libelle and parent_id=:parent_id";
		$stmt = $this->connection->prepare($query);
		$stmt->bindValue(":libelle", $this->libelle);
		$stmt->bindValue(":parent_id", $this->parent_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if ($num > 0) {
			if ($row["code"] != $this->code) {
				$result["error"] = 1;
				$result["message"] =  $this->libelle . ' existe déjà';
				return $result;
			}
		}

		$query = "UPDATE " . $this->table_name . "  SET  libelle=:libelle,n_user_update=:n_user_update,date_update=:date_update WHERE code=:code";
		$stmt = $this->connection->prepare($query);

		$this->n_user_update = strip_tags($this->n_user_update);
		$this->date_update = date("Y-m-d H:i:s");


		$stmt->bindParam(":code", $this->code);
		$stmt->bindParam(":libelle", $this->libelle);
		// $stmt->bindParam(":parent_id", $this->parent_id);
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
		$query = "DELETE FROM " . $this->table_name . " WHERE code=:code";
		$stmt = $this->connection->prepare($query);
		$this->code = strip_tags($this->code);
		$stmt->bindParam(":code", $this->code);
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
		$query = "SELECT code,category_id,parent_id,libelle FROM " . $this->table_name . " WHERE code = ? 	LIMIT 0,1";
		$stmt = $this->connection->prepare($query);
		$this->code = strip_tags($this->code);
		$stmt->bindParam(1, $this->code);
		
		$cacher = new Cacher();
		$row = $cacher->get(['adresseEntity-get-detail', $this->code], function () use ($stmt) {
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
		});

		return $row;
	}

	function GetProvinceAllCommune($id_)
	{
		$query = $query = "SELECT code,libelle  
			FROM " . $this->table_name . "
			WHERE parent_id =:id_province order by libelle";
		$stmt = $this->connection->prepare($query);
		$id_ = (strip_tags($id_));
		$stmt->bindParam(":id_province", $id_);
		$stmt->execute();
		$liste_ville = array();
		$liste_commune = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$liste_ville[] = $row['code'];
		}
		$liste_site_label = implode("','", $liste_ville);
		$liste_site_label = "'" . $liste_site_label . "'";

		//LISTE DES COMMUNES
		$query = $query = "SELECT code,libelle  
			FROM " . $this->table_name . "
			WHERE parent_id in (" . $liste_site_label . ") order by libelle";
		$stmt = $this->connection->prepare($query);
		$stmt->execute();
		return $stmt;
	}
	function GetProvinceAllCVS($id_)
	{
		$query = $query = "SELECT code,libelle  
			FROM " . $this->table_name . "
			WHERE parent_id =:id_province order by libelle";
		$stmt = $this->connection->prepare($query);
		$id_ = (strip_tags($id_));
		$stmt->bindParam(":id_province", $id_);
		$stmt->execute();
		$liste_ville = array();
		$liste_commune = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$liste_ville[] = $row['code'];
		}
		$liste_site_label = implode("','", $liste_ville);
		$liste_site_label = "'" . $liste_site_label . "'";

		//LISTE DES COMMUNES
		$query = $query = "SELECT code,libelle  
			FROM " . $this->table_name . "
			WHERE parent_id in (" . $liste_site_label . ") order by libelle";
		$stmt = $this->connection->prepare($query);
		$stmt->execute();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$liste_commune[] = $row['code'];
		}
		$liste_site_label = implode("','", $liste_commune);
		$liste_site_label = "'" . $liste_site_label . "'";

		//LISTE DES CVS POOUR COMMUNES
		$query = $query = "SELECT code,libelle   FROM t_param_cvs WHERE id_commune in (" . $liste_site_label . ") order by libelle";
		$stmt = $this->connection->prepare($query);
		$stmt->execute();
		return $stmt;
	}

	function GetProvinceVille($id_)
	{
		$query = $query = "SELECT code,libelle  
			FROM " . $this->table_name . "
			WHERE parent_id =:id_province order by libelle";
		$stmt = $this->connection->prepare($query);
		$id_ = (strip_tags($id_));
		$stmt->bindParam(":id_province", $id_);
		$stmt->execute();
		return $stmt;
	}
	function GetVilleCommuneTerritoire($id_)
	{
		$query = $query = "SELECT code,libelle  
			FROM " . $this->table_name . "
			WHERE parent_id =:id_province order by libelle";
		$stmt = $this->connection->prepare($query);
		$id_ = (strip_tags($id_));
		$stmt->bindParam(":id_province", $id_);
		$stmt->execute();
		return $stmt;
	}

	function GetCommuneQuartier($id_)
	{
		$query = $query = "SELECT code,libelle  
			FROM " . $this->table_name . "
			WHERE parent_id =:id_p order by libelle";
		$stmt = $this->connection->prepare($query);
		$id_ = (strip_tags($id_));
		$stmt->bindParam(":id_p", $id_);
		$stmt->execute();
		return $stmt;
	}
	function FetAllChild($id_)
	{
		$query = $query = "SELECT code,libelle  
			FROM " . $this->table_name . "
			WHERE parent_id =:id_p order by libelle";
		$stmt = $this->connection->prepare($query);
		$id_ = (strip_tags($id_));
		$stmt->bindParam(":id_p", $id_);
		$stmt->execute();
		return $stmt;
	}

	function GetDetailIN()
	{
		$query = "SELECT code,category_id,parent_id,libelle FROM " . $this->table_name . " WHERE code = ? 	LIMIT 0,1";
		$stmt = $this->connection->prepare($query);
		$this->code = strip_tags($this->code);
		$stmt->bindParam(1, $this->code);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$this->code = $row["code"];
		$this->libelle = $row["libelle"];
	}
	function read($category_id)
	{
		$query = "SELECT code,category_id,parent_id,libelle  FROM " . $this->table_name . " where category_id = :category_id  ORDER BY libelle";
		$stmt = $this->connection->prepare($query);
		$stmt->bindParam(":category_id", $category_id);
		$stmt->execute();
		return $stmt;
	}
	function readFilter($category_id, $parent_id)
	{
		$query = "SELECT code,category_id,parent_id,libelle  FROM " . $this->table_name . " where category_id = :category_id and parent_id = :id_p ORDER BY libelle";
		$stmt = $this->connection->prepare($query);
		$stmt->bindParam(":category_id", $category_id);
		$stmt->bindParam(":id_p", $parent_id);
		$stmt->execute();
		return $stmt;
	}

	function getAllProvinces()
	{
		$query = "SELECT code,category_id,parent_id,libelle FROM " . $this->table_name . " where category_id = 3 ORDER BY libelle ASC ";
		$stmt = $this->connection->prepare($query);
		$stmt->execute();

		return $stmt;
	}

	function readAll($from_record_num, $records_per_page, $category_id)
	{
		$query = "SELECT code,category_id,parent_id,libelle FROM " . $this->table_name . " where category_id = :category_id ORDER BY libelle ASC LIMIT {$from_record_num}, {$records_per_page}";
		$stmt = $this->connection->prepare($query);
		$stmt->bindParam(":category_id", $category_id);
		$stmt->execute();
		return $stmt;
	}
	public function search($search_term, $from_record_num, $records_per_page, $category_id)
	{
		$query = "SELECT code,category_id,parent_id,libelle  FROM " . $this->table_name  . " WHERE (libelle LIKE :search_term and category_id = :category_id)  ORDER BY libelle ASC LIMIT :from, :offset";
		$stmt = $this->connection->prepare($query);
		$search_term = "%{$search_term}%";
		$stmt->bindParam(':search_term', $search_term);
		$stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(':category_id', $category_id, $category_id);
		$stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);
		$stmt->execute();
		return $stmt;
	}
	public function countAll($category_id)
	{
		$query = "SELECT code FROM " . $this->table_name . " where category_id = :category_id";
		$stmt = $this->connection->prepare($query);
		$stmt->bindParam(":category_id", $category_id);
		$stmt->execute();
		$num = $stmt->rowCount();
		return $num;
	}
	public function countAll_BySearch($search_term, $category_id)
	{
		$query = "SELECT COUNT(*) as total_rows FROM  " . $this->table_name . " WHERE libelle LIKE :search_term and  category_id = :category_id";
		$stmt = $this->connection->prepare($query);
		$search_term = "%{$search_term}%";
		$stmt->bindParam(":search_term", $search_term);
		$stmt->bindParam(":category_id", $category_id);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row["total_rows"];
	}



	//VERIFICATION AUTOMATIC DOSSIER ET CREATION A AJOUTER
	public function import($FILES, $user_context)
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
					$file = "uploads/" . $_FILES['frm']['name'];
					$isUploaded = copy($_FILES['frm']['tmp_name'], $file);
					if ($isUploaded) {
						/* include("db.php");
                    include("Classes/PHPExcel/IOFactory.php");*/
						try {
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

							$stmt_select = $this->connection->prepare('SELECT code,category_id,parent_id,libelle FROM t_param_adresse_entity where libelle=:libelle AND parent_id=:parent_id');

							$query = "INSERT INTO " . $this->table_name . "  SET code=:code,n_user_create=:n_user_create,category_id=:category_id,parent_id=:parent_id,libelle=:libelle";
							$stmt = $this->connection->prepare($query);


							$query = "UPDATE " . $this->table_name . "  SET n_user_update=:n_user_update,category_id=:category_id,parent_id=:parent_id,libelle=:libelle where code=:code";
							$stmt_update = $this->connection->prepare($query);


							//A REVOIR LATER
							$query = "DELETE FROM  " . $this->table_name . "   where parent_id=:parent_id";
							$stmt_delete = $this->connection->prepare($query);
							$stmt_delete->bindValue(':parent_id', $this->parent_id);
							$stmt_delete->execute();



							$has_ro = 0;
							for ($row = 2; $row <= $total_rows; $row++) {
								$has_ro++;
								$single_row = $sheet->rangeToArray('A' . $row . ':' . $total_columns . $row, NULL, TRUE, FALSE);
								$str = trim($single_row[0][0]);
								//$libelle = preg_replace("/\s+/", "", $str);
								$libelle =  $str;
								if (strlen($libelle) > 0) {
									$stmt_select->bindValue(':parent_id', $this->parent_id);
									$stmt_select->bindValue(':libelle', $libelle);
									$stmt_select->execute();
									$data_row = $stmt_select->fetch(PDO::FETCH_ASSOC);
									if (!$data_row) {
										$ref_import = Utils::uniqUid("t_param_adresse_entity", "code", $this->connection);
										$stmt->bindParam(":code", $ref_import);
										$stmt->bindParam(":n_user_create", $this->n_user_create);
										$stmt->bindParam(":category_id", $this->category_id);
										$stmt->bindParam(":parent_id", $this->parent_id);
										$stmt->bindParam(":libelle", $libelle);
										$stmt->execute();
									} else {
										$stmt_update->bindParam(":code", $data_row['code']);
										$stmt_update->bindParam(":n_user_update", $this->n_user_create);
										$stmt_update->bindParam(":category_id", $this->category_id);
										$stmt_update->bindParam(":parent_id", $this->parent_id);
										$stmt_update->bindParam(":libelle", $libelle);
										$stmt_update->execute();
									}
								}
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
						// At last we will execute the dynamically created query an save it into the database
						//mysqli_query($con, $query);
						/* if(mysqli_affected_rows($con) > 0) {    
                        echo '<span class="msg">Database table updated!</span>';
                    } else {
                        echo '<span class="msg">Can\'t update database table! try again.</span>';
                    } */
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
					$result["message"] = "La taille maximale du fichier requise est 1024kb";
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



		return 	$result;
	}
}
