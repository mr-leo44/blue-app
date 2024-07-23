<?php
/*
// Android (in-app)
if($_SERVER['HTTP_X_REQUESTED_WITH'] == "com.hedi.blue.app") {
    echo 'Android';
}
*/

// if (strpos($_SERVER['HTTP_USER_AGENT'], 'com.hedi.blue.app') !== false){
// echo "true";
// }
// var_dump($_SERVER);
// exit;
// session_start();
$mnu_title = "IDENTIFICATION";
$page_title = "Liste des identifications effectuées";
$home_page = "dashboard.php";
$active = "abonnes";
$parambase = "";

require_once 'vendor/autoload.php';
require_once 'vendor/autoload.php';
require_once 'loader/init.php';
Autoloader::Load('classes');
include_once 'core.php';
header('Content-type: text/html;charset=utf-8');
$database = new Database();
$db = $database->getConnection();
$Abonne = new Identification($db);
$utilisateur = new Utilisateur($db);
$commune = new AdresseEntity($db);
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
$section_cable = new PARAM_Section_Cable($db);
$type_usage = new Param_TypeUsage($db);
$marquecompteur = new MarqueCompteur($db);
$site = new Site($db);
$province = new AdresseEntity($db);

if ($utilisateur->is_logged_in() == false) {
    $utilisateur->redirect('login.php');
}
$utilisateur->readOne();
?>
<!doctype html>
<html lang="fr">

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

        thead {
            position: -webkit-sticky;
            position: -moz-sticky;
            position: -ms-sticky;
            position: -o-sticky;
            position: sticky;
            top: 0px;
            background: white;
        }
    </style>
    <link href="assets/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="assets/css/select2.css" rel="stylesheet">

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
                                <a href="<?php echo 'lst_identifs.php'; ?>" class="breadcrumbs_home"><i class='fas fa-folder-open nav_icon'></i> <?php echo $mnu_title; ?></a> <span class="raquo">»</span>
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
                        <div class="card mb-1">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="h5 font-weight-bold text-primary"> Journal des identifications effectuées</div>
                                        <div id="record_count" class="font-semi-bold" style="color:#5e6e82;font-size:16px">
                                            - </div>
                                    </div>

                                    <div class="col">
                                        <div class="row mb-2">
                                            <div class="col-sm-12 text-right pr-1">







                                                <div class="btn-group">
                                                    <div class="row">
                                                        <div class="col-md-6 pull-right">
                                                            <div> Eléments par page </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <select class="form-control select2 ml-auto w-auto" id="show" onChange="changeDisplayRowCount(this.value);">
                                                                <option value="10" selected="">10</option>
                                                                <option value="20">20</option>
                                                                <option value="30">30</option>
                                                                <option value="50">50</option>
                                                                <option value="100">100</option>
                                                            </select>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-1">

                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="input-group mt-2">
                                                <input type="text" id="srch-term" name='s' class="form-control" placeholder="Recherche ..." required>

                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group text-left mb-0 mt-1" id='bloc_installateur'>
                                                <select class='form-control select2' style='width: 100%;height:35px;' id='filtre' name='filtre[]' required multiple="multiple">
                                                    <option value="est_installer='1'">Installé</option>
                                                    <option value="est_installer='0'">Non Installé</option>
                                                    <option value="is_draft='1'">Brouillon</option>
                                                    <?php
                                                    $stmt_chief = null;
                                                    if ($utilisateur->id_service_group ==  '3') {  //Administration
                                                        $stmt_chief = $utilisateur->GetAllChiefForAdmin();
                                                    } else {
                                                        $stmt_chief = $utilisateur->GetCurrentUserChief($utilisateur); //->code_utilisateur,$utilisateur->id_organisme,$utilisateur->chef_equipe_id);
                                                    }

                                                    $chief_rows = $stmt_chief->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($chief_rows as $row_chief) {
                                                        echo "<option value=t_main_data.chef_equipe='" . $row_chief["code_utilisateur"] . "'> Chef équipe - " . $row_chief["nom_complet"] . "</option>";
                                                    }


                                                    $stmt_chief = null;
                                                    if ($utilisateur->id_service_group ==  '3') {  //Administration
                                                        $stmt_chief = $utilisateur->GetAll_OrganeUserListForAdmin();

                                                        $chief_rows = $stmt_chief->fetchAll(PDO::FETCH_ASSOC);
                                                        foreach ($chief_rows as $row_chief) {
                                                            echo "<option value=t_main_data.identificateur='" . $row_chief["code_utilisateur"] . "'>Identificateur - " . $row_chief["nom_complet"] . "</option>";
                                                        }
                                                    } else {

                                                        $stmt_chief = $utilisateur->GetCurrentUserListIdentificateurs($utilisateur->code_utilisateur, $utilisateur->id_organisme, $utilisateur->is_chief);
                                                        if ($utilisateur->is_chief == '1') {
                                                            echo "<option value=t_main_data.identificateur='" . $utilisateur->code_utilisateur . "'>Identificateur - " . $utilisateur->nom_complet . "</option>";
                                                        }
                                                    }

                                                    $chief_rows = $stmt_chief->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($chief_rows as $row_chief) {
                                                        echo "<option value=t_main_data.identificateur='" . $row_chief["code_utilisateur"] . "'>Identificateur - " . $row_chief["nom_complet"] . "</option>";
                                                    }

                                                    $stmt_select = $province->getAllProvinces();
                                                    $provinces = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($provinces as $province) {
                                                        $stmt_select = $commune->GetProvinceAllCommune($province['code']);
                                                        $rows = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

                                                        foreach ($rows as $row_select) {
                                                            echo "<option value=e_commune.code='" . $row_select["code"] . "'>Commune - " . $row_select["libelle"] . "</option>";
                                                        }


                                                        $stmt_select = $commune->GetProvinceAllCVS($province['code']);
                                                        $rows = $stmt_select->fetchAll(PDO::FETCH_ASSOC);

                                                        foreach ($rows as $row_select) {
                                                            echo "<option value=t_param_cvs.code='" . $row_select["code"] . "'>CVS - " . $row_select["libelle"] . "</option>";
                                                        }
                                                    }

                                                    $stmt_select = $site->GetAll();
                                                    $rows = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($rows as $row_select) {
                                                        echo "<option value=t_main_data.ref_site_identif='" . $row_select["code"] . "'>Site - " . $row_select["libelle"] . "</option>";
                                                    }

                                                    if ($utilisateur->id_service_group ==  '3' || $utilisateur->HasGlobalAccess()) {  //Administration
                                                        $stmt_ = $organisme->read();

                                                        $row_gps = $stmt_->fetchAll(PDO::FETCH_ASSOC);
                                                        foreach ($row_gps as $row_gp) {
                                                            echo "<option value=id_equipe_identification='{$row_gp["ref_organisme"]}'>Organisme - {$row_gp["denomination"]}</option>";
                                                        }
                                                    } else {
                                                        $organisme->ref_organisme = $utilisateur->id_organisme;
                                                        $row_gp = $organisme->GetDetail();
                                                        echo "<option value=id_equipe_identification='{$row_gp["ref_organisme"]}'>Organisme - {$row_gp["denomination"]}</option>";
                                                    }

                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="input-group  mt-2">
                                                <input type="text" class="form-control datetimepicker-input" name="Du" id="Du" placeholder="Du" required />
                                                <div class="input-group-append">
                                                    <div class="input-group-text" id="add_on_du"><i class="far fa-calendar-alt"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="input-group  mt-2">
                                                <input type="text" class="form-control datetimepicker-input" name="Au" id="Au" required="required" placeholder="Au" />
                                                <div class="input-group-append">
                                                    <div class="input-group-text" id="add_on_au"><i class="far fa-calendar-alt"></i></div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="input-group mt-1">
                                                <button name="search" id="search-btn" class="btn btn-primary"><i class="fa fa-search"></i>
                                                </button>
                                                <a class="btn btn-outline-light float-right ml-1 view-all" href="#">Voir tout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="results"></div>


                    </div>



                </div>
            </div>

        </div>
    </div>

    <?php
    if ($utilisateur->HasDroits("10_10")) {
        echo '<div class="btn-group-fab" role="group" aria-label="FAB Menu">
	  <div>
		<button type="button" class="btn btn-main btn-primary has-tooltip" data-placement="left" title="Menu" id="btn_new_"> <i class="fa fa-plus"></i> </button>
	  </div>
	</div>';
    }
    ?>

    <div class="modal" id="dlg_main" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
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
                        <input name="UID" id="UID" type="hidden">
                        <input name="view" id="view" type="hidden">
                        <div class="row">


                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-body">


                                        <div class="row">

                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">

                                                <div class="form-group">
                                                    <label>N° COMPTEUR </label>
                                                    <div class="input-group" style="width: 100%;">
                                                        <input type="text" class="form-control pull-right" name="num_compteur_actuel" id="num_compteur_actuel" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">

                                                <div class="form-group">
                                                    <label>DATE IDENTIFICATION</label>
                                                    <div class="input-group" style="width: 100%;">
                                                        <input type="text" class="form-control pull-right" name="date_identification" id="date_identification" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- ============================================================== -->
                            <!-- Localisation form -->
                            <!-- ============================================================== -->
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="card">
                                    <h5 class="card-header">Localisation</h5>
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                            <div class="card">

                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>VILLE</label>
                                                        <select class='form-control select2' style='width: 100%;' name='ville_id' id='ville_id' required>
                                                            <option selected='selected' value=''> </option>
                                                            <?php
                                                            $stmt_select = $commune->readFilter('4', $USER_SITE_PROVINCE);
                                                            while ($row_select = $stmt_select->fetch(PDO::FETCH_ASSOC)) {
                                                                echo "<option value=" . $row_select["code"] . ">{$row_select["libelle"]}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>COMMUNE</label>
                                                        <select class='form-control select2' style='width: 100%;' name='commune_id' id='commune_id' required>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>QUARTIER</label>
                                                        <select class='form-control select2' style='width: 100%;' name='quartier' id='quartier' required>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>CVS</label>
                                                        <select class='form-control select2' style='width: 100%;' name='cvs_id' id='cvs_id' required>
                                                            <option selected='selected' disabled>Veuillez préciser le CVS</option>
                                                            <?php
                                                            /* 	$stmt_select_st = $cvs->read();	 			 
                                          while ($row_gp = $stmt_select_st->fetch(PDO::FETCH_ASSOC)){
                                          echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
                                          } */
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>AVENUE</label>
                                                        <div class="input-group" style="width: 100%;">
                                                            <!--   <input type="text" class="form-control pull-right" name="adresse" id="adresse" required>  -->

                                                            <select class='form-control select2' style='width: 100%;' name='adresse' id='adresse' required>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                            <div class="card">
                                                <div class="card-body">

                                                    <div class="form-group">
                                                        <label>NUMERO</label>
                                                        <div class="input-group" style="width: 100%;">
                                                            <input type="text" class="form-control pull-right" name="numero_avenue" id="numero_avenue" required>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>TYPE RACCORDEMENT</label>
                                                        <select class='form-control select2' style='width: 100%;' name='type_raccordement_identif' id='type_raccordement_identif' required>
                                                            <option selected='selected' disabled>Veuillez préciser</option>
                                                            <?php
                                                            $stmt_tarif = $raccordement->read();
                                                            while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
                                                                echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>N° P.A</label>
                                                        <div class="input-group" style="width: 100%;">
                                                            <input type="text" class="form-control pull-right" name="p_a" id="p_a">
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <label>CONSOMMATEUR GERE</label>
                                                        <select class='form-control select2' style='width: 100%;' name='consommateur_gerer' id='consommateur_gerer' required>
                                                            <option selected='selected' disabled>Veuillez préciser</option>
                                                            <?php
                                                            $stmt_select_st = $yes_no->read();
                                                            while ($row_gp = $stmt_select_st->fetch(PDO::FETCH_ASSOC)) {
                                                                echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>ACCESSIBILITE CLIENT</label>
                                                        <select class='form-control select2' style='width: 100%;' name='accessibility_client' id='accessibility_client' required>
                                                            <option selected='selected' disabled>Veuillez préciser</option>
                                                            <?php
                                                            $stmt_tarif = $accessib->read();
                                                            while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
                                                                echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <button type="button" class="btn btn-warning  float-right" id="btn_Signaler_Exoneration" style="display:none;">&nbsp;Signaler Exonération</button>
                                                    <button type="button" class="btn btn-danger  float-right" id="btn_Signaler_Refus" style="display:none;">&nbsp;Signaler refus</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <!--	<a class="btn btn-outline-light float-right" id="btn_gps"><i class="fas fa-map-marker-alt"></i>Get GPS</a> -->
                                                        <a class="btn btn-outline-light float-right" id="btn_gps"><i class="fas fa-map-marker-alt"></i> Récupérer Coordonnées</a>
                                                        <?php if (Utils::IsWebView($_SERVER)) { ?>
                                                            <a class="btn btn-outline-light float-right" id="btn_gps_native"><i class="fas fa-map-marker-alt"></i> GPS Native </a>
                                                        <?php } ?>

                                                        <label>LATITUDE</label>
                                                        <div class="input-group" style="width: 50%;">
                                                            <input type="text" class="form-control pull-right" name="gps_latitude" id="gps_latitude" required>
                                                        </div>

                                                        <label>LONGITUDE</label>
                                                        <div class="input-group" style="width: 50%;">
                                                            <input type="text" class="form-control pull-right" name="gps_longitude" id="gps_longitude" required>
                                                        </div>
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

                            <!-- ============================================================== -->
                            <!-- infos client form -->
                            <!-- ============================================================== -->
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="card">
                                    <h5 class="card-header">Information Client</h5>
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                            <div class="card">

                                                <div class="card-body">


                                                    <div class="form-group">
                                                        <label>TYPE DE CLIENT</label>
                                                        <select class='form-control select2' style='width: 100%;' name='type_client' id='type_client' required>
                                                            <option selected='selected' disabled>Veuillez préciser</option>
                                                            <?php
                                                            $stmt_select_st = $type_client->read();
                                                            while ($row_gp = $stmt_select_st->fetch(PDO::FETCH_ASSOC)) {
                                                                echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>TARIF</label>
                                                        <select class='form-control select2' style='width: 100%;' name='tarif_identif' id='tarif_identif' required>
                                                            <option selected='selected' disabled>Veuillez préciser le tarif</option>
                                                            <?php
                                                            $stmt_tarif = $tarif->read();
                                                            while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
                                                                echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>NOMS CLIENT (PROPRIETAIRE)</label>
                                                        <div class="input-group" style="width: 100%;">
                                                            <div class="input-group" style="width: 100%;">
                                                                <input id-abonne="" type="text" class="form-control pull-right" name="nom_abonne" id="nom_abonne" required disabled>
                                                                <div class="input-group-text" id="select_identite"><a id="verify_identite" href="#" class="icon"><i class="fas fa-search"></i></a>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>STATUT CLIENT</label>
                                                        <select class='form-control select2' style='width: 100%;' name='statut_client' id='statut_client' required disabled>
                                                            <option selected='selected' value=""> </option>
                                                            <?php
                                                            $stmt_select_st = $statut_personne->read();
                                                            while ($row_gp = $stmt_select_st->fetch(PDO::FETCH_ASSOC)) {
                                                                echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>PHONE CLIENT</label>
                                                        <div class="input-group" style="width: 100%;">
                                                            <input type="text" class="form-control pull-right" name="phone_abonne" id="phone_abonne" disabled>
                                                        </div>
                                                    </div>


                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>NOM OCCUPANT TROUVE *</label>
                                                        <div class="input-group" style="width: 100%;">
                                                            <div class="input-group" style="width: 100%;">
                                                                <input type="text" class="form-control pull-right" name="nom_occupant_trouver" id="nom_occupant_trouver" required disabled>
                                                                <div class="input-group-text" id="select_identite_occupant"><a id="verify_identite_occupant" href="#" class="icon"><i class="fas fa-search"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>N° PIECE D'IDENTITE</label>
                                                        <div class="input-group" style="width: 100%;">
                                                            <input type="text" class="form-control pull-right" name="numero_piece_identity" id="numero_piece_identity" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>PHONE OCCUPANT</label>
                                                        <div class="input-group" style="width: 100%;">
                                                            <input type="text" class="form-control pull-right" name="phone_occupant_trouver" id="phone_occupant_trouver" disabled>
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <label>STATUT OCCUPANT</label>
                                                        <select class='form-control select2' style='width: 100%;' name='statut_occupant' id='statut_occupant' disabled>
                                                            <option selected='selected' value=""> </option>
                                                            <?php
                                                            $stmt_select_st = $statut_personne->read();
                                                            while ($row_gp = $stmt_select_st->fetch(PDO::FETCH_ASSOC)) {
                                                                echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>REFERENCE APPARTEMENT</label>
                                                        <div class="input-group" style="width: 100%;">
                                                            <input type="text" class="form-control pull-right" name="reference_appartement" id="reference_appartement">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                            <div class="card">
								<div class="card-body">
								</div>
                            </div>
                        </div> -->
                                    </div>
                                </div>
                            </div>
                            <!-- ============================================================== -->
                            <!-- end infos client form -->
                            <!-- ============================================================== -->

                            <!-- ============================================================== -->
                            <!-- Information raccordement form -->
                            <!-- ============================================================== -->
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="card">
                                    <h5 class="card-header">Information raccordement</h5>
                                    <div class="row">

                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label>CABINE</label>
                                                        <div class="input-group" style="width: 100%;">
                                                            <input type="text" class="form-control pull-right" name="cabine_id" id="cabine_id">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>NUMERO DEPART</label>
                                                        <div class="input-group" style="width: 100%;">
                                                            <input type="text" class="form-control pull-right" name="numero_depart" id="numero_depart">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>NUMERO POTEAU</label>
                                                        <div class="input-group" style="width: 100%;">
                                                            <input type="text" class="form-control pull-right" name="numero_poteau_identif" id="numero_poteau_identif">
                                                        </div>
                                                    </div>




                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                            <div class="card">
                                                <div class="card-body">

                                                    <div class="form-group">
                                                        <label>SECTION CABLE</label>
                                                        <div class="input-group">

                                                            <select class='form-control select2' style='width: 100%;' name='section_cable' id='section_cable' required>
                                                                <option selected='selected' disabled>Veuillez préciser</option>
                                                                <?php
                                                                $stmt_tarif = $section_cable->read();
                                                                while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
                                                                    echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>NBRE BRANCH.</label>
                                                        <div class="input-group">
                                                            <input type="number" class="form-control pull-right allow-numeric" name="nbre_branchement" id="nbre_branchement" step=".5" required>
                                                        </div>
                                                    </div>



                                                    <div class="form-group">
                                                        <label>TYPE RACCORDEMENT PROPOSE</label>
                                                        <select class='form-control select2' style='width: 100%;' name='type_compteur' id='type_compteur' required>
                                                            <option selected='selected' disabled>Veuillez préciser</option>
                                                            <?php
                                                            $stmt_tarif = $type_compteur->read();
                                                            while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
                                                                echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>PRESENCE INVERSEUR</label>
                                                        <select class='form-control select2' style='width: 100%;' name='presence_inversor' id='presence_inversor'>
                                                            <option selected='selected' disabled>Veuillez préciser</option>
                                                            <?php
                                                            $stmt_select_st = $yes_no->read();
                                                            while ($row_gp = $stmt_select_st->fetch(PDO::FETCH_ASSOC)) {
                                                                echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">

                                            <div class="card">
                                                <div class="card-header d-flex">
                                                    <h4 class="mb-0">GALERIE PHOTO PA</h4>
                                                    <div class="dropdown ml-auto">


                                                        <?php if (Utils::IsWebView($_SERVER)) { ?>
                                                            <div class="image-upload">
                                                                <label for="file-input">
                                                                    <img id="previewImg" src="image/camera.jpg" style="width: 25px; height: 25px;" />
                                                                </label>

                                                                <input id="file-input" type="file" onchange="previewFile(this);" style="display: none;" accept="image/*;capture=camera" capture="camera" />
                                                            </div>
                                                        <?php } else {
                                                        ?>
                                                            <a class="btn btn-xs delete-install-record" id="btn_capture"><i class="fas fa-plus"></i></a>

                                                            <div class="image-upload">
                                                                <label for="file-input">
                                                                    <img id="previewImg" src="image/camera.jpg" style="width: 25px; height: 25px;" />
                                                                </label>
                                                                <!-- Using accept="image/png, image/jpeg" fixes the issue  -->
                                                                <input id="file-input" type="file" onchange="previewFile(this);" style="display: none;" accept="image/*;capture=camera" capture="camera" />
                                                            </div>
                                                    </div>
                                                <?php
                                                        }
                                                ?>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row" id="photo_pa_list">
                                                </div>

                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <div class="table-responsive table-bordered table-hover" style="height:250px;">
                                                        <table class="table no-wrap p-table lignes ui-sortable" id="lignes">
                                                            <thead>
                                                                <tr>
                                                                    <th style="width:5%">N°</th>
                                                                    <th style="width:90%">Matériel</th>
                                                                    <th>Qté</th>
                                                                    <th><a class="btn btn-xs delete-record" id="add_line"><i class="fas fa-plus"></i></a></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- ============================================================== -->
                        <!-- end Information raccordement -->
                        <!-- ============================================================== -->

                        <!-- ============================================================== -->
                        <!-- Information IMMEUBLE form -->
                        <!-- ============================================================== -->
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">Informations sur l'immeuble</h5>
                                <div class="row">

                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label>NOMBRE D'APPARTEMMENT *</label>
                                                    <div class="input-group" style="width: 100%;">
                                                        <input type="text" class="form-control pull-right allow-numeric" name="nbre_appartement" id="nbre_appartement" required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                        <div class="card">
                                            <div class="card-body">

                                                <div class="form-group">
                                                    <label>CONFORMITE D'INSTALLATION</label>
                                                    <select class='form-control select2' style='width: 100%;' name='conformites_installation' id='conformites_installation'>
                                                        <option selected='selected' disabled>Veuillez préciser</option>
                                                        <?php
                                                        $stmt_tarif = $conformity_install->read();
                                                        while ($row_gp = $stmt_tarif->fetch(PDO::FETCH_ASSOC)) {
                                                            echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ============================================================== -->
                        <!-- end Information IMMEUBLE form -->
                        <!-- ============================================================== -->

                        <!-- ============================================================== -->
                        <!-- Information SUPPLEMENTAIRE form -->
                        <!-- ============================================================== -->
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <h5 class="card-header">Informations supplémentaires</h5>
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <!--<div class="form-group">
                                    <label>INFORMATION SUPPLEMENTAIRE</label>
                                    <div class="input-group"  style="width: 100%;" > 
                                        <textarea class="form-control pull-right" name="infos_supplementaires" id="infos_supplementaires"  ></textarea>
                                    </div>                
                                </div> -->
                                                <div class="form-group">
                                                    <label>AVIS TECHNIQUE BLUE ENERGY</label>
                                                    <div class="input-group" style="width: 100%;">
                                                        <textarea class="form-control pull-right" name="avis_technique_blue" id="avis_technique_blue" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>AVIS OCCUPANT</label>
                                                    <div class="input-group" style="width: 100%;">
                                                        <textarea class="form-control pull-right" name="avis_occupant" id="avis_occupant"></textarea>
                                                    </div>
                                                </div>


                                                <div class="row">

                                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                                        <div class="card">
                                                            <div class="card-body">

                                                                <div class="form-group">
                                                                    <label>SOCIETE EN CHARGE DE L'IDENTIFICATION</label>
                                                                    <select class='form-control select2' style='width: 100%;' name='id_equipe_identification' id='id_equipe_identification' required>
                                                                        <option selected='selected' disabled>Veuillez préciser</option>
                                                                        <?php
                                                                        $stmt_ = null;
                                                                        if ($utilisateur->id_service_group ==  '3') {  //Administration
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
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label>CHEF D'EQUIPE *</label>
                                                            <div class="input-group" style="width: 100%;">
                                                                <input type="text" class="form-control pull-right" name="chef_equipe_view" id="chef_equipe_view" readOnly style="display:none" /> <select class='form-control select2' style='width: 100%;' name='chef_equipe' id='chef_equipe' required>
                                                                    <option selected='selected' disabled> </option>
                                                                    <?php
                                                                    $stmt_chief = null;
                                                                    if ($utilisateur->id_service_group ==  '3') {  //Administration
                                                                        $stmt_chief = $utilisateur->GetAllChiefForAdmin();
                                                                    } else {
                                                                        $stmt_chief = $utilisateur->GetCurrentUserChief($utilisateur); //->code_utilisateur,$utilisateur->id_organisme,$utilisateur->chef_equipe_id);
                                                                    }

                                                                    while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
                                                                        echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
                                                                    }


                                                                    ?>
                                                                </select>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                                        <div class="form-group">
                                                            <label>IDENTIFICATEUR *</label>
                                                            <div class="input-group" style="width: 100%;">
                                                                <input type="text" class="form-control pull-right" name="identificateur_view" id="identificateur_view" readOnly style="display:none">
                                                                <select class='form-control select2' style='width: 100%;' name='identificateur' id='identificateur' required>
                                                                    <option selected='selected' disabled>Veuillez préciser</option>
                                                                    <?php




                                                                    $stmt_chief = null;
                                                                    if ($utilisateur->id_service_group ==  '3') {  //Administration
                                                                        $stmt_chief = $utilisateur->GetAll_OrganeUserListForAdmin();
                                                                        while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
                                                                            echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
                                                                        }
                                                                    } else {

                                                                        $stmt_chief = $utilisateur->GetCurrentUserListIdentificateurs($utilisateur->code_utilisateur, $utilisateur->id_organisme, $utilisateur->is_chief);
                                                                        if ($utilisateur->is_chief == '1') {
                                                                            echo "<option value='" . $utilisateur->code_utilisateur . "'>" . $utilisateur->nom_complet . "</option>";
                                                                        }
                                                                    }
                                                                    while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
                                                                        echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
                                                                    }


                                                                    /*
                                        $stmt_chief = $utilisateur->GetCurrentUserListIdentificateurs($utilisateur->code_utilisateur,$utilisateur->id_organisme,$utilisateur->is_chief);
                                        while ($row_chief = $stmt_chief->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='{$row_chief["code_utilisateur"]}'>{$row_chief["nom_complet"]}</option>";
                                        }*/
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ============================================================== -->
                        <!-- end Information SUPPLEMENTAIRE form -->
                        <!-- ============================================================== -->

                    </div>


                    <div class="modal-footer ">
                        <div class="row">
                            <div class="col-md-6">
                                <label>Mode sauvegarde</label>
                                <div class="input-group" style="width: 100%;">
                                    <select class="form-control select2" name="doc_save_mode" id="doc_save_mode" required>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <button type="button" class="form-control btn btn-primary btn-lg" id="btn_save_"><span class="glyphicon glyphicon-ok-sign"></span> Appliquer</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    </form>
    </div>
    <div class="modal" id="ligne_form" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 5000;" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="item_titre" class="modal-title"></h4>
                    <!--  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span> </button>   -->
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body mx-3">
                    <div class="form-group">
                        <input type="hidden" id="item-id" name="item-id">
                        <input type="hidden" id="item-type" name="item-type">
                        <label>Matériel</label>
                        <select class='form-control select2' style='width: 100%;' id='item_label'>
                            <option selected='selected' disabled>Veuillez choisir le matériel</option>
                            <?php
                            $stmt_select_mat = $materiel->read();
                            while ($row_gp = $stmt_select_mat->fetch(PDO::FETCH_ASSOC)) {
                                echo "<option value='{$row_gp["ref_produit"]}'>{$row_gp["designation"]}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Quantité</label>
                        <input id="item-qte" type="number" class="form-control border-input allow-numeric" placeholder="" style='width: 50%;' step=".5" value="0"><span class="error" style="color: red; display: none">* Valeur numérique (0 - 9)</span>
                    </div>
                    <button id="btn_add_line" type="button" class="btn btn-primary">Valider</button>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="camera_shooter" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content" style="width: 355px;">
                <div class="modal-header">
                    <h4 id="item_titre" class="modal-title">CAPTURE PHOTO</h4>

                </div>
                <div class="modal-body text-center">


                    <div id="my_camera" style="width: 320px; height: 240px;">
                        <div></div><video id="webcam" autoplay style="width: 320px; height: 240px;" width="320" height="240"></video>
                        <canvas id="canvas" class="d-none"></canvas>
                        <div class="flash"></div>
                        <audio id="snapSound" src="audio/snap.wav" preload="auto"></audio>
                    </div>
                    <input type="button" class="btn btn-primary" value="Capturer" onclick="take_snapshot()">
                    <input type="button" class="btn btn-primary" value="Changer caméra" id="cameraFlip">
                    <input type="button" class="btn btn-primary" value="Fermer" id="cameraClose" onclick="CloseCamera()">

                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="box_fiche_viewer" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="fiche_viewer_title" class="modal-title">VISUALISATION FICHE</h4>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div id="fiche_viewer" class="modal-body">

                </div>
            </div>
        </div>
    </div>



    <div class="modal" id="box_signaler_Refus" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="notification_title" class="modal-title">Notification Refus</h4>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body text-center">
                    <form id="frm_signaler_Refus" method="post" action="controller.php" enctype="multipart/form-data">
                        <input id="view" name="view" type="hidden">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="text-dark text-left">
                                    Adresse
                                </div>
                                <div class="font-medium text-primary client-adress text-left" id="refus_adresse" refus-ville="" refus-commune="" refus-quartier="" refus-cvs="" refus-avenue='' refus-numero='' refus-pa='' refus-accessibility=''></div>
                            </div>
                            <div class="col-sm-6 text-right">
                                <div class="text-dark">
                                    CVS
                                </div>
                                <div id="refus_cvs" class="font-medium text-primary client-cvs"></div>
                            </div>
                        </div>
                        <div class="row" id="bloc_dat_rendez_vous" style="display:none;">
                            <div class="col-sm-6">
                                <div class="form-group  text-left" id='bloc_rendez_vous'>
                                    <label>Date Rendez-vous</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control pull-right" name="dat_rendez_vous" id="dat_rendez_vous" style="width: 300px;">
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-4 text-left">
                            <label class="text-dark text-left">COMMMNETAIRE</label>
                            <div class="input-group" style="width: 100%;">
                                <textarea class="form-control pull-right" name="refus_commentaire" id="refus_commentaire"></textarea>
                            </div>
                        </div>
                        <div class="text-center">
                            <button id="btn_submit_refus" type="button" class="btn btn-success btn-fill float-right">Envoyer</button>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="dlg_frm_lst_identite" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="menage_title">Liste des ménages pour l'adresse actuelle</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">

                    <div class="table-responsive table-bordered table-hover" style="height:250px;">
                        <table class="table no-wrap p-table lignes ui-sortable">
                            <thead>
                                <tr>
                                    <th style="width:90%"><input type="text" id="srch-term-lst-identite" class="form-control" placeholder="Recherche..."></th>
                                    <th>
                                        <?php if ($utilisateur->HasDroits("10_740")) {
                                            echo '<a class="btn btn-xs add-record" id="add_line_menage"><i class="fas fa-plus"></i></a>';
                                        }
                                        ?></th>
                                </tr>
                            </thead>
                            <tbody id="lignes_menages">
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="dlg_frm_identite" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="identite_title">Information de l'identité</h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">

                    <!-- ============================================================== -->
                    <!-- Information IDENTITE -->
                    <!-- ============================================================== -->

                    <form id="frm_identite">
                        <input name="identite_id" id="identite_id" type="hidden">
                        <input name="identite_adress_id" id="identite_adress_id" type="hidden">
                        <input name="view" id="view_identite" type="hidden">
                        <div class="row">

                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>NOM</label>
                                            <div class="input-group" style="width: 100%;">
                                                <input type="text" class="form-control pull-right" name="identite_nom" id="identite_nom">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>POSTNOM</label>
                                            <div class="input-group" style="width: 100%;">
                                                <input type="text" class="form-control pull-right" name="identite_postnom" id="identite_postnom">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>PRENOM</label>
                                            <div class="input-group" style="width: 100%;">
                                                <input type="text" class="form-control pull-right" name="identite_prenom" id="identite_prenom">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>SEXE</label>
                                            <div class="input-group">

                                                <select class='form-control select2' style='width: 100%;' name='identite_sexe' id='identite_sexe' required>
                                                    <option selected='selected' disabled>Veuillez préciser</option>
                                                    <option value='M'>Homme</option>
                                                    <option value='F'>Femme</option>
                                                    <option value='PM'>Personne Morale</option>
                                                </select>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                                <div class="card">
                                    <div class="card-body">


                                        <div class="form-group">
                                            <label>LIEU DE NAISSANCE</label>
                                            <div class="input-group" style="width: 100%;">
                                                <input type="text" class="form-control pull-right" name="identite_lieu" id="identite_lieu">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>N° TELEPHONE</label>
                                            <div class="input-group" style="width: 100%;">
                                                <input type="text" class="form-control pull-right" name="identite_phone" id="identite_phone">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>STATUT<span class="ml-1 text-danger">*</span></label>
                                            <div class="input-group">
                                                <select class='form-control select2' style='width: 100%;' name='identite_statut' id='identite_statut' required>
                                                    <option selected='selected' disabled>Veuillez préciser</option>
                                                    <?php
                                                    $stmt_select_st = $statut_personne->read();
                                                    while ($row_gp = $stmt_select_st->fetch(PDO::FETCH_ASSOC)) {
                                                        echo "<option value='{$row_gp["code"]}'>{$row_gp["libelle"]}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                    <!-- ============================================================== -->
                    <!-- end Information IDENTITE -->
                    <!-- ============================================================== -->

                    <div class="text-right">
                        <input type="button" class="btn btn-primary" value="OK" id="btn_valider_identite">
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="box_motif" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="invalidation_title" class="modal-title"></h4>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body text-center">
                    <form id="frm_invalidation" method="post" action="controller.php" enctype="multipart/form-data">
                        <input id="view_invalidation" name="view" type="hidden">
                        <input id="invalidation_id" name="id_" type="hidden">

                        <div class="form-group mt-4 text-left">
                            <label class="text-dark text-left">Veuillez préciser</label>
                            <div class="input-group" style="width: 100%;">
                                <input type="text" class="form-control pull-right" name="invalidation_motif" id="invalidation_motif" required>
                            </div>
                        </div>
                        <div class="text-center">
                            <button id="btn_submit_invalidation" type="button" class="btn btn-success btn-fill float-right">OK</button>
                        </div>
                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
    include_once "layout_script.php";
    include_once 'layout_map_viewer.php';  ?>
    <div id="myBackdrop" class="modal-backdrop" style="display:none;opacity:.5"></div>
    <div id="map" style="display:none;"></div>

    <?php //if($MobileRun != "1"){ 
    ?>
    <script type="text/javascript" src="assets/js/WebcamEasyNew.js"></script>
    <?php //} 
    ?>
    <script src="assets/js/select2.min.js"></script>
    <script src="assets/js/mapviewer-script.js"></script>
    <script>
        $(document).ready(function() {
            // $(function () {
            modalbox_scroll();
            var identite_trigger = "";
            var actual_avenue = "";
            var actual_commune = "";
            var actual_quartier = "";
            var load_avenue = false;
            var load_commune = false;
            var load_quartier = false;
            var actual_chief = "";
            //var select_c="";        
            var actual_cvs = "";
            var load_cvs = false;
            var load_chief = false;
            $('#btn_valider_identite').click(function() {
                var frm = $("#frm_identite");
                if (frm.parsley().validate()) {
                    // alert("oui");				   
                } else {
                    // alert("non");
                    return false;
                }
                var form = document.getElementById("frm_identite");
                var formDataIdentite = new FormData(form);

                ShowLoader("Enregistrement identité en cours...");
                $.ajax({
                    //enctype: 'multipart/form-data',
                    url: "controller.php",
                    data: formDataIdentite, // Add as Data the Previously create formData
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
                            var view_mode = $('#view_identite').val();
                            if (view_mode == 'create_menage') {
                                if (result.identite_id.length > 0) {
                                    var data_statut = $('#identite_statut').val();
                                    var identite_phone = $('#identite_phone').val();
                                    var identite_piece = $('#identite_piece').val();
                                    var nom = $('#identite_nom').val() + ' ' + $('#identite_postnom').val() + ' ' + $('#identite_prenom').val();

                                    if (identite_trigger == 'occupant') {
                                        $('#statut_occupant').val(data_statut).change();
                                        $('#phone_occupant_trouver').val(identite_phone);
                                        $('#numero_piece_identity').val(identite_piece);
                                        $('#nom_occupant_trouver').val(nom);
                                        $('#nom_occupant_trouver').attr('data_id', result.identite_id);
                                        // $('#nom_occupant_trouver').attr('data_phone',identite_phone);
                                        // $('#nom_occupant_trouver').attr('data_statut',data_statut);
                                        // $('#nom_occupant_trouver').attr('data_name',data_name);
                                        // $('#nom_occupant_trouver').attr('data_piece',data_piece);
                                    } else if (identite_trigger == 'client') {
                                        $('#statut_client').val(data_statut).change();
                                        $('#phone_abonne').val(identite_phone);
                                        $('#nom_abonne').val(nom);
                                        $('#nom_abonne').attr('data_id', result.identite_id);
                                    }
                                    ClearFormIdentite();

                                    $('#dlg_frm_identite').hide();
                                    $('#dlg_main').show();
                                }

                            } else if (view_mode == 'edit_menage') {
                                if (result.is_done == true) {
                                    ClearFormIdentite();
                                    $('#dlg_frm_identite').hide();
                                    $('#dlg_main').show();
                                } else if (result.is_done == false) {
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
                        console.log("Request finished.");
                        HideLoader();
                    }
                });



            });




            $("#p_a").on("keypress keyup blur", function(event) {
                var text = $(this).val();
                if (text.length > 0) {
                    $("#consommateur_gerer").val('Oui').change();

                } else {
                    $("#consommateur_gerer").val('Non').change();
                }

            });


            $(".allow-numeric").on("keypress keyup blur", function(event) {
                // $(this).val($(this).val().replace(/[^\d].+/, ""));
                /* if (event.which!= 46 ||(event.which < 48 || event.which > 57)) {
                     $(".error").css("display", "inline");
                     event.preventDefault();
                 }else{
                 	$(".error").css("display", "none");
                 }*/
                var charCode = (event.which) ? event.which : event.keyCode
                var val = $(this).val();
                if (charCode == 8 || charCode == 59 || (charCode >= 48 && charCode <= 57)) {
                    $(".error").css("display", "none");
                } else {
                    //alert(charCode);
                    // $(".error").css("display", "inline");
                    event.preventDefault();
                }
                if (isNaN(val)) {
                    val = val.replace(/[^0-9\.]/g, '');
                    if (val.split('.').length > 2)
                        val = val.replace(/\.+$/, "");
                }
                $(this).val(val);
                /* return !!(
            (charCode >= 48 && charCode <= 57)
            || (charCode >= 37 && charCode <= 40)
            || (charCode >= 96 && charCode <= 105)
            || charCode == 17
            || charCode == 13
            || charCode == 46
            || charCode == 8
            || charCode == 9
            || charCode == 188)*/
            });




            jQuery(document).delegate('a.close', 'click', function(e) {
                e.preventDefault();
                var pId = $(this).parents('div.modal').attr("id");
                $(this).parents('div.modal').hide();
                if (pId == 'dlg_main' || pId == 'box_fiche_viewer') {
                    CloseMain();
                }
                if (pId == "dlg_frm_lst_identite" || pId == "dlg_frm_identite" || pId == "box_motif") {
                    $('#dlg_main').show();
                }
            });

            $('#doc_save_mode').select2();
            $('#show').select2();
            //$('#filtre').select2({ placeholder: "Filtre", allowClear: true,matcher:function(term,text,opt){return text.toUpperCase().indexOf(term.toUpperCase())>=0||opt.attr("value").toUpperCase().indexOf(term.toUpperCase())>=0;}, tags: true });	
            $('#filtre').select2({
                placeholder: "Filtre",
                multiple: true
            });
            /*.on('select2:select',function(e){
            	//$(this).val([]).trigger('change');
            	//$(this).val([e.params.data.id]).trigger('change');
            });	*/
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



            $('#dat_rendez_vous').datetimepicker({
                format: 'dd/mm/yyyy',
                language: 'fr',
                weekStart: 1,
                todayBtn: 1,
                autoclose: 1,
                minView: 2
            });





            function modalbox_scroll() {
                /* $('.modal').on("hidden.bs.modal", function (e) { //fire on closing modal box scroll issue hack
			if ($('.modal:visible').length) { 
				$('body').addClass('modal-open');  
			}
				});*/

                $('.modal').on("shown.bs.modal", function(e) { //fire on closing modal box scroll issue hack
                    if ($('.modal:visible').length) {
                        $('body').addClass('modal-open');
                    }
                });
            }

            function AppendOption(id_select, option_val, option_text) {
                $(id_select).append('<option value="' + option_val + '">' + option_text + '</option>');
            }

            var ctr = 0;
            var exist = false;

            /*$(".deggre").change(function(){
            if($(this).val() == "")
            $('#dropdownMenu1').css({"display": "none"});
            else
            $('#dropdownMenu1').css({"display": "block"});
            });*/

            // $("#advanced_search").on("hide.bs.collapse", function(){ 
            // $('#srch-term').show();
            // $('#search-btn').show();
            // });
            // $("#advanced_search").on("show.bs.collapse", function(){
            // $('#srch-term').hide();
            // $('#search-btn').hide();
            // });
            $('#dlg_main .select2').each(function() {
                var $sel = $(this).parent();
                $(this).select2({
                    dropdownParent: $sel
                });
            });
            $('#frm_identite .select2').each(function() {
                var $sel = $(this).parent();
                $(this).select2({
                    dropdownParent: $sel
                });
            });
            $('#dlg_main-install .select2').each(function() {
                var $sel = $(this).parent();
                $(this).select2({
                    dropdownParent: $sel
                });
            });
            $('#ligne_form_install .select2').each(function() {
                var $sel = $(this).parent();
                $(this).select2({
                    dropdownParent: $sel
                });
            });
            $('#item_label').val("").change();
            $('.modal').on("hidden.bs.modal", function(e) { //fire on closing modal box scroll issue hack
                if ($('.modal:visible').length) {
                    $('body').addClass('modal-open');
                }
            });
            $("#commune_id").on("change", function(e) {
                var item = $(this).val();
                e.preventDefault();

                if (load_cvs == false) {
                    return false;
                }
                ShowLoader("Chargement liste des CVS en cours...");
                $("#cvs_id").html('');
                $.ajax({
                    url: "controller.php",
                    method: "GET",
                    data: {
                        view: "get_commune_cvs",
                        id_: item
                    },
                    success: function(data, statut) {

                        try {
                            var result = $.parseJSON(data);
                            if (result.error == 0) {
                                $("#cvs_id").html(result.data);
                                if (actual_cvs != "") {
                                    $("#cvs_id").val(actual_cvs).change();
                                    //actual_cvs = "";									  
                                }
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
                    complete: function(r) {
                        HideLoader();
                    }
                });
            });
            jQuery(document).delegate('a.delete-item', 'click', function(e) {
                e.preventDefault();
                var itemId = $(this).parents('tr.item-row').attr('item-id');
                var label = $('tr.item-row[item-id="' + itemId + '"]').find('span.sn').html();
                swal({
                    title: "Information",
                    text: 'Voulez-vous rétirer le matériel (' + label + ') de la liste?',
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#00A65A",
                    confirmButtonText: "Oui",
                    cancelButtonText: "Non",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        $('tr.item-row[item-id="' + itemId + '"]').remove();
                        reOrderRow();
                    }
                });
            });

            function generateItemID(parent) {
                ctr++;
                $(parent).find('tr').each(function(i) {
                    var itemId = $(this).attr('data-id');
                    if (ctr == itemId) {
                        generateItemID(parent);
                    }

                });
                return ctr;
            }
            $('#ligne_form .select2').each(function() {
                var $sel = $(this).parent();
                $(this).select2({
                    dropdownParent: $sel
                });
            });
            /* function displayError(err = ''){
             if (err != ''){
             console.log(err);
                     // $("#errorMsg").html(err);
             }
             // $("#errorMsg").removeClass("d-none");
             }*/

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

            $("#btn_gps").click(function(e) {
                e.preventDefault();
                ShowLoader("Localisation en cours...");
                locateNew();
            });

            $("#btn_gps_native").click(function(e) {
                e.preventDefault();
                ShowLoader("Localisation en cours...");
                try {
                    contactSupport.getGPS('onLocationFoundAndroid', 'onLocationFailAndroid');
                } catch (e) {
                    HideLoader();
                }
            });





            $('#btn_submit_refus').click(function() {


                if ($('#accessibility_client').val() == 4) {
                    var item = $('#dat_rendez_vous').val() != null ? $('#dat_rendez_vous').val() : '';
                    if (item == '') {
                        swal("Information", "Veuillez préciser la date du rendez-vous", "error");
                        return false;
                    }

                }
                $("#frm_signaler_Refus #view").val('create_refus');
                var form = document.getElementById("frm_signaler_Refus");
                var formRefus = new FormData(form);

                var refus_ville = $('#refus_adresse').attr('refus-ville');
                var refus_commune = $('#refus_adresse').attr('refus-commune');
                var refus_cvs = $('#refus_adresse').attr('refus-cvs');
                var refus_quartier = $('#refus_adresse').attr('refus-quartier');
                var refus_avenue = $('#refus_adresse').attr('refus-avenue');
                var refus_numero = $('#refus_adresse').attr('refus-numero');
                var refus_accessibility = $('#refus_adresse').attr('refus-accessibility');
                var refus_comment = $('#refus_commentaire').val();

                //formRefus.append("view", 'create_refus');
                formRefus.append("refus_ville", refus_ville);
                formRefus.append("refus_commune", refus_commune);
                formRefus.append("refus_cvs", refus_cvs);
                formRefus.append("refus_quartier", refus_quartier);
                formRefus.append("refus_avenue", refus_avenue);
                formRefus.append("refus_numero", refus_numero);
                formRefus.append("refus_accessibility", refus_accessibility);
                formRefus.append("refus_comment", refus_comment);


                $("#btn_submit_refus").attr('disabled', 'disabled');
                $("#btn_submit_refus").removeClass('btn-primary');
                $("#btn_submit_refus").text("Envoi en cours ...");

                $.ajax({
                    //enctype: 'multipart/form-data',
                    url: "controller.php",
                    data: formRefus, // Add as Data the Previously create formData
                    type: "POST",
                    contentType: false,
                    processData: false,
                    cache: false,
                    dataType: "json", // Change this according to your response from the server.
                    error: function(err) {
                        console.error(err);
                        $("#btn_submit_refus").removeAttr('disabled');
                        $("#btn_submit_refus").text("Envoyer");
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
                                $("#btn_submit_refus").text("Envoi terminé.");
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
                                    ClearForm();
                                    ClearMaterielsRow();
                                    CloseMain();
                                    $('#box_signaler_Refus').hide();
                                    LoadALL();
                                });
                            } else if (result.error == 1) {
                                $("#btn_submit_refus").removeAttr('disabled');
                                $("#btn_submit_refus").text("Envoyer");
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

                            $("#btn_submit_refus").removeAttr('disabled');
                            $("#btn_submit_refus").addClass('btn-primary');
                            $("#btn_submit_refus").text("Envoyer");
                        }
                    },
                    complete: function() {
                        console.log("Request finished.");
                    }
                });

            });

            $("#btn_Signaler_Refus").click(function(e) {
                e.preventDefault();
                var com_Label = '';
                var q_Label = '';
                var adr_Label = '';
                var cvs_Label = '';
                var numero_Label = '';
                var access_cli_Label = '';
                var v = $("#ville_id").val();
                var com = $("#commune_id").val();
                var q = $("#quartier").val();
                var adr = $("#adresse").val();
                var cvs = $("#cvs_id").val() != null ? $("#cvs_id").val() : '';
                var numero = $("#numero_avenue").val();
                var refus_pa = $("#p_a").val();
                var access_cli = $("#accessibility_client").val();
                if (v.length > 0 && com.length > 0 && q.length > 0 && adr.length > 0 && cvs.length > 0 && numero.length > 0 && access_cli.length > 0) {
                    $('#refus_adresse').attr('refus-pa', refus_pa);
                    $('#refus_adresse').attr('refus-ville', v);
                    $('#refus_adresse').attr('refus-commune', com);
                    $('#refus_adresse').attr('refus-quartier', q);
                    $('#refus_adresse').attr('refus-avenue', adr);
                    $('#refus_adresse').attr('refus-cvs', cvs);
                    $('#refus_adresse').attr('refus-numero', numero);
                    $('#refus_adresse').attr('refus-accessibility', access_cli);
                    var selected = $('#commune_id').select2('data');
                    if (selected) {
                        com_Label = selected[0].text;
                    }
                    selected = $('#quartier').select2('data');
                    if (selected) {
                        q_Label = selected[0].text;
                    }
                    selected = $('#adresse').select2('data');
                    if (selected) {
                        adr_Label = selected[0].text;
                    }
                    selected = $('#cvs_id').select2('data');
                    if (selected) {
                        cvs_Label = selected[0].text;
                    }

                    $("#refus_adresse").html('N°' + numero + ', AV/' + adr_Label + ' Q/' + q_Label + ' C/' + com_Label);
                    $("#refus_cvs").html(cvs_Label);
                    $("#notification_title").html("Notification Refus");
                    //$("#frm_signaler_Refus #view").val("create_refus");
                    CloseMain();
                    $("#box_signaler_Refus").show();
                } else if (v == "") {
                    $("#ville_id").focus();
                    swal("Information", "Veuillez préciser la ville", "error");

                } else if (com == "") {

                    $("#commune_id").focus();
                    swal("Information", "Veuillez préciser la commune", "error");
                } else if (q == "") {
                    $("#quartier").focus();
                    swal("Information", "Veuillez préciser le quartier", "error");

                } else if (adr == "") {
                    $("#adresse").focus();
                    swal("Information", "Veuillez préciser l'avenue", "error");

                } else if (cvs == "") {
                    $("#cvs_id").focus();
                    swal("Information", "Veuillez préciser le CVS", "error");

                } else if (numero == "") {
                    $("#numero_avenue").focus();
                    swal("Information", "Veuillez préciser le numéro de la parcelle", "error");

                } else if (access_cli == "") {
                    $("#accessibility_client").focus();
                    swal("Information", "Veuillez préciser l'accesbilité client", "error");

                }
            });




            $("#btn_Signaler_Exoneration").click(function(e) {
                e.preventDefault();
                var com_Label = '';
                var q_Label = '';
                var adr_Label = '';
                var cvs_Label = '';
                var numero_Label = '';
                var access_cli_Label = '';
                var v = $("#ville_id").val();
                var com = $("#commune_id").val();
                var q = $("#quartier").val();
                var adr = $("#adresse").val();
                var cvs = $("#cvs_id").val() != null ? $("#cvs_id").val() : '';
                var numero = $("#numero_avenue").val();
                var refus_pa = $("#p_a").val();
                var access_cli = $("#accessibility_client").val();
                if (v.length > 0 && com.length > 0 && q.length > 0 && adr.length > 0 && cvs.length > 0 && numero.length > 0 && access_cli.length > 0) {
                    $('#refus_adresse').attr('refus-pa', refus_pa);
                    $('#refus_adresse').attr('refus-ville', v);
                    $('#refus_adresse').attr('refus-commune', com);
                    $('#refus_adresse').attr('refus-quartier', q);
                    $('#refus_adresse').attr('refus-avenue', adr);
                    $('#refus_adresse').attr('refus-cvs', cvs);
                    $('#refus_adresse').attr('refus-numero', numero);
                    $('#refus_adresse').attr('refus-accessibility', access_cli);
                    var selected = $('#commune_id').select2('data');
                    if (selected) {
                        com_Label = selected[0].text;
                    }
                    selected = $('#quartier').select2('data');
                    if (selected) {
                        q_Label = selected[0].text;
                    }
                    selected = $('#adresse').select2('data');
                    if (selected) {
                        adr_Label = selected[0].text;
                    }
                    selected = $('#cvs_id').select2('data');
                    if (selected) {
                        cvs_Label = selected[0].text;
                    }

                    $("#refus_adresse").html('N°' + numero + ', AV/' + adr_Label + ' Q/' + q_Label + ' C/' + com_Label);
                    $("#refus_cvs").html(cvs_Label);
                    $("#notification_title").html("Notification Exonération");
                    CloseMain();
                    $("#box_signaler_Refus").show();
                } else if (v == "") {
                    $("#ville_id").focus();
                    swal("Information", "Veuillez préciser la ville", "error");
                } else if (com == "") {
                    $("#commune_id").focus();
                    swal("Information", "Veuillez préciser la commune", "error");
                } else if (q == "") {
                    $("#quartier").focus();
                    swal("Information", "Veuillez préciser le quartier", "error");
                } else if (adr == "") {
                    $("#adresse").focus();
                    swal("Information", "Veuillez préciser l'avenue", "error");
                } else if (cvs == "") {
                    $("#cvs_id").focus();
                    swal("Information", "Veuillez préciser le CVS", "error");
                } else if (numero == "") {
                    $("#numero_avenue").focus();
                    swal("Information", "Veuillez préciser le numéro de la parcelle", "error");
                } else if (access_cli == "") {
                    $("#accessibility_client").focus();
                    swal("Information", "Veuillez préciser l'accesbilité client", "error");
                }
            });



            <?php

            //if ($utilisateur->HasDroits("10_30")) {
            ?>
            $("#add_line_menage").click(function() {
                $('#identite_title').html('AJOUT NOUVEAU MENAGE');
                ClearFormIdentite();
                $('#view_identite').val('create_menage');
                $('#dlg_frm_lst_identite').hide();
                $('#dlg_frm_identite').show();
            });
            <?php //}
            ?>

            $("#add_line").click(function() {
                $('#item_titre').html('AJOUT MATERIEL');
                $('#ligne_form').show();
                $('#item-type').val('0');
                $('#item_label').val('').change();
                $('#item-qte').val('');
            });
            $("#btn_add_line").click(function() {
                if ($('#item_label').val() === null) {
                    swal("Information", "Veuillez choisir le matériel!", "error");
                    return false;
                }
                if ($('#item-qte').val() == "") {
                    swal("Information", "Veuillez saisir la quantité!", "error");
                    return false;
                }


                var mat_id = $('#item_label').val();
                var mat_Label = "";
                var selected = $('#item_label').select2('data');
                if (selected) {
                    mat_Label = selected[0].text;
                }


                var lignes = $('#lignes tbody');
                var label = mat_Label;
                var type = $('#item-type').val();
                var id = $('#item-id').val();
                var qte = $('#item-qte').val();
                var numero = '0';
                if (type == '0') {
                    var Id = generateItemID(lignes);
                    exist = false;
                    lignes.find('tr').each(function(i) {
                        var itemId = $(this).attr('materiel-id');
                        if (mat_id == itemId) {
                            exist = true;
                            //$(this).remove();
                        }

                    });
                    if (exist == true) {
                        //alert("found");
                        swal("Information", "Le matériel (" + label + ") existe déjà dans la liste", "error");
                        return false;
                    }

                    /*
                     lignes.each(function(i) {        
                     x = $(this).children();
                     var itArr = [];
                     var item_row_id = $(this).attr('item-id');
                     var item_row_data_id = $(this).attr('data-id');
                     var item_row_label =$('tr.item-row[item-id="' + item_row_id + '"]').find('span.sn').html();
                     
                     //otArr.push('"' + i + '": [' + itArr.join(',') + ']');
                     otArr.push("{\"item_id\":\"" + item_row_id + "\",\"data_id\":\"" + item_row_data_id +"\",\"item_label\":\""+item_row_label+"\",\"is_other\":\"0\"}");	
                     });*/



                    lignes.append('<tr class="item-row" item-id="item-' + Id + '" data-id="' + Id + '" materiel-id="' + mat_id + '"><td style="width:5%"><span class="n">' + numero + '</span></td><td style="width:80%"><span class="sn">' + label + '</span></td><td><span class="qte">' + qte + '</span></td><td><a class="btn btn-xs edit-item"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-xs delete-item"><i class="fas fa-trash"></i></a></td></tr>');
                } else {
                    $('tr.item-row[item-id="' + id + '"]').find('span.sn').html(label);
                    $('tr.item-row[item-id="' + id + '"]').find('span.qte').html(qte);
                    $('tr.item-row[item-id="' + id + '"]').attr('materiel-id', mat_id);
                }

                reOrderRow();
                $('#ligne_form').hide();
                $('#item_label').val("").change();
                $('#item-qte').val("");
                $('#item-type').val("");
                return false;
            });

            function reOrderRow() {
                $('#lignes tr').each(function(index) {
                    $(this).find('span.n').html(index);
                });
            }

            function ClearMaterielsRow() {
                $('#lignes tbody tr').each(function(index) {
                    $(this).remove();
                    // var itemId = $(this).attr('item-id');
                    ctr = 0;
                    // $('tr.item-row[item-id="' + itemId + '"]').remove();
                });
            }

            function ClearInstall_MaterielsRow() {
                $('#lignes_inst tbody tr').each(function(index) {
                    var itemId = $(this).attr('item-id');
                    ctr = 0;
                    $('tr.item-row-inst[item-id="' + itemId + '"]').remove();
                });
            }

            jQuery(document).delegate('a.edit-item', 'click', function(e) {
                e.preventDefault();
                var itemId = $(this).parents('tr.item-row').attr('item-id');
                var mat_id = $('tr.item-row[item-id="' + itemId + '"]').attr('materiel-id');
                var label = $('tr.item-row[item-id="' + itemId + '"]').find('span.sn').html();
                var qte = $('tr.item-row[item-id="' + itemId + '"]').find('span.qte').html();
                $('#item_titre').html('MODIFICATION MATERIEL');
                $('#ligne_form').show();
                $('#item-id').val(itemId);
                $('#item_label').val(mat_id).change();
                $('#item-qte').val(qte);
                $('#item-type').val('1');
            });



            <?php //if ($utilisateur->HasDroits("10_30")) {
            ?> // $('.delete').click(function () {
            jQuery(document).delegate('a.select-item-identite', 'click', function(e) {
                e.preventDefault();
                if (identite_trigger == 'occupant') {
                    var itemId = $(this).parents('tr.item-row-identite').attr('data-id');
                    var data_phone = $('tr.item-row-identite[data-id="' + itemId + '"]').attr('data-phone');
                    var data_statut = $('tr.item-row-identite[data-id="' + itemId + '"]').attr('data-statut');
                    var data_name = $('tr.item-row-identite[data-id="' + itemId + '"]').attr('data-name');
                    var data_piece = $('tr.item-row-identite[data-id="' + itemId + '"]').attr('data-piece');
                    $('#statut_occupant').val(data_statut).change();
                    $('#phone_occupant_trouver').val(data_phone);
                    $('#numero_piece_identity').val(data_piece);
                    $('#nom_occupant_trouver').val(data_name);
                    $('#nom_occupant_trouver').attr('data_id', itemId);
                    $('#nom_occupant_trouver').attr('data_phone', data_phone);
                    $('#nom_occupant_trouver').attr('data_statut', data_statut);
                    $('#nom_occupant_trouver').attr('data_name', data_name);
                    $('#nom_occupant_trouver').attr('data_piece', data_piece);
                } else if (identite_trigger == 'client') {
                    var itemId = $(this).parents('tr.item-row-identite').attr('data-id');
                    var data_phone = $('tr.item-row-identite[data-id="' + itemId + '"]').attr('data-phone');
                    var data_statut = $('tr.item-row-identite[data-id="' + itemId + '"]').attr('data-statut');
                    var data_name = $('tr.item-row-identite[data-id="' + itemId + '"]').attr('data-name');
                    // var data_piece = $('tr.item-row-identite[data-id="' + itemId + '"]').attr('data-piece');
                    $('#statut_client').val(data_statut).change();
                    $('#phone_abonne').val(data_phone);
                    $('#nom_abonne').val(data_name);
                    $('#nom_abonne').attr('data_id', itemId);
                    $('#nom_abonne').attr('data_phone', data_phone);
                    $('#nom_abonne').attr('data_statut', data_statut);
                    $('#nom_abonne').attr('data_name', data_name);
                    $('#nom_abonne').attr('data_piece', data_piece);
                }
                $('#dlg_frm_lst_identite').hide();
                $('#dlg_main').show();
            });
            <?php
            // }			


            //if ($utilisateur->HasDroits("10_30")) {
            ?>
            jQuery(document).delegate('a.delete-item-identite', 'click', function(e) {
                e.preventDefault();
                var itemId = $(this).parents('tr.item-row-identite').attr('data-id');
                var label = $('tr.item-row-identite[data-id="' + itemId + '"]').attr('data-name');

                $('#view_invalidation').val('delete_menage');
                $('#invalidation_id').val(itemId);
                $('#invalidation_motif').val('');
                $('#invalidation_title').html("Motif d\'invalidation du ménage (" + label + ")");
                swal({
                    title: "Information",
                    text: 'Voulez-vous invalider le ménage (' + label + ') de la liste?',
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#00A65A",
                    confirmButtonText: "Oui",
                    cancelButtonText: "Non",
                    closeOnConfirm: true,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        $("#dlg_frm_lst_identite").hide();
                        $("#box_motif").show();
                    }
                });
            });

            $('#btn_submit_invalidation').click(function() {

                var frm = $("#frm_invalidation");
                if (frm.parsley().validate()) {} else {
                    return false;
                }
                var form = document.getElementById("frm_invalidation");
                var formDataInval = new FormData(form);



                $.ajax({
                    url: "controller.php",
                    data: formDataInval,
                    type: "POST",
                    contentType: false,
                    processData: false,
                    cache: false,
                    dataType: "json",
                    beforeSend: function() {
                        ShowLoader("Invalidation du ménage en cours...");
                    },
                    success: function(result) {
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
                                    //window.location.reload();
                                    $('#dlg_main').show();
                                    $('#box_motif').hide();
                                });
                            } else if (result.error == 1) {
                                swal("Information", result.message, "error");
                            }

                        } catch (erreur) {}

                    },
                    complete: function() {
                        HideLoader();
                    }
                });
            });

            <?php

            // }			
            //if ($utilisateur->HasDroits("10_30")) {
            ?> // $('.delete').click(function () {
            jQuery(document).delegate('a.edit-item-identite', 'click', function(e) {
                e.preventDefault();
                var itemId = $(this).parents('tr.item-row-identite').attr('data-id');
                var data_phone = $('tr.item-row-identite[data-id="' + itemId + '"]').attr('data-phone');
                var data_statut = $('tr.item-row-identite[data-id="' + itemId + '"]').attr('data-statut');
                var data_nom = $('tr.item-row-identite[data-id="' + itemId + '"]').attr('data-nom');
                var data_postnom = $('tr.item-row-identite[data-id="' + itemId + '"]').attr('data-postnom');
                var data_prenom = $('tr.item-row-identite[data-id="' + itemId + '"]').attr('data-prenom');
                var data_piece = $('tr.item-row-identite[data-id="' + itemId + '"]').attr('data-piece');
                var data_lieu = $('tr.item-row-identite[data-id="' + itemId + '"]').attr('data-lieu');
                var data_sexe = $('tr.item-row-identite[data-id="' + itemId + '"]').attr('data-sexe');
                $('#identite_id').val(itemId);
                $('#view_identite').val("edit_menage");
                $('#identite_nom').val(data_nom);
                $('#identite_postnom').val(data_postnom);
                $('#identite_prenom').val(data_prenom);
                $('#identite_lieu').val(data_lieu);
                $('#identite_piece').val(data_piece);
                $('#identite_phone').val(data_phone);
                $('#identite_sexe').val(data_sexe).change();
                $('#identite_statut').val(data_statut).change();

                $('#identite_title').html('MISE A JOUR MENAGE');
                $('#dlg_frm_lst_identite').hide();
                $('#dlg_frm_identite').show();
            });
            <?php
            // }

            if ($utilisateur->HasDroits("10_30")) {
            ?> // $('.delete').click(function () {
                jQuery(document).delegate('a.delete', 'click', function(e) {
                    e.preventDefault();
                    var name_actuel = jQuery(this).attr("data-name");
                    var jeton_actuel = jQuery(this).attr("data-id");
                    swal({
                        title: "Information",
                        text: 'Voulez-vous invalider la fiche de l\'abonné (' + name_actuel + ')?',
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#00A65A",
                        confirmButtonText: "Oui",
                        cancelButtonText: "Non",
                        closeOnConfirm: false,
                        closeOnCancel: true
                    }, function(isConfirm) {
                        if (isConfirm) {
                            var view_mode = "delete_customer";
                            $.ajax({
                                url: "controller.php",
                                method: "POST",
                                data: {
                                    view: view_mode,
                                    k: jeton_actuel
                                },
                                beforeSend: function() {
                                    // $('.loader').html('<img src="loading.gif" alt="" width="24" height="24" style="padding-left: 400px; margin-top:10px;" >');
                                    // $("#overlay").show();
                                    ShowLoader("Invalidation de la Fiche en cours...");
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
                                            //window.location.reload();
                                            LoadALL();
                                        });
                                    } else if (result.error == 1) {
                                        swal("Information", result.message, "error");
                                    }
                                },
                                complete: function() {
                                    HideLoader();
                                }
                            });
                        }
                    });
                });
            <?php } ?>
            <?php if ($utilisateur->HasDroits("10_730")) {
            ?>

                // $('.delete').click(function () {
                jQuery(document).delegate('a.view-fiche-identification', 'click', function(e) {
                    e.preventDefault();
                    var name_actuel = jQuery(this).attr("data-name");
                    var jeton_actuel = jQuery(this).attr("data-id");
                    var view_mode = "visualiser_fiche_identification";
                    $.ajax({
                        url: "controller.php",
                        method: "GET",
                        data: {
                            view: view_mode,
                            q: jeton_actuel
                        },
                        beforeSend: function() {
                            ShowLoader("Chargement de la Fiche en cours...");
                        },
                        success: function(data) {
                            try {
                                var result = $.parseJSON(data);
                                if (result.error == 0) {

                                    $("#fiche_viewer_title").html('VISUALISATION FICHE IDENTIFICATION');
                                    $("#fiche_viewer").html(result.data);
                                    // modalbox_scroll();

                                    // $("#box_fiche_viewer").show();
                                    // $("#myBackdrop").show();
                                    ShowFiche();
                                } else if (result.error == 1) {
                                    swal("Information", result.message, "error");
                                }
                            } catch (erreur) {}
                        },
                        complete: function() {
                            HideLoader();
                        }
                    });

                });
            <?php } ?>




            <?php if ($utilisateur->HasDroits("10_10")) {
            ?>
                $('#btn_new_').click(function(e) {
                    e.preventDefault();
                    actual_avenue = "";
                    actual_commune = "";
                    actual_quartier = "";
                    actual_cvs = "";
                    actual_chief = "";
                    $('#add_line').show();
                    ClearMaterielsRow();
                    ClearForm();

                    $('#doc_save_mode').html('');
                    $('#doc_save_mode').append('<option selected="" value="">Choisir mode de sauvegarde </option>');
                    $('#doc_save_mode').append('<option value="1">Brouillon</option>');
                    $('#doc_save_mode').append('<option value="0">Définitive</option>');

                    // $('#mainForm #view').val("create_customer");
                    $('#mainForm #view').val("edit_customer");
                    $('#titre').html('NOUVELLE IDENTIFICATION');
                    //$("#loading_msg").html("Enregistrement en cours...");  
                    var view_mode = "prepare_identification";
                    $.ajax({
                        url: "controller.php",
                        method: "GET",
                        dataType: "json",
                        data: {
                            view: view_mode
                        },
                        beforeSend: function() {
                            ShowLoader("Préparation Fiche Identification en cours...");
                        },
                        success: function(result) {
                            try {
                                if (result.error == 0) {
                                    $('#mainForm #UID').val(result.uid);
                                    ShowMain();
                                } else if (result.error == 1) {
                                    swal("Information", result.message, "error");
                                }

                            } catch (erreur) {
                                swal("Information", erreur, "error");

                            }

                        },
                        complete: function() {
                            HideLoader();
                        }
                    });

                    //}								  

                });
            <?php } ?>


            function ShowMain() {
                $('#myBackdrop').show();
                $('#dlg_main').show();
                if ($('#dlg_main').is(':visible')) {
                    if (!$('body').hasClass('modal-open'))
                        $('body').addClass('modal-open');
                }
            }

            function ShowFiche() {
                $('#myBackdrop').show();
                $('#box_fiche_viewer').show();
                if ($('#box_fiche_viewer').is(':visible')) {
                    if (!$('body').hasClass('modal-open'))
                        $('body').addClass('modal-open');
                }
            }

            function OpenBackDrop() {
                if (!$('body').hasClass('modal-open'))
                    $('body').addClass('modal-open');

            }

            function CloseBackDrop() {
                if ($('body').hasClass('modal-open'))
                    $('body').removeClass('modal-open');
            }

            function CloseMain() {
                $('#myBackdrop').hide();
                $('#dlg_main').hide();
                if ($('body').hasClass('modal-open'))
                    $('body').removeClass('modal-open');

            }

            function CloseFiche() {
                $('#myBackdrop').hide();
                $('#box_fiche_viewer').hide();
                if ($('body').hasClass('modal-open'))
                    $('body').removeClass('modal-open');

            }



            function ClearFormIdentite() {
                $('#frm_identite')[0].reset();
                $('#frm_identite').find('select, select1').val('').change();
            }

            function ClearForm() {
                load_chief = false;
                load_cvs = false;
                load_avenue = false;
                load_commune = false;
                load_quartier = false;
                $('#photo_pa_list').html('');
                $("#ville_id").val("").change();
                $("#commune_id").val("").change();
                $("#quartier").val("").change();
                $("#adresse").val("").change();
                $("#id_equipe_identification").val("").change();
                $("#chef_equipe").val("").change();
                $("#identificateur").val("").change();
                $("#view").val("");
                $("#UID").val("");
                $("#date_identification").val("");
                $("#p_a").val("");
                $("#num_compteur_actuel").val("");
                $("#type_client").val("").change();
                $("#consommateur_gerer").val("").change();
                $("#conformites_installation").val("").change();
                $("#cvs_id").val("").change();
                $("#nom_responsable").val("");
                $("#phone_responsable").val("");
                $("#nom_remplacant").val("");
                $("#phone_remplacant").val("");
                $("#nom_abonne").val("");
                $("#phone_abonne").val("");
                $("#photo_pa_avant").attr('src', 'pictures/');
                $("#nbre_branchement").val("");
                $("#section_cable").val("").change();
                // $("#section_cable_deux").val("").change();
                $("#numero_piece_identity").val("");
                $("#numero_avenue").val("");
                $("#accessibility_client").val("").change();
                $("#tarif_identif").val("").change();
                $("#infos_supplementaires").val("");
                $("#nbre_menage_a_connecter").val("");
                $("#noms_equipe_blue_energy").val("");
                $("#numero_depart").val("");
                $("#numero_poteau_identif").val("");
                $("#type_raccordement_identif").val("").change();
                $("#type_compteur").val("").change();
                $("#type_construction").val("");
                $("#nbre_appartement").val("");
                $("#nbre_habitant").val("");
                $("#type_activites").val("").change();
                $("#conformites_installation").val("");
                $("#avis_technique_blue").val("");
                $("#avis_occupant").val("");
                $("#chef_equipe").val("");
                $("#statut_occupant").val("").change();
                $("#statut_client").val("").change();
                $("#titre_responsable").val("").change();
                $("#titre_remplacant").val("").change();
                $('#mainForm')[0].reset();
                var frm = $("#mainForm");
                frm.parsley().reset();
                load_cvs = true;
                load_chief = true;

                load_commune = true;
            }

            /*  function ClearForm_install(){
                                //$("#view_inst").val("");
                                        $("#id_install").val("");
                                        $("#date_fin_installation").val("");
                                        $("#p_a").val("");
                                        $("#nom_abonne").val("");
                                        $("#phone_abonne").val("");
                                        $("#adresse").val("");
                                        $("#photo_pa_avant").attr('src', 'pictures/');
                                        $("#photo_compteur").attr('src', 'pictures/');
                                        $("#numero_compteur").val("");
                                        $("#marque_compteur").val("").change();
                                        $("#nom_equipe").val("").change();
										$('#mainForm_install')[0].reset();
                                        var frm = $("#mainForm_install");
                                        frm.parsley().reset();
                                }*/
            /*
             $(document).on("submit", "form", function(event) { event.preventDefault(); $.ajax({ url: $(this).attr("action"), type: $(this).attr("method"), dataType: "JSON", data: new FormData(this), processData: false, contentType: false, 
             success: function (data, status) { }
             , error: function (xhr, desc, err) { } });
             });*/

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
                ShowLoader("Enregistrement en cours ...");
                // Create a FormData and append the file with "image" as parameter name
                var formDataToUpload = new FormData(form);
                var lst_materiels = '[';
                var otArr = [];
                // var tbl2 = $('#lignes tbody tr').each(function(i) {
                var tbl2 = $('#lignes  .item-row').each(function(i) {
                    var itArr = [];
                    var item_row_id = $(this).attr('item-id');
                    var item_row_label = $(this).attr('materiel-id');
                    var item_row_qte = $('tr.item-row[item-id="' + item_row_id + '"]').find('span.qte').html();
                    otArr.push("{\"libelle\":\"" + item_row_label + "\",\"qte\":\"" + item_row_qte + "\"}");
                });
                lst_materiels += otArr.join(",") + ']';

                var lignes = $('#photo_pa_list .photo-item');
                lignes.each(function(i) {
                    // x = $(this).children();
                    //var itArr = [];
                    var row_id = $(this).attr('bloc-photo-id');
                    var base64img = document.getElementById("pa_pic_" + row_id).src;
                    if (base64img.length) {
                        if (base64img.match(/^data\:image\/(\w+)/)) {
                            var block_img = base64img.split(";");
                            var contentype = block_img[0].split(":")[1];
                            var realDat = block_img[1].split(",")[1];

                            // Convert it to a blob to upload
                            var blob_ = b64toBlob(realDat, contentype);
                            formDataToUpload.append("photo_pa_avant[]", blob_);
                        }
                    }
                });

                var client_id = $('#nom_abonne').attr('data_id');
                var occupant_id = $('#nom_occupant_trouver').attr('data_id');
                formDataToUpload.append("lst_materiels", lst_materiels);
                formDataToUpload.append("client_id", client_id);
                formDataToUpload.append("occupant_id", occupant_id);

                $.ajax({
                    //enctype: 'multipart/form-data',
                    url: "controller.php",
                    data: formDataToUpload,
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


                                    var op_ref = $('#dlg_main-install #view').val();


                                    ClearForm();
                                    ClearMaterielsRow();
                                    CloseMain();
                                    LoadALL();





                                });
                            } else if (result.error == 1) {
                                //swal("Information", result.message, "error");
                                var need_reconnect = result.reconnect != null ? result.reconnect : false;
                                if (need_reconnect == true) {
                                    Reconnect();
                                } else {
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
                    beforeSend: function() {

                    },
                    complete: function() {
                        HideLoader();
                    }
                });

            });

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
            <?php if ($utilisateur->HasDroits("10_20") || $utilisateur->HasDroits("10_650")) {  ?>
                jQuery(document).delegate('a.edit', 'click', function(e) {
                    e.preventDefault();
                    ClearForm();
                    ClearMaterielsRow();
                    var jeton_actuel = jQuery(this).attr("data-id");
                    $('#titre').html('MODIFICATION INFORMATIONS IDENTIFICATION');
                    $.ajax({
                        url: "controller.php",
                        dataType: "json",
                        method: "GET",
                        data: {
                            view: 'detail_customer',
                            k: jeton_actuel
                        },
                        beforeSend: function() {
                            ShowLoader("Chargement en cours...");
                        },
                        success: function(result, statut) {
                            try {
                                if (result.error == 0) {
                                    $('#doc_save_mode').html('');
                                    if (result.data.is_draft == '1') {
                                        $('#add_line').show();
                                        $('#doc_save_mode').append('<option selected="" value="">Choisir mode de sauvegarde </option>');
                                        $('#doc_save_mode').append('<option value="1">Brouillon</option>');
                                        $('#doc_save_mode').append('<option value="0">Définitive</option>');
                                    } else {
                                        $('#add_line').hide();
                                        $('#doc_save_mode').append('<option value="0">Définitive</option>');
                                        $('#doc_save_mode').val('0').change();
                                    }
                                    $("#mainForm #view").val("edit_customer");
                                    $("#loading_msg").html("Mise à jour en cours...");
                                    $("#UID").val(result.data.id_);
                                    $("#UID").attr('adress_id', result.adresse.id);
                                    $("#date_identification").val(result.data.date_identification_fr);
                                    $("#p_a").val(result.data.p_a);
                                    if (result.data.num_compteur_actuel != null && result.data.num_compteur_actuel.length > 0) {
                                        $("#num_compteur_actuel").prop("readonly", false);
                                    } else {
                                        $("#num_compteur_actuel").prop("readonly", true);
                                    }
                                    $("#num_compteur_actuel").val(result.data.num_compteur_actuel);
                                    actual_cvs = result.data.cvs_id;
                                    $("#id_equipe_identification").val(result.data.id_equipe_identification).change();
                                    $("#nom_responsable").val(result.data.nom_proprietaire_facture_snel);
                                    $("#phone_responsable").val(result.data.phone_proprietaire_facture_snel);
                                    $("#nom_remplacant").val(result.data.nom_remplacant);
                                    $("#phone_remplacant").val(result.data.phone_remplacant);
                                    $("#nom_abonne").val(result.client.noms);
                                    $("#nom_abonne").attr('data-id', result.client.id);
                                    // $("#nom_abonne").attr('data_name',result.client.id);
                                    $("#nom_abonne").attr('data_id', result.client.id);
                                    $("#phone_abonne").val(result.client.phone_number);
                                    $("#gps_longitude").val(result.data.gps_longitude);
                                    $("#gps_latitude").val(result.data.gps_latitude);
                                    $("#photo_pa_avant").attr('src', 'pictures/' + result.data.id_ + '.png');
                                    $("#nbre_branchement").val(result.data.nbre_branchement);
                                    $("#section_cable").val(result.data.section_cable).change();
                                    // $("#section_cable_deux").val(result.data.section_cable_deux).change();

                                    //ADRESSE
                                    actual_avenue = result.adresse.avenue;
                                    actual_commune = result.adresse.commune_id;
                                    actual_quartier = result.adresse.quartier_id;
                                    $("#ville_id").val(result.adresse.ville_id).change();
                                    $("#numero_avenue").val(result.adresse.numero);
                                    //ADRESSE

                                    $("#numero_piece_identity").val(result.occupant.num_piece_identity);
                                    $("#accessibility_client").val(result.data.accessibility_client).change();
                                    $("#tarif_identif").val(result.data.tarif_identif).change();
                                    $("#infos_supplementaires").val(result.data.infos_supplementaires);
                                    $("#nbre_menage_a_connecter").val(result.data.nbre_menage_a_connecter);
                                    $("#noms_equipe_blue_energy").val(result.data.noms_equipe_blue_energy);
                                    $("#numero_depart").val(result.data.numero_depart);
                                    $("#numero_poteau_identif").val(result.data.numero_poteau_identif);
                                    $("#type_raccordement_identif").val(result.data.type_raccordement_identif).change();
                                    $("#type_compteur").val(result.data.type_compteur).change();
                                    $("#type_construction").val(result.data.type_construction);
                                    $("#nbre_appartement").val(result.data.nbre_appartement);
                                    $("#nbre_habitant").val(result.data.nbre_habitant);
                                    $("#type_activites").val(result.data.type_activites).change();
                                    $("#conformites_installation").val(result.data.conformites_installation).change();
                                    $("#avis_technique_blue").val(result.data.avis_technique_blue);
                                    $("#avis_occupant").val(result.data.avis_occupant);
                                    $("#chef_equipe").val(result.data.chef_equipe).change();


                                    actual_chief = result.data.chef_equipe;
                                    // $("#statut_occupant").val(result.data.statut_occupant).change();
                                    $("#statut_client").val(result.client.statut_identity).change();
                                    $("#titre_responsable").val(result.data.titre_responsable).change();
                                    $("#titre_remplacant").val(result.data.titre_remplacant).change();




                                    $("#statut_occupant").val(result.occupant.statut_identity).change();
                                    $("#type_client").val(result.data.type_client).change();
                                    $("#consommateur_gerer").val(result.data.consommateur_gerer).change();
                                    $("#nom_occupant_trouver").val(result.occupant.noms);
                                    $("#nom_occupant_trouver").attr('data-id', result.occupant.id);
                                    $("#nom_occupant_trouver").attr('data_id', result.occupant.id);
                                    $("#phone_occupant_trouver").val(result.occupant.phone_number);
                                    $("#nature_activity").val(result.data.nature_activity);
                                    $("#cabine_id").val(result.data.cabine_id);
                                    $("#identificateur").val(result.data.identificateur).change();





                                    $.each(result.photos, function(i, item) {
                                        $('#photo_pa_list').append('<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" >' +
                                            '<div class="card">' +
                                            '<div class="card-body">' +
                                            '	<label></label>' +
                                            '	  ' +
                                            '<div class="input-group" style="width: 100%;"> ' +
                                            '	<img style="height:300px;" class="form-control pull-right"  src="pictures/' + item.ref_photo + '.png"> ' +
                                            '</div> ' +
                                            '</div>	' +
                                            '</div> ' +
                                            '</div>');
                                    });



                                    var lignes = $('#lignes tbody');
                                    var Id;
                                    $.each(result.items, function(i, item) {
                                        Id = generateItemID(lignes);

                                        /*           */




                                        if (result.data.is_draft == '1') {


                                            lignes.append('<tr class="item-row" item-id="item-' + Id + '" data-id="' + Id + '" materiel-id="' + item.ref_article + '"><td style="width:5%"><span class="n"></span></td><td style="width:80%"><span class="sn">' + item.designation + '</span></td><td><span class="qte">' + item.qte_identification + '</span></td><td><a class="btn btn-xs edit-item"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-xs delete-item"><i class="fas fa-trash"></i></a></td></tr>');
                                        } else {

                                            lignes.append('<tr   item-id="item-' + Id + '" data-id="' + Id + '" materiel-id="' + item.ref_article + '"><td style="width:5%"><span class="n"></span></td><td style="width:80%"><span class="sn">' + item.designation + '</span></td><td><span class="qte">' + item.qte_identification + '</span></td><td></td></tr>');
                                        }



                                    });
                                    reOrderRow();
                                    ShowMain();
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
                            //		$inputs.prop("disabled", false);
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
            <?php } ?>

            <?php
            if ($utilisateur->HasDroits("10_930")) {

            ?>
                jQuery(document).delegate('a.change-numero', 'click', function(e) {
                    e.preventDefault();


                });

            <?php } ?>
            <?php
            // if ($utilisateur->HasDroits("10_20") ||$utilisateur->HasDroits("10_650")) {  

            ?>
            jQuery(document).delegate('a.add', 'click', function(e) {
                e.preventDefault();
                ClearForm();
                ClearMaterielsRow();
                var jeton_actuel = jQuery(this).attr("data-id");
                $('#titre').html('NOUVELLE IDENTIFICATION MEME ADRESSE');
                $.ajax({
                    url: "controller.php",
                    dataType: "json",
                    method: "GET",
                    data: {
                        view: 'add_customer',
                        k: jeton_actuel
                    },
                    beforeSend: function() {
                        ShowLoader("Préparation Fiche identification en cours...");
                    },
                    success: function(result, statut) {
                        try {
                            if (result.error == 0) {
                                $('#doc_save_mode').html('');
                                $('#add_line').show();
                                $('#doc_save_mode').append('<option selected="" value="">Choisir mode de sauvegarde </option>');
                                $('#doc_save_mode').append('<option value="1">Brouillon</option>');
                                $('#doc_save_mode').append('<option value="0">Définitive</option>');
                                $("#mainForm #view").val("edit_customer");
                                $("#loading_msg").html("Mise à jour en cours...");
                                $("#UID").val(result.uid);
                                $("#UID").attr('adress_id', result.adresse.id);
                                $("#p_a").val(result.data.p_a);

                                $("#num_compteur_actuel").prop("readonly", true);

                                $("#num_compteur_actuel").val("");
                                actual_cvs = result.data.cvs_id;
                                $("#id_equipe_identification").val(result.data.id_equipe_identification).change();
                                $("#nom_responsable").val("");
                                $("#phone_responsable").val("");
                                $("#nom_remplacant").val("");
                                $("#phone_remplacant").val("");
                                $("#nom_abonne").val("");
                                $("#nom_abonne").attr('data-id', null);
                                // $("#nom_abonne").attr('data_name',result.client.id);
                                $("#nom_abonne").attr('data_id', null);
                                $("#phone_abonne").val("");
                                $("#gps_longitude").val(result.data.gps_longitude);
                                $("#gps_latitude").val(result.data.gps_latitude);
                                // $("#photo_pa_avant").attr('src', 'pictures/' + result.data.id_ + '.png');
                                // $("#photo_pa_avant").attr('src', null);
                                $("#nbre_branchement").val(result.data.nbre_branchement);
                                $("#section_cable").val(result.data.section_cable).change();

                                //ADRESSE
                                actual_avenue = result.adresse.avenue;
                                actual_commune = result.adresse.commune_id;
                                actual_quartier = result.adresse.quartier_id;
                                $("#ville_id").val(result.adresse.ville_id).change();
                                $("#numero_avenue").val(result.adresse.numero);
                                //ADRESSE

                                $("#numero_piece_identity").val("");
                                $("#accessibility_client").val(result.data.accessibility_client).change();
                                $("#tarif_identif").val("").change();
                                $("#infos_supplementaires").val("");
                                $("#nbre_menage_a_connecter").val(result.data.nbre_menage_a_connecter);
                                $("#noms_equipe_blue_energy").val(result.data.noms_equipe_blue_energy);
                                $("#numero_depart").val(result.data.numero_depart);
                                $("#numero_poteau_identif").val(result.data.numero_poteau_identif);
                                $("#type_raccordement_identif").val(result.data.type_raccordement_identif).change();
                                $("#type_compteur").val("").change();
                                $("#type_construction").val(result.data.type_construction);
                                $("#nbre_appartement").val(result.data.nbre_appartement);
                                $("#nbre_habitant").val(result.data.nbre_habitant);
                                $("#type_activites").val(result.data.type_activites).change();
                                $("#conformites_installation").val("").change();
                                $("#avis_technique_blue").val("");
                                $("#avis_occupant").val("");
                                $("#chef_equipe").val(result.data.chef_equipe).change();


                                actual_chief = result.data.chef_equipe;
                                // $("#statut_occupant").val(result.data.statut_occupant).change();
                                $("#statut_client").val(result.client.statut_identity).change();
                                $("#titre_responsable").val(result.data.titre_responsable).change();
                                $("#titre_remplacant").val(result.data.titre_remplacant).change();




                                $("#statut_occupant").val("").change();
                                $("#type_client").val("").change();
                                $("#consommateur_gerer").val("").change();
                                $("#nom_occupant_trouver").val("");
                                $("#nom_occupant_trouver").attr('data-id', null);
                                $("#nom_occupant_trouver").attr('data_id', null);
                                $("#phone_occupant_trouver").val("");
                                $("#nature_activity").val("");
                                $("#cabine_id").val(result.data.cabine_id);
                                $("#identificateur").val(result.data.identificateur).change();





                                /*	$.each(result.photos, function(i, item) { 
                                			$('#photo_pa_list').append('<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" >'+
                                												'<div class="card">' + 
                                													'<div class="card-body">' + 												   
                                													'	<label></label>' + 
                                													'	  ' + 
                                														'<div class="input-group" style="width: 100%;"> ' + 
                                														'	<img style="height:300px;" class="form-control pull-right"  src="pictures/' + item.ref_photo +'.png"> ' + 
                                														'</div> ' +                
                                													'</div>	' + 													 
                                												'</div> ' + 
                                											'</div>');
                                	});*/



                                /*    var lignes = $('#lignes tbody');
                                                    var Id;
                                                    $.each(result.items, function(i, item) {
                                            Id = generateItemID(lignes);
                                                  
                                      
									   
									   
									   
									   
									   if(result.data.is_draft == '1'){
										   
												
													   lignes.append('<tr class="item-row" item-id="item-' + Id + '" data-id="' + Id + '" materiel-id="' + item.ref_article + '"><td style="width:5%"><span class="n"></span></td><td style="width:80%"><span class="sn">' + item.designation + '</span></td><td><span class="qte">' + item.qte_identification + '</span></td><td><a class="btn btn-xs edit-item"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-xs delete-item"><i class="fas fa-trash"></i></a></td></tr>');
												}else{	
												 
													   lignes.append('<tr   item-id="item-' + Id + '" data-id="' + Id + '" materiel-id="' + item.ref_article + '"><td style="width:5%"><span class="n"></span></td><td style="width:80%"><span class="sn">' + item.designation + '</span></td><td><span class="qte">' + item.qte_identification + '</span></td><td></td></tr>');
												}
												
												
												
                                            });*/
                                reOrderRow();
                                ShowMain();
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
                        //		$inputs.prop("disabled", false);
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
            //	} 

            ?>


            function hideReplaceBlock() {
                //$("#block_remplacement").prop("display", "none");
                $("#block_remplacement").hide();
                $("#marque_cpteur_replaced").prop("required", false);
                $("#index_credit_restant_cpteur_replaced").prop("required", false);
                $("#num_serie_cpteur_replaced").prop("required", false);
                $("#type_defaut").prop("required", false);
            }

            function showReplaceBlock() {
                $("#block_remplacement").show();
                $("#marque_cpteur_replaced").prop("required", true);
                $("#index_credit_restant_cpteur_replaced").prop("required", true);
                $("#num_serie_cpteur_replaced").prop("required", true);
                $("#type_defaut").prop("required", true);
            }

            function ShowMenage() {
                var v = $("#ville_id").val();
                var com = $("#commune_id").val();
                var q = $("#quartier").val();
                var adr = $("#adresse").val();
                var numero = $("#numero_avenue").val();
                var can_continue = false;
                if (v.length > 0 && com.length > 0 && q.length > 0 && adr.length > 0 && numero.length > 0) {
                    ShowLoader("Chargement liste des ménages en cours...");
                    $("#lignes_menages").html('');
                    var formMenage = new FormData();
                    var ville_id = $('#ville_id').val();
                    var commune_id = $('#commune_id').val();
                    var quartier = $('#quartier').val();
                    var adresse = $('#adresse').val();
                    var numero_avenue = $('#numero_avenue').val();
                    formMenage.append("view", "get_adress_menage");
                    formMenage.append("ville_id", ville_id);
                    formMenage.append("commune_id", commune_id);
                    formMenage.append("quartier", quartier);
                    formMenage.append("adresse", adresse);
                    formMenage.append("numero_avenue", numero_avenue);
                    $.ajax({
                        url: "controller.php",
                        data: formMenage,
                        type: "POST",
                        contentType: false,
                        processData: false,
                        cache: false,
                        dataType: "json",
                        success: function(result, statut) {
                            try {
                                //var result = $.parseJSON(data);
                                if (result.count > 0) {
                                    $('#lignes_menages').html(result.data);
                                } else {

                                }
                                // if(!$('#mainForm #UID').attr('adress_id').length > 0){
                                $('#mainForm #UID').attr('adress_id', result.adress_id);
                                $('#identite_adress_id').val(result.adress_id);
                                // }
                                $('#dlg_main').hide();
                                $('#dlg_frm_lst_identite').show();
                                // $('#frm_').hide();
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

                } else if (v == "") {
                    $("#ville_id").focus();
                    swal("Information", "Veuillez préciser la ville", "error");
                } else if (com == "") {
                    $("#commune_id").focus();
                    swal("Information", "Veuillez préciser la commune", "error");
                } else if (q == "") {
                    $("#quartier").focus();
                    swal("Information", "Veuillez préciser le quartier", "error");
                } else if (adr == "") {
                    $("#adresse").focus();
                    swal("Information", "Veuillez préciser l'avenue", "error");
                } else if (numero == "") {
                    $("#numero_avenue").focus();
                    swal("Information", "Veuillez préciser le numéro de la parcelle", "error");
                }
            }

            function showBlockPostPaie() {
                $("#block_post_paie").show();
            }

            function hideBlockPostPaie() {
                $("#block_post_paie").hide();
            }



            $("#accessibility_client").on("change", function(e) {
                var item = $(this).val() != null ? $(this).val() : '';
                e.preventDefault();
                $("#btn_Signaler_Refus").hide();
                $("#btn_Signaler_Exoneration").hide();
                $("#bloc_dat_rendez_vous").hide();
                if (item.length > 0) {
                    if (item == '1') {
                        $("#btn_Signaler_Refus").show();

                        $("#notification_title").html("Notification Refus");
                    } else if (item == '3') {
                        $("#notification_title").html("Notification Exonération");
                        $("#btn_Signaler_Exoneration").text('Signaler Exonération');
                        $("#btn_Signaler_Exoneration").addClass('btn-warning');
                        $("#btn_Signaler_Exoneration").show();
                    } else if (item == '4') {
                        $("#notification_title").html("Enregistrement rendez-vous");
                        $("#btn_Signaler_Exoneration").text('Rendez-vous');
                        $("#btn_Signaler_Exoneration").removeClass('btn-warning');
                        $("#btn_Signaler_Exoneration").addClass('btn-info');
                        $("#btn_Signaler_Exoneration").show();

                        $("#bloc_dat_rendez_vous").show();
                    }
                }
            });
            <?php
            if ($utilisateur->id_service_group ==  '3') {
            ?>

                $("#id_equipe_identification").on("change", function(e) {
                    var item = $(this).val();
                    e.preventDefault();
                    if (load_chief == false) {
                        return false;
                    }
                    ShowLoader("Chargement liste des chefs d'equipe en cours...");
                    $("#chef_equipe").html('');
                    $.ajax({
                        url: "controller.php",
                        method: "GET",
                        data: {
                            view: "get_organisme_chief",
                            id_: item
                        },
                        success: function(data, statut) {
                            try {
                                var result = $.parseJSON(data);
                                if (result.error == 0) {
                                    $("#chef_equipe").html(result.data);
                                    if (actual_chief != "") {
                                        $("#chef_equipe").val(actual_chief).change();
                                    }
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
            }

            ?>
            $("#ville_id").on("change", function(e) {
                var item = $(this).val();
                load_quartier = false;
                load_avenue = false;
                $("#commune_id").html('');
                $("#cvs_id").html('');
                $("#quartier").html('');
                $("#adresse").html('');
                if (load_commune == false || item != null && item.length == 0) {
                    return false;
                }
                e.preventDefault();
                ShowLoader("Chargement liste des communes en cours...");
                $.ajax({
                    url: "controller.php",
                    method: "GET",
                    data: {
                        view: "get_ville_commune",
                        id_: item
                    },
                    success: function(data, statut) {
                        try {
                            var result = $.parseJSON(data);
                            if (result.error == 0) {
                                $("#commune_id").html(result.data);
                                load_quartier = true;
                                if (actual_commune != "") {
                                    $("#commune_id").val(actual_commune).change();
                                }
                            } else if (result.error == 1) {

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


            $("#commune_id").on("change", function(e) {
                load_avenue = false;
                var item = $(this).val();
                if (load_quartier == false || item != null && item.length == 0) {
                    return false;
                }
                e.preventDefault();
                ShowLoader("Chargement liste des quartiers en cours...");
                $("#quartier").html('');
                $.ajax({
                    url: "controller.php",
                    method: "GET",
                    data: {
                        view: "get_commune_quartier",
                        id_: item
                    },
                    success: function(data, statut) {
                        try {
                            var result = $.parseJSON(data);
                            if (result.error == 0) {
                                $("#quartier").html(result.data);
                                load_avenue = true;
                                if (actual_quartier != "") {
                                    $("#quartier").val(actual_quartier).change();
                                }
                            } else if (result.error == 1) {

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

            $("#verify_identite_occupant").on("click", function(e) {
                event.preventDefault();
                identite_trigger = "occupant";
                ShowMenage();
            });



            $("#verify_identite").on("click", function(e) {
                event.preventDefault();
                identite_trigger = "client";
                ShowMenage();
            });



            $("#quartier").on("change", function(e) {
                var item = $(this).val();
                if (load_avenue == false || item != null && item.length == 0) {
                    return false;
                }
                e.preventDefault();
                ShowLoader("Chargement liste des avenues en cours...");
                $("#adresse").html('');
                $.ajax({
                    url: "controller.php",
                    method: "GET",
                    data: {
                        view: "get_commune_quartier",
                        id_: item
                    },
                    success: function(data, statut) {
                        try {
                            var result = $.parseJSON(data);
                            if (result.error == 0) {
                                $("#adresse").html(result.data);
                                if (actual_avenue != "") {
                                    $("#adresse").val(actual_avenue).change();
                                }
                            } else if (result.error == 1) {
                                var need_reconnect = result.reconnect != null ? result.reconnect : false;
                                if (need_reconnect == true) {
                                    Reconnect();
                                }
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
                    complete: function(b) {
                        HideLoader();
                    }
                });
            });

        });
    </script>

    <script>
        /*     var map = L.map('map');
                       L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpandmbXliNDBjZWd2M2x6bDk3c2ZtOTkifQ._QA7i5Mpkd_m30IGElHziw', {
                maxZoom: 18,
                        attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                        '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                        'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                        id: 'mapbox.streets'
                }).addTo(map);
				
				var current_position, current_accuracy;
				
				
				function onLocationFound(e) {
                        if (current_position) {
                        map.removeLayer(current_position);
                                map.removeLayer(current_accuracy);
                        }

						var radius = e.accuracy / 2;
						$("#gps_longitude").val(e.latlng.lng);
						$("#gps_latitude").val(e.latlng.lat); 
						HideLoader();
                }

                function onLocationError(e) {
                alert(e.message); 
                        HideLoader();
                }*/

        function onLocationFoundAndroid(e) {
            HideLoader();
            var tmpJson = $.parseJSON(e);
            $("#gps_longitude").val(tmpJson.lng);
            $("#gps_latitude").val(tmpJson.lat);
        }

        function onLocationFailAndroid(e) {
            HideLoader();
        }

        function showError(error) {
            HideLoader();
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("Permission de localisation refusée.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Coordonnées non disponible");
                    break;
                case error.TIMEOUT:
                    alert("Délai d'attente dépassé.");
                    break;
                case error.UNKNOWN_ERROR:
                    alert("Erreur inconnue.");
                    break;
            }
        }

        function showPosition(pos) {
            HideLoader();
            $("#gps_longitude").val(pos.coords.longitude);
            $("#gps_latitude").val(pos.coords.latitude);
        }

        function locateNew() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError, {
                    maximumAge: 0,
                    enableHighAccuracy: true
                });
            } else {
                alert("Votre navigateur ne supporte pas le GPS.");
            }
        }
        /*  map.on('locationfound', onLocationFound);
                  map.on('locationerror', onLocationError);
                  function locate() {
                  map.locate({setView: true, maxZoom: 16});
                  }*/

        <?php //if($MobileRun != "1"){ 
        ?>
        const webcamElement = document.getElementById('webcam');
        const canvasElement = document.getElementById('canvas');
        const snapSoundElement = document.getElementById('snapSound');
        const webcam = new Webcam(webcamElement, 'user', canvasElement, snapSoundElement);
        <?php //} 
        ?>
        //   Webcam.init(webcamElement, 'user', canvasElement, snapSoundElement);
        jQuery(document).delegate('a.delete-pa-photo', 'click', function(e) {
            e.preventDefault();
            // var itemId = $(this).parents('div.photo-item').attr('bloc-photo-id');
            $(this).parents('div.photo-item').remove();
            // $('div.photo-item[bloc-photo-id="' + itemId + '"]').remove(); 
        });

        function take_snapshot() {
            var t = webcam.snap();
            var img_id = Date.now();
            var photo_pa_list = $('#photo_pa_list');
            photo_pa_list.append('<div class="photo-item col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" bloc-photo-id="' + img_id + '">' +
                '<div class="card">' +
                '<div class="card-body">' +
                '	<label></label>' +
                '	<a class="btn btn-outline-light float-right delete-pa-photo">supprimer photo</a>  ' +
                '<div class="input-group" style="width: 100%;"> ' +
                '	<img style="height:300px;" class="form-control pull-right" name="photo_pa_avant[]" src="' + t + '" id="pa_pic_' + img_id + '"> ' +
                '</div> ' +
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
    </script>
    <script type="text/javascript">
        function ShowLoader(txt) {
            $("#loader").attr("data-text", txt);
            $("#loader").addClass("is-active");
        }

        function HideLoader() {
            $("#loader").removeClass("is-active");
        }

        function displayRecords(numRecords, pageNum, v_mode) {
            var s = $('#srch-term').val() != null ? $('#srch-term').val() : null;
            var du = $('#Du').val() != null ? $('#Du').val() : null;
            var au = $('#Au').val() != null ? $('#Au').val() : null;
            var filtre = $('#filtre').val() != null ? $('#filtre').val() : null;
            $.ajax({
                type: "GET",
                url: "controller.php",
                data: "view=search_view_identification&show=" + numRecords + "&page=" + pageNum + "&Du=" + du + "&Au=" + au + "&s=" + s + "&view_mode=" + v_mode + "&filtre=" + filtre,
                cache: false,
                dataType: "json",
                beforeSend: function() {
                    ShowLoader("Chargement des données en cours...");
                },
                success: function(result) {
                    try {
                        if (result.count > 0) {
                            $("#results").html(result.data);
                            $("#record_count").html(result.count + " Elément(s)");
                        } else {

                            var need_reconnect = result.reconnect != null ? result.reconnect : false;
                            if (need_reconnect == true) {
                                Reconnect();
                            } else {
                                $("#record_count").html("0 Elément(s)");
                                $("#results").html('<div class="card alert-danger"><div class="card-body"><div role="alert" class="text-center"><h1 class="alert-heading">Aucune information trouvée</h1></div></div></div>');

                            }
                        }
                    } catch (erreur) {}

                },
                complete: function() {
                    HideLoader();
                }
            });
        }

        function changeDisplayRowCount(numRecords) {
            var v_mode = '';
            var s = $('#srch-term').val().length > 0 ? $('#srch-term').val() : null;
            var du = $('#Du').val().length > 0 ? $('#Du').val() : null;
            var au = $('#Au').val().length > 0 ? $('#Au').val() : null;
            var filtre = $('#filtre').val().length > 0 ? $('#filtre').val() : null;
            if (s == null && du == null && au == null) {
                v_mode = '';
            } else if (s != null && du != null && au != null) {
                v_mode = 'advanced_search';
            } else if (s == null && du != null && au != null) {
                v_mode = 'date_only';
            } else if (s != null && du == null && au == null) {
                v_mode = 'search';
            } else if (s == null && (du == null || au == null)) {
                swal("Information", "Veuillez préciser les parametres de recherche", "error");
                return false;
            }
            displayRecords(numRecords, 1, v_mode);
        }

        function LoadALL() {
            var show = $("#show").val();
            $('#srch-term').val('');
            $('#Du').val('');
            $('#Au').val('');
            $('#filtre').val('').change();
            displayRecords(show, 1, 'all');
        }
        <?php //if($MobileRun == "1"){ 
        ?>

        /*
         function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
        			var img_id = Date.now();
        			var photo_pa_list = $('#photo_pa_list');	
        												    photo_pa_list.append('<div class="photo-item col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" bloc-photo-id="' + img_id + '">'+
        													'<div class="card">' + 
        														'<div class="card-body">' + 												   
        														'	<label></label>' + 
        														'	<a class="btn btn-outline-light float-right delete-pa-photo">supprimer photo</a>  ' + 
        															'<div class="input-group" style="width: 100%;"> ' + 
        															'	<img style="height:300px;" class="form-control pull-right" name="photo_pa_avant[]" src="' + e.target.result + '" id="pa_pic_' + img_id +'"> ' + 
        															'</div> ' +                
        														'</div>	' + 													 
        													'</div> ' + 
        												'</div>');
                
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#file-input").change(function(){
            readURL(this);
        }); */
        function previewFile(input) {

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var img_id = Date.now();
                    var block_img = e.target.result;
                    var photo_pa_list = $('#photo_pa_list');
                    //console.log(block_img);
                    if (!(block_img.match(/^data\:image\/(\w+)/))) {
                        //block_img = "data:" + block_img;
                    }
                    photo_pa_list.append('<div class="photo-item col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12" bloc-photo-id="' + img_id + '">' +
                        '<div class="card">' +
                        '<div class="card-body">' +
                        '	<label></label>' +
                        '	<a class="btn btn-outline-light float-right delete-pa-photo">supprimer photo</a>  ' +
                        '<div class="input-group" style="width: 100%;"> ' +
                        '	<img style="height:300px;" class="form-control pull-right" name="photo_pa_avant[]" src="' + block_img + '" id="pa_pic_' + img_id + '"> ' +
                        '</div> ' +
                        '</div>	' +
                        '</div> ' +
                        '</div>');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
        <?php //}
        ?>
        $(document).ready(function() {



            var show = $("#show").val();
            displayRecords(show, 1, '');


            $("#srch-term-lst-identite").keyup(function() {
                var val = $(this).val().toString().toLowerCase();
                $('#lignes_menages').find('.item-row-identite').each(function(i) {
                    var row = $(this);
                    var identite_name = row.find('h6.menage-nom').html().toString().toLowerCase();
                    var identite_statut = row.find('p.menage-statut').text().toString().toLowerCase();

                    if (identite_name.indexOf(val) != -1 || identite_statut.indexOf(val) != -1) {
                        row.show();
                    } else row.hide();
                });
                if (!val)
                    $('#lignes_menages').find('.item-row-identite').each(function(i) {
                        $(this).show();
                    });
            });


            jQuery(document).delegate('a.page-link', 'click', function(e) {
                e.preventDefault();
                var page = jQuery(this).attr("data-page");
                var v_mode = jQuery(this).attr("view-mode");
                var show = $("#show").val();
                displayRecords(show, page, v_mode);

            });

            $('#search-btn').click(function() {
                var v_mode = '';
                var show = $("#show").val();
                var filtre = $('#filtre').val().length > 0 ? $('#filtre').val() : null;
                var s = $('#srch-term').val().length > 0 ? $('#srch-term').val() : null;
                var du = $('#Du').val().length > 0 ? $('#Du').val() : null;
                var au = $('#Au').val().length > 0 ? $('#Au').val() : null;
                if (s == null && du == null && au == null && filtre == null) {
                    swal("Information", "Veuillez préciser les parametres de recherche", "error");
                    return false;
                } else if (s != null && du != null && au != null) {
                    v_mode = 'advanced_search';

                } else if (s == null && du != null && au != null) {
                    v_mode = 'date_only';
                } else if (s != null && du == null && au == null) {
                    v_mode = 'search';
                } else if (filtre != null) {
                    v_mode = 'all';
                } else if (s == null && (du == null || au == null)) {
                    swal("Information", "Veuillez préciser les parametres de recherche", "error");
                    return false;
                }

                displayRecords(show, 1, v_mode);

            });

            $('.view-all').click(function(e) {
                e.preventDefault();
                LoadALL();

            });



        });
    </script>
</body>

</html>