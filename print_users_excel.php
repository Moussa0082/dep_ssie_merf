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

if(isset($_GET["down"])){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Liste_acteurs_SSE.xls"); } ?>
<?php
 

$page = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
    $page .= (strpos($page, '?')) ? "&" : "?";
    $page .= $_SERVER['QUERY_STRING'];
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_limit_etape1 = "SELECT * FROM structure,personnel where N!=1 and structure=code_structure and projet LIKE '%".$_SESSION["clp_projet"]."%' and code_structure='".$_SESSION["clp_structure"]."' ORDER BY personnel.niveau";
$etape1 = mysql_query($query_limit_etape1, $pdar_connexion) or die(mysql_error());
$row_etape1 = mysql_fetch_assoc($etape1);
$totalRows_etape1 = mysql_num_rows($etape1);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php if(!isset($_GET["down"])){  ?>
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
<?php }  ?>
<body>
 <header class="header navbar navbar-fixed-top" role="banner">
    <?php if(!isset($_GET["down"])) include_once("includes/header.php"); ?>
 </header>
<div id="container">
    <div id="sidebar" class="sidebar-fixed">
        <div id="sidebar-content">
            <?php if(!isset($_GET["down"])) include_once("includes/menu_top.php"); ?>
        </div>
        <div id="divider" class="resizeable"></div>
    </div>

    <div id="content">
        <div class="container">
            <div class="crumbs">
                <?php if(!isset($_GET["down"])) include_once("includes/sous_menu.php"); ?>
            </div>
        <div class="page-header">
            <div class="p_top_5">
<!-- Site contenu ici -->
<style>#sp_hr {margin:0px; }
.r_float{float: right;}
.Style1{color: white;}
.titrecorps2 {
	background-color: #D2E2B1;
}

</style>
<div class="contenu">
  <div id="msg" align="center" class="red"></div>
  <?php if(!isset($_GET["down"])){  ?>
  <div class="r_float"><a href="s_parametrage.php" class="button">Retour</a></div>
  <div class="r_float" style="margin-right: 10px;"><a href="<?php echo $editFormAction."&down=1"; ?>" class="button">Télécharger</a></div>
<div class="clear h0">&nbsp;</div><br />
<?php } ?>

<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td valign="top" bgcolor="#D2E2B1"><div align="center"><strong>Liste des Utilisateurs du <?php if(isset($_SESSION["clp_projet"])){ ?><b><?php //$_SESSION["clp_projet_nom"].' '.
echo $_SESSION["clp_projet_sigle"].' ';  mysql_select_db($database_connect_transfert, $connect_transfert);
$mySqlQuery = "SELECT * FROM ".$database_connect_prefix."ugl where code_ugl='".$_SESSION['clp_structure']."'";
$qh = mysql_query_ruche($mySqlQuery, $connect_transfert) or die(mysql_error_show_message(mysql_error()));
$data = mysql_fetch_assoc($qh);
$totalRows_clp = mysql_num_rows($qh);

if(isset($data["nom_ugl"])) echo "&nbsp;<span style='color:#FF9900;'>( ".$data["abrege_ugl"]." )</span>"; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?>&nbsp;&nbsp;<u>&eacute;diter le</u> <span class="Style7 Style1"><u><strong><?php echo date("d/m/Y"); ?></strong></u></span></strong></div></td>
  </tr>
</table>

<?php if ((isset($_GET["etape2"])) && ($_GET["etape2"] == "etape2") && isset($_GET["id_personnel"])) { ?>
  <?php }

else { ?>
  <table border="1" width="100%" cellpadding="2" cellspacing="0" align="center" align="center">
    <tr align="center" bgcolor="#506429">
      <td><span class="Style1">Login</span></td>
    <td><span class="Style1">Nom</span></td>
    <td><span class="Style1">Prenom</span></td>
    <!--<td><span class="Style1">Guichet</span></td> -->
    <td><span class="Style1">Contact</span></td>
    <td><span class="Style1">Fonction</span></td>
    <td><div align="left"><span class="Style1">Description</span></div></td>
    </tr>
    <?php if($totalRows_etape1>0) { $i=0; do {  ?>
      <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; $i=$i+1;?>>
        <td><?php echo $row_etape1['id_personnel']; ?></td>
        <td><?php echo $row_etape1['nom']; ?></td>
        <td><?php echo $row_etape1['prenom']; ?></td>
        <!--<td><?php $row_etape1['nom_structure']; ?></td>-->
        <td align="center"><?php echo $row_etape1['contact']; ?></td>
        <td><?php echo $row_etape1['fonction']; ?></td>
        <td align="center"><div align="left"><?php echo $row_etape1['description_fonction']; ?></div></td>
        </tr>
<?php } while ($row_etape1 = mysql_fetch_assoc($etape1));?>
      <tr>
        <td colspan="7"><strong>Total: <?php echo $i;?></strong></td>
        </tr>
      
<?php } else { ?>
  <tr><td colspan="7" align="center"><h3>Aucun personnel !</h3></td></tr>
    <?php } ?>
  </table>
  <?php } ?>
<!-- Fin Site contenu ici -->

            </div>

        </div>



        </div>

    </div>

    <?php include_once ("includes/footer.php");?>

</div>

</body>

</html>