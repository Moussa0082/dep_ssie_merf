<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & DÃ©veloppement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
$editFormAction = $_SERVER['PHP_SELF'];
$currentPage = $_SERVER['PHP_SELF']."?composante=0";
/*if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/

$mode_calcul = array("SOMME"=>"SUM","MOYENNE"=>"AVG","COMPTER"=>"COUNT","COMPTAGE DISTINCTEMENT"=>"COUNT","COMPTER TOUT"=>"COUNT");
$array_indic = array("OUI/NON","texte");

$query_liste_composante = sprintf("SELECT * FROM ".$database_connect_prefix."referentiel_indicateur ORDER BY code_ref_ind");
try{
    $liste_composante = $pdar_connexion->prepare($query_liste_composante);
    $liste_composante->execute();
    $row_liste_composante = $liste_composante ->fetchAll();
    $totalRows_liste_composante = $liste_composante->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$liste_indicateur_mode_array = $liste_indicateur_id_array = array();
if($totalRows_liste_composante > 0) { foreach($row_liste_composante as $row_liste_composante1){
 $liste_indicateur_mode_array[$row_liste_composante1['code_ref_ind']] = $row_liste_composante1['mode_calcul'];
 $liste_indicateur_id_array[$row_liste_composante1['code_ref_ind']] = $row_liste_composante1['id_ref_ind'];
}}


$query_liste_indicateur_calcul = "SELECT indicateur_ref, id_ref_ind, code_ref_ind, intitule_ref_ind FROM referentiel_indicateur, calcul_indicateur_simple_ref
WHERE FIND_IN_SET( id_ref_ind, indicateur_simple ) and mode_calcul = 'Unique' ORDER BY indicateur_ref";
try{
    $liste_indicateur_calcul = $pdar_connexion->prepare($query_liste_indicateur_calcul);
    $liste_indicateur_calcul->execute();
    $row_liste_indicateur_calcul = $liste_indicateur_calcul ->fetchAll();
    $totalRows_liste_indicateur_calcul = $liste_indicateur_calcul->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_indicateur_simple_array=$liste_affiche_indicateur_simple_array=array();
if($totalRows_liste_indicateur_calcul>0){
foreach($row_liste_indicateur_calcul as $row_liste_indicateur_calcul){ $liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]=(isset($liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]))?$liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']].$row_liste_indicateur_calcul['code_ref_ind'].",":$row_liste_indicateur_calcul['code_ref_ind'].",";

 $liste_affiche_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]=(isset($liste_affiche_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]))?$liste_affiche_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']].$row_liste_indicateur_calcul['code_ref_ind'].", ":$row_liste_indicateur_calcul['code_ref_ind'].", ";
} }

$query_liste_ind_ratio = "SELECT indicateur_ref, numerateur, denominateur, coefficient FROM ratio_indicateur_ref order by indicateur_ref";
try{
    $liste_ind_ratio = $pdar_connexion->prepare($query_liste_ind_ratio);
    $liste_ind_ratio->execute();
    $row_liste_ind_ratio = $liste_ind_ratio ->fetchAll();
    $totalRows_liste_ind_ratio = $liste_ind_ratio->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$liste_num_ratio_array = array();
$liste_deno_ratio_array = array();
if($totalRows_liste_ind_ratio>0){
foreach($row_liste_ind_ratio as $row_liste_ind_ratio){
 $liste_num_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["numerateur"];
  $liste_deno_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = ($row_liste_ind_ratio["denominateur"]==-1)?$row_liste_ind_ratio["coefficient"]." / 1)":$row_liste_ind_ratio["denominateur"];
}}


$query_liste_code_ref = "SELECT code_ref_ind, id_ref_ind FROM referentiel_indicateur order by code_ref_ind";
try{
    $liste_code_ref = $pdar_connexion->prepare($query_liste_code_ref);
    $liste_code_ref->execute();
    $row_liste_code_ref = $liste_code_ref ->fetchAll();
    $totalRows_liste_code_ref = $liste_code_ref->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$liste_code_ref_array = array();
if($totalRows_liste_code_ref>0){
foreach($row_liste_code_ref as $row_liste_code_ref){
 $liste_code_ref_array[$row_liste_code_ref["id_ref_ind"]] = $row_liste_code_ref["code_ref_ind"];
}}

//Valeur de suivi
$cible_val_array = $cible_val_txt_array = array();

$query_cible_indicateur = "SELECT s.indicateur_cr, s.annee, sum(s.valeur_suivi) as valeur_suivi, s.valeur_txt, r.unite FROM   ".$database_connect_prefix."suivi_indicateur_cmr s, ".$database_connect_prefix."referentiel_indicateur r WHERE s.projet='".$_SESSION['clp_projet']."' and r.id_ref_ind = s.indicateur_cr group by s.annee, s.indicateur_cr";
try{
    $cible_indicateur = $pdar_connexion->prepare($query_cible_indicateur);
    $cible_indicateur->execute();
    $row_cible_indicateur = $cible_indicateur ->fetchAll();
    $totalRows_cible_indicateur = $cible_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_cible_indicateur>0){
foreach($row_cible_indicateur as $row_cible_indicateur){
   if(!in_array($row_cible_indicateur["unite"],$array_indic))
   {
     if(!isset($cible_val_array[$row_cible_indicateur["indicateur_cr"]][$row_cible_indicateur["annee"]])) $cible_val_array[$row_cible_indicateur["indicateur_cr"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_suivi"];
     else $cible_val_array[$row_cible_indicateur["indicateur_cr"]][$row_cible_indicateur["annee"]]+=$row_cible_indicateur["valeur_suivi"];
   }
   else
   {
     $cible_val_txt_array[$row_cible_indicateur["indicateur_cr"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_txt"];
   }
}}

 //cmr
  $cmr_array =array();
  $query_cmr = "SELECT * FROM ".$database_connect_prefix."referentiel_indicateur WHERE type_ref_ind=1 and mode_calcul='Unique' order by intitule_ref_ind";
  try{
    $cmr = $pdar_connexion->prepare($query_cmr);
    $cmr->execute();
    $row_cmr = $cmr ->fetchAll();
    $totalRows_cmr = $cmr->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  if($totalRows_cmr>0){ foreach($row_cmr as $row_cmr){
  $cmr_array[$row_cmr["id_ref_ind"]]=$row_cmr["intitule_ref_ind"]."|".$row_cmr["unite"];  } }

   
      $tab_referentiel_array =$tab_type_array =array();
  $query_liste_ind_projet = "SELECT referentiel, 'Produit' as type FROM ".$database_connect_prefix."indicateur_produit_cmr where projet_prd='".$_SESSION["clp_projet"]."' and referentiel!=0
  
  union SELECT 	referentiel , 'Effet' as type FROM ".$database_connect_prefix."indicateur_resultat_cmr, indicateur_resultat, resultat where id_resultat=resultat and id_indicateur_resultat=indicateur_res and resultat.projet='".$_SESSION["clp_projet"]."' and referentiel!=0
  
  union SELECT 	referentiel , 'Objectif spécifique' as type FROM ".$database_connect_prefix."indicateur_objectif_specifique_cmr, indicateur_objectif_specifique, objectif_specifique where id_objectif_specifique=objectif_specifique and id_indicateur_objectif_specifique=indicateur_os and objectif_specifique.projet='".$_SESSION["clp_projet"]."' and referentiel!=0
  
  union SELECT 	referentiel, 'Impact' as type FROM indicateur_objectif_global_cmr, indicateur_objectif_global where id_indicateur_objectif_global=indicateur_og and indicateur_objectif_global.projet='".$_SESSION["clp_projet"]."' and referentiel!=0";
    try{
    $liste_ind_projet = $pdar_connexion->prepare($query_liste_ind_projet);
    $liste_ind_projet->execute();
    $row_liste_ind_projet = $liste_ind_projet ->fetchAll();
    $totalRows_liste_ind_projet = $liste_ind_projet->rowCount();
	}catch(Exception $e){ die(mysql_error_show_message($e)); }
  if($totalRows_liste_ind_projet>0){ foreach($row_liste_ind_projet as $row_liste_ind_projet){
  $tab_referentiel_array[]=$row_liste_ind_projet["referentiel"];
  $tab_type_array[$row_liste_ind_projet["referentiel"]]=$row_liste_ind_projet["type"];  } }
  
  $query_liste_view_indicateur = $db ->prepare('SELECT Nom_View, Indicateur FROM t_rapport_indicateur C WHERE Group_By="ANNEE" and Id_Projet=:projet');
$query_liste_view_indicateur->execute(array(':projet' => isset($_SESSION['clp_projet'])?$_SESSION['clp_projet']:0));
$row_liste_view_indicateur = $query_liste_view_indicateur ->fetchAll();
$totalRows_liste_view_indicateur = $query_liste_view_indicateur->rowCount();
$liste_ind_view_array = array();
if($totalRows_liste_view_indicateur>0){  foreach($row_liste_view_indicateur as $row_liste_view_indicateur0){
$liste_ind_view_array[$row_liste_view_indicateur0["Indicateur"]] = $row_liste_view_indicateur0["Nom_View"];
} }

//print_r($liste_ind_view_array);exit;
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <title><?php print $config->sitename;?></title>

  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone;?>" />

  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

  <meta name="keywords" content="<?php print $config->MetaKeys;?>" />

  <meta name="description" content="<?php print $config->MetaDesc;?>" />

  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->

  <meta name="author" content="<?php print $config->MetaAuthor;?>" />

  <!--<meta charset="utf-8">-->

  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>



  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->

  <link href="<?php print $config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/plugins.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/login.css" rel="stylesheet" type="text/css"/>

  <link rel="stylesheet" href="<?php print $config->theme_folder;?>/fontawesome/font-awesome.min.css">

  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->theme_folder;?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->

  <!--[if IE 8]><link href="<?php print $config->theme_folder;?>/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->

  <link href='<?php print $config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/jquery-1.10.2.min.js"></script>

  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>

  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/lodash.compat.min.js"></script>

  <!--[if lt IE 9]><script src="<?php print $config->script_folder;?>/libs/html5shiv.js"></script><![endif]-->

  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>

  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/breakpoints.js"></script>

  <script type="text/javascript" src="plugins/respond/respond.min.js"></script>

  <script type="text/javascript" src="plugins/cookie/jquery.cookie.min.js"></script>

  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.min.js"></script>

  <script type="text/javascript" src="plugins/slimscroll/jquery.slimscroll.horizontal.min.js"></script>

  <!--[if lt IE 9]><script type="text/javascript" src="plugins/flot/excanvas.min.js"></script><![endif]-->

  <!--<script type="text/javascript" src="plugins/sparkline/jquery.sparkline.min.js"></script>

  <script type="text/javascript" src="plugins/flot/jquery.flot.min.js"></script>

  <script type="text/javascript" src="plugins/flot/jquery.flot.tooltip.min.js"></script>

  <script type="text/javascript" src="plugins/flot/jquery.flot.resize.min.js"></script>

  <script type="text/javascript" src="plugins/flot/jquery.flot.time.min.js"></script>

  <script type="text/javascript" src="plugins/flot/jquery.flot.growraf.min.js"></script>

  <script type="text/javascript" src="plugins/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>

  <script type="text/javascript" src="plugins/daterangepicker/moment.min.js"></script>

  <script type="text/javascript" src="plugins/daterangepicker/daterangepicker.js"></script>-->

  <script type="text/javascript" src="plugins/blockui/jquery.blockUI.min.js"></script>

  <script type="text/javascript" src="plugins/pickadate/picker.js"></script>

  <script type="text/javascript" src="plugins/pickadate/picker.date.js"></script>

  <script type="text/javascript" src="plugins/pickadate/picker.time.js"></script>

  <script type="text/javascript" src="plugins/bootstrap-colorpicker/bootstrap-colorpicker.min.js"></script>

  <script type="text/javascript" src="plugins/fullcalendar/fullcalendar.min.js"></script>

  <script type="text/javascript" src="plugins/noty/jquery.noty.js"></script>

  <script type="text/javascript" src="plugins/noty/layouts/top.js"></script>

  <script type="text/javascript" src="plugins/noty/themes/default.js"></script>

  <script type="text/javascript" src="plugins/uniform/jquery.uniform.min.js"></script>

  <script type="text/javascript" src="plugins/select2/select2.min.js"></script>

  <script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>

  <script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>

  <script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/app.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/plugins.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/plugins.form-components.js"></script>

<!--

  <script type="text/javascript" src="<?php print $config->script_folder;?>/custom.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/pages_calendar.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/charts/chart_filled_blue.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/charts/chart_simple.js"></script>-->

 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>

 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/login.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/ui_general.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/form_validation.js"></script>

 <script>$(document).ready(function(){Login.init()});</script>

 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init()});</script>

</head>

<body>

 <header class="header navbar navbar-fixed-top" role="banner">

    <?php include_once ("includes/header.php");?>

 </header>

<div id="container">

    <div id="sidebar" class="sidebar-fixed">

        <div id="sidebar-content">

            <?php include_once ("includes/menu_top.php");?>

        </div>

        <div id="divider" class="resizeable"></div>

    </div>



    <div id="content">

        <div class="container">

            <div class="crumbs">

                <?php include_once ("includes/sous_menu.php");?>

            </div>

        <div class="page-header">

            <div class="p_top_5">

<!-- Site contenu ici -->
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }

</style>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Cadre de mesure de r&eacute;sultats <?php if(isset($ugl)) echo "(".$row_nom_ugl["nom_ugl"].")"; ?></h4>
    <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2){?>
<?php include_once 'modal_add.php'; ?>

    <?php } ?>
</div>

<div class="widget-content">
<table class="table table-striped table-bordered table-hover table-responsive  datatable dataTable hide_befor_load" id="DataTables_Table" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
 <!-- <th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">&nbsp;</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">composante</th>-->
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Code</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Indicateur </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Unit&eacute; </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Type</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Mode de calcul</th>
<?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" <?php if($i==date("Y")) { ?>style="background-color:#FFCC33"   <?php } ?>><strong><?php echo $i; ?></strong>&nbsp;</th>
<?php } ?>
<!--<th  class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Valeur r&eacute;elle </th>-->
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
<th class="sorting" role="" tabindex="0" aria-controls="" aria-label="" width="80">Actions</th>
<?php } ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php if($totalRows_liste_composante>0) { $i=0;$totalrfd=$val_view_annee_array = array(); foreach($row_liste_composante as $row_liste_composante){ if(1==1) {  $id = $row_liste_composante['code_ref_ind']; $id_ref = $row_liste_composante['id_ref_ind']; 


/* foreach($row_liste_cadre_strategique as $row_liste_cadre_strategique){ $id = $row_liste_cadre_strategique["id_indicateur_cr"]; $code = $row_liste_cadre_strategique["code_indicateur_cr"]; $parent = $row_liste_cadre_strategique["code_cr"]; */
if(isset($liste_ind_view_array[$id]) && !empty($liste_ind_view_array[$id])) {
$query_vval_annee = $db ->prepare("SHOW TABLES LIKE '".$liste_ind_view_array[$id]."'");
//$query_vval_annee = $db->prepare($query_vval_annee); //$db needs to be PDO instance
$query_vval_annee->execute();
$row_vval_annee = $query_vval_annee ->fetchAll();
$totalRows_vval_annee = $query_vval_annee->rowCount();

//$table_name = 'your_table_here'; 
if($totalRows_vval_annee>0)
{
$query_vval_annee = 'SELECT VALEUR, ANNEE FROM '.$liste_ind_view_array[$id].'  ORDER BY annee asc';
$query_vval_annee = $db->prepare($query_vval_annee); //$db needs to be PDO instance
$query_vval_annee->execute();
$row_vval_annee = $query_vval_annee ->fetchAll();
$totalRows_vval_annee = $query_vval_annee->rowCount();
if($totalRows_vval_annee>0){ foreach($row_vval_annee as $row_vval_annee) { $val_view_annee_array[$id][$row_vval_annee["ANNEE"]] = $row_vval_annee["VALEUR"];  $totalrfd[$id]=$row_vval_annee["VALEUR"];} }
}}




 ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo (!empty($row_liste_composante['code_ref_ind']))?$row_liste_composante['code_ref_ind']:""; ?></td>
