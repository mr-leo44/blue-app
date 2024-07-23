<?php
// session_start(); 
$mnu_title = "Assignation Directe pour Contrôle";
$page_title = "Assignation Directe pour Contrôle";
$active = "assignation_control_masse";
$parambase = "";
$fichier_base = "controller_import.php";
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
// $dashview = new Dashviewer($db); 
if ($utilisateur->is_logged_in() == false) {
	$utilisateur->redirect('login.php');
}
$utilisateur->readOne();
?>
<!doctype html>
<html lang="fr">

<head>
	<?php
	include_once "layout_style.php";
	?>

</head>

<body>

	<div id="loader" class="loader loader-default"></div>
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
								<h2><?php echo $mnu_title; ?></h2>
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



					<!-- Content start  -->
					<?php
					//  if($page->HasDroits("190_10") == true){ 
					?>
					<div class="col-lg-12 pl-0 grid-margin stretch-card">
						<div class="card">
							<div class="card-body">
								<div class="page-header flex-wrap">
									<h4 class="mb-0">Liste des compteurs pour contrôle
										<span class="pl-0 h6 pl-sm-2 text-muted d-inline-block"></span>
									</h4>
								</div>

								<div class="page-header flex-wrap">
									<!--	<form method="post" action="reporting/journal_caisse.php" target="blank">  -->
									<form id="frm_main_rep_inpp" method="post" enctype="multipart/form-data">
										<div class="row">
											<div class="col-xl-9 col-lg-9 col-md-9 col-sm-12 col-12 mb-2 ">
												<label>*.XLSX </label>
												<div class="form-group">
													<input class="form-control" type="file" name="file" required />
												</div>
											</div>

											<div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12 mt-4">
												<button type="button" name="search" id="btn_import_rep_inpp" class="btn btn-primary pr-1 pl-1">
													<i class="fas fa-sync text-white mr-2"></i>Importer</button>
											</div>


										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<?php  //} 
					?>
					<!-- Content end  -->

					<!-- Content start  -->
					<?php
					//  if($page->HasDroits("190_10") == true){ 
					?>
					<div id="bloc_log" class="col-lg-12 pl-0 grid-margin stretch-card">
						<div class="card">
							<div class="card-body">
								<div id="box_log_import">
								</div>

							</div>
						</div>
					</div>
					<?php  //} 
					?>
					<!-- Content end  -->



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


			$('#btn_import_rep_inpp').click(function() {
				var frm = $("#frm_main_rep_inpp");
				if (frm.parsley().validate()) {
					// alert("oui");				   
				} else {
					// alert("non");
					return false;
				}
				$('#box_log_import').html("");
				$('#bloc_log').css({
					"display": "none"
				});
				var form = document.getElementById("frm_main_rep_inpp");
				var formData = new FormData(form);
				formData.append("view", "import-ctr-ctl");
				ShowLoaderX("Importation Compteurs pour assingation en cours...");
				$.ajax({
					url: "<?php echo $fichier_base; ?>",
					data: formData, // Add as Data the Previously create formData
					type: "POST",
					contentType: false,
					processData: false,
					cache: false,
					dataType: "json", // Change this according to your response from the server.
					error: function(err) {
						console.error(err);
						swal("Information", "Serveur non disponible", "error");
					},
					success: function(result) {
						//console.log(result);
						try {
							var str_log = '<div class="container py-5"><h4 class="text-center text-uppercase">DETAIL IMPORTATION</h4> ';

							if (result.nbre_success > 0) {
								str_log += '<h6>Compteurs assignées</h6><div class="alert alert-success"><strong>(' + result.nbre_success + ')</strong>  Compteur(s) assigné(s) avec succès</div> ';
							}
							if (result.nbre_error > 0) {
								str_log += '<h6>(' + result.nbre_error + ')Notification(s)</h6>';
								$.each(result.error_list, function(i, item) {
									if (item.error_type == "warning") {
										str_log += '<div class="alert alert-warning"><strong>(' + item.compteur + ')</strong> ' + item.error_message + '</div> ';
									} else if (item.error_type == "error") {
										str_log += '<div class="alert alert-danger"><strong>(' + item.compteur + ')</strong> ' + item.error_message + '</div> ';

									}

								});

							} else {

							}
							str_log += '</div>';
							// swal("Erreur!", str_log, "error");
							$('#box_log_import').html(str_log);
							$('#bloc_log').css({
								"display": "block"
							});
						} catch (erreur) {
							swal("Erreur!", erreur, "error");
						}
					},
					complete: function() {
						HideLoaderX();
					}
				});



			});







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