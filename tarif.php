<?php

// session_start();
$mnu_title = "Tarif";
$page_title = "Liste des Tarifs";
$home_page = "index.php";
$active = "tarif";
$parambase = " active";
$page_root = "tarif.php";
require_once 'vendor/autoload.php';
require_once 'loader/init.php';
Autoloader::Load('classes');
include_once "core.php";

/*
 include_once "include/database_pdo.php";
 include_once "classes/class.utilisateur.php";
 include_once "classes/class.tarif.php"; */
$database = new Database();
$db = $database->getConnection();
$item = new Tarif($db);

$utilisateur = new Utilisateur($db);
if ($utilisateur->is_logged_in() == "") {
	$utilisateur->redirect("login.php");
}
$utilisateur->code_utilisateur = $_SESSION["uSession"];
$utilisateur->readOne();
$search_term = isset($_GET["s"]) ? $_GET["s"] : "";
$stmt = null;
$page_url = "tarif.php?";

// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;
if ($search_term == "") {
	$stmt = $item->readAll($from_record_num, $records_per_page);
	$total_rows = $item->countAll();
} else {
	$page_url .= "s={$search_term}&";
	$stmt = $item->search($search_term, $from_record_num, $records_per_page);
	$total_rows = $item->countAll_BySearch($search_term);
}
$search_value = isset($search_term) ? "value='{$search_term}'" : "";
?>
<!doctype html>
<html lang="en">

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
	</style>
	<link href="assets/css/select2.css" rel="stylesheet">
	<?php
	include_once "layout_style.php";
	?>
</head>

