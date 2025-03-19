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



set_include_path(get_include_path() . PATH_SEPARATOR . "..");

header('Content-type: text/html;charset=utf-8');
$database = new Database();
$db = $database->getConnection();
$utilisateur = new Utilisateur($db);
$cls_report = new CLS_Reporting($db);
$Installation = new Installation($db);
$adresseItem = new AdresseEntity($db);
$cvs = new CVS($db);
$marquecompteur = new MarqueCompteur($db);
$typeFraude = new PARAM_TypeFraude($db);

$site_classe = new Site($db);
$liste_site = array();
$header_bg_color = "#ECF2FE"; // "#5969ff";//eee6ff
$site = isset($_POST['site']) ? $_POST['site'] : NULL;
$du = isset($_POST['Du']) ? Utils::ClientToDbDateFormat($_POST['Du']) : "";
$au = isset($_POST['Au']) ? Utils::ClientToDbDateFormat($_POST['Au']) : "";
$du_ = isset($_POST['Du']) ? ($_POST['Du']) : "";
$au_ = isset($_POST['Au']) ? ($_POST['Au']) : "";
$chef_item = isset($_POST['chef_item']) ? ($_POST['chef_item']) : "";
$utilisateur->is_logged_in();
$utilisateur->readOne();

if (in_array($MULTI_ACCESS_SITE_CODE, $site)) {
	$liste_site =  $cls_report->GetAll_AccessibleUSerSite($utilisateur->code_utilisateur);
} else {
	$liste_site = $site;
}

$ctr_cvs = 0;
$objPHPExcel =  new Spreadsheet();
$sheet = $objPHPExcel->getActiveSheet();
$sheet->setTitle('SYNTHESE');
foreach ($liste_site as $site_item) {
    $site_classe->code_site = $site_item;
    $site_classe->GetDetailIN();
    
    $user_list = $cls_report->getChiefTechnician($chef_item);
    
    // Parcours des techniciens
    $ctr_tech = 0;
    foreach ($user_list as $user_item) {
        $ctr_tech++;
        
        // Récupérer les données pour le technicien
        $nbre_control = $cls_report->getSite_CompteursControlPeriodeCountUser($user_item['code_utilisateur'], $site_item, $du_, $au_);
        $nbre_fraude = $cls_report->getSite_CompteursFraudePeriodeCountUser($user_item['code_utilisateur'], $site_item, $du_, $au_);
        
        // Mettre à jour les totaux
        $Total_general += $nbre_control;
        $Total_fraude += $nbre_fraude;
        
        // Remplir les données
        $sheet->setCellValue('A' . $rowNumber, $ctr_tech);
        $sheet->setCellValue('B' . $rowNumber, $user_item['nom_complet']);
        $sheet->setCellValue('C' . $rowNumber, $nbre_control . ' ');
        $sheet->setCellValue('D' . $rowNumber, $nbre_fraude . ' ');
        
        $rowNumber++;
    }

    // Total général
    $sheet->setCellValue('C' . $rowNumber, $Total_general . ' ');
    $sheet->setCellValue('D' . $rowNumber, $Total_fraude . ' ');

    $rowNumber += 3; // Espace pour le prochain site
}

//FIN SYNTHESE  

