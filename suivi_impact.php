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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}


if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form6") && $_SESSION['clp_niveau']<4) {
$date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

//suppression
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$an=$_POST['annee'];
$idog=$_POST['ind'];
$vcible=$_POST['valeur_cible'];

$query_sup_set = "DELETE FROM cible_indog_cmr WHERE indicateur_ogcmr='$idog'";
$Result1 = mysql_query($query_sup_set, $pdar_connexion) or die(mysql_error());
//fin suppression
foreach ($an as $key => $value)
{
	if(isset($vcible[$key]) && $vcible[$key]!=NULL) {
	
  $insertSQL = sprintf("INSERT INTO cible_indog_cmr (annee, indicateur_ogcmr, valeur_cible, id_personnel, date_enregistrement) VALUES (%s, %s, %s,'$personnel', '$date')",
                       GetSQLValueString($an[$key], "int"),
   					   GetSQLValueString($idog, "int"),
					   GetSQLValueString($vcible[$key], "double"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
  }
  }
  $insertGoTo = $_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no"; 
  header(sprintf("Location: %s", $insertGoTo));
}


// query og
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_og = "SELECT * FROM objectif_global WHERE ".$_SESSION["clp_where"]."";
$og  = mysql_query($query_og , $pdar_connexion) or die(mysql_error());
$row_og  = mysql_fetch_assoc($og);
$totalRows_og  = mysql_num_rows($og);

// query indicateur
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_ind = "SELECT * FROM indicateur_objectif_global_cmr, indicateur_objectif_global WHERE ".$_SESSION["clp_where"]." and id_indicateur_objectif_global=indicateur_og  order by id_indicateur_objectif_global, code_cmr, id_indicateur";
$ind  = mysql_query($query_ind , $pdar_connexion) or die(mysql_error());
$row_ind  = mysql_fetch_assoc($ind);
$totalRows_ind  = mysql_num_rows($ind);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind FROM referentiel_indicateur";
$liste_ind_ref  = mysql_query($query_liste_ind_ref , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref);
$totalRows_liste_ind_ref  = mysql_num_rows($liste_ind_ref);
$liste_ind_ref_array = array();
$unite_ind_ref_array = array();
do{  $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"]; $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
}while($row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref));

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
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init();
 /*$("#container").addClass("sidebar-closed");*/});</script>
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
.titrecorps2 {background-color: #999999; color:white; }
</style>
<div class="widget box box_projet">
<div class="widget-header1"> <center><h4><?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>
<div class="widget-header"> <h4><i class="icon-reorder"></i> Suivi des indicateurs d'impact </h4>
    <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1){?>
<?php
$libelle = array("suivi_produit.php"=>"Indicateurs de produit","suivi_cmr_resultat.php"=>"Indicateurs d'effet","suivi_effet_cmr.php"=>"Indicateurs ODP","suivi_impact.php"=>"Indicateurs d'impact");
foreach($libelle as $key=>$lib){
  echo do_link("",$key,"$lib","<i> $lib </i>","","./","pull-right p11","",0,"",$nfile);
  $i--; }
?>
<!--<a href="suivi_produit.php" title="Suivi des indicateurs de produit" class="pull-right p11"><i class="icon-plus"> Indicateurs de produit </i></a>
<a href="suivi_cmr_resultat.php" title="Suivi des indicateurs d'effet" class="pull-right p11"><i class="icon-plus"> Indicateurs d'effet </i></a>
<a href="suivi_effet_cmr.php" title="Suivi des indicateurs ODP" class="pull-right p11"><i class="icon-plus"> Indicateurs ODP </i></a>
<a href="suivi_impact.php" title="Suivi des indicateurs d'impact" class="pull-right p11"><i class="icon-plus"> Indicateurs d'impact </i></a>-->
    <?php } ?>
</div>


