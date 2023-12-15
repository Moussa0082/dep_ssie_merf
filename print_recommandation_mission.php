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
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="p"){

require_once('./tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$PDF_HEADER_TITLE = "Mission de Récommandation";
$PDF_HEADER_STRING = "Mission de Récommandation";

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ruche');
$pdf->SetTitle($PDF_HEADER_TITLE);
$pdf->SetSubject($PDF_HEADER_STRING);
$pdf->SetKeywords('PDF, mission, Mission de Récommandation, Récommandation');

// set default header data //PDF_HEADER_LOGO
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE, $PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}
// set font
//$pdf->SetFont('dejavusans', '', 10);
$pdf->AddPage();

  ob_start(); // turn on output buffering
  /*$_GET["id"]="0001";
  $_GET["down"]=5; */
  include("./print_recommandation_mission_pdf.php");
  $content = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering

$html = utf8_encode($content);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('Mission_Recommandation.pdf', 'D');
/*
include("pdf/mpdf.php");
$mpdf=new mPDF('win-1252','A4-L','','',15,10,16,10,10,10);//A4 page in portrait for landscape add -L.
$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetDisplayMode('fullpage');
ob_start();
include "print_recommandation_mission_pdf.php";
$html = ob_get_contents();
ob_end_clean();
$mpdf->WriteHTML($html);
$mpdf->Output();
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Recommandation_mission.pdf"); */
exit;

 } ?>
<?php


$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

$id_m=$_GET['id'];
                //projet='".$_SESSION["clp_projet"]."' and
