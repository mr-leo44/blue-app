<?php

// session_start();
$mnu_title = "ASSIGNATION POUR REMPLACEMENT";
$page_title = "Historique des assignations pour remplacement";
$home_page = "lst_assign_replace.php";
$active = "lst_assign_replace";
$parambase = "";

require_once 'vendor/autoload.php';
require_once 'loader/init.php';
Autoloader::Load('classes');
include_once 'core.php';
header('Content-type: text/html;charset=utf-8');
$database = new Database();
$db = $database->getConnection();
//$Abonne = new PARAM_Assign_install($db);
$Abonne = new PARAM_Assign($db);
$Abonne->type_assignation = '3'; // Remplacement
$utilisateur = new Utilisateur($db);
// $commune = new Commune($db);
$etat_poc = new PARAM_EtatPOC($db);
$pTypeDefaut = new Param_TypeDefaut($db);
$statut_installation = new PARAM_StatutInstallation($db);
$materiel = new Materiels($db);
$organisme = new Organisme($db);
$type_client = new TypeClient($db);
$yes_no = new PARAM_YesNo($db);
$conformity_install = new PARAM_ConformityInstall($db);
$tarif = new Tarif($db);
$cvs = new CVS($db);
$accessib = new Param_Accessibility($db);
$raccordement = new Param_Raccordement($db);
$statut_personne = new Param_Statut_Personne($db);
$type_compteur = new Param_TypeCompteur($db);
$type_usage = new Param_TypeUsage($db);
$marquecompteur = new MarqueCompteur($db);

$adress_item = new  AdresseEntity($db);
if ($utilisateur->is_logged_in() == false) {
    $utilisateur->redirect('login.php');
}


/*
  if(!isset($_SESSION['uSession'])){
  $utilisateur->redirect('login.php');
  } */
//$utilisateur->code_utilisateur=$_SESSION['uSession'];
$utilisateur->readOne();
/* if($utilisateur->is_logged_in()=="")
  {
  $utilisateur->redirect('login.php');
  } */
$search_item_value = "";
/*var_dump($utilisateur);
exit();*/

$search_term = isset($_GET['s']) ? $_GET['s'] : '';
$stmt = null;
$page_url = 'lst_assign_install.php?';

// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;
$page_c = 'lst_assign_install.php';
$collapse = "";
$search_value = "";
$du = "";
$au = "";
$du_ = "";
$au_ = "";
$hide_it = "";
$expanded = "false";
$collapsed = "";
/*if (isset($_GET['Du']) && isset($_GET['Au'])) {
    $hide_it = "style=\"display: none;\"";
    $collapse = "show";
    $expanded = "true";
    $search_item = isset($_GET['s']) ? $_GET['s'] : '';
    $du = isset($_GET['Du']) ? Utils::ClientToDbDateFormat($_GET['Du']) : "";
    $au = isset($_GET['Au']) ? Utils::ClientToDbDateFormat($_GET['Au']) : "";
    $du_ = isset($_GET['Du']) ? ($_GET['Du']) : "";
    $au_ = isset($_GET['Au']) ? ($_GET['Au']) : "";


    if ($search_item == '') {
		$page_url.="Du=".$_GET['Du']."&Au=".$_GET['Au']."&";
        $stmt = $Abonne->search_advanced_DateOnly($du,$au,$from_record_num, $records_per_page, $utilisateur->site_id);
        $total_rows = $Abonne->countAll_BySearch_advanced_DateOnly($du,$au,$utilisateur->site_id);
    } else if ($search_item != '') {
		$page_url.="s={$search_item}&Du=".$_GET['Du']."&Au=".$_GET['Au']."&";
        $stmt = $Abonne->search_advanced($du, $au, $search_item,$from_record_num, $records_per_page, $utilisateur->site_id);
        $total_rows = $Abonne->countAll_BySearch_advanced($du, $au, $search_item,$utilisateur->site_id);
    }
    $search_item_value = isset($search_item) ? "value='{$search_item}'" : "";
} else {*/
$collapse = "collapse";
$collapsed = "collapsed";

