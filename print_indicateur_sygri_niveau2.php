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

 if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Suivi_SYGRI_2eme_Niveau.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Suivi_SYGRI_2eme_Niveau.rtf"); } 

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

//annee en cours
  if(isset($_GET['annee'])) $annee=$_GET['annee']; else $annee=date("Y");
//annee precedent
 $anneep=$annee-1;
 
 //cible unique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_indicateur_sygri_fida = " SELECT * FROM   liste_indicateur_sygri";
$liste_indicateur_sygri_fida  = mysql_query($query_liste_indicateur_sygri_fida , $pdar_connexion) or die(mysql_error());
$row_liste_indicateur_sygri_fida = mysql_fetch_assoc($liste_indicateur_sygri_fida);
$totalRows_liste_indicateur_sygri_fida  = mysql_num_rows($liste_indicateur_sygri_fida);
$liste_indicateur_sygri_array = array();
$liste_referentiel_sygri_array = array();
do{
$liste_indicateur_sygri_array[$row_liste_indicateur_sygri_fida["id_indicateur_sygri_fida"]] = $row_liste_indicateur_sygri_fida["intitule_indicateur_sygri_fida"];
$liste_referentiel_sygri_array[$row_liste_indicateur_sygri_fida["id_indicateur_sygri_fida"]] = $row_liste_indicateur_sygri_fida["referentiel"];

}while($row_liste_indicateur_sygri_fida = mysql_fetch_assoc($liste_indicateur_sygri_fida));

//$icosop=$row_cosop['id_cosop'];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_scomposante = "SELECT * FROM activite_projet WHERE niveau=1 and projet='".$_SESSION["clp_projet"]."' order by code";
$scomposante  = mysql_query($query_scomposante , $pdar_connexion) or die(mysql_error());
$row_scomposante  = mysql_fetch_assoc($scomposante);
$totalRows_scomposante  = mysql_num_rows($scomposante);
$cp_array = array();
if($totalRows_scomposante>0){
  do{ $cp_array[$row_scomposante["code"]]=$row_scomposante["intitule"]; } while($row_scomposante  = mysql_fetch_assoc($scomposante));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_scomposante = "SELECT * FROM activite_projet WHERE niveau=2 and projet='".$_SESSION["clp_projet"]."' order by code";
$scomposante  = mysql_query($query_scomposante , $pdar_connexion) or die(mysql_error());
$row_scomposante  = mysql_fetch_assoc($scomposante);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind FROM referentiel_indicateur";
$liste_ind_ref  = mysql_query($query_liste_ind_ref , $pdar_connexion) or die(mysql_error());
$row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref);
$totalRows_liste_ind_ref  = mysql_num_rows($liste_ind_ref);
$liste_ind_ref_array = array();
$unite_ind_ref_array = array();
do{  $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"]; $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];
}while($row_liste_ind_ref = mysql_fetch_assoc($liste_ind_ref));

//Suivi unique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_suivi_ind_ref = " SELECT referentiel_indicateur.id_ref_ind,referentiel_fiche_config.* FROM  referentiel_indicateur,referentiel_fiche_config  where id_ref_ind=referentiel and feuille<>'' ";
$suivi_ind_ref  = mysql_query($query_suivi_ind_ref , $pdar_connexion) or die(mysql_error());
$row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref);
$totalRows_suivi_ind_ref  = mysql_num_rows($suivi_ind_ref);
$suivi_ind_ref_array = array();
do{ $feuille=$row_suivi_ind_ref["feuille"]; $col=$row_suivi_ind_ref["colonne"];
//sur les feuilles
mysql_select_db($database_pdar_connexion, $pdar_connexion);
if($row_suivi_ind_ref["mode_calcul"]=="COMPTER")
$query_feuille = " SELECT COUNT($col) as nb, annee FROM  $feuille WHERE projet='".$_SESSION["clp_projet"]."' ";
elseif($row_suivi_ind_ref["mode_calcul"]=="MOYENNE")
$query_feuille = " SELECT AVG($col) as nb, annee FROM  $feuille WHERE projet='".$_SESSION["clp_projet"]."' ";
else
$query_feuille = " SELECT SUM($col) as nb, annee FROM  $feuille WHERE projet='".$_SESSION["clp_projet"]."' ";
$feuilles  = mysql_query($query_feuille , $pdar_connexion);
if(($feuilles)) {
$row_feuille = mysql_fetch_assoc($feuilles);
$totalRows_feuille  = mysql_num_rows($feuilles);
}
$feuille_array = array();
if(isset($totalRows_feuille) && $totalRows_feuille>0) {
do{
   $suivi_ind_ref_array[$row_suivi_ind_ref["id_ref_ind"]] = $row_feuille["nb"];
}while($row_feuille = mysql_fetch_assoc($feuilles));
}
}while($row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref));

