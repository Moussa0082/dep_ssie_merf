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
header("content-disposition: attachment;filename=ano_instance_bailleur.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=ano_instance_bailleur.doc"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="p"){

require_once('./tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$PDF_HEADER_TITLE = "Listes des DANO à re(soumettre) au bailleur";
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
  include("./print_dno_non_soumis_pdf.php");
  $content = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering

$html = utf8_encode($content);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('dno_non_soumis.pdf', 'D');
/*
include("pdf/mpdf.php");
$mpdf=new mPDF('win-1252','A4-L','','',15,10,16,10,10,10);//A4 page in portrait for landscape add -L.
$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetDisplayMode('fullpage');
ob_start();
include "print_dno_non_soumis_pdf.php";
$html = ob_get_contents();
ob_end_clean();
$mpdf->WriteHTML($html);
$mpdf->Output();
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=dno_non_soumis.pdf"); */
exit;

 }

//if(isset($_GET['annee'])) {$annee=$_GET['annee'];} else {$annee=date("Y");}

//fonction calcul nb jour
function NbJours($debut, $fin) {
  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);
  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) - mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);
  return(($diff / 86400)+1);
}

$editFormAction1 = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction1 .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_conv = "SELECT distinct ".$database_connect_prefix."dno.* FROM ".$database_connect_prefix."dno where ".$database_connect_prefix."dno.projet='".$_SESSION["clp_projet"]."'  ORDER BY numero desc";
$liste_conv = mysql_query($query_liste_conv, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_conv = mysql_fetch_assoc($liste_conv);
$totalRows_liste_conv = mysql_num_rows($liste_conv);


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_bailleur = "SELECT code, sigle, definition FROM ".$database_connect_prefix."partenaire WHERE dno=1 ";
$liste_bailleur = mysql_query($query_liste_bailleur, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
$totalRows_liste_bailleur = mysql_num_rows($liste_bailleur);
$destinateur_array = array();
if($totalRows_liste_bailleur>0){ do{
  $destinateur_array[$row_liste_bailleur["code"]] = $row_liste_bailleur["sigle"];
}while($row_liste_bailleur = mysql_fetch_assoc($liste_bailleur)); }



mysql_select_db($database_pdar_connexion, $pdar_connexion);
//$id=$row_liste_conv['id_dno'];  mysql_error_show_message(mysql_error())
$query_edit_ano = "SELECT dno, phase, date_phase FROM ".$database_connect_prefix."suivi_dno";
$edit_ano = mysql_query($query_edit_ano, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_ano = mysql_fetch_assoc($edit_ano);
$totalRows_edit_ano = mysql_num_rows($edit_ano);
$tableau_date_envoi = array();
$tableau_date_ano = array();
$tableau_date_rejet = array();
if($totalRows_edit_ano>0){  do{
  if($row_edit_ano["phase"]=="ANO") $tableau_date_ano[$row_edit_ano["dno"]]=$row_edit_ano["date_phase"];
  if($row_edit_ano["phase"]=="Envoi au bailleur") $tableau_date_envoi[$row_edit_ano["dno"]]=$row_edit_ano["date_phase"];
    if($row_edit_ano["phase"]=="Objection du bailleur") $tableau_date_rejet[$row_edit_ano["dno"]]=$row_edit_ano["date_phase"];

   }while($row_edit_ano = mysql_fetch_assoc($edit_ano));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rubrique = "select * from ".$database_connect_prefix."suivi_dno order by dno, date_phase desc";
$liste_rubrique = mysql_query($query_liste_rubrique, $pdar_connexion) or die(mysql_error());
$row_liste_rubrique = mysql_fetch_assoc($liste_rubrique);
$totalRows_liste_rubrique = mysql_num_rows($liste_rubrique);
$tableau_stat = $tableau_obs = $tableau_persp = array();
if($totalRows_liste_rubrique>0){  do{ if(!isset($tableau_obs[$row_liste_rubrique["dno"]])) $tableau_obs[$row_liste_rubrique["dno"]]="";
  $tableau_stat[$row_liste_rubrique["dno"]]=$row_liste_rubrique["phase"];
  $tableau_persp[$row_liste_rubrique["dno"]]=$row_liste_rubrique["observation"];
  $tableau_obs[$row_liste_rubrique["dno"]].="<u>".implode('-',array_reverse(explode('-',$row_liste_rubrique["date_phase"])))."</u>: (<b>".$row_liste_rubrique["phase"]."</b>)<i> ".$row_liste_rubrique["observation"]."&nbsp;    </br></i>"; }while($row_liste_rubrique = mysql_fetch_assoc($liste_rubrique));
}

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_phase_dano = "select * from ".$database_connect_prefix."suivi_dno order by dno, date_phase, id_suivi asc";
$liste_phase_dano = mysql_query($query_liste_phase_dano, $pdar_connexion) or die(mysql_error());
$row_liste_phase_dano = mysql_fetch_assoc($liste_phase_dano);
$totalRows_liste_phase_dano = mysql_num_rows($liste_phase_dano);
$tableau_phase_dano= array();
$tableau_date_phase_dano= array();
if($totalRows_liste_phase_dano>0){  do{ //if(!isset($tableau_obs[$row_liste_rubrique["dno"]])) $tableau_obs[$row_liste_rubrique["dno"]]="";
  $tableau_phase_dano[$row_liste_phase_dano["dno"]]=$row_liste_phase_dano["phase"];
   $tableau_date_phase_dano[$row_liste_phase_dano["dno"]]=$row_liste_phase_dano["date_phase"];
  }while($row_liste_phase_dano = mysql_fetch_assoc($liste_phase_dano));
}

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
.Style11 { font-weight: bold;color: #FFFFFF;}
.well {margin-bottom: 5px;}
#mtable tr td, .table thead tr th {vertical-align: middle; }  .table {
  border-spacing: 0px !important; border-collapse: collapse; font-size: small;
} .table tbody tr td {vertical-align: middle; }
</style>
<div class="contenu">
<?php if(!isset($_GET["down"])){  ?>
<div class="well well-sm r_float"><div class="r_float"><a href="./s_supervision.php" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction1."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction1."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format PDF" href="<?php echo $editFormAction1."&down=1&t=p"; ?>" class="button"><img src="./images/pdf.png" width='20' height='20' alt='Modifier' /></a></div></div>
<div class="clear h0">&nbsp;</div>
<?php } else { ?>

<center><?php include "./includes/print_header.php"; ?></center>

<?php } ?>

<div class="well well-sm"><strong>Listes des DANO &agrave; re(soumettre) au bailleur</strong></div>

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive table-tabletools table-colvis datatable dataTable">
<thead>
<tr role="row">
<th>Num&eacute;ro</th>
<th>Activit&eacute;</th>
<th>Source</th>
<th>Objet</th>
<th>Date de reception </th>
<th>Dur&eacute;e (J)</th>
<th>Observations</th>
</tr>
</thead>
<tbody class="hide_befor_load">
<?php if($totalRows_liste_conv>0) { $i=0; do { $id = $row_liste_conv['numero'];
 if(isset($tableau_date_envoi[$row_liste_conv['numero']])) $denvoi=$tableau_date_envoi[$row_liste_conv['numero']]; else $denvoi=date("Y-m-d");  if($denvoi>=$row_liste_conv['date_initialisation'])$Nombres_jourse = NbJours($row_liste_conv['date_initialisation'], $denvoi); else $Nombres_jourse="  ???";
?>

<?php if(isset($tableau_phase_dano[$id]) && ($tableau_phase_dano[$id]=='Envoi du bailleur' || $tableau_phase_dano[$id]=='Renvoi au bailleur')){  ?>

<tr class="<?php echo ($i%2==0)?"odd":"even"; ?>">
<td class=" "><?php echo $row_liste_conv['numero']; ?></td>
<td class=" "><div  title=" <?php echo $row_liste_conv['intitule_activite_ptba']; ?>"><?php if(isset($row_liste_conv["code_activite"])) echo $row_liste_conv["code_activite"]; ?></div></td>
<td class=" "><?php echo (isset($row_liste_conv['expediteur']))?$row_liste_conv['expediteur']:''; ?></td>
<td class=" "><?php echo $row_liste_conv['objet']; ?></td>
<td class=" "><?php if(isset($tableau_date_phase_dano[$id]) ) echo date_reg($tableau_date_phase_dano[$id],'/');  ?></td>
<td class=" "><div align="center">
  <?php if(isset($tableau_date_phase_dano[$id])) {$date_retour=$tableau_date_phase_dano[$id];  $Nombres_jours = NbJours($date_retour, date("Y-m-d"));
// Affiche 2
if($Nombres_jours>5) {echo  "<div style=\"width: 40%; background-color:#FF0000; color:#FFFFFF;\">".number_format($Nombres_jours, 0, ',', ' ')."</div>";}else echo number_format($Nombres_jours, 0, ',', ' ');} ?>
</div></td>
<td class=" "><?php echo (isset($tableau_obs[$row_liste_conv['numero']]))?$tableau_obs[$row_liste_conv['numero']]:"<div align='center'>Aucun suivi</div>"; ?></td>
</tr>
<?php } ?>
<?php }while($row_liste_conv  = mysql_fetch_assoc($liste_conv)); } else { ?>
<tr>
<td colspan="7"><h2 align="center">Aucune donn&eacute;e !</h2></td>
</tr>

<?php } ?>
</tbody></table>

</div>
<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>   <?php if(!isset($_GET["down"])) include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>

</body>
</html>