<?php

/*include_once 'Droits.php';
include_once 'Utilisateur.php';
include_once 'GroupUser.php';*/
class LoginMobile
{

    private $connection;

    public function __construct($db)
    {
        $this->connection = $db;
    }


    function Login($json)
    {
        $value = json_decode($json);
        $query = "select t_utilisateurs.code_utilisateur,t_utilisateurs.nom_utilisateur,t_utilisateurs.mot_de_passe,t_utilisateurs.site_id,t_utilisateurs.signature_id,t_utilisateurs.id_group,t_utilisateurs.n_user_create,t_utilisateurs.must_reconnect,t_utilisateurs.online_login_mode,t_utilisateurs.activated as user_active,ts_group_user.activated as group_active FROM t_utilisateurs
INNER JOIN ts_group_user ON t_utilisateurs.id_group = ts_group_user.id_group
		where nom_utilisateur=:nom_utilisateur and mot_de_passe=:mot_de_passe;";
        $value->nom_utilisateur = htmlspecialchars(strip_tags($value->nom_utilisateur));
        $value->mot_de_passe = htmlspecialchars(strip_tags($value->mot_de_passe));

        $stmt = $this->connection->prepare($query);
        $stmt->bindValue(":nom_utilisateur", $value->nom_utilisateur);
        $stmt->bindValue(":mot_de_passe", $value->mot_de_passe);
        $stmt->execute();
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            if ($row["user_active"] == 0) {
                $result["error"] = true;
                $result["message"] = "Compte utilisateur non activé";
                $result["data"] = null;
            } else if ($row["group_active"] == 0) {
                $result["error"] = true;
                $result["message"] = "Groupe utilisateur non activé";
                $result["data"] = null;
            } else {
                $item = new E_Utilisateur();
                $item->code_utilisateur = $row["code_utilisateur"];
                $item->nom_utilisateur = $row["nom_utilisateur"];
                $item->must_reconnect = $row["must_reconnect"];
                //$item->site_user = $this->GetUserSite($row["site_id"]);
                $item->signature_id = $row["signature_id"];
                $item->online_login_mode = $row["online_login_mode"];
                $item->pNuser_Create = $row["n_user_create"];
                $item->activated = $row["user_active"] == 0 ? false : true;

                $item->groupe = $this->GetGroupeUser($row["id_group"], true);
                $item->PrivilegesCodes = array(); // $this->GetUserPrivilegesSupplementary($row["code_utilisateur"]);
                foreach ($item->groupe->Privileges as $value) {
                    //$item->Privileges[] = $value;
                    $item->PrivilegesCodes[] = $value->id_module;
                }

                $item->groupe = null;
                $result["error"] = false;
                $result["message"] = "Connexion effectuée avec succès";
                $result["data"] = $item;
                /*
				$query_spin = "select code,libelle from t_province order by libelle asc";
                $stmt_spin = $this->connection->prepare($query_spin);
                $stmt_spin->execute();
                $data = array(); 
                while ($row_spin = $stmt_spin->fetch(PDO::FETCH_ASSOC)) {
                    $item = new SpinnerItem();
                    $item->code = $row_spin["code"];
                    $item->name = $row_spin["libelle"];
                    $data[] = $item;
                }
                $result["province"] = $data; */
            }
        } else {
            $result["error"] = true;
            $result["message"] = "Accès non autorisé";
            $result["data"] = null;
        }
        //return $result;
        echo json_encode($result);
        //return null;
    }
    function GetUserSite($_id)
    {
        $item = new Site_Perception();

        $query = "SELECT
				t_site_perception.code_site,
				t_site_perception.intitule_site,
				t_site_perception.adresse_site,
				t_commune.libelle as entite_secondaire,
				t_province.libelle as entite_principal 
				FROM 
				t_site_perception 
				INNER JOIN t_province ON t_province.`code` = t_site_perception.province_id 
				INNER JOIN t_commune ON t_commune.`code` = t_site_perception.commune_id 
				 where t_site_perception.code_site='" . $_id . "' and t_site_perception.annule=0";
        $stmt = $this->connection->query($query);
        //$stmt = $db->query($query);  

        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (count($row) > 0) {
                $item->code_site = $row["code_site"];
                $item->intitule_site = $row["intitule_site"];
                $item->adresse_site = $row["adresse_site"];
                // $item->contact_site = $row["contact_site"];
                // $item->commentaire_site = $row["commentaire_site"];
                // $item->partenaire_id = $row["partenaire_id"];
                // $item->province_id = $row["province_id"];
                $item->entite_principal = $row["entite_principal"];
                $item->entite_secondaire = $row["entite_secondaire"];
                return $item;
            }
        }
        return null;
    }
    function GetGroupeUser($_id, $with_privileges)
    {
        $item = new GroupUser();
        $query = "SELECT ts_group_user.id_group,ts_group_user.intitule,ts_group_user.access_any_where,ts_group_user.id_ser,ts_group_user.n_user_create,ts_group_user.activated FROM ts_group_user where id_group='" . $_id . "'";
        $stmt = $this->connection->query($query);
        //$stmt = $db->query($query);  	
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $item->id_group = $row["id_group"];
            $item->intutile = $row["intitule"];
            $item->access_any_where = $row["access_any_where"];
            $item->id_ser = $row["id_ser"] == 0 ? false : true;
            $item->pNuser_Create = $row["n_user_create"];
            $item->activated = $row["activated"] == 0 ? false : true;
            if ($with_privileges) {
                $item->Privileges = $this->GetPrivilegesGroupeSelected($row["id_group"]);
            }
        }
        return $item;
        //return null;
    }

    public function GetPrivilegesGroupeSelected($id_group)
    {
        $items = array();
        $query_group_assign = " SELECT ts_droits.id_module,ts_droits.intutile,ts_droits.id_ser,ts_droits.is_main,ts_assignation_group.id_assign,ts_assignation_group.id_group_,
			ts_assignation_group.id_droit FROM ts_assignation_group inner join ts_droits on ts_droits.id_module=ts_assignation_group.id_droit 
			WHERE ts_assignation_group.id_group_=:id_group_";
        $stmt_group_assign = $this->connection->prepare($query_group_assign);
        $stmt_group_assign->bindValue(":id_group_", $id_group);
        $stmt_group_assign->execute();
        while ($row = $stmt_group_assign->fetch(PDO::FETCH_ASSOC)) {
            $item = new Droits();
            $item->id_module = $row["id_module"];
            $item->intutile = $row["intutile"];
            $item->id_ser = $row["id_ser"];
            $item->is_main = $row["is_main"];
            $item->id_group = $id_group;
            $items[] = $item;
        }
        return $items;
    }
}
