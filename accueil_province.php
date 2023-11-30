<?php
session_start();
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
include_once 'classes/class.utils.php';*/ 
include_once 'core.php';
header('Content-type: text/html;charset=utf-8');

$database = new Database();
$db = $database->getConnection();
$utilisateur = new Utilisateur($db); 
$province_class = new Province($db); 
$site_classe = new Site($db); 
$dashview = new Dashviewer($db); 
//$utilisateur->code_utilisateur=$_SESSION['uSession'];
if($utilisateur->is_logged_in() == false)
{
	$utilisateur->redirect('login.php');
}
$utilisateur->readOne();
$province=isset($_POST['province']) ? $_POST['province'] : '';
$site=isset($_POST['site']) ? $_POST['site'] : '';
$du=isset($_POST['Du']) ?ClientToDbDateFormat($_POST['Du']) : '';
$au=isset($_POST['Au']) ? ClientToDbDateFormat($_POST['Au']) : ''; 
$du_=isset($_POST['Du']) ?($_POST['Du']) : '';
$au_=isset($_POST['Au']) ? ($_POST['Au']) : ''; 
$message="Accueil";

if(isset($_POST['site']) && isset($_POST['Du']) && isset($_POST['Au'])){
	$site_classe->code_site=$site;
	$site_classe->GetDetailIN();
	
$message="Production ".$site_classe->intitule_site." DU ".$_POST['Du']." AU ".$_POST['Au'];
}

function ClientToDbDateFormat($c_date){	
		$n_date=str_ireplace('/','-',$c_date);
		$f_dt=date('Y-m-d',strtotime($n_date));
		return $f_dt;
	}
?>


