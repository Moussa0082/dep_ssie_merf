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
  $insertSQL = sprintf("INSERT INTO appendice4 (referentiel, intitule_indicateur, cible_cmr,cible_rmp, responsable_collecte, cle, code, personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, '$personnel', '$date')",
                       GetSQLValueString((isset($_POST['referentiel'])?$_POST['referentiel']:0), "int"),
	   				   GetSQLValueString($_POST['indicateur'], "text"),
					   GetSQLValueString($cible, "double"),
                       GetSQLValueString($ciblermp, "double"),
					   GetSQLValueString($acteur, "text"),
                       GetSQLValueString($_POST['cle'], "int"),
   					 // GetSQLValueString($_POST['beneficiaire'], "int"),
					   GetSQLValueString(trim($_POST['code']), "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));   exit(0);
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
      $id = intval($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from appendice4 WHERE id_indicateur=%s",
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

  $insertSQL = sprintf("UPDATE appendice4 SET  referentiel=%s,  intitule_indicateur=%s, cible_cmr=%s, cible_rmp=%s, responsable_collecte=%s, cle=%s,  code=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur='$c'",
                       GetSQLValueString((isset($_POST['referentiel'])?$_POST['referentiel']:0), "int"),
	   				   GetSQLValueString($_POST['indicateur'], "text"),
					   GetSQLValueString($cible, "double"),
                       GetSQLValueString($ciblermp, "double"),
   					   GetSQLValueString($acteur, "text"),
                       GetSQLValueString($_POST['cle'], "int"),
   					 // GetSQLValueString($_POST['beneficiaire'], "int"),
					   GetSQLValueString(trim($_POST['code']), "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));   exit(0);
  }

}


if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form5"))
{
   //insertion indicateur
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $idsy = $_POST['id'];

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_sup_ind = "DELETE FROM calcul_indicateur_simple_ref WHERE indicateur_ref='$idsy'";
  $Result1 = mysql_query($query_sup_ind, $pdar_connexion) or die(mysql_error());

  $indicateur_simple="";
  if(!empty($_POST['indicateur_simple'])) { foreach($_POST['indicateur_simple'] as $vindicateur_simple) { $indicateur_simple=$indicateur_simple.",".$vindicateur_simple; } }

    $insertSQL = sprintf("INSERT INTO calcul_indicateur_simple_ref (indicateur_ref, formule_indicateur_simple, indicateur_simple, id_personnel, date_enregistrement) VALUES (%s, %s, %s,'$personnel', '$date')",
                         GetSQLValueString($idsy, "int"),
  					   GetSQLValueString($_POST['formule_indicateur_simple'], "text"),
  					   GetSQLValueString($indicateur_simple, "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
      $insertGoTo = $_SERVER['PHP_SELF'];

    if ($Result1) $insertGoTo .= "?insert=ok";  else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();

    }

}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form6"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert") && isset($_POST['denominateur']) && isset($_POST['numerateur'])) {

  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $idsy = $_POST['id'];

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_sup_ind = "DELETE FROM ratio_indicateur_ref WHERE indicateur_ref='$idsy'";
  $Result1 = mysql_query($query_sup_ind, $pdar_connexion) or die(mysql_error());

    $insertSQL = sprintf("INSERT INTO ratio_indicateur_ref (indicateur_ref, numerateur, denominateur, coefficient, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s,'$personnel', '$date')",
                         GetSQLValueString($idsy, "int"),
  					   GetSQLValueString($_POST['numerateur'], "text"),
  					   GetSQLValueString($_POST['denominateur'], "text"),
                         GetSQLValueString($_POST['coefficient'], "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
      $insertGoTo = $_SERVER['PHP_SELF'];

    if ($Result1) $insertGoTo .= "?insert=ok$param_url";  else $insertGoTo .= "?insert=no$param_url";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

}

mysql_select_db($database_pdar_connexion, $pdar_connexion); //".$_SESSION["clp_where"]." and
//$query_liste_composante = "SELECT * FROM activite_projet Right JOIN referentiel_indicateur ON code=composante WHERE niveau=1 $where ORDER BY code,code_ref_ind";
//$query_liste_composante = "SELECT * FROM indicateur_produit, appendice4 where  id_indicateur_produit=indicateur_prd  order by code_iprd asc, code_irprd";

$query_liste_composante = "SELECT * FROM appendice4 order by code";

$liste_composante  = mysql_query($query_liste_composante , $pdar_connexion) or die(mysql_error());
$row_liste_composante  = mysql_fetch_assoc($liste_composante );
$totalRows_liste_composante  = mysql_num_rows($liste_composante );




mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur_calcul = "SELECT indicateur_ref, id_ref_ind, code_ref_ind, intitule_ref_ind FROM referentiel_indicateur, calcul_indicateur_simple_ref
WHERE FIND_IN_SET( id_ref_ind, indicateur_simple ) and mode_calcul = 'Unique' ORDER BY indicateur_ref";
$liste_indicateur_calcul = mysql_query($query_liste_indicateur_calcul, $pdar_connexion) or die(mysql_error());
$row_liste_indicateur_calcul = mysql_fetch_assoc($liste_indicateur_calcul);
$totalRows_liste_indicateur_calcul = mysql_num_rows($liste_indicateur_calcul);

$liste_indicateur_simple_array=array();
if($totalRows_liste_indicateur_calcul>0){
do{ $liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]=(isset($liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]))?$liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']].$row_liste_indicateur_calcul['code_ref_ind'].",":$row_liste_indicateur_calcul['code_ref_ind'].",";}while($row_liste_indicateur_calcul = mysql_fetch_assoc($liste_indicateur_calcul)); }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ratio = "SELECT indicateur_ref, numerateur, denominateur, coefficient FROM ratio_indicateur_ref order by indicateur_ref";
$liste_ind_ratio  = mysql_query($query_liste_ind_ratio , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ratio = mysql_fetch_assoc($liste_ind_ratio);
$totalRows_liste_ind_ratio  = mysql_num_rows($liste_ind_ratio);
$liste_num_ratio_array = array();
$liste_deno_ratio_array = array();
do{
 $liste_num_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["numerateur"];
  $liste_deno_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = ($row_liste_ind_ratio["denominateur"]==-1)?$row_liste_ind_ratio["coefficient"]." / 1)":$row_liste_ind_ratio["denominateur"];
}while($row_liste_ind_ratio = mysql_fetch_assoc($liste_ind_ratio));


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_code_ref = "SELECT code_ref_ind, id_ref_ind FROM referentiel_indicateur order by code_ref_ind";
$liste_code_ref  = mysql_query($query_liste_code_ref , $pdar_connexion) or die(mysql_error());
$row_liste_code_ref = mysql_fetch_assoc($liste_code_ref);
$totalRows_liste_code_ref  = mysql_num_rows($liste_code_ref);
$liste_code_ref_array = array();
do{
 $liste_code_ref_array[$row_liste_code_ref["id_ref_ind"]] = $row_liste_code_ref["code_ref_ind"];
}while($row_liste_code_ref = mysql_fetch_assoc($liste_code_ref));


mysql_select_db($database_pdar_connexion, $pdar_connexion); //".$_SESSION["clp_where"]." and
$query_edit_composante = "SELECT * FROM activite_projet WHERE niveau=1 order by code";
$edit_composante = mysql_query($query_edit_composante, $pdar_connexion) or die(mysql_error());
$row_edit_composante = mysql_fetch_assoc($edit_composante);
$totalRows_edit_composante = mysql_num_rows($edit_composante);

mysql_select_db($database_pdar_connexion, $pdar_connexion);  //".$_SESSION["clp_where"]." and
//$query_edit_nom = "SELECT * FROM activite_projet WHERE niveau=1 order by code";
$query_edit_nom = "SELECT id_produit, code_produit, intitule_produit  FROM resultat, produit WHERE resultat.projet='".$_SESSION["clp_projet"]."' and id_resultat=effet order by code_resultat, code_produit";
$edit_nom = mysql_query($query_edit_nom, $pdar_connexion) or die(mysql_error());
$row_edit_nom = mysql_fetch_assoc($edit_nom);
$totalRows_edit_nom = mysql_num_rows($edit_nom);
$code_produit_array = array(); //$composante_code_array = array();
if($totalRows_edit_nom>0){
do{
$code_produit_array[$row_edit_nom["id_produit"]]=$row_edit_nom["code_produit"];
//$composante_code_array[$row_edit_nom["code"]]=$row_edit_nom["code"];
}while($row_edit_nom = mysql_fetch_assoc($edit_nom));  }

if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_activite = "DELETE FROM referentiel_indicateur WHERE id_ref_ind='$id'";
$Result1 = mysql_query($query_sup_activite, $pdar_connexion) or die(mysql_error());
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok&composante=$composante"; else $insertGoTo .= "?del=no&composante=$composante";
  $insertGoTo .= (isset($_GET['prefecture']))?"&prefecture=".$_GET['prefecture']:"";
  $insertGoTo .= (isset($_POST['sc']))?"&sc=".$_POST['sc']:"";
  header(sprintf("Location: %s", $insertGoTo));
}


//Cible indicateur à sommer

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cible_indicateur = "SELECT indicateur_produit, annee, sum(valeur_cible) as valeur_cible, avg(valeur_cible) as valeur_ciblem  FROM   ".$database_connect_prefix."cible_cmr_produit group by annee, indicateur_produit";
$cible_indicateur  = mysql_query_ruche($query_cible_indicateur , $pdar_connexion) or die(mysql_error());
$row_cible_indicateur = mysql_fetch_assoc($cible_indicateur );
$totalRows_cible_indicateur = mysql_num_rows($cible_indicateur );
$cible_array = array();
$ciblem_array = array();
if($totalRows_cible_indicateur>0){ 
 do{
  $cible_array[$row_cible_indicateur["indicateur_produit"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_cible"];
  $ciblem_array[$row_cible_indicateur["indicateur_produit"]][$row_cible_indicateur["annee"]]=$row_cible_indicateur["valeur_ciblem"];
   } while($row_cible_indicateur  = mysql_fetch_assoc($cible_indicateur));}
   
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
<div class="widget-header"> <h4><i class="icon-reorder"></i> Appendice 4 (FIDA) </h4>
    <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2){?>
	<a onclick="get_content('edit_indicateur_app4.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="Ajout d'indicateur d'appendice 4" class="btn btn-sm btn-warning pull-right p11" dir=""><i class="icon-plus"> Nouvel indicateur </i></a>
<!--<a href="og_cmr.php" title="Editer les impacts" class="pull-right p11"> Impacts </a>
<a href="odp_cmr.php" title="Editer les objectifs de d&eacute;veloppement" class="pull-right p11"> Objectif de D&eacute;veloppement </a>
<a href="effet_cmr.php" title="Editer les effets" class="pull-right p11"> Effets </a>
<a href="produit_cmr.php" title="Editer les produits" class="pull-right p11"><i class="icon-plus"> Produits </i></a>-->

<?php include_once 'modal_add.php'; ?>
    <?php } ?>
</div>

<div class="widget-content">
<table class="table table-striped table-bordered table-hover table-responsive table-checkable table-tabletools table-colvis datatable dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">composante</th>-->
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Code</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Indicateur </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Unit&eacute; </th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Mode de calcul</th>
 <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>

 <th class="" role="" tabindex="0" aria-controls="" aria-label="" <?php if($i==date("Y")) { ?>style="background-color:#FFCC33"   <?php } ?>><strong>

				      <?php

					  

						 echo $i; ?>

                  </strong>&nbsp;</th>

                      
                       <?php } ?> <th class="" role="" tabindex="0" aria-controls="" aria-label="" width="90">&nbsp;</th>
				      <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
<th class="" role="" tabindex="0" aria-controls="" aria-label="" width="90">Actions</th>
<?php } ?>
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php if($totalRows_liste_composante>0) { $i=0; do { if(isset($row_liste_composante['id_indicateur']) && !empty($row_liste_composante['id_indicateur']))$id = $row_liste_composante['id_indicateur']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php if(isset($row_liste_composante['code'])) echo $row_liste_composante['code']; ?></td>
<td class=" " <?php if(!isset($unite_ind_ref_array[$row_liste_composante["referentiel"]])) {?>style="color:#FF0000"<?php } ?>><?php echo (!empty($row_liste_composante['intitule_indicateur']))?$row_liste_composante['intitule_indicateur']:""; ?></td>
<td class=" "><span class="Style22">
  <?php if(isset($unite_ind_ref_array[$row_liste_composante["referentiel"]])) $unite = $unite_ind_ref_array[$row_liste_composante["referentiel"]]; else  $unite=""; echo $unite; ?>
</span></td>
<td class=" " align="center"><span class="Style22">
  <?php if(isset($mode_calcul_ind_ref_array[$row_liste_composante["referentiel"]])) echo $mode_calcul_ind_ref_array[$row_liste_composante["referentiel"]];  ?>
</span></td>
 
 <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>

                     <td nowrap="nowrap" class=" "><strong>

				      <?php

				if(isset($ugl) && isset($vcible_an_array[$row_liste_composante["id_indicateur"]][$i][$ugl]))

				

				 echo number_format($vcible_an_array[$row_liste_composante["id_indicateur"]][$i][$ugl], 0, ',', ' ');

				 

				elseif(!isset($ugl) && isset($cible_array[$row_liste_composante["id_indicateur"]][$i]))

				 echo number_format($cible_array[$row_liste_composante["id_indicateur"]][$i], 0, ',', ' ');

				// elseif(!isset($ugl) && isset($ciblem_array[$row_liste_composante["id_indicateur"]][$i]) && $row_liste_composante['unite']=="%")

				// echo number_format($ciblem_array[$row_liste_composante["id_indicateur"]][$i], 0, ',', ' ');

				  ?>

                  </strong>&nbsp;</td>
    <?php } ?>
                       <td class=" " align="center"><a onclick="get_content('./edit_cible_indicateur.php','<?php echo "id_ind=".$row_liste_composante['id_indicateur']."&code_act="; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add" data-backdrop="static" data-keyboard="false" title="'<?php echo str_replace("'","\'",$row_liste_composante['intitule_indicateur']);?>'" class="thickbox" dir="">D&eacute;tails</a></td>
                   
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) { ?>
<td class=" " align="center">
<?php
echo do_link("","","Modifier Indicateur ".$row_liste_composante['intitule_indicateur'],"","edit","./","","get_content('edit_indicateur_app4.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=$id","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cet indicateur ?');",0,"margin:0px 5px;",$nfile);
?></td>
</tr>
<?php } ?>
<?php }while($row_liste_composante  = mysql_fetch_assoc($liste_composante)); } ?>
</tbody></table>

    </div>
</div>

<!-- Fin Site contenu ici -->

           

        </div>



        </div>

    </div>

    <?php include_once ("includes/footer.php");?>

</div>
</div>
</body>

</html>