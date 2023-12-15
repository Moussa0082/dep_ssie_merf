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

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form1"))
{
  if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "MM_insert")) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];  $acteur="";
	if(!empty($_POST['acteur'])) { foreach($_POST['acteur'] as $vacteur) { $acteur=$acteur.",".$vacteur; } }
	  $insertSQL = sprintf("INSERT INTO indicateur_resultat_cmr (indicateur_res, referentiel, intitule_indicateur_cmr_res, cible_dp, cible_rmp, code_cmr, annee_reference, valeur_reference, responsable_collecte, personnel, date_enregistrement) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, '$personnel', '$date')",
					   GetSQLValueString($_POST['indicateur_cl'], "int"),
                       GetSQLValueString((isset($_POST['referentiel'])?$_POST['referentiel']:0), "int"),
	   				   GetSQLValueString($_POST['indicateur'], "text"),
   					   GetSQLValueString($_POST['cible_dp'], "double"),
   					   GetSQLValueString($_POST['cible_rmp'], "double"),
   					   GetSQLValueString($_POST['code_cmr'], "text"),
   					   GetSQLValueString($_POST['annee_reference'], "int"),
   					   GetSQLValueString($_POST['valeur_reference'], "double"),
   					   GetSQLValueString($acteur, "text"));
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?insert=ok"; else $insertGoTo .= "?insert=no";
    header(sprintf("Location: %s", $insertGoTo));   exit(0);
  }

  if ((isset($_POST["MM_delete"]) && intval($_POST["MM_delete"])>0)) {
      $id = intval($_POST["MM_delete"]);
      $insertSQL = sprintf("DELETE from indicateur_resultat_cmr WHERE id_indicateur=%s",
                           GetSQLValueString($id, "int"));

      mysql_select_db($database_pdar_connexion, $pdar_connexion);
      $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
      $insertGoTo = $_SERVER['PHP_SELF'];
      if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
      header(sprintf("Location: %s", $insertGoTo)); exit();
    }  

  if ((isset($_POST["MM_update"]) && intval($_POST["MM_update"])>0)) {
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id']; $c=$_POST['id'];  $acteur="";
	if(!empty($_POST['acteur'])) { foreach($_POST['acteur'] as $vacteur) { $acteur=$acteur.",".$vacteur; } }
  $insertSQL = sprintf("UPDATE indicateur_resultat_cmr SET  indicateur_res=%s, referentiel=%s, intitule_indicateur_cmr_res=%s, cible_dp=%s, cible_rmp=%s, code_cmr=%s, annee_reference=%s, valeur_reference=%s, responsable_collecte=%s, etat='Modifié', modifier_par='$personnel', modifier_le='$date' WHERE id_indicateur='$c'",
					   GetSQLValueString($_POST['indicateur_cl'], "int"),
                       GetSQLValueString((isset($_POST['referentiel'])?$_POST['referentiel']:0), "int"),
	   				   GetSQLValueString($_POST['indicateur'], "text"),
   					   GetSQLValueString($_POST['cible_dp'], "double"),
   					   GetSQLValueString($_POST['cible_rmp'], "double"),
   					   GetSQLValueString($_POST['code_cmr'], "text"),
					   GetSQLValueString($_POST['annee_reference'], "int"),
					   GetSQLValueString($_POST['valeur_reference'], "double"),
					   //GetSQLValueString($_POST['unite_cmr'], "text"),
   					   GetSQLValueString($acteur, "text"));

  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    $insertGoTo = $_SERVER['PHP_SELF'];
    if ($Result1) $insertGoTo .= "?update=ok"; else $insertGoTo .= "?update=no";
    header(sprintf("Location: %s", $insertGoTo));   exit(0);
  }

}

