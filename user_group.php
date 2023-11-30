<?php
// session_start();
$mnu_title = "Groupes utilisateurs";
$page_title = "Liste des Groupes utilisateurs";
$home_page = "dashboard.php";
$active = "user_group";
$parambase = "";

require_once 'loader/init.php';
Autoloader::Load('classes');
include_once 'core.php';
header('Content-type: text/html;charset=utf-8');
$database = new Database();
$db = $database->getConnection();
$groupe = new GroupUtilisateur($db);  
$utilisateur = new Utilisateur($db); 
$categorisation_groupe = new Param_Categorisation_Groupe($db); 

if($utilisateur->is_logged_in() == false)
{
	$utilisateur->redirect('login.php');
}
$utilisateur->readOne();
/*
$utilisateur->code_utilisateur=$_SESSION['uSession'];
$utilisateur->readOne();
if($utilisateur->is_logged_in()=="")
{
	$utilisateur->redirect('login.php');
}*/
$search_term=isset($_GET['s']) ? $_GET['s'] : '';
$stmt = null;
$page_url = 'user_group.php?';
$records_per_page = 10;


// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;

if($search_term==''){	
	$stmt = $groupe->readAll($from_record_num, $records_per_page);
	$total_rows=$groupe->countAll(); 
}else{
	$page_url.="s={$search_term}&";
	$stmt = $groupe->search($search_term, $from_record_num, $records_per_page);	
	$total_rows=$groupe->countAll_BySearch($search_term);
}
$search_value=isset($search_term) ? "value='{$search_term}'" : "";
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
  bottom: 0; z-index: 8;
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
 cursor:pointer
}
/*
.text-small { font-size: 0.9rem !important; }

body { background: linear-gradient(to left, rgb(86, 171, 47), rgb(168, 224, 99)); }

.cursor-pointer { cursor: pointer; }*/
	</style>
<link href="assets/css/select2.css" rel="stylesheet">
        <link href="assets/css/parsley.css" rel="stylesheet">
 <?php 
include_once "layout_style.php";
?>

</head>

