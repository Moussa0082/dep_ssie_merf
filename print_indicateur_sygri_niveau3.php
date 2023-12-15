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

if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else $annee=date("Y");

 if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Indicateurs_SYGRI_Niveau3.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Indicateurs_SYGRI_Niveau3.rtf"); } ?>
<?php
 
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
/*$phase="Phase3";
  if($phase=="Phase1") $nom_phase="Démarrage"; elseif($phase=="Phase2") $nom_phase="Mi-parcours";  elseif($phase=="Phase3") $nom_phase="Fin du projet"; else $nom_phase="";*/

//liste des indicateurs renseignés
//$projet_courant=",".$a;
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur = "SELECT intitule_indicateur_sygri_fida,indicateur_sygri_fida.referentiel,annee_reference,reference_cmr,cible_cmr FROM  indicateur_sygri_fida,indicateur_objectif_global_cmr WHERE indicateur_sygri_fida.referentiel=indicateur_objectif_global_cmr.referentiel GROUP BY indicateur_objectif_global_cmr.referentiel UNION SELECT intitule_indicateur_sygri_fida,indicateur_sygri_fida.referentiel,annee_reference,reference_cmr,cible_cmr FROM  indicateur_sygri_fida,indicateur_objectif_specifique_cmr WHERE indicateur_sygri_fida.referentiel=indicateur_objectif_specifique_cmr.referentiel GROUP BY indicateur_objectif_specifique_cmr.referentiel";
$liste_indicateur  = mysql_query($query_liste_indicateur , $pdar_connexion) or die(mysql_error());
$row_liste_indicateur = mysql_fetch_assoc($liste_indicateur );
$totalRows_liste_indicateur = mysql_num_rows($liste_indicateur );


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind FROM referentiel_indicateur";
$liste_ind_ref  = mysql_query($query_liste_ind_ref , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref);
$totalRows_liste_ind_ref  = mysql_num_rows($liste_ind_ref);
$liste_ind_ref_array = array();
$unite_ind_ref_array = array();
do{  $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"]; $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
}while($row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref));


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_ind_cmr_og = "SELECT referentiel, valeur_reelle, valeur_reelle1 FROM indicateur_objectif_global_cmr, indicateur_objectif_global where id_indicateur_objectif_global=indicateur_og order by id_indicateur_objectif_global, code_cmr, id_indicateur";
$ind_cmr_og  = mysql_query($query_ind_cmr_og , $pdar_connexion) or die(mysql_error());
$row_ind_cmr_og  = mysql_fetch_assoc($ind_cmr_og);
$totalRows_ind_cmr_og  = mysql_num_rows($ind_cmr_og);
$valeur_reel_ind_array = $valeur_reel_ind_array1 = array();
do{  $valeur_reel_ind_array[$row_ind_cmr_og["referentiel"]] = $row_ind_cmr_og["valeur_reelle"];
$valeur_reel_ind_array1[$row_ind_cmr_og["referentiel"]] = $row_ind_cmr_og["valeur_reelle1"];
}while($row_ind_cmr_og = mysql_fetch_assoc($ind_cmr_og));
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
.Style23{color: white;}

</style>
<div class="contenu">
  <div id="msg" align="center" class="red"></div>
<?php if(!isset($_GET["down"])){  ?>
  <div class="l_float"><?php //include("content/annee_ptba.php"); ?></div>
  <div class="r_float"><a href="s_sygri.php?annee=<?php echo $annee; ?>" class="button">Retour</a></div>
  <!--<div class="r_float" style="margin-right: 10px;"><a href="<?php echo $editFormAction."&down=1&t=p"; ?>" class="button"><img src="./images/pdf.jpg" width='20' height='20' alt='Modifier' /></a></div>-->
    <div class="r_float" style="margin-right: 10px;"><a href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
  <div class="r_float" style="margin-right: 10px;"><a href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<br />
<?php } ?>
<h4 align="center">Indicateurs de 3<sup>&egrave;me</sup> niveau SYGRI en <?php echo $annee;?></h4>

<table width="70%" border="0" align="center"  cellpadding="0" cellspacing="0" >
  <tr>
    <td><table width="98%" border="1" cellspacing="0" >
      <?php $t=0;  if($totalRows_liste_indicateur>0) { ?>
      <tr class="titrecorps2">
        <td>Indicateur 3</td>
        <td><span class="Style22">Unit&eacute;</span></td>
        <td nowrap="nowrap"><span class="Style22">Situation de r&eacute;f&eacute;rence</span></td>
        <td nowrap="nowrap"><span class="Style22">Mi parcours</span></td>
        <td><span class="Style22">Ach&egrave;vement</span></td>
        <td nowrap="nowrap"><span class="Style22">Objectifs DCP</span></td>
        </tr>

      <?php $p1="j"; $t=0; $i=0;do { ?>
      <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"';  $t=$t+1; $i=$i+1;?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='#ECF0DF';">
        <td ><u><span class="Style46"><?php echo $row_liste_indicateur['intitule_indicateur_sygri_fida']; ?></span></u> </td>
        <td align="center"><span class="Style46"><?php echo (isset($unite_ind_ref_array[$row_liste_indicateur['referentiel']]))?$unite_ind_ref_array[$row_liste_indicateur['referentiel']]:""; ?></span></td>
        <td ><div align="center">
            <div align="center">
              <?php echo $row_liste_indicateur["reference_cmr"]; if(isset($valeur_ref_ind_array[$row_liste_indicateur["referentiel"]])) {  ?>
              <span class="Style46">&nbsp;<?php echo (isset($unite_ind_ref_array[$row_liste_indicateur['referentiel']]))?$unite_ind_ref_array[$row_liste_indicateur['referentiel']]:"";}  ?></span></div>
        </div></td>

        <td><div align="center"><strong><strong><span class="Style16">
            <?php if(isset($valeur_reel_ind_array1[$row_liste_indicateur["referentiel"]])) {echo $valeur_reel_ind_array1[$row_liste_indicateur["referentiel"]]; ?>
          &nbsp;<?php echo (isset($unite_ind_ref_array[$row_liste_indicateur['referentiel']]))?$unite_ind_ref_array[$row_liste_indicateur['referentiel']]:"";} ?> </span><span class="Style16"> </span><strong><span class="Style16"> </span></strong><span class="Style16">

        </span></strong></strong></div></td>

        <td><div align="center"><strong><strong><span class="Style16">
            <?php if(isset($valeur_reel_ind_array[$row_liste_indicateur["referentiel"]])) {echo $valeur_reel_ind_array[$row_liste_indicateur["referentiel"]]; ?>
          &nbsp;<?php echo (isset($unite_ind_ref_array[$row_liste_indicateur['referentiel']]))?$unite_ind_ref_array[$row_liste_indicateur['referentiel']]:"";} ?> </span><span class="Style16"> </span><strong><span class="Style16"> </span></strong><span class="Style16">
           
        </span></strong></strong></div></td>
        <td><div align="center">
<?php echo $row_liste_indicateur['cible_cmr']; ?> </div></td>
        </tr>
      <?php } while ($row_liste_indicateur = mysql_fetch_assoc($liste_indicateur)); ?>
      <?php } else echo "<h3>Aucune valeur mesur&eacute;e</h3>" ;?>
    </table></td>
  </tr>
</table>  </div> 

<!-- Fin Site contenu ici -->

            </div>

        </div>



        </div>

    </div>

    <?php include_once ("includes/footer.php");?>

</div>

</body>

</html>