if ($search_term == '') {
    $stmt = $Abonne->readAll($from_record_num, $records_per_page, $utilisateur);
    $total_rows = $Abonne->countAll($utilisateur);
} else {
    $page_url .= "s={$search_term}&";
    $stmt = $Abonne->search($search_term, $from_record_num, $records_per_page, $utilisateur);
    $total_rows = $Abonne->countAll_BySearch($search_term, $utilisateur);
}
$search_value = isset($search_term) ? "value='{$search_term}'" : "";
//}
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

        /*
            .text-small { font-size: 0.9rem !important; }
            
            body { background: linear-gradient(to left, rgb(86, 171, 47), rgb(168, 224, 99)); }
            
            .cursor-pointer { cursor: pointer; }*/


        .form-control:-ms-input-placeholder {
            color: #c0c5ca;
            opacity: 1
        }

        .form-control::-ms-input-placeholder {
            color: #c0c5ca;
            opacity: 1
        }

        .form-control::placeholder {
            color: #c0c5ca;
            opacity: 1
        }

        .form-control::-webkit-input-placeholder {
            color: #c0c5ca;
            opacity: 1
        }
    </style>
    <link href="assets/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="assets/css/select2.css" rel="stylesheet">
    <link href="assets/css/parsley.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/leaflet.css" />
    <script src="assets/js/leaflet.js"></script>
    <?php
    include_once "layout_style.php";
    ?>

</head>