$query_edit_ms = "SELECT * FROM ".$database_connect_prefix."mission_supervision   where   id_mission='$id_m'";
  try{
    $edit_ms = $pdar_connexion->prepare($query_edit_ms);
    $edit_ms->execute();
    $row_edit_ms = $edit_ms ->fetchAll();
    $totalRows_edit_ms = $edit_ms->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

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

$query_liste_code_ref = "SELECT * FROM ".$database_connect_prefix."rubrique_projet order by code_rub";
  try{
    $liste_code_ref = $pdar_connexion->prepare($query_liste_code_ref);
    $liste_code_ref->execute();
    $row_liste_code_ref = $liste_code_ref ->fetchAll();
    $totalRows_liste_code_ref = $liste_code_ref->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$liste_rub_array = array();
$code_rub_array = array();
if($totalRows_liste_code_ref>0){ foreach($row_liste_code_ref as $row_liste_code_ref){
  $liste_rub_array[$row_liste_code_ref["code_rub"]] = /*$row_liste_code_ref["code_rub"].": ".*/$row_liste_code_ref["nom_rubrique"];
  $code_rub_array[$row_liste_code_ref["code_rub"]] = $row_liste_code_ref["code_rub"];
} }

$query_liste_respo_ugl = "SELECT id_personnel, fonction FROM ".$database_connect_prefix."personnel";
  try{
    $liste_respo_ugl = $pdar_connexion->prepare($query_liste_respo_ugl);
    $liste_respo_ugl->execute();
    $row_liste_respo_ugl = $liste_respo_ugl ->fetchAll();
    $totalRows_liste_respo_ugl = $liste_respo_ugl->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }

$respo_ugl=array();
if($totalRows_liste_respo_ugl>0){ foreach($row_liste_respo_ugl as $row_liste_respo_ugl){ $respo_ugl[$row_liste_respo_ugl["id_personnel"]]=$row_liste_respo_ugl["fonction"];  }  }
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
<div class="well well-sm r_float"><div class="r_float"><a href="./s_supervision.php" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a target="_blank" title="Imprimer" href="<?php echo $editFormAction."&down=1"; ?>" class="button"><img src="./images/print.png" width='20' height='20' alt='Modifier' /></a></div></div>
<div class="clear h0">&nbsp;</div>
<?php } else { ?>

<center><?php //include "./includes/print_header.php"; ?></center>

<?php } ?>


<div class="well well-sm"><strong>P&eacute;riode et </strong><strong>Objet de la mission</strong></div>
      <?php  if($totalRows_edit_ms>0) { foreach($row_edit_ms as $row_edit_ms){?>
      <div align="left" class="well well-sm">
            <?php  echo $row_edit_ms['type']." du ".implode('-',array_reverse(explode('-',$row_edit_ms['debut'])))." au ".implode('-',array_reverse(explode('-',$row_edit_ms['fin']))); ?>
        :&nbsp;&nbsp;<?php echo $row_edit_ms['observation']; ?></div>
        <?php
		if(isset($row_edit_ms['id_mission']))$id=$row_edit_ms['id_mission']; else $id=0;
		$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."recommandation_mission where mission='$id' ORDER BY rubrique asc, numero asc";
		  try{
    $liste_rec = $pdar_connexion->prepare($query_liste_rec);
    $liste_rec->execute();
    $row_liste_rec = $liste_rec ->fetchAll();
    $totalRows_liste_rec = $liste_rec->rowCount();
}catch(Exception $e){ die(mysql_error_show_message($e)); }
		?>
<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive">
            <?php if($totalRows_liste_rec>0) {$i=0; $t=0; $p2=$p1="j"; ?>
            <thead>
            <tr>
              <th rowspan="2" align="center"><strong>R&eacute;f.</strong></th>
              <th rowspan="2"><div align="left"><strong>Recommandation </strong></div></th>
              <th rowspan="2"><div align="center"><strong>Date buttoir </strong></div></th>
              <th colspan="2"><div align="center"><strong>Responsables</strong></div></th>
            </tr>
            <tr>
              <th><strong>D&eacute;di&eacute;</strong></th>
              <th><strong>Autres</strong></th>
            </tr>
            </thead>
            <?php foreach($row_liste_rec as $row_liste_rec){ ?>
            <?php  if($p2!=$row_liste_rec['rubrique']) {?>
            <tr bgcolor="#BED694">
              <td bgcolor="#BED694" colspan="5" align="center"><div align="left" style="background-color: #BED694; "><strong>
                <?php  if($p2!=$row_liste_rec['rubrique']) {
                  if(isset($liste_rub_array[$row_liste_rec["rubrique"]]))  echo $liste_rub_array[$row_liste_rec['rubrique']]; else echo "N/A";
				  }$p2=$row_liste_rec['rubrique']; ?>
              </strong></div></td>
            </tr>
			   <?php } ?>
			 <?php  if($p1!=$row_liste_rec['volet_recommandation']) {/*?>
            <tr bgcolor="#BED694">
              <td colspan="5" align="center"><div align="left" class="Style4"><strong>  <?php  if($p1!=$row_liste_rec['rubrique']) {if(isset($tableau_rubrique[$row_liste_rec["rubrique"]])) echo $tableau_rubrique[$row_liste_rec["rubrique"]];
				else echo "N/A"; $i=0; }$p1=$row_liste_rec['rubrique']; ?>
              </strong></div></td>
            </tr>
            <?php */} ?>
            <tr>
              <td><div align="center"><strong><?php echo $row_liste_rec['numero']; ?></strong></div></td>
              <td><div align="left" class="Style4"><?php echo $row_liste_rec['recommandation']; ?></div></td>
              <td valign="top"><div align="center"><span class="Style46">
                <?php if(isset($row_liste_rec['type']) && $row_liste_rec['type']=="Continu") echo "Continu"; else echo date("d/m/y", strtotime($row_liste_rec['date_buttoir']));?>
              </span></div></td>
              <td valign="top"><div align="left">
                <?php if(isset($respo_ugl[$row_liste_rec["responsable_interne"]])) echo $respo_ugl[$row_liste_rec["responsable_interne"]]; ?>
              </div></td>
              <td valign="top"><?php echo $row_liste_rec['responsable']; ?></td>
            </tr>
            <?php }  ?>
            <?php } else { ?>
            <tr>
              <td colspan="5"><div align="center"><span class="Style4"><em><strong>Aucune recommandation enregistr&eacute;e! </strong></em></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span></div></td>
            </tr>
            <?php }  ?>
        </table>
        <hr id="sp_hr" />
      <?php }  } else { ?>
      <tr>
        <td align="center"><strong><em>Aucune mission effectu&eacute;e!</em></strong></td>
      </tr>
      <?php } ?>

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