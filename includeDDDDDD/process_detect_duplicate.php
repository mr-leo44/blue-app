
detection duplicate

SELECT count(*) as nbre,
t_main_data.p_a,
t_main_data.code_identificateur,
t_main_data.gps_longitude,
t_main_data.gps_latitude,
t_main_data.client_id,
t_main_data.occupant_id,
t_main_data.cvs_id
FROM
t_main_data
 group by  t_main_data.p_a,
t_main_data.code_identificateur,
t_main_data.gps_longitude,
t_main_data.gps_latitude,
t_main_data.client_id,
t_main_data.occupant_id,
t_main_data.cvs_id

HAVING count(t_main_data.p_a)>1 and count(t_main_data.code_identificateur)>1 and count(t_main_data.gps_longitude) >1 and count(t_main_data.gps_latitude)>1 and count(t_main_data.client_id)>1 and count(t_main_data.occupant_id)>1 and count(t_main_data.cvs_id)>1

--------------------------------------------------------------------------------------------------
identificateur

SELECT
Count(*) AS nbre,
t_main_data.p_a,
t_main_data.code_identificateur,
t_main_data.gps_longitude,
t_main_data.gps_latitude,
t_main_data.client_id,
t_main_data.occupant_id,
t_main_data.cvs_id,
min(t_main_data.identificateur) as identtif_1,
max(t_main_data.identificateur) as identif_2,
min(t_main_data.deja_assigner) as gh,
max(t_main_data.est_installer) as est_int 
FROM
t_main_data
GROUP BY
t_main_data.p_a,
t_main_data.code_identificateur,
t_main_data.gps_longitude,
t_main_data.gps_latitude,
t_main_data.client_id,
t_main_data.occupant_id,
t_main_data.cvs_id
HAVING
count(t_main_data.p_a) > 1 AND
count(t_main_data.code_identificateur) > 1 AND
count(t_main_data.gps_longitude) > 1 AND
count(t_main_data.gps_latitude) > 1 AND
count(t_main_data.client_id) > 1 AND
count(t_main_data.occupant_id) > 1 AND
count(t_main_data.cvs_id) > 1 and (min(t_main_data.identificateur)=max(t_main_data.identificateur))

