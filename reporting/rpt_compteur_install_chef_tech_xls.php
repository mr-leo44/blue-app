<?php

require_once '../loader/init.php';

require_once '../classes/CLS_Reporting.php';
require_once '../classes/CVS.php';
require_once '../classes/MarqueCompteur.php';
require_once '../classes/AdresseEntity.php';
require_once '../classes/Site.php';
require_once '../classes/Organisme.php';
require_once '../classes/Droits.php';
require_once '../classes/Utils.php';
require_once '../classes/Utilisateur.php';
require_once '../classes/Database.php';
require_once '../classes/Cacher.php';
require_once '../classes/Installation.php';
require_once '../classes/PARAM_TypeFraude.php';
require_once '../classes/PARAM_TypeObservation.php';
require_once '../classes/CLS_Controle.php';
include_once '../core.php';
header('Content-type: text/html;charset=utf-8');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$database = new Database();
$db = $database->getConnection();
$utilisateur = new Utilisateur($db);
//$droits = new Droits();
$cls_report = new CLS_Reporting($db);
$cvs = new CVS($db); 
$marquecompteur = new MarqueCompteur($db); 
$adresseItem = new AdresseEntity($db); 
$site_classe = new Site($db); 

$organisme = new Organisme($db);   

$liste_site = array();
$header_bg_color = "#ECF2FE";// "#5969ff";//eee6ff
$site=isset($_POST['site']) ? $_POST['site'] : NULL;
$du=isset($_POST['Du']) ? Utils::ClientToDbDateFormat($_POST['Du']) : "";
$au=isset($_POST['Au']) ? Utils::ClientToDbDateFormat($_POST['Au']) : ""; 
$du_=isset($_POST['Du']) ?($_POST['Du']) : "";
$au_=isset($_POST['Au']) ? ($_POST['Au']) : ""; 
$chef_item=isset($_POST['chef_item']) ? ($_POST['chef_item']) : ""; 
$utilisateur->is_logged_in();
$utilisateur->readOne();

function cellAlign($newsheet, $cells, $align)
{

	if ($align == "R") {
		$newsheet->getStyle($cells)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
	} else if ($align == "J") {
		$newsheet->getStyle($cells)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_JUSTIFY);
	} else if ($align == "C") {
		$newsheet->getStyle($cells)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
	}
}

function cellBorder($newsheet, $cells)
{
	$newsheet->getStyle($cells)->applyFromArray(
		[
			'borders' => [
				'outline' => [
					'style' => Border::BORDER_THIN,
					'color' => ['argb' => 'FF000000'],
				],
			],
		]
	);
}

function cellStyle($newsheet, $cells, $size)
{

	$newsheet->getStyle($cells)->getFont()->setSize($size);
	$newsheet->getStyle($cells)->getFont()->setBold(true);
}

function cellColor($newsheet, $cells, $color)
{
	$newsheet->getStyle($cells)->getFill()->applyFromArray([
		'fillType' => Fill::FILL_SOLID,
		'startColor' => [
			'rgb' => $color,
		],
	]);
}

if (in_array($MULTI_ACCESS_SITE_CODE, $site)) {
	$liste_site =  $cls_report->GetAll_AccessibleUSerSite($utilisateur->code_utilisateur);
} else {
	$liste_site = $site;
}

 
$query_installateurs_suppl = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_complet,t_log_installation_users.ref_inst_ FROM t_log_installation_users INNER JOIN t_utilisateurs ON t_log_installation_users.ref_user = t_utilisateurs.code_utilisateur where t_log_installation_users.ref_inst_=:ref_inst_";
$stmt_supp = $db->prepare($query_installateurs_suppl);
$objPHPExcel = new Spreadsheet;


