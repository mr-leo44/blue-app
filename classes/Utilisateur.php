<?php
class Utilisateur
{

	private $conn;
	private $table_name = "t_utilisateurs";
	public $code_utilisateur;
	public $nom_utilisateur;
	public $nom_complet;
	public $mot_de_passe;
	public $n_user_update;
	public $n_user_create;
	public $date_update;
	public $datesys;
	public $annule;
	public $n_user_annule;
	public $motif_annulation;
	public $date_synchro;
	public $is_sync;
	public $site_id;
	public $activated;
	public $id_group;
	public $is_chief;
	public $chef_equipe_id;
	public $id_organisme;
	public $id_organisme_chief;
	public $email_user;
	public $phone_user;
	public $id_service_group;
	public $access_au_module_deux;
	// public $OFF_SET=' OFFSET '; 
	public $OFF_SET = ',';

	public function __construct($db)
	{
		$this->conn = $db;
	}





	function GetUserFilterIdentification()
	{
		$user_filtre = "";
		if ($this->id_service_group ==  '3' || $this->HasGlobalAccess()) {
			$user_filtre = "";
		} else if ($this->is_chief == '1') {
			$lst_user_chief = '';
			$row_chief = $this->GenerateUserTree($this->code_utilisateur);
			if (count($row_chief) > 0) {
				//$lst_user_chief .= ",";
				foreach ($row_chief as $item) {
					//$lst_user_chief .= "'" . $item["code_utilisateur"] . "',";
					$lst_user_chief .= "'" . $item . "',";
				}
			}
			$clean = rtrim($lst_user_chief, ",");
			$user_filtre = " and identificateur in (" . $clean . ")";
		} else {
			$user_filtre = " and identificateur='" . $this->code_utilisateur  . "'";
		}
		return $user_filtre;
	}



	function GetUserFilterInstallation()
	{
		$user_filtre = "";
		if ($this->id_service_group ==  '3' || $this->HasGlobalAccess()) {
			$user_filtre = "";
		} else if ($this->is_chief == '1') {
			$lst_user_chief = '';
			//$lst_user_chief= "'" . $this->code_utilisateur . "'";
			//$stmt_chief = $this->GetCurrentUserListIdentificateurs($this->code_utilisateur,$this->id_organisme,$this->is_chief);
			$row_chief = $this->GenerateUserTree($this->code_utilisateur);
			if (count($row_chief) > 0) {
				//$lst_user_chief .= ",";
				foreach ($row_chief as $item) {
					//$lst_user_chief .= "'" . $item["code_utilisateur"] . "',";
					$lst_user_chief .= "'" . $item . "',";
				}
			}
			$clean = rtrim($lst_user_chief, ",");
			$user_filtre = " and code_installateur in (" . $clean . ")";
		} else {
			$user_filtre = " and code_installateur='" . $this->code_utilisateur  . "'";
		}
		return $user_filtre;
	}

	function GenerateUserTree($user_code)
	{
		$context_tree = array();

		//$stmt_chief = $this->GetCurrentUserListIdentificateurs($this->code_utilisateur,$this->id_organisme,$this->is_chief);

		$query = "SELECT code_utilisateur,nom_utilisateur,nom_complet,chef_equipe_id,id_organisme	FROM " . $this->table_name . "
				WHERE (code_utilisateur = :id_u)";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':id_u', $user_code);
		$stmt->execute();
		$row_chief = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (count($row_chief) > 0) {
			foreach ($row_chief as $item) {
				$context_tree[] = $item['code_utilisateur'];
				$this->GetParentUserAllChild($item, $context_tree);
			}
		}

		return $context_tree;
	}



	function GetParentUserAllChild($user_context, &$context_tree)
	{
		//$organe_filter = "";
		// if($this->HasGlobalAccess()){
		$query = "SELECT code_utilisateur,nom_utilisateur,nom_complet,chef_equipe_id,id_organisme	FROM " . $this->table_name . "
				WHERE (chef_equipe_id = :id_u)";
		$stmt = $this->conn->prepare($query);
		// $this->code_utilisateur=(strip_tags($this->code_utilisateur));
		$stmt->bindParam(':id_u', $user_context['code_utilisateur']);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		foreach ($rows as $item) {
			$context_tree[] = $item['code_utilisateur'];
			$this->GetParentUserAllChild($item, $context_tree);
		}

		/*}else{
				$query = "SELECT code_utilisateur,nom_utilisateur,nom_complet,chef_equipe_id,id_organisme	FROM " . $this->table_name . "
			WHERE (chef_equipe_id = :id_u and  id_organisme = :id_organisme)";	 
				$stmt = $this->conn->prepare( $query );
				// $this->code_utilisateur=(strip_tags($this->code_utilisateur));
				$stmt->bindParam(':id_u',  $user_context['code_utilisateur']);
				$stmt->bindParam(':id_organisme', $user_context['id_organisme']); 
				$stmt->execute(); 
				$rows= $stmt->fetchAll(PDO::FETCH_ASSOC);	
				foreach($rows as $item){
					$context_tree[]=$item['code_utilisateur'];
					$this->GetParentUserAllChild($item,$context_tree);
				}	
			}	*/
	}


