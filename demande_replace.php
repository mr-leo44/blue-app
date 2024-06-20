<?php
// session_start();
$mnu_title = "DEMANDE DE REMPLACEMENT";
$page_title = "Historique des demandes de remplacement";
$home_page = "demande_replace.php";
$active = "demande_replace";
$parambase = "";

require_once 'vendor/autoload.php';
require_once 'loader/init.php';
Autoloader::Load('classes');
include_once 'core.php';
header('Content-type: text/html;charset=utf-8');
$database = new Database();
$db = $database->getConnection();
$Abonne = new PARAM_Notification($db);
$utilisateur = new Utilisateur($db);
$commune = new Commune($db);
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
$page_url = $home_page . '?';
$page_c = $home_page;
$collapse = "";
$search_value = "";
$du = "";
$au = "";
$du_ = "";
$au_ = "";
$hide_it = "";
$expanded = "false";
$collapsed = "";

// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;
$Abonne->type_notification = '2'; //DEMANDE DE REMPLACEMENT
if (isset($_GET['Du']) && isset($_GET['Au'])) {
    $hide_it = "style=\"display: none;\"";
    $collapse = "show";
    $expanded = "true";
    $search_item = isset($_GET['s']) ? $_GET['s'] : '';
    $du = isset($_GET['Du']) ? Utils::ClientToDbDateFormat($_GET['Du']) : "";
    $au = isset($_GET['Au']) ? Utils::ClientToDbDateFormat($_GET['Au']) : "";
    $du_ = isset($_GET['Du']) ? ($_GET['Du']) : "";
    $au_ = isset($_GET['Au']) ? ($_GET['Au']) : "";

    /*
    if ($search_item == '') {
		$page_url.="Du=".$_GET['Du']."&Au=".$_GET['Au']."&";
        $stmt = $Abonne->search_advanced_DateOnly($du,$au,$from_record_num, $records_per_page, $utilisateur->site_id);
        $total_rows = $Abonne->countAll_BySearch_advanced_DateOnly($du,$au,$utilisateur->site_id);
    } else if ($search_item != '') {
		$page_url.="s={$search_item}&Du=".$_GET['Du']."&Au=".$_GET['Au']."&";
        $stmt = $Abonne->search_advanced($du, $au, $search_item,$from_record_num, $records_per_page, $utilisateur->site_id);
        $total_rows = $Abonne->countAll_BySearch_advanced($du, $au, $search_item,$utilisateur->site_id);
    }*/
    $search_item_value = isset($search_item) ? "value='{$search_item}'" : "";
} else {
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
}
?>
<!doctype html>
<html lang="fr">

