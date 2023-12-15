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

if ((isset($_GET["id_sup"]) && intval($_GET["id_sup"])>0)) {
  $id = intval($_GET["id_sup"]);
  $insertSQL = sprintf("DELETE from ".$database_connect_prefix."annee WHERE annee=%s",
                       GetSQLValueString($id, "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{ //Fonction
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
    $insertSQL = sprintf("INSERT INTO ".$database_connect_prefix."annee (annee, etiquette) VALUES (%s, %s)",
                         GetSQLValueString($_POST['annee'], "int"),
						 GetSQLValueString($_POST['etiquette'], "text"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE from ".$database_connect_prefix."annee WHERE annee=%s",
                         GetSQLValueString($id, "int"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $insertSQL = sprintf("UPDATE ".$database_connect_prefix."annee SET annee=%s, etiquette=%s WHERE annee=%s",
                         GetSQLValueString($_POST['annee'], "int"),
						 GetSQLValueString($_POST['etiquette'], "text"),
                         GetSQLValueString($id, "int"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}

//AnnÃ©e
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_annee = "SELECT * FROM ".$database_connect_prefix."annee ";
$liste_annee  = mysql_query($query_liste_annee , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_annee = mysql_fetch_assoc($liste_annee);
$totalRows_liste_annee  = mysql_num_rows($liste_annee);

//$liste_niveau_array = array('Aucun','RSE / Administrateur','Responsable de composante','OPP','Financier','Coordonateur','Visiteur','OPM');

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
<?php include_once("modal_add.php"); ?>
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }

</style>
<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Ann&eacute;e</h4>
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){ ?>
<?php
echo do_link("","","Ajout d'ann&eacute;e","<i class=\"icon-plus\"> Nouvelle Ann&eacute;e </i>","","./","pull-right p11","get_content('new_annee.php','','modal-body_add',this.title);",1,"",$nfile);
?>
<?php } ?>
</div>
<div class="widget-content">

<table id="mtable" border="0" class="table table-striped table-bordered table-hover table-responsive dataTable hide_befor_load" style="width: 500px" cellspacing="3" align="center">
                <thead>
                  <tr>
                    <td rowspan="2"><div align="left"><strong>Ann&eacute;e</strong></div></td>
                    <td rowspan="2"><strong>Etiquette PTBA </strong></td>
                    <td colspan="2" align="center"><strong>Importation du budget</strong> </td>
                  </tr>
                  <tr>
                    <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?> <td align="center"><strong>Par cat&eacute;gorie </strong></td>
                    <td align="center"><strong>Par r&eacute;gion</strong></td>
                    <?php } ?>
                  </tr>
                </thead>
                <?php if($totalRows_liste_annee>0) {$i=0;do { ?>
                <tr>
                  <td><div align="left"><a onclick="get_content('new_annee.php','id=<?php echo $row_liste_annee['annee']; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modification ann&eacute;e" /><?php echo $row_liste_annee['annee']; ?></a></div></td>
				   <td><?php echo $row_liste_annee['etiquette']; ?></td>
				   <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION['clp_niveau']==1)) { ?>
                  <td  align="center">
<?php
echo do_link("","","Importation du budget par cat&eacute;gorie de d&eacute;penses","","import","./","","get_content('modal_content/import_cout_ptba.php','id=".$row_liste_annee['annee']."','modal-body_add',this.title,'iframe');",1,"",$nfile);
?>
                  </td>
                  <td  align="center">
<?php
echo do_link("","","Importation du budget par r&eacute;gion","","import","./","","get_content('modal_content/import_cout_ptba.php','id=".$row_liste_annee['annee']."&zone=1','modal-body_add',this.title,'iframe');",1,"",$nfile);
?>
                  </td>
                   <?php } ?>
				  </tr>

                <?php } while ($row_liste_annee = mysql_fetch_assoc($liste_annee)); ?>
                <?php } ?>
              </table>

</div> </div>

<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>