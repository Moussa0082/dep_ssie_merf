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

 if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Liste_Indicateurs_PNF.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Liste_Indicateurs_PNF.rtf"); } ?>
<?php
 

$editFormAction = $_SERVER['PHP_SELF'];    $projet=$_SESSION["clp_projet"];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$an1p=$_SESSION["annee_debut_projet"];
$an2p=$_SESSION["annee_fin_projet"];

// Partie objectif specifique
// objectif specifique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur_ref = "SELECT * FROM  referentiel_indicateur ORDER BY  type_ref_ind desc, code_ref_ind ASC";
$liste_indicateur_ref  = mysql_query($query_liste_indicateur_ref , $pdar_connexion) or die(mysql_error());
$row_liste_indicateur_ref  = mysql_fetch_assoc($liste_indicateur_ref);
$totalRows_liste_indicateur_ref  = mysql_num_rows($liste_indicateur_ref);				  


/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind FROM referentiel_indicateur";
$liste_ind_ref  = mysql_query($query_liste_ind_ref , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref);
$totalRows_liste_ind_ref  = mysql_num_rows($liste_ind_ref);
$liste_ind_ref_array = array();
$unite_ind_ref_array = array();
do{  $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"]; 
$unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
}while($row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref));*/


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_projet = "SELECT * FROM projet order by code_projet";
$liste_projet = mysql_query($query_liste_projet, $pdar_connexion) or die(mysql_error());
	$tableauProjet=array();
	while($ligne=mysql_fetch_assoc($liste_projet)){$tableauProjet[]=$ligne['id_projet']."<>".$ligne['sigle_projet'];}
	mysql_free_result($liste_projet);


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur_calcul = "SELECT indicateur_ref, id_ref_ind, code_ref_ind, intitule_ref_ind FROM referentiel_indicateur, calcul_indicateur_simple_ref
WHERE FIND_IN_SET( id_ref_ind, indicateur_simple ) and mode_calcul = 'Unique' ORDER BY indicateur_ref";
$liste_indicateur_calcul = mysql_query($query_liste_indicateur_calcul, $pdar_connexion) or die(mysql_error());
$row_liste_indicateur_calcul = mysql_fetch_assoc($liste_indicateur_calcul);
$totalRows_liste_indicateur_calcul = mysql_num_rows($liste_indicateur_calcul);

