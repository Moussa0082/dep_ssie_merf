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

$personnel = $_SESSION["clp_id"];
$date = date("Y-m-d");

if ((isset($_GET["id_sup"]) && intval($_GET["id_sup"])>0)) {
  $id = intval($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."type_requete_dano WHERE id_type_requete=%s",
                       GetSQLValueString($id, "int"));

  	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_GET["id_sup_rubrique"]) && !empty($_GET["id_sup_rubrique"]))) {
  $id = ($_GET["id_sup_rubrique"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."rubrique_projet WHERE code_rub=%s",
                       GetSQLValueString($id, "text"));

  	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_GET["id_sup_type_doc"]) && !empty($_GET["id_sup_type_doc"]))) {
  $id = ($_GET["id_sup_type_doc"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."type_doc_workflow WHERE code=%s",
                       GetSQLValueString($id, "text"));

  	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{ //Fonction
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."type_requete_dano (type_requete, description) VALUES (%s, %s)",
                         GetSQLValueString($_POST['type_requete'], "text"),
                         GetSQLValueString($_POST['description'], "text"));

  	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."type_requete_dano WHERE id_type_requete=%s",
                         GetSQLValueString($id, "int"));

  	  try{
    $Result1 = $pdar_connexion->prepare($insertSQL);
    $Result1->execute();
  }catch(Exception $e){ die(mysql_error_show_message($e)); }    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."type_requete_dano SET type_requete=%s, description=%s WHERE id_type_requete=%s",
                         GetSQLValueString($_POST['type_requete'], "text"),
						 GetSQLValueString($_POST['description'], "text"),
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

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form3"))
{ //rubrique
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."rubrique_projet (code_rub, nom_rubrique, date_enregistrement, id_personnel) VALUES (%s, %s, '$date', '$personnel')",
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['nom_rubrique'], "text"));

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
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."rubrique_projet WHERE code_rub=%s",
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
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."rubrique_projet SET code_rub=%s, nom_rubrique=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date'  WHERE code_rub=%s",
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['nom_rubrique'], "text"),
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
{ //Type doc workfow
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."type_doc_workflow (code, intitule, description, responsable_concerne, duree, date_enregistrement, id_personnel) VALUES (%s, %s, %s, %s, %s, '$date', '$personnel')",
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['intitule'], "text"),
                         GetSQLValueString($_POST['description'], "text"),
                         GetSQLValueString(implode("|",$_POST['responsable']), "text"),
                         GetSQLValueString(implode("|",$_POST['duree']), "text"));

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
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."type_doc_workflow WHERE code=%s",
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
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."type_doc_workflow SET code=%s, intitule=%s, description=%s, responsable_concerne=%s, duree=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date'  WHERE code=%s",
                         GetSQLValueString($_POST['code'], "text"),
                         GetSQLValueString($_POST['intitule'], "text"),
                         GetSQLValueString($_POST['description'], "text"),
                         GetSQLValueString(implode("|",$_POST['responsable']), "text"),
                         GetSQLValueString(implode("|",$_POST['duree']), "text"),
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