---------------------------------------------------------------------------------------------------
SELECT
t_main_data.id_,
t_main_data.date_identification,
t_main_data.date_debut_identification,
t_main_data.date_fin_identification,
t_main_data.p_a,
t_main_data.code_identificateur,
t_main_data.gps_longitude,
t_main_data.gps_latitude,
t_main_data.client_id,
t_main_data.occupant_id,
t_main_data.ref_site_identif,
t_main_data.annule,
t_main_data.is_draft,
t_main_data.est_installer,
t_main_data.adresse_id,
t_main_data.nbre_appartement,
t_main_data.ref_dernier_log_controle,
t_main_data.ref_dernier_log_install,
t_main_data.ref_installation_actuel
FROM
t_main_data
WHERE
t_main_data.p_a IN ('31-104-1500', '31-105-6145', '31-105-6246', '31-105-6310', '31-107-0305', '31-107-0333', '31-107-0410', '31-107-0430', '31-107-0455', '31-107-9525', '31-108-0582', '61- 912-0185', '61- 912-2312', '61- 913 - 5889', '61-309-2630', '61-4046080', '61-407-0123', '61-407-0311', '61-407-0530', '61-407-0725', '61-407-0774', '61-407-0776', '61-407-0778', '61-407-0784', '61-407-0786', '61-407-2170', '61-407-3966', '61-407-4082', '61-407-4371', '61-407-4990', '61-407-6040', '61-407-6248', '61-407-6722', '61-407-6810', '61-407-6829', '61-407-6831', '61-407-6882', '61-407-6884', '61-407-6893', '61-407-6923', '61-407-6930', '61-407-6971', '61-407-6987', '61-407-7191', '61-407-7409', '61-407_7166', '61-408-0156', '61-408-0159', '61-408-0239', '61-408-0280', '61-408-0302', '61-408-0316', '61-408-0504', '61-408-0947', '61-408-1023', '61-408-1025', '61-408-1064', '61-408-1124', '61-408-1377', '61-408-1477', '61-408-2374', '61-408-2941', '61-408-3247', '61-408-3290', '61-408-3294', '61-408-3324', '61-408-4115', '61-408-4162', '61-408-4164', '61-408-4256', '61-408-4423', '61-408-4530', '61-408-6020', '61-408-6142', '61-408-6268', '61-408-6327', '61-408-6399', '61-408-6621', '61-408-6910', '61-408-7159', '61-408-7160', '61-408-7185', '61-408-7411', '61-408-8066', '61-408-8271', '61-408-9333', '61-408-9414', '61-411-7330', '61-414-0285', '61-414-0421', '61-414-0426', '61-414-0958', '61-414-1371', '61-414-1545', '61-414-2276', '61-414-2511', '61-414-2615', '61-414-2638', '61-414-4028', '61-414-4918', '61-414-6050', '61-414-6060', '61-414-6070', '61-414-6120', '61-414-6210', '61-414-6410', '61-414-6415', '61-414-6440', '61-414-6450', '61-414-6470', '61-414-6500', '61-414-6510', '61-414-6580', '61-414-6625', '61-414-6680', '61-414-6700', '61-414-6720', '61-414-6830', '61-414-6840', '61-414-6859', '61-414-6860', '61-414-6862', '61-414-6870', '61-414-6899', '61-414-6928', '61-414-7061', '61-414-7080', '61-414-7100', '61-414-7122', '61-414-7180', '61-414-7220', '61-414-7255', '61-414-7290', '61-414-7310', '61-414-7320', '61-414-7350', '61-414-7390', '61-414-7400', '61-414-7410', '61-414-7421', '61-414-7444', '61-414-7450', '61-414-7475', '61-414-7570', '61-414-8045', '61-414-8085', '61-414-8110', '61-414-8180', '61-414-8240', '61-414-8280', '61-414-8360', '61-414-8470', '61-414-8480', '61-414-8520', '61-414-8530', '61-415-1510', '61-415-2012', '61-415-2328', '61-415-4417', '61-415-4600', '61-415-4650', '61-415-6377', '61-415-7276', '61-415-8632', '61-415-8885', '61-415-8970', '61-425-7300', '61-48-7160', '61-48-7411', '61-497-6867', '61-515-2627', '61-707-0725', '61-707-4306', '61-707-7611', '61-708-0111', '61-803-8215', '61-902-9160', '61-904-0410', '61-904-0566', '61-904-0800', '61-904-1741', '61-904-2021', '61-904-2025', '61-904-2317', '61-904-2353', '61-904-2354', '61-904-2355', '61-904-2357', '61-904-2360', '61-904-2413', '61-904-2416', '61-904-2425', '61-904-2427', '61-904-4150', '61-904-4194', '61-904-4203', '61-904-4216', '61-904-4218', '61-904-4362', '61-904-4367', '61-904-4545', '61-904-4553', '61-904-4565', '61-904-4570', '61-904-4575', '61-904-4585', '61-904-4587', '61-904-4590', '61-904-4595', '61-904-4625', '61-904-4822', '61-904-4905', '61-904-7069', '61-904-8242', '61-904-9719', '61-907-0281', '61-907-2017', '61-907-2018', '61-907-2858', '61-907-2941', '61-907-3035', '61-907-3581', '61-907-4320', '61-907-4327', '61-912+6003', '61-912-0100', '61-912-0185', '61-912-0296', '61-912-0345', '61-912-0372', '61-912-0550', '61-912-0570', '61-912-1044', '61-912-2078', '61-912-2080', '61-912-2089', '61-912-2122', '61-912-2126', '61-912-2127', '61-912-2160', '61-912-2183', '61-912-2340', '61-912-2661', '61-912-2665', '61-912-2666', '61-912-2993', '61-912-3402', '61-912-4180', '61-912-4320', '61-912-4495', '61-912-4900', '61-912-6072', '61-912-6271', '61-912-6547', '61-912-8568', '61-912-8880', '61-912-8900', '61-912-9567', '61-913-', '61-913-0320', '61-913-0686', '61-913-0921', '61-913-2175', '61-913-2220', '61-913-2416', '61-913-2476', '61-913-3049', '61-913-4049', '61-913-4279', '61-913-4373', '61-913-5600', '61-913-6224', '61-913-6904', '61-913-6905', '614-408-7411', '619122666', '619124321', '61_308_0020', '61_907_3035', '61_913_4373', '61_913_6904', '62-104-0195', '62-408-0159', '693060511', '69-306-6874', 'Xxxxxxxxxxxxxxxxxxxxxx')
ORDER BY
t_main_data.p_a ASC
;
--------------------------------------------------------------------------------------------------
S

