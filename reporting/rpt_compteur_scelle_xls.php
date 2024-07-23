<?php
// session_start();


function cellAlign($newsheet,$cells,$align){
  
	//Text alignment anchor: bbb
	if($align == "R"){
		$newsheet->getStyle($cells)->getAlignment()->setHorizontal (PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); // Align in the horizontal direction
	}else if($align == "J"){
		$newsheet->getStyle($cells)->getAlignment()->setHorizontal (PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY); // Align both ends horizontally
	}else if($align == "C"){
		$newsheet->getStyle($cells)->getAlignment()->setHorizontal (PHPExcel_Style_Alignment::VERTICAL_CENTER); // Center in the vertical direction 
	}
}


function cellBorder($newsheet,$cells){
  
	 
//Set cell border Anchor: bbb 

$newsheet->getStyle($cells)->applyFromArray(
	array(
		'borders' => array (
			'outline' => array (
				  'style' => PHPExcel_Style_Border :: BORDER_THIN, // Set border style
				  // 'style' => PHPExcel_Style_Border :: BORDER_THICK, another style
				  'color' => array ('argb' => 'FF000000'), // Set the border color
		   ),
	 )
	)
  );
}

function cellStyle($newsheet,$cells,$size){
 
	//Set the cell font Anchor: bbb
	// Set B1's text font to Candara. The bold underline of the 20th has a background color.
	//$newsheet->getStyle($cells)->getFont()->setName('Candara');
	$newsheet->getStyle($cells)->getFont()->setSize($size);
	$newsheet->getStyle($cells)->getFont()->setBold(true);
	//$newsheet->getStyle($cells)->getFont()->setUnderline (PHPExcel_Style_Font :: UNDERLINE_SINGLE);
	//$newsheet->getStyle($cells)->getFont()->getColor ()-> setARGB (PHPExcel_Style_Color :: COLOR_WHITE); 

}




function cellColor($newsheet,$cells,$color){

    $newsheet->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
             'rgb' => $color
        )
    ));


	/*
	$newsheet->getStyle($cells)->applyFromArray(
		array(
		  'borders' => array (
			'allborders' => array (
			  'style' => PHPExcel_Style_Border::BORDER_THIN,
			  'color' => array('rgb' => '000000'),        // BLACK
			)
		  )
		)
	  );*/
}
//var_dump($_SESSION['uSession']);
//exit();
require_once '../loader/init.php';
//loading Classes filess
Autoloader::Load('../classes');
include_once '../core.php';
header('Content-type: text/html;charset=utf-8');
$database = new Database();
$db = $database->getConnection();
$utilisateur = new Utilisateur($db);
//$droits = new Droits();
$cls_report = new CLS_Reporting($db);
$cvs = new CVS($db); 
$marquecompteur = new MarqueCompteur($db); 
$adresseItem = new AdresseEntity($db); 
$site_classe = new Site($db); 
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