<body>

    <div id="loader" class="loader loader-default"></div>
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
                                <a href="<?php echo 'lst_assign_replace.php'; ?>" class="breadcrumbs_home"><i class='fas fa-calendar-plus nav_icon'></i> <?php echo $mnu_title; ?></a> <span class="raquo">»</span>
                            </div>
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
                                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 mb-2">
                                    <label for="validationCustom03"><?php echo $page_title; ?></label>
                                </div>

                                <div class="row" id="advanced_search">
                                    <!-- ============================================================== -->
                                    <!-- validation form -->
                                    <!-- ============================================================== -->
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                                        <div class="card">

                                            <div class="card-body">
                                                <div id="record_count" class="font-semi-bold" style="color:#5e6e82;font-size:16px">

                                                    <?php echo $total_rows . ' Elément(s)'; ?></div>
                                                <form method="get" role='search' id="frm_search_advanced">
                                                    <div class="input-group mt-1">
                                                        <input type="text" id="s" name="s" class="form-control" placeholder="Recherche..." value="<?php echo $search_term; ?>">
                                                        <button type="submit" name="search" id="btn_search" class="btn btn-primary"><i class="fa fa-search"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">

                                    <div id="clients-rows" class="modal-body">



                                        <!-- ==========================start==================================== -->


                                        <?php
                                        if ($utilisateur->HasDroits("10_500")) {
                                            $num_line = 0;







                                            while ($row_ = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $num_line++;

                                                $organisme->ref_organisme = $row_["id_organe"];
                                                $row_gp = $organisme->GetDetail();
                                                //	echo "<option value='{$row_gp["ref_organisme"]}'>




                                                $nom_identificateur = $utilisateur->GetUserDetailName($row_['identificateur']);

                                                // $commune->code = $row_["commune_id"];
                                                $cvs->code = $row_["cvs_id"];
                                                // $commune->GetDetailIN();
                                                $cvs->GetDetailIN();
                                                //    echo '<td>' . $commune->libelle . '</td>';

                                                $ctl_rw = $utilisateur->readDetail($row_["id_chef_operation"]);

                                        ?>
                                                <div class="client-row card bg-white">
                                                    <div class="card-header d-flex">
                                                        <div>
                                                            <div class="text-dark">Compteur</div>
                                                            <h4 class="mb-0 text-primary"><?php echo $row_["num_compteur_actuel"];   ?></h4>
                                                        </div>
                                                        <div class="dropdown ml-auto">
                                                            <a class="toolbar" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-dots-vertical"></i> </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" x-placement="bottom-end" style="position: absolute; transform: translate3d(-160px, 23px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                <?php
                                                                if ($utilisateur->HasDroits("10_510")) {
                                                                    echo '<a class="dropdown-item delete" href="#"  data-name="' . $row_["nom_client_blue"] . '" data-id="' . $row_["id_assign"] . '" data-num-compteur="' . $row_["num_compteur_actuel"] . '">Annuler</a>';
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">

                                                            <div class="col-sm-3">
                                                                <div class="text-dark">
                                                                    Organisme
                                                                </div>
                                                                <div class="font-medium text-primary client-adress"><?php echo $row_gp["denomination"];   ?></div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="text-dark">
                                                                    Date assignation
                                                                </div>
                                                                <div class="font-medium text-primary client-adress"><?php echo $row_["date_sys_fr"];   ?></div>
                                                            </div>
                                                            <div class="col-sm-3 text-left">
                                                                <div class="text-dark">
                                                                    Client
                                                                </div>
                                                                <div class="font-medium text-primary client-phone"><?php echo $row_["nom_client_blue"];   ?></div>
                                                            </div>
                                                            <div class="col-sm-3 text-left">
                                                                <div class="text-dark">
                                                                    Identificateur
                                                                </div>
                                                                <div class="font-medium text-primary client-identif"><?php echo $nom_identificateur;   ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <div class="text-dark">
                                                                    Adresse
                                                                </div>
                                                                <div class="font-medium text-primary client-device"><?php echo $adress_item->GetAdressInfoTexte($row_["adresse_id"]);     ?></div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="text-dark">
                                                                    REFERENCE APPARTEMENT
                                                                </div>
                                                                <div class="font-medium text-primary client-device"><?php echo $row_["reference_appartement"];     ?></div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="text-dark">
                                                                    CVS
                                                                </div>
                                                                <div class="font-medium text-primary client-phone"><?php echo  $cvs->libelle;      ?></div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="text-dark">
                                                                    Chef équipe

                                                                </div>
                                                                <div class="font-medium text-primary client-phone"><?php echo  $ctl_rw['nom_complet'];      ?></div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="text-dark">
                                                                    Statut
                                                                </div>
                                                                <div>
                                                                    <span class="badge <?php echo Utils::getAssign_Control_Badge($row_["statut_"]); ?>"><?php echo Utils::getAssign_Install_Statut($row_["statut_"]);   ?></span>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                        <?php }
                                        }


                                        ?>
                                        <div class="clearfix">

                                            <?php
                                            // paging buttons
                                            include_once 'layout_paging.php';
                                            ?>

                                        </div>
                                    </div>
                                    <!-- ==========================start==================================== -->

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
        if ($utilisateur->HasDroits("10_515")) {

            echo '<div class="btn-group-fab" role="group" aria-label="FAB Menu">
	  <div>
		<button type="button" class="btn btn-main btn-primary has-tooltip" data-placement="left" title="Menu" id="btn_new_"> <i class="fa fa-plus"></i> </button>
	  </div>
	</div>';
        }
        include_once "layout_script.php";
        ?>

        <div class="modal fade" id="dlg_main" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true" data-backdrop="static" style="overflow: scroll;">
            <form id="mainForm" method="post" action="controller.php" enctype="multipart/form-data">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="titre"></h5>
                            <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </a>
                        </div>
                        <div class="modal-body">
                            <input name="view" id="view" type="hidden">
                            <div class="row">


                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="card">
                                        <div class="card-body">


                                            <div class="row">

                                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">

                                                    <div class="form-group">
                                                        <label>CVS<span class="ml-1 text-danger">*</span></label>
                                                        <select class='form-control select2' style='width: 100%;' name='cvs_id' id='cvs_id' required>
                                                            <option selected='selected' value=''>Veuillez préciser le CVS</option>
                                                            <?php
                                                            // $stmt_select_st = $cvs->GetSiteCVS($utilisateur->site_id);	 			 

                                                            $stmt_select_st = $adress_item->GetProvinceAllCVS($USER_SITE_PROVINCE);

                                                            while ($row_gp = $stmt_select_st->fetch(PDO::FETCH_ASSOC)) {
                                                                echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label>Organisme<span class="ml-1 text-danger">*</span></label>
                                                        <select class='form-control select2' style='width: 100%;' name='id_equipe_identification' id='id_equipe_identification' required>
                                                            <option selected='selected' value=''>Veuillez préciser</option>
                                                            <?php
                                                            $stmt_ = null;
                                                            if ($utilisateur->id_service_group ==  '3' || $utilisateur->id_service_group ==  '4') {  //Administration OU CONTROLE
                                                                $stmt_ = $organisme->read();
                                                                while ($row_gp = $stmt_->fetch(PDO::FETCH_ASSOC)) {
                                                                    echo "<option value='{$row_gp["ref_organisme"]}'>{$row_gp["denomination"]}</option>";
                                                                }
                                                            } else {
                                                                $organisme->ref_organisme = $utilisateur->id_organisme;
                                                                $row_gp = $organisme->GetDetail();
                                                                echo "<option value='{$row_gp["ref_organisme"]}'>{$row_gp["denomination"]}</option>";
                                                            }

                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label>CHEF D'EQUIPE <span class="ml-1 text-danger">*</span></label>
                                                        <div class="input-group" style="width: 100%;">

                                                            <select class='form-control select2' style='width: 100%;' name='chef_equipe_install' id='chef_equipe_install' required>
                                                                <option selected='selected' disabled>Veuillez préciser</option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label>CONTROLEUR QUALITE <span class="ml-1 text-danger">*</span></label>
                                                        <div class="input-group" style="width: 100%;">

                                                            <select class='form-control select2' style='width: 100%;' name='controleur_quality' id='controleur_quality'>

                                                                <option selected='selected' disabled>Veuillez préciser</option>
                                                                <?php
                                                                //Exclusivité du remplacement à Blue-Energy (1) 10_570law 
                                                                $stmt_chief = $utilisateur->GetExclusiveQualityControleur($utilisateur, '1', '10_565');
                                                                while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
                                                                    echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
                                                                }    ?>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- ============================================================== -->

                                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                    <div class="card bg-white font-semi-bold mt-3 mb-4">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12">


                                                    <div class="input-group mt-1">
                                                        <input type="text" id="srch-term-client" name="s" class="form-control" placeholder="Recherche...">
                                                        <button type="button" name="search" id="search-btn" class="btn btn-primary"><i class="fa fa-search"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header d-flex">
                                            <div>
                                                <div class="text-dark">Liste des compteurs</div>
                                                <h4 class="mb-0 text-primary" id="nbre_resultat_box"><span></span></h4>
                                            </div>
                                            <div id="cpteur_selecteur" class="dropdown ml-auto">
                                                <a class="toolbar" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-dots-vertical"></i> </a>
                                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" x-placement="bottom-end" style="position: absolute; transform: translate3d(-160px, 23px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a id="ctr-select-all" class="dropdown-item" href="#">Sélectionner tout</a>
                                                </div>
                                            </div>

                                        </div>

                                    </div>
                                </div>

                                <div id="list-compteurs" class="modal-body">


                                </div>
                                <!-- Localisation form -->
                                <!-- ============================================================== -->



                            </div>


                            <div class="modal-footer ">
                                <button type="button" class="btn btn-primary btn-lg" id="btn_save_"><span class="glyphicon glyphicon-ok-sign"></span> Valider</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>




        <script type="text/javascript" src="assets/js/webcam-easy.min.js"></script>

        <script src="assets/js/select2.min.js"></script>
        <script src="assets/js/parsley.js"></script>
        <script>
            $(function() {

                //  var actual_chief="";           
                //var select_c="";        
                //var actual_cvs="";        
                var load_cvs = false;
                var load_chief = false;

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
                /* $('.modal').on("hidden.bs.modal", function (e) { //fire on closing modal box scroll issue hack
                                if ($('.modal:visible').length) {
                                $('body').addClass('modal-open');
                                }
                                });*/

                /*  function modalbox_scroll(){
				if ($('.modal-open').length == 0) { 
				$('body').addClass('modal-open');  
			}
           $('.modal').on("hidden.bs.modal", function (e) { //fire on closing modal box scroll issue hack
			if ($('.modal:visible').length) { 
				$('body').addClass('modal-open');  
			}
		});
            }	*/

                var ctr = 0;
                var exist = false;

                jQuery(document).delegate('a.checkAll', 'click', function(e) {
                    e.preventDefault();
                    $('#list-compteurs input:checkbox').each(function() {
                        if ($(this).is(':visible')) {
                            $(this).prop('checked', true);
                        }
                    });
                    $("#ctr-select-all").removeClass('checkAll');
                    $("#ctr-select-all").addClass('uncheckAll');
                    $("#ctr-select-all").text("Désélectionner tout");
                });

                jQuery(document).delegate('a.uncheckAll', 'click', function(e) {
                    e.preventDefault();
                    $('#list-compteurs input:checkbox').each(function() {
                        $(this).prop('checked', false);
                    });
                    $("#ctr-select-all").removeClass('uncheckAll');
                    $("#ctr-select-all").addClass('checkAll');
                    $("#ctr-select-all").text("Sélectionner tout");
                });
                /*$(".deggre").change(function(){
                 if($(this).val() == "")
                 $('#dropdownMenu1').css({"display": "none"});
                 else
                 $('#dropdownMenu1').css({"display": "block"});
                 });*/

                $('#dlg_main .select2').each(function() {
                    var $sel = $(this).parent();
                    $(this).select2({
                        dropdownParent: $sel
                    });
                });




                $("#search-btn").on("click", function(e) {
                    var item = $("#cvs_id").val();
                    var search_param = $("#srch-term-client").val();
                    // e.preventDefault();										
                    /*if(load_cvs == false)
                    {
                    	return false;
                    }
                    if(item.length == 0){
                    	return false;
                    }*/

                    ShowLoader("Chargement liste des compteurs en cours...");

                    $("#list-compteurs").html('');
                    $.ajax({
                        url: "controller.php",
                        method: "GET",
                        data: {
                            view: "get_cvs_compteur_replace",
                            id_: item,
                            search_param: search_param
                        },
                        success: function(data, statut) {
                            /*  $("#loadMe").modal("hide").on('hidden.bs.modal', functionThatEndsUpDestroyingTheDOM);*/


                            //modalbox_scroll();
                            //$('#loadMe').hide();
                            //$('#loadMe').attr('aria-hidden',"true");
                            //$('.modal-backdrop').hide();
                            //$('body').removeClass('modal-open');
                            try {
                                var result = $.parseJSON(data);
                                if (result.error == 0) {
                                    // $("#list-compteurs").html(result.data);
                                    ShowLoader("Chargement liste en cours");
                                    $('#nbre_resultat_box').html(result.items_count + ' Résultat(s)');
                                    $.each(result.items, function(i, item) {
                                        // Id = generateItemID(lignes);
                                        $("#list-compteurs").append('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">  <div class="client-row card bg-white"><div class="card-body"><div class="custom-control custom-checkbox"><input class="custom-control-input" id="chk_' + item.data.id_ + '" name="tbl-checkbox[]" type="checkbox" value="' + item.data.id_ + '" data-parsley-multiple="tbl-checkbox"><label class="cursor-pointer font-italic d-block custom-control-label" for="chk_' + item.data.id_ + '"> </label></div><div class="row"><div class="col-sm-4"><div class="text-dark">Compteur</div><div class="font-medium text-primary compteur-number">' + item.data.num_compteur_actuel + ' </div></div><div class="col-sm-4 text-left"><div class="text-dark">Client</div><div class="font-medium text-primary compteur-customer">' + item.data.nom_client_blue + '</div></div><div class="col-sm-4 text-right"><div class="text-dark">Téléphone</div><div class="font-medium text-primary compteur-phone">' + item.data.phone_client_blue + '</div></div></div><div class="row"><div class="col-sm-3"><div class="text-dark">Adresse</div><div class="font-medium text-primary compteur-adress">' + item.adresseTexte + '</div></div><div class="col-sm-3 text-left"><div class="text-dark">CVS</div><div class="font-medium text-primary compteur-cvs">' + item.data.libelle + '</div></div><div class="col-sm-3 text-left"><div class="text-dark">REFERENCE APPARTEMENT</div><div class="font-medium text-primary compteur-cvs">' + item.data.reference_appartement + '</div></div><div class="col-sm-3 text-right"><div class="text-dark">Statut</div><div><span class="badge badge-success">Déjà installé</span></div></div><div class="col-sm-3"><div class="text-dark">Identificateur</div><div class="font-medium text-primary compteur-identificateur">' + item.data.nom_complet + '</div></div></div></div></div></div>');

                                    });
                                    HideLoader();
                                    if (result.deja_assigner_count > 0) {
                                        if (result.deja_assigner_count == 1) {
                                            swal("Information", "Il y a " + result.deja_assigner_count + " compteur déjà assigné qui est exclus de ce résultat", "warning");

                                        } else {
                                            swal("Information", "Il y a " + result.deja_assigner_count + " compteurs déjà assignés qui sont exclus de ce résultat", "warning");
                                        }
                                    }
                                    if (result.items.length == 0) {
                                        $('#cpteur_selecteur').css({
                                            display: 'none'
                                        });
                                        $("#list-compteurs").append('<div class="card alert-danger"><div class="card-body"><div role="alert" class=""><h4 class="alert-heading">Notification!</h4><p>Aucun Abonné à installer trouvé pour ce CVS</p></div></div></div>');
                                    } else {
                                        $("#ctr-select-all").removeClass('uncheckAll');
                                        $("#ctr-select-all").addClass('checkAll');
                                        $("#ctr-select-all").text("Sélectionner tout");
                                        $('#cpteur_selecteur').css({
                                            display: 'block'
                                        });
                                    }
                                    $('#dlg_main-control-assign').modal('show');
                                    // $('#btn_save_paie').show();
                                } else if (result.error == 1) {

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
                                HideLoader();
                            } catch (erreur) {
                                HideLoader();
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
                            HideLoader();
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

                function ClearForm() {
                    //$("#view_inst").val("");
                    //$('#mainForm_install')[0].reset();
                    //var frm = $("#mainForm_install");
                    //frm.parsley().reset();
                }

                $('#btn_save_').click(function() {

                    ShowLoader('Patientez ...');
                    // Get the form
                    var form = document.getElementById("mainForm");
                    var frm = $("#mainForm");
                    if (frm.parsley().validate()) {
                        // alert("oui");				   
                    } else {
                        // alert("non");
                        return false;
                    }



                    // Create a FormData and append the file with "image" as parameter name
                    var formDataToUpload = new FormData(form);
                    $.ajax({
                        //enctype: 'multipart/form-data',
                        url: "controller.php",
                        data: formDataToUpload, // Add as Data the Previously create formData
                        type: "POST",
                        contentType: false,
                        processData: false,
                        cache: false,
                        dataType: "json", // Change this according to your response from the server.
                        error: function(err) {
                            HideLoader();
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
                            HideLoader();
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
                                        //  ClearMaterielsRow();
                                        $("#dlg_main").modal('hide');
                                        window.location.reload();
                                    });
                                } else if (result.error == 1) {
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
                            }
                        },
                        complete: function() {
                            HideLoader();
                        }
                    });


                });
                ////////////////INSTALLATION
                /*     jQuery(document).delegate('a.edit-install-item', 'click', function(e) {
                     e.preventDefault();
                             var itemId = $(this).parents('tr.item-row-install').attr('item-id');
                             var label = $('tr.item-row-install[item-id="' + itemId + '"]').find('span.sn').html();
                             var qte = $('tr.item-row-install[item-id="' + itemId + '"]').find('span.qte').html();
                             $('#item_titre_install').html('MODIFICATION MATERIEL');
                             $('#ligne_form_install').modal('show');
                             $('#item-id').val(itemId);
                             $('#item_label_install').val(label).change();
                             $('#item-qte-install').val(qte);
                             $('#item-type-install').val('1');
                     });*/


                <?php if ($utilisateur->HasDroits("10_515")) {
                ?> $('#btn_new_').click(function() {
                        load_cvs = true;
                        ///     ClearForm();

                        $('#id_equipe_identification').val('').change();
                        $('#chef_equipe_install').val('').change();
                        $('#controleur_quality').val('').change();
                        $('#cvs_id').val('').change();
                        $('#list-compteurs').html('');
                        $('#cpteur_selecteur').css({
                            display: 'none'
                        });
                        $('#mainForm #view').val("create_replace_assign");
                        $('#titre').html('NOUVELLE ASSIGNATION POUR REMPLACEMENT');
                        $('#dlg_main').modal('show');
                        //var d = new Date($.now());
                        //alert(d.getDate()+"-"+(d.getMonth() + 1)+"-"+d.getFullYear()+" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds());

                    });
                <?php } ?>


                <?php if ($utilisateur->HasDroits("10_510")) {
                ?>

                    $('.delete').click(function() {
                        var name_compteur = jQuery(this).attr("data-num-compteur");
                        var name_actuel = jQuery(this).attr("data-name");
                        var jeton_actuel = jQuery(this).attr("data-id");
                        swal({
                            title: "Information",
                            text: 'Voulez-vous annuler l\'assignation du compteur (' + name_compteur + ')?',
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#00A65A",
                            confirmButtonText: "Oui",
                            cancelButtonText: "Non",
                            closeOnConfirm: false,
                            closeOnCancel: true
                        }, function(isConfirm) {
                            if (isConfirm) {
                                var view_mode = "delete_assign_install";
                                $.ajax({
                                    url: "controller.php",
                                    method: "POST",
                                    data: {
                                        view: view_mode,
                                        k: jeton_actuel
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


            });


            <?php
            //if($utilisateur->id_service_group ==  '3'){
            ?>

            $("#id_equipe_identification").on("change", function(e) {
                e.preventDefault();
                var item = $(this).val();
                $("#chef_equipe_install").html('');
                if (item.length == 0) {
                    return false;
                }

                ShowLoader("Chargement liste des chefs d'equipe en cours...");

                $.ajax({
                    url: "controller.php",
                    method: "GET",
                    data: {
                        view: "get_organisme_chief_install",
                        id_: item
                    },
                    success: function(data, statut) {
                        HideLoader();
                        try {
                            var result = $.parseJSON(data);
                            if (result.error == 0) {
                                $("#chef_equipe_install").html(result.data);
                                //if(actual_chief != ""){
                                // $("#chef_equipe_install").val(actual_chief).change();      
                                //}
                            } else if (result.error == 1) {}
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
                        HideLoader();
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


            <?php
            //}
            ?>

            /*$("#srch-term-client").keyup(function(){
                        var val = $(this).val().toString().toLowerCase();
                                $('#list-compteurs').find('.client-row').each(function(i){
                        var row = $(this);
                        var client_name =  row.find('.compteur-customer').text().toString().toLowerCase();
                        var client_adress =  row.find('.compteur-adress').text().toString().toLowerCase();
                        var client_device =  row.find('.compteur-number').text().toString().toLowerCase();
                        var client_phone =  row.find('.compteur-phone').text().toString().toLowerCase();
                        var client_cvs =  row.find('.compteur-cvs').text().toString().toLowerCase();
                        var client_identificateur =  row.find('.compteur-identificateur').text().toString().toLowerCase();
                       
             
            			if (client_name.indexOf(val) != - 1||client_adress.indexOf(val) != - 1||client_identificateur.indexOf(val) != - 1
            			||client_device.indexOf(val) != - 1||client_phone.indexOf(val) != - 1||client_cvs.indexOf(val) != - 1)
                        {
                        row.show();
                             //   return false;
                        }
                        else  row.hide(); 
                        });
            			
                        if (!val)
                                $('#list-compteurs').find('.client-row').each(function(i){
                        $(this).show();
                        });
                        });			
            */

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
            //Empeche la propagation du hide.bs.collapse et show.bs.collapse sur le datepicker
            $("#Du").on("show", function(e) {
                e.preventDefault();
                e.stopPropagation();
            }).on("hide", function(e) {
                e.preventDefault();
                e.stopPropagation();
            });
            $("#Au").on("show", function(e) {
                e.preventDefault();
                e.stopPropagation();
            }).on("hide", function(e) {
                e.preventDefault();
                e.stopPropagation();
            });


            function ShowLoader(txt) {
                $("#loader").attr("data-text", txt);
                $("#loader").addClass("is-active");
            }

            function HideLoader() {
                $("#loader").removeClass("is-active");
            }
        </script>


</body>

</html>