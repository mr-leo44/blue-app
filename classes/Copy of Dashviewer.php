<?php
class Dashviewer{
 
    // database connection and table name
    private $conn;
    private $Aborted=1;
    private $Valid=0;
 
    public function __construct($db){
        $this->conn = $db;
    }
	
	function ClientToDbDateFormat($c_date){	
		//$dt="17/07/2012";
		$n_date=str_ireplace('/','-',$c_date);
		$f_dt=date('Y-m-d',strtotime($n_date));
		return $f_dt;
	}
	
	function FormatNbre($nbre){	 
		$f_dt=number_format($nbre,0,"."," ");
		return $f_dt;
	}
	
    
   function GetSite_CompteurIdentified($user_context,$site,$du,$au){
	    $user_filtre = $user_context->GetUserFilterIdentification();
		$site=htmlspecialchars(strip_tags($site));
		$du=htmlspecialchars(strip_tags($du));
		$au=htmlspecialchars(strip_tags($au));        
		$query = "SELECT count(t_main_data.id_) as nbre,t_main_data.ref_site_identif FROM t_main_data where (DATE_FORMAT(t_main_data.date_identification,'%Y-%m-%d')  between :du and :au) and t_main_data.ref_site_identif=:site and t_main_data.annule=:annule " . $user_filtre ;  
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(":site", $site);
		$stmt->bindParam(":du", $du);
		$stmt->bindParam(":au", $au);
		$stmt->bindParam(":annule", $this->Valid);
		$stmt->execute(); 
		$row = $stmt->fetch(PDO::FETCH_ASSOC); 			
		return $row['nbre'];
    }
	
   function GetAll_CompteurIdentified($user_context,$du,$au){
	   $user_filtre = $user_context->GetUserFilterIdentification();
	   $liste_site = array();
	   $query = "SELECT t_param_site_production.code_site,t_param_site_production.intitule_site,t_param_site_production.annule,t_utilisateur_site_accessible.code_user
FROM t_param_site_production INNER JOIN t_utilisateur_site_accessible ON t_param_site_production.code_site = t_utilisateur_site_accessible.code_site WHERE t_utilisateur_site_accessible.code_user=:code_user";
		$code_user=(strip_tags($user_context->code_utilisateur));
		$stmt = $this->conn->prepare( $query );		
		$stmt->bindValue(":code_user",$code_user);
		$stmt->execute();	
 		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$liste_site[] = $row['code_site'];
		}
	   $liste_site_label = implode("','",$liste_site);
	   $liste_site_label = "'" . $liste_site_label ."'";
	   
