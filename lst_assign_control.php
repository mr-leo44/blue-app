<?php
// session_start();
$mnu_title = "ASSIGNATION POUR CONTROLE";
$page_title = "Historique des assignations pour contrôle";
$home_page = "lst_assign_control.php";
$active = "lst_assign_control";
$parambase = "";


require_once 'loader/init.php';
Autoloader::Load('classes');
include_once 'core.php';
header('Content-type: text/html;charset=utf-8');
$database = new Database();
$db = $database->getConnection();
$Abonne = new PARAM_Assign($db);
$Abonne->type_assignation = '1';
$utilisateur = new Utilisateur($db);
$commune = new Commune($db);
$communeEntity = new AdresseEntity($db);

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
$site = new Site($db);
$province = new AdresseEntity($db);

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
$page_url = 'lst_assign_control.php?';

// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;
$page_c = 'lst_assign_control.php';
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
$records_per_page = 30;

$filtre = '';

if (isset($_GET['filtre-search']) && count($_GET['filtre-search']) > 0) {
    $est_installer = array();
    $e_commune = array();
    $param_cvs = array();
    $assignation_status_ = array();
    $equipe_ident_ = array();
    $chef_equipe_ident_ = array();
    $identificateurs_arr = array();
    $arr_sites =  [];
    $organisme_ = [];

    // $filtres = explode(',', $_GET['filtre-search']);
    $filtres = $_GET['filtre-search'];

    foreach ($filtres as $k_ => $v_) {
        $filter_item = explode('=', $v_);

        if ($filter_item[0] == 't_param_assignation.statut_') {
            $est_installer[] = $v_;
        } else if ($filter_item[0] == 'e_commune.code') {
            $e_commune[] = $v_;
        } else if ($filter_item[0] == 't_main_data.cvs_id') {
            $param_cvs[] = $v_;
        } else if ($filter_item[0] == 'id_equipe_identification') {
            $equipe_ident_[] = $v_;
        } else if ($filter_item[0] == 't_chef_equipe.code_utilisateur') {
            $chef_equipe_ident_[] = $v_;
        } else if ($filter_item[0] == 't_main_data.identificateur') {
            $identificateurs_arr[] = $v_;
        } else if ($filter_item[0] == 't_param_assignation') {
            $assignation_status_[] = $v_;
        } else if ($filter_item[0] == 't_main_data.ref_site_identif') {
            $arr_sites[] = $v_;
        } else if ($filter_item[0] == "t_param_assignation.id_organe") {
            $organisme_[] = $v_;
        }
    }

    if (count($organisme_) > 0) {
        $filtre .= " and (";
        $len_ = count($organisme_);
        $contexte_ctr = 0;
        foreach ($organisme_ as $est_item) {
            //$len_moins = $len_ - 1;
            if ($contexte_ctr == 0) {
                $filtre .=  $est_item . "";
            } else {
                $filtre .= " Or " . $est_item . "";
            }

            $contexte_ctr++;
        }
        $filtre .= ")";
    }

    if (count($arr_sites) > 0) {
        $filtre .= " and (";
        $len_ = count($arr_sites);
        $contexte_ctr = 0;
        foreach ($arr_sites as $est_item) {
            //$len_moins = $len_ - 1;
            if ($contexte_ctr == 0) {
                $filtre .=  $est_item . "";
            } else {
                $filtre .= " Or " . $est_item . "";
            }

            $contexte_ctr++;
        }
        $filtre .= ")";
    }
    if (count($assignation_status_) > 0) {
        $filtre .= " and (";
        $len_ = count($assignation_status_);
        $contexte_ctr = 0;
        foreach ($assignation_status_ as $est_item) {
            //$len_moins = $len_ - 1;
            if ($contexte_ctr == 0) {
                $filtre .=  $est_item . "";
            } else {
                $filtre .= " Or " . $est_item . "";
            }

            $contexte_ctr++;
        }
        $filtre .= ")";
    }

    if (count($chef_equipe_ident_) > 0) {
        $filtre .= " and (";
        $len_ = count($chef_equipe_ident_);
        $contexte_ctr = 0;
        foreach ($chef_equipe_ident_ as $est_item) {
            //$len_moins = $len_ - 1;
            if ($contexte_ctr == 0) {
                $filtre .=  $est_item . "";
            } else {
                $filtre .= " Or " . $est_item . "";
            }

            $contexte_ctr++;
        }
        $filtre .= ")";
    }
    if (count($identificateurs_arr) > 0) {
        $filtre .= " and (";
        $len_ = count($identificateurs_arr);
        $contexte_ctr = 0;
        foreach ($identificateurs_arr as $est_item) {
            //$len_moins = $len_ - 1;
            if ($contexte_ctr == 0) {
                $filtre .=  $est_item . "";
            } else {
                $filtre .= " Or " . $est_item . "";
            }

            $contexte_ctr++;
        }
        $filtre .= ")";
    }
    if (count($equipe_ident_) > 0) {
        $filtre .= " and (";
        $len_ = count($equipe_ident_);
        $contexte_ctr = 0;
        foreach ($equipe_ident_ as $est_item) {
            //$len_moins = $len_ - 1;
            if ($contexte_ctr == 0) {
                $filtre .=  $est_item . "";
            } else {
                $filtre .= " Or " . $est_item . "";
            }

            $contexte_ctr++;
        }
        $filtre .= ")";
    }
    if (count($est_installer) > 0) {
        $filtre .= " and (";
        $len_ = count($est_installer);
        $contexte_ctr = 0;
        foreach ($est_installer as $est_item) {
            //$len_moins = $len_ - 1;
            if ($contexte_ctr == 0) {
                $filtre .=  $est_item . "";
            } else {
                $filtre .= " Or " . $est_item . "";
            }

            $contexte_ctr++;
        }
        $filtre .= ")";
    }
    if (count($e_commune) > 0) {
        $filtre .= " and (";
        $len_ = count($e_commune);
        $contexte_ctr = 0;
        foreach ($e_commune as $est_item) {
            //$len_moins = $len_ - 1;
            if ($contexte_ctr == 0) {
                $filtre .=  $est_item . "";
            } else {
                $filtre .= " Or " . $est_item . "";
            }

            $contexte_ctr++;
        }
        $filtre .= ")";
    }
    if (count($param_cvs) > 0) {
        $filtre .= " and (";
        $len_ = count($param_cvs);
        $contexte_ctr = 0;
        foreach ($param_cvs as $est_item) {
            //$len_moins = $len_ - 1;
            if ($contexte_ctr == 0) {
                $filtre .=  $est_item . "";
            } else {
                $filtre .= " Or " . $est_item . "";
            }

            $contexte_ctr++;
        }
        $filtre .= ")";
    }
}
$du = isset($_GET['Du']) ? Utils::ClientToDbDateFormat($_GET['Du']) : "";
$au = isset($_GET['Au']) ? Utils::ClientToDbDateFormat($_GET['Au']) : "";
if ($search_term == '' and $du == "" and $au == "") {
    $stmt = $Abonne->readAll($from_record_num, $records_per_page, $utilisateur, $filtre);
    $total_rows = $Abonne->countAll($utilisateur, $filtre);
} else {
    $page_url .= "s={$search_term}&";
    $stmt = $Abonne->search($du, $au, $search_term, $from_record_num, $records_per_page, $utilisateur, $filtre);
    $total_rows = $Abonne->countAll_BySearch($du, $au, $search_term, $utilisateur, $filtre);
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
                                <a href="<?php echo 'lst_assign_control.php'; ?>" class="breadcrumbs_home"><i class='fas fa-calendar-check nav_icon'></i> <?php echo $mnu_title; ?></a> <span class="raquo">»</span>
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
                                <div class="row " id="advanced_search">
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
                                                        <div class="col-sm-3">
                                                            <div class="form-group text-left mb-0 mt-1">
                                                                <select class='form-control select2' style='width: 100%;' id='filtre-search' name='filtre-search[]' multiple="multiple">
                                                                    <option value="t_param_assignation.statut_='1'">Controlé</option>
                                                                    <option value="t_param_assignation.statut_='0'">Non controlé</option>
                                                                    <?php

                                                                    $stmt_tarif = $type_compteur->read();
                                                                    while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
                                                                        echo "<option value=t_log_installation.type_new_cpteur='" . $row_gp["code"] . "'>Type compteur - " . $row_gp["libelle"] . "</option>";
                                                                    }
                                                                    echo "<option value=t_log_installation.type_new_cpteur=''>Type compteur - Non défini</option>";
                                                                    if ($utilisateur->id_service_group ==  '3') {  //Administration
                                                                        $stmt_chief = $utilisateur->GetAll_OrganeUserListForAdmin();
                                                                        while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
                                                                            echo "<option value=t_log_installation.installateur='" . $row_chief["code_utilisateur"] . "'>Installateur - " . $row_chief["nom_complet"] . "</option>";
                                                                        }
                                                                    } else {
                                                                        $stmt_chief = $utilisateur->GetCurrentUserListIdentificateurs($utilisateur->code_utilisateur, $utilisateur->id_organisme, $utilisateur->is_chief);

                                                                        while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
                                                                            echo "<option value=t_log_installation.installateur='" . $row_chief["code_utilisateur"] . "'>Installateur - " . $row_chief["nom_complet"] . "</option>";
                                                                        }
                                                                    }


                                                                    $stmt_chief = null;
                                                                    if ($utilisateur->id_service_group ==  '3') {  //Administration
                                                                        $stmt_chief = $utilisateur->GetAllChiefForAdmin();
                                                                        while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
                                                                            echo "<option value=t_chef_equipe.code_utilisateur='{$row_chief["code_utilisateur"]}'>Chef équipe - {$row_chief["nom_complet"]}</option>";
                                                                        }
                                                                    } else {
                                                                        $stmt_chief = $utilisateur->GetCurrentUserChief($utilisateur);
                                                                        while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
                                                                            echo "<option value=t_chef_equipe.code_utilisateur='{$row_chief["code_utilisateur"]}'>Chef équipe - {$row_chief["nom_complet"]}</option>";
                                                                        }
                                                                    }

                                                                    $stmt_select = $province->getAllProvinces();
                                                                    $provinces = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

                                                                    foreach ($provinces as $row_select) {
                                                                        // echo "<option value=t_param_adresse_entity.code='" . $row_select["code"] . "'>Province - " . $row_select["libelle"] . "</option>";
                                                                    }

                                                                    foreach ($provinces as $province) {
                                                                        $stmt_select = $communeEntity->GetProvinceAllCommune($province['code']);
                                                                        while ($row_select = $stmt_select->fetch(PDO::FETCH_ASSOC)) {
                                                                            echo "<option value=e_commune.code='" . $row_select["code"] . "'>Commune - " . $row_select["libelle"] . "</option>";
                                                                        }


                                                                        $stmt_select = $communeEntity->GetProvinceAllCVS($province['code']);
                                                                        while ($row_select = $stmt_select->fetch(PDO::FETCH_ASSOC)) {
                                                                            echo "<option value=t_main_data.cvs_id,='" . $row_select["code"] . "'>CVS - " . $row_select["libelle"] . "</option>";
                                                                        }
                                                                    }

                                                                    $stmt_select = $site->GetAll();
                                                                    while ($row_select = $stmt_select->fetch(PDO::FETCH_ASSOC)) {
                                                                        echo "<option value=t_main_data.ref_site_identif='" . $row_select["code"] . "'>Site - " . $row_select["libelle"] . "</option>";
                                                                    }

                                                                    if ($utilisateur->id_service_group ==  '3' || $utilisateur->HasGlobalAccess()) {  //Administration
                                                                        $stmt_ = $organisme->read();
                                                                        while ($row_gp = $stmt_->fetch(PDO::FETCH_ASSOC)) {
                                                                            echo "<option value=t_param_assignation.id_organe='{$row_gp["ref_organisme"]}'>Organisme - {$row_gp["denomination"]}</option>";
                                                                        }
                                                                    } else {
                                                                        $organisme->ref_organisme = $utilisateur->id_organisme;
                                                                        $row_gp = $organisme->GetDetail();
                                                                        echo "<option value=t_param_assignation.id_organe='{$row_gp["ref_organisme"]}'>Organisme - {$row_gp["denomination"]}</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="input-group  mt-2">
                                                                <input type="text" autocomplete="none" class="form-control datetimepicker-input" name="Du" id="Du" placeholder="Du" />
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text" id="add_on_du"><i class="far fa-calendar-alt"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-2">
                                                            <div class="input-group  mt-2">
                                                                <input type="text" class="form-control datetimepicker-input" name="Au" id="Au" placeholder="Au" />
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text" id="add_on_au"><i class="far fa-calendar-alt"></i></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <button type="submit" name="search" id="btn_search" class="mx-2 btn btn-primary"><i class="fa fa-search"></i>
                                                        </button>
                                                        <button style="display: none;" id="delete-cancel-checkboxes" class="btn mx-2 btn-danger">Désactiver l'annulation</button>
                                                        <button style="display: none;" id="select-all-checkboxes" class="btn btn-warning mr-2">Tout séléctionner</button>
                                                        <div id="remove-assignments-container"> </div>

                                                        <button id="add-cancel-checkboxes" class="btn  btn-warning">Activer l'annulation</button>
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
                                        if ($utilisateur->HasDroits("10_480")) {
                                            $num_line = 0;

                                            while ($row_ = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $num_line++;

                                                $organisme->ref_organisme = $row_["id_organe"];
                                                $row_gp = $organisme->GetDetail();
                                                //	echo "<option value='{$row_gp["ref_organisme"]}'>

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
                                                                if ($utilisateur->HasDroits("10_490")) {
                                                                    echo '<a class="dropdown-item delete" href="#"  data-name="' . $row_["nom_client_blue"] . '" data-id="' . $row_["id_assign"] . '" data-num-compteur="' . $row_["num_compteur_actuel"] . '">Annuler</a>';
                                                                }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">

                                                            <div class="col-sm-4">
                                                                <div class="text-dark">
                                                                    Organisme
                                                                </div>
                                                                <div class="font-medium text-primary client-adress"><?php echo $row_gp["denomination"];   ?></div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="text-dark">
                                                                    Date assignation
                                                                </div>
                                                                <div class="font-medium text-primary client-adress"><?php echo $row_["date_sys_fr"];   ?></div>
                                                            </div>
                                                            <div class="col-sm-4 text-left">
                                                                <div class="text-dark">
                                                                    Client
                                                                </div>
                                                                <div class="font-medium text-primary client-phone"><?php echo $row_["nom_client_blue"];   ?></div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-3">
                                                                <div class="text-dark">
                                                                    Adresse
                                                                </div>
                                                                <div class="font-medium text-primary client-device"><?php

                                                                                                                    echo $adress_item->GetAdressInfoTexte($row_["adresse_id"]);   ?></div>
                                                            </div>
                                                            <div class="col-sm-3">
                                                                <div class="text-dark">
                                                                    REFERENCE APPARTEMENT
                                                                </div>
                                                                <div class="font-medium text-primary client-device"><?php

                                                                                                                    echo $row_["reference_appartement"];   ?></div>
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
                                                                    <span class="badge <?php echo Utils::getAssign_Control_Badge($row_["statut_"]); ?>"><?php echo Utils::getAssign_Control_Statut($row_["statut_"]);   ?></span>
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
        <!--
<div class="btn-group-fab" role="group" aria-label="FAB Menu">
  <div>
    <button type="button" class="btn btn-main btn-primary has-tooltip" data-placement="left" title="Menu"> <i class="fa fa-bars"></i> </button>
    <button type="button" class="btn btn-sub btn-info has-tooltip" data-placement="left" title="Fullscreen"> <i class="fa fa-arrows-alt"></i> </button>
    <button type="button" class="btn btn-sub btn-danger has-tooltip" data-placement="left" title="Save"> <i class="fa fa-floppy-o"></i> </button>
    <button type="button" class="btn btn-sub btn-warning has-tooltip" data-placement="left" title="Download"> <i class="fa fa-download"></i> </button>
  </div>
</div>		-->
        <?php
        if ($utilisateur->HasDroits("10_495")) {

            echo '<div class="btn-group-fab" role="group" aria-label="FAB Menu">
	  <div>
		<button type="button" class="btn btn-main btn-primary has-tooltip" data-placement="left" title="Menu" id="btn_new_"> <i class="fa fa-plus"></i> </button>
		<button type="button" class="btn btn-sub btn-primary has-tooltip" data-placement="left" title="Menu" id="btn_new_"> <i class="fa fa-plus"></i> </button>
	  </div>
	</div>';
        }
        include_once "layout_script.php";
        ?>

        <div class="modal" id="dlg_main" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true" data-backdrop="static" style="overflow: scroll;">
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
                                                            <option selected='selected' value=''>- Veuillez préciser le CVS - </option>
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
                                                            if ($utilisateur->id_service_group ==  '3') {  //Administration
                                                                $stmt_ = $organisme->readExclusive($utilisateur, 2);
                                                                //	$stmt_ = $organisme->read();
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
                                                            <select class='form-control select2' style='width: 100%;' name='chef_equipe_control' id='chef_equipe_control' required>
                                                                <option selected='selected' disabled>Veuillez préciser</option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label>CONTROLEUR QUALITE <span class="ml-1 text-danger"></span></label>
                                                        <div class="input-group" style="width: 100%;">

                                                            <select class='form-control select2' style='width: 100%;' name='controleur_quality' id='controleur_quality'>

                                                                <option selected='selected' disabled>Veuillez préciser</option>
                                                                <?php
                                                                //Exclusivité du remplacement à Blue-Energy (1) 10_570law 
                                                                $stmt_chief = $utilisateur->GetExclusiveQualityControleur($utilisateur, '2', '10_560');
                                                                while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
                                                                    echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
                                                                }    ?>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label>Filtre</span></label>
                                                        <div class="input-group" style="width: 100%;">
                                                            <select class='form-control select2' style='width: 100%;height:35px;' id='filtre' name='filtre'>
                                                                <option value=""> </option>
                                                                <option value="t_main_data.ref_dernier_log_controle IS NULL">Jamais Contrôlé</option>
                                                                <option value="t_main_data.ref_dernier_log_controle IS NOT NULL">Contrôlé</option>
                                                                <option value=">">Contrôlé plus de </option>
                                                                <option value="<">Contrôlé moins de </option>
                                                            </select>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                                                    <div class="form-group">
                                                        <label>Nbre Jours</span></label>
                                                        <div class="input-group" style="width: 100%;">
                                                            <input type="number" id="nbre_jour" name="nbre_jour" class="form-control" placeholder="Nombre jour">

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
                                            <h4 class="mb-0">Liste des compteurs</h4>
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




        <!--  <script type="text/javascript" src="assets/js/webcam-easy.min.js"></script>  -->

        <script src="assets/js/select2.min.js"></script>
        <script src="assets/js/parsley.js"></script>
        <script>
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

            var load_cvs = false;
            var load_chief = false;

            $('#filtre-search').select2({
                placeholder: "Filtre CVS, Equipe installation, ....",
                multiple: true
            });




            jQuery(document).delegate('a.close', 'click', function(e) {
                e.preventDefault();
                var pId = $(this).parents('div.modal').attr("id");
                $(this).parents('div.modal').hide();

            });

            jQuery(document).delegate('a.checkAll', 'click', function(e) {
                e.preventDefault();
                $('#list-compteurs input:checkbox').each(function() {
                    $(this).prop('checked', true);
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



            /*
								$("#cvs_id").on("change", function (e) {
                                });*/

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

                ShowLoader("Patientez ...");
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
                                    $("#dlg_main").hide();
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
                    $('#dlg_main').show();

                });
            <?php } ?>


            <?php if ($utilisateur->HasDroits("10_490")) {
            ?>

                function cancelAssignements(data = []) {
                    let textMessage = ""

                    if (data.length == 1) {
                        textMessage = `Voulez-vous annuler l'assignation du compteur ( ${data[0]} )?`
                    } else if (data.length > 1) {
                        textMessage = `${data.length} elements à supprimer. \n Voulez-vous annuler l(es) assignation(s) de(s) compteur(s) ( ${data.join(', ')} )?`
                    } else {
                        return false
                    }

                    swal({
                        title: "Informations",
                        text: textMessage,
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
                                    k: JSON.stringify(data)
                                },
                                success: function(data) {
                                    var result = $.parseJSON(data);
                                    if (result.error == 0) {
                                        swal({
                                            title: "Informations",
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
                }
                $('.delete').click(function() {
                    var name_compteur = jQuery(this).attr("data-num-compteur");
                    var name_actuel = jQuery(this).attr("data-name");
                    var jeton_actuel = jQuery(this).attr("data-id");
                    cancelAssignements([jeton_actuel])
                });
            <?php } ?>


            <?php
            //if($utilisateur->id_service_group ==  '3'){
            ?>

            $("#id_equipe_identification").on("change", function(e) {
                var item = $(this).val();
                e.preventDefault();
                $("#chef_equipe_control").html('');
                if (item.length == 0) {
                    return false;
                }
                ShowLoader("Chargement liste des chefs d'equipe en cours...");

                $.ajax({
                    url: "controller.php",
                    method: "GET",
                    data: {
                        view: "get_organisme_chief_control",
                        id_: item
                    },
                    success: function(data, statut) {
                        try {
                            var result = $.parseJSON(data);
                            if (result.error == 0) {
                                $("#chef_equipe_control").html(result.data);
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
                    complete: function() {
                        HideLoader();
                    }
                });
            });

            <?php
            //}
            ?>

            let checkboxes_list = []

            function toggleAddCancelCheckboxesButton() {
                $('#add-cancel-checkboxes').toggle()
            }

            function toggleSelectAllCheckboxesButton() {
                $("#select-all-checkboxes").toggle()
            }

            function toggleDeleteSelectedCheckbosesButton() {
                $('#delete-cancel-checkboxes').toggle()
                // $('#remove-assignements').toggle()
            }

            function removeAssignments() {
                $("#remove-assignments").click(function() {
                    event.preventDefault()
                    event.stopImmediatePropagation()

                    cancelAssignements(checkboxes_list)
                })
            }

            // function selectAllCheckboxes() {
            $("#select-all-checkboxes").click(function() {
                event.preventDefault()
                event.stopPropagation()

                $(".client-row label.selected-checkbox input").each(function(_, element) {
                    element.checked = false
                    element.click()
                })
            })
            // }

            $('#add-cancel-checkboxes').click(function() {
                event.preventDefault()
                event.stopImmediatePropagation()

                toggleAddCancelCheckboxesButton()
                toggleSelectAllCheckboxesButton()
                toggleDeleteSelectedCheckbosesButton()

                addCheckboxes()
            });

            function addCheckboxes() {
                // Créez une nouvelle case à cocher avec une classe spécifique
                checkboxes_list = []
                var newCheckbox = $(`
                <label style="position:relative;top:5px" class="rounded-b selected-checkbox btn btn-warning">
                    <span  span>Sélectionner l'assignation</span>
                    <input type="checkbox" name="selected-checkboxes[]" class="dynamic-checkbox btn ">
                </label>
                `);

                function toggleIdInArray(array, id) {
                    var index = array.indexOf(id);

                    if (index === -1) {
                        array.push(id);
                    } else {
                        array.splice(index, 1);
                    }
                }

                // Ajoutez la case à cocher au conteneur 
                $('.client-row').append(newCheckbox);


                $(".client-row label.selected-checkbox input").change(function() {

                    var labelElement = $(this).closest('label.selected-checkbox');
                    var clientRowElement = $(this).closest('.client-row');

                    clientRowElement.toggleClass("app-border-danger  ")

                    let name = clientRowElement.find(".dropdown-item.delete").data("name")
                    let id = clientRowElement.find(".dropdown-item.delete").data("id")
                    let num_compteur = clientRowElement.find(".dropdown-item.delete").data("num-compteur")

                    toggleIdInArray(checkboxes_list, id)
                    console.log("IDs : ", checkboxes_list)
                    // console.log(clientRowElement.find(".dropdown-item.delete").data())
                    labelElement.toggleClass('btn-warning');
                    labelElement.toggleClass('btn-danger');


                    var remove_assignments = $(`<button id="remove-assignments" class="btn  btn-success">Annuller les assignations (<span id="assignements-nomber">${checkboxes_list.length}</span>)</button>`)

                    if (checkboxes_list.length) {
                        $("#remove-assignments-container").html("")
                        $("#remove-assignments-container").append(remove_assignments)
                    }

                    removeAssignments()

                })
            }


            $("#delete-cancel-checkboxes").click(function(e) {
                event.preventDefault()
                event.stopImmediatePropagation()

                toggleAddCancelCheckboxesButton()
                toggleDeleteSelectedCheckbosesButton()
                deleteSelectedCheckboxes()
            })

            function deleteSelectedCheckboxes() {
                checkboxes_list = []
                $(".client-row label.selected-checkbox").remove()
                $(".client-row").each(function(_, e) {
                    e.classList.remove("app-border-danger")
                })
                $("#remove-assignments-container").html("")

            }

            $("#search-btn").click(function() {
                var filtre = $("#filtre").val();
                var jour = $("#nbre_jour").val();
                var item = $("#cvs_id").val();
                var search_param = $("#srch-term-client").val();

                ShowLoader("Chargement liste des compteurs en cours...");
                $("#list-compteurs").html('');
                $.ajax({
                    url: "controller.php",
                    method: "GET",
                    data: {
                        view: "get_cvs_compteur_controle",
                        id_: item,
                        filtre: filtre,
                        jour: jour,
                        search_param: search_param
                    },
                    success: function(data, statut) {
                        try {
                            var result = $.parseJSON(data);
                            if (result.error == 0) {
                                //  $("#list-compteurs").html(result.data);
                                $("#list-compteurs").html('');

                                if (result.deja_assigner_count > 0) {
                                    if (result.deja_assigner_count == 1) {
                                        swal("Information", "Il y a " + result.deja_assigner_count + " compteur déjà une assignation valide qui est exclus de ce résultat", "warning");

                                    } else {
                                        swal("Information", "Il y a " + result.deja_assigner_count + " compteurs ayant déjà des assignations valides qui sont exclus de ce résultat", "warning");
                                    }
                                }
                                $.each(result.items, function(i, item) {
                                    // Id = generateItemID(lignes);
                                    //<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"> 
                                    $("#list-compteurs").append(' <div class="client-row card bg-white"><div class="card-body"><div class="custom-control custom-checkbox"><input class="custom-control-input" id="chk_' + item.data.id_ + '" name="tbl-checkbox[]" type="checkbox" value="' + item.data.id_ + '" data-parsley-multiple="tbl-checkbox"><label class="cursor-pointer font-italic d-block custom-control-label" for="chk_' + item.data.id_ + '"> </label></div><div class="row"><div class="col-sm-3"><div class="text-dark">Compteur</div><div class="font-medium text-primary compteur-number">' + item.data.num_compteur_actuel + ' </div></div><div class="col-sm-3 text-left"><div class="text-dark">Date dernier contrôle</div><div class="font-medium text-primary compteur-customer">' + item.data.date_dernier_controle_fr + ' <span class="badge badge-danger">' + item.data.jour_passer_dernier_controle + ' jour(s)</span></div></div><div class="col-sm-3 text-left"><div class="text-dark">Client</div><div class="font-medium text-primary compteur-customer">' + item.data.nom_client_blue + '</div></div><div class="col-sm-3 text-right"><div class="text-dark">Téléphone</div><div class="font-medium text-primary compteur-phone">' + item.data.phone_client_blue + '</div></div><div class="col-sm-3"><div class="text-dark">Adresse</div><div class="font-medium text-primary compteur-adress">' + item.adresseTexte + '</div></div><div class="col-sm-3 text-left"><div class="text-dark">CVS</div><div class="font-medium text-primary compteur-cvs">' + item.data.libelle + '</div></div><div class="col-sm-3 text-left"><div class="text-dark">REFERENCE APPARTEMENT</div><div class="font-medium text-primary compteur-cvs">' + item.data.reference_appartement + '</div></div><div class="col-sm-3 text-right"><div class="text-dark">Statut</div><div><span class="badge badge-danger">' + '' + '</span></div></div></div></div></div>');
                                    // item.data.etat_compteur
                                });
                                if (result.items.length == 0) {
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
                                $('#dlg_main-control-assign').show();
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
                    complete: function() {
                        HideLoader();
                    }
                });

            });

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