update t_main_data set t_main_data.annule=1 WHERE t_main_data.ref_installation_actuel is null and 
t_main_data.p_a IN ('31-104-1500', '31-105-6145', '31-105-6246', '31-105-6310', '31-107-0305', '31-107-0333', '31-107-0410', '31-107-0430', '31-107-0455', '31-107-9525', '31-108-0582', '61- 912-0185', '61- 912-2312', '61- 913 - 5889', '61-309-2630', '61-4046080', '61-407-0123', '61-407-0311', '61-407-0530', '61-407-0725', '61-407-0774', '61-407-0776', '61-407-0778', '61-407-0784', '61-407-0786', '61-407-2170', '61-407-3966', '61-407-4082', '61-407-4371', '61-407-4990', '61-407-6040', '61-407-6248', '61-407-6722', '61-407-6810', '61-407-6829', '61-407-6831', '61-407-6882', '61-407-6884', '61-407-6893', '61-407-6923', '61-407-6930', '61-407-6971', '61-407-6987', '61-407-7191', '61-407-7409', '61-407_7166', '61-408-0156', '61-408-0159', '61-408-0239', '61-408-0280', '61-408-0302', '61-408-0316', '61-408-0504', '61-408-0947', '61-408-1023', '61-408-1025', '61-408-1064', '61-408-1124', '61-408-1377', '61-408-1477', '61-408-2374', '61-408-2941', '61-408-3247', '61-408-3290', '61-408-3294', '61-408-3324', '61-408-4115', '61-408-4162', '61-408-4164', '61-408-4256', '61-408-4423', '61-408-4530', '61-408-6020', '61-408-6142', '61-408-6268', '61-408-6327', '61-408-6399', '61-408-6621', '61-408-6910', '61-408-7159', '61-408-7160', '61-408-7185', '61-408-7411', '61-408-8066', '61-408-8271', '61-408-9333', '61-408-9414', '61-411-7330', '61-414-0285', '61-414-0421', '61-414-0426', '61-414-0958', '61-414-1371', '61-414-1545', '61-414-2276', '61-414-2511', '61-414-2615', '61-414-2638', '61-414-4028', '61-414-4918', '61-414-6050', '61-414-6060', '61-414-6070', '61-414-6120', '61-414-6210', '61-414-6410', '61-414-6415', '61-414-6440', '61-414-6450', '61-414-6470', '61-414-6500', '61-414-6510', '61-414-6580', '61-414-6625', '61-414-6680', '61-414-6700', '61-414-6720', '61-414-6830', '61-414-6840', '61-414-6859', '61-414-6860', '61-414-6862', '61-414-6870', '61-414-6899', '61-414-6928', '61-414-7061', '61-414-7080', '61-414-7100', '61-414-7122', '61-414-7180', '61-414-7220', '61-414-7255', '61-414-7290', '61-414-7310', '61-414-7320', '61-414-7350', '61-414-7390', '61-414-7400', '61-414-7410', '61-414-7421', '61-414-7444', '61-414-7450', '61-414-7475', '61-414-7570', '61-414-8045', '61-414-8085', '61-414-8110', '61-414-8180', '61-414-8240', '61-414-8280', '61-414-8360', '61-414-8470', '61-414-8480', '61-414-8520', '61-414-8530', '61-415-1510', '61-415-2012', '61-415-2328', '61-415-4417', '61-415-4600', '61-415-4650', '61-415-6377', '61-415-7276', '61-415-8632', '61-415-8885', '61-415-8970', '61-425-7300', '61-48-7160', '61-48-7411', '61-497-6867', '61-515-2627', '61-707-0725', '61-707-4306', '61-707-7611', '61-708-0111', '61-803-8215', '61-902-9160', '61-904-0410', '61-904-0566', '61-904-0800', '61-904-1741', '61-904-2021', '61-904-2025', '61-904-2317', '61-904-2353', '61-904-2354', '61-904-2355', '61-904-2357', '61-904-2360', '61-904-2413', '61-904-2416', '61-904-2425', '61-904-2427', '61-904-4150', '61-904-4194', '61-904-4203', '61-904-4216', '61-904-4218', '61-904-4362', '61-904-4367', '61-904-4545', '61-904-4553', '61-904-4565', '61-904-4570', '61-904-4575', '61-904-4585', '61-904-4587', '61-904-4590', '61-904-4595', '61-904-4625', '61-904-4822', '61-904-4905', '61-904-7069', '61-904-8242', '61-904-9719', '61-907-0281', '61-907-2017', '61-907-2018', '61-907-2858', '61-907-2941', '61-907-3035', '61-907-3581', '61-907-4320', '61-907-4327', '61-912+6003', '61-912-0100', '61-912-0185', '61-912-0296', '61-912-0345', '61-912-0372', '61-912-0550', '61-912-0570', '61-912-1044', '61-912-2078', '61-912-2080', '61-912-2089', '61-912-2122', '61-912-2126', '61-912-2127', '61-912-2160', '61-912-2183', '61-912-2340', '61-912-2661', '61-912-2665', '61-912-2666', '61-912-2993', '61-912-3402', '61-912-4180', '61-912-4320', '61-912-4495', '61-912-4900', '61-912-6072', '61-912-6271', '61-912-6547', '61-912-8568', '61-912-8880', '61-912-8900', '61-912-9567', '61-913-', '61-913-0320', '61-913-0686', '61-913-0921', '61-913-2175', '61-913-2220', '61-913-2416', '61-913-2476', '61-913-3049', '61-913-4049', '61-913-4279', '61-913-4373', '61-913-5600', '61-913-6224', '61-913-6904', '61-913-6905', '614-408-7411', '619122666', '619124321', '61_308_0020', '61_907_3035', '61_913_4373', '61_913_6904', '62-104-0195', '62-408-0159', '693060511', '69-306-6874', 'Xxxxxxxxxxxxxxxxxxxxxx')


