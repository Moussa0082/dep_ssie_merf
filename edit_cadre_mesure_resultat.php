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

$personnel=$_SESSION['clp_id']; $date = date("Y-m-d");


if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if(isset($_GET["ugl"]) && $_GET["ugl"]) $ugl=$_GET["ugl"]; else unset($_GET["ugl"],$ugl);

if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
$query_sup_activite = "DELETE FROM ".$database_connect_prefix."indicateur_cmr WHERE id_ref_ind='$id'";

  try{
        $Result1 = $pdar_connexion->prepare($query_sup_activite);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo));
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  	if(!empty($_POST['acteur'])) { foreach($_POST['acteur'] as $vacteur) { $acteur=$acteur.",".$vacteur; } }
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."indicateur_cmr (intitule_ref_ind,resultat, code_ref_ind, referentiel, cible_cmr, annee_reference, accueil, reference_cmr,  unite_cmr, fonction_agregat, structure, responsable_collecte, projet,id_personnel) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, '$personnel')",
                         GetSQLValueString($_POST['intitule_ref_ind'], "text"),
                         GetSQLValueString($_POST['resultat'], "text"),
  					     GetSQLValueString($_POST['code_ref_ind'], "text"),
                         GetSQLValueString((isset($_POST['referentiel'])?$_POST['referentiel']:0), "int"),
     				   GetSQLValueString($_POST['cible_dp'], "text"),
                       GetSQLValueString($_POST['annee_reference'], "int"),
                       GetSQLValueString((isset($_POST['accueil'])?$_POST['accueil']:0), "int"),
  					   GetSQLValueString($_POST['reference_cmr'], "text"),
   					   GetSQLValueString($_POST['unite_cmr'], "text"),
  					   GetSQLValueString($_POST['fonction_agregat'], "text"),
  						  GetSQLValueString("00", "text"),
  					   GetSQLValueString($acteur, "text"),
                       GetSQLValueString($_SESSION['clp_projet'], "text"));


  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));
  }

  if (isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"])) {
      $id = ($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."indicateur_cmr WHERE id_ref_ind=%s",
                           GetSQLValueString($id, "text"));


  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
        $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if (isset($_POST["MM_update"]) && !empty($_POST["MM_update"])) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id'];
  	if(!empty($_POST['acteur'])) { foreach($_POST['acteur'] as $vacteur) { $acteur=$acteur.",".$vacteur; } }
  	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."indicateur_cmr SET intitule_ref_ind=%s, resultat=%s, code_ref_ind=%s, referentiel=%s, cible_cmr=%s, annee_reference=%s, accueil=%s, reference_cmr=%s, unite_cmr=%s, fonction_agregat=%s,  responsable_collecte=%s, modifier_par='$personnel', modifier_le='$date' WHERE id_ref_ind='$c'",
                         GetSQLValueString($_POST['intitule_ref_ind'], "text"),
                         GetSQLValueString($_POST['resultat'], "text"),
  					     GetSQLValueString($_POST['code_ref_ind'], "text"),
                         GetSQLValueString((isset($_POST['referentiel'])?$_POST['referentiel']:0), "int"),
     					   GetSQLValueString($_POST['cible_dp'], "text"),
                         GetSQLValueString($_POST['annee_reference'], "int"),
                         GetSQLValueString($_POST['accueil'], "int"),
  					   GetSQLValueString($_POST['reference_cmr'], "text"),
   					   GetSQLValueString($_POST['unite_cmr'], "text"),
  					   GetSQLValueString($_POST['fonction_agregat'], "text"),
  					   GetSQLValueString($acteur, "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));
  }
}


