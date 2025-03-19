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
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


$database = new Database();
$db = $database->getConnection();
$utilisateur = new Utilisateur($db);
//$droits = new Droits();
$cls_report = new CLS_Reporting($db);
$Installation = new Installation($db);
$adresseItem = new AdresseEntity($db);
$cvs = new CVS($db);
$marquecompteur = new MarqueCompteur($db);
$typeFraude = new PARAM_TypeFraude($db);
$typeObservation = new PARAM_TypeObservation($db);

$item_ctl = new CLS_Controle($db);
$site_classe = new Site($db);
$liste_site = array();
$header_bg_color = "#ECF2FE"; // "#5969ff";//eee6ff
$site = isset($_POST['site']) ? $_POST['site'] : NULL;
$du = isset($_POST['Du']) ? Utils::ClientToDbDateFormat($_POST['Du']) : "";
$au = isset($_POST['Au']) ? Utils::ClientToDbDateFormat($_POST['Au']) : "";
$du_ = isset($_POST['Du']) ? ($_POST['Du']) : "";
$au_ = isset($_POST['Au']) ? ($_POST['Au']) : "";
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

$ctr_cvs = 0;
$objPHPExcel = new Spreadsheet();
$objPHPExcel->setActiveSheetIndex(0);
$newsheet = $objPHPExcel->getActiveSheet();
$newsheet->setTitle("SYNTHESE");



foreach ($liste_site as $site_item) {
	
	$site_classe->code_site = $site_item;
	$site_classe->GetDetailIN();
	//$USER_SITENAME = $site_classe->intitule_site;

	$cvs_list = $cls_report->GetAll_Site_CVSList($site_item);
	$count_cvs = count($cvs_list);
	$ctr_cvs = 0;
	$start_l = false;
	$rowNumber = 7; //start in row 1

	$newsheet =   $objPHPExcel->getActiveSheet();
	//DEBUT SYNTHESE

	$newsheet->setTitle("SYNTHESE");
	$newsheet->mergeCells('B' . $rowNumber . ':H' . $rowNumber);
	cellStyle($newsheet, 'B' . $rowNumber, 14);
	$newsheet->setCellValue('B' . $rowNumber, 'TABLEAU RESUME  du ' . $du_ . ' au ' . $au_);
	cellBorder($newsheet, 'B' . $rowNumber . ':H' . $rowNumber);
	cellAlign($newsheet, 'B' . $rowNumber, 'C');
	cellColor($newsheet, 'B' . $rowNumber, 'b6b8b9');
	// cellColor($newsheet,'B'.$rowNumber, '6c757d');


	//////////////////CONTROLES /////////////////////////
	$rowNumber++;
	$rowNumber++;
	cellStyle($newsheet, 'A' . $rowNumber, 13);
	$newsheet->setCellValue('A' . $rowNumber, 'Compteurs contrôlés');
	$rowNumber++;
	$rowNumber++;
	$Total_sceller = 0;
	$Total_install = 0;
	$col = 'A';
	$rowNumber_titre = $rowNumber;
	$rowNumber_install = $rowNumber + 1;
	$rowNumber_replace = $rowNumber + 2;

	$newsheet->setCellValue($col . $rowNumber_install, 'Contrôlés');
	cellBorder($newsheet, $col . $rowNumber_install);

	$newsheet->setCellValue($col . $rowNumber_replace, 'Fraudes');
	cellBorder($newsheet, $col . $rowNumber_replace);

	$col = 'B';
	foreach ($cvs_list as $cvs_item) {
		$nbre_install = $cls_report->getCVS_CompteursControlPeriodeCount($cvs_item["code"], $du, $au);
		$nbre_sceller = $cls_report->getCVS_CompteursFraudePeriodeCount($cvs_item["code"], $du, $au);

		$Total_sceller += $nbre_sceller;
		$Total_install += $nbre_install;

		$newsheet->setCellValue($col . $rowNumber_titre, 'CVS/' . $cvs_item["libelle"]);
		$newsheet->getColumnDimension($col)->setAutoSize(true);
		cellBorder($newsheet, $col . $rowNumber_titre);
		cellStyle($newsheet, $col . $rowNumber_titre, 10);

		$newsheet->setCellValue($col . $rowNumber_install, $nbre_install . ' ');
		cellBorder($newsheet, $col . $rowNumber_install);

		$newsheet->setCellValue($col . $rowNumber_replace, $nbre_sceller . ' ');
		cellBorder($newsheet, $col . $rowNumber_replace);

		$col++;
	}
	// Total_install

	$newsheet->setCellValue($col . $rowNumber_titre, 'TOTAL');
	cellBorder($newsheet, $col . $rowNumber_titre);
	cellStyle($newsheet, $col . $rowNumber_titre, 10);

	$newsheet->setCellValue($col . $rowNumber_install, $Total_install . ' ');
	cellBorder($newsheet, $col . $rowNumber_install);

	$newsheet->setCellValue($col . $rowNumber_replace, $Total_sceller . ' ');
	cellBorder($newsheet, $col . $rowNumber_replace);

	$rowNumber = $rowNumber + 3;

	//////////////////END CONTROLE  /////////////////////////


}
//FIN SYNTHESE  

