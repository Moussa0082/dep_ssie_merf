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
header("content-disposition: attachment;filename=Recommandation_mission.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Recommandation_mission.doc"); }
 ?>
<?php


$editFormAction = $_SERVER['PHP_SELF'];
/*if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}*/

/*$id_m=$_GET['id'];
                //projet='".$_SESSION["clp_projet"]."' and
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_ms = "SELECT * FROM ".$database_connect_prefix."mission_supervision   where   id_mission='$id_m'";
$edit_ms = mysql_query($query_edit_ms, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_ms = mysql_fetch_assoc($edit_ms);
$totalRows_edit_ms = mysql_num_rows($edit_ms);*/

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rubrique = "SELECT * FROM ".$database_connect_prefix."rubrique_projet order by code_rub";
$liste_rubrique = mysql_query($query_liste_rubrique, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_rubrique = mysql_fetch_assoc($liste_rubrique);
$totalRows_liste_rubrique = mysql_num_rows($liste_rubrique);
$tableau_rubrique = array();
if($totalRows_liste_rubrique>0){  do{ $tableau_rubrique[$row_liste_rubrique["code_rub"]]=$row_liste_rubrique["nom_rubrique"]; }while($row_liste_rubrique = mysql_fetch_assoc($liste_rubrique));
}*/

/*  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $query_liste_volet = "SELECT * FROM ".$database_connect_prefix."activite_projet WHERE ".$_SESSION["clp_where"]." and niveau=1 ORDER BY code ASC";
  $liste_volet  = mysql_query($query_liste_volet , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
  $row_liste_volet  = mysql_fetch_assoc($liste_volet);
  $totalRows_liste_volet  = mysql_num_rows($liste_volet);
  $tableau_volet = array();
if($totalRows_liste_volet>0){  do{ $tableau_volet[$row_liste_volet["code"]]=$row_liste_volet["intitule"]; }while($row_liste_volet = mysql_fetch_assoc($liste_volet));
}  */

$query_liste_code_ref = "SELECT * FROM ".$database_connect_prefix."categorie_beneficiaire order by code_cat";
		try{
    $liste_code_ref = $pdar_connexion->prepare($query_liste_code_ref);
    $liste_code_ref->execute();
    $row_liste_code_ref = $liste_code_ref ->fetchAll();
    $totalRows_liste_code_ref = $liste_code_ref->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$liste_rub_array = array();
$code_rub_array = array();
if($totalRows_liste_code_ref>0){ foreach($row_liste_code_ref as $row_liste_code_ref){
  $liste_rub_array[$row_liste_code_ref["id_categorie"]] = $row_liste_code_ref["categorie"];
  $code_rub_array[$row_liste_code_ref["id_categorie"]] = $row_liste_code_ref["code_cat"];
} }

/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_respo_ugl = "SELECT id_personnel, fonction FROM ".$database_connect_prefix."personnel";
$liste_respo_ugl  = mysql_query($query_liste_respo_ugl , $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_respo_ugl  = mysql_fetch_assoc($liste_respo_ugl );
$totalRows_liste_respo_ugl  = mysql_num_rows($liste_respo_ugl );
$respo_ugl=array();
if($totalRows_liste_respo_ugl>0){ do{ $respo_ugl[$row_liste_respo_ugl["id_personnel"]]=$row_liste_respo_ugl["fonction"];  }while($row_liste_respo_ugl  = mysql_fetch_assoc($liste_respo_ugl ));  }*/
//SYGRI


//Suivi CMR produit
$query_liste_realise = "SELECT ireferentiel, sum(valeur_realise) as valeur_realise, annee FROM realise_cmr_produit group by annee, ireferentiel"; 
try{
    $liste_realise = $pdar_connexion->prepare($query_liste_realise);
    $liste_realise->execute();
    $row_liste_realise = $liste_realise ->fetchAll();
    $totalRows_liste_realise = $liste_realise->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$realise_arrayp= array();
if($totalRows_liste_realise>0){ 
foreach($row_liste_realise as $row_liste_realise){ $realise_arrayp[$row_liste_realise["ireferentiel"]][$row_liste_realise["annee"]]=$row_liste_realise["valeur_realise"];}
}

//Suivi indicateur PTBA produit
$query_liste_realise_ptba = "SELECT indicateur_cr AS ireferentiel, SUM( valeur_suivi ) AS valeur_realise, YEAR( date_suivi ) AS annee
FROM suivi_indicateur_tache, indicateur_tache, ptba WHERE id_ptba = id_activite AND id_indicateur_tache = indicateur GROUP BY annee, ireferentiel"; 
try{
    $liste_realise_ptba = $pdar_connexion->prepare($query_liste_realise_ptba);
    $liste_realise_ptba->execute();
    $row_liste_realise_ptba = $liste_realise_ptba ->fetchAll();
    $totalRows_liste_realise_ptba = $liste_realise_ptba->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
$realise_array_ptba= array();
if($totalRows_liste_realise_ptba>0){ 
foreach($row_liste_realise_ptba as $row_liste_realise_ptba){ $realise_array_ptba[$row_liste_realise_ptba["ireferentiel"]][$row_liste_realise_ptba["annee"]]=$row_liste_realise_ptba["valeur_realise"];}}

/*
//Fiche de collecte
$query_liste_ind_ref = "SELECT id_ref_ind, intitule_ref_ind, unite, type_ref_ind, requete_sql_an FROM referentiel_indicateur, indicateur_resultat_cmr where id_ref_ind=referentiel";
try{
    $liste_ind_ref = $pdar_connexion->prepare($query_liste_ind_ref);
    $liste_ind_ref->execute();
    $row_liste_ind_ref = $liste_ind_ref ->fetchAll();
    $totalRows_liste_ind_ref = $liste_ind_ref->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$liste_ind_ref_array = array();
$unite_ind_ref_array = array();
foreach($row_liste_ind_ref as $row_liste_ind_ref){
 $liste_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]] = $row_liste_ind_ref["intitule_ref_ind"];
  $unite_ind_ref_array[$row_liste_ind_ref["id_ref_ind"]]=$row_liste_ind_ref["unite"];

	$query_liste_val_ref = $row_liste_ind_ref["requete_sql_an"];
	$query_liste_val_ref =str_replace("crp", "", $query_liste_val_ref);
	try{
    $liste_val_ref = $pdar_connexion->prepare($query_liste_ind_ref);
    $liste_val_ref->execute();
	$suivief_an_ref_array = array();
	if ($liste_val_ref)
	{// Traitement de l'erreur
	    $row_liste_val_ref = $liste_val_ref ->fetchAll();
    $totalRows_liste_val_ref = $liste_val_ref->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

	if($totalRows_liste_val_ref>0){ foreach($row_liste_val_ref as $row_liste_val_ref){
	$suivief_an_ref_array[$row_liste_ind_ref["id_ref_ind"]][$row_liste_val_ref["annee"]]=$row_liste_val_ref["val"]; 
	 } }
	 }
  
  }*/

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
<?php } ?>
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
.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
</style>
<div class="contenu">
  <div id="msg" align="center" class="red"></div>

<?php if(!isset($_GET["down"])){  ?>
<div class="well well-sm r_float"><div class="r_float"><a href="./s_suivi_resultat.php" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."?down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."?down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div></div>
<div class="clear h0">&nbsp;</div>
<?php } else { ?>

<center><?php //include "./includes/print_header.php"; ?></center>

<?php } ?>


<div class="well well-sm"><strong>Liste des indicateurs de type bénéficiaires</strong></div>
    
        <?php
		$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."referentiel_indicateur where beneficiaire=1 and categorie!=0 and type_ref_ind=1 ORDER BY categorie asc";
		try{
    $liste_rec = $pdar_connexion->prepare($query_liste_rec);
    $liste_rec->execute();
    $row_liste_rec = $liste_rec ->fetchAll();
    $totalRows_liste_rec = $liste_rec->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
		?>
<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive">
            <?php if($totalRows_liste_rec>0) {$total_an=$i=0; $t=0; $p2=$p1="j"; ?>
            <thead>
            <tr>
              <th rowspan="2" align="center" nowrap="nowrap">N&deg;</th>
              <th rowspan="2" align="center"><strong>Indicateurs</strong></th>
              <th rowspan="2"><div align="left"><strong>Unit&eacute;</strong></div></th>
              <th colspan=" <?php echo (1+$_SESSION["annee_fin_projet"]-$_SESSION["annee_debut_projet"]); ?>"><div align="center"><strong>R&eacute;sultats annuels </strong></div></th>
            </tr>
            <tr>
			 <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
                    
					    <th width="5%"><?php echo $i; ?></th>
                      <?php } ?>
            
              </tr>
            </thead>
            <?php foreach($row_liste_rec as $row_liste_rec){  ?>
            <?php  if($p2!=$row_liste_rec['categorie']) {?>
            <tr bgcolor="#BED694">
              <td bgcolor="#BED694" colspan="<?php echo (5+$_SESSION["annee_fin_projet"]-$_SESSION["annee_debut_projet"]); ?>" align="center"><div align="left" style="background-color: #BED694; "><strong>
                <?php  if($p2!=$row_liste_rec['categorie']) {
                  if(isset($liste_rub_array[$row_liste_rec["categorie"]]))  echo $liste_rub_array[$row_liste_rec['categorie']]; else echo "N/A";
				  }$p2=$row_liste_rec['categorie']; ?>
              </strong></div></td>
            </tr>
			   <?php } ?>
			
            <tr>
              <td><strong><?php echo $row_liste_rec['code_ref_ind']; ?></strong></td>
              <td><div align="left"><strong><?php echo $row_liste_rec['intitule_ref_ind']; ?></strong></div></td>
              <td><div align="left" class="Style4"><?php echo $row_liste_rec['unite']; ?></div></td>
             
			   <?php for($i=$_SESSION["annee_debut_projet"]; $i<=$_SESSION["annee_fin_projet"]; $i++){ ?>
                    
					    <td width="5%"><?php
                            
							  if(isset($suivief_an_ref_array[$row_liste_rec["id_ref_ind"]][$i])) $valraug=$suivief_an_ref_array[$row_liste_rec["id_ref_ind"]][$i]; 
							 // if(isset($suivief_an_ref_array[$annee])) print_r($suivief_an_ref_array);
							// $realise_array_ptba[$row_liste_realise_ptba["ireferentiel"]][$row_liste_realise_ptba["annee"]]
							   elseif(isset($realise_array_ptba[$row_liste_rec['id_ref_ind']][$i])) 
								{$valraug=$realise_array_ptba[$row_liste_rec['id_ref_ind']][$i];}
							  elseif(isset($realise_arrayp[$row_liste_rec['id_ref_ind']][$i])) 
								{$valraug=$realise_arrayp[$row_liste_rec['id_ref_ind']][$i];}
								 else {
								 if(isset($realise_array_sygri[$row_liste_rec['id_ref_ind']][$i])) 
								{$valraug=$realise_array_sygri[$row_liste_rec['id_ref_ind']][$i];}
								 else $valraug=0;
								
								 }
							    if(isset($valraug) &&  $valraug>0) {echo  $valraug; $total_an=$total_an+$valraug;} 
							   ?></td>
                      <?php } ?>
              </tr>
            <?php }  ?>
            <?php } else { ?>
            <tr>
              <td colspan="4"><div align="center"><span class="Style4"><em><strong>Aucun indicateur de type bénéficiaires! </strong></em></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span></div></td>
            </tr>
            <?php }  ?>
        </table>
        <hr id="sp_hr" />
     

</div>
<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>
    <?php if(!isset($_GET["down"])) include_once("includes/footer.php"); ?>
</div>

</body>
</html>