affected 982
--------------------------------------------------------------------------------------------------



select count(*) as nb,p_a from t_main_data GROUP BY  p_a HAVING count(p_a)>1

--------------------------------------------------------------------------------------------------
Voici en vrac quelques observations :

a-   GIS : où en sommes-nous exactement ?

b-   Me trouver un exemple pour illustrer la procédure de duplication d’une fiche d’installation pour un PA avec plusieurs ménages

c-    Photo : il faut qu’il soit possible de la voir à l’écran dans avoir à la décharger

d-   Les photos sont toujours en petit format. J’avais communiqué ce fait il y a de nombreuses semaines. Nous sommes contraint de les agrandir, et nous perdons de la définition.

e-   Prise des photos : il faut continuer à former les agents à la prise de photos. Quasiment aucune n’est exploitable ; y compris celles des factures.

f-    Assembler puis me communiquer une liste des états à construire.

g-   Je ne vois pas le nom de l’identificateur dans la fiche d’installation.

h-    Dans la fiche d’installation je vois la date d’identification – ce qui est une bonne chose, mais pas celle de l’installation

i-     La prise de photo à l’identification doit être obligatoire. Elle ne l’est pas pour le moment.

j-     Discutons avec Randy des cas de réhabilitation par les installateurs d’installations fraudées.

k-    Remplacer la fonction ‘nouvelle fiche même adresse’ par un choix de deux menus (boutons) une fois les coordonnées des différents ménages saisies :

                      i.        Créer les fiches et ‘appliquer’

                    ii.        Créer les fiches à l’état brouillon

l-     Nice to have : un  seul statut de l’installation qui évolue : en cours -> terminé -> Approuvé.
Et plus de statut ‘brouillon’.

m-  Nice to have : affichage des journaux : en format tableau, ce qui permettrait en autres d’afficher beaucoup plus de fiches sur un écran, et de classer par tête de colonne.

n-    Pourquoi pas de menu voir la fiche pour les fiches de contrôle ?

o-   Pas de statut affiché pour les fiches de contrôle ? Et comment filtrer par statut ?

p-   Aucune des fiches de contrôle ne semble être validée.

q-   A la validation est-il vérifié qu’il y a au moins une photo ?

r-    Comment un contrôleur voit-il les photos prises par l’installateur ?

