<?php
// session_start();
$mnu_title = "";
$page_title = "Rapports supplémentaires";
$active = "rpt_suppl";
$parambase = "";

require_once 'vendor/autoload.php';
require_once 'loader/init.php';
Autoloader::Load('classes');
include_once 'core.php';

/*
include_once 'include/database_pdo.php';
include_once 'classes/class.utilisateur.php'; 
include_once 'classes/class.province.php'; 
include_once 'classes/class.dashboard.php'; 
include_once 'classes/class.site.php'; 
include_once 'classes/class.utils.php';*/
header('Content-type: text/html;charset=utf-8');

$database = new Database();
$db = $database->getConnection();
$utilisateur = new Utilisateur($db);
$province_class = new Province($db);
$site_classe = new Site($db);
$dashview = new Dashviewer($db);

$organisme = new Organisme($db);
//$utilisateur->code_utilisateur=$_SESSION['uSession'];
if ($utilisateur->is_logged_in() == false) {
	$utilisateur->redirect('login.php');
}
$utilisateur->readOne();
/*
$province=isset($_POST['province']) ? $_POST['province'] : '';

$first_site="";*/
/*
function ClientToDbDateFormat($c_date){	
		$n_date=str_ireplace('/','-',$c_date);
		$f_dt=date('Y-m-d',strtotime($n_date));
		return $f_dt;
	}*/
?>


<!doctype html>
<html lang="en">

<head>

	<link href="assets/css/select2.css" rel="stylesheet">
	<style>
		/** SPINNER CREATION **/

		.loader {
			position: relative;
			text-align: center;
			margin: 15px auto 35px auto;
			z-index: 9999;
			display: block;
			width: 80px;
			height: 80px;
			border: 10px solid rgba(0, 0, 0, .3);
			border-radius: 50%;
			border-top-color: #5969ff;
			animation: spin 1s ease-in-out infinite;
			-webkit-animation: spin 1s ease-in-out infinite;
		}

		@keyframes spin {
			to {
				-webkit-transform: rotate(360deg);
			}
		}

		@-webkit-keyframes spin {
			to {
				-webkit-transform: rotate(360deg);
			}
		}


		/** MODAL STYLING **/

		.modal-content {
			border-radius: 0px;
			box-shadow: 0 0 20px 8px rgba(0, 0, 0, 0.7);
		}

		.modal-backdrop.show {
			opacity: 0.75;
		}

		.loader-txt {
			p {
				font-size: 13px;
				color: #666;

				small {
					font-size: 11.5px;
					color: #999;
				}
			}
		}

		/*dashboard-spinner {
    margin: 0px 8px;
    border-radius: 50%;
    background-color: transparent;
    border: 6px solid transparent;
    border-top: 6px solid #5969ff;
    border-left: 6px solid #5969ff;
    -webkit-animation: 1s spin linear infinite;
    animation: 1s spin linear infinite;
    display: inline-block;
}

.spinner-xl {
    width: 120px;
    height: 120px;
}*/
	</style>
	<link href="assets/css/select2.css" rel="stylesheet">

	<?php
	include_once "layout_style.php";
	?>

</head>

