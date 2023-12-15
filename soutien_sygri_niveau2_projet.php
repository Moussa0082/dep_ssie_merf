<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

if ((isset($_GET["id_sup_ind"]) && intval($_GET["id_sup_ind"])>0)) {
  $id = intval($_GET["id_sup_ind"]);
  $insertSQL = sprintf("DELETE FROM soutien_indicateur_sygri2 WHERE id_indicateur_soutien=%s",
                       GetSQLValueString($id, "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo)); exit();
}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{ //personnel
    $date=date("Y-m-d");

  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {

    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

  $insertSQL = sprintf("INSERT INTO  soutien_indicateur_sygri2 (intitule_indicateur_soutien, ordre, proportion, cible, referentiel, indicateur_sygri_niveau2, id_personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s,'$personnel', '$date')",
					   GetSQLValueString($_POST['intitule_indicateur_soutien'], "text"),
					   GetSQLValueString($_POST['ordre'], "text"),
					   GetSQLValueString($_POST['proportion'], "double"),
                       GetSQLValueString($_POST['cible'], "double"),
   					   GetSQLValueString($_POST['referentiel'], "int"),
					   GetSQLValueString($_POST['sygri'], "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
  header(sprintf("Location: %s", $insertGoTo));  exit();

  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
    $id = intval($_POST["MM_delete"]);
    $insertSQL = sprintf("DELETE FROM soutien_indicateur_sygri2 WHERE id_indicateur_soutien=%s",
                         GetSQLValueString($id, "int"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
    header(sprintf("Location: %s", $insertGoTo)); exit();
  }

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
    $id = intval($_POST["MM_update"]);
    $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];
$insertSQL = sprintf("UPDATE soutien_indicateur_sygri2 SET intitule_indicateur_soutien=%s, ordre=%s, proportion=%s, cible=%s, referentiel=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur_soutien='$id'",

					   GetSQLValueString($_POST['intitule_indicateur_soutien'], "text"),
					   GetSQLValueString($_POST['ordre'], "text"),
					   GetSQLValueString($_POST['proportion'], "double"),
                       GetSQLValueString($_POST['cible'], "double"),
   					   GetSQLValueString($_POST['referentiel'], "int"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());

    $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));  exit();
  }
}


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind FROM referentiel_indicateur";
$liste_ind_ref  = mysql_query($query_liste_ind_ref , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref);
$totalRows_liste_ind_ref  = mysql_num_rows($liste_ind_ref);
$liste_ind_ref_array = array();
$unite_ind_ref_array = array();
do{  $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"]; $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
}while($row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref));

include_once 'modal_add.php';

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
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }

</style>
<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Indicateurs de soutien</h4>
<?php /* if (isset ($_SESSION["clp_fonction"]) && ($_SESSION["clp_fonction"] == "Administrateur")) {?>
<a onclick="get_content('new_soutien_sygri_niveau2_projet.php','','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Ajout d'indicateur" class="pull-right p11"><i class="icon-plus"> Ajouter un Indicateur </i></a>
<?php } */ ?>

<?php  include_once 'modal_add.php'; ?>
</div>
<div class="widget-content">

<table border="0" style="border: solid 1px;" width="100%" cellpadding="0" cellspacing="1">
  <tr bgcolor="#CCCCCC">
    <th align="left" class="titrecorps2" width="50"><strong>Code </strong></th>
    <th align="center" class="titrecorps2"><strong>Indicateur</strong></th>
    <th align="center" class="titrecorps2" align="center">Unit&eacute;</th>
    <th align="center" class="titrecorps2" width="100" align="center"><strong>Proportion</strong></th>
    <th align="center" class="titrecorps2" align="center"><strong>Cible</strong></th>
    <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION["clp_niveau"]<3)) { ?>
    <th colspan="2" align="center" class="titrecorps2"><strong>Editer</strong></th>
    <?php } ?>
  </tr>
<?php
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_soutien = "SELECT * FROM activite_projet, indicateur_sygri2_projet LEFT JOIN soutien_indicateur_sygri2 ON indicateur_sygri_niveau2=id_indicateur_sygri_niveau2_projet where sous_composante=code and niveau=2 order by code";
$liste_ind_soutien  = mysql_query($query_liste_ind_soutien , $pdar_connexion) or die(mysql_error());
$row_liste_ind_soutien = mysql_fetch_assoc($liste_ind_soutien);
$totalRows_liste_ind_soutien  = mysql_num_rows($liste_ind_soutien);

