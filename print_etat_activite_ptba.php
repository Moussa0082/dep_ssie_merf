<?php
///////////////////////////////////////////////
/*                 SSE                       */
/*	Conception & DÃ©veloppement: SEYA SERVICES */
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
header("content-disposition: attachment;filename=Situation_activites_ptba.xls"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="w"){
header("Content-Type: application/vnd.ms-word");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Situation_activites_ptba.doc"); }
else if(isset($_GET["down"]) && isset($_GET["t"]) && $_GET["t"]=="p"){

require_once('./tcpdf/tcpdf.php');

// create new PDF document
$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$PDF_HEADER_TITLE = "Etat des activités du PTBA";
$PDF_HEADER_STRING = "Situation des activités du PTBA";

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Ruche');
$pdf->SetTitle($PDF_HEADER_TITLE);
$pdf->SetSubject($PDF_HEADER_STRING);
$pdf->SetKeywords('PDF, mission, Situation des activités du PTBA');

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
  include("./print_etat_activite_ptba_pdf.php");
  $content = ob_get_contents(); // get the contents of the output buffer
  ob_end_clean(); //  clean (erase) the output buffer and turn off output buffering

$html = utf8_encode($content);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('Situation_activites_ptba.pdf', 'D');
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

 } ?>
<?php
$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Ao&ucirc;t","Septembre","Octobre","Novembre","Decembre");
$annee=(isset($_GET['annee']))?$_GET['annee']:date("Y");
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_edit_ms = "SELECT code,intitule FROM ".$database_connect_prefix."activite_projet WHERE niveau=1 and ".$_SESSION["clp_where"]." ORDER BY code asc";
$edit_ms = mysql_query($query_edit_ms, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_edit_ms = mysql_fetch_assoc($edit_ms);
$totalRows_edit_ms = mysql_num_rows($edit_ms);

mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_act = "SELECT * FROM ".$database_connect_prefix."ptba where annee='$annee' and projet='".$_SESSION["clp_projet"]."' ";
$query_act .= " order by code_activite_ptba asc";
$act = mysql_query($query_act, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_act = mysql_fetch_assoc($act);
$totalRows_act = mysql_num_rows($act);

//Calcul
$statut_act = array();
if(isset($totalRows_act) && $totalRows_act>0) { $i=0; do { $id_act=$row_act['id_ptba']; $code_act = $row_act['code_activite_ptba'];
//suivi tache
/*mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_suivi_tache = "SELECT ".$database_connect_prefix."groupe_tache.proportion as valeur_suivi, ".$database_connect_prefix."suivi_tache.id_activite
FROM ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."suivi_tache where ".$database_connect_prefix."suivi_tache.id_activite='$code_act' and ".$database_connect_prefix."groupe_tache.projet='".$_SESSION["clp_projet"]."' and ".$database_connect_prefix."suivi_tache.observation is not null GROUP BY ".$database_connect_prefix."suivi_tache.id_activite";*/

$query_suivi_tache = "SELECT ROUND(SUM(s.proportion)) as valeur_suivi, id_activite FROM ".$database_connect_prefix."groupe_tache, ".$database_connect_prefix."suivi_tache s WHERE id_groupe_tache=id_tache  and s.valider=1 and ".$database_connect_prefix."groupe_tache.id_activite='$id_act' GROUP BY id_activite";

$suivi_tache  = mysql_query($query_suivi_tache , $pdar_connexion) or die(mysql_error());
$row_suivi_tache  = mysql_fetch_assoc($suivi_tache);
$totalRows_suivi_tache  = mysql_num_rows($suivi_tache);
$taux_tache = $taux_progress = 0;
$ttt=0; $maxt=0; $idmaxt=0; if($totalRows_suivi_tache>0) { do {
$taux_tache+=$row_suivi_tache["valeur_suivi"];
} while ($row_suivi_tache = mysql_fetch_assoc($suivi_tache));  }
if(isset($taux_tache) && $taux_tache>0 && $totalRows_suivi_tache>0) {
$ttt=$taux_tache; $taux_progress = $ttt; $stat = $ttt; }

if($totalRows_suivi_tache>=0) { $taux=$ttt;
if (isset($taux_progress) && $taux_progress>0 && $taux_progress<=100) $percent = $taux_progress;
elseif (isset($taux_progress) && $taux_progress>100) $percent = 100;
else $percent = 0;

unset($taux_progress);

if(isset($stat)){ if($stat==0 && $annee==date("Y")) $statut_act[$code_act]="Non entam&eacute;e"; elseif($stat>0 && $stat<100) $statut_act[$code_act]="En cours"; elseif($stat>=100) $statut_act[$code_act]="Ex&eacute;cut&eacute;e"; else $statut_act[$code_act]="Non ex&eacute;cut&eacute;e"; } else $statut_act[$code_act]="Non entam&eacute;e";
unset($stat);
$i++; } } while ($row_act = mysql_fetch_assoc($act)); }
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
} .table tbody tr td {vertical-align: middle; } .marquer{background: #EBEBEB!important; }
</style>
<div class="contenu">
<?php if(!isset($_GET["down"])){  ?>
<div class="well well-sm r_float"><div class="r_float"><a href="./s_programmation.php" class="button">Retour</a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Word" href="<?php echo $editFormAction."&down=1&t=w"; ?>" class="button"><img src="./images/doc.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format Excel" href="<?php echo $editFormAction."&down=1&t=x"; ?>" class="button"><img src="./images/xls.png" width='20' height='20' alt='Modifier' /></a></div>
<div class="r_float" style="margin-right: 20px;"><a title="Exporter au format PDF" href="<?php echo $editFormAction."&down=1&t=p"; ?>" class="button"><img src="./images/pdf.png" width='20' height='20' alt='Modifier' /></a></div></div>
<div class="clear h0">&nbsp;</div>
<?php } else { ?>

<center><?php include "./includes/print_header.php"; ?></center>

<?php } ?>
<div class="well well-sm"><strong>SITUATION DES ACTIONS DU PTBA <?php echo "$annee"; ?> AU <?php echo date("t")." ".strtoupper($mois[date("n")]); ?></strong></div>

<table width="<?php echo (!isset($_GET["down"]))?'':'100%'; ?>" border="<?php echo (!isset($_GET["down"]))?0:1; ?>" cellspacing="0" class="table table-striped table-bordered table-hover table-responsive">
            <thead>
            <tr>
              <th rowspan="2" align="center">ACTIVITES</th>
              <th colspan="7">STATUTS</th>
              <th rowspan="2">OBSERVATIONS (raisons du retard, du report ou de l'annulation)</th>
            </tr>
            <tr>
              <th>Activit&eacute;s compl&egrave;tement ex&eacute;cut&eacute;es </th>
              <th>Activit&eacute;s en cours  ex&eacute;cution mais qui s'ach&egrave;vera au <?php echo "31/12/$annee"; ?> </th>
              <th>Activit&eacute;s en cours  ex&eacute;cution mais qui s'ach&egrave;vera en <?php echo $annee+1; ?></th>
              <th>Activit&eacute;s non encore  ex&eacute;cut&eacute;es mais qui s'ach&egrave;vera au <?php echo "31/12/$annee"; ?> </th>
              <th>Activit&eacute;s non encore initi&eacute;es  et donc report&eacute;e &agrave; <?php echo $annee+1; ?> </th>
              <th>Activit&eacute;s non encore initi&eacute;es et pr&eacute;vue pour &ecirc;tre annul&eacute;es </th>
              <th>Autres activit&eacute;s &agrave; statut incertain ou probl&eacute;matiques</th>
            </tr>
            </thead>
<?php  if($totalRows_edit_ms>0) { do {
$code = $row_edit_ms['code'];
mysql_select_db($database_pdar_connexion, $pdar_connexion);
$query_liste_rec = "SELECT * FROM ".$database_connect_prefix."ptba where projet='".$_SESSION["clp_projet"]."' and annee='$annee' and code_activite_ptba like '$code%' ORDER By code_activite_ptba asc";
$liste_rec = mysql_query($query_liste_rec, $pdar_connexion) or die(mysql_error_show_message(mysql_error()));
$row_liste_rec = mysql_fetch_assoc($liste_rec);
$totalRows_liste_rec = mysql_num_rows($liste_rec);
if($totalRows_liste_rec>0) {$i=0; $t=0; $p2=$p1="j"; ?>
            <tr bgcolor="#BED694">
              <td colspan="9" align="center" style="background-color: #BED694;">
                <b><?php echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?></b>
              </td>
            </tr>
<?php do { $code_act = $row_liste_rec['code_activite_ptba']; ?>
            <tr>
<td><?php echo "<b>".$row_liste_rec['code_activite_ptba'].'</b> : '.$row_liste_rec['intitule_activite_ptba']; ?></td>
<td width="10%" class="<?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="Ex&eacute;cut&eacute;e")?"marquer":""; ?>" valign="middle" align="center"><?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="Ex&eacute;cut&eacute;e")?"<b>x</b>":""; ?></td>
<td width="10%" class="<?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="En cours" && !strrchr($row_liste_rec['statut'],"Reporté"))?"marquer":""; ?>" valign="middle" align="center"><?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="En cours" && !strrchr($row_liste_rec['statut'],"Reporté"))?"<b>x</b>":""; ?></td>
<td width="10%" class="<?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="En cours" && strrchr($row_liste_rec['statut'],"Reporté"))?"marquer":""; ?>" valign="middle" align="center"><?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="En cours" && strrchr($row_liste_rec['statut'],"Reporté"))?"<b>x</b>":""; ?></td>
<td width="10%" class="<?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="Non ex&eacute;cut&eacute;e" && !strrchr($row_liste_rec['statut'],"Reporté"))?"marquer":""; ?>" valign="middle" align="center"><?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="Non ex&eacute;cut&eacute;e" && !strrchr($row_liste_rec['statut'],"Reporté"))?"<b>x</b>":""; ?></td>
<td width="10%" class="<?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="Non entam&eacute;e" && strrchr($row_liste_rec['statut'],"Reporté"))?"marquer":""; ?>" valign="middle" align="center"><?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="Non entam&eacute;e" && strrchr($row_liste_rec['statut'],"Reporté"))?"<b>x</b>":""; ?></td>
<td width="10%" class="<?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="Non entam&eacute;e" && strrchr($row_liste_rec['statut'],"Annulé"))?"marquer":""; ?>" valign="middle" align="center"><?php echo (isset($statut_act[$code_act]) && $statut_act[$code_act]=="Non entam&eacute;e" && strrchr($row_liste_rec['statut'],"Annulé"))?"<b>x</b>":""; ?></td>
<td width="10%" class="<?php echo ($row_liste_rec['statut']=="Incertain")?"marquer":""; ?>" valign="middle" align="center"><?php echo ($row_liste_rec['statut']=="Incertain")?"<b>x</b>":""; ?></td>
<td><?php echo $row_liste_rec['observation']; ?></td>
            </tr>
            <?php } while ($row_liste_rec= mysql_fetch_assoc($liste_rec)); ?>
            <?php } else { ?>
<!--            <tr>
              <td colspan="9"><div align="center"><span class="Style4"><em><strong>Aucune activit&eacute; enregistr&eacute;e dans la composante <?php //echo "<b>".$row_edit_ms['code'].'</b> : '.$row_edit_ms['intitule']; ?> ! </strong></em></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span><span class="Style4"></span></div></td>
            </tr>-->
            <?php }  ?>
      <?php } while ($row_edit_ms = mysql_fetch_assoc($edit_ms)); } else { ?>
      <tr>
        <td colspan="9" align="center"><strong><em>Aucune composante trouv&eacute;e!</em></strong></td>
      </tr>
      <?php } ?>
</table>

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