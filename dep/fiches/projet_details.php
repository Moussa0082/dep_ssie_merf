<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
if (!isset ($_SESSION["id"])) {
        header(sprintf("Location: %s", "./login.php"));  exit();
}
include_once 'api/configuration.php';
$config = new Config;

extract($_GET);
if (!isset ($id)) {
        header(sprintf("Location: %s", "./partenaires.php"));  exit();
}

//projet
$query_projet = $db ->prepare('SELECT T.* FROM t_projets T WHERE T.id_projet=:id_projet');
$query_projet->execute(array(':id_projet' => $id));
$row_projet = $query_projet ->fetch();
$totalRows_projet = $query_projet->rowCount();
if($totalRows_projet>0)
{
    //Partenaires
    $query_partenaire = $db ->prepare('SELECT id_partenaire,code_partenaire,sigle_partenaire,nom_partenaire,type_partenaire FROM t_partenaires WHERE FIND_IN_SET(:type_partenaire,type_partenaire) ');
    $query_partenaire->execute(array(':type_partenaire' => 1));
    $row_liste_partenaire = $query_partenaire ->fetchAll();
    $totalRows_liste_partenaire = $query_partenaire->rowCount();
    $liste_structure_arrayV = $liste_structure_array = array();
    if($totalRows_liste_partenaire>0){  foreach($row_liste_partenaire as $row_liste_partenaire){
    $liste_structure_arrayV[$row_liste_partenaire["id_partenaire"]]=$row_liste_partenaire["nom_partenaire"];
    $liste_structure_array[$row_liste_partenaire["id_partenaire"]]=$row_liste_partenaire["sigle_partenaire"];
    } }

    //Projet users
    $query_projet_user = $db ->prepare('SELECT * FROM t_projet_users WHERE projet_up=:id_projet');
    $query_projet_user->execute(array(':id_projet' => $id));
    $row_projet_user = $query_projet_user ->fetchAll();
    $totalRows_projet_user = $query_projet_user->rowCount();
    $projet_user_array = array();
    if($totalRows_projet_user>0){  foreach($row_projet_user as $row_projet_user){
    if(!isset($projet_user_array[$row_projet_user["projet_up"]])) $projet_user_array[$row_projet_user["projet_up"]]=$row_projet_user["personnel_up"]; else $projet_user_array[$row_projet_user["projet_up"]].=",".$row_projet_user["personnel_up"];
    } }

    //Users
    //if(isset($_SESSION["type_fonction"]) && $_SESSION["type_fonction"]==100)
    $query_personnel = $db ->prepare('SELECT P.*, F.fonction, S.sigle_partenaire as sigle, S.nom_partenaire as nom_structure, S.id_partenaire as id_structure FROM t_users P, t_fonction F, t_partenaires S WHERE FIND_IN_SET(:type_partenaire,S.type_partenaire) and F.structure=S.id_partenaire and F.id_fonction=P.fonction ORDER BY S.sigle_partenaire desc');
    $query_personnel->execute(array(':type_partenaire' => 1));
    $row_personnel = $query_personnel ->fetchAll();
    $totalRows_personnel = $query_personnel->rowCount();
    $User_array = $Nuser_array = array();
    if($totalRows_personnel>0){  foreach($row_personnel as $row_personnel1){
    $User_array[$row_personnel1["id_user"]]=$row_personnel1["fonction"];
    $Nuser_array[$row_personnel1["id_user"]]=$row_personnel1["nom"]." ".$row_personnel1["prenom"]." (".$row_personnel1["sigle"].")";
    } }


$query_parametres = $db ->prepare('SELECT * FROM t_dp_fpbf_dcs');
$query_parametres->execute();
$row_liste_parametre = $query_parametres ->fetchAll();
$totalRows_liste_parametre = $query_parametres->rowCount();
 $domaine_array = array();
    if($totalRows_liste_parametre>0){  foreach($row_liste_parametre as $row_liste_parametre){
    $domaine_array[$row_liste_parametre["id_parametre"]]=$row_liste_parametre["libelle_parametre"];
    } }
	
    //Montant projet bailleur
    $query_projet_cout = $db ->prepare('SELECT sum(montant) as montant, projet_bud FROM t_repartition_projet_budget WHERE projet_bud=:id_projet group by projet_bud');
    $query_projet_cout->execute(array(':id_projet' => $id));
    $row_projet_cout = $query_projet_cout ->fetchAll();
    $totalRows_projet_cout = $query_projet_cout->rowCount();
    $projet_cout_array = array();
    if($totalRows_projet_cout>0){  foreach($row_projet_cout as $row_projet_cout){
    $projet_cout_array[$row_projet_cout["projet_bud"]]=number_format($row_projet_cout["montant"], 0, ',', ' ');
    } }
    $onglet_array = array(0=>"Projets en cours",1=>"Projets clôturés");
}

