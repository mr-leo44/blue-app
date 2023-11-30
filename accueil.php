<?php
// session_start();
$mnu_title = "";
$page_title = "Accueil";
$active = "dashboard";
$parambase = "";

require_once 'loader/init.php';
//loading Classes filess
Autoloader::Load('classes');

/*
include_once 'include/database_pdo.php';
include_once 'classes/class.utilisateur.php'; 
include_once 'classes/class.province.php'; 
include_once 'classes/class.dashboard.php'; 
include_once 'classes/class.site.php'; 
include_once 'classes/class.utils.php'; */
include_once 'core.php';
header('Content-type: text/html;charset=utf-8');

// $database = new Database();
// $db = $database->getConnection();
$utilisateur = new Utilisateur($db);
$province_class = new Province($db);
$site_classe = new Site($db);
$dashview = new Dashviewer($db);




//$utilisateur->code_utilisateur=$_SESSION['uSession'];
if ($utilisateur->is_logged_in() == false) {
	$utilisateur->redirect('login.php');
}
$utilisateur->readOne();

//test tree
//echo ($utilisateur->GetUserFilterInstallation());//and code_installateur in ('007','005','008','009')
//echo ($utilisateur->GetUserFilterIdentification());//and identificateur in ('007','005','008','009')
//echo ($utilisateur->GetUserFilterControl());//and controleur in ('007','005','008','009')

//





$province = isset($_POST['province']) ? $_POST['province'] : '';
$site = isset($_POST['site']) ? $_POST['site'] : NULL;
$du = isset($_POST['Du']) ? Utils::ClientToDbDateFormat($_POST['Du']) : Utils::GetFirstDayOfCurrentMonth();
$au = isset($_POST['Au']) ? Utils::ClientToDbDateFormat($_POST['Au']) : Utils::GetLastDayOfCurrentMonth();
$du_ = isset($_POST['Du']) ? ($_POST['Du']) : Utils::DbToClientDateFormat(Utils::GetFirstDayOfCurrentMonth());
$au_ = isset($_POST['Au']) ? ($_POST['Au']) : Utils::DbToClientDateFormat(Utils::GetLastDayOfCurrentMonth());
$message = "Accueil";
$first_site = "";
if (isset($_POST['site']) && isset($_POST['Du']) && isset($_POST['Au'])) {
	$site_classe->code_site = $site;
	$site_classe->GetDetailIN();
	$message = "Production " . $site_classe->intitule_site . " DU " . $_POST['Du'] . " AU " . $_POST['Au'];
}
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
	<style>
		.ticket-replaced {
			background-color: #d359ff;
		}

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
	<?php
	include_once "layout_style.php";
	?>

	<style>
		.navbar {
			background: transparent !important;
			box-shadow: none !important;
			transition: .3s;
		}
		.navbar.bg-white.white{
			background: #fff!important;
		}
		
		.navbar::before {
			content: '';
			position: absolute;
			width: 19.3%;
			height: 100%;
			background: #fff;
			z-index: -1;
		}

		.navbar .nav-item.dropdown span {
			color: #fff !important;
		}
		.navbar.bg-white.white .nav-item.dropdown span{
			color: var(--colorTitle)!important;
		}
		.navbar.bg-white.white .item-site{
			color: #38d594!important;
			background: #f2fff2!important;
			border: 1px solid #38d594!important;
		}
		.item-site {
			border-color: transparent;
			color: #fff;
			background: #ffffff30;
		}

		.banner-linear {
			padding-top: 70px;
			padding-bottom: 90px;
			margin-top: -104px;
		}
	</style>
</head>

