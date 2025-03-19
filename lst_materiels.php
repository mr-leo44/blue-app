<?php
// session_start();
$mnu_title = "Matériel";
$page_title = "Liste des Matériels";
$home_page = "index.php";
$active = "lst_materiels";
$parambase = " active";

require_once 'vendor/autoload.php';
require_once 'loader/init.php';
//loading Classes filess
Autoloader::Load('classes');

include_once "core.php";
/*include_once "include/database_pdo.php";
 include_once "classes/class.utilisateur.php";
 include_once "classes/class.commune.php";
 include_once "classes/class.province.php";*/
$database = new Database();
$db = $database->getConnection();
$item = new Materiels($db);
$unite_de_mesure = new Unite_de_Mesure($db);
$utilisateur = new Utilisateur($db);


if ($utilisateur->is_logged_in() == false) {
	$utilisateur->redirect('login.php');
}
$utilisateur->readOne();
/*if($utilisateur->is_logged_in()=="")
 {
 	$utilisateur->redirect("login.php");
 }
 $utilisateur->code_utilisateur=$_SESSION["uSession"];
 $utilisateur->readOne();*/



$category_id = '10'; //Avenue
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
					<!-- ============================================================== -->
					<!-- Localisation form -->
					<!-- ============================================================== -->
					<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
						<div class="card">
							<h5 class="card-header"><?php echo $page_title; ?></h5>
							<div class="row">

								<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
									<div class="card">
										<div class="card-body">

											<div class="card bg-white font-semi-bold mt-3 mb-4">
												<div class="card-body">
													<div class="row">
														<div class="col-sm-12">
															<input type="text" id="srch-term-entity" name="s" class="form-control" placeholder="Recherche...">
														</div>
													</div>
												</div>
											</div>

											<div class="form-group">
												<div class="table-responsive table-bordered table-hover">
													<table class="table no-wrap p-table lignes_install ui-sortable" id="lignes_install">
														<thead>
															<tr>
																<th style="width:95%"><?php echo $mnu_title; ?></th>
																<th><a class="btn btn-xs " id="add_line"><i class="fas fa-plus"></i></a></th>
															</tr>
														</thead>
														<tbody>
															<?php
															if ($utilisateur->HasDroits("10_700")) {
																$stmt =	$item->read();
																while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
																	echo '<tr class="item-row" data-id="' . $row["ref_produit"] . '" code-unite="' . $row["unite_de_mesure"] . '" ><td style="width:95%"><h6 class="figure-title">' . $row["designation"] . '</h6> 
												<p class="text-muted mb-0 figure-unite">' . $row["libelle_unite"] . '</p></td><td><a class="btn btn-xs edit-item"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-xs delete-item"><i class="fas fa-trash"></i></a></td></tr>';
																}
															}


															?>
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
					<!-- end Localisation form -->
					<!-- ============================================================== -->

				</div>
			</div>
			<?php
			//include_once "layout_footer.php";
			?>
		</div>
	</div>
	<?php


	//include_once "layout_loader.php";
	include_once "layout_script.php";
	?>
	<div class="modal fade" id="dlg_main" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="dlg_title"></h5>
					<a href="#" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</a>
				</div>
				<div class="modal-body">
					<form id="dlg_frm" method="post" action="controller.php" enctype="multipart/form-data">
						<input name="code" id="code" type="hidden">
						<input name="view" id="view" type="hidden">
						<div class="form-group">
							<label><?php echo $mnu_title; ?></label>
							<div class="input-group" style="width: 100%;">
								<input type="text" class="form-control pull-right" name="libelle" id="libelle">
							</div>
						</div>
						<div class="form-group">
							<label>Unité de mesure</label>
							<select class='form-control select2' style='width: 100%;' name='code_unite' id='code_unite' required>
								<option selected='selected' value=''> </option>
								<?php
								$stmt_select = $unite_de_mesure->read();
								while ($row_select = $stmt_select->fetch(PDO::FETCH_ASSOC)) {
									echo "<option value=" . $row_select["code_unite"] . ">{$row_select["libelle_unite"]}</option>";
								}
								?>
							</select>
						</div>
						<div class="modal-footer ">
							<button type="button" class="btn btn-primary btn-lg" id="btn_save_"><span class="glyphicon glyphicon-ok-sign"></span> Valider</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="modal" id="loadMe" tabindex="-1" role="dialog" aria-hidden="true">
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
	<script src="assets/js/select2.min.js"></script>
	<script>
		$(function() {


			$('#dlg_main .select2').each(function() {
				var $sel = $(this).parent();
				$(this).select2({
					dropdownParent: $sel
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
			<?php //if($utilisateur->HasDroits("10_700")){  
			?>
			jQuery(document).delegate('a.delete-item', 'click', function(e) {
				e.preventDefault();
				var itemId = $(this).parents('tr.item-row').attr('data-id');
				var label = $('tr.item-row[data-id="' + itemId + '"]').find('h6.figure-title').html();

				swal({
					title: "Information",
					text: "Voulez-vous supprimer le <?php echo $mnu_title; ?>(" + label + ")?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: "#00A65A",
					confirmButtonText: "Oui",
					cancelButtonText: "Non",
					closeOnConfirm: false,
					closeOnCancel: true
				}, function(isConfirm) {
					if (isConfirm) {
						var view_mode = "delete_materiel";
						$.ajax({
							url: "controller.php",
							method: "POST",
							dataType: "json",
							data: {
								view: view_mode,
								code: itemId
							},
							success: function(result) {
								//  var result = $.parseJSON(item_data);
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
			<?php //} 
			?>


			$("#btn_save_").click(function() {
				var form = document.getElementById("dlg_frm");
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
									//ClearForm();
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

			<?php //if($utilisateur->HasDroits("12_38")){  
			?> $("#add_line").click(function() {

				ClearR();
				$("#dlg_frm #view").val("create_materiel");
				$("#dlg_title").html("Création <?php echo $mnu_title; ?>");
				$("#dlg_main").modal("show");
			});
			<?php //} 
			?>
			<?php if ($utilisateur->HasDroits("10_700")) {  ?>

				jQuery(document).delegate('a.edit-item', 'click', function(e) {
					e.preventDefault();
					var itemId = $(this).parents('tr.item-row').attr('data-id');
					var unite = $(this).parents('tr.item-row').attr('code-unite');
					var label = $('tr.item-row[data-id="' + itemId + '"]').find('h6.figure-title').html();
					$("#dlg_main #view").val("edit_materiel");
					$('#dlg_title').html('Modification <?php echo $mnu_title; ?>');
					$('#code').val(itemId);
					$('#libelle').val(label);
					$('#code_unite').val(unite).change();
					$('#dlg_main').modal('show');

				});

			<?php }  ?>

			$("#srch-term-entity").keyup(function() {
				var val = $(this).val().toString().toLowerCase();
				$('#lignes_install').find('.item-row').each(function(i) {
					var row = $(this);
					var client_label = row.find('h6.figure-title').html().toString().toLowerCase();
					var client_unite = row.find('h6.figure-unite').text().toString().toLowerCase();


					if (client_label.indexOf(val) != -1 || client_unite.indexOf(val) != -1) {
						row.show();
						//return false;
					} else row.hide();
				});

				if (!val)
					$('#lignes_install').find('.item-row').each(function(i) {
						$(this).show();
					});
			});

			function ClearR() {
				$('#srch-term-entity').val('');
				$('#code').val('');
				$('#libelle').val('');
				$('#code_unite').val('').change();
			}


		})
	</script>
</body>

</html>