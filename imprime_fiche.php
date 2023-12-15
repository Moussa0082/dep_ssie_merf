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
?>

<?php

$editFormAction = $_SERVER['PHP_SELF']."?";
/*if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
} */

$plog=$_SESSION["clp_id"];
$date=date("Y-m-d");
if(isset($_SESSION['annee']) && !isset($_GET['annee'])) {$annee=$_SESSION['annee'];}
elseif(isset($_GET['annee'])) {$annee=$_GET['annee']; $_SESSION['annee']=$annee;}
elseif(!isset($_GET['annee']) && isset($_SESSION['annee'])) $annee=$_SESSION['annee'];
else $annee=date("Y");

if(isset($_SESSION["cp"]) && !isset($_GET['cp'])){$cp=$_SESSION["cp"];}
elseif(isset($_GET['cp'])){$cp=$_GET['cp']; $_SESSION["cp"]=$cp; }
elseif(!isset($_GET['cp']) && isset($_SESSION['cp'])) $_GET['cp']=$cp;


// query fonction

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_mois= "SELECT * FROM mois order by num_mois";
$liste_mois = mysql_query($query_liste_mois, $pdar_connexion) or die(mysql_error());
	$tableauMois=array();
	while($ligne=mysql_fetch_assoc($liste_mois)){$tableauMois[]=$ligne['num_mois']."<>".$ligne['abrege'];}
	mysql_free_result($liste_mois);


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_cp = "SHOW tables";
$liste_cp = mysql_query($query_liste_cp, $pdar_connexion) or die(mysql_error());
$row_liste_cp = mysql_fetch_assoc($liste_cp);
$totalRows_liste_cp = mysql_num_rows($liste_cp);

$cp_array=array();
if($totalRows_liste_cp>0) {
do {  if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="fiche_config"){  $cp_array[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];
}
} while ($row_liste_cp = mysql_fetch_assoc($liste_cp));
$rows = mysql_num_rows($liste_cp);
if($rows > 0) {
mysql_data_seek($liste_cp, 0);
$row_liste_cp = mysql_fetch_assoc($liste_cp);
}}

if(!in_array(isset($cp) && $cp,$cp_array)) unset($cp);

$entete_array = $libelle = array();

if(isset($cp) && !empty($cp))
{
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT * FROM $cp WHERE annee=$annee";
$act  = mysql_query($query_act , $pdar_connexion) or die(mysql_error());
$row_act  = mysql_fetch_assoc($act);
$totalRows_act  = mysql_num_rows($act);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "SELECT * FROM fiche_config WHERE `table`='$cp'";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);

if($totalRows_entete>0){ $entete_array=explode(",",$row_entete["show"]); $libelle=explode(",",$row_entete["libelle"]);}

$count = count($libelle)-2;
$count = explode("=",$libelle[$count]);
$lib_nom_fich = "";
if(isset($count[1]))
$lib_nom_fich = $count[1];
elseif(isset($count[0]))
$lib_nom_fich = $count[0];

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete = "DESCRIBE $cp";
$entete  = mysql_query($query_entete , $pdar_connexion) or die(mysql_error());
$row_entete  = mysql_fetch_assoc($entete);
$totalRows_entete  = mysql_num_rows($entete);
$num=0;
if($totalRows_entete>0){ do{ if(in_array($row_entete["Field"],$entete_array)) $num++; }while($row_entete  = mysql_fetch_assoc($entete));  }

$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
}

//toutes les fiches
$lib_nom_fich_array = $table_array = array();
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_cfg = "SELECT * FROM fiche_config WHERE `table` NOT LIKE '%_details'";
$cfg  = mysql_query($query_cfg , $pdar_connexion) or die(mysql_error());
$row_cfg  = mysql_fetch_assoc($cfg);
$totalRows_cfg  = mysql_num_rows($cfg);

if($totalRows_cfg>0){ do{
  $table_array[] = $row_cfg["table"];
  $cfg_array=explode(",",$row_cfg["show"]); $libelleF=explode(",",$row_cfg["libelle"]);

$count = count($libelleF)-2;
$count = explode("=",$libelleF[$count]);

if(isset($count[1]))
$lib_nom_fich_array[$row_cfg["table"]] = $count[1];
elseif(isset($count[0]))
$lib_nom_fich_array[$row_cfg["table"]] = $count[0];

  }while($row_cfg  = mysql_fetch_assoc($cfg));
}

if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Fiche Technique $cp.doc"); }

}