<body>

	<!-- graphic  file:///C:/Program%20Files%20(x86)/EasyPHP-DevServer-14.1VC11/data/localweb/template/concept-master%20(1)/concept-master/dashboard-sales.html ============================================================== -->
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
				<div class="banner-linear">
					<div class="container-fluid px-lg-4">
						<div class="d-flex justify-content-between align-items-end">
							<div class="text-star">
								<!-- <h6><?php echo $message; ?></h6> -->
								<h1>Bienvenu sur BlueApp</h1>
								<p>Votre système de monitoring intelligent</p>
							</div>
						</div>
					</div>
				</div>
				<div class="container-fluid dashboard-content px-lg-4 pt-0">
					<!-- ============================================================== -->
					<!-- pageheader  -->
					<!-- ============================================================== -->
					<!-- <div class="row">
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="page-header">
								<h2 class="pageheader-title"><?php echo $mnu_title; ?></h2>
								<div class="page-breadcrumb">
									<nav aria-label="breadcrumb">
										<ol class="breadcrumb">
											<li class="breadcrumb-item"><a href="#" class="breadcrumb-link"></a></li>
										</ol>
									</nav>
								</div>
							</div>
						</div>
					</div> -->
					<!-- ============================================================== -->
					<!-- end pageheader  -->
					<!-- ============================================================== -->
					<?php if ($utilisateur->HasDroits("12_20")) { ?>
						<div class="ecommerce-widget">

							<div class="row">
								<!-- ============================================================== -->
								<!-- validation form -->
								<!-- ============================================================== -->
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

									<div class="card block-search-site">

										<div class="card-body">
											<form class="needs-validation mb-0" method="post" action="accueil.php">
												<div class="form-row align-items-center">

													<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
														<label for="validationCustom04">Site</label>
														<div class="form-group">
															<select class="form-control" id="site" name="site" required>
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
																}
																while ($row_ = $site_array->fetch(PDO::FETCH_ASSOC)) {
																	//$options.= "<option value='{$row_["code_site"]}'>{$row_["intitule_site"]}</option>";
																	if ($deja == false) {
																		$deja = true;
																		if ($multi_access == true) {
																			$first_site = $MULTI_ACCESS_SITE_CODE;
																			if ($site == $first_site) {
																				echo "<option selected='selected' value='{$MULTI_ACCESS_SITE_CODE}'>{$MULTI_ACCESS_SITE_LABEL}</option>";
																			} else {
																				echo "<option value='{$MULTI_ACCESS_SITE_CODE}'>{$MULTI_ACCESS_SITE_LABEL}</option>";
																			}
																		} else {
																			$first_site = $row_["code_site"];
																		}
																	}

																	if ($site == $row_["code_site"]) {
																		echo "<option selected='selected' value='{$row_["code_site"]}'>{$row_["intitule_site"]}</option>";
																	} else {
																		echo "<option value='{$row_["code_site"]}'>{$row_["intitule_site"]}</option>";
																	}
																}
																if ($nbre_site_ == 0) {
																	if ($multi_access == true) {
																		$first_site = $MULTI_ACCESS_SITE_CODE;
																		// if($site == $first_site){
																		if ($site == $first_site || $site == NULL) {
																			echo "<option selected='selected' value='{$MULTI_ACCESS_SITE_CODE}'>{$MULTI_ACCESS_SITE_LABEL}</option>";
																		} else {
																			echo "<option value='{$MULTI_ACCESS_SITE_CODE}'>{$MULTI_ACCESS_SITE_LABEL}</option>";
																		}
																	}
																}
																//$options.= "<option value='{$row_["code_site"]}'>{$row_["intitule_site"]}</option>";



																if ($site == NULL) {
																	$site = $first_site;
																	$site_classe->code_site = $first_site;
																	$site_classe->GetDetailIN();
																	//$message="Production ".$site_classe->intitule_site." DU ".$du_." AU ".$au_;
																}
																?></select>
														</div>
													</div>
													<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2 ">
														<label for="validationCustom05">Du</label>
														<div class="form-group">
															<div class="input-group date">
																<input type="text" class="form-control datetimepicker-input" name="Du" id="Du" required value="<?php echo $du_; ?>" />
																<div class="input-group-append">
																	<div class="input-group-text" id="add_on_du"><i class="far fa-calendar-alt"></i></div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 mb-2 ">
														<label for="validationCustom05">Au</label>
														<div class="form-group">
															<div class="input-group date">
																<input type="text" class="form-control datetimepicker-input" name="Au" id="Au" required value="<?php echo $au_; ?>" />
																<div class="input-group-append">
																	<div class="input-group-text" id="add_on_au"><i class="far fa-calendar-alt"></i></div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-xl-1 col-lg-2 col-md-12 col-sm-12 col-12 ">
														<p class="text-right">
															<button type="submit" class="btn btn-primary w-100">
																<i class="fas fa-sync text-white"></i></button>
														</p>
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
							<div class="row">
								<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
									<div class="card card-1 card-widget">
										<div class="justify-between d-flex align-items-center">
											<div style="width: 70%" class="me-2">
												<h4>Identifications</h4>
											</div>
											<div style="width: 30%" class="d-flex justify-content-end">
												<div class="icon" style="background: #dc3545">
													<i class="fas fa-folder-open"></i>
												</div>
											</div>
										</div>
										<h5>
											<?php
											if ($site == $MULTI_ACCESS_SITE_CODE) {
												echo $dashview->GetAll_CompteurIdentified($utilisateur, $du, $au);
											} else {
												echo $dashview->GetSite_CompteurIdentified($utilisateur, $site, $du, $au);
											}

											?>
										</h5>
									</div>
									<!-- <div class="card ticket-annule">
										<div class="card-body">
											<h5 class="text-muted">IDENTIFICATION</h5>
											<div class="metric-value d-inline-block">
												<h1 class="mb-1 text-white"><?php
																			if ($site == $MULTI_ACCESS_SITE_CODE) {
																				echo $dashview->GetAll_CompteurIdentified($utilisateur, $du, $au);
																			} else {
																				echo $dashview->GetSite_CompteurIdentified($utilisateur, $site, $du, $au);
																			}

																			?></h1>


											</div>
											<div class="metric-label d-inline-block float-right text-success font-weight-bold">

											</div>
										</div>

									</div> -->
								</div>

								<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
									<div class="card card-1 card-widget">
										<div class="justify-between d-flex align-items-center">
											<div style="width: 70%" class="me-2">
												<h4>Installations</h4>
											</div>
											<div style="width: 30%" class="d-flex justify-content-end">
												<div class="icon" style="background: #2ec551">
													<i class="fas fa-handshake"></i>
												</div>
											</div>
										</div>
										<h5>
											<?php

											if ($site == $MULTI_ACCESS_SITE_CODE) {
												echo $dashview->GetAll_CompteurInstalled($utilisateur, $du, $au);
											} else {
												echo $dashview->GetSite_CompteurInstalled($utilisateur, $site, $du, $au);
											}
											?>
										</h5>
									</div>
									<!-- <div class="card ticket-produit">
										
										<div class="card-body">
											<h5 class="text-muted">INSTALLATION</h5>
											<div class="metric-value d-inline-block">
												<h1 class="mb-1 text-white"></h1>
											</div>
											<div class="metric-label d-inline-block float-right text-success font-weight-bold">

											</div>
										</div>

									</div> -->
								</div>
								<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
									<div class="card card-1 card-widget">
										<div class="justify-between d-flex align-items-center">
											<div style="width: 70%" class="me-2">
												<h4>Controles</h4>
											</div>
											<div style="width: 30%" class="d-flex justify-content-end">
												<div class="icon" style="background: #5969ff">
													<i class="fas fa-edit"></i>
												</div>
											</div>
										</div>
										<h5>
											<?php



											if ($site == $MULTI_ACCESS_SITE_CODE) {
												echo $dashview->GetAll_CompteurControled($utilisateur, $du, $au);
											} else {
												echo $dashview->GetSite_CompteurControled($utilisateur, $site, $du, $au);
											}
											?>
										</h5>
									</div>
									<!-- <div class="card ticket-total">
										<div class="card-body">
											<h5 class="text-muted">CONTROLE</h5>
											<div class="metric-value d-inline-block">
												<h1 class="mb-1 text-white"></h1>
											</div>
											<div class="metric-label d-inline-block float-right text-success font-weight-bold">
											</div>
										</div>
									</div> -->
								</div>
								<div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
									<div class="card card-1 card-widget">
										<div class="justify-between d-flex align-items-center">
											<div style="width: 70%" class="me-2">
												<h4>Remplacements</h4>
											</div>
											<div style="width: 30%" class="d-flex justify-content-end">
												<div class="icon" style="background: #d359ff">
													<i class="fas fa-cogs"></i>
												</div>
											</div>
										</div>
										<h5>
											<?php



											if ($site == $MULTI_ACCESS_SITE_CODE) {
												echo $dashview->GetAll_CompteurReplaced($utilisateur, $du, $au);
											} else {
												echo $dashview->GetSite_CompteurReplaced($utilisateur, $site, $du, $au);
											}
											?>
										</h5>
									</div>
									<!-- <div class="card" style="background-color: #d359ff;color: #fff !important;">
										<div class="card-body">
											<h5 style="color: #fff !important;">REMPLACEMENT</h5>
											<div class="metric-value d-inline-block">
												<h1 class="mb-1 text-white"></h1>
											</div>
											<div class="metric-label d-inline-block float-right text-success font-weight-bold">
											</div>
										</div>
									</div> -->
								</div>


							</div>
							<?php

							if ($site == $MULTI_ACCESS_SITE_CODE) {
								$synt = $dashview->GetAll_CVS_SYNTHE_Par_Date($utilisateur, $du, $au, $USER_SITE_PROVINCE);
								//var_dump($synt);
								//exit();
								$nbre_synthese = $synt["nbre_total"];
								$synthese = $synt["sites"];
							} else {
								$synthese = $dashview->GetSite_CVS_SYNTHE_Par_Date($utilisateur, $site, $du, $au, $USER_SITE_PROVINCE);
								$nbre_synthese = count($synthese);
							}
							?>
							<div class="row">
								<!-- ============================================================== -->

								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="card card-table-lg">
										<h3 class="section-title mb-4 d-flex align-items-center">Synthèse par CVS<span class="badge badge-secondary ml-3"><?php echo $nbre_synthese; ?></span></h3>
										<div class="row">
											<?php

											if ($site == $MULTI_ACCESS_SITE_CODE) {
												foreach ($synthese as $item_site) {
													foreach ($item_site as $item) {
											?>
														<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
															<div class="card card-widget card-bg">
																<div>
																	<h6 class="mb-0 text-card"><?php echo $item["CVS"]; ?></h6>
																</div>
																<div class="all-items d-flex flex-column">
																	<div class="item d-flex align-items-center justify-content-between">
																		<h4 class="mb-0 cvs-identif-text">Identification</h4>
																		<p><?php echo $item["nbre_identification"];  ?></p>
																	</div>
																	<div class="item d-flex align-items-center justify-content-between">
																		<h4 class="mb-0 cvs-install-text">Installation</h4>
																		<p><?php echo $item["nbre_installation"];  ?></p>
																	</div>
																	<div class="item d-flex align-items-center justify-content-between">
																		<h4 class="mb-0 cvs-control-text">Contrôle</h4>
																		<p><?php echo $item["nbre_controle"];  ?></p>
																	</div>
																</div>
																<!-- <div class="pard-body">
						
					</div> -->
																<!-- <div class="card-footer p-0 text-center d-flex justify-content-center ">
						<div class="pard-footer-item pard-footer-item-bordered">
							
							
						</div>
						<div class="pard-footer-item pard-footer-item-bordered">
							<h4 class="mb-0 cvs-install-text">Installation</h4>
							<p><?php echo $item["nbre_installation"];  ?></p>
						</div>
						<div class="pard-footer-item pard-footer-item-bordered">
							
						</div>
					</div> -->
															</div>
														</div>
													<?php

													}
												}
											} else {
												foreach ($synthese as $item) {
													?>
													<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
														<div class="card card-widget card-bg">
															<div>
																<h6 class="mb-0 text-card"><?php echo $item["CVS"]; ?></h6>
															</div>
															<div class="all-items d-flex flex-column">
																<div class="item d-flex align-items-center justify-content-between">
																	<h4 class="mb-0 cvs-identif-text">Identification</h4>
																	<p><?php echo $item["nbre_identification"];  ?></p>
																</div>
																<div class="item d-flex align-items-center justify-content-between">
																	<h4 class="mb-0 cvs-install-text">Installation</h4>
																	<p><?php echo $item["nbre_installation"];  ?></p>
																</div>
																<div class="item d-flex align-items-center justify-content-between">
																	<h4 class="mb-0 cvs-control-text">Contrôle</h4>
																	<p><?php echo $item["nbre_controle"];  ?></p>
																</div>
															</div>
														</div>
														<!-- <div class="card">

															<div class="pard-body">
																<h5 class="mb-0"><?php echo $item["CVS"]; ?></h5>
															</div>
															<div class="card-footer p-0 text-center d-flex justify-content-center ">
																<div class="pard-footer-item pard-footer-item-bordered">
																	<h4 class="mb-0 cvs-identif-text">Identification</h4>
																	<p><?php echo $item["nbre_identification"];  ?></p>
																</div>
																<div class="pard-footer-item pard-footer-item-bordered">
																	<h4 class="mb-0 cvs-install-text">Installation</h4>
																	<p><?php echo $item["nbre_installation"];  ?></p>
																</div>
																<div class="pard-footer-item pard-footer-item-bordered">
																	<h4 class="mb-0 cvs-control-text">Contrôle</h4>
																	<p><?php echo $item["nbre_controle"];  ?></p>
																</div>
															</div>
														</div> -->
													</div>
											<?php

												}
											}

											?>
										</div>
									</div>

								</div>


								<!-- end recent orders  -->
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
	<script>
		jQuery(document).ready(function($) {
			'use strict';

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



		});
		$(window).scroll(function() {

			if ($(this).scrollTop() > 40) {
				$(".navbar").addClass('white');

			}
			else {
				$(".navbar").removeClass('white');
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

<?php

$db = null;
?>

</html>