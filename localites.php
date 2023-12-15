<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: BAMASOFT */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
if(isset($_GET['niveau']) && intval($_GET['niveau'])>=0 && intval($_GET['niveau'])<=3) {$_SESSION["niveau"]=intval($_GET['niveau']); $niveau=$_SESSION["niveau"];} else { unset($_SESSION["niveau"],$niveau); $_SESSION["niveau"]=2; $niveau=$_SESSION["niveau"]; }
$libelle = array("R&eacute;gions","Pr&eacute;fectures","Communes","Canton");
$loc = array("region","departement","commune","village");
$editFormAction = $_SERVER['PHP_SELF'];
$currentPage = (isset($niveau))?$_SERVER['PHP_SELF']."?niveau=$niveau":$_SERVER['PHP_SELF']."?";
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
//Suppression multiple
if ((isset($_POST["id_val"]) && !empty($_POST["id_val"])) && isset($_POST["niveau"]) && intval($_POST["niveau"])>=0 && intval($_POST["niveau"])<=3) { $i = intval($_POST["niveau"]);
    $id = implode(',',$_POST["id_val"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix.$loc[$i]." WHERE code_".$loc[$i]." in ($id)");
    try{
        $query = $pdar_connexion->prepare($insertSQL);
        $Result1 = $query->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

      $insertGoTo = $_SERVER['PHP_SELF']."?niveau=".intval($_POST["niveau"]);
      if ($Result1) $insertGoTo .= "&del=ok"; else $insertGoTo .= "&del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
}
if ((isset($_GET["id_sup"]) && !empty($_GET["id_sup"])) && isset($_GET["niveau"]) && intval($_GET["niveau"])>=0 && intval($_GET["niveau"])<=3) { $i = intval($_GET["niveau"]);
      $id = ($_GET["id_sup"]);
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix.$loc[$i]." WHERE code_".$loc[$i]."=%s",
                           GetSQLValueString($id, "text"));
        try{
            $query = $pdar_connexion->prepare($insertSQL);
            $Result1 = $query->execute();
        }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $insertGoTo = $_SERVER['PHP_SELF']."?niveau=".intval($_GET["niveau"]);
      if ($Result1) $insertGoTo .= "&del=ok"; else $insertGoTo .= "&del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{ //region
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."region (nom_region, code_region, couleur, abrege_region, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, '$personnel', '$date')",
                         GetSQLValueString($_POST['intitule'], "text"),
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['couleur'], "text"),
                         GetSQLValueString($_POST['abrege'], "text"));
    try{
        $query = $pdar_connexion->prepare($insertSQL);
        $Result1 = $query->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok&add_new=1"; else $insertGoTo .= "?insert=no";
    $insertGoTo .= (isset($_POST['niveau']))?"&niveau=".$_POST['niveau']:"";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."region WHERE code_region=%s",
                         GetSQLValueString($id, "text"));
    try{
        $query = $pdar_connexion->prepare($insertSQL);
        $Result1 = $query->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    $insertGoTo .= (isset($_POST['niveau']))?"&niveau=".$_POST['niveau']:"";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
    $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."region SET nom_region=%s, code_region=%s, abrege_region=%s, couleur=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE code_region=%s",
                         GetSQLValueString($_POST['intitule'], "text"),
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['abrege'], "text"),
                         GetSQLValueString($_POST['couleur'], "text"),
                         GetSQLValueString($id, "text"));
    try{
        $query = $pdar_connexion->prepare($insertSQL);
        $Result1 = $query->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }

    //update code des departements
    $insertSQL1 = sprintf("UPDATE ".$database_connect_prefix."departement SET region=%s WHERE region=%s",
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['old_code'], "text"));
    try{
        $query = $pdar_connexion->prepare($insertSQL1);
        $Result1 = $query->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    $insertGoTo .= (isset($_POST['niveau']))?"&niveau=".$_POST['niveau']:"";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{ //departement
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."departement (nom_departement, code_departement, region, id_personnel, date_enregistrement) VALUES (%s, %s, %s,'$personnel', '$date')",
                         GetSQLValueString($_POST['intitule'], "text"),
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['parent'], "text"));
    try{
        $query = $pdar_connexion->prepare($insertSQL);
        $Result1 = $query->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok&add_new=1"; else $insertGoTo .= "?insert=no";
    $insertGoTo .= (isset($_POST['niveau']))?"&niveau=".$_POST['niveau']:"";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."departement WHERE code_departement=%s",
                         GetSQLValueString($id, "text"));
    try{
        $query = $pdar_connexion->prepare($insertSQL);
        $Result1 = $query->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    $insertGoTo .= (isset($_POST['niveau']))?"&niveau=".$_POST['niveau']:"";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
    $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."departement SET nom_departement=%s, code_departement=%s, region=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE code_departement=%s",
                         GetSQLValueString($_POST['intitule'], "text"),
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['parent'], "text"),
                         GetSQLValueString($id, "text"));
    try{
        $query = $pdar_connexion->prepare($insertSQL);
        $Result1 = $query->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    //update code des communes
    $insertSQL1 = sprintf("UPDATE ".$database_connect_prefix."commune SET departement=%s WHERE departement=%s",
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['old_code'], "text"));
    try{
        $query = $pdar_connexion->prepare($insertSQL1);
        $Result1 = $query->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    $insertGoTo .= (isset($_POST['niveau']))?"&niveau=".$_POST['niveau']:"";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{ //commune
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."commune (nom_commune, code_commune, departement, id_personnel, date_enregistrement) VALUES (%s, %s, %s,'$personnel', '$date')",
                         GetSQLValueString($_POST['intitule'], "text"),
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['parent'], "text"));
    try{
        $query = $pdar_connexion->prepare($insertSQL);
        $Result1 = $query->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok&add_new=1"; else $insertGoTo .= "?insert=no";
    $insertGoTo .= (isset($_POST['niveau']))?"&niveau=".$_POST['niveau']:"";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."commune WHERE code_commune=%s",
                         GetSQLValueString($id, "text"));
    try{
        $query = $pdar_connexion->prepare($insertSQL);
        $Result1 = $query->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    $insertGoTo .= (isset($_POST['niveau']))?"&niveau=".$_POST['niveau']:"";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
    $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."commune SET nom_commune=%s, code_commune=%s, departement=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE code_commune=%s",
                         GetSQLValueString($_POST['intitule'], "text"),
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['parent'], "text"),
                         GetSQLValueString($id, "text"));
    try{
        $query = $pdar_connexion->prepare($insertSQL);
        $Result1 = $query->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    //update code des communes
    $insertSQL1 = sprintf("UPDATE ".$database_connect_prefix."village SET commune=%s WHERE commune=%s",
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['old_code'], "text"));
    try{
        $query = $pdar_connexion->prepare($insertSQL1);
        $Result = $query->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    $insertGoTo .= (isset($_POST['niveau']))?"&niveau=".$_POST['niveau']:"";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form4"))
{ //Village
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."village (nom_village, code_village, commune, longitude, latitude, homme, femme, jeune, nb_menage, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, '$personnel', '$date')",
                         GetSQLValueString($_POST['intitule'], "text"),
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['parent'], "text"),
                         GetSQLValueString($_POST['longitude'], "double"),
                         GetSQLValueString($_POST['latitude'], "double"),
                         GetSQLValueString($_POST['homme'], "int"),
                         GetSQLValueString($_POST['femme'], "int"),
                         GetSQLValueString($_POST['jeune'], "int"),
                         GetSQLValueString($_POST['menage'], "int"));
    try{
        $query = $pdar_connexion->prepare($insertSQL);
        $Result1 = $query->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok&add_new=1"; else $insertGoTo .= "?insert=no";
    $insertGoTo .= (isset($_POST['niveau']))?"&niveau=".$_POST['niveau']:"";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."village WHERE code_village=%s",
                         GetSQLValueString($id, "text"));
    try{
        $query = $pdar_connexion->prepare($insertSQL);
        $Result1 = $query->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    $insertGoTo .= (isset($_POST['niveau']))?"&niveau=".$_POST['niveau']:"";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
    $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."village SET nom_village=%s, code_village=%s, commune=%s, longitude=%s, latitude=%s, homme=%s, femme=%s, jeune=%s, nb_menage=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE code_village=%s",
                         GetSQLValueString($_POST['intitule'], "text"),
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['parent'], "text"),
                         GetSQLValueString($_POST['longitude'], "double"),
                         GetSQLValueString($_POST['latitude'], "double"),
                         GetSQLValueString($_POST['homme'], "int"),
                         GetSQLValueString($_POST['femme'], "int"),
                         GetSQLValueString($_POST['jeune'], "int"),
                         GetSQLValueString($_POST['menage'], "int"),
                         GetSQLValueString($id, "text"));
    try{
        $query = $pdar_connexion->prepare($insertSQL);
        $Result1 = $query->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    $insertGoTo .= (isset($_POST['niveau']))?"&niveau=".$_POST['niveau']:"";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
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
h1, .h1, h2, .h2, h3, .h3 {
  margin-top: 10px;
  margin-bottom: 10px;
}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>
<script>
	$(document).ready(function() {
	  <?php if(isset($_GET["add_new"]) && $_GET["add_new"]==1){ ?>
	  $("#new_loc").click();
      <?php } ?>
    });
</script>
<?php
//Localite
$val0 = $val1 = $val2 = "";
for($i=0; $i<$niveau; $i++) {
$val0 .= " T$i.nom_".$loc[$i].", ";
$val1 .= (($i>0)?" and":"")." T".($i+1).".".$loc[$i]."=T$i.code_".$loc[$i]." ";
$val2 .= $database_connect_prefix.$loc[$i]." T$i, ";
}
$val0 .= " T$i.* ";
$val1 = ($i>0)?" WHERE ".$val1:$val1;
$val2 .= $database_connect_prefix.$loc[$i]." T$i ";
$query_liste_activite_1 = "SELECT distinct $val0 FROM $val2 $val1 ORDER BY T$i.code_".$loc[$niveau]." ASC";
try{
    $liste_activite_1 = $pdar_connexion->prepare($query_liste_activite_1);
    $liste_activite_1->execute();
    $row_liste_activite_1 = $liste_activite_1 ->fetchAll();
    $totalRows_liste_activite_1 = $liste_activite_1->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_popoulation = "SELECT T0.code_region, T1.code_departement, T2.code_commune, T3.* FROM region T0, departement T1, commune T2, village T3 WHERE T1.region=T0.code_region and T2.departement=T1.code_departement and T3.commune=T2.code_commune";
$popoulation = mysql_query($query_popoulation , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_popoulation = mysql_fetch_assoc($popoulation);
$totalRows_popoulation = mysql_num_rows($popoulation);
$population_array = array();
if($totalRows_popoulation>0){
do{
  foreach($loc as $a)
  {
    //echo $a;
    if(!isset($population_array[$a]["homme"][$row_popoulation["code_$a"]]))
    $population_array[$a]["homme"][$row_popoulation["code_$a"]] = 0;
    if(!isset($population_array[$a]["femme"][$row_popoulation["code_$a"]]))
    $population_array[$a]["femme"][$row_popoulation["code_$a"]] = 0;
    if(!isset($population_array[$a]["jeune"][$row_popoulation["code_$a"]]))
    $population_array[$a]["jeune"][$row_popoulation["code_$a"]] = 0;
    if(!isset($population_array[$a]["total"][$row_popoulation["code_$a"]]))
    $population_array[$a]["total"][$row_popoulation["code_$a"]] = 0;
    $population_array[$a]["homme"][$row_popoulation["code_$a"]] += $row_popoulation["homme"];
    $population_array[$a]["femme"][$row_popoulation["code_$a"]] += $row_popoulation["femme"];
    $population_array[$a]["jeune"][$row_popoulation["code_$a"]] += $row_popoulation["jeune"];
    $population_array[$a]["total"][$row_popoulation["code_$a"]] += $row_popoulation["homme"]+$row_popoulation["femme"];
  }
}while($row_popoulation = mysql_fetch_assoc($popoulation)); }*/
?>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> <?php echo "$libelle[$niveau]"; ?> </h4>
<?php //if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php //if(isset($niveau)) { $lib = $libelle[$niveau]; if($niveau<count($libelle)) {
 // echo do_link("new_loc","","$lib","<i class=\"icon-plus\"> Ajouter $lib </i>","","./","pull-right p11","get_content('new_localite.php','niveau=".($niveau)."','modal-body_add',this.title);",1,"",$nfile);
  //echo do_link("","localites.php","Retour","Retour","","./","pull-right p11","",0,"",$nfile);
  //}
//} }
?>
<?php echo do_link("","./localites.php?niveau=0","R&eacute;gions","R&eacute;gions","","./","pull-right p11","",0,"",$nfile); ?>
<?php echo do_link("","./localites.php?niveau=1","Pr&eacute;fectures","Pr&eacute;fectures","","./","pull-right p11","",0,"",$nfile); ?>
<?php echo do_link("","./localites.php?niveau=2","Communes","Communes","","./","pull-right p11","",0,"",$nfile); ?>
<?php echo do_link("","./localites.php?niveau=3","Canton","Canton","","./","pull-right p11","",0,"",$nfile); ?>
</div>
<div class="widget-content" style="display: block;">
<form name="form1" action="" method="post">
<table class="table table-striped table-bordered table-hover table-responsive table-checkable table-colvis datatable dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<?php if(count($libelle)>0 && $niveau<count($libelle)){ ?>
                <thead>
                  <tr>
                    <th class="checkbox-column" width="10"> <input type="checkbox" class="uniform"> </th>
                    <td width="120"><strong>Code</strong></td>
<?php for($i=0; $i<=$niveau; $i++) { ?>
                    <td><?php echo ($i==$niveau)?"<strong>$libelle[$i]</strong>":$libelle[$i]; ?></td>
<?php } ?>
                    <?php if($niveau==0) { ?>
                    <td><strong>Abbr&eacute;viation</strong></td>
                    <td><strong>Couleur</strong></td>
                    <?php } ?>
                   
                 
                  </tr>
                </thead>
                <tbody>
<?php if($totalRows_liste_activite_1>0){ foreach($row_liste_activite_1 as $row_liste_activite_1){ $id = $row_liste_activite_1["id_".$loc[$niveau]]; $code = $row_liste_activite_1["code_".$loc[$niveau]]; $parent = ($niveau>0)?$row_liste_activite_1[$loc[$niveau-1]]:0; ?>
                <tr>
                    <td class="checkbox-column"> <input type="checkbox" name="id_val[]" value="<?php echo $id; ?>" class="uniform"> </td>
                    <td><?php echo $code; ?></td>
                    <?php for($i=0; $i<$niveau; $i++) { ?>
                    <td><?php echo $row_liste_activite_1["nom_".$loc[$i]]; ?></td>
                    <?php } ?>
                    <td><strong><?php echo $row_liste_activite_1["nom_".$loc[$niveau]]; ?></strong></td>
                    <?php if($niveau==0) { ?>
                    <td><?php echo $row_liste_activite_1["abrege_".$loc[$niveau]]; ?></td>
                    <td><div class="progress-bar progress-bar-info" style="width: 100%;background-color: <?php echo $row_liste_activite_1["couleur"]; ?>;height: 20px;"><?php echo $row_liste_activite_1["couleur"]; ?></div></td>
                    <?php } ?>
                   

                </tr>
<?php } } ?>
                </tbody>
<?php } else { ?>
                <tr>
                  <td><div align="center" class=""><h2>Aucune localit&eacute;/Site</h2></div></td>
                </tr>
                <?php } ?>
            </table>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<div class="row"> <div class="table-footer"> <div class="col-md-6"> <div class="table-actions"> <label>Pour la s&eacute;lection :</label> <select onchange="if(confirm('Vous confirmez la suppression multiple ?')) form1.submit();" class="select2" data-minimum-results-for-search="-1" data-placeholder="S&eacute;lection..."> <option value=""></option> <option value="Delete">Supprimer</option>  </select> <input type="hidden" name="niveau" value="<?php echo $niveau; ?>" /></div> </div></div> </div>
<?php } ?>
</form>
    </div>
</div>
<!-- Fin Site contenu ici -->
            </div>
        </div>
        </div>
    </div> <?php include_once("modal_add.php"); ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>