$query_entete = $db ->prepare('SELECT nombre FROM t_config_cadre_resultat_projet WHERE projet=:projet LIMIT 1');
$query_entete->execute(array(':projet' => isset($id)?$id:0));
$row_entete = $query_entete ->fetch();
$totalRows_entete = $query_entete->rowCount();
$code_len = 0;
if($totalRows_entete>0) $code_len = $row_entete["nombre"];

$query_liste_activite = $db ->prepare('SELECT code,intitule, categorie_depense, id_cadre_resultat FROM t_cadre_resultat WHERE niveau=:niveau and projet=:projet order by code asc');
$query_liste_activite->execute(array(':niveau' => $code_len,':projet' => isset($id)?$id:0));
$row_liste_activite = $query_liste_activite ->fetchAll();
$totalRows_liste_activite = $query_liste_activite->rowCount();
$activite_array = array();

$query_liste_cout_decaisse = $db ->prepare('SELECT code_activite_ptba, SUM( if(taux_dollars_jour>0, montant_decaisse/taux_dollars_jour,0) ) AS montant FROM t_suivi_decaissement_ptba, t_ptba WHERE id_ptba=activite_ptba and t_ptba.projet=:projet GROUP BY code_activite_ptba');
$query_liste_cout_decaisse->execute(array(':projet' => isset($id)?$id:0));
$row_liste_cout_decaisse = $query_liste_cout_decaisse ->fetchAll();
$totalRows_liste_cout_decaisse = $query_liste_cout_decaisse->rowCount();
$tableauCoutDecaisse=array();
if($totalRows_liste_cout_decaisse>0){  foreach($row_liste_cout_decaisse as $row_liste_cout_decaisse){
$tableauCoutDecaisse[$row_liste_cout_decaisse["code_activite_ptba"]]=$row_liste_cout_decaisse["montant"];
} }

$query_liste_tache = $db ->prepare('SELECT code_activite_ptba, avg(taux_tache) as taux_niveau FROM ((SELECT sum(D.proportion) as taux_tache, G.id_activite  FROM t_type_tache D inner join t_groupe_tache G on D.id_groupe_tache=G.id_groupe_tache   WHERE G.valider="oui" Group BY id_activite) as taux inner join t_ptba on id_ptba=id_activite and t_ptba.projet=:projet) group by code_activite_ptba');
$query_liste_tache->execute(array(':projet' => isset($id)?$id:0));
$row_liste_tache = $query_liste_tache ->fetchAll();
$totalRows_liste_tache = $query_liste_tache->rowCount();
$tableauTache=array();
if($totalRows_liste_tache>0){  foreach($row_liste_tache as $row_liste_tache){
$tableauTache[$row_liste_tache["code_activite_ptba"]]=$row_liste_tache["taux_niveau"];
} }

	$query_liste_cout= $db ->prepare('SELECT activite_cr, sum(montant) as budget FROM t_projet_budget WHERE  projet=:projet group BY activite_cr ASC');
$query_liste_cout->execute(array(':projet' => isset($id)?$id:0));
$row_liste_cout = $query_liste_cout ->fetchAll();
$totalRows_liste_cout = $query_liste_cout->rowCount();
$activite_cr_cout=array();
if($totalRows_liste_cout>0){ foreach($row_liste_cout as $row_liste_cout){
$activite_cr_cout[$row_liste_cout["activite_cr"]]=$row_liste_cout["budget"];
 }}