s-    Revoir tous ensemble le flux des données. Le concept initial est d’avoir une grande fiche qui évolue : identification -> installation -> contrôle 1 -> contrôle 2 -> installation, etc., avec toutes les données disponible en même temps. Avec les données les plus récentes en début de fiche.
Egalement la possibilité de rentrer un numéro de compteur et de voir sa situation : installé, contrôlé, etc,

 

--------------------------------------------------------------------------------------------------

DROP TABLE IF EXISTS `select_adress`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `select_adress` AS select `t_main_data`.`id_` AS `id_`,`t_main_data`.`date_identification` AS `date_identification`,`t_main_data`.`date_debut_identification` AS `date_debut_identification`,`t_main_data`.`date_fin_identification` AS `date_fin_identification`,`t_main_data`.`p_a` AS `p_a`,`t_main_data`.`code_identificateur` AS `code_identificateur`,`t_main_data`.`gps_longitude` AS `gps_longitude`,`t_main_data`.`gps_latitude` AS `gps_latitude`,`t_main_data`.`client_id` AS `client_id`,`t_main_data`.`occupant_id` AS `occupant_id`,`t_main_data`.`photo_compteur` AS `photo_compteur`,`t_main_data`.`nbre_branchement` AS `nbre_branchement`,`t_main_data`.`signature_electronique` AS `signature_electronique`,`t_main_data`.`section_cable` AS `section_cable`,`t_main_data`.`date_update` AS `date_update`,`t_main_data`.`n_user_update` AS `n_user_update`,`t_main_data`.`num_compteur_initial` AS `num_compteur_initial`,`t_main_data`.`num_compteur_actuel` AS `num_compteur_actuel`,`t_main_data`.`date_installation_initial` AS `date_installation_initial`,`t_main_data`.`date_installation_actuel` AS `date_installation_actuel`,`t_main_data`.`photo_pa_avant` AS `photo_pa_avant`,`t_main_data`.`ref_installation_actuel` AS `ref_installation_actuel`,`t_main_data`.`cvs_id` AS `cvs_id`,`t_main_data`.`type_install_actuel` AS `type_install_actuel`,`t_main_data`.`refus_incapacite_technique` AS `refus_incapacite_technique`,`t_main_data`.`nom_precurseur` AS `nom_precurseur`,`t_main_data`.`date_depot_lettre` AS `date_depot_lettre`,`t_main_data`.`date_expiration` AS `date_expiration`,`t_main_data`.`etat_connexion_client` AS `etat_connexion_client`,`t_main_data`.`compteur_pilote` AS `compteur_pilote`,`t_main_data`.`marque_compteur_rpl` AS `marque_compteur_rpl`,`t_main_data`.`num_scelle_un_prec_rpl` AS `num_scelle_un_prec_rpl`,`t_main_data`.`num_scelle_un_rpl` AS `num_scelle_un_rpl`,`t_main_data`.`date_pose_scelle_prec_rpl` AS `date_pose_scelle_prec_rpl`,`t_main_data`.`date_pose_scelle_rpl` AS `date_pose_scelle_rpl`,`t_main_data`.`num_scelle_coffret_prec_rpl` AS `num_scelle_coffret_prec_rpl`,`t_main_data`.`num_scelle_coffret_rpl` AS `num_scelle_coffret_rpl`,`t_main_data`.`index_credit_restant_rpl` AS `index_credit_restant_rpl`,`t_main_data`.`code_defaut_cpteur_rpl` AS `code_defaut_cpteur_rpl`,`t_main_data`.`marque_compteur_install` AS `marque_compteur_install`,`t_main_data`.`date_rehabilitation` AS `date_rehabilitation`,`t_main_data`.`disjoncteur` AS `disjoncteur`,`t_main_data`.`num_scelle_cpteur_un_install` AS `num_scelle_cpteur_un_install`,`t_main_data`.`num_scelle_coffret_deux_install` AS `num_scelle_coffret_deux_install`,`t_main_data`.`date_pose_scelle_install` AS `date_pose_scelle_install`,`t_main_data`.`marque_cpteur_mecanique` AS `marque_cpteur_mecanique`,`t_main_data`.`num_serie_cpteur_mecanique` AS `num_serie_cpteur_mecanique`,`t_main_data`.`index_cpteur_mecanique` AS `index_cpteur_mecanique`,`t_main_data`.`date_retrait_cpteur_mecanique` AS `date_retrait_cpteur_mecanique`,`t_main_data`.`etat_compteur` AS `etat_compteur`,`t_main_data`.`autocollant_place` AS `autocollant_place`,`t_main_data`.`nom_installateur_qui_remplace` AS `nom_installateur_qui_remplace`,`t_main_data`.`organe_installateur` AS `organe_installateur`,`t_main_data`.`etat_fraude_premier_controle` AS `etat_fraude_premier_controle`,`t_main_data`.`ref_dernier_log_controle` AS `ref_dernier_log_controle`,`t_main_data`.`ref_dernier_log_install` AS `ref_dernier_log_install`,`t_main_data`.`ref_site_identif` AS `ref_site_identif`,`t_main_data`.`annule` AS `annule`,`t_main_data`.`n_user_annule` AS `n_user_annule`,`t_main_data`.`is_draft` AS `is_draft`,`t_main_data`.`motif_annulation` AS `motif_annulation`,`t_main_data`.`numero_piece_identity` AS `numero_piece_identity`,`t_main_data`.`accessibility_client` AS `accessibility_client`,`t_main_data`.`tarif_identif` AS `tarif_identif`,`t_main_data`.`infos_supplementaires` AS `infos_supplementaires`,`t_main_data`.`numero_depart` AS `numero_depart`,`t_main_data`.`numero_poteau_identif` AS `numero_poteau_identif`,`t_main_data`.`type_raccordement_identif` AS `type_raccordement_identif`,`t_main_data`.`type_compteur` AS `type_compteur`,`t_main_data`.`type_construction` AS `type_construction`,`t_main_data`.`type_activites` AS `type_activites`,`t_main_data`.`conformites_installation` AS `conformites_installation`,`t_main_data`.`avis_technique_blue` AS `avis_technique_blue`,`t_main_data`.`avis_occupant` AS `avis_occupant`,`t_main_data`.`chef_equipe` AS `chef_equipe`,`t_main_data`.`statut_occupant` AS `statut_occupant`,`t_main_data`.`titre_responsable` AS `titre_responsable`,`t_main_data`.`titre_remplacant` AS `titre_remplacant`,`t_main_data`.`est_installer` AS `est_installer`,`t_main_data`.`type_raccordement_propose` AS `type_raccordement_propose`,`t_main_data`.`nature_activity` AS `nature_activity`,`t_main_data`.`type_client` AS `type_client`,`t_main_data`.`consommateur_gerer` AS `consommateur_gerer`,`t_main_data`.`id_organisme_allouer` AS `id_organisme_allouer`,`t_main_data`.`cabine_id` AS `cabine_id`,`t_main_data`.`index_consommation` AS `index_consommation`,`t_main_data`.`identificateur` AS `identificateur`,`t_main_data`.`n_user_create` AS `n_user_create`,`t_main_data`.`statut_client` AS `statut_client`,`t_main_data`.`id_equipe_identification` AS `id_equipe_identification`,`t_main_data`.`statut_conclusion_dernier_controle` AS `statut_conclusion_dernier_controle`,`t_main_data`.`statut_approbation_dernier_install` AS `statut_approbation_dernier_install`,`t_main_data`.`date_dernier_controle` AS `date_dernier_controle`,`t_main_data`.`deja_assigner` AS `deja_assigner`,`t_main_data`.`adresse_id` AS `adresse_id`,`t_main_data`.`presence_inversor` AS `presence_inversor`,`t_main_data`.`nbre_appartement` AS `nbre_appartement`,`t_main_data`.`is_from_excel` AS `is_from_excel`,`t_main_data`.`reference_appartement` AS `reference_appartement`,`t_main_data`.`datesys` AS `datesys` from `t_main_data` where (`t_main_data`.`adresse_id` = '3e1025cbe7fcc45efc1eeb8bf01c7e36');

