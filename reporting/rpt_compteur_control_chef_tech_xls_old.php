<?php
//session_start();

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



require_once '../loader/init.php';

//loading Classes filess
Autoloader::Load('../classes');
include_once '../core.php';
// include_once '../classes/core.php';
header('Content-type: text/html;charset=utf-8');
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

$site_classe = new Site($db); 
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
if($site == ($MULTI_ACCESS_SITE_CODE . '')){
	$liste_site =  $cls_report->GetAll_AccessibleUSerSite($utilisateur->code_utilisateur);
}else{
	$liste_site[] = $site;
} 

	$ctr_cvs = 0;
$objPHPExcel = new PHPExcel();

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
	$Total_fraude=0; 
	$col='A';
	//ITERATION DATA IINSTALL FOR CURRENT SITE 
	
			$newsheet->setCellValue($col.$rowNumber, 'N°'); 
			cellBorder($newsheet,$col.$rowNumber);
			$col++;
			$newsheet->setCellValue($col.$rowNumber, 'Techniciens'); 
			cellBorder($newsheet,$col.$rowNumber);
			$col++;
	
			$newsheet->setCellValue($col.$rowNumber, 'Contrôlés '); 
			cellBorder($newsheet,$col.$rowNumber);
			$col++;
			$newsheet->setCellValue($col.$rowNumber, 'Fraudes'); 
			cellBorder($newsheet,$col.$rowNumber); 
	
		$ctr_tech=0;
	foreach($user_list as $user_item){	
	$ctr_tech++;
		//RECUPERATION LISTE DES COMPTEURS INSTALLES PAR LE USER CURRENT  AND GIVEN PERIOD 
		$nbre_control = $cls_report->getSite_CompteursControlPeriodeCountUser($user_item['code_utilisateur'], $site_item, $du, $au);		
		$nbre_fraude = $cls_report->getSite_CompteursFraudePeriodeCountUser($user_item['code_utilisateur'], $site_item, $du, $au);	
		 
	 $Total_general += $nbre_control;
	 $Total_fraude += $nbre_fraude;
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
			$newsheet->setCellValue($col.$rowNumber,$nbre_control . ' '); 
			cellBorder($newsheet,$col.$rowNumber);
			
			$col++;	
			$newsheet->setCellValue($col.$rowNumber,$nbre_fraude . ' '); 
			cellBorder($newsheet,$col.$rowNumber);
			 
			
			//$col++;	
	}
	
	$rowNumber++;
	$newsheet->setCellValue('C'.$rowNumber,$Total_general . ' '); 
	cellBorder($newsheet,'C'.$rowNumber); 	
	$newsheet->setCellValue($col.$rowNumber,$Total_fraude . ' '); 
	cellBorder($newsheet,  $col.$rowNumber); 
	$rowNumber = $rowNumber + 3;	
	
	
}
//FIN SYNTHESE  