?>
<!DOCTYPE html>
<html>
<head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <!-- Page title -->
        <title><?php print $config->sitename;?></title>
        <link rel="shortcut icon" type="image/ico" href="<?php print $config->icon_folder;?>/favicon.ico" />
        <meta name="keywords" content="<?php print $config->MetaKeys;?>" />
        <meta name="description" content="<?php print $config->MetaDesc;?>" />
        <meta name="author" content="<?php print $config->MetaAuthor;?>" />

        <!-- Vendor styles -->
        <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.css" />
        <link rel="stylesheet" href="vendor/metisMenu/dist/metisMenu.css" />
        <link rel="stylesheet" href="vendor/animate.css/animate.css" />
        <link rel="stylesheet" href="vendor/bootstrap/dist/css/bootstrap.css" />
        <link rel="stylesheet" href="vendor/sweetalert/lib/sweet-alert.css" />
        <link rel="stylesheet" href="vendor/datatables.net-bs/css/dataTables.bootstrap.min.css" />

        <!-- App styles -->
        <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/pe-icon-7-stroke.css" />
        <link rel="stylesheet" href="fonts/pe-icon-7-stroke/css/helper.css" />

        <!-- App custom styles -->
        <link rel="stylesheet" href="styles/style.css">
        <link rel="stylesheet" href="styles/style_fst.css">

        <!-- Vendor scripts -->
        <script src="vendor/jquery/dist/jquery.min.js"></script>
        <script src="vendor/jquery-ui/jquery-ui.min.js"></script>
        <script src="vendor/slimScroll/jquery.slimscroll.min.js"></script>
        <script src="vendor/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="vendor/jquery-flot/jquery.flot.js"></script>
        <script src="vendor/jquery-flot/jquery.flot.resize.js"></script>
        <script src="vendor/jquery-flot/jquery.flot.pie.js"></script>
        <script src="vendor/flot.curvedlines/curvedLines.js"></script>
        <script src="vendor/jquery.flot.spline/index.js"></script>
        <script src="vendor/metisMenu/dist/metisMenu.min.js"></script>
        <script src="vendor/iCheck/icheck.min.js"></script>
        <script src="vendor/peity/jquery.peity.min.js"></script>
        <script src="vendor/sparkline/index.js"></script>
        <script src="vendor/jquery-validation/jquery.validate.min.js"></script>

        <!-- DataTables -->
        <script src="vendor/datatables/media/js/jquery.dataTables.min.js"></script>
        <script src="vendor/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
        <!-- DataTables buttons scripts -->
        <script src="vendor/pdfmake/build/pdfmake.min.js"></script>
        <script src="vendor/pdfmake/build/vfs_fonts.js"></script>
        <script src="vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="vendor/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>

        <!-- App scripts -->
        <script src="scripts/homer.js"></script>
</head>
<body class="fixed-navbar fixed fixed-footer sidebar-scroll">
        <?php require_once "./theme_components/header.php"; ?>
        <?php require_once "./theme_components/main-menu.php"; ?>
<!-- Main Wrapper -->
<div id="wrapper">
<?php $vprojet=1; require_once "./theme_components/sub-header.php"; ?>
        <div class="content animate-panel">
                <div class="row">
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
    border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; } .project-label small {font-weight: 100; }
</style>

<script>
$("#mbreadcrumb").html('<div class="btn-circle-zone"><a href="./projets.php" class="btn btn-success btn-circle mgr-5" title="Retour" ><span title="Retour à la liste des projets" class="glyphicon glyphicon-arrow-left"></span></a></div>');
</script>

