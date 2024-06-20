<?php
// session_start();
$mnu_title = "";
$page_title = "Rapport compteurs scellés";
$active = "rpt_compteur_sceller";
$parambase = "";
require_once 'vendor/autoload.php';
require_once 'loader/init.php';
Autoloader::Load('classes');
include_once 'core.php';
header('Content-type: text/html;charset=utf-8');
$database = new Database();
$db = $database->getConnection();
$utilisateur = new Utilisateur($db);
$province_class = new Province($db);
$site_classe = new Site($db);
$dashview = new Dashviewer($db);
if ($utilisateur->is_logged_in() == false) {
	$utilisateur->redirect('login.php');
}
$utilisateur->readOne();
?>
<!doctype html>
<html lang="fr">

<head>
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
								<h2>Rapport des compteurs scellés</h2>
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
					<?php if ($utilisateur->HasDroits("12_20")) { ?>
						<div class="ecommerce-widget">



							<div class="row">
								<!-- ============================================================== -->
								<!-- validation form -->
								<!-- ============================================================== -->
								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

									<div class="card">

										<div class="card-body">
											<form method="post" action="reporting/rpt_compteur_scelle_xls.php" target="blank">
												<div class="row">

													<div class="form-row">

														<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
															<label for="validationCustom04">Site</label>
															<div class="form-group">
																<select class="form-control" id="site" name="site[]" required>
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
		$('#site').select2({
			placeholder: "Sites",
			multiple: true
		});
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