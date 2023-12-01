<?php
// session_start();
$mnu_title = "Installations";
$page_title = "Liste des installations";
$home_page = "dashboard.php";
$active = "lst_install";
$parambase = "";

require_once 'loader/init.php';
Autoloader::Load('classes');
include_once 'core.php';

header('Content-type: text/html;charset=utf-8');
$database = new Database();
$db = $database->getConnection();
$Abonne = new Identification($db);
$Installation = new Installation($db);
$utilisateur = new Utilisateur($db);
$organisme = new Organisme($db);
$etatreaffectation = new EtatReaffectation($db);
$marquecompteur = new MarqueCompteur($db);
$cvs = new CVS($db);
$materiel = new Materiels($db);
$pTypeDefaut = new Param_TypeDefaut($db);

$section_cable = new PARAM_Section_Cable($db);
$commune = new AdresseEntity($db);
$accessib = new Param_Accessibility($db);
$raccordement = new Param_Raccordement($db);
$type_compteur = new Param_TypeCompteur($db);
$type_usage = new Param_TypeUsage($db);
$etat_poc = new PARAM_EtatPOC($db);
$statut_installation = new PARAM_StatutInstallation($db);
$type_client = new TypeClient($db);
$yes_no = new PARAM_YesNo($db);
$conformity_install = new PARAM_ConformityInstall($db);
$tarif = new Tarif($db);
//$statut_personne = new Param_Statut_Personne($db); 
$type_usage = new Param_TypeUsage($db);
$site = new Site($db);
$province = new AdresseEntity($db);

if ($utilisateur->is_logged_in() == false) {
	$utilisateur->redirect('login.php');
}
$utilisateur->readOne();

?>
<!doctype html>
<html lang="fr">

<head>
	<style>
		.btn-group-fab {
			position: fixed;
			width: 50px;
			height: auto;
			right: 20px;
			bottom: 50px;
		}

		.btn-group-fab div {
			position: relative;
			width: 100%;
			height: auto;
		}

		.btn-group-fab .btn {
			position: absolute;
			bottom: 0;
			border-radius: 50%;
			display: block;
			margin-bottom: 4px;
			width: 40px;
			height: 40px;
			margin: 4px auto;
		}

		.btn-group-fab .btn-main {
			width: 50px;
			height: 50px;
			right: 50%;
			margin-right: -25px;
			z-index: 9;
		}

		.btn-group-fab .btn-sub {
			bottom: 0;
			z-index: 8;
			right: 50%;
			margin-right: -20px;
			-webkit-transition: all 2s;
			transition: all 0.5s;
		}

		.btn-group-fab.active .btn-sub:nth-child(2) {
			bottom: 60px;
		}

		.btn-group-fab.active .btn-sub:nth-child(3) {
			bottom: 110px;
		}

		.btn-group-fab.active .btn-sub:nth-child(4) {
			bottom: 160px;
		}

		.btn-group-fab .btn-sub:nth-child(5) {
			bottom: 210px;
		}

		.btn-group-fab .btn-sub:nth-child(6) {
			bottom: 260px;
		}



		.pagination {
			float: right;
			margin: 0 30px 5px;
		}

		.pagination li a {
			border: none;
			font-size: 95%;
			width: 50px;
			height: 30px;
			color: #6c757d;
			line-height: 30px;
			border-radius: 30px !important;
			text-align: center;
			padding: 0;
			background-color: #fff;
			border: 2px solid #ddd;
		}

		.pagination li a:hover {
			color: #FFF;
		}

		.pagination li.disabled a {
			background-color: #000;
		}

		.pagination li.disabled i {
			color: #eee;
		}

		.pagination li i {
			font-size: 16px;
		}

		.page-item.disabled .page-link {
			color: #6c757d;
			pointer-events: none;
			cursor: auto;
			background-color: #000;
			border-color: #dee2e6;
		}


		.search {
			cursor: pointer
		}

		/*
.text-small { font-size: 0.9rem !important; }

body { background: linear-gradient(to left, rgb(86, 171, 47), rgb(168, 224, 99)); }

.cursor-pointer { cursor: pointer; }*/


		.form-control:-ms-input-placeholder {
			color: #c0c5ca;
			opacity: 1
		}

		.form-control::-ms-input-placeholder {
			color: #c0c5ca;
			opacity: 1
		}

		.form-control::placeholder {
			color: #c0c5ca;
			opacity: 1
		}

		.form-control::-webkit-input-placeholder {
			color: #c0c5ca;
			opacity: 1
		}
	</style>
	<link href="assets/css/materialdesignicons.min.css" rel="stylesheet">
	<link href="assets/css/select2.css" rel="stylesheet">

	<link href="assets/css/parsley.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/leaflet.css" />

	<?php
	include_once "layout_style.php";
	?>

</head>

