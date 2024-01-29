<?php
// session_start();
$mnu_title = "Dispatching Contrôle";
$page_title = "Journal Dispatching contrôles";
$home_page = "index.php";
$active = "dispatch_control";
$parambase = "";
$create_view = 'dispatch_control_set_user';
$edit_view = '';
$delete_view = '';
require_once 'vendor/autoload.php';
require_once 'loader/init.php';
//loading Classes filess
Autoloader::Load('classes');

$page_url = 'dispatch_control.php?';
include_once "core.php";
/*include_once "include/database_pdo.php";
 include_once "classes/class.utilisateur.php";
 include_once "classes/class.commune.php";
 include_once "classes/class.province.php";*/
$database = new Database();
$db = $database->getConnection();
$item = new PARAM_Assign($db);
$utilisateur = new Utilisateur($db);

$search_param = isset($_REQUEST['srch-term-client']) ? $_REQUEST['srch-term-client'] : "";
// var_dump($search_param);
// exit;
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

$records_per_page = 100;
// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;
$total_rows = 0;
$category_id = '10'; //Avenue
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

	<link href="assets/css/parsley.css" rel="stylesheet">
	<link href="assets/css/materialdesignicons.min.css" rel="stylesheet">
	<link href="assets/css/select2.css" rel="stylesheet">
	<?php
	include_once "layout_style.php";
	?>
	<?php


	//include_once "layout_loader.php";
	include_once "layout_script.php";
	?>
</head>

