<?php
class GroupUtilisateur
{

	// database connection and table name
	private $conn;
	private $table_name = "ts_group_user";

	public $id_group;
	public $intitule;
	public $id_service;
	public $n_user_create;
	public $date_update;
	public $datesys;
	public $n_user_update;
	public $activated;
	public $access_any_where;
	public $is_sync;


	public function __construct($db)
	{
		$this->conn = $db;
	}

	function Create()
	{
		//verification duplicate
		$query = "select id_group,intitule from  " . $this->table_name . " where
		intitule=:intitule";
		$stmt = $this->conn->prepare($query);
		$stmt->bindValue(":intitule", $this->intitule);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if ($num > 0) {
			$result["error"] = 1;
			$result["message"] = 'Il y a déjà un groupe (' . $this->intitule . ')';
			return $result;
		}





		//write query
		$query = "INSERT INTO
                    " . $this->table_name . "
                SET id_group=:id_group,intitule=:intitule,n_user_create=:n_user_create,datesys=:datesys,activated=:activated,access_any_where=:access_any_where,is_sync=:is_sync,id_service=:id_service";

		$stmt = $this->conn->prepare($query);
		$this->id_group = (strip_tags($this->id_group));
		$this->intitule = (strip_tags($this->intitule));
		$this->id_service = (strip_tags($this->id_service));
		$this->n_user_create = (strip_tags($this->n_user_create));
		$this->activated = 1;
		$this->access_any_where = 0;
		$this->is_sync = 0;
		$this->datesys = date('Y-m-d H:i:s');

		// affectation des valeurs
		$stmt->bindParam(":id_group", $this->id_group);
		$stmt->bindParam(":intitule", $this->intitule);
		$stmt->bindParam(":id_service", $this->id_service);
		$stmt->bindParam(":n_user_create", $this->n_user_create);
		$stmt->bindParam(":activated", $this->activated);
		$stmt->bindParam(":access_any_where", $this->access_any_where);
		$stmt->bindParam(":is_sync", $this->is_sync);
		$stmt->bindParam(":datesys", $this->datesys);
		if ($stmt->execute()) {
			$result_array["error"] = 0;
			$result_array["message"] = "Création effectuée avec succes.";
			return $result_array;
		} else {
			$result_array["error"] = 1;
			$result_array["message"] = "L'opératon de la création du groupe utilisateur a échoué.";
			return $result_array;
		}
	}

	function Modifier()
	{
		$query = "select id_group,intitule from  " . $this->table_name . " where
		intitule=:intitule";
		$stmt = $this->conn->prepare($query);
		$stmt->bindValue(":intitule", $this->intitule);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$num = $stmt->rowCount();
		if ($num > 0) {
			if ($this->id_group != $row["id_group"]) {
				$result["error"] = 1;
				$result["message"] = 'Il y a déjà un groupe (' . $this->intitule . ')';
				return $result;
			}
		}



		//write query
		$query = "UPDATE " . $this->table_name . "
                SET  intitule=:intitule,date_update=:date_update,n_user_update=:n_user_update,activated=:activated,access_any_where=:access_any_where,is_sync=:is_sync,id_service=:id_service  WHERE id_group=:id_group";

		$stmt = $this->conn->prepare($query);
		$this->id_group = (strip_tags($this->id_group));
		$this->intitule = (strip_tags($this->intitule));
		$this->id_service = (strip_tags($this->id_service));
		$this->n_user_update = (strip_tags($this->n_user_update));
		$this->activated = 1; //(strip_tags($this->activated));   
		$this->access_any_where = 0; //(strip_tags($this->access_any_where));      
		$this->is_sync = 0;
		$this->date_update = date('Y-m-d H:i:s');

		// affectation des valeurs
		$stmt->bindParam(":id_group", $this->id_group);
		$stmt->bindParam(":intitule", $this->intitule);
		$stmt->bindParam(":id_service", $this->id_service);
		$stmt->bindParam(":n_user_update", $this->n_user_update);
		$stmt->bindParam(":activated", $this->activated);
		$stmt->bindParam(":access_any_where", $this->access_any_where);
		$stmt->bindParam(":is_sync", $this->is_sync);
		$stmt->bindParam(":date_update", $this->date_update);
		if ($stmt->execute()) {
			$result_array["error"] = 0;
			$result_array["message"] = "Modification effectuée avec succès.";
		} else {
			$result_array["error"] = 1;
			$result_array["message"] = "L'opératon de la modification a échoué.";
		}
		return $result_array;
	}

