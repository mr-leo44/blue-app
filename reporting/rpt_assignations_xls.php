<?php
// session_start();


function cellAlign($newsheet, $cells, $align)
{

    //Text alignment anchor: bbb
    if ($align == "R") {
        $newsheet->getStyle($cells)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT); // Align in the horizontal direction
    } else if ($align == "J") {
        $newsheet->getStyle($cells)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_JUSTIFY); // Align both ends horizontally
    } else if ($align == "C") {
        $newsheet->getStyle($cells)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER); // Center in the vertical direction 
    }
}


function cellBorder($newsheet, $cells)
{


    //Set cell border Anchor: bbb 

    $newsheet->getStyle($cells)->applyFromArray(
        array(
            'borders' => array(
                'outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN, // Set border style
                    // 'style' => PHPExcel_Style_Border :: BORDER_THICK, another style
                    'color' => array('argb' => 'FF000000'), // Set the border color
                ),
            )
        )
    );
}

function cellStyle($newsheet, $cells, $size)
{

    //Set the cell font Anchor: bbb
    // Set B1's text font to Candara. The bold underline of the 20th has a background color.
    //$newsheet->getStyle($cells)->getFont()->setName('Candara');
    $newsheet->getStyle($cells)->getFont()->setSize($size);
    $newsheet->getStyle($cells)->getFont()->setBold(true);
    //$newsheet->getStyle($cells)->getFont()->setUnderline (PHPExcel_Style_Font :: UNDERLINE_SINGLE);
    //$newsheet->getStyle($cells)->getFont()->getColor ()-> setARGB (PHPExcel_Style_Color :: COLOR_WHITE); 

}




function cellColor($newsheet, $cells, $color)
{

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

if (in_array($MULTI_ACCESS_SITE_CODE, $site)) {
    $liste_site =  $cls_report->GetAll_AccessibleUSerSite($utilisateur->code_utilisateur);
} else {
    $liste_site = $site;
}


$query_installateurs_suppl = "SELECT t_utilisateurs.code_utilisateur,t_utilisateurs.nom_complet,t_log_installation_users.ref_inst_ FROM t_log_installation_users INNER JOIN t_utilisateurs ON t_log_installation_users.ref_user = t_utilisateurs.code_utilisateur where t_log_installation_users.ref_inst_=:ref_inst_";
$stmt_supp = $db->prepare($query_installateurs_suppl);
$objPHPExcel = new PHPExcel();
// aa7700
/*
Set document security Anchor: bbb
$ objPHPExcel-> getSecurity ()-> setLockWindows (true);
$ objPHPExcel-> getSecurity ()-> setLockStructure (true);
$ objPHPExcel-> getSecurity ()-> setWorkbookPassword ("PHPExcel"); // Set password 
*/

foreach ($liste_site as $site_item) {
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
    $newsheet->setCellValue('A' . $rowNumber, "Nombre total d'assignations");
    // exit;
    // $rowNumber++;
    $rowNumber++;
    $rowNumber++;
    $Total_replaced = 0;
    $Total_install = 0;
    $col = 'A';
    //ITERATION DATA IINSTALL FOR CURRENT SITE
    $rowNumber_titre = $rowNumber;
    $rowNumber_install = $rowNumber + 1;
    $rowNumber_replace = $rowNumber + 2;

    $newsheet->setCellValue($col . $rowNumber_install, 'Controlés ');
    cellBorder($newsheet, $col . $rowNumber_install);

    $newsheet->setCellValue($col . $rowNumber_replace, 'Non Controllés');
    cellBorder($newsheet, $col . $rowNumber_replace);

    $col = 'B';
    foreach ($cvs_list as $cvs_item) {
        //RECUPERATION LISTE DES COMPTEURS INSTALLES POUR LE CURRENT CVS AND GIVEN PERIOD 
        $nbre_install = $cls_report->getCVS_AssignationControllesCount($cvs_item["code"], $du, $au);
        $nbre_replace = $cls_report->getCVS_AssignationNonControllesCount($cvs_item["code"], $du, $au);

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
}

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// header('Content-Disposition: attachment;filename="survey.xls"');
header('Content-Disposition: attachment;filename="rapport_assignations.xlsx"');
header('Cache-Control: max-age=0');

/*
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=rapport_installation.xls");
header("Pragma: no-cache");
header("Expires: 0");*/
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
