<?php
// session_start();
$mnu_title = "DEMANDE DE TICKET";
$page_title = "Historique des demandes de ticket";
$home_page = "demande_ticket.php";
$active = "demande_ticket";
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
$dt_installation = new Installation($db);
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
$filtre = isset($_GET['filtre']) ? $_GET['filtre'] : "";
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
$Abonne->type_notification = '4'; //DEMANDE DE TICKET

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

    $where = "";
    switch ($filtre) {
        case 'installation':
            $where = " AND t_param_notification_log.from_control IS NULL ";
            break;
        case 'control':
            $where = " AND t_param_notification_log.from_control IS NOT NULL";
            break;
        default:
            $where = "";
            break;
    }


    if ($search_term == '') {
        $stmt = $Abonne->readAll($from_record_num, $records_per_page, $utilisateur, $where);
        $total_rows = $Abonne->countAll($utilisateur, $where);
    } else {
        $page_url .= "s={$search_term}&";
        $stmt = $Abonne->search($search_term, $from_record_num, $records_per_page, $utilisateur, $where);
        $total_rows = $Abonne->countAll_BySearch($search_term, $utilisateur, $where);
    }

    $search_value = isset($search_term) ? "value='{$search_term}'" : "";
}


?>



<!doctype html>
<html lang="en">