$liste_indicateur_simple_array=array();
if($totalRows_liste_indicateur_calcul>0){
do{ $liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]=(isset($liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']]))?$liste_indicateur_simple_array[$row_liste_indicateur_calcul['indicateur_ref']].$row_liste_indicateur_calcul['code_ref_ind'].",":$row_liste_indicateur_calcul['code_ref_ind'].",";}while($row_liste_indicateur_calcul = mysql_fetch_assoc($liste_indicateur_calcul)); }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ratio = "SELECT indicateur_ref, numerateur, denominateur FROM ratio_indicateur_ref order by indicateur_ref";
$liste_ind_ratio  = mysql_query($query_liste_ind_ratio , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ratio = mysql_fetch_assoc($liste_ind_ratio);
$totalRows_liste_ind_ratio  = mysql_num_rows($liste_ind_ratio);
$liste_num_ratio_array = array();
$liste_deno_ratio_array = array();
do{ 
 $liste_num_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["numerateur"];
  $liste_deno_ratio_array[$row_liste_ind_ratio["indicateur_ref"]] = $row_liste_ind_ratio["denominateur"];
}while($row_liste_ind_ratio = mysql_fetch_assoc($liste_ind_ratio));


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_code_ref = "SELECT code_ref_ind, id_ref_ind FROM referentiel_indicateur order by code_ref_ind";
$liste_code_ref  = mysql_query($query_liste_code_ref , $pdar_connexion) or die(mysql_error());
$row_liste_code_ref = mysql_fetch_assoc($liste_code_ref);
$totalRows_liste_code_ref  = mysql_num_rows($liste_code_ref);
$liste_code_ref_array = array();
do{ 
 $liste_code_ref_array[$row_liste_code_ref["id_ref_ind"]] = $row_liste_code_ref["code_ref_ind"];
}while($row_liste_code_ref = mysql_fetch_assoc($liste_code_ref));

//effet cosop
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_effet_cosop = "SELECT referentiel, valeur_cible FROM indicateur_os_cosop";
$liste_effet_cosop  = mysql_query($query_liste_effet_cosop , $pdar_connexion) or die(mysql_error());
$row_liste_effet_cosop = mysql_fetch_assoc($liste_effet_cosop);
$totalRows_liste_effet_cosop  = mysql_num_rows($liste_effet_cosop);
$liste_effet_cosop_array = array();
do{  $liste_effet_cosop_array[$row_liste_effet_cosop["referentiel"]] = $row_liste_effet_cosop["valeur_cible"]; 
}while($row_liste_effet_cosop = mysql_fetch_assoc($liste_effet_cosop));

//produit cosop
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_produit_cosop = "SELECT referentiel, valeur_cible FROM indicateur_resultat_cosop";
$liste_produit_cosop  = mysql_query($query_liste_produit_cosop , $pdar_connexion) or die(mysql_error());
$row_liste_produit_cosop = mysql_fetch_assoc($liste_produit_cosop);
$totalRows_liste_produit_cosop  = mysql_num_rows($liste_produit_cosop);
$liste_produit_cosop_array = array();
do{  $liste_produit_cosop_array[$row_liste_produit_cosop["referentiel"]] = $row_liste_produit_cosop["valeur_cible"]; 
}while($row_liste_produit_cosop = mysql_fetch_assoc($liste_produit_cosop));

//produit I3N
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_produit_i3n = "SELECT referentiel, id_indicateur_i3n FROM indicateur_i3n";
$liste_produit_i3n  = mysql_query($query_liste_produit_i3n , $pdar_connexion) or die(mysql_error());
$row_liste_produit_i3n = mysql_fetch_assoc($liste_produit_i3n);
$totalRows_liste_produit_i3n  = mysql_num_rows($liste_produit_i3n);
$liste_produit_i3n_array = array();
do{  $liste_produit_i3n_array[$row_liste_produit_i3n["referentiel"]] = $row_liste_produit_i3n["id_indicateur_i3n"]; 
}while($row_liste_produit_i3n = mysql_fetch_assoc($liste_produit_i3n));


//Niveau 3 SYGRI
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_impact_sygri = "SELECT referentiel, id_indicateur_sygri_fida FROM indicateur_sygri_fida";
$liste_impact_sygri  = mysql_query($query_liste_impact_sygri , $pdar_connexion) or die(mysql_error());
$row_liste_impact_sygri = mysql_fetch_assoc($liste_impact_sygri);
$totalRows_liste_impact_sygri  = mysql_num_rows($liste_impact_sygri);
$liste_impact_sygri_array = array();
do{  $liste_impact_sygri_array[$row_liste_impact_sygri["referentiel"]] = $row_liste_impact_sygri["id_indicateur_sygri_fida"]; 
}while($row_liste_impact_sygri = mysql_fetch_assoc($liste_impact_sygri));

//Niveau 2 SYGRI
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_effet_sygri = "SELECT referentiel, id_indicateur_sygri_niveau2_projet FROM indicateur_sygri2_projet";
$liste_effet_sygri  = mysql_query($query_liste_effet_sygri , $pdar_connexion) or die(mysql_error());
$row_liste_effet_sygri = mysql_fetch_assoc($liste_effet_sygri);
$totalRows_liste_effet_sygri  = mysql_num_rows($liste_effet_sygri);
$liste_effet_sygri_array = array();
do{  $liste_effet_sygri_array[$row_liste_effet_sygri["referentiel"]] = $row_liste_effet_sygri["id_indicateur_sygri_niveau2_projet"]; 
}while($row_liste_effet_sygri = mysql_fetch_assoc($liste_effet_sygri));

//Niveau 1 SYGRI
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_produit_sygri = "SELECT referentiel, id_indicateur_sygri_niveau1_projet FROM indicateur_sygri1_projet";
$liste_produit_sygri  = mysql_query($query_liste_produit_sygri , $pdar_connexion) or die(mysql_error());
$row_liste_produit_sygri = mysql_fetch_assoc($liste_produit_sygri);
$totalRows_liste_produit_sygri  = mysql_num_rows($liste_produit_sygri);
$liste_produit_sygri_array = array();
do{$liste_produit_sygri_array[$row_liste_produit_sygri["referentiel"]] = $row_liste_produit_sygri["id_indicateur_sygri_niveau1_projet"]; 
}while($row_liste_produit_sygri = mysql_fetch_assoc($liste_produit_sygri));

//Impact projet
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_impact_projet = "SELECT projet, referentiel, id_indicateur FROM indicateur_objectif_global_cmr, indicateur_but_projet, but_projet where id_indicateur_but_projet=indicateur_og and but_projet=id_but_projet order by referentiel";
$liste_impact_projet  = mysql_query($query_liste_impact_projet , $pdar_connexion) or die(mysql_error());
$row_liste_impact_projet = mysql_fetch_assoc($liste_impact_projet);
$totalRows_liste_impact_projet  = mysql_num_rows($liste_impact_projet);
$liste_impact_projet_array = array();
do{  $liste_impact_projet_array[$row_liste_impact_projet["projet"]][$row_liste_impact_projet["referentiel"]] = $row_liste_impact_projet["id_indicateur"]; 
}while($row_liste_impact_projet = mysql_fetch_assoc($liste_impact_projet));

//Niveau 2 SYGRI
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_effet_projet = "SELECT projet, referentiel, id_indicateur FROM os_projet, indicateur_os_projet, indicateur_objectif_specifique_cmr where  id_indicateur_os_projet=indicateur_os and os_projet=id_os_projet
 union SELECT projet, referentiel, id_indicateur FROM indicateur_resultat_cmr, indicateur_resultat, resultat  where id_indicateur_resultat=indicateur_res and id_resultat=resultat";
$liste_effet_projet  = mysql_query($query_liste_effet_projet , $pdar_connexion) or die(mysql_error());
$row_liste_effet_projet = mysql_fetch_assoc($liste_effet_projet);
$totalRows_liste_effet_projet  = mysql_num_rows($liste_effet_projet);
$liste_effet_projet_array = array();
do{  $liste_effet_projet_array[$row_liste_effet_projet["projet"]][$row_liste_effet_projet["referentiel"]] = $row_liste_effet_projet["id_indicateur"]; 
}while($row_liste_effet_projet = mysql_fetch_assoc($liste_effet_projet));

//Niveau 1 SYGRI
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_produit_projet = "SELECT projet, referentiel, id_indicateur FROM indicateur_produit, indicateur_produit_cmr, produit, resultat
					 
					  where  id_indicateur_produit=indicateur_prd and produit=id_produit  and id_resultat=effet";
$liste_produit_projet  = mysql_query($query_liste_produit_projet , $pdar_connexion) or die(mysql_error());
$row_liste_produit_projet = mysql_fetch_assoc($liste_produit_projet);
$totalRows_liste_produit_projet  = mysql_num_rows($liste_produit_projet);
$liste_produit_projet_array = array();
do{$liste_produit_projet_array[$row_liste_produit_projet["projet"]][$row_liste_produit_projet["referentiel"]] = $row_liste_produit_projet["id_indicateur"]; 
}while($row_liste_produit_projet = mysql_fetch_assoc($liste_produit_projet));    */
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
  <div class="r_float" style="margin-right: 10px;"><a href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
  <div class="r_float" style="margin-right: 10px;"><a href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="clear h0">
<?php } ?></div>

<br /><table align="center" width="100%" border="0" cellpadding="0" cellspacing="0" >
  <tr>
    <td valign="top" bgcolor="#D2E2B1"><div align="center"><strong>Liste des Indicateurs r&eacute;&eacute;rentiels du <?php if(isset($_SESSION["clp_projet"])){ ?><b><?php //$_SESSION["clp_projet_nom"].' '.
echo $_SESSION["clp_projet_sigle"].' ';  mysql_select_db($database_connect_transfert, $connect_transfert);
$mySqlQuery = "SELECT * FROM ".$database_connect_prefix."ugl where code_ugl='".$_SESSION['clp_structure']."'";
$qh = mysql_query_ruche($mySqlQuery, $connect_transfert) or die(mysql_error_show_message(mysql_error()));
$data = mysql_fetch_assoc($qh);
$totalRows_clp = mysql_num_rows($qh);

if(isset($data["nom_ugl"])) echo "&nbsp;<span style='color:#FF9900;'>( ".$data["abrege_ugl"]." )</span>"; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?>&nbsp;&nbsp;<u>&eacute;diter le</u> <span class="Style7 Style1"><u><strong><?php echo date("d/m/Y"); ?></strong></u></span></strong></div></td>
  </tr>
</table>

<table border="0" align="center"  cellpadding="0" cellspacing="0" >
  <tr>
    <td><table width="100%" border="1" cellspacing="0">

      <?php if($totalRows_liste_indicateur_ref>0) { ?>
      <tr class="titrecorps2" >
        <td rowspan="1" >Code</td>
        <td rowspan="1" ><span class="Style22">Liste des indicateurs </span></td>
        <td rowspan="1" ><span class="Style22">Unit&eacute;</span></td>
        <td rowspan="1" ><span class="Style22">Type</span></td>
        <td rowspan="1" ><span class="Style22">Mode de calcul </span></td>
        <td rowspan="1" bgcolor="#FFFFFF" >&nbsp;</td>
        <?php foreach($tableauProjet as $vprojet){
		 $aprojet = explode('<>',$vprojet);
		 $iprojet = $aprojet[0]; 
		?>
		
        <td <?php if(isset($iprojet) && $iprojet==$projet) echo "style=\"background-color:#FFFF00; color:#000000\""; ?>><div align="center" class="Style31">
            <?php 
					   
						 echo $aprojet[1]; ?>
        </div></td>
        <?php } ?>
        <td rowspan="1"><strong>COSOP</strong></td>
        <td rowspan="1">SYGRI</td>
        <td rowspan="1">I3N</td>
        <td rowspan="1" bgcolor="#FFFFFF">&nbsp;</td>
        </tr>
      <?php $i=0; $j=0; $p1="j"; $p11="k"; do { $tcic=0; ?>
      <?php  if($p1!=$row_liste_indicateur_ref['volet_ref']) {?>
      <tr bgcolor="#ECF000">
        <td colspan="10" align="center" bgcolor="#D2E2B1" ><div align="left" class="Style22">
            <?php  if($p1!=$row_liste_indicateur_ref['volet_ref']) {if(isset($row_liste_indicateur_ref['code_ref_volet'])) echo $row_liste_indicateur_ref['code_ref_volet'].": ".$row_liste_indicateur_ref['intitule_ref_volet']; $i=0; }$p1=$row_liste_indicateur_ref['volet_ref']; ?>
        </div></td>
      </tr>
      <?php } ?>
      <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#FFFFFF"'; $i=$i+1;?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='<?php if($i%2!=0) echo '#FFFFFF';?>';">
        <td ><div align="center"><span class="Style46"><?php echo $row_liste_indicateur_ref['code_ref_ind']; ?></span></div></td>
        <td ><div align="left"><span class="Style46"><?php echo $row_liste_indicateur_ref['intitule_ref_ind']; ?></span></div></td>
        <td ><div align="center"><?php echo $row_liste_indicateur_ref['unite']; ?></div></td>
        <td ><div align="center">
          <?php if(isset($row_liste_indicateur_ref['type_ref_ind']) &&  $row_liste_indicateur_ref['type_ref_ind']==1) echo "produit"; elseif(isset($row_liste_indicateur_ref['type_ref_ind']) &&  $row_liste_indicateur_ref['type_ref_ind']==2) echo "Effet"; elseif(isset($row_liste_indicateur_ref['type_ref_ind']) &&  $row_liste_indicateur_ref['type_ref_ind']==3) echo "Impact";?>
        </div></td>
        <td ><?php if(isset($row_liste_indicateur_ref['mode_calcul']) && $row_liste_indicateur_ref['mode_calcul']=="Unique") { echo "Unique"; } elseif (isset($row_liste_indicateur_ref['mode_calcul']) && $row_liste_indicateur_ref['mode_calcul']=="Ratio") {?>
          <?php echo $row_liste_indicateur_ref['mode_calcul']; 
					  if(isset($liste_num_ratio_array[$row_liste_indicateur_ref['id_ref_ind']]) 
					  && isset($liste_deno_ratio_array[$row_liste_indicateur_ref['id_ref_ind']])
					  && isset($liste_code_ref_array[$liste_num_ratio_array[$row_liste_indicateur_ref['id_ref_ind']]])
					  && isset($liste_code_ref_array[$liste_deno_ratio_array[$row_liste_indicateur_ref['id_ref_ind']]]))
					   echo " (".$liste_code_ref_array[$liste_num_ratio_array[$row_liste_indicateur_ref['id_ref_ind']]]." / ".$liste_code_ref_array[$liste_deno_ratio_array[$row_liste_indicateur_ref['id_ref_ind']]].")";?>
          <?php } else {?>
          <?php echo $row_liste_indicateur_ref['mode_calcul']; ?>
          <?php if(isset($liste_indicateur_simple_array[$row_liste_indicateur_ref['id_ref_ind']])) echo " (".substr($liste_indicateur_simple_array[$row_liste_indicateur_ref['id_ref_ind']],0,strlen($liste_indicateur_simple_array[$row_liste_indicateur_ref['id_ref_ind']])-1).")"; ?>
          
          <?php }?></td>
        <td >&nbsp;</td>
        <?php foreach($tableauProjet as $vprojet){
		 $aprojet = explode('<>',$vprojet);
		 $iprojet = $aprojet[0]; 
		?>
        <td ><div align="center">
          <?php if(isset($row_liste_indicateur_ref['type_ref_ind']) &&  $row_liste_indicateur_ref['type_ref_ind']==3 && isset($liste_impact_projet_array[$iprojet][$row_liste_indicateur_ref["id_ref_ind"]])) echo "X"; elseif(isset($row_liste_indicateur_ref['type_ref_ind']) &&  $row_liste_indicateur_ref['type_ref_ind']==2 && isset($liste_effet_projet_array[$iprojet][$row_liste_indicateur_ref["id_ref_ind"]])) echo "X";  elseif(isset($row_liste_indicateur_ref['type_ref_ind']) &&  $row_liste_indicateur_ref['type_ref_ind']==1 && isset($liste_produit_projet_array[$iprojet][$row_liste_indicateur_ref["id_ref_ind"]])) echo "X";?>
        </div></td>
        <?php } ?>
        <td rowspan="1"><div align="center">
		<?php if(isset($row_liste_indicateur_ref['type_ref_ind']) &&  $row_liste_indicateur_ref['type_ref_ind']==2 && isset($liste_effet_cosop_array[$row_liste_indicateur_ref["id_ref_ind"]])) echo "X"; elseif(isset($row_liste_indicateur_ref['type_ref_ind']) &&  $row_liste_indicateur_ref['type_ref_ind']==1 && isset($liste_produit_cosop_array[$row_liste_indicateur_ref["id_ref_ind"]])) echo "X";?>
		</div></td>
        <td rowspan="1"><div align="center">
          <?php if(isset($row_liste_indicateur_ref['type_ref_ind']) &&  $row_liste_indicateur_ref['type_ref_ind']==3 && isset($liste_impact_sygri_array[$row_liste_indicateur_ref["id_ref_ind"]])) echo "III"; elseif(isset($row_liste_indicateur_ref['type_ref_ind']) &&  $row_liste_indicateur_ref['type_ref_ind']==2 && isset($liste_effet_sygri_array[$row_liste_indicateur_ref["id_ref_ind"]])) echo "II";  elseif(isset($row_liste_indicateur_ref['type_ref_ind']) &&  $row_liste_indicateur_ref['type_ref_ind']==1 && isset($liste_produit_sygri_array[$row_liste_indicateur_ref["id_ref_ind"]])) echo "I";?>
        </div></td>
        <td rowspan="1"><div align="center">
          <div align="center">
            <?php if(isset($row_liste_indicateur_ref['type_ref_ind']) &&  $row_liste_indicateur_ref['type_ref_ind']==1 && isset($liste_produit_i3n_array[$row_liste_indicateur_ref["id_ref_ind"]])) echo "X";?>
          </div>
        </div></td>
        <td rowspan="1"><div align="center"></div></td>
        </tr>
      <?php } while ($row_liste_indicateur_ref = mysql_fetch_assoc($liste_indicateur_ref)); mysql_free_result($liste_indicateur_ref);?>
      <?php } ?>
    </table></td>
  </tr>
</table>
<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div> <?php include_once("modal_add.php"); ?>
    <?php include_once("includes/footer.php"); ?>
</div>
</body>
</html>