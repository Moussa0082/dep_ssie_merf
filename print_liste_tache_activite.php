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

if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=tache_type_activites.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=tache_type_activites.doc"); }

include_once 'modal_add.php';

$query_liste_ind_soutien = "SELECT categorie, id_type, type_activite.type_activite, ordre, proportion, intitule_tache, id_groupe_tache FROM type_activite LEFT JOIN type_tache ON id_type=type_tache.type_activite  order by categorie, ordre";
try{
    $liste_ind_soutien = $pdar_connexion->prepare($query_liste_ind_soutien);
    $liste_ind_soutien->execute();
    $row_liste_ind_soutien = $liste_ind_soutien ->fetchAll();
    $totalRows_liste_ind_soutien = $liste_ind_soutien->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<?php if(!isset($_GET["down"])){  ?>
<head>
  <title><?php print $config->sitename; ?></title>
  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone; ?>" />
  <?php } ?>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <?php if(!isset($_GET["down"])){  ?>
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
.Style11 { font-weight: bold;color: #FFFFFF;}
.well {margin-bottom: 5px;} thead tr th{vertical-align: middle; text-align: center; }
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; } .marquer{background: #EBEBEB!important; }
</style>
<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<?php if(!isset($_GET["down"])){  ?>
<div class="well well-sm r_float">
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."?down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."?down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<?php } ?>
</div>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> T&acirc;ches par type d'activit&eacute;</h4>

<?php  if(!isset($_GET["down"])) include_once 'modal_add.php'; ?>
</div>
<div class="widget-content">

<table width="<?php echo (!isset($_GET["down"]))?'80%':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" style="border: solid 1px;" cellpadding="0" cellspacing="1">
  <tr bgcolor="#CCCCCC">
    <th align="left" class="titrecorps2" width="50"><strong>Code </strong></th>
    <th align="center" class="titrecorps2"><strong>T&acirc;ches</strong></th>
  
    <th align="center" class="titrecorps2" width="100"><strong>Proportion</strong></th>
   
    <th align="center" class="titrecorps2" width="100">Porportion cumulée </th>
    <?php if(!isset($_GET["down"])) if(isset($_SESSION['clp_niveau']) && ($_SESSION["clp_niveau"]<3)) { ?>
    <?php } ?>
  </tr>
<?php
if($totalRows_liste_ind_soutien>0){
$p1=$p11="j"; $ii=0;  $pp=$i=0;   foreach($row_liste_ind_soutien as $row_liste_ind_soutien){ 
?>

 <?php if($p11!=$row_liste_ind_soutien['id_type']) {?>
          <tr bgcolor="#B1CD78">
            <td colspan="4" align="center" bgcolor="#B1CD78"><div align="left" class="Style4"><strong>&nbsp;&nbsp;

            <?php if($p11!=$row_liste_ind_soutien['id_type']) {echo $row_liste_ind_soutien['categorie'].": ".$row_liste_ind_soutien['type_activite']; }
                      $p11=$row_liste_ind_soutien['id_type']; $pp=0; ?>
                        </strong></div></td>
            <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION["clp_niveau"]<3)) {
$sommes=0; $cat=$row_liste_ind_soutien["id_type"];
$query_edit_data_sommes = "SELECT SUM(proportion) as sommes FROM type_tache where type_activite=$cat";
try{
    $edit_data_sommes = $pdar_connexion->prepare($query_edit_data_sommes);
    $edit_data_sommes->execute();
    $row_edit_data_sommes = $edit_data_sommes ->fetch();
    $totalRows_edit_data_sommes = $edit_data_sommes->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

if(isset($row_edit_data_sommes["sommes"])) $sommes = $row_edit_data_sommes["sommes"];
             ?>
            <?php } ?>
            </tr>
          <?php } ?>
<?php if(!empty($row_liste_ind_soutien['intitule_tache'])) {?>
  <tr <?php if($ii%2==0) echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#FFFFFF"';?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='<?php if($ii%2==0) echo '#ECF0DF'; else echo '#FFFFFF'; $ii=$ii+1 ?>';">
      <td align="center" width="100">&nbsp;<?php echo $row_liste_ind_soutien['ordre']; ?></td>
      <td><div align="left">&nbsp;&nbsp;<?php echo $row_liste_ind_soutien['intitule_tache']; ?></div></td>
     
      <td align="center"><div align="center"> <?php echo $row_liste_ind_soutien["proportion"]." %"; ?></div></td>
     
      <td align="center"><?php echo ($row_liste_ind_soutien['proportion']+$pp)."%"; $pp=$row_liste_ind_soutien['proportion']+$pp;?></td>
      <?php if(!isset($_GET["down"])) if(isset($_SESSION['clp_niveau']) && ($_SESSION["clp_niveau"]<3)) { ?>
      <?php } ?>
      </tr>
<?php }  }
}else
{
  echo "<tr class=''><td colspan='6' align='center'><h1>Aucun r&eacute;sultat !</h1></td></tr>";
} ?>
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