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

$personnel = $_SESSION["clp_id"];
$date = date("Y-m-d");
/*
if ((isset($_GET["id_sup"]) && intval($_GET["id_sup"])>0)) {
  $id = intval($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."faitiere WHERE id_faitiere=%s",
                       GetSQLValueString($id, "int"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_GET["id_supet"]) && intval($_GET["id_supet"])>0)) {
  $id = intval($_GET["id_supet"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."etape_demande_mp WHERE id_etape=%s",
                       GetSQLValueString($id, "int"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}*/

if ((isset($_GET["id_sup_speculation"]) && !empty($_GET["id_sup_speculation"]))) {
  $id = ($_GET["id_sup_speculation"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."speculation WHERE id_speculation=%s",
                       GetSQLValueString($id, "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}


if ((isset($_GET["id_sup_if"]) && !empty($_GET["id_sup_if"]))) {
  $id = ($_GET["id_sup_if"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."imf WHERE id_imf=%s",
                       GetSQLValueString($id, "int"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_GET["id_sup_maillon"]) && !empty($_GET["id_sup_maillon"]))) {
  $id = ($_GET["id_sup_maillon"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."maillon WHERE id_maillon=%s",
                       GetSQLValueString($id, "int"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    
   //taches ugls mois
  $insertSQL1 = "DELETE FROM speculation_maillon WHERE maillon='$id'";
  try{
        $Result1 = $pdar_connexion->prepare($insertSQL1);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
  
    //taches ugls mois
  $insertSQL1 = "DELETE FROM speculation_maillon WHERE maillon='$id'";
  try{
        $Result2 = $pdar_connexion->prepare($insertSQL1);
        $Result2->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  }
/*
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{ //Fonction
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."faitiere (sigle, libelle, id_personnel) VALUES (%s, %s, '$personnel')",
                         GetSQLValueString($_POST['sigle'], "text"),
                         GetSQLValueString($_POST['libelle'], "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."faitiere WHERE id_faitiere=%s",
                         GetSQLValueString($id, "int"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
      $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."faitiere SET sigle=%s, libelle=%s WHERE id_faitiere=%s",
                         GetSQLValueString($_POST['sigle'], "text"),
                         GetSQLValueString($_POST['libelle'], "text"),
                         GetSQLValueString($id, "int"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}*/

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{ //rubrique
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."speculation (filiere, libelle, id_personnel) VALUES (%s, %s, '$personnel')",
                         GetSQLValueString($_POST['filiere'], "text"),
                         GetSQLValueString($_POST['libelle'], "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."speculation WHERE id_speculation=%s",
                         GetSQLValueString($id, "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
    $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."speculation SET filiere=%s, libelle=%s WHERE id_speculation=%s",
                         GetSQLValueString($_POST['filiere'], "text"),
                         GetSQLValueString($_POST['libelle'], "text"),
                         GetSQLValueString($id, "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form4"))
{ //cat&eacute;gories de b&eacute;n&eacute;ficiaires
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."imf (sigle, libelle, id_personnel) VALUES (%s, %s, '$personnel')",
                         GetSQLValueString($_POST['sigle'], "text"),
                         GetSQLValueString($_POST['libelle'], "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
      $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."imf WHERE id_imf=%s",
                         GetSQLValueString($id, "int"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
      $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
    $id = ($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."imf SET sigle=%s, libelle=%s  WHERE id_imf=%s",
                         GetSQLValueString($_POST['sigle'], "text"),
                         GetSQLValueString($_POST['libelle'], "text"),
                         GetSQLValueString($id, "int"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}


if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form5"))
{ //cat&eacute;gories de b&eacute;n&eacute;ficiaires
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $cle=date("ymdis").$_SESSION['clp_n'];
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."maillon (id_maillon, libelle, id_personnel) VALUES ($cle, %s,  '$personnel')",
                         GetSQLValueString($_POST['libelle'], "text"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  	
		if(isset($_POST["sepc"]) && !empty($_POST["sepc"])){ foreach($_POST["sepc"] as $key=>$val){ 

  $insertSQL1 = sprintf("INSERT INTO speculation_maillon (maillon, speculation, id_personnel) VALUES (%s, %s, '$personnel')",
					   GetSQLValueString($cle, "text"),
					   GetSQLValueString($val, "text"));

  try{
        $Result2 = $pdar_connexion->prepare($insertSQL1);
        $Result2->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    
    } }
	
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
    $id = ($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."maillon WHERE id_maillon=%s",
                         GetSQLValueString($id, "int"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  	
	 //taches ugls mois
  $insertSQL1 = "DELETE FROM speculation_maillon WHERE maillon='$id'";
  try{
        $Result2 = $pdar_connexion->prepare($insertSQL1);
        $Result2->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
	
	 
  }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  
    $id = ($_POST["MM_update"]);
	
	  //taches ugls mois
     // mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $insertSQL1 = "DELETE FROM speculation_maillon WHERE maillon='$id'";
  try{
        $Result2 = $pdar_connexion->prepare($insertSQL1);
        $Result2->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
    
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."maillon SET libelle=%s  WHERE id_maillon=%s",
                         GetSQLValueString($_POST['libelle'], "text"),
                         GetSQLValueString($id, "int"));

  try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  	
	if(isset($_POST["sepc"]) && !empty($_POST["sepc"])){ foreach($_POST["sepc"] as $key=>$val){ 

  $insertSQL1 = sprintf("INSERT INTO speculation_maillon (maillon, speculation, id_personnel) VALUES (%s, %s, '$personnel')",
					   GetSQLValueString($id, "text"),
					   GetSQLValueString($val, "text"));


  try{
        $Result2 = $pdar_connexion->prepare($insertSQL1);
        $Result2->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  
    } }

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
	
	
  }
}
/*
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2ee"))
{ //Fonction
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."etape_demande_mp (duree, libelle, ordre, id_personnel) VALUES (%s, %s, %s, '$personnel')",
                         GetSQLValueString($_POST['duree'], "int"),
                         GetSQLValueString($_POST['libelle'], "text"),
						 GetSQLValueString($_POST['ordre'], "int"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."etape_demande_mp WHERE id_etape=%s",
                         GetSQLValueString($id, "int"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."etape_demande_mp SET duree=%s, libelle=%s, ordre=%s WHERE id_etape=%s",
                         GetSQLValueString($_POST['duree'], "text"),
                         GetSQLValueString($_POST['libelle'], "text"),
						  GetSQLValueString($_POST['ordre'], "text"),
                         GetSQLValueString($id, "int"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}*/


//Unite indicateur
/*$query_liste_faitiere = "SELECT * FROM ".$database_connect_prefix."faitiere ";
        try{
    $liste_faitiere = $pdar_connexion->prepare($query_liste_faitiere);
    $liste_faitiere->execute();
    $row_liste_faitiere = $liste_faitiere ->fetchAll();
    $totalRows_liste_faitiere = $liste_faitiere->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }*/

//Mission supervision
$query_liste_speculation = "SELECT * FROM ".$database_connect_prefix."speculation ";
        try{
    $liste_speculation = $pdar_connexion->prepare($query_liste_speculation);
    $liste_speculation->execute();
    $row_liste_speculation = $liste_speculation ->fetchAll();
    $totalRows_liste_speculation = $liste_speculation->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//cat&eacute;gories de b&eacute;n&eacute;ficiaires
$query_liste_maillon = "SELECT * FROM ".$database_connect_prefix."maillon ";
        try{
    $liste_maillon = $pdar_connexion->prepare($query_liste_maillon);
    $liste_maillon->execute();
    $row_liste_maillon = $liste_maillon ->fetchAll();
    $totalRows_liste_maillon = $liste_maillon->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//imf
$query_liste_imf = "SELECT * FROM ".$database_connect_prefix."imf ";
        try{
    $liste_imf = $pdar_connexion->prepare($query_liste_imf);
    $liste_imf->execute();
    $row_liste_imf = $liste_imf ->fetchAll();
    $totalRows_liste_imf = $liste_imf->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//equipement de transformation
/*$query_liste_et = "SELECT * FROM ".$database_connect_prefix."etape_demande_mp";
        try{
    $liste_et = $pdar_connexion->prepare($query_liste_et);
    $liste_et->execute();
    $row_liste_et = $liste_et ->fetchAll();
    $totalRows_liste_et = $liste_et->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }*/


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print $config->sitename; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
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
  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->
theme_folder; ?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->
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
<?php include_once 'modal_add.php'; ?>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }

</style>
<div style="padding-top:20px;">
<!--
<div class="col-md-6">
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i>Faiti&egrave;res</h4>
   <div class="toolbar no-padding"><div class="btn-group"><span class="btn btn-xs widget-collapse pull-right"><i class="icon-angle-down"></i></span>
  <?php //if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
//echo do_link("","","Ajout de faitiere","<i class=\"icon-plus\"> Nouvelle faitiere </i>","","./","pull-right p11","get_content('new_faitiere.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php //} ?>
</div></div>
</div>
<div class="widget-content">
<table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" align="center" >
            <thead>
                <tr>
                  <td><div align="left"><strong>Sigle</strong></div></td>
                  <td><div align="left"><strong>Nom</strong></div></td>
                  <?php //if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?> <td align="center" width="80" ><strong>Actions</strong></td> <?php //} ?>
                </tr>
            </thead>
                <?php //if($totalRows_liste_faitiere>0) {$i=0;do { $id = $row_liste_faitiere['id_faitiere']; ?>
                <tr>
                  <td><div align="left"><?php //echo $row_liste_faitiere['sigle']; ?></div></td>
                <td><div align="left"><?php //echo $row_liste_faitiere['libelle']; ?></div></td>
				   <?php //if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center">
<?php
//echo do_link("","","Modifier faitiere","","edit","./","","get_content('new_faitiere.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

//echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette faitière ?');",0,"margin:0px 5px;",$nfile);
?>
                </td>
                   <?php //} ?>
				  </tr>

                <?php //} while ($row_liste_faitiere = mysql_fetch_assoc($liste_faitiere)); ?>
                <?php //} ?>
              </table>

</div></div>
</div>
-->
<div class="col-md-6">
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> Fili&egrave;res</h4>
   <div class="toolbar no-padding"><div class="btn-group"><span class="btn btn-xs widget-collapse pull-right"><i class="icon-angle-down"></i></span>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
echo do_link("","","Ajout de fili&egrave;re","<i class=\"icon-plus\"> Nouvelle fili&egrave;re </i>","","./","pull-right p11","get_content('new_speculation.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div></div>
</div>
<div class="widget-content">
<table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" align="center" id="mtable" >
            <thead>
                <tr>
                  <td><div align="left"><strong>Fili&egrave;re</strong></div></td>
                  <td><div align="left"><strong>Sp&eacute;culation </strong></div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center" width="80" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
                <?php if($totalRows_liste_speculation>0) {$i=0; foreach($row_liste_speculation as $row_liste_speculation){ $id = $row_liste_speculation['id_speculation']; ?>
                <tr>
                  <td><div align="left"><?php echo $row_liste_speculation['filiere']; ?></div></td>
                  <td><div align="left"><?php echo $row_liste_speculation['libelle']; ?></div></td>
				   <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center">
<?php
echo do_link("","","Modifier fili&egrave;re","","edit","./","","get_content('new_speculation.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup_speculation=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette fil&egrave;re ?');",0,"margin:0px 5px;",$nfile);
?>
                </td>
                   <?php } ?>
				  </tr>
                <?php }  ?>
                <?php } ?>
              </table>

</div></div>
</div>

<div class="col-md-6">
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> Activit&eacute;s principales </h4>
   <div class="toolbar no-padding"><div class="btn-group"><span class="btn btn-xs widget-collapse pull-right"><i class="icon-angle-down"></i></span>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
echo do_link("","","Ajout d'activit&eacute;","<i class=\"icon-plus\"> Nouvelle activit&eacute; </i>","","./","pull-right p11","get_content('new_maillon.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div></div>
</div>
<div class="widget-content">
<table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" align="center" >
            <thead>
                <tr>
                  <td><div align="left"><strong>Activit&eacute;s</strong></div></td>
                  <td><div align="left"><strong>Fili&egrave;res concern&eacute;es </strong></div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?> <td align="center" width="80" ><strong>Actions</strong></td> <?php } ?>
                </tr>
            </thead>
                <?php if($totalRows_liste_maillon>0) {$i=0;foreach($row_liste_maillon as $row_liste_maillon){ $id = $row_liste_maillon['id_maillon'];
				
  $query_listespm = "SELECT * FROM ".$database_connect_prefix."speculation_maillon, speculation WHERE id_speculation=speculation and maillon=$id ";
          try{
    $listespm = $pdar_connexion->prepare($query_listespm);
    $listespm->execute();
    $row_listespm = $listespm ->fetchAll();
    $totalRows_listespm = $listespm->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

  $tableausp="";
  if($totalRows_listespm) {
  foreach($row_listespm as $row_listespm){
	$tableausp.=$row_listespm['libelle'].", ";}
	}
	//mysql_free_result($listespm);
				 ?>
                <tr>
                  <td><div align="left"><?php echo $row_liste_maillon['libelle']; ?></div></td>
                <td><div align="left"><?php echo $tableausp; ?></div></td>
				   <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center">
<?php
echo do_link("","","Modifier maillon","","edit","./","","get_content('new_maillon.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup_maillon=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce maillon?');",0,"margin:0px 5px;",$nfile);
?>
                </td>
                   <?php } ?>
				  </tr>

                <?php }  ?>
                <?php } ?>
              </table>

</div></div>
</div>

<div class="col-md-12">
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> R&eacute;seaux SFD </h4>
   <div class="toolbar no-padding"><div class="btn-group"><span class="btn btn-xs widget-collapse pull-right"><i class="icon-angle-down"></i></span>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
echo do_link("","","Ajout de SFD","<i class=\"icon-plus\"> Nouveau SFD </i>","","./","pull-right p11","get_content('new_imf.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div></div>
</div>
<div class="widget-content">
<table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" align="center" id="mtable" >
            <thead>
                <tr>
                  <td>Code</td>
                  <td><div align="left"><strong>Sigle</strong></div></td>
                  <td><div align="left"><strong>Intitul&eacute;</strong></div></td>
                  <td><strong>Localit&eacute;</strong></td>
                  <td><strong>Contact</strong></td>
                  <td><strong>Distance</strong></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center" width="80" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
                <?php if($totalRows_liste_imf>0) {$i=0;foreach($row_liste_imf as $row_liste_imf){ $id = $row_liste_imf['id_imf']; ?>
                <tr>
                  <td><?php echo $row_liste_imf['code_imf']; ?></td>
                  <td><div align="left"><?php echo $row_liste_imf['sigle']; ?></div></td>
                  <td><div align="left"><?php echo $row_liste_imf['libelle']; ?></div></td>
				   <td><?php echo $row_liste_imf['village']; ?></td>
				   <td><?php echo $row_liste_imf['contact']; ?></td>
				   <td><?php echo $row_liste_imf['distance']; ?></td>
				   <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center">
<?php
echo do_link("","","Modifier IF","","edit","./","","get_content('new_imf.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup_if=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette IF ?');",0,"margin:0px 5px;",$nfile);
?>                </td>
                   <?php } ?>
				  </tr>
                <?php }  ?>
                <?php } ?>
              </table>

</div></div>
</div>
<!--
<div class="col-md-6">
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i>Etape du chrnogramme du montage des MP</h4>
   <div class="toolbar no-padding"><div class="btn-group"><span class="btn btn-xs widget-collapse pull-right"><i class="icon-angle-down"></i></span>
  <?php //if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
//echo do_link("","","Ajout d'étape de montage de dossier de MP","<i class=\"icon-plus\"> Nouvelle étape </i>","","./","pull-right p11","get_content('new_etape_mp.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php //} ?>
</div></div>
</div>
<div class="widget-content">
<table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" align="center" >
            <thead>
                <tr>
                  <td><div align="left"><strong>N&deg;</strong></div></td>
                  <td><div align="left"><strong>Etape</strong></div></td>
                  <td><div align="left"><strong>Dur&eacute;e pr&eacute;visionnelle (jours) </strong></div></td>
                  <?php //if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?> <td align="center" width="80" ><strong>Actions</strong></td> <?php //} ?>
                </tr>
            </thead>
                <?php //if($totalRows_liste_et>0) {$i=0;foreach($row_liste_et as $row_liste_et){ $id = $row_liste_et['id_etape']; ?>
                <tr>
                  <td><?php //echo $row_liste_et['ordre']; ?></td>
                  <td><div align="left"><?php //echo $row_liste_et['libelle']; ?></div></td>
                <td><div align="left"><?php //echo $row_liste_et['duree']; ?></div></td>
				   <?php //if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center">
<?php
//echo do_link("","","Modifier étape de montage MP","","edit","./","","get_content('new_etape_mp.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

//echo do_link("",$_SERVER['PHP_SELF']."?id_supet=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette étape ?');",0,"margin:0px 5px;",$nfile);
?>                </td>
                   <?php //} ?>
				  </tr>

                <?php //}  ?>
                <?php //} ?>
              </table>

</div></div>
</div>
-->
</div>

<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>