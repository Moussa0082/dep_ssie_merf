<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & Développement: SEYA SERVICES */
///////////////////////////////////////////////
session_start();
include_once 'system/configuration.php';
$config = new Config;

if (!isset ($_SESSION["clp_id"])) {
  //header(sprintf("Location: %s", "./"));
  exit;
}
include_once $config->sys_folder . "/database/db_connexion.php";

//fonction calcul nb jour
function NbJours($debut, $fin) {

  $tDeb = explode("-", $debut);
  $tFin = explode("-", $fin);

  $diff = mktime(0, 0, 0, $tFin[1], $tFin[2], $tFin[0]) -
          mktime(0, 0, 0, $tDeb[1], $tDeb[2], $tDeb[0]);

  return(($diff / 86400)+1);

}

function frenchMonthName($monthnum) {
        $armois=array("", "Jan", "F&eacute;v", "Mars", "Avril", "Mai", "Juin", "Juil", "Aout", "Sept", "Oct", "Nov", "D&eacute;c");
        if ($monthnum>0 && $monthnum<13) {
            return $armois[$monthnum];
        } else {
            return $monthnum;
        }
    }

 if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="x"){
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Tableau_Passation_Marche_PASCII.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Tableau_Passation_Marche_PASCII.doc"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="p"){

require_once('./tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$PDF_HEADER_TITLE = "Plan de Passation de Marchés du PASC II";
$PDF_HEADER_STRING = "Tableau PPM du PASC II";

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ruche');
$pdf->SetTitle($PDF_HEADER_TITLE);
$pdf->SetSubject($PDF_HEADER_STRING);
$pdf->SetKeywords('PDF, PPM, PASC II');

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
  if(isset($_GET['periode'])) {$periode=$_GET['periode'];} else $periode=0;
  include("print_tableau_passation_pdf.php");
  $content = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering

$html = utf8_encode($content);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('PPM.pdf', 'D');
/*
include("pdf/mpdf.php");
$mpdf=new mPDF('win-1252','A4-L','','',15,10,16,10,10,10);//A4 page in portrait for landscape add -L.
$mpdf->useOnlyCoreFonts = true;    // false is default
$mpdf->SetDisplayMode('fullpage');
ob_start();
include "print_etat_recommandation_mission_pdf.php";
$html = ob_get_contents();
ob_end_clean();
$mpdf->WriteHTML($html);
$mpdf->Output();
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Recommandation_mission.pdf"); */
exit;

 }

if(isset($_GET['periode'])) {$periode=$_GET['periode'];} else $periode=0;
if(isset($_GET['cat'])) {$cat=$_GET['cat'];} else $cat="*";

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_last_periode = "SELECT max(id_periode) as periode FROM periode_marche";
$last_periode = mysql_query($query_last_periode, $pdar_connexion) or die(mysql_error());
$row_last_periode = mysql_fetch_assoc($last_periode);
$totalRows_last_periode = mysql_num_rows($last_periode);

if(isset($row_last_periode['periode']) && $row_last_periode['periode']>0 && !isset($_GET['periode'])) $periode=$row_last_periode['periode'];


mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_categorie = "SELECT * FROM categorie_marche ORDER BY nom_categorie asc";
$liste_categorie  = mysql_query($query_liste_categorie , $pdar_connexion) or die(mysql_error());
$row_liste_categorie  = mysql_fetch_assoc($liste_categorie);
$totalRows_liste_categorie  = mysql_num_rows($liste_categorie);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_methode = "SELECT * FROM methode_marche";
$liste_methode  = mysql_query($query_liste_methode , $pdar_connexion) or die(mysql_error());
$row_liste_methode  = mysql_fetch_assoc($liste_methode);
$totalRows_liste_methode  = mysql_num_rows($liste_methode);
$methode_array = array();
if($totalRows_liste_methode>0){ do{ $methode_array[$row_liste_methode["id_methode"]]=$row_liste_methode["sigle"]; }while($row_liste_methode  = mysql_fetch_assoc($liste_methode));  }

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_bailleur = "SELECT * FROM partenaire ORDER BY sigle asc";
$liste_bailleur  = mysql_query($query_liste_bailleur , $pdar_connexion) or die(mysql_error());
$row_liste_bailleur  = mysql_fetch_assoc($liste_bailleur);
$totalRows_liste_bailleur  = mysql_num_rows($liste_bailleur);

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
 <script>$(document).ready(function(){App.init();Plugins.init();FormComponents.init();$("#container").addClass("sidebar-closed");});</script>
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
} .table tbody tr td {vertical-align: middle; } .marquer{background: #EBEBEB!important; }
</style>
<script type="text/JavaScript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>

<?php if(!isset($_GET["down"])){  ?>
<div class="well well-sm r_float"><div class="r_float"><a href="./s_programmation.php" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format PDF" href="<?php echo $editFormAction."&down=1&t=p"; ?>" class="button"><img src="./images/pdf.png" width='20' height='20' alt='Modifier' /></a></div></div>
<div class="clear h0">&nbsp;</div>
<?php }  ?>

<table width="100%" border="0" align="center" cellspacing="0">

  <?php if($totalRows_liste_categorie>0){

do{
    $cat=$_GET['cat']=$row_liste_categorie["code_categorie"];

	mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$query_la_categorie = "SELECT * FROM categorie_marche WHERE code_categorie='$cat' ORDER BY nom_categorie asc ";
	$la_categorie  = mysql_query($query_la_categorie , $pdar_connexion) or die(mysql_error());
	$row_la_categorie  = mysql_fetch_assoc($la_categorie);
	$totalRows_la_categorie  = mysql_num_rows($la_categorie);

	mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$result=mysql_query("SELECT distinct id_etape AS DP, intitule FROM etape_marche where categorie='$cat' ORDER BY ordre asc") or die (mysql_error());
	$etape=array();
	$titreEtape=array();
	while($ligne=mysql_fetch_assoc($result)){$etape[]=$ligne['DP']; $titreEtape[$ligne['DP']]=$ligne['intitule'];}
	mysql_free_result($result);

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$sql="SELECT * FROM periode_marche INNER JOIN plan_marche ON periode_marche.id_periode = plan_marche.periode inner join etape_plan_marche on etape_plan_marche.marche=plan_marche.id_marche where id_periode='$periode' and plan_marche.categorie='$cat' ORDER BY id_marche DESC";
	$liste_nmp=mysql_query($sql, $pdar_connexion) or die(mysql_error());
	$row_liste_nmp  = mysql_fetch_assoc($liste_nmp);
	$totalRows_liste_nmp  = mysql_num_rows($liste_nmp);

    mysql_select_db($database_pdar_connexion, $pdar_connexion);
	$sql="SELECT count( id_etape ) AS nb,libelle_groupe FROM etape_marche,groupe_etape where groupe=id_groupe and categorie='$cat' GROUP BY groupe ORDER BY ordre asc ";
	$liste_gp=mysql_query($sql, $pdar_connexion) or die(mysql_error());
	$row_liste_gp  = mysql_fetch_assoc($liste_gp);
	$totalRows_liste_gp  = mysql_num_rows($liste_gp);
    $etapeGp=array();
    $titreGp=""; $i=0;
	do{  $i+=$row_liste_gp['nb'];
    $titreGp.='<td align="center" valign="middle" colspan="'.($row_liste_gp['nb']*2).'"><span class="Style5"><strong>'.$row_liste_gp['libelle_groupe'].'</strong></span></td>';
    /*if($j!=$row_liste_gp['groupe']) { if($i==1) $i=2; else $i=1; $j=$row_liste_gp['groupe']; } else $i++; $etapeGp[$row_liste_gp['groupe']]=$i; $titreGp[$row_liste_gp['groupe']]=$row_liste_gp['libelle_groupe'];*/ }while($row_liste_gp=mysql_fetch_assoc($liste_gp));

if($i<count($titreEtape))
$titreGp.='<td align="center" valign="middle" colspan="'.((count($titreEtape)-$i+5)*2).'"><span class="Style5"><strong>&nbsp;</strong></span></td>';
else
$titreGp.='<td align="center" valign="middle" colspan="'.(5).'"><span class="Style5"><strong>&nbsp;</strong></span></td>';

echo "<tr><td bgcolor='#CCCCCC'><h3 style='padding:0px; margin:0px;' align='center'>".$row_liste_categorie["nom_categorie"]."</h3></td></tr>";
			?>
  <tr>
    <td valign="top"><?php if($totalRows_liste_nmp>0) {?>
        <table width="100%" border="1" align="center" cellspacing="3" class="table table-striped table-bordered table-hover table-responsive">
          <thead>
          <tr class="titrecorps2">
            <th rowspan="2"><div align="left" class="Style5">
                <div align="center" style="color:#339900"><strong>&nbsp;&nbsp; <u><?php echo $row_la_categorie['nom_categorie'];?></u></strong></div>
            </div></th>
            <th rowspan="2"><span class="Style5"><strong>Nb <br />Lot</strong></span></th>
            <th rowspan="2"><span class="Style5"><strong>M&eacute;thode</strong></span></th>
            <th rowspan="2">Pr&eacute;vu<br />R&eacute;alis&eacute;</th>
            <th rowspan="2"><span class="Style5"><strong>Montant</strong></span><span class="Style34"> (Ouguiya)</span></th>
            <th rowspan="2"><span class="Style5"><strong>Montant</strong></span><span class="Style34"> (US)</span></th>
            <!--<th rowspan="2"><span class="Style5"><strong>Examen DNCMP </strong></span><span class="Style34"> (Oui/Non)</span></th>
            <th rowspan="2"><span class="Style5"><strong>Examen CCMP</strong></span><span class="Style34"> (Oui/Non)</span></th>
            <th rowspan="2"><span class="Style5"><strong>Examen pr&eacute;alable par la Banque mondiale</strong></span><span class="Style34"> (Oui/Non)</span></th>-->
            <?php echo $titreGp; $nbcol=0; /*$nbcol=10; $nee=0; $num=0; $l=1; foreach($titreEtape as $te){ $num++; if(isset($etapeGp[$l]) && $num==$etapeGp[$l]){ $col=$num*2; ?>
            <!--<th align="center" valign="middle" colspan="<?php echo ($l==count($etapeGp))?$col+5:$col; ?>"><span class="Style5"><strong><?php echo $titreGp[$l]; ?></strong></span></th>-->
            <?php $l++;  $num=0; } $nbcol++; }*/?>



          </tr>
          <tr class="titrecorps2">
            <?php $nee=0; foreach($titreEtape as $te){ $nbcol++;?>
            <th align="center" valign="middle" colspan="2"><span class="Style5"><strong><?php echo $te; $nee=$nee+1; ?></strong></span></th>
            <?php }?>
            <th><span class="Style5"><strong>Dur&eacute;e (J) </strong></span></th>
            <th><span class="Style5"><strong>Dur&eacute;e (M) </strong></span></th>
            <th><span class="Style5"><strong>Date de fin d'ex&eacute;cution </strong></span></th>
            <th><span class="Style5"><strong>Date de d&eacute;caissement </strong></span></th>
            <th><span class="Style5"><strong>Source de financement </strong></span></th>
          </tr>
          </thead>
          <?php $j=0; $p1="j"; $nombreJ = array(); do { ?>

          <tr bgcolor="#FFFFFF">
            <td rowspan="2" valign="middle"><?php echo $row_liste_nmp['intitule']; $mr=$row_liste_nmp['id_marche']; $methode=(isset($methode_array[$row_liste_nmp['methode']]))?$methode_array[$row_liste_nmp['methode']]:""; ?></td>
            <td rowspan="2"><span class="Style5"><?php echo $row_liste_nmp['lot']; ?></span></td>
            <td rowspan="2" align="center"><span class="Style5"><?php echo $methode; ?></span></td>
            <td height="50%" bgcolor="#506429"><strong><span class="Style10 Style31 Style32"><em style="color: white; background-color: #506429;">Pr&eacute;vu</em></span></strong></td>
            <td nowrap="nowrap"><div align="center"><span class="Style5"><?php echo number_format($row_liste_nmp['montant_local'], 0, ',', ' '); ?></span></div></td>
            <td nowrap="nowrap"><div align="center"><span class="Style5"><?php echo number_format($row_liste_nmp['montant_usd'], 0, ',', ' '); ?></span></div></td>
            <!--<td rowspan="2" align="center"><span class="Style5"><?php echo $row_liste_nmp['examen_dncmp']; ?></span></td>
            <td rowspan="2" align="center"><span class="Style5"><?php echo $row_liste_nmp['examen_ccmp']; ?></span></td>
            <td rowspan="2" align="center"><span class="Style5"><?php echo $row_liste_nmp['examen_banque']; ?></span></td>-->
            <?php $num = 1;       $methode=$row_liste_nmp['methode'];
            foreach($etape as $item){
			//plan planification
    		 mysql_select_db($database_pdar_connexion, $pdar_connexion);
            if($num!=1)
			$query_liste_date = "SELECT * FROM etape_marche inner join methode_etape on id_etape=etapei where categorie='$cat' and id_etape=$item and methodei=$methode ORDER BY ordre asc";
            else
            $query_liste_date = "SELECT * FROM etape_plan_marche where etape='$item' and marche='$mr'";
			$liste_date  = mysql_query($query_liste_date , $pdar_connexion) or die(mysql_error());
			$row_liste_date  = mysql_fetch_assoc($liste_date);
			$totalRows_liste_date  = mysql_num_rows($liste_date);
            //if($row_liste_nmp['methode']=="CF") echo $query_liste_date."<br />";

			//min et max de la planification
			 mysql_select_db($database_pdar_connexion, $pdar_connexion);
			$query_m_date = "SELECT min(date_prevue) as min, max(date_prevue) as max FROM etape_plan_marche where marche='$mr'";
			$m_date  = mysql_query($query_m_date , $pdar_connexion) or die(mysql_error());
			$row_m_date  = mysql_fetch_assoc($m_date);
			$totalRows_m_date  = mysql_num_rows($m_date);
            if(!isset($row_liste_date['duree']) && isset($row_liste_date['duree_etape'])) $row_liste_date['duree']=$row_liste_date['duree_etape'];
			?>
            <td align="center" valign="middle"><div align="center" class="Style5">
            <?php if(isset($row_liste_date['date_prevue'])){ echo date("d/m/y", strtotime($row_liste_date['date_prevue'])); $date_debut = $row_liste_date['date_prevue']; if($num==1) $date_deb = $row_liste_date['date_prevue']; }
            elseif(isset($row_liste_date['duree'])) { $date_next = strtotime($date_debut." +".$row_liste_date['duree']."days"); $date_debut = date("Y-m-d",$date_next); echo date("d/m/y",$date_next); $date_fin = date("Y-m-d",$date_next);  }
            else echo "N/A"; ?>
            </div></td>
			<!--<td>&nbsp;</td> -->
            <?php $num++; }?>
            <td style="color:#000"><div align="center"><strong><span>
                <?php if(isset($date_deb) && isset($date_fin)) {
                  $Nombres_jours = NbJours($date_deb, $date_fin); $nombreJ[$row_liste_nmp['id_marche']]=$Nombres_jours-1;
                  echo number_format($Nombres_jours-1, 0, ',', ' ');} ?>
            </span></strong></div></td>
            <td style="color:#000"><div align="center"><strong><span>
                <?php if(isset($date_deb) && isset($date_fin)) {
                  $Nombres_jours = NbJours($date_deb, $date_fin);
                  echo number_format(($Nombres_jours-1)/30, 0, ',', ' ');} ?>
            </span></strong></div></td>
            <td style="color:#000"><div align="center"><strong><span>
                <?php if(isset($date_fin)) { $nbj = $Nombres_jours-1; echo date("d/m/Y",(strtotime($date_fin." +".$nbj."days"))); $fin_execution = date("Y-m-d",(strtotime($date_fin." +".$nbj."days")));  } ?>
            </span></strong></div></td>
            <td style="color:#000"><div align="center"><strong><span>
                <?php if(isset($fin_execution)) { echo frenchMonthName(date("n",(strtotime($fin_execution." +45days")))).date("-Y",(strtotime($fin_execution." +45days")));  } ?>
            </span></strong></div></td>
            <td rowspan="2" align="center"><span class="Style5"><?php if(isset($row_liste_nmp['partenaire'])) $as = explode(",", $row_liste_nmp['partenaire']); else $as=array();
            if($totalRows_liste_bailleur>0) { $bailleur = "";
			   do {  if(in_array($row_liste_bailleur['id_partenaire'], $as, TRUE)) { $bailleur .= $row_liste_bailleur['sigle'].",";}
			   }while ($row_liste_bailleur = mysql_fetch_assoc($liste_bailleur)); echo substr($bailleur,0,strlen($bailleur)-1);
      $rows = mysql_num_rows($liste_bailleur);
      if($rows > 0) {
      mysql_data_seek($liste_bailleur, 0);
	  $row_liste_bailleur = mysql_fetch_assoc($liste_bailleur);
  }  } ?></span></td>
          </tr>
          <tr>
            <td height="50%" bgcolor="#FF99FF" ><strong><span class="Style10 Style31"><em>R&eacute;alis&eacute;</em></span></strong></td>
            <?php
	  //Montant reel
			 mysql_select_db($database_pdar_connexion, $pdar_connexion);
			$query_montant_reel = "SELECT * FROM suivi_montant_marche where marche='$mr'";
			$montant_reel  = mysql_query($query_montant_reel , $pdar_connexion) or die(mysql_error());
			$row_montant_reel  = mysql_fetch_assoc($montant_reel);
			$totalRows_montant_reel  = mysql_num_rows($montant_reel);
	  ?>
            <td><div align="center"><strong> <span <?php if(isset($row_montant_reel['montant_local']) && $row_liste_nmp['montant_local']<$row_montant_reel['montant_local']) { echo "style=\"color:#FF0000\"";} else {echo "style=\"color:#339900\"";}?> >
                <?php if(isset($row_montant_reel['montant_local']) && $row_montant_reel['montant_local']>0) echo number_format($row_montant_reel['montant_local'], 0, ',', ' '); else echo "-"; ?>
            </span></strong></div></td>
            <td><div align="center"><strong>
                <?php if(isset($row_montant_reel['montant_usd']) && $row_montant_reel['montant_usd']>0) echo number_format($row_montant_reel['montant_usd'], 0, ',', ' '); else echo "-"; ?>
            </strong></div></td>
            <?php $num = 1; $cum = 0;
            foreach($etape as $item){
            $where = "etape='$item'";
			//plan suivi
			 mysql_select_db($database_pdar_connexion, $pdar_connexion);
			$query_liste_sdate = "SELECT * FROM suivi_plan_marche where $where and marche='$mr'";
			$liste_sdate  = mysql_query($query_liste_sdate , $pdar_connexion) or die(mysql_error());
			$row_liste_sdate  = mysql_fetch_assoc($liste_sdate);
			$totalRows_liste_sdate  = mysql_num_rows($liste_sdate);  //if($row_liste_nmp['methode']) echo $item." - ".$query_liste_sdate;

			//min et max du suvi
    		 mysql_select_db($database_pdar_connexion, $pdar_connexion);
			$query_m_dates = "SELECT min(date_reelle) as min, max(date_reelle) as max FROM suivi_plan_marche where marche='$mr'";
			$m_dates  = mysql_query($query_m_dates , $pdar_connexion) or die(mysql_error());
			$row_m_dates  = mysql_fetch_assoc($m_dates);
			$totalRows_m_dates  = mysql_num_rows($m_dates);

			//plan planification
			 mysql_select_db($database_pdar_connexion, $pdar_connexion);
			$query_liste_date2 = "SELECT * FROM etape_plan_marche where $where and marche='$mr'";
			$liste_date2  = mysql_query($query_liste_date2 , $pdar_connexion) or die(mysql_error());
			$row_liste_date2  = mysql_fetch_assoc($liste_date2);
			$totalRows_liste_date2  = mysql_num_rows($liste_date2);
			?>
            <td align="center" bgcolor="#fff" valign="middle" <?php $dj=date("Y-m-d"); $color="#FFFFFF"; if(isset($row_liste_sdate['date_reelle']) && isset($row_liste_date2['date_prevue']) && $row_liste_sdate['date_reelle']<=$row_liste_date2['date_prevue']) { $color= "#00FF33";} elseif(isset($row_liste_sdate['date_reelle']) && isset($row_liste_date2['date_prevue']) && $row_liste_sdate['date_reelle']>$row_liste_date2['date_prevue']) { $color= "#FF0000";} elseif(!isset($row_liste_sdate['date_reelle']) && isset($row_liste_date2['date_prevue']) && $dj>=$row_liste_date2['date_prevue']) { $color= "#FF0000";} elseif(!isset($row_liste_date2['date_prevue'])) { $color= "";} ?>><div align="center" class="Style5">
              <span <?php if($color!="" && isset($row_liste_sdate['date_reelle'])) echo 'style="background-color:'.$color.'; border: dashed '.$color.' 1px;"'; ?>><?php if(isset($row_liste_sdate['date_reelle'])) echo date("d/m/y", strtotime($row_liste_sdate['date_reelle'])); else echo "-";
              if($num==1) { $last_date = $row_liste_sdate['date_reelle']; $date_deb = $row_liste_sdate['date_reelle']; }
              ?></span>
            </div></td>
			<td><?php if($num==1) echo "&nbsp;"; elseif(isset($row_liste_sdate['date_reelle'])){ $cum++; echo "<div align='center' style='background-color:yellow;'>"; echo ($num==2)?NbJours($last_date,$row_liste_sdate['date_reelle'])-1:NbJours($last_date,$row_liste_sdate['date_reelle']); $last_date = $row_liste_sdate['date_reelle']; echo "</div>"; }  ?></td>
            <?php $num++; if(isset($row_liste_sdate['date_reelle'])) $date_fin = $row_liste_sdate['date_reelle']; else unset($date_fin); }?>
            <td bgcolor="#fff"  <?php echo (isset($date_deb)  && isset($date_fin) && $nombreJ[$row_liste_nmp['id_marche']]<(NbJours($date_deb, $date_fin)-1))?'style="color:#FF0000"':'style="color:#00C427"'; ?>><div align="center">
              <b><?php
              if(isset($date_deb)  && isset($date_fin)) { $Nombres_jours = NbJours($date_deb, $date_fin);
              echo (($Nombres_jours-1)>0)?number_format($Nombres_jours-1, 0, ',', ' '):""; }
              ?></b>
            </div></td>
            <td bgcolor="#fff" ><div align="center"><b><?php if(isset($date_deb)  && isset($date_fin)) { echo (($Nombres_jours-1)>0)?number_format(($Nombres_jours-1)/30, 0, ',', ' '):""; } ?></b></div></td>
            <td bgcolor="#fff" ><div align="center"><b><?php if(isset($date_fin)) { $nbj = $Nombres_jours-1; echo date("d/m/Y",(strtotime($date_fin." +".$nbj."days"))); $fin_execution = date("Y-m-d",(strtotime($date_fin." +".$nbj."days")));  } ?></b></div></td>
            <td bgcolor="#fff" ><div align="center"><b><?php if(isset($fin_execution) && isset($date_fin)) { echo frenchMonthName(date("n",(strtotime($fin_execution." +45days")))).date("-Y",(strtotime($fin_execution." +45days")));  } ?> </b></div></td>
          </tr>
         <!--<tr bgcolor="#FFFFFF">
            <td colspan="<?php echo $nbcol+1;  ?>" align="center"><hr id="sp_hr" /></td>
          </tr>-->
          <?php unset($date_debut); } while ($row_liste_nmp = mysql_fetch_assoc($liste_nmp)); ?>
        </table>
      <?php } else echo "<h3 align='center'>Aucun march&eacute; !</h3>"; ?>
    </td>
  </tr>
  <?php   }while($row_liste_categorie  = mysql_fetch_assoc($liste_categorie));   }  ?>
</table>

<!-- Fin Site contenu ici -->
            </div>
        </div>

        </div>
    </div>   <?php if(!isset($_GET["down"])) include_once 'modal_add.php'; ?>
    <?php include_once("includes/footer.php"); ?>
</div>

</body>
</html>