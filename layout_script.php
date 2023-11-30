 
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
 $(function () {  
  
$('.not-implemented').click(function () {
swal("Information", "Fonctionnalité non implémentée", "error");
});


$('#btn_reconnect').click(function () {
	
	var frm = $("#frm_reconnect");
                                if (frm.parsley().validate()){
                                // alert("oui");				   
                                } else{
                                // alert("non");
									return false;
                                }
						 var form = document.getElementById("frm_reconnect"); 
				var formReconnect = new FormData(form);
				
		ShowLoader("Reconnexion en cours..."); 
									$.ajax({ 
										url:"controller.php",
										data: formReconnect,
										type:"POST",
										contentType:false,
										processData:false,
										cache:false,
										dataType:"json", 
										error:function(err){
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
											}, function (isConfirm) {
											});
										},
										success:function(result){ 
											try{  
												 if(result.error ==  0) {
													$('#reloginModal').hide();							 
													ClearRelogin();  
												 }else if(result.error == 1){
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
												}catch(erreur){
												swal({
														title: "Information",
														text: erreur,
														type: "error",
														showCancelButton: false,
														confirmButtonColor: "#DD6B55",
														confirmButtonText: "Ok",
														closeOnConfirm: true,
														closeOnCancel: false
													}, function (isConfirm) {
													}); 
												}
										},
										complete:function(){ 
											HideLoader();
										}
									});
});




 });
 
function ShowLoaderX(txt){
	 $("#loader").attr("data-text",txt);
	 $("#loader").addClass("is-active");
}

function HideLoaderX(){
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
   if(isset($utilisateur) && $utilisateur->HasDroits("10_430")){ 
	include_once "layout_edit_pwd.php";
   }
?>

<div class="modal " id="reloginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"style="z-index:99999;" >
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
        <div class="signup-section"><button type="button"  id="btn_reconnect" class="btn btn-primary btn-lg btn-block">Se connecter</button>
               </div>
      </div>
  </div>
</div>
</div>