<td class=" "><?php echo (!empty($row_liste_composante['intitule_ref_ind']))?$row_liste_composante['intitule_ref_ind']:""; ?></td>
<td class=" "><?php echo $row_liste_composante['unite']; ?></td>
<td class=" "><?php  if(isset( $tab_type_array[$row_liste_composante["id_ref_ind"]])) echo  $tab_type_array[$row_liste_composante["id_ref_ind"]]; ?></td>
<td class=" " ><?php if(isset($row_liste_composante['mode_calcul']) && $row_liste_composante['mode_calcul']=="Unique") { echo "Unique"; } elseif (isset($row_liste_composante['mode_calcul']) && $row_liste_composante['mode_calcul']=="Ratio") {?>
<?php echo $row_liste_composante['mode_calcul'];
					  if(isset($liste_num_ratio_array[$row_liste_composante['id_ref_ind']])
					  && isset($liste_deno_ratio_array[$row_liste_composante['id_ref_ind']])
					  && isset($liste_code_ref_array[$liste_num_ratio_array[$row_liste_composante['id_ref_ind']]])
					  && isset($liste_code_ref_array[$liste_deno_ratio_array[$row_liste_composante['id_ref_ind']]])) { echo " (".$liste_code_ref_array[$liste_num_ratio_array[$row_liste_composante['id_ref_ind']]]." / ".$liste_code_ref_array[$liste_deno_ratio_array[$row_liste_composante['id_ref_ind']]].")"; $list_indic_select = array($liste_code_ref_array[$liste_num_ratio_array[$row_liste_composante['id_ref_ind']]],$liste_code_ref_array[$liste_deno_ratio_array[$row_liste_composante['id_ref_ind']]]); $sign = "ratio"; } elseif(isset($liste_num_ratio_array[$row_liste_composante['id_ref_ind']]) && isset($liste_code_ref_array[$liste_num_ratio_array[$row_liste_composante['id_ref_ind']]])){ echo " (".$liste_code_ref_array[$liste_num_ratio_array[$row_liste_composante['id_ref_ind']]]."*".$liste_deno_ratio_array[$row_liste_composante['id_ref_ind']]; $list_indic_select = array($liste_code_ref_array[$liste_num_ratio_array[$row_liste_composante['id_ref_ind']]],$liste_deno_ratio_array[$row_liste_composante['id_ref_ind']]); $sign = "*"; } ?><!--</a>-->
                     <?php } else { ?>
                     <!--<a onclick="get_content('edit_calcul_indicateur_ref.php','iden=<?php echo $row_liste_composante['code_ref_ind']."&type_ind=".$row_liste_composante['type_ref_ind']; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Elements de calcul d'indicateur " class="thickbox Add"  dir="">--><?php echo $row_liste_composante['mode_calcul']; ?>

					 <?php if(isset($liste_affiche_indicateur_simple_array[$row_liste_composante['id_ref_ind']])){echo " (".substr($liste_affiche_indicateur_simple_array[$row_liste_composante['id_ref_ind']],0,strlen($liste_affiche_indicateur_simple_array[$row_liste_composante['id_ref_ind']])-1).")"; $list_indic_select = explode(",",substr($liste_indicateur_simple_array[$row_liste_composante['id_ref_ind']],0,strlen($liste_indicateur_simple_array[$row_liste_composante['id_ref_ind']])-1)); } ?><!--</a>-->
                     <?php } ?></td>
 <?php $val_reel = 0; for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
                     <td nowrap="nowrap" class=" " align="right"><strong id="ind_<?php echo $i."_".$id; ?>">
				      <?php $valeur = 0;  $n = 0; $s = ""; $deno =  0; $nume = 0;