<body>

	<div id="loader" class="loader loader-default"></div>
	<div class="dashboard-main-wrapper">
		<?php
		include_once "layout_top_bar.php";
		include_once "layout_side_bar.php";
		?>
		<script>
			function ShowLoader(txt) {
				$("#loader").attr("data-text", txt);
				$("#loader").addClass("is-active");
			}

			function HideLoader() {
				$("#loader").removeClass("is-active");
			}


			ShowLoader("Chargement en cours...");
		</script>
		<div class="dashboard-wrapper">

			<form id="mainForm" enctype="multipart/form-data">
				<div class="container-fluid  dashboard-content">


					<div class="row">

						<!-- ============================================================== -->
						<!-- Localisation form -->
						<!-- ============================================================== -->
						<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							<div class="card">

								<div class="card-header d-flex">
									<div>
										<h4 class="mb-0 text-primary"><?php echo $page_title; ?></h4>
									</div>
									<?php
									if ($utilisateur->HasDroits("10_615")) { ?>
										<div class="dropdown ml-auto">
											<a class="toolbar" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-dots-vertical"></i> </a>
											<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" x-placement="bottom-end" style="position: absolute; transform: translate3d(-160px, 23px, 0px); top: 0px; left: 0px; will-change: transform;">
												<a href="#" class="dropdown-item approve-install">Assigner Technicien</a>

											</div>
										</div>
									<?php     }

									$item->type_assignation = "1";	// Control
									$list_items = $item->GetOrganeControlAssignedSearch($utilisateur, $search_param, $from_record_num, $records_per_page);
									$total_rows = $item->GetOrganeControlAssignedSearchAll($utilisateur, $search_param);

									// $paginate_now = new CLS_Paginate();	

									$_items =	$list_items['items'];

									?>

								</div>


								<div class="row">

									<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
										<div class="card">
											<div class="card-body">

												<div class="card bg-white font-semi-bold mt-3 mb-4">
													<div class="card-body">
														<div class="row">
															<div class="col-sm-12">
																<div id="record_count" class="font-semi-bold" style="color:#5e6e82;font-size:16px">

																	<?php echo count($_items) . ' Elément(s)'; ?></div>
																<div class="input-group mt-1">
																	<form action="dispatch_control.php" method="post"><input type="text" id="srch-term-client" name="srch-term-client" class="form-control" placeholder="Recherche..." value="<?php echo $search_param; ?>">
																		<button type="submit" name="search" id="search-btn" class="btn btn-primary"><i class="fa fa-search"></i>
																		</button>
																	</form>
																</div>
															</div>
														</div>
													</div>
												</div>

												<div id="client_lst">
													<?php

													// var_dump($_items);
													// exit;
													set_time_limit(0);
													foreach ($_items as $row) {

														/*
											$organisme->ref_organisme=$row_["id_equipe"];
														$organisme->GetDetailIN();
														
														$cvs->code=$row_["cvs_id"];
														$cvs->GetDetailIN();
														$ctl_rw=$utilisateur->readDetail($row_["code_installateur"]);
														$statut_installation->code =  $row_["statut_installation"];
														$row_statut = $statut_installation->GetDetail();
											*/
														//$operation= "Installation"; 
														$rendez_vous = '';
														if (!empty($row['data']['date_rendez_vous'])) {
															$rendez_vous = '<span class="badge badge-primary client-rendez-vous">' . $row['data']['date_rendez_vous_fr'] . '</span>';
														}


													?>

														<div class="client-row card bg-white">
															<div class="card-header d-flex">
																<div>
																	<div class="text-dark">Client </div>
																	<h4 class="mb-0 text-primary client-name"><?php echo $row['data']['nom_client_blue'] . ' ' . $rendez_vous; ?></h4>
																</div>
															</div>
															<div class="card-body">


																<?php
																$has_technician = isset($row['technicien']) ? strlen($row['technicien']) : 0;

																if ($utilisateur->HasDroits("10_920") || $has_technician == 0) {   ?>

																	<div class="custom-control custom-checkbox"><input class="custom-control-input" id="chk_<?php echo $row['data']['id_assign']; ?>" name="tbl-checkbox[]" type="checkbox" value="<?php echo  $row['data']['id_assign']; ?>" data-parsley-multiple="tbl-checkbox"><label class="cursor-pointer font-italic d-block custom-control-label" for="chk_<?php echo  $row['data']['id_assign']; ?>"> </label></div>

																<?php }
																if ($has_technician > 0) {	  ?>
																	<span class="badge badge-success ">Déjà assigné au technicien</span>
																<?php } ?>
																<div class="row">
																	<div class="col-sm-3">
																		<div class="text-dark">Adresse</div>
																		<div class="font-medium text-primary client-adress"><?php echo  $row['adresseTexte']; ?></div>
																	</div>
																	<div class="col-sm-3">
																		<div class="text-dark">REFERENCE APPARTEMENT</div>
																		<div class="font-medium text-primary client-adress"><?php echo  $row['data']['reference_appartement']; ?></div>
																	</div>
																	<div class="col-sm-3 text-center">
																		<div class="text-dark">Téléphone</div>
																		<div class="font-medium text-primary client-phone"><?php echo $row['data']['phone_client_blue']; ?></div>
																	</div>
																	<div class="col-sm-3 text-right">
																		<div class="text-dark">CVS </div>
																		<div class="font-medium text-primary client-cvs"><?php echo  $row['data']['libelle']; ?></div>
																	</div>
																</div>
																<div class="row">
																	<div class="col-sm-3">
																		<div class="text-dark">Compteur </div>
																		<div class="font-medium text-primary client-device"><?php echo  $row['data']['num_compteur_actuel']; ?> </div>
																	</div>


																	<div class="col-sm-3  text-center">
																		<div class="text-dark">Technicien </div>
																		<div class="font-medium text-primary client-technician"><?php echo  $row['technicien']; ?></div>
																	</div>

																	<div class="col-sm-3 text-right">
																		<div class="text-dark">Chef équipe </div>
																		<div class="font-medium text-primary client-chief"><?php echo  $row['chef']; ?></div>
																	</div>

																</div>
															</div>
														</div>


													<?php 		}



													?>






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
			</form>
			<?php
			include_once "layout_paging.php";
			?>
		</div>
	</div>
	<?php


	//include_once "layout_loader.php";
	// include_once "layout_script.php";
	?>



	<div class="modal" id="box_technicien" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog  modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 id="notification_title" class="modal-title">Assignation technicien</h4>
					<a href="#" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</a>
				</div>
				<div class="modal-body text-center">
					<form id="frm_approbation" enctype="multipart/form-data">

						<div class="form-group text-left" id='bloc_installateur'>
							<label>Technicien</label>
							<select class='form-control select2' style='width: 100%;' id='list_installateurs_secondaire' name='technicien' required>
								<option value=''>Veuillez préciser</option>
								<?php
								$stmt_chief = null;
								if ($utilisateur->id_service_group ==  '3') {  //Administration
									$stmt_chief = $utilisateur->GetAllControleur();
								} else {
									$stmt_chief = $utilisateur->GetAllChiefLinkedUsers($utilisateur->code_utilisateur);
								}
								while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
									echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
								}

								?>
							</select>
						</div>
						<div class="text-center">
							<button id="btn_submit_dispatch" type="button" class="btn btn-primary btn-fill float-right">Valider</button>
						</div>
						<div class="clearfix"></div>
					</form>
				</div>
			</div>
		</div>
	</div>



	<script src="assets/js/parsley.js"></script>
	<script src="assets/js/select2.min.js"></script>
	<script>
		$(function() {
			HideLoader();
			$('#box_technicien .select2').each(function() {
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

			$("#btn_submit_dispatch").click(function() {

				var frm = $("#frm_approbation");
				if (frm.parsley().validate()) {
					// alert("oui");				   
				} else {
					// alert("non");
					return false;
				}


				var tech = $("#list_installateurs_secondaire").val();
				var form = document.getElementById("mainForm");
				var formData = new FormData(form);
				formData.append('technicien', tech);
				formData.append('view', "<?php echo $create_view; ?>");
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
									$("#dlg_main").hide();
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
			/*
 $("#srch-term-entity").keyup(function(){
            var val = $(this).val().toString().toLowerCase();
                    $('#client_lst').find('.client-row').each(function(i){
            var row = $(this);
            var client_name =  row.find('.client-name').text().toString().toLowerCase();
            var client_adress =  row.find('.client-adress').text().toString().toLowerCase();
            var client_device =  row.find('.client-device').text().toString().toLowerCase();
            var client_phone =  row.find('.client-phone').text().toString().toLowerCase();
            var client_cvs =  row.find('.client-cvs').text().toString().toLowerCase();
            var client_technician =  row.find('.client-technician').text().toString().toLowerCase();
            var client_chief =  row.find('.client-chief').text().toString().toLowerCase();
            var client_install_mode =  row.find('.client-mode-install').text().toString().toLowerCase();
            var client_rendez_vous =  row.find('.client-rendez-vous').text().toString().toLowerCase();
           
 
			if (client_rendez_vous.indexOf(val) != - 1||client_install_mode.indexOf(val) != - 1||client_name.indexOf(val) != - 1||client_chief.indexOf(val) != - 1||client_technician.indexOf(val) != - 1||client_adress.indexOf(val) != - 1
			||client_device.indexOf(val) != - 1||client_phone.indexOf(val) != - 1||client_cvs.indexOf(val) != - 1)
            {
            row.show();
                   // return false;
            }
            else  row.hide(); 
            });
			
            if (!val)
                    $('#client_lst').find('.client-row').each(function(i){
            $(this).show();
            });
            });*/

			function ClearR() {
				$('#srch-term-entity').val('');
				$('#code').val('');
				$('#libelle').val('');
			}


			<?php if ($utilisateur->HasDroits("10_615")) { ?>
				$('.approve-install').click(function(e) {
					e.preventDefault();
					var selec = 0;

					$('#client_lst input:checkbox').each(function() {

						if ($(this).prop('checked') == true) {
							selec++;
						}
					});

					if (selec == 0) {
						swal("Information", "Veuillez sélectionner les adresses à assigner", "error");
						return false;
					}
					/*var jeton_actuel = jQuery(this).attr("data-id-install");
					var num_cpteur = jQuery(this).attr("data-compteur-install");
					$("#id_").val(jeton_actuel);
					$("#frm_approbation #view").val("create_install_approve");						
					$("#bloc_installateur").hide();					
					$("#btn_submit_cloture").hide();
					$("#btn_submit_approve").show();
					$("#approve_compteur").html(num_cpteur);
					$("#notification_title").text("Approbation Installation");	*/
					//$("#btn_submit_approve").text("Approuver");	 
					$("#box_technicien").show();
				});



				$('#btn_submit_approve').click(function() {
					var form = document.getElementById("frm_approbation");
					var formApprove = new FormData(form);
					// $("#btn_submit_approve").attr('disabled','disabled');
					// $("#btn_submit_approve").removeClass('btn-success');
					// $("#btn_submit_approve").text("Dispatching en cours ...");	  
					ShowLoader("Dispatching en cours ...");
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
							// console.error(err);
							// $("#btn_submit_approve").removeAttr('disabled');
							// $("#btn_submit_approve").addClass('btn-success');
							// $("#btn_submit_approve").text("Approuver");
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
									$("#btn_submit_approve").text("Dispatching terminée.");
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
									// $("#btn_submit_approve").removeAttr('disabled');
									// $("#btn_submit_approve").text("Approuver");
									// $("#btn_submit_approve").addClass('btn-success');

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
								// $("#btn_submit_approve").removeAttr('disabled');
								// $("#btn_submit_approve").addClass('btn-success');
								// $("#btn_submit_approve").text("Approuver");
							}
						},
						complete: function() {
							HideLoader();
						}
					});

				});





			<?php   }  ?>


		})
	</script>
</body>

</html>