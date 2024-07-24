<?php

/*
ALTER TABLE `blue_app`.`t_utilisateurs` 
ADD COLUMN `access_au_module_deux` int(1) NULL DEFAULT 0 AFTER `id_organisme_chief`;

*/
// session_start();
$mnu_title = "Contrôles";
$page_title = "Liste des contrôles";
$home_page = "dashboard.php";
$active = "lst_control";
$parambase = "";
header('Content-type: text/html;charset=utf-8');

require_once 'vendor/autoload.php';
require_once 'vendor/autoload.php';
require_once 'loader/init.php';
Autoloader::Load('classes');
include_once 'core.php';

$database = new Database();
$db = $database->getConnection();
$Abonne = new Identification($db);
$controle = new CLS_Controle($db);
$utilisateur = new Utilisateur($db);
$organisme = new Organisme($db);
$commune = new AdresseEntity($db);
$marquecompteur = new MarqueCompteur($db);
$cvs = new CVS($db);
$materiel = new Materiels($db);
$typeFraude = new PARAM_TypeFraude($db);
$typeObservation = new PARAM_TypeObservation($db);
$etat_interrupteur = new PARAM_Etat_Interrupteur($db);
$indicateur_led = new PARAM_Indicateur_led($db);


$accessib = new Param_Accessibility($db);
$raccordement = new Param_Raccordement($db);
$type_compteur = new Param_TypeCompteur($db);
$type_usage = new Param_TypeUsage($db);

$section_cable = new PARAM_Section_Cable($db);



$etat_poc = new PARAM_EtatPOC($db);
$statut_control = new PARAM_StatutInstallation($db);
$type_client = new TypeClient($db);
$yes_no = new PARAM_YesNo($db);
$presence = new PARAM_Presence($db);
$conformity_control = new PARAM_ConformityInstall($db);
$tarif = new Tarif($db);
//$statut_personne = new Param_Statut_Personne($db); 
$p_wifi = new PARAM_WIFI_CPL($db);
$type_conclusion = new Param_Conclusion($db);

$site = new Site($db);
$province = new AdresseEntity($db);

$page_c = 'lst_control.php';
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

		.cursor-pointer {
			cursor: pointer;
		}


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
	<script src="assets/js/leaflet.js"></script>
	<?php
	include_once "layout_style.php";
	?>

</head>