//Unite indicateur
$query_liste_requete_dano = "SELECT * FROM ".$database_connect_prefix."type_requete_dano";
           try{
    $liste_requete_dano = $pdar_connexion->prepare($query_liste_requete_dano);
    $liste_requete_dano->execute();
    $row_liste_requete_dano = $liste_requete_dano ->fetchAll();
    $totalRows_liste_requete_dano = $liste_requete_dano->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//Mission supervision
$query_liste_rubrique = "SELECT * FROM ".$database_connect_prefix."rubrique_projet ";
           try{
    $liste_rubrique = $pdar_connexion->prepare($query_liste_rubrique);
    $liste_rubrique->execute();
    $row_liste_rubrique = $liste_rubrique ->fetchAll();
    $totalRows_liste_rubrique = $liste_rubrique->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

//Doc Workflow
$query_liste_docworkflow = "SELECT * FROM ".$database_connect_prefix."type_doc_workflow ";
           try{
    $liste_docworkflow = $pdar_connexion->prepare($query_liste_docworkflow);
    $liste_docworkflow->execute();
    $row_liste_docworkflow = $liste_docworkflow ->fetchAll();
    $totalRows_liste_docworkflow = $liste_docworkflow->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$query_liste_responsable = "SELECT distinct fonction FROM ".$database_connect_prefix."personnel ORDER BY fonction asc";
           try{
    $liste_responsable = $pdar_connexion->prepare($query_liste_responsable);
    $liste_responsable->execute();
    $row_liste_responsable = $liste_responsable ->fetchAll();
    $totalRows_liste_responsable = $liste_responsable->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$fonction_array = array();
if($totalRows_liste_responsable>0){foreach($row_liste_responsable as $row_liste_responsable){ $fonction_array[]=$row_liste_responsable["fonction"]; } }

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
<?php include_once 'modal_add.php'; ?>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }

</style>
<div style="padding-top:20px;">

<div class="col-md-6">
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> Type de Requ&ecirc;tes DNO </h4>
   <div class="toolbar no-padding"><div class="btn-group"><span class="btn btn-xs widget-collapse pull-right"><i class="icon-angle-down"></i></span>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
echo do_link("","","Type de requ&ecirc;tes DNO","<i class=\"icon-plus\"> Nouveau type </i>","","./","pull-right p11","get_content('new_type_requete_dano.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div></div>
</div>
<div class="widget-content">
<table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" align="center" >
            <thead>
                <tr>
                  <td><div align="left"><strong>Types de requ&ecirc;tes </strong></div></td>
                  <td><div align="left"><strong>Description </strong></div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?> <td align="center" width="80" ><strong>Actions</strong></td> <?php } ?>
                </tr>
            </thead>
                <?php if($totalRows_liste_requete_dano>0) {$i=0;foreach($row_liste_requete_dano as $row_liste_requete_dano){ $id = $row_liste_requete_dano['id_type_requete']; ?>
                <tr>
                  <td><div align="left"><?php echo $row_liste_requete_dano['type_requete']; ?></div>                    <div align="left"></div></td>
                  <td><div align="left"><?php echo $row_liste_requete_dano['description']; ?></div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center">
<?php
echo do_link("","","Type de requ&ecirc;tes DANO","","edit","./","","get_content('new_type_requete_dano.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce type ?');",0,"margin:0px 5px;",$nfile);
?>                </td>
                   <?php } ?>
				  </tr>

                <?php }  ?>
                <?php } ?>
              </table>

</div></div>
</div>
<div class="col-md-6">
<div class="widget box ">
 <div class="widget-header"> <h4><i class="icon-reorder"></i> Types de dossier</h4>
<div class="toolbar no-padding"><div class="btn-group"><span class="btn btn-xs widget-collapse pull-right"><i class="icon-angle-down"></i></span>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
echo do_link("","","Ajout de types de dossier","<i class=\"icon-plus\"> Nouveau types de dossier </i>","","./","pull-right p11","get_content('new_type_doc_wf.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div></div>
</div>
<div class="widget-content">
<table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" align="center" id="mtable" >
            <thead>
                <tr>
                  <td><div align="left"><strong>Code</strong></div></td>
                  <td><div align="left"><strong>Libell&eacute;</strong></div></td>
                  <td><div align="left"><strong>Responsables (Dur&eacute;e) </strong></div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center" width="80" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
                <?php if($totalRows_liste_docworkflow>0) {$i=0;foreach($row_liste_docworkflow as $row_liste_docworkflow){ $id = $row_liste_docworkflow['code']; ?>
                <tr>
                  <td><div align="left"><?php echo $row_liste_docworkflow['code']; ?></div></td>
                  <td><div align="left"><?php echo $row_liste_docworkflow['intitule']; ?></div></td>
                  <td><div align="left"><?php $a = explode('|',$row_liste_docworkflow['responsable_concerne']); $d = explode('|',$row_liste_docworkflow['duree']); foreach($a as $b=>$c) echo (!empty($c) && in_array($c,$fonction_array))?$c."(<b>".((isset($d[$b]) && !empty($d[$b]))?$d[$b]:"NaN")."</b>) - ":""; ?></div></td>
				   <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center">
<?php
echo do_link("","","Modifier mission","","edit","./","","get_content('new_type_doc_wf.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup_type_doc=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer ce type de document ?');",0,"margin:0px 5px;",$nfile);
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
 <div class="widget-header"> <h4><i class="icon-reorder"></i> Rubrique</h4>
<div class="toolbar no-padding"><div class="btn-group"><span class="btn btn-xs widget-collapse pull-right"><i class="icon-angle-down"></i></span>
  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
echo do_link("","","Ajout de rubrique","<i class=\"icon-plus\"> Nouveau rubrique </i>","","./","pull-right p11","get_content('new_rubrique.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div></div>
</div>
<div class="widget-content">
<table border="0" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" align="center" id="mtable" >
            <thead>
                <tr>
                  <td><div align="left"><strong>Code</strong></div></td>
                  <td><div align="left"><strong>Rubrique des missions </strong></div></td>
                  <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center" width="80" ><strong>Actions</strong></td>
                  <?php } ?>
                </tr>
            </thead>
                <?php if($totalRows_liste_rubrique>0) {$i=0;foreach($row_liste_rubrique as $row_liste_rubrique){$id = $row_liste_rubrique['code_rub']; ?>
                <tr>
                  <td><div align="left"><?php echo $row_liste_rubrique['code_rub']; ?></div></td>
                  <td><div align="left"><?php echo $row_liste_rubrique['nom_rubrique']; ?></div></td>
				   <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td align="center">
<?php
echo do_link("","","Modifier mission","","edit","./","","get_content('new_rubrique.php','id=$id','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup_rubrique=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cette mission ?');",0,"margin:0px 5px;",$nfile);
?>
                </td>
                   <?php } ?>
				  </tr>
                <?php } ?>
                <?php } ?>
              </table>

</div></div>
</div>



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