<head>
    <style>
        .lg {
            background-color: #000 !important;
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

        /*    LIGHT GALLERY   */
        .media-cell {
            width: 100%;
            height: 200px;
            /*  z-index: -1;Ensure div tag stays behind content; -999 might work, too. */
        }

        .media-cell>a {
            border: 3px solid #FFF;
            border-radius: 3px;
            display: block;
            overflow: hidden;
            position: relative;
            float: left;
        }

        .img-responsive {
            -webkit-transition: -webkit-transform 0.15s ease 0s;
            -moz-transition: -moz-transform 0.15s ease 0s;
            -o-transition: -o-transform 0.15s ease 0s;
            transition: transform 0.15s ease 0s;
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
            width: 100%;
            height: 300px;
            margin-bottom: 10px;
        }

        .media-cell>a:hover>img {
            -webkit-transform: scale3d(1.1, 1.1, 1.1);
            transform: scale3d(1.1, 1.1, 1.1);
        }

        .media-cell>a:hover .demo-gallery-poster>img {
            opacity: 1;
        }

        .media-cell>a>.media-poster {
            background-color: rgba(0, 0, 0, 0.1);
            bottom: 0;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            -webkit-transition: background-color 0.15s ease 0s;
            -o-transition: background-color 0.15s ease 0s;
            transition: background-color 0.15s ease 0s;
        }

        .media-cell>a .media-poster>img {
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            opacity: 0;
            position: absolute;
            top: 50%;
            -webkit-transition: opacity 0.3s ease 0s;
            -o-transition: opacity 0.3s ease 0s;
            transition: opacity 0.3s ease 0s;
        }

        .media-cell>a:hover .media-poster {
            background-color: rgba(0, 0, 0, 0.5);
        }

        .media-cell .justified-gallery>a>img {
            -webkit-transition: -webkit-transform 0.15s ease 0s;
            -moz-transition: -moz-transform 0.15s ease 0s;
            -o-transition: -o-transform 0.15s ease 0s;
            transition: transform 0.15s ease 0s;
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
            height: 100%;
            width: 100%;
        }

        /*    LIGHT GALLERY    */
    </style>
    <link href="assets/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="assets/css/select2.css" rel="stylesheet">
    <link href="assets/css/parsley.css" rel="stylesheet">
    <link href="assets/css/lightgallery.min.css" rel="stylesheet">
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
                                <a href="<?php echo $home_page; ?>" class="breadcrumbs_home"><i class='fas fa-calendar-check nav_icon'></i> <?php echo $mnu_title; ?></a> <span class="raquo">»</span>
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
                                                <select class="form-control" name="filtre" id="filtre">
                                                    <option>Filtre : Tous les tickets</option>
                                                    <option value="installation">Ticket d'installation</option>
                                                    <option value="control">Ticket de contrôle</option>
                                                </select>
                                                <input type="text" id="srch-term" name='s' class="mx-2 form-control" placeholder="Recherche ..." <?php echo $search_value;
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
                                        if ($utilisateur->HasDroits("10_528")) {
                                            $num_line = 0;
                                            while ($row_ = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $num_line++;
                                                $cvs->code = $row_["cvs_id"];
                                                $cvs->GetDetailIN();
                                                // $ref_transaction_install = $row_["ref_transaction"];//
                                                $dt_installation->id_install = $row_["ref_transaction"]; //
                                                $inst_det = $dt_installation->GetDetail(""); //
                                                $index_par_defaut = $inst_det['data']["index_par_defaut"]; //
                                                $chef_equipe = $utilisateur->GetUserDetailName($inst_det['data']['chef_equipe']);
                                                $installateur_current = $utilisateur->GetUserDetailName($inst_det['data']['installateur']);
                                                $tarif_installation = $inst_det['data']['code_tarif'];
                                        ?>
                                                <div class="client-row card bg-white">
                                                    <div class="card-header d-flex">
                                                        <div>
                                                            <div class="text-dark">Compteur</div>
                                                            <h4 class="mb-0 text-primary"><?php echo $row_["num_compteur"];   ?></h4>
                                                        </div>
                                                        <div class="dropdown ml-auto">
                                                            <a class="toolbar" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-dots-vertical"></i> </a>
                                                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink" x-placement="bottom-end" style="position: absolute; transform: translate3d(-160px, 23px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                                <?php
                                                                if ($utilisateur->HasDroits("10_720")) {
                                                                    echo '<a class="dropdown-item assign-ticket" href="#"  data-client="' . $row_["nom_client"] . '" data-id="' . $row_["ref_log"] . '" data-num-compteur="' . $row_["num_compteur"] . '" data-cvs="' . $cvs->libelle . '"  data-pa="' . $row_["p_a"] . '"  data-chef-install="' . $chef_equipe . '"  data-index-par-defaut="' . $index_par_defaut . '"    data-installateur="' . $installateur_current . '"   data-tarif="' . $tarif_installation . '"  data-adress="' . $adress_item->GetAdressInfoTexte($row_["adresse_id"]) . '">Assigner Ticket</a>';
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
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
                                                                    Date Demande
                                                                </div>
                                                                <div class="font-medium text-primary client-date"><?php echo $row_["datesys_fr"];   ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-4">
                                                                <div class="text-dark">
                                                                    Adresse
                                                                </div>
                                                                <div class="font-medium text-primary client-device"><?php echo $adress_item->GetAdressInfoTexte($row_["adresse_id"]);  ?></div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="text-dark">
                                                                    CVS
                                                                </div>
                                                                <div class="font-medium text-primary client-cvs"><?php echo  $cvs->libelle;      ?></div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="text-dark">
                                                                    Ticket <span class="badge <?php echo Utils::getAssign_Ticket_Badge($row_["numero_ticket"]); ?>"><?php echo Utils::getAssign_Ticket_Statut($row_["numero_ticket"]);   ?></span>
                                                                </div>

                                                                <div class="text-dark">
                                                                    <strong> Type de ticket :</strong>
                                                                    <?php

                                                                    if ($row_['from_control']) {
                                                                        echo "<span  class='mt-2 badge badge-warning'>Contrôle</span>";
                                                                    } else {
                                                                        echo "<span  class='mt-2 badge badge-dark'>Installation</span>";
                                                                    }

                                                                    ?>
                                                                </div>

                                                                <div class="font-medium text-primary client-ticket"><?php echo $row_["numero_ticket"];
                                                                                                                    if (isset($row_["date_creation_ticket"])) {
                                                                                                                    ?>
                                                                        <a class="btn btn-outline-light float-right ml-1 visualiser" data-id="<?php echo  $row_["ref_log"]; ?>" data-compteur="<?php echo $row_["num_compteur"];   ?>" href="#">Visualiser</a>
                                                                    <?php
                                                                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="text-dark">
                                                                    Date assignation
                                                                </div>
                                                                <div class="font-medium text-primary"><?php echo  $row_["date_creation_ticket_fr"];      ?></div>
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


    <div class="modal" id="ticket_gallery" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="ticket_gallery_title" class="modal-title">Ticket compteur : </h4>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body text-center">
                    <div class="card">
                        <!-- <div id="lightgallery" class="row"> -->
                        <ul id="lightgallery" class="list-unstyled row">
                        </ul>
                        <!--  </div>  -->
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <div class="modal" id="box_assignation_ticket" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="notification_title" class="modal-title">Assignation Ticket sur la demande</h4>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body text-center">
                    <form id="frm_assignation_ticket" method="post" action="controller.php" enctype="multipart/form-data">
                        <input id="view" name="view" type="hidden" value="assign_ticket">
                        <input id="ref_demande" name="ref_demande" type="hidden">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="text-dark text-left">
                                    N° Compteur
                                </div>
                                <div class="font-medium text-primary client-adress text-left" id="assign_compteur"></div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-dark text-left">
                                    Client
                                </div>
                                <div class="font-medium text-primary client-adress text-left" id="assign_client"></div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-dark text-left">
                                    Adresse
                                </div>
                                <div class="font-medium text-primary client-adress text-left" id="assign_adresse"></div>
                            </div>
                            <div class="col-sm-6 text-left">
                                <div class="text-dark">
                                    CVS
                                </div>
                                <div id="assign_cvs" class="font-medium text-primary client-cvs"></div>
                            </div>
                            <div class="col-sm-6 text-left">
                                <div class="text-dark">
                                    Chef Equipe Installation
                                </div>
                                <div id="assign_chef" class="font-medium text-primary client-cvs"></div>
                            </div>
                            <div class="col-sm-6 text-left">
                                <div class="text-dark">
                                    Index par Defaut
                                </div>
                                <div id="assign_index" class="font-medium text-primary client-cvs"></div>
                            </div>
                            <div class="col-sm-6 text-left">
                                <div class="text-dark">
                                    PA
                                </div>
                                <div id="assign_pa" class="font-medium text-primary client-cvs"></div>
                            </div>
                            <div class="col-sm-6 text-left">
                                <div class="text-dark">
                                    Installateur
                                </div>
                                <div id="assign_installateur" class="font-medium text-primary client-cvs"></div>
                            </div>
                            <div class="col-sm-6 text-left">
                                <div class="text-dark">
                                    Tarif Installation
                                </div>
                                <div id="assign_tarif" class="font-medium text-primary client-cvs"></div>
                            </div>
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-header d-flex">
                                        <h4 class="mb-0">GALERIE PHOTO TICKET</h4>
                                        <div class="dropdown ml-auto">
                                            <?php if ($MobileRun != "1") { ?>
                                                <a class="btn btn-outline-light float-right" id="btn_capture">Capturer photo</a>
                                            <?php } else { ?><div class="image-upload">
                                                    <label for="file-input">
                                                        <img id="previewImg" src="image/camera.jpg" style="width: 25px; height: 25px;" />
                                                    </label>
                                                    <input id="file-input" type="file" onchange="previewFile(this);" style="display: none;" accept="image/*;capture=camera" capture="camera" />
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row" id="photo_pa_list">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-4 text-left">
                            <label class="text-dark text-left">N° Ticket </label>
                            <div class="input-group" style="width: 100%;">
                                <textarea class="form-control pull-right" name="numero_ticket_" id="numero_ticket_" required></textarea>
                            </div>
                        </div>
                        <div class="text-center">
                            <button id="btn_submit_assignation" type="button" class="btn btn-success btn-fill float-right">Assigner</button>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="camera_shooter" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="gps_loader">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="width: 355px;">
                <div class="modal-header">
                    <h4 id="item_titre" class="modal-title">CAPTURE PHOTO TICKET</h4>
                </div>
                <div class="modal-body text-center">
                    <div id="my_camera" style="width: 320px; height: 240px;">
                        <div></div><video id="webcam" autoplay="autoplay" style="width: 320px; height: 240px;"></video>
                        <canvas id="canvas" class="d-none"></canvas>
                        <div class="flash"></div>
                        <audio id="snapSound" src="audio/snap.wav" preload="auto"></audio>
                    </div>
                    <label class="text-dark text-left">Etiquette Photo</label>
                    <div class="input-group" style="width: 100%;">
                        <input type="text" class="form-control pull-right" name="label_photo" id="label_photo" required />
                    </div>
                    <input type="button" class="btn btn-primary" value="Capturer" onclick="take_snapshot()">
                    <input type="button" class="btn btn-primary" value="Changer caméra" id="cameraFlip">
                    <input type="button" class="btn btn-primary" value="Fermer" id="cameraClose" onclick="CloseCamera()">
                </div>
            </div>
        </div>
    </div>
    <div id="myBackdrop" class="modal-backdrop" style="display:none;opacity:.5"></div>
    <!-- <script type="text/javascript" src="assets/js/WebcamEasy.js"></script> -->
    <script type="text/javascript" src="assets/js/WebcamEasyNew.js"></script>
    <script src="assets/js/lightgallery-all.min.js"></script>
    <script src="assets/js/select2.min.js"></script>
    <script src="assets/js/parsley.js"></script>
    <script>
        <?php if ($MobileRun != "1") { ?>
            const webcamElement = document.getElementById('webcam');
            const canvasElement = document.getElementById('canvas');
            const snapSoundElement = document.getElementById('snapSound');
            const webcam = new Webcam(webcamElement, 'user', canvasElement, snapSoundElement);
        <?php } ?>

        function b64toBlob(b64Data, contentType, sliceSize) {
            contentType = contentType || '';
            sliceSize = sliceSize || 512;
            var byteCharacters = atob(b64Data);
            var byteArrays = [];
            for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                var slice = byteCharacters.slice(offset, offset + sliceSize);
                var byteNumbers = new Array(slice.length);
                for (var i = 0; i < slice.length; i++) {
                    byteNumbers[i] = slice.charCodeAt(i);
                }
                var byteArray = new Uint8Array(byteNumbers);
                byteArrays.push(byteArray);
            }
            var blob = new Blob(byteArrays, {
                type: contentType
            });
            return blob;
        }
        <?php if ($MobileRun == "1") { ?>

            function previewFile(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img_id = Date.now();
                        var photo_pa_list = $('#photo_pa_list');
                        var label_photo = $('#label_photo').val();
                        photo_pa_list.append('<div class="photo-item col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" bloc-photo-id="' + img_id + '" label-texte="' + label_photo + '">' +
                            '<div class="card">' +
                            '<div class="card-body">' +
                            '	<label></label>' +
                            '	<a class="btn btn-outline-light float-right delete-pa-photo">supprimer photo</a>  ' +
                            '<div class="input-group" style="width: 100%;"> ' +
                            '	<img style="height:300px;" class="form-control pull-right" name="photo_pa_avant[]" src="' + e.target.result + '" id="pa_pic_' + img_id + '"> ' +
                            '</div> <div class="font-medium text-primary text-left">' + label_photo + '</div> ' +
                            '</div>	' +
                            '</div> ' +
                            '</div>');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        <?php } ?>

        function take_snapshot() {
            webcam.snap();
            var img_id = Date.now()
            var label_photo = $('#label_photo').val();
            var photo_pa_list = $('#photo_pa_list');
            photo_pa_list.append('<div class="photo-item col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" bloc-photo-id="' + img_id + '" label-texte="' + label_photo + '">' +
                '<div class="card">' +
                '<div class="card-body">' +
                '	<label></label>' +
                '	<a class="btn btn-outline-light float-right delete-pa-photo">supprimer photo</a>  ' +
                '<div class="input-group" style="width: 100%;"> ' +
                '	<img style="height:300px;" class="form-control pull-right" name="photo_pa_avant[]" src="' + canvasElement.toDataURL("image/png") + '" id="pa_pic_' + img_id + '"> ' +
                '</div> <div class="font-medium text-primary text-left">' + label_photo + '</div> ' +
                '</div>	' +
                '</div> ' +
                '</div>');
            webcam.stop();
            $("#camera_shooter").hide();
        }

        function CloseCamera() {
            webcam.stop();
            $("#camera_shooter").hide();
        }

        function modalbox_scroll() {
            $('.modal').on("hidden.bs.modal", function(e) { //fire on closing modal box scroll issue hack
                if ($('.modal:visible').length) {
                    $('body').addClass('modal-open');
                }
            });
        }

        function ShowMain() {
            $('#myBackdrop').show();
            $('#box_assignation_ticket').show();
            if ($('#box_assignation_ticket').is(':visible')) {
                if (!$('body').hasClass('modal-open'))
                    $('body').addClass('modal-open');
            }
        }

        function CloseMain() {
            $('#myBackdrop').hide();
            $('#box_assignation_ticket').hide();
            if ($('body').hasClass('modal-open'))
                $('body').removeClass('modal-open');
        }
        modalbox_scroll();
        $(function() {
            $("#cameraFlip").click(function() {
                // webcam.stop();
                webcam.flip();
                webcam.start();
            });
            $("#btn_capture").click(function(e) {
                e.preventDefault();
                webcam.start();
                $("#camera_shooter").show();
            });

            $('.visualiser').click(function() {
                var jeton_actuel = jQuery(this).attr("data-id");
                var compteur = jQuery(this).attr("data-compteur");
                ShowLoader("Chargement des photos en cours...");
                $.ajax({
                    url: "controller.php",
                    dataType: "json",
                    method: "GET",
                    data: {
                        view: 'view_ticket_pic',
                        k: jeton_actuel
                    },
                    success: function(result, statut) {
                        $('#lightgallery').html('');
                        var lignes = $('#lightgallery');
                        if (result.count > 0) {
                            $.each(result.photos, function(i, item) {
                                lignes.append('<li class="col-xs-6 col-sm-4 col-md-4" data-responsive="tickets/' + item.ref_photo + '.png' + '" data-src="tickets/' + item.ref_photo + '.png' + '" data-sub-html="<h4></h4><p>' + item.label_photo + '</p><a  href="#"> <img class="img-responsive" src="tickets/' + item.ref_photo + '.png"></a></li>');
                            });
                        }
                        if ($("#lightgallery").data("lightGallery"))
                            $("#lightgallery").data("lightGallery").destroy(true);
                        jQuery("#lightgallery").lightGallery();
                        $('#ticket_gallery_title').html('TICKET COMPTEUR : ' + compteur);
                        $('#myBackdrop').show();
                        $('#ticket_gallery').show();
                        if (!$('body').hasClass('modal-open'))
                            $('body').addClass('modal-open');
                    },
                    complete: function() {
                        HideLoader();
                    }
                });
            });
            jQuery(document).delegate('a.delete-pa-photo', 'click', function(e) {
                e.preventDefault();
                var itemId = $(this).parents('div.photo-item').attr('bloc-photo-id');
                $('div.photo-item[bloc-photo-id="' + itemId + '"]').remove();
            });
            <?php if ($utilisateur->HasDroits("10_720")) {
            ?>
                $(".assign-ticket").click(function(e) {
                    e.preventDefault();
                    var compteur = jQuery(this).attr("data-num-compteur");
                    var client = jQuery(this).attr("data-client");
                    var ref_assign = jQuery(this).attr("data-id");
                    var cvs = jQuery(this).attr("data-cvs");
                    var adress = jQuery(this).attr("data-adress");
                    var pa = jQuery(this).attr("data-pa");
                    var chef = jQuery(this).attr("data-chef-install");
                    var index_defaut = jQuery(this).attr("data-index-par-defaut");
                    var installateur = jQuery(this).attr("data-installateur");
                    var tarif = jQuery(this).attr("data-tarif");
                    $('#ref_demande').val(ref_assign);
                    $('#assign_compteur').html(compteur);
                    $('#assign_client').html(client);
                    $('#assign_adresse').html(adress);
                    $('#assign_cvs').html(cvs);
                    $('#assign_installateur').html(installateur);
                    $('#assign_tarif').html(tarif);
                    $('#assign_pa').html(pa);
                    $('#assign_index').html(index_defaut);
                    $('#assign_chef').html(chef);
                    ShowMain();
                });
                $('#btn_submit_assignation').click(function(e) {
                    e.preventDefault();
                    var form = document.getElementById("frm_assignation_ticket");
                    var frm = $("#frm_assignation_ticket");
                    if (frm.parsley().validate()) {
                        // alert("oui");				   
                    } else {
                        // alert("non");
                        return false;
                    }
                    swal({
                        title: "Information",
                        text: 'Voulez-vous assigner le ticket?',
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#00A65A",
                        confirmButtonText: "Oui",
                        cancelButtonText: "Non",
                        closeOnConfirm: false,
                        closeOnCancel: true
                    }, function(isConfirm) {
                        if (isConfirm) {
                            ShowLoader("Assignation Ticket en cours...");
                            var num_ticket = $("#numero_ticket_").val();
                            var formTicket = new FormData(form);
                            formTicket.append("numero_ticket", num_ticket);
                            var lignes = $('#photo_pa_list .photo-item');
                            lignes.each(function(i) {
                                var row_id = $(this).attr('bloc-photo-id');
                                var row_label = $(this).attr('label-texte');
                                var base64img = document.getElementById("pa_pic_" + row_id).src;
                                if (base64img.length) {
                                    if (base64img.match(/^data\:image\/(\w+)/)) {
                                        var block_img = base64img.split(";");
                                        var contentype = block_img[0].split(":")[1];
                                        var realDat = block_img[1].split(",")[1];
                                        var blob_ = b64toBlob(realDat, contentype);
                                        formTicket.append("photo_pa_avant[]", blob_);
                                        formTicket.append("photo_pa_avant_label[]", row_label);
                                    }
                                }
                            });
                            $.ajax({
                                //enctype: 'multipart/form-data',
                                url: "controller.php",
                                data: formTicket, // Add as Data the Previously create formData
                                type: "POST",
                                contentType: false,
                                processData: false,
                                cache: false,
                                dataType: "json", // Change this according to your response from the server.
                                error: function(err) {
                                    console.error(err);
                                    // $('#loadMe').modal('hide');
                                    $("#btn_submit_assignation").removeAttr('disabled');
                                    $("#btn_submit_assignation").text("Assigner");
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
                                            $("#btn_submit_assignation").text("Assignation terminée");
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
                                            $("#btn_submit_assignation").removeAttr('disabled');
                                            $("#btn_submit_assignation").text("Assigner");
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
                                        //$("#btn_demander_ticket").attr('disabled','disabled');
                                        $("#btn_submit_assignation").removeAttr('disabled');
                                        //$("#btn_demander_ticket").addClass('btn-primary');
                                        //$("#btn_demander_ticket").text("Envoyer");
                                    }
                                },
                                complete: function() {
                                    // console.log("Request finished.");
                                    HideLoader();
                                }
                            });
                        }
                    });
                });
            <?php } ?>
            jQuery(document).delegate('a.close', 'click', function(e) {
                e.preventDefault();
                var pId = $(this).parents('div.modal').attr("id");
                $(this).parents('div.modal').hide();
                $('#myBackdrop').hide();
                if ($('body').hasClass('modal-open'))
                    $('body').removeClass('modal-open');
            });
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