	function GetUserFilterControl()
	{
		$user_filtre = "";
		if ($this->id_service_group ==  '3' || $this->HasGlobalAccess()) {
			$user_filtre = "";
		} else if ($this->is_chief == '1') {
			$lst_user_chief = '';
			//$lst_user_chief= "'" . $this->code_utilisateur . "'";
			//$stmt_chief = $this->GetCurrentUserListIdentificateurs($this->code_utilisateur,$this->id_organisme,$this->is_chief);
			$row_chief = $this->GenerateUserTree($this->code_utilisateur);
			if (count($row_chief) > 0) {
				//$lst_user_chief .= ",";
				foreach ($row_chief as $item) {
					//$lst_user_chief .= "'" . $item["code_utilisateur"] . "',";
					$lst_user_chief .= "'" . $item . "',";
				}
			}
			$clean = rtrim($lst_user_chief, ",");
			$user_filtre = " and controleur in (" . $clean . ")";
		} else {
			$user_filtre = " and controleur='" . $this->code_utilisateur  . "'";
		}
		return $user_filtre;
	}

	function GetUserFilterAssignation()
	{
		$user_filtre = "";
		if ($this->id_service_group ==  '3' || $this->HasGlobalAccess()) {
			$user_filtre = "";
		} else if ($this->is_chief == '1') {
			//$user_filtre=" and (id_chef_operation='" . $this->code_utilisateur  . "' or id_technicien='" . $this->code_utilisateur  . "')";
			$lst_user_chief = '';
			//$lst_user_chief= "'" . $this->code_utilisateur . "'";
			//$stmt_chief = $this->GetCurrentUserListIdentificateurs($this->code_utilisateur,$this->id_organisme,$this->is_chief);
			$row_chief = $this->GenerateUserTree($this->code_utilisateur);
			if (count($row_chief) > 0) {
				//$lst_user_chief .= ",";
				foreach ($row_chief as $item) {
					//$lst_user_chief .= "'" . $item["code_utilisateur"] . "',";
					$lst_user_chief .= "'" . $item . "',";
				}
			}
			$clean = rtrim($lst_user_chief, ",");
			// $user_filtre=" and (t_param_assignation.id_chef_operation in (" . $clean . ") OR t_param_assignation.id_technicien in (" . $clean . "))";
			$user_filtre = " and (t_param_assignation.id_chef_operation='" . $this->code_utilisateur . "' OR t_param_assignation.id_technicien in (" . $clean . "))";
		} else {
			$user_filtre = " and (t_param_assignation.id_technicien='" . $this->code_utilisateur  . "' or t_param_assignation.id_controleur_quality='" . $this->code_utilisateur  . "')";
		}
		return $user_filtre;
	}

	function Create()
	{
		//verification duplicate
		$query = "select nom_utilisateur,nom_complet from  " . $this->table_name . " where
		nom_complet=:nom_complet or nom_utilisateur=:nom_utilisateur";
		$stmt = $this->conn->prepare($query);
		$stmt->bindValue(":nom_utilisateur", $this->nom_utilisateur);
		$stmt->bindValue(":nom_complet", $this->nom_complet);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if ($num > 0) {
			$result["error"] = 1;
			$result["message"] = 'Il y a déjà un utilisateur avec un des noms utilisés';
			return $result;
		}

		$query = "INSERT INTO
                    " . $this->table_name . "  SET  code_utilisateur=:code_utilisateur,nom_utilisateur=:nom_utilisateur,nom_complet=:nom_complet,mot_de_passe=:mot_de_passe,n_user_create=:n_user_create ,datesys=:datesys,id_group=:id_group,activated=:activated,site_id=:site_id,phone_user=:phone_user,email_user=:email_user,chef_equipe_id=:chef_equipe_id,id_organisme=:id_organisme,id_organisme_chief=:id_organisme_chief,access_au_module_deux=:access_au_module_deux,is_chief=:is_chief";

		$stmt = $this->conn->prepare($query);
		$this->code_utilisateur = (strip_tags($this->code_utilisateur));
		$this->nom_utilisateur = (strip_tags($this->nom_utilisateur));
		$this->nom_complet = (strip_tags($this->nom_complet));
		$this->mot_de_passe = "12345";
		$this->n_user_create = (strip_tags($this->n_user_create));
		$this->id_group = (strip_tags($this->id_group));
		$this->activated = (strip_tags($this->activated));
		$this->site_id = (strip_tags($this->site_id));
		$this->phone_user = (strip_tags($this->phone_user));
		$this->email_user = (strip_tags($this->email_user));
		$this->chef_equipe_id = (strip_tags($this->chef_equipe_id));
		$this->id_organisme = (strip_tags($this->id_organisme));
		$this->id_organisme_chief = (strip_tags($this->id_organisme_chief));
		$this->is_chief = (strip_tags($this->is_chief));
		$this->access_au_module_deux = (strip_tags($this->access_au_module_deux));
		$this->is_sync = 0;
		$this->datesys = date('Y-m-d H:i:s');

		$stmt->bindParam(":code_utilisateur", $this->code_utilisateur);
		$stmt->bindParam(":nom_utilisateur", $this->nom_utilisateur);
		$stmt->bindParam(":nom_complet", $this->nom_complet);
		$stmt->bindParam(":mot_de_passe", $this->mot_de_passe);
		$stmt->bindParam(":n_user_create", $this->n_user_create);
		$stmt->bindParam(":datesys", $this->datesys);
		$stmt->bindParam(":id_group", $this->id_group);
		$stmt->bindParam(":activated", $this->activated);
		$stmt->bindParam(":site_id", $this->site_id);
		$stmt->bindParam(":phone_user", $this->phone_user);
		$stmt->bindParam(":email_user", $this->email_user);
		$stmt->bindParam(":chef_equipe_id", $this->chef_equipe_id);
		$stmt->bindParam(":id_organisme", $this->id_organisme);
		$stmt->bindParam(":id_organisme_chief", $this->id_organisme_chief);
		$stmt->bindParam(":is_chief", $this->is_chief);
		$stmt->bindParam(":access_au_module_deux", $this->access_au_module_deux);
		if ($stmt->execute()) {
			$result["error"] = 0;
			$result["message"] = 'Création effectuée avec succès';
		} else {
			$result["error"] = 1;
			$result["message"] = "L'opératon de la création a échoué.";
		}
		return $result;
	}