if ((isset($_POST["MM_form"])) && ($_POST["MM_form"] == "form2"))
{
  $date=date("Y-m-d"); $personnel=$_SESSION['clp_id'];

  //suppression
  mysql_select_db($database_pdar_connexion, $pdar_connexion);
  $an=$_POST['annee'];
  $idres=$_POST['id'];
  $vcible=$_POST['valeur_cible'];
  //$id_cp=$_POST['id_cp'];

  $query_sup_set = "DELETE FROM cible_indres_cmr WHERE indicateur_rescmr='$idres'";
  $Result1 = mysql_query($query_sup_set, $pdar_connexion) or die(mysql_error());
  //fin suppression
  foreach ($an as $key => $value)
  {
  	if(isset($vcible[$key]) && $vcible[$key]!=NULL) {

    $insertSQL = sprintf("INSERT INTO cible_indres_cmr (annee, indicateur_rescmr, valeur_cible, id_personnel, date_enregistrement) VALUES (%s, %s, %s,'$personnel', '$date')",
                         GetSQLValueString($an[$key], "int"),
     					   GetSQLValueString($idres, "int"),
  					   GetSQLValueString($vcible[$key], "double"));

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
    $Result1 = mysql_query($insertSQL, $pdar_connexion) or die(mysql_error());
    }
    }
}



// Partie objectif specifique
// objectif specifique
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_res = "SELECT * FROM resultat WHERE ".$_SESSION["clp_where"]." order by code_resultat";
$liste_res  = mysql_query($query_liste_res , $pdar_connexion) or die(mysql_error());
$row_liste_res  = mysql_fetch_assoc($liste_res);
$totalRows_liste_res  = mysql_num_rows($liste_res);