	function Supprimer()
	{
		//write query
		$query = "DELETE FROM " . $this->table_name . " WHERE id_group=:id_group";
		$stmt = $this->conn->prepare($query);
		$this->id_group = (strip_tags($this->id_group));
		$stmt->bindParam(":id_group", $this->id_group);
		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}
	// used by select drop-down list
	function read()
	{
		//select all data
		$query = "SELECT *
                FROM
                    " . $this->table_name . "
                ORDER BY
                    intitule";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		return $stmt;
	}

	function GrantPrivileges($POST)
	{
		$datesys = date("Y-m-d H:i:s");
		//Suppression de tous les droits du groupe pour inserer les nouveaux droits
		$query_ven = "delete from ts_assignation_group  where id_group_=:id_group_";
		$stmt_ven = $this->conn->prepare($query_ven);
		$stmt_ven->bindValue(':id_group_', $this->id_group);
		$stmt_ven->execute();


		$query = "INSERT INTO ts_assignation_group (id_assign,id_group_,id_droit,n_user_create,datesys) values (:id_assign,:id_group_,:id_droit,:n_user_create,:datesys);";
		$query_avoid_duplicate = "SELECT id_group_,id_droit FROM ts_assignation_group  WHERE id_group_=:id_group_ and id_droit=:id_droit;";
		$stmt = $this->conn->prepare($query);
		$stmt_avoid_duplicate = $this->conn->prepare($query_avoid_duplicate);
		//$k => $v
		foreach ($POST as $value) {
			$id_assign = $this->uniqUid("ts_assignation_group", "id_assign");
			$stmt->bindValue(':id_assign', $id_assign);
			$stmt->bindValue(':id_group_', $this->id_group);
			$stmt->bindValue(':id_droit', $value);
			$stmt->bindValue(':n_user_create', $this->n_user_create);
			$stmt->bindValue(':datesys', $datesys);
			$stmt->execute();
		}
		$result["error"] = 0;
		$result["message"] = "Opération effectuée avec succès";
		$result["data"] = null;
		return $result;
	}

	function readAll($from_record_num, $records_per_page)
	{
		$query = "SELECT id_group,intitule,id_service  
				FROM " . $this->table_name . "
				ORDER BY
					intitule ASC
				LIMIT
					{$from_record_num}, {$records_per_page}";

		$stmt = $this->conn->prepare($query);
		$stmt->execute();
		return $stmt;
	}