$objPHPExcel = new PHPExcel();
 // aa7700
 /*
Set document security Anchor: bbb
$ objPHPExcel-> getSecurity ()-> setLockWindows (true);
$ objPHPExcel-> getSecurity ()-> setLockStructure (true);
$ objPHPExcel-> getSecurity ()-> setWorkbookPassword ("PHPExcel"); // Set password 
*/

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
	//////////////////SCELLES POSES /////////////////////////
	$rowNumber++;
	$rowNumber++;
	cellStyle($newsheet,'A'.$rowNumber, 13);
	$newsheet->setCellValue('A'.$rowNumber,'Scellés posés'); 
	$rowNumber++;
	$rowNumber++;
	$Total_sceller=0;
	$Total_install=0;
	$col='A'; 
		$rowNumber_titre = $rowNumber ;
		$rowNumber_install = $rowNumber + 1;
		$rowNumber_replace = $rowNumber + 2;
	
			$newsheet->setCellValue($col.$rowNumber_install, 'Compteurs'); 
			cellBorder($newsheet,$col.$rowNumber_install);
			
			$newsheet->setCellValue($col.$rowNumber_replace, 'Scellés en place'); 
			cellBorder($newsheet,$col.$rowNumber_replace);	
	
	$col='B';	
	foreach($cvs_list as $cvs_item){	 
		$nbre_install = $cls_report->getCVS_CompteursInstallALLEnPlaceCount($cvs_item["code"]);		
		$nbre_sceller = $cls_report->getCVS_CompteursScelleALLPeriodeCount($cvs_item["code"]);	
		
	$Total_sceller += $nbre_sceller;
	$Total_install += $nbre_install;
			
			$newsheet->setCellValue($col.$rowNumber_titre,'CVS/' . $cvs_item["libelle"]); 
			$newsheet->getColumnDimension($col)->setAutoSize(true);
			cellBorder($newsheet,$col.$rowNumber_titre);
			cellStyle($newsheet,$col.$rowNumber_titre,10);
			
			$newsheet->setCellValue($col.$rowNumber_install,$nbre_install . ' '); 
			cellBorder($newsheet,$col.$rowNumber_install);
			
			$newsheet->setCellValue($col.$rowNumber_replace,$nbre_sceller . ' '); 
			cellBorder($newsheet,$col.$rowNumber_replace);
			
			$col++;	
	}
	// Total_install
	
			$newsheet->setCellValue($col.$rowNumber_titre,'TOTAL'); 
			cellBorder($newsheet,$col.$rowNumber_titre);
			cellStyle($newsheet,$col.$rowNumber_titre,10);
			
			$newsheet->setCellValue($col.$rowNumber_install,$Total_install . ' '); 
			cellBorder($newsheet,$col.$rowNumber_install);
			
			$newsheet->setCellValue($col.$rowNumber_replace,$Total_sceller . ' '); 
			cellBorder($newsheet,$col.$rowNumber_replace);
			
	$rowNumber = $rowNumber + 3;
	
	//////////////////END SCELLES POSES /////////////////////////
	 		
	
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
		$data_ = $cls_report->getCVS_CompteursScellerALL($cvs_item["code"], $du, $au);		
		$nb_data_ = count($data_);
		if($nb_data_ > 0){
			
	
  
$rowNumber = 1; //start in row 1

$col = 'A'; // start at column A
			$newsheet = $objPHPExcel->createSheet();
$newsheet->setCellValue("A1","LISTE DES COMPTEURS SCELLES ( CVS " . $cvs_item["libelle"] .') ' . count($data_) . " Compteurs");
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

$newsheet ->mergeCells('H'.$rowNumber.':M'.$rowNumber);
cellStyle($newsheet,'H'.$rowNumber, 14);
$newsheet->setCellValue('H'.$rowNumber,"COMPTEUR PREPAIEMENT INSTALLE");
cellBorder($newsheet,'H'.$rowNumber.':M'.$rowNumber);
cellAlign($newsheet,'H'.$rowNumber, 'C');
cellColor($newsheet,'H'.$rowNumber, 'ffc107');

  

/*
$newsheet ->mergeCells('B1:G1');
cellStyle($newsheet,'B1', 23);
$newsheet->setCellValue('B1',"INFOS DU CLIENT");
cellAlign($newsheet,'B1', 'C');
cellColor($newsheet,'B5', 'F28A8C');
cellBorder($newsheet,'B1:G1', 'C');*/
$rowNumber++;
		$headers_=array("","Quartier", "CVS", "Adresse (Avenue et N°)", "Noms et Postnoms", "PA (POC)", "Tarif", "Marque", "Numéro de compteur", "Date installation", "N° Scellé cpt 1", "N° Scellé coffret 2", "Date de pose scellé");
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
				set_time_limit(0);
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

				$newsheet->setCellValue($col.$rowNumber, $row_["scelle_un_cpteur"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;

				$newsheet->setCellValue($col.$rowNumber, $row_["scelle_deux_coffret"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;

				$newsheet->setCellValue($col.$rowNumber, $row_["date_pose_scelle_fr"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++;
				
					 $ctr_context++;
					 $rowNumber++;
			} 
        }
	}
}


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// header('Content-Disposition: attachment;filename="survey.xls"');
header('Content-Disposition: attachment;filename="rapport_compteurs_scelles.xlsx"');
header('Cache-Control: max-age=0');

/*
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=rapport_installation.xls");
header("Pragma: no-cache");
header("Expires: 0");*/
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>