		$du=htmlspecialchars(strip_tags($du));
		$au=htmlspecialchars(strip_tags($au));        
		$query = "SELECT count(t_main_data.id_) as nbre,t_main_data.ref_site_identif FROM t_main_data where (DATE_FORMAT(t_main_data.date_identification,'%Y-%m-%d')  between :du and :au) and t_main_data.ref_site_identif in (". $liste_site_label .") and t_main_data.annule=:annule " . $user_filtre ; 
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(":du", $du);
		$stmt->bindParam(":au", $au);
		$stmt->bindParam(":annule", $this->Valid);
		$stmt->execute(); 
		$row = $stmt->fetch(PDO::FETCH_ASSOC); 			
		return $row['nbre'];
    }
    
   function GetSite_CompteurInstalled($user_context,$site,$du,$au){
	    $user_filtre = $user_context->GetUserFilterInstallation();
		$site=(strip_tags($site));
		$du=(strip_tags($du));
		$au=(strip_tags($au));
	/*	$query = "SELECT count(t_log_installation.id_install) as nbre,t_log_installation.ref_site_install FROM t_log_installation where (DATE_FORMAT(t_log_installation.datesys,'%Y-%m-%d')  between :du and :au) and t_log_installation.ref_site_install=:site and t_log_installation.annule=:annule"; */
	$query = "SELECT count(t_log_installation.id_install) as nbre,t_log_installation.ref_site_install FROM t_log_installation INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ where (DATE_FORMAT(t_log_installation.datesys,'%Y-%m-%d')  between :du and :au) and   t_log_installation.ref_site_install=:site and t_log_installation.annule=:annule and t_log_installation.statut_installation=1 " . $user_filtre ;	
		
		
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(":site", $site);
		$stmt->bindParam(":du", $du);
		$stmt->bindParam(":au", $au);
		$stmt->bindParam(":annule", $this->Valid);
		$stmt->execute(); 
		$row = $stmt->fetch(PDO::FETCH_ASSOC); 			
		return $row['nbre'];
    }
    
   function GetAll_CompteurInstalled($user_context,$du,$au){
	     $user_filtre = $user_context->GetUserFilterInstallation();
	   $liste_site = array();
	   $query = "SELECT t_param_site_production.code_site,t_param_site_production.intitule_site,t_param_site_production.annule,t_utilisateur_site_accessible.code_user
FROM t_param_site_production INNER JOIN t_utilisateur_site_accessible ON t_param_site_production.code_site = t_utilisateur_site_accessible.code_site WHERE t_utilisateur_site_accessible.code_user=:code_user";
		$code_user=(strip_tags($user_context->code_utilisateur));
		$stmt = $this->conn->prepare( $query );		
		$stmt->bindValue(":code_user", $code_user);
		$stmt->execute();	
 		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$liste_site[] = $row['code_site'];
		}
	   $liste_site_label = implode("','",$liste_site);
	   $liste_site_label = "'" . $liste_site_label ."'";
	   
		$du=(strip_tags($du));
		$au=(strip_tags($au));
		
	/*	$query = "SELECT count(t_log_installation.id_install) as nbre,t_log_installation.ref_site_install FROM t_log_installation where (DATE_FORMAT(t_log_installation.datesys,'%Y-%m-%d')  between :du and :au) and t_log_installation.ref_site_install in (". $liste_site_label .") and t_log_installation.annule=:annule"; */
		
		$query = "SELECT count(t_log_installation.id_install) as nbre,t_log_installation.ref_site_install FROM t_log_installation INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ where (DATE_FORMAT(t_log_installation.datesys,'%Y-%m-%d')  between :du and :au) and   t_log_installation.ref_site_install in (". $liste_site_label .") and t_log_installation.annule=:annule and t_log_installation.statut_installation=1 " . $user_filtre ; 	
		
		
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(":du", $du);
		$stmt->bindParam(":au", $au);
		$stmt->bindParam(":annule", $this->Valid);
		$stmt->execute(); 
		$row = $stmt->fetch(PDO::FETCH_ASSOC); 			
		return $row['nbre'];
    }
    
   function GetSite_CompteurControled($user_context,$site,$du,$au){
	   $user_filtre = $user_context->GetUserFilterControl();
		$site=(strip_tags($site));
		$du=(strip_tags($du));
		$au=(strip_tags($au));		
		$query = "SELECT count(t_log_controle.ref_fiche_controle) as nbre,t_log_controle.ref_site_controle FROM t_log_controle  INNER JOIN t_main_data ON t_log_controle.ref_fiche_identification = t_main_data.id_ where (DATE_FORMAT(t_log_controle.date_controle,'%Y-%m-%d')  between :du and :au) and t_log_controle.ref_site_controle=:site and t_log_controle.annule=:annule " . $user_filtre ; 
		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(":site", $site);
		$stmt->bindParam(":du", $du);
		$stmt->bindParam(":au", $au);
		$stmt->bindParam(":annule", $this->Valid);
		$stmt->execute(); 
		$row = $stmt->fetch(PDO::FETCH_ASSOC); 			
		return $row['nbre'];
    }
	
   
    
   function GetAll_CompteurControled($user_context,$du,$au){
	   $user_filtre = $user_context->GetUserFilterControl();
	  $liste_site = array();
	   $query = "SELECT t_param_site_production.code_site,t_param_site_production.intitule_site,t_param_site_production.annule,t_utilisateur_site_accessible.code_user
FROM t_param_site_production INNER JOIN t_utilisateur_site_accessible ON t_param_site_production.code_site = t_utilisateur_site_accessible.code_site WHERE t_utilisateur_site_accessible.code_user=:code_user";
		$code_user=(strip_tags($user_context->code_utilisateur));
		$stmt = $this->conn->prepare( $query );		
		$stmt->bindValue(":code_user", $code_user);
		$stmt->execute();	
 		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$liste_site[] = $row['code_site'];
		}
	   $liste_site_label = implode("','",$liste_site);
	   $liste_site_label = "'" . $liste_site_label ."'";
	      
	   
	    
		$du=htmlspecialchars(strip_tags($du));
		$au=htmlspecialchars(strip_tags($au));
		
		$query = "SELECT count(t_log_controle.ref_fiche_controle) as nbre,t_log_controle.ref_site_controle FROM t_log_controle   INNER JOIN t_main_data ON t_log_controle.ref_fiche_identification = t_main_data.id_ where (DATE_FORMAT(t_log_controle.date_controle,'%Y-%m-%d')  between :du and :au) and t_log_controle.ref_site_controle in (". $liste_site_label .") and t_log_controle.annule=:annule " . $user_filtre ; 
		$stmt = $this->conn->prepare( $query ); 
		$stmt->bindParam(":du", $du);
		$stmt->bindParam(":au", $au);
		$stmt->bindParam(":annule", $this->Valid);
		$stmt->execute(); 
		$row = $stmt->fetch(PDO::FETCH_ASSOC); 			
		return $row['nbre'];
    }
	
   
    
   function GetAll_CVS_SYNTHE_Par_Date($user_context,$du,$au,$province_id){
	  $liste_site_s = array();
	  $liste_site = array();
	  $liste_province = array();
	   $query = "SELECT t_param_site_production.code_site,t_param_site_production.intitule_site,t_param_site_production.annule,t_utilisateur_site_accessible.code_user
FROM t_param_site_production INNER JOIN t_utilisateur_site_accessible ON t_param_site_production.code_site = t_utilisateur_site_accessible.code_site WHERE t_utilisateur_site_accessible.code_user=:code_user";
		$code_user=(strip_tags($user_context->code_utilisateur));
		$stmt = $this->conn->prepare( $query );		
		$stmt->bindValue(":code_user", $code_user);
		$stmt->execute();	
		$nbre_total_cvs = 0;
		
		/*
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$liste_site[] = $row['code_site'];
		}
	   $liste_site_label = implode("','",$liste_site);
	   $liste_site_label = "'" . $liste_site_label ."'";
		 
		//RECUPERATION de maniere distinct des PROVINCES
		$stmt = $this->conn->prepare("SELECT DISTINCT province_id FROM t_param_site_production WHERE code_site IN (" . $liste_site_label . ")";	
		$stmt->execute();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			
		
		}
		*/
		
 		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$item = $this->GetSite_CVS_SYNTHE_Par_Date($user_context,$row['code_site'],$du,$au,$province_id);
			//var_dump($item);
			$nbre_total_cvs += count($item);
			$liste_site[] = $item;
		
		}
   
   	$liste_site_s["nbre_total"] = $nbre_total_cvs;
   	$liste_site_s["sites"] = $liste_site;
	
	
   return $liste_site_s;
   }
   function GetSite_CVS_SYNTHE_Par_Date($user_context,$site,$du,$au,$province_id){
		$site=htmlspecialchars(strip_tags($site));
		$du=htmlspecialchars(strip_tags($du));
		$au=htmlspecialchars(strip_tags($au));  
		$result= array();	

		//$commune = new AdresseEntity($this->conn);
		//$stmt = $commune->GetProvinceAllCVS($province_id);		
		
		//DATE_FORMAT(t_vente.date_vente,'%d/%m/%Y')
		//DATE_FORMAT(t_vente.date_vente,'%Y-%m-%d') between :du and :au)
		$query = "SELECT t_param_cvs.code,t_param_cvs.libelle,t_param_cvs.annule,t_param_cvs.code_province,t_param_cvs.id_site,t_param_cvs.activated FROM t_param_cvs WHERE t_param_cvs.id_site=:site  ORDER BY t_param_cvs.libelle";		
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(":site", $site);
		$stmt->execute(); 
		
		while ($row_ = $stmt->fetch(PDO::FETCH_ASSOC)){
 			 
		 $item= array();
		 $item["CVS"] = $row_["libelle"];
		
		
		//RECUPERATION DES COMPTEURS IDENTIFIES POUR LE CVS EN COURS
		$user_filtre = $user_context->GetUserFilterIdentification();
		$query = "SELECT count(t_main_data.id_) as nbre,t_main_data.ref_site_identif FROM t_main_data where (DATE_FORMAT(t_main_data.date_identification,'%Y-%m-%d')  between :du and :au) and  t_main_data.cvs_id=:id_cvs and t_main_data.annule=:annule " . $user_filtre; 
		$st = $this->conn->prepare( $query );
		$st->bindValue(":id_cvs", $row_["code"]);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", $this->Valid);
		$st->execute(); 
		$ident = $st->fetch(PDO::FETCH_ASSOC);
		$item["nbre_identification"] = $ident['nbre'];
		
		
		//RECUPERATION DES COMPTEURS INSTALLES POUR LE CVS EN COURS
		$user_filtre = $user_context->GetUserFilterInstallation();
		$query = "SELECT count(t_log_installation.id_install) as nbre,t_log_installation.ref_site_install FROM t_log_installation INNER JOIN t_main_data ON t_log_installation.ref_identific = t_main_data.id_ where (DATE_FORMAT(t_log_installation.datesys,'%Y-%m-%d')  between :du and :au) and   t_main_data.cvs_id=:id_cvs and t_log_installation.annule=:annule and t_log_installation.statut_installation=1 " . $user_filtre;
		$st = $this->conn->prepare( $query );
		$st->bindValue(":id_cvs", $row_["code"]);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", $this->Valid);
		$st->execute(); 
		$ident = $st->fetch(PDO::FETCH_ASSOC);
		$item["nbre_installation"] = $ident['nbre'];
		
		
		//RECUPERATION DES COMPTEURS CONTROLEURS POUR LE CVS EN COURS
		$user_filtre = $user_context->GetUserFilterControl();
		$query = "SELECT count(t_log_controle.ref_fiche_controle) as nbre,t_log_controle.ref_site_controle FROM t_log_controle  INNER JOIN t_main_data ON t_log_controle.ref_fiche_identification = t_main_data.id_  where (DATE_FORMAT(t_log_controle.date_controle,'%Y-%m-%d')  between :du and :au) and  t_main_data.cvs_id=:id_cvs and t_log_controle.annule=:annule " . $user_filtre; 
		$st = $this->conn->prepare( $query );
		$st->bindValue(":id_cvs", $row_["code"]);
		$st->bindValue(":du", $du);
		$st->bindValue(":au", $au);
		$st->bindValue(":annule", $this->Valid);
		$st->execute(); 
		$ident = $st->fetch(PDO::FETCH_ASSOC);
		$item["nbre_controle"] = $ident['nbre'];
		array_push($result,$item);
		}
		//$stmt->fetch(PDO::FETCH_ASSOC); 	
		return	$result;
    }
	
	
	
   
}
?>