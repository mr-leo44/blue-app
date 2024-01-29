<!-- ============================================================== -->
<!-- left sidebar -->
<!-- ============================================================== -->

<div class="nav-left-sidebar sidebar-dark" id="sidebar">
    <div class="menu-list">
        <nav class="navbar navbar-expand-lg navbar-light">
            <!--  <a class="d-xl-none d-lg-none" href="#"> <?php echo $APP_NAME; ?> </a> -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav flex-column mt-4">

                    <!-- <li class="nav-divider">
                        SITE : 
                    </li> -->
                    <?php //if($utilisateur->HasDroits("10_40")){ 
                    ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $active == "dashboard" ? " active" : ""; ?>" href="accueil.php<?php echo $is_mobile; ?>"><i class='fas fa-home nav_icon'></i> <span class="nav_name">Accueil</span></a>
                    </li>
                    <?php //}  
                    ?>
                    <?php if ($utilisateur->HasDroits("10_40")) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active == "abonnes" ? " active" : ""; ?>" href="lst_identifs.php<?php echo $is_mobile; ?>"><i class='fas fa-folder-open nav_icon'></i> <span class="nav_name">Identification</span></a>
                        </li>
                    <?php } ?>
                    <?php if ($utilisateur->HasDroits("10_90")) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active == "lst_install" ? " active" : ""; ?>" href="lst_install.php<?php echo $is_mobile; ?>"><i class='fas fa-handshake nav_icon'></i> Installations</a>
                        </li><?php } ?>
                    <?php if ($utilisateur->HasDroits("10_140")) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active == "lst_control" ? " active" : ""; ?>" href="lst_control.php<?php echo $is_mobile; ?>"><i class='fas fa-edit nav_icon'></i> <span class="nav_name">Contrôles</span></a>
                        </li> <?php }   ?>

                    <?php if ($utilisateur->HasDroits("10_970")) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active == "log_visit_pa" ? " active" : ""; ?>" href="log_visit_pa.php<?php echo $is_mobile; ?>"><i class='fab fa-fw fa-wpforms nav_icon'></i> <span class="nav_name">Journal Visite</span></a>
                        </li><?php } ?>

                    <?php if ($utilisateur->HasDroits("10_210")) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active == "user_group" ? " active" : ""; ?>" href="user_group.php<?php echo $is_mobile; ?>"><i class='fas fa-users nav_icon'></i> <span class="nav_name">Groupes utilisateurs</span></a>
                        </li><?php } ?>
                    <?php if ($utilisateur->HasDroits("10_160")) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active == "users" ? " active" : ""; ?>" href="users.php<?php echo $is_mobile; ?>"><i class=' fas fa-user nav_icon'></i> <span class="nav_name">Utilisateurs</span></a>
                        </li> <?php }

                            if ($utilisateur->HasDroits("10_480")) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active == "lst_assign_control" ? " active" : ""; ?>" href="lst_assign_control.php<?php echo $is_mobile; ?>"><i class='fas fa-calendar-check nav_icon'></i> <span class="nav_name">Assignation pour contrôle</span></a>
                        </li> <?php }

                            if ($utilisateur->HasDroits("10_500")) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active == "lst_assign_install" ? " active" : ""; ?>" href="lst_assign_install.php<?php echo $is_mobile; ?>"><i class='fas fa-calendar-plus nav_icon'></i> <span class="nav_name">Assignation pour installation</span></a>
                        </li> <?php }

                            if ($utilisateur->HasDroits("10_529")) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active == "lst_assign_replace" ? " active" : ""; ?>" href="lst_assign_replace.php<?php echo $is_mobile; ?>"><i class='fas fa-calendar-plus nav_icon'></i> <span class="nav_name">Assignation pour remplacement</span></a>
                        </li> <?php }
                            if ($utilisateur->HasDroits("10_980")) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active == "assignation_control_masse" ? " active" : ""; ?>" href="assignation_control_masse.php<?php echo $is_mobile; ?>"><i class='fas fa-calendar-plus nav_icon'></i> <span class="nav_name">Assignation directe pour Contrôle </span></a>
                        </li> <?php }
                            if ($utilisateur->HasDroits("10_620")) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active == "dispatch_install" ? " active" : ""; ?>" href="dispatch_install.php<?php echo $is_mobile; ?>"><i class='fas fa-calendar-plus nav_icon'></i> <span class="nav_name">Dispatching Installations</span></a>
                        </li> <?php }

                            if ($utilisateur->HasDroits("10_610")) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active == "dispatch_control" ? " active" : ""; ?>" href="dispatch_control.php<?php echo $is_mobile; ?>"><i class='fas fa-calendar-plus nav_icon'></i> <span class="nav_name">Dispatching Contrôles</span></a>
                        </li> <?php }

                            if ($utilisateur->HasDroits("10_526")) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active == "demande_legal" ? " active" : ""; ?>" href="demande_legal.php<?php echo $is_mobile; ?>"><i class='fas fa-calendar-check nav_icon'></i> <span class="nav_name">Demande ré-légalisation</span></a>
                        </li> <?php }
                            if ($utilisateur->HasDroits("10_527")) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active == "demande_replace" ? " active" : ""; ?>" href="demande_replace.php"><i class='fas fa-calendar-check nav_icon'></i> <span class="nav_name">Demande remplacement</span></a>
                        </li> <?php }
                            if ($utilisateur->HasDroits("10_528")) { ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active == "demande_ticket" ? " active" : ""; ?>" href="demande_ticket.php<?php echo $is_mobile; ?>"><i class='fas fa-calendar-check nav_icon'></i> <span class="nav_name">Demande ticket</span></a>
                        </li> <?php }
                            if ($utilisateur->HasDroits("10_528")) {

                                ?>
                        <!--             <li class="nav-item">
                            <a class="nav-link <?php echo $active == "log_visit_pa" ? " active" : ""; ?>" href="log_visit_pa.php<?php echo $is_mobile; ?>"><i class='fas fa-calendar-check nav_icon'></i> <span class="nav_name">LOG  VISIT PA</span></a>
                        </li> --> <?php


                                }


                                $expanded_ = "true";
                                $collapse_ = " show";
                                $collapsed_ = "";
                                if ($parambase == '') {
                                    $expanded_ = "false";
                                    $collapse_ = "";
                                    $collapsed_ = " collapsed";
                                }

                                if ($utilisateur->HasDroits("10_460")) { ?>
                        <li class="nav-item ">
                            <a class="nav-link<?php echo $collapsed_;
                                                echo $parambase; ?>" href="#" aria-controls="submenu-1" data-toggle="collapse" aria-expanded="<?php echo $expanded_ ?>" data-target="#submenu-1"><i class="fab fa-fw fa-wpforms"></i>Paramètres de base</a>
                            <!--   <a class="nav-link" href="#" aria-expanded="true"  aria-controls="submenu-1"><i class="fab fa-fw fa-wpforms"></i>Paramètres de base</a>  -->
                            <div id="submenu-1" class="collapse <?php echo $collapse_ ?> submenu" style="">
                                <ul class="nav flex-column">
                                    <?php //if($utilisateur->HasDroits("12_25")){  
                                    ?>
                                    <!-- 	 <li class="nav-item">
                <a class="nav-link<?php echo $active == "pays" ? " active" : ""; ?>" href="pays.php">Pays</a>
            </li>  -->
                                    <?php //}  
                                    ?>
                                    <?php if ($utilisateur->HasDroits("10_516")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "province" ? " active" : ""; ?>" href="province.php<?php echo $is_mobile; ?>"><i class="fab fa-fw fa-wpforms"></i>Province</a>
                                        </li>
                                    <?php }  ?>
                                    <?php if ($utilisateur->HasDroits("10_517")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "siteproduction" ? " active" : ""; ?>" href="siteproduction.php<?php echo $is_mobile; ?>"><i class="fab fa-fw fa-wpforms"></i>Site</a>
                                        </li>
                                    <?php }  ?>
                                    <?php if ($utilisateur->HasDroits("10_518")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "organisme" ? " active" : ""; ?>" href="organisme.php<?php echo $is_mobile; ?>"><i class="fab fa-fw fa-wpforms"></i>Organisme</a>
                                        </li>
                                    <?php }  ?>
                                    <?php if ($utilisateur->HasDroits("10_519")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "cvs" ? " active" : ""; ?>" href="cvs.php<?php echo $is_mobile; ?>"><i class="fab fa-fw fa-wpforms"></i>CVS</a>
                                        </li>
                                    <?php }  ?>
                                    <?php if ($utilisateur->HasDroits("10_605")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "ville" ? " active" : ""; ?>" href="ville.php<?php echo $is_mobile; ?>"><i class="fab fa-fw fa-wpforms"></i>Ville</a>
                                        </li>
                                    <?php }  ?>
                                    <?php if ($utilisateur->HasDroits("10_520")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "commune" ? " active" : ""; ?>" href="commune.php<?php echo $is_mobile; ?>"><i class="fab fa-fw fa-wpforms"></i>Commune</a>
                                        </li>
                                    <?php }  ?>


                                    <?php if ($utilisateur->HasDroits("10_540")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "quartier" ? " active" : ""; ?>" href="quartier.php<?php echo $is_mobile; ?>"><i class="fab fa-fw fa-wpforms"></i>Quartier</a>
                                        </li>
                                    <?php }  ?>

                                    <?php if ($utilisateur->HasDroits("10_575")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "avenue" ? " active" : ""; ?>" href="avenue.php<?php echo $is_mobile; ?>"><i class="fab fa-fw fa-wpforms"></i>Avenue</a>
                                        </li>
                                    <?php }  ?>

                                    <?php if ($utilisateur->HasDroits("10_580")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "type_client" ? " active" : ""; ?>" href="type_client.php<?php echo $is_mobile; ?>"><i class="fab fa-fw fa-wpforms"></i>Type Client</a>
                                        </li>
                                    <?php }  ?>

                                    <?php if ($utilisateur->HasDroits("10_585")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "type_fraude" ? " active" : ""; ?>" href="type_fraude.php"><i class="fab fa-fw fa-wpforms"></i>Type Fraude</a>
                                        </li>
                                    <?php }  ?>

                                    <?php if ($utilisateur->HasDroits("10_590")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "type_usage" ? " active" : ""; ?>" href="type_usage.php<?php echo $is_mobile; ?>"><i class="fab fa-fw fa-wpforms"></i>Type Usage</a>
                                        </li>
                                    <?php }  ?>

                                    <?php if ($utilisateur->HasDroits("10_600")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "type_defaut" ? " active" : ""; ?>" href="type_defaut.php"><i class="fab fa-fw fa-wpforms"></i>Type Defaut</a>
                                        </li>
                                    <?php }  ?>

                                    <?php if ($utilisateur->HasDroits("10_990")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "type_diagnostic" ? " active" : ""; ?>" href="type_diagnostic.php"><i class="fab fa-fw fa-wpforms"></i>Codes des Diagnostics</a>
                                        </li>
                                    <?php }  ?>

                                    <?php if ($utilisateur->HasDroits("10_521")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "tarif" ? " active" : ""; ?>" href="tarif.php<?php echo $is_mobile; ?>"><i class="fab fa-fw fa-wpforms"></i>Tarif</a>
                                        </li>
                                    <?php }  ?>
                                    <?php if ($utilisateur->HasDroits("10_522")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "unite_de_mesure" ? " active" : ""; ?>" href="unite_de_mesure.php<?php echo $is_mobile; ?>"><i class="fab fa-fw fa-wpforms"></i>Unités de mesure</a>
                                        </li>
                                    <?php }  ?>
                                    <?php if ($utilisateur->HasDroits("10_700")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "lst_materiels" ? " active" : ""; ?>" href="lst_materiels.php<?php echo $is_mobile; ?>"><i class="fab fa-fw fa-wpforms"></i>Liste des matériels</a>
                                        </li>
                                    <?php }  ?>
                                    <?php if ($utilisateur->HasDroits("10_700")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "section_cable" ? " active" : ""; ?>" href="section_cable.php<?php echo $is_mobile; ?>"><i class="fab fa-fw fa-wpforms"></i>Sections câbles</a>
                                        </li>
                                    <?php }  ?>
                                    <?php if ($utilisateur->HasDroits("10_523")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "compteurs" ? " active" : ""; ?>" href="compteurs.php<?php echo $is_mobile; ?>"><i class="fab fa-fw fa-wpforms"></i>Compteurs</a>
                                        </li>
                                    <?php }  ?>
                                    <?php if ($utilisateur->HasDroits("10_524")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "pa_lst" ? " active" : ""; ?>" href="pa_lst.php<?php echo $is_mobile; ?>"><i class="fab fa-fw fa-wpforms"></i>PA</a>
                                        </li>
                                    <?php }  ?>
                                    <?php if ($utilisateur->HasDroits("10_525")) {  ?>
                                        <li class="nav-item">
                                            <a class="nav-link<?php echo $active == "marque_compteur" ? " active" : ""; ?>" href="marque_compteur.php<?php echo $is_mobile; ?>"><i class="fab fa-fw fa-wpforms"></i>Marque compteur</a>
                                        </li>
                                    <?php }  ?>
                                    <!--     <li class="nav-item">
                                             <a class="nav-link<?php //echo $active == "droits"?" active":"";  
                                                                ?>" href="dashboard-sales.html">Droits</a>
                                         </li>  -->

                                </ul>
                            </div>
                        </li> <?php } ?>
                    <?php if ($utilisateur->HasDroits("10_330")) { ?>
                        <li class="nav-divider">
                            MIGRATION
                        </li>
                        <?php if ($utilisateur->HasDroits("10_350")) { ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $active == "migration" ? " active" : ""; ?>" href="migration.php">Importer les données</a>
                            </li> <?php }   ?>
                        <li class="nav-divider">
                            REPORTING
                        </li>
                        <?php if ($utilisateur->HasDroits("10_340")) { ?>
                            <!--	<li class="nav-item">
                                            <a class="nav-link<?php echo $active == "rpt_identification" ? " active" : ""; ?>" href="rpt_identification.php">Liste des identifications effectuées</a>
                                    </li> --> <?php }   ?>
                        <?php if ($utilisateur->HasDroits("10_350")) { ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $active == "rpt_install" ? " active" : ""; ?>" href="rapport_installation.php">Liste des compteurs installés</a>
                            </li> <?php }   ?>

                        <?php if ($utilisateur->HasDroits("10_350")) { ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $active == "rpt_assignations" ? " active" : ""; ?>" href="rapport_assignations.php">Liste d'assignations</a>
                            </li> <?php }   ?>

                        <?php if ($utilisateur->HasDroits("10_350")) { ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $active == "rpt_identifications" ? " active" : ""; ?>" href="rapport_identifications.php">Liste d'identifications</a>
                            </li> <?php }   ?>

                        <?php if ($utilisateur->HasDroits("10_360")) { ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $active == "rpt_control" ? " active" : ""; ?>" href="rapport_compteur_control.php">Liste des compteurs contrôlés</a>
                            </li> <?php }   ?>
                        <?php if ($utilisateur->HasDroits("10_360")) { ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $active == "rpt_control_maps" ? " active" : ""; ?>" href="rapport_compteur_maps.php">Maps</a>
                            </li> <?php }   ?>
                        <?php if ($utilisateur->HasDroits("10_370")) { ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $active == "rpt_compteur_fraude" ? " active" : ""; ?>" href="rapport_compteur_fraude.php">Liste des compteurs en état de fraude</a>
                            </li> <?php }   ?>
                        <?php if ($utilisateur->HasDroits("10_341")) { ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo $active == "rpt_compteur_sceller" ? " active" : ""; ?>" href="rapport_compteur_scelle.php">Liste des compteurs scellés</a>
                            </li> <?php }   ?>
                    <?php }  ?>

                    <?php if ($utilisateur->HasDroits("10_950")) {   ?>
                        <li class="nav-item">
                            <a class="nav-link <?php echo $active == "rpt_suppl" ? " active" : ""; ?>" href="rapport_supplementaires.php"> <span class="nav_name">Rapports Supplémentaires</span></a>
                        </li> <?php }
                            //apk/app-debug.apk  
                                ?>

                    <?php //if($utilisateur->HasDroits("10_341")){ 
                    ?>
                    <li class="nav-item">
                        <a class="nav-link" href="apk/blue-app.apk" style="color:#77df61;"><i class='fab fa-google-play nav_icon'></i> <span class="nav_name">Télécharger Application</span></a>
                    </li> <?php //}   
                            ?>
                    <li class="nav-item">
                        <a class="nav-link" href="controller.php?view=logout"><i class='fas fa-power-off nav_icon'></i> <span class="nav_name">Se déconnecter</span></a>
                    </li>

                </ul>
            </div>
        </nav>
    </div>
</div>