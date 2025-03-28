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
$utilisateur->is_logged_in();
$utilisateur->readOne();

if (in_array($MULTI_ACCESS_SITE_CODE, $site)) {
	$liste_site =  $cls_report->GetAll_AccessibleUSerSite($utilisateur->code_utilisateur);
} else {
	$liste_site = $site;
}


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


 
$query_installateurs_suppl = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_complet,t_log_installation_users.ref_inst_ FROM t_log_installation_users INNER JOIN t_utilisateurs ON t_log_installation_users.ref_user = t_utilisateurs.code_utilisateur where t_log_installation_users.ref_inst_=:ref_inst_";
$stmt_supp = $db->prepare($query_installateurs_suppl);
$objPHPExcel = new Spreadsheet;



foreach($liste_site as $site_item){
	$site_classe->code_site = $site_item;
	$site_classe->GetDetailIN();
	//$USER_SITENAME = $site_classe->intitule_site;
	
	$cvs_list = $cls_report->GetAll_Site_CVSList($site_item);
	$count_cvs = count($cvs_list);
	$ctr_cvs = 0;
	$start_l = false;
	$rowNumber = 7; //start in row 1
	
	
	
	
	
$objPHPExcel->setActiveSheetIndex(0);
$newsheet =   $objPHPExcel->getActiveSheet();
//DEBUT SYNTHESE

$newsheet->setTitle("SYNTHESE");
	$newsheet ->mergeCells('B'.$rowNumber.':H'.$rowNumber);
	cellStyle($newsheet,'B'.$rowNumber, 14);
	$newsheet->setCellValue('B'.$rowNumber,'TABLEAU RESUME  du '. $du_ .' au '. $au_);
	cellBorder($newsheet,'B'.$rowNumber.':H'.$rowNumber);
	cellAlign($newsheet,'B'.$rowNumber, 'C');
	cellColor($newsheet,'B'.$rowNumber, 'b6b8b9');
	// cellColor($newsheet,'B'.$rowNumber, '6c757d');
	
	
	/*
		$col = 'A'; // start at column A
		foreach($headers_ as $cell) {
			$newsheet->setCellValue($col.$rowNumber,$cell); 
			cellBorder($newsheet,$col.$rowNumber);
			cellStyle($newsheet,$col.$rowNumber,10);
			$col++;
		}
		$rowNumber++;
		$ctr_context =1;
	*/
	$rowNumber++;
	$rowNumber++;
	cellStyle($newsheet,'A'.$rowNumber, 13);
	$newsheet->setCellValue('A'.$rowNumber,'Nombre total de compteurs défectueux');
	// exit;
	// $rowNumber++;
	$rowNumber++;
	$rowNumber++;
	$Total_replaced=0;
	$Total_install=0;
	$col='A';
	//ITERATION DATA IINSTALL FOR CURRENT SITE
		$rowNumber_titre = $rowNumber ;
		$rowNumber_install = $rowNumber + 1;
		// $rowNumber_replace = $rowNumber + 2;
	
			$newsheet->setCellValue($col.$rowNumber_install, 'Défectueux '); 
			cellBorder($newsheet,$col.$rowNumber_install);
			
			// $newsheet->setCellValue($col.$rowNumber_replace, 'Défectueux'); 
			// cellBorder($newsheet,$col.$rowNumber_replace);	
	
	$col='B';	
	foreach($cvs_list as $cvs_item){	
		//RECUPERATION LISTE DES COMPTEURS INSTALLES POUR LE CURRENT CVS AND GIVEN PERIOD 
		// $nbre_install = $cls_report->getCVS_CompteursInstallPeriodeCount($cvs_item["code"], $du, $au);		
		$nbre_replace = $cls_report->getCVS_CompteursReplaceDefectueuxPeriodeCount($cvs_item["code"], $du, $au);	
		
	$Total_replaced += $nbre_replace;
	// $Total_install += $nbre_install;
			
			$newsheet->setCellValue($col.$rowNumber_titre,'CVS/' . $cvs_item["libelle"]); 
			cellBorder($newsheet,$col.$rowNumber_titre);
			cellStyle($newsheet,$col.$rowNumber_titre,10);
			
			$newsheet->setCellValue($col.$rowNumber_install,$nbre_replace . ' '); 
			cellBorder($newsheet,$col.$rowNumber_install);
			
			// $newsheet->setCellValue($col.$rowNumber_replace,$nbre_replace . ' '); 
			// cellBorder($newsheet,$col.$rowNumber_replace);
			
			$col++;	
	}
	// Total_install
	
			$newsheet->setCellValue($col.$rowNumber_titre,'TOTAL'); 
			cellBorder($newsheet,$col.$rowNumber_titre);
			cellStyle($newsheet,$col.$rowNumber_titre,10);
			
			$newsheet->setCellValue($col.$rowNumber_install,$Total_replaced . ' '); 
			cellBorder($newsheet,$col.$rowNumber_install);
			
			// $newsheet->setCellValue($col.$rowNumber_replace,$Total_replaced . ' '); 
			// cellBorder($newsheet,$col.$rowNumber_replace);
			
	$rowNumber = $rowNumber + 3;	
	
	
	
}
//FIN SYNTHESE 
foreach($liste_site as $site_item){
	
	$site_classe->code_site = $site_item;
	$site_classe->GetDetailIN();
	//$USER_SITENAME = $site_classe->intitule_site;
	
	$cvs_list = $cls_report->GetAll_Site_CVSList($site_item);
	$count_cvs = count($cvs_list);
	$ctr_cvs = 0;
	$start_l = false;
	 
//	<img class="logo img-fluid ml-4" src="../image/logo.png" style="height: 30px;"/><br>

                       // <h5 class="pt-2 mb-3 d-inline-block"><?php echo $site_classe->intitule_site;   
                         
                           //echo 'Date impression:  ' . date('m/d/Y'); 
                               
                      //     <h4><?php echo 'Liste des installations effectuées du '. $du_ .' au '. $au_;  
         
	
	//ITERATION DATA IINSTALL FOR CURRENT SITE
	foreach($cvs_list as $cvs_item){
	
		//RECUPERATION LISTE DES COMPTEURS INSTALLES POUR LE CURRENT CVS AND GIVEN PERIOD 
		$data_ = $cls_report->getCVS_CompteursReplaceDefectueux($cvs_item["code"], $du, $au);		
		$nb_data_ = count($data_);
		if($nb_data_ > 0){
			
	
  
$rowNumber = 1; //start in row 1

$col = 'A'; // start at column A
			$newsheet = $objPHPExcel->createSheet();
$newsheet->setCellValue("A1",$site_classe->intitule_site . " :  COMPTEURS DEFECTUEUX ( CVS " . $cvs_item["libelle"] .') ' . count($data_) . " Compteurs");
cellStyle($newsheet,'A1', 14);
$cell = 'Période : du '. $du_ .' au '. $au_;  
$rowNumber++;
 $newsheet->setCellValue($col.$rowNumber,$cell);
cellStyle($newsheet,$col.$rowNumber, 13);
$rowNumber++; 

  // Rename worksheet
$newsheet->setTitle($cvs_item["libelle"]);

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

$newsheet ->mergeCells('H'.$rowNumber.':L'.$rowNumber);
cellStyle($newsheet,'H'.$rowNumber, 14);
$newsheet->setCellValue('H'.$rowNumber,"COMPTEUR DEFECTUEUX");
cellBorder($newsheet,'H'.$rowNumber.':L'.$rowNumber);
cellAlign($newsheet,'H'.$rowNumber, 'C');
cellColor($newsheet,'H'.$rowNumber, 'ff0007');
/*
$newsheet ->mergeCells('N'.$rowNumber.':Q'.$rowNumber);
cellStyle($newsheet,'N'.$rowNumber, 14);
$newsheet->setCellValue('N'.$rowNumber,"COMPTEUR POST PAIEMENT RETIR.");
cellBorder($newsheet,'N'.$rowNumber.':Q'.$rowNumber);
cellAlign($newsheet,'N'.$rowNumber, 'C');
cellColor($newsheet,'N'.$rowNumber, '007bff');
 
cellStyle($newsheet,'R'.$rowNumber, 14);
$newsheet->setCellValue('R'.$rowNumber,"MAINTENANCE"); 
cellAlign($newsheet,'R'.$rowNumber, 'C');
cellColor($newsheet,'R'.$rowNumber, '1a5da6');*/
  

/*
$newsheet ->mergeCells('B1:G1');
cellStyle($newsheet,'B1', 23);
$newsheet->setCellValue('B1',"INFOS DU CLIENT");
cellAlign($newsheet,'B1', 'C');
cellColor($newsheet,'B5', 'F28A8C');
cellBorder($newsheet,'B1:G1', 'C');*/
$rowNumber++;
		$headers_=array("","Quartier", "CVS", "Adresse (Avenue et N°)", "Noms et Postnoms", "PA (POC)", "Tarif", "Marque", "Numéro de compteur", "Date Remplacement", "Equipe Remplacement", "Techniciens");
/** Loop through the result set */


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
					  $marquecompteur->code=$row_["marque_cpteur_replaced"];
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
				// $newsheet->setCellValue($col.$rowNumber,$marquecompteur->code);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;

				$newsheet->setCellValue($col.$rowNumber,$row_["num_serie_cpteur_replaced"]);
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
$clean ="";
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
			// $clean = rtrim($installateurs_suppl,",");
			 
					
			}
			$clean = rtrim($installateurs_suppl,",");
			///////////////////SUPPLEMENTAIRES //////////////////////

				$newsheet->setCellValue($col.$rowNumber, $clean);
				// $newsheet->setCellValue($col.$rowNumber, $installateurs_name);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;
				
					 $ctr_context++;
					 $rowNumber++;
			} 
        }
	}
}


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="rapport_defectueux.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = new Xlsx($objPHPExcel);
$objWriter->save('php://output');
exit();
?>