	function GetDetail()
	{
		$query = "SELECT code_utilisateur as ref,nom_utilisateur as k,nom_complet as nk,id_group as gp,activated as et,site_id as site,phone_user,email_user,chef_equipe_id,id_organisme,id_organisme_chief,is_chief FROM " . $this->table_name . "
			WHERE code_utilisateur = ?
			LIMIT 0,1";

		$stmt = $this->conn->prepare($query);
		$this->code_utilisateur = (strip_tags($this->code_utilisateur));
		$stmt->bindParam(1, $this->code_utilisateur);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}

	function GetUserDetailName($user_id)
	{
		$query = "SELECT code_utilisateur,nom_utilisateur,nom_complet,id_group as gp,activated as et,site_id as site,phone_user,email_user,chef_equipe_id,id_organisme,is_chief FROM " . $this->table_name . "
			WHERE code_utilisateur = ?
			LIMIT 0,1";

		$stmt = $this->conn->prepare($query);
		$user_id = (strip_tags($user_id));
		$stmt->bindParam(1, $user_id);

		$cacher = new Cacher();
		$row = $cacher->get(['utilisateur-get-user-detail-name', $user_id], function () use ($stmt) {
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
		});

		return $row['nom_complet'];
	}
	function GetUserDetailINFO($user_id)
	{
		$query = "SELECT code_utilisateur,nom_utilisateur,nom_complet,id_group as gp,activated as et,site_id as site,phone_user,email_user,chef_equipe_id,id_organisme,is_chief FROM " . $this->table_name . "
			WHERE code_utilisateur = ?
			LIMIT 0,1";

		$stmt = $this->conn->prepare($query);
		$user_id = (strip_tags($user_id));
		$stmt->bindParam(1, $user_id);

		$cacher = new Cacher();
		$row = $cacher->get(['utilisateur-get-user-detail-info', $user_id], function () use ($stmt) {
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
		});
		return $row;
	}

	function Modifier()
	{
		//write query
		$query = "UPDATE " . $this->table_name . "
                SET nom_utilisateur=:nom_utilisateur,nom_complet=:nom_complet,n_user_update=:n_user_update ,date_update=:date_update,id_group=:id_group,activated=:activated,site_id=:site_id,phone_user=:phone_user,email_user=:email_user,chef_equipe_id=:chef_equipe_id,id_organisme=:id_organisme,id_organisme_chief=:id_organisme_chief,access_au_module_deux=:access_au_module_deux,is_chief=:is_chief  WHERE code_utilisateur=:code_utilisateur";

		$stmt = $this->conn->prepare($query);
		$this->code_utilisateur = (strip_tags($this->code_utilisateur));
		$this->nom_utilisateur = (strip_tags($this->nom_utilisateur));
		$this->nom_complet = (strip_tags($this->nom_complet));
		$this->n_user_update = (strip_tags($this->n_user_update));
		$this->id_group = (strip_tags($this->id_group));
		$this->activated = (strip_tags($this->activated));
		$this->site_id = (strip_tags($this->site_id));
		$this->phone_user = (strip_tags($this->phone_user));
		$this->email_user = (strip_tags($this->email_user));
		$this->chef_equipe_id = (strip_tags($this->chef_equipe_id));
		$this->id_organisme = (strip_tags($this->id_organisme));
		$this->id_organisme_chief = (strip_tags($this->id_organisme_chief));
		$this->is_chief = (strip_tags($this->is_chief));
		$this->access_au_module_deux = (strip_tags($this->access_au_module_deux));
		$this->is_sync = 0;
		$this->date_update = date('Y-m-d H:i:s');

		$stmt->bindParam(":code_utilisateur", $this->code_utilisateur);
		$stmt->bindParam(":nom_utilisateur", $this->nom_utilisateur);
		$stmt->bindParam(":nom_complet", $this->nom_complet);
		$stmt->bindParam(":n_user_update", $this->n_user_update);
		$stmt->bindParam(":date_update", $this->date_update);
		$stmt->bindParam(":id_group", $this->id_group);
		$stmt->bindParam(":activated", $this->activated);
		$stmt->bindParam(":site_id", $this->site_id);
		$stmt->bindParam(":phone_user", $this->phone_user);
		$stmt->bindParam(":email_user", $this->email_user);
		$stmt->bindParam(":chef_equipe_id", $this->chef_equipe_id);
		$stmt->bindParam(":id_organisme", $this->id_organisme);
		$stmt->bindParam(":id_organisme_chief", $this->id_organisme_chief);
		$stmt->bindParam(":is_chief", $this->is_chief);
		$stmt->bindParam(":access_au_module_deux", $this->access_au_module_deux);

		if ($stmt->execute()) {
			$result["error"] = 0;
			$result["message"] = 'Modification effectuée avec succès';
		} else {
			$result["error"] = 1;
			$result["message"] = "L'opératon de la modification a échoué.";
		}
		return $result;
	}

