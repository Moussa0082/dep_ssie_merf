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

$date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; 
if (isset($_POST["MM_form"]) && $_SESSION["clp_id"]!="admin") {
  header(sprintf("Location: %s", $_SERVER['PHP_SELF'].'?auth=no'));
  exit;
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form5"))
{ //Objectif Spécifique
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO objectif_specifique (intitule_objectif_specifique, code_os, structure, projet, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, '$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_SESSION['clp_structure'], "text"),
                         GetSQLValueString($_SESSION['clp_projet'], "text"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from objectif_specifique WHERE id_objectif_specifique=%s",
                         GetSQLValueString($id, "int"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE objectif_specifique SET intitule_objectif_specifique=%s, code_os=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_objectif_specifique=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($id, "int"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form6"))
{ //Indicateur Objectif Spécifique
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO indicateur_objectif_specifique (intitule_indicateur_objectif_specifique, code_ios, unite, reference, mi_parcours, cible_dp, source, periodicite, responsable,objectif_specifique, id_personnel, date_enregistrement) VALUES (%s, %s, %s,%s, %s, %s, %s,%s, %s, %s, '$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "text"),
						 GetSQLValueString($_POST['unite'], "text"),
						 GetSQLValueString($_POST['reference'], "double"),
                         GetSQLValueString($_POST['mi_parcours'], "double"),
						 GetSQLValueString($_POST['cible_dp'], "double"),
                         GetSQLValueString($_POST['source'], "text"),
						 GetSQLValueString($_POST['periodicite'], "text"),
                         GetSQLValueString($_POST['responsable'], "text"),
                         GetSQLValueString($_POST['objectif'], "int"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from indicateur_objectif_specifique WHERE id_indicateur_objectif_specifique=%s",
                         GetSQLValueString($id, "int"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE indicateur_objectif_specifique SET intitule_indicateur_objectif_specifique=%s, code_ios=%s, objectif_specifique=%s, unite=%s, reference=%s, mi_parcours=%s, cible_dp=%s, source=%s, periodicite=%s, responsable=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur_objectif_specifique=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['objectif'], "int"),
						 GetSQLValueString($_POST['unite'], "text"),
						 GetSQLValueString($_POST['reference'], "double"),
                         GetSQLValueString($_POST['mi_parcours'], "double"),
						 GetSQLValueString($_POST['cible_dp'], "double"),
                         GetSQLValueString($_POST['source'], "text"),
						 GetSQLValueString($_POST['periodicite'], "text"),
                         GetSQLValueString($_POST['responsable'], "text"),
                         GetSQLValueString($id, "int"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form7"))
{ //Source Objectif Spécifique
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO source_os (intitule_source, objectif_specifique, id_personnel, date_enregistrement) VALUES (%s, %s, '$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['objectif'], "int"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from source_os WHERE id_source=%s",
                         GetSQLValueString($id, "int"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE source_os SET intitule_source=%s, objectif_specifique=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_source=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['objectif'], "int"),
                         GetSQLValueString($id, "int"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form8"))
{ //Hypothèse Objectif Spécifique
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO hypothese_os (intitule_hypothese, objectif_specifique, id_personnel, date_enregistrement) VALUES (%s, %s, '$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['objectif'], "int"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from hypothese_os WHERE id_hypothese=%s",
                         GetSQLValueString($id, "int"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE hypothese_os SET intitule_hypothese=%s, objectif_specifique=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_hypothese=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['objectif'], "int"),
                         GetSQLValueString($id, "int"));

  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }
}
/*
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form9"))
{ //Hypothèse Objectif Spécifique
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO resultat (intitule_resultat, code_resultat, composante, id_personnel, date_enregistrement) VALUES (%s, %s, %s, '$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "int"),
                         GetSQLValueString($_POST['composante'], "int"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from resultat WHERE id_resultat=%s",
                         GetSQLValueString($id, "int"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE resultat SET intitule_resultat=%s, code_resultat=%s, composante=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_resultat=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "int"),
                         GetSQLValueString($_POST['composante'], "int"),
                         GetSQLValueString($id, "int"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}*/
/*
$editFormAction = $_SERVER['PHP_SELF'];
if (isset ($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/
// Partie objectif specifique

// objectif specifique
$query_os = "SELECT * FROM objectif_specifique WHERE projet='".$_SESSION["clp_projet"]."' order by id_objectif_specifique";
try{
    $os = $pdar_connexion->prepare($query_os);
    $os->execute();
    $row_os = $os ->fetchAll();
    $totalRows_os = $os->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

// Partie resultat

//composante

// requete composante
$query_cp = "SELECT * FROM activite_projet WHERE projet='".$_SESSION["clp_projet"]."' and niveau=1 order by code";
try{
    $cp = $pdar_connexion->prepare($query_cp);
    $cp->execute();
    $row_cp = $cp ->fetchAll();
    $totalRows_cp = $cp->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
//include_once 'modal_add.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print $config->sitename; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="keywords" content="<?php print $config->MetaKeys; ?>" />
  <meta name="description" content="<?php print $config->MetaDesc; ?>" />
  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->
  <meta name="author" content="<?php print $config->MetaAuthor; ?>" />
  <!--<meta charset="utf-8">-->
  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>

  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->
  <link href="<?php print $config->theme_folder; ?>/main.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/plugins.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/responsive.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/icons.css" rel="stylesheet" type="text/css"/>
  <link href="<?php print $config->theme_folder; ?>/login.css" rel="stylesheet" type="text/css"/>
  <link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome.min.css">
  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->theme_folder; ?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->
  <!--[if IE 8]><link href="<?php print $config->theme_folder; ?>/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->
  <link href='<?php print $config->theme_folder; ?>/css.css' rel='stylesheet' type='text/css'>
  <!--<link rel="stylesheet" href="<?php print $config->theme_folder; ?>/table.css" type="text/css" > -->
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/jquery-1.10.2.min.js"></script>
  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>
  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/lodash.compat.min.js"></script>
  <!--[if lt IE 9]><script src="<?php print $config->script_folder; ?>/libs/html5shiv.js"></script><![endif]-->
  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>
  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>
  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/libs/breakpoints.js"></script>
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
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/app.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/plugins.form-components.js"></script>
<!--
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/custom.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/pages_calendar.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_filled_blue.js"></script>
  <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/charts/chart_simple.js"></script>-->
 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>
 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/login.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/myscript.js"></script>
 <script type="text/javascript" src="<?php print $config->script_folder; ?>/demo/ui_general.js"></script>
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
} .table tbody tr td {vertical-align: top; }
</style>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Cadre Logique : <span style="color:#FFFF00">Edition des objectifs de d&eacute;veloppement </span></h4>
    <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2){?>
<a href="objectif_general_edit.php" title="Editer l'Objectif global" class="pull-right p11"><i class="icon-plus"> Objectif global </i></a>
<a href="objectif_developpement_edit.php" title="Editer les objectifs de d&eacute;veloppement" class="pull-right p11"><i class="icon-plus"> Objectif de D&eacute;veloppement </i></a>
<a href="effet_edit.php" title="Editer les effets" class="pull-right p11"><i class="icon-plus"> Effets </i></a>
<a href="produit_edit.php" title="Editer les produits" class="pull-right p11"><i class="icon-plus"> Produits </i></a>

    <?php } ?>
</div>

<div class="widget-content" style="display: block;">
<div class="well well-sm"><strong> 2. <span class="Style22">OBJECTIFS SPECIFIQUES</span></strong></div>

<table width="100%" border="1" cellspacing="1" class="table table-striped table-bordered table-responsive">
                <tr>
                  <td nowrap="nowrap" bgcolor="" width="25%"><strong>R&eacute;sum&eacute; descriptif</strong></td>
                  <td bgcolor="" width="25%"><strong>Indicateurs objectivement v&eacute;rifiables</strong> </td>
                  <td bgcolor="" width="25%"><strong>Source d&rsquo;information</strong></td>
                  <td bgcolor="" width="25%"><strong>Risques/hypoth&egrave;ses</strong></td>
                </tr>
                <?php
			    if($totalRows_os>0) { $o=0; foreach($row_os as $row_os){ 
                      $id = $row_os['id_objectif_specifique'];
                    ?>
                <tr     <?php
     if ($o % 2 == 0)
       echo 'bgcolor="#ECF0DF"';
     $o = $o + 1;
     ?>
>
                  <td valign="top"><div align="left"><a onclick="get_content('new_objectif_specifique.php','id=<?php echo $id; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><?php echo $row_os['intitule_objectif_specifique']; ?>
</a>&nbsp;</div>
</td>
                  <td valign="top"><table border="0" align="left" cellspacing="0">
                          <?php
                          $id_os = $row_os['id_objectif_specifique'];
                          $query_ind = "SELECT * FROM indicateur_objectif_specifique where objectif_specifique='$id_os' order by code_ios";
							try{
								$ind = $pdar_connexion->prepare($query_ind);
								$ind->execute();
								$row_ind = $ind ->fetchAll();
								$totalRows_ind = $ind->rowCount();
							}catch(Exception $e){ die(mysql_error_show_message($e)); }
						  
						  
                          ?>
                  
					  			    <?php   if($totalRows_ind>0) { $i=0; foreach($row_ind as $row_ind){ $id = $row_ind['id_indicateur_objectif_specifique'];?>

                      <tr <?php if ($i % 2 == 0) echo 'bgcolor="#FFFFFF"';$i = $i + 1;?>>
                        <td><div align="left"><?php echo $i; ?><a onclick="get_content('new_indicateur_objectif_specifique.php','id=<?php echo $id; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><span style="font-size:13px"><?php echo "- " . $row_ind['intitule_indicateur_objectif_specifique'] ?></span></a></div></td>
                      </tr>
                              <?php } }
                          ?>
                      <tr>
                        <td><div align="center" class="Style2">
                                <?php
                                if (!$totalRows_ind > 0)
                                  echo "Aucun indicateur enregistr&eacute;: ";
                                ?>
                        </div></td>
                      </tr>
                        
                  </table><div align="center" class="clear"><a onclick="get_content('new_indicateur_objectif_specifique.php','<?php echo 'id_os='.$id_os; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div></td>
                  <td valign="top"><table border="0" align="left" cellspacing="0">
                          <?php
                          $id_os = $row_os['id_objectif_specifique'];
                          $query_src = "SELECT * FROM source_os where objectif_specifique='$id_os'";
							try{
								$src = $pdar_connexion->prepare($query_src);
								$src->execute();
								$row_src = $src ->fetchAll();
								$totalRows_src = $src->rowCount();
							}catch(Exception $e){ die(mysql_error_show_message($e)); }
                          ?>
	  			    <?php   if($totalRows_src>0) { $i=0; foreach($row_src as $row_src){ $id = $row_src['id_source'];?>
                      <tr <?php if ($i % 2 == 0) echo 'bgcolor="#ECF0DF"';$i = $i + 1;?>>
                        <td><div align="left"><a onclick="get_content('new_source_objectif_specifique.php','id=<?php echo $id;?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><span style="font-size:13px"><?php echo "- " . $row_src['intitule_source'];?></span></a>&nbsp;</div></td>
                      </tr>
                              <?php  } }
                          ?>
                      <tr>
                        <td><div align="center">
                                <?php
                                if (!$totalRows_src > 0)
                                  echo "Aucune source enregistr&eacute;e: ";
                                ?>
                        </div></td>
                      </tr>
                         
                  </table><div align="center" class="clear"><a onclick="get_content('new_source_objectif_specifique.php','<?php echo 'id_os='.$id_os; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div></td>
                  <td valign="top"><table border="0" align="left" cellspacing="0">
                          <?php
                          $id_os = $row_os['id_objectif_specifique'];
                          $query_hyp = "SELECT * FROM hypothese_os where objectif_specifique='$id_os' order by id_hypothese";
							try{
								$hyp = $pdar_connexion->prepare($query_hyp);
								$hyp->execute();
								$row_hyp = $hyp ->fetchAll();
								$totalRows_hyp = $hyp->rowCount();
							}catch(Exception $e){ die(mysql_error_show_message($e)); }
                          ?>
  	  			    <?php   if($totalRows_hyp>0) { $i=0; foreach($row_hyp as $row_hyp){ $id = $row_hyp['id_hypothese'];?>
                      <tr <?php if ($i % 2 == 0) echo 'bgcolor="#ECF0DF"';$i = $i + 1;?>>
                        <td><div align="left"><a onclick="get_content('new_hypothese_objectif_specifique.php','id=<?php echo $id;?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><span style="font-size:13px"><?php echo "- " . $row_hyp['intitule_hypothese'];?></span></a>&nbsp;</div></td>
                      </tr>
                      <?php }  }?>
                      <tr>
                        <td><div align="center" class="Style2">
                            <?php if (!$totalRows_hyp > 0) echo "Aucune hypothese enregistr&eacute;e: ";?>
                        </div></td>
                      </tr>
                     
                  </table><div align="center" class="clear"><a onclick="get_content('new_hypothese_objectif_specifique.php','<?php echo 'id_os='.$id_os; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div></td>
                </tr>
                    <?php  }  } else { ?>
                <tr>
                  <td colspan="4" nowrap="nowrap"><div align="center"><em><strong>Aucun objectif sp&eacute;cifique enregistr&eacute; </strong></em></div></td>
                </tr>
                  <?php } ?>
              <tr>
                  <td colspan="4" nowrap="nowrap"><div align="center" class="clear"><a onclick="get_content('new_objectif_specifique.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter un objectif de d&eacute;veloppement</a></div></td>
                </tr>
              </table>
</div>

</div></div>

<!-- Fin Site contenu ici -->
          
        </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>

</body>
</html>