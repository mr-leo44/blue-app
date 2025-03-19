<?php
// session_start();
$mnu_title = "Ville";
$page_title = "Liste des Villes";
$home_page = "index.php";
$active = "ville";
$parambase = " active";
$parent_id = "province_id";
$parent_label = "province";
require_once 'vendor/autoload.php';
require_once 'loader/init.php';
//loading Classes filess
Autoloader::Load('classes');

include_once "core.php";
/*include_once "include/database_pdo.php";
 include_once "classes/class.utilisateur.php";
 include_once "classes/class.commune.php";
 include_once "classes/class.province.php";*/
$database = new Database();
$db = $database->getConnection();
$adressEntity = new AdresseEntity($db);
$utilisateur = new Utilisateur($db);


if ($utilisateur->is_logged_in() == false) {
  $utilisateur->redirect('login.php');
}
$utilisateur->readOne();
/*if($utilisateur->is_logged_in()=="")
 {
 	$utilisateur->redirect("login.php");
 }
 $utilisateur->code_utilisateur=$_SESSION["uSession"];
 $utilisateur->readOne();*/



$category_id = '4'; //Avenue
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
          <!-- ============================================================== -->
          <!-- Localisation form -->
          <!-- ============================================================== -->
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
              <h5 class="card-header"><?php echo $page_title; ?></h5>
              <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                  <div class="card">

                    <div class="card-body">
                      <form id="mainForm" method="post" action="controller.php" enctype="multipart/form-data">
                        <div class="form-group">
                          <label>Province</label>
                          <select class='form-control select2' style='width: 100%;' name='province_id' id='province_id' required>
                            <option selected='selected' value=''> </option>
                            <?php
                            $stmt_select = $adressEntity->read('3');

                            while ($row_select = $stmt_select->fetch(PDO::FETCH_ASSOC)) {
                              echo "<option value=" . $row_select["code"] . ">{$row_select["libelle"]}</option>";
                            }
                            ?>
                          </select>
                        </div>

                      </form>
                    </div>

                  </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12">
                  <div class="card">
                    <div class="card-body">

                      <div class="card bg-white font-semi-bold mt-3 mb-4">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-sm-12">
                              <input type="text" id="srch-term-entity" name="s" class="form-control" placeholder="Recherche...">
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="table-responsive table-bordered table-hover" style="height:350px;">
                          <table class="table no-wrap p-table lignes_install ui-sortable" id="lignes_install">
                            <thead>
                              <tr>
                                <th style="width:95%"><?php echo $mnu_title; ?></th>
                                <th><a class="btn btn-xs " id="add_line"><i class="fas fa-plus"></i></a></th>
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
          <!-- end Localisation form -->
          <!-- ============================================================== -->

        </div>
      </div>
      <?php
      //include_once "layout_footer.php";
      ?>
    </div>
  </div>
  <?php


  //include_once "layout_loader.php";
  include_once "layout_script.php";
  ?>
  <div class="modal fade" id="dlg_main" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="dlg_title"></h5>
          <a href="#" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </a>
        </div>
        <div class="modal-body">
          <form id="dlg_frm" method="post" action="controller.php" enctype="multipart/form-data">
            <input name="code" id="code" type="hidden">
            <input name="view" id="view" type="hidden">
            <input name="parent_id" id="parent_id" type="hidden">


            <div class="form-group">
              <label><?php echo  $mnu_title; ?></label>
              <div class="input-group" style="width: 100%;">
                <input type="text" class="form-control pull-right" name="libelle" id="libelle">
              </div>
            </div>

            <div class="modal-footer ">
              <button type="button" class="btn btn-primary btn-lg" id="btn_save_"><span class="glyphicon glyphicon-ok-sign"></span> Valider</button>
            </div>
          </form>
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
  <script src="assets/js/select2.min.js"></script>
  <script>
    $(function() {

      var actual_avenue = "";
      var actual_commune = "";
      var actual_quartier = "";
      var load_ville = false;
      var load_commune = false;
      var load_quartier = false;

      $("#mainForm .select2").each(function() {
        var $sel = $(this).parent();
        $(this).select2({
          dropdownParent: $sel
        });
      });

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
      <?php //if($utilisateur->HasDroits("12_40")){  
      ?>
      jQuery(document).delegate('a.delete-item', 'click', function(e) {
        e.preventDefault();
        var itemId = $(this).parents('tr.item-row').attr('data-id');
        var label = $('tr.item-row[data-id="' + itemId + '"]').find('span.sn').html();

        swal({
          title: "Information",
          text: "Voulez-vous supprimer la <?php echo $active; ?> (" + label + ")?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#00A65A",
          confirmButtonText: "Oui",
          cancelButtonText: "Non",
          closeOnConfirm: true,
          closeOnCancel: true
        }, function(isConfirm) {
          if (isConfirm) {
            var view_mode = "delete_ville";

            $("#loading_msg").html("Suppresion en cours...");
            $("#loadMe").modal({
              backdrop: "static",
              keyboard: false,
              show: true
            });
            $.ajax({
              url: "controller.php",
              method: "POST",
              data: {
                view: view_mode,
                code: itemId
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
                    $("#province_id").change();
                  });
                } else if (result.error == 1) {
                  swal("Information", result.message, "error");
                }
              },
              complete: function() {
                $("#loadMe").modal("hide");
              }
            });
          }
        });
      });
      <?php //} 
      ?>


      $("#btn_save_").click(function() {
        var form = document.getElementById("dlg_frm");
        var formData = new FormData(form);
        $("#loadMe").modal({
          backdrop: "static",
          keyboard: false,
          show: true
        });
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
                  //ClearForm();
                  $("#dlg_main").modal("hide");
                  $("#<?php echo $parent_id; ?>").change();
                  //window.location.reload();
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
          },
          complete: function() {
            $("#loadMe").modal("hide");
          }
        });
      });

      <?php //if($utilisateur->HasDroits("12_38")){  
      ?> $("#add_line").click(function() {
        ClearR();
        var parent_id = $('#<?php echo $parent_id; ?>').val() != null ? $('#<?php echo $parent_id; ?>').val() : '';
        if (parent_id == '') {
          swal("Information", "Veuillez préciser la <?php echo $parent_label; ?>", "error");
          return false;
        }
        $('#parent_id').val(parent_id);
        $("#dlg_frm #view").val("create_ville");
        $("#dlg_title").html("Création <?php echo $mnu_title; ?>");

        $("#loading_msg").html("Création en cours...");
        $("#dlg_main").modal("show");
      });
      <?php //} 
      ?>
      <?php //if($utilisateur->HasDroits("12_39")){  
      ?>

      jQuery(document).delegate('a.edit-item', 'click', function(e) {
        e.preventDefault();
        var itemId = $(this).parents('tr.item-row').attr('data-id');
        var parent_id = $(this).parents('tr.item-row').attr('parent-id');
        var label = $('tr.item-row[data-id="' + itemId + '"]').find('span.sn').html();
        $("#dlg_main #view").val("edit_ville");
        $('#dlg_title').html('Modification <?php echo $mnu_title; ?>');
        $('#code').val(itemId);
        $('#libelle').val(label);
        $('#parent_id').val(parent_id);
        $("#loading_msg").html("Modification en cours...");

        $('#dlg_main').modal('show');

      });

      <?php //}  
      ?>

      $("#srch-term-entity").keyup(function() {
        var val = $(this).val().toString().toLowerCase();
        $('#lignes_install').find('.item-row').each(function(i) {
          var row = $(this);
          var client_label = row.find('span.sn').html().toString().toLowerCase();


          if (client_label.indexOf(val) != -1) {
            row.show();
            //return false;
          } else row.hide();
        });

        if (!val)
          $('#lignes_install').find('.item-row').each(function(i) {
            $(this).show();
          });
      });

      function ClearR() {
        $('#srch-term-entity').val('');
        $('#parent_id').val('');
        $('#code').val('');
        $('#libelle').val('');
      }

      function ClearRows() {
        $('#lignes_install tbody tr').each(function(index) {
          var itemId = $(this).attr('data-id');
          ctr = 0;
          $('tr.item-row[data-id="' + itemId + '"]').remove();
        });
      }
      $("#province_id").on("change", function(e) {
        var item = $(this).val();
        load_commune = false;
        load_quartier = false;
        //ClearRows();
        $('#lignes_install tbody').html('');
        if (item != null && item.length == 0) {
          return false;
        }
        e.preventDefault();
        $("#loading_msg").html("Chargement liste des villes/territoires en cours...");
        $("#loadMe").modal({
          backdrop: "static",
          keyboard: false,
          show: true
        });
        $.ajax({
          url: "controller.php",
          method: "GET",
          data: {
            view: "get_adress_json",
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
              if (result.count > 0) {
                var lignes_install = $('#lignes_install tbody');

                $.each(result.data, function(k, it) {
                  lignes_install.append('<tr class="item-row" data-id="' + it.code + '" parent-id="' + item + '"><td style="width:95%"><span class="sn">' + it.libelle + '</span></td><td><a class="btn btn-xs edit-item"><i class="fas fa-pencil-alt"></i></a><a class="btn btn-xs delete-item"><i class="fas fa-trash"></i></a></td></tr>');

                });
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
            //	
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
            $("#loadMe").modal("hide");
          }
        });
      });







    })
  </script>
</body>

</html>