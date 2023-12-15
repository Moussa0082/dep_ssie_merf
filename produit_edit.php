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


if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form13"))
{ //Produit
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO produit (intitule_produit, code_produit, sous_composante, effet, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, '$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['composante'], "text"),
                         GetSQLValueString($_POST['resultat'], "int"));

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
    $insertSQL = sprintf("DELETE from produit WHERE id_produit=%s",
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
    $insertSQL = sprintf("UPDATE produit SET intitule_produit=%s, code_produit=%s, sous_composante=%s, effet=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_produit=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['composante'], "text"),
                         GetSQLValueString($_POST['resultat'], "int"),
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

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form14"))
{ //Indicateur Produit
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO indicateur_produit (intitule_indicateur_produit, code_iprd, unite, reference, mi_parcours, cible_dp, source, periodicite, responsable, produit, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s,%s, %s, %s,%s, %s, %s, '$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['unite'], "text"),
						 GetSQLValueString($_POST['reference'], "double"),
                         GetSQLValueString($_POST['mi_parcours'], "double"),
						 GetSQLValueString($_POST['cible_dp'], "double"),
                         GetSQLValueString($_POST['source'], "text"),
						 GetSQLValueString($_POST['periodicite'], "text"),
                         GetSQLValueString($_POST['responsable'], "text"),
                         GetSQLValueString($_POST['produit1'], "int"));

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
    $insertSQL = sprintf("DELETE from indicateur_produit WHERE id_indicateur_produit=%s",
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
    $insertSQL = sprintf("UPDATE indicateur_produit SET intitule_indicateur_produit=%s, code_iprd=%s, produit=%s, unite=%s, reference=%s, mi_parcours=%s, cible_dp=%s, source=%s, periodicite=%s, responsable=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur_produit=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['produit1'], "int"),
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

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form15"))
{ //Indicateur Produit
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO source_produit (intitule_source, produit, id_personnel, date_enregistrement) VALUES (%s, %s, '$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['produit1'], "int"));

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
    $insertSQL = sprintf("DELETE from source_produit WHERE id_source=%s",
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
    $insertSQL = sprintf("UPDATE source_produit SET intitule_source=%s, produit=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_source=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['produit1'], "int"),
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

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form16"))
{ //Hypothèse Produit
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO hypothese_produit (intitule_hypothese, produit, id_personnel, date_enregistrement) VALUES (%s, %s, '$personnel', '$date')",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['produit1'], "int"));

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
    $insertSQL = sprintf("DELETE from hypothese_produit WHERE id_hypothese=%s",
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
    $insertSQL = sprintf("UPDATE hypothese_produit SET intitule_hypothese=%s, produit=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_hypothese=%s",
                         GetSQLValueString($_POST['nom'], "text"),
                         GetSQLValueString($_POST['produit1'], "int"),
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

/*$editFormAction = $_SERVER['PHP_SELF'];
if (isset ($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/

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
<div class="widget-header"> <h4><i class="icon-reorder"></i> Cadre Logique :<span style="color:#FFFF00"> Edition des produits </span></h4>
    <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2){?>
<a href="objectif_general_edit.php" title="Editer l'Objectif global" class="pull-right p11"><i class="icon-plus"> Objectif global </i></a>
<a href="objectif_developpement_edit.php" title="Editer les objectifs de d&eacute;veloppement" class="pull-right p11"><i class="icon-plus"> Objectif de D&eacute;veloppement </i></a>
<a href="effet_edit.php" title="Editer les effets" class="pull-right p11"><i class="icon-plus"> Effets </i></a>
<a href="produit_edit.php" title="Editer les produits" class="pull-right p11"><i class="icon-plus"> Produits </i></a>

    <?php } ?>
</div>

<div class="widget-content" style="display: block;">
<div class="well well-sm"><strong> 3. <span class="Style22">PRODUITS</span></strong></div>

<table width="100%" border="0" cellspacing="1" class="table table-striped table-bordered table-responsive">
           
	     <?php if($totalRows_cp>0) { $c=0; foreach($row_cp as $row_cp){  ?>
            <tr bgcolor="#009900">
              <td colspan="4" style="color: white; background-color: #009900" bgcolor="#009900" valign="top" align="left"><?php echo $row_cp['code'] . ": " . $row_cp['intitule']; ?>&nbsp;</td>
            
            </tr>

                <?php
            //debut de ligne
                $id_cp = $row_cp['code'];
	               $query_res = "SELECT * FROM resultat where composante='$id_cp' and projet='".$_SESSION["clp_projet"]."' order by code_resultat";
					try{
						$res = $pdar_connexion->prepare($query_res);
						$res->execute();
						$row_res = $res ->fetchAll();
						$totalRows_res = $res->rowCount();
					}catch(Exception $e){ die(mysql_error_show_message($e)); }
                ?>
                <?php
              if($totalRows_res>0) { $o=0; foreach($row_res as $row_res){   $id_res = $row_res['id_resultat'];   
                    ?>
            <tr         <?php
         //if ($o % 2 == 0)
           echo 'bgcolor="#ECF0DF"';
         $o = $o + 1;
         ?>
>
              <td valign="top" colspan="4">
              <div align="left"><?php echo "<b>Effet ".$row_res['code_resultat']."</b>: ".$row_res['intitule_resultat']; ?></div>
              </td>
            </tr>

            <tr>
              <td nowrap="nowrap" bgcolor="" width="25%"><div align="left"><strong>Produits</strong></div></td>
              <td bgcolor="" width="25%"><strong>Indicateurs objectivement v&eacute;rifiables</strong> </td>
              <td bgcolor="" width="25%"><strong>Source d&rsquo;information</strong></td>
              <td bgcolor="" width="25%"><strong>Risques/hypoth&egrave;ses</strong></td>
            </tr>

                    <?php
            //produit
                   
					$query_produit = "SELECT * FROM produit where effet='$id_res' order by code_produit";
					try{
						$produit = $pdar_connexion->prepare($query_produit);
						$produit->execute();
						$row_produit = $produit ->fetchAll();
						$totalRows_produit = $produit->rowCount();
					}catch(Exception $e){ die(mysql_error_show_message($e)); }
                    ?>
                    <?php
	              if($totalRows_produit>0) { $op=0; foreach($row_produit as $row_produit){    $id = $prd = $row_produit['id_produit'];  ?>
            <tr             <?php
             if ($op % 2 == 0)
               echo 'bgcolor="#ECF0DF"';
             $op = $op + 1;
             ?>
>
              <td valign="top"><div align="left"><a onclick="get_content('new_produit.php','id=<?php echo $id.'&cmp='.$id_cp.'&resultat='.$id_res; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><?php echo "<b>Produit ".$row_produit['code_produit']."</b>: ".$row_produit['intitule_produit']; ?></a>
              </div></td>
              <td valign="top"><table border="0" align="left" cellspacing="0">
                  <?php
           	   $id_prd=$row_produit['id_produit'];					
					$query_indp = "SELECT * FROM indicateur_produit where produit='$id_prd'";
					try{
						$indp = $pdar_connexion->prepare($query_indp);
						$indp->execute();
						$row_indp = $indp ->fetchAll();
						$totalRows_indp = $indp->rowCount();
					}catch(Exception $e){ die(mysql_error_show_message($e)); }

				  ?>
   		          <?php if($totalRows_indp>0) { $b=0; foreach($row_indp as $row_indp){ $id = $row_indp['id_indicateur_produit']; ?>
                  <tr <?php if ($b % 2 == 0) echo 'bgcolor="#FFFFFF"';$b = $b + 1;?>>
                    <td><div align="left" class="Style11"><a onclick="get_content('new_indicateur_produit.php','id=<?php echo $id.'&cmp='.$id_cp.'&resultat='.$id_res.'&prd='.$prd; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir="">
                                        <?php
                       echo "- " . $row_indp['intitule_indicateur_produit']; ?>
                    </div></td>
                  </tr>
                                  <?php } } ?>
                  <tr>
                    <td><div align="center" class="Style2">
                                    <?php
                                    if (!$totalRows_indp > 0)
                                      echo "Aucun indicateur enregistr&eacute;: ";
                                    ?>
                    </div></td>
                  </tr>
              </table><div align="center" class="clear"><a onclick="get_content('new_indicateur_produit.php','cmp=<?php echo $id_cp; ?>&resultat=<?php echo $id_res.'&prd='.$prd; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div></td>
              <td valign="top"><table border="0" align="left" cellspacing="0">
                              <?php
                  //$id_res=$row_res['id_resultat'];
                           	$query_srcp = "SELECT * FROM source_produit where produit='$id_prd'";
					try{
						$srcp = $pdar_connexion->prepare($query_srcp);
						$srcp->execute();
						$row_srcp = $srcp ->fetchAll();
						$totalRows_srcp = $srcp->rowCount();
					}catch(Exception $e){ die(mysql_error_show_message($e)); }
					
				  ?>
   		          <?php if($totalRows_srcp>0) { $i=0; foreach($row_srcp as $row_srcp){ $id = $row_srcp['id_source']; ?>
                  <tr                 <?php  if ($i % 2 == 0) echo 'bgcolor="#FFFFFF"';  $i = $i + 1;
                 ?>
>
                    <td><div align="left" class="Style11"><a onclick="get_content('new_source_produit.php','id=<?php echo $id.'&cmp='.$id_cp.'&resultat='.$id_res.'&prd='.$prd; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><?php echo "- " . $row_srcp['intitule_source']; ?></a></div></td>
                  </tr>
                                  <?php } } ?>
                  <tr>
                    <td><div align="center" class="Style2">
                                    <?php
                                    if (!$totalRows_srcp > 0)
                                      echo "Aucune source enregistr&eacute;e: ";
                                    ?>
                    </div></td>
                  </tr>
              </table><div align="center" class="clear"><a onclick="get_content('new_source_produit.php','cmp=<?php echo $id_cp; ?>&resultat=<?php echo $id_res.'&prd='.$prd; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div></td>
              <td valign="top"><table border="0" align="left" cellspacing="0">
                      <?php
          //$id_res=$row_res['id_resultat'];
                     	$query_hypp = "SELECT * FROM hypothese_produit where produit='$id_prd'";
					try{
						$hypp = $pdar_connexion->prepare($query_hypp);
						$hypp->execute();
						$row_hypp = $hypp ->fetchAll();
						$totalRows_hypp = $hypp->rowCount();
					}catch(Exception $e){ die(mysql_error_show_message($e)); }

				  ?>
   		          <?php if($totalRows_hypp>0) { $i=0; foreach($row_hypp as $row_hypp){  $id = $row_hypp['id_hypothese'];?>
                  <tr <?php if ($i % 2 == 0) echo 'bgcolor="#FFFFFF"';$i = $i + 1;?>>
                    <td><div align="left" class="Style11"><a onclick="get_content('new_hypothese_produit.php','id=<?php echo $id.'&cmp='.$id_cp.'&resultat='.$id_res.'&prd='.$prd; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modifier" class="thickbox Add"  dir=""><?php echo "- " . $row_hypp['intitule_hypothese'];?></a><!--&nbsp;<a onclick="return confirm('Voulez-vous vraiment supprimer cette Hypoth&egrave;se ?');" href="new_hypothese_produit.php?id_sup=<?php echo $id;?>" title="Supprimer" class="thickbox Add"  dir=""><img src="images/delete.png" width="15" border="0"/></a>--></div></td>
                  </tr>
                  <?php }  }?>
                  <tr>
                    <td><div align="center" class="Style2">
                        <?php if (!$totalRows_hypp > 0) echo "Aucune hypothese enregistr&eacute;e: ";?>
                    </div></td>
                  </tr>
              </table><div align="center" class="clear"><a onclick="get_content('new_hypothese_produit.php','cmp=<?php echo $id_cp;?>&resultat=<?php echo $id_res.'&prd='.$prd; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></div></td>
            </tr>
            <?php } ?>
<tr><td colspan="4" align="center"><a onclick="get_content('new_produit.php','cmp=<?php echo $id_cp;?>&resultat=<?php echo $id_res;?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter</a></td></tr>
            <?php } ?>

            <?php } }?>
<tr><td colspan="4" align="center"><a onclick="get_content('new_produit.php','cmp=<?php echo $id_cp;?>&resultat=<?php echo $id_res;?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Nouveau"  class="btn btn-success" dir="">Ajouter un produit</a></td></tr>             

            <?php } }else {?>
            <tr>
              <td colspan="4" nowrap="nowrap"><div align="center"><em><strong>Aucune composante enregistr&eacute;e; </strong></em></div></td>
            </tr>
            <?php }?>
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