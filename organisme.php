<?php
// session_start();
$mnu_title = "Organisme";
$page_title = "Liste des Organismes";
$home_page = "index.php";
$active = "organisme";
$parambase = " active";

require_once 'vendor/autoload.php';
require_once 'loader/init.php';
Autoloader::Load('classes');
include_once "core.php";
/*
include_once "include/database_pdo.php";
include_once "classes/class.utilisateur.php";
include_once "classes/class.organisme.php";
include_once "classes/class.province.php";
include_once "classes/class.commune.php";*/

$database = new Database();
$db = $database->getConnection();
$item = new Organisme($db);
$province = new AdresseEntity($db);
$commune = new AdresseEntity($db);
$quartier = new AdresseEntity($db);

$utilisateur = new Utilisateur($db);
if ($utilisateur->is_logged_in() == false) {
    $utilisateur->redirect('login.php');
}
$utilisateur->readOne();
/* if($utilisateur->is_logged_in()=="")
  {
  $utilisateur->redirect("login.php");
  }
  $utilisateur->code_utilisateur=$_SESSION["uSession"];
  $utilisateur->readOne(); */
$search_term = isset($_GET["s"]) ? $_GET["s"] : "";
$stmt = null;
$page_url = "organisme.php?";

// calculate for the query LIMIT clause
$from_record_num = ($records_per_page * $page) - $records_per_page;
if ($search_term == "") {
    $stmt = $item->readAll($from_record_num, $records_per_page);
    $total_rows = $item->countAll();
} else {
    $page_url .= "s={$search_term}&";
    $stmt = $item->search($search_term, $from_record_num, $records_per_page);
    $total_rows = $item->countAll_BySearch($search_term);
}
$search_value = isset($search_term) ? "value='{$search_term}'" : "";
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
    <?php
    include_once "layout_style.php";
    ?>
</head>