//Suivi Somme Moyenne
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_suivi_ind_ref = " SELECT * FROM  referentiel_indicateur, soutien_indicateur_sygri2, calcul_indicateur_simple_ref  where id_ref_ind=referentiel and indicateur_ref=id_ref_ind and mode_calcul<>'Ratio'";
$suivi_ind_ref  = mysql_query($query_suivi_ind_ref , $pdar_connexion) or die(mysql_error());
$row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref);
$totalRows_suivi_ind_ref  = mysql_num_rows($suivi_ind_ref);

$suivi_somme_ind_ref_array = $suivi_moyenne_ind_ref_array = array();

do{ $ref=$row_suivi_ind_ref["indicateur_ref"]; $ind=explode(",",$row_suivi_ind_ref["indicateur_simple"]); $formule=$row_suivi_ind_ref["formule_indicateur_simple"];
if($formule=="Somme"){
 foreach($ind as $indicateur){
   if(isset($suivi_ind_ref_array[$indicateur])){
      //foreach($suivi_ind_ref_array as $indicateur_referentiel){
        if(isset($suivi_somme_ind_ref_array[$ref])) $suivi_somme_ind_ref_array[$ref]+=$suivi_ind_ref_array[$indicateur];
        else $suivi_somme_ind_ref_array[$ref]=$suivi_ind_ref_array[$indicateur];

      // }
   }
 }         }

if($formule=="Moyenne"){
 foreach($ind as $indicateur){
   if(isset($suivi_ind_ref_array[$indicateur])){
      //foreach($suivi_ind_ref_array as $indicateur_referentiel){
        if(isset($suivi_moyenne_ind_ref_array[$ref])) $suivi_moyenne_ind_ref_array[$ref]+=$suivi_ind_ref_array[$indicateur];
        else $suivi_moyenne_ind_ref_array[$ref]=$suivi_ind_ref_array[$indicateur];

       //}
   }
 }

if(isset($suivi_ind_ref_array[$indicateur])){
//foreach($suivi_ind_ref_array as $indicateur_referentiel){
      if((count($ind)-1)>0) $suivi_moyenne_ind_ref_array[$ref]=$suivi_moyenne_ind_ref_array[$ref]/(count($ind)-1); //}
       }      }

}while($row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref)); //print_r($suivi_moyenne_ind_ref_array); exit;


//Suivi Ratio
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_suivi_ind_ref = " SELECT * FROM  referentiel_indicateur, soutien_indicateur_sygri2, ratio_indicateur_ref  where id_ref_ind=referentiel and indicateur_ref=id_ref_ind and mode_calcul='Ratio'";
$suivi_ind_ref  = mysql_query($query_suivi_ind_ref , $pdar_connexion) or die(mysql_error());
$row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref);
$totalRows_suivi_ind_ref  = mysql_num_rows($suivi_ind_ref);

$liste_num_ratio_array = array();
$liste_deno_ratio_array = array();

do{
$ref=$row_suivi_ind_ref["indicateur_ref"]; $numerateur=$row_suivi_ind_ref["numerateur"]; $denominateur=$row_suivi_ind_ref["denominateur"]; $coef=$row_suivi_ind_ref["coefficient"];

$liste_num_ratio_array[$ref]=$numerateur;
$liste_deno_ratio_array[$ref]=$denominateur;

}while($row_suivi_ind_ref = mysql_fetch_assoc($suivi_ind_ref)); //print_r($cible_ind_ref_array); exit;

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
<h4 align="center">Indicateurs de 2<sup>&egrave;me</sup> niveau SYGRI en <?php echo $annee;?></h4>

