<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & DÃ©veloppement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;
if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";
if(isset($_GET['composante']) && $_GET['composante']!="") {$_SESSION["composante"]=$_GET['composante']; $composante=$_GET['composante']; $filiere=$_SESSION["composante"];} else {$filiere=0; $composante=0; $_GET['composante']=0;}
if(isset($_GET['composante']) && $_GET['composante']==""){ $_GET['composante']=""; unset($_SESSION["composante"]); $composante=0; }
$where = ($filiere==0)?"":" and composante = ".$filiere." ";
$where .= ($composante==0)?"":" and id_composante=".$composante." ";
if(isset($_GET["sc"])) $sc = $_GET["sc"]; else $sc = 0;
$editFormAction = $_SERVER['PHP_SELF'];
$currentPage = $_SERVER['PHP_SELF']."?composante=$filiere&sc=$sc";
/*if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $acteur="";
	if(!empty($_POST['acteur'])) { foreach($_POST['acteur'] as $vacteur) { $acteur=$acteur.",".$vacteur; } }
	  $insertSQL = sprintf("INSERT INTO indicateur_resultat_cmr (indicateur_res, referentiel, intitule_indicateur_cmr_res, cible_dp, cible_rmp, code_cmr, annee_reference, valeur_reference, responsable_collecte, personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, '$personnel', '$date')",
					   GetSQLValueString($_POST['indicateur_cl'], "int"),
                       GetSQLValueString((isset($_POST['referentiel'])?$_POST['referentiel']:0), "int"),
	   				   GetSQLValueString($_POST['indicateur'], "text"),
   					   GetSQLValueString($_POST['cible_dp'], "double"),
   					   GetSQLValueString($_POST['cible_rmp'], "double"),
   					   GetSQLValueString($_POST['code_cmr'], "text"),
   					   GetSQLValueString($_POST['annee_reference'], "int"),
   					   GetSQLValueString($_POST['valeur_reference'], "double"),
   					   GetSQLValueString($acteur, "text"));
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
      $insertSQL = sprintf("DELETE from indicateur_resultat_cmr WHERE id_indicateur=%s",
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
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id'];  $acteur="";
	if(!empty($_POST['acteur'])) { foreach($_POST['acteur'] as $vacteur) { $acteur=$acteur.",".$vacteur; } }
  $insertSQL = sprintf("UPDATE indicateur_resultat_cmr SET  indicateur_res=%s, referentiel=%s, intitule_indicateur_cmr_res=%s, cible_dp=%s, cible_rmp=%s, code_cmr=%s, annee_reference=%s, valeur_reference=%s, responsable_collecte=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur='$c'",
					   GetSQLValueString($_POST['indicateur_cl'], "int"),
                       GetSQLValueString((isset($_POST['referentiel'])?$_POST['referentiel']:0), "int"),
	   				   GetSQLValueString($_POST['indicateur'], "text"),
   					   GetSQLValueString($_POST['cible_dp'], "double"),
   					   GetSQLValueString($_POST['cible_rmp'], "double"),
   					   GetSQLValueString($_POST['code_cmr'], "text"),
					   GetSQLValueString($_POST['annee_reference'], "int"),
					   GetSQLValueString($_POST['valeur_reference'], "double"),
   					   GetSQLValueString($acteur, "text"));
	   try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form5"))
{
   //insertion indicateur
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $idsy = $_POST['id'];
          $insertSQL = sprintf("DELETE from ratio_indicateur_ref WHERE indicateur_ref=%s",
                           GetSQLValueString($idsy, "int"));
	   try{ $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $indicateur_simple="";
  if(!empty($_POST['indicateur_simple'])) { foreach($_POST['indicateur_simple'] as $vindicateur_simple) { $indicateur_simple=$indicateur_simple.",".$vindicateur_simple; } }
    $insertSQL = sprintf("INSERT INTO calcul_indicateur_simple_ref (indicateur_ref, formule_indicateur_simple, indicateur_simple, id_personnel, date_enregistrement) VALUES (%s, %s, %s,'$personnel', '$date')",
                         GetSQLValueString($idsy, "int"),
  					   GetSQLValueString($_POST['formule_indicateur_simple'], "text"),
  					   GetSQLValueString($indicateur_simple, "text"));
		   try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form6"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert") && isset($_POST['denominateur']) && isset($_POST['numerateur'])) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $idsy = $_POST['id'];
        $insertSQL = sprintf("DELETE from ratio_indicateur_ref WHERE indicateur_ref=%s",
                           GetSQLValueString($idsy, "int"));
	   try{ $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertSQL = sprintf("INSERT INTO ratio_indicateur_ref (indicateur_ref, numerateur, denominateur, coefficient, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s,'$personnel', '$date')",
                         GetSQLValueString($idsy, "int"),
  					   GetSQLValueString($_POST['numerateur'], "text"),
  					   GetSQLValueString($_POST['denominateur'], "text"),
                         GetSQLValueString($_POST['coefficient'], "text"));
	   try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?insert=ok$param_url"; else $insertGoTo .= "?insert=no$param_url";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }
}

$query_liste_composante = "SELECT 	indicateur_resultat.*, indicateur_resultat_cmr.*  FROM resultat, indicateur_resultat,indicateur_resultat_cmr WHERE resultat.projet='".$_SESSION["clp_projet"]."' and id_resultat=resultat and id_indicateur_resultat=indicateur_res order by code_resultat, code_ir, code_cmr";
try{
    $liste_composante = $pdar_connexion->prepare($query_liste_composante);
    $liste_composante->execute();
    $row_liste_composante = $liste_composante ->fetchAll();
    $totalRows_liste_composante = $liste_composante->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }


$query_liste_indicateur_calcul = "SELECT indicateur_ref, id_ref_ind, code_ref_ind, intitule_ref_ind FROM referentiel_indicateur, calcul_indicateur_simple_ref
WHERE FIND_IN_SET( id_ref_ind, indicateur_simple ) and mode_calcul = 'Unique' ORDER BY indicateur_ref";
try{
    $liste_indicateur_calcul = $pdar_connexion->prepare($query_liste_indicateur_calcul);
    $liste_indicateur_calcul->execute();
    $row_liste_indicateur_calcul = $liste_indicateur_calcul ->fetchAll();
    $totalRows_liste_indicateur_calcul = $liste_indicateur_calcul->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_indicateur_simple_array=array();
 if($totalRows_liste_indicateur_calcul>0) { $c=0; foreach($row_liste_indicateur_calcul as $row_liste_indicateur_calcul){  
 $liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]=(isset($liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]))?$liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']].$row_liste_indicateur_calcul['code_ref_ind'].",":$row_liste_indicateur_calcul['code_ref_ind'].",";} }

$query_liste_ind_ratio = "SELECT indicateur_ref, numerateur, denominateur, coefficient FROM ratio_indicateur_ref order by indicateur_ref";
try{
    $liste_ind_ratio = $pdar_connexion->prepare($query_liste_ind_ratio);
    $liste_ind_ratio->execute();
    $row_liste_ind_ratio = $liste_ind_ratio ->fetchAll();
    $totalRows_liste_ind_ratio = $liste_ind_ratio->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_num_ratio_array = array();
$liste_deno_ratio_array = array();
 if($totalRows_liste_ind_ratio>0) { $c=0; foreach($row_liste_ind_ratio as $row_liste_ind_ratio){  
 $liste_num_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["numerateur"];
  $liste_deno_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = ($row_liste_ind_ratio["denominateur"]==-1)?$row_liste_ind_ratio["coefficient"]." / 1)":$row_liste_ind_ratio["denominateur"]; }}

$query_liste_code_ref = "SELECT code_ref_ind, id_ref_ind FROM referentiel_indicateur order by code_ref_ind";
try{
    $liste_code_ref = $pdar_connexion->prepare($query_liste_code_ref);
    $liste_code_ref->execute();
    $row_liste_code_ref = $liste_code_ref ->fetchAll();
    $totalRows_liste_code_ref = $liste_code_ref->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_code_ref_array = array();
 if($totalRows_liste_code_ref>0) { foreach($row_liste_code_ref as $row_liste_code_ref){  
 $liste_code_ref_array[$row_liste_code_ref["id_ref_ind"]] = $row_liste_code_ref["code_ref_ind"];
}}

$query_edit_composante = "SELECT * FROM activite_projet WHERE niveau=1 order by code";
try{
    $edit_composante = $pdar_connexion->prepare($query_edit_composante);
    $edit_composante->execute();
    $row_edit_composante = $edit_composante ->fetchAll();
    $totalRows_edit_composante = $edit_composante->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$query_edit_nom = "SELECT id_resultat, code_resultat, intitule_resultat  FROM resultat WHERE resultat.projet='".$_SESSION["clp_projet"]."' order by code_resultat";
try{
    $edit_nom = $pdar_connexion->prepare($query_edit_nom);
    $edit_nom->execute();
    $row_edit_nom = $edit_nom ->fetchAll();
    $totalRows_edit_nom = $edit_nom->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$code_resultat_array = array(); //$composante_code_array = array();
 if($totalRows_edit_nom>0) { $c=0; foreach($row_edit_nom as $row_edit_nom){  
$code_resultat_array[$row_edit_nom["id_resultat"]]=$row_edit_nom["code_resultat"];
} }

  if ((isset($_GET["id_sup"]) && intval($_GET["id_sup"])>0)) {
      $id = intval($_GET["id_sup"]);
      $insertSQL = sprintf("DELETE from indicateur_resultat_cmr WHERE id_indicateur=%s",
                           GetSQLValueString($id, "int"));
	   try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }
//Cible indicateur à sommer
  
$query_cible_indicateur = "SELECT indicateur_resultat, annee, sum(valeur_cible) as valeur_cible, avg(valeur_cible) as valeur_ciblem  FROM   ".$database_connect_prefix."cible_cmr_resultat group by annee, indicateur_resultat";
try{
    $cible_indicateur = $pdar_connexion->prepare($query_cible_indicateur);
    $cible_indicateur->execute();
    $row_cible_indicateur = $cible_indicateur ->fetchAll();
    $totalRows_cible_indicateur = $cible_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$cible_array = $ciblem_array = array();
 if($totalRows_cible_indicateur>0) { foreach($row_cible_indicateur as $row_cible_indicateur){  
  $cible_array[$row_cible_indicateur["indicateur_resultat"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_cible"];
  $ciblem_array[$row_cible_indicateur["indicateur_resultat"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_ciblem"];
   }}
   
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind, mode_calcul FROM referentiel_indicateur";
try{
    $liste_ind_ref = $pdar_connexion->prepare($query_liste_ind_ref);
    $liste_ind_ref->execute();
    $row_liste_ind_ref = $liste_ind_ref ->fetchAll();
    $totalRows_liste_ind_ref = $liste_ind_ref->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_ind_ref_array = $unite_ind_ref_array = $mode_calcul_ind_ref_array = array();
 if($totalRows_liste_ind_ref>0) { foreach($row_liste_ind_ref as $row_liste_ind_ref){  
  $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"];
 $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
 $mode_calcul_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["mode_calcul"];
}}
//exit;
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
<div class="widget-header"> <h4><i class="icon-reorder"></i> Cadre de Mesure de R&eacute;sultats (<span style="color:#FFFF00">Effets</span>) </h4>
    <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2){?>
		<a onclick="get_content('edit_indicateur_rescmr.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Ajout d'indicateur d'Effet" class="btn btn-sm btn-warning pull-right p11" dir=""><i class="icon-plus"> Nouvel indicateur </i></a>
<a href="og_cmr.php" title="Editer les Impacts" class="pull-right p11"> Impacts </a>
<a href="odp_cmr.php" title="Editer les objectifs de d&eacute;veloppement" class="pull-right p11"> Objectif de D&eacute;veloppement </a>
<!--<a href="effet_cmr.php" title="Editer les effets" class="pull-right p11"><i class="icon-plus"> Effets </i></a>-->
<a href="produit_cmr.php" title="Editer les produits" class="pull-right p11">Produits </a>
    <?php } ?>
</div>
<div class="widget-content">
<table class="table table-striped table-bordered table-hover table-responsive table-checkable table-colvis datatable dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">composante</th>-->
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Effet</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Indicateur </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Unit&eacute; </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier"><div align="center">R&eacute;f&eacute;rence</div></th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Mode</th>
 <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
 <th class="" role="" tabindex="0" aria-controls="" aria-label="" <?php if($i==date("Y")) { ?>style="background-color:#FFCC33"   <?php } ?>><strong>
				      <?php
					  
						 echo $i; ?>
                  </strong>&nbsp;</th>
                      
                       <?php } ?> <th class="" role="" tabindex="0" aria-controls="" aria-label="">&nbsp;</th>
				      <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="90">Actions</th>
<?php } ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
 <?php  if($totalRows_liste_composante>0) { $i=0; foreach($row_liste_composante as $row_liste_composante){   if(isset($row_liste_composante['id_indicateur']) && !empty($row_liste_composante['id_indicateur']))$id = $row_liste_composante['id_indicateur']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php if(isset($code_resultat_array[$row_liste_composante['resultat']])) echo $code_resultat_array[$row_liste_composante['resultat']]; ?></td>
<td class=" "><?php echo (!empty($row_liste_composante['intitule_indicateur_cmr_res']))?$row_liste_composante['intitule_indicateur_cmr_res']:""; ?></td>
<td class=" "><span class="Style22">
  <?php if(isset($unite_ind_ref_array[$row_liste_composante["referentiel"]])) $unite = $unite_ind_ref_array[$row_liste_composante["referentiel"]]; else  $unite=""; echo $unite; ?>
</span></td>
<td class=" "><div align="center"><?php echo (!empty($row_liste_composante['valeur_reference']))?$row_liste_composante['valeur_reference']:""; ?></div></td>
<td class=" " align="center"><span class="Style22">
  <?php if(isset($mode_calcul_ind_ref_array[$row_liste_composante["referentiel"]])) echo $mode_calcul_ind_ref_array[$row_liste_composante["referentiel"]];  ?>
</span></td>
 
 <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
                     <td nowrap="nowrap" class=" "><strong>
				      <?php
				if(isset($cible_array[$row_liste_composante["id_indicateur"]][$i])) 
				 echo number_format($cible_array[$row_liste_composante["id_indicateur"]][$i], 0, ',', ' ');
				 else echo "-";
				  ?>
                  </strong>&nbsp;</td>
    <?php } ?>
                       <td class=" " align="center"><a onclick="get_content('./edit_cible_indicateur_res.php','<?php echo "id_ind=".$row_liste_composante['id_indicateur']."&code_act=".$row_liste_composante['code_cmr']; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="'<?php echo str_replace("'","\'",$row_liste_composante['intitule_indicateur_cmr_res']);?>'" class="thickbox" dir="">Cible</a></td>
                   
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
<td class=" " align="center">
<?php
echo do_link("","","Modifier Indicateur ".$row_liste_composante['intitule_indicateur_cmr_res'],"","edit","./","","get_content('edit_indicateur_rescmr.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);
echo do_link("",$_SERVER['PHP_SELF']."?id_sup=$id","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cet indicateur ?');",0,"margin:0px 5px;",$nfile);
?></td>
</tr>
<?php } ?>
<?php } } ?>
</tbody></table>
    </div>
</div>
<!-- Fin Site contenu ici -->
        </div>

        </div>
    </div> <?php include_once 'modal_add.php'; ?>  
    <?php include_once ("includes/footer.php");?>
</div>
</div>
</body>
</html>