	function GetDroits($group)
	{
		$query = "SELECT id_assign,id_group_,id_droit FROM ts_assignation_group
			WHERE id_group_ = ?";
		$stmt = $this->conn->prepare($query);
		$group = (strip_tags($group));
		$stmt->bindParam(1, $group);
		$stmt->execute();
		$row_granted = $stmt->fetchAll(PDO::FETCH_ASSOC);


		$query = "SELECT ts_modules.libelle,ts_modules.code FROM ts_modules ORDER BY ts_modules.libelle ASC";
		$stmt_module = $this->conn->prepare($query);
		$group = (strip_tags($group));
		// $stmt_module->bindParam(1, $group);
		$stmt_module->execute();
		$row_modules = $stmt_module->fetchAll(PDO::FETCH_ASSOC);


		$list_droits = "";
		$tablist = array();
		$tab_content_list = array();
		$tabitem = "";
		$tab_content_item = "";
		$ctr = 0;
		$active_item = "";
		$active_tab = "";
		foreach ($row_modules as $item_module) {
			if ($ctr == 0) {
				$active_item = " active";
				$active_tab = " show active";
			} else {
				$active_item = "";
				$active_tab = "";
			}
			$ctr++;
			$tabitem = '<li class="nav-item"><a class="nav-link' . $active_item . '" id="module-' . $item_module["code"] . '-tab" data-toggle="tab" href="#module-' . $item_module["code"] . '" role="tab" aria-controls="home" aria-selected="true">' . $item_module["libelle"] . '</a></li>';

			$list_droits = '<ul class="list-group" id="search_results">';



			$query_group_assign = " SELECT ts_droits.id_module,ts_droits.intutile,ts_droits.id_ser,ts_droits.is_main FROM ts_droits where id_ser = :item_module order by order_number";
			$stmt_group_assign = $this->conn->prepare($query_group_assign);
			$stmt_group_assign->bindValue(":item_module", $item_module["code"]);
			$stmt_group_assign->execute();
			$list_droits = "";
			while ($row = $stmt_group_assign->fetch(PDO::FETCH_ASSOC)) {
				$list_droits .= '<li class="list-group-item rounded-0">
										<div class="custom-control custom-checkbox">';
				$granted = false;
				foreach ($row_granted as $revP) {
					if ($revP['id_droit'] == $row["id_module"]) {
						$granted = true;
						break;
					}
				}
				if ($granted == false) {
					$list_droits .= '<input class="custom-control-input" id="ch_' . $row["id_module"] . '" name="tbl-checkbox[]" type="checkbox" value="' . $row["id_module"] . '">';
				} else {
					$list_droits .= '<input class="custom-control-input" id="ch_' . $row["id_module"] . '" name="tbl-checkbox[]" type="checkbox"  checked="checked"  value="' . $row["id_module"] . '">';
				}
				$list_droits .= '<label class="cursor-pointer font-italic d-block custom-control-label" for="ch_' . $row["id_module"] . '"> ' . $row["intutile"] . '</label>
								</div>
							</li>';
			}
			$list_droits .= '</ul>';
			$tab_content_item = '<div class="tab-pane fade' . $active_tab . '" id="module-' . $item_module["code"] . '" role="tabpanel" aria-labelledby="module-' . $item_module["code"] . '-tab">' . $list_droits . '</div>';

			$tablist[] = $tabitem;
			$tab_content_list[] =  $tab_content_item;
		}
		$final_result = '<ul class="nav nav-tabs" id="module_list_tab" role="tablist">';
		foreach ($tablist as $tab_item) {
			$final_result .= $tab_item;
		}
		$final_result .= '</ul>';
		$final_result .= '<div class="tab-content" id="module_list_content">';
		foreach ($tab_content_list as $content_item) {
			$final_result .= $content_item;
		}
		$final_result .= '</div>';


		return $final_result;
	}
	/*
    function GetDroits($group){
		 $query = "SELECT id_assign,id_group_,id_droit FROM ts_assignation_group
			WHERE id_group_ = ?";	 
		$stmt = $this->conn->prepare( $query );
		 $group=(strip_tags($group));
		$stmt->bindParam(1,$group);
		$stmt->execute(); 
		$row_granted = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		
		$list_droits="";
		$query_group_assign = " SELECT ts_droits.id_module,ts_droits.intutile,ts_droits.id_ser,ts_droits.is_main FROM ts_droits order by order_number";
        $stmt_group_assign = $this->conn->prepare($query_group_assign);
        $stmt_group_assign->execute();
        while ($row = $stmt_group_assign->fetch(PDO::FETCH_ASSOC)) {
            $list_droits.='<li class="list-group-item rounded-0">
                                <div class="custom-control custom-checkbox">';
				$granted = false;
				foreach ($row_granted as $revP) {
					if ($revP['id_droit'] == $row["id_module"]){
						$granted = true;
						break;
					}
				}
				if ($granted == false) {
				  $list_droits.='<input class="custom-control-input" id="ch_'.$row["id_module"].'" name="tbl-checkbox[]" type="checkbox" value="'.$row["id_module"].'">';
				}else{ 
					$list_droits.='<input class="custom-control-input" id="ch_'.$row["id_module"].'" name="tbl-checkbox[]" type="checkbox"  checked="checked"  value="'.$row["id_module"].'">';
				}
				 $list_droits.='<label class="cursor-pointer font-italic d-block custom-control-label" for="ch_'.$row["id_module"].'"> '.$row["intutile"].'</label>
						</div>
					</li>';
        }
		return $list_droits;
    }*/

	function GetDetail()
	{
		$query = "SELECT id_group,intitule,id_service	FROM " . $this->table_name . "
			WHERE id_group = ?
			LIMIT 0,1";

		$stmt = $this->conn->prepare($query);
		$this->id_group = (strip_tags($this->id_group));
		$stmt->bindParam(1, $this->id_group);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row;
	}


	// used for paging products
	public function countAll()
	{

		$query = "SELECT id_group FROM " . $this->table_name . "";

		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		$num = $stmt->rowCount();

		return $num;
	}

	// read products by search term
	public function search($search_term, $from_record_num, $records_per_page)
	{

		// select query
		$query = "SELECT  id_group,intitule,id_service   FROM " . $this->table_name . " WHERE
                intitule Like :search_term ORDER BY intitule ASC LIMIT :from, :offset";
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
            FROM
                " . $this->table_name . " WHERE
                intitule LIKE :search_term ORDER BY intitule ASC ";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// bind variable values
		$search_term = "%{$search_term}%";
		$stmt->bindParam(':search_term', $search_term);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		return $row['total_rows'];
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
