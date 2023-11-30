<?php 
$ref_user ="";
 if(isset($utilisateur)){ 
	$utilisateur->readone();
	$ref_user = $utilisateur->code_utilisateur;
 }
 
 
 ?>
<div class="modal fade" id="dlg_edit_pwd" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
      <div class="modal-dialog">
    <div class="modal-content">
         
		<div class="modal-header">
			<h5 class="modal-title" id="Heading">Mise à jour mot de passe</h5>
			<a href="#" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</a>
		</div>
          <div class="modal-body">
		 <form id="frm_edit_pwd"  method="post" enctype="multipart/form-data">  <input name="ref_id" id="ref_id" type="hidden" value="<?php echo $ref_user; ?>"> 
		   <input name="view" id="view" type="hidden" value="update_pwd"> 		 
        <div class="form-group">
                <label>Actuel Mot de passe</label>
                <div class="input-group"  style="width: 100%;" > 
                  <input type="password" class="form-control pull-right" name="cp" id="cp" required >
                </div>                
        </div>  		 
        <div class="form-group">
                <label>Nouveau Mot de passe</label>
                <div class="input-group"  style="width: 100%;" > 
                  <input type="password" class="form-control pull-right" name="np" id="np" required >
                </div>                
        </div>  		 
        <div class="form-group">
                <label>Ré-saisir Nouveau Mot de passe</label>
                <div class="input-group"  style="width: 100%;" > 
                  <input type="password" class="form-control pull-right" name="rp" id="rp" required >
                </div>                
        </div> 		 
        <div class="modal-footer ">
        <button type="button" class="btn btn-primary btn-lg" id="btn_edit_pwd" ><span class="glyphicon glyphicon-ok-sign"></span> Valider</button>
      </div> </form>
        </div> 
  </div> 
    </div>
 </div>
 
  <script src="assets/js/parsley.js"></script>
 <script>
  $(function () {  
 $('#btn_edit_pwd').click(function () {		 
				
				
					// Get the form
               var form = document.getElementById("frm_edit_pwd");
               var frm = $("#frm_edit_pwd");
			   
			   if(frm.parsley().validate()){
				  // alert("oui");				   
			   }else{
				  // alert("non");
				   return false;
			   }
				// Create a FormData and append the file with "image" as parameter name
				var formDataToUpload = new FormData(form);

									$.ajax({
                    //enctype: 'multipart/form-data',
										url:"controller.php",
										data: formDataToUpload,// Add as Data the Previously create formData
										type:"POST",
										contentType:false,
										processData:false,
                    cache:false,
										dataType:"json", // Change this according to your response from the server.
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
											console.log(result);
											try{ 
												 if(result.error == 0) {
												  swal({
																	title: "Information",
																	text: result.message,
																	type: "success",
																	showCancelButton: false,
																	confirmButtonColor: "#00A65A",
																	confirmButtonText: "Ok",
																	closeOnConfirm: true,
																	closeOnCancel: false
																}, function (isConfirm) {
																	//ClearForm();
																	//ClearMaterielsRow();
																	//$("#dlg_main").modal('hide');
																	window.location.reload();
																});
																}else if( result.error == 1) {
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
														}, function (isConfirm) {
														}); 
												}
												}catch(erreur){
												swal({
														title: "Information",
														text: "Echec d'execution de la requete",
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
											console.log("Request finished.");
										}
									});
					
			  });
			})
	</script>