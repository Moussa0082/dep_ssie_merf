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
if(isset($_GET['niveau']) && $_GET['niveau']!="") {$_SESSION["niveau"]=$_GET['niveau']; $niveau=$_SESSION["niveau"];} else { unset($_SESSION["niveau"],$niveau); }
$where = (!isset($niveau) || $niveau==0)?" niveau =1":" niveau = ".$niveau." ";
if(isset($_GET['cmp']) && $_GET['cmp']!="") $wh = " and code=".GetSQLValueString($_GET['cmp'], "text"); else $wh = "";

$editFormAction = $_SERVER['PHP_SELF'];
$currentPage = $_SERVER['PHP_SELF']."?niveau=".((isset($niveau)?$niveau:""));
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//import
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form0"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert"))
  {
    $poids_max=2048576; //Poids maximal du fichier en octets
    $extensions_autorisees=array('xls','xlsx'); //Extensions autorisées ,'csv'
    $url_site='./attachment/'; //Adresse où se trouve le fichier upload.
    $page = $_SERVER['PHP_SELF'];
    $ext = substr(strrchr($_FILES['fichier']['name'], "."), 1);

    if(in_array($ext,$extensions_autorisees))
    {
      if($_FILES['fichier']['size']>$poids_max)
      {
        $message='Un ou plusieurs fichiers sont trop lourds !';
        echo $message;
      }
      elseif(isset($_FILES['fichier']) && $_FILES['fichier']['error'] == 0)
      {
        $inputFileName=$url_site.$_FILES['fichier']['name'];
        move_uploaded_file($_FILES['fichier']['tmp_name'],$inputFileName);

        require_once('Classes/PHPExcel.php');
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
        } catch (Exception $e) {
            die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
            . '": ' . $e->getMessage());
        }
        if(!isset($_GET["niveau"])) $niveau1 = -1; else $niveau1 = intval($_GET["niveau"]);

        mysql_select_db($database_pdar_connexion, $pdar_connexion);
        $query_sup_import_annee = sprintf("DELETE from ".$database_connect_prefix."plan_budget_projet WHERE ".(($niveau1==-1)?"":" niveau=".GetSQLValueString(intval($_GET["niveau"])+1, "int")." and ")." projet=%s",
                             GetSQLValueString($_SESSION['clp_projet'], "text"));
        $Result1 = mysql_query_ruche($query_sup_import_annee, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
        if($niveau1==-1)
        {
          mysql_select_db($database_pdar_connexion, $pdar_connexion);
          $query_entete = "SELECT * FROM ".$database_connect_prefix."plan_budget_config WHERE projet='".$_SESSION["clp_projet"]."' LIMIT 1";
          $entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
          $row_entete  = mysql_fetch_assoc($entete);
          $totalRows_entete  = mysql_num_rows($entete);
          $codes = array();
          if($totalRows_entete>0) $codes=explode(",",$row_entete["code_number"]);
        }

        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        for ($row = 5; $row <= $highestRow; $row++)
        {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
            NULL, TRUE, FALSE);
            if(!empty($rowData[0][0]) && $rowData[0][0]!='Code')
            {
              $code = trim($rowData[0][0]); $n = 0;
              if($niveau1==-1){
                for($i=0;$i<count($codes);$i++)
                { if(isset($codes[$i]) && strlen($code)==$codes[$i]) $n = $i+1; } }
                if(isset($_GET["niveau"])) $n = intval($_GET["niveau"])+1;
                if($n>1){
                //determination du parent
                $where = "niveau = ".($n-1)." and code=LEFT('$code',".(isset($codes[$n-2])?$codes[$n-2]:1).")";
                mysql_select_db($database_pdar_connexion, $pdar_connexion);
                $query_liste_activite_1 = "SELECT code FROM ".$database_connect_prefix."plan_budget_projet WHERE $where and projet='".$_SESSION["clp_projet"]."' ";
                $liste_activite_1  = mysql_query_ruche($query_liste_activite_1 , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
                $row_liste_activite_1  = mysql_fetch_assoc($liste_activite_1 );
                //$totalRows_liste_activite_1  = mysql_num_rows($liste_activite_1 );
                $p = $row_liste_activite_1['code'];
                } else $p = 0;

              $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."plan_budget_projet (intitule,niveau,code,parent,structure,projet, id_personnel) VALUES (%s, %s, %s, %s, %s, %s, '$personnel')",
                                   GetSQLValueString(trim($rowData[0][1]), "text"),
                                   GetSQLValueString($n, "int"),
            					   GetSQLValueString($code, "text"),
                                   GetSQLValueString($p, "text"),
                                   GetSQLValueString($_SESSION['clp_structure'], "text"),
                                   GetSQLValueString($_SESSION['clp_projet'], "text"));
              $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
              //echo $n." - ".$code." - <font size='1'>".$insertSQL."</font><br />";
            }
          }
          unlink($inputFileName);
          if($Result1) $insertGoTo = $page."?import=ok";
          else $insertGoTo = $page."?import=no";
          $insertGoTo .= (isset($_GET["niveau"]))?"&niveau=".intval($_GET["niveau"]):"";
          header(sprintf("Location: %s", $insertGoTo)); exit();
        }
    }
    else
    {
      $insertGoTo = $page."?import=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }
  }
}

