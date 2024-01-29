<?php
// session_start();
$mnu_title = "Utilisateurs";
$page_title = "Liste des Utilisateurs";
$home_page = "dashboard.php";
$active = "users";
$parambase = "";

require_once 'vendor/autoload.php';
require_once 'loader/init.php';
Autoloader::Load('classes');
include_once 'core.php';
header('Content-type: text/html;charset=utf-8');
$database = new Database();
$db = $database->getConnection();
$site = new Site($db);
$etat = new Statut($db);
$groupe = new GroupUtilisateur($db);
$organisme = new Organisme($db);
$utilisateur = new Utilisateur($db);
if ($utilisateur->is_logged_in() == false) {
    $utilisateur->redirect('login.php');
}
$utilisateur->readOne();
/* $utilisateur->code_utilisateur=$_SESSION['uSession'];
  $utilisateur->readOne();
  if($utilisateur->is_logged_in()=="")
  {
  $utilisateur->redirect('login.php');
  } */
$search_term = isset($_GET['s']) ? $_GET['s'] : '';
$stmt = null;
$page_url = 'users.php?';

// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;
if ($search_term == '') {
    $stmt = $utilisateur->readAll($from_record_num, $records_per_page);
    $total_rows = $utilisateur->countAll();
} else {
    $page_url .= "s={$search_term}&";
    $stmt = $utilisateur->search($search_term, $from_record_num, $records_per_page);
    $total_rows = $utilisateur->countAll_BySearch($search_term);
}
$search_value = isset($search_term) ? "value='{$search_term}'" : "";
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
    </style>
    <link href="assets/css/select2.css" rel="stylesheet">
    <link href="assets/css/loader.css" rel="stylesheet">
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
                                <a href="<?php echo 'users.php'; ?>" class="breadcrumbs_home"><i class='fas fa-user nav_icon'></i> <?php echo $mnu_title; ?></a> <span class="raquo">»</span>
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
                                                <input type="text" id="srch-term" name='s' class="form-control" placeholder="Recherche..." required <?php echo $search_value; ?>>
                                                <button type="submit" name="search" id="search-btn" class="btn btn-primary"><i class="fa fa-search"></i>
                                                </button>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <table class="table table-responsive table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width:5%;"></th>
                                            <th>Pseudo</th>
                                            <th>Noms</th>
                                            <th>Statut</th>
                                            <th>Groupe</th>
                                            <th>Site</th>
                                            <th>Organisme</th>
                                            <th>Est chef</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($utilisateur->HasDroits("10_160")) {
                                            $num_line = 0;
                                            while ($row_ = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                // $num_line++;
                                                echo ' <tr>';

                                                echo '<td> 					 
														<button type="button" data-toggle="dropdown" class="btn btn-secondary dropdown-toggle" aria-expanded="false"></button>
											<ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(705px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">';

                                                if ($utilisateur->HasDroits("10_180")) {
                                                    echo '<a href="#" class="dropdown-item edit"  data-id="' . $row_["code_utilisateur"] . '">Modifier</a>';
                                                }
                                                if ($utilisateur->HasDroits("10_170")) {
                                                    echo '<a href="#" class="dropdown-item delete" data-name="' . $row_["nom_complet"] . '" data-id="' . $row_["code_utilisateur"] . '">Supprimer</a>';
                                                }
                                                if ($utilisateur->HasDroits("12_57")) {
                                                    echo '<a href="#" class="dropdown-item reset-pwd" data-name="' . $row_["nom_complet"] . '" data-id="' . $row_["code_utilisateur"] . '">Ré-initialiser mot de passe</a>';
                                                }

                                                //if($utilisateur->HasDroits("12_55")){	
                                                echo '<div class="dropdown-divider"></div><a href="#" class="dropdown-item law" data-name="' . $row_["nom_complet"] . '" data-id="' . $row_["code_utilisateur"] . '">Sites accessibles</a>';
                                                //}
                                                echo ' </ul>';
                                                echo '</td>';

                                                echo '<td>' . $row_["nom_utilisateur"] . '</td>
													<td>' . $row_["nom_complet"] . '</td>';

                                                $etat->code = $row_["activated"];
                                                $etat->GetDetail();
                                                echo '<td>' . ($etat->libelle) . '</td>';

                                                //	$groupe->id_group=$row_["id_group"];
                                                //$r=$groupe->GetDetail();	
                                                echo '<td>' . ($row_['intitule']) . '</td>';

                                                $site->code_site = $row_["site_id"];
                                                $r = $site->GetDetail();
                                                $organisme->ref_organisme = $row_["id_organisme"];
                                                $organe = $organisme->GetDetail();
                                                echo '<td>' . ($r['intitule_site']) . '</td>';
                                                echo '<td>' . ($organe['denomination']) . '</td>';
                                                echo '<td>' . Utils::getIs_ActiveLabelDigit($row_['is_chief']) . '</td>';

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
    if ($utilisateur->HasDroits("10_190")) {
        echo '<div class="btn-group-fab" role="group" aria-label="FAB Menu">
	  <div>
		<button type="button" class="btn btn-main btn-primary has-tooltip" data-placement="left" title="Menu" id="btn_new_"> <i class="fa fa-plus"></i> </button>
	  </div>
	</div>';
    }
    include_once "layout_script.php";
    include_once "layout_loader.php";
    ?>
    <div class="modal fade" id="dlg_main" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true" data-backdrop="static" style="overflow: scroll;">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="titre"></h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <form id="mainForm" method="post">
                        <input id="ref_id" type="hidden">
                        <input id="view_mode_" type="hidden">
                        <div class="form-group">
                            <label>Pseudo *</label>
                            <div class="input-group" style="width: 100%;">
                                <input type="text" class="form-control pull-right" id="k" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nom complet *</label>
                            <div class="input-group" style="width: 100%;">
                                <input type="text" class="form-control pull-right" id="nk" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Adresse mail:</label>
                            <div class="input-group" style="width: 100%;">
                                <input type="text" class="form-control pull-right" id="email_user">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <div class="input-group" style="width: 100%;">
                                <input type="text" class="form-control pull-right" id="phone_user">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" checked="" class="custom-control-input" id='etat' /><span class="custom-control-label">Activer</span>
                            </label>
                        </div>

                        <div class="form-group">
                            <label>Groupe *</label>
                            <select class='form-control select2' style='width: 100%;' id='gp' required>
                                <option selected='selected' disabled>Veuillez préciser le groupe</option>
                                <?php
                                $stmt_select_gp = $groupe->read();

                                while ($row_gp = $stmt_select_gp->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='{$row_gp["id_group"]}'>{$row_gp["intitule"]}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Organisme Utilisateur *</label>
                            <select class='form-control select2' style='width: 100%;' id='id_organisme' required>
                                <option selected='selected' disabled>Veuillez préciser</option>
                                <?php
                                $stmt_select_st = $organisme->read();
                                while ($row_gp = $stmt_select_st->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='{$row_gp["ref_organisme"]}'>{$row_gp["denomination"]}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Site d'affectation par défaut *</label>
                            <select class='form-control select2' style='width: 100%;' id='site' required>
                                <option selected='selected' disabled>Veuillez préciser le site</option>
                                <?php
                                $stmt_select_st = $site->read();
                                while ($row_gp = $stmt_select_st->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='{$row_gp["code_site"]}'>{$row_gp["intitule_site"]}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Organisme Chef</label>
                            <select class='form-control select2' style='width: 100%;' id='id_organisme_chief' required>
                                <option selected='selected' value="">Veuillez préciser</option>
                                <?php
                                $stmt_select_st = $organisme->read();
                                while ($row_gp = $stmt_select_st->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='{$row_gp["ref_organisme"]}'>{$row_gp["denomination"]}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Chef d'équipe</label>
                            <select class='form-control select2' style='width: 100%;' id='chef_equipe_id'> </select>
                        </div>

                        <div class="form-group">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name='is_chief' id='is_chief' /><span class="custom-control-label">Est Chef</span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name='access_au_module_deux' id='access_au_module_deux' /><span class="custom-control-label">Accès Capture 2</span>
                            </label>
                        </div>
                        <div class="modal-footer ">
                            <button type="button" class="btn btn-primary btn-lg" id="btn_save_"><span class="glyphicon glyphicon-ok-sign"></span> Valider</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="dlg_site" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 id="title_law" class="h4 mb-1">Sites Accessibles</h2>
                    <p class="small text-muted font-italic mb-4">Veuillez cocher les sites accessibles à l'utilisateur.</p>
                </div>
                <div class="modal-body">
                    <form <?php //if($utilisateur->HasDroits("12_56")){  
                            ?> action="controller.php" method="post" id="frm_law" <?php //}  
                                                                                                                                ?>>
                        <?php //if($utilisateur->HasDroits("12_56")){  
                        ?><input type="hidden" name="view" id="view" value="grant_site" /><?php //}  
                                                                                                                                            ?>
                        <input type="hidden" name="k_m" id="view_id" value="" />

                        <ul class="list-group" id="search_results">

                        </ul>


                        <?php //	if($utilisateur->HasDroits("12_56")){   
                        ?>
                        <div class="modal-footer ">
                            <button type="submit" class="btn btn-primary btn-lg" id="btn_save_law"><span class="glyphicon glyphicon-ok-sign"></span> Valider</button>
                        </div><?php //} 
                                ?>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="assets/js/select2.min.js"></script>
    <script src="assets/js/parsley.js"></script>
    <script>
        $(function() {
            var actual_chief = "";
            var load_chief = false;
            $('#dlg_main .select2').each(function() {
                var $sel = $(this).parent();
                $(this).select2({
                    dropdownParent: $sel
                });
            });


            function modalbox_scroll() {
                $('.modal').on("hidden.bs.modal", function(e) { //fire on closing modal box scroll issue hack
                    if ($('.modal:visible').length) {
                        $('body').addClass('modal-open');
                    }
                });
            }

            modalbox_scroll();
            // $('has-tooltip').tooltip();

            <?php if ($utilisateur->HasDroits("10_170")) {
            ?> $('.delete').click(function() {
                    var name_actuel = jQuery(this).attr("data-name");
                    var jeton_actuel = jQuery(this).attr("data-id");
                    swal({
                        title: "Information",
                        text: 'Voulez-vous supprimer l\'utilisateur (' + name_actuel + ')?',
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#00A65A",
                        confirmButtonText: "Oui",
                        cancelButtonText: "Non",
                        closeOnConfirm: false,
                        closeOnCancel: true
                    }, function(isConfirm) {
                        if (isConfirm) {
                            var view_mode = "delete_user";
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


            <?php if ($utilisateur->HasDroits("12_57")) {
            ?> $('.reset-pwd').click(function() {
                    var name_actuel = jQuery(this).attr("data-name");
                    var jeton_actuel = jQuery(this).attr("data-id");
                    swal({
                        title: "Information",
                        text: 'Voulez-vous ré-initialiser le mot de passe de  l\'utilisateur (' + name_actuel + ')?',
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#00A65A",
                        confirmButtonText: "Oui",
                        cancelButtonText: "Non",
                        closeOnConfirm: false,
                        closeOnCancel: true
                    }, function(isConfirm) {
                        if (isConfirm) {
                            var view_mode = "reset_user";
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

            <?php if ($utilisateur->HasDroits("10_190")) {
            ?> $('#btn_new_').click(function() {
                    ClearForm();
                    $('#view_mode_').val("create_user");
                    $('#titre').html('CREATION UTILISATEUR');
                    $('#dlg_main').modal('show');
                });
            <?php } ?>

            function ClearForm() {
                load_chief = false;
                $("#view_mode_").val("");
                $("#ref_id").val("");
                $("#k").val("");
                $("#nk").val("");
                $("#gp").val("");
                $("#etat").prop('checked', false);
                $("#is_chief").prop('checked', false);
                $("#access_au_module_deux").prop('checked', false);
                $("#site").val("").change();
                $("#id_organisme").val("").change();
                $("#id_organisme_chief").val("").change();
                $("#email_user").val("");
                $("#phone_user").val("");
                load_chief = true;
            }
            $('#btn_save_').click(function() {
                var z_ = $("#ref_id").val();
                var view_mode = $("#view_mode_").val();
                var k = $("#k").val();
                var nk = $("#nk").val();
                var gp = $("#gp").val();
                var etat = $("#etat").prop('checked') ? "1" : "0";
                var is_chief = $("#is_chief").prop('checked') ? "1" : "0";
                var access_au_module_deux = $("#access_au_module_deux").prop('checked') ? "1" : "0";
                var site = $("#site").val();
                var email_user = $("#email_user").val();
                var phone_user = $("#phone_user").val();
                var id_organisme = $("#id_organisme").val();
                var id_organisme_chief = $("#id_organisme_chief").val();
                var chef_equipe_id = $("#chef_equipe_id").val();

                var frm = $("#mainForm");
                if (frm.parsley().validate()) {
                    // alert("oui");				   
                } else {
                    // alert("non");
                    return false;
                }

                $.ajax({
                    url: "controller.php",
                    method: "POST",
                    data: {
                        view: view_mode,
                        z_: z_,
                        k: k,
                        nk: nk,
                        gp: gp,
                        etat: etat,
                        site: site,
                        is_chief: is_chief,
                        access_au_module_deux: access_au_module_deux,
                        email_user: email_user,
                        phone_user: phone_user,
                        id_organisme: id_organisme,
                        id_organisme_chief: id_organisme_chief,
                        chef_equipe_id: chef_equipe_id
                    },
                    success: function(data, statut) {
                        try {
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
                                    ClearForm();
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
                        //		$inputs.prop("disabled", false);
                    }
                });
            });
            <?php if ($utilisateur->HasDroits("10_180")) {
            ?>
                $('.edit').click(function() {

                    ClearForm();
                    var jeton_actuel = jQuery(this).attr("data-id");
                    $('#titre').html('MODIFICATION INFORMATIONS UTILISATEUR');
                    $.ajax({
                        url: "controller.php",
                        method: "GET",
                        data: {
                            view: 'detail_user',
                            k: jeton_actuel
                        },
                        success: function(data, statut) { // success est toujours en place, bien sûr !
                            var result = $.parseJSON(data);
                            $("#view_mode_").val("edit_user");
                            $("#ref_id").val(result.data.ref);
                            $("#k").val(result.data.k);
                            $("#nk").val(result.data.nk);
                            $("#etat").prop('checked', result.data.et == "1" ? true : false);
                            $("#is_chief").prop('checked', result.data.is_chief == "1" ? true : false);
                            $("#access_au_module_deux").prop('checked', result.data.access_au_module_deux == "1" ? true : false);
                            $("#gp").val(result.data.gp).change();
                            $("#site").val(result.data.site).change();
                            $("#phone_user").val(result.data.phone_user);
                            $("#email_user").val(result.data.email_user);
                            //$("#chef_equipe_id").val(result.data.chef_equipe_id).change();
                            actual_chief = result.data.chef_equipe_id;
                            $("#id_organisme_chief").val(result.data.id_organisme_chief).change();
                            $("#id_organisme").val(result.data.id_organisme).change();
                            // modalbox_scroll();
                            $('#dlg_main').modal('show');

                        },
                        error: function(resultat, statut, erreur) {
                            //		$inputs.prop("disabled", false);
                        }
                    });
                });
                <?php } ?>12_55
                $('.law').click(function() {
                    ClearForm();
                    var jeton_actuel = jQuery(this).attr("data-name");
                    var id_ = jQuery(this).attr("data-id");
                    var view_mode = "get_user_site";
                    $('#view_id').val(id_);
                    $('#title_law').html(jeton_actuel);
                    //
                    $.ajax({
                        url: "controller.php",
                        method: "GET",
                        data: {
                            view: view_mode,
                            q: id_
                        },
                        success: function(data, statut) {
                            var result = $.parseJSON(data);
                            $('#search_results').html('');
                            $('#search_results').append(result.data);
                            /*	if(result.length == 0){
                             $('#search_results').html('Pas de droits trouvés');
                             }*/
                            $('#dlg_site').modal('show');
                        },
                        error: function(resultat, statut, erreur) {}
                    });
                });


                $("#id_organisme_chief").on("change", function(e) {
                    var item = $(this).val();
                    if (load_chief == false) {
                        return false;
                    }
                    e.preventDefault();
                    $("#loading_msg").html("Chargement liste des chefs d'équipe en cours...");
                    $("#loadMe").modal({
                        backdrop: "static",
                        keyboard: false,
                        show: true
                    });
                    //modalbox_scroll();
                    $("#chef_equipe_id").html('');
                    $.ajax({
                        url: "controller.php",
                        method: "GET",
                        data: {
                            view: "get_organisme_chief",
                            id_: item
                        },
                        success: function(data, statut) {
                            /*  $("#loadMe").modal("hide").on('hidden.bs.modal', functionThatEndsUpDestroyingTheDOM);*/

                            $('#loadMe').modal('hide');
                            modalbox_scroll();
                            //$('#loadMe').hide();
                            //$('#loadMe').attr('aria-hidden',"true");
                            //$('.modal-backdrop').hide();
                            //$('body').removeClass('modal-open');
                            try {
                                var result = $.parseJSON(data);
                                if (result.error == 0) {
                                    $("#chef_equipe_id").html(result.data);
                                    if (actual_chief != "") {
                                        $("#chef_equipe_id").val(actual_chief).change();
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
                            }, function(isConfirm) {});
                        }
                    });
                });

        })
    </script>

</body>

</html>