<body>
    <div class="dashboard-main-wrapper">
        <?php
        include_once "layout_top_bar.php";
        include_once "layout_side_bar.php";
        ?>
        <div class="dashboard-wrapper">
            <div class="container-fluid  dashboard-content">
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">

                            <div class="page-header">
                                <!--  <h2 class="pageheader-title"><?php echo $mnu_title; ?></h2>  -->
                                <div id="breadcrumbs" class="clearfix">
                                    <a href="<?php echo 'organisme.php'; ?>" class="breadcrumbs_home"><i class='fas fa-folder-open nav_icon'></i> <?php echo $mnu_title; ?></a> <span class="raquo">»</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 mb-2">
                                        <label for="validationCustom03"><?php echo $page_title; ?></label>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 ">
                                        <form method="get" role="search" id="frm_search">
                                            <div class="input-group mb-3">
                                                <input type="text" id="srch-term" name="s" class="form-control" placeholder="Recherche..." required <?php echo $search_value; ?>>
                                                <button type="submit" name="search" id="search-btn" class="btn btn-primary"><i class="fa fa-search"></i>
                                                </button>
                                                <a class="btn btn-outline-light float-right" href="organisme.php">Voir tout</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width:5%;"></th>
                                            <th>Dénomination</th>
                                            <th>Adresse</th>
                                            <th>Contact</th>
                                            <th>Téléphone</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($utilisateur->HasDroits("10_518")) {
                                            $num_line = 0;
                                            while ($row_ = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                $num_line++;
                                                // echo "<tr><td>" . $num_line . "</td>";
                                                echo '<td>
 											<button type="button" data-toggle="dropdown" class="btn btn-secondary dropdown-toggle" aria-expanded="false"></button>
 								<ul class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(705px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">';
                                                //if($utilisateur->HasDroits("12_39")){		
                                                echo '<a href="#" class="dropdown-item edit"  data-id="' . $row_["ref_organisme"] . '">Modifier</a>';
                                                //}	
                                                //if($utilisateur->HasDroits("12_40")){		
                                                echo '<a href="#" class="dropdown-item delete" data-name="' . $row_["denomination"] . '" data-id="' . $row_["ref_organisme"] . '">Supprimer</a>';
                                                //}
                                                echo '</ul></td>';
                                                echo "<td>" . $row_["denomination"] . "</td>";
                                                echo "<td>" . $row_["adresse"] . "</td>";
                                                echo "<td>" . $row_["contact_organisme"] . "</td>";
                                                echo "<td>" . $row_["phone"] . "</td>";
                                        ?>
                                                </tr>
                                        <?php }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div class="clearfix">
                                    <?php
                                    include_once "layout_paging.php";
                                    ?>
                                </div>
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
    //if($utilisateur->HasDroits("12_38")){ 
    echo '<div class="btn-group-fab" role="group" aria-label="FAB Menu">
 	  <div>
 		<button type="button" class="btn btn-main btn-primary has-tooltip" data-placement="left" title="Menu" id="btn_new_"> <i class="fa fa-plus"></i> </button>
 	  </div>
 	</div>';
    //}
    include_once "layout_script.php";
    include_once "layout_loader.php";
    ?>
    <div class="modal fade" id="dlg_main" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="titre"></h5>
                    <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </a>
                </div>
                <div class="modal-body">
                    <form id="mainForm" method="post" action="controller.php" enctype="multipart/form-data">
                        <input name="ref_organisme" id="ref_organisme" type="hidden">
                        <input name="view" id="view" type="hidden">
                        <div class="form-group">
                            <label>Dénomination</label>
                            <div class="input-group" style="width: 100%;">
                                <input type="text" class="form-control pull-right" name="denomination" id="denomination">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Province</label>
                            <div class="input-group" style="width: 100%;">
                                <select class="form-control select2" name="code_province" id="code_province" style="width: 100%;">
                                    <option selected='selected' disabled>Veuillez choisir</option>
                                    <?php
                                    $stmt_select = $province->read('3');
                                    while ($row_select = $stmt_select->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value=" . $row_select["code"] . ">{$row_select["libelle"]}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Ville</label>
                            <div class="input-group" style="width: 100%;">
                                <select class="form-control select2" name="id_ville" id="id_ville" style="width: 100%;">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Commune</label>
                            <div class="input-group" style="width: 100%;">
                                <select class="form-control select2" name="id_commune" id="id_commune" style="width: 100%;">
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Quartier</label>
                            <div class="input-group" style="width: 100%;">
                                <select class="form-control select2" name="id_quartier" id="id_quartier" style="width: 100%;">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Avenue et Numéro</label>
                            <div class="input-group" style="width: 100%;">
                                <input type="text" class="form-control pull-right" name="adresse" id="adresse">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Contact</label>
                            <div class="input-group" style="width: 100%;">
                                <input type="text" class="form-control pull-right" name="contact_organisme" id="contact_organisme">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Téléphone</label>
                            <div class="input-group" style="width: 100%;">
                                <input type="text" class="form-control pull-right" name="phone" id="phone">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name='is_blue_energy' id='is_blue_energy' /><span class="custom-control-label">C'est Blue-Energy</span>
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

    <script src="assets/js/select2.min.js"></script>
    <script>
        $(function() {
            var actual_ville = "";
            var actual_commune = "";
            var actual_quartier = "";
            var load_ville = false;
            var load_commune = false;
            var load_quartier = false;

            function modalbox_scroll() {
                $('.modal').on("hidden.bs.modal", function(e) { //fire on closing modal box scroll issue hack
                    if ($('.modal:visible').length) {
                        $('body').addClass('modal-open');
                    }
                });
            }



            modalbox_scroll();
            $("#dlg_main .select2").each(function() {
                var $sel = $(this).parent();
                $(this).select2({
                    dropdownParent: $sel
                });
            });
            <?php //if($utilisateur->HasDroits("12_40")){   
            ?>
            $(".delete").click(function() {
                var name_actuel = jQuery(this).attr("data-name");
                var jeton_actuel = jQuery(this).attr("data-id");
                swal({
                    title: "Information",
                    text: "Voulez-vous supprimer  (" + name_actuel + ")?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#00A65A",
                    confirmButtonText: "Oui",
                    cancelButtonText: "Non",
                    closeOnConfirm: false,
                    closeOnCancel: true
                }, function(isConfirm) {
                    if (isConfirm) {
                        var view_mode = "delete_organisme";
                        $.ajax({
                            url: "controller.php",
                            method: "POST",
                            data: {
                                view: view_mode,
                                ref_organisme: jeton_actuel
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
            <?php //}  
            ?>
            <?php //if($utilisateur->HasDroits("12_38")){   
            ?>
            $("#btn_new_").click(function() {

                ClearForm();

                $("#mainForm #view").val("create_organisme");
                $("#titre").html("CREATION ORGANISME");
                $("#dlg_main").modal("show");
            });
            <?php //}  
            ?>

            function ClearForm() {
                load_ville = false;
                load_commune = false;
                load_quartier = false;
                $("#view").val("");
                $("#ref_organisme").val("");
                $("#denomination").val("");
                $("#adresse").val("");
                $("#contact_organisme").val("");
                $("#id_quartier").val("").change();
                $("#phone").val("");
                $("#code_province").val("").change();
                $("#id_commune").val("").change();
                $("#is_blue_energy").prop('checked', false);
                load_ville = true;
            }
            $("#btn_save_").click(function() {
                var form = document.getElementById("mainForm");
                var formData = new FormData(form);
                $.ajax({
                    url: "controller.php",
                    method: "POST",
                    dataType: "json",
                    data: formData,
                    contentType: false,
                    processData: false,
                    cache: false,
                    success: function(result, statut) {
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
                                    ClearForm();
                                    $("#dlg_main").modal("hide");
                                    window.location.reload();
                                });
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
            <?php //if($utilisateur->HasDroits("12_39")){   
            ?>
            $(".edit").click(function() {
                ClearForm();
                var jeton_actuel = jQuery(this).attr("data-id");
                $("#titre").html("MODIFICATION INFORMATIONS ORGANISME");
                $.ajax({
                    url: "controller.php",
                    method: "GET",
                    dataType: "json",
                    data: {
                        view: "detail_organisme",
                        ref_organisme: jeton_actuel
                    },
                    success: function(result, statut) {
                        $("#mainForm #view").val("edit_organisme");
                        $("#ref_organisme").val(result.data.ref_organisme);
                        $("#ref_organisme").val(result.data.ref_organisme);
                        $("#denomination").val(result.data.denomination);
                        $("#adresse").val(result.data.adresse);
                        //$("#id_quartier").val(result.data.id_quartier);
                        $("#contact_organisme").val(result.data.contact_organisme);
                        $("#phone").val(result.data.phone);
                        $("#code_province").val(result.data.id_province).change();
                        actual_ville = result.data.id_ville;
                        actual_commune = result.data.id_commune;
                        actual_quartier = result.data.id_quartier;
                        //$("#id_commune").val(result.data.id_commune).change();
                        $("#is_blue_energy").prop('checked', result.data.is_blue_energy == "on" ? true : false);
                        modalbox_scroll();
                        $("#dlg_main").modal("show");
                    },
                    error: function(resultat, statut, erreur) {}
                });
            });
            <?php //}   
            ?>


            $("#code_province").on("change", function(e) {
                var item = $(this).val();
                load_commune = false;
                load_quartier = false;
                if (load_ville == false || item != null && item.length == 0) {
                    return false;
                }
                e.preventDefault();
                $("#loading_msg").html("Chargement liste des communes en cours...");
                $("#loadMe").modal({
                    backdrop: "static",
                    keyboard: false,
                    show: true
                });
                $("#id_ville").html('');
                $("#id_commune").html('');
                $("#id_quartier").html('');
                $.ajax({
                    url: "controller.php",
                    method: "GET",
                    data: {
                        view: "get_province_ville",
                        id_: item
                    },
                    success: function(data, statut) {
                        /*  $("#loadMe").modal("hide").on('hidden.bs.modal', functionThatEndsUpDestroyingTheDOM);*/

                        $('#loadMe').modal('hide');
                        //$('#loadMe').hide();
                        //$('#loadMe').attr('aria-hidden',"true");
                        //$('.modal-backdrop').hide();
                        //$('body').removeClass('modal-open');
                        //  try {
                        var result = $.parseJSON(data);
                        if (result.error == 0) {
                            $("#id_ville").html(result.data);
                            load_commune = true;
                            if (actual_ville != "") {
                                $("#id_ville").val(actual_ville).change();
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
                        /*  } catch (erreur) {
                              swal({
                                  title: "Information",
                                  text: "Echec d'execution de la requete",
                                  type: "error",
                                  showCancelButton: false,
                                  confirmButtonColor: "#DD6B55",
                                  confirmButtonText: "Ok",
                                  closeOnConfirm: true,
                                  closeOnCancel: false
                              }, function(isConfirm) {
                              });
                          }*/
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


            $("#id_ville").on("change", function(e) {
                var item = $(this).val();
                load_quartier = false;
                if (load_commune == false || item != null && item.length == 0) {
                    return false;
                }
                e.preventDefault();
                $("#loading_msg").html("Chargement liste des communes en cours...");
                $("#loadMe").modal({
                    backdrop: "static",
                    keyboard: false,
                    show: true
                });
                $("#id_commune").html('');
                $.ajax({
                    url: "controller.php",
                    method: "GET",
                    data: {
                        view: "get_ville_commune",
                        id_: item
                    },
                    success: function(data, statut) {
                        /*  $("#loadMe").modal("hide").on('hidden.bs.modal', functionThatEndsUpDestroyingTheDOM);*/

                        $('#loadMe').modal('hide');
                        //$('#loadMe').hide();
                        //$('#loadMe').attr('aria-hidden',"true");
                        //$('.modal-backdrop').hide();
                        //$('body').removeClass('modal-open');
                        try {
                            var result = $.parseJSON(data);
                            if (result.error == 0) {
                                $("#id_commune").html(result.data);
                                load_quartier = true;
                                if (actual_commune != "") {
                                    $("#id_commune").val(actual_commune).change();
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


            $("#id_commune").on("change", function(e) {
                var item = $(this).val();
                if (load_quartier == false || item != null && item.length == 0) {
                    return false;
                }
                e.preventDefault();
                $("#loading_msg").html("Chargement liste des quartiers en cours...");
                $("#loadMe").modal({
                    backdrop: "static",
                    keyboard: false,
                    show: true
                });
                $("#id_quartier").html('');
                $.ajax({
                    url: "controller.php",
                    method: "GET",
                    data: {
                        view: "get_commune_quartier",
                        id_: item
                    },
                    success: function(data, statut) {
                        /*  $("#loadMe").modal("hide").on('hidden.bs.modal', functionThatEndsUpDestroyingTheDOM);*/

                        $('#loadMe').modal('hide');
                        //$('#loadMe').hide();
                        //$('#loadMe').attr('aria-hidden',"true");
                        //$('.modal-backdrop').hide();
                        //$('body').removeClass('modal-open');
                        try {
                            var result = $.parseJSON(data);
                            if (result.error == 0) {
                                $("#id_quartier").html(result.data);
                                if (actual_quartier != "") {
                                    $("#id_quartier").val(actual_quartier).change();
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