DROP TABLE IF EXISTS `select_client`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `select_client` AS select `t_param_identite`.`id` AS `id`,`t_param_identite`.`nom` AS `nom`,`t_param_identite`.`date_naissance` AS `date_naissance`,`t_param_identite`.`postnom` AS `postnom`,`t_param_identite`.`prenom` AS `prenom`,`t_param_identite`.`lieu_naissance` AS `lieu_naissance`,`t_param_identite`.`sexe` AS `sexe`,`t_param_identite`.`id_adress` AS `id_adress`,`t_param_identite`.`user_create` AS `user_create`,`t_param_identite`.`user_update` AS `user_update`,`t_param_identite`.`date_create` AS `date_create`,`t_param_identite`.`date_update` AS `date_update`,`t_param_identite`.`date_annule` AS `date_annule`,`t_param_identite`.`annule` AS `annule`,`t_param_identite`.`motif` AS `motif`,`t_param_identite`.`phone_number` AS `phone_number`,`t_param_identite`.`num_piece_identity` AS `num_piece_identity`,`t_param_identite`.`statut_identity` AS `statut_identity`,`t_param_identite`.`site_id` AS `site_id`,`t_param_identite`.`user_annule` AS `user_annule` from `t_param_identite` where (`t_param_identite`.`nom` like 'kazadi%');