if(!isset($liste_ind_view_array[$id]) && $row_liste_composante['mode_calcul']=='Unique')
{
   if(!in_array($row_liste_composante["unite"],$array_indic))
   {
      if(isset($cible_val_array[$liste_indicateur_id_array[$id]][$i]) && $cible_val_array[$liste_indicateur_id_array[$id]][$i]!=0)
      {
        $val_reel += $cible_val_array[$liste_indicateur_id_array[$id]][$i];
        echo number_format($cible_val_array[$liste_indicateur_id_array[$id]][$i], 0, ',', ' ');
      }
      else echo "-";
   }
   else
   {
      if(isset($cible_val_txt_array[$liste_indicateur_id_array[$id]][$i]) && $cible_val_txt_array[$liste_indicateur_id_array[$id]][$i]!=0)
      {
        $val_reel += $cible_val_txt_array[$liste_indicateur_id_array[$id]][$i];
        echo $cible_val_txt_array[$liste_indicateur_id_array[$id]][$i];
      }
      else echo "-";
   }
   } 
   elseif(isset($liste_ind_view_array[$id]) && $row_liste_composante['mode_calcul']=='Unique')
{

if(isset($val_view_annee_array[$id][$i])) echo $val_view_annee_array[$id][$i];
} 
?>
                  </strong>&nbsp;</td>
                       <?php } ?>

<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
<td class=" " align="center">
<?php if(!isset($indicateur_dynamique[$liste_indicateur_id_array[$id]]) && $row_liste_composante['mode_calcul']=='Unique')
echo do_link("","","Valeurs Suivies","Suivre","","./","","get_content('new_suivi_referentiel.php','code_act=$id&id_ref=$id_ref','modal-body_add',this.title,'iframe');",1,"margin:0px 5px;",$nfile);
elseif($row_liste_composante['mode_calcul']=='Unique') echo do_link("","","Valeurs Suivies","D&eacute;tails","","./","","get_content('new_suivi_referentiel.php','code_act=$id&id_ref=$id_ref','modal-body_add',this.title,'iframe');",1,"margin:0px 5px;",$nfile);
?></td>
<?php } ?>
</tr>
<?php } }} ?>
</tbody></table>

    </div>
</div>
</div>

<!-- Fin Site contenu ici -->

           

        </div>



        </div>

    </div>

    <?php include_once ("includes/footer.php");?>

</div>

</body>

</html>