if($totalRows_liste_ind_soutien>0){
$p1=$p11="j"; $ii=0;  do {
?>
 <?php if($p1!=$row_liste_ind_soutien['id_sous_composante']) {?>
          <tr bgcolor="#B1CD78">
            <td colspan="7" align="center" bgcolor="#B1CD78"><div align="left" class="Style4"><strong>

            <?php if($p1!=$row_liste_ind_soutien['id_sous_composante']) {echo $row_liste_ind_soutien['code_sous_composante'].": ".$row_liste_ind_soutien['intitule_sous_composante'];}
                      $p1=$row_liste_ind_soutien['id_sous_composante']; ?>
                        </strong></div></td>
            </tr>
          <?php } ?>
 <?php if($p11!=$row_liste_ind_soutien['id_indicateur_sygri_niveau2_projet']) {?>
          <tr bgcolor="#D2E2B1">
            <td colspan="5" align="center" bgcolor="#D2E2B1"><div align="left" class="Style4"><strong>&nbsp;&nbsp;

            <?php if($p11!=$row_liste_ind_soutien['id_indicateur_sygri_niveau2_projet']) {echo $row_liste_ind_soutien['code_ind_sygri2'].": ".$row_liste_ind_soutien['intitule_indicateur_sygri2']; }
                      $p11=$row_liste_ind_soutien['id_indicateur_sygri_niveau2_projet']; ?>
                        </strong></div></td>
            <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION["clp_niveau"]<3)) {
$sommes=0; $sygri=$row_liste_ind_soutien["id_indicateur_sygri_niveau2_projet"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_data_sommes = "SELECT SUM(proportion) as sommes FROM soutien_indicateur_sygri2 where indicateur_sygri_niveau2=$sygri";
$edit_data_sommes = mysql_query($query_edit_data_sommes, $pdar_connexion) or die(mysql_error());
$row_edit_data_sommes = mysql_fetch_assoc($edit_data_sommes);
$totalRows_edit_data_sommes = mysql_num_rows($edit_data_sommes);
if(isset($row_edit_data_sommes["sommes"])) $sommes = $row_edit_data_sommes["sommes"];
             ?>
            <td align="center" colspan="2"><?php if((100-$sommes)>0){ ?><a style="color: EF1D42 !important;" onclick="get_content('new_soutien_sygri_niveau2_projet.php','sygri=<?php echo $row_liste_ind_soutien["id_indicateur_sygri_niveau2_projet"]; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Ajout d'indicateur">Ajouter</a><?php } ?></td> <?php } ?>
            </tr>
          <?php } ?>
<?php if(!empty($row_liste_ind_soutien['intitule_indicateur_soutien'])) {?>
  <tr <?php if($ii%2==0) echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#FFFFFF"';?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='<?php if($ii%2==0) echo '#ECF0DF'; else echo '#FFFFFF'; $ii=$ii+1 ?>';">
      <td align="center" width="100">&nbsp;<?php echo $row_liste_ind_soutien['ordre']; ?></td>
      <td><div align="left">&nbsp;&nbsp;<?php echo $row_liste_ind_soutien['intitule_indicateur_soutien']; ?></div></td>
      <td align="center"><div align="center"> <?php if(isset($unite_ind_ref_array[$row_liste_ind_soutien["referentiel"]])) echo " (".$unite_ind_ref_array[$row_liste_ind_soutien["referentiel"]].")"; ?></div></td>
      <td align="center"><div align="center"> <?php echo $row_liste_ind_soutien["proportion"]; ?></div></td>
      <td align="center"><div align="center"> <?php echo $row_liste_ind_soutien["cible"]; ?></div></td>
       <?php if(isset($_SESSION['clp_niveau']) && ($_SESSION["clp_niveau"]<3)) { ?>
      <td width="20"><a onclick="get_content('new_soutien_sygri_niveau2_projet.php','id=<?php echo $row_liste_ind_soutien["id_indicateur_soutien"]."&sygri=".$row_liste_ind_soutien["id_indicateur_sygri_niveau2_projet"]; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Ajout d'indicateur"><img src='images/edit.png' width='20' height='20' alt='Modifier'></a></td>
      <td width="20"><?php if(!isset($ref_ind_array[$row_liste_ind_soutien['id_indicateur_soutien']])){ ?><a href="<?php echo $_SERVER['PHP_SELF']."?id_sup_ind=".$row_liste_ind_soutien['id_indicateur_soutien'];?>" onclick="return confirm('Voulez-vous vraiment supprimer ?');" /><img src="images/delete.png" width="16"/ alt="Supprimer" /></a><?php } ?></td> <?php } ?>
      </tr>
<?php }  }while($row_liste_ind_soutien = mysql_fetch_assoc($liste_ind_soutien));
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