//}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php if(!isset($_GET["down"])){  ?>
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
  <!--<script type="text/javascript" src="plugins/datatables/jquery.dataTables.min.js"></script>
  <script type="text/javascript" src="plugins/datatables/DT_bootstrap.js"></script>
  <script type="text/javascript" src="plugins/datatables/responsive/datatables.responsive.js"></script>-->
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
 <style>
.firstcapitalize:first-letter{
  text-transform: capitalize;
}
</style>
<?php }else{ ?>
<style>
table {width: 100%; }
table td {border: solid 1px; }
.colore {
  background-color: #66CC66;
}


</style>
<?php } ?>
</head>
<body>
<?php if(!isset($_GET["down"])){  ?>
 <header class="header navbar navbar-fixed-top" role="banner">
    <?php include_once("includes/header.php"); ?>
 </header><?php } ?>
<div id="container">
<?php if(!isset($_GET["down"])){  ?>
    <div id="sidebar" class="sidebar-fixed">
        <div id="sidebar-content">
            <?php include_once("includes/menu_top.php"); ?>
        </div>
        <div id="divider" class="resizeable"></div>
    </div>
<?php } ?>
    <div id="content">
        <div class="container">
        <?php if(!isset($_GET["down"])){  ?>
            <div class="crumbs">
                <?php include_once("includes/sous_menu.php"); ?>
            </div>
            <?php } ?>
        <div class="page-header">
            <div class="p_top_5">
<?php if(!isset($_GET["down"])){  ?>
<!-- Site contenu ici -->
<style>#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse;
} .table tbody tr td {vertical-align: middle; }
</style>
                <table width="100%"  border="0" align="left" cellspacing="2" cellpadding="2">
                <tr bgcolor="">
                   <td valign="middle" colspan="3" nowrap="nowrap"><div align="left">
                     <?php include("content/annee_ptba.php"); ?></div></td>
                    </tr>
                 <tr bgcolor="">
                   <td colspan="3" valign="middle" nowrap="nowrap"><div align="right">
                     <form name="form2" id="form2" method="get" action="" class="contenuh1">
                       <table   border="0" cellspacing="2">
                         <tr>
                           <th nowrap="nowrap" scope="col"><input type="hidden" name="annee" value="<?php echo $annee; ?>" /></th>
                           <th nowrap="nowrap" scope="col">Fiche:
                             <select name="cp" style=" ">
                               <option value="">-- Choisissez --</option>

                               <?php }  $table_array=array();
				  if($totalRows_liste_cp>0) {
				do { $table_array[]=$row_liste_cp["Tables_in_$database_pdar_connexion"];  if(strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"fiche")!="fiche_config" && strchr($row_liste_cp["Tables_in_$database_pdar_connexion"],"details")==""){  if(!isset($_GET["down"])){
				?>
                                 <option value="<?php echo $row_liste_cp["Tables_in_$database_pdar_connexion"];?>"<?php if(isset($cp)) {if (!(strcmp($cp, $row_liste_cp["Tables_in_$database_pdar_connexion"]))) {echo "SELECTED";} } ?>><?php echo (isset($lib_nom_fich_array[$row_liste_cp["Tables_in_$database_pdar_connexion"]]))?$lib_nom_fich_array[$row_liste_cp["Tables_in_$database_pdar_connexion"]]:substr($row_liste_cp["Tables_in_$database_pdar_connexion"],6); ?></option>
                               <?php }  }
			} while ($row_liste_cp = mysql_fetch_assoc($liste_cp));
			  $rows = mysql_num_rows($liste_cp);
			  if($rows > 0) {
				  mysql_data_seek($liste_cp, 0);
				  $row_liste_cp = mysql_fetch_assoc($liste_cp);
			  }}


          if(!isset($_GET["down"])){  ?>
                             </select></th>
                           <th scope="col"><input type="submit" name="Submit" value="Rechercher" style="color:#FF0000 " /></th>
                         </tr>
                       </table>
                     </form>
                   </div></td>
                   </tr>

                </table>
<?php  } if(isset($cp)) {
                                ?>
<!--<div class="page-header">
<div class="page-title"><h3>Mon profil</h3></div>
</div> -->
<div class="widget box">
<?php if(!isset($_GET["down"])){  ?>
<div class="widget-header no-print"> <h4 style="width: 49%"><i class="icon-reorder"></i><strong><?php echo $lib_nom_fich;?></strong></h4><h4 align="right" style="width: 49%"><?php if(isset($_SESSION['clp_niveau']) && $_SESSION['clp_niveau']<3 && isset($cp) && !empty($cp) && $totalRows_act>0) {?><a href="<?php echo $editFormAction."&down=1&t=w&cp=$cp&annee=$annee"; ?>" title="Imprimer en Word"><img src="images/doc.jpg" width="23" height="22" alt="" /></a><?php }?>&nbsp;|&nbsp;<a href="<?php echo "suivi_technique.php?cp=$cp&annee=$annee"; ?>" title="Imprimer en Word">R&eacute;tour</a></h4>

<?php include_once 'modal_add.php'; ?>

</div>
<?php } ?>
<div class="widget-content">
<?php if($num>0){
if(isset($_GET["down"])){  ?><h3 align="center">Fiche: <?php echo $lib_nom_fich;  ?></h3><?php } ?>
<table class="table table-responsive datatable dataTable" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<!--<thead>
<tr role="row">
<?php if($totalRows_entete>0){ do{ if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)){  ?>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize"><?php echo str_replace("_"," ",$row_entete["Field"]); ?></div></th>
<?php } }while($row_entete  = mysql_fetch_assoc($entete)); }
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
} ?>