<?php if($totalRows_projet>0){ ?>

        <div class="row">

            <div class="col-md-8">

                <div class="font-bold m-b-sm">Détails</div>

                <div class="hpanel">
                    <div class="panel-body">

                        <div class="pull-right">
                            <button class="btn btn-success btn-xs"><?php echo $onglet_array[$row_projet['statut']]; ?></button>
                            <a href="./prodoc_gen.php?id=<?php echo $row_projet['id_projet']; ?>" class="btn btn-xs" title="Télécharger le document projet"><button class="btn btn-default btn-xs"><strong>PRODOC</strong></button></a>
                            <?php //echo do_link("","./docs1.php?id=".$row_projet['id_projet'],"Télécharger le document","","view","./","btn btn-default btn-xs","",0,"",$nfile);; ?>
                        </div>
                        <h2 class="m-t-none"><?php echo $row_projet['code_projet']." : ".$row_projet['sigle_projet']; ?></h2>
                        <h3><?php echo $row_projet['intitule_projet']; ?></h3>
                        <div class="m-t-xs">
                            <strong>Date de signature :</strong> <?php echo date_reg($row_projet['date_signature'],"/"); ?><br/>
                            <strong>Modalité de financement :</strong> <?php echo $row_projet['modalite_financement']; ?><br/>
                            <strong>Personnes dédiées :</strong> <?php $a = array(); if(isset($projet_user_array[$id])) $a = explode(",",$projet_user_array[$id]); if(count($a)>0){ $c = array(); foreach($a as $b){ if(isset($User_array[$b])) $c[]="<span title=\"".$Nuser_array[$b]."\">".$User_array[$b]."</span>"; } echo implode('; ',$c); } else echo "Aucune"; ?><br/>
							 <strong>Domaine d'intervention prioritaire :</strong> <?php if(isset( $domaine_array[$row_projet['domaine_intervention_prioritaire']])) echo  $domaine_array[$row_projet['domaine_intervention_prioritaire']]; else echo "-"; ?>
							 <!-- <strong>Zone de couverture</strong> <?php //echo $row_projet['zone']; ?>-->
                        </div>

                       
                    </div>
                </div>

                <div class="hpanel">

                    <div class="panel-body">

                        <p>
                           
                        </p>

                        <div class="table-responsive">
                        <table class="table table-hover table-bordered table-striped">
                            <tbody>
                            <tr>
                                <td>
                                   Statut                                </td>
                                <td class="issue-info">
                                    <a href="#">
                                        Activités clées                                   </a>
                                    <br/></td>
                                <td nowrap>
                                    <div align="center">Budget  ($) </div></td>
                                <td nowrap>
                                    <div align="center">Décaissé ($) </div></td>
                                <td class="text-right">Décaissement (%) </td>
                                <td class="text-right">Avancement technique(%) </td>
                            </tr>
							<?php if($totalRows_liste_activite>0){  foreach($row_liste_activite as $row_liste_activite1){  ?>
                            <tr>
                                <td><?php if(isset($tableauCoutDecaisse[$row_liste_activite1["code"]])) echo "<span class=\"label label-success\">Démarré</span>"; else echo "<span class=\"label label-info\">En attente</span>" ; ?>                              </td>
                                <td class="issue-info">
                                   
                                   
                                    <small> <?php echo $row_liste_activite1["intitule"]; ?></small>  <br/>    <a href="#">
                                        <?php echo $row_liste_activite1["categorie_depense"]; ?>                                    </a>                            </td>
                                <td><div align="right"><a href="#"><span class="text-right">
                                  <?php if(isset($activite_cr_cout[$row_liste_activite1["id_cadre_resultat"]]) && $activite_cr_cout[$row_liste_activite1["id_cadre_resultat"]]>0) echo number_format($activite_cr_cout[$row_liste_activite1["id_cadre_resultat"]], 0, ',', ' '); ?>
                                </span></a></div></td>
                                <td>
                                   <div align="right">
                                     <?php if(isset($tableauCoutDecaisse[$row_liste_activite1["code"]])) echo number_format($tableauCoutDecaisse[$row_liste_activite1["code"]], 0, ',', ' '); ?>
                                   </div></td>
                                <td class="text-right"><?php if(isset($tableauCoutDecaisse[$row_liste_activite1["code"]]) && isset($activite_cr_cout[$row_liste_activite1["id_cadre_resultat"]]) && $activite_cr_cout[$row_liste_activite1["id_cadre_resultat"]]>0) echo number_format(($tableauCoutDecaisse[$row_liste_activite1["code"]]/$activite_cr_cout[$row_liste_activite1["id_cadre_resultat"]])*100, 1, ',', ' '); ?></td>
                                <td class="text-right"><?php if(isset($tableauTache[$row_liste_activite1["code"]])) echo $tableauTache[$row_liste_activite1["code"]];?></td>
                            </tr>
							<?php }} ?>
                            </tbody>
                        </table>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-4">

                <div class="font-bold m-b-sm">Statistiques</div>

                <div class="hpanel stats">
                    <div class="panel-body">

                        <div>
                            <i class="pe-7s-cash fa-4x"></i>
                            <h1 class="m-xs text-success pull-right"><?php  if(isset($projet_cout_array[$id])) echo $projet_cout_array[$id]." <small> USD</smal>";  else echo "-"; ?></h1>
                        </div>

                        <p>
                            <strong>Budget total du projet</strong> (par agence récipiendaire) :
                        </p>
                            <div class="row">
<?php
    //Montant projet bailleur
    $query_projet_cout = $db ->prepare('SELECT sum(montant) as montant, structure_bud FROM t_repartition_projet_budget WHERE projet_bud=:id_projet group by structure_bud');
    $query_projet_cout->execute(array(':id_projet' => $row_projet['id_projet']));
    $row_projet_cout = $query_projet_cout ->fetchAll();
    $totalRows_projet_cout = $query_projet_cout->rowCount();
    if($totalRows_projet_cout>0){ $i=1; foreach($row_projet_cout as $row_projet_cout){
?>
                                <div class="col-xs-6">
                                    <small><h5><u><?php echo isset($liste_structure_array[$row_projet_cout["structure_bud"]])?$liste_structure_array[$row_projet_cout["structure_bud"]]:'-'; ?></u></h5></small>
                                    <h4><?php echo number_format($row_projet_cout["montant"], 0, ',', ' '); ?> <span style="font-size:10px">$US</span></h4>
                                </div>
<?php $i++; echo $i%2==0?"<div class='cleaar h0'>&nbsp;</div>":""; } } ?>
                            </div>
                        </div>
                </div>

                <div class="font-bold m-b-sm">Agences</div>

                <div class="row">
<?php if(isset($row_projet['agence_lead']) && isset($liste_structure_array[$row_projet['agence_lead']])){ $id = $row_projet['agence_lead']; ?>
                    <div class="col-md-6">
                        <div class="hpanel hblue">
                            <div class="panel-body text-center">
                                <img alt="logo" class="img-circle img-small" src="<?php echo (file_exists("./images/partenaire/img_$id.jpg"))?"./images/partenaire/img_$id.jpg":"./images/partenaire/none.png"; ?>">
                                <div class="m-t-sm">
                                    <strong><?php echo $liste_structure_array[$row_projet['agence_lead']]; ?></strong>
                                    <p class="small"><?php echo $liste_structure_arrayV[$row_projet['agence_lead']]; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
<?php } ?>
<?php if(isset($row_projet['autres_agences_recipiendaires']) && !empty($row_projet['autres_agences_recipiendaires'])){ $a = explode(",",$row_projet['autres_agences_recipiendaires']); if(count($a)>0){ $i=1; foreach($a as $id) { if(isset($liste_structure_array[$id])){ ?>
                    <div class="col-md-6">
                        <div class="hpanel hblue">
                            <div class="panel-body text-center">
                                <img alt="logo" class="img-circle img-small" src="<?php echo (file_exists("./images/partenaire/img_$id.jpg"))?"./images/partenaire/img_$id.jpg":"./images/partenaire/none.png"; ?>">
                                <div class="m-t-sm">
                                    <strong><?php echo $liste_structure_array[$id]; ?></strong>
                                    <p class="small"><?php echo $liste_structure_arrayV[$id]; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
<?php $i++; echo $i%2==0?"<div class='cleaar h0'>&nbsp;</div>":""; } } } } ?>
                </div>

                <div class="font-bold m-b-sm">Partenaires</div>
                <div class="row">
<?php
    //Partenaires
    $query_partenaire = $db ->prepare('SELECT * FROM t_partenaires WHERE FIND_IN_SET(:type_partenaire,type_partenaire) OR FIND_IN_SET(:type_partenaire1,type_partenaire) OR FIND_IN_SET(:type_partenaire2,type_partenaire) ORDER BY RAND() LIMIT 3');
    $query_partenaire->execute(array(':type_partenaire' => 2,':type_partenaire1' => 3,':type_partenaire2' => 4));
    $row_liste_partenaire = $query_partenaire ->fetchAll();
    $totalRows_liste_partenaire = $query_partenaire->rowCount();
    if($totalRows_liste_partenaire>0){ $i=1; foreach($row_liste_partenaire as $row_liste_partenaire){ $id = $row_liste_partenaire["id_partenaire"];
?>
                    <div class="col-md-6">
                        <div class="hpanel hblue">
                            <div class="panel-body text-center">
                                <img alt="logo" class="img-circle img-small" src="<?php echo (file_exists("./images/partenaire/img_$id.jpg"))?"./images/partenaire/img_$id.jpg":"./images/partenaire/none.png"; ?>">
                                <div class="m-t-sm">
                                    <strong><?php echo $row_liste_partenaire["sigle_partenaire"]; ?></strong>
                                    <p class="small"><?php echo $row_liste_partenaire["nom_partenaire"]; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
<?php } $i++; echo $i%2==0?"<div class='cleaar h0'>&nbsp;</div>":""; } ?>
                </div>
   
  </div></div>
   

<?php } else{ ?>
<div class="col-md-12 col-lg-12">
        <div <?php echo 'class="hpanel '.$Panel_Style.'"'; ?>>
                <div class="panel-heading">
                        <div class="panel-tools">
                                <a class="showhide"><i class="fa fa-chevron-up"></i></a>
                        </div>
                    <span class="text-primary"><i class="fa fa-reorder"></i> Partenaire</span>
                </div>
                <div class="panel-body">
                        <h1 align="center">Aucun partenaire selectionnée !</h1>
                </div>
        </div>
</div>
<?php } ?>


        </div>
    </div>
    <?php require_once "./theme_components/footer.php"; ?>
</div>

</body>
</html>