<body>
    <!-- ============================================================== -->
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
        <div class="dashboard-wrapper">
            
		<div class="container-fluid  dashboard-content">
                <!-- ============================================================== -->
                <!-- pageheader -->
                <!-- ============================================================== -->
				 <div class="row">
				  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                          <!--  <h2 class="pageheader-title"><?php echo $mnu_title; ?></h2>  -->
							<div id="breadcrumbs" class="clearfix">
					<a href="<?php echo 'user_group.php';?>" class="breadcrumbs_home"><i class='fas fa-users nav_icon'></i>  <?php echo $mnu_title; ?></a> <span class="raquo">»</span></div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader -->
                <!-- ============================================================== -->
                   <div class="row">
                        <!-- ============================================================== -->
                        <!-- bordered table -->
                        <!-- ============================================================== -->
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <div class="card-body">
												
											<div class="form-row">
												<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 mb-2">
													<label for="validationCustom03"><?php echo $page_title;?></label>
												</div>
												<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 ">
		<form method="get" role='search' id="frm_search"><div class="input-group mb-3">
			<input type="text" id="srch-term" name='s' class="form-control" placeholder="Recherche..."  required <?php echo $search_value;?>>
			<button type="submit" name="search" id="search-btn" class="btn btn-primary"><i class="fa fa-search"></i>
                </button>
               
													</div></form>
												</div> 
											</div>
                                    <table class="table table-responsive table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width:5%;"></th> 
                                                <th>Intitulé</th> 
                                                <th>Service</th> 
                                            </tr>
                                        </thead>
                                        <tbody>										
											<?php
												if($utilisateur->HasDroits("10_210"))
												{
												$num_line=0; 												 
												while ($row_groupe = $stmt->fetch(PDO::FETCH_ASSOC)){ 
														 $num_line++; 
												echo ' <tr>';
												
												echo '<td> 					 
														<button type="button" data-toggle="dropdown" class="btn btn-secondary dropdown-toggle" aria-expanded="false"></button>
														<ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(705px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">';
											if($utilisateur->HasDroits("10_230"))
											{			
												echo '<a href="#" class="dropdown-item edit"  data-id="'.$row_groupe["id_group"].'">Modifier</a>';
											}
											if($utilisateur->HasDroits("10_220"))
											{			
												echo '<a href="#" class="dropdown-item delete" data-name="'.$row_groupe["intitule"].'" data-id="'.$row_groupe["id_group"].'">Supprimer</a>';
											}
											if($utilisateur->HasDroits("12_54"))
											{			
												echo '<div class="dropdown-divider"></div><a href="#" class="dropdown-item law" data-name="'.$row_groupe["intitule"].'" data-id="'.$row_groupe["id_group"].'">Privilèges</a>';
											}
												echo '</ul>
													</td>';
													echo '<td>'.$row_groupe["intitule"].'</td>';
													$categorisation_groupe->code=$row_groupe["id_service"];
											$r=$categorisation_groupe->GetDetail();		
													
												echo '<td>'.$r["libelle"].'</td>';
												echo '</tr>';
												}
											}			 
												?>   										
                                        </tbody>
                                    </table>
									<div class="clearfix">
				
				<!-- <ul class="pagination"><li class="page-item disabled"><a class="page-link" href="#"><i class="fa fa-angle-double-left"></i></a></li><li class="page-item active"><a class="page-link" href="#">1</a></li><li class="page-item"><a class="page-link" href="stagiaire.php?page=2">2</a></li><li class="page-item"><a class="page-link" href="stagiaire.php?page=3">3</a></li><li class="page-item"><a class="page-link" href="stagiaire.php?page=4">4OO</a></li><li class="page-item"><a class="page-link" href="stagiaire.php?page=5">5</a></li><li class="page-item "><a href="stagiaire.php?page=6" class="page-link"><i class="fa fa-angle-double-right"></i></a></li></ul>
				 -->
				<?php
				  // paging buttons
					include_once 'layout_paging.php';
				?> 

                </div>
                                </div>
                            </div>
							
							
							
                        </div>
                        <!-- ============================================================== -->
                        <!-- end bordered table -->
                        <!-- ============================================================== -->
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
	 <?php
	if($utilisateur->HasDroits("10_240"))
	{		  echo  '<div class="btn-group-fab" role="group" aria-label="FAB Menu">
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
          <form id="mainForm" method="post" >
		   <input id="ref_id" type="hidden"> 
		   <input id="view_mode_" type="hidden"> 		 
        <div class="form-group">
                <label>Intitulé *</label>
                <div class="input-group date"  style="width: 100%;" > 
                  <input type="text" class="form-control pull-right" id="intitule" required>
                </div>                
        </div> 
                        <div class="form-group">
                            <label>Service *</label>			
                            <select class='form-control select2' style='width: 100%;' id='id_service' required >
                                <option selected='selected' disabled>Veuillez préciser</option>
<?php
$stmt_select_st = $categorisation_groupe->read();
while ($row_gp = $stmt_select_st->fetch(PDO::FETCH_ASSOC)) {
    echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
}
?></select>
                        </div>
      <div class="modal-footer ">
        <button type="button" class="btn btn-primary btn-lg" id="btn_save_" ><span class="glyphicon glyphicon-ok-sign"></span> Valider</button>
      </div>
      </form>
        </div> 
  </div> 
    </div>
 </div>
<div class="modal fade" id="dlg_droits" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
      <div class="modal-dialog  modal-lg">
    <div class="modal-content" style="background-color: #efeff6;">
	  <div class="modal-header">
                        <h2 id="title_law" class="h4 mb-1">Privilèges</h2>
                        <p class="small text-muted font-italic ml-auto">Veuillez cocher les privilèges du groupe.</p>
						<a href="#" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</a>
						</div>
<div class="modal-body">
<form <?php 	if($utilisateur->HasDroits("12_54"))
{ ?> action="controller.php" method="post" id="frm_law" <?php } ?>>
<?php 	if($utilisateur->HasDroits("12_54"))
{ ?> <input type="hidden" name="view" id="view"  value="grant_law"/><?php } ?>
<input type="hidden" name="group_id" id="view_id"  value=""/>
        
                        <div class="tab-vertical" id="search_results">
                           
                        </div>
                       
                  
				<?php 	if($utilisateur->HasDroits("12_54"))
		{  ?>
				<div class="modal-footer ">
        <button type="submit" class="btn btn-primary btn-lg" id="btn_save_law" ><span class="glyphicon glyphicon-ok-sign"></span> Valider</button>
      </div><?php }  ?></form>
  </div> 
  </div> 
    </div>
 </div>
 
 
 
<script src="assets/js/select2.min.js"></script>
<script src="assets/js/parsley.js"></script>
<script>
 $(function (){   
   $('#dlg_main .select2').each(function(){
            var $sel = $(this).parent();
            $(this).select2({
            dropdownParent:$sel
            });
            });
  //$('has-tooltip').tooltip();
	<?php 	if($utilisateur->HasDroits("10_220"))
		{  ?> $('.delete').click(function (e) {
			 e.preventDefault();
					  var name_actuel = jQuery(this).attr("data-name");					  
					  var jeton_actuel = jQuery(this).attr("data-id");						  
					swal({
										title: "Information",
										text: 'Voulez-vous supprimer le groupe ('+name_actuel+')?',
										type: "warning",
										showCancelButton: true,
										confirmButtonColor: "#00A65A",
										confirmButtonText: "Oui",
										cancelButtonText: "Non",
										closeOnConfirm: false,
										closeOnCancel: true
									}, function (isConfirm) {
										if (isConfirm) {          
											   var view_mode ="delete_group_user";
											  $.ajax({
												   url:"controller.php",
												   method:"POST",
												   data:{view:view_mode,id_group:jeton_actuel},
												   success:function(data)
												   {
													  var result = $.parseJSON(data);
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
																			window.location.reload();
																		});
																}else if( result.error == 1) {								
																	swal("Information", result.message, "error");	
																}
												   }
												  });
										} 
									});
						 
            });<?php }  ?>
	<?php 	if($utilisateur->HasDroits("10_240"))
		{  ?> $('#btn_new_').click(function () {		 
					 ClearForm();
					 $('#view_mode_').val("create_group_user");					 
					 $('#titre').html('CREATION NOUVEAU GROUPE UTILISATEUR');
					  $('#dlg_main').modal('show');
					  
            });<?php }  ?>
			
  function ClearForm(){
	$("#view_mode_").val("");
	$("#ref_id").val("");
	$("#intitule").val("");
  } 
	$('#btn_save_').click(function (){			 
				var z_code_ =$("#ref_id").val();
				var view_mode=$("#view_mode_").val();
				var z_intitule=$("#intitule").val(); 
				var z_categorie=$("#id_service").val(); 
				var frm = $("#mainForm");
			   if (frm.parsley().validate()){
					// alert("oui");				   
			   }else{
					// alert("non");
						return false;
			   }
				  $.ajax({
					   url:"controller.php",
					   method:"POST",
					   data: {view:view_mode, id_group:z_code_,intitule:z_intitule,id_service:z_categorie},
					   success : function(data, statut){ 
						    var result = $.parseJSON(data);
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
										ClearForm();
										$("#dlg_main").modal('hide');
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
					   },
					   error : function(resultat, statut, erreur){
					//		$inputs.prop("disabled", false);
					   }
				  });
			  });

<?php 	if($utilisateur->HasDroits("10_230"))
		{  ?>	$('.edit').click(function (e) {	
			e.preventDefault();
				 ClearForm();
				var jeton_actuel = jQuery(this).attr("data-id");
				//$('#Heading').html('CREATION NOUVEAU GROUPE UTILISATEUR');
				  $.ajax({
					   url:"controller.php",
					   method:"GET",
					   data: {view:'detail_group_user',id_group:jeton_actuel},
					   success : function(data, statut){ // success est toujours en place, bien sûr !
						    var result = $.parseJSON(data);
							$("#view_mode_").val("edit_group_user");
							$("#ref_id").val(result.data.id_group);
							$("#intitule").val(result.data.intitule);				 
							$("#id_service").val(result.data.id_service).change();				 
							$('#titre').html('MODIFICATION INFORMATIONS GROUPE');
							$('#dlg_main').modal('show');
					   },
					   error : function(resultat, statut, erreur){
					//		$inputs.prop("disabled", false);
					   }
				  });
			  });<?php }  ?>
			  
<?php 	if($utilisateur->HasDroits("12_53"))
		{  ?>
	$('.law').click(function (e) {	
		e.preventDefault();	
				 ClearForm();
				var jeton_actuel = jQuery(this).attr("data-name");	
				var id_ = jQuery(this).attr("data-id");	
				var view_mode="get_group_user_law";
				
				$('#view_id').val(id_);
				$('#title_law').html(jeton_actuel);
				//
				$.ajax({
					   url:"controller.php",
					   method:"GET",
					   data: {view:view_mode,group_id:id_},
					   success : function(data, statut){ 
						    var result = $.parseJSON(data);
							/*$("#view_mode_").val("edit_group_user");
							$("#ref_id").val(result.data.id_group);
							$("#intitule").val(result.data.intitule);				 
							$('#Heading').html('MODIFICATION INFORMATIONS GROUPE');
							$('#dlg_main').modal('show');*/
							
							$('#search_results').html('');
							//var result = JSON.parse(data);
							$('#search_results').append(result.data);
							/*$.each(result.data, function(key, value){
								$('#search_results').append(value);
							});*/
							//If no employees match the name that was searched for, display a
							//message saying that no results were found.
						/*	if(result.length == 0){
								$('#search_results').html('Pas de droits trouvés');
							}*/
							$('#dlg_droits').modal('show');
					   },
					   error : function(resultat, statut, erreur){
					   }
				  });
			  });<?php }  ?>
  })
  
</script>		

</body>
 
</html>