<div class="widget-content" style="display: block;">
<table width="100%" border="0" align="center" cellspacing="1" class="table table-striped table-bordered table-responsive">
                      <?php if($totalRows_og>0) {$i=0;do { ?>
                      <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; $i=$i+1;?>>
                        <td><div align="left"><span class="Style27"><strong>Objectif G&eacute;n&eacute;ral: </strong></span> <span class="Style27"><?php echo $row_og['intitule_objectif_global']; ?></span></div></td>
                      </tr>
                      <tr>
                        <td>
                        <table border="0" cellspacing="1" width="100%" class="table table-striped table-bordered table-hover table-responsive">
                          <?php if($totalRows_ind>0) { ?>
                          <thead>
                          <tr>
                            <td rowspan="2" align="center" >Indicateur</td>
                            <td rowspan="2" align="center" >Unit&eacute;</td>
                            <td colspan="2" align="center" >R&eacute;f&eacute;rences </td>
                            <td rowspan="2" align="center" >Attendu <br />fin Projet </td>
							<td rowspan="2" align="center" >R&eacute;alis&eacute;</td>
							<td rowspan="2" align="center" >Variation<br /> (%) </td>
							<td colspan="2" align="center" rowspan="2">Responsable</td>
                            <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
                            <td rowspan="2" align="center" align="center">Actions</td>
                            <?php }?>
                            </tr>
                          <tr>
                            <td align="center">Ann&eacute;e</td>
					        <td align="center">Situation</td>
					        </tr>
                          </thead>
						   <?php $i=0; $p1="j"; do { ?>
						   <?php  if($p1!=$row_ind['id_indicateur_objectif_global']) {?>
                          <tr bgcolor="#ECF000">
                            <td colspan="<?php  echo 10; ?>" align="center" bgcolor="#D2E2B1"><div align="left"><strong> <u>
                                <?php  if($p1!=$row_ind['id_indicateur_objectif_global']) {echo $row_ind['code_iog'].". ".$row_ind['intitule_indicateur_objectif_global']; $i=0; }$p1=$row_ind['id_indicateur_objectif_global']; ?>
                            </u> </strong></div></td>
                          </tr>
                          <?php } ?>
                          <tr <?php /*if($i%2==0) echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#FFFFFF"';*/ $i=$i+1;?> >
                            <td ><div align="left"><span class="Style20">.&nbsp;</span><span class="Style22"><?php echo $row_ind['intitule_indicateur_cmr_og']; ?></span></div></td>
                            <td ><div align="center"><span class="Style22"><?php if(isset($row_ind['referentiel']) && isset($unite_ind_ref_array[$row_ind['referentiel']])) echo $unite_ind_ref_array[$row_ind['referentiel']];?></span></div></td>
                            <td ><div align="center" class="Style22"><strong><?php echo $row_ind['annee_reference']; ?></strong></div></td>
							
							
							
							
							 <td ><div align="center"><span class="Style22"><?php echo $row_ind['reference_cmr']." <em>".$row_ind['unite']."</em>"; ?></span></div></td>
							 <td><div align="center"><span class="Style22"><?php echo $row_ind['cible_cmr']." <em>".$row_ind['unite']."</em>"; ?></span></div></td>
                             <td><div align="center"><span class="Style23"><?php if(isset($row_ind['valeur_reelle']) && $row_ind['valeur_reelle']>0) echo $row_ind['valeur_reelle']." <em>".$row_ind['unite']."</em>"; elseif(isset($row_ind['valeur_reelle1']) && $row_ind['valeur_reelle1']>0) echo $row_ind['valeur_reelle1']." <em>".$row_ind['unite']."</em>"; ?></span></div></td>
                             <td><div align="center"><span class="Style22"><span class="Style29"><?php if(isset($row_ind['reference_cmr']) && $row_ind['reference_cmr']>0 && isset($row_ind['valeur_reelle'])) echo number_format((100*($row_ind['valeur_reelle']-$row_ind['reference_cmr'])/$row_ind['reference_cmr']), 2, ',', ' ')." <em>".$row_ind['unite']."</em>"; ?></span></span></div></td>
                             <td colspan="2"><span class="Style22">&nbsp; </span><span class="Style22">&nbsp; </span><span class="Style22">
                              <?php  
			$as = explode(",", $row_ind['responsable_collecte']); 	$lacteur=implode("','", $as);
			mysql_select_db($database_pdar_connexion, $pdar_connexion);
			$query_liste_acteur = "SELECT id_acteur, nom_acteur FROM acteur where id_acteur in ('$lacteur') ORDER BY categorie,code_acteur, nom_acteur";
			$liste_acteur   = mysql_query($query_liste_acteur , $pdar_connexion) or die(mysql_error());
			$row_liste_acteur   = mysql_fetch_assoc($liste_acteur );
			$totalRows_liste_acteur  = mysql_num_rows($liste_acteur );
           //affichage
		    if($totalRows_liste_acteur>0) { 	do {  echo $row_liste_acteur['nom_acteur']." - "; 	} while ($row_liste_acteur= mysql_fetch_assoc($liste_acteur)); mysql_free_result($liste_acteur);}
			else {echo "Aucun"; }
	  ?>
                             </span></td>
                            <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<4) {?>
                      <td><div align="center">
<a onclick="get_content('modal_content/realise_cmr_impact.php','<?php echo "&id_ind=".$row_ind['id_indicateur']; ?>','modal-body_add',this.title,'iframe');" data-toggle="modal" href="#myModal_add"  title="Valeurs r&eacute;alis&eacute;es d'indicateur" class="thickbox" dir="">
                      <strong>Suivi</strong></a> </div></td>
                      <?php }?>
                          </tr>
                          <?php } while ($row_ind = mysql_fetch_assoc($ind)); mysql_free_result($ind); ?>
                          <?php } ?>
                        </table></td>
                      </tr>
                      <?php } while ($row_og = mysql_fetch_assoc($og)); mysql_free_result($og); ?>
                      <?php } ?>
                    </table>
<div class="clearfix"></div>

</div></div>

<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>

</body>
</html>