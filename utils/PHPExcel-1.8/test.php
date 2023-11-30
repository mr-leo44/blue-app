<?php

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('Europe/London');

/** PHPExcel_IOFactory */
require_once dirname(__FILE__) . '/Classes/PHPExcel/IOFactory.php';


try {
	$fileName='essai_xform_select.xlsx';
        $fileType = PHPExcel_IOFactory::identify($fileName);
        $objReader = PHPExcel_IOFactory::createReader($fileType);
        $objPHPExcel = $objReader->load($fileName);
       /* $sheets = [];
        foreach ($objPHPExcel->getAllSheets() as $sheet) {
            $sheets[$sheet->getTitle()] = $sheet->toArray();
        }
        var_dump($sheets);*/
		
 $settings_attributes_database = array();
 						 
		$settings_attributes_database['form_title']="name";
		$settings_attributes_database['form_id']="form_id";
		$settings_attributes_database['table_id']="form_id";
		$settings_attributes_database['public_key']="public_key";
		$settings_attributes_database['submission_url']="submission_url";
		$settings_attributes_database['default_language']="default_language";
		$settings_attributes_database['version']="form_version";
		$settings_attributes_database['form_version']="form_version";
		$settings_attributes_database['instance_name']="instance_name";
		$settings_attributes_database['instance_id']="instance_id";
		$header=array();
  $settings_name = array('setting_name');
  $values_name = array('value');
 $setting_name_attr_index = -1;
 $value_attr_index = -1;$has_setting_name_attrib=false;
					$objWorksheet = $objPHPExcel->getSheetByName('settings');
					//var_dump($objWorksheet);
					$h_r = $objWorksheet->getHighestRow();
					$h_c = $objWorksheet->getHighestColumn();
					//RECUPERAION DES ENTETES ACTIVE SHEET
					$items = $objWorksheet->rangeToArray('A'.'1'.':'.$h_c.'1');					
					$len=count($items[0]); 
					$ix=0;
					for($c=0;$c<=$len;$c++){
						if($items[0][$c] != null)
						array_push($header,$items[0][$c]);
						
						//var_dump($items[0][$c]);
						if(in_array($items[0][$c], $settings_name)){				
							$setting_name_attr_index=$ix;
							$has_setting_name_attrib=true;
						}else if(in_array($items[0][$c], $values_name)){				
							$value_attr_index=$ix; 
						}
						$ix++;
					} 
					if($has_setting_name_attrib == true){
							$item_prepare = array();
							for($cc=2;$cc<=$h_r;$cc++){
							$row = $objWorksheet->rangeToArray('A'.$cc.':'.$h_c.$cc);
							if( array_key_exists($row[0][$setting_name_attr_index], $settings_attributes_database)  ){
									if($row[0][$setting_name_attr_index]!=null)
									$item_prepare[$settings_attributes_database[$row[0][$setting_name_attr_index]]]=$row[0][$value_attr_index];
								}
						}
					}
    } catch (Exception $e) {
         die($e->getMessage());
    }
	?>