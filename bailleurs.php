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
$editFormAction = $_SERVER['PHP_SELF'];
$currentPage = $_SERVER['PHP_SELF'];
/*if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/
$personnel=$_SESSION['clp_id'];
$page = $_SERVER['PHP_SELF'];
if(isset($_GET["id_sup"])) { $id=$_GET["id_sup"];
$query_sup_tache = "DELETE FROM ".$database_connect_prefix."partenaire WHERE id_partenaire='$id'";
try{
    $Result = $pdar_connexion->prepare($query_sup_tache);
    $Result->execute();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($Result) $lien =$page."?del=ok"; else $lien=$page."?del=no";
header(sprintf("Location: %s", $lien));
}
//convention
if(isset($_GET["id_sup_tp"])) { $id=$_GET["id_sup_tp"];
$query_sup_part = "DELETE FROM ".$database_connect_prefix."type_part WHERE id_part='$id'";
try{
    $Result1 = $pdar_connexion->prepare($query_sup_part);
    $Result1->execute();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$currentPage;
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}
//import
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form0"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert"))
  {
    $personnel=$_SESSION['clp_id'];
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
        $query_sup_import_annee = "DELETE FROM ".$database_connect_prefix."partenaire ";
        // WHERE structure='".$_SESSION["clp_structure"]."'";
        try{
            $Result1 = $pdar_connexion->prepare($query_sup_import_annee);
            $Result1->execute();
        }catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }
        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        for ($row = 5; $row <= $highestRow; $row++)
        {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
            NULL, TRUE, FALSE);
            if(!empty($rowData[0][2]) && $rowData[0][2]!='Code')
            {
              $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."partenaire (code, sigle, definition, description, id_personnel) VALUES (%s, %s, %s, %s, '$personnel')",
              			   GetSQLValueString(trim($rowData[0][2]), "text"),
              			   GetSQLValueString(trim($rowData[0][2]), "text"),
              			   GetSQLValueString(trim($rowData[0][5]), "text"),
              			   GetSQLValueString(trim($rowData[0][5]), "text"));
              try{
                    $Result1 = $pdar_connexion->prepare($insertSQL);
                    $Result1->execute();
              }catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }
            }
          }
          unlink($inputFileName);
          if($Result1) $insertGoTo = $page."?import=ok";
          else $insertGoTo = $page."?import=no";
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
//import convention
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form00"))
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
        $query_sup_import_annee = "DELETE FROM ".$database_connect_prefix."type_part ";
        // WHERE structure='".$_SESSION["clp_structure"]."'";
        try{
            $Result1 = $pdar_connexion->prepare($query_sup_import_annee);
            $Result1->execute();
        }catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }
        //  Get worksheet dimensions
        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        for ($row = 5; $row <= $highestRow; $row++)
        {
            //  Read a row of data into an array
            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
            NULL, TRUE, FALSE);
            if(!empty($rowData[0][2]) && $rowData[0][2]!='Code')
            {
              $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."type_part (code_type, bailleur, intitule, montant, projet, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, '$personnel', '$date')", //%s, , date_accord
                              GetSQLValueString(trim($rowData[0][2]), "text"),
                              GetSQLValueString(trim($rowData[0][9]), "text"),
        					  GetSQLValueString(trim($rowData[0][4]), "text"),
        					  (is_null(GetSQLValueString(trim($rowData[0][14]), "double"))?0:GetSQLValueString(trim($rowData[0][14]), "double")),
                              GetSQLValueString($_SESSION["clp_projet"], "text"));
              try{
                    $Result1 = $pdar_connexion->prepare($insertSQL);
                    $Result1->execute();
              }catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }    ;
              //Auto ajustement
              $query_liste_bailleur = sprintf("UPDATE ".$database_connect_prefix."type_part SET bailleur=(SELECT code FROM ".$database_connect_prefix."partenaire WHERE definition=%s) WHERE id_part=%s ",GetSQLValueString(trim($rowData[0][9]), "text"),GetSQLValueString(trim($rowData[0][2]), "int"));
              try{
                    $liste_bailleur = $pdar_connexion->prepare($query_liste_bailleur);
                    $liste_bailleur->execute();
              }catch(Exception $e){ /*die(mysql_error_show_message($e));*/ }
            }
          }
          unlink($inputFileName);
          if($Result1) $insertGoTo = $page."?import=ok";
          else $insertGoTo = $page."?import=no";
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
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
  $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."partenaire (code, sigle, definition, description, adresse_mail, dno, id_personnel) VALUES (%s, %s, %s, %s, %s, %s, '$personnel')",
  			   GetSQLValueString($_POST['code'], "text"),
  			   GetSQLValueString($_POST['sigle'], "text"),
  			   GetSQLValueString($_POST['definition'], "text"),
  			   GetSQLValueString($_POST['description'], "text"),
               GetSQLValueString($_POST['adresse_mail'], "text"),
               GetSQLValueString($_POST['dno'], "int"));
  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo));   exit(0);
  }
  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
      $id = ($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."partenaire WHERE id_partenaire=%s",
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
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id'];
  $insertSQL = sprintf("UPDATE ".$database_connect_prefix."partenaire SET code=%s, sigle=%s, definition=%s, description=%s, adresse_mail=%s, dno=%s WHERE id_partenaire=%s",
  			   GetSQLValueString($_POST['code'], "text"),
  			   GetSQLValueString($_POST['sigle'], "text"),
  			   GetSQLValueString($_POST['definition'], "text"),
  			   GetSQLValueString($_POST['description'], "text"),
               GetSQLValueString($_POST['adresse_mail'], "text"),
               GetSQLValueString($_POST['dno'], "int"),
               GetSQLValueString($c, "text"));
  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }
  $insertGoTo = $page;
  if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
  header(sprintf("Location: %s", $insertGoTo)); exit(0);
  }
}
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{
  if ((isset($_POST["MM_update"]))) {
    $id=$_POST['id'];
    include "includes/class.upload.php";
    $handle = new upload($_FILES['photo']);
    if ($handle->uploaded && !empty($id))
    {
      //resize to 250 px
      $handle->file_new_name_body = 'img_'.$id;
      $handle->image_resize = true;
      $handle->image_x = 250;
      $handle->image_y = 250;
      $handle->file_auto_rename = true;
      $handle->image_ratio = true;
      $handle->image_convert = 'jpg';
      $handle->file_overwrite = true;
      $handle->process('./images/bailleur/');   /*
      if ($handle->processed)
      {
        $img_full_name = $handle->file_dst_name_body.".".$handle->file_dst_name_ext;
      }  */
      //terminé
      $handle->clean();
    }
    if($handle->processed) $insertGoTo = $page."?insert=ok";
    else $insertGoTo = $page."?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}
//convention
if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."type_part (code_type, bailleur, intitule, montant, date_accord, projet, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, '$personnel', '$date')",
                        GetSQLValueString($_POST['code_type'], "text"),
                        GetSQLValueString($_POST['bailleur'], "text"),
  					    GetSQLValueString($_POST['intitule'], "text"),
  					    GetSQLValueString($_POST['montant'], "double"),
                        GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_accord']))), "date"),
  					    //GetSQLValueString($_POST['observation'], "text"),
                       // GetSQLValueString($_SESSION['clp_structure'], "text"),
                       GetSQLValueString($_SESSION['clp_projet'], "text"));
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $currentPage;
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
  if ((isset($_POST["MM_delete"]) && !empty($_POST["MM_delete"]))) {
      $id = ($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from ".$database_connect_prefix."type_part WHERE id_part=%s",
                           GetSQLValueString($id, "int"));
      try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
      }catch(Exception $e){ die(mysql_error_show_message($e)); }
      $insertGoTo = $currentPage;
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }
  if ((isset($_POST["MM_update"]) && !empty($_POST["MM_update"]))) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id'];
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."type_part SET code_type=%s, bailleur=%s, intitule=%s, montant=%s, date_accord=%s, observation=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_part=%s",
                         GetSQLValueString($_POST['code_type'], "text"),
                         GetSQLValueString($_POST['bailleur'], "text"),
  					   GetSQLValueString($_POST['intitule'], "text"),
  					   GetSQLValueString($_POST['montant'], "double"),
                         GetSQLValueString(implode('-',array_reverse(explode('/',$_POST['date_accord']))), "date"),
  					   GetSQLValueString($_POST['observation'], "text"),
                       GetSQLValueString($c, "int"));
    try{
        $Result1 = $pdar_connexion->prepare($insertSQL);
        $Result1->execute();
    }catch(Exception $e){ die(mysql_error_show_message($e)); }
    $insertGoTo = $currentPage;
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }
}
//Bailleurs
$query_liste_bailleur = "SELECT * FROM ".$database_connect_prefix."partenaire where code in (select bailleur from ".$database_connect_prefix."type_part WHERE projet='".$_SESSION["clp_projet"]."')";
try{
    $liste_bailleur = $pdar_connexion->prepare($query_liste_bailleur);
    $liste_bailleur->execute();
    $row_liste_bailleur = $liste_bailleur ->fetchAll();
    $totalRows_liste_bailleur = $liste_bailleur->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$bailleur=$cbailleur=array();
$query_liste_part = "SELECT id_partenaire, count(bailleur) as nbbail, sum(montant) as partb FROM ".$database_connect_prefix."partenaire, ".$database_connect_prefix."type_part WHERE code=bailleur and projet='".$_SESSION["clp_projet"]."' GROUP BY id_partenaire";
try{
    $liste_part = $pdar_connexion->prepare($query_liste_part);
    $liste_part->execute();
    $row_liste_part = $liste_part ->fetchAll();
    $totalRows_liste_part = $liste_part->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
if($totalRows_liste_part>0){ foreach($row_liste_part as $row_liste_part){ 
$bailleur[$row_liste_part["id_partenaire"]]=$row_liste_part["nbbail"]; 
$cbailleur[$row_liste_part["id_partenaire"]]=$row_liste_part["partb"];
} }
  
  $totalgg=0;
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
.details { background-color: #94b86e!important; }
.details table.table-striped tbody>tr:nth-child(odd)>td, .details table.table-striped tbody>tr:nth-child(odd)>th { background-color: none!important; }
</style>
<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
$(document).ready(function() {
      /*
     * Insert a 'details' column to the table
     */
    /*var nCloneTh = document.createElement( 'th' );
    var nCloneTd = document.createElement( 'td' );
    nCloneTd.innerHTML = '<img src="./images/plus.gif">';
    nCloneTd.className = "center";
    $('#mytable thead tr').each( function () {
        this.insertBefore( nCloneTh, this.childNodes[0] );
    } );
    $('#mytable tbody tr').each( function () {
        this.insertBefore(  nCloneTd.cloneNode( true ), this.childNodes[0] );
    } );  */

var oTable = $('#mytable').dataTable();
//Delete the datable object first
if(oTable != null)oTable.fnDestroy();
//Remove all the DOM elements
//$('#mytable').empty();
var oTable = $('#mytable').dataTable( {
        "aoColumnDefs": [
            { "bSortable": false, "aTargets": [ -1 ] }
        ], 
       // sDom:"<'row'<'dataTables_header clearfix'<'col-md-7'lT><'col-md-5'Cf>r>>t<'row'<'dataTables_footer clearfix'<'col-md-6'i><'col-md-6'p>>>",
       // oTableTools:{aButtons:["copy","print","csv","xls",{"sExtends": "pdf","sPdfOrientation": "landscape"}],sSwfPath:"./swf/copy_csv_xls_pdf.swf"},
        "aaSorting": [], 
        //"aLengthMenu":[[25, 50, 100, 200, -1],[25, 50, 100, 200, "TOUS1"]],
        "iDisplayLength": -1,
        paging: false
    });
/* Formating function for row details */
function fnFormatDetails ( oTable, nTr )
{
    var aData = oTable.fnGetData( nTr );
    return aData[0];
}
                       //img[id="plus"]
$('#mytable tbody td').on('click', function () {
        var nTr = $(this).parents('tr')[0];
        if ( oTable.fnIsOpen(nTr) )
        {
            /* This row is already open - close it */
            //this.src = "./images/plus.png";
            oTable.fnClose( nTr );
        }
        else
        {
            /* Open this row */
            //this.src = "./images/moins.png";
            oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'details' );
        }
    } );
} );
/*]]>*/
</script>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Bailleurs </h4>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==0){ ?>
<?php
//echo do_link("","","Importation d&eacute;puis un format excel","<i class=\"icon-plus\"> Importer bailleur </i>","","./","pull-right p11","get_content('import.php','id=bailleur','modal-body_add',this.title);",1,"",$nfile);
//echo do_link("","","Ajout de bailleur","<i class=\"thickbox\"> Liste des bailleurs </i>","","./","pull-right p11","get_content('new_bailleur.php','','modal-body_add',this.title,'iframe');",1,"",$nfile);
//echo do_link("","","Importation d&eacute;puis un format excel","<i class=\"icon-plus\"> Importer convention </i>","","./","pull-right p11","get_content('import.php','id=convention&form=form00','modal-body_add',this.title);",1,"",$nfile);
echo do_link("","","Ajout de convention","<i class=\"icon-plus\"> Nouvelle convention </i>","","./","pull-right p11","get_content('new_convention.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div>
<div class="widget-content" style="display: block;">
<table class="table table-striped table-bordered table-hover table-responsive table-colvis datatable dataTable hide_befor_load display" id="mytable" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row">
<th class="hidden" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Convention</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Code</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Logo</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Sigle</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Nom</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Adresse mail</th>
<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Montant (F CFA) </th>
<!--<th class="sorting" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier">Convention</th>  -->
</tr>
</thead>
<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php if($totalRows_liste_bailleur>0) { $i=0; foreach($row_liste_bailleur as $row_liste_bailleur){ $id = $row_liste_bailleur['id_partenaire']; $code = $row_liste_bailleur['code']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class="hidden"><?php include "convention_data.php"; ?></td>
<td class=" "><?php echo $code; ?></td>
<td class=" "><a href="#myModal_add" data-toggle="modal" title="Actualiser l'image de l'acteur" onclick="get_content('edit_bailleur_photo.php','<?php echo "id=$id&code=$code"; ?>','modal-body_add',this.title);"><img src="<?php  echo (is_file("./images/bailleur/img_".$code.".jpg"))?'./images/bailleur/img_'.$code.".jpg":'./images/bailleur/none.png'; ?>" width="50" height="50" alt="<?php echo $row_liste_bailleur['definition']; ?>"></a></td>
<td class=" "><?php echo $row_liste_bailleur['sigle']; ?></td>
<td class=" "><?php echo $row_liste_bailleur['definition']; ?></td>
<td class=" "><?php echo $row_liste_bailleur['adresse_mail']; ?></td>
<td class=" "><div align="right">
  <?php if(isset($cbailleur[$id])){ echo number_format($cbailleur[$id], 0, ',', ' ') ; $totalgg=$totalgg+$cbailleur[$id];} ?>
  &nbsp;</div></td>
</tr>
<?php }  ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
  <td class="hidden">&nbsp;</td>
  <td class=" ">&nbsp;</td>
  <td class=" ">&nbsp;</td>
  <td class=" ">&nbsp;</td>
  <td class=" ">&nbsp;</td>
  <td class=" ">Total</td>
  <td class=" "><div align="right">
    <span class="list-group-item-danger"><strong>
    <?php if(isset($totalgg)){ echo number_format($totalgg, 0, ',', ' ') ; } ?></strong></span>
  </div></td>
</tr>
<?php } ?>
</tbody></table>
</div>     
<!-- Fin Site contenu ici -->
            </div>
        </div>
        </div>
        </div>
    </div> <?php include_once 'modal_add.php'; ?>
    <?php include_once ("includes/footer.php");?>
</div>
</body>
</html>