foreach ($liste_site as $site_item) {

	$site_classe->code_site = $site_item;
	$site_classe->GetDetailIN();
	//$USER_SITENAME = $site_classe->intitule_site;
	$user_list = $cls_report->getChiefTechnician($chef_item);
	$chef_item_name = $utilisateur->GetUserDetailName($chef_item);



	$rowNumber = 1; //start in row 1

	$col = 'A'; // start at column A
	$newsheet = $objPHPExcel->createSheet();
	$newsheet->setCellValue("A1",  "LISTE DES COMPTEURS CONTROLES PAR TECHNICIEN");
	cellStyle($newsheet, 'A1', 14);
	$cell = 'Période : du ' . $du_ . ' au ' . $au_;
	$rowNumber++;
	$newsheet->setCellValue($col . $rowNumber, $cell);
	cellStyle($newsheet, $col . $rowNumber, 13);
	$rowNumber += 2;

	$newsheet->setCellValue($col . $rowNumber, "Chef d'équipe : " . $chef_item_name);
	cellStyle($newsheet, $col . $rowNumber, 13);
	$rowNumber += 2;

	// Rename worksheet
	$newsheet->setTitle('Détails');


	$headers_ = array("", "Quartier", "CVS", "Adresse (Avenue et N°)", "Noms et Postnoms", "PA (POC)", "Tarif", "Marque", "Numéro de compteur", "Date installation", "N° Scellé cpt 1", "N° Scellé coffret 2", "Date de pose scellé", "Scellé compteur brisé 1", "Scellé coffret brisé 2", "Scellé cpt existant 1", "Scellé coffret existant 2", "Numéro série compteur trouvé", "Etat de fraude", "Raison de la fraude", "Etat du compteur", "Date de dernier ticket rentré", "Qté des derniers Kwh rentrés", "Crédit restant", "Tarif contrôle", "Autocollant placé (Contrôleur)", "Observation");
	$newsheet->getColumnDimension('B')->setAutoSize(true); // Content adaptation 
	$newsheet->getColumnDimension('C')->setAutoSize(true); // Content adaptation 
	$newsheet->getColumnDimension('D')->setAutoSize(true); // Content adaptation 


	//ITERATION DATA IINSTALL FOR CURRENT SITE
	foreach ($user_list as $user_item) {

		//RECUPERATION LISTE DES COMPTEURS CONTROLES POUR LE CURRENT CVS AND GIVEN PERIOD 
		$data_ = $cls_report->getSite_CompteursControlUser($user_item['code_utilisateur'], $site_item, $du, $au);
		$nb_data_ = count($data_);
		if ($nb_data_ > 0) {
			$col = 'A'; // start at column A

			$newsheet->setCellValue($col . $rowNumber, "Technicien : " . $user_item['nom_complet'] . "(" . $nb_data_ . " Compteurs)");
			cellStyle($newsheet, $col . $rowNumber, 14);
			$rowNumber++;
			//ENTETES PRINCIPALES

			cellStyle($newsheet, 'A' . $rowNumber, 10);
			$newsheet->setCellValue('A' . $rowNumber, "N°");
			cellAlign($newsheet, 'A' . $rowNumber, 'C');
			cellColor($newsheet, 'A' . $rowNumber, 'ffc107');

			$colons = array('B', 'C', 'D', 'E', 'F', 'G');
			foreach ($colons as $mycol) {
				cellBorder($newsheet, $mycol . $rowNumber);
				cellStyle($newsheet, $mycol . $rowNumber, 10);
				cellAlign($newsheet, $mycol . $rowNumber, 'C');
				cellColor($newsheet, $mycol . $rowNumber, '17a2b8');
				$newsheet->getColumnDimension($mycol)->setAutoSize(true); // Content adaptation 
			}


			$colons = array('H', 'I', 'J', 'K', 'L', 'M');
			foreach ($colons as $mycol) {
				cellBorder($newsheet, $mycol . $rowNumber);
				cellStyle($newsheet, $mycol . $rowNumber, 10);
				cellAlign($newsheet, $mycol . $rowNumber, 'C');
				cellColor($newsheet, $mycol . $rowNumber, 'ffc107');

				$newsheet->getColumnDimension($mycol)->setAutoSize(true); // Content adaptation 
			}

			$colons = array('N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA');
			foreach ($colons as $mycol) {
				cellBorder($newsheet, $mycol . $rowNumber);
				cellStyle($newsheet, $mycol . $rowNumber, 10);
				cellAlign($newsheet, $mycol . $rowNumber, 'C');
				cellColor($newsheet, $mycol . $rowNumber, '007bff');
				$newsheet->getColumnDimension($mycol)->setAutoSize(true); // Content adaptation 
			}

			//end ENTETES PRINCIPALES
			foreach ($headers_ as $cell) {
				$newsheet->setCellValue($col . $rowNumber, $cell);
				$col++;
			}
			$rowNumber++;

			$ctr_context = 1;
			foreach ($data_ as $row_) {
				$col = 'A'; // start at column A

				$cvs->code = $row_["cvs_id"];
				$cvs->GetDetailIN();
				$E_item = $adresseItem->GetAdressInfo($row_["adresse_id"]);
				$numero =  $E_item['numero'];
				$avenue =  $adresseItem->GetLabel($E_item['avenue']);
				$quartier_ = $adresseItem->GetLabel($E_item['quartier_id']);

				$newsheet->setCellValue($col . $rowNumber, $ctr_context);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $quartier_);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $cvs->libelle);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;


				$newsheet->setCellValue($col . $rowNumber, $avenue . ' ' . $numero);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_["nom_client_blue"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_["p_a"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_["tarif_identif"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				//RECUPERATION INFOS FOUND INSTALL DURING CONTROLES
				$row_install = $Installation->GetDetail_Light($row_["ref_last_install_found"]);
				$marquecompteur->code = $row_install["marque_compteur"];
				$marquecompteur->GetDetailIN();

				$newsheet->setCellValue($col . $rowNumber, $marquecompteur->libelle);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_install["numero_compteur"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_install["date_fin_installation_fr"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_install["scelle_un_cpteur"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_install["scelle_deux_coffret"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_install["date_pose_scelle_fr"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_["scelle_compteur_poser"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;


				$newsheet->setCellValue($col . $rowNumber, $row_["scelle_coffret_poser"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_["scelle_cpt_existant"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_["scelle_coffret_existant"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_["numero_serie_cpteur"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_["cas_de_fraude"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;


				$typeFraude->code = $row_["type_fraude"];
				$typeFraude->GetDetailIN();


				$newsheet->setCellValue($col . $rowNumber, $typeFraude->libelle);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_["etat_du_compteur"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_["date_de_dernier_ticket_rentre"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;


				$newsheet->setCellValue($col . $rowNumber, $row_["qte_derniers_kwh_rentre"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_["credit_restant"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_["tarif_controle"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_["autocollant_place_controleur"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;

				$newsheet->setCellValue($col . $rowNumber, $row_["observation"]);
				cellBorder($newsheet, $col . $rowNumber);
				$col++;
				$rowNumber++;

				$ctr_context++;
			}
			$rowNumber += 3;
		}
	}
}




header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="rapport_controle_technicien.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($objPHPExcel);
$writer->save('php://output');