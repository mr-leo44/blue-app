<?php
require_once 'utils/PHPExcel-1.8/Classes/PHPExcel.php';


class Thread_Import extends Thread
{

	private $site_id = 't_param_liste_compteurs';
	private $connection;
	private $FILES;
	private $user_context;
	private $marque_compteur;
	private $category_id;
	private $parent_id;
	public $import_mode = 0; // Compteur(0),Adresse(1)
	public function __construct($site_id, $FILES, $user_context, $marque_compteur, $connection)
	{
		$this->site_id = strip_tags($site_id);
		$this->marque_compteur = strip_tags($marque_compteur);
		$this->FILES =  $FILES;
		$this->user_context =  $user_context;
		$this->connection = $connection;
	}
	/*
    public function __construct($parent_id,$category_id,$FILES, $user_context,$connection) {
       $this->parent_id = strip_tags($parent_id);
        $this->category_id = strip_tags($category_id);
        $this->FILES =  $FILES;
        $this->user_context =  $user_context;
		$this->connection = $connection; 
    }*/

	public function run()
	{
		$this->import($this->site_id, $this->FILES, $this->user_context, $this->marque_compteur);
	}

	//VERIFICATION AUTOMATIC DOSSIER ET CREATION A AJOUTER
	function import($site_id, $FILES, $user_context, $marque_compteur)
	{
		set_time_limit(0);
		$result = array();
		/*
        $frm_id = $this->uniqUid("surveys_entity_questionnaires", "id");
        $filePath = $location . '/' . $frm_id . '/';
        if (is_dir($filePath) === false) {
            mkdir($filePath);
        }
        $filePath = $filePath . 'xlsform/';
        if (is_dir($filePath) === false) {
            mkdir($filePath);
        }
        $filePath.=$FILES['frm']['name'];


        if (move_uploaded_file($FILES['frm']['tmp_name'], $filePath)) {
            $this->result_array["error"] = 0;
            $this->result_array["message"] = "Importation effectuée avec succès";
        } else {
            $this->result_array["error"] = 1;
            $this->result_array["message"] = "Echec d'importation du fichier";
            return $this->result_array;
        }*
		
		$this->file_name = $filePath;
        $objPHPExcel = new PHPExcel();
        $input_file_type = PHPExcel_IOFactory::identify($filePath);
        $obj_reader = PHPExcel_IOFactory::createReader($input_file_type);
        $objPHPExcel = $obj_reader->load($filePath);*/
		$headers = array();
		$rows = array();

		if (isset($FILES['frm']['name']) && $FILES['frm']['name'] != "") {
			$allowedExtensions = array("xls", "xlsx");
			$ext = pathinfo($FILES['frm']['name'], PATHINFO_EXTENSION);
			if (in_array($ext, $allowedExtensions)) {
				$file_size = $FILES['frm']['size'] / 1024;
				if ($file_size < 1024) { //Ko
					$file = "uploads/" . $FILES['frm']['name'];
					$isUploaded = copy($FILES['frm']['tmp_name'], $file);
					if ($isUploaded) {
						/* include("db.php");
                    include("Classes/PHPExcel/IOFactory.php");*/
						try {
							//Load the excel(.xls/.xlsx) file
							$objPHPExcel = PHPExcel_IOFactory::load($file);
						} catch (Exception $e) {
							//  die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME). '": ' . $e->getMessage());
							$result["error"] = true;
							$result["message"] = "Echec de la lecture du fichier";
						}

						//An excel file may contains many sheets, so you have to specify which one you need to read or work with.
						$sheet = $objPHPExcel->getSheet(0);
						//It returns the highest number of rows
						$total_rows = $sheet->getHighestRow();
						//It returns the highest number of columns
						$total_columns = $sheet->getHighestColumn();

						//echo '<h4>Data from excel file</h4>';
						//echo '<table cellpadding="5" cellspacing="1" border="1" class="responsive">';
						//DEBUT TRANSACTION
						try {
							$this->connection->beginTransaction();

							$stmt_select = $this->connection->prepare('SELECT ref_produit_series,serial_number FROM t_param_liste_compteurs where serial_number=:numero_serie');

							$query = "INSERT INTO " . $this->table_name . "  SET ref_produit_series=:ref_produit_series,n_user_create=:n_user_create,serial_number=:serial_number,sts_serial_number=:sts_serial_number,order_number=:order_number,manufacturer_ref=:manufacturer_ref,site_id_affectation=:site_id_affectation";
							$stmt = $this->connection->prepare($query);

							$query_update = "UPDATE " . $this->table_name . "  SET n_user_update=:n_user_create,serial_number=:serial_number,sts_serial_number=:sts_serial_number,order_number=:order_number,manufacturer_ref=:manufacturer_ref,site_id_affectation=:site_id_affectation WHERE ref_produit_series=:ref_produit_series";
							$stmt_update = $this->connection->prepare($query_update);



							//$query = "insert into `user_details` (`id`, `name`, `mobile`, `country`) VALUES ";
							$has_ro = 0;
							//Loop through each row of the worksheet
							for ($row = 2; $row <= $total_rows; $row++) {
								$sleep = mt_rand(1, 10);
								$has_ro++;
								//[Serial Number(0)]	[STS Serial Number(1)]	[Order No(2)]	[Manufacturer(3)]
								//Read a single row of data and store it as a array.
								//This line of code selects range of the cells like A1:D1
								$single_row = $sheet->rangeToArray('A' . $row . ':' . $total_columns . $row, NULL, TRUE, FALSE);
								//echo "<tr>";
								//Creating a dynamic query based on the rows from the excel file
								//$query .= "(";
								//Print each cell of the current row
								/*foreach($single_row[0] as $key=>$value) {
										echo "<td>".$value."</td>";
										$query .= "'".mysqli_real_escape_string($con, $value)."',";
									}
									$query = substr($query, 0, -1);
									$query .= "),";
									echo "</tr>";*/


								$str = trim($single_row[0][1]); //sts_serial_number REAL COMPTEUR NUMBER
								$serial_number = preg_replace("/\s+/", "", $str);
								$stmt_select->bindValue(':numero_serie', $serial_number);
								$stmt_select->execute();
								$data_row = $stmt_select->fetch(PDO::FETCH_ASSOC);
								if (!$data_row) {
									$ref_produit_series = Utils::uniqUid("t_param_liste_compteurs", "ref_produit_series", $this->connection);
									$sts_serial_number = $single_row[0][0];
									$order_number = $single_row[0][2];
									/*$manufacturer_ref = $single_row[0][3];
										$manufacturer_ref_ID = $this->GetIDonLabel($manufacturer_ref) . "";
										if(strlen($manufacturer_ref_ID) == 0){
											$result["error"] = true;
											$result["message"] = "Manufacturer non repertorié";
											break;
										}*/
									$stmt->bindParam(":ref_produit_series", $ref_produit_series);
									$stmt->bindParam(":n_user_create", $this->n_user_create);
									$stmt->bindParam(":serial_number", $serial_number);
									$stmt->bindParam(":sts_serial_number", $sts_serial_number);
									$stmt->bindParam(":order_number", $order_number);
									$stmt->bindParam(":manufacturer_ref", $marque_compteur);
									$stmt->bindParam(":site_id_affectation", $site_id);
									//$stmt->bindParam(":annee_fabrication", $this->annee_fabrication);
									//$stmt->bindParam(":date_actuelle_affectation", $this->date_actuelle_affectation);
									$stmt->execute();
								} else {
									$sts_serial_number = $single_row[0][0];
									$order_number = $single_row[0][2];
									$stmt->bindParam(":ref_produit_series", $data_row['ref_produit_series']);
									$stmt->bindParam(":n_user_create", $this->n_user_create);
									$stmt->bindParam(":serial_number", $serial_number);
									$stmt->bindParam(":sts_serial_number", $sts_serial_number);
									$stmt->bindParam(":order_number", $order_number);
									$stmt->bindParam(":manufacturer_ref", $marque_compteur);
									$stmt->bindParam(":site_id_affectation", $site_id);
									$stmt->execute();
								}
								sleep($sleep);
							}
							$this->connection->commit();
							if ($has_ro > 0) {
								$result["error"] = false;
								$result["message"] = "Importation effectuée avec succès";
							} else {
								$result["error"] = false;
								$result["message"] = "Fichier sans donnée à importer";
							}
						} catch (\Exception $e) {
							if ($this->connection->inTransaction()) {
								$this->connection->rollback();
								$result["error"] = true;
								$result["message"] = "Echec opération";
								$result["data"] = $e->getMessage();
							}
						}
						// At last we will execute the dynamically created query an save it into the database
						//mysqli_query($con, $query);
						/* if(mysqli_affected_rows($con) > 0) {    
                        echo '<span class="msg">Database table updated!</span>';
                    } else {
                        echo '<span class="msg">Can\'t update database table! try again.</span>';
                    } */
						// Finally we will remove the file from the uploads folder (optional) 
						unlink($file);
					} else {
						// echo '<span class="msg">File not uploaded!</span>';
						$result["error"] = true;
						$result["message"] = "Fichier non téléchargé";
					}
				} else {
					// echo '<span class="msg">Maximum file size should not cross 50 KB on size!</span>';  
					$result["error"] = true;
					$result["message"] = "La taille maximale du fichier requise est 50kb";
				}
			} else {
				// echo '<span class="msg">This type of file not allowed!</span>';
				$result["error"] = true;
				$result["message"] = "Le type de fichier non prise en charge";
			}
		} else {
			//echo '<span class="msg">Select an excel file first!</span>';
			$result["error"] = true;
			$result["message"] = "Veuillez sélectionner le fichier";
		}



		return 	$result;
	}



