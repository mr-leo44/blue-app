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
$droits = new Droits();
$cls_report = new CLS_Reporting($db);
$cvs = new CVS($db);
$marquecompteur = new MarqueCompteur($db);
$adresseItem = new AdresseEntity($db);
$site_classe = new Site($db);

$organisme = new Organisme($db);

$liste_site = array();
$header_bg_color = "#ECF2FE"; // "#5969ff";//eee6ff
$site = isset($_POST['site']) ? $_POST['site'] : NULL;
$du = isset($_POST['Du']) ? Utils::ClientToDbDateFormat($_POST['Du']) : "";
$au = isset($_POST['Au']) ? Utils::ClientToDbDateFormat($_POST['Au']) : "";
$du_ = isset($_POST['Du']) ? ($_POST['Du']) : "";
$au_ = isset($_POST['Au']) ? ($_POST['Au']) : "";
$utilisateur->is_logged_in();
$utilisateur->readOne();

try {

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
	$objPHPExcel = new Spreadsheet();
	$objPHPExcel->setActiveSheetIndex(0);
	$newsheet = $objPHPExcel->getActiveSheet();
	$newsheet->setTitle("SYNTHESE");

	foreach ($liste_site as $site_item) {
		$site_classe->code_site = $site_item;
		$site_classe->GetDetailIN();
		$USER_SITENAME = $site_classe->intitule_site;

		$cvs_list = $cls_report->GetAll_Site_CVSList($site_item);
		$count_cvs = count($cvs_list);
		$ctr_cvs = 0;
		$start_l = false;
		$rowNumber = 7; //start in row 1

		$newsheet = $objPHPExcel->getActiveSheet();


		//DEBUT SYNTHESE

		$newsheet->setTitle("SYNTHESE");
		$newsheet->mergeCells('B' . $rowNumber . ':H' . $rowNumber);
		cellStyle($newsheet, 'B' . $rowNumber, 14);
		$newsheet->setCellValue('B' . $rowNumber, 'TABLEAU RESUME  du ' . $du_ . ' au ' . $au_);
		cellBorder($newsheet, 'B' . $rowNumber . ':H' . $rowNumber);
		cellAlign($newsheet, 'B' . $rowNumber, 'C');
		cellColor($newsheet, 'B' . $rowNumber, 'b6b8b9');
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
		cellStyle($newsheet, 'A' . $rowNumber, 13);
		$newsheet->setCellValue('A' . $rowNumber, 'Nombre total de compteurs installés et remplacés');


		$rowNumber++;
		$rowNumber++;
		$Total_replaced = 0;
		$Total_install = 0;
		$col = 'A';
		//ITERATION DATA IINSTALL FOR CURRENT SITE
		$rowNumber_titre = $rowNumber;
		$rowNumber_install = $rowNumber + 1;
		$rowNumber_replace = $rowNumber + 2;

		$newsheet->setCellValue($col . $rowNumber_install, 'Installés ');
		cellBorder($newsheet, $col . $rowNumber_install);

		$newsheet->setCellValue($col . $rowNumber_replace, 'Dont Remplacés');
		cellBorder($newsheet, $col . $rowNumber_replace);

		$col = 'B';
		set_time_limit(300);
		foreach ($cvs_list as $cvs_item) {
			//RECUPERATION LISTE DES COMPTEURS INSTALLES POUR LE CURRENT CVS AND GIVEN PERIOD 
			$nbre_install = $cls_report->getCVS_CompteursInstallPeriodeCount($cvs_item["code"], $du, $au);
			$nbre_replace = $cls_report->getCVS_CompteursReplacePeriodeCount($cvs_item["code"], $du, $au);

			$Total_replaced += $nbre_replace;
			$Total_install += $nbre_install;

			$newsheet->setCellValue($col . $rowNumber_titre, 'CVS/' . $cvs_item["libelle"]);
			cellBorder($newsheet, $col . $rowNumber_titre);
			cellStyle($newsheet, $col . $rowNumber_titre, 10);

			$newsheet->setCellValue($col . $rowNumber_install, $nbre_install . ' ');
			cellBorder($newsheet, $col . $rowNumber_install);

			$newsheet->setCellValue($col . $rowNumber_replace, $nbre_replace . ' ');
			cellBorder($newsheet, $col . $rowNumber_replace);

			$col++;
		}
		// Total_install

		$newsheet->setCellValue($col . $rowNumber_titre, 'TOTAL');
		cellBorder($newsheet, $col . $rowNumber_titre);
		cellStyle($newsheet, $col . $rowNumber_titre, 10);

		$newsheet->setCellValue($col . $rowNumber_install, $Total_install . ' ');
		cellBorder($newsheet, $col . $rowNumber_install);

		$newsheet->setCellValue($col . $rowNumber_replace, $Total_replaced . ' ');
		cellBorder($newsheet, $col . $rowNumber_replace);

		$rowNumber = $rowNumber + 3;
		//////////////////SCELLES POSES /////////////////////////
		$rowNumber++;
		$rowNumber++;
		cellStyle($newsheet, 'A' . $rowNumber, 13);
		$newsheet->setCellValue('A' . $rowNumber, 'Scellés posés');
		$rowNumber++;
		$rowNumber++;
		$Total_sceller = 0;
		$Total_install = 0;
		$col = 'A';
		$rowNumber_titre = $rowNumber;
		$rowNumber_install = $rowNumber + 1;
		$rowNumber_replace = $rowNumber + 2;

		$newsheet->setCellValue($col . $rowNumber_install, 'Compteurs');
		cellBorder($newsheet, $col . $rowNumber_install);

		$newsheet->setCellValue($col . $rowNumber_replace, 'Scellés en place');
		cellBorder($newsheet, $col . $rowNumber_replace);

		$col = 'B';
		$Total_sceller = 0;
		$Total_install = 0;

		set_time_limit(600); // Augmenter le temps d'exécution

		foreach ($cvs_list as $cvs_item) {
			$nbre_install = $cls_report->getCVS_CompteursInstallALLEnPlaceCount($cvs_item["code"]);
			$nbre_sceller = $cls_report->getCVS_CompteursScelleALLPeriodeCount($cvs_item["code"]);

			$Total_sceller += $nbre_sceller;
			$Total_install += $nbre_install;

			$newsheet->setCellValue($col . $rowNumber_titre, 'CVS/' . $cvs_item["libelle"]);
			cellBorder($newsheet, $col . $rowNumber_titre);
			cellStyle($newsheet, $col . $rowNumber_titre, 10);

			$newsheet->setCellValue($col . $rowNumber_install, $nbre_install . ' ');
			cellBorder($newsheet, $col . $rowNumber_install);

			$newsheet->setCellValue($col . $rowNumber_replace, $nbre_sceller . ' ');
			cellBorder($newsheet, $col . $rowNumber_replace);

			$col++;
		}



		// Afficher les totaux pour le débogage


		$newsheet->setCellValue($col . $rowNumber_titre, 'TOTAL');
		cellBorder($newsheet, $col . $rowNumber_titre);
		cellStyle($newsheet, $col . $rowNumber_titre, 10);

		$newsheet->setCellValue($col . $rowNumber_install, $Total_install . ' ');
		cellBorder($newsheet, $col . $rowNumber_install);

		$newsheet->setCellValue($col . $rowNumber_replace, $Total_sceller . ' ');
		cellBorder($newsheet, $col . $rowNumber_replace);

		$rowNumber = $rowNumber + 3;



		//////////////////END SCELLES POSES /////////////////////////

		// $rowNumber = $rowNumber + 3;	 	
		//////////////////POST PAIEMENT RETIRES /////////////////////////
		$rowNumber++;
		$rowNumber++;
		cellStyle($newsheet, 'A' . $rowNumber, 13);
		$newsheet->setCellValue('A' . $rowNumber, 'Compteurs à postpaiement rétirés');
		$rowNumber++;
		$rowNumber++;
		$Total_sceller = 0;
		$Total_postpaie = 0;
		$col = 'A';
		$rowNumber_titre = $rowNumber;
		$rowNumber_install = $rowNumber + 1;
		$rowNumber_replace = $rowNumber + 2;

		$newsheet->setCellValue($col . $rowNumber_install, 'Compteurs post');
		cellBorder($newsheet, $col . $rowNumber_install);

		$col = 'B';

		foreach ($cvs_list as $cvs_item) {
			$nbre_postpaie = $cls_report->getCVS_CompteursPostPeriodeCount($cvs_item["code"], $du, $au);

			$Total_postpaie += $nbre_postpaie;

			$newsheet->setCellValue($col . $rowNumber_titre, 'CVS/' . $cvs_item["libelle"]);
			cellBorder($newsheet, $col . $rowNumber_titre);
			cellStyle($newsheet, $col . $rowNumber_titre, 10);

			$newsheet->setCellValue($col . $rowNumber_install, $nbre_postpaie . ' ');
			cellBorder($newsheet, $col . $rowNumber_install);


			$col++;
		}
		// Total_install

		$newsheet->setCellValue($col . $rowNumber_titre, 'TOTAL');
		cellBorder($newsheet, $col . $rowNumber_titre);
		cellStyle($newsheet, $col . $rowNumber_titre, 10);

		$newsheet->setCellValue($col . $rowNumber_install, $Total_postpaie . ' ');
		cellBorder($newsheet, $col . $rowNumber_install);


		$rowNumber = $rowNumber + 3;

		//////////////////END POST PAIEMENT RETIRES /////////////////////////




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

	foreach ($liste_site as $site_item) {

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
		foreach ($cvs_list as $cvs_item) {

			//RECUPERATION LISTE DES COMPTEURS INSTALLES POUR LE CURRENT CVS AND GIVEN PERIOD 
			$data_ = $cls_report->getCVS_CompteursInstall($cvs_item["code"], $du, $au);
			$nb_data_ = count($data_);
			if ($nb_data_ > 0) {



				$rowNumber = 1; //start in row 1

				$col = 'A'; // start at column A
				$newsheet = $objPHPExcel->createSheet();
				$newsheet->setCellValue("A1", $site_classe->intitule_site . " :  INSTALLATION COMPTEURS A PREPAIEMENT ( CVS " . $cvs_item["libelle"] . ') ' . count($data_) . " Compteurs");
				cellStyle($newsheet, 'A1', 14);
				$cell = 'Période : du ' . $du_ . ' au ' . $au_;
				$rowNumber++;
				$newsheet->setCellValue($col . $rowNumber, $cell);
				cellStyle($newsheet, $col . $rowNumber, 13);
				$rowNumber++;
				/*
				//Add a picture for the header. Valid in office. Invalid in wps. Anchor: bbb
				$objDrawing = new PHPExcel_Worksheet_HeaderFooterDrawing();
				$objDrawing->setName('PHPExcel logo');
				$objDrawing->setPath('./images/logo.png'); 
				//$newsheet->getHeaderFooter()->addImage ($objDrawing, PHPExcel_Worksheet_HeaderFooter::IMAGE_HEADER_LEFT); 
				$objDrawing->setCoordinates('B5'); // Image add position
				$objDrawing->setOffsetX(21);
				//$objDrawing->setRotation(25);
				$objDrawing->setHeight(36);
				//$objDrawing->getShadow()->setVisible(true);
				//$objDrawing->getShadow()->setDirection(45);
				$objDrawing->setWorksheet($newsheet);
				// You can also add pictures produced by the gd library, see the example 25 for details 
	*/

				// Rename worksheet
				$newsheet->setTitle($cvs_item["libelle"]);

				//ENTETES PRINCIPALES

				cellStyle($newsheet, 'A' . $rowNumber, 14);
				$newsheet->setCellValue('A' . $rowNumber, "N°");
				cellAlign($newsheet, 'A' . $rowNumber, 'C');
				cellColor($newsheet, 'A' . $rowNumber, 'ffc107');

				$newsheet->mergeCells('B' . $rowNumber . ':G' . $rowNumber);
				cellStyle($newsheet, 'B' . $rowNumber, 14);
				$newsheet->setCellValue('B' . $rowNumber, "INFOS DU CLIENT");
				cellBorder($newsheet, 'B' . $rowNumber . ':G' . $rowNumber);
				cellAlign($newsheet, 'B' . $rowNumber, 'C');
				cellColor($newsheet, 'B' . $rowNumber, '17a2b8');

				$newsheet->mergeCells('H' . $rowNumber . ':M' . $rowNumber);
				cellStyle($newsheet, 'H' . $rowNumber, 14);
				$newsheet->setCellValue('H' . $rowNumber, "COMPTEUR PREPAIEMENT INSTALLE");
				cellBorder($newsheet, 'H' . $rowNumber . ':M' . $rowNumber);
				cellAlign($newsheet, 'H' . $rowNumber, 'C');
				cellColor($newsheet, 'H' . $rowNumber, 'ffc107');

				$newsheet->mergeCells('N' . $rowNumber . ':Q' . $rowNumber);
				cellStyle($newsheet, 'N' . $rowNumber, 14);
				$newsheet->setCellValue('N' . $rowNumber, "COMPTEUR POST PAIEMENT RETIR.");
				cellBorder($newsheet, 'N' . $rowNumber . ':Q' . $rowNumber);
				cellAlign($newsheet, 'N' . $rowNumber, 'C');
				cellColor($newsheet, 'N' . $rowNumber, '007bff');

				cellStyle($newsheet, 'R' . $rowNumber, 14);
				$newsheet->setCellValue('R' . $rowNumber, "MAINTENANCE");
				cellAlign($newsheet, 'R' . $rowNumber, 'C');
				cellColor($newsheet, 'R' . $rowNumber, '1a5da6');


				/*
	$newsheet ->mergeCells('B1:G1');
	cellStyle($newsheet,'B1', 23);
	$newsheet->setCellValue('B1',"INFOS DU CLIENT");
	cellAlign($newsheet,'B1', 'C');
	cellColor($newsheet,'B5', 'F28A8C');
	cellBorder($newsheet,'B1:G1', 'C');*/
				$rowNumber++;
				$headers_ = array("", "Quartier", "CVS", "Adresse (Avenue et N°)", "Noms et Postnoms", "PA (POC)", "Tarif", "Marque", "Numéro de compteur", "Date installation", "Equipe Installation", "Installateurs", "N° Scellé cpt 1", "N° Scellé coffret 2", "Date de pose scellé", "Marque", "Numéro de serie", "Index", "Date retrait", "Compteur
				prépaiement remplacé");
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
				$newsheet->getColumnDimension('O')->setAutoSize(true); // Content adaptation 
				$newsheet->getColumnDimension('Q')->setAutoSize(true); // Content adaptation 
				$newsheet->getColumnDimension('R')->setAutoSize(true); // Content adaptation 

				$col = 'A'; // start at column A
				foreach ($headers_ as $cell) {
					$newsheet->setCellValue($col . $rowNumber, $cell);
					cellBorder($newsheet, $col . $rowNumber);
					cellStyle($newsheet, $col . $rowNumber, 10);
					$col++;
				}
				$rowNumber++;
				$ctr_context = 1;
				foreach ($data_ as $row_) {

					$col = 'A'; // start at column A
					$cvs->code = $row_["cvs_id"];
					$cvs->GetDetailIN();
					$marquecompteur->code = $row_["marque_compteur"];
					$marquecompteur->GetDetailIN();

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


					$newsheet->setCellValue($col . $rowNumber, $marquecompteur->libelle);
					cellBorder($newsheet, $col . $rowNumber);
					$col++;

					$newsheet->setCellValue($col . $rowNumber, $row_["numero_compteur"]);
					cellBorder($newsheet, $col . $rowNumber);
					$col++;

					$newsheet->setCellValue($col . $rowNumber, $row_["date_fin_installation_fr"]);
					cellBorder($newsheet, $col . $rowNumber);
					$col++;
					// Equipe
					$organisme->ref_organisme = $row_["id_equipe"];
					$organisme->GetDetailIN();

					$newsheet->setCellValue($col . $rowNumber,  $organisme->denomination);
					cellBorder($newsheet, $col . $rowNumber);
					$col++;

					// Installateurs

					///////////////////SUPPLEMENTAIRES //////////////////////

					$installateurs_name = $utilisateur->GetUserDetailName($row_['installateur']);
					$clean = $installateurs_name;
					// var_dump($installateurs_name);
					// exit;		
					// exit;		
					$installateurs_suppl = $installateurs_name;
					$stmt_supp->bindValue(":ref_inst_", $row_["id_install"]);
					$stmt_supp->execute();
					$ro = $stmt_supp->fetchAll(PDO::FETCH_ASSOC);
					if (count($ro) > 0) {
						foreach ($ro as $ins_suppItem) {
							$installateurs_suppl .= "  "  . $ins_suppItem["nom_complet"] . " , ";
						}
						$clean = rtrim($installateurs_suppl, " , ");
					}
					///////////////////SUPPLEMENTAIRES //////////////////////

					$newsheet->setCellValue($col . $rowNumber, $clean);
					cellBorder($newsheet, $col . $rowNumber);
					$col++;

					$newsheet->setCellValue($col . $rowNumber, $row_["scelle_un_cpteur"]);
					cellBorder($newsheet, $col . $rowNumber);
					$col++;

					$newsheet->setCellValue($col . $rowNumber, $row_["scelle_deux_coffret"]);
					cellBorder($newsheet, $col . $rowNumber);
					$col++;

					$newsheet->setCellValue($col . $rowNumber, $row_["date_pose_scelle_fr"]);
					cellBorder($newsheet, $col . $rowNumber);
					$col++;

					$newsheet->setCellValue($col . $rowNumber, $row_["marque_cpteur_post_paie"]);
					cellBorder($newsheet, $col . $rowNumber);
					$col++;

					$newsheet->setCellValue($col . $rowNumber, $row_["num_serie_cpteur_post_paie"]);
					cellBorder($newsheet, $col . $rowNumber);
					$col++;

					$newsheet->setCellValue($col . $rowNumber, $row_["index_credit_restant_cpteur_post_paie"]);
					cellBorder($newsheet, $col . $rowNumber);
					$col++;

					if (strlen(trim($row_["num_serie_cpteur_post_paie"])) > 0) {
						$newsheet->setCellValue($col . $rowNumber, $row_["date_retrait_cpteur_post_paie_fr"]);
					}
					cellBorder($newsheet, $col . $rowNumber);
					$col++;

					$newsheet->setCellValue($col . $rowNumber, $row_["num_serie_cpteur_replaced"]);
					cellBorder($newsheet, $col . $rowNumber);
					$col++;

					$ctr_context++;
					$rowNumber++;
				}
			}
		}
	}


	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="rapport_installation.xlsx"');
	header('Cache-Control: max-age=0');

	$objWriter = new Xlsx($objPHPExcel);
	$objWriter->save('php://output');
	exit();
} catch (Exception $exception) {
	// log error message here
	echo "An error occurred: " . $exception->getMessage();
}