if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form5"))
{
   //insertion indicateur
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $idsy = $_POST['id'];

  $query_sup_ind = "DELETE FROM ".$database_connect_prefix."calcul_indicateur_simple_ref WHERE indicateur_ref='$idsy'";
  try{
        $Result1 = $pdar_connexion->prepare($query_sup_ind);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."calcul_indicateur_simple_ref (indicateur_ref, formule_indicateur_simple, indicateur_simple, id_personnel, date_enregistrement) VALUES (%s, %s, %s,'$personnel', '$date')",
                         GetSQLValueString($idsy, "text"),
  					   GetSQLValueString($_POST['formule_indicateur_simple'], "text"),
  					   GetSQLValueString(implode(',',$_POST['indicateur_simple']), "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
        $insertGoTo = $_SERVER['PHP_SELF'];

    if ($Result1) $insertGoTo .= "?insert=ok";  else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();

    }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form6"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert") && isset($_POST['denominateur']) && isset($_POST['numerateur'])) {

  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $idsy = $_POST['id'];

  $query_sup_ind = "DELETE FROM ".$database_connect_prefix."ratio_indicateur_ref WHERE indicateur_ref='$idsy'";
  try{
        $Result1 = $pdar_connexion->prepare($query_sup_ind);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }

    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."ratio_indicateur_ref (indicateur_ref, numerateur, denominateur, coefficient, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s,'$personnel', '$date')",
                         GetSQLValueString($idsy, "text"),
  					   GetSQLValueString($_POST['numerateur'], "text"),
  					   GetSQLValueString($_POST['denominateur'], "text"),
                         GetSQLValueString($_POST['coefficient'], "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $insertGoTo = $_SERVER['PHP_SELF'];

    if ($Result1) $insertGoTo .= "?insert=ok$param_url";  else $insertGoTo .= "?insert=no$param_url";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
}

$query_liste_composante = sprintf("SELECT * FROM ".$database_connect_prefix."indicateur_cmr WHERE projet=%s ORDER BY code_ref_ind",
    //GetSQLValueString($_SESSION['clp_structure'], "text"),
    GetSQLValueString($_SESSION['clp_projet'], "text"));
      	try{
    $liste_composante = $pdar_connexion->prepare($query_liste_composante);
    $liste_composante->execute();
    $row_liste_composante = $liste_composante ->fetchAll();
    $totalRows_liste_composante = $liste_composante->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur_calcul = sprintf("SELECT indicateur_ref, code_ref_ind, code_ref_ind, intitule_ref_ind FROM ".$database_connect_prefix."indicateur_cmr, ".$database_connect_prefix."calcul_indicateur_simple_ref WHERE FIND_IN_SET( code_ref_ind, indicateur_simple ) and mode_calcul = 'Unique' and projet=%s ORDER BY indicateur_ref",
    //GetSQLValueString($_SESSION['clp_structure'], "text"),
    GetSQLValueString($_SESSION['clp_projet'], "text"));
$liste_indicateur_calcul = mysql_query_ruche($query_liste_indicateur_calcul, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_indicateur_calcul = mysql_fetch_assoc($liste_indicateur_calcul);
$totalRows_liste_indicateur_calcul = mysql_num_rows($liste_indicateur_calcul);

$liste_indicateur_simple_array=array();
if($totalRows_liste_indicateur_calcul>0){
do{ $liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]=(isset($liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]))?$liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']].$row_liste_indicateur_calcul['code_ref_ind'].",":$row_liste_indicateur_calcul['code_ref_ind'].",";}while($row_liste_indicateur_calcul = mysql_fetch_assoc($liste_indicateur_calcul)); }*/
$query_liste_ind_ratio = "SELECT indicateur_ref, numerateur, denominateur, coefficient FROM ".$database_connect_prefix."ratio_indicateur_ref order by indicateur_ref";
      	try{
    $liste_ind_ratio = $pdar_connexion->prepare($query_liste_ind_ratio);
    $liste_ind_ratio->execute();
    $row_liste_ind_ratio = $liste_ind_ratio ->fetchAll();
    $totalRows_liste_ind_ratio = $liste_ind_ratio->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_num_ratio_array = array();
$liste_deno_ratio_array = array();
foreach($row_liste_ind_ratio as $row_liste_ind_ratio){ 
 $liste_num_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["numerateur"];
  $liste_deno_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = ($row_liste_ind_ratio["denominateur"]==-1)?$row_liste_ind_ratio["coefficient"]." / 1)":$row_liste_ind_ratio["denominateur"];
}


$query_liste_code_ref = sprintf("SELECT code_ref_ind, code_ref_ind FROM ".$database_connect_prefix."indicateur_cmr WHERE projet=%s order by code_ref_ind",
   // GetSQLValueString($_SESSION['clp_structure'], "text"),
    GetSQLValueString($_SESSION['clp_projet'], "text"));
      	try{
    $liste_code_ref = $pdar_connexion->prepare($query_liste_code_ref);
    $liste_code_ref->execute();
    $row_liste_code_ref = $liste_code_ref ->fetchAll();
    $totalRows_liste_code_ref = $liste_code_ref->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$liste_code_ref_array = array();
foreach($row_liste_code_ref as $row_liste_code_ref){ 
 $liste_code_ref_array[$row_liste_code_ref["code_ref_ind"]] = $row_liste_code_ref["code_ref_ind"];
}

//Cible indicateur à sommer
$query_cible_indicateur = "SELECT indicateur_produit, annee, sum(valeur_cible) as valeur_cible, avg(valeur_cible) as valeur_ciblem  FROM   ".$database_connect_prefix."cible_cmr_produit group by annee, indicateur_produit";
      	try{
    $cible_indicateur = $pdar_connexion->prepare($query_cible_indicateur);
    $cible_indicateur->execute();
    $row_cible_indicateur = $cible_indicateur ->fetchAll();
    $totalRows_cible_indicateur = $cible_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$cible_array = array();
$ciblem_array = array();
if($totalRows_cible_indicateur>0){ 
foreach($row_cible_indicateur as $row_cible_indicateur){ 
  $cible_array[$row_cible_indicateur["indicateur_produit"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_cible"];
  $ciblem_array[$row_cible_indicateur["indicateur_produit"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_ciblem"];
   }}

//cible indicateurs à faire la moyenne
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_ciblem_indicateur = "SELECT indicateur_cr, annee, avg(valeur_cible) as valeur_cible FROM   ".$database_connect_prefix."cible_cmr group by annee, indicateur_cr";
$ciblem_indicateur  = mysql_query_ruche($query_ciblem_indicateur , $pdar_connexion) or die(mysql_error());
$row_ciblem_indicateur = mysql_fetch_assoc($ciblem_indicateur );
$totalRows_ciblem_indicateur = mysql_num_rows($ciblem_indicateur );
$ciblem_array = array();
if($totalRows_ciblem_indicateur>0){  do{ $cible_array[$row_ciblem_indicateur["indicateur_cr"]][$row_ciblem_indicateur["annee"]]=$row_ciblem_indicateur["valeur_cible"]; }
while($row_ciblem_indicateur  = mysql_fetch_assoc($ciblem_indicateur));}*/

$query_liste_ugl = "SELECT * FROM ".$database_connect_prefix."ugl  order by code_ugl asc";
      	try{
    $liste_ugl = $pdar_connexion->prepare($query_liste_ugl);
    $liste_ugl->execute();
    $row_liste_ugl = $liste_ugl ->fetchAll();
    $totalRows_liste_ugl = $liste_ugl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if(isset($ugl)) {
$query_vcible_indicateur = "SELECT indicateur_produit, zone, annee, sum(valeur_cible) as valeur_cible FROM   ".$database_connect_prefix."cible_cmr_produit group by indicateur_produit, zone, annee";
      	try{
    $vcible_indicateur = $pdar_connexion->prepare($query_vcible_indicateur);
    $vcible_indicateur->execute();
    $row_vcible_indicateur = $vcible_indicateur ->fetchAll();
    $totalRows_vcible_indicateur = $vcible_indicateur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$vcible_array = array();
$vcible_an_array = array();
if($totalRows_vcible_indicateur>0){   foreach($row_vcible_indicateur as $row_vcible_indicateur){ 
 $vcible_an_array[$row_vcible_indicateur["indicateur_produit"]][$row_vcible_indicateur["annee"]][$row_vcible_indicateur["zone"]]=$row_vcible_indicateur["valeur_cible"];
 $vcible_array[$row_vcible_indicateur["indicateur_produit"]][$row_vcible_indicateur["zone"]]=$row_vcible_indicateur["valeur_cible"];
}}

$query_nom_ugl = "SELECT * FROM ".$database_connect_prefix."ugl  where id_ugl='$ugl'";
      	try{
    $nom_ugl = $pdar_connexion->prepare($query_nom_ugl);
    $nom_ugl->execute();
    $row_nom_ugl = $nom_ugl ->fetch();
    $totalRows_nom_ugl = $nom_ugl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
}

$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind FROM referentiel_indicateur";
      	try{
    $liste_ind_ref = $pdar_connexion->prepare($query_liste_ind_ref);
    $liste_ind_ref->execute();
    $row_liste_ind_ref = $liste_ind_ref ->fetchAll();
    $totalRows_liste_ind_ref = $liste_ind_ref->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$liste_ind_ref_array = array();
$unite_ind_ref_array = array();
$mode_calcul_ind_ref_array = array();
 foreach($row_liste_ind_ref as $row_liste_ind_ref){  $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"];
 $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite_cmr"];
// $mode_calcul_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["mode_calcul"];
}

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

 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init();});</script>

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
    <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==0){?>
<?php   echo do_link("","","Ajout un indicateur CMR","<i class=\"icon-plus\"> Ajouter un indicateur </i>","","./","pull-right p11","get_content('new_indicateur_cmr.php','','modal-body_add',this.title);",1,"",$nfile); ?>



<!--<form name="form38" id="form38" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="pull-right">

<select name="ugl" onchange="form38.submit();" style="background-color: #FFFF00; padding: 7px; width: 150px;" class="btn p11">

  <option value="">-- Toutes les zones --</option>

<?php //if($totalRows_liste_ugl>0) {  foreach($row_liste_ugl as $row_liste_ugl){ ?>
<option <?php //echo (isset($ugl) && $row_liste_ugl['code_ugl']==$ugl)?'selected="selected"':''; ?> value="<?php //echo $row_liste_ugl['code_ugl'];?>"><?php //echo  $row_liste_ugl['nom_ugl'];?></option>
  <?php //} } ?>

</select>

</form>-->

    <?php } ?>
</div>

<div class="widget-content">
<div align="center"></div>
<table class="table table-striped table-bordered table-hover table-responsive  table-colvis datatable dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">composante</th>-->
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Code</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Indicateur </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Unit&eacute; </th>
<th nowrap="nowrap" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Mode de calcul</th>
<th nowrap="nowrap" class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Situation de<br /> 
  r&eacute;f&eacute;rence </th>
 <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
 <th class="" role="" tabindex="0" aria-controls="" aria-label="" <?php if($i==date("Y")) { ?>style="background-color:#FFCC33"   <?php } ?>><div align="center"><strong>
   <?php
					  
						 echo $i; ?>
 </strong>&nbsp;</div></th>
                       <?php } ?>

<th  class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" >Valeurs cibles </th>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==0) { ?>
<th class="sorting" role="" tabindex="0" aria-controls="" aria-label="" width="80">Actions</th>
<?php } ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php if($totalRows_liste_composante>0) { $i=0; foreach($row_liste_composante as $row_liste_composante){ if(isset($row_liste_composante['code_ref_ind']) && !empty($row_liste_composante['code_ref_ind']))$id = $row_liste_composante['id_ref_ind'];
if((isset($ugl) && isset($vcible_array[$row_liste_composante["id_ref_ind"]][$ugl])) || !isset($ugl)) {?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo (!empty($row_liste_composante['code_ref_ind']))?$row_liste_composante['code_ref_ind']:""; ?></td>
<td class=" "><?php echo (!empty($row_liste_composante['intitule_ref_ind']))?$row_liste_composante['intitule_ref_ind']:""; ?></td>
<td class=" "><div align="center"><?php // echo (!empty($row_liste_composante['unite_cmr']))?$row_liste_composante['unite_cmr']:""; ?>
<?php if(!empty($row_liste_composante['unite_cmr']) && $row_liste_composante['unite_cmr']=="Score") { ?>
<a onclick="get_content('./liste_critere_cmr.php','<?php echo "id_ind=".$id ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Crit&egrave;res par score" class="thickbox" dir=""><?php echo $row_liste_composante['unite_cmr']  ?></a></div><?php } else echo $row_liste_composante['unite_cmr']; ?>
</td>
<td class=" " ><div align="center"><?php echo (!empty($row_liste_composante['fonction_agregat']))?$row_liste_composante['fonction_agregat']:""; ?></div></td>
<td class=" " ><div align="right"><?php echo (!empty($row_liste_composante['reference_cmr']))?$row_liste_composante['reference_cmr']:""; ?></div></td>
 <?php $unite=$row_liste_composante['unite_cmr']; for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
                     <td nowrap="nowrap" class=" "><div align="right"><strong>
                       <?php
				if(isset($ugl) && isset($vcible_an_array[$row_liste_composante["id_ref_ind"]][$i][$ugl]))
				
				 echo number_format($vcible_an_array[$row_liste_composante["id_ref_ind"]][$i][$ugl], 0, ',', ' ');
				 
				elseif(!isset($ugl) && isset($cible_array[$row_liste_composante["id_ref_ind"]][$i]) && $unite!="%")
				 echo number_format($cible_array[$row_liste_composante["id_ref_ind"]][$i], 0, ',', ' ');
				 elseif(!isset($ugl) && isset($ciblem_array[$row_liste_composante["id_ref_ind"]][$i]) && $unite=="%")
				 echo number_format($ciblem_array[$row_liste_composante["id_ref_ind"]][$i], 0, ',', ' ');
				  ?>
                     </strong>&nbsp;</div></td>
                       <?php } ?>
<td class=" "><div align="right"><b>
  <a onclick="get_content('./edit_cible_indicateur.php','<?php echo "id_ind=".$id."&code_act=".$row_liste_composante['code_ref_ind']; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="'<?php echo str_replace("'","\'",$row_liste_composante['intitule_ref_ind']);?>'" class="thickbox" dir=""><?php echo (!empty($row_liste_composante['cible_cmr']))?$row_liste_composante['cible_cmr']:""; ?></a>
</b></div></td>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==0) { ?>
<td class=" " align="center">
<?php
echo do_link("","","Modifier Indicateur ".$row_liste_composante['intitule_ref_ind'],"","edit","./","","get_content('new_indicateur_cmr.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cet indicateur ?');",0,"margin:0px 5px;",$nfile);
?></td>
<?php } ?>
</tr>
<?php } ?>
<?php } } ?>
</tbody></table>

    </div>
</div>
</div>

<!-- Fin Site contenu ici -->

            </div>

        </div>



        </div>


<?php include_once 'modal_add.php'; ?>
    <?php include_once ("includes/footer.php");?>

</div>

</body>

</html>