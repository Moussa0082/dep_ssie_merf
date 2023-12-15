<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
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
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $acteur="";
	if(!empty($_POST['acteur'])) { foreach($_POST['acteur'] as $vacteur) { $acteur=$acteur.",".$vacteur; } }
	$cible = $_POST['cible_cmr'];
if(trim(strtolower($cible))=="oui") $cible = 0;
if(trim(strtolower($cible))=="non") $cible = 1;
if(trim(strtolower($cible))=="n/a") $cible = -1;

$ciblermp = (isset($_POST['cible_rmp']))?$_POST['cible_rmp']:"";
if(trim(strtolower($ciblermp))=="oui") $ciblermp = 0;
if(trim(strtolower($ciblermp))=="non") $ciblermp = 1;
if(trim(strtolower($ciblermp))=="n/a") $ciblermp = -1;

/*$reference = $_POST['reference_cmr'];
if(trim(strtolower($reference))=="oui") $reference = 0;
if(trim(strtolower($reference))=="non") $reference = 1;
if(trim(strtolower($reference))=="n/a") $reference = -1;*/
  $insertSQL = sprintf("INSERT INTO indicateur_produit_cmr (indicateur_prd, referentiel, intitule_indicateur, cible_cmr,cible_rmp, responsable_collecte, cle, code_irprd, personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, '$personnel', '$date')",
					   GetSQLValueString($_POST['indicateur_cl'], "int"),
                       GetSQLValueString((isset($_POST['referentiel'])?$_POST['referentiel']:0), "int"),
	   				   GetSQLValueString($_POST['indicateur'], "text"),
					   GetSQLValueString($cible, "double"),
                       GetSQLValueString($ciblermp, "double"),
					   GetSQLValueString($acteur, "text"),
                       GetSQLValueString($_POST['cle'], "int"),
   					 // GetSQLValueString($_POST['beneficiaire'], "int"),
					   GetSQLValueString(trim($_POST['code_irprd']), "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));   exit(0);
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
      $id = intval($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from indicateur_produit_cmr WHERE id_indicateur=%s",
                           GetSQLValueString($id, "int"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id']; $acteur="";
	if(!empty($_POST['acteur'])) { foreach($_POST['acteur'] as $vacteur) { $acteur=$acteur.",".$vacteur; } }
		$cible = $_POST['cible_cmr'];
if(trim(strtolower($cible))=="oui") $cible = 0;
if(trim(strtolower($cible))=="non") $cible = 1;
if(trim(strtolower($cible))=="n/a") $cible = -1;

/*$reference = $_POST['reference_cmr'];
if(trim(strtolower($reference))=="oui") $reference = 0;
if(trim(strtolower($reference))=="non") $reference = 1;
if(trim(strtolower($reference))=="n/a") $reference = -1;*/

$ciblermp = (isset($_POST['cible_rmp']))?$_POST['cible_rmp']:"";
if(trim(strtolower($ciblermp))=="oui") $ciblermp = 0;
if(trim(strtolower($ciblermp))=="non") $ciblermp = 1;
if(trim(strtolower($ciblermp))=="n/a") $ciblermp = -1;

  $insertSQL = sprintf("UPDATE indicateur_produit_cmr SET  indicateur_prd=%s, referentiel=%s,  intitule_indicateur=%s, cible_cmr=%s, cible_rmp=%s, responsable_collecte=%s, cle=%s,  code_irprd=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur='$c'",
					   GetSQLValueString($_POST['indicateur_cl'], "int"),
                       GetSQLValueString((isset($_POST['referentiel'])?$_POST['referentiel']:0), "int"),
	   				   GetSQLValueString($_POST['indicateur'], "text"),
					   GetSQLValueString($cible, "double"),
                       GetSQLValueString($ciblermp, "double"),
   					   GetSQLValueString($acteur, "text"),
                       GetSQLValueString($_POST['cle'], "int"),
   					 // GetSQLValueString($_POST['beneficiaire'], "int"),
					   GetSQLValueString(trim($_POST['code_irprd']), "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));   exit(0);
  }

}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{   $id_ind=$_POST['id'];
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  $annee=$_POST['annee'];
  $valind=$_POST['valind'];
  $id_region=$_POST['id_region'];
  //suppression

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  //$idzone=$id_zone[$key];
  $query_sup_cible_indicateur = "DELETE FROM cible_cmr_produit WHERE indicateur_produit=$id_ind";
  $Result1 = mysql_query($query_sup_cible_indicateur, $pdar_connexion) or die(mysql_error());


  // `indicateur` int(11) NOT NULL,   `mois` int(11) DEFAULT NULL,  `cible` float DEFAULT '0',
  foreach ($id_region as $key => $value)
  {
  	if(isset($valind[$key]) && $valind[$key]!=NULL) {
  	if(trim(strtolower($valind[$key]=="oui"))) $valind[$key] = "0";
  elseif(trim(strtolower($valind[$key]=="non"))) $valind[$key] = "1";

    $insertSQL = sprintf("INSERT INTO cible_cmr_produit  (indicateur_produit, zone, annee, valeur_cible, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, '$personnel', '$date')",
  					   GetSQLValueString($id_ind, "int"),
  					     GetSQLValueString($id_region[$key], "int"),
  					   GetSQLValueString($annee[$key], "text"),
  					   GetSQLValueString($valind[$key], "double"));
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
      }
    }
    $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?insert=ok&id_ind=$id_ind"; else $insertGoTo .= "?insert=no&id_ind=$id_ind";
    header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_res = "SELECT id_produit, code_produit, intitule_produit, code_resultat, id_resultat, intitule_resultat  FROM resultat, produit, activite_projet WHERE activite_projet.projet='".$_SESSION["clp_projet"]."' and code=composante and id_resultat=effet order by code_resultat, code_produit ";
$liste_res  = mysql_query($query_liste_res, $pdar_connexion) or die(mysql_error());
$row_liste_res  = mysql_fetch_assoc($liste_res);
$totalRows_liste_res  = mysql_num_rows($liste_res);

if(isset($_GET["id_sup_indprd"]))  { $idiprd=$_GET["id_sup_indprd"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_ind = "DELETE FROM indicateur_produit_cmr WHERE id_indicateur='$idiprd'";
$Result1 = mysql_query($query_sup_ind, $pdar_connexion) or die(mysql_error());

$query_sup_ind_d = "DELETE FROM cible_cmr_produit WHERE indicateur_produit='$idiprd'";
$Result1d = mysql_query($query_sup_ind_d, $pdar_connexion) or die(mysql_error());

  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok&idcl=$idcl"; else $insertGoTo .= "?del=no&idcl=$idcl";
  header(sprintf("Location: %s", $insertGoTo));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind, mode_calcul FROM referentiel_indicateur";
$liste_ind_ref  = mysql_query($query_liste_ind_ref , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref);
$totalRows_liste_ind_ref  = mysql_num_rows($liste_ind_ref);
$liste_ind_ref_array = array();
$unite_ind_ref_array = array();
$mode_calcul_ind_ref_array = array();
do{  $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"];
 $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
 $mode_calcul_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["mode_calcul"];
}while($row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref));


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cible_ind_ref = " SELECT referentiel, sum(valeur_cible) as valeur_cible FROM   cible_cmr_produit, indicateur_produit_cmr, referentiel_indicateur  where id_indicateur=indicateur_produit and id_ref_ind=referentiel  AND mode_calcul =  'Unique' and zone in(select id_region from region) group by indicateur_produit ";
$cible_ind_ref  = mysql_query($query_cible_ind_ref , $pdar_connexion) or die(mysql_error());
$row_cible_ind_ref = mysql_fetch_assoc($cible_ind_ref);
$totalRows_cible_ind_ref  = mysql_num_rows($cible_ind_ref);
$cible_ind_ref_array = array();
do{  $cible_ind_ref_array[$row_cible_ind_ref["referentiel"]] = $row_cible_ind_ref["valeur_cible"];
}while($row_cible_ind_ref = mysql_fetch_assoc($cible_ind_ref));



mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_somme_ind_ref = " SELECT indicateur_ref, indicateur_prd, sum(valeur_cible) as valeur_cible FROM   cible_cmr_produit, indicateur_produit_cmr, referentiel_indicateur, calcul_indicateur_simple_ref  where id_indicateur=indicateur_produit and id_ref_ind=referentiel  and  FIND_IN_SET( id_ref_ind, indicateur_simple )
AND mode_calcul =  'Unique' and zone in(select id_region from region) group by indicateur_prd,indicateur_ref ";
$somme_ind_ref  = mysql_query($query_somme_ind_ref , $pdar_connexion) or die(mysql_error());
$row_somme_ind_ref = mysql_fetch_assoc($somme_ind_ref);
$totalRows_somme_ind_ref  = mysql_num_rows($somme_ind_ref);
$somme_ind_ref_array = array();

do{  $somme_ind_ref_array[$row_somme_ind_ref["indicateur_ref"]] = $row_somme_ind_ref["valeur_cible"];
}while($row_somme_ind_ref = mysql_fetch_assoc($somme_ind_ref));

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_moyenne_ind_ref = " SELECT indicateur_ref ,  avg(valeur_cible) as valeur_cible
					FROM (SELECT referentiel, id_indicateur, indicateur_ref, sum(valeur_cible) as valeur_cible FROM   cible_cmr_produit, indicateur_produit_cmr, referentiel_indicateur, calcul_indicateur_simple_ref  where id_indicateur=indicateur_produit and id_ref_ind=referentiel  and  FIND_IN_SET( id_ref_ind, indicateur_simple )
AND mode_calcul =  'Unique' and zone in(select id_region from region) group by id_indicateur, indicateur_ref, referentiel)  AS alias_sr group by indicateur_ref ";
$moyenne_ind_ref  = mysql_query($query_moyenne_ind_ref , $pdar_connexion) or die(mysql_error());
$row_moyenne_ind_ref = mysql_fetch_assoc($moyenne_ind_ref);
$totalRows_moyenne_ind_ref  = mysql_num_rows($moyenne_ind_ref);
$moyenne_ind_ref_array = array();

do{  $moyenne_ind_ref_array[$row_moyenne_ind_ref["indicateur_ref"]] = $row_moyenne_ind_ref["valeur_cible"];
}while($row_moyenne_ind_ref = mysql_fetch_assoc($moyenne_ind_ref));


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ratio = "SELECT indicateur_ref, numerateur, denominateur FROM ratio_indicateur_ref order by indicateur_ref";
$liste_ind_ratio  = mysql_query($query_liste_ind_ratio , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ratio = mysql_fetch_assoc($liste_ind_ratio);
$totalRows_liste_ind_ratio  = mysql_num_rows($liste_ind_ratio);
$liste_num_ratio_array = array();
$liste_deno_ratio_array = array();
do{
 $liste_num_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["numerateur"];
  $liste_deno_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["denominateur"];
}while($row_liste_ind_ratio = mysql_fetch_assoc($liste_ind_ratio));
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <title><?php print $config->sitename;?></title>

  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone;?>" />

  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

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
    <?php include_once("includes/header.php"); ?>
 </header>
<div id="container">
    <div id="sidebar" class="sidebar-fixed">
        <div id="sidebar-content">
            <?php include_once("includes/menu_top.php"); ?>
        </div>
        <div id="divider" class="resizeable"></div>
    </div>

    <div id="content">
        <div class="container">
            <div class="crumbs">
                <?php include_once("includes/sous_menu.php"); ?>
            </div>
        <div class="page-header">
            <div class="p_top_5">
<!-- Site contenu ici -->
<style>
.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }  .menu_head{ margin-top: 5px; }

</style>
<div class="widget box box_projet">
<div class="widget-header1"> <center><h4><?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Indicateurs de produit </h4>
<?php
$libelle = array("cmr_produit.php"=>"Indicateurs de produit","cmr_resultat.php"=>"Indicateurs d'effet","cmr_effet.php"=>"Indicateurs ODP","cmr_impact.php"=>"Indicateurs d'impact");
foreach($libelle as $key=>$lib){
  echo do_link("",$key,"$lib","<i> $lib </i>","","./","pull-right p11","",0,"",$nfile);
  $i--; }
?>
<!--<a href="cmr_produit.php" title="Indicateurs de produit" class="pull-right p11"><i class="icon-plus"> Indicateurs de produit </i></a>
<a href="cmr_resultat.php" title="Indicateurs d'effet" class="pull-right p11"><i class="icon-plus"> Indicateurs d'effet </i></a>
<a href="cmr_effet.php" title="Indicateurs ODP" class="pull-right p11"><i class="icon-plus"> Indicateurs ODP </i></a>
<a href="cmr_impact.php" title="Indicateurs d'impact" class="pull-right p11"><i class="icon-plus"> Indicateurs d'impact </i></a>-->
</div>
<div class="widget-content" style="display: block;">

<table width="100%" border="0" align="center" cellspacing="1" class="table table-striped table-bordered table-responsive">
              <?php if($totalRows_liste_res>0) {$p1="j"; $p11 ="j";$c=0; $i=0; do { ?>
<?php
$id_prd=$row_liste_res['id_produit'];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_ind = "SELECT * FROM indicateur_produit, indicateur_produit_cmr where  id_indicateur_produit=indicateur_prd and produit='$id_prd' order by code_iprd asc, code_irprd";
$ind  = mysql_query($query_ind , $pdar_connexion) or die(mysql_error());
$row_ind  = mysql_fetch_assoc($ind);
$totalRows_ind  = mysql_num_rows($ind);
?>
			  <?php if($p1!=$row_liste_res['id_produit']) {?>
          <tr bgcolor="#EBEBEB">
            <td colspan="4" align="center" onclick="show_tab('amontrer<?php echo $row_liste_res['id_produit'] ?>');"><div align="left" class="Style4"><strong>

                      <?php  if($p1!=$row_liste_res['id_produit']){echo "<p class=\"menu_head\"> ".$row_liste_res['code_produit'].": ".$row_liste_res['intitule_produit']."<span class='label label-success pull-right'>$totalRows_ind</span></p>"; $i=0; }$p1=$row_liste_res['id_produit']; ?>
                        </strong></div></td>
            <td align="center" height="35"> <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?><a onclick="get_content('edit_indicateur_prdcmr.php','prd=<?php echo $row_liste_res['id_produit']; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Ajout d'indicateur de produit" class="thickbox Add"  dir=""><span style="background-color:#ebebeb">&nbsp;<span class="Style5">Ajouter</span>&nbsp;</span></a><?php }?></td>
          </tr>
          <?php } ?>
		   <tbody id="amontrer<?php echo $row_liste_res['id_produit']."_".$i ?>" class="<?php if(isset($_GET['idcl'])) echo "show"; else echo "hide";?>">
              <tr >
                <td valign="top" colspan="6" id="acharger<?php echo $row_liste_res['id_produit']; ?>">
              <table border="0" width="100%" cellspacing="0" class="table table-striped table-bordered table-responsive">
                   <?php if($totalRows_ind>0) { if(isset($unite_ind_ref_array[$row_ind["referentiel"]])) $unite = $unite_ind_ref_array[$row_ind["referentiel"]]; else  $unite=""; ?>
                <thead>
                    <tr>
                      <th width="70%"><font size="2">Indicateurs de produit</font></th>
                      <th width="10%"><font size="2"> Unit&eacute;</font></th>
                      <th width="10%"><font size="2">Cible DCP</font></th>
                      <th width="10%"><strong><font size="2">Cible CMR</font></strong></th>
                      <!--<th width="10%"><strong><font size="2">R&eacute;vis&eacute;e</font></strong></th>-->
                    </tr>
                </thead>
                    <?php  $tbcount=0; $pp="j"; $cible_deno=$cible_num=0; do { ?>
					 <?php if($pp!=$row_ind['id_indicateur_produit']) {?>
          <tr bgcolor="#BED694">
            <td colspan="7" align="center" bgcolor="#CCCCCC"><div align="left" class="Style4"><strong><font size="2">

                      <?php  if($pp!=$row_ind['id_indicateur_produit']){echo $row_ind['intitule_indicateur_produit']; }$pp=$row_ind['id_indicateur_produit']; ?></font>
                        </strong></div></td>
            </tr>
          <?php } ?>
					<tr <?php if(isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) &&  $mode_calcul_ind_ref_array[$row_ind["referentiel"]]!='Unique') echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#FFFFFF"'; $i=$i+1;?> >
                      <td <?php echo (!isset($liste_ind_ref_array[$row_ind['referentiel']]))?'style="color:#FF0000"':''; ?>><div align="left" class="Style22">
                     <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
                      <a onclick="get_content('edit_indicateur_prdcmr.php','id=<?php echo $row_ind['id_indicateur']; ?>&prd=<?php echo $row_liste_res['id_produit']; ?>&iframe=1','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" title="Modification d'indicateur de produit" class="thickbox Add"  dir=""><span class="Style17">.&nbsp;</span><?php echo $row_ind['intitule_indicateur']; ?></a>
					  <?php } else { ?>
<span class="Style17">.&nbsp;</span><?php echo $row_ind['intitule_indicateur']; ?>
<?php } ?></div></td>
                      <td ><div align="center" class="Style21"><span class="Style22">
                        <?php if(isset($unite_ind_ref_array[$row_ind["referentiel"]])) $unite = $unite_ind_ref_array[$row_ind["referentiel"]]; else  $unite=""; echo $unite; ?>
                      </span></div></td>
                      <td><div align="center"><span class="Style21">
                        <?php

					  $cible_cmr = $row_ind['cible_cmr'];
						if(trim(strtolower($cible_cmr))==0 &&  $unite=="Oui/Non") echo "Oui";
						elseif(trim(strtolower($cible_cmr))==1 && $unite=="Oui/Non") echo "Non";
						elseif(trim(strtolower($cible_cmr))==-1) echo "n/a";
				        else echo $cible_cmr;
					   ?>
                      </span></div></td>

                      <?php if(isset($_SESSION['clp_niveau'])) {?>
                      <td>
                        <div align="center"><?php if(isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) && $mode_calcul_ind_ref_array[$row_ind["referentiel"]]=="Unique") {?><a onclick="get_content('edit_cible_cmr_produit.php','id=<?php echo $row_ind['id_indicateur']; ?>&prd=<?php echo $row_liste_res['id_produit']; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" title="Valeurs cibles annuelles" class="thickbox Add"  dir=""><strong><?php if(isset($cible_ind_ref_array[$row_ind["referentiel"]]) && ((isset($unite_ind_ref_array[$row_ind['referentiel']]) && $unite_ind_ref_array[$row_ind['referentiel']]!="%") || !isset($unite_ind_ref_array[$row_ind['referentiel']]))) echo $cible_ind_ref_array[$row_ind["referentiel"]]; elseif(isset($unite_ind_ref_array[$row_ind['referentiel']]) && $unite_ind_ref_array[$row_ind['referentiel']]=="%") echo "Cibles (%)"; else echo "Cibles annuelles"; ?></strong></a>
						<?php
                                        
						} elseif(isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) && $mode_calcul_ind_ref_array[$row_ind["referentiel"]]=="Somme")
						 {
						 if(isset($somme_ind_ref_array[$row_ind["referentiel"]])) echo $somme_ind_ref_array[$row_ind["referentiel"]];
						 } elseif(isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) && $mode_calcul_ind_ref_array[$row_ind["referentiel"]]=="Ratio" && isset($liste_num_ratio_array[$row_ind["referentiel"]]) && isset($liste_deno_ratio_array[$row_ind["referentiel"]]))
						 {
						 //cas ou numerateur est une somme
						 if(isset($mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]] =="Somme" && isset($somme_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) )  $cible_num=$somme_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]];

						  //cas ou denominateur est une somme
						 if(isset($mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]] =="Somme" && isset($somme_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) )   $cible_deno=$somme_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]];

						  //cas ou num est unique
						  if(isset($mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]] =="Unique" && isset($cible_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) ) $cible_num=$cible_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]];

						  //cas ou deno est unique
						  if(isset($mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]] =="Unique" && isset($cible_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) )  $cible_deno=$cible_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]];

						   //cas ou numerateur est une moyenne
						 if(isset($mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]] =="Moyenne" && isset($moyenne_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]]) )  $cible_num=$moyenne_ind_ref_array[$liste_num_ratio_array[$row_ind["referentiel"]]];

						  //cas ou denominateur est une moyenne
						 if(isset($mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) && $mode_calcul_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]] =="Moyenne" && isset($moyenne_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]]) )   $cible_deno=$moyenne_ind_ref_array[$liste_deno_ratio_array[$row_ind["referentiel"]]];


						 //if() echo $somme_ind_ref_array[$row_ind["referentiel"]];
						 if($cible_deno!=0) echo number_format(100*$cible_num/$cible_deno, 2, ',', ' ');

						 $cible_num=$cible_deno=0;
						 }
						  elseif(isset($mode_calcul_ind_ref_array[$row_ind["referentiel"]]) && $mode_calcul_ind_ref_array[$row_ind["referentiel"]]=="Moyenne")
						 {
						 if(isset($moyenne_ind_ref_array[$row_ind["referentiel"]])) echo number_format($moyenne_ind_ref_array[$row_ind["referentiel"]], 2, ',', ' ');
						 }	else echo "N/A";


						?> </div></td>
<!--                      <td><div align="center"><span class="Style21">
                        <?php  /*

					  $cible_rmp = $row_ind['cible_rmp'];
						if(trim(strtolower($cible_rmp))==0 &&  $unite=="Oui/Non") echo "Oui";
						elseif(trim(strtolower($cible_rmp))==1 && $unite=="Oui/Non") echo "Non";
						elseif(trim(strtolower($cible_rmp))==-1) echo "n/a";
				        else echo $cible_rmp;  */
					   ?>
                      </span></div></td>-->
					  <?php }?>
					</tr>

                    <?php } while ($row_ind = mysql_fetch_assoc($ind)); ?>
                    <?php } ?>
                </table></td>
              </tr>
 </tbody>
              <?php } while ($row_liste_res = mysql_fetch_assoc($liste_res)); ?>
              <?php } else {?>
              <tr>
                <td nowrap="nowrap"><div align="center"><em><strong>Aucune composante enregistr&eacute;e; </strong></em></div></td>
              </tr>
              <?php } ?>
            </table>
</div>

</div></div>

<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>

</body>
</html>