<head>
    <style>
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
                                <a href="<?php echo $home_page . '.php'; ?>" class="breadcrumbs_home"><i class='fas fa-calendar-check nav_icon'></i> <?php echo $mnu_title; ?></a> <span class="raquo">»</span>
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

                                <div class="form-row">
                                    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 mb-2">
                                        <label for="validationCustom03"><?php echo $page_title; ?></label>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 ">
                                        <form method="get" role='search' id="frm_search">
                                            <div class="input-group mb-3">
                                                <input type="text" id="srch-term" name='s' class="form-control" placeholder="Recherche ..." required <?php echo $search_value;
                                                                                                                                                        echo $hide_it; ?>>
                                                <button type="submit" name="search" id="search-btn" class="btn btn-primary" <?php echo $hide_it; ?>><i class="fa fa-search"></i>
                                                </button>
                                                <!--   <a id="btn_advanced_search" class="btn btn-secondary ml-1 <?php echo $collapsed; ?>" data-toggle="collapse" href="#advanced_search" role="button" aria-expanded="<?php echo $expanded; ?>" aria-controls="advanced_search"><i class="fas fa-filter text-white mr-2"></i></a> -->
                                                <a class="btn btn-outline-light float-right ml-1" href="<?php echo $page_c; ?>">Voir tout</a>

                                                <!--													</div></form>
                                                     <div class="collapse" id="advanced_search">
                                                      <div class="card card-body">
                                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident.
                                                      </div>
                                                    </div>   -->





                                            </div>
                                        </form>
                                    </div>

                                    <div class="row <?php echo $collapse; ?>" id="advanced_search">
                                        <!-- ============================================================== -->
                                        <!-- validation form -->
                                        <!-- ============================================================== -->
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                                            <div class="card">

                                                <div class="card-body">
                                                    <form method="get" role='search' id="frm_search_advanced">
                                                        <div class="row">
                                                            <div class="form-row">
                                                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 mb-2">

                                                                    <div class="form-group">
                                                                        <input type="text" name='s' class="form-control" placeholder="Recherche ..." <?php echo $search_item_value; ?>>

                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-12 mb-2 ">

                                                                    <div class="form-group" style="width : 135px;margin-right:120px;">
                                                                        <div class="input-group date" id="datetimepicker4" data-target-input="nearest">
                                                                            <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker4" name="Du" id="Du" required="required" value="<?php echo $du_; ?>" placeholder="Du" />
                                                                            <div class="input-group-append" data-target="#datetimepicker4" data-toggle="datetimepicker">
                                                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-12 mb-2 ">

                                                                    <div class="form-group" style="width : 135px;margin-right:120px;">
                                                                        <div class="input-group date" id="datepickerAu" data-target-input="nearest">
                                                                            <input type="text" class="form-control datetimepicker-input" data-target="#datepickerAu" name="Au" id="Au" required="required" value="<?php echo $au_; ?>" placeholder="Au" />
                                                                            <div class="input-group-append" data-target="#datepickerAu" data-toggle="datetimepicker">
                                                                                <div class="input-group-text"><i class="far fa-calendar-alt"></i></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xl-1 col-lg-1 col-md-12 col-sm-12 col-12 ">

                                                                    <p class="text-right">
                                                                        <button type="submit" class="btn btn-primary">
                                                                            <i class="fas fa-filter text-white mr-2"></i>Filtrer</button>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div id="clients-rows" class="modal-body">



                                        <!-- ==========================start==================================== -->


                                        <?php
                                        if ($utilisateur->HasDroits("10_527")) {
                                            $num_line = 0;







                                            while ($row_ = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $num_line++;
                                                $cvs->code = $row_["cvs_id"];
                                                $cvs->GetDetailIN();
                                        ?>
                                                <div class="client-row card bg-white">
                                                    <div class="card-header d-flex">
                                                        <div>
                                                            <div class="text-dark">Compteur</div>
                                                            <h4 class="mb-0 text-primary"><?php echo $row_["num_compteur"];   ?></h4>
                                                        </div>
                                                        <!--         <div class="dropdown ml-auto">
                                            <a class="toolbar" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-dots-vertical"></i>  </a>
                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" x-placement="bottom-end" style="position: absolute; transform: translate3d(-160px, 23px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                <?php
                                                if ($utilisateur->HasDroits("10_490")) {
                                                    echo '<a class="dropdown-item delete" href="#"  data-name="' . $row_["nom_client"] . '" data-id="' . $row_["ref_log"] . '" data-num-compteur="' . $row_["num_compteur"] . '">Annuler</a>';
                                                }
                                                ?>
                                            </div>
                                        </div>   -->
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">

                                                            <div class="col-sm-4">
                                                                <div class="text-dark">
                                                                    Client
                                                                </div>
                                                                <div class="font-medium text-primary client-name"><?php echo $row_["nom_client"];   ?></div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="text-dark">
                                                                    Date
                                                                </div>
                                                                <div class="font-medium text-primary client-date"><?php echo $row_["datesys_fr"];   ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <div class="text-dark">
                                                                    Adresse
                                                                </div>
                                                                <div class="font-medium text-primary client-device"><?php echo $adress_item->GetAdressInfoTexte($row_["adresse_id"]);   ?></div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="text-dark">
                                                                    CVS
                                                                </div>
                                                                <div class="font-medium text-primary client-phone"><?php echo  $cvs->libelle;      ?></div>
                                                            </div>
                                                            <!--	<div class="col-sm-4">
						<div class="text-dark">
							Statut
						</div>
						<div>
						<span class="badge <?php echo Utils::getAssign_Control_Badge($row_["statut_"]); ?>"><?php echo Utils::getAssign_Control_Statut($row_["statut_"]);   ?></span>	
						</div>
					</div> -->

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
        /*  if ($utilisateur->HasDroits("10_495")) {

                echo '<div class="btn-group-fab" role="group" aria-label="FAB Menu">
	  <div>
		<button type="button" class="btn btn-main btn-primary has-tooltip" data-placement="left" title="Menu" id="btn_new_"> <i class="fa fa-plus"></i> </button>
	  </div>
	</div>';
            }*/
        include_once "layout_script.php";
        ?>




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
                jQuery(document).delegate('a.checkAll', 'click', function() {
                    $('#list-compteurs input:checkbox').each(function() {
                        $(this).prop('checked', true);
                    });
                    $("#ctr-select-all").removeClass('checkAll');
                    $("#ctr-select-all").addClass('uncheckAll');
                    $("#ctr-select-all").text("Désélectionner tout");
                });

                jQuery(document).delegate('a.uncheckAll', 'click', function() {
                    $('#list-compteurs input:checkbox').each(function() {
                        $(this).prop('checked', false);
                    });
                    $("#ctr-select-all").removeClass('uncheckAll');
                    $("#ctr-select-all").addClass('checkAll');
                    $("#ctr-select-all").text("Sélectionner tout");
                });

                var ctr = 0;
                var exist = false;

                /*$(".deggre").change(function(){
                 if($(this).val() == "")
                 $('#dropdownMenu1').css({"display": "none"});
                 else
                 $('#dropdownMenu1').css({"display": "block"});
                 });*/

                $("#advanced_search").on("hide.bs.collapse", function() {
                    //$(".btn").html('<span class="glyphicon glyphicon-collapse-down"></span> Open');
                    //alert("Close");
                    $('#srch-term').show();
                    $('#search-btn').show();
                });
                $("#advanced_search").on("show.bs.collapse", function() {
                    //  alert("Open");
                    $('#srch-term').hide();
                    $('#search-btn').hide();
                    //$(".btn").html('<span class="glyphicon glyphicon-collapse-up"></span> Close');
                });
                $('#dlg_main .select2').each(function() {
                    var $sel = $(this).parent();
                    $(this).select2({
                        dropdownParent: $sel
                    });
                });




                $("#cvs_id").on("change", function(e) {
                    var item = $(this).val();
                    e.preventDefault();
                    if (load_cvs == false) {
                        return false;
                    }

                    $("#loading_msg").html("Chargement liste des compteurs en cours...");
                    $("#loadMe").modal({
                        backdrop: "static",
                        keyboard: false,
                        show: true
                    });
                    $("#list-compteurs").html('');
                    $.ajax({
                        url: "controller.php",
                        method: "GET",
                        data: {
                            view: "get_cvs_compteur",
                            id_: item
                        },
                        success: function(data, statut) {
                            /*  $("#loadMe").modal("hide").on('hidden.bs.modal', functionThatEndsUpDestroyingTheDOM);*/

                            $('#loadMe').modal('hide');
                            //modalbox_scroll();
                            //$('#loadMe').hide();
                            //$('#loadMe').attr('aria-hidden',"true");
                            //$('.modal-backdrop').hide();
                            //$('body').removeClass('modal-open');
                            try {
                                var result = $.parseJSON(data);
                                if (result.error == 0) {
                                    $("#list-compteurs").html(result.data);

                                    $.each(result.data, function(i, item) {
                                        // Id = generateItemID(lignes);
                                        $("#list-compteurs").append('<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">  <div class="client-row card bg-white"><div class="card-body"><div class="custom-control custom-checkbox"><input class="custom-control-input" id="chk_' + item.id_ + '" name="tbl-checkbox[]" type="checkbox" value="' + item.id_ + '" data-parsley-multiple="tbl-checkbox"><label class="cursor-pointer font-italic d-block custom-control-label" for="chk_' + item.id_ + '"> </label></div><div class="row"><div class="col-sm-4"><div class="text-dark">Compteur</div><div class="font-medium text-primary compteur-number">' + item.num_compteur_actuel + ' </div></div><div class="col-sm-4 text-left"><div class="text-dark">Client</div><div class="font-medium text-primary compteur-customer">' + item.nom_client_blue + '</div></div><div class="col-sm-4 text-right"><div class="text-dark">Téléphone</div><div class="font-medium text-primary compteur-phone">' + item.phone_client_blue + '</div></div></div><div class="row"><div class="col-sm-4"><div class="text-dark">Adresse</div><div class="font-medium text-primary compteur-adress">' + item.adresse + '</div></div><div class="col-sm-4 text-left"><div class="text-dark">CVS</div><div class="font-medium text-primary compteur-cvs">' + item.libelle + '</div></div><div class="col-sm-4 text-right"><div class="text-dark">Statut</div><div><span class="badge badge-danger">' + item.etat_compteur + '</span></div></div></div></div></div></div>');

                                    });
                                    if (result.data.length == 0) {
                                        $('#cpteur_selecteur').css({
                                            display: 'none'
                                        });
                                        $("#list-compteurs").append('<div class="card alert-danger"><div class="card-body"><div role="alert" class=""><h4 class="alert-heading">Notification!</h4><p>Aucune information trouvée.</p></div></div></div>');
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
                            $("#loadMe").modal("hide");
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
                            console.log(result);
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
                            console.log("Request finished.");
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


                <?php if ($utilisateur->HasDroits("10_495")) {
                ?> $('#btn_new_').click(function() {
                        load_cvs = true;
                        //     ClearForm();
                        $('#cpteur_selecteur').css({
                            display: 'none'
                        });
                        //$('#cpteur_selecteur').prop("display","none");
                        $('#mainForm #view').val("create_control_assign");
                        $('#titre').html('NOUVELLE ASSIGNATION POUR CONTROLE');
                        $('#dlg_main').modal('show');
                        //var d = new Date($.now());
                        //alert(d.getDate()+"-"+(d.getMonth() + 1)+"-"+d.getFullYear()+" "+d.getHours()+":"+d.getMinutes()+":"+d.getSeconds());

                    });
                <?php } ?>


                <?php if ($utilisateur->HasDroits("10_490")) {
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
                                var view_mode = "delete_assign_control";
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
        </script>


</body>

</html>