<body>

	<!-- Loader 
  <div class="loader loader-default is-active"></div> -->
	<div id="loader" class="loader loader-default"></div>
	<!-- ============================================================== -->
	<!-- main wrapper -->
	<!-- ============================================================== -->
	<div class="dashboard-main-wrapper">
		<!-- ============================================================== -->
		<!-- navbar -->
		<!-- ============================================================== -->
		<?php
		include_once "layout_top_bar.php";
		include_once "layout_side_bar.php";
		?>
		<!-- ============================================================== -->
		<!-- wrapper  -->
		<!-- ============================================================== -->
		<div class="dashboard-wrapper">

			<div class="container-fluid  dashboard-content">
				<!-- ============================================================== -->
				<!-- pageheader -->
				<!-- ============================================================== -->
				<div class="row">
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="page-header">
							<!--  <h2 class="pageheader-title"><?php echo $mnu_title; ?></h2>  -->
							<div id="breadcrumbs" class="clearfix">
								<a href="<?php echo 'lst_install.php'; ?>" class="breadcrumbs_home"><i class='fas fa-handshake nav_icon'></i> <?php echo $mnu_title; ?></a> <span class="raquo">»</span>
							</div>
						</div>
					</div>
				</div>




				<!-- ============================================================== -->
				<!-- start data cardview -->
				<!-- ============================================================== -->
				<div class="row">
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="card mb-1">

							<div class="card-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="h5 font-weight-bold text-primary"> Journal des installations</div>
										<div id="record_count" class="font-semi-bold" style="color:#5e6e82;font-size:16px">
											- </div>
									</div>

									<div class="col">
										<div class="row mb-2">
											<div class="col-sm-12 text-right pr-1">
												<div class="text-right">







													<div class="btn-group">
														<div class="input-group-prepend">
															<span class="input-group-text">Eléments par page</span>
														</div>
														<select class="custom-select ml-auto w-auto" id="show" onChange="changeDisplayRowCount(this.value);">
															<option value="10" selected="">10</option>
															<option value="20">20</option>
															<option value="30">30</option>
															<option value="50">50</option>
															<option value="100">100</option>
														</select>
													</div>


												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card mb-1">

							<div class="card-body">
								<div class="row">
									<div class="col-sm-3">
										<div class="input-group mt-2">
											<input type="text" id="srch-term" name='s' class="form-control" placeholder="Recherche ..." required>

										</div>
									</div>
									<div class="col-sm-3">
										<div class="form-group text-left mb-0 mt-1">
											<select class='form-control select2' style='width: 100%;' id='filtre' name='filtre[]' required multiple="multiple">
												<option value="t_log_installation.statut_installation='1'">Terminée</option>
												<option value="t_log_installation.compteur_desaffecte='1'">Désaffecté</option>
												<option value="t_log_installation.statut_installation='0'">En cours</option>
												<option value="t_log_installation.type_installation='0'">Installation</option>
												<option value="t_log_installation.type_installation='1'">Remplacement</option>
												<option value="t_log_installation.type_installation='7'">Pilote</option>
												<?php

												$stmt_tarif = $type_compteur->read();
												while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
													echo "<option value=t_log_installation.type_new_cpteur='" . $row_gp["code"] . "'>Type compteur - " . $row_gp["libelle"] . "</option>";
												}
												echo "<option value=t_log_installation.type_new_cpteur=''>Type compteur - Non défini</option>";
												if ($utilisateur->id_service_group ==  '3') {  //Administration
													$stmt_chief = $utilisateur->GetAll_OrganeUserListForAdmin();
													while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
														echo "<option value=t_log_installation.installateur='" . $row_chief["code_utilisateur"] . "'>Installateur - " . $row_chief["nom_complet"] . "</option>";
													}
												} else {
													$stmt_chief = $utilisateur->GetCurrentUserListIdentificateurs($utilisateur->code_utilisateur, $utilisateur->id_organisme, $utilisateur->is_chief);

													while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
														echo "<option value=t_log_installation.installateur='" . $row_chief["code_utilisateur"] . "'>Installateur - " . $row_chief["nom_complet"] . "</option>";
													}
												}


												$stmt_chief = null;
												if ($utilisateur->id_service_group ==  '3') {  //Administration
													$stmt_chief = $utilisateur->GetAllChiefForAdmin();
													while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
														echo "<option value=t_log_installation.chef_equipe='{$row_chief["code_utilisateur"]}'>Chef équipe - {$row_chief["nom_complet"]}</option>";
													}
												} else {
													$stmt_chief = $utilisateur->GetCurrentUserChief($utilisateur);
													while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
														echo "<option value=t_log_installation.chef_equipe='{$row_chief["code_utilisateur"]}'>Chef équipe - {$row_chief["nom_complet"]}</option>";
													}
												}

												$stmt_select = $province->getAllProvinces();
												$provinces = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

												foreach ($provinces as $row_select) {
													// echo "<option value=t_param_adresse_entity.code='" . $row_select["code"] . "'>Province - " . $row_select["libelle"] . "</option>";
												}

												foreach ($provinces as $province) {
													$stmt_select = $commune->GetProvinceAllCommune($province['code']);
													while ($row_select = $stmt_select->fetch(PDO::FETCH_ASSOC)) {
														echo "<option value=e_commune.code='" . $row_select["code"] . "'>Commune - " . $row_select["libelle"] . "</option>";
													}


													$stmt_select = $commune->GetProvinceAllCVS($province['code']);
													while ($row_select = $stmt_select->fetch(PDO::FETCH_ASSOC)) {
														echo "<option value=t_param_cvs.code='" . $row_select["code"] . "'>CVS - " . $row_select["libelle"] . "</option>";
													}
												}

												$stmt_select = $site->GetAll();
												while ($row_select = $stmt_select->fetch(PDO::FETCH_ASSOC)) {
													echo "<option value=t_log_installation.ref_site_install='" . $row_select["code"] . "'>Site - " . $row_select["libelle"] . "</option>";
												}

												if ($utilisateur->id_service_group ==  '3' || $utilisateur->HasGlobalAccess()) {  //Administration
													$stmt_ = $organisme->read();
													while ($row_gp = $stmt_->fetch(PDO::FETCH_ASSOC)) {
														echo "<option value=id_equipe='{$row_gp["ref_organisme"]}'>Organisme - {$row_gp["denomination"]}</option>";
													}
												} else {
													$organisme->ref_organisme = $utilisateur->id_organisme;
													$row_gp = $organisme->GetDetail();
													echo "<option value=id_equipe='{$row_gp["ref_organisme"]}'>Organisme - {$row_gp["denomination"]}</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="input-group  mt-2">
											<input type="text" class="form-control datetimepicker-input" name="Du" id="Du" placeholder="Du" required />
											<div class="input-group-append">
												<div class="input-group-text" id="add_on_du"><i class="far fa-calendar-alt"></i></div>
											</div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="input-group  mt-2">
											<input type="text" class="form-control datetimepicker-input" name="Au" id="Au" required="required" placeholder="Au" />
											<div class="input-group-append">
												<div class="input-group-text" id="add_on_au"><i class="far fa-calendar-alt"></i></div>
											</div>
										</div>
									</div>

									<div class="col">
										<div class="input-group mt-1">
											<button name="search" id="search-btn" class="btn btn-primary"><i class="fa fa-search"></i>
											</button>
											<a class="btn btn-outline-light float-right ml-1 view-all" href="#">Voir tout</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div id="results"></div>


					</div>



				</div>

				<!-- ============================================================== -->
				<!-- end data cardview -->
				<!-- ============================================================== -->

			</div>
		</div>
	</div>
	<?php
	if ($utilisateur->HasDroits("10_60")) {

		echo  '<div class="btn-group-fab" role="group" aria-label="FAB Menu">
	  <div>
		<button type="button" class="btn btn-main btn-primary has-tooltip" data-placement="left" title="Menu" id="btn_new_"> <i class="fa fa-plus"></i> </button>
	  </div>
	</div>';
	}
	include_once "layout_script.php";
	?>
	<div class="modal" id="dlg_main-install" tabindex="-1" role="dialog" aria-labelledby="edit-install" aria-hidden="true" data-backdrop="static" style="overflow:scroll;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="mainForm_install" method="post" action="controller.php" enctype="multipart/form-data">
					<div class="modal-header">
						<h5 class="modal-title" id="Heading-install"></h5>
						<a href="#" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</a>
					</div>
					<div class="modal-body">
						<input name="id_install" id="id_install" type="hidden">
						<input name="view" id="view" type="hidden">
						<input name="id_assign" id="id_assign" type="hidden">
						<!-- ============================================================== -->
						<!-- Information CLIENT form -->
						<!-- ============================================================== -->
						<!--   <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                             <div class="form-group">
									<label>DATE INSTALLATION</label>
									<div class="input-group"  style="width: 100%;" > 
									  <input type="text" class="form-control pull-right" name="date_fin_installation" id="date_fin_installation" readonly>
									</div>                
							</div> 		
							</div> 		
							
						</div>  -->

						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="card">
								<h5 class="card-header">INFORMATIONS DU CLIENT</h5>
								<div class="row">

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<input type="hidden" class="form-control pull-right" name="ref_identific" id="ref_identific" readOnly>

												<div class="form-group">
													<label>NOM DU PROPRIETAIRE (Identité sur la facture SNEL)</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="nom_responsable_inst2" id="nom_responsable_inst2" disabled>
													</div>
												</div>
												<div class="form-group">
													<label>NOM DU LOCATAIRE (ou Ménage à connecté)</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="nomclientblue_" id="nomclientblue_" disabled>
													</div>
												</div>

												<div class="form-group">
													<label>TARIF INSTALLATION</label>
													<select class='form-control select2' style='width: 100%;' name='code_tarif' id='code_tarif' required>
														<option selected='selected' disabled>Veuillez préciser le tarif</option>
														<?php
														$stmt_tarif = $tarif->read();
														while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
															echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
														}
														?>
													</select>
												</div>
											</div>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>DATE IDENTIFICATION</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="date_identification_inst2" id="date_identification_inst2" disabled>
													</div>
												</div>
												<div class="form-group">
													<label>ADRESSE</label>
													<div class="input-group" style="width: 100%;">
														<textarea class="form-control pull-right" name="adresse_inst2" id="adresse_inst2" disabled></textarea>
													</div>
												</div>


												<div class="form-group">
													<label>ACCESSIBILITE CLIENT</label>
													<select class='form-control select2' style='width: 100%;' name='accessibility_client' id='accessibility_client' required>
														<option selected='selected' disabled>Veuillez préciser</option>
														<?php
														$stmt_tarif = $accessib->read();
														while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
															echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
														}
														?>
													</select>
												</div>
												<button type="button" class="btn btn-warning  float-right" id="btn_Signaler_Exoneration" style="display:none;">&nbsp;Signaler Exonération</button>
												<button type="button" class="btn btn-danger  float-right" id="btn_Signaler_Refus" style="display:none;">&nbsp;Signaler refus</button>


												<div class="form-group">
													<a class="btn btn-outline-light float-right" id="btn_map_viewer" data-toggle="modal" data-target="#myModalLeaflet"><i class="fas fa-map"></i> Visualiser Carte</a>
													<!-- <a class="btn btn-outline-light float-right" id="btn_map_viewer" data-toggle="modal" data-target="#myModalLeaflet" data-lat='-4.34176' data-lng='15.299379199999999'><i class="fas fa-map"></i> Visualiser Carte</a>	-->
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="row">

									<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>CVS</label>
													<div class="input-group" style="width: 100%;">

														<select class='form-control' style='width: 100%;' id='cvs_id_inst2' readonly="true" disabled>
															<option selected='selected' disabled> </option>
															<?php
															$stmt_select_mat = $cvs->read();
															while ($row_gp = $stmt_select_mat->fetch(PDO::FETCH_ASSOC)) {
																echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
															}
															?>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>P.A</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="p_a_inst2" id="p_a_inst2" disabled>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>TARIF</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="tarif_identif2" id="tarif_identif2" disabled>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>

							</div>
						</div>
						<!-- ============================================================== -->
						<!-- end Information CLIENT form -->
						<!-- ============================================================== -->
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

							<div class="card">
								<div class="card-header d-flex">
									<h4 class="mb-0">PHOTO PA</h4>
								</div>
								<div class="card-body">
									<div class="row" id="photo_pa_list">
									</div>

								</div>
							</div>
						</div>
						<!-- ============================================================== -->
						<!-- Information RACORDEMENT form -->
						<!-- ============================================================== -->
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="card">
								<h5 class="card-header">INFORMATIONS SUR LE RACCORDEMENT </h5>
								<div class="row">

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>CABINE</label>
													<div class="input-group" style="width: 100%;">
														<input type="number" class="form-control pull-right allow-numeric" name="cabine" id="cabine">
													</div>
												</div>
												<div class="form-group">
													<label>N° DEPART</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="num_depart" id="num_depart">
													</div>
												</div>
												<div class="form-group">
													<label>N° POTEAU</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="num_poteau" id="num_poteau">
													</div>
												</div>
												<div class="form-group">
													<label>TYPE RACCORDEMENT</label>
													<div class="input-group" style="width: 100%;">
														<select class='form-control select2' style='width: 100%;' name='type_raccordement' id='type_raccordement' required>
															<option selected='selected' disabled>Veuillez préciser</option>
															<?php
															$stmt_tarif = $raccordement->read();
															while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
																echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
															}
															?>
														</select>

													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<!--	<div class="form-group">
                                    <label>TYPE COMPTEUR</label>			
                                    <select class='form-control select2' style='width: 100%;' name='type_cpteur_raccord'  id='type_cpteur_raccord'  required>
                                        <option selected='selected' disabled>Veuillez préciser</option>
                                        <?php
										/* $stmt_tarif = $type_compteur->read();
                                        while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
                                        }*/
										?></select>
                                </div> -->
												<div class="form-group">
													<label>NOMBRE D'ALIMENTATION</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="nbre_alimentation" id="nbre_alimentation" required>
													</div>
												</div>

												<div class="form-group">
													<label>SECTION CABLE D'ALIMENTATION</label>
													<div class="input-group" style="width: 100%;">

														<select class='form-control select2' style='width: 100%;' name='section_cable_alimentation' id='section_cable_alimentation' required>
															<option selected='selected' disabled>Veuillez préciser</option>
															<?php
															$stmt_tarif = $section_cable->read();
															while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
																echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
															}
															?>
														</select>
													</div>
												</div>

												<div id="div_section_cable_alimentation_deux" class="form-group">
													<label>SECTION CABLE D'ALIMENTATION 2</label>
													<div class="input-group" style="width: 100%;">

														<select class='form-control select2' style='width: 100%;' name='section_cable_alimentation_deux' id='section_cable_alimentation_deux' required>
															<option selected='selected' disabled>Veuillez préciser</option>
															<?php
															$stmt_tarif = $section_cable->read();
															while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
																echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
															}
															?>
														</select>
													</div>
												</div>

												<div class="form-group">
													<label>SECTION CABLE DE SORTIE</label>
													<div class="input-group" style="width: 100%;">
														<select class='form-control select2' style='width: 100%;' name='section_cable_sortie' id='section_cable_sortie' required>
															<option selected='selected' disabled>Veuillez préciser</option>
															<?php
															$stmt_tarif = $section_cable->read();
															while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
																echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
															}
															?>
														</select>
													</div>
												</div>

												<div class="form-group">
													<label>PRESENCE INVERSEUR</label>
													<select class='form-control select2' style='width: 100%;' name='presence_inverseur' id='presence_inverseur'>
														<option selected='selected' disabled>Veuillez préciser</option>
														<?php
														$stmt_select_st = $yes_no->read();
														while ($row_gp = $stmt_select_st->fetch(PDO::FETCH_ASSOC)) {
															echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
														}
														?>
													</select>
												</div>

											</div>
										</div>
									</div>






								</div>
							</div>
						</div>
						<!-- ============================================================== -->
						<!-- end Information RACORDEMENT form -->
						<!-- ============================================================== -->

						<!-- ============================================================== -->
						<!-- Information POST PAIE form -->
						<!-- ============================================================== -->
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" id="block_post_paie">
							<div class="card">
								<h5 class="card-header">INFORMATIONS COMPTEUR POST-PAIE</h5>
								<div class="row">

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>COMPTEUR POST-PAIE EXISTE</label>
													<div class="input-group" style="width: 100%;">

														<select class='form-control select2' style='width: 100%;' name='post_paie_trouver' id='post_paie_trouver' required>
															<option selected='selected' disabled>Veuillez préciser</option>
															<?php
															$stmt_select_st = $yes_no->read();
															while ($row_gp = $stmt_select_st->fetch(PDO::FETCH_ASSOC)) {
																echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
															}
															?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label>MARQUE COMPTEUR POST-PAIE</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="marque_cpteur_post_paie" id="marque_cpteur_post_paie">
													</div>
												</div>
												<div class="form-group">
													<label>NUMERO DE SERIE</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="num_serie_cpteur_post_paie" id="num_serie_cpteur_post_paie">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">

												<div class="form-group">
													<label>INDEX OU CREDIT RESTANT</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="index_credit_restant_cpteur_post_paie" id="index_credit_restant_cpteur_post_paie">
													</div>
												</div>
												<div class="form-group">
													<label>PHOTO COMPTEUR POST-PAIE</label>
													<?php if (Utils::IsWebView($_SERVER)) {  ?>
														<div class="image-upload">
															<label for="file-input_post_paie">
																<img id="previewImg_post_paie" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label>
															<input id="file-input_post_paie" type="file" onchange="previewFile(this,'photo_compteur_post_paie');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>
													<?php } else { ?>
														<a class="btn btn-outline-light float-right" id="btn_capture_post_paie">Capturer photo</a>

														<div class="image-upload">
															<label for="file-input_post_paie">
																<img id="previewImg_post_paie" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label>
															<input id="file-input_post_paie" type="file" onchange="previewFile(this,'photo_compteur_post_paie');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>
													<?php } ?>
													<div class="input-group" style="width: 100%;">
														<img style="height:300px;" class="form-control pull-right" id="photo_compteur_post_paie" />
													</div>
												</div>

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- ============================================================== -->
						<!-- end Information POST PAIE form -->
						<!-- ============================================================== -->


						<!-- ============================================================== -->
						<!-- Information compteur defectueux form -->
						<!-- ============================================================== -->
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" id="block_remplacement">
							<div class="card">
								<h5 class="card-header">INFORMATIONS DU COMPTEUR DEFECTUEUX</h5>
								<div class="row">

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>MARQUE</label>
													<div class="input-group" style="width: 100%;">
														<select class='form-control select2' style='width: 100%;' name='marque_cpteur_replaced' id='marque_cpteur_replaced'>
															<option selected='selected' disabled> </option>
															<?php
															$stmt_tarif = $marquecompteur->read();
															while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
																echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
															}
															?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label>NUMERO DE SERIE</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="num_serie_cpteur_replaced" id="num_serie_cpteur_replaced">
													</div>
												</div>
												<div class="form-group" id="bloc_photo_cpteur_defectueux">
													<label>PHOTO COMPTEUR DEFECTUEUX</label>
													<?php if (Utils::IsWebView($_SERVER)) { ?>

														<div class="image-upload">
															<label for="file-input_defectueux">
																<img id="previewImg_defectueux" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label>
															<input id="file-input_defectueux" type="file" onchange="previewFile(this,'photo_compteur_defectueux');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>
													<?php } else { ?>
														<a class="btn btn-outline-light float-right" id="btn_capture_defectueux">Capturer photo</a>
														<div class="image-upload">
															<label for="file-input_defectueux">
																<img id="previewImg_defectueux" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label>
															<input id="file-input_defectueux" type="file" onchange="previewFile(this,'photo_compteur_defectueux');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>
													<?php } ?>
													<div class="input-group" style="width: 100%;">
														<img style="height:300px;" class="form-control pull-right" id="photo_compteur_defectueux" />
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">

												<div class="form-group">
													<label>INDEX OU CREDIT RESTANT</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="index_credit_restant_cpteur_replaced" id="index_credit_restant_cpteur_replaced">
													</div>
												</div>
												<div class="form-group">
													<label>TYPE DEFAUT</label>
													<div class="input-group" style="width: 100%;">
														<select class='form-control select2' style='width: 100%;' name='type_defaut' id='type_defaut'>
															<option selected='selected' disabled> </option>
															<?php
															$stmt_tarif = $pTypeDefaut->read();
															while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
																echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
															}
															?>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- ============================================================== -->
						<!-- end Information DEFECTUEUX-->
						<!-- ============================================================== -->
						<!-- ============================================================== -->
						<!-- Information NOUVEAU COMPTEUR form -->
						<!-- ============================================================== -->
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="card">
								<h5 class="card-header">NOUVEAU COMPTEUR</h5>
								<div class="row">

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>MARQUE NOUVEAU COMPTEUR</label>
													<div class="input-group" style="width: 100%;">
														<select class='form-control select2 allow-numeric' style='width: 100%;' name='marque_compteur' id='marque_compteur' required disabled>
															<option selected='selected' disabled> </option>
															<?php
															$stmt_tarif = $marquecompteur->read();
															while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
																echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
															}
															?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label>NUMERO DE SERIE</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="numero_compteur" id="numero_compteur" required disabled>
														<div class="input-group-text" id="add_on_du"><a id="verify_compteur" href="#" class="icon"><i class="fas fa-search"></i></a>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label>INDEX PAR DEFAUT</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="index_par_defaut" id="index_par_defaut" required>

													</div>
												</div>
												<div class="form-group">
													<label>TYPE COMPTEUR</label>
													<select class='form-control select2' style='width: 100%;' name='type_new_cpteur' id='type_new_cpteur' required>
														<option selected='selected' disabled>Veuillez préciser</option>
														<?php
														$stmt_tarif = $type_compteur->read();
														while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
															echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
														}
														?>
													</select>
												</div>
												<!--			<div class="form-group">
                                    <label>DISJONCTEUR</label>
                                    <div class="input-group"  style="width: 100%;" > 
                                        <input type="text" class="form-control pull-right allow-numeric" name="disjoncteur" id="disjoncteur" maxlength="3">
                                    </div>                
                                </div> -->

												<div class="form-group">
													<label class="custom-control custom-checkbox">
														<input type="checkbox" class="custom-control-input" id='replace_client_disjonct' name='replace_client_disjonct' /><span class="custom-control-label">Disjoncteur Remplacé par celui du client?</span>
													</label>
												</div>

												<div class="form-group">
													<label>AMPERAGE DISJONCTEUR</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="client_disjonct_amperage" id="client_disjonct_amperage" maxlength="3">
													</div>
												</div>

												<div class="form-group">
													<label>AUTOCOLLANT POSE</label>
													<select class='form-control select2' style='width: 100%;' name='is_autocollant_posed' id='is_autocollant_posed'>
														<option selected='selected' disabled>Veuillez préciser</option>
														<?php
														$stmt_select_st = $yes_no->read();
														while ($row_gp = $stmt_select_st->fetch(PDO::FETCH_ASSOC)) {
															echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
														}
														?>
													</select>
												</div>


											</div>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" id="bloc_photo_nouveau_cpteur">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>PHOTO NOUVEAU COMPTEUR</label>
													<?php if (Utils::IsWebView($_SERVER)) { ?>

														<div class="image-upload">
															<label for="file-input_install">
																<img id="previewImg_install" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label><input id="file-input_install" type="file" onchange="previewFile(this,'photo_compteur');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>
													<?php } else { ?>
														<a class="btn btn-outline-light float-right" id="btn_capture_install">Capturer photo</a>
														<div class="image-upload">
															<label for="file-input_install">
																<img id="previewImg_install" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label><input id="file-input_install" type="file" onchange="previewFile(this,'photo_compteur');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>
													<?php } ?>
													<div class="input-group" style="width: 100%;">
														<img style="height:300px;" class="form-control pull-right" id="photo_compteur" />
													</div>
												</div>

												<div class="form-group">
													<label>ETAT REAFFECTATION</label>
													<select class='form-control select2' style='width: 100%;' name='etat_compteur_reaffected' id='etat_compteur_reaffected' required>
														<option selected='selected' disabled>Veuillez préciser</option>
														<?php
														$stmt_select_st = $etatreaffectation->read();
														while ($row_gp = $stmt_select_st->fetch(PDO::FETCH_ASSOC)) {
															echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
														}
														?>
													</select>
												</div>
											</div>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" id="bloc_photo_avant_install">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>PHOTO AVANT INSTALLATION</label>
													<?php if (Utils::IsWebView($_SERVER)) { ?>
														<div class="image-upload">
															<label for="file-input_avant_install">
																<img id="previewImg_avant_install" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label>
															<input id="file-input_avant_install" type="file" onchange="previewFile(this,'photo_avant_install');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>

													<?php } else { ?>
														<a class="btn btn-outline-light float-right" id="btn_capture_avant_install">Capturer photo</a>
														<div class="image-upload">
															<label for="file-input_avant_install">
																<img id="previewImg_avant_install" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label>
															<input id="file-input_avant_install" type="file" onchange="previewFile(this,'photo_avant_install');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>
													<?php } ?>
													<div class="input-group" style="width: 100%;">
														<img style="height:300px;" class="form-control pull-right" id="photo_avant_install" />
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" id="bloc_photo_apres_install">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>PHOTO APRES INSTALLATION</label>
													<?php if (Utils::IsWebView($_SERVER)) {  ?>
														<div class="image-upload">
															<label for="file-input_apres_install">
																<img id="previewImg_apres_install" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label><input id="file-input_apres_install" type="file" onchange="previewFile(this,'photo_apres_install');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>


													<?php } else { ?>
														<a class="btn btn-outline-light float-right" id="btn_capture_apres_install">Capturer photo</a>
														<div class="image-upload">
															<label for="file-input_apres_install">
																<img id="previewImg_apres_install" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label><input id="file-input_apres_install" type="file" onchange="previewFile(this,'photo_apres_install');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>
													<?php } ?>
													<div class="input-group" style="width: 100%;">
														<img style="height:300px;" class="form-control pull-right" id="photo_apres_install" />
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" id="bloc_photo_sceller_un">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>PHOTO SCELLE 1</label>
													<?php if (Utils::IsWebView($_SERVER)) {  ?>
														<div class="image-upload">
															<label for="file-input_sceller_un">
																<img id="previewImg_sceller_un" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label>
															<input id="file-input_sceller_un" type="file" onchange="previewFile(this,'photo_sceller_un');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>
													<?php } else { ?>
														<a class="btn btn-outline-light float-right" id="btn_capture_sceller_un">Capturer photo</a>
														<div class="image-upload">
															<label for="file-input_sceller_un">
																<img id="previewImg_sceller_un" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label>
															<input id="file-input_sceller_un" type="file" onchange="previewFile(this,'photo_sceller_un');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>
													<?php } ?>
													<div class="input-group" style="width: 100%;">
														<img style="height:300px;" class="form-control pull-right" id="photo_sceller_un" />
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" id="bloc_photo_scelle_deux">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>PHOTO SCELLE 2</label>
													<?php if (Utils::IsWebView($_SERVER)) { ?>
														<div class="image-upload">
															<label for="file-input_sceller_deux">
																<img id="previewImg_sceller_deux" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label>
															<input id="file-input_sceller_deux" type="file" onchange="previewFile(this,'photo_sceller_deux');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>

													<?php } else { ?>
														<a class="btn btn-outline-light float-right" id="btn_capture_sceller_deux">Capturer photo</a>
														<div class="image-upload">
															<label for="file-input_sceller_deux">
																<img id="previewImg_sceller_deux" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label>
															<input id="file-input_sceller_deux" type="file" onchange="previewFile(this,'photo_sceller_deux');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>
													<?php } ?>
													<div class="input-group" style="width: 100%;">
														<img style="height:300px;" class="form-control pull-right" id="photo_sceller_deux" />
													</div>
												</div>
												<?php
												/*				<button type="button" class="btn btn-success  float-right" id="btn_demander_ticket"><span class="glyphicon glyphicon-ok-sign"></span>&nbsp;Demander Ticket</button>
										<!-- 	<div class="form-group"> 
									<label>STATUT INSTALLATION</label>
									<div class="input-group"  style="width: 100%;" > 
										<select class='form-control select2' style='width: 100%;' name='statut_installation'  id='statut_installation'  required >
															<option selected='selected' disabled>Veuillez préciser</option> $stmt_tarif = $statut_installation->read();
                                        while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
                                        }</select>
									</div>                
								</div> -->*/
												?>
											</div>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>SCELLE COMPTEUR</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="scelle_un_cpteur" id="scelle_un_cpteur">
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>SCELLE COFFRET</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="scelle_deux_coffret" id="scelle_deux_coffret">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<div class="table-responsive table-bordered table-hover" style="height:250px;">
														<table class="table no-wrap p-table lignes_install ui-sortable" id="lignes_install">
															<thead>
																<tr>
																	<th style="width:5%">N°</th>
																	<th style="width:90%">Matériel</th>
																	<th>Qté</th>
																	<th><a class="btn btn-xs delete-install-record" id="add_line_install"><i class="fas fa-plus"></i></a></th>
																</tr>
															</thead>
															<tbody>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- ============================================================== -->
						<!-- end Information NOUVEAU COMPTEUR form -->
						<!-- ============================================================== -->
						<!-- ============================================================== -->
						<!-- Information COMMMNETAIRE INSTALLATEUR form -->
						<!-- ============================================================== -->
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="card">
								<h5 class="card-header">COMMENTAIRE DE L'INSTALLATEUR</h5>
								<div class="row">

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>TYPE DE CLIENT</label>
													<select class='form-control select2' style='width: 100%;' name='usage_electricity' id='usage_electricity' required>
														<option selected='selected' disabled>Veuillez préciser</option>
														<?php
														$stmt_tarif = $type_usage->read();
														while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
															echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
														}
														?>
													</select>
												</div>
											</div>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">

												<div class="form-group">
													<label>ETAT POC</label>
													<select class='form-control select2' style='width: 100%;' name='etat_poc' id='etat_poc' required>
														<option selected='selected' disabled>Veuillez préciser</option>
														<?php
														$stmt_tarif = $etat_poc->read();
														while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
															echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
														}
														?>
													</select>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">

												<div class="form-group">
													<label>SOCIETE EN CHARGE DE L'INSTALLATION</label>
													<select class='form-control select2' style='width: 100%;' name='id_equipe' id='id_equipe' required>
														<option selected='selected' disabled>Veuillez préciser</option>
														<?php
														/*$stmt_tarif = $organisme->read();
															while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
																echo "<option value='{$row_gp["ref_organisme"]}'>{$row_gp["denomination"]}</option>";
															}*/
														$stmt_ = null;
														if ($utilisateur->id_service_group ==  '3') {  //Administration
															$stmt_ = $organisme->read();
															while ($row_gp = $stmt_->fetch(PDO::FETCH_ASSOC)) {
																echo "<option value='{$row_gp["ref_organisme"]}'>{$row_gp["denomination"]}</option>";
															}
														} else {
															$organisme->ref_organisme = $utilisateur->id_organisme;
															$row_gp = $organisme->GetDetail();
															echo "<option value='{$row_gp["ref_organisme"]}'>{$row_gp["denomination"]}</option>";
														}

														?>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- ============================================================== -->
						<!-- end Information COMMMNETAIRE INSTALLATEUR form -->
						<!-- ============================================================== -->

						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="form-group">
								<label>COMMETAIRE INSTALLATEUR</label>
								<div class="input-group" style="width: 100%;">
									<textarea class="form-control pull-right" name="commentaire_installateur" id="commentaire_installateur" required></textarea>
								</div>
							</div>
						</div>
						<!--      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                             <div class="form-group">
                                    <label>COMMMNETAIRE CONTROLEUR BE</label>
                                    <div class="input-group"  style="width: 100%;" > 
                                        <textarea class="form-control pull-right" name="commenteur_controle_blue" id="commenteur_controle_blue" ></textarea>
                                    </div>                
                                </div>
                        </div> -->


						<div class="row">
							<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
								<div class="card">
									<div class="card-body">
										<div class="form-group">
											<label>CHEF D'EQUIPE INSTALLATION*</label>
											<div class="input-group" style="width: 100%;">
												<input type="text" class="form-control pull-right" name="chef_equipe_view" id="chef_equipe_view" readOnly style="display:none" />
												<!-- <select class='form-control select2' style='width: 100%;' name='chef_equipe_edit'  id='chef_equipe_edit' ></select> -->

												<select class='form-control select2' style='width: 100%;' name='chef_equipe_install' id='chef_equipe_install' required>
													<option selected='selected' disabled> </option>
													<?php
													/* $stmt_chief = $utilisateur->GetCurrentUserChief($utilisateur->code_utilisateur,$utilisateur->id_organisme,$utilisateur->chef_equipe_id);
										
                                        while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
                                        }*/
													$stmt_chief = null;
													if ($utilisateur->id_service_group ==  '3') {  //Administration
														$stmt_chief = $utilisateur->GetAllChiefForAdmin();
														while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
															echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
														}
													} else {
														$stmt_chief = $utilisateur->GetCurrentUserChief($utilisateur);
														while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
															echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
														}
													}



													?>
												</select>

											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
								<div class="card">
									<div class="card-body">
										<div class="form-group">
											<label>INSTALLATEUR *</label>
											<div class="input-group" style="width: 100%;">
												<input type="text" class="form-control pull-right" name="identificateur_view" id="identificateur_view" readOnly style="display:none">
												<select class='form-control select2' style='width: 100%;' name='installateur' id='installateur' required>
													<option selected='selected' disabled>Veuillez préciser</option>
													<?php
													/*$stmt_chief = $utilisateur->GetCurrentUserListIdentificateurs($utilisateur->code_utilisateur,$utilisateur->id_organisme,$utilisateur->is_chief);
                                        while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
                                        }*/



													$stmt_chief = null;
													if ($utilisateur->id_service_group ==  '3') {  //Administration
														$stmt_chief = $utilisateur->GetAll_OrganeUserListForAdmin();
														while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
															echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
														}
													} else {
														$stmt_chief = $utilisateur->GetCurrentUserListIdentificateurs($utilisateur->code_utilisateur, $utilisateur->id_organisme, $utilisateur->is_chief);

														while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
															echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
														}
													}
													?>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<?php //if($utilisateur->id_service_group ==  '3'){  //Administration										
						//  <div class="modal-footer "><button type="button" class="btn btn-primary btn-lg" id="btn_save_install" ><span class="glyphicon glyphicon-ok-sign sn"> Valider</span></button></div>
						//}  
						?>


						<div class="modal-footer ">
							<div class="row">
								<div class="col-md-6">
									<label>Mode sauvegarde</label>
									<div class="input-group" style="width: 100%;">
										<select class="form-control select2" name="doc_save_mode" id="doc_save_mode" required>
										</select>
									</div>
								</div>

								<div class="col-md-6">
									<button type="button" class="form-control btn btn-primary btn-lg" id="btn_save_install"><span class="glyphicon glyphicon-ok-sign"></span> Appliquer</button>
								</div>
							</div>
						</div>



					</div>
				</form>
			</div>
		</div>
	</div>





	<div class="modal" id="ligne_form_install" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 5000;" data-backdrop="static">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 id="item_titre_install" class="modal-title"></h4>
					<a href="#" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</a>
				</div>
				<div class="modal-body mx-3">
					<div class="form-group">
						<input type="hidden" id="item-id" name="item-id">
						<input type="hidden" id="item-type-install" name="item-type-install">
						<label>Matériel</label>
						<select class='form-control select2' style='width: 100%;' id='item_label_install'>
							<option selected='selected' disabled>Veuillez choisir le matériel</option>
							<?php
							$stmt_select_mat = $materiel->read();
							while ($row_gp = $stmt_select_mat->fetch(PDO::FETCH_ASSOC)) {
								echo "<option value='{$row_gp["ref_produit"]}'>{$row_gp["designation"]}</option>";
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label>Quantité</label>
						<input id="item-qte-install" type="text" class="form-control border-input allow-numeric" placeholder="" style='width: 50%;'>
					</div>
					<button id="btn_add_line_install" type="button" class="btn btn-primary">Valider</button>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>
	</div>



	<div class="modal" id="camera_shooter_install" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content" style="width: 355px;">
				<div class="modal-header">
					<h4 id="item_titre_install" class="modal-title">CAPTURE PHOTO</h4>
					<a href="#" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</a>
				</div>
				<div class="modal-body text-center">
					<input id="bloc_destination" type="hidden">
					<div id="my_camera_install" style="width: 320px; height: 240px;">
						<div></div><video id="webcam_install" autoplay="autoplay" style="width: 320px; height: 240px;"></video>
						<canvas id="canvas_install" class="d-none"></canvas>
						<div class="flash_install"></div>
						<audio id="snapSound_install" src="audio/snap.wav" preload="auto"></audio>
					</div>
					<input type="button" class="btn btn-primary" value="Changer caméra" id="cameraFlip_install">

					<input type="button" class="btn btn-primary" value="Capturer" onclick="take_snapshot_install()">

				</div>
			</div>
		</div>
	</div>


	<div class="modal" id="dlg_main-control-assign" tabindex="-1" role="dialog" aria-labelledby="edit-control" aria-hidden="true" data-backdrop="static" style="overflow: scroll;">
		<div class="modal-dialog modal-lg" style="background-color: #f4f8fc;">
			<div class="modal-content">
				<form id="client_lst" method="post" action="controller.php" enctype="multipart/form-data">
					<div class="modal-header">
						<h5 class="modal-title">Compteurs assignés</h5>

						<a href="#" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</a>
					</div>

					<div class="card bg-white font-semi-bold mt-3 mb-4">
						<div class="card-body">
							<div class="row">
								<div class="col-sm-12">
									<input type="text" id="srch-term-client" name="s" class="form-control" placeholder="Recherche...">
								</div>
							</div>
						</div>
					</div>
					<div id="clients-rows" class="modal-body">


					</div>
				</form>
			</div>
		</div>
	</div>


	<div class="modal" id="box_approbation" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog  modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 id="notification_title" class="modal-title">Approbation Installation</h4>
					<a href="#" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</a>
				</div>
				<div class="modal-body text-center">
					<form id="frm_approbation" method="post" action="controller.php" enctype="multipart/form-data">
						<input id="view" name="view" type="hidden">
						<input id="id_" name="id_" type="hidden">

						<div class="form-group text-left">
							<label class="text-dark text-left">Compteur</label>
							<div id="approve_compteur" class="input-group font-medium text-primary"></div>
						</div>
						<div class="form-group text-left" id='bloc_installateur'>
							<label>Installateurs supplémataires</label>
							<select class='form-control select2' style='width: 100%;' id='list_installateurs_secondaire' name='list_installateurs_secondaire[]' required multiple="multiple">
								<?php
								$stmt_chief = null;
								if ($utilisateur->id_service_group ==  '3') {  //Administration
									$stmt_chief = $utilisateur->GetAllInstallateur();
								} else {
									$stmt_chief = $utilisateur->GetAllChiefLinkedUsers($utilisateur->code_utilisateur);
								}
								while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
									echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
								}

								?></select>
						</div>
						<div class="form-group mt-4 text-left">
							<label class="text-dark text-left">Commentaire</label>
							<div class="input-group" style="width: 100%;">
								<textarea class="form-control pull-right" name="comment_" id="comment_"></textarea>
							</div>
						</div>
						<div class="text-center">
							<button id="btn_submit_cloture" type="button" class="btn btn-success btn-fill float-right">Clôturer</button><button id="btn_submit_approve" type="button" class="btn btn-success btn-fill float-right">Approuver</button>
						</div>
						<div class="clearfix"></div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal" id="box_fiche_viewer" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
		<div class="modal-dialog  modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 id="fiche_viewer_title" class="modal-title">VISUALISATION FICHE</h4>
					<a href="#" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</a>
				</div>
				<div id="fiche_viewer" class="modal-body">

				</div>
			</div>
		</div>
	</div>


	<div class="modal" id="box_verify_compteur" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog  modal-sm" role="document" data-backdrop="static">
			<div class="modal-content">
				<div class="modal-header">
					<h4 id="notification_title" class="modal-title">Vérification Numéro Série Compteur</h4>
					<a href="#" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</a>
				</div>
				<div class="modal-body text-center">
					<form id="frm_verify_compteur" method="post" action="controller.php" enctype="multipart/form-data">
						<input id="view" name="view" type="hidden">
						<input id="verify_fiche_identif" name="verify_fiche_identif" type="hidden">
						<div class="form-group  text-left" id='bloc_rendez_vous'>
							<label>Numéro Compteur</label>
							<div class="input-group">
								<input type="text" class="form-control pull-right" name="serial_number_verify" id="serial_number_verify" style="width: 300px;">
							</div>

						</div>
						<div class="text-center">
							<button id="btn_submit_verify_compteur" type="button" class="btn btn-success btn-fill float-right">Vérifier</button>
						</div>
						<div class="clearfix"></div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal" id="box_signaler_Refus" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog  modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 id="notification_title" class="modal-title">Notification Refus</h4>
					<a href="#" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</a>
				</div>
				<div class="modal-body text-center">
					<form id="frm_signaler_Refus" method="post" action="controller.php" enctype="multipart/form-data">
						<input id="view" name="view" type="hidden">
						<div class="row">
							<div class="col-sm-6">
								<div class="text-dark text-left">
									Adresse
								</div>
								<div class="font-medium text-primary client-adress text-left" id="refus_adresse" refus-ville="" refus-commune="" refus-quartier="" refus-cvs="" refus-avenue='' refus-numero='' refus-pa='' refus-accessibility=''></div>
							</div>
							<div class="col-sm-6 text-right">

								<div id="refus_cvs" class="font-medium text-primary client-cvs"></div>
							</div>
						</div>
						<div class="row" id="bloc_dat_rendez_vous" style="display:none;">
							<div class="col-sm-6">
								<div class="form-group  text-left" id='bloc_rendez_vous'>
									<label>Date Rendez-vous</label>
									<div class="input-group">
										<input type="text" class="form-control pull-right" name="dat_rendez_vous" id="dat_rendez_vous" style="width: 300px;">
									</div>

								</div>
							</div>
						</div>
						<div class="form-group mt-4 text-left">
							<label class="text-dark text-left">COMMETAIRE</label>
							<div class="input-group" style="width: 100%;">
								<textarea class="form-control pull-right" name="refus_commentaire" id="refus_commentaire"></textarea>
							</div>
						</div>
						<div class="text-center">
							<button id="btn_submit_refus" type="button" class="btn btn-success btn-fill float-right">Envoyer</button>
						</div>
						<div class="clearfix"></div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<?php include_once 'layout_map_viewer.php';  ?>
	<div id="myBackdrop" class="modal-backdrop" style="display:none;opacity:.5"></div>
	<script src="assets/js/select2.min.js"></script>
	<script src="assets/js/leaflet.js"></script>
	<script src="assets/js/mapviewer-script.js"></script>
	<script>
		function ShowLoader(txt) {
			$("#loader").attr("data-text", txt);
			$("#loader").addClass("is-active");
		}

		function HideLoader() {
			$("#loader").removeClass("is-active");
		}

		function ShowMain() {
			$('#myBackdrop').show();
			$('#dlg_main-install').show();
			if ($('#dlg_main-install').is(':visible')) {
				if (!$('body').hasClass('modal-open'))
					$('body').addClass('modal-open');
			}
		}

		function CloseMain() {
			$('#myBackdrop').hide();
			$('#dlg_main-install').hide();
			if ($('body').hasClass('modal-open'))
				$('body').removeClass('modal-open');

		}


		function ShowFiche() {
			$('#myBackdrop').show();
			$('#box_fiche_viewer').show();
			if ($('#box_fiche_viewer').is(':visible')) {
				if (!$('body').hasClass('modal-open'))
					$('body').addClass('modal-open');
			}
		}

		function CloseFiche() {
			$('#myBackdrop').hide();
			$('#box_fiche_viewer').hide();
			if ($('body').hasClass('modal-open'))
				$('body').removeClass('modal-open');

		}

		function OpenBackDrop() {
			$('#myBackdrop').show();
			if (!$('body').hasClass('modal-open'))
				$('body').addClass('modal-open');

		}

		function CloseBackDrop() {
			$('#myBackdrop').hide();
			if ($('body').hasClass('modal-open'))
				$('body').removeClass('modal-open');
		}

		$(function() {
			var ctr = 0;

			var actual_chief = "";
			var load_chief = false;
			$('#filtre').select2({
				placeholder: "Filtre CVS, Equipe installation, ....",
				multiple: true
			});
			/*			
			 $("#btn_demander_ticket").click(function (e) {
			            e.preventDefault();	
						
						var fiche = $("#ref_identific").val()!= null?$("#ref_identific").val():'';
						var marque = $("#marque_compteur").val()!= null?$("#marque_compteur").val():'';
						var compteur = $("#numero_compteur").val()!= null?$("#numero_compteur").val():'';
						var type_cpteur = $("#type_new_cpteur").val()!= null?$("#type_new_cpteur").val():'';
						
						if(fiche.length > 0 && marque.length > 0 && compteur.length > 0 && type_cpteur.length > 0 )
						{
						     swal({
			                                    title: "Information",
			                                            text: 'Voulez-vous demander un ticket?',
			                                            type: "warning",
			                                            showCancelButton: true,
			                                            confirmButtonColor: "#00A65A",
			                                            confirmButtonText: "Oui",
			                                            cancelButtonText: "Non",
			                                            closeOnConfirm: false,
			                                            closeOnCancel: true
			                                    }, function (isConfirm) {
			                                    if (isConfirm) {
												var formTicket = new FormData();
												formTicket.append("view", 'ticket_require');
												formTicket.append("fiche", fiche);
												formTicket.append("compteur", compteur);
												formTicket.append("marque", marque);
												formTicket.append("type_cpteur", type_cpteur);
						
						                
					    $.ajax({
			                                //enctype: 'multipart/form-data',
			                                url:"controller.php",
			                                        data: formTicket, // Add as Data the Previously create formData
			                                        type:"POST",
			                                        contentType:false,
			                                        processData:false,
			                                        cache:false,
			                                        dataType:"json", // Change this according to your response from the server.
			                                        error:function(err){
			                                console.error(err);
											$("#btn_demander_ticket").removeAttr('disabled');
												$("#btn_demander_ticket").text("Demander Ticket");
			                                        swal({
			                                title: "Information",
			                                        text: "Serveur non disponible",
			                                        type: "error",
			                                        showCancelButton: false,
			                                        confirmButtonColor: "#DD6B55",
			                                        confirmButtonText: "Ok",
			                                        closeOnConfirm: true,
			                                        closeOnCancel: false
			                                }, function (isConfirm) {
			                                });
			                                },
			                                        success:function(result){
			                                console.log(result);
			                                        try{
			                                if (result.error == 0) {
												$("#btn_demander_ticket").text("Demande ticket envoyée");
						                    swal({
			                                title: "Information",
			                                        text: result.message,
			                                        type: "success",
			                                        showCancelButton: false,
			                                        confirmButtonColor: "#00A65A",
			                                        confirmButtonText: "Ok",
			                                        closeOnConfirm: true,
			                                        closeOnCancel: false
			                                }, function (isConfirm) {
			                                });
			                                } else if (result.error == 1) {
												$("#btn_demander_ticket").removeAttr('disabled');
												$("#btn_demander_ticket").text("Demander Ticket");
			                                //swal("Information", result.message, "error");
			                                swal({
			                                title: "Information",
			                                        text: result.message,
			                                        type: "error",
			                                        showCancelButton: false,
			                                        confirmButtonColor: "#DD6B55",
			                                        confirmButtonText: "Ok",
			                                        closeOnConfirm: true,
			                                        closeOnCancel: false
			                                }, function (isConfirm) {
			                                });
			                                }
			                                } catch (erreur){
			                                swal({
			                                title: "Information",
			                                        text: "Echec d'execution de la requete",
			                                        type: "error",
			                                        showCancelButton: false,
			                                        confirmButtonColor: "#DD6B55",
			                                        confirmButtonText: "Ok",
			                                        closeOnConfirm: true,
			                                        closeOnCancel: false
			                                }, function (isConfirm) {
			                                });
											
											
									//$("#btn_demander_ticket").attr('disabled','disabled');
									$("#btn_demander_ticket").removeAttr('disabled');
									//$("#btn_demander_ticket").addClass('btn-primary');
									//$("#btn_demander_ticket").text("Envoyer");
			                                }
			                                },
			                                        complete:function(){
			                                console.log("Request finished.");
			                                }
			                                });
			                                    }
			                                    });
						
						}else if(fiche == "")
						{ 
							swal("Information", "Fiche abonné non valide!", "error");
			                 			            				
						}	
						else if(marque == "")
						{ 
							
			                 $("#marque_compteur").focus();
							 swal("Information", "Veuillez préciser la marque du compteur", "error");				            				
						}	
						else if(compteur == "")
						{  
							$("#numero_compteur").focus();
							swal("Information", "Veuillez préciser le numéro du compteur", "error");
			                				            				
						}		
						else if(type_cpteur == "")
						{ 
							$("#type_new_cpteur").focus();	
							swal("Information", "Veuillez préciser le type du compteur", "error");
			                 			            				
						}		
											
			                                });*/



			jQuery(document).delegate('a.close,a.fermer', 'click', function(e) {
				e.preventDefault();
				var pId = $(this).parents('div.modal').attr("id");
				$(this).parents('div.modal').hide();
				if (pId == 'box_fiche_viewer') {
					CloseFiche();
				} else if (pId == 'dlg_main-install') {
					CloseMain();
				}
				if (pId == "camera_shooter_install") {
					webcam.stop();
				}
			});

			$("#verify_compteur").click(function(e) {
				e.preventDefault();
				$("#box_verify_compteur #view").val('verify_compteur');
				$("#box_verify_compteur").show();
			});

			$("#btn_submit_verify_compteur").click(function(e) {
				var item = $('#serial_number_verify').val() != null ? $('#serial_number_verify').val() : '';
				if (item == '') {
					swal("Information", "Veuillez saisir le numéro série du compteur", "error");
					return false;
				}
				var form = document.getElementById("frm_verify_compteur");
				var formVerify = new FormData(form);
				$("#verify_compteur").attr('disabled', 'disabled');
				//$("#verify_compteur").text("Vérifier");			
				$.ajax({
					//enctype: 'multipart/form-data',
					url: "controller.php",
					data: formVerify, // Add as Data the Previously create formData
					type: "POST",
					contentType: false,
					processData: false,
					cache: false,
					dataType: "json", // Change this according to your response from the server.
					error: function(err) {
						//console.error(err);

						swal({
							title: "Information",
							text: "Serveur non disponible",
							type: "error",
							showCancelButton: false,
							confirmButtonColor: "#DD6B55",
							confirmButtonText: "Ok",
							closeOnConfirm: true,
							closeOnCancel: false
						}, function(isConfirm) {});
					},
					success: function(result) {
						//console.log(result);
						try {
							if (result.error == 0) {

								//$("#verify_compteur").text("Envoi terminé.");
								//var chk_number=$("#serial_number_verify").val('');
								$("#numero_compteur").val(result.serial_number);
								$("#marque_compteur").val(result.manufacturer_ref).change();
								$("#serial_number_verify").val('');
								$("#box_verify_compteur").hide();
							} else if (result.error == 1) {
								var need_reconnect = result.reconnect != null ? result.reconnect : false;
								if (need_reconnect == true) {
									Reconnect();
								} else {
									//$("#verify_compteur").removeAttr('disabled');
									//$("#verify_compteur").text("Envoyer");						
									//swal("Information", result.message, "error");
									swal({
										title: "Information",
										text: result.message,
										type: "error",
										showCancelButton: false,
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "Ok",
										closeOnConfirm: true,
										closeOnCancel: false
									}, function(isConfirm) {});
								}
							}
						} catch (erreur) {
							swal({
								title: "Information",
								text: "Echec d'execution de la requête",
								type: "error",
								showCancelButton: false,
								confirmButtonColor: "#DD6B55",
								confirmButtonText: "Ok",
								closeOnConfirm: true,
								closeOnCancel: false
							}, function(isConfirm) {});


							//$("#btn_submit_refus").attr('disabled','disabled');
							//$("#btn_submit_refus").removeAttr('disabled');
							//$("#btn_submit_refus").addClass('btn-primary');
							//$("#btn_submit_refus").text("Envoyer");
						}
					},
					complete: function() {
						//console.log("Request finished.");

						$("#verify_compteur").removeAttr('disabled');
						///$("#verify_compteur").text("Vérifier");	
					}
				});


			});
			$("#srch-term-client").keyup(function() {
				var val = $(this).val().toString().toLowerCase();
				$('#client_lst .modal-body').find('.client-row').each(function(i) {
					var row = $(this);
					var client_name = row.find('.client-name').text().toString().toLowerCase();
					var client_adress = row.find('.client-adress').text().toString().toLowerCase();
					var client_device = row.find('.client-device').text().toString().toLowerCase();
					var client_phone = row.find('.client-phone').text().toString().toLowerCase();
					var client_cvs = row.find('.client-cvs').text().toString().toLowerCase();


					if (client_name.indexOf(val) != -1 || client_adress.indexOf(val) != -1 ||
						client_device.indexOf(val) != -1 || client_phone.indexOf(val) != -1 || client_cvs.indexOf(val) != -1) {
						row.show();
						// return false;
					} else row.hide();
				});

				if (!val)
					$('#client_lst .modal-body').find('.client-row').each(function(i) {
						$(this).show();
					});
			});



			$('form input').keydown(function(e) {
				if (e.keyCode == 13) {
					var inputs = $(this).parents("form").eq(0).find(":input");
					if (inputs[inputs.index(this) + 1] != null) {
						inputs[inputs.index(this) + 1].focus();
					}
					e.preventDefault();
					return false;
				}

			});
			$("#post_paie_trouver").change(function() {
				if ($(this).val() == "Oui") {
					$('#num_serie_cpteur_post_paie').prop("required", true);
					$('#index_credit_restant_cpteur_post_paie').prop("required", true);
					//$('#date_retrait_cpteur_post_paie').prop("required", true);
					$('#marque_cpteur_post_paie').prop("required", true);
					//$('#dropdownMenu1').css({"display": "none"});
				} else {
					$('#num_serie_cpteur_post_paie').prop("required", false);
					$('#index_credit_restant_cpteur_post_paie').prop("required", false);
					//$('#date_retrait_cpteur_post_paie').prop("required", false);
					$('#marque_cpteur_post_paie').prop("required", false);
					//$('#dropdownMenu1').css({"display": "block"});
				}
			});
			$('#dlg_main-install .select2').each(function() {
				var $sel = $(this).parent();
				$(this).select2({
					dropdownParent: $sel
				});
			});

			$('#list_installateurs_secondaire').select2({
				tags: true
			});
			$(".allow-numeric").on("keypress keyup blur", function(event) {
				// $(this).val($(this).val().replace(/[^\d].+/, ""));
				/* if (event.which!= 46 ||(event.which < 48 || event.which > 57)) {
				     $(".error").css("display", "inline");
				     event.preventDefault();
				 }else{
				 	$(".error").css("display", "none");
				 }*/
				var charCode = (event.which) ? event.which : event.keyCode
				var val = $(this).val();
				if (charCode == 8 || charCode == 59 || (charCode >= 48 && charCode <= 57)) {
					$(".error").css("display", "none");
				} else {
					//alert(charCode);
					//$(".error").css("display", "inline");
					event.preventDefault();
				}
				if (isNaN(val)) {
					val = val.replace(/[^0-9\.]/g, '');
					if (val.split('.').length > 2)
						val = val.replace(/\.+$/, "");
				}
				$(this).val(val);
				/* return !!(
            (charCode >= 48 && charCode <= 57)
            || (charCode >= 37 && charCode <= 40)
            || (charCode >= 96 && charCode <= 105)
            || charCode == 17
            || charCode == 13
            || charCode == 46
            || charCode == 8
            || charCode == 9
            || charCode == 188)*/
			});


			$("#type_raccordement").on("change", function(e) {
				var item = $(this).val() != null ? $(this).val() : '';
				$("#div_section_cable_alimentation_deux").hide();
				$("#section_cable_alimentation_deux").prop("required", false);
				$("#section_cable_alimentation_deux").val("").change();
				if (item.length > 0) {
					if (item == '3') {
						$("#div_section_cable_alimentation_deux").show();
						$("#section_cable_alimentation_deux").prop("required", true);
					}
				}
			});

			$("#accessibility_client").on("change", function(e) {
				var item = $(this).val() != null ? $(this).val() : '';
				e.preventDefault();
				$("#btn_Signaler_Refus").hide();
				$("#btn_Signaler_Exoneration").hide();
				$("#bloc_dat_rendez_vous").hide();
				if (item.length > 0) {
					if (item == '1') {
						$("#btn_Signaler_Refus").show();
					} else if (item == '3') {
						$("#notification_title").html("Notification Exonération");
						$("#btn_Signaler_Exoneration").text('Signaler Exonération');
						$("#btn_Signaler_Exoneration").addClass('btn-warning');
						$("#btn_Signaler_Exoneration").show();
					} else if (item == '4') {
						$("#notification_title").html("Enregistrement rendez-vous");
						$("#btn_Signaler_Exoneration").text('Rendez-vous');
						$("#btn_Signaler_Exoneration").removeClass('btn-warning');
						$("#btn_Signaler_Exoneration").addClass('btn-info');
						$("#btn_Signaler_Exoneration").show();

						$("#bloc_dat_rendez_vous").show();
					}
				}
			});

			$("#btn_Signaler_Exoneration").click(function(e) {
				e.preventDefault();
				var access_cli = $("#accessibility_client").val();
				var cvs_id = $("#cvs_id_inst2").attr('data_id');
				var assign_id = $("#cvs_id_inst2").attr('assign_id');
				var cvs_Label = '';
				var adresse_id = $("#adresse_inst2").attr('adresse_id');
				var adr_Label = $("#adresse_inst2").attr('adressetexte');
				if (access_cli.length > 0) {


					$("#refus_adresse").html(adr_Label);
					$("#refus_cvs").html('');
					$("#refus_cvs").attr('cvs_id', cvs_id);
					$("#refus_cvs").attr('assign_id', assign_id);
					$("#refus_cvs").attr('accessibility_client', access_cli);
					$("#refus_cvs").attr('adresse_id', adresse_id);
					$("#notification_title").html("Notification Exonération");
					//	$("#frm_signaler_Refus #view").html("create_refus");				
					$("#dlg_main-install").hide();
					$("#box_signaler_Refus").show();
				} else if (access_cli == "") {
					$("#accessibility_client").focus();
					swal("Information", "Veuillez préciser l'accesbilité client", "error");

				}
			});


			$("#btn_Signaler_Refus").click(function(e) {
				e.preventDefault();
				var access_cli = $("#accessibility_client").val();
				var cvs_id = $("#cvs_id_inst2").attr('data_id');
				var assign_id = $("#cvs_id_inst2").attr('assign_id');
				var cvs_Label = '';
				var adresse_id = $("#adresse_inst2").attr('adresse_id');
				var adr_Label = $("#adresse_inst2").attr('adressetexte');
				if (access_cli.length > 0) {


					$("#refus_adresse").html(adr_Label);
					$("#refus_cvs").html('');
					$("#refus_cvs").attr('cvs_id', cvs_id);
					$("#refus_cvs").attr('assign_id', assign_id);
					$("#refus_cvs").attr('accessibility_client', access_cli);
					$("#refus_cvs").attr('adresse_id', adresse_id);
					$("#notification_title").html("Notification Refus");
					//	$("#frm_signaler_Refus #view").html("create_refus");				
					$("#dlg_main-install").hide();
					$("#box_signaler_Refus").show();
				} else if (access_cli == "") {
					$("#accessibility_client").focus();
					swal("Information", "Veuillez préciser l'accesbilité client", "error");

				}
			});




			$('#btn_submit_refus').click(function() {
				if ($('#accessibility_client').val() == 4) {
					var item = $('#dat_rendez_vous').val() != null ? $('#dat_rendez_vous').val() : '';
					if (item == '') {
						swal("Information", "Veuillez préciser la date du rendez-vous", "error");
						return false;
					}

				}
				$("#frm_signaler_Refus #view").val('create_refus_install');
				var form = document.getElementById("frm_signaler_Refus");
				var formRefus = new FormData(form);



				var refus_cvs = $("#refus_cvs").attr('cvs_id');
				var refus_assign_id = $("#refus_cvs").attr('assign_id');
				var adresse_id = $('#refus_cvs').attr('adresse_id');
				var refus_accessibility = $('#refus_cvs').attr('accessibility_client');
				var refus_comment = $('#refus_commentaire').val();

				//formRefus.append("view", 'create_refus');
				formRefus.append("refus_assign_id", refus_assign_id);
				formRefus.append("refus_adress_id", adresse_id);
				formRefus.append("refus_cvs", refus_cvs);
				formRefus.append("refus_accessibility", refus_accessibility);
				formRefus.append("refus_comment", refus_comment);


				$("#btn_submit_refus").attr('disabled', 'disabled');
				$("#btn_submit_refus").removeClass('btn-primary');
				$("#btn_submit_refus").text("Envoi en cours ...");

				$.ajax({
					//enctype: 'multipart/form-data',
					url: "controller.php",
					data: formRefus, // Add as Data the Previously create formData
					type: "POST",
					contentType: false,
					processData: false,
					cache: false,
					dataType: "json", // Change this according to your response from the server.
					error: function(err) {
						// console.error(err);
						$("#btn_submit_refus").removeAttr('disabled');
						$("#btn_submit_refus").text("Envoyer");
						swal({
							title: "Information",
							text: "Serveur non disponible",
							type: "error",
							showCancelButton: false,
							confirmButtonColor: "#DD6B55",
							confirmButtonText: "Ok",
							closeOnConfirm: true,
							closeOnCancel: false
						}, function(isConfirm) {});
					},
					success: function(result) {
						console.log(result);
						try {
							if (result.error == 0) {
								$("#btn_submit_refus").text("Envoi terminé.");
								swal({
									title: "Information",
									text: result.message,
									type: "success",
									showCancelButton: false,
									confirmButtonColor: "#00A65A",
									confirmButtonText: "Ok",
									closeOnConfirm: true,
									closeOnCancel: false
								}, function(isConfirm) {
									// ClearForm();
									//ClearMaterielsRow();
									$("#box_signaler_Refus").hide();
									window.location.reload();
								});
							} else if (result.error == 1) {
								$("#btn_submit_refus").removeAttr('disabled');
								$("#btn_submit_refus").text("Envoyer");

								//swal("Information", result.message, "error");
								swal({
									title: "Information",
									text: result.message,
									type: "error",
									showCancelButton: false,
									confirmButtonColor: "#DD6B55",
									confirmButtonText: "Ok",
									closeOnConfirm: true,
									closeOnCancel: false
								}, function(isConfirm) {});
							}
						} catch (erreur) {
							swal({
								title: "Information",
								text: "Echec d'execution de la requete",
								type: "error",
								showCancelButton: false,
								confirmButtonColor: "#DD6B55",
								confirmButtonText: "Ok",
								closeOnConfirm: true,
								closeOnCancel: false
							}, function(isConfirm) {});


							//$("#btn_submit_refus").attr('disabled','disabled');
							$("#btn_submit_refus").removeAttr('disabled');
							$("#btn_submit_refus").addClass('btn-primary');
							$("#btn_submit_refus").text("Envoyer");
						}
					},
					complete: function() {
						console.log("Request finished.");
					}
				});

			});




			$('#dat_rendez_vous').datetimepicker({
				format: 'dd/mm/yyyy',
				language: 'fr',
				weekStart: 1,
				todayBtn: 1,
				autoclose: 1,
				minView: 2
			});

			$("#add_on_du").click(function() {
				$('#Du').datetimepicker('show');
			});
			$("#add_on_au").click(function() {
				$('#Au').datetimepicker('show');
			});

			/* $("#add_on_retrait").click(function() {
	$('#date_retrait_cpteur_post_paie').datetimepicker('show');
});*/
			/*
			  $("#add_on_date_pose_scelle").click(function() {
				$('#date_pose_scelle').datetimepicker('show');
			});*/

			if ($("#Du").length) {
				$('#Du').datetimepicker({
					format: 'dd/mm/yyyy',
					language: 'fr',
					weekStart: 1,
					todayBtn: 1,
					autoclose: 1,
					minView: 2
				});

			}
			if ($("#Au").length) {
				$('#Au').datetimepicker({
					format: 'dd/mm/yyyy',
					language: 'fr',
					weekStart: 1,
					todayBtn: 1,
					autoclose: 1,
					minView: 2
				});

			}
			/*if ($("#date_retrait_cpteur_post_paie").length) {
					$('#date_retrait_cpteur_post_paie').datetimepicker({
						  format: 'dd/mm/yyyy',
        language:  'fr',
        weekStart: 1,
        todayBtn:  1,
		autoclose: 1,
		minView: 2
					});

				}*/
			//Empeche la propagation du hide.bs.collapse et show.bs.collapse sur le datepicker
			$("#Du").on("show", function(e) {
				e.preventDefault();
				e.stopPropagation();
			}).on("hide", function(e) {
				e.preventDefault();
				e.stopPropagation();
			});
			$("#Au").on("show", function(e) {
				e.preventDefault();
				e.stopPropagation();
			}).on("hide", function(e) {
				e.preventDefault();
				e.stopPropagation();
			});

			$("#advanced_search").on("hide.bs.collapse", function() {
				$('#srch-term').show();
				$('#search-btn').show();
			});
			$("#advanced_search").on("show.bs.collapse", function() {
				$('#srch-term').hide();
				$('#search-btn').hide();
			});



			/*
			if ($("#date_pose_scelle").length) {
								$('#date_pose_scelle').datetimepicker({
									  format: 'dd/mm/yyyy',
			        language:  'fr',
			        weekStart: 1,
			        todayBtn:  1,
					autoclose: 1,
					minView: 2
								});
			}*/

			function hideReplaceBlock() {
				//$("#block_remplacement").prop("display", "none");
				$("#block_remplacement").hide();
				$("#marque_cpteur_replaced").prop("required", false);
				$("#index_credit_restant_cpteur_replaced").prop("required", false);
				$("#num_serie_cpteur_replaced").prop("required", false);
				$("#type_defaut").prop("required", false);
			}

			function showReplaceBlock() {
				$("#block_remplacement").show();
				$("#marque_cpteur_replaced").prop("required", true);
				$("#index_credit_restant_cpteur_replaced").prop("required", true);
				$("#num_serie_cpteur_replaced").prop("required", true);
				$("#type_defaut").prop("required", true);
			}

			function showBlockPostPaie() {
				$("#block_post_paie").show();
			}

			function hideBlockPostPaie() {
				$("#block_post_paie").hide();
			}

			$('#item_label_install').val("").change();
			$('.modal').on("hidden.bs.modal", function(e) { //fire on closing modal box scroll issue hack
				if ($('.modal:visible').length) {
					$('body').addClass('modal-open');
				}
			});

			jQuery(document).delegate('a.delete-install-item', 'click', function(e) {
				e.preventDefault();
				var itemId = $(this).parents('tr.item-row-install').attr('item-id');
				var label = $('tr.item-row-install[item-id="' + itemId + '"]').find('span.sn').html();
				swal({
					title: "Information",
					text: 'Voulez-vous rétirer le matériel (' + label + ') de la liste?',
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#00A65A",
					confirmButtonText: "Oui",
					cancelButtonText: "Non",
					closeOnConfirm: true,
					closeOnCancel: true
				}, function(isConfirm) {
					if (isConfirm) {
						$('tr.item-row-install[item-id="' + itemId + '"]').remove();
						reOrderRow_install();
					}
				});
			});

			function generateItemID(parent) {
				ctr++;
				$(parent).find('tr').each(function(i) {
					var itemId = $(this).attr('data-id-install');
					if (ctr == itemId) {
						generateItemID(parent);
					}

				});
				return ctr;
			}
			$('#ligne_form_install .select2').each(function() {
				var $sel = $(this).parent();
				$(this).select2({
					dropdownParent: $sel
				});
			});


			$("#cameraFlip_install").click(function() {
				webcam.flip();
				webcam.start();

			});

			$("#btn_capture_post_paie").click(function(e) {
				e.preventDefault();
				webcam.start();
				$("#bloc_destination").val('photo_compteur_post_paie');
				//  Webcam.attach('#my_camera_install');
				$("#camera_shooter_install").show();
			});
			$("#btn_capture_install").click(function(e) {
				e.preventDefault();
				webcam.start();
				$("#bloc_destination").val('photo_compteur');
				//  Webcam.attach('#my_camera_install');
				$("#camera_shooter_install").show();
			});

			$("#btn_capture_avant_install").click(function(e) {
				e.preventDefault();
				webcam.start();
				$("#bloc_destination").val('photo_avant_install');
				//  Webcam.attach('#my_camera_install');
				$("#camera_shooter_install").show();
			});

			$("#btn_capture_apres_install").click(function(e) {
				e.preventDefault();
				webcam.start();
				$("#bloc_destination").val('photo_apres_install');
				//  Webcam.attach('#my_camera_install');
				$("#camera_shooter_install").show();
			});

			$("#btn_capture_sceller_un").click(function(e) {
				e.preventDefault();
				webcam.start();
				$("#bloc_destination").val('photo_sceller_un');
				//  Webcam.attach('#my_camera_install');
				$("#camera_shooter_install").show();
			});

			$("#btn_capture_sceller_deux").click(function(e) {
				e.preventDefault();
				webcam.start();
				$("#bloc_destination").val('photo_sceller_deux');
				//  Webcam.attach('#my_camera_install');
				$("#camera_shooter_install").show();
			});

			$("#btn_capture_defectueux").click(function(e) {
				e.preventDefault();
				webcam.start();
				$("#bloc_destination").val('photo_compteur_defectueux');
				//  Webcam.attach('#my_camera_install');
				$("#camera_shooter_install").show();
			});





			$("#add_line_install").click(function() {
				$('#item_titre_install').html('AJOUT MATERIEL');
				$('#item-qte-install').val('');
				$('#item_label_install').val('').change();
				$('#ligne_form_install').show();
				$('#item-type-install').val('0');
			});

			$("#btn_add_line_install").click(function() {
				if ($('#item_label_install').val() === null) {
					swal("Information", "Veuillez choisir le matériel!", "error");
					return false;
				}
				if ($('#item-qte-install').val() == "") {
					swal("Information", "Veuillez saisir la quantité!", "error");
					return false;
				}

				var mat_id = $('#item_label_install').val();
				var mat_Label = "";
				var selected = $('#item_label_install').select2('data');
				if (selected) {
					mat_Label = selected[0].text;
				}



				var lignes_install = $('#lignes_install tbody');
				var label = mat_Label; //$('#item_label_install').val();
				var type = $('#item-type-install').val();
				var id = $('#item-id').val();
				var qte = $('#item-qte-install').val();
				var numero = '0';

				if (type == '0') {

					var exist = false;
					lignes_install.find('tr').each(function(i) {
						var itemId = $(this).attr('materiel-id');
						if (mat_id == itemId) {
							exist = true;
							//$(this).remove();
						}

					});
					if (exist == true) {
						//alert("found");
						swal("Information", "Le matériel (" + label + ") existe déjà dans la liste", "error");
						return false;
					}


					var Id = generateItemID(lignes_install);
					lignes_install.append('<tr class="item-row-install" item-id="item-' + Id + '" data-id-install="' + Id + '"  materiel-id="' + mat_id + '"><td style="width:5%"><span class="n">' + numero + '</span></td><td style="width:80%"><span class="sn">' + label + '</span></td><td><span class="qte">' + qte + '</span></td><td><a class="btn btn-xs edit-install-item"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-xs delete-install-item"><i class="fas fa-trash"></i></a></td></tr>');
				} else {
					$('tr.item-row-install[item-id="' + id + '"]').find('span.sn').html(label);
					$('tr.item-row-install[item-id="' + id + '"]').find('span.qte').html(qte);
					$('tr.item-row[item-id="' + id + '"]').attr('materiel-id', mat_id);
				}

				reOrderRow_install();

				$('#ligne_form_install').hide();
				$('#item_label_install').val("").change();
				$('#item-qte-install').val("");
				$('#item-type-install').val("");
				return false;

			});

			function reOrderRow_install() {
				$('#lignes_install tr').each(function(index) {
					$(this).find('span.n').html(index);
				});
			}

			function ClearMaterielsRow() {
				$('#lignes_install tbody tr').each(function(index) {
					var itemId = $(this).attr('item-id');
					ctr = 0;
					$('tr.item-row-install[item-id="' + itemId + '"]').remove();
				});
			}

			jQuery(document).delegate('a.edit-install-item', 'click', function(e) {
				e.preventDefault();
				var itemId = $(this).parents('tr.item-row-install').attr('item-id');
				var materiel_id = $(this).parents('tr.item-row-install').attr('materiel-id');
				var label = $('tr.item-row-install[item-id="' + itemId + '"]').find('span.sn').html();
				var qte = $('tr.item-row-install[item-id="' + itemId + '"]').find('span.qte').html();
				$('#item_titre_install').html('MODIFICATION MATERIEL');
				$('#ligne_form_install').show();
				$('#item-id').val(itemId);
				$('#item_label_install').val(materiel_id).change();
				$('#item-qte-install').val(qte);
				$('#item-type-install').val('1');

			});

			<?php if ($utilisateur->HasDroits("10_80")) {
			?>
				jQuery(document).delegate('a.delete-install', 'click', function(e) {
					e.preventDefault();
					var name_actuel = jQuery(this).attr("data-name-install");
					var jeton_actuel = jQuery(this).attr("data-id-install");
					swal({
						title: "Information",
						text: 'Voulez-vous supprimer l\'installation de l\'abonné (' + name_actuel + ')?',
						type: "warning",
						showCancelButton: true,
						confirmButtonColor: "#00A65A",
						confirmButtonText: "Oui",
						cancelButtonText: "Non",
						closeOnConfirm: false,
						closeOnCancel: true
					}, function(isConfirm) {
						if (isConfirm) {
							var view_mode = "delete_install";
							$.ajax({
								url: "controller.php",
								method: "POST",
								data: {
									view: view_mode,
									k: jeton_actuel
								},
								success: function(data) {
									var result = $.parseJSON(data);
									if (result.error == 0) {
										swal({
											title: "Information",
											text: result.message,
											type: "success",
											showCancelButton: false,
											confirmButtonColor: "#00A65A",
											confirmButtonText: "Ok",
											closeOnConfirm: true,
											closeOnCancel: false
										}, function(isConfirm) {
											window.location.reload();
										});
									} else if (result.error == 1) {
										swal("Information", result.message, "error");
									}
								}
							});
						}
					});

				});
			<?php }  ?>



			<?php
			if ($utilisateur->id_service_group ==  '3') {
			?>

				$("#id_equipe").on("change", function(e) {
					var item = $(this).val();
					e.preventDefault();
					if (load_chief == false) {
						return false;
					}
					ShowLoader("Chargement liste des chefs d'equipe en cours...");
					$("#chef_equipe_install").html('');
					$.ajax({
						url: "controller.php",
						method: "GET",
						data: {
							view: "get_organisme_chief",
							id_: item
						},
						success: function(data, statut) {
							try {
								var result = $.parseJSON(data);
								if (result.error == 0) {
									$("#chef_equipe_install").html(result.data);
									if (actual_chief != "") {
										$("#chef_equipe_install").val(actual_chief).change();
									}
								} else if (result.error == 1) {}
							} catch (erreur) {
								swal({
									title: "Information",
									text: "Echec d'execution de la requete",
									type: "error",
									showCancelButton: false,
									confirmButtonColor: "#DD6B55",
									confirmButtonText: "Ok",
									closeOnConfirm: true,
									closeOnCancel: false
								}, function(isConfirm) {});
							}
						},
						error: function(resultat, statut, erreur) {
							swal({
								title: "Information",
								text: "Serveur non disponible",
								type: "error",
								showCancelButton: false,
								confirmButtonColor: "#DD6B55",
								confirmButtonText: "Ok",
								closeOnConfirm: true,
								closeOnCancel: false
							}, function(isConfirm) {});
						},
						complete: function(e) {
							HideLoader();
						}
					});

				});


			<?php
			}


			?>

			function ClearForm_install() {

				load_chief = false;
				$("#btn_save_install").show();
				//$("#statut_installation").removeAttr('disabled');
				$("#view").val("");
				$("#id_install").val("");
				//	$("#date_fin_installation").val("");
				$("#nom_responsable_inst2").val("");
				$("#nomclientblue_").val("");
				$("#adresse_inst2").val("");
				//$("#quartier_install2").val("");
				$("#cvs_id_inst2").val("").change();
				$("#ref_identific").val("");
				$("#p_a_inst2").val("");
				$("#tarif_identif2").val("");
				$("#cabine").val("");
				$("#num_depart").val("");
				$("#num_poteau").val("");
				$("#type_cpteur_raccord").val("").change();
				$("#type_raccordement").val("").change();
				$("#nbre_alimentation").val("");
				$("#section_cable_alimentation").val("").change();
				$("#section_cable_alimentation_deux").val("").change();
				$("#section_cable_sortie").val("").change();
				$("#presence_inverseur").val("").change();
				$("#marque_cpteur_post_paie").val("");
				$("#num_serie_cpteur_post_paie").val("");
				$("#num_serie_cpteur_post_paie").val("");
				$("#index_credit_restant_cpteur_post_paie").val("");
				//$("#date_retrait_cpteur_post_paie").val("");
				$("#marque_compteur").val("").change();
				$("#numero_compteur").val("");
				$("#type_new_cpteur").val("").change();
				$("#disjoncteur").val("");
				$("#replace_client_disjonct").prop('checked', false);

				$("#client_disjonct_amperage").val("");
				$("#is_autocollant_posed").val("").change();
				//$("#statut_installation").val("").change();
				$("#scelle_un_cpteur").val("");
				$("#scelle_deux_coffret").val("");
				//$("#date_pose_scelle").val("");
				$("#usage_electricity").val("").change();
				$("#etat_poc").val("").change();
				$("#id_equipe").val("").change();
				$("#etat_compteur_reaffected").val("").change();
				$("#commentaire_installateur").val("");
				//$("#commenteur_controle_blue").val("");
				$("#chef_equipe_install").val("").change();
				$("#installateur").val("").change();


				$("#photo_pa_list").html('');

				$("#photo_compteur").attr('src', 'pictures/');

				load_chief = true;
			}
			$('#btn_save_install').click(function() {

				var opera_tion = $("#mainForm_install #view").val();
				if (opera_tion == 'create_install_rpl') {
					$("#post_paie_trouver").attr('required', false);
				} else if (opera_tion == 'edit_install') {
					$("#post_paie_trouver").attr('required', false);
				}
				var frm = $("#mainForm_install");
				if (frm.parsley().validate()) {
					// alert("oui");				   
				} else {
					// alert("non"); 
					return false;
				}


				var form = document.getElementById("mainForm_install");
				// Create a FormData and append the file with "image" as parameter name
				var formDataToUpload = new FormData(form);
				var numero_compteur = $('#numero_compteur').val();
				var marque_compteur = $('#marque_compteur').val();
				var lst_materiels = '[';
				var has_Rows = false;
				var otArr = [];
				var rows_count = $('#lignes_install tbody tr.item-row-install');
				$('#lignes_install tbody tr.item-row-install').each(function(i) {
					var itArr = [];
					has_Rows = true;
					var item_row_id = $(this).attr('item-id');
					/*var item_row_data_id = $(this).attr('data-id-install');*/
					//var item_row_label =$('tr.item-row-install[item-id="' + item_row_id + '"]').find('span.sn').html();
					var item_row_label = $(this).attr('materiel-id');
					var item_row_qte = $('tr.item-row-install[item-id="' + item_row_id + '"]').find('span.qte').html();
					otArr.push("{\"libelle\":\"" + item_row_label + "\",\"qte\":\"" + item_row_qte + "\"}");
				});
				var typ = $('#mainForm_install #view').val();
				var doc_save_mode = $('#doc_save_mode').val();
				if (has_Rows == false && typ == '0') {
					swal("Information", "Veuillez définir les matériels utilisés", "error");
					return false;
				}
				lst_materiels += otArr.join(",") + ']';
				// Get the form

				ShowLoader("Enregistrement en cours...");
				///Imagery
				var base64image = document.getElementById("photo_compteur").src;
				if (base64image.match(/^data\:image\/(\w+)/)) {
					var block = base64image.split(";");
					var contentType = block[0].split(":")[1];
					var realData = block[1].split(",")[1];
					var blob = b64toBlob(realData, contentType);
					formDataToUpload.append("photo_compteur", blob);
				}
				///Imagery
				var base64image = document.getElementById("photo_compteur_post_paie").src;
				if (base64image.match(/^data\:image\/(\w+)/)) {
					var block = base64image.split(";");
					var contentType = block[0].split(":")[1];
					var realData = block[1].split(",")[1];
					var blob = b64toBlob(realData, contentType);
					formDataToUpload.append("photo_compteur_post_paie", blob);
				}

				base64image = document.getElementById("photo_avant_install").src;
				if (base64image.match(/^data\:image\/(\w+)/)) {
					var block = base64image.split(";");
					var contentType = block[0].split(":")[1];
					var realData = block[1].split(",")[1];
					var blob = b64toBlob(realData, contentType);
					formDataToUpload.append("photo_avant_install", blob);
				}

				base64image = document.getElementById("photo_apres_install").src;
				if (base64image.match(/^data\:image\/(\w+)/)) {
					var block = base64image.split(";");
					var contentType = block[0].split(":")[1];
					var realData = block[1].split(",")[1];
					var blob = b64toBlob(realData, contentType);
					formDataToUpload.append("photo_apres_install", blob);
				}

				base64image = document.getElementById("photo_sceller_un").src;
				if (base64image.match(/^data\:image\/(\w+)/)) {
					var block = base64image.split(";");
					var contentType = block[0].split(":")[1];
					var realData = block[1].split(",")[1];
					var blob = b64toBlob(realData, contentType);
					formDataToUpload.append("photo_sceller_un", blob);
				}

				base64image = document.getElementById("photo_sceller_deux").src;
				if (base64image.match(/^data\:image\/(\w+)/)) {
					var block = base64image.split(";");
					var contentType = block[0].split(":")[1];
					var realData = block[1].split(",")[1];
					var blob = b64toBlob(realData, contentType);
					formDataToUpload.append("photo_sceller_deux", blob);
				}

				base64image = document.getElementById("photo_compteur_defectueux").src;
				if (base64image.match(/^data\:image\/(\w+)/)) {
					var block = base64image.split(";");
					var contentType = block[0].split(":")[1];
					var realData = block[1].split(",")[1];
					var blob = b64toBlob(realData, contentType);
					formDataToUpload.append("photo_compteur_defectueux", blob);
				}
				formDataToUpload.append("numero_compteur", numero_compteur);
				formDataToUpload.append("marque_compteur", marque_compteur);


				formDataToUpload.append("lst_materiels", lst_materiels);

				$.ajax({
					//enctype: 'multipart/form-data',
					url: "controller.php",
					data: formDataToUpload, // Add as Data the Previously create formData
					type: "POST",
					contentType: false,
					processData: false,
					cache: false,
					dataType: "json", // Change this according to your response from the server.

					beforeSend: function() {
						// ShowLoader("Enregistrement en cours..."); 
					},
					error: function(err) {
						console.error(err);
						swal({
							title: "Information",
							text: "Serveur non disponible",
							type: "error",
							showCancelButton: false,
							confirmButtonColor: "#DD6B55",
							confirmButtonText: "Ok",
							closeOnConfirm: true,
							closeOnCancel: false
						}, function(isConfirm) {});
					},
					success: function(result) {
						console.log(result);
						try {
							if (result.error == 0) {
								swal({
									title: "Information",
									text: result.message,
									type: "success",
									showCancelButton: false,
									confirmButtonColor: "#00A65A",
									confirmButtonText: "Ok",
									closeOnConfirm: true,
									closeOnCancel: false
								}, function(isConfirm) {

									var op_ref = $('#dlg_main-install #view').val();

									if (op_ref == 'create_install' || op_ref == 'create_install_rpl') {
										$('#id_install').val(result.id);

										$("#dlg_main-install #view").val("edit_install");

										$('#Heading-install').html("MISE A JOUR INFORMATIONS DE L' INSTALLATION");
										$('#btn_save_install').find('span.sn').html('Valider');

									} else {
										ClearForm_install();
										ClearMaterielsRow();
										$("#dlg_main-install").hide();
										window.location.reload();
									}
								});
							} else if (result.error == 1) {

								var need_reconnect = result.reconnect != null ? result.reconnect : false;
								if (need_reconnect == true) {
									Reconnect();
								} else {

									swal({
										title: "Information",
										text: result.message,
										type: "error",
										showCancelButton: false,
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "Ok",
										closeOnConfirm: true,
										closeOnCancel: false
									}, function(isConfirm) {});


								}
							}
						} catch (erreur) {
							swal({
								title: "Information",
								text: "Echec d'execution de la requete",
								type: "error",
								showCancelButton: false,
								confirmButtonColor: "#DD6B55",
								confirmButtonText: "Ok",
								closeOnConfirm: true,
								closeOnCancel: false
							}, function(isConfirm) {});
						}
					},
					complete: function() {
						HideLoader();
						console.log("Request finished.");
					}
				});

			});

			function b64toBlob(b64Data, contentType, sliceSize) {
				contentType = contentType || '';
				sliceSize = sliceSize || 512;

				var byteCharacters = atob(b64Data);
				var byteArrays = [];

				for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
					var slice = byteCharacters.slice(offset, offset + sliceSize);

					var byteNumbers = new Array(slice.length);
					for (var i = 0; i < slice.length; i++) {
						byteNumbers[i] = slice.charCodeAt(i);
					}

					var byteArray = new Uint8Array(byteNumbers);

					byteArrays.push(byteArray);
				}

				var blob = new Blob(byteArrays, {
					type: contentType
				});
				return blob;
			}




			<?php if ($utilisateur->HasDroits("10_60")) {
			?> $('#btn_new_').click(function() {
					ClearForm_install();

					ShowLoader("Chargement en cours...");

					$('#doc_save_mode').html('');
					$('#doc_save_mode').append('<option selected="" value="">Choisir mode de sauvegarde </option>');
					$('#doc_save_mode').append('<option value="1">Brouillon</option>');
					$('#doc_save_mode').append('<option value="0">Définitive</option>');




					$("#clients-rows").html('');
					$.ajax({
						url: "controller.php",
						method: "GET",
						data: {
							view: "get_install_assign"
						},
						success: function(data, statut) {
							try {
								var result = $.parseJSON(data);
								if (result.error == 0) {
									var rendez_vous = '';
									//  $("#clients-rows").html(result.data);
									$.each(result.items, function(i, item) {
										// Id = generateItemID(lignes);
										var operation = "Installer";
										var operation_class = "control-assign-detail";
										rendez_vous = '';
										if (item.data.date_rendez_vous != null) {
											rendez_vous = '<span class="badge badge-primary client-rendez-vous"> Date rendez-vous ' + item.data.date_rendez_vous_fr + '</span>';
										}
										if (item.data.est_installer == '1') {
											operation = "Remplacer";
											operation_class = "replace-assign-detail";
										}
										$("#clients-rows").append('<div class="client-row card bg-white"><div class="card-header">	<div class="row"><div class="col-sm-6">	<div class="text-dark">Client</div>	<div class="font-medium text-primary client-name">' + item.data.nom_client_blue + ' ' + rendez_vous + '</div></div><div class="col-sm-6"><div class="text-right"><div class="btn-group"><a href="#" class="btn btn-outline-primary ' + operation_class + '" data-id-detail="' + item.data.id_ + '" data-id-assign="' + item.data.id_assign + '">' + operation + '</a></div>	</div></div>	</div></div><div class="card-body">	<div class="row">	<div class="col-sm-4"><div class="text-dark">Adresse</div>	<div class="font-medium text-primary client-adress">' + item.adresseTexte + '</div></div><div class="col-sm-4 text-center">	<div class="text-dark">Téléphone</div>	<div class="font-medium text-primary client-phone">' + item.data.phone_client_blue + '</div></div><div class="col-sm-4 text-right">	<div class="text-dark">CVS	</div>	<div class="font-medium text-primary client-cvs">' + item.data.libelle + '</div></div></div>	<div class="row"><div class="col-sm-6">	<div class="text-dark">Compteur	</div>	<div class="font-medium text-primary client-device">' + item.data.num_compteur_actuel + '</div></div> </div></div></div>');


									});
									if (result.items.length == 0) {
										$("#clients-rows").append('<div class="card alert-danger"><div class="card-body"><div role="alert" class=""><h4 class="alert-heading">Notification!</h4><p>Aucune information trouvée.</p></div></div></div>');
									}
									$('#dlg_main-control-assign').show();

								} else if (result.error == 1) {
									var need_reconnect = result.reconnect != null ? result.reconnect : false;
									if (need_reconnect == true) {
										Reconnect();
									}
								}
							} catch (erreur) {
								swal({
									title: "Information",
									text: "Echec d'execution de la requête",
									type: "error",
									showCancelButton: false,
									confirmButtonColor: "#DD6B55",
									confirmButtonText: "Ok",
									closeOnConfirm: true,
									closeOnCancel: false
								}, function(isConfirm) {});
							}
						},
						error: function(resultat, statut, erreur) {
							swal({
								title: "Information",
								text: "Serveur non disponible",
								type: "error",
								showCancelButton: false,
								confirmButtonColor: "#DD6B55",
								confirmButtonText: "Ok",
								closeOnConfirm: true,
								closeOnCancel: false
							}, function(isConfirm) {});
						},
						complete: function(resultat, statut, erreur) {
							HideLoader();
						}
					});


				});
			<?php } ?>



			jQuery(document).delegate('a.control-assign-detail', 'click', function(e) {
				e.preventDefault();
				$('#dlg_main-control-assign').hide();
				ClearForm_install();
				ClearMaterielsRow();
				hideReplaceBlock();
				showBlockPostPaie();
				$('#Heading-install').html("NOUVELLE INSTALLATION");
				$('#btn_save_install').find('span.sn').html('Pré-valider');
				$('#dlg_main-install #view').val("create_install");
				$('#add_line_install').show();
				var jeton_actuel = jQuery(this).attr("data-id-detail");
				var id_assign = jQuery(this).attr("data-id-assign");
				///$('#titre_control').html('NOUVELLE INSTALLATION');
				$('#id_assign').val(id_assign);
				$("#cvs_id_inst2").attr('assign_id', id_assign);
				loadDetail(jeton_actuel);
			});

			jQuery(document).delegate('a.replace-assign-detail', 'click', function(e) {
				e.preventDefault();
				$('#dlg_main-control-assign').hide();
				ClearForm_install();
				ClearMaterielsRow();
				$('#dlg_main-install #view').val("create_install_rpl");
				$('#Heading-install').html('NOUVEAU REMPLACEMENT');
				$('#btn_save_install').find('span.sn').html('Pré-valider');
				showReplaceBlock();
				hideBlockPostPaie();
				var jeton_actuel = jQuery(this).attr("data-id-detail");
				var id_assign = jQuery(this).attr("data-id-assign");
				//$('#titre_control').html('NOUVELLE INSTALLATION');									  
				$('#id_assign').val(id_assign);
				$("#cvs_id_inst2").attr('assign_id', id_assign);
				loadDetail(jeton_actuel);
			});


			function loadDetail(jeton_actuel) {
				ShowLoader("Chargement détails liés au compteur en cours...");
				$.ajax({
					url: "controller.php",
					dataType: "json",
					method: "GET",
					data: {
						view: 'detail_customer',
						k: jeton_actuel
					},
					success: function(result, statut) { // success est toujours en place, bien sûr !


						try {
							//var result = $.parseJSON(data);
							if (result.error == 0) {

								$("#ref_identific").val(result.data.id_);
								$("#verify_fiche_identif").val(result.data.id_);
								$("#date_identification_inst2").val(result.data.date_identification_fr);
								$("#p_a_inst2").val(result.data.p_a);

								if (result.data.gps_longitude != null && result.data.gps_latitude != null) {
									$("#btn_map_viewer").attr('data-lat', result.data.gps_latitude);
									$("#btn_map_viewer").attr('data-lng', result.data.gps_longitude);
									//$("#btn_map_viewer").text('Visualiser Carte');
									$("#btn_map_viewer").removeAttr('disabled');
									$("#btn_map_viewer").show();
									//$("#btn_map_viewer").removeClass('btn-outline-danger');
									//$("#btn_map_viewer").addClass('btn-outline-light');

								} else {

									$("#btn_map_viewer").hide();
									//$("#btn_map_viewer").removeClass('btn-outline-light');
									//$("#btn_map_viewer").addClass('btn-outline-danger');
									//$("#btn_map_viewer").text('Pas de coordonnées GPS');
									//$("#btn_map_viewer").attr('disabled','disabled');

								}
								//	 $("#marque_compteur_inst").val(result.data.num_compteur_actuel);
								// $("#commune_id").val(result.data.commune_id).change();
								$("#cabine").val(result.cabine);
								$("#num_depart").val(result.num_depart);
								$("#num_poteau").val(result.num_poteau);
								$("#type_raccordement").val(result.type_raccordement).change();
								$("#type_cpteur_raccord").val(result.type_cpteur_raccord).change();
								$("#nbre_alimentation").val(result.nbre_alimentation);
								$("#section_cable_alimentation").val(result.section_cable_alimentation).change();
								$("#section_cable_alimentation_deux").val(result.section_cable_alimentation_deux).change();
								$("#section_cable_sortie").val(result.section_cable_sortie).change();
								$("#presence_inverseur").val(result.presence_inverseur).change();
								$("#marque_cpteur_replaced").val(result.marque_compteur_installed).change();
								$("#num_serie_cpteur_replaced").val(result.numero_compteur_installed);
								$("#nom_responsable_inst2").val(result.client.noms);
								$("#nomclientblue_").val(result.occupant.noms);
								$("#adresse_inst2").val(result.adresseTexte);


								$("#adresse_inst2").attr('adresse_id', result.data.adresse_id);
								$("#adresse_inst2").attr('adresseTexte', result.adresseTexte);

								$("#quartier_install2").val(result.data.quartier);
								$("#tarif_identif2").val(result.data.tarif_identif);
								$("#marque_cpteur_post_paie").val(result.data.marque_cpteur_post_paie);
								$("#num_serie_cpteur_post_paie").val(result.data.num_serie_cpteur_post_paie);
								$("#index_credit_restant_cpteur_post_paie").val(result.data.index_credit_restant_cpteur_post_paie);
								//$("#date_retrait_cpteur_post_paie").val(result.data.date_retrait_cpteur_post_paie);
								$("#post_paie_trouver").val(result.data.post_paie_trouver).change();

								$("#cvs_id_inst2").val(result.data.cvs_id).change();
								$("#cvs_id_inst2").attr('data_id', result.data.cvs_id);

								//$("#photo_pa_avant").attr('src','pictures/' + result.data.id_ +'.png');

								$.each(result.photos, function(i, item) {

									$('#photo_pa_list').append('<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" >' +
										'<div class="card">' +
										'<div class="card-body">' +
										'	<label></label>' +
										'	  ' +
										'<div class="input-group" style="width: 100%;"> ' +
										'	<img style="height:300px;" class="form-control pull-right"  src="pictures/' + item.ref_photo + '.png"> ' +
										'</div> ' +
										'</div>	' +
										'</div> ' +
										'</div>');
								});
								//        $("#phone_abonne").val(result.data.phone_client_blue);


								// $("#photo_pa_avant").attr('src', 'pictures/' + result.data.id_ + '.jpeg');
								//	'http://127.0.0.1:8080/blue-app/pictures/' + result.data.photo_pa_avant);

								$('#dlg_main-install').show();
							} else if (result.error == 1) {
								swal({
									title: "Information",
									text: result.message,
									type: "error",
									showCancelButton: false,
									confirmButtonColor: "#DD6B55",
									confirmButtonText: "Ok",
									closeOnConfirm: true,
									closeOnCancel: false
								}, function(isConfirm) {});
							}
						} catch (erreur) {
							swal({
								title: "Information",
								text: "Echec d'execution de la requete",
								type: "error",
								showCancelButton: false,
								confirmButtonColor: "#DD6B55",
								confirmButtonText: "Ok",
								closeOnConfirm: true,
								closeOnCancel: false
							}, function(isConfirm) {});
						}
					},
					error: function(resultat, statut, erreur) {
						//		$inputs.prop("disabled", false);
						swal({
							title: "Information",
							text: "Serveur non disponible",
							type: "error",
							showCancelButton: false,
							confirmButtonColor: "#DD6B55",
							confirmButtonText: "Ok",
							closeOnConfirm: true,
							closeOnCancel: false
						}, function(isConfirm) {});
					},
					complete: function(resultat, statut, erreur) {
						HideLoader();
					}

				});

			}




			<?php if ($utilisateur->HasDroits("10_545")) { ?>
				jQuery(document).delegate('a.approve-install', 'click', function(e) {
					e.preventDefault();
					$("#comment_").val('');
					CloseFiche();
					var jeton_actuel = jQuery(this).attr("data-id-install");
					var num_cpteur = jQuery(this).attr("data-compteur-install");
					$("#id_").val(jeton_actuel);
					$("#frm_approbation #view").val("create_install_approve");
					$("#bloc_installateur").hide();
					$("#btn_submit_cloture").hide();
					$("#btn_submit_approve").show();
					$("#approve_compteur").html(num_cpteur);
					$("#notification_title").text("Approbation Installation");
					$("#btn_submit_approve").text("Approuver");
					$("#box_approbation").show();
				});



				$('#btn_submit_approve').click(function() {
					var form = document.getElementById("frm_approbation");
					var formApprove = new FormData(form);
					$("#btn_submit_approve").attr('disabled', 'disabled');
					$("#btn_submit_approve").removeClass('btn-success');
					$("#btn_submit_approve").text("Approbation en cours ...");
					$.ajax({
						//enctype: 'multipart/form-data',
						url: "controller.php",
						data: formApprove, // Add as Data the Previously create formData
						type: "POST",
						contentType: false,
						processData: false,
						cache: false,
						dataType: "json", // Change this according to your response from the server.
						error: function(err) {
							console.error(err);
							$("#btn_submit_approve").removeAttr('disabled');
							$("#btn_submit_approve").addClass('btn-success');
							$("#btn_submit_approve").text("Approuver");
							swal({
								title: "Information",
								text: "Serveur non disponible",
								type: "error",
								showCancelButton: false,
								confirmButtonColor: "#DD6B55",
								confirmButtonText: "Ok",
								closeOnConfirm: true,
								closeOnCancel: false
							}, function(isConfirm) {});
						},
						success: function(result) {
							console.log(result);
							try {
								if (result.error == 0) {
									$("#btn_submit_approve").text("Approbation terminée.");
									swal({
										title: "Information",
										text: result.message,
										type: "success",
										showCancelButton: false,
										confirmButtonColor: "#00A65A",
										confirmButtonText: "Ok",
										closeOnConfirm: true,
										closeOnCancel: false
									}, function(isConfirm) {
										$("#box_approbation").hide();
										window.location.reload();
									});
								} else if (result.error == 1) {
									$("#btn_submit_approve").removeAttr('disabled');
									$("#btn_submit_approve").text("Approuver");
									$("#btn_submit_approve").addClass('btn-success');

									//swal("Information", result.message, "error");
									swal({
										title: "Information",
										text: result.message,
										type: "error",
										showCancelButton: false,
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "Ok",
										closeOnConfirm: true,
										closeOnCancel: false
									}, function(isConfirm) {});
								}
							} catch (erreur) {
								swal({
									title: "Information",
									text: "Echec d'execution de la requete",
									type: "error",
									showCancelButton: false,
									confirmButtonColor: "#DD6B55",
									confirmButtonText: "Ok",
									closeOnConfirm: true,
									closeOnCancel: false
								}, function(isConfirm) {});


								//$("#btn_submit_approve").attr('disabled','disabled');
								$("#btn_submit_approve").removeAttr('disabled');
								$("#btn_submit_approve").addClass('btn-success');
								$("#btn_submit_approve").text("Approuver");
							}
						},
						complete: function() {
							console.log("Request finished.");
						}
					});

				});





			<?php   }  ?>


			<?php if ($utilisateur->HasDroits("10_555")) { ?>
				jQuery(document).delegate('a.cloture-install', 'click', function(e) {
					e.preventDefault();
					$("#comment_").val('');
					$("#list_installateurs_secondaire").val([]).change();
					var jeton_actuel = jQuery(this).attr("data-id-install");
					var num_cpteur = jQuery(this).attr("data-compteur-install");
					$("#id_").val(jeton_actuel);
					$("#frm_approbation #view").val("create_install_cloture");
					$("#bloc_installateur").show();
					$("#btn_submit_cloture").show();
					$("#btn_submit_approve").hide();
					$("#approve_compteur").html(num_cpteur);
					$("#notification_title").text("Clôture Installation");
					$("#btn_submit_cloture").text("Clôturer");
					$("#box_approbation").show();
					CloseFiche();
				});



				$('#btn_submit_cloture').click(function() {
					var form = document.getElementById("frm_approbation");
					var formApprove = new FormData(form);
					$("#btn_submit_cloture").attr('disabled', 'disabled');
					$("#btn_submit_cloture").removeClass('btn-success');
					$("#btn_submit_cloture").text("Clôture en cours ...");
					$.ajax({
						//enctype: 'multipart/form-data',
						url: "controller.php",
						data: formApprove, // Add as Data the Previously create formData
						type: "POST",
						contentType: false,
						processData: false,
						cache: false,
						dataType: "json", // Change this according to your response from the server.
						error: function(err) {
							console.error(err);
							$("#btn_submit_cloture").removeAttr('disabled');
							$("#btn_submit_cloture").addClass('btn-success');
							$("#btn_submit_cloture").text("Clôturer");
							swal({
								title: "Information",
								text: "Serveur non disponible",
								type: "error",
								showCancelButton: false,
								confirmButtonColor: "#DD6B55",
								confirmButtonText: "Ok",
								closeOnConfirm: true,
								closeOnCancel: false
							}, function(isConfirm) {});
						},
						success: function(result) {
							console.log(result);
							try {
								if (result.error == 0) {
									$("#btn_submit_cloture").text("Clôture terminée.");
									swal({
										title: "Information",
										text: result.message,
										type: "success",
										showCancelButton: false,
										confirmButtonColor: "#00A65A",
										confirmButtonText: "Ok",
										closeOnConfirm: true,
										closeOnCancel: false
									}, function(isConfirm) {
										$("#box_approbation").hide();
										window.location.reload();
									});
								} else if (result.error == 1) {
									$("#btn_submit_cloture").removeAttr('disabled');
									$("#btn_submit_cloture").text("Clôturer");
									$("#btn_submit_cloture").addClass('btn-success');

									//swal("Information", result.message, "error");
									swal({
										title: "Information",
										text: result.message,
										type: "error",
										showCancelButton: false,
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "Ok",
										closeOnConfirm: true,
										closeOnCancel: false
									}, function(isConfirm) {});
								}
							} catch (erreur) {
								swal({
									title: "Information",
									text: "Echec d'execution de la requete",
									type: "error",
									showCancelButton: false,
									confirmButtonColor: "#DD6B55",
									confirmButtonText: "Ok",
									closeOnConfirm: true,
									closeOnCancel: false
								}, function(isConfirm) {});


								//$("#btn_submit_cloture").attr('disabled','disabled');
								$("#btn_submit_cloture").removeAttr('disabled');
								$("#btn_submit_cloture").addClass('btn-success');
								$("#btn_submit_cloture").text("Clôturer");
							}
						},
						complete: function() {
							console.log("Request finished.");
						}
					});

				});

			<?php   }  ?>

			<?php if ($utilisateur->HasDroits("10_800")) {
			?>

				function desaffect_compteur() {
					$('#desaffect-compteur').click(function(e) {
						event.preventDefault();
						event.stopPropagation()
						var name_actuel = $(this).attr("data-name-install");
						var jeton_actuel = $(this).attr("data-id-install");
 
						var view_mode = "desaffect_compteur_in_installation";
						$.ajax({
							url: "controller.php",
							method: "GET",
							data: {
								view: view_mode,
								q: jeton_actuel
							},
							beforeSend: function() {
								ShowLoader("Le compteur est en cours de désaffectation...");
							},
							success: function(data) {
								try {
									var result = $.parseJSON(data);
									if (result.error == 0) {
										swal("Information", result.message, "success");
										CloseFiche()
										var show_ = $("#show").val();
										displayRecords(show_, 1, '');
									} else if (result.error == 1) {
										swal("Information", result.message, "error");
									}
								} catch (erreur) {}
							},
							complete: function() {
								HideLoader();
							}
						});
					});
				}
				// $('.delete').click(function () {
				jQuery(document).delegate('a.view-fiche', 'click', function(e) {
					e.preventDefault();
					var name_actuel = jQuery(this).attr("data-name");
					var jeton_actuel = jQuery(this).attr("data-id");
					var view_mode = "visualiser_fiche_installation";
					$.ajax({
						url: "controller.php",
						method: "GET",
						data: {
							view: view_mode,
							q: jeton_actuel
						},
						beforeSend: function() {
							ShowLoader("Chargement de la Fiche en cours...");
						},
						success: function(data) {
							try {
								var result = $.parseJSON(data);
								if (result.error == 0) {
									$("#fiche_viewer_title").html('VISUALISATION FICHE INSTALLATION');
									$("#fiche_viewer").html(result.data);
									ShowFiche();
								} else if (result.error == 1) {
									swal("Information", result.message, "error");
								}
							} catch (erreur) {}
						},
						complete: function() {
							desaffect_compteur()
							HideLoader();
						}
					});
				});
			<?php } ?>


			<?php if ($utilisateur->HasDroits("10_70") || $utilisateur->HasDroits("10_550")) { ?>

				jQuery(document).delegate('a.edit-install', 'click', function(e) {
					e.preventDefault();
					ClearForm_install();
					ClearMaterielsRow();
					CloseFiche();


					ShowLoader("Veuillez patienter ...");
					var jeton_actuel = jQuery(this).attr("data-id-install");
					$('#Heading-install').html('MODIFICATION INFORMATIONS INSTALLATION');
					$('#btn_save_install').find('span.sn').html('Valider');
					$.ajax({
						url: "controller.php",
						dataType: "json",
						method: "GET",
						data: {
							view: 'detail_install',
							k: jeton_actuel
						},
						success: function(result, statut) { // success est toujours en place, bien sûr !
							try {
								//var result = $.parseJSON(data);
								if (result.error == 0) {
									$('#doc_save_mode').html('');
									if (result.data.is_draft_install == '1') {
										$('#add_line').show();
										$('#doc_save_mode').append('<option selected="" value="">Choisir mode de sauvegarde </option>');
										$('#doc_save_mode').append('<option value="1">Brouillon</option>');
										$('#doc_save_mode').append('<option value="0">Définitive</option>');
									} else {
										$('#add_line').hide();
										$('#doc_save_mode').append('<option value="0">Définitive</option>');
										$('#doc_save_mode').val('0').change();
									}
									/*if(result.readOnly == 1){									 
									 $("#btn_save_install").hide();									 
								 }else{									 
									 $("#btn_save_install").show();
								 }*/

									if (result.data.type_installation == '0') {
										hideReplaceBlock();
										showBlockPostPaie();
									} else {
										showReplaceBlock();
										hideBlockPostPaie();
									}

									if (result.data.gps_longitude != null && result.data.gps_latitude != null) {
										$("#btn_map_viewer").attr('data-lat', result.data.gps_latitude);
										$("#btn_map_viewer").attr('data-lng', result.data.gps_longitude);
										//$("#btn_map_viewer").text('Visualiser Carte');
										$("#btn_map_viewer").removeAttr('disabled');
										$("#btn_map_viewer").show();
										//$("#btn_map_viewer").removeClass('btn-outline-danger');
										//$("#btn_map_viewer").addClass('btn-outline-light');

									} else {

										$("#btn_map_viewer").hide();
										//$("#btn_map_viewer").removeClass('btn-outline-light');
										//$("#btn_map_viewer").addClass('btn-outline-danger');
										//$("#btn_map_viewer").text('Pas de coordonnées GPS');
										//$("#btn_map_viewer").attr('disabled','disabled');

									}

									$("#marque_cpteur_replaced").val(result.data.marque_cpteur_replaced).change();
									$("#index_credit_restant_cpteur_replaced").val(result.data.index_credit_restant_cpteur_replaced);
									$("#num_serie_cpteur_replaced").val(result.data.num_serie_cpteur_replaced);
									$("#type_defaut").val(result.data.type_defaut).change();

									$("#dlg_main-install #view").val("edit_install");
									$("#id_install").val(result.data.id_install);
									//$("#date_fin_installation").val(result.data.date_fin_installation_fr);
									$("#nom_responsable_inst2").val(result.data.nom_occupant);
									$("#nomclientblue_").val(result.data.nom_client_blue);
									$("#adresse_inst2").val(result.adresseTexte);

									$("#date_identification_inst2").val(result.data.date_identification_fr);
									$("#adresse_inst2").attr('adresse_id', result.data.adresse_id);
									$("#adresse_inst2").attr('adresseTexte', result.adresseTexte);

									$("#quartier_install2").val(result.data.quartier);
									$("#cvs_id_inst2").val(result.data.cvs_id).change()
									$("#cvs_id_inst2").attr('data_id', result.data.cvs_id);

									$("#ref_identific").val(result.data.ref_identific);
									$("#verify_fiche_identif").val(result.data.ref_identific);
									$("#p_a_inst2").val(result.data.p_a);
									$("#tarif_identif2").val(result.data.tarif_identif);
									$("#cabine").val(result.data.cabine);
									$("#num_depart").val(result.data.num_depart);
									$("#num_poteau").val(result.data.num_poteau);
									$("#type_cpteur_raccord").val(result.data.type_cpteur_raccord).change();
									$("#type_raccordement").val(result.data.type_raccordement).change();
									$("#nbre_alimentation").val(result.data.nbre_alimentation);
									$("#section_cable_alimentation").val(result.data.section_cable_alimentation).change();
									$("#section_cable_alimentation_deux").val(result.data.section_cable_alimentation_deux).change();
									$("#section_cable_sortie").val(result.data.section_cable_sortie).change();
									$("#presence_inverseur").val(result.data.presence_inverseur).change();
									$("#post_paie_trouver").val(result.data.post_paie_trouver).change();
									$("#marque_cpteur_post_paie").val(result.data.marque_cpteur_post_paie);
									$("#num_serie_cpteur_post_paie").val(result.data.num_serie_cpteur_post_paie);
									$("#num_serie_cpteur_post_paie").val(result.data.num_serie_cpteur_post_paie);
									$("#index_credit_restant_cpteur_post_paie").val(result.data.index_credit_restant_cpteur_post_paie);
									//$("#date_retrait_cpteur_post_paie").val(result.data.date_retrait_cpteur_post_paie);
									$("#marque_compteur").val(result.data.marque_compteur).change();
									$("#index_par_defaut").val(result.data.index_par_defaut);
									$("#numero_compteur").val(result.data.numero_compteur);
									$("#type_new_cpteur").val(result.data.type_new_cpteur).change();
									$("#disjoncteur").val(result.data.disjoncteur);
									$("#replace_client_disjonct").prop('checked', result.data.replace_client_disjonct == "on" ? true : false);

									$("#client_disjonct_amperage").val(result.data.client_disjonct_amperage);
									$("#is_autocollant_posed").val(result.data.is_autocollant_posed).change();
									/*$("#statut_installation").val(result.data.statut_installation).change();
									
									if(result.data.statut_installation == '1'){ 
										$("#statut_installation").prop('disabled', true);
									} */




									$("#code_tarif").val(result.data.code_tarif).change();
									$("#scelle_un_cpteur").val(result.data.scelle_un_cpteur);
									$("#scelle_deux_coffret").val(result.data.scelle_deux_coffret);
									//$("#date_pose_scelle").val(result.data.date_pose_scelle);
									$("#usage_electricity").val(result.data.usage_electricity).change();
									$("#etat_poc").val(result.data.etat_poc).change();
									$("#id_equipe").val(result.data.id_equipe).change();
									$("#etat_compteur_reaffected").val(result.data.etat_compteur_reaffected).change();
									$("#commentaire_installateur").val(result.data.commentaire_installateur);
									//$("#commenteur_controle_blue").val(result.data.commenteur_controle_blue);
									$("#chef_equipe_install").val(result.data.chef_equipe).change();
									actual_chief = result.data.chef_equipe;
									$("#installateur").val(result.data.installateur).change();

									//$("#photo_pa_avant").attr('src','pictures/' + result.data.ref_identific +'.png');
									$.each(result.photos, function(i, item) {

										$('#photo_pa_list').append('<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" >' +
											'<div class="card">' +
											'<div class="card-body">' +
											'	<label></label>' +
											'	  ' +
											'<div class="input-group" style="width: 100%;"> ' +
											'	<img style="height:300px;" class="form-control pull-right"  src="pictures/' + item.ref_photo + '.png"> ' +
											'</div> ' +
											'</div>	' +
											'</div> ' +
											'</div>');
									});
									$("#photo_compteur_post_paie").attr('src', 'pictures/' + result.data.id_install + '_INST_POST.png');
									$("#photo_compteur").attr('src', 'pictures/' + result.data.id_install + '_INST_CTR.png');
									$("#photo_avant_install").attr('src', 'pictures/' + result.data.id_install + '_INST_BFR.png');
									$("#photo_apres_install").attr('src', 'pictures/' + result.data.id_install + '_INST_AFT.png');
									$("#photo_sceller_un").attr('src', 'pictures/' + result.data.id_install + '_INST_SC1.png');
									$("#photo_sceller_deux").attr('src', 'pictures/' + result.data.id_install + '_INST_SC2.png');
									$("#photo_compteur_defectueux").attr('src', 'pictures/' + result.data.id_install + '_INST_DFT.png');
									//	'http://127.0.0.1:8080/blue-app/pictures/' + result.data.photo_pa_avant);
									//$("#nbre_branchement").val(result.data.nbre_branchement);
									//$("#section_cable").val(result.data.section_cable); 

									var lignes_install = $('#lignes_install tbody');
									var Id;
									var edit_options = "";


									if (result.data.approbation_installation == "0") {
										if (result.data.statut_installation == "0") {
											edit_options = '<a class="btn btn-xs edit-install-item"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-xs delete-install-item"><i class="fas fa-trash"></i></a>';
											$('#add_line_install').show();

										} else {
											<?php if ($utilisateur->HasDroits("10_960")) {
											?>
												edit_options = '<a class="btn btn-xs edit-install-item"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-xs delete-install-item"><i class="fas fa-trash"></i></a>';
												$('#add_line_install').hide();

											<?php } ?>
										}
									} else {
										$('#add_line_install').show();
									}
									$.each(result.items, function(i, item) {
										Id = generateItemID(lignes_install);
										lignes_install.append('<tr class="item-row-install" item-id="item-' + Id + '" data-id-install="' + Id + '" materiel-id="' + item.ref_article + '"><td style="width:5%"><span class="n"></span></td><td style="width:80%"><span class="sn">' + item.designation + '</span></td><td><span class="qte">' + item.qte_identification + '</span></td><td>' + edit_options + '</td></tr>');
										// item.approbation_installation				

										//lignes_install.append('<tr class="item-row-install" item-id="item-'+Id+'" data-id-install="'+Id+'" materiel-id="'+item.ref_article+'"><td style="width:5%"><span class="n"></span></td><td style="width:80%"><span class="sn">'+item.designation+'</span></td><td><span class="qte">'+item.qte_identification+'</span></td><td><a class="btn btn-xs edit-install-item"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-xs delete-install-item"><i class="fas fa-trash"></i></a></td></tr>');

									});
									reOrderRow_install();
									ShowMain();
									HideLoader();
								} else if (result.error == 1) {
									swal({
										title: "Information",
										text: result.message,
										type: "error",
										showCancelButton: false,
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "Ok",
										closeOnConfirm: true,
										closeOnCancel: false
									}, function(isConfirm) {});
								}
							} catch (erreur) {
								console.log(erreur);
								HideLoader();
								swal({
									title: "Information",
									text: "Echec d'execution de la requete",
									type: "error",
									showCancelButton: false,
									confirmButtonColor: "#DD6B55",
									confirmButtonText: "Ok",
									closeOnConfirm: true,
									closeOnCancel: false
								}, function(isConfirm) {});
							}
						},
						error: function(resultat, statut, erreur) {

							HideLoader();
							//		$inputs.prop("disabled", false);
							swal({
								title: "Information",
								text: "Serveur non disponible",
								type: "error",
								showCancelButton: false,
								confirmButtonColor: "#DD6B55",
								confirmButtonText: "Ok",
								closeOnConfirm: true,
								closeOnCancel: false
							}, function(isConfirm) {});
						},
						complete: function() {
							HideLoader();
						}
					});
				});
			<?php   }  ?>
		})
	</script>
	<!--  <script type="text/javascript" src="assets/js/webcam.min.js"></script>  -->
	<script type="text/javascript" src="assets/js/WebcamEasyNew.js"></script>
	<script src="assets/js/parsley.js"></script>


	<!-- Configure a few settings and attach camera -->
	<script language="JavaScript">
		<?php //if($MobileRun != "1"){ 
		?>
		// Configure a few settings and attach camera
		const web_install_camElement = document.getElementById('webcam_install');
		const canvasElement_install = document.getElementById('canvas_install');
		const snapSoundElement_install = document.getElementById('snapSound');

		const webcam = new Webcam(web_install_camElement, 'user', canvasElement_install, snapSoundElement_install);

		//  Webcam.init(web_install_camElement, 'user', canvasElement_install, snapSoundElement_install);

		<?php //} 
		?>



		// preload shutter audio clip
		/*var shutter = new Audio();
		shutter.autoplay = true;
		shutter.src = navigator.userAgent.match(/Firefox/) ? 'shutter.ogg' : 'shutter.mp3';
		*/
		function take_snapshot_install() {

			webcam.snap();
			//	$("#photo_compteur").prop("src", canvasElement_install.toDataURL("image/png"));
			var bloc_destination = $("#bloc_destination").val();
			$('#' + bloc_destination).prop("src", canvasElement_install.toDataURL("image/png"));
			webcam.stop();
			$("#camera_shooter_install").hide();
		}
	</script>

	<script type="text/javascript">
		// fetching records
		function displayRecords(numRecords, pageNum, v_mode) {
			var s = $('#srch-term').val() != null ? $('#srch-term').val() : null;
			var du = $('#Du').val() != null ? $('#Du').val() : null;
			var au = $('#Au').val() != null ? $('#Au').val() : null;
			var filtre = $('#filtre').val() != null ? $('#filtre').val() : null;
			$.ajax({
				type: "GET",
				url: "controller.php",
				data: "view=search_view_installation&show=" + numRecords + "&page=" + pageNum + "&Du=" + du + "&Au=" + au + "&s=" + s + "&view_mode=" + v_mode + "&filtre=" + filtre,
				cache: false,
				dataType: "json",
				beforeSend: function() {
					// $('.loader').html('<img src="loading.gif" alt="" width="24" height="24" style="padding-left: 400px; margin-top:10px;" >');
					// $("#overlay").show();
					$("#loader").attr("data-text", "Chargement des données en cours...");
					$("#loader").addClass("is-active");
				},
				success: function(result) {
					if (result.count > 0) {
						$("#results").html(result.data);
						$("#record_count").html(result.count + " Elément(s)");
					} else {
						var need_reconnect = result.reconnect != null ? result.reconnect : false;
						if (need_reconnect == true) {
							Reconnect();
						} else {
							$("#record_count").html("0 Elément(s)");
							$("#results").html('<div class="card alert-danger"><div class="card-body"><div role="alert" class="text-center"><h1 class="alert-heading">Aucune information trouvée</h1></div></div></div>');
						}
					}
				},
				error: function(erreur) {

					swal("Erreur", "Serveur non  disponible", "error");
				},
				complete: function() {
					$("#loader").removeClass("is-active");
				}
			});
		}

		// used when user change row limit
		/* function checkSearchParam() {
							 var s = $('#srch-term').val().length>0?$('#srch-term').val():null;
								 var du = $('#Du').val().length>0?$('#Du').val():null;
								 var au = $('#Au').val().length>0?$('#Au').val():null; 
								 if(s == null && du == null && au == null){
									 v_mode = '';
								 }else if(s != null && du != null && au != null){
									  v_mode = 'advanced_search'; 									  
								 }else if(s == null && du != null && au != null){
									  v_mode = 'date_only'; 
								 }else if(s != null && du == null && au == null){
									  v_mode = 'search'; 
								 }
							
							}*/
		function changeDisplayRowCount(numRecords) {

			var v_mode = '';
			var s = $('#srch-term').val().length > 0 ? $('#srch-term').val() : null;
			var du = $('#Du').val().length > 0 ? $('#Du').val() : null;
			var au = $('#Au').val().length > 0 ? $('#Au').val() : null;
			var filtre = $('#filtre').val().length > 0 ? $('#filtre').val() : null;
			if (s == null && du == null && au == null) {
				v_mode = '';
			} else if (s != null && du != null && au != null) {
				v_mode = 'advanced_search';
			} else if (s == null && du != null && au != null) {
				v_mode = 'date_only';
			} else if (s != null && du == null && au == null) {
				v_mode = 'search';
			} else if (s == null && (du == null || au == null)) {
				swal("Information", "Veuillez préciser les paramètres de recherche", "error");
				return false;
			}
			displayRecords(numRecords, 1, v_mode);
		}

		function LoadALL() {
			var show = $("#show").val();
			$('#srch-term').val('');
			$('#Du').val('');
			$('#Au').val('');
			$('#filtre').val('').change();
			displayRecords(show, 1, 'all');
		}
		<?php //if($MobileRun == "1"){ 
		?>

		/*
		 function readURL(input) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();

		        reader.onload = function (e) {
					var img_id = Date.now();
					var photo_pa_list = $('#photo_pa_list');	
														    photo_pa_list.append('<div class="photo-item col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" bloc-photo-id="' + img_id + '">'+
															'<div class="card">' + 
																'<div class="card-body">' + 												   
																'	<label></label>' + 
																'	<a class="btn btn-outline-light float-right delete-pa-photo">supprimer photo</a>  ' + 
																	'<div class="input-group" style="width: 100%;"> ' + 
																	'	<img style="height:300px;" class="form-control pull-right" name="photo_pa_avant[]" src="' + e.target.result + '" id="pa_pic_' + img_id +'"> ' + 
																	'</div> ' +                
																'</div>	' + 													 
															'</div> ' + 
														'</div>');
		        
		        }

		        reader.readAsDataURL(input.files[0]);
		    }
		}
		$("#file-input").change(function(){
		    readURL(this);
		}); */
		function previewFile(input, bloc_destination) {

			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$('#' + bloc_destination).prop("src", e.target.result);
				}

				reader.readAsDataURL(input.files[0]);
			}
		}
		<?php //} 
		?>




		$(document).ready(function() {

			$("#div_section_cable_alimentation_deux").hide();
			$("#section_cable_alimentation_deux").prop("required", false);

			var show = $("#show").val();
			displayRecords(show, 1, '');

			jQuery(document).delegate('a.page-link', 'click', function(e) {
				e.preventDefault();
				var page = jQuery(this).attr("data-page");
				var view_mode = jQuery(this).attr("view-mode");
				var show = $("#show").val();
				displayRecords(show, page, view_mode);

			});


			$('#search-btn').click(function() {
				var v_mode = '';
				var show = $("#show").val();
				var filtre = $('#filtre').val().length > 0 ? $('#filtre').val() : null;
				var s = $('#srch-term').val().length > 0 ? $('#srch-term').val() : null;
				var du = $('#Du').val().length > 0 ? $('#Du').val() : null;
				var au = $('#Au').val().length > 0 ? $('#Au').val() : null;
				if (s == null && du == null && au == null && filtre == null) {
					swal("Information", "Veuillez préciser les paramètres de recherche", "error");
					return false;
				} else if (s != null && du != null && au != null) {
					v_mode = 'advanced_search';

				} else if (s == null && du != null && au != null) {
					v_mode = 'date_only';
				} else if (s != null && du == null && au == null) {
					v_mode = 'search';
				} else if (filtre != null) {
					v_mode = 'all';
				} else if (s == null && (du == null || au == null)) {
					swal("Information", "Veuillez préciser les paramètres de recherche", "error");
					return false;
				}

				displayRecords(show, 1, v_mode);

			});

			$('.view-all').click(function(e) {
				e.preventDefault();

				LoadALL();

			});

			$("#doc_save_mode").on("change", function(e) {
				var item = $(this).val();
				if (item != null) //&& item.length == 0)
				{
					if (item == "1") {
						// $("#marque_compteur").attr('required', false);//Non modifiable
						$("#type_raccordement").attr('required', false);
						$("#nbre_alimentation").attr('required', false);
						$("#section_cable_alimentation").attr('required', false);
						$("#section_cable_sortie").attr('required', false);
						//$("#post_paie_trouver").attr('required', false);
						$("#usage_electricity").attr('required', false);
						$("#etat_poc").attr('required', false);
						$("#commentaire_installateur").attr('required', false);
					} else if (item == "0") {
						// $("#marque_compteur").attr('required', false);//Non modifiable
						$("#type_raccordement").attr('required', true);
						$("#nbre_alimentation").attr('required', true);
						$("#section_cable_alimentation").attr('required', true);
						$("#section_cable_sortie").attr('required', true);
						var opera_tion = $("#mainForm_install #view").val();
						if (opera_tion == 'create_install_rpl') {
							$("#post_paie_trouver").attr('required', false);
						}

						$("#usage_electricity").attr('required', true);
						$("#etat_poc").attr('required', true);
						$("#commentaire_installateur").attr('required', true);
					}


				}
				// e.preventDefault();	  
			});
		});
	</script>




</body>

<?php

$db = null;
?>

</html>