foreach($liste_site as $site_item){
	
	$site_classe->code_site = $site_item;
	$site_classe->GetDetailIN();
	//$USER_SITENAME = $site_classe->intitule_site;
 	$user_list = $cls_report->getChiefTechnician($chef_item);
	$chef_item_name = $utilisateur->GetUserDetailName($chef_item);	
	
 
 
$rowNumber = 1; //start in row 1

$col = 'A'; // start at column A
			$newsheet = $objPHPExcel->createSheet();
$newsheet->setCellValue("A1",  "LISTE DES COMPTEURS CONTROLES PAR TECHNICIEN");
cellStyle($newsheet,'A1', 14);
$cell = 'Période : du '. $du_ .' au '. $au_;   
$rowNumber++;
 $newsheet->setCellValue($col.$rowNumber,$cell);
cellStyle($newsheet,$col.$rowNumber, 13);
$rowNumber +=2;

$newsheet->setCellValue($col.$rowNumber,"Chef d'équipe : " . $chef_item_name);
cellStyle($newsheet,$col.$rowNumber, 13);
$rowNumber+=2;

  // Rename worksheet
$newsheet->setTitle('Détails');

			
				$headers_=array("","Quartier","CVS","Adresse (Avenue et N°)","Noms et Postnoms","PA (POC)","Tarif","Marque","Numéro de compteur","Date installation","N° Scellé cpt 1","N° Scellé coffret 2","Date de pose scellé","Scellé compteur brisé 1","Scellé coffret brisé 2","Scellé cpt existant 1","Scellé coffret existant 2","Numéro série compteur trouvé","Etat de fraude","Raison de la fraude", "Etat du compteur","Date de dernier ticket rentré","Qté des derniers Kwh rentrés","Crédit restant","Tarif contrôle","Autocollant placé (Contrôleur)","Observation");
  $newsheet->getColumnDimension('B')->setAutoSize(true); // Content adaptation 
$newsheet->getColumnDimension('C')->setAutoSize(true); // Content adaptation 
$newsheet->getColumnDimension('D')->setAutoSize(true); // Content adaptation 
			

	//ITERATION DATA IINSTALL FOR CURRENT SITE
	foreach($user_list as $user_item){
	
		//RECUPERATION LISTE DES COMPTEURS CONTROLES POUR LE CURRENT CVS AND GIVEN PERIOD 
		$data_ = $cls_report->getSite_CompteursControlUser($user_item['code_utilisateur'], $site_item, $du, $au);		
		$nb_data_ = count($data_);
		if($nb_data_ > 0){
				

                       /* <h5 class="mb-3">CVS :</h5>                                            
                                            <h4 class="text-dark mb-1"><?php echo $cvs_item["libelle"]; ?></h4>*/
		
// $rowNumber = 1; //start in row 1

$col = 'A'; // start at column A

$newsheet->setCellValue($col.$rowNumber,"Technicien : " . $user_item['nom_complet'] . "(". $nb_data_ . " Compteurs)" );
cellStyle($newsheet,$col.$rowNumber, 14); 
$rowNumber++;



//ENTETES PRINCIPALES
 
cellStyle($newsheet,'A'.$rowNumber, 10);
$newsheet->setCellValue('A'.$rowNumber,"N°"); 
cellAlign($newsheet,'A'.$rowNumber, 'C');
cellColor($newsheet,'A'.$rowNumber, 'ffc107'); 


$colons = array('B','C','D','E','F','G');
foreach($colons as $mycol){
cellBorder($newsheet,$mycol.$rowNumber);
cellStyle($newsheet,$mycol.$rowNumber, 10);
cellAlign($newsheet,$mycol.$rowNumber, 'C');
cellColor($newsheet,$mycol.$rowNumber, '17a2b8');	
$newsheet->getColumnDimension($mycol)->setAutoSize(true); // Content adaptation 
}


$colons = array('H','I','J','K','L','M');
foreach($colons as $mycol){
cellBorder($newsheet,$mycol.$rowNumber);
cellStyle($newsheet,$mycol.$rowNumber, 10);
cellAlign($newsheet,$mycol.$rowNumber, 'C');
cellColor($newsheet,$mycol.$rowNumber, 'ffc107');	

$newsheet->getColumnDimension($mycol)->setAutoSize(true); // Content adaptation 
}

$colons = array('N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA');
foreach($colons as $mycol){
cellBorder($newsheet,$mycol.$rowNumber);
cellStyle($newsheet,$mycol.$rowNumber, 10);
cellAlign($newsheet,$mycol.$rowNumber, 'C');
cellColor($newsheet,$mycol.$rowNumber, '007bff');	
$newsheet->getColumnDimension($mycol)->setAutoSize(true); // Content adaptation 
}
 


//end ENTETES PRINCIPALES


    foreach($headers_ as $cell) {
        $newsheet->setCellValue($col.$rowNumber,$cell);
        $col++;
    }
	$rowNumber++;

				  $ctr_context =1;
			foreach($data_ as $row_){
	$col = 'A'; // start at column A
    			
				$cvs->code=$row_["cvs_id"];
					  $cvs->GetDetailIN();
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
				
				 

  //RECUPERATION INFOS FOUND INSTALL DURING CONTROLES
$row_install = $Installation->GetDetail_Light($row_["ref_last_install_found"]);
 $marquecompteur->code=$row_install["marque_compteur"];
					  $marquecompteur->GetDetailIN(); 
 
							

				$newsheet->setCellValue($col.$rowNumber,$marquecompteur->libelle);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				
				$newsheet->setCellValue($col.$rowNumber,$row_install["numero_compteur"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				
				$newsheet->setCellValue($col.$rowNumber,$row_install["date_fin_installation_fr"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				
				$newsheet->setCellValue($col.$rowNumber,$row_install["scelle_un_cpteur"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				
				$newsheet->setCellValue($col.$rowNumber,$row_install["scelle_deux_coffret"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				
				$newsheet->setCellValue($col.$rowNumber,$row_install["date_pose_scelle_fr"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				
				$newsheet->setCellValue($col.$rowNumber,$row_["scelle_compteur_poser"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				
				
				$newsheet->setCellValue($col.$rowNumber,$row_["scelle_coffret_poser"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				
				$newsheet->setCellValue($col.$rowNumber,$row_["scelle_cpt_existant"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				
				$newsheet->setCellValue($col.$rowNumber,$row_["scelle_coffret_existant"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				
				$newsheet->setCellValue($col.$rowNumber,$row_["numero_serie_cpteur"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				
				$newsheet->setCellValue($col.$rowNumber,$row_["cas_de_fraude"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				 
  
			$typeFraude->code=$row_["type_fraude"];
			$typeFraude->GetDetailIN();

			
				$newsheet->setCellValue($col.$rowNumber,$typeFraude->libelle);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
			
				$newsheet->setCellValue($col.$rowNumber,$row_["etat_du_compteur"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
			
				$newsheet->setCellValue($col.$rowNumber,$row_["date_de_dernier_ticket_rentre"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				
				
				$newsheet->setCellValue($col.$rowNumber,$row_["qte_derniers_kwh_rentre"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				
				$newsheet->setCellValue($col.$rowNumber,$row_["credit_restant"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				
				$newsheet->setCellValue($col.$rowNumber,$row_["tarif_controle"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				
				$newsheet->setCellValue($col.$rowNumber,$row_["autocollant_place_controleur"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				
				$newsheet->setCellValue($col.$rowNumber,$row_["observation"]);
				cellBorder($newsheet,$col.$rowNumber);
				$col++; 
				 $rowNumber++;
					   
					 $ctr_context++;

			}
$rowNumber +=3;
        }
		 
	}
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// header('Content-Disposition: attachment;filename="survey.xls"');
header('Content-Disposition: attachment;filename="rapport_controle_technicien.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');


?>