</tr>
</thead>-->

<tbody>
<?php $i=0; if($totalRows_act>=0) { /*do { */ $id = $row_act['LKEY']; ?>
<tr>
<?php if($totalRows_entete>0){ $j=0; $ii=0; do{

if(isset($libelle[$ii])){
$lib=explode("=",$libelle[$ii]);
$libelle_array[$lib[0]]=isset($lib[1])?$lib[1]:"ND";   }

if($row_entete["Field"]!="LKEY" && in_array($row_entete["Field"],$entete_array)){

if(strtolower($row_entete["Field"])=="village" && intval($row_act[$row_entete["Field"]])>0){ $village=$row_act[$row_entete["Field"]];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_region = "SELECT nom_village,nom_commune FROM commune,village WHERE commune=code_commune and code_village='$village'";
$region = mysql_query($query_region, $pdar_connexion) or die(mysql_error());
$row_region = mysql_fetch_assoc($region);
$totalRows_region = mysql_num_rows($region);
$lib_vill = $row_region["nom_commune"]." / ".$row_region["nom_village"];
mysql_free_result($region);
}

?>
<?php if($j%4==0 && $j!=0){ ?>
</tr><tr>
<?php } ?>
<td style="border: none"><?php echo "<b>".((isset($libelle_array[$row_entete["Field"]]))?$libelle_array[$row_entete["Field"]]:$row_entete["Field"])."</b>: "; /*if(strtolower($row_entete["Type"])=="date") echo implode('-',array_reverse(explode('-',$row_act[$row_entete["Field"]]))); else echo ((strtolower($row_entete["Field"])=="village" && isset($row_region["nom_village"]) && isset($lib_vill))?$lib_vill:$row_act[$row_entete["Field"]]); unset($lib_vill);$row_act[$row_entete["Field"]];*/ ?>........................................</td>
<?php } $j++; $ii++; }while($row_entete  = mysql_fetch_assoc($entete));
$rows = mysql_num_rows($entete);
if($rows > 0) {
mysql_data_seek($entete, 0);
$row_entete = mysql_fetch_assoc($entete);
} }

 ?>
<td style="border: none" colspan="<?php echo ($j-4); ?>" class=" ">&nbsp;</td>
</tr>
<?php if(in_array($cp."_details",$table_array)){  $id_f=$row_act["LKEY"];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act1 = "SELECT * FROM $cp"."_details WHERE fiche=$id_f";
$act1  = mysql_query($query_act1 , $pdar_connexion) or die(mysql_error());
$row_act1  = mysql_fetch_assoc($act1);
$totalRows_act1  = mysql_num_rows($act1);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete1 = "SELECT * FROM fiche_config WHERE `table`='$cp"."_details'";
$entete1  = mysql_query($query_entete1 , $pdar_connexion) or die(mysql_error());
$row_entete1  = mysql_fetch_assoc($entete1);
$totalRows_entete1  = mysql_num_rows($entete1);

if($totalRows_entete1>0){ $entete1_array=explode(",",$row_entete1["show"]); $libelle1=explode(",",$row_entete1["libelle"]);}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_entete1 = "DESCRIBE $cp"."_details";
$entete1  = mysql_query($query_entete1 , $pdar_connexion) or die(mysql_error());
$row_entete1  = mysql_fetch_assoc($entete1);
$totalRows_entete1  = mysql_num_rows($entete1);
$num1=0;
if($totalRows_entete1>0){ do{ if(isset($entete1_array) && in_array($row_entete1["Field"],$entete1_array)) $num1++; }while($row_entete1  = mysql_fetch_assoc($entete1));  }

$rows = mysql_num_rows($entete1);
if($rows > 0) {
mysql_data_seek($entete1, 0);
$row_entete1 = mysql_fetch_assoc($entete1);
}
 ?>
<tr><td colspan="4">

<?php if($num1>0){ ?>
<table class="table table-striped table-bordered table-responsive datatable dataTable hide_befor_load" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info">
<thead>
<tr role="row" class="colore">
<?php if($totalRows_entete1>0){  $iii=0; do{

if(isset($libelle1[$iii])){
$lib1=explode("=",$libelle1[$iii]);
$libelle_array1[$lib1[0]]=isset($lib1[1])?$lib1[1]:"ND";   }

if($row_entete1["Field"]!="LKEY" && in_array($row_entete1["Field"],$entete1_array)){  ?>
<th class="" role="columnheader" tabindex="0" aria-controls="DataTables_Table_0" aria-label="Trier" ><div class="firstcapitalize"><?php echo (isset($libelle_array1[$row_entete1["Field"]]))?$libelle_array1[$row_entete1["Field"]]:str_replace("_"," ",$row_entete1["Field"]); ?></div></th>
<?php } $iii++; }while($row_entete1  = mysql_fetch_assoc($entete1)); }
$rows = mysql_num_rows($entete1);
if($rows > 0) {
mysql_data_seek($entete1, 0);
$row_entete1 = mysql_fetch_assoc($entete1);
} ?>
</tr>
</thead>

<tbody role="alert" aria-live="polite" aria-relevant="all" class="hide_befor_load">
<?php $i=0; /*if($totalRows_act1>0) {*/ do { $id = $row_act1['LKEY']; ?>
<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<?php $rows = mysql_num_rows($entete1);
if($rows > 0) {
mysql_data_seek($entete1, 0);
$row_entete1 = mysql_fetch_assoc($entete1);
}
if($totalRows_entete1>0){ do{ if($row_entete1["Field"]!="LKEY" && in_array($row_entete1["Field"],$entete1_array)){
if(strtolower($row_entete1["Field"])=="village" && intval($row_act1[$row_entete1["Field"]])>0){ $village=$row_act1[$row_entete1["Field"]];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_region = "SELECT nom_village,nom_commune FROM commune,village WHERE commune=id_commune and id_village=$village";
$region = mysql_query($query_region, $pdar_connexion) or die(mysql_error());
$row_region = mysql_fetch_assoc($region);
$totalRows_region = mysql_num_rows($region);
$lib_vill = $row_region["nom_commune"]." / ".$row_region["nom_village"];
mysql_free_result($region);
}
?>
<td class=" "><?php /*if(strtolower($row_entete1["Type"])=="date") echo implode('-',array_reverse(explode('-',$row_act1[$row_entete1["Field"]]))); else echo (strtolower($row_entete1["Field"])=="village" && isset($row_region["nom_village"]))?$lib_vill:$row_act1[$row_entete1["Field"]]; unset($lib_vill);*/ ?></td>
<?php } }while($row_entete1  = mysql_fetch_assoc($entete1));

} ?>
</tr>
<?php $i++; } while ($i <= 10);/*while ($row_act1 = mysql_fetch_assoc($act1));*/ //} ?>
</tbody></table>
<?php if(isset($_GET["down"])){  ?><tr style="border: none"><td style="border: none" colspan="3">&nbsp;</td></tr><?php } ?>
<?php }else echo "<h3 align='center'>Aucune colonne &agrave; afficher dans cette fiche ".substr(str_replace("_"," ",$cp),6)."!</h3>"; ?>

</td></tr>
<?php }
$i++; /*} while ($i <= 1);*/ //while ($row_act = mysql_fetch_assoc($act));
 }else echo "<h3 align='center'>Aucune donn&eacute;e &agrave; afficher dans cette fiche ".substr(str_replace("_"," ",$cp),6)."!</h3>"; ?>
</tbody></table><?php }else echo "<h3 align='center'>Aucune colonne &agrave; afficher dans cette fiche ".substr(str_replace("_"," ",$cp),6)."!</h3>"; ?>

</div> </div>


				   <?php } else {?>
                  <h3 align="center" class="Style5">Veuillez s&eacute;lectionnez une fiche !!! </h3>
                  <?php } ?>


<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>    <?php if(!isset($_GET["down"])) include_once 'modal_add.php'; ?>
    <?php if(!isset($_GET["down"])) include_once("includes/footer.php"); ?>
</div>

</body>
</html>