	//VERIFICATION AUTOMATIC DOSSIER ET CREATION A AJOUTER
	function importAdresse($FILES, $user_context)
	{
		set_time_limit(0);
		$result = array();
		/*
        $frm_id = $this->uniqUid("surveys_entity_questionnaires", "id");
        $filePath = $location . '/' . $frm_id . '/';
        if (is_dir($filePath) === false) {
            mkdir($filePath);
        }
        $filePath = $filePath . 'xlsform/';
        if (is_dir($filePath) === false) {
            mkdir($filePath);
        }
        $filePath.=$FILES['frm']['name'];


        if (move_uploaded_file($FILES['frm']['tmp_name'], $filePath)) {
            $this->result_array["error"] = 0;
            $this->result_array["message"] = "Importation effectuée avec succès";
        } else {
            $this->result_array["error"] = 1;
            $this->result_array["message"] = "Echec d'importation du fichier";
            return $this->result_array;
        }*
		
		$this->file_name = $filePath;
        $objPHPExcel = new PHPExcel();
        $input_file_type = PHPExcel_IOFactory::identify($filePath);
        $obj_reader = PHPExcel_IOFactory::createReader($input_file_type);
        $objPHPExcel = $obj_reader->load($filePath);*/
		$headers = array();
		$rows = array();

		if (isset($FILES['frm']['name']) && $FILES['frm']['name'] != "") {
			$allowedExtensions = array("xls", "xlsx");
			$ext = pathinfo($FILES['frm']['name'], PATHINFO_EXTENSION);
			if (in_array($ext, $allowedExtensions)) {
				$file_size = $FILES['frm']['size'] / 1024;
				if ($file_size < 1024) { //Ko
					$file = "uploads/" . $FILES['frm']['name'];
					$isUploaded = copy($FILES['frm']['tmp_name'], $file);
					if ($isUploaded) {
						/* include("db.php");
                    include("Classes/PHPExcel/IOFactory.php");*/
						try {
							//Load the excel(.xls/.xlsx) file
							$objPHPExcel = PHPExcel_IOFactory::load($file);
						} catch (Exception $e) {
							//  die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME). '": ' . $e->getMessage());
							$result["error"] = true;
							$result["message"] = "Echec de la lecture du fichier";
						}

						//An excel file may contains many sheets, so you have to specify which one you need to read or work with.
						$sheet = $objPHPExcel->getSheet(0);
						//It returns the highest number of rows
						$total_rows = $sheet->getHighestRow();
						//It returns the highest number of columns
						$total_columns = $sheet->getHighestColumn();

						//echo '<h4>Data from excel file</h4>';
						//echo '<table cellpadding="5" cellspacing="1" border="1" class="responsive">';
						//DEBUT TRANSACTION
						try {
							$this->connection->beginTransaction();

							$stmt_select = $this->connection->prepare('SELECT code,category_id,parent_id,libelle FROM t_param_adresse_entity where libelle=:libelle AND parent_id=:parent_id');

							$query = "INSERT INTO " . $this->table_name . "  SET code=:code,n_user_create=:n_user_create,category_id=:category_id,parent_id=:parent_id,libelle=:libelle";
							$stmt = $this->connection->prepare($query);


							$query = "UPDATE " . $this->table_name . "  SET n_user_update=:n_user_update,category_id=:category_id,parent_id=:parent_id,libelle=:libelle where code=:code";
							$stmt_update = $this->connection->prepare($query);


							//A REVOIR LATER
							$query = "DELETE FROM  " . $this->table_name . "   where parent_id=:parent_id";
							$stmt_delete = $this->connection->prepare($query);
							$stmt_delete->bindValue(':parent_id', $this->parent_id);
							$stmt_delete->execute();



							$has_ro = 0;
							for ($row = 2; $row <= $total_rows; $row++) {
								$sleep = mt_rand(1, 10);
								$has_ro++;
								$single_row = $sheet->rangeToArray('A' . $row . ':' . $total_columns . $row, NULL, TRUE, FALSE);
								$str = trim($single_row[0][0]);
								//$libelle = preg_replace("/\s+/", "", $str);
								$libelle =  $str;
								if (strlen($libelle) > 0) {
									$stmt_select->bindValue(':parent_id', $this->parent_id);
									$stmt_select->bindValue(':libelle', $libelle);
									$stmt_select->execute();
									$data_row = $stmt_select->fetch(PDO::FETCH_ASSOC);
									if (!$data_row) {
										$ref_import = Utils::uniqUid("t_param_adresse_entity", "code", $this->connection);
										$stmt->bindParam(":code", $ref_import);
										$stmt->bindParam(":n_user_create", $this->n_user_create);
										$stmt->bindParam(":category_id", $this->category_id);
										$stmt->bindParam(":parent_id", $this->parent_id);
										$stmt->bindParam(":libelle", $libelle);
										$stmt->execute();
									} else {
										$stmt_update->bindParam(":code", $data_row['code']);
										$stmt_update->bindParam(":n_user_update", $this->n_user_create);
										$stmt_update->bindParam(":category_id", $this->category_id);
										$stmt_update->bindParam(":parent_id", $this->parent_id);
										$stmt_update->bindParam(":libelle", $libelle);
										$stmt_update->execute();
									}
								}
								sleep($sleep);
							}
							$this->connection->commit();
							if ($has_ro > 0) {
								$result["error"] = false;
								$result["message"] = "Importation effectuée avec succès";
							} else {
								$result["error"] = false;
								$result["message"] = "Fichier sans donnée à importer";
							}
						} catch (\Exception $e) {
							if ($this->connection->inTransaction()) {
								$this->connection->rollback();
								$result["error"] = true;
								$result["message"] = "Echec opération";
								$result["data"] = $e->getMessage();
							}
						}
						// At last we will execute the dynamically created query an save it into the database
						//mysqli_query($con, $query);
						/* if(mysqli_affected_rows($con) > 0) {    
                        echo '<span class="msg">Database table updated!</span>';
                    } else {
                        echo '<span class="msg">Can\'t update database table! try again.</span>';
                    } */
						// Finally we will remove the file from the uploads folder (optional) 
						unlink($file);
					} else {
						// echo '<span class="msg">File not uploaded!</span>';
						$result["error"] = true;
						$result["message"] = "Fichier non téléchargé";
					}
				} else {
					// echo '<span class="msg">Maximum file size should not cross 50 KB on size!</span>';  
					$result["error"] = true;
					$result["message"] = "La taille maximale du fichier requise est 1024kb";
				}
			} else {
				// echo '<span class="msg">This type of file not allowed!</span>';
				$result["error"] = true;
				$result["message"] = "Le type de fichier non prise en charge";
			}
		} else {
			//echo '<span class="msg">Select an excel file first!</span>';
			$result["error"] = true;
			$result["message"] = "Veuillez sélectionner le fichier";
		}


		//printf('%s: %s  -finish' . "\n", date("g:i:sa"), $this->arg);
		return 	$result;
	}
}
