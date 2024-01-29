<?php
// session_start();
$mnu_title = "VISITE PA";
$page_title = "Historiques visites PA";
$home_page = "dashboard.php";
$active = "log_visit_pa";
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
                                        <div class="h5 font-weight-bold text-primary"> Journal visites </div>
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


                                                    <option value="t_param_log_visite_pa.type_motif_visite='0'">Identification</option>
                                                    <option value="t_param_log_visite_pa.type_motif_visite='1'">Contrôle</option>
                                                    <option value="t_param_log_visite_pa.type_motif_visite='2'">Installation</option>
                                                    <?php


                                                    $stmt_ = $accessib->readProbleme();
                                                    while ($row_ = $stmt_->fetch(PDO::FETCH_ASSOC)) {
                                                        echo "<option value=t_param_log_visite_pa.statut_accessibilite='" . $row_["code"] . "'>" . $row_["libelle"] .  "</option>";
                                                    }

                                                    $stmt_select = $province->getAllProvinces();
                                                    $provinces = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
                                                    foreach ($provinces as $province) {
                                                        $stmt_select = $commune->GetProvinceAllCommune($province['code']);
                                                        while ($row_select = $stmt_select->fetch(PDO::FETCH_ASSOC)) {
                                                            echo "<option value=e_commune.code='" . $row_select["code"] . "'>Commune - " . $row_select["libelle"] . "</option>";
                                                        }


                                                        $stmt_select = $commune->GetProvinceAllCVS($province['code']);
                                                        while ($row_select = $stmt_select->fetch(PDO::FETCH_ASSOC)) {
                                                            echo "<option value=t_param_cvs.code='" . $row_select["code"] . "'>CVS - " . $row_select["libelle"] . "</option>";
                                                        }
                                                    }

                                                    $stmt_select = $site->GetAll();
                                                    while ($row_select = $stmt_select->fetch(PDO::FETCH_ASSOC)) {
                                                        echo "<option value=t_param_log_visite_pa.site_id='" . $row_select["code"] . "'>Site - " . $row_select["libelle"] . "</option>";
                                                    }

                                                    /*
												if($utilisateur->id_service_group ==  '3'||$utilisateur->HasGlobalAccess()){  //Administration
													$stmt_ = $organisme->read();
															while ($row_gp = $stmt_->fetch(PDO::FETCH_ASSOC)) {
																echo "<option value=id_equipe_identification='{$row_gp["ref_organisme"]}'>Organisme - {$row_gp["denomination"]}</option>";
															}										
													}else{
														$organisme->ref_organisme = $utilisateur->id_organisme;
														$row_gp = $organisme->GetDetail();
														echo "<option value=id_equipe_identification='{$row_gp["ref_organisme"]}'>Organisme - {$row_gp["denomination"]}</option>";
													}*/

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


    <?php
    include_once "layout_script.php";
    include_once 'layout_map_viewer.php';  ?>

    <script src="assets/js/select2.min.js"></script>
    <script src="assets/js/mapviewer-script.js"></script>
    <script type="text/javascript">
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

        function displayRecords(numRecords, pageNum, v_mode) {
            var s = $('#srch-term').val() != null ? $('#srch-term').val() : null;
            var du = $('#Du').val() != null ? $('#Du').val() : null;
            var au = $('#Au').val() != null ? $('#Au').val() : null;
            var filtre = $('#filtre').val() != null ? $('#filtre').val() : null;
            $.ajax({
                type: "GET",
                url: "controller_visit.php",
                data: "view=search_view_log_visit&show=" + numRecords + "&page=" + pageNum + "&Du=" + du + "&Au=" + au + "&s=" + s + "&view_mode=" + v_mode + "&filtre=" + filtre,
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
        $(document).ready(function() {


            $('#filtre').select2({
                placeholder: "Filtre",
                multiple: true
            });

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