 <script type="text/javascript" src="assets/js/jquery-3.3.1.min.js" charset="UTF-8"></script>

 <!-- bootstap bundle js -->
 <script src="assets/js/bootstrap.bundle.js"></script>

 <!-- <script src="assets/js/moment.js"></script>
<script src="assets/js/tempusdominus-bootstrap-4.js"></script>  -->

 <script type="text/javascript" src="assets/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>

 <script type="text/javascript" src="assets/js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>

 <!-- SweetAlert Plugin Js -->
 <script src="assets/js/sweetalert.min.js"></script>
 <script src="assets/js/parsley.js"></script>
 <script>
 	// Fonction pour effectuer la déconnexion
 	function deconnexion() {
 		// Envoyer la requête de déconnexion au serveur en utilisant AJAX
 		// Assurez-vous d'ajuster l'URL de déconnexion en conséquence
 		$.ajax({
 			url: 'controller.php?view=logout',
 			type: 'POST',
 			// Ajouter les en-têtes ou données nécessaires
 			success: function(response) {
 				// Si la requête réussit, afficher une fenêtre modale (Swal) de notification
 				swal({
 					icon: 'success',
 					title: "Déconnexion automatique",
 					showConfirmButton: false,
 					showCancelButton: false,
 					type: "success",
 					text: "Vous avez été déconnecté avec succès !",
 					timer: 5000 // La notification disparaîtra après 2 secondes
 				})

 				setTimeout(function() {
 					location.reload()
 				}, 5000);
 			},
 			error: function(error) {
 				// En cas d'erreur lors de la déconnexion, afficher une fenêtre modale (Swal) d'erreur
 				swal({
 					icon: 'error',
 					type: "error",
 					title: "Déconnexion automatique",
 					text: 'Erreur lors de la déconnexion. Veuillez réessayer !',
 					showConfirmButton: false,
 					showCancelButton: false,
 					timer: 5000 // La notification d'erreur disparaîtra après 2 secondes
 				});
 			}
 		});
 	}

 	// Initialiser le minuteur d'inactivité
 	let minuteurInactivite;

 	// Fonction pour réinitialiser le minuteur à chaque interaction de l'utilisateur
 	function reinitialiserMinuteur() {
 		clearTimeout(minuteurInactivite);
 		minuteurInactivite = setTimeout(deconnexion, 5 * 60 * 1000); // minutes en millisecondes
 	}

 	// Ajouter des écouteurs d'événements pour les interactions utilisateur
 	document.addEventListener('mousemove', reinitialiserMinuteur);
 	document.addEventListener('keypress', reinitialiserMinuteur);

 	// Initialiser le minuteur au chargement de la page
 	reinitialiserMinuteur();
 </script>

 <script>
 	$(function() {

 		$('.not-implemented').click(function() {
 			swal("Information", "Fonctionnalité non implémentée", "error");
 		});


 		$('#btn_reconnect').click(function() {

 			var frm = $("#frm_reconnect");
 			if (frm.parsley().validate()) {
 				// alert("oui");				   
 			} else {
 				// alert("non");
 				return false;
 			}
 			var form = document.getElementById("frm_reconnect");
 			var formReconnect = new FormData(form);

 			ShowLoader("Reconnexion en cours...");
 			$.ajax({
 				url: "controller.php",
 				data: formReconnect,
 				type: "POST",
 				contentType: false,
 				processData: false,
 				cache: false,
 				dataType: "json",
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
 					try {
 						if (result.error == 0) {
 							$('#reloginModal').hide();
 							ClearRelogin();
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
 							text: erreur,
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




 	});

 	function ShowLoaderX(txt) {
 		$("#loader").attr("data-text", txt);
 		$("#loader").addClass("is-active");
 	}

 	function HideLoaderX() {
 		$("#loader").removeClass("is-active");
 	}

 	function Reconnect() {
 		$('#reloginModal').show();
 	}


 	function ClearRelogin() {
 		$('#username').val('');
 		$('#password').val('');
 	}
 </script>
 <?php
	if (isset($utilisateur) && $utilisateur->HasDroits("10_430")) {
		include_once "layout_edit_pwd.php";
	}
	?>

 <div class="modal " id="reloginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:99999;">
 	<div class="modal-dialog" role="document">
 		<div class="modal-content">
 			<div class="modal-header border-bottom-0">
 				<h5>Veuillez vous reconnecter puis recommencer l'opération précédente</h5>
 			</div>
 			<div class="modal-body">
 				<form id="frm_reconnect" action="controller.php" method="post">
 					<input name="view" value="reconnect" type="hidden">

 					<div class="form-group">
 						<input class="form-control form-control-lg" name="username" type="text" placeholder="Utilisateur" autocomplete="off" value="">
 					</div>
 					<div class="form-group">
 						<input class="form-control form-control-lg" name="password" type="password" placeholder="Mot de passe">
 					</div>
 				</form>
 			</div>
 			<div class="modal-footer d-flex justify-content-right">
 				<div class="signup-section"><button type="button" id="btn_reconnect" class="btn btn-primary btn-lg btn-block">Se connecter</button>
 				</div>
 			</div>
 		</div>
 	</div>
 </div>