foreach($liste_site as $site_item){
	$site_classe->code_site = $site_item;
	$site_classe->GetDetailIN();
	//$USER_SITENAME = $site_classe->intitule_site;
	
	$user_list = $cls_report->getChiefTechnician($chef_item);
	 $rowNumber = 7; //start in row 1
	
	
	
	
	
$objPHPExcel->setActiveSheetIndex(0);
$newsheet =   $objPHPExcel->getActiveSheet();
//DEBUT SYNTHESE

$newsheet->setTitle("SYNTHESE");
	$newsheet ->mergeCells('B'.$rowNumber.':H'.$rowNumber);
	cellStyle($newsheet,'B'.$rowNumber, 14);
	$newsheet->setCellValue('B'.$rowNumber,'TABLEAU SYNTHESE  du '. $du_ .' au '. $au_);
	cellBorder($newsheet,'B'.$rowNumber.':H'.$rowNumber);
	cellAlign($newsheet,'B'.$rowNumber, 'C');
	cellColor($newsheet,'B'.$rowNumber, 'b6b8b9');
	$rowNumber++;
	$rowNumber++;
	$rowNumber++;
	$Total_general=0; 
	$col='A';
	//ITERATION DATA IINSTALL FOR CURRENT SITE 
	
			$newsheet->setCellValue($col.$rowNumber, 'N°'); 
			cellBorder($newsheet,$col.$rowNumber);
			$col++;
			$newsheet->setCellValue($col.$rowNumber, 'Techniciens'); 
			cellBorder($newsheet,$col.$rowNumber);
			$col++;
	
			$newsheet->setCellValue($col.$rowNumber, 'Installés '); 
			cellBorder($newsheet,$col.$rowNumber);
			$col++;
			$newsheet->setCellValue($col.$rowNumber, 'Remplacés'); 
			cellBorder($newsheet,$col.$rowNumber);
			$col++;
			$newsheet->setCellValue($col.$rowNumber, 'Total'); 
			cellBorder($newsheet,$col.$rowNumber);	
	
		$ctr_tech=0;
	foreach($user_list as $user_item){	
	$ctr_tech++;
		//RECUPERATION LISTE DES COMPTEURS INSTALLES PAR LE USER CURRENT  AND GIVEN PERIOD 
		$nbre_install = $cls_report->getSite_CompteursInstallPeriodeCountUser($user_item['code_utilisateur'], $site_item, $du, $au);		
		$nbre_replace = $cls_report->getSite_CompteursReplacePeriodeCountUser($user_item['code_utilisateur'], $site_item, $du, $au);	
		
	 $Total_user = $nbre_install + $nbre_replace;
	 $Total_general += $Total_user;
			$col='A';
			$rowNumber++;
			$newsheet->setCellValue($col.$rowNumber,$ctr_tech); 
			cellBorder($newsheet,$col.$rowNumber);
			cellStyle($newsheet,$col.$rowNumber,10);
			
			$col++;	
			$newsheet->setCellValue($col.$rowNumber,$user_item['nom_complet']); 
			cellBorder($newsheet,$col.$rowNumber);
			cellStyle($newsheet,$col.$rowNumber,10);
			
			$col++;	
			$newsheet->setCellValue($col.$rowNumber,$nbre_install . ' '); 
			cellBorder($newsheet,$col.$rowNumber);
			
			$col++;	
			$newsheet->setCellValue($col.$rowNumber,$nbre_replace . ' '); 
			cellBorder($newsheet,$col.$rowNumber);
			
			$col++;	
			$newsheet->setCellValue($col.$rowNumber,$Total_user . ' '); 
			cellBorder($newsheet,$col.$rowNumber);
			
			//$col++;	
	}
	
			$rowNumber++;
	$newsheet->setCellValue($col.$rowNumber,$Total_general . ' '); 
			cellBorder($newsheet,$col.$rowNumber);
	// Total_install
	
	$rowNumber = $rowNumber + 3;	
	
	
}
//FIN SYNTHESE 
foreach($liste_site as $site_item){
	
	$site_classe->code_site = $site_item;
	$site_classe->GetDetailIN();
	//$USER_SITENAME = $site_classe->intitule_site;
	
	$user_list = $cls_report->getChiefTechnician($chef_item);
	$chef_item_name = $utilisateur->GetUserDetailName($chef_item);	
	// $count_cvs = count($user_list);
	// $ctr_cvs = 0;
	$start_l = false;
	 
 
         
	
$newsheet = $objPHPExcel->createSheet();

$rowNumber = 1; //start in row 1
  // Rename worksheet
$newsheet->setTitle("Détails");


$col = 'A'; // start at column A
$newsheet->setCellValue("A1",$site_classe->intitule_site . " :  INSTALLATION COMPTEURS A PREPAIEMENT  PAR TECHNICIEN");
cellStyle($newsheet,'A1', 14);
$cell = 'Période : du '. $du_ .' au '. $au_;  
$rowNumber++;
$rowNumber++;
 $newsheet->setCellValue($col.$rowNumber,"Chef d'équipe : " . $chef_item_name);
cellStyle($newsheet,$col.$rowNumber, 13);
$rowNumber+=2;

	//ITERATION DATA IINSTALL FOR CURRENT SITE
	foreach($user_list as $user_item){
	
		//RECUPERATION LISTE DES COMPTEURS INSTALLES POUR LE CURRENT CVS AND GIVEN PERIOD  	
		$data_ = $cls_report->getSite_CompteursInstallUser($user_item['code_utilisateur'], $site_item, $du, $au);		
		$nb_data_ = count($data_);
		if($nb_data_ > 0){
			
	
  

$col = 'A'; // start at column A
$newsheet->setCellValue($col.$rowNumber,"Technicien : " . $user_item['nom_complet'] . "(". $nb_data_ . " Compteurs)" );
cellStyle($newsheet,$col.$rowNumber, 13); 
$rowNumber++;  

//ENTETES PRINCIPALES
 
cellStyle($newsheet,'A'.$rowNumber, 14);
$newsheet->setCellValue('A'.$rowNumber,"N°"); 
cellAlign($newsheet,'A'.$rowNumber, 'C');
cellColor($newsheet,'A'.$rowNumber, 'ffc107'); 

$newsheet ->mergeCells('B'.$rowNumber.':G'.$rowNumber);
cellStyle($newsheet,'B'.$rowNumber, 14);
$newsheet->setCellValue('B'.$rowNumber,"INFOS DU CLIENT");
cellBorder($newsheet,'B'.$rowNumber.':G'.$rowNumber);
cellAlign($newsheet,'B'.$rowNumber, 'C');
cellColor($newsheet,'B'.$rowNumber, '17a2b8');

$newsheet ->mergeCells('H'.$rowNumber.':M'.$rowNumber);
cellStyle($newsheet,'H'.$rowNumber, 14);
$newsheet->setCellValue('H'.$rowNumber,"COMPTEUR PREPAIEMENT INSTALLE");
cellBorder($newsheet,'H'.$rowNumber.':M'.$rowNumber);
cellAlign($newsheet,'H'.$rowNumber, 'C');
cellColor($newsheet,'H'.$rowNumber, 'ffc107');

$newsheet ->mergeCells('N'.$rowNumber.':Q'.$rowNumber);
cellStyle($newsheet,'N'.$rowNumber, 14);
$newsheet->setCellValue('N'.$rowNumber,"COMPTEUR POST PAIEMENT RETIR.");
cellBorder($newsheet,'N'.$rowNumber.':Q'.$rowNumber);
cellAlign($newsheet,'N'.$rowNumber, 'C');
cellColor($newsheet,'N'.$rowNumber, '007bff');
 

$newsheet ->mergeCells('R'.$rowNumber.':T'.$rowNumber);
cellStyle($newsheet,'R'.$rowNumber, 14);
$newsheet->setCellValue('R'.$rowNumber,"MAINTENANCE"); 
cellAlign($newsheet,'R'.$rowNumber, 'C');
cellColor($newsheet,'R'.$rowNumber, '1a5da6');
  


$rowNumber++;
		$headers_=array("","Quartier", "CVS", "Adresse (Avenue et N°)", "Noms et Postnoms", "PA (POC)", "Tarif", "Marque", "Numéro de compteur", "Date installation", "Equipe Installation", "Installateurs", "N° Scellé cpt 1", "N° Scellé coffret 2", "Date de pose scellé", "Marque", "Numéro de serie", "Index", "Date retrait", "Compteur
			prépaiement remplacé");



$newsheet->getColumnDimension('B')->setAutoSize(true); // Content adaptation 
$newsheet->getColumnDimension('C')->setAutoSize(true); // Content adaptation 
$newsheet->getColumnDimension('D')->setAutoSize(true); // Content adaptation 
$newsheet->getColumnDimension('E')->setAutoSize(true); // Content adaptation 
$newsheet->getColumnDimension('F')->setAutoSize(true); // Content adaptation 
$newsheet->getColumnDimension('H')->setAutoSize(true); // Content adaptation 
$newsheet->getColumnDimension('I')->setAutoSize(true); // Content adaptation 
$newsheet->getColumnDimension('J')->setAutoSize(true); // Content adaptation 
$newsheet->getColumnDimension('K')->setAutoSize(true); // Content adaptation 
$newsheet->getColumnDimension('L')->setAutoSize(true); // Content adaptation 
$newsheet->getColumnDimension('O')->setAutoSize(true); // Content adaptation 
$newsheet->getColumnDimension('Q')->setAutoSize(true); // Content adaptation 
$newsheet->getColumnDimension('R')->setAutoSize(true); // Content adaptation 
 
			$col = 'A'; // start at column A
    foreach($headers_ as $cell) {
        $newsheet->setCellValue($col.$rowNumber,$cell); 
		cellBorder($newsheet,$col.$rowNumber);
		cellStyle($newsheet,$col.$rowNumber,10);
        $col++;
    }
	$rowNumber++;
  $ctr_context =1;
			foreach($data_ as $row_){
				
  $col = 'A'; // start at column A
				$cvs->code=$row_["cvs_id"];
					  $cvs->GetDetailIN();
					  $marquecompteur->code=$row_["marque_compteur"];
					  $marquecompteur->GetDetailIN(); 
					  
				$E_item = $adresseItem->GetAdressInfo($row_["adresse_id"]);
				$numero =  $E_item['numero'];
				$avenue =  $adresseItem->GetLabel($E_item['avenue']);
				$quartier_ = $adresseItem->GetLabel($E_item['quartier_id']);
				
				$newsheet->setCellValue($col.$rowNumber,$ctr_context);	
				cellBorder($newsheet,$col.$rowNumber);
				$col++;
				
				$newsheet->setCellValue($col.$rowNumber,$quartier_);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;
			

				$newsheet->setCellValue($col.$rowNumber,$cvs->libelle);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;
			

				$newsheet->setCellValue($col.$rowNumber,$avenue . ' ' . $numero);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;
			

				$newsheet->setCellValue($col.$rowNumber,$row_["nom_client_blue"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;
			

				$newsheet->setCellValue($col.$rowNumber,$row_["p_a"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;

				$newsheet->setCellValue($col.$rowNumber,$row_["tarif_identif"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;
			

				$newsheet->setCellValue($col.$rowNumber,$marquecompteur->libelle);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;

				$newsheet->setCellValue($col.$rowNumber,$row_["numero_compteur"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;

				$newsheet->setCellValue($col.$rowNumber, $row_["date_fin_installation_fr"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;		
// Equipe
$organisme->ref_organisme=$row_["id_equipe"];
$organisme->GetDetailIN(); 

				$newsheet->setCellValue($col.$rowNumber,  $organisme->denomination);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;		

// Installateurs
		
			///////////////////SUPPLEMENTAIRES //////////////////////

$installateurs_name = $utilisateur->GetUserDetailName($row_['installateur']);
$clean = $installateurs_name;			
 $installateurs_suppl=$installateurs_name; 
        $stmt_supp->bindValue(":ref_inst_", $row_["id_install"]);
        $stmt_supp->execute();
        $ro = $stmt_supp->fetchAll(PDO::FETCH_ASSOC); 
			if(count($ro)>0){ 			
				foreach ($ro as $ins_suppItem) { 
						$installateurs_suppl .= "  "  . $ins_suppItem["nom_complet"] . ",";
					}
			$clean = rtrim($installateurs_suppl,",");
			 
					
			}
			///////////////////SUPPLEMENTAIRES //////////////////////

				$newsheet->setCellValue($col.$rowNumber, $clean);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;		

				$newsheet->setCellValue($col.$rowNumber, $row_["scelle_un_cpteur"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;

				$newsheet->setCellValue($col.$rowNumber, $row_["scelle_deux_coffret"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;

				$newsheet->setCellValue($col.$rowNumber, $row_["date_pose_scelle_fr"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;

				$newsheet->setCellValue($col.$rowNumber, $row_["marque_cpteur_post_paie"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;

				$newsheet->setCellValue($col.$rowNumber, $row_["num_serie_cpteur_post_paie"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;

				$newsheet->setCellValue($col.$rowNumber, $row_["index_credit_restant_cpteur_post_paie"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;

				if(strlen(trim($row_["num_serie_cpteur_post_paie"]))>0){
					$newsheet->setCellValue($col.$rowNumber, $row_["date_retrait_cpteur_post_paie_fr"]);
				}
				cellBorder($newsheet,$col.$rowNumber);
				$col++;

				$newsheet->setCellValue($col.$rowNumber, $row_["num_serie_cpteur_replaced"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;
				
					 $ctr_context++;
					 $rowNumber++;
			} 
        }
$rowNumber +=3;
	}
	
}


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="rapport_installation_technicien.xlsx"');
header('Cache-Control: max-age=0');
$objWriter = new Xlsx($objPHPExcel);
$objWriter->save('php://output');
exit();
?>