<body>
	<div class="dashboard-main-wrapper">
		<?php
		include_once "layout_top_bar.php";
		include_once "layout_side_bar.php";
		?>
		<div class="dashboard-wrapper">
			<div class="container-fluid  dashboard-content">
				<div class="row">
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="page-header">
							<div id="breadcrumbs" class="clearfix">
								<a href="<?php echo $page_root; ?>" class="breadcrumbs_home"><i class="fab fa-fw fa-wpforms nav_icon"></i> <?php echo $mnu_title; ?></a> <span class="raquo">»</span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="card">
							<div class="card-body">
								<div class="form-row">
									<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 mb-2">
										<label for="validationCustom03"><?php echo $page_title; ?></label>
									</div>
									<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 ">
										<form method="get" role="search" id="frm_search">
											<div class="input-group mb-3">
												<input type="text" id="srch-term" name="s" class="form-control" placeholder="Recherche..." required <?php echo $search_value; ?>>
												<button type="submit" name="search" id="search-btn" class="btn btn-primary"><i class="fa fa-search"></i>
												</button>
											</div>
										</form>
									</div>
								</div>
								<table class="table table-bordered table-hover">
									<thead>
										<tr>
											<th style="width:5%;"></th>
											<th style="width:30%;">Code</th>
											<th>Libellé</th>
										</tr>
									</thead>
									<tbody>
										<?php
										if ($utilisateur->HasDroits("10_521")) {
											$num_line = 0;
											while ($row_ = $stmt->fetch(PDO::FETCH_ASSOC)) {
												// $num_line++; 
												//echo "<tr><td>".$num_line."</td>";
												echo '<td>
 											<button type="button" data-toggle="dropdown" class="btn btn-secondary dropdown-toggle" aria-expanded="false"></button>
 								<ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(705px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">';
												if ($utilisateur->HasDroits("10_460")) {
													echo '<a href="#" class="dropdown-item edit"  data-id="' . $row_["code"] . '">Modifier</a>';
												}
												if ($utilisateur->HasDroits("10_460")) {
													echo '<a href="#" class="dropdown-item delete" data-name="' . $row_["libelle"] . '" data-id="' . $row_["code"] . '">Supprimer</a>';
												}
												echo '</ul></td>';
												echo "<td>" . $row_["code"] . "</td>";
												echo "<td>" . $row_["libelle"] . "</td>";
										?>

												</tr>
										<?php			}
										}			?>
									</tbody>
								</table>
								<div class="clearfix">
									<?php
									include_once "layout_paging.php";
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			//include_once "layout_footer.php";
			?>
		</div>
	</div>
	<?php
	if ($utilisateur->HasDroits("10_460")) {
		echo  '<div class="btn-group-fab" role="group" aria-label="FAB Menu">
 	  <div>
 		<button type="button" class="btn btn-main btn-primary has-tooltip" data-placement="left" title="Menu" id="btn_new_"> <i class="fa fa-plus"></i> </button>
 	  </div>
 	</div>';
	}
	include_once "layout_script.php";
	?>
	<div class="modal fade" id="dlg_main" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="titre"></h5>
					<a href="#" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</a>
				</div>
				<div class="modal-body">
					<form id="mainForm" method="post" action="controller.php" enctype="multipart/form-data">
						<input name="view" id="view" type="hidden">
						<div class="form-group">
							<label>Code</label>
							<div class="input-group" style="width: 100%;">
								<input type="text" class="form-control pull-right" name="code" id="code">
							</div>
						</div>
						<div class="form-group">
							<label>Libellé</label>
							<div class="input-group" style="width: 100%;">
								<input type="text" class="form-control pull-right" name="libelle" id="libelle">
							</div>
						</div>
						<div class="modal-footer ">
							<button type="button" class="btn btn-primary btn-lg" id="btn_save_"><span class="glyphicon glyphicon-ok-sign"></span> Valider</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	<script src="assets/js/select2.min.js"></script>
	<script>
		$(function() {
			$("#dlg_main .select2").each(function() {
				var $sel = $(this).parent();
				$(this).select2({
					dropdownParent: $sel
				});
			});
			<?php if ($utilisateur->HasDroits("10_460")) {  ?> $(".delete").click(function() {
					var name_actuel = jQuery(this).attr("data-name");
					var jeton_actuel = jQuery(this).attr("data-id");
					swal({
						title: "Information",
						text: "Voulez-vous supprimer  (" + name_actuel + ")?",
						type: "warning",
						showCancelButton: true,
						confirmButtonColor: "#00A65A",
						confirmButtonText: "Oui",
						cancelButtonText: "Non",
						closeOnConfirm: false,
						closeOnCancel: true
					}, function(isConfirm) {
						if (isConfirm) {
							var view_mode = "delete_tarif";
							$.ajax({
								url: "controller.php",
								method: "POST",
								data: {
									view: view_mode,
									code: jeton_actuel
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
			<?php } ?>
			<?php if ($utilisateur->HasDroits("10_460")) {  ?> $("#btn_new_").click(function() {
					ClearForm();
					$("#dlg_main #view").val("create_tarif");
					$("#titre").html("CREATION TARIF");
					$("#dlg_main").modal("show");
				});
			<?php } ?>

			function ClearForm() {
				$("#dlg_main #view").val("");
				$("#code").val("");
				$("#libelle").val("");
			}
			$("#btn_save_").click(function() {
				var form = document.getElementById("mainForm");
				var formData = new FormData(form);
				$.ajax({
					url: "controller.php",
					method: "POST",
					dataType: "json",
					data: formData,
					contentType: false,
					processData: false,
					cache: false,
					success: function(result, statut) {
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
									$("#dlg_main").modal("hide");
									window.location.reload();
								});
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
					}
				});
			});
			<?php if ($utilisateur->HasDroits("10_460")) {  ?>
				$(".edit").click(function() {
					ClearForm();
					var jeton_actuel = jQuery(this).attr("data-id");
					$("#titre").html("MODIFICATION INFORMATIONS TARIF");
					$.ajax({
						url: "controller.php",
						method: "GET",
						dataType: "json",
						data: {
							view: "detail_tarif",
							code: jeton_actuel
						},
						success: function(result, statut) {
							$("#dlg_main #view").val("edit_tarif");
							$("#code").val(result.data.code);
							$("#code").val(result.data.code);
							$("#libelle").val(result.data.libelle);
							$("#dlg_main").modal("show");
						},
						error: function(resultat, statut, erreur) {}
					});
				});
			<?php }  ?>
		})
	</script>
</body>

</html>