	function Supprimer()
	{
		$query = "DELETE FROM " . $this->table_name . " WHERE code_utilisateur=:code_utilisateur";
		$stmt = $this->conn->prepare($query);
		$this->code_utilisateur = (strip_tags($this->code_utilisateur));
		$stmt->bindParam(":code_utilisateur", $this->code_utilisateur);
		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	function ResetPwd()
	{
		$query = "UPDATE " . $this->table_name . " set mot_de_passe='12345' WHERE code_utilisateur=:code_utilisateur";
		$stmt = $this->conn->prepare($query);
		$this->code_utilisateur = (strip_tags($this->code_utilisateur));
		$stmt->bindParam(":code_utilisateur", $this->code_utilisateur);
		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}




	function readAll($from_record_num, $records_per_page)
	{
		$query = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_utilisateur,t_utilisateurs.nom_complet ,t_utilisateurs.id_group,t_utilisateurs.activated,t_utilisateurs.site_id,is_chief,id_organisme,chef_equipe_id,ts_group_user.intitule,phone_user,email_user FROM ts_group_user
INNER JOIN t_utilisateurs ON t_utilisateurs.id_group = ts_group_user.id_group ORDER BY t_utilisateurs.nom_utilisateur ASC
				LIMIT
					{$from_record_num}, {$records_per_page}";

		$stmt = $this->conn->prepare($query);
		
		$stmt->execute();
		return $stmt;
	}
	function GetOrganismeChief($id_org)
	{
		$query = "SELECT code_utilisateur,nom_utilisateur,nom_complet	FROM " . $this->table_name . "
			WHERE id_organisme = ? and is_chief =1";
		$stmt = $this->conn->prepare($query);
		$this->code_utilisateur = (strip_tags($this->code_utilisateur));
		$stmt->bindParam(1, $id_org);
		$stmt->execute();
		return $stmt;
	}

	function GetOrganismeChiefControl($id_org)
	{
		$query = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_complet,t_utilisateurs.nom_utilisateur,t_utilisateurs.id_group,t_utilisateurs.activated,t_utilisateurs.is_chief,t_utilisateurs.id_organisme,t_utilisateurs.chef_equipe_id,t_utilisateurs.site_id,t_utilisateurs.phone_user,t_utilisateurs.email_user,ts_group_user.id_service FROM t_utilisateurs INNER JOIN ts_group_user ON t_utilisateurs.id_group = ts_group_user.id_group 
		  WHERE id_organisme = ? and is_chief =1 and ts_group_user.id_service='1'";
		$stmt = $this->conn->prepare($query);
		$this->code_utilisateur = (strip_tags($this->code_utilisateur));
		$stmt->bindParam(1, $id_org);
		$stmt->execute();
		return $stmt;
	}
	function GetOrganismeChiefInstall($id_org)
	{
		$user_filtre = "";
		if ($this->id_service_group ==  '3' || $this->HasGlobalAccess()) {
			$user_filtre = "";
		} else if ($this->is_chief == '1') {
			$user_filtre = " and code_utilisateur='" . $this->code_utilisateur . "'";
		}
		$query = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_complet,t_utilisateurs.nom_utilisateur,t_utilisateurs.id_group,t_utilisateurs.activated,t_utilisateurs.is_chief,t_utilisateurs.id_organisme,t_utilisateurs.chef_equipe_id,t_utilisateurs.site_id,t_utilisateurs.phone_user,t_utilisateurs.email_user,ts_group_user.id_service FROM t_utilisateurs INNER JOIN ts_group_user ON t_utilisateurs.id_group = ts_group_user.id_group 
		  WHERE id_organisme = ? and is_chief =1 and (ts_group_user.id_service='2' OR  ts_group_user.id_service='4') " . $user_filtre;
		//		  WHERE id_organisme = ? and is_chief =1 and ts_group_user.id_service='2'"; 
		$stmt = $this->conn->prepare($query);
		$this->code_utilisateur = (strip_tags($this->code_utilisateur));
		$stmt->bindParam(1, $id_org);
		$stmt->execute();
		return $stmt;
	}
	function GetOrganismeChiefIdentification($id_org)
	{
		$query = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_complet,t_utilisateurs.nom_utilisateur,t_utilisateurs.id_group,t_utilisateurs.activated,t_utilisateurs.is_chief,t_utilisateurs.id_organisme,t_utilisateurs.chef_equipe_id,t_utilisateurs.site_id,t_utilisateurs.phone_user,t_utilisateurs.email_user,ts_group_user.id_service FROM t_utilisateurs INNER JOIN ts_group_user ON t_utilisateurs.id_group = ts_group_user.id_group 
		  WHERE id_organisme = ? and is_chief =1 and ts_group_user.id_service='0'";
		$stmt = $this->conn->prepare($query);
		$this->code_utilisateur = (strip_tags($this->code_utilisateur));
		$stmt->bindParam(1, $id_org);
		$stmt->execute();
		return $stmt;
	}
	function GetAllQualityControleur()
	{
		$query = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_complet,t_utilisateurs.nom_utilisateur,t_utilisateurs.id_group,t_utilisateurs.activated,t_utilisateurs.is_chief,t_utilisateurs.id_organisme,t_utilisateurs.chef_equipe_id,t_utilisateurs.site_id,t_utilisateurs.phone_user,t_utilisateurs.email_user,ts_group_user.id_service FROM t_utilisateurs INNER JOIN ts_group_user ON t_utilisateurs.id_group = ts_group_user.id_group 
		  WHERE ts_group_user.id_service='4'";
		$stmt = $this->conn->prepare($query);
		// $this->code_utilisateur=(strip_tags($this->code_utilisateur));
		$stmt->execute();
		return $stmt;
	}
	function GetExclusiveQualityControleur($user_context, $setting_value, $quality_law)
	{
		$v = $user_context->GetSettingValue($setting_value);
		if ($v == '1') {
			$query = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_complet,t_utilisateurs.nom_utilisateur,t_utilisateurs.id_group,t_utilisateurs.activated,t_utilisateurs.is_chief,t_utilisateurs.id_organisme,t_utilisateurs.chef_equipe_id,t_utilisateurs.site_id,t_utilisateurs.phone_user,t_utilisateurs.email_user,ts_group_user.id_service FROM t_utilisateurs INNER JOIN ts_group_user ON t_utilisateurs.id_group = ts_group_user.id_group   INNER JOIN t_param_organisme ON t_utilisateurs.id_organisme = t_param_organisme.ref_organisme INNER JOIN ts_assignation_group ON ts_group_user.id_group = ts_assignation_group.id_group_  
		  WHERE ts_assignation_group.id_droit=:quality_law and t_param_organisme.is_blue_energy='on'";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam(':quality_law', $quality_law);
			$stmt->execute();
			return $stmt;
		} else {
			$query = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_complet,t_utilisateurs.nom_utilisateur,t_utilisateurs.id_group,t_utilisateurs.activated,t_utilisateurs.is_chief,t_utilisateurs.id_organisme,t_utilisateurs.chef_equipe_id,t_utilisateurs.site_id,t_utilisateurs.phone_user,t_utilisateurs.email_user,ts_group_user.id_service FROM t_utilisateurs INNER JOIN ts_group_user ON t_utilisateurs.id_group = ts_group_user.id_group INNER JOIN ts_assignation_group ON ts_group_user.id_group = ts_assignation_group.id_group_ 
		  WHERE  ts_assignation_group.id_droit=:quality_law";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam(':quality_law', $quality_law);
			// $this->code_utilisateur=(strip_tags($this->code_utilisateur));
			$stmt->execute();
			return $stmt;
		}
	}
	function GetUserHasQualityControlLaw($user_context, $quality_law)
	{
		//$v = $user_context->GetSettingValue($setting_value);
		$query = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_complet,t_utilisateurs.nom_utilisateur,t_utilisateurs.id_group,t_utilisateurs.activated,t_utilisateurs.is_chief,t_utilisateurs.id_organisme,t_utilisateurs.chef_equipe_id,t_utilisateurs.site_id,t_utilisateurs.phone_user,t_utilisateurs.email_user,ts_group_user.id_service FROM t_utilisateurs INNER JOIN ts_group_user ON t_utilisateurs.id_group = ts_group_user.id_group INNER JOIN ts_assignation_group ON ts_group_user.id_group = ts_assignation_group.id_group_ 
		  WHERE  ts_assignation_group.id_droit=:quality_law and t_utilisateurs.site_id = :site_id";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':quality_law', $quality_law);
		$stmt->bindParam(':site_id', $user_context->site_id);
		// $this->code_utilisateur=(strip_tags($this->code_utilisateur));
		$stmt->execute();
		return $stmt;
	}




	function GetAllInstallateur()
	{
		$query = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_complet,t_utilisateurs.nom_utilisateur,t_utilisateurs.id_group,t_utilisateurs.activated,t_utilisateurs.is_chief,t_utilisateurs.id_organisme,t_utilisateurs.chef_equipe_id,t_utilisateurs.site_id,t_utilisateurs.phone_user,t_utilisateurs.email_user,ts_group_user.id_service FROM t_utilisateurs INNER JOIN ts_group_user ON t_utilisateurs.id_group = ts_group_user.id_group 
		  WHERE ts_group_user.id_service='2'";
		$stmt = $this->conn->prepare($query);
		// $this->code_utilisateur=(strip_tags($this->code_utilisateur));
		$stmt->execute();
		return $stmt;
	}

	function GetAllControleur()
	{
		$query = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_complet,t_utilisateurs.nom_utilisateur,t_utilisateurs.id_group,t_utilisateurs.activated,t_utilisateurs.is_chief,t_utilisateurs.id_organisme,t_utilisateurs.chef_equipe_id,t_utilisateurs.site_id,t_utilisateurs.phone_user,t_utilisateurs.email_user,ts_group_user.id_service FROM t_utilisateurs INNER JOIN ts_group_user ON t_utilisateurs.id_group = ts_group_user.id_group 
		  WHERE ts_group_user.id_service='1'";
		$stmt = $this->conn->prepare($query);
		// $this->code_utilisateur=(strip_tags($this->code_utilisateur));
		$stmt->execute();
		return $stmt;
	}

	function GetAllIdentificateur()
	{
		$query = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_complet,t_utilisateurs.nom_utilisateur,t_utilisateurs.id_group,t_utilisateurs.activated,t_utilisateurs.is_chief,t_utilisateurs.id_organisme,t_utilisateurs.chef_equipe_id,t_utilisateurs.site_id,t_utilisateurs.phone_user,t_utilisateurs.email_user,ts_group_user.id_service FROM t_utilisateurs INNER JOIN ts_group_user ON t_utilisateurs.id_group = ts_group_user.id_group 
		  WHERE ts_group_user.id_service='0'";
		$stmt = $this->conn->prepare($query);
		// $this->code_utilisateur=(strip_tags($this->code_utilisateur));
		$stmt->execute();
		return $stmt;
	}

	function GetCurrentUserChief($user_context)
	{ //$id_u,$id_org,$chef_equipe_id){
		if (trim($user_context->chef_equipe_id) != "" && $user_context->chef_equipe_id != "Veuillez préciser") {
			$query = "SELECT code_utilisateur,nom_utilisateur,nom_complet	FROM " . $this->table_name . "
			WHERE (code_utilisateur = :chef_equipe_id) or (code_utilisateur =:code_utilisateur and is_chief =1)"; // and  id_organisme = :id_organisme)";	 
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam(':chef_equipe_id', $user_context->chef_equipe_id);
			$stmt->bindParam(':code_utilisateur', $user_context->code_utilisateur);
			//$stmt->bindParam(':id_organisme', $id_org); 
			$stmt->execute();
			return $stmt;
		} else {
			$query = "SELECT code_utilisateur,nom_utilisateur,nom_complet	FROM " . $this->table_name . "
			WHERE ((code_utilisateur = :id_u and is_chief =1))";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam(':id_u', $user_context->code_utilisateur);
			//$stmt->bindParam(':id_organisme', ); 
			$stmt->execute();
			return $stmt;
		}
	}

	function GetAllChiefForAdmin()
	{
		$query = "SELECT code_utilisateur,nom_utilisateur,nom_complet	FROM " . $this->table_name . "
			WHERE (is_chief =1)";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}

	function GetCurrentUserListIdentificateurs($id_u, $id_org, $Is_chief)
	{
		/*echo "$id_u,$id_org,$Is_chief";
		exit;*/
		if ($Is_chief == '1') {
			$query = "SELECT code_utilisateur,nom_utilisateur,nom_complet	FROM " . $this->table_name . "
			WHERE (chef_equipe_id = :id_u and  id_organisme = :id_organisme)";
			$stmt = $this->conn->prepare($query);
			// $this->code_utilisateur=(strip_tags($this->code_utilisateur));
			$stmt->bindParam(':id_u', $id_u);
			$stmt->bindParam(':id_organisme', $id_org);
			$stmt->execute();
			return $stmt;
		} else {
			$query = "SELECT code_utilisateur,nom_utilisateur,nom_complet	FROM " . $this->table_name . "
			WHERE (code_utilisateur = :id_u)";
			$stmt = $this->conn->prepare($query);
			$stmt->bindParam(':id_u', $id_u);
			$stmt->execute();
			return $stmt;
		}
	}


	function GetAllChiefLinkedUsers($id_chief)
	{
		$query = "SELECT code_utilisateur,nom_utilisateur,nom_complet	FROM " . $this->table_name . "
			WHERE (chef_equipe_id = :id_chief)";
		$stmt = $this->conn->prepare($query);
		// $this->code_utilisateur=(strip_tags($this->code_utilisateur));
		$stmt->bindParam(':id_chief', $id_chief);
		$stmt->execute();
		return $stmt;
	}

	function GetAll_OrganeUserListForAdmin()
	{

		$query = "SELECT code_utilisateur,nom_utilisateur,nom_complet	FROM " . $this->table_name . "
			WHERE coalesce(chef_equipe_id,'') != ''";
		$stmt = $this->conn->prepare($query);
		// $stmt->bindParam(':id_u', $id_u);
		$stmt->execute();
		return $stmt;
	}


	function readOne()
	{
		$query = "SELECT t_utilisateurs.code_utilisateur, t_utilisateurs.nom_utilisateur, t_utilisateurs.id_group, t_utilisateurs.activated, t_utilisateurs.is_chief, t_utilisateurs.id_organisme, t_utilisateurs.chef_equipe_id, t_utilisateurs.site_id, t_utilisateurs.access_au_module_deux, t_utilisateurs.phone_user, t_utilisateurs.email_user, ts_group_user.id_service, t_utilisateurs.nom_complet FROM t_utilisateurs INNER JOIN ts_group_user ON t_utilisateurs.id_group = ts_group_user.id_group WHERE code_utilisateur = ? LIMIT 0, 1";
		$stmt = $this->conn->prepare($query);
		$this->code_utilisateur = strip_tags($this->code_utilisateur);
		$stmt->bindParam(1, $this->code_utilisateur);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($row) {
			$this->code_utilisateur = $row['code_utilisateur'];
			$this->nom_utilisateur = $row['nom_utilisateur'];
			$this->id_group = $row['id_group'];
			$this->activated = $row['activated'];
			$this->site_id = $row['site_id'];
			$this->is_chief = $row['is_chief'];
			$this->id_organisme = $row['id_organisme'];
			$this->chef_equipe_id = $row['chef_equipe_id'];
			$this->id_service_group = $row['id_service'];
			$this->nom_complet = $row['nom_complet'];
			$this->access_au_module_deux = $row['access_au_module_deux'];
		} else {
			echo "Aucune donnée correspondante trouvée.";
		}
	}

	function readDetail($id)
	{
		$query = " SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_utilisateur,t_utilisateurs.nom_complet,t_utilisateurs.id_group,t_utilisateurs.activated,t_utilisateurs.is_chief,t_utilisateurs.id_organisme,t_utilisateurs.chef_equipe_id,t_utilisateurs.site_id,t_utilisateurs.phone_user,t_utilisateurs.email_user,ts_group_user.id_service FROM t_utilisateurs INNER JOIN ts_group_user ON t_utilisateurs.id_group = ts_group_user.id_group 
		  WHERE code_utilisateur = ?
			LIMIT 0,1";
		$stmt = $this->conn->prepare($query);
		$id = (strip_tags($id));
		$stmt->bindParam(1, $id);

		$cacher = new Cacher();
		$row = $cacher->get(['utilisateur-read-detail', $id], function () use ($stmt) {
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);
		});

		return  $row;
	}


	// used for paging products
	public function countAll()
	{
		$query = "SELECT t_utilisateurs.code_utilisateur FROM ts_group_user
INNER JOIN t_utilisateurs ON t_utilisateurs.id_group = ts_group_user.id_group";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		$num = $stmt->rowCount();
		return $num;
	}

	// read products by search term
	public function search($search_term, $from_record_num, $records_per_page)
	{

		// select query
		$query = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_utilisateur,t_utilisateurs.nom_complet,t_utilisateurs.id_group,t_utilisateurs.activated,t_utilisateurs.site_id,ts_group_user.intitule,t_utilisateurs.phone_user,t_utilisateurs.email_user,t_utilisateurs.chef_equipe_id,t_utilisateurs.id_organisme,t_utilisateurs.is_chief,t_param_organisme.denomination,t_param_site_production.intitule_site  FROM ts_group_user INNER JOIN t_utilisateurs ON t_utilisateurs.id_group = ts_group_user.id_group INNER JOIN t_param_organisme ON t_param_organisme.ref_organisme = t_utilisateurs.id_organisme INNER JOIN t_param_site_production ON t_param_site_production.code_site = t_utilisateurs.site_id WHERE
                t_utilisateurs.nom_utilisateur LIKE :search_term OR t_utilisateurs.nom_complet LIKE :search_term OR ts_group_user.intitule LIKE :search_term OR t_param_site_production.intitule_site LIKE :search_term OR t_param_organisme.denomination LIKE :search_term ORDER BY t_utilisateurs.nom_utilisateur ASC LIMIT :from, :offset";
		$stmt = $this->conn->prepare($query);
		$search_term = "%{$search_term}%";
		$stmt->bindParam(':search_term', $search_term);
		$stmt->bindParam(':from', $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(':offset', $records_per_page, PDO::PARAM_INT);

		// execute query
		$stmt->execute();

		// return values from database
		return $stmt;
	}
	public function countAll_BySearch($search_term)
	{

		// select query
		$query = "SELECT
                COUNT(*) as total_rows
            FROM ts_group_user INNER JOIN t_utilisateurs ON t_utilisateurs.id_group = ts_group_user.id_group INNER JOIN t_param_organisme ON t_param_organisme.ref_organisme = t_utilisateurs.id_organisme INNER JOIN t_param_site_production ON t_param_site_production.code_site = t_utilisateurs.site_id WHERE t_utilisateurs.nom_utilisateur LIKE :search_term OR t_utilisateurs.nom_complet LIKE :search_term OR ts_group_user.intitule LIKE :search_term OR t_param_site_production.intitule_site LIKE :search_term OR t_param_organisme.denomination LIKE :search_term";
		$stmt = $this->conn->prepare($query);
		$search_term = "%{$search_term}%";
		$stmt->bindParam(':search_term', $search_term);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['total_rows'];
	}

	function HasDroits($code_droit)
	{

		$query = "select id_droit,id_group_ FROM ts_assignation_group WHERE id_group_=:id_group_ AND id_droit=:id_droit";
		$stmt = $this->conn->prepare($query);
		$code_droit = (strip_tags($code_droit));
		$this->id_group = (strip_tags($this->id_group));
		$stmt->bindParam(":id_group_", $this->id_group);
		$stmt->bindParam(":id_droit", $code_droit);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	function HasGlobalAccess()
	{
		$code_droit = '10_710';
		$query = "select id_droit,id_group_ FROM ts_assignation_group WHERE id_group_=:id_group_ AND id_droit=:id_droit";
		$stmt = $this->conn->prepare($query);
		//  $code_droit=(strip_tags($code_droit));
		$this->id_group = (strip_tags($this->id_group));
		$stmt->bindParam(":id_group_", $this->id_group);
		$stmt->bindParam(":id_droit", $code_droit);
		$stmt->execute();
		if ($stmt->rowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}
	function GetSettingValue($code)
	{
		$query = "SELECT id, value_setting FROM t_app_setting WHERE id = :id";
		$stmt = $this->conn->prepare($query);
		$code = strip_tags($code);
		$stmt->bindParam(":id", $code);
		$stmt->execute();
		$uRow = $stmt->fetch(PDO::FETCH_ASSOC);

		if ($uRow && isset($uRow['value_setting'])) {
			return $uRow['value_setting'];
		} else {
			// Gérer le cas où aucune donnée correspondante n'a été trouvée
			return "Aucune donnée correspondante trouvée.";
		}
	}

	function GetSettingDefaultValue($code)
	{
		$query = "select id,default_value FROM t_app_setting WHERE id=:id";
		$stmt = $this->conn->prepare($query);
		$code = (strip_tags($code));
		$this->id_group = (strip_tags($this->id_group));
		$stmt->bindParam(":id", $code);
		$stmt->execute();
		$uRow = $stmt->fetch(PDO::FETCH_ASSOC);
		return  $uRow['default_value'];
	}

	public function logout()
	{
		if (!isset($_SESSION)) {
			session_start();
		}
		try {
			session_destroy();
		} catch (Exception $e) {
		}
		$_SESSION['uSession'] = "";
		$this->redirect("login.php");
		exit();
	}
	public function redirect($url)
	{
		header("Location: $url");
		exit();
	}



	public function EvaluateContextExpirationDuration($context_code)
	{
		$v = $this->GetSettingValue('6');
		if ($v == '1') { //Expiration authorized				  
			$c = $this->GetSettingValue($context_code);
			if ($c != '0') {
				if (isset($_SESSION['last_acted_on']) && (time() - $_SESSION['last_acted_on'] > 60 * ((int)$c))) { // Context duration
					session_unset();     // unset $_SESSION variable for the run-time
					session_destroy();   // destroy session data in storage
					return false;
				} else {
					session_regenerate_id(true);
					$_SESSION['last_acted_on'] = time();
				}
			}
		}
	}
	public function is_logged_in()
	{
		$v = $this->GetSettingValue('6');
		if ($v == '1') {
			$d_value = (int)$this->GetSettingDefaultValue('6');
			if (isset($_SESSION['last_acted_on']) && (time() - $_SESSION['last_acted_on'] > 60 * $d_value)) { //65 minutes
				session_unset();     // unset $_SESSION variable for the run-time
				session_destroy();   // destroy session data in storage
				//header('Location: path/to/login/page');
				return false;
			} else {
				session_regenerate_id(true);
				$_SESSION['last_acted_on'] = time();
			}
		}
		if (isset($_SESSION['uSession'])) {
			$this->code_utilisateur = $_SESSION['uSession'];
			$stmt = $this->conn->prepare("SELECT code_utilisateur,nom_utilisateur,id_group,activated FROM " . $this->table_name . " WHERE code_utilisateur=:code_utilisateur");
			$stmt->execute(array(":code_utilisateur" => $this->code_utilisateur));
			$userRow = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($stmt->rowCount() == 1) {
				if ($userRow['activated'] == 1) {
					return true;
				}
				return false;
			}
			return false;
			//return;
		}
		return false;
		//$this->redirect('login.php');
	}

	public function login($email, $upass)
	{
		$result = array();
		// try
		// {
		$stmt = $this->conn->prepare("SELECT code_utilisateur,nom_utilisateur,mot_de_passe,id_group,activated FROM " . $this->table_name . " WHERE nom_utilisateur=:nom_utilisateur");
		$stmt->execute(array(":nom_utilisateur" => strip_tags($email)));
		$userRow = $stmt->fetch(\PDO::FETCH_ASSOC);
		// $userRow=$stmt->fetch();	
		// var_dump($userRow);
		// exit;			
		if ($stmt->rowCount() == 1) {
			if ($userRow['activated'] == 1) {
				//if($userRow['mot_de_passe']==md5($upass))
				if ($userRow['mot_de_passe'] == ($upass)) {
					$_SESSION['uSession'] = $userRow['code_utilisateur'];
					//$_SESSION['last_login'] = time();
					// session_regenerate_id();
					$_SESSION['logged_in'] = true;
					$_SESSION['last_login'] = time();
					$_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
					$result["login"] = true;
					$result["message"] = "Opération effectuée avec succès";
					//return true;
				} else {
					//header("Location: login.php?error=100");
					//exit;

					$result["login"] = false;
					$result["error"] = 100;
					$result["message"] = "Mot de passe ou compte non valide";
				}
			} else {
				//header("Location: login.php?error=105");
				//exit;
				$result["login"] = false;
				$result["error"] = 105;
				$result["message"] = "Compte non activé";
			}
		} else {
			$result["login"] = false;
			$result["error"] = 110;
			$result["message"] = "Accès non autorisé";
			//header("Location: login.php?error=110");
			//exit;
		}
		// }
		// catch(PDOException $ex)
		// {
		//echo $ex->getMessage();
		// $result["login"] = false;
		// $result["error"] = 110;
		// $result["message"] = "";

		// }
		return $result;
	}


	public function UpdatePwd($code_utilisateur, $old_pwd, $new_pwd)
	{
		$result = array();
		$query = "select code_utilisateur,nom_utilisateur from t_utilisateurs where code_utilisateur=:code_utilisateur and mot_de_passe=:mot_de_passe";
		$stmt = $this->conn->prepare($query);
		$code_utilisateur = (strip_tags($code_utilisateur));
		$old_pwd = (strip_tags($old_pwd));
		$new_pwd = (strip_tags($new_pwd));
		$stmt->bindValue(":code_utilisateur", $code_utilisateur);
		$stmt->bindValue(":mot_de_passe", $old_pwd);
		$stmt->execute();
		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$query_pwd = "update t_utilisateurs set mot_de_passe=:mot_de_passe  where code_utilisateur=:code_utilisateur";
			$stmt_pwd = $this->conn->prepare($query_pwd);
			$stmt_pwd->bindValue(":code_utilisateur", $code_utilisateur);
			$stmt_pwd->bindValue(":mot_de_passe", $new_pwd);
			$stmt_pwd->execute();
			if ($stmt->rowCount() > 0) {
				$result["error"] = 0;
				$result["message"] = "Opération effectuée avec succès";
				//$result["data"] = null;
			} else {
				$result["error"] = 1;
				$result["message"] = "Echec opération";
				//$result["data"] = null;
			}
		} else {
			$result["error"] = 0;
			$result["message"] = "Actuel mot de passe incorrect";
			//$result["data"] = null;
		}
		// return $result;
		echo json_encode($result);
	}
}