<!doctype html>
<html lang="en">
<head>
<style>
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
                <div class="container-fluid dashboard-content ">
                    <!-- ============================================================== -->
                    <!-- pageheader  -->
                    <!-- ============================================================== -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
							<h2 class="pageheader-title"><?php echo $mnu_title;?></h2>
							<h2><?php echo $message; ?></h2>
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
 <?php if($utilisateur->HasDroits("12_20"))
				{ ?>                   
				   <div class="ecommerce-widget">

						<div class="row">
                        <!-- ============================================================== -->
                        <!-- validation form -->
                        <!-- ============================================================== -->
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
							
								<div class="card">
									
									<div class="card-body">
										<form class="needs-validation" method="post" action="accueil.php">
											<div class="row">
												
											<div class="form-row">
												<div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-12 mb-2">
													<label for="validationCustom03">Province</label>
													<select class="form-control" required id="province" name="province">
									<!--		<option value='ALL'>Toutes</option> -->
														<?php
													$stmt_province = $province_class->read();											
												while ($row_ = $stmt_province->fetch(PDO::FETCH_ASSOC)){
                                                             														if($province==$row_["code"]){
				echo "<option selected='selected'  value='{$row_["code"]}'>{$row_["libelle"]}</option>";
			}else{
					echo "<option value='{$row_["code"]}'>{$row_["libelle"]}</option>";
			}
															
														}
													?> 
														</select>  
												</div>
												<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">
													<label for="validationCustom04">Site</label>
													<div class="form-group">
									<select class="form-control"  id="site" name="site" required >
							<!-- 				<option value='ALL'>Toutes</option>  -->
	<?php					
$site_array=$site_classe->GetSiteAccessibleForProvince($utilisateur->code_utilisateur,$province); 
	 while ($row_ = $site_array->fetch(PDO::FETCH_ASSOC)){
					 //$options.= "<option value='{$row_["code_site"]}'>{$row_["intitule_site"]}</option>";
					 
					 if($site==$row_["code_site"]){
				echo "<option selected='selected' value='{$row_["code_site"]}'>{$row_["intitule_site"]}</option>";																									}else{
					echo "<option value='{$row_["code_site"]}'>{$row_["intitule_site"]}</option>";
				}
				 }
?></select>
													</div>
												</div>
												<div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-12 mb-2 ">
													<label for="validationCustom05">Du</label>	
													<div class="form-group" style="width : 135px;margin-right:120px;">
														<div class="input-group date" id="datetimepicker4" data-target-input="nearest">
															<input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker4" name="Du" required value="<?php echo $du_; ?>" />
															<div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
																<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-12 mb-2 ">
													<label for="validationCustom05">Au</label>	
													<div class="form-group" style="width : 135px;margin-right:120px;">
														<div class="input-group date" id="datepickerAu" data-target-input="nearest">
															<input type="text" class="form-control datetimepicker-input" data-target="#datepickerAu" name="Au" required value="<?php echo $au_; ?>" />
															<div class="input-group-append" data-target="#datepickerAu" data-toggle="datetimepicker">
																<div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
															</div>
														</div>
													</div>
												</div>
												<div class="col-xl-1 col-lg-1 col-md-12 col-sm-12 col-12 ">
													<label> </label>
													<p class="text-right">
                                                    <button type="submit" class="btn btn-primary">
													<i class="fas fa-sync text-white mr-2"></i>Actualiser</button>
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
                        <div class="row">						
							<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
									<div class="card ticket-annule">
										<div class="card-body">
											<h5 class="text-muted">IDENTIFICATION</h5>
											<div class="metric-value d-inline-block">
												<h1 class="mb-1 text-white"><?php echo $dashview->GetSite_CompteurIdentified($site,$du,$au); ?></h1>
											</div>
											<div class="metric-label d-inline-block float-right text-success font-weight-bold">
												<i class="far fa-eye-slash fa-4x text-white"></i>
	<!-- <span class="icon-circle-small icon-box-xs text-danger ml-4 bg-danger-light"><i class="fa fa-fw fa-arrow-down fa-4x"></i></span> -->
											</div> 
									</div>
								
								</div>
							</div>
							
							<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
									<div class="card ticket-produit">
										<div class="card-body">
											<h5 class="text-muted">INSTALLATION</h5>
											<div class="metric-value d-inline-block">
												<h1 class="mb-1 text-white"><?php echo $dashview->GetSite_CompteurInstalled($site,$du,$au); ?></h1>
											</div>
											<div class="metric-label d-inline-block float-right text-success font-weight-bold">
												<i class="far fa-money-bill-alt fa-4x text-white"></i> 
												<!-- <span class="icon-circle-small icon-box-xs text-success ml-4 bg-success-light fa-4x"><i class="fa fa-fw fa-arrow-up"></i></span> -->
											</div> 
									</div>
								
								</div>
							</div>
							<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
									<div class="card ticket-total">
										<div class="card-body">
											<h5 class="text-muted">CONTROLE</h5>
											<div class="metric-value d-inline-block">
												<h1 class="mb-1 text-white"><?php echo $dashview->GetSite_CompteurControled($site,$du,$au); ?></h1>
											</div>
											<div class="metric-label d-inline-block float-right text-success font-weight-bold">
												<i class="fas fa-cubes fa-4x text-white"></i>
												</div> 
									</div>							
								</div>
							</div>
							  
			
                        </div>
						<?php $synthese = $dashview->GetSite_CVS_SYNTHE_Par_Date($site,$du,$au); 
									$nbre_synthese = count($synthese);					?>
                        <div class="row">
                            <!-- ============================================================== -->
							
							<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="section-block" id="cardfooterlink">
                                    <h3 class="section-title">Synthèse par CVS<span class="badge badge-secondary ml-3"><?php echo $nbre_synthese; ?></span></h3>
                                  <!--   <p>Liste des cvs</p>  -->
                                </div>
                            </div>
							<?php
							
							foreach($synthese as $item){
								?>
								<div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 col-12">
                                <div class="card">
                                   
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
                                </div>
                            </div>
								<?php
								
							}
							
							
							?>
                      
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
<div class="modal" id="loadMe" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" >
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
	<script >
		jQuery(document).ready(function($) {
		'use strict';
			if ($("#datetimepicker4").length) {
					$('#datetimepicker4').datetimepicker({
						format: 'L'
					});

				}
			if ($("#datepickerAu").length) {
					$('#datepickerAu').datetimepicker({
						format: 'L'
					});

				}
				
			$("#province").on("change",function (e) {
			  var item=$(this).val();						 
			e.preventDefault();
			 $("#loading_msg").html("Chargement liste des sites en cours...");
			$("#loadMe").modal({
			  backdrop: "static", 
			  keyboard: false, 
			  show: true 
			});
			 $("#site").html('');
			  $.ajax({
					   url:"controller.php",
					   method:"GET",
					   data: {view:"get_province_site",id_:item},
					   success : function(data, statut){ 
					        /*  $("#loadMe").modal("hide").on('hidden.bs.modal', functionThatEndsUpDestroyingTheDOM);*/
					         
$('#loadMe').modal('hide');
//$('#loadMe').hide();
//$('#loadMe').attr('aria-hidden',"true");
//$('.modal-backdrop').hide();
//$('body').removeClass('modal-open');
						   try{
							   var result = $.parseJSON(data);
								 if(result.error == 0) {
									  $("#site").html(result.data);
									  // $('#btn_save_paie').show();
								}else if( result.error == 1) {	
								
									/*swal({
											title: "Information",
											text: result.message,
											type: "error",
											showCancelButton: false,
											confirmButtonColor: "#DD6B55",
											confirmButtonText: "Ok",
											closeOnConfirm: true,
											closeOnCancel: false
										}, function (isConfirm) {
										});*/ 
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
					   error : function(resultat, statut, erreur){
				//	$("#loadMe").modal("hide");
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