DROP TABLE IF EXISTS `select_identification_par_identificateur`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `select_identification_par_identificateur` AS select `t_main_data`.`id_` AS `id_`,`t_main_data`.`date_identification` AS `date_identification`,`t_main_data`.`date_debut_identification` AS `date_debut_identification`,`t_main_data`.`date_fin_identification` AS `date_fin_identification`,`t_main_data`.`p_a` AS `p_a`,`t_main_data`.`code_identificateur` AS `code_identificateur`,`t_main_data`.`gps_longitude` AS `gps_longitude`,`t_main_data`.`gps_latitude` AS `gps_latitude`,`t_main_data`.`client_id` AS `client_id`,`t_main_data`.`occupant_id` AS `occupant_id`,`t_main_data`.`photo_compteur` AS `photo_compteur`,`t_main_data`.`nbre_branchement` AS `nbre_branchement`,`t_main_data`.`signature_electronique` AS `signature_electronique`,`t_main_data`.`section_cable` AS `section_cable`,`t_main_data`.`date_update` AS `date_update`,`t_main_data`.`n_user_update` AS `n_user_update`,`t_main_data`.`num_compteur_initial` AS `num_compteur_initial`,`t_main_data`.`num_compteur_actuel` AS `num_compteur_actuel`,`t_main_data`.`date_installation_initial` AS `date_installation_initial`,`t_main_data`.`date_installation_actuel` AS `date_installation_actuel`,`t_main_data`.`photo_pa_avant` AS `photo_pa_avant`,`t_main_data`.`ref_installation_actuel` AS `ref_installation_actuel`,`t_main_data`.`cvs_id` AS `cvs_id`,`t_main_data`.`type_install_actuel` AS `type_install_actuel`,`t_main_data`.`refus_incapacite_technique` AS `refus_incapacite_technique`,`t_main_data`.`nom_precurseur` AS `nom_precurseur`,`t_main_data`.`date_depot_lettre` AS `date_depot_lettre`,`t_main_data`.`date_expiration` AS `date_expiration`,`t_main_data`.`etat_connexion_client` AS `etat_connexion_client`,`t_main_data`.`compteur_pilote` AS `compteur_pilote`,`t_main_data`.`marque_compteur_rpl` AS `marque_compteur_rpl`,`t_main_data`.`num_scelle_un_prec_rpl` AS `num_scelle_un_prec_rpl`,`t_main_data`.`num_scelle_un_rpl` AS `num_scelle_un_rpl`,`t_main_data`.`date_pose_scelle_prec_rpl` AS `date_pose_scelle_prec_rpl`,`t_main_data`.`date_pose_scelle_rpl` AS `date_pose_scelle_rpl`,`t_main_data`.`num_scelle_coffret_prec_rpl` AS `num_scelle_coffret_prec_rpl`,`t_main_data`.`num_scelle_coffret_rpl` AS `num_scelle_coffret_rpl`,`t_main_data`.`index_credit_restant_rpl` AS `index_credit_restant_rpl`,`t_main_data`.`code_defaut_cpteur_rpl` AS `code_defaut_cpteur_rpl`,`t_main_data`.`marque_compteur_install` AS `marque_compteur_install`,`t_main_data`.`date_rehabilitation` AS `date_rehabilitation`,`t_main_data`.`disjoncteur` AS `disjoncteur`,`t_main_data`.`num_scelle_cpteur_un_install` AS `num_scelle_cpteur_un_install`,`t_main_data`.`num_scelle_coffret_deux_install` AS `num_scelle_coffret_deux_install`,`t_main_data`.`date_pose_scelle_install` AS `date_pose_scelle_install`,`t_main_data`.`marque_cpteur_mecanique` AS `marque_cpteur_mecanique`,`t_main_data`.`num_serie_cpteur_mecanique` AS `num_serie_cpteur_mecanique`,`t_main_data`.`index_cpteur_mecanique` AS `index_cpteur_mecanique`,`t_main_data`.`date_retrait_cpteur_mecanique` AS `date_retrait_cpteur_mecanique`,`t_main_data`.`etat_compteur` AS `etat_compteur`,`t_main_data`.`autocollant_place` AS `autocollant_place`,`t_main_data`.`nom_installateur_qui_remplace` AS `nom_installateur_qui_remplace`,`t_main_data`.`organe_installateur` AS `organe_installateur`,`t_main_data`.`etat_fraude_premier_controle` AS `etat_fraude_premier_controle`,`t_main_data`.`ref_dernier_log_controle` AS `ref_dernier_log_controle`,`t_main_data`.`ref_dernier_log_install` AS `ref_dernier_log_install`,`t_main_data`.`ref_site_identif` AS `ref_site_identif`,`t_main_data`.`annule` AS `annule`,`t_main_data`.`n_user_annule` AS `n_user_annule`,`t_main_data`.`is_draft` AS `is_draft`,`t_main_data`.`motif_annulation` AS `motif_annulation`,`t_main_data`.`numero_piece_identity` AS `numero_piece_identity`,`t_main_data`.`accessibility_client` AS `accessibility_client`,`t_main_data`.`tarif_identif` AS `tarif_identif`,`t_main_data`.`infos_supplementaires` AS `infos_supplementaires`,`t_main_data`.`numero_depart` AS `numero_depart`,`t_main_data`.`numero_poteau_identif` AS `numero_poteau_identif`,`t_main_data`.`type_raccordement_identif` AS `type_raccordement_identif`,`t_main_data`.`type_compteur` AS `type_compteur`,`t_main_data`.`type_construction` AS `type_construction`,`t_main_data`.`type_activites` AS `type_activites`,`t_main_data`.`conformites_installation` AS `conformites_installation`,`t_main_data`.`avis_technique_blue` AS `avis_technique_blue`,`t_main_data`.`avis_occupant` AS `avis_occupant`,`t_main_data`.`chef_equipe` AS `chef_equipe`,`t_main_data`.`statut_occupant` AS `statut_occupant`,`t_main_data`.`titre_responsable` AS `titre_responsable`,`t_main_data`.`titre_remplacant` AS `titre_remplacant`,`t_main_data`.`est_installer` AS `est_installer`,`t_main_data`.`type_raccordement_propose` AS `type_raccordement_propose`,`t_main_data`.`nature_activity` AS `nature_activity`,`t_main_data`.`type_client` AS `type_client`,`t_main_data`.`consommateur_gerer` AS `consommateur_gerer`,`t_main_data`.`id_organisme_allouer` AS `id_organisme_allouer`,`t_main_data`.`cabine_id` AS `cabine_id`,`t_main_data`.`index_consommation` AS `index_consommation`,`t_main_data`.`identificateur` AS `identificateur`,`t_main_data`.`n_user_create` AS `n_user_create`,`t_main_data`.`statut_client` AS `statut_client`,`t_main_data`.`id_equipe_identification` AS `id_equipe_identification`,`t_main_data`.`statut_conclusion_dernier_controle` AS `statut_conclusion_dernier_controle`,`t_main_data`.`statut_approbation_dernier_install` AS `statut_approbation_dernier_install`,`t_main_data`.`date_dernier_controle` AS `date_dernier_controle`,`t_main_data`.`deja_assigner` AS `deja_assigner`,`t_main_data`.`adresse_id` AS `adresse_id`,`t_main_data`.`presence_inversor` AS `presence_inversor`,`t_main_data`.`nbre_appartement` AS `nbre_appartement`,`t_main_data`.`is_from_excel` AS `is_from_excel`,`t_main_data`.`reference_appartement` AS `reference_appartement`,`t_main_data`.`datesys` AS `datesys` from `t_main_data` where (`t_main_data`.`identificateur` = '4392d6f141ac5e0bdce387ea64bc6b02');
--------------------------------------------------------------------------------------------------