//Suppression multiple
if ((isset($_POST["id_val"]) && !empty($_POST["id_val"]))) {
      $id = "";
      foreach($_POST["id_val"] as $a) $id .= GetSQLValueString($a, "int").",";
      $id = substr($id, 0, strlen($id)-1);
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."plan_budget_projet WHERE projet='".$_SESSION["clp_projet"]."' and id in ($id)");
              //echo($insertSQL);
      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
      $insertGoTo = $_SERVER['PHP_SELF'].((isset($niveau)?"?niveau=".$niveau:"?"));
      if ($Result1) $insertGoTo .= "&del=ok"; else $insertGoTo .= "&del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_GET["id_sup"]) && !empty($_GET["id_sup"]))) {
      $id = ($_GET["id_sup"]);
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."plan_budget_projet WHERE id=%s",
                           GetSQLValueString($id, "int"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
      $insertGoTo = $_SERVER['PHP_SELF'].((isset($niveau)?"?niveau=".$niveau:"?"));
      if ($Result1) $insertGoTo .= "&del=ok"; else $insertGoTo .= "&del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
   $personnel=$_SESSION['clp_id'];
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $query_liste_actpa = sprintf("SELECT code FROM ".$database_connect_prefix."plan_budget_projet WHERE  projet=%s and code=%s",
    GetSQLValueString($_SESSION['clp_projet'], "text"),
    GetSQLValueString($_POST['code'], "text"));
    $liste_actpa  = mysql_query_ruche($query_liste_actpa , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $row_liste_actpa = mysql_fetch_assoc($liste_actpa);
    $totalRows_liste_actpa = mysql_num_rows($liste_actpa);
    if($totalRows_liste_actpa==0){
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."plan_budget_projet (intitule,niveau,code,parent,structure,projet, id_personnel) VALUES (%s, %s, %s, %s, %s, %s, '$personnel')",
                         GetSQLValueString($_POST['intitule'], "text"),
                         GetSQLValueString($_POST['niveau'], "int"),
  					     GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['parent'], "text"),
                         GetSQLValueString($_SESSION['clp_structure'], "text"),
                         GetSQLValueString($_SESSION['clp_projet'], "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    }
    else $Result1 = false;
    $insertGoTo = $_SERVER['PHP_SELF'].((isset($niveau)?"?niveau=".$niveau:"?"));
    if ($Result1) $insertGoTo .= "&insert=ok&add_new=1"; else $insertGoTo .= "&insert=no";
    $insertGoTo .= (isset($_POST['sc']))?"&sc=".$_POST['sc']:"";
    header(sprintf("Location: %s", $insertGoTo));
  }

  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
      $id = ($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."plan_budget_projet WHERE id=%s",
                           GetSQLValueString($id, "text"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
      $insertGoTo = $_SERVER['PHP_SELF'].((isset($niveau)?"?niveau=".$niveau:"?"));
      if ($Result1) $insertGoTo .= "&del=ok"; else $insertGoTo .= "&del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }

  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id'];
    /*mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $query_liste_actpa = sprintf("SELECT code FROM ".$database_connect_prefix."plan_budget_projet WHERE structure=%s and projet=%s and code=%s",
    GetSQLValueString($_SESSION['clp_structure'], "text"),
    GetSQLValueString($_SESSION['clp_projet'], "text"),
    GetSQLValueString($_POST['code'], "text"));
    $liste_actpa  = mysql_query_ruche($query_liste_actpa , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $row_liste_actpa = mysql_fetch_assoc($liste_actpa);
    $totalRows_liste_actpa = mysql_num_rows($liste_actpa);
    if($totalRows_liste_actpa==1 && $row_liste_actpa["code"]==$c){   */
    //structure=%s, GetSQLValueString($_SESSION['clp_structure'], "text"),
  	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."plan_budget_projet SET intitule=%s, niveau=%s, code=%s, parent=%s, projet=%s, modifier_par='$personnel', modifier_le='$date' WHERE structure=%s and projet=%s and id='$c'",
                         GetSQLValueString($_POST['intitule'], "text"),
                         GetSQLValueString($_POST['niveau'], "int"),
  					     GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['parent'], "text"),
                         GetSQLValueString($_SESSION['clp_projet'], "text"),
                         GetSQLValueString($_SESSION['clp_structure'], "text"),
                         GetSQLValueString($_SESSION['clp_projet'], "text"));


    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    /*}
    else $Result1 = false; */

    $insertGoTo = $_SERVER['PHP_SELF'].((isset($niveau)?"?niveau=".$niveau:"?"));
    if ($Result1) $insertGoTo .= "&update=ok"; else $insertGoTo .= "&update=no";
    $insertGoTo .= (isset($_POST['sc']))?"&sc=".$_POST['sc']:"";
    header(sprintf("Location: %s", $insertGoTo));
  }
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  $form= $_POST['form']; $noms= $form['lib']; $codes= $form['code']; $nombre=count($noms);

    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."plan_budget_config (nombre, libelle, code_number, structure, projet) VALUES (%s, %s, %s, %s, %s)",
                        GetSQLValueString($nombre, "int"),
  					    GetSQLValueString(implode(',',$noms), "text"),
                        GetSQLValueString(implode(',',$codes), "text"),
                        GetSQLValueString($_SESSION['clp_structure'], "text"),
                        GetSQLValueString($_SESSION['clp_projet'], "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = $_SERVER['PHP_SELF'].((isset($niveau)?"?niveau=".$niveau:"?"));
    if ($Result1) $insertGoTo .= "&insert=ok"; else $insertGoTo .= "&insert=no";
    header(sprintf("Location: %s", $insertGoTo));
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  $form= $_POST['form']; $noms= $form['lib']; $codes= $form['code']; $nombre=count($noms);

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $query_liste_actpa = sprintf("SELECT nombre FROM ".$database_connect_prefix."plan_budget_config WHERE structure=%s and projet=%s",
    GetSQLValueString($_SESSION['clp_structure'], "text"),
    GetSQLValueString($_SESSION['clp_projet'], "text"));
    $liste_actpa  = mysql_query_ruche($query_liste_actpa , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $row_liste_actpa = mysql_fetch_assoc($liste_actpa);
    $totalRows_liste_actpa = mysql_num_rows($liste_actpa);
    if($totalRows_liste_actpa>0 && $row_liste_actpa["nombre"]==$nombre+1){
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."plan_budget_projet WHERE niveau=%s  and projet=%s",
                           GetSQLValueString($nombre+1, "int"),
                           GetSQLValueString($_SESSION['clp_projet'], "text"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result0 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    }

  	$insertSQL = sprintf("UPDATE ".$database_connect_prefix."plan_budget_config SET nombre=%s, libelle=%s, code_number=%s WHERE structure=%s and projet=%s",
  					   GetSQLValueString($nombre, "int"),
  					   GetSQLValueString(implode(',',$noms), "text"),
                       GetSQLValueString(implode(',',$codes), "text"),
                       GetSQLValueString($_SESSION['clp_structure'], "text"),
                       GetSQLValueString($_SESSION['clp_projet'], "text"));
                             //echo $insertSQL; exit();
    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query_ruche($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = $_SERVER['PHP_SELF'].((isset($niveau)?"?niveau=".$niveau:"?"));
    if ($Result1) $insertGoTo .= "&update=ok"; else $insertGoTo .= "&update=no";
    header(sprintf("Location: %s", $insertGoTo));
  }

}

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_activite = "SELECT * FROM ".$database_connect_prefix."plan_budget_projet WHERE niveau =1 and projet='".$_SESSION["clp_projet"]."' ORDER BY niveau,code ASC";
  $liste_activite  = mysql_query_ruche($query_liste_activite , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_activite  = mysql_fetch_assoc($liste_activite );
  $totalRows_liste_activite  = mysql_num_rows($liste_activite );

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_entete = "SELECT * FROM ".$database_connect_prefix."plan_budget_config WHERE projet='".$_SESSION["clp_projet"]."' LIMIT 1";
  $entete  = mysql_query_ruche($query_entete , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_entete  = mysql_fetch_assoc($entete);
  $totalRows_entete  = mysql_num_rows($entete);
  $libelle = array();
  if($totalRows_entete>0){ $libelle=explode(",",$row_entete["libelle"]);}

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
<script>
	$(document).ready(function() {
	  <?php if(isset($_GET["add_new"]) && $_GET["add_new"]==1){ ?>
	  $("#new_plan").click();
      <?php } ?>
    });
</script>
<div class="widget box box_projet">
<div class="widget-header1"> <center><h4><?php if(!empty($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>
<div class="widget-header"> <h4><i class="icon-reorder"></i> Plan Analytique </h4>
<?php //if(!isset($niveau)){
  if(isset($libelle[0]) && !empty($libelle[0])){ $i=count($libelle)-1; $libelle = array_reverse($libelle); foreach($libelle as $lib){
  echo do_link("",$_SERVER['PHP_SELF']."?niveau=".$i,"$lib","<i> $lib </i>","","./","pull-right p11","",0,"",$nfile);
  $i--; } echo '<div class="clear h0"></div>'; }
$libelle = array_reverse($libelle);
if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2){
if(isset($niveau)){ $lib = $libelle[$niveau]; if($niveau<count($libelle)) {
  echo do_link("","","Importation d&eacute;puis un format excel $lib","<i class=\"icon-plus\"> Importer $lib </i>","","./","pull-right p11","get_content('import.php','id=plan_budget&niveau=".($niveau+1)."','modal-body_add',this.title);",1,"",$nfile);
  echo do_link("new_plan","","Ajout $lib","<i class=\"icon-plus\"> Ajouter $lib </i>","","./","pull-right p11","get_content('new_plan_budget_projet.php','niveau=".($niveau+1)."','modal-body_add',this.title);",1,"",$nfile);
  //echo do_link("","plan_analytique_projet.php","Retour","Retour","","./","pull-right p11","",0,"",$nfile);
  }
}
  else
  echo do_link("","","Importation d&eacute;puis un format excel","<i class=\"icon-plus\"> Importer </i>","","./","pull-right p11","get_content('import.php','id=plan_budget&niveau=-1','modal-body_add',this.title);",1,"",$nfile);
if(isset($_SESSION["clp_niveau"]) && $_SESSION["clp_niveau"]==1)
  echo do_link("","","G&eacute;rer les niveaux","<i class=\"icon-plus\"> Gestion des niveaux </i>","","./","pull-right p11","get_content('new_plan_budget_niveau.php','','modal-body_add',this.title);",1,"",$nfile);
    }
?>
</div>

<div class="widget-content" style="display: block;">

<?php if(!isset($niveau)){      

if(isset($libelle[0]) && !empty($libelle[0])){  ?>
              <table border="0" align="center" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive dataTable" style="width:50%;">
                <tr>
                  <td><div align="left" class=""><?php echo $libelle[0]; ?>&nbsp;</div></td>
                  <td valign="middle"><form name="form38" id="form38" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <select name="cmp" onchange="form38.submit();" style="background-color: #FFFF00">
                      <option value="">-- Choisissez <?php echo $libelle[0]; ?> --</option>
                      <?php do { ?>
                  <option value="<?php echo $row_liste_activite["code"]; ?>" <?php if(isset($_GET["cmp"]) && $row_liste_activite["code"]==$_GET["cmp"]) echo "selected='SELECTED'"; ?>><?php echo $row_liste_activite["code"]." : ".$row_liste_activite["intitule"]; ?></option>
                      <?php } while($row_liste_activite  = mysql_fetch_assoc($liste_activite )); ?>
                    </select>
                  </form></td>
                </tr>
              </table>
<?php } $n = count($libelle); ?>

              <table width="99%" border="1" align="left" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive dataTable">
                <tr class="titrecorps2">
                  <!--<td align="center"><div class="Style8"><b>Niveau</b></div></td> -->
<!--                  <td align="center" colspan="<?php echo count($libelle)+1; ?>"><div class="Style8"><b>Code</b></div></td>      -->
                  <td colspan="<?php echo $n+1; ?>"><div align="left"><strong><span class="Style8">Activit&eacute;s </span></strong></div></td>
                 <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
                  <td width="80" align="center"><strong><span class="Style8">Actions</span></strong></td>
				  <?php }?>
                </tr>
                <?php $t=0; $i=0; if($totalRows_liste_activite>0) { ?>


<?php
function trace_tr($niveau,$j,$n,$libelle,$libelle1,$session,$nfile="")
{
  $activitep_array = array(); $id = $libelle1['id']; $code = $libelle1['code'];
  $data = "";
  $data .= "<tr>";
  for($k=0;$k<$j;$k++){ $data .= "<td width='30' align='right'>&nbsp;</td>"; }
  $data .= "<td colspan='".($n-$j+1)."'><b>".$libelle." ".$libelle1["code"]." :</b> ".$libelle1["intitule"]."</td>";
if($session<2) {
$data .= "<td align='center' width='80'>";
$data .= do_link("","","Modifier $libelle","","edit","./","","get_content('new_plan_budget_projet.php','id=$id&niveau=".$libelle1['niveau']."','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);
if(!isset($activitep_array[$libelle1['code']])) {
$data .= do_link("",$_SERVER['PHP_SELF']."?id_sup=$id&niveau=".$libelle1['niveau'],"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer $libelle ?');",0,"margin:0px 5px;",$nfile);
}
$data .= "</td>";
 }
$data .= "</tr>";
  return $data;
}
//$niveau_indent limite = 6;
$niveau_indent = $n;   $k = 0;
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_activite_1 = "SELECT * FROM ".$database_connect_prefix."plan_budget_projet WHERE $where $wh and projet='".$_SESSION["clp_projet"]."' ORDER BY niveau,code ASC";
$liste_activite_1  = mysql_query_ruche($query_liste_activite_1 , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_activite_1  = mysql_fetch_assoc($liste_activite_1 );
$totalRows_liste_activite_1  = mysql_num_rows($liste_activite_1 );
do
{
  $niveau_indent = $n; $k = $j = 0;
  if($niveau_indent-$j>0)
  {
    $code_1 = $row_liste_activite_1["code"]; $id_1 = $row_liste_activite_1["id"];
    //traitement ici
    echo trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_1,$_SESSION['clp_niveau'],$nfile);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_activite_2 = "SELECT * FROM ".$database_connect_prefix."plan_budget_projet WHERE niveau=".($j+2)." and parent='$code_1' and projet='".$_SESSION["clp_projet"]."' ORDER BY niveau,code ASC";
$liste_activite_2  = mysql_query_ruche($query_liste_activite_2 , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_activite_2  = mysql_fetch_assoc($liste_activite_2 );
$totalRows_liste_activite_2  = mysql_num_rows($liste_activite_2 );
    if($totalRows_liste_activite_2>0) { do
    {
      $j=1; $k=1;
      if($niveau_indent-$j>0)
      {
        $code_2 = $row_liste_activite_2["code"]; $id_2 = $row_liste_activite_2["id"];
        //traitement ici
        echo trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_2,$_SESSION['clp_niveau'],$nfile);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_activite_3 = "SELECT * FROM ".$database_connect_prefix."plan_budget_projet WHERE niveau=".($j+2)." and parent='$code_2' and projet='".$_SESSION["clp_projet"]."' ORDER BY niveau,code ASC";
$liste_activite_3  = mysql_query_ruche($query_liste_activite_3 , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_activite_3  = mysql_fetch_assoc($liste_activite_3 );
$totalRows_liste_activite_3  = mysql_num_rows($liste_activite_3 );
        if($totalRows_liste_activite_3>0) { do
        {
          if($niveau_indent-$j>0)
          {
            $j=2; $k=2;
            $code_3 = $row_liste_activite_3["code"]; $id_3 = $row_liste_activite_3["id"];
            //traitement ici
            echo trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_3,$_SESSION['clp_niveau'],$nfile);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_activite_4 = "SELECT * FROM ".$database_connect_prefix."plan_budget_projet WHERE niveau=".($j+2)." and parent='$code_3' and projet='".$_SESSION["clp_projet"]."' ORDER BY niveau,code ASC";
$liste_activite_4  = mysql_query_ruche($query_liste_activite_4 , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_activite_4  = mysql_fetch_assoc($liste_activite_4 );
$totalRows_liste_activite_4  = mysql_num_rows($liste_activite_4 );
            if($totalRows_liste_activite_4>0) { do
            {
              if($niveau_indent-$j>0)
              {
                $j=3; $k=3;
                $code_4 = $row_liste_activite_4["code"]; $id_4 = $row_liste_activite_4["id"];
                //traitement ici
                echo trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_4,$_SESSION['clp_niveau'],$nfile);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_activite_5 = "SELECT * FROM ".$database_connect_prefix."plan_budget_projet WHERE niveau=".($j+2)." and parent='$code_4' and projet='".$_SESSION["clp_projet"]."' ORDER BY niveau,code ASC";
$liste_activite_5  = mysql_query_ruche($query_liste_activite_5 , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_activite_5  = mysql_fetch_assoc($liste_activite_5 );
$totalRows_liste_activite_5  = mysql_num_rows($liste_activite_5 );
                if($totalRows_liste_activite_5>0) { do
                {
                  if($niveau_indent-$j>0)
                  {
                    $j=4; $k=4;
                    $code_5 = $row_liste_activite_5["code"]; $id_5 = $row_liste_activite_5["id"];
                    //traitement ici
                    echo trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_5,$_SESSION['clp_niveau'],$nfile);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_activite_6 = "SELECT * FROM ".$database_connect_prefix."plan_budget_projet WHERE niveau=".($j+2)." and parent='$code_5' and projet='".$_SESSION["clp_projet"]."' ORDER BY niveau,code ASC";
$liste_activite_6  = mysql_query_ruche($query_liste_activite_6 , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_activite_6  = mysql_fetch_assoc($liste_activite_6 );
$totalRows_liste_activite_6  = mysql_num_rows($liste_activite_6 );
                    if($totalRows_liste_activite_6>0) { do
                    {
                      //activite limite ici à niveau 6
                      $code_6 = $row_liste_activite_6["code"];
                      $id_6 = $row_liste_activite_6["id"];
                      //traitement ici
                      echo trace_tr($k,$j,$n,$libelle[$k],$row_liste_activite_6,$_SESSION['clp_niveau'],$nfile);

                    } while($row_liste_activite_6  = mysql_fetch_assoc($liste_activite_6 )); }
                  }
                } while($row_liste_activite_5  = mysql_fetch_assoc($liste_activite_5 )); }
              }
            } while($row_liste_activite_4  = mysql_fetch_assoc($liste_activite_4 )); }
          }
        } while($row_liste_activite_3  = mysql_fetch_assoc($liste_activite_3 )); }
      }
    } while($row_liste_activite_2  = mysql_fetch_assoc($liste_activite_2 )); }
  }
} while($row_liste_activite_1  = mysql_fetch_assoc($liste_activite_1 ));

?>
<!--                <tr class="titrecorps2">
                  <td align="center" colspan="<?php echo $n; ?>"><div align="right"><b><span class="Style7 Style11">Total</span></b></div></td>
                  <td  align="center">&nbsp;</td>
                  <td colspan="<?php echo $n; ?>" align="center">&nbsp;</td>
                </tr>-->
                <?php }else{ ?>
                <tr>
                  <td colspan="<?php echo $n+2; ?>"><div align="center" class=""><b>Aucune activit&eacute;</b></div></td>
                </tr>
                <?php } ?>
              </table>
<div class="clear h0">&nbsp;</div>
    </div>
<?php } else { //autre niveau
$where = ($niveau==0)?" niveau =1":" niveau = ".($niveau+1)." ";

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_activite_1 = "SELECT * FROM ".$database_connect_prefix."plan_budget_projet WHERE $where and projet='".$_SESSION["clp_projet"]."' ORDER BY niveau,code ASC";
$liste_activite_1 = mysql_query_ruche($query_liste_activite_1 , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_activite_1 = mysql_fetch_assoc($liste_activite_1);
$totalRows_liste_activite_1 = mysql_num_rows($liste_activite_1);
?>
<form name="form1" action="" method="post">
<table id="example" border="0" align="center" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive table-checkable table-tabletools table-colvis datatable dataTable" >
<?php if(count($libelle)>0 && $niveau<count($libelle)){ ?>
                <thead>
                  <tr>
                    <th class="checkbox-column"> <input type="checkbox" class="uniform"> </th>
                    <?php if($niveau>0) { ?>
                    <td width="120"><?php echo $libelle[$niveau-1]; ?></td>
                    <?php } ?>
                    <td width="120"><strong>Code <?php //echo $libelle[$niveau]; ?></strong></td>
                    <td ><strong><?php echo $libelle[$niveau]; ?></strong></td>
                    <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
                    <td width="80"><strong>Actions</strong></td>
                    <?php } ?>
                  </tr>
                </thead>
                <tbody>
<?php if($totalRows_liste_activite_1>0){ do{ $id = $row_liste_activite_1["id"]; $code = $row_liste_activite_1["code"]; $parent = $row_liste_activite_1["parent"]; ?>
                <tr>
                    <td class="checkbox-column"> <input type="checkbox" name="id_val[]" value="<?php echo $id; ?>" class="uniform"> </td>
                    <?php /*for($i=$niveau; $i>0; $i--) {
                      if($i==$niveau) $parent1 = $parent;
                      elseif($i==$niveau-1) $parent1 = $liste_loc_array[$i+1][$parent][0];
                      else $parent1 = $liste_loc_array[$i+1][$parent1][0];
                      $val[$i] = $parent1; ?>
                    <?php }*/ ?>
                    <?php if($niveau>0) { ?>
                    <td><?php echo $row_liste_activite_1["parent"]; ?></td>
                    <?php } ?>
                    <td><strong><?php echo $row_liste_activite_1["code"]; ?></strong></td>
                    <td><strong><?php echo $row_liste_activite_1["intitule"]; ?></strong></td>
                    <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<2) {?>
                    <td class=" " align="center">
                    <?php
if(isset($_SESSION["clp_niveau"]) && $_SESSION["clp_niveau"]==1){
                    echo do_link("","","Modifier ".$libelle[$niveau],"","edit","./","","get_content('new_plan_budget_projet.php','id=$id&niveau=".($niveau+1)."','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);
                    echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id."&niveau=$niveau","Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ".$libelle[$niveau]."');",0,"margin:0px 5px;",$nfile);
}
                    ?>
                    </td>
                    <?php } ?>
                </tr>
<?php }while($row_liste_activite_1  = mysql_fetch_assoc($liste_activite_1)); } ?>
                </tbody>
<?php } else { ?>
                <tr>
                  <td><div align="center" class=""><h2>Aucune localit&eacute;/Site</h2></div></td>
                </tr>
                <?php } ?>
            </table>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<div class="row"> <div class="table-footer"> <div class="col-md-6"> <div class="table-actions"> <label>Pour la s&eacute;lection :</label> <select onchange="if(confirm('Vous confirmez la suppression multiple ?')) form1.submit();" class="select2" data-minimum-results-for-search="-1" data-placeholder="S&eacute;lection..."> <option value=""></option> <option value="Delete">Supprimer</option>  </select> </div> </div></div> </div>
<?php } ?>
</form>
<?php } ?>

<!-- Fin Site contenu ici -->

            </div>

        </div>

 </div>

        </div>

    </div>  <?php include_once 'modal_add.php'; ?>

    <?php include_once ("includes/footer.php");?>

</div>

</body>

</html>