foreach ($liste_site as $site_item) {

	$site_classe->code_site = $site_item;
	$site_classe->GetDetailIN();
	//$USER_SITENAME = $site_classe->intitule_site;

	$cvs_list = $cls_report->GetAll_Site_CVSList($site_item);
	$count_cvs = count($cvs_list);
	$start_l = false;

	$rowNumber = 1; //start in row 1

	$col = 'A'; // start at column A
	$newsheet = $objPHPExcel->createSheet();
	$newsheet->setCellValue("A1",  "LISTE DES COMPTEURS CONTROLES");
	cellStyle($newsheet, 'A1', 14);
	$cell = 'Période : du ' . $du_ . ' au ' . $au_;
	$rowNumber++;
	$newsheet->setCellValue($col . $rowNumber, $cell);
	cellStyle($newsheet, $col . $rowNumber, 13);
	$rowNumber++;


	// Rename worksheet
	$newsheet->setTitle('Compteurs contrôlés');

	//ENTETES PRINCIPALES

	cellStyle($newsheet, 'A' . $rowNumber, 10);
	$newsheet->setCellValue('A' . $rowNumber, "N°");
	cellAlign($newsheet, 'A' . $rowNumber, 'C');
	cellColor($newsheet, 'A' . $rowNumber, 'b3d0ee');


	$colons = array('B', 'C', 'D', 'E', 'F', 'G');
	foreach ($colons as $mycol) {
		cellBorder($newsheet, $mycol . $rowNumber);
		cellStyle($newsheet, $mycol . $rowNumber, 10);
		cellAlign($newsheet, $mycol . $rowNumber, 'C');
		cellColor($newsheet, $mycol . $rowNumber, 'b3d0ee');
		$newsheet->getColumnDimension($mycol)->setAutoSize(true); // Content adaptation 
	}


	$colons = array('H', 'I', 'J', 'K', 'L', 'M');
	foreach ($colons as $mycol) {
		cellBorder($newsheet, $mycol . $rowNumber);
		cellStyle($newsheet, $mycol . $rowNumber, 10);
		cellAlign($newsheet, $mycol . $rowNumber, 'C');
		cellColor($newsheet, $mycol . $rowNumber, 'b3d0ee');

		$newsheet->getColumnDimension($mycol)->setAutoSize(true); // Content adaptation 
	}

	$colons = array('N', 'O', 'P', 'Q');
	foreach ($colons as $mycol) {
		cellBorder($newsheet, $mycol . $rowNumber);
		cellStyle($newsheet, $mycol . $rowNumber, 10);
		cellAlign($newsheet, $mycol . $rowNumber, 'C');
		cellColor($newsheet, $mycol . $rowNumber, 'b3d0ee');
		$newsheet->getColumnDimension($mycol)->setAutoSize(true); // Content adaptation 
	}

	$headers_ = array("Date du controle", "Controleur", "Quartier", "CVS", "Adresse (Avenue et N°)", "Noms et Postnoms", "Date Installation", "N° de Compteur à vérifier", "N° du Compteur trouvé", "Date du dernier ticket", "Montant du dernier ticket", "Crédit restant", "Conso 30 jours", "Etat de fraude", "Type(s) de fraude", "Code(s) du diagnostic", "Observations complémentaires");


	$newsheet->getColumnDimension('B')->setAutoSize(true); // Content adaptation 
	$newsheet->getColumnDimension('C')->setAutoSize(true); // Content adaptation 
	$newsheet->getColumnDimension('D')->setAutoSize(true); // Content adaptation 
	$col = 'A'; // start at column A
	foreach ($headers_ as $cell) {
		$newsheet->setCellValue($col . $rowNumber, $cell);
		$col++;
	}
	$rowNumber++;
	$ctr_context = 1;
	//ITERATION DATA IINSTALL FOR CURRENT SITE
	foreach ($cvs_list as $cvs_item) {

		//RECUPERATION LISTE DES COMPTEURS CONTROLES POUR LE CURRENT CVS AND GIVEN PERIOD 
		$data_ = $cls_report->getCVS_CompteursControl($cvs_item["code"], $du, $au);
		$nb_data_ = count($data_);
		if ($nb_data_ > 0) {


			/* <h5 class="mb-3">CVS :</h5>                                            
                                            <h4 class="text-dark mb-1"><?php echo $cvs_item["libelle"]; ?></h4>*/

			// $rowNumber = 1; //start in row 1

			$col = 'B'; // start at column A 

			foreach ($data_ as $row_) {
				$col = 'A'; // start at column A
				$date_controle_fr = $row_["date_controle_fr"];
				$controleur_name = $utilisateur->GetUserDetailName($row_['controleur']);

				$cvs->code = $row_["cvs_id"];
				$cvs->GetDetailIN();
				$E_item = $adresseItem->GetAdressInfo($row_["adresse_id"]);
				$numero =  $E_item['numero'];
				$avenue =  $adresseItem->GetLabel($E_item['avenue']);
				$quartier_ = $adresseItem->GetLabel($E_item['quartier_id']);
				$cvs_libelle = $cvs->libelle;
				$adresse = $avenue . ' ' . $numero;
				$nom_postnom = $row_["nom_client_blue"];



				//RECUPERATION INFOS FOUND INSTALL DURING CONTROLES
				$row_install = $Installation->GetDetail_Light($row_["ref_last_install_found"]);

				//RECUPERATION INFOS FOUND INSTALL DURING CONTROLES		
				$date_installation = $row_install["date_fin_installation_fr"];
				$numero_compteur_a_verifier = $row_install["numero_compteur"];
				$numero_compteur_trouver = $row_["numero_serie_cpteur"];
				$date_de_dernier_ticket_rentre = "";
				if (!empty($row_["date_de_dernier_ticket_rentre"])) {
					$date_de_dernier_ticket_rentre = Utils::DbToClientDateFormat($row_["date_de_dernier_ticket_rentre"]);
				}
				// $date_de_dernier_ticket_rentre = $row_["date_de_dernier_ticket_rentre"];
				$valeur_du_dernier_ticket = $row_["date_de_dernier_ticket_rentre"];
				$credit_restant = $row_["credit_restant"];
				$consommation_de_30jours_actuels = $row_["consommation_de_30jours_actuels"];

				$etat_fraude = $row_["cas_de_fraude"];



				$item_ctl->ref_fiche_controle = $row_["ref_fiche_controle"];
				$data = $item_ctl->GetDetail($utilisateur->id_service_group);
				$type_fraudes = "";
				if (!empty($data['fraudes'])) {
					$lst_fraudes_selected = "";
					foreach ($data['fraudes'] as $value_) {
						$typeFraude->code = $value_['ref_code_fraude'];
						$typeFraude->GetDetailIN();
						// $lst_fraudes_selected .=$value_['ref_code_fraude'] . "-";
						$lst_fraudes_selected .= $typeFraude->code_label . "-";
					}
					$type_fraudes =  rtrim($lst_fraudes_selected, "-");
				}

				$type_codes_diagnostics = "";
				if (!empty($data['codes_observations'])) {
					$lst_type_codes_diagnostics_selected = "";
					foreach ($data['codes_observations'] as $value_) {

						$typeObservation->code = $value_['ref_code_obs'];
						$typeObservation->GetDetailIN();
						$lst_type_codes_diagnostics_selected .= $typeObservation->code_label . "-";


						// $lst_type_codes_diagnostics_selected .=$value_['ref_code_obs'] . "-";
					}
					$type_codes_diagnostics =  rtrim($lst_type_codes_diagnostics_selected, "-");
				}


				$diagnostics_general = $row_["diagnostics_general"];


				///ECRITURES CELLULES DATA

				$newsheet->setCellValue($col . $rowNumber, $date_controle_fr);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $controleur_name);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $quartier_);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $cvs_libelle);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $adresse);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $nom_postnom);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $date_installation);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $numero_compteur_a_verifier);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $numero_compteur_trouver);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $date_de_dernier_ticket_rentre);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $valeur_du_dernier_ticket);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $credit_restant);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $consommation_de_30jours_actuels);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $etat_fraude);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $type_fraudes);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $type_codes_diagnostics);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $diagnostics_general);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				///ECRITURES CELLULES DATA
				$rowNumber++;

				$ctr_context++;
			}
		}
	}
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="rapport_compteur_control.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = new Xlsx($objPHPExcel);
$objWriter->save('php://output');