if(isset($_GET["id_sup_indos"]) && $_SESSION['clp_niveau']==1) { $idios=$_GET["id_sup_indos"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_sup_ind = "DELETE FROM indicateur_resultat_cmr WHERE id_indicateur='$idios'";
$Result1 = mysql_query($query_sup_ind, $pdar_connexion) or die(mysql_error());
  $insertGoTo = (isset($_GET['page']))?$_GET['page']:$_SERVER['PHP_SELF'];
  if ($Result1) $insertGoTo .= "?del=ok"; else $insertGoTo .= "?del=no";
  header(sprintf("Location: %s", $insertGoTo));
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

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_reference = "SELECT indicateur_rescmr, valeur_cible, referentiel FROM indicateur_resultat_cmr,cible_indres_cmr WHERE indicateur_rescmr=id_indicateur group by indicateur_rescmr ORDER BY annee ASC";
$liste_reference = mysql_query($query_liste_reference, $pdar_connexion) or die(mysql_error());
$row_liste_reference = mysql_fetch_assoc($liste_reference);
$totalRows_liste_reference = mysql_num_rows($liste_reference);
$cible_array = array();
if($totalRows_liste_reference>0){ do{
if(isset($unite_ind_ref_array[$row_liste_reference['referentiel']]) && $unite_ind_ref_array[$row_liste_reference['referentiel']]="%")
$cible_array[$row_liste_reference["indicateur_rescmr"]]=$row_liste_reference["valeur_cible"];
elseif(isset($cible_array[$row_liste_reference["indicateur_rescmr"]])) $cible_array[$row_liste_reference["indicateur_rescmr"]]+=$row_liste_reference["valeur_cible"];
else $cible_array[$row_liste_reference["indicateur_rescmr"]]=$row_liste_reference["valeur_cible"]; }
while($row_liste_reference  = mysql_fetch_assoc($liste_reference));}


include_once 'modal_add.php';

?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

  <title><?php print $config->sitename;?></title>

  <link rel="shortcut icon" type="image/x-icon" href="<?php print $config->FaveIcone;?>" />

  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

  <meta name="keywords" content="<?php print $config->MetaKeys;?>" />

  <meta name="description" content="<?php print $config->MetaDesc;?>" />

  <!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->

  <meta name="author" content="<?php print $config->MetaAuthor;?>" />

  <!--<meta charset="utf-8">-->

  <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0"/>



  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

  <!--[if lt IE 9]><link rel="stylesheet" type="text/css" href="plugins/jquery-ui/jquery.ui.1.10.2.ie.css"/><![endif]-->

  <link href="<?php print $config->theme_folder;?>/main.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/plugins.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/responsive.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/icons.css" rel="stylesheet" type="text/css"/>

  <link href="<?php print $config->theme_folder;?>/login.css" rel="stylesheet" type="text/css"/>

  <link rel="stylesheet" href="<?php print $config->theme_folder;?>/fontawesome/font-awesome.min.css">

  <!--[if IE 7]><link rel="stylesheet" href="<?php print $config->theme_folder;?>/fontawesome/font-awesome-ie7.min.css"><![endif]-->

  <!--[if IE 8]><link href="<?php print $config->theme_folder;?>/ie8.css" rel="stylesheet" type="text/css"/><![endif]-->

  <link href='<?php print $config->theme_folder;?>/css.css' rel='stylesheet' type='text/css'>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/jquery-1.10.2.min.js"></script>

  <script type="text/javascript" src="plugins/jquery-ui/jquery-ui-1.10.2.custom.min.js"></script>

  <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/lodash.compat.min.js"></script>

  <!--[if lt IE 9]><script src="<?php print $config->script_folder;?>/libs/html5shiv.js"></script><![endif]-->

  <script type="text/javascript" src="plugins/bootbox/bootbox.min.js"></script>

  <script type="text/javascript" src="plugins/touchpunch/jquery.ui.touch-punch.min.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.move.js"></script>

  <script type="text/javascript" src="plugins/event.swipe/jquery.event.swipe.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/libs/breakpoints.js"></script>

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

  <script type="text/javascript" src="<?php print $config->script_folder;?>/app.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/plugins.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/plugins.form-components.js"></script>

<!--

  <script type="text/javascript" src="<?php print $config->script_folder;?>/custom.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/pages_calendar.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/charts/chart_filled_blue.js"></script>

  <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/charts/chart_simple.js"></script>-->

 <script type="text/javascript" src="plugins/validation/jquery.validate.min.js"></script>

 <script type="text/javascript" src="plugins/nprogress/nprogress.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/login.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/myscript.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/ui_general.js"></script>

 <script type="text/javascript" src="<?php print $config->script_folder;?>/demo/form_validation.js"></script>

 <script>$(document).ready(function(){Login.init()});</script>

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
<style>
.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
</style>
<div class="widget box box_projet">
<div class="widget-header1"> <center><h4><?php if(isset($_SESSION["clp_projet"])){ ?><b><?php echo $_SESSION["clp_projet_nom"].' ('.$_SESSION["clp_projet_sigle"].')'; ?></b><?php } else { ?>Veuillez s&eacute;lectionner un projet<?php } ?> </h4></center></div>
<div class="widget box">
<div class="widget-header"> <h4><i class="icon-reorder"></i> Indicateurs de r&eacute;sultat </h4>
<?php
$libelle = array("cmr_produit.php"=>"Indicateurs de produit","cmr_resultat.php"=>"Indicateurs d'effet","cmr_effet.php"=>"Indicateurs ODP","cmr_impact.php"=>"Indicateurs d'impact");
foreach($libelle as $key=>$lib){
  echo do_link("",$key,"$lib","<i> $lib </i>","","./","pull-right p11","",0,"",$nfile);
  $i--; }
?>
<!--<a href="cmr_produit.php" title="Indicateurs de produit" class="pull-right p11"><i class="icon-plus"> Indicateurs de produit </i></a>
<a href="cmr_resultat.php" title="Indicateurs d'effet" class="pull-right p11"><i class="icon-plus"> Indicateurs d'effet </i></a>
<a href="cmr_effet.php" title="Indicateurs ODP" class="pull-right p11"><i class="icon-plus"> Indicateurs ODP </i></a>
<a href="cmr_impact.php" title="Indicateurs d'impact" class="pull-right p11"><i class="icon-plus"> Indicateurs d'impact </i></a>-->
</div>
<div class="widget-content" style="display: block;">

                <?php if($totalRows_liste_res>0) {$o=0;do { ?>
<table width="100%" border="0" align="center" cellspacing="1" class="table table-striped table-bordered table-responsive">
                <tr <?php if($o%2==0) echo 'bgcolor="#ECF0DF"'; $o=$o+1;?>>
                  <td valign="top" bgcolor="#99CC99"><div align="left"><span class="Style27"><?php echo "<b>".$row_liste_res['code_resultat'].":</b> ".$row_liste_res['intitule_resultat']; ?></span>
                                            </div>
                    <br />
                  <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
<?php
echo do_link("","","Ajout d'indicateur &agrave; l'effet","Ajouter un indicateur","","./","pull-right","get_content('edit_indicateur_rescmr.php','res=".$row_liste_res['id_resultat']."','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile); ?>
<!--<a onclick="get_content('edit_indicateur_rescmr.php','res=<?php echo $row_liste_res['id_resultat']; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Ajout d'indicateur &agrave; l'effet" class="thickbox Add pull-right"  dir=""><span style="background-color:#ebebeb">&nbsp;<span class="Style5">Ajouter un indicateur</span>&nbsp;</span></a> -->
                    <?php }?></td>
                  </tr>
                <tr >
				     <?php
				    $id_res=$row_liste_res['id_resultat'];
				    mysql_select_db($database_pdar_connexion, $pdar_connexion);
					$query_liste_indres = "SELECT * FROM indicateur_resultat,indicateur_resultat_cmr where id_indicateur_resultat=indicateur_res and resultat='$id_res' order by id_indicateur_resultat ASC";
					$liste_indres  = mysql_query($query_liste_indres , $pdar_connexion) or die(mysql_error());
					$row_liste_indres  = mysql_fetch_assoc($liste_indres);
					$totalRows_liste_indres  = mysql_num_rows($liste_indres);
				  ?>
                    <?php if($totalRows_liste_indres>0) { ?>
                <td valign="top">
                  <table border="0" cellspacing="1" width="100%" class="table table-striped table-bordered table-hover table-responsive">
                  <thead>
                    <tr>
                      <td rowspan="2" align="center" >Indicateur</td>
                      <td rowspan="2" align="center">Unit&eacute;</td>
                      <td colspan="2" align="center">R&eacute;f&eacute;rence </td>
                      <td colspan="2" align="center">Valeurs cibles  </td>
                      <td colspan="2" align="center">Collecte des donn&eacute;es et rapports </td>
                      <?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
                      <td rowspan="2" align="center">Cible finale </td>
                      <td rowspan="2" align="center">Actions</td>
                      <?php }?>
                    </tr>
                    <tr>
                      <td align="center">Ann&eacute;e&nbsp;</td>
                      <td align="center">&nbsp;Situation</td>
                      <td align="center">&nbsp;DCP&nbsp;</td>
                      <td align="center">&nbsp;RMP&nbsp;</td>
                      <td align="center">P&eacute;riodicit&eacute;</td>
                      <td align="center">Responsable</td>
                    </tr>
                    </thead>
                    <?php $i=0; $p1="j"; do { $id = $row_liste_indres['id_indicateur']; ?>
                    <?php  if($p1!=$row_liste_indres['id_indicateur_resultat']) {?>
                    <tr bgcolor="#ECF000">
                      <td colspan="<?php  echo 11; ?>" align="center" bgcolor="#D2E2B1"><div align="left" class="Style27"><strong> <u>
                          <?php  if($p1!=$row_liste_indres['id_indicateur_resultat']) {echo $row_liste_indres['intitule_indicateur_resultat']; $i=0; }$p1=$row_liste_indres['id_indicateur_resultat']; ?>
                      </u> </strong></div></td>
                    </tr>
                    <?php } ?>
                    <tr <?php if($i%2==0) echo 'bgcolor="#ECF0DF"'; else echo 'bgcolor="#FFFFFF"'; $i=$i+1;?> onmouseover="this.bgColor='#CCFFFF';" onmouseout="this.bgColor='<?php if($i%2!=0) echo '#ECF0DF';?>';">
                      <td <?php echo (!isset($liste_ind_ref_array[$row_liste_indres['referentiel']]))?'style="color:#FF0000"':''; ?>><div align="left"><span class="Style20">.&nbsp;</span><span class="Style22"><?php echo $row_liste_indres['intitule_indicateur_cmr_res']; ?></span></div></td>
                      <td ><div align="center">
                        <?php if(isset($unite_ind_ref_array[$row_liste_indres['referentiel']])) echo " (".$unite_ind_ref_array[$row_liste_indres['referentiel']].")"; ?>
                      </div></td>
                      <td ><div align="center" class="Style22"><strong><?php echo $row_liste_indres['annee_reference']; ?></strong></div></td>



                      <td ><div align="center"><span class="Style22"><?php echo $row_liste_indres['valeur_reference']; ?></span></div></td>
                      <td><div align="center"><span class="Style22"><?php echo $row_liste_indres['cible_dp']; ?></span></div></td>
                      <td><div align="center"><span class="Style22"><?php echo $row_liste_indres['cible_rmp']; ?></span></div></td>
                      <td align="center"><span class="Style22">Annuelle</span></td>
                      <td><span class="Style22">
                        <?php
			$as = explode(",", $row_liste_indres['responsable_collecte']); 	$lacteur=implode("','", $as);
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
<?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']==1) {?>
                      <td align="center">
<?php
echo do_link("","","Valeurs cibles annuelles",(isset($cible_array[$row_liste_indres["id_indicateur"]]))?$cible_array[$row_liste_indres["id_indicateur"]]:'Cibles annuelles',"","./","","get_content('edit_cible_indicateur_rescmr.php','id=$id&res".$row_liste_res['id_resultat']."','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);
?>
<!--<a onclick="get_content('edit_cible_indicateur_rescmr.php','id=<?php echo $row_liste_indres['id_indicateur']; ?>&res=<?php echo $row_liste_res['id_resultat']; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Valeurs cibles annuelles" class="thickbox Add"  dir=""><strong><?php if(isset($cible_array[$row_liste_indres["id_indicateur"]])) echo $cible_array[$row_liste_indres["id_indicateur"]]; else echo "Cibles annuelles";?></strong></a> -->
                        </td>
                      <td align="center">
<?php
echo do_link("","","Modifier indicateur : ".$row_liste_indres['intitule_indicateur_cmr_res'],"","edit","./","","get_content('edit_indicateur_rescmr.php','id=$id&res=".$row_liste_res['id_resultat']."','modal-body_add',this.title);",1,"margin:0px 5px;",$nfile);

echo do_link("",$_SERVER['PHP_SELF']."?id_sup_indos=".$id,"Supprimer","","del","./","","return confirm('Voulez-vous vraiment supprimer cet indicateur ?');",0,"margin:0px 5px;",$nfile);
?>
<!--<a onclick="get_content('edit_indicateur_rescmr.php','id=<?php echo $row_liste_indres['id_indicateur']; ?>&res=<?php echo $row_liste_res['id_resultat']; ?>','modal-body_add',this.title);" data-toggle="modal" href="#myModal_add" title="Modification d'indicateur" class="thickbox Add" dir=""><img src='images/edit.png' width='20' height='20' alt='Modifier' /></a>
<a href="<?php echo $_SERVER['PHP_SELF']."?id_sup_indos=".$row_liste_indres['id_indicateur'].""?>" onclick="return confirm('Voulez-vous vraiment supprimer ?');" /><img src="images/delete.png" width="15"/></a>-->
</td>
                      <?php }?>

                    </tr>
                    <?php } while ($row_liste_indres = mysql_fetch_assoc($liste_indres)); mysql_free_result($liste_indres); ?>
                  </table>
                  </td>
                  <?php } ?>
                </tr>
<!--				<tr><td><hr /></td></tr>  -->  </table>
                <?php } while ($row_liste_res = mysql_fetch_assoc($liste_res)); mysql_free_result($liste_res); ?>
                <?php } ?>

</div>

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