<table border="1" align="center" cellspacing="0">
  <tr class="titrecorps2">
        <td align="center" width="50%">R&eacute;sultats </td>
        <td align="center">&nbsp;Objectifs DCP &nbsp;</td>
        <td align="center">&nbsp;Taux<br />pond&eacute;ration&nbsp;</td>
        <td align="center">R&eacute;alis&eacute; </td>
        <td align="center">%Exe</td>
        <td align="center">Bar&egrave;me</td>
        <td align="center">&nbsp;</td>
      </tr>
  <?php if($totalRows_scomposante>0) {$o2=0; $p111="j"; do { ?>
  <?php 
				    $id_scp=$row_scomposante['code'];
				    mysql_select_db($database_pdar_connexion, $pdar_connexion);
					
					//$query_ind = "SELECT id_indicateur_sygri_niveau2_projet,  intitule_indicateur_sygri2, cible, proportion, id_indicateur_soutien, soutien_indicateur_sygri2.referentiel, intitule_indicateur_soutien, code_ind_sygri2  FROM activite_projet, indicateur_sygri2_projet LEFT JOIN soutien_indicateur_sygri2 ON indicateur_sygri_niveau2=id_indicateur_sygri_niveau2_projet WHERE niveau=2 and sous_composante=code and sous_composante='$id_scp' order by code_ind_sygri2";
					
			$query_ind = "SELECT id_sygri, id_indicateur_sygri_niveau2_projet, cible, proportion, id_indicateur_soutien, soutien_indicateur_sygri2.referentiel, intitule_indicateur_soutien, code_ind_sygri2  FROM activite_projet, indicateur_sygri2_projet LEFT JOIN soutien_indicateur_sygri2 ON indicateur_sygri_niveau2=id_indicateur_sygri_niveau2_projet WHERE niveau=2 and indicateur_sygri2_projet.projet='".$_SESSION["clp_projet"]."' and sous_composante=code and sous_composante='$id_scp' order by code_ind_sygri2";
					$ind  = mysql_query($query_ind , $pdar_connexion) or die(mysql_error());
					$row_ind  = mysql_fetch_assoc($ind);
					$totalRows_ind  = mysql_num_rows($ind);				  
				  ?>
  <?php if($totalRows_ind>0) {$i=0; $p1="j"; ?>
  <?php  if($p111!=$row_scomposante['code']) {?>
      <tr <?php  echo 'bgcolor="#CCCCCC"'; ?>>
        <td colspan="7" align="center"><div align="left">
          <?php  if($p111!=substr($row_scomposante['code'],0,1)) {echo "<b>Composante ".substr($row_scomposante['code'],0,1).":</b> ".(isset($cp_array[substr($row_scomposante['code'],0,1)])?$cp_array[substr($row_scomposante['code'],0,1)]:"ND"); }$p111=substr($row_scomposante['code'],0,1); ?>
        </div></td>
      </tr>
      <?php } ?>
  <tr <?php if($o2%2==0) echo 'bgcolor="#D2E2B1"'; $o2=$o2+1;?>>
    <td colspan="7" valign="top"><span class="Style51"><?php echo "<b>Sous composante ".$row_scomposante['code'].":</b> ".$row_scomposante['intitule']; ?></span></td>
  </tr>
      <?php do { ?>
      <?php								//semestre courant
						$indics=$row_ind['id_indicateur_soutien'];
						$indicsygri=$row_ind['id_indicateur_sygri_niveau2_projet'];
						mysql_select_db($database_pdar_connexion, $pdar_connexion);
					   $query_resultat_soutien = "SELECT id_suivi_soutien_indicateur, resultat FROM suivi_indicateur_soutien where annee='$annee' and indicateur_soutien='$indics'";
						$resultat_soutien  = mysql_query($query_resultat_soutien , $pdar_connexion) or die(mysql_error());
						$row_resultat_soutien = mysql_fetch_assoc($resultat_soutien );
						$totalRows_resultat_soutien = mysql_num_rows($resultat_soutien );
						
						mysql_select_db($database_pdar_connexion, $pdar_connexion);
					  $query_tresultat_soutien = " SELECT sum((if(resultat>cible,proportion/100, (resultat / cible)*proportion/100) ) ) AS rtotal
FROM soutien_indicateur_sygri2, suivi_indicateur_soutien
WHERE annee =$annee AND indicateur_soutien = id_indicateur_soutien AND indicateur_sygri_niveau2 =$indicsygri";
						$tresultat_soutien  = mysql_query($query_tresultat_soutien , $pdar_connexion) or die(mysql_error());
						$row_tresultat_soutien = mysql_fetch_assoc($tresultat_soutien );
						$totalRows_tresultat_soutien = mysql_num_rows($tresultat_soutien );
						?>
      <?php  if($p1!=$row_ind['code_ind_sygri2']) {?>
      <tr bgcolor="#666633">
        <td colspan="3" align="center" bgcolor="#666633"><div align="left" class="Style23">
          <?php  if($p1!=$row_ind['code_ind_sygri2'] && isset($liste_indicateur_sygri_array[$row_ind["id_sygri"]])) {echo $liste_indicateur_sygri_array[$row_ind["id_sygri"]]; $i=0; }$p1=$row_ind['code_ind_sygri2']; ?>
        </div></td>
        <td align="center" bgcolor="#666633" class="Style53">&nbsp;</td>
        <td align="center" bgcolor="#666633"><span class="Style53"><?php echo number_format(100*$row_tresultat_soutien['rtotal'], 0, ',', ' '); ?></span></td>
        <td align="center" bgcolor="#666633"><span class="Style53">
          <?php if(((100*$row_tresultat_soutien['rtotal'])/16.5)<1 && ((100*$row_tresultat_soutien['rtotal'])/16.5)>0) echo 1; else echo number_format((100*$row_tresultat_soutien['rtotal'])/16.5, 0, ',', ' '); ?>
        </span></td>
        <td align="center" bgcolor="#666633">&nbsp;</td>
      </tr>
      <?php } ?>
      <tr <?php if($i%2==0) echo 'bgcolor="#F9F9F7"'; $i=$i+1;?>>
        <td width="50%"><div align="left" class="Style51"><?php echo $row_ind['intitule_indicateur_soutien']; ?></div>
              <div align="left" class="Style51"> </div></td>
        <td ><div align="center"><span class="Style51"><?php echo $row_ind['cible']; ?>
                    <?php if(isset($unite_ind_ref_array[$row_ind["referentiel"]])) echo " (".$unite_ind_ref_array[$row_ind["referentiel"]].")"; ?>
        </span></div></td>
        <td ><div align="center"><span class="Style51"><?php echo $row_ind['proportion']; ?>%</span></div></td>
        <td ><div align="center"><strong><strong><span class="Style16">
          <?php if(isset($suivi_ind_ref_array[$row_ind["referentiel"]])) { echo $suivi_ind_ref_array[$row_ind["referentiel"]]; }elseif(isset($suivi_somme_ind_ref_array[$row_ind["referentiel"]])){ echo $suivi_somme_ind_ref_array[$row_ind["referentiel"]]; } ?>
          </span><strong><span class="Style16">

          </span></strong><span class="Style16"> </span></strong></strong></div></td>
        <td ><div align="center">
          <?php
							   if(isset($suivi_ind_ref_array[$row_ind["referentiel"]]) && $row_ind['cible']>0)
							   { $tex=100*$suivi_ind_ref_array[$row_ind["referentiel"]]/$row_ind['cible'];}
							    elseif(isset($suivi_ind_ref_array[$row_ind["referentiel"]]) && $suivi_ind_ref_array[$row_ind["referentiel"]]>$row_ind['cible'])
							   { $tex=100;}
							   if(isset($suivi_ind_ref_array[$row_ind["referentiel"]])) {if($tex>100) $tex=100; echo number_format($tex, 0, ',', ' ')." %";} ?>
        </div></td>
        <td >&nbsp;</td>
        <td align="center"></td>
      </tr>
      <?php } while ($row_ind = mysql_fetch_assoc($ind)); ?>
      <tr>
        <td colspan="7"><div align="center" class="Style2">
          <?php if(!$totalRows_ind>0) echo "Aucun indicateur enregistr&eacute;: ";?>
        </div></td>
      </tr>
  <?php } ?>
  <?php } while ($row_scomposante = mysql_fetch_assoc($scomposante)); ?>
  <?php } else {?>
  <tr>
    <td colspan="7" nowrap="nowrap"><div align="center"><em><strong>Aucune composante enregistr&eacute;e </strong></em></div></td>
  </tr>
  <?php } ?>
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