<body>

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
		<div class="dashboard-wrapper" id="dashboard">
			<div class="dashboard-ecommerce">
				<div class="container-fluid dashboard-content ">
					<!-- ============================================================== -->
					<!-- pageheader  -->
					<!-- ============================================================== -->
					<div class="row">
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="page-header">
								<h2 class="pageheader-title"><?php echo $mnu_title; ?></h2>
								<h2>Rapports Supplémentaires</h2>
								<div class="page-breadcrumb">
									<nav aria-label="breadcrumb">
										<ol class="breadcrumb">
											<li class="breadcrumb-item"><a href="#" class="breadcrumb-link"></a></li>
										</ol>
									</nav>
								</div>
							</div>
						</div>
					</div>
					<!-- ============================================================== -->
					<!-- end pageheader  -->
					<!-- ============================================================== -->
					<?php if ($utilisateur->HasDroits("10_950")) { ?>
						<div class="ecommerce-widget">




							<div class="row">
								<!-- ============================================================== -->
								<!-- validation form -->
								<!-- ============================================================== -->
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

									<div class="card">

										<div class="card-body">
											<form method="post" action="reporting/rpt_compteur_defectueux_xls.php" target="blank">
												<div class="h5 font-weight-bold text-primary"> Liste des compteurs défectueux </div>
												<div class="row">

													<div class="form-row">

														<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
															<label for="validationCustom04">Sites</label>
															<div class="form-group">
																<select class="form-controls site-filter" id="site-1" name="site[]" required>
																	<!-- 				<option value='ALL'>Toutes</option>  -->
																	<?php
																	$multi_access = false;
																	if ($utilisateur->HasDroits("10_470")) {
																		$multi_access = true;
																	}
																	//$site_array=$site_classe->GetAllSiteAccessibleForUser($utilisateur->code_utilisateur,$multi_access); 
																	$site_array = $site_classe->GetAllSiteAccessibleForUser($utilisateur);
																	$deja = false;
																	$nbre_site_ = $site_array->rowCount();
																	// if(
																	//$site =USER_SITE_ID;
																	$first_site  = $USER_SITE_ID;

																	if ($multi_access == false) {
																		echo "<option selected='selected' value='{$USER_SITE_ID}'>{$USER_SITENAME}</option>";
																	} else {
																		$sites = $site_array->fetchAll(PDO::FETCH_ASSOC);
																		// var_dump($sites); die();
																		echo "<option selected value='{$MULTI_ACCESS_SITE_CODE}'>{$MULTI_ACCESS_SITE_LABEL}</option>";

																		foreach ($sites as  $row_) {
																			echo "<option value='{$row_["code_site"]}'>{$row_["intitule_site"]}</option>";
																		}
																	}

																	?></select>
															</div>
														</div>
														<div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-12 mb-2 ">
															<label for="validationCustom05">Du</label>
															<div class="form-group" style="width : 135px;margin-right:120px;">
																<div class="input-group date">
																	<input type="text" class="form-control datetimepicker-input" name="Du" id="Du" required />
																	<div class="input-group-append">
																		<div class="input-group-text" id="add_on_du"><i class="far fa-calendar-alt"></i></div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-12 mb-2 ">
															<label for="validationCustom05">Au</label>
															<div class="form-group" style="width : 135px;margin-right:120px;">
																<div class="input-group date">
																	<input type="text" class="form-control datetimepicker-input" name="Au" id="Au" required />
																	<div class="input-group-append">
																		<div class="input-group-text" id="add_on_au"><i class="far fa-calendar-alt"></i></div>
																	</div>
																</div>
															</div>
														</div>


														<!--		<div class="form-group">
							<label>SOCIETE</label>			
														<select class='form-control select2' style='width: 100%;' name='id_equipe'  id='id_equipe'  required>
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

															?></select>
													</div>
													-->

														<div class="col-xl-1 col-lg-1 col-md-12 col-sm-12 col-12 ">
															<label> </label>
															<p class="text-right">
																<button type="submit" class="btn btn-outline-primary">
																	<i class="fas fa-search mr-2"></i>Extraire</button>
															</p>
														</div>
													</div>
												</div>
											</form>
										</div>
									</div>
									<!-- ============================================================== -->
									<!-- end validation form -->
									<!-- ============================================================== -->
								</div>
							</div>
						</div>
					<?php				} ?>
					<!-- ============================================================== -->
					<!-- end pageheader  -->
					<!-- ============================================================== -->
					<?php if ($utilisateur->HasDroits("10_950")) { ?>
						<div class="ecommerce-widget">




							<div class="row">
								<!-- ============================================================== -->
								<!-- validation form -->
								<!-- ============================================================== -->
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

									<div class="card">

										<div class="card-body">
											<form method="post" action="reporting/rpt_compteur_install_chef_tech_xls.php" target="blank">
												<div class="h5 font-weight-bold text-primary"> Liste des installations par technicien </div>
												<div class="row">

													<div class="form-row">

														<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
															<label for="validationCustom04">Sites</label>
															<div class="form-group">
																<select class="form-control site-filter" id="site-2" name="site[]" required>
																	<!-- 				<option value='ALL'>Toutes</option>  -->
																	<?php
																	$multi_access = false;
																	if ($utilisateur->HasDroits("10_470")) {
																		$multi_access = true;
																	}
																	//$site_array=$site_classe->GetAllSiteAccessibleForUser($utilisateur->code_utilisateur,$multi_access); 
																	$site_array = $site_classe->GetAllSiteAccessibleForUser($utilisateur);
																	$deja = false;
																	$nbre_site_ = $site_array->rowCount();
																	// if(
																	//$site =USER_SITE_ID;
																	$first_site  = $USER_SITE_ID;

																	if ($multi_access == false) {
																		echo "<option selected='selected' value='{$USER_SITE_ID}'>{$USER_SITENAME}</option>";
																	} else {
																		$sites = $site_array->fetchAll(PDO::FETCH_ASSOC);
																		// var_dump($sites); die();
																		echo "<option selected value='{$MULTI_ACCESS_SITE_CODE}'>{$MULTI_ACCESS_SITE_LABEL}</option>";

																		foreach ($sites as  $row_) {
																			echo "<option value='{$row_["code_site"]}'>{$row_["intitule_site"]}</option>";
																		}
																	}
																	?></select>
															</div>
														</div>
														<div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-12 mb-2 ">
															<label for="validationCustom05">Du</label>
															<div class="form-group" style="width : 135px;margin-right:120px;">
																<div class="input-group date">
																	<input type="text" class="form-control datetimepicker-input" name="Du" id="Du_tech_inst" required />
																	<div class="input-group-append">
																		<div class="input-group-text" id="add_on_du_tech_inst"><i class="far fa-calendar-alt"></i></div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-12 mb-2 ">
															<label for="validationCustom05">Au</label>
															<div class="form-group" style="width : 135px;margin-right:120px;">
																<div class="input-group date">
																	<input type="text" class="form-control datetimepicker-input" name="Au" id="Au_tech_inst" required />
																	<div class="input-group-append">
																		<div class="input-group-text" id="add_on_au_tech_inst"><i class="far fa-calendar-alt"></i></div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-12 mb-2 ">
															<div class="form-group">
																<label>CHEF D'EQUIPE *</label>
																<div class="input-group" style="width: 100%;"> <select class='form-control select2' style='width: 100%;' name='chef_item' id='chef_item' required>
																		<option selected='selected' disabled> </option>
																		<?php
																		$stmt_chief = null;
																		if ($utilisateur->id_service_group ==  '3') {  //Administration
																			$stmt_chief = $utilisateur->GetAllChiefForAdmin();
																		} else {
																			$stmt_chief = $utilisateur->GetCurrentUserChief($utilisateur); //->code_utilisateur,$utilisateur->id_organisme,$utilisateur->chef_equipe_id);
																		}

																		while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
																			echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
																		}


																		?>
																	</select>

																</div>
															</div>
														</div>
														<div class="col-xl-1 col-lg-1 col-md-12 col-sm-12 col-12 ">
															<label> </label>
															<p class="text-right">
																<button type="submit" class="btn btn-outline-primary">
																	<i class="fas fa-search mr-2"></i>Extraire</button>
															</p>
														</div>
													</div>
												</div>
											</form>
										</div>
									</div>
									<!-- ============================================================== -->
									<!-- end validation form -->
									<!-- ============================================================== -->
								</div>
							</div>







						</div>
					<?php				} ?>


					<!-- ============================================================== -->
					<!-- end pageheader  -->
					<!-- ============================================================== -->
					<?php if ($utilisateur->HasDroits("10_950")) { ?>
						<div class="ecommerce-widget">




							<div class="row">
								<!-- ============================================================== -->
								<!-- validation form -->
								<!-- ============================================================== -->
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

									<div class="card">

										<div class="card-body">
											<form method="post" action="reporting/rpt_compteur_control_chef_tech_xls.php" target="blank">
												<div class="h5 font-weight-bold text-primary"> Liste des contrôles par technicien </div>
												<div class="row">

													<div class="form-row">

														<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
															<label for="validationCustom04">Sites</label>
															<div class="form-group">
																<select class="form-control site-filter" id="site-3" name="site[]" required>
																	<!-- 				<option value='ALL'>Toutes</option>  -->
																	<?php
																	$multi_access = false;
																	if ($utilisateur->HasDroits("10_470")) {
																		$multi_access = true;
																	}
																	//$site_array=$site_classe->GetAllSiteAccessibleForUser($utilisateur->code_utilisateur,$multi_access); 
																	$site_array = $site_classe->GetAllSiteAccessibleForUser($utilisateur);
																	$deja = false;
																	$nbre_site_ = $site_array->rowCount();
																	// if(
																	//$site =USER_SITE_ID;
																	$first_site  = $USER_SITE_ID;

																	if ($multi_access == false) {
																		echo "<option selected='selected' value='{$USER_SITE_ID}'>{$USER_SITENAME}</option>";
																	} else {
																		$sites = $site_array->fetchAll(PDO::FETCH_ASSOC);
																		// var_dump($sites); die();
																		echo "<option selected value='{$MULTI_ACCESS_SITE_CODE}'>{$MULTI_ACCESS_SITE_LABEL}</option>";

																		foreach ($sites as  $row_) {
																			echo "<option value='{$row_["code_site"]}'>{$row_["intitule_site"]}</option>";
																		}
																	}
																	?></select>
															</div>
														</div>
														<div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-12 mb-2 ">
															<label for="validationCustom05">Du</label>
															<div class="form-group" style="width : 135px;margin-right:120px;">
																<div class="input-group date">
																	<input type="text" class="form-control datetimepicker-input" name="Du" id="Du_tech_ctl" required />
																	<div class="input-group-append">
																		<div class="input-group-text" id="add_on_du_tech_ctl"><i class="far fa-calendar-alt"></i></div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-12 mb-2 ">
															<label for="validationCustom05">Au</label>
															<div class="form-group" style="width : 135px;margin-right:120px;">
																<div class="input-group date">
																	<input type="text" class="form-control datetimepicker-input" name="Au" id="Au_tech_ctl" required />
																	<div class="input-group-append">
																		<div class="input-group-text" id="add_on_au_tech_ctl"><i class="far fa-calendar-alt"></i></div>
																	</div>
																</div>
															</div>
														</div>
														<div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-12 mb-2 ">
															<div class="form-group">
																<label>CHEF D'EQUIPE *</label>
																<div class="input-group" style="width: 100%;"> <select class='form-control select2' style='width: 100%;' name='chef_item' id='chef_item_ctl' required>
																		<option selected='selected' disabled> </option>
																		<?php
																		$stmt_chief = null;
																		if ($utilisateur->id_service_group ==  '3') {  //Administration
																			$stmt_chief = $utilisateur->GetAllChiefForAdmin();
																		} else {
																			$stmt_chief = $utilisateur->GetCurrentUserChief($utilisateur); //->code_utilisateur,$utilisateur->id_organisme,$utilisateur->chef_equipe_id);
																		}

																		while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
																			echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
																		}


																		?>
																	</select>

																</div>
															</div>
														</div>
														<div class="col-xl-1 col-lg-1 col-md-12 col-sm-12 col-12 ">
															<label> </label>
															<p class="text-right">
																<button type="submit" class="btn btn-outline-primary">
																	<i class="fas fa-search mr-2"></i>Extraire</button>
															</p>
														</div>
													</div>
												</div>
											</form>
										</div>
									</div>
									<!-- ============================================================== -->
									<!-- end validation form -->
									<!-- ============================================================== -->
								</div>
							</div>







						</div>
					<?php				} ?>


				</div>
			</div>
			<!-- ============================================================== -->
			<!-- footer -->
			<!-- ============================================================== -->
			<?php
			//include_once "layout_footer.php";
			?>
		</div>
		<!-- ============================================================== -->
		<!-- end wrapper  -->
		<!-- ============================================================== -->
	</div>
	<!-- ============================================================== -->
	<!-- end main wrapper  -->
	<!-- ============================================================== -->
	<!-- Optional JavaScript -->
	<!-- Modal -->
	<div class="modal" id="loadMe" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">

				<div class="modal-body text-center">
					<!-- <span class="dashboard-spinner spinner-primary spinner-xxl"></span> -->
					<div class="loader"></div>
					<div class="loader-txt">
						<p id="loading_msg"></p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
	include_once "layout_script.php";
	?>

	<script src="assets/js/select2.min.js"></script>
	<script>
		$('#site-1').select2({
			placeholder: "Sites",
			multiple: true
		});
		$('#site-2').select2({
			placeholder: "Sites",
			multiple: true
		});
		$('#site-3').select2({
			placeholder: "Sites",
			multiple: true
		});
		jQuery(document).ready(function($) {
			'use strict';

			$('#chef_item').select2();
			$('#chef_item_ctl').select2();
			$("#add_on_du").click(function() {
				$('#Du').datetimepicker('show');
			});
			$("#add_on_du_tech_ctl").click(function() {
				$('#Du_tech_ctl').datetimepicker('show');
			});
			$("#add_on_du_tech_inst").click(function() {
				$('#Du_tech_inst').datetimepicker('show');
			});
			$("#add_on_au").click(function() {
				$('#Au').datetimepicker('show');
			});
			$("#add_on_au_tech_ctl").click(function() {
				$('#Au_tech_ctl').datetimepicker('show');
			});
			$("#add_on_au_tech_inst").click(function() {
				$('#Au_tech_inst').datetimepicker('show');
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

			if ($("#Du_tech_ctl").length) {
				$('#Du_tech_ctl').datetimepicker({
					format: 'dd/mm/yyyy',
					language: 'fr',
					weekStart: 1,
					todayBtn: 1,
					autoclose: 1,
					minView: 2
				});

			}
			if ($("#Du_tech_inst").length) {
				$('#Du_tech_inst').datetimepicker({
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
			if ($("#Au_tech_ctl").length) {
				$('#Au_tech_ctl').datetimepicker({
					format: 'dd/mm/yyyy',
					language: 'fr',
					weekStart: 1,
					todayBtn: 1,
					autoclose: 1,
					minView: 2
				});

			}
			if ($("#Au_tech_inst").length) {
				$('#Au_tech_inst').datetimepicker({
					format: 'dd/mm/yyyy',
					language: 'fr',
					weekStart: 1,
					todayBtn: 1,
					autoclose: 1,
					minView: 2
				});

			}




		});
	</script>
	<!--
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('sidebar-collapse');
                $('#dashboard').toggleClass('dashboard-collapse');
            });
        });
   -->
</body>

</html>