<body>


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
								<a href="<?php echo $page_c; ?>" class="breadcrumbs_home"><i class='fas fa-handshake nav_icon'></i> <?php echo $mnu_title; ?></a> <span class="raquo">»</span>
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
										<div class="h5 font-weight-bold text-primary"> Journal des contrôles</div>
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
										<div class="form-group text-left mb-0 mt-1" id='bloc_installateur'>
											<select class='form-control select2' style='width: 100%;' id='filtre' name='filtre[]' required multiple="multiple">
												<option value="t_log_controle.cas_de_fraude='Oui'">Cas Fraude</option>
												<?php


												$stmt_chief = null;
												if ($utilisateur->id_service_group ==  '3') {  //Administration
													$stmt_chief = $utilisateur->GetAllControleur();
												} else {
													$stmt_chief = $utilisateur->GetCurrentUserListIdentificateurs($utilisateur->code_utilisateur, $utilisateur->id_organisme, $utilisateur->is_chief);
												}

												while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
													echo "<option value=t_log_controle.controleur='" . $row_chief["code_utilisateur"] .  "'>Contrôleur - " . $row_chief["nom_complet"];
													"</option>";
												}

												$stmt_chief = null;
												if ($utilisateur->id_service_group ==  '3') {  //Administration
													$stmt_chief = $utilisateur->GetAllChiefForAdmin();
												} else {
													$stmt_chief = $utilisateur->GetCurrentUserChief($utilisateur);
												}

												while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
													echo "<option value=t_log_controle.chef_equipe_control='" . $row_chief["code_utilisateur"] .  "'>Chef-Equipe - " . $row_chief["nom_complet"] . "</option>";
												}

												$stmt_select = $province->getAllProvinces();
												$provinces = $stmt_select->fetchAll(PDO::FETCH_ASSOC);


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
													echo "<option value=t_log_controle.ref_site_controle='" . $row_select["code"] . "'>Site - " . $row_select["libelle"] . "</option>";
												}


												if ($utilisateur->id_service_group ==  '3' || $utilisateur->HasGlobalAccess()) {  //Administration
													$stmt_ = $organisme->read();
													while ($row_gp = $stmt_->fetch(PDO::FETCH_ASSOC)) {
														echo "<option value=id_organisme_control='{$row_gp["ref_organisme"]}'>Organisme - {$row_gp["denomination"]}</option>";
													}
												} else {
													$organisme->ref_organisme = $utilisateur->id_organisme;
													$row_gp = $organisme->GetDetail();
													echo "<option value=id_organisme_control='{$row_gp["ref_organisme"]}'>Organisme - {$row_gp["denomination"]}</option>";
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
						<div id="maps"></div>
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
	if ($utilisateur->HasDroits("10_110")) {

		echo  '<div class="btn-group-fab" role="group" aria-label="FAB Menu">
	  <div>
		<button type="button" class="btn btn-main btn-primary has-tooltip" data-placement="left" title="Menu" id="btn_new_"> <i class="fa fa-plus"></i> </button>
	  </div>
	</div>';
	}
	include_once "layout_script.php";
	//display: <?php echo $diplay_modal;? >;
	?>

	<div class="modal" id="dlg_main-control" tabindex="-1" role="dialog" aria-labelledby="edit-control" aria-hidden="true" data-backdrop="static" style="padding-right: 17px; overflow: scroll;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<form id="mainForm_control" method="post" enctype="multipart/form-data">
					<div class="modal-header">
						<h5 class="modal-title" id="titre_control"></h5>
						<a href="#" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</a>
					</div>
					<div class="modal-body">
						<input name="id_control" id="id_control" type="hidden">

						<input name="view" id="view_control" type="hidden">
						<input name="id_assign" id="id_assign" type="hidden">
						<!-- ============================================================== -->
						<!-- Information CLIENT form -->
						<!-- ============================================================== -->
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="card">
								<h5 class="card-header">INFORMATIONS GENERALES</h5>
								<div class="row">

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">

												<div class="form-group">
													<label>N° COMPTEUR</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" id="num_compteur_actuel" disabled>
													</div>
												</div>
												<!-- <div class="form-group">
												<label>REFERENCE FICHE D'IDENTIFICATION</label>
												<div class="input-group"  style="width: 100%;" >  -->
												<input type="hidden" class="form-control pull-right" name="ref_identific" id="ref_identific" readOnly>
												<!--	</div>                
											</div> -->
												<div class="form-group">
													<label>NOM DU PROPRIETAIRE (Identité sur la facture SNEL)</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" id="nom_responsable_inst" disabled>
													</div>
												</div>
												<div class="form-group">
													<label>NOM DU LOCATAIRE (ou Ménage à connecté)</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" id="nom_abonne" disabled>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>DATE INSTALLATION</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" id="date_identification_inst" disabled>
													</div>
												</div>
												<div class="form-group">
													<label>ADRESSE</label>
													<div class="input-group" style="width: 100%;">
														<textarea class="form-control pull-right" id="adresse_inst" disabled></textarea>
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
													<a class="btn btn-outline-light float-right" id="btn_map_viewer" data-toggle="modal" data-target="#myModalLeaflet" data-lat='' data-lng=''><i class="fas fa-map"></i> Visualiser Carte</a>
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


														<select class='form-control select2' style='width: 100%;' id='cvs_id_inst' disabled>
															<option selected='selected'>Non défini</option>
															<?php
															$stmt_select_st = $cvs->read();
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
									<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>P.A</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" id="p_a_inst" disabled>
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
														<input type="text" class="form-control pull-right" id="tarif_identif" disabled>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!--	   <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
									<div class="card">
										<div class="card-body">  
											<div class="form-group"> 
												<label>MARQUE COMPTEUR</label>
												<div class="input-group"  style="width: 100%;" > 
													<input type="text" class="form-control pull-right" id="marque_compteur_inst" disabled>
												</div>                
											</div> 
										</div>
									</div>
								</div>  -->
								</div>

							</div>
						</div>
						<!-- ============================================================== -->
						<!-- end Information CLIENT form -->
						<!-- ============================================================== -->

						<!-- ============================================================== -->
						<!-- Information NOUVEAU COMPTEUR form -->
						<!-- ============================================================== -->
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="card">
								<h5 class="card-header">INFORMATIONS SUR LE COMPTEUR</h5>
								<div class="row">

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>PRESENCE COMPTEUR</label>
													<div class="input-group" style="width: 100%;">
														<select class='form-control select2' style='width: 100%;' name='presence_inverseur' id='presence_inverseur' required>
															<option selected='selected' disabled> </option>
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
													<label>CLAVIER DEPORTE</label>
													<select class='form-control select2' style='width: 100%;' name='clavier_deporter' id='clavier_deporter' required>
														<option selected='selected' disabled> </option>
														<?php
														$stmt_presence = $presence->read();
														while ($row_gp = $stmt_presence->fetch(PDO::FETCH_ASSOC)) {
															echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
														}
														?>
													</select>
												</div>
												<div class="form-group">
													<label>NUMERO DE SERIE</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" disabled class="form-control pull-right allow-numeric" name="numero_serie_cpteur" id="numero_serie_cpteur" required>
														<div class="input-group-text" id="add_on_du"><a id="verify_compteur" href="#" class="icon"><i class="fas fa-search"></i></a>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label>MARQUE DU COMPTEUR</label>
													<div class="input-group" style="width: 100%;">
														<select class='form-control select2' style='width: 100%;' name='marque_compteur' id='marque_compteur' required>
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
													<label>TYPE COMPTEUR</label>
													<select class='form-control select2' style='width: 100%;' name='type_cpteur' id='type_cpteur' required>
														<option selected='selected' disabled> </option>
														<?php
														$stmt_tarif = $type_compteur->read();
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
													<label>PHOTO COMPTEUR</label>
													<?php if (Utils::IsWebView($_SERVER)) {  ?>

														<div class="image-upload">
															<label for="file-input">
																<img id="previewImg" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label>
															<input id="file-input" type="file" onchange="previewFile(this,'photo_compteur');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>
													<?php } else {
													?>

														<a class="btn btn-outline-light float-right" id="btn_capture_control">Capturer photo</a>
														<?php if ($utilisateur->access_au_module_deux == '1') {			?>
															<div class="image-upload">
																<label for="file-input">
																	<img id="previewImg" src="image/camera.jpg" style="width: 25px; height: 25px;" />
																</label>
																<input id="file-input" type="file" onchange="previewFile(this,'photo_compteur');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
															</div>

													<?php  }
													} ?>
													<div class="input-group" style="width: 100%;">
														<img style="height:300px;" class="form-control pull-right" id="photo_compteur" />
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" id="bloc_photo_avant_ctl">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>PHOTO AVANT CONTROLE</label>




													<?php if (Utils::IsWebView($_SERVER)) { ?>
														<div class="image-upload">
															<label for="file-input_avant_control">
																<img id="previewImg" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label>
															<input id="file-input_avant_control" type="file" onchange="previewFile(this,'photo_avant_control');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>
													<?php } else { ?>
														<a class="btn btn-outline-light float-right" id="btn_capture_avant_control">Capturer photo</a>

														<?php

														if ($utilisateur->access_au_module_deux == '1') {		?>
															<div class="image-upload">
																<label for="file-input_avant_control">
																	<img id="previewImg" src="image/camera.jpg" style="width: 25px; height: 25px;" />
																</label>
																<input id="file-input_avant_control" type="file" onchange="previewFile(this,'photo_avant_control');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
															</div>

													<?php }
													} ?>



													<div class="input-group" style="width: 100%;">
														<img style="height:300px;" class="form-control pull-right" id="photo_avant_control" />
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" id="bloc_photo_after_ctl">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>PHOTO APRES CONTROLE</label>
													<?php if (Utils::IsWebView($_SERVER)) { ?><div class="image-upload">
															<label for="file-input_apres_control">
																<img id="previewImg" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label>
															<input id="file-input_apres_control" type="file" onchange="previewFile(this,'photo_apres_control');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>

													<?php } else { ?>

														<a class="btn btn-outline-light float-right" id="btn_capture_apres_control">Capturer photo</a>
														<?php

														if ($utilisateur->access_au_module_deux == '1') {		?>
															<div class="image-upload">
																<label for="file-input_apres_control">
																	<img id="previewImg" src="image/camera.jpg" style="width: 25px; height: 25px;" />
																</label>
																<input id="file-input_apres_control" type="file" onchange="previewFile(this,'photo_apres_control');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
															</div>

													<?php }
													} ?>


													<div class="input-group" style="width: 100%;">
														<img style="height:300px;" class="form-control pull-right" id="photo_apres_control" />
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
																<img id="previewImg" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label>
															<input id="file-input_sceller_un" type="file" onchange="previewFile(this,'photo_sceller_un');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>
													<?php } else { ?>
														<a class="btn btn-outline-light float-right" id="btn_capture_sceller_un">Capturer photo</a>

														<?php
														if ($utilisateur->access_au_module_deux == '1') {	 ?>
															<div class="image-upload">
																<label for="file-input_sceller_un">
																	<img id="previewImg" src="image/camera.jpg" style="width: 25px; height: 25px;" />
																</label>
																<input id="file-input_sceller_un" type="file" onchange="previewFile(this,'photo_sceller_un');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
															</div>

													<?php }
													} ?>


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
																<img id="previewImg" src="image/camera.jpg" style="width: 25px; height: 25px;" />
															</label>
															<input id="file-input_sceller_deux" type="file" onchange="previewFile(this,'photo_sceller_deux');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
														</div>
													<?php } else { ?>

														<a class="btn btn-outline-light float-right" id="btn_capture_sceller_deux">Capturer photo</a>
														<?php
														if ($utilisateur->access_au_module_deux == '1') {	?>
															<div class="image-upload">
																<label for="file-input_sceller_deux">
																	<img id="previewImg" src="image/camera.jpg" style="width: 25px; height: 25px;" />
																</label>
																<input id="file-input_sceller_deux" type="file" onchange="previewFile(this,'photo_sceller_deux');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
															</div>

													<?php }
													} ?>


													<div class="input-group" style="width: 100%;">
														<img style="height:300px;" class="form-control pull-right" id="photo_sceller_deux" />
													</div>
												</div>
											</div>
										</div>
									</div>


									<!-- ============================================================== -->
									<!-- Information DERNIERS SCELLES -->
									<!-- ============================================================== -->
									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">

												<div class="form-group">
													<label>DERNIER SCELLE COFFRET</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="dernier_sceller_coffret" id="dernier_sceller_coffret" readOnly>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>DERNIER SCELLE COMPTEUR</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="dernier_sceller_compteur" id="dernier_sceller_compteur" readOnly>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- ============================================================== -->
									<!-- end Information DERNIERS SCELLES  -->
									<!-- ============================================================== -->

									<!-- ============================================================== -->
									<!-- Information SCELLES EXISTANTS -->
									<!-- ============================================================== -->
									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<h5 class="card-header">SCELLES TROUVES</h5>
											<div class="card-body">

												<div class="form-group">
													<label class="custom-control custom-checkbox">
														<input type="checkbox" checked="" class="custom-control-input" name='sceller_identique' id='sceller_identique' /><span class="custom-control-label">SCELLES IDENTIQUES AUX DERNIERS</span>
													</label>
												</div>
												<div class="form-group">
													<label>SCELLE COFFRET</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right allow-numeric" name="scelle_coffret_existant" id="scelle_coffret_existant">
													</div>
												</div>
												<div class="form-group">
													<label>SCELLE COMPTEUR</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right allow-numeric" name="scelle_cpt_existant" id="scelle_cpt_existant">
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- ============================================================== -->
									<!-- end Information SCELLES EXISTANTS -->
									<!-- ============================================================== -->



									<!-- ============================================================== -->
									<!-- Information SCELLES EXISTANTS -->
									<!-- ============================================================== -->
									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<h5 class="card-header">SCELLES POSES PAR LE CONTROLEUR</h5>
											<div class="card-body">
												<div class="form-group">
													<label>SCELLE COFFRET</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right allow-numeric" name="scelle_coffret_poser" id="scelle_coffret_poser">
													</div>
												</div>
												<div class="form-group">
													<label>SCELLE COMPTEUR</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right allow-numeric" name="scelle_compteur_poser" id="scelle_compteur_poser">
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- ============================================================== -->
									<!-- end Information SCELLES EXISTANTS -->
									<!-- ============================================================== -->

								</div>
							</div>
						</div>
						<!-- ============================================================== -->
						<!-- end Information NOUVEAU COMPTEUR form -->
						<!-- ============================================================== -->




						<!-- ============================================================== -->
						<!-- Information RACORDEMENT form -->
						<!-- ============================================================== -->
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="card">
								<h5 class="card-header">EXAMEN DU RACCORDEMENT </h5>
								<div class="row">

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>TYPE RACCORDEMENT</label>
													<div class="input-group" style="width: 100%;">
														<select class='form-control select2' style='width: 100%;' name='type_raccordement' id='type_raccordement' required>
															<option selected='selected' disabled> </option>
															<?php
															$stmt_tarif = $raccordement->read();
															while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
																echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
															}
															?>
														</select>

													</div>
												</div>

												<div class="form-group">
													<label>NOMBRE D'ARRIVEES</label>
													<div class="input-group" style="width: 100%;">
														<input type="number" class="form-control pull-right allow-numeric" name="nbre_arrived" id="nbre_arrived">
													</div>
												</div>
												<div class="form-group">
													<label>SECTION CABLE ARRIVEE</label>
													<div class="input-group" style="width: 100%;">
														<select class='form-control select2' style='width: 100%;' name='section_cable_arrived' id='section_cable_arrived' required>
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






											</div>
										</div>
									</div>


									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">

												<div class="form-group">
													<label>PAR WIFI/CPL</label>
													<div class="input-group" style="width: 100%;">
														<select class='form-control select2' style='width: 100%;' name='par_wifi_cpl' id='par_wifi_cpl'>
															<option selected='selected' disabled> </option>
															<?php
															$stmt_p = $p_wifi->read();
															while ($row_ = $stmt_p->fetch(PDO::FETCH_ASSOC)) {
																echo "<option value='{$row_["code"]}'>{$row_["libelle"]}</option>";
															}
															?>
														</select>
													</div>
												</div>

												<div class="form-group">
													<label>POSSIBILITES DE FRAUDE (EXPLIQUER)</label>
													<div class="input-group" style="width: 100%;">
														<textarea class="form-control pull-right" name="possibility_fraud_expliquer" id="possibility_fraud_expliquer"></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>




									<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="card-body">
											<div class="form-group">
												<!--	<a class="btn btn-outline-light float-right" id="btn_gps"><i class="fas fa-map-marker-alt"></i>Get GPS</a> -->
												<a class="btn btn-outline-light float-right" id="btn_gps"><i class="fas fa-map-marker-alt"></i> Récupérer Coordonnées</a>
												<?php if (Utils::IsWebView($_SERVER)) { ?>
													<a class="btn btn-outline-light float-right" id="btn_gps_native"><i class="fas fa-map-marker-alt"></i> GPS Native </a>
												<?php } ?>
												<label>LATITUDE</label>
												<div class="input-group" style="width: 50%;">
													<input type="text" class="form-control pull-right" name="gps_latitude_control" id="gps_latitude" required>
												</div>

												<label>LONGITUDE</label>
												<div class="input-group" style="width: 50%;">
													<input type="text" class="form-control pull-right" name="gps_longitude_control" id="gps_longitude" required>
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
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="card">
								<h5 class="card-header">EXAMEN APPROFONDI DU COMPTEUR A PREPAIEMENT</h5>
								<div class="row">

									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<label>INTERRUPTEUR</label>
													<div class="input-group" style="width: 100%;">
														<select class='form-control select2' style='width: 100%;' name='etat_interrupteur' id='etat_interrupteur' required>
															<option selected='selected' disabled> </option>
															<?php
															$stm = $etat_interrupteur->read();
															while ($row_ = $stm->fetch(PDO::FETCH_ASSOC)) {
																echo "<option value='{$row_["code"]}'>{$row_["libelle"]}</option>";
															}
															?>
														</select>
													</div>
												</div>
												<div class="form-group">
													<label>CREDIT RESTANT</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="credit_restant" id="credit_restant">
													</div>
												</div>

												<div class="form-group">
													<label>INDICATEUR LED</label>
													<div class="input-group" style="width: 100%;">
														<select class='form-control select2' style='width: 100%;' name='indicateur_led' id='indicateur_led' required>
															<option selected='selected' disabled> </option>
															<?php
															$stmt_ = $indicateur_led->read();
															while ($row_ = $stmt_->fetch(PDO::FETCH_ASSOC)) {
																echo "<option value='{$row_["code"]}'>{$row_["libelle"]}</option>";
															}
															?>
														</select>
													</div>
												</div>


												<div class="form-group">
													<label>CONSOMMATION JOURNALIERE</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="consommation_journaliere" id="consommation_journaliere">
													</div>
												</div>


												<div class="form-group">
													<label>CONSOMMATION DE 30 JOURS ACTUELS</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="consommation_de_30jours_actuels" id="consommation_de_30jours_actuels">
													</div>
												</div>

												<div class="form-group">
													<label>CONSOMMATION DE 30 JOURS PRECEDENTS</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="consommation_de_30jours_precedents" id="consommation_de_30jours_precedents">
													</div>
												</div>



											</div>
										</div>
									</div>
									<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">
												<div class="form-group">
													<a class="btn btn-outline-light float-right fraude-hide" id="btn_voir_fraude">Masquer</a>
													<label class="custom-control custom-checkbox" style="width:60px;">
														<input type="hidden" name='cas_de_fraude' id='cas_de_fraude'>
														<input type="checkbox" checked="" class="custom-control-input" id='cas_de_fraude_chk' /><span class="custom-control-label">CAS DE FRAUDE</span>
													</label>
												</div>
												<div id="bloc_info_fraude">
													<div class="form-group">
														<label class="custom-control custom-checkbox">
															<input type="hidden" name='client_reconnait_pas' id='client_reconnait_pas'>
															<input type="checkbox" checked="" class="custom-control-input" id='client_reconnait_pas_chk' /><span class="custom-control-label">CLIENT RECONNAIT</span>
														</label>
													</div>
													<div class="form-group">
														<label>TYPE DE FRAUDE</label>
														<div class="input-group" style="width: 100%;">
															<input type="text" class="form-control pull-right" name="txt_frd_search" id="txt_frd_search" placeholder="Filtrer Fraude ...">
														</div>

														<div class="card shadow border-0 mb-5" style="height:250px;overflow-y:scroll">
															<div class="card-body">
																<p class="small text-muted font-italic mb-4">Veuillez cocher les fraudes constatées</p>
																<ul class="list-group table table-hover" id='type_fraude_lst'>
																	<?php
																	$stmt_tarif = $typeFraude->read();
																	while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) { ?>
																		<li class="list-group-item rounded-0 lstr-fraude-item">
																			<div class="custom-control custom-checkbox">
																				<input class="custom-control-input" id="fr_<?php echo  $row_gp["code"]; ?>" type="checkbox" value="<?php echo  $row_gp["code"]; ?>" name="frd_checkbox[]">
																				<label class="cursor-pointer font-italic d-block custom-control-label lst-fraude-item-label" for="fr_<?php echo  $row_gp["code"]; ?>"><?php echo  $row_gp["libelle"]; ?></label>
																			</div>
																		</li><?php	} ?>
																</ul>
															</div>
														</div>



													</div>
													<div class="card">
														<div class="card-body">
															<div class="form-group">
																<label>PHOTO SIGNATURE CLIENT</label>
																<?php if (Utils::IsWebView($_SERVER)) {  ?>
																	<div class="image-upload">
																		<label for="file-input_signature_client">
																			<img id="previewImg" src="image/camera.jpg" style="width: 25px; height: 25px;" />
																		</label>
																		<input id="file-input_signature_client" type="file" onchange="previewFile(this,'photo_signature_client');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
																	</div>
																<?php } else { ?>
																	<a class="btn btn-outline-light float-right" id="btn_capture_signature_client">Capturer photo</a>


																	<?php
																	if ($utilisateur->access_au_module_deux == '1') {		?>
																		<div class="image-upload">
																			<label for="file-input_signature_client">
																				<img id="previewImg" src="image/camera.jpg" style="width: 25px; height: 25px;" />
																			</label>
																			<input id="file-input_signature_client" type="file" onchange="previewFile(this,'photo_signature_client');" style="display: none;" accept="image/*;capture=camera" capture="camera" />
																		</div>

																<?php }
																} ?>
																<div class="input-group" style="width: 100%;">
																	<img style="height:300px;" class="form-control pull-right" id="photo_signature_client" />
																</div>
															</div>
														</div>
													</div>



													<div class="form-group">
														<label class="custom-control custom-checkbox">
															<input type="checkbox" class="custom-control-input" id='refus_client_de_signer' name='refus_client_de_signer' /><span class="custom-control-label">Client Refuse de signer</span>
														</label>
													</div>


												</div>
												<?php    /* <div class="form-group">
                                    <label>CAS DE FRAUDE</label>
                                    <div class="input-group"  style="width: 100%;" > 
                                  <!--  <select class='form-control select2' style='width: 100%;' name='cas_de_fraude'  id='cas_de_fraude' required >
                                        <option selected='selected' disabled> </option>
                                       
                                        $stmt_select_st = $yes_no->read();
                                        while ($row_gp = $stmt_select_st->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
                                        }
                                       
                                        </select>   --> 
                                    </div>                
                                </div>				*/ ?>

												<div class="form-group">
													<label>AUTOCOLLANT PLACE</label>
													<div class="input-group" style="width: 100%;">
														<select class='form-control select2' style='width: 100%;' name='autocollant_place_controleur' id='autocollant_place_controleur' required>
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
													<label>AUTOCOLLANT TROUVE</label>
													<div class="input-group" style="width: 100%;">

														<select class='form-control select2' style='width: 100%;' name='autocollant_trouver' id='autocollant_trouver' required>
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


												<div class="form-group" style="width : 135px;margin-right:120px;">
													<label>DATE DERNIER TICKET</label>
													<div class="input-group date" style="width: 100%;">
														<input type="text" class="form-control datetimepicker-input" name="date_de_dernier_ticket_rentre" id="date_de_dernier_ticket_rentre" />
														<div class="input-group-append">
															<div class="input-group-text" id="add_on_date_de_dernier_ticket"><i class="far fa-calendar-alt"></i></div>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label>VALEUR DU DERNIER TICKET</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="valeur_du_dernier_ticket" id="valeur_du_dernier_ticket">
													</div>
												</div>
												<div class="form-group">
													<label>INDEX DE TARIF DU COMPTEUR</label>
													<div class="input-group" style="width: 100%;">
														<input type="text" class="form-control pull-right" name="index_de_tarif_du_compteur" id="index_de_tarif_du_compteur">
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

						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

							<div class="form-group">
								<label>CODES DES DIAGNOSTICS</label>
								<div class="input-group" style="width: 100%;">
									<input type="text" class="form-control pull-right" name="txt_obser_search" id="txt_obser_search" placeholder="Filtrer Codes  ...">
								</div>

								<div class="card shadow border-0 mb-5" style="height:250px;overflow-y:scroll">
									<div class="card-body">
										<p class="small text-muted font-italic mb-4">Veuillez cocher les codes des diagnostics</p>
										<ul class="list-group table table-hover" id='type_observation_lst'>
											<?php
											$stmt_observation = $typeObservation->read();
											while ($row_gp = $stmt_observation->fetch(PDO::FETCH_ASSOC)) { ?>
												<li class="list-group-item rounded-0 lst-obs-item">
													<div class="custom-control custom-checkbox">
														<input class="custom-control-input" id="obser_<?php echo  $row_gp["code"]; ?>" type="checkbox" value="<?php echo  $row_gp["code"]; ?>" name="obser_checkbox[]">
														<label class="cursor-pointer font-italic d-block custom-control-label lst-obs-item-label" for="obser_<?php echo  $row_gp["code"]; ?>"><?php echo  $row_gp["libelle"]; ?></label>
													</div>
												</li><?php	} ?>
										</ul>
									</div>
								</div>
							</div>








						</div>
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="form-group">
								<label>OBSERVATIONS COMPLEMENTAIRES</label>
								<div class="input-group" style="width: 100%;">
									<textarea class="form-control pull-right" name="diagnostics_general" id="diagnostics_general" required></textarea>
								</div>
							</div>
						</div>
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="form-group">
								<label>AVIS DU CLIENT</label>
								<div class="input-group" style="width: 100%;">
									<textarea class="form-control pull-right" name="avis_client" id="avis_client"></textarea>
								</div>
							</div>
						</div>

						<div class="row">

							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="card">
									<div class="card-body">
										<div class="form-group">
											<label>Conclusion contrôle<span class="ml-1 text-danger">*</span></label>
											<div class="input-group" style="width: 100%;">



												<select class='form-control select2' style='width: 100%;' name='typ_conclusion' id='typ_conclusion' required>
													<option selected='selected' disabled> </option>
													<?php
													$stmt_conclusion = $type_conclusion->read();

													while ($row_conclu = $stmt_conclusion->fetch(PDO::FETCH_ASSOC)) {
														echo "<option value='{$row_conclu["code"]}'>{$row_conclu["libelle"]}</option>";
													}
													?>
												</select>

											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
								<div class="card">
									<div class="card-body">
										<div class="form-group">
											<label>SOCIETE EN CHARGE DU CONTROLE<span class="ml-1 text-danger">*</span></label>
											<div class="input-group" style="width: 100%;">

												<select class='form-control select2' style='width: 100%;' name='id_organisme_control' id='id_organisme_control' required>
													<option selected='selected' disabled> </option>
													<?php
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

						<div class="row">

							<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
								<div class="card">
									<div class="card-body">
										<div class="form-group">
											<label>CHEF D'EQUIPE CONTROLE<span class="ml-1 text-danger">*</span></label>
											<div class="input-group" style="width: 100%;">



												<select class='form-control select2' style='width: 100%;' name='chef_equipe_control' id='chef_equipe_control' required>
													<option selected='selected' disabled> </option>
													<?php
													$stmt_chief = null;
													if ($utilisateur->id_service_group ==  '3') {  //Administration
														$stmt_chief = $utilisateur->GetAllChiefForAdmin();
													} else {
														$stmt_chief = $utilisateur->GetCurrentUserChief($utilisateur);
													}

													while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
														echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
													}

													/*
										$stmt_chief = $utilisateur->GetCurrentUserChief($utilisateur->code_utilisateur,$utilisateur->id_organisme,$utilisateur->chef_equipe_id);
										
                                        while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
                                        }*/
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
											<label>CONTROLEUR <span class="ml-1 text-danger">*</span></label>
											<div class="input-group" style="width: 100%;">

												<select class='form-control select2' style='width: 100%;' name='controleur' id='controleur' required>
													<option selected='selected' disabled>Veuillez préciser</option>
													<?php


													$stmt_chief = null;
													if ($utilisateur->id_service_group ==  '3') {  //Administration
														$stmt_chief = $utilisateur->GetAllControleur();
													} else {
														$stmt_chief = $utilisateur->GetCurrentUserListIdentificateurs($utilisateur->code_utilisateur, $utilisateur->id_organisme, $utilisateur->is_chief);
													}




													while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
														echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
													}
													?>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
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
									<button type="button" class="form-control btn btn-primary btn-lg" id="btn_save_control"><span class="glyphicon glyphicon-ok-sign"></span> Appliquer</button>
								</div>
							</div>
						</div>


					</div>
				</form>
			</div>
		</div>
	</div>






	<div class="modal" id="camera_shooter_control" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="camera_shooter_control_FRM">
		<div class="modal-dialog" role="document">
			<div class="modal-content" style="width: 355px;">
				<div class="modal-header">
					<h4 id="item_titre_control" class="modal-title">CAPTURE PHOTO</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span> </button>
				</div>
				<div class="modal-body text-center">

					<input id="bloc_destination" type="hidden">
					<div id="my_camera_control" style="width: 320px; height: 240px;">
						<div></div><video id="webcam_control" autoplay="autoplay" style="width: 320px; height: 240px;"></video>
						<canvas id="canvas_control" class="d-none"></canvas>
						<div class="flash_control"></div>
						<audio id="snapSound_control" src="audio/snap.wav" preload="auto"></audio>
					</div>
					<input type="button" class="btn btn-primary" value="Changer caméra" id="cameraFlip_control">

					<input type="button" class="btn btn-primary" value="Capturer" onclick="take_snapshot_control()">

				</div>
			</div>
		</div>
	</div>

	<div class="modal" id="dlg_main-control-assign" tabindex="-1" role="dialog" aria-labelledby="edit-control" aria-hidden="true" data-backdrop="static" style="overflow: scroll;">
		<div class="modal-dialog modal-lg" style="background-color: #f4f8fc;">
			<div class="modal-content">
				<form id="client_lst" method="post" enctype="multipart/form-data">
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
					<form id="frm_signaler_Refus" method="post" enctype="multipart/form-data">
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
	<div class="modal" id="box_verify_compteur" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog  modal-sm" role="document" data-backdrop="static">
			<div class="modal-content">
				<div class="modal-header">
					<h4 id="notification_title" class="modal-title">
						Confirmer le Numéro Série Compteur</h4>
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
							<label>Type de ticket contrôle</label>
							<select multiple class="form-control" required name="control_type[]" id="control_type">
								<option disabled>Choisir le type</option>
								<option value="Ticket Achats">Ticket Achats</option>
								<option value="Ticket Anti-fraude">Ticket Anti-fraude</option>
								<option value="Ticket Adresse Incorrecte">Ticket Adresse Incorrecte</option>
								<option value="Ticket Date Installation">Ticket Date Installation</option>
								<option value="Nombre de compteurs dans une adresse">Nombre de compteurs dans une adresse</option>
								<option value="Ticket autre">Ticket Autre</option>
							</select>
						</div>

						<div class="text-center">
							<button id="btn_submit_verify_compteur" type="button" class="btn btn-success btn-fill float-right">Confirmer</button>
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
	<div id="map" style="display:none;"></div>
	<?php include_once 'layout_map_viewer.php';  ?>

	<div id="myBackdrop" class="modal-backdrop" style="display:none;opacity:.5"></div>
	<script src="assets/js/leaflet.js"></script>
	<script src="assets/js/mapviewer-script.js"></script>
	<script src="assets/js/select2.min.js"></script>
	<script>
		function previewFile(input, bloc_destination) {

			if (input.files && input.files[0]) {
				var reader = new FileReader();
				reader.onload = function(e) {
					$('#' + bloc_destination).prop("src", e.target.result);
				}

				reader.readAsDataURL(input.files[0]);
			}
		}

		$("#verify_compteur").click(function(e) {
			e.preventDefault();
			$("#box_verify_compteur #view").val('verify_compteur_and_send_ticket_demand');
			$("#box_verify_compteur").show();
		});

		$("#btn_submit_verify_compteur").click(function(e) {
			var item = $('#serial_number_verify').val() != null ? $('#serial_number_verify').val() : '';
			var control_type = $('#control_type').val() != null ? $('#control_type').val() : '';
			if (item == '') {
				swal("Information", "Veuillez saisir le numéro série du compteur", "error");
				return false;
			}
			if (control_type = '') {
				swal("Information", "Veuillez saisir le(s) type(s) de contrôle à effectuer !", "error")
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
					swal({
						title: "Information - Demande de ticker automatique ",
						text: "Serveur non disponible",
						type: "error",
						showCancelButton: false,
						confirmButtonColor: "#008000",
						confirmButtonText: "Ok",
						closeOnConfirm: true,
						closeOnCancel: false
					}, function(isConfirm) {});
				},
				success: function(result) {
					//console.log(result);
					try {
						if (result.error == 0) {
							swal({
								title: "Information",
								text: result.message,
								type: "success",
								showCancelButton: false,
								confirmButtonColor: "#DD6B55",
								confirmButtonText: "Ok",
								closeOnConfirm: true,
								closeOnCancel: false
							}, function(isConfirm) {});

							$("#numero_serie_cpteur").val(result.serial_number);
							$("#marque_compteur").val(result.manufacturer_ref).change();
							$("#serial_number_verify").val('');
							$("#box_verify_compteur").hide();
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
				complete: function() {
					$("#verify_compteur").removeAttr('disabled');
				}
			});


		});

		$(document).ready(function() {


			$("#txt_frd_search").keyup(function() {
				var val = $(this).val().toString().toLowerCase();
				$('#type_fraude_lst').find('.lstr-fraude-item').each(function(i) {
					var row = $(this);
					var label_fraude = row.find('.lst-fraude-item-label').text().toString().toLowerCase();

					if (label_fraude.indexOf(val) != -1) {
						row.show();
						// return false;
					} else row.hide();
				});

				if (!val)
					$('#type_fraude_lst').find('.lstr-fraude-item').each(function(i) {
						$(this).show();
					});
			});

			$("#txt_obser_search").keyup(function() {
				var val = $(this).val().toString().toLowerCase();
				$('#type_observation_lst').find('.lst-obs-item').each(function(i) {
					var row = $(this);
					var label_observ = row.find('.lst-obs-item-label').text().toString().toLowerCase();

					if (label_observ.indexOf(val) != -1) {
						row.show();
						// return false;
					} else row.hide();
				});

				if (!val)
					$('#type_observation_lst').find('.lst-obs-item').each(function(i) {
						$(this).show();
					});
			});


			$("#add_on_date_de_dernier_ticket").click(function() {
				$('#date_de_dernier_ticket_rentre').datetimepicker('show');
			});



			jQuery(document).delegate('a.fraude-show', 'click', function(e) {
				e.preventDefault();
				$("#bloc_info_fraude").show();
				$("#btn_voir_fraude").html('Masquer');
				$("#btn_voir_fraude").removeClass('fraude-show');
				$("#btn_voir_fraude").addClass('fraude-hide');
			});

			jQuery(document).delegate('a.fraude-hide', 'click', function(e) {
				e.preventDefault();
				$("#bloc_info_fraude").hide();
				$("#btn_voir_fraude").html('Voir');
				$("#btn_voir_fraude").removeClass('fraude-hide');
				$("#btn_voir_fraude").addClass('fraude-show');
			});


			if ($("#date_de_dernier_ticket_rentre").length) {
				$('#date_de_dernier_ticket_rentre').datetimepicker({
					format: 'dd/mm/yyyy',
					language: 'fr',
					weekStart: 1,
					todayBtn: 1,
					autoclose: 1,
					minView: 2
				});

			}
			$('form#mainForm_control').submit(function(e) {
				e.preventDefault();
				alert('prevent submit');
			});

			$('form#client_lst').submit(function(e) {
				e.preventDefault();
				alert('prevent submit');
			});
			$('form#frm_signaler_Refus').submit(function(e) {
				e.preventDefault();
				alert('prevent submit');
			});
		});


		$(function() {
			var ctr = 0;


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
			/* $('#mainForm_control .select2').each(function(){
			                            var $sel = $(this).parent();
			                                    $(this).select2({
			                            dropdownParent:$sel
			                            });
			                            });*/

			$('#filtre').select2({
				placeholder: "Filtre",
				multiple: true
			});
			$("#bloc_info_fraude").hide();
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
					// $(".error").css("display", "inline");
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
				var cvs_id = $("#cvs_id_inst").attr('data_id');
				var assign_id = $("#cvs_id_inst").attr('assign_id');
				var cvs_Label = '';
				var adresse_id = $("#adresse_inst").attr('adresse_id');
				var adr_Label = $("#adresse_inst").attr('adressetexte');
				if (access_cli.length > 0) {


					$("#refus_adresse").html(adr_Label);
					$("#refus_cvs").html('');
					$("#refus_cvs").attr('cvs_id', cvs_id);
					$("#refus_cvs").attr('assign_id', assign_id);
					$("#refus_cvs").attr('accessibility_client', access_cli);
					$("#refus_cvs").attr('adresse_id', adresse_id);
					$("#notification_title").html("Notification Exonération");
					//	$("#frm_signaler_Refus #view").html("create_refus");				
					$("#dlg_main-control").hide();
					$("#box_signaler_Refus").show();
				} else if (access_cli == "") {
					$("#accessibility_client").focus();
					swal("Information", "Veuillez préciser l'accesbilité client", "error");

				}
			});


			$("#btn_Signaler_Refus").click(function(e) {
				e.preventDefault();
				var access_cli = $("#accessibility_client").val();
				var cvs_id = $("#cvs_id_inst").attr('data_id');
				var assign_id = $("#cvs_id_inst").attr('assign_id');
				var cvs_Label = '';
				var adresse_id = $("#adresse_inst").attr('adresse_id');
				var adr_Label = $("#adresse_inst").attr('adressetexte');
				if (access_cli.length > 0) {


					$("#refus_adresse").html(adr_Label);
					$("#refus_cvs").html('');
					$("#refus_cvs").attr('cvs_id', cvs_id);
					$("#refus_cvs").attr('assign_id', assign_id);
					$("#refus_cvs").attr('accessibility_client', access_cli);
					$("#refus_cvs").attr('adresse_id', adresse_id);
					$("#notification_title").html("Notification Refus");
					//	$("#frm_signaler_Refus #view").html("create_refus");				
					$("#dlg_main-control").hide();
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
				$("#frm_signaler_Refus #view").val('create_refus_control');
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

					}
				});

			});

			$("#add_on_du").click(function() {
				$('#Du').datetimepicker('show');
			});
			$("#add_on_au").click(function() {
				$('#Au').datetimepicker('show');
			});

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

			$('#dat_rendez_vous').datetimepicker({
				format: 'dd/mm/yyyy',
				language: 'fr',
				weekStart: 1,
				todayBtn: 1,
				autoclose: 1,
				minView: 2
			});



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
						//return false;
					} else row.hide();
				});

				if (!val)
					$('#client_lst .modal-body').find('.client-row').each(function(i) {
						$(this).show();
					});
			});

			<?php if ($utilisateur->HasDroits("10_110")) {
			?> $('#btn_new_').click(function() {
					ClearForm();

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
							view: "get_control_assign"
						},
						success: function(data, statut) {
							try {
								var rendez_vous = '';
								var result = $.parseJSON(data);
								if (result.error == 0) {
									//  $("#clients-rows").html(result.data);
									$.each(result.items, function(i, item) {
										// Id = generateItemID(lignes);

										rendez_vous = '';
										if (item.data.date_rendez_vous != null) {
											rendez_vous = '<span class="badge badge-primary client-rendez-vous">' + item.data.date_rendez_vous_fr + '</span>';
										}

										$("#clients-rows").append('<div class="client-row card bg-white"><div class="card-header">	<div class="row"><div class="col-sm-6">	<div class="text-dark">Client</div>	<div class="font-medium text-primary client-name">' + item.data.nom_client_blue + ' ' + rendez_vous + '</div></div><div class="col-sm-6"><div class="text-right"><div class="btn-group"><a href="#" class="btn btn-outline-primary control-assign-detail" data-id-detail="' + item.data.id_ + '" data-id-assign="' + item.data.id_assign + '">Contrôler</a></div>	</div></div>	</div></div><div class="card-body">	<div class="row">	<div class="col-sm-4"><div class="text-dark">Adresse</div>	<div class="font-medium text-primary client-adress">' + item.adresseTexte + '</div></div><div class="col-sm-4 text-center">	<div class="text-dark">Téléphone</div>	<div class="font-medium text-primary client-phone">' + item.data.phone_client_blue + '</div></div><div class="col-sm-4 text-right">	<div class="text-dark">CVS	</div>	<div class="font-medium text-primary client-cvs">' + item.data.libelle + '</div></div></div>	<div class="row"><div class="col-sm-6">	<div class="text-dark">Compteur	</div>	<div class="font-medium text-primary client-device">' + item.data.num_compteur_actuel + '</div></div> </div></div></div>');


									});
									if (result.items.length == 0) {
										$("#clients-rows").append('<div class="card alert-danger"><div class="card-body"><div role="alert" class=""><h4 class="alert-heading">Notification!</h4><p>Aucune information trouvée.</p></div></div></div>');
									}
									$('#dlg_main-control-assign').show();

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
						complete: function() {
							HideLoader();
						}
					});


				});
			<?php } ?>

			function modalbox_scroll() {
				$('.modal').on("hidden.bs.modal", function(e) { //fire on closing modal box scroll issue hack
					if ($('.modal:visible').length) {
						$('body').addClass('modal-open');
					}
				});
			}
			modalbox_scroll();

			var actual_chief = "";
			var load_chief = false;
			$('#dlg_main-control .select2').each(function() {
				var $sel = $(this).parent();
				$(this).select2({
					dropdownParent: $sel
				});
			});

			$('#item_label_control').val("").change();
			$('.modal').on("hidden.bs.modal", function(e) { //fire on closing modal box scroll issue hack
				if ($('.modal:visible').length) {
					$('body').addClass('modal-open');
				}
			});

			$("#advanced_search").on("hide.bs.collapse", function() {
				$('#srch-term').show();
				$('#search-btn').show();
			});

			$("#advanced_search").on("show.bs.collapse", function() {
				$('#srch-term').hide();
				$('#search-btn').hide();
			});


			$("#cameraFlip_control").click(function() {
				webcam.flip();
				webcam.start();

			});

			$("#btn_capture_control").click(function(e) {
				e.preventDefault();
				webcam.start();
				$("#bloc_destination").val('photo_compteur');
				//  Webcam.attach('#my_camera_control');
				$("#camera_shooter_control").show();
			});

			$("#btn_capture_apres_control").click(function(e) {
				e.preventDefault();
				webcam.start();
				$("#bloc_destination").val('photo_apres_control');
				//  Webcam.attach('#my_camera_install');
				$("#camera_shooter_control").show();
			});

			$("#btn_capture_avant_control").click(function(e) {
				e.preventDefault();
				webcam.start();
				$("#bloc_destination").val('photo_avant_control');
				//  Webcam.attach('#my_camera_install');
				$("#camera_shooter_control").show();
			});
			$("#btn_capture_sceller_un").click(function(e) {
				e.preventDefault();
				webcam.start();
				$("#bloc_destination").val('photo_sceller_un');
				//  Webcam.attach('#my_camera_install');
				$("#camera_shooter_control").show();
			});

			$("#btn_capture_sceller_deux").click(function(e) {
				e.preventDefault();
				webcam.start();
				$("#bloc_destination").val('photo_sceller_deux');
				//  Webcam.attach('#my_camera_install');
				$("#camera_shooter_control").show();
			});
			$("#btn_capture_signature_client").click(function(e) {
				e.preventDefault();
				webcam.start();
				$("#bloc_destination").val('photo_signature_client');
				//  Webcam.attach('#my_camera_install');
				$("#camera_shooter_control").show();
			});



			$("#btn_gps").click(function(e) {
				e.preventDefault();
				ShowLoader("Localisation en cours...");
				locateNew();
			});

			$("#btn_gps_native").click(function(e) {
				e.preventDefault();
				ShowLoader("Localisation en cours...");
				try {
					contactSupport.getGPS('onLocationFoundAndroid', 'onLocationFailAndroid');
				} catch (e) {
					HideLoader();
				}
			});


			jQuery(document).delegate('a.control-assign-detail', 'click', function(e) {
				e.preventDefault();
				$('#dlg_main-control-assign').hide();
				ClearForm();
				ShowLoader("Chargement détails liés au compteur en cours...");
				var jeton_actuel = jQuery(this).attr("data-id-detail");
				var id_assign = jQuery(this).attr("data-id-assign");
				$('#titre_control').html('NOUVEAU CONTROLE');
				$.ajax({
					url: "controller.php",
					dataType: "json",
					method: "GET",
					data: {
						view: 'prepare_controle',
						k: jeton_actuel
					},
					success: function(result, statut) {
						try {
							//var result = $.parseJSON(data);
							if (result.error == 0) {
								$('#view_control').val("create_control");
								$('#id_control').val(result.uid);
								$('#id_assign').val(id_assign);
								if (result.detail.data.gps_longitude != null && result.detail.data.gps_latitude != null) {
									$("#btn_map_viewer").attr('data-lat', result.detail.data.gps_latitude);
									$("#btn_map_viewer").attr('data-lng', result.detail.data.gps_longitude);
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
								$("#ref_identific").val(result.detail.data.id_);
								//if(result.detail.infos_installation){
								$("#date_identification_inst").val(result.detail.data.date_installation_actuel_fr);
								//	}
								$("#p_a_inst").val(result.detail.data.p_a);
								$("#num_compteur_actuel").val(result.detail.data.num_compteur_actuel);
								//	 $("#marque_compteur_inst").val(result.detail.data.num_compteur_actuel);
								// $("#commune_id").val(result.detail.data.commune_id).change();
								$("#nom_responsable_inst").val(result.detail.data.nom_proprietaire_facture_snel);
								$("#nom_abonne").val(result.detail.data.nom_client_blue);
								$("#adresse_inst").val(result.adresseTexte);
								$("#adresse_inst").attr('adresse_id', result.detail.data.adresse_id);
								$("#adresse_inst").attr('adresseTexte', result.adresseTexte);
								// $("#quartier").val(result.detail.data.quartier);
								$("#tarif_identif").val(result.detail.data.tarif_identif);
								$("#cvs_id_inst").val(result.detail.data.cvs_id).change();
								$("#cvs_id_inst").attr('detail.data_id', result.detail.data.cvs_id);
								$("#cvs_id_inst").attr('assign_id', id_assign);

								$("#dernier_sceller_compteur").val(result.detail.scelle_un_cpteur);
								$("#dernier_sceller_coffret").val(result.detail.scelle_deux_coffret);
								//        $("#phone_abonne").val(result.data.phone_client_blue);


								// $("#photo_pa_avant").attr('src', 'pictures/' + result.data.id_ + '.jpeg');
								//	'http://127.0.0.1:8080/blue-app/pictures/' + result.data.photo_pa_avant);

								$('#dlg_main-control').show();
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




			<?php
			//if ($utilisateur->HasDroits("10_800")) {

			?>

			// $('.delete').click(function () {
			jQuery(document).delegate('a.view-fiche', 'click', function(e) {
				e.preventDefault();
				var name_actuel = jQuery(this).attr("data-name");
				var jeton_actuel = jQuery(this).attr("data-id");
				var view_mode = "visualiser_fiche_controle";
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
							// if (result.error == 0) {
							$("#fiche_viewer_title").html('VISUALISATION FICHE CONTROLE');
							$("#fiche_viewer").html(result.data);
							// modalbox_scroll();
							// $("#box_fiche_viewer").show();
							// $("#myBackdrop").show();
							ShowFiche();
							/*} else if (result.error == 1) {
							swal("Information", result.message, "error");
							}*/
						} catch (erreur) {}
					},
					complete: function() {
						HideLoader();
					}
				});

			});
			<?php //} 
			?>



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

			jQuery(document).delegate('a.close,a.fermer', 'click', function(e) {
				e.preventDefault();
				var pId = $(this).parents('div.modal').attr("id");
				$(this).parents('div.modal').hide();
				if (pId == 'box_fiche_viewer') {
					CloseFiche();
				}
				/*else if(pId == 'dlg_main-install'){ 
					CloseMain();
				}*/
			});


			<?php if ($utilisateur->HasDroits("10_130")) {
			?>
				jQuery(document).delegate('a.delete-control', 'click', function(e) {
					e.preventDefault();
					// $('.delete-control').click(function () {
					var name_actuel = jQuery(this).attr("data-name-control");
					var jeton_actuel = jQuery(this).attr("data-id-control");
					swal({
						title: "Information",
						text: 'Voulez-vous supprimer l\'controlation de l\'abonné (' + name_actuel + ')?',
						type: "warning",
						showCancelButton: true,
						confirmButtonColor: "#00A65A",
						confirmButtonText: "Oui",
						cancelButtonText: "Non",
						closeOnConfirm: false,
						closeOnCancel: true
					}, function(isConfirm) {
						if (isConfirm) {
							var view_mode = "delete_control";
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
			$("#client_reconnait_pas_chk").on("change", function(e) {
				var chk_value = $("#client_reconnait_pas_chk").prop('checked') ? "Oui" : "Non";
				$("#client_reconnait_pas").val(chk_value);
			});

			$("#cas_de_fraude_chk").on("change", function(e) {
				var cas_de_fraude = $("#cas_de_fraude_chk").prop('checked') ? "Oui" : "Non";
				$("#cas_de_fraude").val(cas_de_fraude);
				if (cas_de_fraude == 'Oui') {
					$("#bloc_info_fraude").show();
					$("#btn_voir_fraude").show();
					$("#btn_voir_fraude").html('Voir');
					// $("#type_fraude").prop('required', true);

				} else {
					$("#bloc_info_fraude").hide();
					$("#btn_voir_fraude").hide();
					$("#btn_voir_fraude").html('Masquer');
					ClearFraudes();
					// $("#type_fraude").prop('required', false);
				}
			});

			$("#sceller_identique").on("change", function(e) {
				var dernier_sceller_coffret = $("#dernier_sceller_coffret").val();
				var dernier_sceller_compteur = $("#dernier_sceller_compteur").val();

				if ($("#sceller_identique").prop('checked') == true) {
					$("#scelle_coffret_existant").val(dernier_sceller_coffret);
					$("#scelle_cpt_existant").val(dernier_sceller_compteur);
					$("#scelle_coffret_existant").prop('readonly', true);
					$("#scelle_cpt_existant").prop('readonly', true);
				} else {
					$("#scelle_coffret_existant").val('');
					$("#scelle_cpt_existant").val('');
					$("#scelle_coffret_existant").prop('readonly', false);
					$("#scelle_cpt_existant").prop('readonly', false);
				}
			});

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
					$("#chef_equipe_control").html('');
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
									$("#chef_equipe_control").html(result.data);
									if (actual_chief != "") {
										$("#chef_equipe_control").val(actual_chief).change();
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
						complete: function() {
							HideLoader();
						}
					});

				});


			<?php
			}


			?>

			function ClearFraudes() {
				var fraudes = document.getElementsByName('frd_checkbox[]');
				for (var i = 0; i < fraudes.length; i++) {
					if (fraudes[i].type == 'checkbox')
						fraudes[i].checked = false;
				}
			}

			function ClearObservations() {
				var observations = document.getElementsByName('obser_checkbox[]');
				for (var i = 0; i < observations.length; i++) {
					if (observations[i].type == 'checkbox')
						observations[i].checked = false;
				}
			}

			function ClearForm() {
				load_chief = false;
				//$("#btn_save_control").show();
				$("#statut_control").prop('disabled', false);
				$("#sceller_identique").prop('disabled', false);
				$("#view_control").val("");
				// $("#dernier_sceller_compteur").val("");
				// $("#dernier_sceller_coffret").val("");
				$('#mainForm_control').find('input[type=text],input[type=text], textarea').val('');
				$('#mainForm_control').find('input[type=checkbox]').prop('checked', false);
				$('#mainForm_control').find('select, select1').val('').change();
				$("#photo_pa_avant").attr('src', 'pictures/');
				$("#photo_compteur").attr('src', 'pictures/');
				load_chief = true;
			}


			$('#btn_save_control').click(function() {
				var frm = $("#mainForm_control");
				if (frm.parsley().validate()) {
					// alert("oui");				   
				} else {
					// alert("non");
					return false;
				}
				//Verification

				if ($("#cas_de_fraude_chk").prop('checked') == true) {
					var nbre = 0;
					var fraudes = document.getElementsByName('frd_checkbox[]');
					for (var i = 0; i < fraudes.length; i++) {
						if (fraudes[i].checked == true)
							nbre++;
					}
					if (nbre == 0) {
						swal("Information", "Veuillez préciser les fraudes", "error");
						return false;
					}
				}
				//VERIFICATION SI CODES DIAGNOSTICS CHOISIS
				var nbre_codes = 0;
				var lst_codes_diagnos = document.getElementsByName('obser_checkbox[]');
				for (var i = 0; i < lst_codes_diagnos.length; i++) {
					if (lst_codes_diagnos[i].checked == true)
						nbre_codes++;
				}
				if (nbre_codes == 0) {
					swal("Information", "Veuillez préciser le(s) code(s) diagnostic(s)", "error");
					return false;
				}


				var form = document.getElementById("mainForm_control");
				// Create a FormData and append the file with "image" as parameter name
				var formWData = new FormData(form);

				//Imagery
				var base64image = document.getElementById("photo_compteur").src;
				if (base64image.match(/^data\:image\/(\w+)/)) {
					//s=RegExp.$1;else
					// Split the base64 string in data and contentType
					var block = base64image.split(";");
					// Get the content type of the image
					var contentType = block[0].split(":")[1]; // In this case "image/gif"
					// get the real base64 content of the file
					var realData = block[1].split(",")[1]; // In this case "R0lGODlhPQBEAPeoAJosM...."

					// Convert it to a blob to upload
					var blob = b64toBlob(realData, contentType);
					formWData.append("photo_compteur", blob);


				}

				base64image = document.getElementById("photo_sceller_un").src;
				if (base64image.match(/^data\:image\/(\w+)/)) {
					var block = base64image.split(";");
					var contentType = block[0].split(":")[1];
					var realData = block[1].split(",")[1];
					var blob = b64toBlob(realData, contentType);
					formWData.append("photo_sceller_un", blob);
				}

				base64image = document.getElementById("photo_sceller_deux").src;
				if (base64image.match(/^data\:image\/(\w+)/)) {
					var block = base64image.split(";");
					var contentType = block[0].split(":")[1];
					var realData = block[1].split(",")[1];
					var blob = b64toBlob(realData, contentType);
					formWData.append("photo_sceller_deux", blob);
				}


				base64image = document.getElementById("photo_apres_control").src;
				if (base64image.match(/^data\:image\/(\w+)/)) {
					var block = base64image.split(";");
					var contentType = block[0].split(":")[1];
					var realData = block[1].split(",")[1];
					var blob = b64toBlob(realData, contentType);
					formWData.append("photo_apres_control", blob);
				}

				base64image = document.getElementById("photo_avant_control").src;
				if (base64image.match(/^data\:image\/(\w+)/)) {
					var block = base64image.split(";");
					var contentType = block[0].split(":")[1];
					var realData = block[1].split(",")[1];
					var blob = b64toBlob(realData, contentType);
					formWData.append("photo_avant_control", blob);
				}

				base64image = document.getElementById("photo_signature_client").src;
				if (base64image.match(/^data\:image\/(\w+)/)) {
					var block = base64image.split(";");
					var contentType = block[0].split(":")[1];
					var realData = block[1].split(",")[1];
					var blob = b64toBlob(realData, contentType);
					formWData.append("photo_signature_client", blob);
				}

				// formWData.append("observation", $("#observation").val());
				formWData.append("numero_serie_cpteur", $("#numero_serie_cpteur").val());
				formWData.append("diagnostics_general", $("#diagnostics_general").val());
				formWData.append("avis_client", $("#avis_client").val());
				//var cas_de_fraude = $("#cas_de_fraude_chk").prop('checked')?"Oui":"Non";
				//$("#cas_de_fraude").val(cas_de_fraude);  
				ShowLoader("Enregistrement en cours ...");
				$.ajax({
					url: "controller.php",
					data: formWData,
					type: "POST",
					contentType: false,
					processData: false,
					cache: false,
					dataType: "json",
					error: function(err) {

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
									ClearForm();
									$("#dlg_main-control").hide();
									window.location.reload();
								});
							} else if (result.error == 1) {
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
						}
					},
					complete: function() {
						HideLoader();
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

			<?php if ($utilisateur->HasDroits("10_120") || $utilisateur->HasDroits("10_940")) { ?>

				jQuery(document).delegate('a.edit-control', 'click', function(e) {
					e.preventDefault();
					ShowLoader("Chargement des données en cours...");
					//$('.edit-control').click(function () {				  
					ClearForm();

					var jeton_actuel = jQuery(this).attr("data-id-control");
					$('#titre_control').html('MODIFICATION INFORMATIONS CONTROLE');
					$.ajax({
						url: "controller.php",
						dataType: "json",
						method: "GET",
						data: {
							view: 'detail_control',
							k: jeton_actuel
						},
						success: function(result, statut) { // success est toujours en place, bien sûr !
							try {
								//var result = $.parseJSON(data);
								HideLoader();
								if (result.error == 0) {

									$('#doc_save_mode').html('');
									if (result.data.is_draft_control == '1') {
										$('#doc_save_mode').append('<option selected="" value="">Choisir mode de sauvegarde </option>');
										$('#doc_save_mode').append('<option value="1">Brouillon</option>');
										$('#doc_save_mode').append('<option value="0">Définitive</option>');
									} else {
										$('#doc_save_mode').append('<option value="0">Définitive</option>');
										$('#doc_save_mode').val('0').change();
									}


									if (result.readOnly == 1) {
										$("#btn_save_control").hide();
									} else {
										$("#btn_save_control").show();
									}



									$("#view_control").val("edit_control");

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
									/*  $("#replace_client_disjonct").prop('checked', result.data.replace_client_disjonct == "on"?true:false);
									$("#type_cpteur_raccord").val(result.data.type_cpteur_raccord).change();
									
									$("#client_disjonct_amperage").val(result.data.client_disjonct_amperage);
									$("#is_autocollant_posed").val(result.data.is_autocollant_posed).change();
									 */

									$("#id_control").val(result.data.ref_fiche_controle);
									$("#ref_identific").val(result.data.ref_fiche_identification);
									$("#autocollant_place_controleur").val(result.data.autocollant_place_controleur);
									$("#autocollant_trouver").val(result.data.autocollant_trouver);
									$("#diagnostics_general").val(result.data.diagnostics_general);
									$("#avis_client").val(result.data.avis_client);
									// $("#observation").val(result.data.observation); 
									$("#numero_serie_cpteur").val(result.data.numero_serie_cpteur);
									$("#scelle_cpt_existant").val(result.data.scelle_cpt_existant);
									$("#scelle_coffret_existant").val(result.data.scelle_coffret_existant);
									$("#scelle_compteur_poser").val(result.data.scelle_compteur_poser);
									$("#scelle_coffret_poser").val(result.data.scelle_coffret_poser);
									$("#section_cable_arrived").val(result.data.section_cable_arrived).change();
									$("#num_photo_cpteur").val(result.data.num_photo_cpteur);
									$("#num_photo_raccord").val(result.data.num_photo_raccord);
									$("#credit_restant").val(result.data.credit_restant);
									$("#gps_latitude").val(result.data.gps_latitude_control);
									$("#gps_longitude").val(result.data.gps_longitude_control);
									$("#typ_conclusion").val(result.data.typ_conclusion).change();
									$("#possibility_fraud_expliquer").val(result.data.possibility_fraud_expliquer);
									$("#indicateur_led").val(result.data.indicateur_led).change();
									$("#cas_de_fraude").val(result.data.cas_de_fraude);
									$("#cas_de_fraude_chk").prop('checked', result.data.cas_de_fraude == 'Oui' ? true : false).change();
									//$("#type_fraude").val(result.data.type_fraude).change(); 
									$("#client_reconnait_pas").val(result.data.client_reconnait_pas);
									$("#client_reconnait_pas_chk").prop('checked', result.data.client_reconnait_pas == 'Oui' ? true : false);
									$("#etat_interrupteur").val(result.data.etat_interrupteur).change();
									$("#id_organisme_control").val(result.data.id_organisme_control).change();
									$("#type_raccordement").val(result.data.type_raccordement).change();
									$("#nbre_arrived").val(result.data.nbre_arrived);

									$("#consommation_journaliere").val(result.data.consommation_journaliere);
									$("#consommation_de_30jours_actuels").val(result.data.consommation_de_30jours_actuels);
									$("#consommation_de_30jours_precedents").val(result.data.consommation_de_30jours_precedents);
									$("#valeur_du_dernier_ticket").val(result.data.valeur_du_dernier_ticket);
									$("#index_de_tarif_du_compteur").val(result.data.index_de_tarif_du_compteur);
									$("#date_de_dernier_ticket_rentre").val(result.data.date_de_dernier_ticket_rentre_fr);
									//$("#refus_access").prop('checked', result.data.refus_access == "on"?true:false);
									$("#accessibility_client").val(result.data.refus_access).change();

									$("#refus_client_de_signer").prop('checked', result.data.refus_client_de_signer == "on" ? true : false);
									$("#presence_inverseur").val(result.data.presence_inverseur).change();
									$("#par_wifi_cpl").val(result.data.par_wifi_cpl).change();
									$("#marque_compteur").val(result.data.marque_compteur).change();
									$("#type_cpteur").val(result.data.type_cpteur).change();
									$("#clavier_deporter").val(result.data.clavier_deporter).change();

									actual_chief = result.data.chef_equipe_control;
									$("#chef_equipe_control").val(result.data.chef_equipe_control).change();

									$("#controleur").val(result.data.controleur).change();


									$("#photo_compteur").attr('src', 'pictures/' + result.data.ref_fiche_controle + '_CTL_CTR.png');
									$("#photo_sceller_un").attr('src', 'pictures/' + result.data.ref_fiche_controle + '_CTL_SC1.png');
									$("#photo_sceller_deux").attr('src', 'pictures/' + result.data.ref_fiche_controle + '_CTL_SC2.png');
									$("#photo_avant_control").attr('src', 'pictures/' + result.data.ref_fiche_controle + '_CTL_BFR.png');
									$("#photo_apres_control").attr('src', 'pictures/' + result.data.ref_fiche_controle + '_CTL_AFT.png');
									$("#photo_signature_client").attr('src', 'pictures/' + result.data.ref_fiche_controle + '_CTL_SGN.png');

									//identif
									$("#ref_identific").val(result.data.id_);

									$("#date_identification_inst").val('');
									if (result.infos_installation != null) {
										$("#date_identification_inst").val(result.infos_installation.date_fin_installation_fr);
									}
									$("#p_a_inst").val(result.data.p_a);
									$("#num_compteur_actuel").val(result.data.num_compteur_actuel);
									//	 $("#marque_compteur_inst").val(result.data.num_compteur_actuel);
									// $("#commune_id").val(result.data.commune_id).change();
									$("#nom_responsable_inst").val(result.client.noms);
									$("#nom_abonne").val(result.occupant.noms);
									$("#adresse_inst").val(result.adresseTexte);


									$("#adresse_inst").attr('adresse_id', result.data.adresse_id);
									$("#adresse_inst").attr('adresseTexte', result.adresseTexte);
									//$("#quartier").val(result.data.quartier);
									$("#tarif_identif").val(result.data.tarif_identif);
									$("#cvs_id_inst").val(result.data.cvs_id).change();
									$("#tarif_identif").val(result.data.tarif_identif);

									$("#cvs_id_inst").attr('data_id', result.data.cvs_id);



									$("#sceller_identique").prop('checked', result.data.sceller_identique == "1" ? true : false);
									$("#dernier_sceller_compteur").val(result.data.dernier_sceller_compteur);
									$("#dernier_sceller_coffret").val(result.data.dernier_sceller_coffret);

									//	if(result.fraudes.length=0){
									var fraudes = document.getElementsByName('frd_checkbox[]');
									for (var i = 0; i < fraudes.length; i++) {
										if (fraudes[i].type == 'checkbox')
											fraudes[i].checked = false;
									}
									// console.log(item.ref_code_fraude);	
									// console.error(result.fraudes);	
									$.each(result.fraudes, function(i, item) {
										//if(item.ref_code_fraude !=null){  

										$("#fr_" + item.ref_code_fraude).prop("checked", true);
										//}
									});
									//}

									//CODES DIAGNOSTICS
									var codes_diagnostics = document.getElementsByName('obser_checkbox[]');
									for (var i = 0; i < codes_diagnostics.length; i++) {
										if (codes_diagnostics[i].type == 'checkbox')
											codes_diagnostics[i].checked = false;
									}
									$.each(result.codes_observations, function(i, item) {
										//if(item.ref_code_fraude !=null){  

										$("#obser_" + item.ref_code_obs).prop("checked", true);
										//}
									});
									//CODES DIAGNOSTICS

									$('#dlg_main-control').show();
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
								alert(erreur);
								/*	swal({
										title: "Information",
										text: "Echec d'execution de la requete",
										type: "error",
										showCancelButton: false,
										confirmButtonColor: "#DD6B55",
										confirmButtonText: "Ok",
										closeOnConfirm: true,
										closeOnCancel: false
									}, function (isConfirm) {
									}); */
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
	<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
	<!-- Configure a few settings and attach camera -->
	<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC1vmpecnt_2I2qzPz6BjQd2ZFo4PurMkA&libraries=geometry&callback=initMap" async></script> -->
	<!-- prettier-ignore -->
	<script language="JavaScript">
		<?php //if($MobileRun != "1"){ 
		?>
		// Configure a few settings and attach camera
		const web_control_camElement = document.getElementById('webcam_control');
		const canvasElement_control = document.getElementById('canvas_control');
		const snapSoundElement_control = document.getElementById('snapSound');
		const webcam = new Webcam(web_control_camElement, 'user', canvasElement_control, snapSoundElement_control);

		<?php //} 
		?>

		function take_snapshot_control() {
			webcam.snap();

			var bloc_destination = $("#bloc_destination").val();
			$('#' + bloc_destination).prop("src", canvasElement_control.toDataURL("image/png"));


			//$("#photo_compteur").prop("src", canvasElement_control.toDataURL("image/png"));
			webcam.stop();
			$("#camera_shooter_control").hide();
		}

		/*
	
    var map = L.map('map');
    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpandmbXliNDBjZWd2M2x6bDk3c2ZtOTkifQ._QA7i5Mpkd_m30IGElHziw', {
                maxZoom: 18,
                        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                        id: 'mapbox.streets'
                }).addTo(map);
  var current_position, current_accuracy;
                        function onLocationFound(e) {
                        if (current_position) {
                        map.removeLayer(current_position);
                                map.removeLayer(current_accuracy);
                        }

                        var radius = e.accuracy / 2;
                                //alert('Latitude '+e.latlng.lat+'  Longitude '+e.latlng.lng);
                                
                                $("#gps_longitude").val(e.latlng.lng);
                                $("#gps_latitude").val(e.latlng.lat);
                                HideLoader();
                        }

                function onLocationError(e) {
                alert(e.message);
                       HideLoader();
                }

                map.on('locationfound', onLocationFound);
                        map.on('locationerror', onLocationError);
                        function locate() {
                        map.locate({setView: true, maxZoom: 16});
                        }*/


		function onLocationFoundAndroid(e) {
			HideLoader();
			var tmpJson = $.parseJSON(e);
			$("#gps_longitude").val(tmpJson.lng);
			$("#gps_latitude").val(tmpJson.lat);
		}

		function onLocationFailAndroid(e) {
			HideLoader();
		}

		function showError(error) {
			HideLoader();
			switch (error.code) {
				case error.PERMISSION_DENIED:
					alert("Permission de localisation refusée.");
					break;
				case error.POSITION_UNAVAILABLE:
					alert("Coordonnées non disponible");
					break;
				case error.TIMEOUT:
					alert("Délai d'attente dépassé.");
					break;
				case error.UNKNOWN_ERROR:
					alert("Erreur inconnue.");
					break;
			}
		}

		function showPosition(pos) {
			HideLoader();
			$("#gps_longitude").val(pos.coords.longitude);
			$("#gps_latitude").val(pos.coords.latitude);
		}

		function locateNew() {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(showPosition, showError, {
					maximumAge: 0,
					enableHighAccuracy: true
				});
			} else {
				alert("Votre navigateur ne supporte pas le GPS.");
			}
		}
	</script>
	<script type="text/javascript">
		function ShowLoader(txt) {
			$("#loader").attr("data-text", txt);
			$("#loader").addClass("is-active");
		}




		jQuery(document).delegate('a.close', 'click', function(e) {
			e.preventDefault();
			var pId = $(this).parents('div.modal').attr("id");
			$(this).parents('div.modal').hide();
			// if(pId == 'dlg_main'||pId == 'box_fiche_viewer'){
			// CloseMain();
			// }
			// if(pId == "dlg_frm_lst_identite"||pId == "dlg_frm_identite"||pId == "box_motif"){
			// $('#dlg_main').show();
			// }
		});

		function HideLoader() {
			$("#loader").removeClass("is-active");
		}

		function displayRecords(numRecords, pageNum, v_mode) {
			var s = $('#srch-term').val() != null ? $('#srch-term').val() : null;
			var du = $('#Du').val() != null ? $('#Du').val() : null;
			var au = $('#Au').val() != null ? $('#Au').val() : null;
			var filtre = $('#filtre').val() != null ? $('#filtre').val() : null;
			console.log("search temre", filtre)
			$.ajax({
				type: "GET",
				url: "controller.php",
				data: "view=search_view_control&show=" + numRecords + "&page=" + pageNum + "&Du=" + du + "&Au=" + au + "&s=" + s + "&view_mode=" + v_mode + "&filtre=" + filtre,
				cache: false,
				dataType: "json",
				beforeSend: function() {
					// $('.loader').html('<img src="loading.gif" alt="" width="24" height="24" style="padding-left: 400px; margin-top:10px;" >');
					// $("#overlay").show();
					ShowLoader("Chargement des données en cours...");

				},
				success: function(result) {
					if (result.count > 0) {
						$("#results").html(result.data);
						$("#record_count").html(result.count + " Elément(s)");
					} else {

						$("#record_count").html("0 Elément(s)");
						$("#results").html('<div class="card alert-danger"><div class="card-body"><div role="alert" class="text-center"><h1 class="alert-heading">Aucune information trouvée</h1></div></div></div>');
					}
				},
				complete: function() {
					HideLoader();
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

		$(document).ready(function() {
			var show = $("#show").val();
			displayRecords(show, 1, ''); // DEACTIVATE


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

		});
	</script>


</body>
<?php

$db = null;
?>

</html>